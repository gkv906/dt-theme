<?php
/**
 * AJAX Search Suggestions Module
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function dt_ajax_search_handler() {
    $search_term = isset( $_GET['term'] ) ? sanitize_text_field( wp_unslash( $_GET['term'] ) ) : '';
    $category    = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : 'all';

    if ( empty( $search_term ) ) {
        wp_send_json_success( array() );
    }

    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        's'              => $search_term,
    );

    // If searching by SKU or other parameters, build meta queries
    if ( class_exists( 'WooCommerce' ) ) {
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key'     => '_sku',
                'value'   => $search_term,
                'compare' => 'LIKE',
            ),
        );
        $args['meta_query'] = $meta_query;
    }

    // Filter by fabric category
    if ( $category !== 'all' ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    $query = new WP_Query( $args );
    $results = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            
            if ( class_exists( 'WooCommerce' ) ) {
                $item_product = wc_get_product( get_the_ID() );
                $price_html   = $item_product ? $item_product->get_price_html() : '';
                $price        = $item_product ? $item_product->get_price() : 0;
                $regular_price = $item_product ? $item_product->get_regular_price() : 0;
                $img_url      = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
                if ( ! $img_url ) {
                    $img_url = wc_placeholder_img_src();
                }
                
                $fabrics = array();
                $terms = get_the_terms( get_the_ID(), 'product_cat' );
                if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $fabrics[] = $term->name;
                    }
                }

                $results[] = array(
                    'id'          => get_the_ID(),
                    'title'       => get_the_title(),
                    'price_html'  => $price_html,
                    'price'       => $price,
                    'mrp'         => $regular_price,
                    'img'         => $img_url,
                    'url'         => get_permalink(),
                    'sku'         => $item_product ? $item_product->get_sku() : '',
                    'fabric'      => implode( ', ', $fabrics ),
                );
            }
        }
        wp_reset_postdata();
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_dt_ajax_search', 'dt_ajax_search_handler' );
add_action( 'wp_ajax_nopriv_dt_ajax_search', 'dt_ajax_search_handler' );
