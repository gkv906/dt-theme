<?php
/**
 * Demo Content Importer
 *
 * Provides demo product/content import for the DT Ecommerce Theme.
 * Triggered by AJAX after activation wizard completes.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AJAX handler: run the full one-click setup.
 * Calls dt_run_one_click_setup() from setup-wizard.php, then imports demo products.
 */
function dt_ajax_run_setup_wizard(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Insufficient permissions.' );
    }

    // 1. Run page/menu setup
    dt_run_one_click_setup();

    // 2. Import demo products
    dt_import_demo_products();

    // 3. Set default theme options
    dt_set_default_theme_options();

    // 4. Mark setup complete
    update_option( 'dt_setup_completed', 'yes' );
    update_option( 'dt_demo_imported', 'yes' );

    wp_send_json_success( array( 'message' => 'Setup complete.' ) );
}
add_action( 'wp_ajax_dt_run_setup_wizard', 'dt_ajax_run_setup_wizard' );

/**
 * Import demo WooCommerce products (sarees).
 */
function dt_import_demo_products(): void {
    // Avoid re-importing
    if ( get_option( 'dt_demo_imported' ) === 'yes' ) {
        return;
    }

    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Ensure product_cat terms exist
    $categories = array(
        'Banarasi'    => 'Pure Silk Banarasi',
        'Kanjeevaram' => 'Kanjeevaram Silk',
        'Organza'     => 'Organza',
        'Bandhani'    => 'Bandhani',
        'Georgette'   => 'Georgette',
    );

    $cat_ids = array();
    foreach ( $categories as $slug => $name ) {
        $term = get_term_by( 'slug', sanitize_title( $slug ), 'product_cat' );
        if ( ! $term ) {
            $result = wp_insert_term( $name, 'product_cat', array( 'slug' => sanitize_title( $slug ) ) );
            $cat_ids[ $slug ] = ! is_wp_error( $result ) ? $result['term_id'] : 0;
        } else {
            $cat_ids[ $slug ] = $term->term_id;
        }
    }

    // Demo products
    $demo_products = array(
        array(
            'name'         => 'Midnight Onyx Banarasi',
            'description'  => 'A timeless masterwork displaying rich zari work and intricate textures. Exquisitely handcrafted from fine silk fibers, curated especially for royal Indian celebrations.',
            'price'        => 24999,
            'mrp'          => 35000,
            'category'     => 'Banarasi',
            'sku'          => 'DT-SRE-001',
        ),
        array(
            'name'         => 'Crimson Bridal Kanjeevaram',
            'description'  => 'A heritage bridal drape from the looms of Kanchipuram. Adorned with gold temple borders and rich silk brocade work.',
            'price'        => 32999,
            'mrp'          => 48000,
            'category'     => 'Kanjeevaram',
            'sku'          => 'DT-SRE-002',
        ),
        array(
            'name'         => 'Ivory Organza Dreams',
            'description'  => 'Light as a feather yet radiantly luxurious. This organza drape features delicate floral embroidery along its border.',
            'price'        => 18999,
            'mrp'          => 27000,
            'category'     => 'Organza',
            'sku'          => 'DT-SRE-003',
        ),
        array(
            'name'         => 'Royal Blue Bandhani',
            'description'  => 'A Rajasthani art form elevated to luxury — this hand-tied Bandhani in royal blue is a heritage piece of the highest order.',
            'price'        => 15999,
            'mrp'          => 22000,
            'category'     => 'Bandhani',
            'sku'          => 'DT-SRE-004',
        ),
        array(
            'name'         => 'Rose Gold Georgette',
            'description'  => 'An ultra-lightweight georgette drape dyed in blushing rose gold with thread embroidery — perfect for festive evenings.',
            'price'        => 12999,
            'mrp'          => 18000,
            'category'     => 'Georgette',
            'sku'          => 'DT-SRE-005',
        ),
        array(
            'name'         => 'Emerald Banarasi Silk',
            'description'  => 'Lush emerald green with intricate golden jaal work — a drape befitting a queen on any occasion.',
            'price'        => 28999,
            'mrp'          => 40000,
            'category'     => 'Banarasi',
            'sku'          => 'DT-SRE-006',
        ),
    );

    foreach ( $demo_products as $demo ) {
        // Skip if a product with this SKU already exists
        $existing_id = wc_get_product_id_by_sku( $demo['sku'] );
        if ( $existing_id ) {
            continue;
        }

        $product = new WC_Product_Simple();
        $product->set_name( $demo['name'] );
        $product->set_description( $demo['description'] );
        $product->set_regular_price( (string) $demo['mrp'] );
        $product->set_sale_price( (string) $demo['price'] );
        $product->set_sku( $demo['sku'] );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        $product->set_stock_status( 'instock' );
        $product->set_manage_stock( false );

        $cat_slug = $demo['category'];
        if ( isset( $cat_ids[ $cat_slug ] ) && $cat_ids[ $cat_slug ] ) {
            $product->set_category_ids( array( $cat_ids[ $cat_slug ] ) );
        }

        $product_id = $product->save();

        // Set role-based prices as meta
        if ( $product_id ) {
            update_post_meta( $product_id, '_reseller_price',   (string) round( $demo['price'] * 0.85 ) );
            update_post_meta( $product_id, '_retailer_price',   (string) round( $demo['price'] * 0.80 ) );
            update_post_meta( $product_id, '_wholesaler_price', (string) round( $demo['price'] * 0.70 ) );
        }
    }
}

/**
 * Set sensible default theme options on first setup.
 */
function dt_set_default_theme_options(): void {
    $existing = get_option( 'dt_theme_options', array() );

    if ( ! empty( $existing ) ) {
        return; // Don't overwrite user settings
    }

    $defaults = array(
        // General
        'site_width'               => '1280',
        'loader_enabled'           => '1',
        // Header
        'sticky_header'            => '1',
        'announcement_text'        => 'Free Shipping on orders above ₹999 | Use code ARSHMAN10 for 10% off',
        // Colors
        'primary_color'            => '#C8A46A',
        'bg_color'                 => '#000000',
        'text_color'               => '#F7F4EE',
        // Typography
        'heading_font'             => 'Cormorant Garamond',
        'body_font'                => 'Inter',
        // Popup
        'popup_newsletter_enabled' => '1',
        'popup_offer_enabled'      => '1',
        'popup_exit_enabled'       => '1',
        'popup_newsletter_delay'   => '5',
        'popup_offer_delay'        => '8',
        'popup_offer_code'         => 'ARSHMAN10',
        'popup_offer_text'         => 'Get 10% OFF your first order!',
        // Footer
        'footer_copyright'         => '© ' . gmdate( 'Y' ) . ' ARSHMAN DESIGNS. All rights reserved.',
        // Social
        'instagram_url'            => 'https://instagram.com/',
        'facebook_url'             => 'https://facebook.com/',
    );

    update_option( 'dt_theme_options', $defaults );
}

/**
 * Enqueue admin assets (CSS + JS) on theme admin pages.
 *
 * @param string $hook Current admin page hook.
 */
function dt_enqueue_admin_assets( string $hook ): void {
    // Load on all WP admin pages for the theme
    $theme_pages = array(
        'toplevel_page_dt-theme-options',
        'toplevel_page_dt-activation',
        'toplevel_page_dt-setup-wizard',
    );

    if ( in_array( $hook, $theme_pages, true ) || strpos( $hook, 'dt-' ) !== false ) {
        wp_enqueue_style(
            'dt-admin-css',
            get_template_directory_uri() . '/admin/admin.css',
            array(),
            '1.0'
        );

        wp_enqueue_script(
            'dt-admin-js',
            get_template_directory_uri() . '/admin/admin.js',
            array( 'jquery', 'wp-color-picker', 'media-upload' ),
            '1.0',
            true
        );

        wp_localize_script( 'dt-admin-js', 'dtAdminVars', array(
            'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
            'adminUrl'   => admin_url(),
            'nonce'      => wp_create_nonce( 'dt_admin_nonce' ),
            'mediaTitle' => __( 'Select Image', 'dt-ecommerce-theme' ),
            'savingText' => __( 'Saving…', 'dt-ecommerce-theme' ),
            'savedText'  => __( 'Settings saved!', 'dt-ecommerce-theme' ),
        ) );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'dt_enqueue_admin_assets' );
