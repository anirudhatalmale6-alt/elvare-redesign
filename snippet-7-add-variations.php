/**
 * Elvare Group - Add Product Variations (Flavour/Size/Colour)
 *
 * Converts 62 simple products to variable products with proper
 * flavour/size/colour options from PsychoSkull.
 *
 * Set to "Run everywhere" and activate.
 * After green success message, DEACTIVATE this snippet.
 * This may take 30-60 seconds to run - be patient after activating.
 */

add_action('admin_init', function() {
    if (get_option('elvare_variations_done')) return;

    // Increase time limit for this operation
    set_time_limit(300);

    $products_data = array(
        2465 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Lemon Lime', 'price' => 735, 'in_stock' => true),
                array('value' => 'Fruit Punch', 'price' => 735, 'in_stock' => true),
            )
        ),
        2482 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Tropical Fruit', 'price' => 265, 'in_stock' => true),
                array('value' => 'Berries', 'price' => 265, 'in_stock' => true),
                array('value' => 'Strawberry Watermelon', 'price' => 265, 'in_stock' => true),
            )
        ),
        2496 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Litchi Pear', 'price' => 275, 'in_stock' => true),
                array('value' => 'Candy Apple', 'price' => 275, 'in_stock' => true),
            )
        ),
        2500 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Naartjie', 'price' => 15, 'in_stock' => true),
                array('value' => 'Tropical', 'price' => 15, 'in_stock' => true),
            )
        ),
        2502 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Naartjie', 'price' => 105, 'in_stock' => true),
                array('value' => 'Tropical', 'price' => 105, 'in_stock' => true),
            )
        ),
        2504 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Naartjie', 'price' => 25, 'in_stock' => true),
                array('value' => 'Lemon', 'price' => 25, 'in_stock' => true),
            )
        ),
        2506 => array(
            'attr_name' => 'QTY',
            'variations' => array(
                array('value' => '10\'s', 'price' => 105, 'in_stock' => true),
                array('value' => '20\'s', 'price' => 210, 'in_stock' => true),
                array('value' => 'Single Sachet', 'price' => 15, 'in_stock' => true),
            )
        ),
        2532 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Mixed Berries', 'price' => 370, 'in_stock' => true),
                array('value' => 'Tropical Fruit', 'price' => 370, 'in_stock' => true),
            )
        ),
        1664 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Citrus Zest', 'price' => 315, 'in_stock' => true),
                array('value' => 'Kiwi Strawberry', 'price' => 315, 'in_stock' => true),
            )
        ),
        2536 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate', 'price' => 315, 'in_stock' => true),
                array('value' => 'Cinnamon Pancake', 'price' => 315, 'in_stock' => false),
                array('value' => 'Strawberry', 'price' => 315, 'in_stock' => true),
                array('value' => 'Vanilla', 'price' => 315, 'in_stock' => false),
            )
        ),
        2567 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Icy Blue Lemonade', 'price' => 325, 'in_stock' => false),
                array('value' => 'Green Apple', 'price' => 325, 'in_stock' => false),
            )
        ),
        2961 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate Candy Dream', 'price' => 1345, 'in_stock' => false),
                array('value' => 'Vanilla Ice Cream', 'price' => 1345, 'in_stock' => false),
                array('value' => 'Choc Nougat', 'price' => 1345, 'in_stock' => true),
                array('value' => 'Cookies & Cream', 'price' => 1345, 'in_stock' => true),
                array('value' => 'Malted Choc', 'price' => 1345, 'in_stock' => true),
                array('value' => 'Strawberry Cheesecake', 'price' => 1345, 'in_stock' => false),
            )
        ),
        2573 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Fruit Blast', 'price' => 245, 'in_stock' => false),
                array('value' => 'Xplosive Cherry', 'price' => 245, 'in_stock' => false),
            )
        ),
        2586 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Rainbow Stripes', 'price' => 475, 'in_stock' => true),
                array('value' => 'Black Mango', 'price' => 475, 'in_stock' => true),
            )
        ),
        2593 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Sour Kiwi', 'price' => 370, 'in_stock' => false),
                array('value' => 'Rocket Pop', 'price' => 370, 'in_stock' => true),
                array('value' => 'Cotton Candy', 'price' => 370, 'in_stock' => false),
            )
        ),
        2605 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Strawberry Pine', 'price' => 245, 'in_stock' => false),
                array('value' => 'Blue Raspberry', 'price' => 245, 'in_stock' => false),
                array('value' => 'Fruit Punch', 'price' => 245, 'in_stock' => false),
                array('value' => 'Grape', 'price' => 245, 'in_stock' => false),
            )
        ),
        2342 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Vanilla', 'price' => 265, 'in_stock' => true),
                array('value' => 'Chocolate', 'price' => 265, 'in_stock' => false),
            )
        ),
        2617 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Sour Berry', 'price' => 210, 'in_stock' => true),
                array('value' => 'Melon Candy', 'price' => 210, 'in_stock' => true),
            )
        ),
        2621 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Choc Coconut', 'price' => 35, 'in_stock' => true),
                array('value' => 'Choc Mint', 'price' => 35, 'in_stock' => true),
                array('value' => 'Double Choc', 'price' => 35, 'in_stock' => true),
            )
        ),
        2623 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Cookie Dough', 'price' => 40, 'in_stock' => true),
                array('value' => 'Choc Nut', 'price' => 40, 'in_stock' => true),
                array('value' => 'Caramel Sea Salt', 'price' => 40, 'in_stock' => true),
                array('value' => 'Chocolate Caramel', 'price' => 40, 'in_stock' => true),
                array('value' => 'Choc Mint', 'price' => 40, 'in_stock' => false),
            )
        ),
        2629 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Blood Orange', 'price' => 525, 'in_stock' => true),
                array('value' => 'Ice Pop', 'price' => 525, 'in_stock' => true),
                array('value' => 'Melon Splash', 'price' => 525, 'in_stock' => true),
            )
        ),
        2633 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Red Beast', 'price' => 605, 'in_stock' => false),
                array('value' => 'Fruit Punch', 'price' => 605, 'in_stock' => false),
                array('value' => 'Mango Orange', 'price' => 605, 'in_stock' => false),
            )
        ),
        2643 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Fruit Punch', 'price' => 420, 'in_stock' => false),
                array('value' => 'Candy Apple', 'price' => 420, 'in_stock' => false),
            )
        ),
        2994 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'White Chocolate', 'price' => 650, 'in_stock' => true),
                array('value' => 'Chocolate Caramel', 'price' => 650, 'in_stock' => true),
                array('value' => 'Chocolate Nut', 'price' => 650, 'in_stock' => false),
            )
        ),
        2647 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Candy Dream', 'price' => 315, 'in_stock' => true),
                array('value' => 'Chocolate Milkshake', 'price' => 315, 'in_stock' => true),
                array('value' => 'Vanilla Milkshake', 'price' => 315, 'in_stock' => true),
            )
        ),
        2901 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate Mousse', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Vanilla Cream', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Strawberry Sundae', 'price' => 1050, 'in_stock' => true),
            )
        ),
        2659 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate Mousse', 'price' => 350, 'in_stock' => true),
                array('value' => 'Vanilla Cream', 'price' => 350, 'in_stock' => true),
                array('value' => 'Strawberry Sundae', 'price' => 350, 'in_stock' => true),
            )
        ),
        2925 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Vanilla Cream', 'price' => 420, 'in_stock' => true),
                array('value' => 'Chocolate Mousse', 'price' => 420, 'in_stock' => true),
                array('value' => 'Strawberry Sundae', 'price' => 420, 'in_stock' => false),
            )
        ),
        2670 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Blitz Berry', 'price' => 20, 'in_stock' => true),
                array('value' => 'Paradise Punch', 'price' => 20, 'in_stock' => false),
                array('value' => 'Blue Razzberry', 'price' => 20, 'in_stock' => false),
                array('value' => 'Island vibe', 'price' => 20, 'in_stock' => false),
                array('value' => 'Mango Melon', 'price' => 20, 'in_stock' => false),
                array('value' => 'Satsuma Naartjie', 'price' => 20, 'in_stock' => true),
                array('value' => 'Violet Haze', 'price' => 20, 'in_stock' => true),
            )
        ),
        2672 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Candy Cruise', 'price' => 95, 'in_stock' => true),
                array('value' => 'Black Current Blaze', 'price' => 95, 'in_stock' => true),
                array('value' => 'Tropical Thunder', 'price' => 95, 'in_stock' => true),
            )
        ),
        2700 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate', 'price' => 475, 'in_stock' => false),
                array('value' => 'Vanilla Nut', 'price' => 475, 'in_stock' => false),
                array('value' => 'Strawberry', 'price' => 475, 'in_stock' => false),
            )
        ),
        2710 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Pineapple', 'price' => 370, 'in_stock' => false),
                array('value' => 'Blueberry', 'price' => 370, 'in_stock' => false),
                array('value' => 'Fruit Punch', 'price' => 370, 'in_stock' => false),
            )
        ),
        2714 => array(
            'attr_name' => 'Colour',
            'variations' => array(
                array('value' => 'Hydrade', 'price' => 65, 'in_stock' => true),
                array('value' => 'Yellow', 'price' => 65, 'in_stock' => true),
                array('value' => 'Turquoise', 'price' => 65, 'in_stock' => false),
                array('value' => 'Black', 'price' => 65, 'in_stock' => false),
                array('value' => 'Pink', 'price' => 65, 'in_stock' => false),
            )
        ),
        2720 => array(
            'attr_name' => 'Size',
            'variations' => array(
                array('value' => 'Single Can', 'price' => 30, 'in_stock' => true),
                array('value' => '6 Pack', 'price' => 160, 'in_stock' => true),
                array('value' => 'Case (24)', 'price' => 570, 'in_stock' => true),
            )
        ),
        2724 => array(
            'attr_name' => 'Size',
            'variations' => array(
                array('value' => '200g', 'price' => 190, 'in_stock' => true),
                array('value' => '500g', 'price' => 420, 'in_stock' => false),
            )
        ),
        2727 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'BLUEBERRY BLITZ', 'price' => 75, 'in_stock' => true),
                array('value' => 'NATIONAL NAARTJIE', 'price' => 75, 'in_stock' => true),
                array('value' => 'PINEAPPLE PRIME', 'price' => 75, 'in_stock' => true),
            )
        ),
        2735 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Apple Candy', 'price' => 265, 'in_stock' => true),
                array('value' => 'Red Dragon', 'price' => 265, 'in_stock' => true),
                array('value' => 'Strawberry Sting', 'price' => 265, 'in_stock' => true),
            )
        ),
        2741 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Cotton Candy', 'price' => 285, 'in_stock' => true),
                array('value' => 'Mango Orange', 'price' => 285, 'in_stock' => true),
            )
        ),
        2750 => array(
            'attr_name' => 'Colour',
            'variations' => array(
                array('value' => 'Aurora Pink', 'price' => 65, 'in_stock' => true),
                array('value' => 'Peacock Blue', 'price' => 65, 'in_stock' => false),
                array('value' => 'Antrazit', 'price' => 65, 'in_stock' => true),
                array('value' => 'Mint', 'price' => 65, 'in_stock' => false),
                array('value' => 'Yellow', 'price' => 65, 'in_stock' => true),
                array('value' => 'Neon', 'price' => 65, 'in_stock' => false),
            )
        ),
        2805 => array(
            'attr_name' => 'Colour',
            'variations' => array(
                array('value' => 'Black', 'price' => 130, 'in_stock' => false),
                array('value' => 'Turquoise', 'price' => 130, 'in_stock' => false),
                array('value' => 'White', 'price' => 130, 'in_stock' => false),
            )
        ),
        2989 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Cookies & Cream', 'price' => 525, 'in_stock' => true),
                array('value' => 'Chocolate', 'price' => 525, 'in_stock' => true),
                array('value' => 'Strawberry', 'price' => 525, 'in_stock' => true),
                array('value' => 'Vanilla', 'price' => 525, 'in_stock' => true),
            )
        ),
        2760 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Choc Nut Sundae', 'price' => 445, 'in_stock' => true),
                array('value' => 'Coffee Mocha', 'price' => 445, 'in_stock' => true),
                array('value' => 'Raspberry Greek Yoghurt', 'price' => 445, 'in_stock' => true),
                array('value' => 'Vanilla Doughnut', 'price' => 445, 'in_stock' => true),
            )
        ),
        2941 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Choc Biscuit', 'price' => 790, 'in_stock' => true),
                array('value' => 'Choc Candy', 'price' => 790, 'in_stock' => true),
                array('value' => 'Vanilla Cream', 'price' => 790, 'in_stock' => true),
                array('value' => 'Banana', 'price' => 790, 'in_stock' => false),
            )
        ),
        2996 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate Nut', 'price' => 525, 'in_stock' => true),
                array('value' => 'White Chocolate', 'price' => 525, 'in_stock' => true),
                array('value' => 'Strawberry Marshmallow', 'price' => 525, 'in_stock' => true),
                array('value' => 'Chocolate Mousse', 'price' => 525, 'in_stock' => false),
                array('value' => 'Vanilla Fudge', 'price' => 525, 'in_stock' => true),
            )
        ),
        3000 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Strawberry', 'price' => 1050, 'in_stock' => true),
                array('value' => 'White Chocolate', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Vanilla Caramel', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Choc-Nut Butter', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Choc-Bar Deluxe', 'price' => 1050, 'in_stock' => true),
                array('value' => 'Chocolate Caramel', 'price' => 1050, 'in_stock' => true),
            )
        ),
        2835 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Orange Mango', 'price' => 515, 'in_stock' => false),
                array('value' => 'Pink Lemonade', 'price' => 515, 'in_stock' => false),
            )
        ),
        2838 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Sour Berry', 'price' => 370, 'in_stock' => true),
                array('value' => 'Rocket Pop', 'price' => 370, 'in_stock' => true),
                array('value' => 'Strawberry Lime', 'price' => 370, 'in_stock' => true),
                array('value' => 'Cherry Cola', 'price' => 370, 'in_stock' => true),
                array('value' => 'Pina Colada', 'price' => 370, 'in_stock' => false),
            )
        ),
        2840 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Chocolate', 'price' => 500, 'in_stock' => false),
                array('value' => 'Strawberry Banana', 'price' => 500, 'in_stock' => true),
            )
        ),
        2889 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Berry Detonator', 'price' => 525, 'in_stock' => true),
                array('value' => 'Shockwave', 'price' => 525, 'in_stock' => true),
                array('value' => 'Tropical Saigon', 'price' => 525, 'in_stock' => true),
            )
        ),
        2892 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Black Cherry', 'price' => 420, 'in_stock' => true),
                array('value' => 'Strawberry Kiwi', 'price' => 420, 'in_stock' => true),
            )
        ),
        2916 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Tropical Punch', 'price' => 175, 'in_stock' => true),
                array('value' => 'Crushing Cherry Candy', 'price' => 175, 'in_stock' => true),
                array('value' => 'Electric Blueberry', 'price' => 175, 'in_stock' => true),
            )
        ),
        2928 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'AMERICAN CREAM SODA', 'price' => 525, 'in_stock' => false),
                array('value' => 'CHOCOLATE MOUSSE', 'price' => 525, 'in_stock' => true),
                array('value' => 'COOKIES & CREAM SUNDAE', 'price' => 525, 'in_stock' => false),
                array('value' => 'STRAWBERRY CHEESECAKE', 'price' => 525, 'in_stock' => false),
            )
        ),
        2931 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Green Apple', 'price' => 315, 'in_stock' => true),
                array('value' => 'Tropical Punch', 'price' => 315, 'in_stock' => false),
            )
        ),
        2939 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Choc Biscuit', 'price' => 620, 'in_stock' => true),
                array('value' => 'Vanilla Custard', 'price' => 620, 'in_stock' => true),
            )
        ),
        2955 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Fruit Fusion', 'price' => 360, 'in_stock' => true),
                array('value' => 'Wild Strawberry', 'price' => 360, 'in_stock' => true),
            )
        ),
        2977 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Wild Cherry', 'price' => 305, 'in_stock' => true),
                array('value' => 'Crimson Berry', 'price' => 305, 'in_stock' => false),
                array('value' => 'Tropical Punch', 'price' => 305, 'in_stock' => true),
            )
        ),
        2983 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Blueberry', 'price' => 340, 'in_stock' => true),
                array('value' => 'Cherry', 'price' => 340, 'in_stock' => true),
            )
        ),
        2985 => array(
            'attr_name' => 'Colour',
            'variations' => array(
                array('value' => 'Black', 'price' => 130, 'in_stock' => false),
                array('value' => 'Pink', 'price' => 130, 'in_stock' => true),
            )
        ),
        2987 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Watermelon Candy', 'price' => 570, 'in_stock' => true),
                array('value' => 'Fruit Fusion', 'price' => 570, 'in_stock' => true),
                array('value' => 'Candy Apple', 'price' => 570, 'in_stock' => true),
            )
        ),
        2991 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Fruit Bliss', 'price' => 360, 'in_stock' => false),
                array('value' => 'Strawberry Kiwi', 'price' => 360, 'in_stock' => false),
                array('value' => 'Summer Berries', 'price' => 360, 'in_stock' => false),
            )
        ),
        2998 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'Tropical Punch', 'price' => 315, 'in_stock' => true),
                array('value' => 'Cherry Berry', 'price' => 315, 'in_stock' => true),
            )
        ),
        3008 => array(
            'attr_name' => 'Flavour',
            'variations' => array(
                array('value' => 'CRIMSON CRUSH', 'price' => 315, 'in_stock' => true),
                array('value' => 'INDIGO ICE', 'price' => 315, 'in_stock' => true),
                array('value' => 'TROPIAL RAIN', 'price' => 315, 'in_stock' => true),
            )
        ),
    );

    $converted = 0;
    $errors = array();

    foreach ($products_data as $product_id => $pdata) {
        $product = wc_get_product($product_id);
        if (!$product) {
            $errors[] = "Product {$product_id} not found";
            continue;
        }

        if ($product->is_type('variable')) {
            continue;
        }

        $attr_name = $pdata['attr_name'];
        $attr_slug = sanitize_title($attr_name);

        // Collect all variation values
        $all_values = array();
        foreach ($pdata['variations'] as $var) {
            $all_values[] = $var['value'];
        }

        // Step 1: Change product type to variable
        wp_set_object_terms($product_id, 'variable', 'product_type');

        // Step 2: Create the product attribute
        $product_attributes = array();
        $product_attributes[$attr_slug] = array(
            'name' => $attr_name,
            'value' => implode(' | ', $all_values),
            'position' => 0,
            'is_visible' => 1,
            'is_variation' => 1,
            'is_taxonomy' => 0
        );
        update_post_meta($product_id, '_product_attributes', $product_attributes);

        // Step 3: Create variations
        foreach ($pdata['variations'] as $var) {
            $variation_post = array(
                'post_title' => $product->get_name() . ' - ' . $var['value'],
                'post_content' => '',
                'post_status' => 'publish',
                'post_parent' => $product_id,
                'post_type' => 'product_variation'
            );

            $variation_id = wp_insert_post($variation_post);

            if ($variation_id && !is_wp_error($variation_id)) {
                update_post_meta($variation_id, 'attribute_' . $attr_slug, $var['value']);
                update_post_meta($variation_id, '_regular_price', $var['price']);
                update_post_meta($variation_id, '_price', $var['price']);
                $stock_status = $var['in_stock'] ? 'instock' : 'outofstock';
                update_post_meta($variation_id, '_stock_status', $stock_status);
                update_post_meta($variation_id, '_manage_stock', 'no');
                update_post_meta($variation_id, '_virtual', 'no');
                update_post_meta($variation_id, '_downloadable', 'no');
            }
        }

        // Step 4: Update parent product meta
        $prices = array_column($pdata['variations'], 'price');
        update_post_meta($product_id, '_min_variation_price', min($prices));
        update_post_meta($product_id, '_max_variation_price', max($prices));
        update_post_meta($product_id, '_min_variation_regular_price', min($prices));
        update_post_meta($product_id, '_max_variation_regular_price', max($prices));
        update_post_meta($product_id, '_price', min($prices));

        // Clear transients
        delete_transient('wc_product_children_' . $product_id);
        wc_delete_product_transients($product_id);

        $converted++;
    }

    update_option('elvare_variations_done', $converted);
    if (!empty($errors)) {
        update_option('elvare_variations_errors', implode('; ', $errors));
    }
});

add_action('admin_notices', function() {
    $done = get_option('elvare_variations_done');
    if ($done !== false && $done !== '') {
        $errors = get_option('elvare_variations_errors', '');
        $msg = '<div class="notice notice-success"><p><strong>Variations added!</strong> Converted ' . intval($done) . ' products to variable with flavour/size options.';
        if ($errors) {
            $msg .= '<br>Errors: ' . esc_html($errors);
        }
        $msg .= ' You can now deactivate this snippet.</p></div>';
        echo $msg;
    }
});
