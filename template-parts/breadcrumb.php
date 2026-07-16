<?php
/**
 * Template Part: Breadcrumb Navigation
 *
 * Usage: get_template_part( 'template-parts/breadcrumb' );
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't show on front page
if ( is_front_page() ) {
    return;
}

$breadcrumbs = array();

// Home
$breadcrumbs[] = array(
    'url'   => home_url( '/' ),
    'label' => __( 'Home', 'dt-ecommerce-theme' ),
);

// WooCommerce Shop
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
    $shop_id = wc_get_page_id( 'shop' );
    if ( $shop_id ) {
        $breadcrumbs[] = array(
            'url'   => get_permalink( $shop_id ),
            'label' => get_the_title( $shop_id ),
        );
    }
    if ( is_singular( 'product' ) ) {
        $terms = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $breadcrumbs[] = array(
                'url'   => get_term_link( $terms[0] ),
                'label' => $terms[0]->name,
            );
        }
        $breadcrumbs[] = array(
            'url'   => '',
            'label' => get_the_title(),
        );
    } elseif ( is_product_category() ) {
        $queried = get_queried_object();
        if ( $queried->parent ) {
            $parent = get_term( $queried->parent, 'product_cat' );
            if ( $parent && ! is_wp_error( $parent ) ) {
                $breadcrumbs[] = array(
                    'url'   => get_term_link( $parent ),
                    'label' => $parent->name,
                );
            }
        }
        $breadcrumbs[] = array(
            'url'   => '',
            'label' => $queried->name,
        );
    }
} elseif ( is_singular( 'post' ) ) {
    $categories = get_the_category();
    if ( $categories ) {
        $breadcrumbs[] = array(
            'url'   => get_category_link( $categories[0]->term_id ),
            'label' => $categories[0]->name,
        );
    }
    $breadcrumbs[] = array(
        'url'   => '',
        'label' => get_the_title(),
    );
} elseif ( is_page() ) {
    $parent = wp_get_post_parent_id( get_the_ID() );
    if ( $parent ) {
        $breadcrumbs[] = array(
            'url'   => get_permalink( $parent ),
            'label' => get_the_title( $parent ),
        );
    }
    $breadcrumbs[] = array(
        'url'   => '',
        'label' => get_the_title(),
    );
} elseif ( is_archive() ) {
    $breadcrumbs[] = array(
        'url'   => '',
        'label' => get_the_archive_title(),
    );
} elseif ( is_search() ) {
    $breadcrumbs[] = array(
        'url'   => '',
        'label' => sprintf( __( 'Search results for: %s', 'dt-ecommerce-theme' ), get_search_query() ),
    );
} elseif ( is_404() ) {
    $breadcrumbs[] = array(
        'url'   => '',
        'label' => __( '404 Not Found', 'dt-ecommerce-theme' ),
    );
}

if ( count( $breadcrumbs ) <= 1 ) {
    return;
}
?>
<nav class="dt-breadcrumb py-3 px-4 md:px-6 border-b border-[#1a1a1a] bg-[#080808]" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dt-ecommerce-theme' ); ?>">
    <ol class="flex flex-wrap items-center gap-1.5 text-[11px] uppercase tracking-widest max-w-7xl mx-auto" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ( $breadcrumbs as $i => $crumb ) : ?>
            <li class="flex items-center gap-1.5" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if ( ! empty( $crumb['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $crumb['url'] ); ?>" class="text-[#a3a3a3] hover:text-[#C8A46A] transition-colors" itemprop="item">
                        <span itemprop="name"><?php echo esc_html( $crumb['label'] ); ?></span>
                    </a>
                <?php else : ?>
                    <span class="text-[#C8A46A] font-medium" itemprop="name" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo esc_attr( $i + 1 ); ?>">
                <?php if ( $i < count( $breadcrumbs ) - 1 ) : ?>
                    <span class="text-[#333]" aria-hidden="true">›</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>
