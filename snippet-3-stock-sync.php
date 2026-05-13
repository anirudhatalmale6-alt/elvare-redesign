/**
 * Elvare Group - PsychoSkull Stock Sync
 *
 * Automatically syncs stock status from psychoskull.co.za every 6 hours.
 * Only shows "In Stock" or "Out of Stock" (no quantities).
 * Add as Code Snippet - "Run everywhere" or "Run on admin only".
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
add_action('elvare_stock_sync_event', 'elvare_sync_stock_from_psychoskull');

function elvare_sync_stock_from_psychoskull() {
    $updated = 0;
    $errors = 0;
    $page = 1;
    $ps_products = array();

    // Fetch all PsychoSkull products
    while (true) {
        $url = "https://www.psychoskull.co.za/wp-json/wc/store/v1/products?per_page=100&page={$page}";
        $response = wp_remote_get($url, array('timeout' => 30));

        if (is_wp_error($response)) {
            error_log("Elvare Stock Sync: API error on page {$page}: " . $response->get_error_message());
            break;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data) || !is_array($data)) {
            break;
        }

        foreach ($data as $product) {
            $ps_products[] = array(
                'name' => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
                'sku' => isset($product['sku']) ? $product['sku'] : '',
                'in_stock' => !empty($product['is_in_stock']),
                'slug' => isset($product['slug']) ? $product['slug'] : ''
            );
        }

        $page++;
        if ($page > 10) break;
    }

    if (empty($ps_products)) {
        error_log("Elvare Stock Sync: No products fetched from PsychoSkull");
        return;
    }

    // Get all Elvare products
    $elvare_products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    foreach ($elvare_products as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product) continue;

        $elvare_name = strtolower(html_entity_decode($product->get_name(), ENT_QUOTES, 'UTF-8'));
        $elvare_sku = $product->get_sku();

        // Try to find matching PsychoSkull product
        $match = elvare_find_ps_match($elvare_name, $elvare_sku, $ps_products);

        if ($match !== false) {
            $current_status = $product->get_stock_status();
            $new_status = $match['in_stock'] ? 'instock' : 'outofstock';

            if ($current_status !== $new_status) {
                $product->set_stock_status($new_status);
                $product->set_manage_stock(false);
                $product->save();
                $updated++;

                $status_text = $match['in_stock'] ? 'IN STOCK' : 'OUT OF STOCK';
                error_log("Elvare Stock Sync: Updated '{$product->get_name()}' -> {$status_text} (matched: {$match['name']})");
            }
        }
    }

    error_log("Elvare Stock Sync: Complete. Updated {$updated} products. PS catalog: " . count($ps_products) . " products.");
}

function elvare_find_ps_match($elvare_name, $elvare_sku, $ps_products) {
    // First try SKU match
    if (!empty($elvare_sku)) {
        foreach ($ps_products as $pp) {
            if (!empty($pp['sku']) && $pp['sku'] === $elvare_sku) {
                return $pp;
            }
        }
    }

    // Then try name matching
    $elvare_words = elvare_get_significant_words($elvare_name);

    $best_match = false;
    $best_score = 0;

    foreach ($ps_products as $pp) {
        $ps_name = strtolower($pp['name']);
        $ps_words = elvare_get_significant_words($ps_name);

        $common = array_intersect($elvare_words, $ps_words);
        $max_words = max(count($elvare_words), count($ps_words));

        if ($max_words > 0 && count($common) >= 3) {
            $score = count($common) / $max_words;
            if ($score > $best_score && $score >= 0.4) {
                $best_score = $score;
                $best_match = $pp;
            }
        }
    }

    return $best_match;
}

function elvare_get_significant_words($name) {
    $name = preg_replace('/[^a-z0-9\s]/', ' ', $name);
    $words = preg_split('/\s+/', trim($name));
    $stop_words = array('the', 'a', 'an', 'of', 'and', 'or', 'in', 'for', 'to', 'with', 'mg', 'g', 'kg', 'ml');
    return array_values(array_diff($words, $stop_words));
}

// Admin button to trigger manual sync
add_action('admin_notices', function() {
    if (isset($_GET['elvare_sync_now']) && current_user_can('manage_options')) {
        elvare_sync_stock_from_psychoskull();
        echo '<div class="notice notice-success"><p>Stock sync completed! Check the error log for details.</p></div>';
    }

    if (current_user_can('manage_options')) {
        $next = wp_next_scheduled('elvare_stock_sync_event');
        $next_text = $next ? date('Y-m-d H:i:s', $next + (2 * 3600)) : 'Not scheduled';
        echo '<div class="notice notice-info"><p>';
        echo 'Elvare Stock Sync: Next run at ' . $next_text . ' (SAST). ';
        echo '<a href="' . admin_url('?elvare_sync_now=1') . '">Run Now</a>';
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
