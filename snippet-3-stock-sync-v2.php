/**
 * Elvare Group - PsychoSkull Stock Sync v2
 *
 * Automatically syncs stock status from psychoskull.co.za every 6 hours.
 * Only shows "In Stock" or "Out of Stock" (no quantities).
 * Brand-aware matching prevents cross-brand false positives.
 * Skips Peptides category (managed manually by admin).
 *
 * Add as Code Snippet - "Run everywhere".
 */

// Schedule the sync cron
if (!wp_next_scheduled('elvare_stock_sync_event')) {
    wp_schedule_event(time(), 'elvare_every_6h', 'elvare_stock_sync_event');
}

// Add custom 6-hour interval
add_filter('cron_schedules', function($schedules) {
    $schedules['elvare_every_6h'] = array(
        'interval' => 21600,
        'display' => 'Every 6 Hours'
    );
    return $schedules;
});

// The sync function
add_action('elvare_stock_sync_event', 'elvare_sync_stock_v2');

function elvare_sync_stock_v2() {
    $updated = 0;
    $matched = 0;
    $page = 1;
    $ps_products = array();

    while (true) {
        $url = "https://www.psychoskull.co.za/wp-json/wc/store/v1/products?per_page=100&page={$page}";
        $response = wp_remote_get($url, array('timeout' => 30));

        if (is_wp_error($response)) {
            error_log("Elvare Stock Sync: API error on page {$page}: " . $response->get_error_message());
            break;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data) || !is_array($data)) break;

        foreach ($data as $product) {
            $ps_products[] = array(
                'name' => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
                'sku' => isset($product['sku']) ? $product['sku'] : '',
                'in_stock' => !empty($product['is_in_stock'])
            );
        }

        $page++;
        if ($page > 10) break;
    }

    if (empty($ps_products)) {
        error_log("Elvare Stock Sync: No products fetched from PsychoSkull");
        return;
    }

    $peptides_term = get_term_by('slug', 'peptides', 'product_cat');
    $peptides_id = $peptides_term ? $peptides_term->term_id : 0;

    $elvare_products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    foreach ($elvare_products as $product_id) {
        // Skip peptides category
        if ($peptides_id) {
            $cats = wp_get_object_terms($product_id, 'product_cat', array('fields' => 'ids'));
            if (in_array($peptides_id, $cats)) continue;
        }

        $product = wc_get_product($product_id);
        if (!$product) continue;

        $elvare_name = html_entity_decode($product->get_name(), ENT_QUOTES, 'UTF-8');
        $elvare_sku = $product->get_sku();

        $match = elvare_find_ps_match_v2($elvare_name, $elvare_sku, $ps_products);

        if ($match !== false) {
            $matched++;
            $current_status = $product->get_stock_status();
            $new_status = $match['in_stock'] ? 'instock' : 'outofstock';

            if ($current_status !== $new_status) {
                $product->set_stock_status($new_status);
                $product->set_manage_stock(false);
                $product->save();
                $updated++;

                $status_text = $match['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK';
                error_log("Elvare Stock Sync: '{$product->get_name()}' -> {$status_text} (matched: {$match['name']})");
            }
        }
    }

    error_log("Elvare Stock Sync v2: Done. Matched {$matched}, updated {$updated}. PS catalog: " . count($ps_products));
}

function elvare_find_ps_match_v2($elvare_name, $elvare_sku, $ps_products) {
    // 1. SKU match (highest priority)
    if (!empty($elvare_sku)) {
        foreach ($ps_products as $pp) {
            if (!empty($pp['sku']) && $pp['sku'] === $elvare_sku) {
                return $pp;
            }
        }
    }

    // 2. Brand-aware name matching
    $e_brand = '';
    $e_prod = '';
    elvare_extract_brand($elvare_name, $e_brand, $e_prod);
    $e_words = elvare_get_core_words($e_prod);

    if (empty($e_words)) return false;

    $best_match = false;
    $best_score = 0;

    foreach ($ps_products as $pp) {
        $p_brand = '';
        $p_prod = '';
        elvare_extract_brand($pp['name'], $p_brand, $p_prod);
        $p_words = elvare_get_core_words($p_prod);

        if (empty($p_words)) continue;

        // If both have brands, they must match
        if (!empty($e_brand) && !empty($p_brand) && $e_brand !== $p_brand) {
            continue;
        }

        $common = array_intersect($e_words, $p_words);
        $max_words = max(count(array_unique($e_words)), count(array_unique($p_words)));
        $min_words = min(count(array_unique($e_words)), count(array_unique($p_words)));

        if (empty($common)) continue;

        $score = count($common) / $max_words;

        // Short names (1-2 unique words): need higher overlap
        if ($min_words <= 2) {
            if (count($common) >= $min_words && $score >= 0.5) {
                if ($score > $best_score) {
                    $best_score = $score;
                    $best_match = $pp;
                }
            }
        } else {
            // Longer names: standard threshold
            if (count($common) >= 2 && $score >= 0.4) {
                if ($score > $best_score) {
                    $best_score = $score;
                    $best_match = $pp;
                }
            }
        }
    }

    return $best_match;
}

function elvare_extract_brand($name, &$brand, &$product) {
    $name = strtolower(html_entity_decode($name, ENT_QUOTES, 'UTF-8'));
    $known_brands = array(
        'ssa', 'tnt', 'npl', 'nutritech', 'evolve', 'black bull', 'blackbull',
        'slender you', 'vitatech', 'ipharma', 'ipherma', 'hd labs', 'nova labs',
        'nova', 'atp', 'bpi', 'hn', 'biotech', 'biogen', 'barbarian', 'healthy u',
        'body pharm', 'keifei', 'vmed', 'u.p.a'
    );

    // Try splitting on dash separator
    $parts = preg_split('/\s*[\x{2013}\-]\s*/u', $name, 2);
    if (count($parts) == 2) {
        $candidate = trim($parts[0]);
        foreach ($known_brands as $b) {
            if ($candidate === $b || strpos($candidate, $b) === 0) {
                $brand = $b;
                $product = trim($parts[1]);
                return;
            }
        }
    }

    // Check if name starts with a brand
    foreach ($known_brands as $b) {
        if (strpos($name, $b . ' ') === 0) {
            $brand = $b;
            $product = trim(substr($name, strlen($b)));
            return;
        }
    }

    $brand = '';
    $product = $name;
}

function elvare_get_core_words($name) {
    $name = strtolower($name);
    // Remove serving counts and weight units
    $name = preg_replace('/\d+\s*servings?/i', '', $name);
    $name = preg_replace('/\b\d+\s*(g|kg|ml|l|mg|iu|mcg|caps?|tabs?|sachets?|vials?)\b/i', '', $name);
    // Remove punctuation
    $name = preg_replace('/[^a-z0-9\s]/', ' ', $name);
    $name = trim(preg_replace('/\s+/', ' ', $name));

    $words = preg_split('/\s+/', $name);
    $stop_words = array('the', 'a', 'an', 'of', 'and', 'or', 'in', 'for', 'to', 'with');

    $result = array();
    foreach ($words as $w) {
        if (!empty($w) && strlen($w) > 1 && !in_array($w, $stop_words)) {
            $result[] = $w;
        }
    }
    return array_values(array_unique($result));
}

// Admin button to trigger manual sync
add_action('admin_notices', function() {
    if (isset($_GET['elvare_sync_now']) && current_user_can('manage_options')) {
        check_admin_referer('elvare_sync_now');
        elvare_sync_stock_v2();
        echo '<div class="notice notice-success"><p>Stock sync completed! Check the error log for details.</p></div>';
    }

    if (current_user_can('manage_options')) {
        $next = wp_next_scheduled('elvare_stock_sync_event');
        $next_text = $next ? date('Y-m-d H:i:s', $next + (2 * 3600)) : 'Not scheduled';
        $url = wp_nonce_url(admin_url('?elvare_sync_now=1'), 'elvare_sync_now');
        echo '<div class="notice notice-info"><p>';
        echo 'Elvare Stock Sync: Next run at ' . esc_html($next_text) . ' (SAST). ';
        echo '<a href="' . esc_url($url) . '">Run Now</a>';
        echo '</p></div>';
    }
});

// Hide stock quantity on frontend - only show In Stock / Out of Stock
add_filter('woocommerce_get_stock_html', function($html, $product) {
    if ($product->is_in_stock()) {
        return '<p class="stock in-stock">In Stock</p>';
    } else {
        return '<p class="stock out-of-stock">Out of Stock</p>';
    }
}, 10, 2);

// Disable stock quantity display in cart
add_filter('woocommerce_stock_amount', function($amount) {
    return '';
});

// Hide stock quantity from product pages
add_filter('woocommerce_product_get_stock_quantity', function($quantity, $product) {
    return null;
}, 10, 2);
