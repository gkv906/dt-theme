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

                    <div>
                        <div class="text-[10px] uppercase tracking-widest text-gray-500 mb-3"><?php esc_html_e( 'Follow the atelier', 'dt-ecommerce-theme' ); ?></div>
                        <div class="flex gap-2.5">
                            <a href="<?php echo esc_url( $instagram ); ?>" class="social-btn" aria-label="Instagram"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                            <a href="<?php echo esc_url( $facebook ); ?>" class="social-btn" aria-label="Facebook"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                            <a href="<?php echo esc_url( $twitter ); ?>" class="social-btn" aria-label="Twitter"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                            <a href="<?php echo esc_url( $youtube ); ?>" class="social-btn" aria-label="YouTube"><i data-lucide="youtube" class="w-4 h-4"></i></a>
                            <a href="<?php echo esc_url( $whatsapp ); ?>" class="social-btn social-whatsapp" aria-label="WhatsApp" target="_blank" rel="noopener"><i data-lucide="message-circle" class="w-4 h-4"></i></a>
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
                <div class="flex items-center gap-4 md:gap-6 text-gray-500 text-[10px] uppercase tracking-[0.15em]">
                    <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Privacy', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( home_url( '/terms-conditions' ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Terms', 'dt-ecommerce-theme' ); ?></a>
                    <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
                    <a href="<?php echo esc_url( $shipping_url ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php esc_html_e( 'Shipping', 'dt-ecommerce-theme' ); ?></a>
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
</body>
</html>
