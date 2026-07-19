<?php
/**
 * The template for displaying the footer.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$shop_url     = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
$wishlist_url = home_url( '/wishlist' );
$account_url  = class_exists( 'WooCommerce' ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : home_url( '/my-account' );
$checkout_url = class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : home_url( '/checkout' );
$cart_url     = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : home_url( '/cart' );
$track_url    = home_url( '/track-order' );
$contact_url  = home_url( '/contact-us' );
$faq_url      = home_url( '/faq' );
$shipping_url = home_url( '/shipping-policy' );
$about_url    = home_url( '/about-us' );
$story_url    = home_url( '/our-story' );

$facebook         = dt_get_theme_option( 'facebook_url', '#' );
$instagram        = dt_get_theme_option( 'instagram_url', '#' );
$twitter          = dt_get_theme_option( 'twitter_url', '#' );
$youtube          = dt_get_theme_option( 'youtube_url', '#' );
$whatsapp         = dt_get_theme_option( 'whatsapp_url', 'https://wa.me/911234567890' );

// Auto-hide social buttons when URL is not configured
$has_instagram  = ! empty( $instagram ) && $instagram  !== '#';
$has_facebook   = ! empty( $facebook )  && $facebook   !== '#';
$has_twitter    = ! empty( $twitter )   && $twitter    !== '#';
$has_youtube    = ! empty( $youtube )   && $youtube    !== '#';
$has_whatsapp   = ! empty( $whatsapp )  && $whatsapp   !== '#';
$has_any_social = $has_instagram || $has_facebook || $has_twitter || $has_youtube || $has_whatsapp;
$footer_brand_name    = dt_get_theme_option( 'footer_brand_name', get_bloginfo( 'name' ) );
$footer_brand_tagline = dt_get_theme_option( 'footer_brand_tagline', '' );
$footer_logo_url      = dt_get_theme_option( 'footer_logo_url', '' );
$footer_use_site_logo = dt_get_theme_option( 'footer_use_site_logo', '1' );

// Auto-resolve logo: 1) custom footer upload  2) WordPress site logo  3) text fallback
$resolved_footer_logo = $footer_logo_url;
if ( empty( $resolved_footer_logo ) && $footer_use_site_logo === '1' ) {
    $site_logo_id = get_theme_mod( 'custom_logo' );
    if ( $site_logo_id ) {
        $logo_src = wp_get_attachment_image_src( $site_logo_id, 'full' );
        if ( ! empty( $logo_src[0] ) ) {
            $resolved_footer_logo = $logo_src[0];
        }
    }
}
$copyright            = dt_get_theme_option( 'footer_copyright', '&copy; ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' );
$email                = dt_get_theme_option( 'contact_email', 'info@' . wp_parse_url( home_url(), PHP_URL_HOST ) );
$phone                = dt_get_theme_option( 'contact_phone', '+91 12345 67890' );
$address              = dt_get_theme_option( 'contact_address', '' );
$footer_about         = dt_get_theme_option( 'footer_about', 'We weave your dreams into reality - curating heirloom silks and modern drapes from India\'s finest looms, since 2010.' );
$show_newsletter  = dt_get_theme_option( 'newsletter_enabled', '1' ) === '1';
$newsletter_title = dt_get_theme_option( 'newsletter_title', 'Join The Atelier' );
$newsletter_desc  = dt_get_theme_option( 'newsletter_desc', 'Receive priority access to bridal collections, insider styling notes, and a 10% welcome discount on your first drape.' );

$hide_main_footer = (
    ( function_exists( 'is_product' ) && is_product() )
    || ( function_exists( 'is_shop' ) && is_shop() )
    || ( function_exists( 'is_cart' ) && is_cart() )
    || ( function_exists( 'is_checkout' ) && is_checkout() )
    || ( function_exists( 'is_account_page' ) && is_account_page() )
    || is_page( array( 'wishlist', 'login', 'register', 'my-account', 'cart', 'checkout' ) )
);
?>

<?php
$has_elementor_footer = ( ! $hide_main_footer && function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) );
?>
<?php if ( ! $hide_main_footer && ! $has_elementor_footer ) : ?>
    <footer class="footer-luxury relative pt-24 md:pt-28 overflow-hidden">
        <div class="ornament-divider absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center gap-4 px-8 z-10 bg-black">
            <div class="w-16 h-px bg-gradient-to-r from-transparent via-[#C8A46A]/60 to-transparent"></div>
            <svg class="w-5 h-5 text-[#C8A46A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2 L14.5 9.5 L22 12 L14.5 14.5 L12 22 L9.5 14.5 L2 12 L9.5 9.5 Z"/></svg>
            <div class="w-16 h-px bg-gradient-to-l from-transparent via-[#C8A46A]/60 to-transparent"></div>
        </div>

        <div class="absolute inset-0 pointer-events-none opacity-40">
            <div class="absolute inset-0 diagonal-thread-pattern-reverse"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <?php if ( $show_newsletter ) : ?>
            <div class="newsletter-block relative max-w-4xl mx-auto mb-20 md:mb-24 reveal-on-scroll">
                <div class="text-center px-6 md:px-12 py-12 md:py-14 border border-[#C8A46A]/25 bg-gradient-to-br from-[#0e0e0e] to-[#080808] relative overflow-hidden">
                    <div class="absolute top-3 left-3 w-6 h-6 border-t border-l border-[#C8A46A]/40"></div>
                    <div class="absolute top-3 right-3 w-6 h-6 border-t border-r border-[#C8A46A]/40"></div>
                    <div class="absolute bottom-3 left-3 w-6 h-6 border-b border-l border-[#C8A46A]/40"></div>
                    <div class="absolute bottom-3 right-3 w-6 h-6 border-b border-r border-[#C8A46A]/40"></div>
                    <span class="text-[10px] uppercase tracking-[0.4em] text-[#C8A46A]/70 block mb-4 font-medium"><?php esc_html_e( 'Exclusive Access', 'dt-ecommerce-theme' ); ?></span>
                    <h3 class="font-serif text-3xl md:text-5xl text-white mb-4 leading-tight"><?php echo wp_kses_post( $newsletter_title ); ?></h3>
                    <p class="text-gray-400 mb-8 text-sm md:text-base max-w-lg mx-auto leading-relaxed"><?php echo wp_kses_post( $newsletter_desc ); ?></p>

                    <form id="footer-newsletter-form" onsubmit="handleNewsletterSubmit(event)" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                        <div class="relative flex-1">
                            <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#C8A46A]/60"></i>
                            <input id="newsletter-email" type="email" required placeholder="<?php esc_attr_e( 'Enter your email address', 'dt-ecommerce-theme' ); ?>" class="w-full bg-[#050505] border border-[#222] hover:border-[#C8A46A]/40 focus:border-[#C8A46A] outline-none text-white placeholder-gray-500 pl-11 pr-4 py-3.5 text-sm transition-colors rounded-sm">
                        </div>
                        <button type="submit" class="btn-gold-shimmer px-6 md:px-8 py-3.5 uppercase tracking-widest text-xs font-semibold whitespace-nowrap"><?php esc_html_e( 'Subscribe', 'dt-ecommerce-theme' ); ?></button>
                    </form>
                    <div id="newsletter-success" class="hidden mt-6 text-[#C8A46A] text-sm items-center justify-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span><?php esc_html_e( 'Welcome to the atelier - check your inbox for your 10% code', 'dt-ecommerce-theme' ); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-y-10 md:gap-x-10 lg:gap-x-14 pb-14 border-b border-[#C8A46A]/15">
                <div class="md:col-span-12 lg:col-span-4">
                    <?php if ( ! empty( $resolved_footer_logo ) ) : ?>
                    <!-- Footer Logo: auto from site logo or custom upload -->
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block mb-5 focus:outline-none" aria-label="<?php echo esc_attr( $footer_brand_name ); ?> – <?php esc_attr_e( 'Home', 'dt-ecommerce-theme' ); ?>">
                        <img
                            src="<?php echo esc_url( $resolved_footer_logo ); ?>"
                            alt="<?php echo esc_attr( $footer_brand_name ); ?>"
                            class="dt-footer-logo h-10 sm:h-12 md:h-14 lg:h-16 w-auto max-w-[160px] sm:max-w-[200px] object-contain"
                            loading="lazy"
                            decoding="async"
                        >
                    </a>
                    <?php else : ?>
                    <!-- Footer Brand Text fallback -->
                    <div class="flex items-baseline gap-3 mb-3">
                        <?php
                        $bn      = esc_html( $footer_brand_name );
                        $first   = mb_substr( $bn, 0, 1 );
                        $rest    = mb_substr( $bn, 1 );
                        ?>
                        <h2 class="dt-footer-brand-name font-serif text-3xl md:text-4xl tracking-[0.15em] text-white">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-[#C8A46A] transition-colors duration-200">
                                <span class="text-[#C8A46A]"><?php echo $first; ?></span><?php echo $rest; ?>
                            </a>
                        </h2>
                        <?php if ( ! empty( $footer_brand_tagline ) ) : ?>
                        <span class="dt-footer-brand-tagline text-[10px] uppercase tracking-[0.3em] text-[#C8A46A]/60"><?php echo esc_html( $footer_brand_tagline ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <p class="dt-footer-about text-gray-400 text-sm md:text-[15px] leading-relaxed max-w-md mb-6 font-light"><?php echo esc_html( $footer_about ); ?></p>

                    <div class="flex flex-col gap-3 mb-7">
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center gap-3 text-sm text-gray-400 hover:text-[#C8A46A] transition-colors group">
                            <span class="w-9 h-9 flex items-center justify-center border border-[#C8A46A]/20 rounded-sm group-hover:border-[#C8A46A] group-hover:bg-[#C8A46A]/10 transition-all shrink-0"><i data-lucide="mail" class="w-4 h-4 text-[#C8A46A]"></i></span>
                            <span><?php echo esc_html( $email ); ?></span>
                        </a>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="flex items-center gap-3 text-sm text-gray-400 hover:text-[#C8A46A] transition-colors group">
                            <span class="w-9 h-9 flex items-center justify-center border border-[#C8A46A]/20 rounded-sm group-hover:border-[#C8A46A] group-hover:bg-[#C8A46A]/10 transition-all shrink-0"><i data-lucide="phone" class="w-4 h-4 text-[#C8A46A]"></i></span>
                            <span><?php echo esc_html( $phone ); ?> - <?php esc_html_e( 'Mon-Sat, 10am-7pm IST', 'dt-ecommerce-theme' ); ?></span>
                        </a>
                        <div class="flex items-start gap-3 text-sm text-gray-400">
                            <span class="w-9 h-9 flex items-center justify-center border border-[#C8A46A]/20 rounded-sm shrink-0 mt-0.5"><i data-lucide="map-pin" class="w-4 h-4 text-[#C8A46A]"></i></span>
                            <span><?php echo esc_html( $address ); ?></span>
                        </div>
                    </div>

                    <!-- Support / Contact block for mobile -->
                    <div class="footer-support-block md:hidden mt-4 p-4 border border-[#C8A46A]/15 rounded-sm bg-[#0d0d0d]">
                        <p class="text-[10px] uppercase tracking-widest text-[#C8A46A] font-semibold mb-3"><?php esc_html_e( 'Support', 'dt-ecommerce-theme' ); ?></p>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center gap-2 text-sm text-gray-300 hover:text-[#C8A46A] transition-colors mb-2">
                            <svg class="w-4 h-4 text-[#C8A46A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <span class="truncate font-medium"><?php echo esc_html( $email ); ?></span>
                        </a>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="flex items-center gap-2 text-sm text-gray-400 hover:text-[#C8A46A] transition-colors">
                            <svg class="w-4 h-4 text-[#C8A46A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            <span><?php echo esc_html( $phone ); ?></span>
                        </a>
                    </div>

                    <div class="mt-5 md:mt-0">
                        <div class="text-[10px] uppercase tracking-widest text-gray-500 mb-3 font-medium"><?php esc_html_e( 'Follow Us', 'dt-ecommerce-theme' ); ?></div>
                        <div class="flex flex-wrap gap-2.5">

                            <?php if ( $has_instagram ) : ?>
                            <a href="<?php echo esc_url( $instagram ); ?>" class="social-btn social-btn-labeled" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span class="social-btn-label">Instagram</span>
                            </a>
                            <?php endif; ?>

                            <?php if ( $has_facebook ) : ?>
                            <a href="<?php echo esc_url( $facebook ); ?>" class="social-btn social-btn-labeled" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span class="social-btn-label">Facebook</span>
                            </a>
                            <?php endif; ?>

                            <?php if ( $has_twitter ) : ?>
                            <a href="<?php echo esc_url( $twitter ); ?>" class="social-btn social-btn-labeled" aria-label="Twitter / X">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                <span class="social-btn-label">X / Twitter</span>
                            </a>
                            <?php endif; ?>

                            <?php if ( $has_youtube ) : ?>
                            <a href="<?php echo esc_url( $youtube ); ?>" class="social-btn social-btn-labeled" aria-label="YouTube">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <span class="social-btn-label">YouTube</span>
                            </a>
                            <?php endif; ?>

                            <?php if ( $has_whatsapp ) : ?>
                            <a href="<?php echo esc_url( $whatsapp ); ?>" class="social-btn social-btn-labeled social-whatsapp" aria-label="WhatsApp" target="_blank" rel="noopener">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                <span class="social-btn-label">WhatsApp</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="footer-accordion md:col-span-4 lg:col-span-2">
                    <button type="button" class="footer-accordion-toggle" aria-expanded="false"><span class="footer-heading"><?php esc_html_e( 'Explore', 'dt-ecommerce-theme' ); ?></span><span class="footer-accordion-icon" aria-hidden="true"></span></button>
                    <div class="footer-accordion-panel">
                        <ul class="footer-links">
                            <li><a href="<?php echo esc_url( $about_url ); ?>"><?php esc_html_e( 'About Us', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $story_url ); ?>"><?php esc_html_e( 'Our Story', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Store Locator', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $faq_url ); ?>"><?php esc_html_e( 'Journal', 'dt-ecommerce-theme' ); ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion md:col-span-4 lg:col-span-2">
                    <button type="button" class="footer-accordion-toggle" aria-expanded="false"><span class="footer-heading"><?php esc_html_e( 'Categories', 'dt-ecommerce-theme' ); ?></span><span class="footer-accordion-icon" aria-hidden="true"></span></button>
                    <div class="footer-accordion-panel">
                        <ul class="footer-links">
                            <?php
                            $footer_cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'number' => 5 ) );
                            if ( ! is_wp_error( $footer_cats ) && ! empty( $footer_cats ) ) {
                                foreach ( $footer_cats as $footer_cat ) {
                                    echo '<li><a href="' . esc_url( get_term_link( $footer_cat ) ) . '">' . esc_html( $footer_cat->name ) . '</a></li>';
                                }
                            } else {
                                foreach ( array( 'Banarasi Silk', 'Bridal Wear', 'Organza Drapes', 'New Arrivals', 'Festive Edit' ) as $fallback_cat ) {
                                    echo '<li><a href="' . esc_url( $shop_url ) . '">' . esc_html( $fallback_cat ) . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion md:col-span-4 lg:col-span-2">
                    <button type="button" class="footer-accordion-toggle" aria-expanded="false"><span class="footer-heading"><?php esc_html_e( 'Care', 'dt-ecommerce-theme' ); ?></span><span class="footer-accordion-icon" aria-hidden="true"></span></button>
                    <div class="footer-accordion-panel">
                        <ul class="footer-links">
                            <li><a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact Us', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $shipping_url ); ?>"><?php esc_html_e( 'Shipping Policy', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $track_url ); ?>"><?php esc_html_e( 'Track Order', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $faq_url ); ?>"><?php esc_html_e( 'FAQs', 'dt-ecommerce-theme' ); ?></a></li>
                            <li><a href="<?php echo esc_url( $account_url ); ?>"><?php esc_html_e( 'My Account', 'dt-ecommerce-theme' ); ?></a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion md:col-span-12 lg:col-span-2">
                    <button type="button" class="footer-accordion-toggle" aria-expanded="false"><span class="footer-heading"><?php esc_html_e( 'We Accept', 'dt-ecommerce-theme' ); ?></span><span class="footer-accordion-icon" aria-hidden="true"></span></button>
                    <div class="footer-accordion-panel">
                        <div class="grid grid-cols-4 md:grid-cols-3 gap-2">
                            <div class="payment-badge" title="Visa"><span class="font-serif italic text-white text-sm tracking-tight">VISA</span></div>
                            <div class="payment-badge" title="Mastercard"><span class="flex gap-0.5"><span class="w-2.5 h-2.5 rounded-full bg-[#EB001B]/80"></span><span class="w-2.5 h-2.5 rounded-full bg-[#F79E1B]/80 -ml-1"></span></span></div>
                            <div class="payment-badge" title="UPI"><span class="font-mono text-xs text-white">UPI</span></div>
                            <div class="payment-badge" title="Amex"><span class="text-[9px] font-bold text-white tracking-wider">AMEX</span></div>
                            <div class="payment-badge" title="PayPal"><span class="text-[10px] font-bold text-white italic">PayPal</span></div>
                            <div class="payment-badge" title="Cash on Delivery"><span class="text-[9px] font-bold text-white tracking-tight">COD</span></div>
                        </div>
                        <div class="mt-5 flex flex-col gap-2 text-[10px] uppercase tracking-widest text-gray-500">
                            <div class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-3 h-3 text-[#C8A46A]/80"></i> <?php esc_html_e( 'SSL Secured', 'dt-ecommerce-theme' ); ?></div>
                            <div class="flex items-center gap-1.5"><i data-lucide="truck" class="w-3 h-3 text-[#C8A46A]/80"></i> <?php esc_html_e( 'Insured Delivery', 'dt-ecommerce-theme' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-xs tracking-wide text-center md:text-left"><?php echo wp_kses_post( $copyright ); ?></p>
                <div class="footer-bottom-links flex items-center gap-3 md:gap-6 text-gray-500 text-[10px] uppercase tracking-[0.15em]">
                    <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Privacy', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( home_url( '/terms-conditions' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Terms', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( $shipping_url ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Shipping', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'About', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( home_url( '/contact-us' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Contact', 'dt-ecommerce-theme' ); ?></a>
                </div>
            </div>
        </div>

        <button onclick="scrollToTop()" id="back-to-top" class="back-to-top-btn fixed bottom-24 md:bottom-8 right-4 md:right-6 w-11 h-11 md:w-12 md:h-12 rounded-full bg-[#C8A46A] text-black flex items-center justify-center shadow-2xl opacity-0 pointer-events-none z-40" aria-label="<?php esc_attr_e( 'Back to top', 'dt-ecommerce-theme' ); ?>">
            <i data-lucide="arrow-up" class="w-5 h-5"></i>
        </button>
    </footer>
<?php endif; ?>

<?php
$hide_mobile_bottom_nav = (
    ( function_exists( 'is_product' ) && is_product() )
    || ( function_exists( 'is_shop' ) && is_shop() )
    || ( function_exists( 'is_product_category' ) && is_product_category() )
);
?>
<div id="mobile-bottom-nav" class="fixed bottom-0 left-0 w-full bg-[#0a0a0a]/90 backdrop-blur-lg border-t border-[#C8A46A]/20 md:hidden z-50 px-2 py-2 safe-area-bottom <?php echo $hide_mobile_bottom_nav ? 'hidden' : ''; ?>">
    <div class="flex items-end justify-around">
        <button onclick="toggleMobileMenuDrawer(true)" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i><span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Menu', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="toggleMobileSearchOverlay(true)" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors">
            <i data-lucide="search" class="w-5 h-5"></i><span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Search', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="window.location.href='<?php echo esc_url( $shop_url ); ?>'" class="flex flex-col items-center justify-center w-16 -translate-y-3 relative z-10">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-black border-2 border-[#C8A46A] shadow-[0_0_15px_rgba(200,164,106,0.4)] hover:scale-105 transition-transform duration-300">
                <svg class="w-5 h-5 text-[#C8A46A]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
            <span class="text-[10px] uppercase tracking-wider text-[#C8A46A] mt-0.5"><?php esc_html_e( 'Shop', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="window.location.href='<?php echo esc_url( $wishlist_url ); ?>'" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors relative">
            <i data-lucide="heart" class="w-5 h-5"></i>
            <?php
            $wishlist_count = function_exists( 'dt_get_wishlist_count' ) ? dt_get_wishlist_count() : 0;
            if ( $wishlist_count > 0 ) {
                echo '<span class="absolute top-0 right-3 bg-[#C8A46A] text-black text-[8px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full">' . esc_html( $wishlist_count ) . '</span>';
            }
            ?>
            <span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Wishlist', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button data-bag-toggle id="mobile-bottom-cart-btn" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors relative">
            <div class="relative">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <?php $cart_count = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0; ?>
                <span class="cart-badge absolute -top-1 -right-2 bg-[#C8A46A] text-black text-[9px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full <?php echo $cart_count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html( $cart_count ); ?></span>
            </div>
            <span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Cart', 'dt-ecommerce-theme' ); ?></span>
        </button>
    </div>
</div>

<div id="cart-drawer-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[70] transition-opacity hidden" onclick="closeCartDrawer()">
    <div id="cart-drawer-panel" class="absolute top-0 right-0 w-full max-w-md h-full bg-[#0a0a0a] border-l border-[#C8A46A]/20 flex flex-col shadow-2xl translate-x-full transition-transform duration-300" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-[#C8A46A]/10 flex items-center justify-between">
            <div>
                <h3 class="font-serif text-xl text-[#C8A46A] font-semibold tracking-wider"><?php esc_html_e( 'Your Shopping Cart', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-[10px] text-[#666] uppercase tracking-widest mt-0.5"><?php esc_html_e( 'Curated Luxury', 'dt-ecommerce-theme' ); ?></p>
            </div>
            <button id="cart-drawer-close" onclick="closeCartDrawer()" class="text-white hover:text-[#C8A46A] transition-colors p-1" aria-label="<?php esc_attr_e( 'Close Cart', 'dt-ecommerce-theme' ); ?>">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div id="cart-drawer-items" class="flex-1 overflow-y-auto p-6 space-y-6">
            <?php if ( class_exists( 'WooCommerce' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
                <?php foreach ( WC()->cart->get_cart() as $key => $item ) : ?>
                    <?php
                    $_product = $item['data'];
                    if ( ! $_product ) {
                        continue;
                    }
                    $img = get_the_post_thumbnail_url( $item['product_id'], 'thumbnail' );
                    if ( ! $img ) {
                        $img = wc_placeholder_img_src();
                    }
                    ?>
                    <div class="flex gap-4 cart-item" data-key="<?php echo esc_attr( $key ); ?>">
                        <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $_product->get_name() ); ?>" class="w-20 h-24 object-cover border border-[#C8A46A]/15 shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-semibold text-white leading-snug mb-1"><?php echo esc_html( $_product->get_name() ); ?></h4>
                            <p class="text-[10px] text-[#C8A46A] uppercase tracking-widest mb-2"><?php echo wp_kses_post( $_product->get_price_html() ); ?></p>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] text-[#a3a3a3]"><?php esc_html_e( 'Qty:', 'dt-ecommerce-theme' ); ?> <?php echo esc_html( $item['quantity'] ); ?></span>
                                <a href="<?php echo esc_url( wc_get_cart_remove_url( $key ) ); ?>" class="text-[10px] text-red-400 hover:text-red-300"><?php esc_html_e( 'Remove', 'dt-ecommerce-theme' ); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- ── EMPTY CART STATE ── -->
                <div class="dt-ec-state">

                    <!-- Animated bag + headline -->
                    <div class="dt-ec-hero">
                        <div class="dt-ec-bag-wrap">
                            <div class="dt-ec-bag-glow"></div>
                            <svg class="dt-ec-bag-svg" viewBox="0 0 80 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4" y="28" width="72" height="60" rx="4" stroke="#C8A46A" stroke-width="2" fill="rgba(200,164,106,0.06)"/>
                                <path d="M26 28C26 18.059 32.268 10 40 10C47.732 10 54 18.059 54 28" stroke="#C8A46A" stroke-width="2" stroke-linecap="round" fill="none"/>
                                <circle cx="40" cy="55" r="8" fill="rgba(200,164,106,0.12)" stroke="rgba(200,164,106,0.5)" stroke-width="1.5"/>
                                <path d="M37 55l2 2 4-4" stroke="#C8A46A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="dt-ec-kicker">Your Bag</p>
                        <h4 class="dt-ec-headline">Looks a little empty</h4>
                        <p class="dt-ec-sub">Discover our curated collections and find something you'll love.</p>
                        <a href="<?php echo esc_url( $shop_url ); ?>" class="dt-ec-cta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                            Start Shopping
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="dt-ec-badges">
                        <div class="dt-ec-badge">
                            <div class="dt-ec-badge-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m0 0h2l4 4v4h-6m0 0a2 2 0 11-4 0 2 2 0 014 0zm-10 0a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <span class="dt-ec-badge-title">Fast Delivery</span>
                                <span class="dt-ec-badge-sub">2–5 business days</span>
                            </div>
                        </div>
                        <div class="dt-ec-badge">
                            <div class="dt-ec-badge-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <span class="dt-ec-badge-title">Premium Quality</span>
                                <span class="dt-ec-badge-sub">Handpicked fabrics</span>
                            </div>
                        </div>
                        <div class="dt-ec-badge">
                            <div class="dt-ec-badge-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <span class="dt-ec-badge-title">24/7 Support</span>
                                <span class="dt-ec-badge-sub">Always here for you</span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Selling Recommendations Slider -->
                    <?php
                    $rec_args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => 8,
                        'post_status'    => 'publish',
                        'meta_key'       => 'total_sales',
                        'orderby'        => 'meta_value_num',
                        'order'          => 'DESC',
                        'meta_query'     => array( array( 'key' => '_stock_status', 'value' => 'instock' ) ),
                    );
                    $rec_query = new WP_Query( $rec_args );
                    if ( $rec_query->have_posts() ) :
                    ?>
                    <div class="dt-rec-section">
                        <div class="dt-rec-header">
                            <span class="dt-rec-kicker">✦ Trending Now</span>
                            <h5 class="dt-rec-title">Top Sellers</h5>
                            <div class="dt-rec-nav">
                                <button class="dt-rec-btn" id="dt-rec-prev" aria-label="Previous">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <button class="dt-rec-btn" id="dt-rec-next" aria-label="Next">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="dt-rec-track-wrap" id="dt-rec-track-wrap">
                            <div class="dt-rec-track" id="dt-rec-track">
                                <?php while ( $rec_query->have_posts() ) : $rec_query->the_post();
                                    global $product;
                                    if ( ! $product || ! $product->is_visible() ) continue;
                                    $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
                                    if ( ! $thumb_url ) $thumb_url = wc_placeholder_img_src();
                                    $price_html = $product->get_price_html();
                                    $cats = get_the_terms( get_the_ID(), 'product_cat' );
                                    $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
                                ?>
                                <div class="dt-rec-card">
                                    <a href="<?php the_permalink(); ?>" class="dt-rec-img-wrap">
                                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="dt-rec-img" loading="lazy">
                                        <div class="dt-rec-img-overlay">
                                            <span class="dt-rec-view">View</span>
                                        </div>
                                    </a>
                                    <div class="dt-rec-info">
                                        <?php if ( $cat_name ) : ?>
                                        <span class="dt-rec-cat"><?php echo esc_html( $cat_name ); ?></span>
                                        <?php endif; ?>
                                        <h6 class="dt-rec-name"><?php the_title(); ?></h6>
                                        <div class="dt-rec-price-row">
                                            <span class="dt-rec-price"><?php echo wp_kses_post( $price_html ); ?></span>
                                            <?php if ( class_exists('WooCommerce') ) : ?>
                                            <button
                                                class="dt-rec-add"
                                                onclick="addToCart(<?php echo esc_js( get_the_ID() ); ?>, this)"
                                                aria-label="Add to cart"
                                                title="Add to bag"
                                            >
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>

                        <!-- Dots -->
                        <div class="dt-rec-dots" id="dt-rec-dots"></div>
                    </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        </div>
        <?php if ( class_exists( 'WooCommerce' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
        <div id="cart-drawer-footer" class="border-t border-[#C8A46A]/10 bg-[#0d0d0d]">
            <!-- Mini trust strip -->
            <div class="dt-drawer-trust-strip">
                <span><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="2"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m0 0h2l4 4v4h-6m0 0a2 2 0 11-4 0 2 2 0 014 0zm-10 0a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/></svg> Fast Delivery</span>
                <span class="dt-trust-dot">✦</span>
                <span><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-linecap="round" stroke-linejoin="round"/></svg> Secure Payment</span>
                <span class="dt-trust-dot">✦</span>
                <span><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke-linecap="round" stroke-linejoin="round"/></svg> 24/7 Support</span>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4 text-sm">
                    <span class="text-[#a3a3a3] text-xs uppercase tracking-widest font-medium"><?php esc_html_e( 'Subtotal', 'dt-ecommerce-theme' ); ?></span>
                    <span id="cart-drawer-subtotal" class="text-[#C8A46A] font-bold text-lg"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
                </div>
                <div class="space-y-2.5">
                    <a href="<?php echo esc_url( $cart_url ); ?>" class="block w-full text-center border border-[#C8A46A]/60 text-[#C8A46A] hover:bg-[#C8A46A]/10 py-3 uppercase tracking-widest text-[11px] font-semibold transition-all"><?php esc_html_e( 'View Cart', 'dt-ecommerce-theme' ); ?></a>
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="dt-checkout-cta block w-full text-center py-3.5 uppercase tracking-widest text-[11px] font-bold transition-all"><?php esc_html_e( 'Proceed to Checkout', 'dt-ecommerce-theme' ); ?></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php wp_footer(); ?>

<script>
(function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    function openCartDrawer() {
        const overlay = document.getElementById('cart-drawer-overlay');
        const panel = document.getElementById('cart-drawer-panel');
        if (overlay && panel) {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
            document.body.style.overflow = 'hidden';
        }
    }

    window.closeCartDrawer = function() {
        const overlay = document.getElementById('cart-drawer-overlay');
        const panel = document.getElementById('cart-drawer-panel');
        if (overlay && panel) {
            panel.classList.add('translate-x-full');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            document.body.style.overflow = '';
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        document.querySelectorAll('[data-bag-toggle], #cart-drawer-toggle, #cart-drawer-toggle-mobile, #mobile-bottom-cart-btn').forEach(function(el) {
            el.addEventListener('click', openCartDrawer);
        });

        document.querySelectorAll('.footer-accordion-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                if (window.matchMedia('(min-width: 768px)').matches) return;
                const section = toggle.closest('.footer-accordion');
                if (!section) return;
                const isOpen = section.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        const messages = document.querySelectorAll('.announcement-message');
        if (messages.length > 1) {
            let msgIndex = 0;
            setInterval(function() {
                messages[msgIndex].classList.add('opacity-0', '-translate-y-4');
                messages[msgIndex].classList.remove('opacity-100', 'translate-y-0');
                msgIndex = (msgIndex + 1) % messages.length;
                messages[msgIndex].classList.remove('opacity-0', '-translate-y-4');
                messages[msgIndex].classList.add('opacity-100', 'translate-y-0');
            }, 3500);
        }

        const backToTop = document.getElementById('back-to-top');
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 600) backToTop.classList.add('visible');
                else backToTop.classList.remove('visible');
            }, { passive: true });
        }

        if (typeof setupScrollReveal === 'function') setupScrollReveal();

        window.handleNewsletterSubmit = function(e) {
            e.preventDefault();
            const emailEl = e.target.querySelector('input[type="email"]');
            const successEl = document.getElementById('newsletter-success');
            const email = emailEl ? emailEl.value : '';
            if (emailEl) emailEl.value = '';
            fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=dt_newsletter_subscribe&nonce=<?php echo esc_js( wp_create_nonce( 'dt_newsletter' ) ); ?>&email=' + encodeURIComponent(email)
            });
            if (successEl) {
                successEl.classList.remove('hidden');
                successEl.style.display = 'flex';
                setTimeout(function() {
                    successEl.classList.add('hidden');
                    successEl.style.display = 'none';
                }, 6000);
            }
        };
    });

    window.scrollToTop = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    /* ── Recommendation Slider (empty cart) ── */
    (function initRecSlider() {
        var track     = document.getElementById('dt-rec-track');
        var wrap      = document.getElementById('dt-rec-track-wrap');
        var prevBtn   = document.getElementById('dt-rec-prev');
        var nextBtn   = document.getElementById('dt-rec-next');
        var dotsWrap  = document.getElementById('dt-rec-dots');
        if (!track || !wrap) return;

        var cards     = track.querySelectorAll('.dt-rec-card');
        var total     = cards.length;
        if (total === 0) return;

        var visCount  = 2;           // cards visible at once
        var cardW     = 0;
        var gap       = 10;
        var current   = 0;
        var maxIdx    = Math.max(0, total - visCount);
        var autoTimer = null;

        function measure() {
            if (cards[0]) {
                cardW = cards[0].getBoundingClientRect().width;
            }
        }

        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            var pages = Math.ceil(total / visCount);
            for (var i = 0; i < pages; i++) {
                var d = document.createElement('button');
                d.className = 'dt-rec-dot' + (i === 0 ? ' active' : '');
                d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                d.dataset.idx = i * visCount;
                d.addEventListener('click', function() {
                    goTo(parseInt(this.dataset.idx));
                });
                dotsWrap.appendChild(d);
            }
        }

        function updateDots() {
            if (!dotsWrap) return;
            var page = Math.floor(current / visCount);
            dotsWrap.querySelectorAll('.dt-rec-dot').forEach(function(d, i) {
                d.classList.toggle('active', i === page);
            });
        }

        function goTo(idx) {
            current = Math.max(0, Math.min(idx, maxIdx));
            measure();
            track.style.transform = 'translateX(-' + (current * (cardW + gap)) + 'px)';
            updateDots();
            if (prevBtn) prevBtn.disabled = current === 0;
            if (nextBtn) nextBtn.disabled = current >= maxIdx;
        }

        function startAuto() {
            stopAuto();
            autoTimer = setInterval(function() {
                var next = current + visCount;
                if (next > maxIdx) next = 0;
                goTo(next);
            }, 3400);
        }

        function stopAuto() {
            if (autoTimer) clearInterval(autoTimer);
        }

        /* Touch / swipe */
        var touchStartX = 0;
        wrap.addEventListener('touchstart', function(e) {
            touchStartX = e.touches[0].clientX;
            stopAuto();
        }, { passive: true });
        wrap.addEventListener('touchend', function(e) {
            var dx = touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(dx) > 40) {
                goTo(dx > 0 ? current + 1 : current - 1);
            }
            startAuto();
        }, { passive: true });

        /* Mouse drag */
        var mouseStartX = 0, isDragging = false;
        wrap.addEventListener('mousedown', function(e) { mouseStartX = e.clientX; isDragging = true; stopAuto(); });
        window.addEventListener('mouseup', function(e) {
            if (!isDragging) return;
            isDragging = false;
            var dx = mouseStartX - e.clientX;
            if (Math.abs(dx) > 40) goTo(dx > 0 ? current + 1 : current - 1);
            startAuto();
        });

        if (prevBtn) prevBtn.addEventListener('click', function() { goTo(current - 1); stopAuto(); startAuto(); });
        if (nextBtn) nextBtn.addEventListener('click', function() { goTo(current + 1); stopAuto(); startAuto(); });

        /* Init */
        measure();
        buildDots();
        goTo(0);
        startAuto();

        /* Re-measure on resize */
        window.addEventListener('resize', function() {
            measure();
            maxIdx = Math.max(0, total - visCount);
            goTo(Math.min(current, maxIdx));
        }, { passive: true });
    })();
})();
</script>
<?php
$after_body_html = dt_get_theme_option( 'after_body_html' );
if ( ! empty( $after_body_html ) ) {
    echo $after_body_html;
}
?>
</body>
</html>
