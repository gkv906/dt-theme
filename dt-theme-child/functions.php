<?php
/**
 * DT Ecommerce Theme — Child Theme Functions
 *
 * This file intentionally minimal. All theme logic lives in the parent.
 * Add site-specific PHP customisations below the enqueue function.
 *
 * @package DT_Ecommerce_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent stylesheet first, then child stylesheet.
 * Uses filemtime() versioning so LiteSpeed Cache busts stale CSS automatically.
 */
add_action( 'wp_enqueue_scripts', function () {
    $parent_style = 'dt-theme-style';

    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( get_template() )->get( 'Version' )
    );

    wp_enqueue_style(
        'dt-child-style',
        get_stylesheet_uri(),
        array( $parent_style ),
        wp_get_theme()->get( 'Version' )
    );
}, 10 );

// ── Site-specific customisations go below this line ──────────────────────────
// Example: override a parent filter, register a shortcode, etc.
// Do NOT copy parent theme functions here — extend or override them instead.
