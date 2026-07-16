<?php
/**
 * Role-Based Pricing Module
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Register Custom User Roles on Theme Activation/Initialization
function dt_register_custom_user_roles() {
    $roles = array(
        'reseller' => array(
            'name' => __( 'Reseller', 'dt-ecommerce-theme' ),
            'capabilities' => array(
                'read' => true,
            ),
        ),
        'retailer' => array(
            'name' => __( 'Retailer', 'dt-ecommerce-theme' ),
            'capabilities' => array(
                'read' => true,
            ),
        ),
        'wholesaler' => array(
            'name' => __( 'Wholesaler', 'dt-ecommerce-theme' ),
            'capabilities' => array(
                'read' => true,
            ),
        ),
    );

    foreach ( $roles as $role_key => $role_data ) {
        if ( ! get_role( $role_key ) ) {
            add_role( $role_key, $role_data['name'], $role_data['capabilities'] );
        }
    }
}
add_action( 'init', 'dt_register_custom_user_roles' );

// 2. Add Role Price Fields to WooCommerce Product Editor
function dt_add_role_price_fields() {
    global $post;
    
    echo '<div class="options_group show_if_simple show_if_external">';
    
    woocommerce_wp_text_input( array(
        'id'          => '_customer_price',
        'label'       => __( 'Customer Price (₹)', 'dt-ecommerce-theme' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => __( 'Custom pricing for customers', 'dt-ecommerce-theme' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_reseller_price',
        'label'       => __( 'Reseller Price (₹)', 'dt-ecommerce-theme' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => __( 'Custom pricing for resellers', 'dt-ecommerce-theme' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_retailer_price',
        'label'       => __( 'Retailer Price (₹)', 'dt-ecommerce-theme' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => __( 'Custom pricing for retailers', 'dt-ecommerce-theme' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_wholesaler_price',
        'label'       => __( 'Wholesaler Price (₹)', 'dt-ecommerce-theme' ),
        'placeholder' => '',
        'desc_tip'    => 'true',
        'description' => __( 'Custom pricing for wholesalers', 'dt-ecommerce-theme' ),
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );

    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'dt_add_role_price_fields' );

// 3. Save Role Price Fields values
function dt_save_role_price_fields( int $post_id ): void {
    $fields = array( '_customer_price', '_reseller_price', '_retailer_price', '_wholesaler_price' );
    foreach ( $fields as $field ) {
        $val = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
        update_post_meta( $post_id, $field, $val );
    }
}
add_action( 'woocommerce_process_product_meta', 'dt_save_role_price_fields' );

function dt_ajax_save_role_prices(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $post_id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;
    $prices  = isset( $_POST['prices'] ) && is_array( $_POST['prices'] ) ? wp_unslash( $_POST['prices'] ) : array();

    if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
        wp_send_json_error( 'Invalid product.' );
    }

    $field_map = array(
        'customer'   => '_customer_price',
        'reseller'   => '_reseller_price',
        'retailer'   => '_retailer_price',
        'wholesaler' => '_wholesaler_price',
    );

    foreach ( $field_map as $role => $meta_key ) {
        if ( isset( $prices[ $role ] ) ) {
            $price = function_exists( 'wc_format_decimal' )
                ? wc_format_decimal( $prices[ $role ] )
                : preg_replace( '/[^0-9.]/', '', (string) $prices[ $role ] );
            update_post_meta( $post_id, $meta_key, $price );
        }
    }

    wp_send_json_success( array( 'message' => __( 'Role prices updated.', 'dt-ecommerce-theme' ) ) );
}
add_action( 'wp_ajax_dt_save_role_prices', 'dt_ajax_save_role_prices' );

// Helper function to fetch user's WooCommerce role price
function dt_get_product_role_price( WC_Product $product, ?int $user_id = null ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( ! $user_id ) {
        return ''; // Guest users get regular price
    }

    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return '';
    }

    $roles = (array) $user->roles;
    $role = reset( $roles ); // Get primary user role

    $meta_key = '';
    switch ( $role ) {
        case 'administrator':
        case 'customer':
        case 'dt_customer':
            $meta_key = '_customer_price';
            break;
        case 'reseller':
        case 'dt_reseller':
            $meta_key = '_reseller_price';
            break;
        case 'retailer':
        case 'dt_retailer':
            $meta_key = '_retailer_price';
            break;
        case 'wholesaler':
        case 'dt_wholesaler':
            $meta_key = '_wholesaler_price';
            break;
    }

    if ( $meta_key ) {
        $role_price = get_post_meta( $product->get_id(), $meta_key, true );
        if ( $role_price !== '' && $role_price !== false ) {
            return floatval( $role_price );
        }
    }

    return ''; // Fallback to normal price
}

// 4. Override WooCommerce prices dynamically in Frontend loop and pages
function dt_override_product_price( $price, WC_Product $product ) {
    if ( is_admin() && ! wp_doing_ajax() ) {
        return $price;
    }
    
    $role_price = dt_get_product_role_price( $product );
    if ( $role_price !== '' ) {
        return $role_price;
    }
    
    return $price;
}
add_filter( 'woocommerce_product_get_price', 'dt_override_product_price', 10, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'dt_override_product_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'dt_override_product_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_regular_price', 'dt_override_product_price', 10, 2 );

// 5. Override WooCommerce Cart Items Prices
function dt_override_cart_item_prices( WC_Cart $cart ): void {
    if ( is_admin() && ! wp_doing_ajax() ) {
        return;
    }

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }

    foreach ( $cart->get_cart() as $cart_item ) {
        $product = $cart_item['data'];
        $role_price = dt_get_product_role_price( $product );
        if ( $role_price !== '' ) {
            $product->set_price( $role_price );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'dt_override_cart_item_prices', 10, 1 );
