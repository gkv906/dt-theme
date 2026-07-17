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
$copyright        = dt_get_theme_option( 'footer_copyright', '&copy; ' . gmdate( 'Y' ) . ' ARSHMAN DESIGNS. All rights reserved.' );
$email            = dt_get_theme_option( 'contact_email', 'atelier@arshmandesigns.com' );
$phone            = dt_get_theme_option( 'contact_phone', '+91 12345 67890' );
$address          = dt_get_theme_option( 'contact_address', 'Arshman Atelier, Rathyatra Crossing, Varanasi 221010, UP' );
$footer_about     = dt_get_theme_option( 'footer_about', 'We weave your dreams into reality - curating heirloom silks and modern drapes from India\'s finest looms, since 2010.' );
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
                    <div class="flex items-baseline gap-3 mb-3">
                        <h2 class="font-serif text-3xl md:text-4xl tracking-[0.15em] text-white"><span class="text-[#C8A46A]">A</span>RSHMAN</h2>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-[#C8A46A]/60"><?php esc_html_e( 'Designs', 'dt-ecommerce-theme' ); ?></span>
                    </div>
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
                            <a href="<?php echo esc_url( $instagram ); ?>" class="social-btn social-btn-labeled" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                <span class="social-btn-label">Instagram</span>
                            </a>
                            <a href="<?php echo esc_url( $facebook ); ?>" class="social-btn social-btn-labeled" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span class="social-btn-label">Facebook</span>
                            </a>
                            <a href="<?php echo esc_url( $twitter ); ?>" class="social-btn social-btn-labeled" aria-label="Twitter / X">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                <span class="social-btn-label">X / Twitter</span>
                            </a>
                            <a href="<?php echo esc_url( $youtube ); ?>" class="social-btn social-btn-labeled" aria-label="YouTube">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                <span class="social-btn-label">YouTube</span>
                            </a>
                            <a href="<?php echo esc_url( $whatsapp ); ?>" class="social-btn social-btn-labeled social-whatsapp" aria-label="WhatsApp" target="_blank" rel="noopener">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                <span class="social-btn-label">WhatsApp</span>
                            </a>
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

<?php $hide_mobile_bottom_nav = function_exists( 'is_product' ) && is_product(); ?>
<div id="mobile-bottom-nav" class="fixed bottom-0 left-0 w-full bg-[#0a0a0a]/90 backdrop-blur-lg border-t border-[#C8A46A]/20 md:hidden z-50 px-2 py-2 safe-area-bottom <?php echo $hide_mobile_bottom_nav ? 'hidden' : ''; ?>">
    <div class="flex items-end justify-around">
        <button onclick="toggleMobileMenuDrawer(true)" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i><span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Menu', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="toggleMobileSearchOverlay(true)" class="flex flex-col items-center justify-center w-16 gap-1 text-gray-400 hover:text-white transition-colors">
            <i data-lucide="search" class="w-5 h-5"></i><span class="text-[10px] uppercase tracking-wider"><?php esc_html_e( 'Search', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="window.location.href='<?php echo esc_url( $shop_url ); ?>'" class="flex flex-col items-center justify-center w-16 -translate-y-3 relative z-10">
            <div class="font-serif text-[#C8A46A] text-2xl font-bold w-12 h-12 rounded-full flex items-center justify-center bg-black border-2 border-[#C8A46A] shadow-[0_0_15px_rgba(200,164,106,0.4)] hover:scale-105 transition-transform duration-300">A</div>
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
                <div class="flex flex-col items-center justify-center h-full text-center py-12">
                    <i data-lucide="shopping-bag" class="w-12 h-12 text-[#C8A46A]/20 mb-4"></i>
                    <p class="text-[#a3a3a3] text-sm mb-6"><?php esc_html_e( 'Your cart is empty', 'dt-ecommerce-theme' ); ?></p>
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-gold-shimmer px-8 py-3 uppercase tracking-widest text-xs font-semibold"><?php esc_html_e( 'Start Shopping', 'dt-ecommerce-theme' ); ?></a>
                </div>
            <?php endif; ?>
        </div>
        <?php if ( class_exists( 'WooCommerce' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
        <div id="cart-drawer-footer" class="p-6 border-t border-[#C8A46A]/10 bg-[#111]">
            <div class="flex items-center justify-between mb-6 text-sm">
                <span class="text-[#a3a3a3]"><?php esc_html_e( 'Subtotal', 'dt-ecommerce-theme' ); ?></span>
                <span id="cart-drawer-subtotal" class="text-white font-semibold text-lg"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
            </div>
            <div class="space-y-3">
                <a href="<?php echo esc_url( $cart_url ); ?>" class="block w-full text-center border border-[#C8A46A] text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black py-3.5 uppercase tracking-widest text-xs font-semibold transition-all"><?php esc_html_e( 'View Cart', 'dt-ecommerce-theme' ); ?></a>
                <a href="<?php echo esc_url( $checkout_url ); ?>" class="block w-full text-center bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black py-3.5 uppercase tracking-widest text-xs font-bold transition-all"><?php esc_html_e( 'Proceed to Checkout', 'dt-ecommerce-theme' ); ?></a>
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
})();
</script>
<?php
$after_body_html = dt_get_theme_option( 'after_body_html' );
if ( ! empty( $after_body_html ) ) {
    echo $after_body_html;
}
?>

<!-- ================================================================
     MOBILE BOTTOM NAV — Definitive button bindings.
     This runs AFTER wp_footer() (i.e. after main.js), so these
     overwrite any incorrect function defined by main.js.
================================================================ -->
<script>
(function () {
    'use strict';

    /* ── Core drawer toggle ───────────────────────────────────── */
    window.toggleMobileMenuDrawer = function (open) {
        var overlay = document.getElementById('mobile-menu-overlay');
        var drawer  = document.getElementById('mobile-menu-drawer');
        if (!overlay || !drawer) return;

        if (open) {
            overlay.classList.remove('hidden');
            requestAnimationFrame(function () {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
                drawer.classList.remove('-translate-x-full');
            });
            document.body.style.overflow = 'hidden';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            overlay.classList.add('opacity-0');
            overlay.classList.remove('opacity-100');
            drawer.classList.add('-translate-x-full');
            setTimeout(function () { overlay.classList.add('hidden'); }, 300);
            document.body.style.overflow = '';
        }
    };

    /* ── Core search overlay toggle ───────────────────────────── */
    window.toggleMobileSearchOverlay = function (open) {
        var el = document.getElementById('mobile-search-overlay');
        if (!el) return;
        if (open) {
            el.style.display = 'flex';
            el.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                var inp = document.getElementById('overlay-search-input');
                if (inp) { inp.value = ''; inp.focus(); }
                if (typeof renderOverlaySearchSuggestions === 'function') renderOverlaySearchSuggestions('');
            }, 80);
        } else {
            el.style.display = 'none';
            el.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    /* ── Attach via addEventListener as belt-and-braces ──────── */
    document.addEventListener('DOMContentLoaded', function () {
        var menuBtn   = document.querySelector('#mobile-bottom-nav button[onclick*="toggleMobileMenuDrawer"]');
        var searchBtn = document.querySelector('#mobile-bottom-nav button[onclick*="toggleMobileSearchOverlay"]');

        if (menuBtn) {
            menuBtn.removeAttribute('onclick');
            menuBtn.addEventListener('click', function () { window.toggleMobileMenuDrawer(true); });
        }
        if (searchBtn) {
            searchBtn.removeAttribute('onclick');
            searchBtn.addEventListener('click', function () { window.toggleMobileSearchOverlay(true); });
        }

        /* Close drawer when tapping backdrop */
        var overlay = document.getElementById('mobile-menu-overlay');
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) window.toggleMobileMenuDrawer(false);
            });
        }
    });
})();
</script>
</body>
</html>
