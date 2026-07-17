<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-24 bg-[#050505] min-h-[70vh] flex items-center justify-center">
    <div class="text-center max-w-md bg-[#0a0a0a] border border-[#C8A46A]/20 p-12 shadow-xl">
        <span class="text-xs uppercase tracking-[0.3em] text-[#C8A46A]/60 block mb-3 font-semibold"><?php esc_html_e( 'Error 404', 'dt-ecommerce-theme' ); ?></span>
        <h1 class="font-serif text-5xl md:text-7xl text-white mb-6 font-bold"><?php esc_html_e( 'Lost Thread', 'dt-ecommerce-theme' ); ?></h1>
        <p class="text-xs text-[#a3a3a3] leading-relaxed mb-8">
            <?php esc_html_e( 'The page you are looking for has been moved, deleted, or does not exist. Explore our luxury collection to find your next weave.', 'dt-ecommerce-theme' ); ?>
        </p>
        <div class="space-y-3">
            <a href="<?php echo esc_url( home_url() ); ?>" class="block w-full bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black py-3 uppercase tracking-widest text-[10px] font-bold rounded-sm transition-all"><?php esc_html_e( 'Return to Homepage', 'dt-ecommerce-theme' ); ?></a>
            <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>" class="block w-full border border-[#C8A46A] text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black py-3 uppercase tracking-widest text-[10px] font-semibold transition-all rounded-sm"><?php esc_html_e( 'Browse Collection', 'dt-ecommerce-theme' ); ?></a>
        </div>
    </div>
</main>

<?php
get_footer();
