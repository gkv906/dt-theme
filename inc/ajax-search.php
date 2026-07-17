<?php
/**
 * AJAX Search Suggestions Module
 *
 * Searches WooCommerce products by title, SKU, and fabric category.
 * Runs two separate WP_Query calls (title search + SKU search) and merges
 * the results — combining them in a single query would create an AND join,
 * meaning a product would need to match both at once.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dt_ajax_search_handler() {
	$search_term = isset( $_GET['term'] )     ? sanitize_text_field( wp_unslash( $_GET['term'] ) )     : '';
	$category    = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : 'all';

	if ( empty( $search_term ) ) {
		wp_send_json_success( array() );
		return;
	}

	$common_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'fields'         => 'ids',
	);

	// Optional category filter
	if ( 'all' !== $category && '' !== $category ) {
		$common_args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}

	// ── Query 1: title / description text search ──────────────────────
	$title_args   = array_merge( $common_args, array( 's' => $search_term ) );
	$title_query  = new WP_Query( $title_args );
	$title_ids    = $title_query->posts;   // already just IDs
	wp_reset_postdata();

	// ── Query 2: SKU search ───────────────────────────────────────────
	$sku_args = array_merge( $common_args, array(
		'meta_query' => array(
			array(
				'key'     => '_sku',
				'value'   => $search_term,
				'compare' => 'LIKE',
			),
		),
	) );
	$sku_query = new WP_Query( $sku_args );
	$sku_ids   = $sku_query->posts;
	wp_reset_postdata();

	// ── Merge & deduplicate, cap at 10 ───────────────────────────────
	$merged_ids = array_unique( array_merge( $title_ids, $sku_ids ) );
	$merged_ids = array_slice( $merged_ids, 0, 10 );

	if ( empty( $merged_ids ) ) {
		wp_send_json_success( array() );
		return;
	}

	// ── Build result objects ──────────────────────────────────────────
	$results = array();

	foreach ( $merged_ids as $post_id ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			break;
		}
		$wc_product = wc_get_product( $post_id );
		if ( ! $wc_product ) {
			continue;
		}

		$img_url = get_the_post_thumbnail_url( $post_id, 'woocommerce_thumbnail' );
		if ( ! $img_url ) {
			$img_url = wc_placeholder_img_src();
		}

		// Fabric / category names
		$fabrics = array();
		$terms   = get_the_terms( $post_id, 'product_cat' );
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				// Skip the "Uncategorized" default WC category
				if ( 'uncategorized' !== $term->slug ) {
					$fabrics[] = $term->name;
				}
			}
		}

		$results[] = array(
			'id'         => $post_id,
			'title'      => get_the_title( $post_id ),
			'price_html' => $wc_product->get_price_html(),
			'price'      => $wc_product->get_price(),
			'mrp'        => $wc_product->get_regular_price(),
			'img'        => $img_url,
			'url'        => get_permalink( $post_id ),
			'sku'        => $wc_product->get_sku(),
			'fabric'     => implode( ', ', $fabrics ),
		);
	}

	wp_send_json_success( $results );
}

add_action( 'wp_ajax_dt_ajax_search',        'dt_ajax_search_handler' );
add_action( 'wp_ajax_nopriv_dt_ajax_search', 'dt_ajax_search_handler' );
