<?php
/**
 * SEO — Open Graph, Twitter Cards, JSON-LD Schema
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output all SEO meta tags in <head>.
 */
function dt_seo_meta_tags(): void {
    global $post;

    // ── Basic values ────────────────────────────────────────────────────────
    $site_name   = get_bloginfo( 'name' );
    $site_url    = esc_url( home_url( '/' ) );
    $title       = wp_title( '|', false, 'right' ) . $site_name;
    $description = get_bloginfo( 'description' );
    $image       = esc_url( get_template_directory_uri() . '/assets/images/hero-saree.jpg' );
    $url         = esc_url( get_permalink() );
    $type        = 'website';

    if ( is_singular() && $post ) {
        $description = wp_strip_all_tags( get_the_excerpt( $post ) );
        if ( has_post_thumbnail( $post ) ) {
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'large' );
            if ( $thumb ) {
                $image = esc_url( $thumb[0] );
            }
        }
        $type = 'article';
    }

    if ( is_singular( 'product' ) ) {
        $type = 'product';
    }

    if ( ! $description ) {
        $description = get_bloginfo( 'description' );
    }

    $description = esc_attr( wp_trim_words( $description, 30 ) );
    $title       = esc_attr( $title );

    // ── Output Open Graph + Twitter ─────────────────────────────────────────
    ?>
    <!-- DT Ecommerce SEO Meta -->
    <meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:type" content="<?php echo esc_attr( $type ); ?>">
    <meta property="og:url" content="<?php echo $url; ?>">
    <meta property="og:image" content="<?php echo $image; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image" content="<?php echo $image; ?>">

    <meta name="description" content="<?php echo $description; ?>">
    <link rel="canonical" href="<?php echo $url; ?>">
    <?php
}
add_action( 'wp_head', 'dt_seo_meta_tags', 1 );

/**
 * Output Product JSON-LD schema on single product pages.
 */
function dt_product_schema(): void {
    if ( ! is_singular( 'product' ) || ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    global $post;
    $product = wc_get_product( $post->ID );
    if ( ! $product ) {
        return;
    }

    $image_id  = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_url( $image_id ) : wc_placeholder_img_src();

    $schema = array(
        '@context'    => 'https://schema.org/',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags( $product->get_description() ),
        'sku'         => $product->get_sku(),
        'image'       => $image_url,
        'url'         => get_permalink( $post->ID ),
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => get_bloginfo( 'name' ),
        ),
        'offers'      => array(
            '@type'         => 'Offer',
            'url'           => get_permalink( $post->ID ),
            'priceCurrency' => get_woocommerce_currency(),
            'price'         => $product->get_price(),
            'availability'  => $product->is_in_stock()
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'seller'        => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
            ),
        ),
    );

    // Add aggregate rating if reviews exist
    $rating_count = $product->get_rating_count();
    if ( $rating_count > 0 ) {
        $schema['aggregateRating'] = array(
            '@type'       => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $rating_count,
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dt_product_schema' );

/**
 * Output Breadcrumb JSON-LD schema.
 */
function dt_breadcrumb_schema(): void {
    if ( is_front_page() || is_home() ) {
        return;
    }

    $items   = array();
    $items[] = array(
        '@type'    => 'ListItem',
        'position' => 1,
        'name'     => __( 'Home', 'dt-ecommerce-theme' ),
        'item'     => home_url( '/' ),
    );

    $position = 2;

    if ( is_singular( 'product' ) ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => __( 'Shop', 'dt-ecommerce-theme' ),
            'item'     => class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ),
        );
        $terms = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $terms[0]->name,
                'item'     => get_term_link( $terms[0] ),
            );
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_singular( 'post' ) ) {
        $cat = get_the_category();
        if ( $cat ) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $cat[0]->name,
                'item'     => get_category_link( $cat[0]->term_id ),
            );
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_page() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    }

    if ( count( $items ) <= 1 ) {
        return;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dt_breadcrumb_schema' );

/**
 * Organization JSON-LD schema on all pages.
 */
function dt_organization_schema(): void {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => get_bloginfo( 'name' ),
        'url'      => home_url( '/' ),
        'logo'     => array(
            '@type' => 'ImageObject',
            'url'   => esc_url( get_template_directory_uri() . '/assets/images/hero-saree.jpg' ),
        ),
        'sameAs'   => array_filter( array(
            dt_get_theme_option( 'facebook_url' ),
            dt_get_theme_option( 'instagram_url' ),
        ) ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'dt_organization_schema' );
