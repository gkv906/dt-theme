<?php
/**
 * Typography Module
 * Handles loading Google Fonts / Custom Fonts and generating dynamic inline CSS
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue font files dynamically
 */
function dt_enqueue_typography_fonts(): void {
    // 1. Google Fonts
    $body_font = dt_get_theme_option( 'body_font_family', 'Inter' );
    $heading_font = dt_get_theme_option( 'headings_font_family', 'Cormorant Garamond' );
    
    $fonts = array();
    if ( $body_font ) {
        $fonts[] = $body_font . ':wght@300;400;500;600;700';
    }
    if ( $heading_font && $heading_font !== $body_font ) {
        $fonts[] = $heading_font . ':ital,wght@0,300;0,400;0,600;0,700;1,400';
    }
    
    if ( ! empty( $fonts ) ) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', array_map( 'urlencode', $fonts ) ) . '&display=swap';
        wp_enqueue_style( 'dt-dynamic-google-fonts', $fonts_url, array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'dt_enqueue_typography_fonts' );

/**
 * Output dynamic CSS and custom font files to header
 */
function dt_output_typography_styles(): void {
    $body_font = dt_get_theme_option( 'body_font_family', 'Inter' );
    $body_size = dt_get_theme_option( 'body_font_size', '' );
    $body_weight = dt_get_theme_option( 'body_font_weight', '400' );
    
    $heading_font = dt_get_theme_option( 'headings_font_family', 'Cormorant Garamond' );
    $heading_spacing = dt_get_theme_option( 'headings_letter_spacing', '' );
    
    $custom_font_url = dt_get_theme_option( 'custom_font_url_woff2', '' );
    $custom_font_name = dt_get_theme_option( 'custom_font_family_name', '' );
    
    $css = '';
    
    // Custom Font Face
    if ( ! empty( $custom_font_url ) && ! empty( $custom_font_name ) ) {
        $css .= "@font-face {\n";
        $css .= "    font-family: '" . esc_attr( $custom_font_name ) . "';\n";
        $css .= "    src: url('" . esc_url( $custom_font_url ) . "') format('woff2');\n";
        $css .= "    font-weight: normal;\n";
        $css .= "    font-style: normal;\n";
        $css .= "    font-display: swap;\n";
        $css .= "}\n";
    }
    
    // Body styles
    $body_selectors = 'body, p, .dt-body-text, input, textarea, select';
    $css .= $body_selectors . " {\n";
    if ( ! empty( $custom_font_url ) && ! empty( $custom_font_name ) ) {
        $css .= "    font-family: '" . esc_attr( $custom_font_name ) . "', sans-serif;\n";
    } else {
        $css .= "    font-family: '" . esc_attr( $body_font ) . "', sans-serif;\n";
    }
    if ( ! empty( $body_size ) ) {
        $css .= "    font-size: " . esc_attr( $body_size ) . ";\n";
    }
    $css .= "    font-weight: " . esc_attr( $body_weight ) . ";\n";
    $css .= "}\n";
    
    // Heading styles
    $heading_selectors = 'h1, h2, h3, h4, h5, h6, .font-serif, .arrival-title';
    $css .= $heading_selectors . " {\n";
    $css .= "    font-family: '" . esc_attr( $heading_font ) . "', serif;\n";
    if ( ! empty( $heading_spacing ) ) {
        $css .= "    letter-spacing: " . esc_attr( $heading_spacing ) . ";\n";
    }
    $css .= "}\n";
    
    // WooCommerce hover zoom scaling
    $css .= "\n.hover-zoom-enabled:hover .gallery-img-wrap img { transform: scale(1.08); }\n";

    // Custom CSS from theme options
    $custom_css = dt_get_theme_option( 'custom_css' );
    if ( ! empty( $custom_css ) ) {
        $css .= "\n/* Custom CSS */\n" . $custom_css . "\n";
    }
    
    if ( ! empty( $css ) ) {
        echo "<style id='dt-typography-inline-css'>\n" . $css . "</style>\n";
    }
}
add_action( 'wp_head', 'dt_output_typography_styles', 150 );
