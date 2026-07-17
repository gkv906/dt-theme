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
?>

<!-- Hero -->
<section class="py-16 md:py-20 text-center border-b border-[#C8A46A]/10">
    <div class="max-w-3xl mx-auto px-4">
        <i data-lucide="truck" class="w-10 h-10 text-[#C8A46A] mx-auto mb-4"></i>
        <h1 class="font-serif text-4xl md:text-5xl text-white mb-4"><?php esc_html_e( 'Shipping Policy', 'dt-ecommerce-theme' ); ?></h1>
        <p class="text-gray-400"><?php esc_html_e( 'Fast, safe, and free worldwide delivery for every order.', 'dt-ecommerce-theme' ); ?></p>
    </div>
</section>

<!-- Content -->
<section class="py-16 md:py-20">
    <div class="max-w-4xl mx-auto px-4 md:px-8 space-y-10">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-[#111] border border-[#222] p-6 text-center gold-border-glow">
                <i data-lucide="globe" class="w-8 h-8 text-[#C8A46A] mx-auto mb-3"></i>
                <div class="text-[#C8A46A] uppercase tracking-widest text-xs mb-1"><?php esc_html_e( 'Ships to', 'dt-ecommerce-theme' ); ?></div>
                <div class="font-serif text-2xl text-white"><?php esc_html_e( '40+ Countries', 'dt-ecommerce-theme' ); ?></div>
            </div>
            <div class="bg-[#111] border border-[#222] p-6 text-center gold-border-glow">
                <i data-lucide="clock" class="w-8 h-8 text-[#C8A46A] mx-auto mb-3"></i>
                <div class="text-[#C8A46A] uppercase tracking-widest text-xs mb-1"><?php esc_html_e( 'Delivery', 'dt-ecommerce-theme' ); ?></div>
                <div class="font-serif text-2xl text-white"><?php esc_html_e( '3-7 Days', 'dt-ecommerce-theme' ); ?></div>
            </div>
            <div class="bg-[#111] border border-[#222] p-6 text-center gold-border-glow">
                <i data-lucide="shield-check" class="w-8 h-8 text-[#C8A46A] mx-auto mb-3"></i>
                <div class="text-[#C8A46A] uppercase tracking-widest text-xs mb-1"><?php esc_html_e( 'Free Shipping', 'dt-ecommerce-theme' ); ?></div>
                <div class="font-serif text-2xl text-white"><?php esc_html_e( 'Above ₹999', 'dt-ecommerce-theme' ); ?></div>
            </div>
        </div>

        <!-- Policy Details -->
        <div class="space-y-8 text-gray-300 leading-relaxed font-light">

            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Domestic Delivery (India)', 'dt-ecommerce-theme' ); ?></h3>
                <ul class="space-y-2 pl-5 list-disc marker:text-[#C8A46A]">
                    <li><strong class="text-white"><?php esc_html_e( 'Standard Shipping:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( 'FREE on orders above ₹999 · 3-5 business days', 'dt-ecommerce-theme' ); ?></li>
                    <li><strong class="text-white"><?php esc_html_e( 'Express Shipping:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( '₹99 · 1-2 business days', 'dt-ecommerce-theme' ); ?></li>
                    <li><strong class="text-white"><?php esc_html_e( 'Cash on Delivery:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( 'Available across India (₹49 processing fee)', 'dt-ecommerce-theme' ); ?></li>
                </ul>
            </div>

            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'International Delivery', 'dt-ecommerce-theme' ); ?></h3>
                <ul class="space-y-2 pl-5 list-disc marker:text-[#C8A46A]">
                    <li><strong class="text-white"><?php esc_html_e( 'Free Worldwide Shipping:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( 'On all orders above ₹5,000', 'dt-ecommerce-theme' ); ?></li>
                    <li><strong class="text-white"><?php esc_html_e( 'Delivery Time:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( '5-10 business days via DHL Express', 'dt-ecommerce-theme' ); ?></li>
                    <li><strong class="text-white"><?php esc_html_e( 'Customs & Duties:', 'dt-ecommerce-theme' ); ?></strong> <?php esc_html_e( 'Any applicable taxes are the responsibility of the recipient', 'dt-ecommerce-theme' ); ?></li>
                </ul>
            </div>

            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Order Processing', 'dt-ecommerce-theme' ); ?></h3>
                <p>
                    <?php esc_html_e( 'Orders are processed within 24 hours (Monday–Saturday). You\'ll receive a tracking link via email and SMS once your package leaves our atelier. Track your order anytime on our', 'dt-ecommerce-theme' ); ?>
                    <a href="<?php echo esc_url( home_url( '/track-order/' ) ); ?>" class="text-[#C8A46A] underline"><?php esc_html_e( 'Track Order', 'dt-ecommerce-theme' ); ?></a>
                    <?php esc_html_e( 'page.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>

            <div>
                <h3 class="font-serif text-2xl text-[#C8A46A] mb-3"><?php esc_html_e( 'Packaging', 'dt-ecommerce-theme' ); ?></h3>
                <p><?php esc_html_e( 'Every saree is carefully wrapped in premium white muslin cloth and packed in our signature ARSHMAN box — ready to be gifted or treasured.', 'dt-ecommerce-theme' ); ?></p>
            </div>

            <div class="bg-[#0c0c0c] border border-[#C8A46A]/20 p-6 mt-6">
                <h4 class="text-white font-semibold flex items-center gap-2 mb-2">
                    <i data-lucide="info" class="w-4 h-4 text-[#C8A46A]"></i>
                    <?php esc_html_e( 'Need help?', 'dt-ecommerce-theme' ); ?>
                </h4>
                <p class="text-sm text-gray-400">
                    <?php esc_html_e( 'Reach out to us anytime at', 'dt-ecommerce-theme' ); ?>
                    <a href="mailto:atelier@arshmandesigns.com" class="text-[#C8A46A] underline">atelier@arshmandesigns.com</a>
                    <?php esc_html_e( 'or via our', 'dt-ecommerce-theme' ); ?>
                    <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="text-[#C8A46A] underline"><?php esc_html_e( 'Contact Us', 'dt-ecommerce-theme' ); ?></a>
                    <?php esc_html_e( 'page.', 'dt-ecommerce-theme' ); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
