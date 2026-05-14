/**
 * Elvare Group - Fix Variable Product Stock Status
 *
 * Recalculates parent stock status for all variable products
 * based on their variations. If any variation is in stock,
 * parent shows as in stock.
 *
 * Set to "Run everywhere" and activate.
 * After green success message, DEACTIVATE this snippet.
 */

add_action('admin_init', function() {
    if (get_option('elvare_fix_var_stock_done')) return;

    $fixed = 0;

    $variable_products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'variable'
            )
        )
    ));

    foreach ($variable_products as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_type('variable')) continue;

        $children = $product->get_children();
        $any_in_stock = false;

        foreach ($children as $child_id) {
            $variation = wc_get_product($child_id);
            if ($variation && $variation->is_in_stock()) {
                $any_in_stock = true;
                break;
            }
        }

        $new_status = $any_in_stock ? 'instock' : 'outofstock';
        $current_status = $product->get_stock_status();

        if ($current_status !== $new_status) {
            $product->set_stock_status($new_status);
            $product->set_manage_stock(false);
            $product->save();
            $fixed++;
        }

        WC_Product_Variable::sync($product_id);
    }

    wc_delete_product_transients();
    update_option('elvare_fix_var_stock_done', $fixed);
});

add_action('admin_notices', function() {
    $done = get_option('elvare_fix_var_stock_done');
    if ($done !== false && $done !== '') {
        echo '<div class="notice notice-success"><p><strong>Variable stock fix complete!</strong> Updated ' . intval($done) . ' variable products. You can now deactivate this snippet.</p></div>';
    }
});
