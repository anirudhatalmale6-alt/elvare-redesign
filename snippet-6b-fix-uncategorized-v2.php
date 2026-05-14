/**
 * Elvare Group - Fix Uncategorized Products (Round 2)
 *
 * Assigns type categories to 10 newly imported products.
 * Set to "Run everywhere" and activate.
 * After green success message, DEACTIVATE this snippet.
 */

add_action('admin_init', function() {
    if (get_option('elvare_fix_cats_v2_done')) return;

    $fixes = array(
        3005 => 'health-recovery',
        2989 => 'protein',
        2967 => 'performance',
        2941 => 'protein',
        2937 => 'protein',
        2935 => 'protein',
        2933 => 'fat-burners',
        2931 => 'fat-burners',
        2919 => 'pre-workout',
        2885 => 'peptides',
    );

    $fixed = 0;

    foreach ($fixes as $pid => $cat_slug) {
        $term = get_term_by('slug', $cat_slug, 'product_cat');
        if (!$term) continue;

        $existing = wp_get_object_terms($pid, 'product_cat', array('fields' => 'ids'));
        if (is_wp_error($existing)) continue;

        if (!in_array($term->term_id, $existing)) {
            $existing[] = (int)$term->term_id;
            wp_set_object_terms($pid, $existing, 'product_cat');
            $fixed++;
        }
    }

    update_option('elvare_fix_cats_v2_done', $fixed);
});

add_action('admin_notices', function() {
    $done = get_option('elvare_fix_cats_v2_done');
    if ($done !== false && $done !== '') {
        echo '<div class="notice notice-success"><p><strong>Category fix v2 complete!</strong> Updated ' . intval($done) . ' products. You can now deactivate this snippet.</p></div>';
    }
});
