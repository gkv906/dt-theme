<?php
/**
 * DT Ecommerce Theme Functions and Definitions
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load Modules
require_once get_template_directory() . '/inc/activation.php';
require_once get_template_directory() . '/inc/setup-wizard.php';
require_once get_template_directory() . '/inc/theme-options.php';
require_once get_template_directory() . '/inc/roles.php';
require_once get_template_directory() . '/inc/role-pricing.php';
require_once get_template_directory() . '/inc/wishlist.php';
require_once get_template_directory() . '/inc/ajax-search.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/popups.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/typography.php';
require_once get_template_directory() . '/setup/demo-import.php';

// Theme Setup
function dt_theme_setup() {
    // Add theme support
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'
    ) );
    
    // Add WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register Menu locations
    register_nav_menus( array(
        'header-menu'  => esc_html__( 'Header Menu', 'dt-ecommerce-theme' ),
        'mobile-menu'  => esc_html__( 'Mobile Menu', 'dt-ecommerce-theme' ),
        'footer-menu'  => esc_html__( 'Footer Menu', 'dt-ecommerce-theme' ),
        'account-menu' => esc_html__( 'Account Menu', 'dt-ecommerce-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'dt_theme_setup' );

// Enqueue styles and scripts
function dt_enqueue_scripts() {
    // Stylesheet of theme
    wp_enqueue_style( 'dt-theme-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
    
    // Custom copied styles
    wp_enqueue_style( 'dt-custom-css', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime( get_template_directory() . '/assets/css/style.css' ) );
    
    // Custom options custom CSS
    $custom_css = dt_get_theme_option( 'custom_css' );
    if ( ! empty( $custom_css ) ) {
        wp_add_inline_style( 'dt-custom-css', $custom_css );
    }

    // Google Fonts
    wp_enqueue_style( 'dt-google-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap', array(), null );

    // Tailwind CSS Play CDN
    wp_enqueue_script( 'dt-tailwind-cdn', 'https://cdn.tailwindcss.com', array(), null, false );

    // Tailwind config (must come after CDN)
    $tailwind_config = "tailwind.config = {
        theme: {
            extend: {
                colors: { gold: '#C8A46A' },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    serif: ['Cormorant Garamond', 'serif']
                }
            }
        }
    };";
    wp_add_inline_script( 'dt-tailwind-cdn', $tailwind_config, 'after' );

    // Lucide Icons
    wp_enqueue_script( 'dt-lucide-icons', 'https://unpkg.com/lucide@latest', array(), null, false );

    // Custom JS file
    wp_enqueue_script( 'dt-theme-main', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime( get_template_directory() . '/assets/js/main.js' ), true );

    // Localize Script for AJAX values + WordPress page URLs
    $shop_id    = class_exists( 'WooCommerce' ) ? (int) wc_get_page_id( 'shop' )      : 0;
    $account_id = class_exists( 'WooCommerce' ) ? (int) get_option( 'woocommerce_myaccount_page_id' ) : 0;
    $cart_count = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
    wp_localize_script( 'dt-theme-main', 'dt_theme_vars', array(
        'ajax_url'          => admin_url( 'admin-ajax.php' ),
        'add_to_cart_url'   => esc_url( add_query_arg( 'wc-ajax', 'add_to_cart', home_url( '/' ) ) ),
        'wishlist_nonce'    => wp_create_nonce( 'dt_wishlist_nonce' ),
        'search_nonce'      => wp_create_nonce( 'dt_search_nonce' ),
        'cart_count'        => absint( $cart_count ),
        'wishlist_items'    => function_exists( 'dt_get_wishlist' ) ? array_map( 'absint', dt_get_wishlist() ) : array(),
        'wishlist_count'    => function_exists( 'dt_get_wishlist_count' ) ? absint( dt_get_wishlist_count() ) : 0,
        'home_url'          => esc_url( home_url( '/' ) ),
        'images_url'        => esc_url( get_template_directory_uri() . '/assets/images' ),
        'shop_url'          => esc_url( $shop_id    ? get_permalink( $shop_id )    : home_url( '/shop/' ) ),
        'cart_url'          => esc_url( class_exists( 'WooCommerce' ) ? wc_get_cart_url()               : home_url( '/cart/' ) ),
        'checkout_url'      => esc_url( class_exists( 'WooCommerce' ) ? wc_get_checkout_url()           : home_url( '/checkout/' ) ),
        'account_url'       => esc_url( $account_id ? get_permalink( $account_id ) : home_url( '/my-account/' ) ),
        'wishlist_page_url' => esc_url( home_url( '/wishlist/' ) ),
        'track_url'         => esc_url( home_url( '/track-order/' ) ),
        'about_url'         => esc_url( home_url( '/about-us/' ) ),
        'story_url'         => esc_url( home_url( '/our-story/' ) ),
        'faq_url'           => esc_url( home_url( '/faq/' ) ),
        'shipping_url'      => esc_url( home_url( '/shipping-policy/' ) ),
        'contact_url'       => esc_url( home_url( '/contact-us/' ) ),
    ) );

    // Enqueue inline custom JS
    $custom_js = dt_get_theme_option( 'custom_js' );
    if ( ! empty( $custom_js ) ) {
        wp_add_inline_script( 'dt-theme-main', $custom_js );
    }
}
add_action( 'wp_enqueue_scripts', 'dt_enqueue_scripts' );

// ── Enqueue Admin Scripts & Styles ──────────────────────────────────────────
function dt_enqueue_admin_scripts( string $hook ): void {
    // Only load on DT admin pages
    $screen = get_current_screen();
    if ( ! $screen ) return;
    $is_dt_page = (
        strpos( $hook, 'dt-theme' ) !== false ||
        strpos( $hook, 'dt_theme' ) !== false ||
        ( isset( $_GET['page'] ) && strpos( sanitize_key( $_GET['page'] ), 'dt-theme' ) !== false )
    );
    if ( ! $is_dt_page ) return;

    // Google fonts for admin
    wp_enqueue_style( 'dt-admin-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cormorant+Garamond:wght@400;600;700&display=swap', array(), null );

    // Color picker
    wp_enqueue_style( 'wp-color-picker' );

    // Admin CSS
    wp_enqueue_style(
        'dt-admin-style',
        get_template_directory_uri() . '/admin/admin.css',
        array( 'wp-color-picker' ),
        filemtime( get_template_directory() . '/admin/admin.css' )
    );

    // WP media uploader
    wp_enqueue_media();

    // Code editor (for custom CSS/JS fields)
    if ( function_exists( 'wp_enqueue_code_editor' ) ) {
        wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
    }

    // Admin JS
    wp_enqueue_script(
        'dt-admin-js',
        get_template_directory_uri() . '/admin/admin.js',
        array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable', 'media-upload' ),
        filemtime( get_template_directory() . '/admin/admin.js' ),
        true
    );

    // Lucide icons in admin
    wp_enqueue_script( 'dt-lucide-admin', 'https://unpkg.com/lucide@latest', array(), null, true );

    // Localize vars for admin JS
    wp_localize_script( 'dt-admin-js', 'dtAdminVars', array(
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'dt_admin_nonce' ),
        'adminUrl'   => admin_url(),
        'mediaTitle' => esc_html__( 'Select Image', 'dt-ecommerce-theme' ),
        'savedText'  => esc_html__( 'Settings saved!', 'dt-ecommerce-theme' ),
        'savingText' => esc_html__( 'Saving...', 'dt-ecommerce-theme' ),
    ) );
}
add_action( 'admin_enqueue_scripts', 'dt_enqueue_admin_scripts' );

// Register Widget areas
function dt_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar Widgets', 'dt-ecommerce-theme' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'dt-ecommerce-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-8 bg-[#111] p-6 border border-[#C8A46A]/20">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title font-serif text-lg text-[#C8A46A] uppercase mb-4 border-b border-[#C8A46A]/20 pb-2">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets', 'dt-ecommerce-theme' ),
        'id'            => 'footer-widgets',
        'description'   => esc_html__( 'Add widgets here for the footer columns.', 'dt-ecommerce-theme' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="font-serif text-lg text-white uppercase mb-6 tracking-wider">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'dt_widgets_init' );

/**
 * Customize WooCommerce loop products per page count
 */
function dt_loop_shop_per_page( $cols ) {
    $limit = intval( dt_get_theme_option( 'shop_products_per_page', '12' ) );
    return $limit > 0 ? $limit : 12;
}
add_filter( 'loop_shop_per_page', 'dt_loop_shop_per_page', 99 );

/**
 * Send "Buy Now" product submissions straight to checkout after WooCommerce
 * finishes adding the selected product or variation to the cart.
 *
 * @param string $url Default redirect URL.
 * @return string
 */
function dt_buy_now_redirect_to_checkout( $url ) {
    if ( isset( $_REQUEST['dt_buy_now'] ) && '1' === sanitize_text_field( wp_unslash( $_REQUEST['dt_buy_now'] ) ) && class_exists( 'WooCommerce' ) ) {
        return wc_get_checkout_url();
    }

    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'dt_buy_now_redirect_to_checkout' );

/**
 * Match WooCommerce single product button copy to the theme language.
 *
 * @return string
 */
function dt_single_add_to_cart_text() {
    return __( 'Add to Bag', 'dt-ecommerce-theme' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'dt_single_add_to_cart_text' );

/**
 * Auto-create required theme pages if they don't exist.
 * Ensures Wishlist, Track Order, Contact Us, About Us etc. are always available.
 */
function dt_ensure_required_pages() {
    $required_pages = array(
        array(
            'title'    => 'Wishlist',
            'slug'     => 'wishlist',
            'template' => 'templates/template-wishlist.php',
        ),
        array(
            'title'    => 'Track Order',
            'slug'     => 'track-order',
            'template' => 'templates/template-track-order.php',
        ),
        array(
            'title'    => 'Contact Us',
            'slug'     => 'contact-us',
            'template' => 'templates/template-contact.php',
        ),
        array(
            'title'    => 'About Us',
            'slug'     => 'about-us',
            'template' => 'templates/template-about.php',
        ),
        array(
            'title'    => 'Shipping Policy',
            'slug'     => 'shipping-policy',
            'template' => 'templates/template-shipping-policy.php',
        ),
        array(
            'title'    => 'FAQ',
            'slug'     => 'faq',
            'template' => 'templates/template-faq.php',
        ),
    );

    foreach ( $required_pages as $page_data ) {
        $existing = get_page_by_path( $page_data['slug'] );
        if ( ! $existing ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_data['title'],
                'post_name'    => $page_data['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ) );
            if ( $page_id && ! is_wp_error( $page_id ) && ! empty( $page_data['template'] ) ) {
                update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
            }
        } elseif ( ! empty( $page_data['template'] ) ) {
            // Ensure template is set even if page already exists
            $current_template = get_post_meta( $existing->ID, '_wp_page_template', true );
            if ( empty( $current_template ) || $current_template === 'default' ) {
                update_post_meta( $existing->ID, '_wp_page_template', $page_data['template'] );
            }
        }
    }
}
add_action( 'init', 'dt_ensure_required_pages' );

// ─────────────────────────────────────────────────────────────────────────────
// CUSTOM ADDRESS SAVE HANDLER
// WooCommerce's built-in save_address silently fails on some hosts due to
// caching (stale nonces) or endpoint detection issues. This custom handler
// uses its own nonce and directly writes to user meta, guaranteeing saves.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Prevent the edit-address endpoint pages from being cached (stale nonces).
 */
add_action( 'template_redirect', function() {
    if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'edit-address' ) ) {
        wc_nocache_headers();
        header( 'Pragma: no-cache' );
        header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    }
} );

/**
 * Custom address save — fires on wp hook (after WordPress & WooCommerce are loaded).
 * Triggered when the custom form field dt_save_address is present in POST.
 */
// Run earlier on 'wp_loaded' instead of 'wp' to ensure we capture POST before any potential WC redirects
add_action( 'wp_loaded', 'dt_save_customer_address' );

function dt_save_customer_address() {
    // Only fire on a POST request with our custom flag
    if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
        return;
    }
    if ( empty( $_POST['dt_save_address'] ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        wc_add_notice( __( 'You must be logged in to save an address.', 'dt-ecommerce-theme' ), 'error' );
        return;
    }

    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        return;
    }

    // Verify nonce
    $nonce          = isset( $_POST['dt_address_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dt_address_nonce'] ) ) : '';
    $nonce_verified = wp_verify_nonce( $nonce, 'dt_save_address_' . $user_id );

    if ( ! $nonce_verified ) {
        wc_add_notice( __( 'Security check failed. Please refresh and try again.', 'dt-ecommerce-theme' ), 'error' );
        return;
    }

    // Determine address type (billing or shipping)
    $type = sanitize_key( get_query_var( 'edit-address' ) );
    if ( empty( $type ) || ! in_array( $type, array( 'billing', 'shipping' ), true ) ) {
        $type = isset( $_POST['dt_address_type'] ) ? sanitize_key( $_POST['dt_address_type'] ) : '';
    }
    
    // Fallback: If address type is still empty, scan POST keys to see if we can infer it
    if ( empty( $type ) ) {
        $type = 'billing'; // default
        foreach ( array_keys($_POST) as $p_key ) {
            if ( strpos($p_key, 'billing_') === 0 ) {
                $type = 'billing';
                break;
            }
            if ( strpos($p_key, 'shipping_') === 0 ) {
                $type = 'shipping';
                break;
            }
        }
        // Also look at field counts (shipping doesn't submit email)
        if ( ! isset( $_POST['_email'] ) && ! isset( $_POST['billing_email'] ) && ! isset( $_POST['email'] ) ) {
            $type = 'shipping';
        }
    }
    
    if ( ! in_array( $type, array( 'billing', 'shipping' ), true ) ) {
        $type = 'billing';
    }
    // Get the submitted country (to generate correct field list for that country)
    $country_key       = $type . '_country';
    $short_country_key = '_country';
    $country           = isset( $_POST[ $country_key ] ) ? $_POST[ $country_key ] : ( isset( $_POST[ $short_country_key ] ) ? $_POST[ $short_country_key ] : ( isset( $_POST['country'] ) ? $_POST['country'] : '' ) );
    $country           = wc_clean( wp_unslash( $country ) );
    if ( empty( $country ) ) {
        $country = WC()->countries->get_base_country();
    }

    // Get WooCommerce address field definitions for this country + type
    $address_fields = WC()->countries->get_address_fields( $country, $type . '_' );

    $errors = array();

    foreach ( $address_fields as $key => $field ) {
        $field = wp_parse_args( $field, array(
            'required' => false,
            'type'     => 'text',
            'label'    => '',
        ) );

        $short_key      = str_replace( $type . '_', '', $key );
        $underscore_key = '_' . $short_key;

        // Retrieve submitted value from post with fallback
        if ( isset( $_POST[ $key ] ) ) {
            $raw_val = $_POST[ $key ];
        } elseif ( isset( $_POST[ $underscore_key ] ) ) {
            $raw_val = $_POST[ $underscore_key ];
        } elseif ( isset( $_POST[ $short_key ] ) ) {
            $raw_val = $_POST[ $short_key ];
        } else {
            $raw_val = 'checkbox' === $field['type'] ? '0' : '';
        }

        if ( 'checkbox' === $field['type'] ) {
            $value = ( '1' === $raw_val || 'yes' === $raw_val || true === $raw_val ) ? '1' : '0';
        } else {
            $value = wc_clean( wp_unslash( $raw_val ) );
        }

        // Validate required
        if ( ! empty( $field['required'] ) && '' === $value ) {
            /* translators: %s: field label */
            $errors[] = sprintf( __( '%s is a required field.', 'dt-ecommerce-theme' ), '<strong>' . esc_html( $field['label'] ) . '</strong>' );
        }
    }

    // If there are validation errors, show them and stop
    if ( ! empty( $errors ) ) {
        foreach ( $errors as $error ) {
            wc_add_notice( $error, 'error' );
        }
        return;
    }

    // ── Save to user meta (WooCommerce standard keys) ──────────────────────
    foreach ( $address_fields as $key => $field ) {
        $short_key      = str_replace( $type . '_', '', $key );
        $underscore_key = '_' . $short_key;

        if ( isset( $_POST[ $key ] ) ) {
            $raw_val = $_POST[ $key ];
        } elseif ( isset( $_POST[ $underscore_key ] ) ) {
            $raw_val = $_POST[ $underscore_key ];
        } elseif ( isset( $_POST[ $short_key ] ) ) {
            $raw_val = $_POST[ $short_key ];
        } else {
            $raw_val = 'checkbox' === ( $field['type'] ?? 'text' ) ? '0' : '';
        }

        if ( 'checkbox' === ( $field['type'] ?? 'text' ) ) {
            $value = ( '1' === $raw_val || 'yes' === $raw_val || true === $raw_val ) ? '1' : '0';
        } else {
            $value = wc_clean( wp_unslash( $raw_val ) );
        }
        
        update_user_meta( $user_id, $key, $value );
    }

    // ── Also update WooCommerce customer object (updates wc_customer_lookup table) ──
    $customer = new WC_Customer( $user_id );
    foreach ( $address_fields as $key => $field ) {
        $short_key      = str_replace( $type . '_', '', $key );
        $underscore_key = '_' . $short_key;

        if ( isset( $_POST[ $key ] ) ) {
            $raw_val = $_POST[ $key ];
        } elseif ( isset( $_POST[ $underscore_key ] ) ) {
            $raw_val = $_POST[ $underscore_key ];
        } elseif ( isset( $_POST[ $short_key ] ) ) {
            $raw_val = $_POST[ $short_key ];
        } else {
            $raw_val = '';
        }
        $value = wc_clean( wp_unslash( $raw_val ) );

        $setter = 'set_' . $key; // e.g. set_billing_first_name
        if ( is_callable( array( $customer, $setter ) ) ) {
            $customer->$setter( $value );
        }
    }
    $customer->save();

    // ── Fire WooCommerce hook so other plugins know address was saved ────────
    do_action( 'woocommerce_customer_save_address', $user_id, $type );

    $redirect = wc_get_endpoint_url( 'edit-address', $type, wc_get_page_permalink( 'myaccount' ) );

    // ── Redirect with success notice ────────────────────────────────────────
    wc_add_notice( __( 'Address saved successfully.', 'dt-ecommerce-theme' ) );
    wp_safe_redirect( $redirect );
    exit;
}

// ── Hide Downloads Tab from My Account (Store is for physical products only) ──
add_filter( 'woocommerce_account_menu_items', function( $items ) {
    if ( isset( $items['downloads'] ) ) {
        unset( $items['downloads'] );
    }
    return $items;
} );

// ── Output dynamic CSS variables from saved Colors options ──────────────────
function dt_output_color_css_variables(): void {
    $primary        = dt_get_theme_option( 'color_primary',          '#C8A46A' );
    $primary_dark   = dt_get_theme_option( 'color_primary_dark',     '#b08d55' );
    $primary_light  = dt_get_theme_option( 'color_primary_light',    '#d8ba82' );
    $bg_main        = dt_get_theme_option( 'color_bg_main',          '#000000' );
    $bg_card        = dt_get_theme_option( 'color_bg_card',          '#111111' );
    $bg_header      = dt_get_theme_option( 'color_bg_header',        '#000000' );
    $bg_footer      = dt_get_theme_option( 'color_bg_footer',        '#000000' );
    $text_primary   = dt_get_theme_option( 'color_text_primary',     '#F7F4EE' );
    $text_secondary = dt_get_theme_option( 'color_text_secondary',   '#a3a3a3' );
    $text_heading   = dt_get_theme_option( 'color_text_heading',     '#FFFFFF' );
    $btn_bg         = dt_get_theme_option( 'color_btn_bg',           '#C8A46A' );
    $btn_text       = dt_get_theme_option( 'color_btn_text',         '#000000' );
    $btn_radius     = dt_get_theme_option( 'btn_border_radius',      '0' );
    $ann_bg         = dt_get_theme_option( 'color_announcement_bg',  '' );
    $ann_text       = dt_get_theme_option( 'color_announcement_text','#F7F4EE' );

    $css = "<style id='dt-color-variables'>\n:root {\n";
    $css .= "  --dt-gold: " . esc_attr( $primary ) . ";\n";
    $css .= "  --dt-gold-dark: " . esc_attr( $primary_dark ) . ";\n";
    $css .= "  --dt-gold-light: " . esc_attr( $primary_light ) . ";\n";
    $css .= "  --dt-bg: " . esc_attr( $bg_main ) . ";\n";
    $css .= "  --dt-bg-card: " . esc_attr( $bg_card ) . ";\n";
    $css .= "  --dt-text: " . esc_attr( $text_primary ) . ";\n";
    $css .= "  --dt-text-muted: " . esc_attr( $text_secondary ) . ";\n";
    $css .= "}\n";
    if ( $bg_header !== '#000000' && $bg_header !== '' ) {
        $css .= "header, .site-header, #site-header { background-color: " . esc_attr( $bg_header ) . " !important; }\n";
    }
    if ( $bg_footer !== '#000000' && $bg_footer !== '' ) {
        $css .= ".footer-luxury, footer { background-color: " . esc_attr( $bg_footer ) . " !important; }\n";
    }
    if ( $bg_main !== '#000000' && $bg_main !== '' ) {
        $css .= "body { background-color: " . esc_attr( $bg_main ) . " !important; }\n";
    }
    if ( $text_primary !== '#F7F4EE' && $text_primary !== '' ) {
        $css .= "body, p { color: " . esc_attr( $text_primary ) . "; }\n";
    }
    if ( $text_heading !== '#FFFFFF' && $text_heading !== '' ) {
        $css .= "h1,h2,h3,h4,h5,h6,.font-serif { color: " . esc_attr( $text_heading ) . "; }\n";
    }
    if ( $btn_radius !== '0' && $btn_radius !== '' ) {
        $css .= ".btn-premium-cart, .dt-btn { border-radius: " . esc_attr( $btn_radius ) . "; }\n";
    }
    if ( ! empty( $ann_bg ) ) {
        $css .= ".announcement-bar, .dt-announcement { background-color: " . esc_attr( $ann_bg ) . " !important; color: " . esc_attr( $ann_text ) . " !important; }\n";
    }
    $css .= "</style>\n";
    echo $css;
}
add_action( 'wp_head', 'dt_output_color_css_variables', 5 );

// ── Analytics & Tracking Injection ─────────────────────────────────────────
function dt_output_analytics_scripts(): void {
    // Google Analytics 4
    if ( dt_get_theme_option( 'ga_enabled', '0' ) === '1' ) {
        $ga_id = dt_get_theme_option( 'ga_measurement_id', '' );
        if ( ! empty( $ga_id ) ) {
            $exclude_admin = dt_get_theme_option( 'ga_exclude_admin', '0' ) === '1' && current_user_can( 'manage_options' );
            if ( ! $exclude_admin ) {
                echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $ga_id ) . '"></script>' . "\n";
                echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js( $ga_id ) . "');</script>\n";
            }
        }
    }
    // Google Tag Manager
    if ( dt_get_theme_option( 'gtm_enabled', '0' ) === '1' ) {
        $gtm_id = dt_get_theme_option( 'gtm_container_id', '' );
        if ( ! empty( $gtm_id ) ) {
            echo "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . esc_js( $gtm_id ) . "');\n";
        }
    }
    // Facebook Pixel
    if ( dt_get_theme_option( 'fb_pixel_enabled', '0' ) === '1' ) {
        $pixel_id = dt_get_theme_option( 'fb_pixel_id', '' );
        if ( ! empty( $pixel_id ) ) {
            echo "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" . esc_js( $pixel_id ) . "');fbq('track','PageView');\n";
        }
    }
    // Google Site Verification
    $gv = dt_get_theme_option( 'google_site_verification', '' );
    if ( ! empty( $gv ) ) {
        echo '<meta name="google-site-verification" content="' . esc_attr( $gv ) . '">' . "\n";
    }
    $bv = dt_get_theme_option( 'bing_site_verification', '' );
    if ( ! empty( $bv ) ) {
        echo '<meta name="msvalidate.01" content="' . esc_attr( $bv ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'dt_output_analytics_scripts', 99 );

// ── Lucide icons init in admin footer ────────────────────────────────────────
function dt_admin_footer_scripts(): void {
    $screen = get_current_screen();
    if ( ! $screen ) return;
    $page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
    if ( strpos( $page, 'dt-theme' ) === false ) return;
    echo '<script>if(typeof lucide !== "undefined"){ lucide.createIcons(); }</script>' . "\n";
}
add_action( 'admin_footer', 'dt_admin_footer_scripts' );

