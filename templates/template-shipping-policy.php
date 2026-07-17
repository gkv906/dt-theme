<?php
/**
 * Template Name: Shipping Policy
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// ── Admin options ──────────────────────────────────────────────────────────
$ship_subtitle = dt_get_theme_option( 'ship_hero_subtitle', 'Fast, safe, and free worldwide delivery for every order.' );

// Stats cards
$stats = array(
    array(
        'icon'  => 'globe',
        'label' => dt_get_theme_option( 'ship_stat1_label', 'Ships to' ),
        'value' => dt_get_theme_option( 'ship_stat1_value', '40+ Countries' ),
    ),
    array(
        'icon'  => 'clock',
        'label' => dt_get_theme_option( 'ship_stat2_label', 'Delivery' ),
        'value' => dt_get_theme_option( 'ship_stat2_value', '3-7 Days' ),
    ),
    array(
        'icon'  => 'shield-check',
        'label' => dt_get_theme_option( 'ship_stat3_label', 'Free Shipping' ),
        'value' => dt_get_theme_option( 'ship_stat3_value', 'Above ₹999' ),
    ),
);

// Domestic shipping
$dom_std_label = dt_get_theme_option( 'ship_dom_std_label', 'Standard Shipping:' );
$dom_std_text  = dt_get_theme_option( 'ship_dom_std_text',  'FREE on orders above ₹999 · 3-5 business days' );
$dom_exp_label = dt_get_theme_option( 'ship_dom_exp_label', 'Express Shipping:' );
$dom_exp_text  = dt_get_theme_option( 'ship_dom_exp_text',  '₹99 · 1-2 business days' );
$dom_cod_label = dt_get_theme_option( 'ship_dom_cod_label', 'Cash on Delivery:' );
$dom_cod_text  = dt_get_theme_option( 'ship_dom_cod_text',  'Available across India (₹49 processing fee)' );

// International shipping
$intl_free_label    = dt_get_theme_option( 'ship_intl_free_label',    'Free Worldwide Shipping:' );
$intl_free_text     = dt_get_theme_option( 'ship_intl_free_text',     'On all orders above ₹5,000' );
$intl_time_label    = dt_get_theme_option( 'ship_intl_time_label',    'Delivery Time:' );
$intl_time_text     = dt_get_theme_option( 'ship_intl_time_text',     '5-10 business days via DHL Express' );
$intl_customs_label = dt_get_theme_option( 'ship_intl_customs_label', 'Customs & Duties:' );
$intl_customs_text  = dt_get_theme_option( 'ship_intl_customs_text',  'Any applicable taxes are the responsibility of the recipient' );

// Processing & packaging
$processing_text = dt_get_theme_option( 'ship_processing_text', 'Orders are processed within 24 hours (Monday–Saturday). You\'ll receive a tracking link via email and SMS once your package leaves our atelier.' );
$packaging_text  = dt_get_theme_option( 'ship_packaging_text',  'Every saree is carefully wrapped in premium white muslin cloth and packed in our signature ARSHMAN box — ready to be gifted or treasured.' );
$track_url       = dt_get_theme_option( 'ship_track_url',       home_url( '/track-order/' ) );
$contact_email   = dt_get_theme_option( 'contact_email',        'atelier@arshmandesigns.com' );
?>

<!-- Hero -->
<section class="py-16 md:py-20 text-center border-b border-[#C8A46A]/10 bg-[#050505]">
    <div class="max-w-3xl mx-auto px-4">
        <i data-lucide="truck" class="w-10 h-10 text-[#C8A46A] mx-auto mb-4"></i>
        <h1 class="font-serif text-4xl md:text-5xl text-white mb-4"><?php the_title(); ?></h1>
        <p class="text-gray-400"><?php echo esc_html( $ship_subtitle ); ?></p>
    </div>
</section>

<!-- Content -->
<section class="py-16 md:py-20 bg-black">
    <div class="max-w-4xl mx-auto px-4 md:px-8 space-y-10">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ( $stats as $stat ) : ?>
            <div class="bg-[#111] border border-[#222] p-6 text-center gold-border-glow">
                <i data-lucide="<?php echo esc_attr( $stat['icon'] ); ?>" class="w-8 h-8 text-[#C8A46A] mx-auto mb-3"></i>
                <div class="text-[#C8A46A] uppercase tracking-widest text-xs mb-1"><?php echo esc_html( $stat['label'] ); ?></div>
                <div class="font-serif text-2xl text-white"><?php echo esc_html( $stat['value'] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Policy Details -->
        <div class="space-y-8 text-gray-300 leading-relaxed font-light">

            <!-- Domestic -->
            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Domestic Delivery (India)', 'dt-ecommerce-theme' ); ?></h3>
                <ul class="space-y-2 pl-5 list-disc marker:text-[#C8A46A]">
                    <?php if ( $dom_std_label && $dom_std_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $dom_std_label ); ?></strong> <?php echo esc_html( $dom_std_text ); ?></li>
                    <?php endif; ?>
                    <?php if ( $dom_exp_label && $dom_exp_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $dom_exp_label ); ?></strong> <?php echo esc_html( $dom_exp_text ); ?></li>
                    <?php endif; ?>
                    <?php if ( $dom_cod_label && $dom_cod_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $dom_cod_label ); ?></strong> <?php echo esc_html( $dom_cod_text ); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- International -->
            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'International Delivery', 'dt-ecommerce-theme' ); ?></h3>
                <ul class="space-y-2 pl-5 list-disc marker:text-[#C8A46A]">
                    <?php if ( $intl_free_label && $intl_free_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $intl_free_label ); ?></strong> <?php echo esc_html( $intl_free_text ); ?></li>
                    <?php endif; ?>
                    <?php if ( $intl_time_label && $intl_time_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $intl_time_label ); ?></strong> <?php echo esc_html( $intl_time_text ); ?></li>
                    <?php endif; ?>
                    <?php if ( $intl_customs_label && $intl_customs_text ) : ?>
                    <li><strong class="text-white"><?php echo esc_html( $intl_customs_label ); ?></strong> <?php echo esc_html( $intl_customs_text ); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Order Processing -->
            <?php if ( $processing_text ) : ?>
            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Order Processing', 'dt-ecommerce-theme' ); ?></h3>
                <p>
                    <?php echo esc_html( $processing_text ); ?>
                    <?php if ( $track_url ) : ?>
                    <a href="<?php echo esc_url( $track_url ); ?>" class="text-[#C8A46A] underline ml-1"><?php esc_html_e( 'Track Order', 'dt-ecommerce-theme' ); ?></a><?php esc_html_e( '.', 'dt-ecommerce-theme' ); ?>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Packaging -->
            <?php if ( $packaging_text ) : ?>
            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Packaging', 'dt-ecommerce-theme' ); ?></h3>
                <p><?php echo esc_html( $packaging_text ); ?></p>
            </div>
            <?php endif; ?>

            <!-- Help CTA -->
            <div class="bg-[#0c0c0c] border border-[#C8A46A]/20 p-6">
                <h4 class="text-white font-semibold flex items-center gap-2 mb-2">
                    <i data-lucide="info" class="w-4 h-4 text-[#C8A46A]"></i>
                    <?php esc_html_e( 'Need help?', 'dt-ecommerce-theme' ); ?>
                </h4>
                <p class="text-sm text-gray-400">
                    <?php esc_html_e( 'Reach out to us anytime at', 'dt-ecommerce-theme' ); ?>
                    <?php if ( $contact_email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="text-[#C8A46A] underline"><?php echo esc_html( $contact_email ); ?></a>
                    <?php esc_html_e( 'or via our', 'dt-ecommerce-theme' ); ?>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="text-[#C8A46A] underline"><?php esc_html_e( 'Contact Us', 'dt-ecommerce-theme' ); ?></a>
                    <?php esc_html_e( 'page.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
