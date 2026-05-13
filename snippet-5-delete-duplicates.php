/**
 * Elvare Group - Delete Duplicate Products
 *
 * Set to "Run everywhere" and activate.
 * After you see the green success message in admin, DEACTIVATE this snippet.
 * Deletes 47 duplicate products that were imported twice.
 */

add_action('admin_init', function() {
    if (get_option('elvare_dedup_done')) return;

    $duplicate_ids = array(
        2797, 2791, 2787, 2784, 2778, 2775, 2766, 2754, 2748, 2716,
        2708, 2684, 2682, 2680, 2678, 2664, 2657, 2655, 2653, 2651,
        2649, 2639, 2637, 2625, 2619, 2607, 2599, 2595, 2582, 2575,
        2571, 2562, 2557, 2555, 2547, 2544, 2541, 2538, 2534, 2510,
        2498, 2461, 2459, 2455, 2449, 2447, 2445
    );

    $deleted = 0;
    $skipped = 0;

    foreach ($duplicate_ids as $pid) {
        $product = wc_get_product($pid);
        if ($product) {
            wp_delete_post($pid, true);
            $deleted++;
        } else {
            $skipped++;
        }
    }

    if (function_exists('wc_delete_product_transients')) {
        wc_delete_product_transients();
    }

    update_option('elvare_dedup_done', $deleted);
});

add_action('admin_notices', function() {
    $done = get_option('elvare_dedup_done');
    if ($done !== false && $done !== '') {
        echo '<div class="notice notice-success"><p><strong>Duplicate cleanup complete!</strong> Deleted ' . intval($done) . ' duplicate products. You can now deactivate this snippet.</p></div>';
    }
});
