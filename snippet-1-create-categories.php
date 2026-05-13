/**
 * Elvare Group - Create Product Type Categories & Auto-Assign Products
 *
 * Run this ONCE via Code Snippets (set to "Run Once" or activate then deactivate).
 * Creates 12 product-type categories and assigns existing products based on name matching.
 */

// Only run in admin context or via Code Snippets
if (!function_exists('wp_insert_term')) return;

$categories = array(
    'protein' => array(
        'name' => 'Protein',
        'description' => 'Premium whey, casein, vegan and mass gainer protein supplements',
        'keywords' => array('whey', 'protein', 'casein', 'mass', 'gainer', 'hulk', 'gorilla', 'anabolic muscle', 'get big', 'diet meal', 'diet pro', 'tri-plant', 'tri-whey', 'vegan shake', 'cream of rice')
    ),
    'pre-workout' => array(
        'name' => 'Pre Workout',
        'description' => 'High-performance pre-workout formulas for explosive energy and focus',
        'keywords' => array('pre-workout', 'pre workout', 'hellfire', 'nuke', 'napalm', 'anarchy', 'vaso pump', 'pre-ignite', 'n.o. charge', 'red rage', 'hollow-point', 'hydroblast', 'na-no surge', 'intense pump', 'load3d', 'amino pre')
    ),
    'fat-burners' => array(
        'name' => 'Fat Burners',
        'description' => 'Thermogenic fat burners and weight management supplements',
        'keywords' => array('burn', 'fat', 'thermo', 'shred', 'slim', 'cuts', 'cla', 'garcinia', 'carb blocker', 'craving', 'slimming', 'appetite', 'night time burn', 'shape & tone', 'shape &amp; tone', 'diuretic', 'green tea', 'eca ', 'ecya', 'bikini', 'water less', 'helios')
    ),
    'creatine' => array(
        'name' => 'Creatine',
        'description' => 'Creatine monohydrate, HCL and advanced creatine blends',
        'keywords' => array('creatine', 'crea ')
    ),
    'aminos' => array(
        'name' => 'Aminos',
        'description' => 'BCAAs, EAAs, glutamine and amino acid supplements',
        'keywords' => array('amino', 'bcaa', 'eaa', 'glutamine', 'beta-alanine', 'electro blast', 'b-110')
    ),
    'health-recovery' => array(
        'name' => 'Health & Recovery',
        'description' => 'Vitamins, minerals, recovery supplements and overall wellness',
        'keywords' => array('vitamin', 'omega', 'multivitamin', 'joint', 'collagen', 'probiotic', 'immune', 'iron', 'calcium', 'zinc', 'greens', 'daily greens', 'athletic greens', 'digestive', 'liver', 'organ support', 'gut health', 'cleanse', 'detox', 'apple cider', 'cramp', 'nac ', 'lion', 'hinge', 'ashwagandha', 'tongkat', 'gaba', 'fulvic', 'berberine', 'sleep', 'vita ', 'hair, skin', 'super greens', 'bloat', 'total repair', 'tudca', 'melatonin')
    ),
    'performance' => array(
        'name' => 'Performance',
        'description' => 'Testosterone boosters and performance enhancement supplements',
        'keywords' => array('testo', 'test charge', 'rhino', 'big test', 't bomb', 'ultra man', 'testogrowth', 'prototest', 'slu-pp', 'slupp', 'exercize', 'gda', 'nutrient beast', 'anabolic super beast', 'vitargo', 'carb fuel')
    ),
    'peptides' => array(
        'name' => 'Peptides',
        'description' => 'Research peptides, growth factors and peptide pens',
        'keywords' => array('peptide', 'bpc', 'tb500', 'tb 500', 'ipamorelin', 'cjc', 'ghrp', 'igf', 'hgh', 'somatropin', 'melanotan', 'pt-141', 'epitalon', 'epithalon', 'mots-c', 'ghk', 'aod', 'sermorelin', 'tesamorelin', 'semax', 'selank', 'kisspeptin', 'gonadorelin', 'hcg', 'hexarelin', 'hexerelin', 'frag', 'peg mgf', 'nad+', 'nad ', 'wolverine', 'shredded', 'limitless', 'skin glow', 'melano glow', 'meno-pause', 'mito boost', 'lean dreams', 'glutathione', 'fat away', 'needles', 'klow', 'glow', 'diablo', 'predator', 'matrix', 'anti-aging', 'novazempic', 'weightloss')
    ),
    'injectables' => array(
        'name' => 'Injectables',
        'description' => 'Injectable weight loss pens and pharmaceutical-grade injectables',
        'keywords' => array('semaglutide', 'tirzepatide', 'retatrutide', 'izempic', 'tirzepsema', 'cagri', 'maglira', 'tidlira', 'retlira', 'upzep', 'upmag', 'upret', 'magtidelira', 'l-carnitine', 'l-carnatine', 'cialis', 'glp1')
    ),
    'accessories' => array(
        'name' => 'Accessories',
        'description' => 'Needles, shakers and supplement accessories',
        'keywords' => array('needle', 'shaker', 'bag', 'belt', 'glove', 'accessories', 'syringe')
    ),
    'stacks' => array(
        'name' => 'Stacks',
        'description' => 'Value stacks and supplement bundles for maximum results',
        'keywords' => array('stack', 'combo', 'bundle')
    ),
    'specials' => array(
        'name' => 'Specials',
        'description' => 'Limited-time offers and special deals on supplements',
        'keywords' => array()
    )
);

$created = 0;
$assigned = 0;
$log = array();

// Step 1: Create categories
foreach ($categories as $slug => $cat) {
    if (!term_exists($slug, 'product_cat')) {
        $result = wp_insert_term($cat['name'], 'product_cat', array(
            'slug' => $slug,
            'description' => $cat['description']
        ));
        if (!is_wp_error($result)) {
            $created++;
            $log[] = "Created category: {$cat['name']} (ID: {$result['term_id']})";
        } else {
            $log[] = "Error creating {$cat['name']}: {$result->get_error_message()}";
        }
    } else {
        $log[] = "Category already exists: {$cat['name']}";
    }
}

// Step 2: Auto-assign products to type categories
$products = get_posts(array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'fields' => 'ids'
));

foreach ($products as $product_id) {
    $product_name = strtolower(get_the_title($product_id));
    $assigned_cats = array();

    foreach ($categories as $slug => $cat) {
        if (empty($cat['keywords'])) continue;

        foreach ($cat['keywords'] as $keyword) {
            if (strpos($product_name, strtolower($keyword)) !== false) {
                $assigned_cats[] = $slug;
                break;
            }
        }
    }

    if (!empty($assigned_cats)) {
        $term_ids = array();
        foreach ($assigned_cats as $cat_slug) {
            $term = get_term_by('slug', $cat_slug, 'product_cat');
            if ($term) {
                $term_ids[] = (int)$term->term_id;
            }
        }

        // Append to existing categories (don't remove brand categories)
        $existing = wp_get_object_terms($product_id, 'product_cat', array('fields' => 'ids'));
        $merged = array_unique(array_merge($existing, $term_ids));
        wp_set_object_terms($product_id, $merged, 'product_cat');
        $assigned++;
    }
}

// Step 3: Mark products on sale as "Specials"
$sale_products = wc_get_product_ids_on_sale();
if (!empty($sale_products)) {
    $specials_term = get_term_by('slug', 'specials', 'product_cat');
    if ($specials_term) {
        foreach ($sale_products as $pid) {
            $existing = wp_get_object_terms($pid, 'product_cat', array('fields' => 'ids'));
            if (!in_array($specials_term->term_id, $existing)) {
                $existing[] = (int)$specials_term->term_id;
                wp_set_object_terms($pid, $existing, 'product_cat');
            }
        }
        $log[] = "Assigned " . count($sale_products) . " sale products to Specials";
    }
}

$log[] = "---";
$log[] = "Created $created categories, assigned $assigned products";

// Output log to admin notice
error_log("Elvare Category Setup: " . implode(" | ", $log));

// Show result as admin notice
add_action('admin_notices', function() use ($log) {
    echo '<div class="notice notice-success"><p><strong>Elvare Category Setup Complete:</strong><br>';
    echo implode('<br>', $log);
    echo '</p></div>';
});
