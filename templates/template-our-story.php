<?php
/**
 * Template Name: Our Story
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<!-- Hero -->
<section class="py-20 md:py-28 text-center border-b border-[#C8A46A]/10">
    <div class="max-w-3xl mx-auto px-4">
        <span class="text-[#C8A46A] uppercase tracking-[0.4em] text-xs mb-4 block"><?php esc_html_e( 'The Journey', 'dt-ecommerce-theme' ); ?></span>
        <h1 class="font-serif text-4xl md:text-6xl text-white mb-6"><?php esc_html_e( 'Our Story', 'dt-ecommerce-theme' ); ?></h1>
        <p class="text-gray-400 leading-relaxed">
            <?php esc_html_e( 'From a single loom in the ancient city of Varanasi to becoming India\'s most cherished ethnic luxury label — this is the tapestry of our journey.', 'dt-ecommerce-theme' ); ?>
        </p>
    </div>
</section>

<!-- Timeline -->
<section class="py-20 md:py-28">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        <div class="relative pl-8 md:pl-12 border-l-2 border-[#C8A46A]/30 space-y-16">

            <div class="relative">
                <div class="absolute -left-[42px] md:-left-[54px] w-6 h-6 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_20px_rgba(200,164,106,0.4)]">
                    <div class="w-2 h-2 bg-black rounded-full"></div>
                </div>
                <div class="mb-2 text-[#C8A46A] uppercase tracking-[0.3em] text-xs font-semibold">2010</div>
                <h3 class="font-serif text-3xl text-white mb-3"><?php esc_html_e( 'The Beginning', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-gray-400 leading-relaxed">
                    <?php esc_html_e( 'Founded in the historic weaving lanes of Varanasi, ARSHMAN began with a single handloom and a vision — to make authentic Banarasi silk accessible to the world without middlemen.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] md:-left-[54px] w-6 h-6 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_20px_rgba(200,164,106,0.4)]">
                    <div class="w-2 h-2 bg-black rounded-full"></div>
                </div>
                <div class="mb-2 text-[#C8A46A] uppercase tracking-[0.3em] text-xs font-semibold">2015</div>
                <h3 class="font-serif text-3xl text-white mb-3"><?php esc_html_e( 'Growing Our Family', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-gray-400 leading-relaxed">
                    <?php esc_html_e( 'By 2015, we had partnered with 50+ master weavers across Varanasi, Kanchipuram, and Mysore — becoming a trusted name in bridal and festive drapes.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] md:-left-[54px] w-6 h-6 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_20px_rgba(200,164,106,0.4)]">
                    <div class="w-2 h-2 bg-black rounded-full"></div>
                </div>
                <div class="mb-2 text-[#C8A46A] uppercase tracking-[0.3em] text-xs font-semibold">2020</div>
                <h3 class="font-serif text-3xl text-white mb-3"><?php esc_html_e( 'Going Global', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-gray-400 leading-relaxed">
                    <?php esc_html_e( 'The launch of our online atelier brought our handcrafted drapes to over 40 countries. Free worldwide shipping made luxury accessible for every woman.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>

            <div class="relative">
                <div class="absolute -left-[42px] md:-left-[54px] w-6 h-6 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_20px_rgba(200,164,106,0.4)] animate-pulse">
                    <div class="w-2 h-2 bg-black rounded-full"></div>
                </div>
                <div class="mb-2 text-[#C8A46A] uppercase tracking-[0.3em] text-xs font-semibold"><?php esc_html_e( 'Today', 'dt-ecommerce-theme' ); ?></div>
                <h3 class="font-serif text-3xl text-white mb-3"><?php esc_html_e( 'A Movement, Not Just a Brand', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-gray-400 leading-relaxed">
                    <?php esc_html_e( '200+ artisans. 50,000+ delighted patrons. Countless ceremonies, weddings, and heirloom moments woven into every drape. This is only the beginning.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 text-center border-t border-[#C8A46A]/10 bg-[#0a0a0a]">
    <div class="max-w-2xl mx-auto px-4">
        <h3 class="font-serif text-3xl md:text-4xl text-[#C8A46A] mb-6"><?php esc_html_e( 'Be Part of Our Story', 'dt-ecommerce-theme' ); ?></h3>
        <p class="text-gray-400 mb-8"><?php esc_html_e( 'Every drape you wear is a chapter in the ongoing legacy of India\'s finest looms.', 'dt-ecommerce-theme' ); ?></p>
        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>"
           class="inline-block btn-gold-shimmer px-10 py-4 uppercase tracking-widest text-sm font-semibold">
            <?php esc_html_e( 'Explore Collection', 'dt-ecommerce-theme' ); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
