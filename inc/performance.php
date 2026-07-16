<?php
/**
 * Performance Optimization Module
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── 1. Remove WordPress Emoji Scripts (saves ~50KB) ─────────────────────────
function dt_remove_emoji_scripts(): void {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'dt_disable_emoji_tinymce' );
    add_filter( 'wp_resource_hints', 'dt_remove_emoji_dns_prefetch', 10, 2 );
}
if ( dt_get_theme_option( 'remove_emoji_scripts', '1' ) === '1' ) {
    add_action( 'init', 'dt_remove_emoji_scripts' );
}

function dt_disable_emoji_tinymce( array $plugins ): array {
    return array_diff( $plugins, array( 'wpemoji' ) );
}

function dt_remove_emoji_dns_prefetch( array $urls, string $relation_type ): array {
    if ( 'dns-prefetch' === $relation_type ) {
        $urls = array_diff( $urls, array( apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/13.1.0/svg/' ) ) );
    }
    return $urls;
}

// ── 2. Remove Query Strings from Static Assets ───────────────────────────────
function dt_remove_query_strings_static_files( string $src ): string {
    if ( dt_get_theme_option( 'remove_query_strings', '0' ) !== '1' ) {
        return $src;
    }

    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}
add_filter( 'style_loader_src', 'dt_remove_query_strings_static_files', 10 );
add_filter( 'script_loader_src', 'dt_remove_query_strings_static_files', 10 );

// ── 3. DNS Prefetch & Preconnect for External Resources ─────────────────────
function dt_resource_hints(): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="https://cdn.tailwindcss.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://unpkg.com">' . "\n";
}
add_action( 'wp_head', 'dt_resource_hints', 2 );

// ── 4. Native Lazy Loading on Images ────────────────────────────────────────
function dt_add_lazy_loading_to_images( string $content ): string {
    if ( is_admin() || empty( $content ) ) {
        return $content;
    }
    // Add loading="lazy" to <img> tags that don't already have it
    $content = preg_replace_callback(
        '/<img([^>]+)>/i',
        function ( array $matches ) {
            $img_tag = $matches[0];
            // Skip if already has loading attribute
            if ( strpos( $img_tag, 'loading=' ) !== false ) {
                return $img_tag;
            }
            // Skip hero/above-the-fold images that have specific classes
            if ( strpos( $img_tag, 'animate-hero-img' ) !== false ) {
                return $img_tag;
            }
            return str_replace( '<img', '<img loading="lazy"', $img_tag );
        },
        $content
    );
    return $content;
}
if ( dt_get_theme_option( 'lazy_load_images', '1' ) === '1' ) {
    add_filter( 'the_content', 'dt_add_lazy_loading_to_images' );
    add_filter( 'post_thumbnail_html', 'dt_add_lazy_loading_to_images' );
    add_filter( 'woocommerce_product_get_image', 'dt_add_lazy_loading_to_images' );
}

// ── 5. Disable Unused Block CSS (Gutenberg) on Frontend ─────────────────────
function dt_remove_block_css(): void {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
}
if ( dt_get_theme_option( 'disable_gutenberg_css', '1' ) === '1' ) {
    add_action( 'wp_enqueue_scripts', 'dt_remove_block_css', 100 );
}

// ── 6. Remove RSD, WLW, Shortlink from <head> ───────────────────────────────
function dt_remove_head_clutter(): void {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'init', 'dt_remove_head_clutter' );

// ── 7. Defer Non-Critical JavaScript ────────────────────────────────────────
function dt_defer_scripts( string $tag, string $handle ): string {
    if ( is_admin() ) {
        return $tag;
    }

    // Never defer these critical scripts
    $no_defer = array( 'dt-tailwind-cdn', 'dt-lucide-icons', 'jquery' );
    if ( in_array( $handle, $no_defer, true ) ) {
        return $tag;
    }
    if ( strpos( $tag, 'defer' ) !== false ) {
        return $tag;
    }
    return str_replace( ' src=', ' defer src=', $tag );
}
add_filter( 'script_loader_tag', 'dt_defer_scripts', 10, 2 );

// ── 8. Set Image Dimensions Hint ────────────────────────────────────────────
if ( dt_get_theme_option( 'lazy_load_images', '1' ) === '1' ) {
    add_filter( 'wp_lazy_loading_enabled', '__return_true' );
} else {
    add_filter( 'wp_lazy_loading_enabled', '__return_false' );
}
