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
/**
 * Return the complete set of factory-default theme options.
 * Uses the EXACT same option keys that dt_get_theme_option() reads.
 */
function dt_get_default_theme_options(): array {
    return array(
        // ── General ──────────────────────────────────────────────────────────
        'site_width'               => '1280',
        'loader_enabled'           => '1',
        'announcement_text'        => 'Free Shipping on orders above ₹999 | Use code ARSHMAN10 for 10% off',
        'sale_banner_text'         => '',
        'logo_height'              => '40',

        // ── Header ────────────────────────────────────────────────────────────
        'sticky_header'            => '1',
        'header_transparent'       => '1',
        'header_top_bar'           => '1',
        'header_search'            => '1',
        'header_account'           => '1',
        'header_wishlist'          => '1',

        // ── Colors & Branding ────────────────────────────────────────────────
        'color_primary'            => '#C8A46A',
        'color_primary_dark'       => '#b08d55',
        'color_primary_light'      => '#d8ba82',
        'color_bg_main'            => '#000000',
        'color_bg_card'            => '#111111',
        'color_bg_header'          => '#000000',
        'color_bg_footer'          => '#000000',
        'color_text_primary'       => '#F7F4EE',
        'color_text_secondary'     => '#a3a3a3',
        'color_text_heading'       => '#FFFFFF',
        'color_btn_bg'             => '#C8A46A',
        'color_btn_text'           => '#000000',
        'btn_border_radius'        => '0',
        'color_announcement_bg'    => '#C8A46A',
        'color_announcement_text'  => '#000000',

        // ── Typography ───────────────────────────────────────────────────────
        'body_font_family'         => 'Inter',
        'body_font_size'           => '15px',
        'body_font_weight'         => '400',
        'headings_font_family'     => 'Cormorant Garamond',
        'headings_letter_spacing'  => '',
        'custom_font_url_woff2'    => '',
        'custom_font_family_name'  => '',

        // ── WooCommerce ───────────────────────────────────────────────────────
        'shop_products_per_page'   => '12',
        'hover_zoom_enabled'       => '1',
        'quick_view_enabled'       => '1',
        'show_payment_badges'      => '1',
        'show_social_icons'        => '1',

        // ── Popups ───────────────────────────────────────────────────────────
        'popup_newsletter_enabled' => '1',
        'popup_newsletter_delay'   => '5',
        'popup_title'              => 'Join the ARSHMAN Atelier',
        'popup_desc'               => 'Be the first to know about new collections, exclusive offers, and artisan stories.',
        'popup_cookie_days'        => '7',
        'popup_offer_enabled'      => '1',
        'popup_offer_delay'        => '8',
        'popup_offer_text'         => 'Get 10% OFF your first order!',
        'popup_offer_code'         => 'ARSHMAN10',
        'popup_exit_enabled'       => '1',
        'popup_exit_title'         => "Wait — Don't Leave Yet!",
        'popup_exit_code'          => 'ARSHMAN10',

        // ── Social & Contact ─────────────────────────────────────────────────
        'instagram_url'            => '',
        'facebook_url'             => '',
        'twitter_url'              => '',
        'youtube_url'              => '',
        'pinterest_url'            => '',
        'contact_email'            => '',
        'contact_phone'            => '',
        'contact_hours'            => 'Mon–Sat, 10:00am – 7:00pm IST',
        'whatsapp_url'             => '',
        'newsletter_enabled'       => '1',
        'newsletter_title'         => 'Join the Atelier',

        // ── SEO ──────────────────────────────────────────────────────────────
        'seo_site_title_suffix'    => '| Arshman Designs',
        'seo_meta_description'     => '',
        'seo_og_image'             => '',
        'google_site_verification' => '',
        'bing_site_verification'   => '',

        // ── Analytics & Tracking ─────────────────────────────────────────────
        'ga_enabled'               => '0',
        'ga_measurement_id'        => '',
        'ga_anonymize_ip'          => '0',
        'ga_exclude_admin'         => '0',
        'fb_pixel_enabled'         => '0',
        'fb_pixel_id'              => '',
        'fb_pixel_purchase'        => '0',
        'fb_pixel_view'            => '0',
        'fb_pixel_cart'            => '0',
        'gtm_enabled'              => '0',
        'gtm_container_id'         => '',

        // ── Performance ───────────────────────────────────────────────────────
        'lazy_load_images'         => '1',
        'remove_query_strings'     => '0',
        'remove_emoji_scripts'     => '0',
        'disable_gutenberg_css'    => '0',

        // ── Custom Code ───────────────────────────────────────────────────────
        'custom_css'               => '',
        'custom_js'                => '',

        // ── Footer ────────────────────────────────────────────────────────────
        'footer_copyright'         => '© ' . gmdate( 'Y' ) . ' ARSHMAN DESIGNS. All rights reserved.',
    );
}

/**
 * Set defaults on FIRST activation (only when no settings exist).
 */
function dt_set_default_theme_options(): void {
    $existing = get_option( 'dt_theme_options', array() );
    if ( ! empty( $existing ) ) {
        return; // Don't overwrite existing user settings on first boot
    }
    update_option( 'dt_theme_options', dt_get_default_theme_options() );
}

/**
 * Full factory reset — ALWAYS overwrites every setting with defaults.
 * Called by the admin "Reset to Factory Defaults" AJAX button.
 */
function dt_reset_theme_options(): void {
    update_option( 'dt_theme_options', dt_get_default_theme_options() );
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
