<?php
/**
 * One Click Setup Wizard
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Function to run the setup wizard
function dt_run_one_click_setup() {
    // Ensure admin nav menu functions are loaded (needed if triggered from frontend template_redirect)
    if ( ! function_exists( 'wp_update_nav_menu_item' ) ) {
        require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
    }

    // 1. Create Required Pages
    $pages_to_create = array(
        'home' => array(
            'title'    => 'Home',
            'template' => 'front-page.php',
            'content'  => '',
        ),
        'shop' => array(
            'title'    => 'Shop',
            'template' => '',
            'content'  => '',
        ),
        'product' => array(
            'title'    => 'Product Page',
            'template' => '',
            'content'  => '<!-- wp:paragraph --><p>Product details are handled automatically by WooCommerce product templates.</p><!-- /wp:paragraph -->',
        ),
        'cart' => array(
            'title'    => 'Cart',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
        ),
        'checkout' => array(
            'title'    => 'Checkout',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
        ),
        'wishlist' => array(
            'title'    => 'Wishlist',
            'template' => 'templates/template-wishlist.php',
            'content'  => '',
        ),
        'my-account' => array(
            'title'    => 'My Account',
            'template' => '',
            'content'  => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->',
        ),
        'about-us' => array(
            'title'    => 'About Us',
            'template' => 'templates/template-about.php',
            'content'  => '',
        ),
        'contact-us' => array(
            'title'    => 'Contact Us',
            'template' => 'templates/template-contact.php',
            'content'  => '',
        ),
        'privacy-policy' => array(
            'title'    => 'Privacy Policy',
            'template' => '',
            'content'  => '<!-- wp:heading --><h2>Privacy Policy</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add your store privacy policy here.</p><!-- /wp:paragraph -->',
        ),
        'refund-policy' => array(
            'title'    => 'Refund Policy',
            'template' => '',
            'content'  => '<!-- wp:heading --><h2>Refund Policy</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add your refund and exchange policy here.</p><!-- /wp:paragraph -->',
        ),
        'terms-conditions' => array(
            'title'    => 'Terms & Conditions',
            'template' => '',
            'content'  => '<!-- wp:heading --><h2>Terms &amp; Conditions</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add your store terms and conditions here.</p><!-- /wp:paragraph -->',
        ),
        'track-order' => array(
            'title'    => 'Track Order',
            'template' => 'templates/template-track-order.php',
            'content'  => '',
        ),
        'faq' => array(
            'title'    => 'FAQ',
            'template' => 'templates/template-faq.php',
            'content'  => '',
        ),
        'blog' => array(
            'title'    => 'Blog',
            'template' => '',
            'content'  => '',
        ),
        'search' => array(
            'title'    => 'Search Page',
            'template' => '',
            'content'  => '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search products, fabrics, colors...","buttonText":"Search"} /-->',
        ),
        '404-not-found' => array(
            'title'    => '404 Page',
            'template' => '',
            'content'  => '<!-- wp:paragraph --><p>This page is reserved for not found content. The theme 404 template is used automatically.</p><!-- /wp:paragraph -->',
        ),
        'our-story' => array(
            'title'    => 'Our Story',
            'template' => 'templates/template-our-story.php',
            'content'  => '',
        ),
        'shipping-policy' => array(
            'title'    => 'Shipping Policy',
            'template' => 'templates/template-shipping-policy.php',
            'content'  => '',
        ),
    );

    $created_page_ids = array();

    foreach ( $pages_to_create as $slug => $data ) {
        // Check if page already exists
        $existing_page = get_page_by_path( $slug );
        if ( ! $existing_page ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => $data['content'],
            ) );

            if ( $page_id && ! is_wp_error( $page_id ) ) {
                $created_page_ids[$slug] = $page_id;
                if ( ! empty( $data['template'] ) ) {
                    update_post_meta( $page_id, '_wp_page_template', $data['template'] );
                }
            }
        } else {
            $created_page_ids[$slug] = $existing_page->ID;
            $existing_content = trim( (string) $existing_page->post_content );
            $is_placeholder   = strpos( $existing_content, '<!-- wp:paragraph --><p>Content for ' ) === 0;

            if ( ! empty( $data['content'] ) && ( '' === $existing_content || $is_placeholder ) ) {
                wp_update_post( array(
                    'ID'           => $existing_page->ID,
                    'post_content' => $data['content'],
                ) );
            }

            if ( ! empty( $data['template'] ) && 'default' === get_page_template_slug( $existing_page->ID ) ) {
                update_post_meta( $existing_page->ID, '_wp_page_template', $data['template'] );
            }
        }
    }

    // Set Front Page and Blog page configuration
    if ( isset( $created_page_ids['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $created_page_ids['home'] );
    }
    if ( isset( $created_page_ids['blog'] ) ) {
        update_option( 'page_for_posts', $created_page_ids['blog'] );
    }

    // Set WooCommerce default pages
    if ( class_exists( 'WooCommerce' ) ) {
        if ( isset( $created_page_ids['shop'] ) ) {
            update_option( 'woocommerce_shop_page_id', $created_page_ids['shop'] );
        }
        if ( isset( $created_page_ids['cart'] ) ) {
            update_option( 'woocommerce_cart_page_id', $created_page_ids['cart'] );
        }
        if ( isset( $created_page_ids['checkout'] ) ) {
            update_option( 'woocommerce_checkout_page_id', $created_page_ids['checkout'] );
        }
        if ( isset( $created_page_ids['my-account'] ) ) {
            update_option( 'woocommerce_myaccount_page_id', $created_page_ids['my-account'] );
        }
    }

    if ( isset( $created_page_ids['privacy-policy'] ) ) {
        update_option( 'wp_page_for_privacy_policy', $created_page_ids['privacy-policy'] );
    }

    // 2. Create Navigation Menus
    $menus_to_create = array(
        'Header Menu'  => 'header-menu',
        'Mobile Menu'  => 'mobile-menu',
        'Footer Menu'  => 'footer-menu',
        'Account Menu' => 'account-menu',
    );

    $menu_locations = array();

    foreach ( $menus_to_create as $menu_name => $location_slug ) {
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        if ( ! $menu_exists ) {
            $menu_id = wp_create_nav_menu( $menu_name );
            if ( $menu_id && ! is_wp_error( $menu_id ) ) {
                $menu_locations[$location_slug] = $menu_id;
                
                // Add some default items to Header Menu
                if ( $location_slug === 'header-menu' ) {
                    wp_update_nav_menu_item( $menu_id, 0, array(
                        'menu-item-title'   => 'Home',
                        'menu-item-classes' => 'home',
                        'menu-item-url'     => home_url( '/' ),
                        'menu-item-status'  => 'publish',
                    ) );
                    if ( isset( $created_page_ids['shop'] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-title'     => 'Shop',
                            'menu-item-object-id' => $created_page_ids['shop'],
                            'menu-item-object'    => 'page',
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ) );
                    }
                    if ( isset( $created_page_ids['wishlist'] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-title'     => 'Wishlist',
                            'menu-item-object-id' => $created_page_ids['wishlist'],
                            'menu-item-object'    => 'page',
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ) );
                    }
                    if ( isset( $created_page_ids['track-order'] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-title'     => 'Track Order',
                            'menu-item-object-id' => $created_page_ids['track-order'],
                            'menu-item-object'    => 'page',
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ) );
                    }
                    if ( isset( $created_page_ids['contact-us'] ) ) {
                        wp_update_nav_menu_item( $menu_id, 0, array(
                            'menu-item-title'     => 'Contact',
                            'menu-item-object-id' => $created_page_ids['contact-us'],
                            'menu-item-object'    => 'page',
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ) );
                    }
                }
            }
        } else {
            $menu_locations[$location_slug] = $menu_exists->term_id;
        }
    }

    // Assign registered menu locations
    set_theme_mod( 'nav_menu_locations', $menu_locations );

    // 3. Set Default Widgets (Footer and Sidebar)
    // We can declare custom default options in get_option/theme_mod settings
    update_option( 'dt_setup_completed', 'yes' );
}

/**
 * Ensure core WooCommerce page options point to existing published pages.
 *
 * This repairs stores where a WooCommerce page option references a deleted page,
 * which appears in WooCommerce status as "Page ID is set, but the page does not exist".
 */
function dt_ensure_core_store_pages(): void {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    $pages = array(
        'shop' => array(
            'title'   => 'Shop',
            'option'  => 'woocommerce_shop_page_id',
            'content' => '',
        ),
        'cart' => array(
            'title'   => 'Cart',
            'option'  => 'woocommerce_cart_page_id',
            'content' => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
        ),
        'checkout' => array(
            'title'   => 'Checkout',
            'option'  => 'woocommerce_checkout_page_id',
            'content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
        ),
        'my-account' => array(
            'title'   => 'My Account',
            'option'  => 'woocommerce_myaccount_page_id',
            'content' => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->',
        ),
        'terms-conditions' => array(
            'title'   => 'Terms & Conditions',
            'option'  => 'woocommerce_terms_page_id',
            'content' => '<!-- wp:heading --><h2>Terms &amp; Conditions</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add your store terms and conditions here.</p><!-- /wp:paragraph -->',
        ),
        'privacy-policy' => array(
            'title'   => 'Privacy Policy',
            'option'  => 'wp_page_for_privacy_policy',
            'content' => '<!-- wp:heading --><h2>Privacy Policy</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add your store privacy policy here.</p><!-- /wp:paragraph -->',
        ),
    );

    foreach ( $pages as $slug => $data ) {
        $page_id = absint( get_option( $data['option'] ) );
        $page    = $page_id ? get_post( $page_id ) : null;

        if ( $page && 'page' === $page->post_type && 'trash' !== $page->post_status ) {
            continue;
        }

        $existing = get_page_by_path( $slug );
        if ( $existing && 'trash' !== $existing->post_status ) {
            update_option( $data['option'], $existing->ID );
            continue;
        }

        $created_id = wp_insert_post( array(
            'post_title'   => $data['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => $data['content'],
        ) );

        if ( $created_id && ! is_wp_error( $created_id ) ) {
            update_option( $data['option'], $created_id );
        }
    }
}
add_action( 'init', 'dt_ensure_core_store_pages', 20 );
