/**
 * Elvare Group - Homepage Category Navigation Grid
 *
 * Adds a beautiful 11-category navigation section to the homepage.
 * (Injectables merged into Peptides)
 * Place after the hero section, before featured products.
 * Add this as a Code Snippet (PHP) - set to "Run on Frontend".
 */

// Inject category grid after hero section on homepage
add_action('wp_footer', function() {
    if (!is_front_page() && !is_home()) return;
    ?>
    <style>
        /* ========== CATEGORY GRID SECTION ========== */
        .elvare-categories-section {
            max-width: 1222px;
            margin: 0 auto;
            padding: 60px 15px 50px;
        }

        .elvare-categories-section .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .elvare-categories-section .section-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 10px;
            letter-spacing: -0.5px;
        }

        .elvare-categories-section .section-header p {
            font-size: 16px;
            color: #666;
            margin: 0;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .elvare-cat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .elvare-cat-card {
            background: #fff;
            border: 1.5px solid #e8f4f2;
            border-radius: 14px;
            padding: 28px 20px 24px;
            text-align: center;
            text-decoration: none !important;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .elvare-cat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2ec4b6, #20b2aa);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .elvare-cat-card:hover {
            border-color: #2ec4b6;
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(46, 196, 182, 0.15);
        }

        .elvare-cat-card:hover::before {
            transform: scaleX(1);
        }

        .elvare-cat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            transition: transform 0.3s ease;
        }

        .elvare-cat-card:hover .elvare-cat-icon {
            transform: scale(1.08);
        }

        .elvare-cat-icon svg {
            width: 28px;
            height: 28px;
        }

        .elvare-cat-card h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 6px;
            line-height: 1.3;
        }

        .elvare-cat-count {
            font-size: 12px;
            color: #999;
            font-weight: 400;
        }

        .elvare-cat-arrow {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f0f9f8;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.3s ease;
        }

        .elvare-cat-card:hover .elvare-cat-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .elvare-cat-arrow svg {
            width: 12px;
            height: 12px;
            stroke: #2ec4b6;
        }

        /* Color variants for each category */
        .elvare-cat-icon.cat-protein { background: #e8f5e9; }
        .elvare-cat-icon.cat-protein svg { stroke: #43a047; fill: none; }

        .elvare-cat-icon.cat-preworkout { background: #fff3e0; }
        .elvare-cat-icon.cat-preworkout svg { stroke: #ef6c00; fill: none; }

        .elvare-cat-icon.cat-fatburners { background: #fce4ec; }
        .elvare-cat-icon.cat-fatburners svg { stroke: #e53935; fill: none; }

        .elvare-cat-icon.cat-creatine { background: #e3f2fd; }
        .elvare-cat-icon.cat-creatine svg { stroke: #1e88e5; fill: none; }

        .elvare-cat-icon.cat-aminos { background: #f3e5f5; }
        .elvare-cat-icon.cat-aminos svg { stroke: #8e24aa; fill: none; }

        .elvare-cat-icon.cat-health { background: #e0f2f1; }
        .elvare-cat-icon.cat-health svg { stroke: #00897b; fill: none; }

        .elvare-cat-icon.cat-performance { background: #fff8e1; }
        .elvare-cat-icon.cat-performance svg { stroke: #f9a825; fill: none; }

        .elvare-cat-icon.cat-peptides { background: #e8eaf6; }
        .elvare-cat-icon.cat-peptides svg { stroke: #3949ab; fill: none; }

        .elvare-cat-icon.cat-accessories { background: #efebe9; }
        .elvare-cat-icon.cat-accessories svg { stroke: #6d4c41; fill: none; }

        .elvare-cat-icon.cat-stacks { background: #e0f7fa; }
        .elvare-cat-icon.cat-stacks svg { stroke: #00838f; fill: none; }

        .elvare-cat-icon.cat-specials { background: #fff9c4; }
        .elvare-cat-icon.cat-specials svg { stroke: #f57f17; fill: none; }

        /* Responsive */
        @media (max-width: 991px) {
            .elvare-cat-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
            }
            .elvare-categories-section {
                padding: 45px 15px 40px;
            }
            .elvare-categories-section .section-header h2 {
                font-size: 26px;
            }
        }

        @media (max-width: 576px) {
            .elvare-cat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .elvare-categories-section {
                padding: 35px 12px 30px;
            }
            .elvare-categories-section .section-header h2 {
                font-size: 22px;
            }
            .elvare-categories-section .section-header {
                margin-bottom: 24px;
            }
            .elvare-cat-card {
                padding: 20px 12px 18px;
                border-radius: 12px;
            }
            .elvare-cat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                margin-bottom: 10px;
            }
            .elvare-cat-icon svg {
                width: 24px;
                height: 24px;
            }
            .elvare-cat-card h3 {
                font-size: 13px;
            }
            .elvare-cat-count {
                font-size: 11px;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // SVG icons for each category
        var icons = {
            'protein': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2h8l1 5H7L8 2z"/><rect x="7" y="7" width="10" height="13" rx="1"/><path d="M7 20h10v1a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-1z"/><path d="M10 10v4"/><path d="M14 10v4"/></svg>',
            'preworkout': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
            'fatburners': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c-4.97 0-9-2.69-9-6v-.5C3 11.57 7.03 6 12 2c4.97 4 9 9.57 9 13.5v.5c0 3.31-4.03 6-9 6z"/><path d="M12 22c-2.49 0-4.5-1.34-4.5-3v-.25C7.5 16.28 9.51 13 12 10c2.49 3 4.5 6.28 4.5 8.75v.25c0 1.66-2.01 3-4.5 3z"/></svg>',
            'creatine': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>',
            'aminos': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 15c6.667-6 13.333 0 20-6"/><path d="M9 22c1.798-4.86 3.733-8 5.5-10.5"/><path d="M15 2c-1.798 4.86-3.733 8-5.5 10.5"/><circle cx="8" cy="9" r="2"/><circle cx="16" cy="15" r="2"/></svg>',
            'health': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2a2 2 0 0 1 2 0l6.93 4a2 2 0 0 1 1 1.73v8.54a2 2 0 0 1-1 1.73l-6.93 4a2 2 0 0 1-2 0l-6.93-4a2 2 0 0 1-1-1.73V7.73a2 2 0 0 1 1-1.73z"/><path d="M12 8v4"/><path d="M10 10h4"/></svg>',
            'performance': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6-6 6 6"/><path d="M12 3v14"/><circle cx="12" cy="21" r="1"/><path d="M4 15l2-2"/><path d="M20 15l-2-2"/></svg>',
            'peptides': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2l-3 3"/><path d="M15 5l-6 6"/><path d="M9 11l-6 6"/><path d="M3 17l3 3"/><path d="M14 6l4 4"/><path d="M8 12l4 4"/><circle cx="6" cy="18" r="2"/></svg>',
            'accessories': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
            'stacks': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>',
            'specials': '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
        };

        var arrow = '<svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>';

        var categories = [
            { slug: 'protein', name: 'Protein', icon: 'protein', css: 'cat-protein' },
            { slug: 'pre-workout', name: 'Pre Workout', icon: 'preworkout', css: 'cat-preworkout' },
            { slug: 'fat-burners', name: 'Fat Burners', icon: 'fatburners', css: 'cat-fatburners' },
            { slug: 'creatine', name: 'Creatine', icon: 'creatine', css: 'cat-creatine' },
            { slug: 'aminos', name: 'Aminos', icon: 'aminos', css: 'cat-aminos' },
            { slug: 'health-recovery', name: 'Health & Recovery', icon: 'health', css: 'cat-health' },
            { slug: 'performance', name: 'Performance', icon: 'performance', css: 'cat-performance' },
            { slug: 'peptides', name: 'Peptides', icon: 'peptides', css: 'cat-peptides' },
            { slug: 'accessories', name: 'Accessories', icon: 'accessories', css: 'cat-accessories' },
            { slug: 'stacks', name: 'Stacks', icon: 'stacks', css: 'cat-stacks' },
            { slug: 'specials', name: 'Specials', icon: 'specials', css: 'cat-specials' }
        ];

        var baseUrl = '/product-category/';

        // Build the section HTML
        var html = '<div class="elvare-categories-section">';
        html += '<div class="section-header">';
        html += '<h2>Shop by Category</h2>';
        html += '<p>Find exactly what you need — from protein to peptides</p>';
        html += '</div>';
        html += '<div class="elvare-cat-grid">';

        categories.forEach(function(cat) {
            html += '<a href="' + baseUrl + cat.slug + '/" class="elvare-cat-card">';
            html += '<div class="elvare-cat-icon ' + cat.css + '">' + icons[cat.icon] + '</div>';
            html += '<h3>' + cat.name + '</h3>';
            html += '<span class="elvare-cat-arrow">' + arrow + '</span>';
            html += '</a>';
        });

        html += '</div></div>';

        // Insert after hero/banner section
        var inserted = false;

        // Try to find the info boxes section (Standard Delivery / All Day Support / Secure Checkout)
        var infoBoxes = document.querySelector('.woodmart-info-box-wrapper, .info-box-wrapper, .woodmart-info-box');
        if (!infoBoxes) {
            // Look for the section containing "Standard Delivery"
            var allSections = document.querySelectorAll('.elementor-section, .vc_row, section, .wpb_row');
            for (var i = 0; i < allSections.length; i++) {
                var text = allSections[i].textContent || '';
                if (text.indexOf('Standard Delivery') !== -1 && text.indexOf('All Day Support') !== -1) {
                    infoBoxes = allSections[i];
                    break;
                }
            }
        }

        if (infoBoxes) {
            // Insert after the info boxes section
            var wrapper = infoBoxes.closest('.elementor-section') || infoBoxes.closest('section') || infoBoxes.parentElement;
            if (wrapper) {
                wrapper.insertAdjacentHTML('afterend', html);
                inserted = true;
            }
        }

        if (!inserted) {
            // Fallback: find the Featured Products heading and insert before it
            var headings = document.querySelectorAll('h2, .section-title');
            for (var j = 0; j < headings.length; j++) {
                if ((headings[j].textContent || '').indexOf('Featured Products') !== -1) {
                    var parent = headings[j].closest('.elementor-section') || headings[j].closest('section') || headings[j].parentElement;
                    if (parent) {
                        parent.insertAdjacentHTML('beforebegin', html);
                        inserted = true;
                        break;
                    }
                }
            }
        }

        if (!inserted) {
            // Last resort: insert after first major section
            var main = document.querySelector('.site-content, main, #content, .content-area');
            if (main && main.children.length > 1) {
                main.children[0].insertAdjacentHTML('afterend', html);
            }
        }
    });
    </script>
    <?php
});
