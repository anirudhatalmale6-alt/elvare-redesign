/**
 * Elvare Group - Merge Injectables into Peptides (v2)
 *
 * Set to "Run everywhere" and activate.
 * After you see the green success message in admin, DEACTIVATE this snippet.
 */

add_action('admin_init', function() {
    if (get_option('elvare_merge_done')) return;

    $injectables = get_term_by('slug', 'injectables', 'product_cat');
    $peptides = get_term_by('slug', 'peptides', 'product_cat');

    if (!$peptides) return;
    if (!$injectables) {
        update_option('elvare_merge_done', 'no_injectables');
        return;
    }

    $moved = 0;
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $injectables->term_id
            )
        )
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $pid = get_the_ID();
            $terms = wp_get_object_terms($pid, 'product_cat', array('fields' => 'ids'));
            $new_terms = array();
            foreach ($terms as $t) {
                if ($t != $injectables->term_id) {
                    $new_terms[] = (int)$t;
                }
            }
            if (!in_array((int)$peptides->term_id, $new_terms)) {
                $new_terms[] = (int)$peptides->term_id;
            }
            wp_set_object_terms($pid, $new_terms, 'product_cat');
            $moved++;
        }
    }
    wp_reset_postdata();

    wp_delete_term($injectables->term_id, 'product_cat');
    update_option('elvare_merge_done', $moved);
});

add_action('admin_notices', function() {
    $done = get_option('elvare_merge_done');
    if ($done && $done !== '') {
        echo '<div class="notice notice-success"><p><strong>Merge complete!</strong> Moved ' . intval($done) . ' products from Injectables to Peptides. You can now deactivate this snippet.</p></div>';
    }
});
