<?php
/**
 * Template Name: About Template
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// ── Admin options ──────────────────────────────────────────────────────────
$about_hero_image   = dt_get_theme_option( 'about_hero_image_url', get_template_directory_uri() . '/assets/images/banner-bridal.jpg' );
if ( empty( $about_hero_image ) ) $about_hero_image = get_template_directory_uri() . '/assets/images/banner-bridal.jpg';

$about_hero_badge   = dt_get_theme_option( 'about_hero_badge',   'Our Atelier' );
$about_founded_year = dt_get_theme_option( 'about_founded_year', 'Est. 2010' );
$about_story_title  = dt_get_theme_option( 'about_story_title',  'Weaving Dreams, One Thread at a Time' );
$about_story_p1     = dt_get_theme_option( 'about_story_p1',     'ARSHMAN DESIGNS was born from a deep reverence for India\'s timeless textile heritage. What began as a single loom in the narrow alleys of Varanasi has today grown into a modern-day atelier of over 200 skilled artisans, each hand-picking, weaving, and finishing every drape with unwavering dedication.' );
$about_story_p2     = dt_get_theme_option( 'about_story_p2',     'Our founder\'s philosophy was simple — luxury should not compromise on authenticity. Every silk yarn, every zari thread, every color palette is sourced ethically and woven traditionally, honoring the centuries-old techniques passed from grandfather to grandson.' );
$about_story_p3     = dt_get_theme_option( 'about_story_p3',     'From heritage Banarasi weaves to modern Organza drapes, we bridge the gap between tradition and contemporary elegance for the discerning woman of today.' );

$about_values = array(
    array(
        'icon'  => 'hand-heart',
        'title' => dt_get_theme_option( 'about_value1_title', 'Handcrafted' ),
        'text'  => dt_get_theme_option( 'about_value1_text',  'Every saree is meticulously handloomed by master weavers with generations of expertise.' ),
    ),
    array(
        'icon'  => 'leaf',
        'title' => dt_get_theme_option( 'about_value2_title', 'Ethical Sourcing' ),
        'text'  => dt_get_theme_option( 'about_value2_text',  'Sustainable raw materials, fair wages, and honest craftsmanship at every step.' ),
    ),
    array(
        'icon'  => 'gem',
        'title' => dt_get_theme_option( 'about_value3_title', 'Timeless Luxury' ),
        'text'  => dt_get_theme_option( 'about_value3_text',  'Pieces designed to be cherished for a lifetime and passed down for generations.' ),
    ),
);

$default_shop_url   = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
$about_cta_heading  = dt_get_theme_option( 'about_cta_heading',  'Discover Our Collection' );
$about_cta_subtext  = dt_get_theme_option( 'about_cta_subtext',  'Handpicked luxury woven for the modern connoisseur.' );
$about_cta_btn_text = dt_get_theme_option( 'about_cta_btn_text', 'Shop the Collection' );
$about_cta_btn_url  = dt_get_theme_option( 'about_cta_btn_url',  $default_shop_url );
if ( empty( $about_cta_btn_url ) ) $about_cta_btn_url = $default_shop_url;
?>

<!-- Hero -->
<section class="relative h-[45vh] md:h-[55vh] overflow-hidden bg-[#050505]">
    <img src="<?php echo esc_url( $about_hero_image ); ?>"
         alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> Atelier"
         class="absolute inset-0 w-full h-full object-cover opacity-60"
         fetchpriority="high" />
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-black/30"></div>
    <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4">
        <span class="text-[#C8A46A] uppercase tracking-[0.4em] text-xs md:text-sm mb-4"><?php echo esc_html( $about_hero_badge ); ?></span>
        <h1 class="font-serif text-5xl md:text-7xl text-white mb-4"><?php the_title(); ?></h1>
        <div class="w-24 h-px bg-[#C8A46A]"></div>
    </div>
</section>

<!-- Story Section -->
<section class="py-20 md:py-28 bg-black">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        <div class="text-center mb-16">
            <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php echo esc_html( $about_founded_year ); ?></span>
            <h2 class="font-serif text-3xl md:text-5xl text-white mb-6"><?php echo esc_html( $about_story_title ); ?></h2>
            <div class="flex items-center justify-center gap-4">
                <div class="w-16 h-px bg-gradient-to-r from-transparent to-[#C8A46A]/40"></div>
                <i data-lucide="sparkles" class="w-4 h-4 text-[#C8A46A]"></i>
                <div class="w-16 h-px bg-gradient-to-l from-transparent to-[#C8A46A]/40"></div>
            </div>
        </div>

        <div class="space-y-6 text-gray-300 leading-relaxed font-light text-base md:text-lg">
            <?php if ( ! empty( $about_story_p1 ) ) : ?><p><?php echo esc_html( $about_story_p1 ); ?></p><?php endif; ?>
            <?php if ( ! empty( $about_story_p2 ) ) : ?><p><?php echo esc_html( $about_story_p2 ); ?></p><?php endif; ?>
            <?php if ( ! empty( $about_story_p3 ) ) : ?><p><?php echo esc_html( $about_story_p3 ); ?></p><?php endif; ?>
        </div>
    </div>
</section>

<!-- Values Grid -->
<section class="py-20 border-t border-[#C8A46A]/10 bg-[#0a0a0a]">
    <div class="max-w-6xl mx-auto px-4">
        <h3 class="font-serif text-center text-3xl md:text-4xl text-[#C8A46A] mb-16"><?php esc_html_e( 'Our Values', 'dt-ecommerce-theme' ); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ( $about_values as $val_item ) : ?>
            <?php if ( empty( $val_item['title'] ) ) continue; ?>
            <div class="bg-[#111] border border-[#222] p-8 gold-border-glow text-center">
                <i data-lucide="<?php echo esc_attr( $val_item['icon'] ); ?>" class="w-10 h-10 text-[#C8A46A] mx-auto mb-6"></i>
                <h4 class="font-serif text-2xl text-white mb-4"><?php echo esc_html( $val_item['title'] ); ?></h4>
                <p class="text-gray-400 text-sm leading-relaxed"><?php echo esc_html( $val_item['text'] ); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 text-center bg-black">
    <div class="max-w-2xl mx-auto px-4">
        <h3 class="font-serif text-3xl md:text-4xl text-white mb-6"><?php echo esc_html( $about_cta_heading ); ?></h3>
        <p class="text-gray-400 mb-8"><?php echo esc_html( $about_cta_subtext ); ?></p>
        <a href="<?php echo esc_url( $about_cta_btn_url ); ?>"
           class="inline-block bg-[#C8A46A] text-black px-10 py-4 uppercase tracking-widest text-sm font-semibold hover:bg-[#b08d55] transition-all rounded-sm">
            <?php echo esc_html( $about_cta_btn_text ); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
