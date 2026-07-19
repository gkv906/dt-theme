<?php
/**
 * DT Ecommerce Theme Customizer Integration
 * Maps theme options directly to native WordPress Customizer for live previewing.
 * Uses type => 'option' under the 'dt_theme_options' array.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Customizer settings and controls.
 */
function dt_customize_register( WP_Customize_Manager $wp_customize ): void {

    // ── Section: Header Builder ──────────────────────────────────────────────
    $wp_customize->add_section( 'dt_header_section', array(
        'title'    => __( 'Header Settings', 'dt-ecommerce-theme' ),
        'priority' => 30,
    ) );

    // Sticky Header Setting
    $wp_customize->add_setting( 'dt_theme_options[sticky_header]', array(
        'type'              => 'option',
        'default'           => '1',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dt_sticky_header_control', array(
        'label'    => __( 'Enable Sticky Header', 'dt-ecommerce-theme' ),
        'section'  => 'dt_header_section',
        'settings' => 'dt_theme_options[sticky_header]',
        'type'     => 'checkbox',
    ) );

    // Logo Height Setting
    $wp_customize->add_setting( 'dt_theme_options[logo_height]', array(
        'type'              => 'option',
        'default'           => '40',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_logo_height_control', array(
        'label'       => __( 'Logo Height (px)', 'dt-ecommerce-theme' ),
        'description' => __( 'Define the height of the logo in pixels (20px to 80px)', 'dt-ecommerce-theme' ),
        'section'     => 'dt_header_section',
        'settings'    => 'dt_theme_options[logo_height]',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 80,
            'step' => 1,
        ),
    ) );

    // Location Display
    $wp_customize->add_setting( 'dt_theme_options[header_location]', array(
        'type'              => 'option',
        'default'           => 'Mumbai 400001',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_header_location_control', array(
        'label'    => __( 'Deliver To Location Text', 'dt-ecommerce-theme' ),
        'section'  => 'dt_header_section',
        'settings' => 'dt_theme_options[header_location]',
        'type'     => 'text',
    ) );

    // Tagline Below Logo
    $wp_customize->add_setting( 'dt_theme_options[header_tagline]', array(
        'type'              => 'option',
        'default'           => 'Banarasi Elegance',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_header_tagline_control', array(
        'label'    => __( 'Logo Tagline Subtext', 'dt-ecommerce-theme' ),
        'section'  => 'dt_header_section',
        'settings' => 'dt_theme_options[header_tagline]',
        'type'     => 'text',
    ) );

    // ── Section: Announcement Bar ────────────────────────────────────────────
    $wp_customize->add_section( 'dt_announcement_section', array(
        'title'    => __( 'Announcement Bar', 'dt-ecommerce-theme' ),
        'priority' => 35,
    ) );

    $wp_customize->add_setting( 'dt_theme_options[announcement_messages]', array(
        'type'              => 'option',
        'default'           => 'Free Shipping on orders above ₹999,Premium Quality Sarees,Direct from Manufacturer',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dt_announcement_messages_control', array(
        'label'    => __( 'Rotating Messages (Comma Separated)', 'dt-ecommerce-theme' ),
        'section'  => 'dt_announcement_section',
        'settings' => 'dt_theme_options[announcement_messages]',
        'type'     => 'textarea',
    ) );

    // ── Section: Hero Banner ──────────────────────────────────────────────────
    $wp_customize->add_section( 'dt_hero_section', array(
        'title'    => __( 'Hero Banner Settings', 'dt-ecommerce-theme' ),
        'priority' => 40,
    ) );

    // Hero Heading
    $wp_customize->add_setting( 'dt_theme_options[hero_heading]', array(
        'type'              => 'option',
        'default'           => 'Banarasi Elegance',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_hero_heading_control', array(
        'label'    => __( 'Hero Headline', 'dt-ecommerce-theme' ),
        'section'  => 'dt_hero_section',
        'settings' => 'dt_theme_options[hero_heading]',
        'type'     => 'text',
    ) );

    // Hero Subtext
    $wp_customize->add_setting( 'dt_theme_options[hero_subtext]', array(
        'type'              => 'option',
        'default'           => 'Discover our curated collection of handcrafted silk sarees.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_hero_subtext_control', array(
        'label'    => __( 'Hero Subtext Description', 'dt-ecommerce-theme' ),
        'section'  => 'dt_hero_section',
        'settings' => 'dt_theme_options[hero_subtext]',
        'type'     => 'textarea',
    ) );

    // ── Section: Footer settings ─────────────────────────────────────────────
    $wp_customize->add_section( 'dt_footer_section', array(
        'title'    => __( 'Footer Settings', 'dt-ecommerce-theme' ),
        'priority' => 45,
    ) );

    // Footer Brand Name
    $wp_customize->add_setting( 'dt_theme_options[footer_brand_name]', array(
        'type'              => 'option',
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_footer_brand_name_control', array(
        'label'       => __( 'Footer Brand Name', 'dt-ecommerce-theme' ),
        'description' => __( 'Brand name shown in the footer (defaults to site name if left blank)', 'dt-ecommerce-theme' ),
        'section'     => 'dt_footer_section',
        'settings'    => 'dt_theme_options[footer_brand_name]',
        'type'        => 'text',
    ) );

    // Footer Brand Tagline
    $wp_customize->add_setting( 'dt_theme_options[footer_brand_tagline]', array(
        'type'              => 'option',
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_footer_brand_tagline_control', array(
        'label'       => __( 'Footer Brand Tagline', 'dt-ecommerce-theme' ),
        'description' => __( 'Short tagline shown next to the brand name (e.g. Designs, Studio)', 'dt-ecommerce-theme' ),
        'section'     => 'dt_footer_section',
        'settings'    => 'dt_theme_options[footer_brand_tagline]',
        'type'        => 'text',
    ) );

    // Copyright
    $wp_customize->add_setting( 'dt_theme_options[footer_copyright]', array(
        'type'              => 'option',
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_footer_copyright_control', array(
        'label'    => __( 'Copyright Text', 'dt-ecommerce-theme' ),
        'section'  => 'dt_footer_section',
        'settings' => 'dt_theme_options[footer_copyright]',
        'type'     => 'text',
    ) );

    // Brand bio
    $wp_customize->add_setting( 'dt_theme_options[footer_about]', array(
        'type'              => 'option',
        'default'           => 'We weave your dreams into reality.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'dt_footer_about_control', array(
        'label'    => __( 'Brand Bio Description', 'dt-ecommerce-theme' ),
        'section'  => 'dt_footer_section',
        'settings' => 'dt_theme_options[footer_about]',
        'type'     => 'textarea',
    ) );
}
add_action( 'customize_register', 'dt_customize_register' );

/**
 * Customizer Live Preview postMessage JavaScript integration.
 */
function dt_customize_preview_js(): void {
    wp_enqueue_script(
        'dt-customizer-preview',
        get_template_directory_uri() . '/admin/customizer-preview.js',
        array( 'customize-preview' ),
        '1.0',
        true
    );
}
add_action( 'customize_preview_init', 'dt_customize_preview_js' );
