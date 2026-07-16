<?php
/**
 * Wishlist System Module
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Helper to get current user/guest wishlist
function dt_get_wishlist() {
    if ( is_user_logged_in() ) {
        $wishlist = get_user_meta( get_current_user_id(), '_dt_wishlist', true );
        return is_array( $wishlist ) ? $wishlist : array();
    } else {
        $cookie_name = 'dt_wishlist';
        if ( isset( $_COOKIE[$cookie_name] ) ) {
            $wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ), true );
            return is_array( $wishlist ) ? array_map( 'absint', $wishlist ) : array();
        }
    }
    return array();
}

// Helper to get wishlist count
function dt_get_wishlist_count() {
    return count( dt_get_wishlist() );
}

// AJAX: Add to Wishlist
function dt_ajax_add_to_wishlist() {
    check_ajax_referer( 'dt_wishlist_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( wp_unslash( $_POST['product_id'] ) ) : 0;
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'Invalid product ID' ) );
    }

    $wishlist = dt_get_wishlist();
    if ( ! in_array( $product_id, $wishlist ) ) {
        $wishlist[] = $product_id;
    }

    if ( is_user_logged_in() ) {
        update_user_meta( get_current_user_id(), '_dt_wishlist', $wishlist );
    } else {
        setcookie( 'dt_wishlist', wp_json_encode( array_map( 'absint', $wishlist ) ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    }

    wp_send_json_success( array(
        'count' => count( $wishlist ),
        'items' => $wishlist,
        'message' => __( 'Product added to wishlist', 'dt-ecommerce-theme' )
    ) );
}
add_action( 'wp_ajax_dt_add_to_wishlist', 'dt_ajax_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_dt_add_to_wishlist', 'dt_ajax_add_to_wishlist' );

// AJAX: Remove from Wishlist
function dt_ajax_remove_from_wishlist() {
    check_ajax_referer( 'dt_wishlist_nonce', 'nonce' );

    $product_id = isset( $_POST['product_id'] ) ? intval( wp_unslash( $_POST['product_id'] ) ) : 0;
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => 'Invalid product ID' ) );
    }

    $wishlist = dt_get_wishlist();
    $wishlist = array_values( array_diff( $wishlist, array( $product_id ) ) );

    if ( is_user_logged_in() ) {
        update_user_meta( get_current_user_id(), '_dt_wishlist', $wishlist );
    } else {
        setcookie( 'dt_wishlist', wp_json_encode( array_map( 'absint', $wishlist ) ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    }

    wp_send_json_success( array(
        'count' => count( $wishlist ),
        'items' => $wishlist,
        'message' => __( 'Product removed from wishlist', 'dt-ecommerce-theme' )
    ) );
}
add_action( 'wp_ajax_dt_remove_from_wishlist', 'dt_ajax_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_dt_remove_from_wishlist', 'dt_ajax_remove_from_wishlist' );
