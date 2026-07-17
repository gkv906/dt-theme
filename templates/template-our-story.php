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

// ── Admin options ──────────────────────────────────────────────────────────
$story_hero_badge = dt_get_theme_option( 'story_hero_badge', 'The Journey' );
$story_hero_title = dt_get_theme_option( 'story_hero_title', 'Our Story' );
$story_hero_text  = dt_get_theme_option( 'story_hero_text',  'From a single loom in the ancient city of Varanasi to becoming India\'s most cherished ethnic luxury label — this is the tapestry of our journey.' );

// Timeline entries with defaults
$default_timeline = array(
    array( 'year' => '2010', 'heading' => 'The Beginning',                   'text' => 'Founded in the historic weaving lanes of Varanasi, ARSHMAN began with a single handloom and a vision — to make authentic Banarasi silk accessible to the world without middlemen.' ),
    array( 'year' => '2015', 'heading' => 'Growing Our Family',               'text' => 'By 2015, we had partnered with 50+ master weavers across Varanasi, Kanchipuram, and Mysore — becoming a trusted name in bridal and festive drapes.' ),
    array( 'year' => '2020', 'heading' => 'Going Global',                     'text' => 'The launch of our online atelier brought our handcrafted drapes to over 40 countries. Free worldwide shipping made luxury accessible for every woman.' ),
    array( 'year' => 'Today', 'heading' => 'A Movement, Not Just a Brand',   'text' => '200+ artisans. 50,000+ delighted patrons. Countless ceremonies, weddings, and heirloom moments woven into every drape. This is only the beginning.' ),
);

$timeline = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $year    = dt_get_theme_option( "story_t{$i}_year",    '' );
    $heading = dt_get_theme_option( "story_t{$i}_heading", '' );
    $text    = dt_get_theme_option( "story_t{$i}_text",    '' );
    if ( ! empty( $year ) || ! empty( $heading ) ) {
        $timeline[] = array( 'year' => $year, 'heading' => $heading, 'text' => $text );
    } else {
        // fall back to built-in defaults if this slot was never configured
        $saved = get_option( 'dt_theme_options', array() );
        if ( ! array_key_exists( "story_t{$i}_year", $saved ) && isset( $default_timeline[ $i - 1 ] ) ) {
            $timeline[] = $default_timeline[ $i - 1 ];
        }
    }
}
if ( empty( $timeline ) ) $timeline = $default_timeline;

$default_shop    = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
$story_cta_h     = dt_get_theme_option( 'story_cta_heading',  'Be Part of Our Story' );
$story_cta_text  = dt_get_theme_option( 'story_cta_text',     'Every drape you wear is a chapter in the ongoing legacy of India\'s finest looms.' );
$story_cta_btn   = dt_get_theme_option( 'story_cta_btn_text', 'Explore Collection' );
$story_cta_url   = dt_get_theme_option( 'story_cta_btn_url',  $default_shop );
if ( empty( $story_cta_url ) ) $story_cta_url = $default_shop;
?>

<!-- Hero -->
<section class="py-20 md:py-28 text-center border-b border-[#C8A46A]/10 bg-[#050505]">
    <div class="max-w-3xl mx-auto px-4">
        <?php if ( $story_hero_badge ) : ?>
        <span class="text-[#C8A46A] uppercase tracking-[0.4em] text-xs mb-4 block"><?php echo esc_html( $story_hero_badge ); ?></span>
        <?php endif; ?>
        <h1 class="font-serif text-4xl md:text-6xl text-white mb-6"><?php echo esc_html( $story_hero_title ); ?></h1>
        <?php if ( $story_hero_text ) : ?>
        <p class="text-gray-400 leading-relaxed"><?php echo esc_html( $story_hero_text ); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Timeline -->
<section class="py-20 md:py-28 bg-black">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        <div class="relative pl-8 md:pl-12 border-l-2 border-[#C8A46A]/30 space-y-16">
            <?php foreach ( $timeline as $t_idx => $entry ) : ?>
            <?php if ( empty( $entry['heading'] ) && empty( $entry['year'] ) ) continue; ?>
            <div class="relative">
                <div class="absolute -left-[42px] md:-left-[54px] w-6 h-6 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_20px_rgba(200,164,106,0.4)]<?php echo $t_idx === count($timeline)-1 ? ' animate-pulse' : ''; ?>">
                    <div class="w-2 h-2 bg-black rounded-full"></div>
                </div>
                <?php if ( $entry['year'] ) : ?>
                <div class="mb-2 text-[#C8A46A] uppercase tracking-[0.3em] text-xs font-semibold"><?php echo esc_html( $entry['year'] ); ?></div>
                <?php endif; ?>
                <h3 class="font-serif text-3xl text-white mb-3"><?php echo esc_html( $entry['heading'] ); ?></h3>
                <?php if ( $entry['text'] ) : ?>
                <p class="text-gray-400 leading-relaxed"><?php echo esc_html( $entry['text'] ); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 text-center border-t border-[#C8A46A]/10 bg-[#0a0a0a]">
    <div class="max-w-2xl mx-auto px-4">
        <h3 class="font-serif text-3xl md:text-4xl text-[#C8A46A] mb-6"><?php echo esc_html( $story_cta_h ); ?></h3>
        <?php if ( $story_cta_text ) : ?>
        <p class="text-gray-400 mb-8"><?php echo esc_html( $story_cta_text ); ?></p>
        <?php endif; ?>
        <a href="<?php echo esc_url( $story_cta_url ); ?>"
           class="inline-block btn-gold-shimmer px-10 py-4 uppercase tracking-widest text-sm font-semibold">
            <?php echo esc_html( $story_cta_btn ); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
