/**
 * Elvare Group - Merge Injectables into Peptides
 *
 * Moves all products from "Injectables" category to "Peptides" category,
 * removes the Injectables category, and updates the homepage grid.
 * Run this ONCE via Code Snippets (set to "Run Once").
 */

if (!function_exists('wp_insert_term')) return;

$injectables = get_term_by('slug', 'injectables', 'product_cat');
$peptides = get_term_by('slug', 'peptides', 'product_cat');

if (!$injectables || !$peptides) {
    error_log('Elvare Merge: Could not find Injectables or Peptides category');
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Merge failed: Could not find Injectables or Peptides category.</p></div>';
    });
    return;
}

$moved = 0;

// Get all products in Injectables
$products = get_posts(array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $injectables->term_id
        )
    )
));

foreach ($products as $product_id) {
    $terms = wp_get_object_terms($product_id, 'product_cat', array('fields' => 'ids'));

    // Remove Injectables, add Peptides
    $new_terms = array_diff($terms, array($injectables->term_id));
    if (!in_array($peptides->term_id, $new_terms)) {
        $new_terms[] = $peptides->term_id;
    }

    wp_set_object_terms($product_id, array_values($new_terms), 'product_cat');
    $moved++;
}

// Delete the Injectables category
wp_delete_term($injectables->term_id, 'product_cat');

error_log("Elvare Merge: Moved {$moved} products from Injectables to Peptides. Injectables category deleted.");

add_action('admin_notices', function() use ($moved) {
    echo '<div class="notice notice-success"><p>Merge complete: Moved ' . $moved . ' products from Injectables to Peptides. Injectables category has been removed.</p></div>';
});
