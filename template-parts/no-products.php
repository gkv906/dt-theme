<?php
/**
 * Template Part: No Products Found
 *
 * Usage: get_template_part( 'template-parts/no-products' );
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
?>
<div class="text-center py-24 px-4">
    <div class="max-w-sm mx-auto">
        <div class="text-[#C8A46A]/20 mb-6">
            <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h3 class="font-serif text-2xl text-[#F7F4EE] mb-3"><?php esc_html_e( 'No Products Found', 'dt-ecommerce-theme' ); ?></h3>
        <p class="text-[#a3a3a3] text-sm mb-8 font-light leading-relaxed">
            <?php esc_html_e( 'We couldn\'t find any products matching your criteria. Try adjusting your filters or explore our full collection.', 'dt-ecommerce-theme' ); ?>
        </p>
        <a href="<?php echo esc_url( $shop_url ); ?>"
           class="inline-block border border-[#C8A46A]/40 text-[#C8A46A] hover:bg-[#C8A46A]/10 px-8 py-3 uppercase tracking-widest text-xs font-semibold transition-all duration-300">
            <?php esc_html_e( 'View All Products', 'dt-ecommerce-theme' ); ?>
        </a>
    </div>
</div>
