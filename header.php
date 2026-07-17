<?php
/**
 * The header for our theme — 100% matches header.html
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $ga_id = dt_get_theme_option( 'google_analytics_id' );
    if ( ! empty( $ga_id ) ) :
        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js( $ga_id ); ?>');
        </script>
        <?php
    endif;

    $fb_pixel = dt_get_theme_option( 'facebook_pixel_id' );
    if ( ! empty( $fb_pixel ) ) :
        ?>
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo esc_js( $fb_pixel ); ?>');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_attr( $fb_pixel ); ?>&ev=PageView&noscript=1"/></noscript>
        <!-- End Meta Pixel Code -->
        <?php
    endif;

    $head_scripts = dt_get_theme_option( 'head_scripts' );
    if ( ! empty( $head_scripts ) ) {
        echo $head_scripts;
    }
    ?>
    <?php wp_head(); ?>
    <style>
        .diagonal-thread-pattern {
            background-image: repeating-linear-gradient(45deg, #C8A46A 0, #C8A46A 1px, transparent 1px, transparent 22px);
        }
        .diagonal-thread-pattern-reverse {
            background-image: repeating-linear-gradient(-45deg, rgba(200,164,106,0.03) 0, rgba(200,164,106,0.03) 1px, transparent 1px, transparent 24px);
        }
        .delay-0 { animation-delay: 0s; }
        .delay-08 { animation-delay: 0.08s; }
        .delay-16 { animation-delay: 0.16s; }
        .delay-24 { animation-delay: 0.24s; }
        .delay-32 { animation-delay: 0.32s; }
        .delay-40 { animation-delay: 0.4s; }
    </style>
</head>
<body <?php body_class( 'min-h-screen bg-black text-[#F7F4EE] font-sans selection:bg-[#C8A46A] selection:text-black overflow-x-hidden pb-24 md:pb-0' ); ?>>
<?php wp_body_open(); ?>

<?php
// Output "Before Body HTML" configured in theme options
$before_body_html = dt_get_theme_option( 'before_body_html' );
if ( ! empty( $before_body_html ) ) {
    echo $before_body_html;
}

// Elementor Pro / Theme Builder header override check
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
    return;
}
?>

<?php if ( dt_get_theme_option( 'loader_enabled', '1' ) === '1' ) : ?>
    <!-- Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-[9999] bg-black flex items-center justify-center transition-opacity duration-500">
        <div class="relative flex flex-col items-center">
            <div class="w-12 h-12 border-2 border-[#C8A46A]/20 border-t-2 border-t-[#C8A46A] rounded-full animate-spin mb-4"></div>
            <span class="text-[10px] uppercase tracking-[0.3em] text-[#C8A46A] animate-pulse">Loading Atelier...</span>
        </div>
    </div>
    <script>
        // Use requestAnimationFrame for instant visual fade, then remove from DOM
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.transition = 'opacity 0.3s ease';
                requestAnimationFrame(function() {
                    loader.style.opacity = '0';
                    setTimeout(() => { loader.style.display = 'none'; }, 300);
                });
            }
        });
        // Also hide loader on DOMContentLoaded if load fires late (slow images)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const loader = document.getElementById('page-loader');
                if (loader && loader.style.opacity !== '0') {
                    loader.style.transition = 'opacity 0.3s ease';
                    loader.style.opacity = '0';
                    setTimeout(() => { loader.style.display = 'none'; }, 300);
                }
            }, 1800);
        });
    </script>
<?php endif; ?>

    <?php if ( dt_get_theme_option( 'header_top_bar', '1' ) === '1' ) : ?>
    <!-- Announcement Bar -->
    <div class="bg-[#0a0a0a] border-b border-[#C8A46A]/20 h-10 flex items-center justify-center overflow-hidden relative z-50">
        <div id="announcement-bar-content" class="relative w-full text-center h-full flex items-center justify-center">
            <?php
            $ann_str = dt_get_theme_option( 'announcement_messages', 'Free Shipping on orders above ₹999,Premium Quality Sarees,Direct from Manufacturer,Secure Payments,Easy Returns' );
            $ann_arr = array_map( 'trim', explode( ',', $ann_str ) );
            foreach ( $ann_arr as $idx => $msg ) :
                $ac = ( 0 === $idx ) ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform -translate-y-4';
                ?>
                <p class="announcement-message absolute text-xs md:text-sm tracking-widest text-[#C8A46A] uppercase transition-all duration-700 ease-in-out <?php echo esc_attr( $ac ); ?>"><?php echo esc_html( $msg ); ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ================================================================
         GLOBAL HEADER — Sticky
    ================================================================ -->
    <?php
    $sticky_header = dt_get_theme_option( 'sticky_header', '1' ) === '1';
    $header_class  = $sticky_header ? 'sticky top-0 z-50 bg-[#0a0a0a]/95 backdrop-blur-md border-b border-[#C8A46A]/20' : 'relative z-50 bg-[#0a0a0a]/95 border-b border-[#C8A46A]/20';
    
    // Transparent header option overlay on homepage hero
    if ( ( dt_get_theme_option( 'header_transparent', '0' ) === '1' ) && is_front_page() ) {
        $header_class = $sticky_header ? 'sticky top-0 z-50 bg-transparent border-b-0 absolute w-full' : 'relative z-50 bg-transparent border-b-0 absolute w-full';
    }

    $tagline = dt_get_theme_option( 'header_tagline' );
    if ( ! $tagline ) {
        $tagline = get_bloginfo( 'description' );
    }
    
    $location = dt_get_theme_option( 'header_location', 'Mumbai 400001' );
    $logo_height = dt_get_theme_option( 'logo_height', '40' );
    if ( ! $logo_height ) {
        $logo_height = '40';
    }
    ?>
    <header class="<?php echo esc_attr( $header_class ); ?>">

        <!-- ── DESKTOP HEADER ─────────────────────────────────────────── -->
        <div class="hidden md:flex max-w-7xl mx-auto px-4 h-16 items-center justify-between gap-4">

            <!-- Desktop Left: Logo & Location -->
            <div class="flex items-center gap-6 shrink-0">
                <?php
                $logo_url  = dt_get_theme_option( 'logo_url' );
                $home_url  = esc_url( home_url( '/' ) );
                ?>
                <div onclick="window.location.href='<?php echo $home_url; ?>'" class="flex flex-col cursor-pointer group">
                    <?php if ( $logo_url ) : ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="object-contain animate-logo" style="height: <?php echo esc_attr( $logo_height ); ?>px;">
                    <?php else : ?>
                        <h1 class="font-serif text-2xl md:text-3xl tracking-widest text-[#C8A46A] group-hover:scale-105 transition-transform duration-500"><?php bloginfo( 'name' ); ?></h1>
                        <h2 class="dt-logo-tagline text-[0.4rem] md:text-[0.5rem] tracking-[0.3em] text-[#C8A46A]/80 uppercase mt-0.5"><?php echo esc_html( $tagline ); ?></h2>
                    <?php endif; ?>
                </div>
                <!-- Location Widget -->
                <div class="hidden lg:flex items-center gap-2 text-xs border border-transparent hover:border-[#C8A46A]/30 px-2 py-1.5 cursor-pointer">
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#C8A46A]"></i>
                    <div class="flex flex-col">
                        <span class="text-gray-500 text-[9px] uppercase">Deliver to</span>
                        <span class="dt-header-location text-white font-medium"><?php echo esc_html( $location ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Desktop Center: Search Bar -->
            <div class="flex-1 max-w-xl relative animate-fade-in" id="header-search-wrap">
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="flex w-full rounded-sm overflow-hidden border border-[#C8A46A]/40 focus-within:border-[#C8A46A] focus-within:ring-1 focus-within:ring-[#C8A46A] bg-[#111] transition-all">
                    <?php
                    /* id="header-search-category" is read by the AJAX search JS to filter by fabric */
                    ?>
                    <select id="header-search-category" name="product_cat" title="Select Category" aria-label="Select Category" class="bg-[#1A1A1A] text-[#F7F4EE] px-3 text-xs outline-none border-r border-[#C8A46A]/20 cursor-pointer hover:bg-[#222] transition-colors font-medium">
                        <option value="all"><?php esc_html_e( 'All Fabrics', 'dt-ecommerce-theme' ); ?></option>
                        <?php
                        $cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
                        if ( ! is_wp_error( $cats ) ) {
                            foreach ( $cats as $cat ) {
                                echo '<option value="' . esc_attr( $cat->slug ) . '">' . esc_html( $cat->name ) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <?php
                    $search_placeholder = dt_get_theme_option( 'search_placeholder', 'Search sarees, fabrics, colors...' );
                    ?>
                    <?php /* autocomplete="new-password" prevents Chrome/Edge from showing browser history */ ?>
                    <input type="text" name="s" id="header-search-input"
                           placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                           class="flex-1 bg-transparent text-[#F7F4EE] px-4 py-2 outline-none placeholder:text-[#F7F4EE]/30 text-sm font-light"
                           autocomplete="new-password"
                           spellcheck="false"
                           value="<?php echo esc_attr( get_search_query() ); ?>">
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" title="Search" aria-label="Search" class="bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black px-5 flex items-center justify-center transition-all cursor-pointer">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
                <!-- AJAX Suggestions Dropdown -->
                <div id="search-suggestions" class="absolute left-0 top-full w-full bg-[#111] border border-[#C8A46A]/30 shadow-2xl z-50 rounded-b-sm hidden mt-1" style="min-width:100%;">
                    <div class="flex items-center justify-between px-3 py-2 border-b border-[#222]">
                        <span id="search-suggestions-label" class="text-[10px] uppercase tracking-widest text-[#C8A46A]"><?php esc_html_e( 'Suggested Products', 'dt-ecommerce-theme' ); ?></span>
                        <button type="button" onclick="document.getElementById('search-suggestions').classList.add('hidden')" class="text-gray-600 hover:text-[#C8A46A] transition-colors text-xs leading-none" aria-label="Close suggestions">✕</button>
                    </div>
                    <div id="suggested-products-list" class="space-y-0.5 p-2"></div>
                    <div class="px-3 py-2 border-t border-[#222] text-right">
                        <a id="search-view-all-link" href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="text-[10px] text-[#C8A46A] uppercase tracking-widest hover:underline">
                            <?php esc_html_e( 'View all results →', 'dt-ecommerce-theme' ); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Desktop Right: Account, Wishlist & Cart -->
            <?php
            $my_account_url = class_exists( 'WooCommerce' ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : home_url( '/my-account' );
            $wishlist_url   = home_url( '/wishlist' );
            $wish_count     = function_exists( 'dt_get_wishlist_count' ) ? dt_get_wishlist_count() : 0;
            $cart_count     = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
            ?>
            <div class="flex items-center gap-4 shrink-0 text-xs text-white">
                <!-- Account -->
                <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( $my_account_url ); ?>" class="flex flex-col border border-transparent hover:border-[#C8A46A]/30 px-2 py-1.5 cursor-pointer no-underline">
                    <span class="text-gray-500 text-[9px] uppercase">
                        Hello, <?php echo esc_html( wp_get_current_user()->display_name ); ?>
                    </span>
                    <span class="font-medium flex items-center gap-1 text-white">My Account <i data-lucide="user" class="w-3 h-3 text-[#C8A46A]"></i></span>
                </a>
                <?php else : ?>
                <div data-user-toggle class="flex flex-col border border-transparent hover:border-[#C8A46A]/30 px-2 py-1.5 cursor-pointer">
                    <span class="text-gray-500 text-[9px] uppercase">Hello, Sign In</span>
                    <span class="font-medium flex items-center gap-1">Account &amp; Lists <i data-lucide="chevron-down" class="w-3 h-3"></i></span>
                </div>
                <?php endif; ?>

                <!-- Wishlist -->
                <div onclick="window.location.href='<?php echo esc_url( $wishlist_url ); ?>'" class="relative flex flex-col border border-transparent hover:border-[#C8A46A]/30 px-2 py-1.5 cursor-pointer">
                    <span class="text-gray-500 text-[9px] uppercase">Wishlist</span>
                    <span class="font-medium flex items-center gap-1 text-white">
                        <i data-lucide="heart" class="w-4 h-4 text-[#C8A46A]"></i>
                        My Saved
                        <span class="wishlist-badge bg-[#C8A46A] text-black text-[9px] font-bold px-1.5 rounded-full <?php echo $wish_count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html( $wish_count ); ?></span>
                    </span>
                </div>

                <!-- Cart -->
                <div id="cart-drawer-toggle" data-bag-toggle class="relative flex items-center gap-2 border border-transparent hover:border-[#C8A46A]/30 px-2 py-1.5 cursor-pointer">
                    <div class="relative">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-[#C8A46A]"></i>
                        <span class="cart-badge absolute -top-1.5 -right-2 bg-[#C8A46A] text-black text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full"><?php echo esc_html( $cart_count ); ?></span>
                    </div>
                    <span class="font-medium uppercase tracking-wider text-[10px]">Cart</span>
                </div>
            </div>
        </div>

        <!-- ── MOBILE HEADER ──────────────────────────────────────────── -->
        <div class="md:hidden px-4 h-16 flex items-center justify-between gap-4 w-full">

            <!-- LAYOUT 1: HOME PAGE (Logo left + account & cart right) -->
            <div id="mobile-header-home" class="<?php echo is_front_page() ? 'flex' : 'hidden'; ?> items-center justify-between w-full h-full">
                <div onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'" class="flex flex-col cursor-pointer">
                    <?php if ( $logo_url ) : 
                        $mobile_height = max( 24, intval( $logo_height ) * 0.8 );
                        ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="object-contain animate-logo" style="height: <?php echo esc_attr( $mobile_height ); ?>px;">
                    <?php else : ?>
                        <h1 class="font-serif text-xl tracking-widest text-[#C8A46A] font-bold"><?php bloginfo( 'name' ); ?></h1>
                        <h2 class="text-[0.35rem] tracking-[0.25em] text-[#C8A46A]/80 uppercase"><?php bloginfo( 'description' ); ?></h2>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-3">
                    <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( $my_account_url ); ?>" class="p-1 cursor-pointer">
                        <i data-lucide="user-check" class="w-5 h-5 text-[#C8A46A]"></i>
                    </a>
                    <?php else : ?>
                    <div data-user-toggle class="p-1 cursor-pointer">
                        <i data-lucide="user" class="w-5 h-5 text-[#C8A46A]"></i>
                    </div>
                    <?php endif; ?>
                    <div data-bag-toggle id="cart-drawer-toggle-mobile" class="relative p-1 cursor-pointer">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-[#C8A46A]"></i>
                        <span class="cart-badge absolute top-0 right-0 bg-[#C8A46A] text-black text-[8px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full <?php echo $cart_count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html( $cart_count ); ?></span>
                    </div>
                </div>
            </div>

            <!-- LAYOUT 2: SHOP PAGE (Back button + search + cart) -->
            <?php 
            $filter_value = isset( $_GET['filter'] ) ? sanitize_key( wp_unslash( $_GET['filter'] ) ) : '';
            $fabric_value = isset( $_GET['fabric'] ) ? sanitize_text_field( wp_unslash( $_GET['fabric'] ) ) : '';
            $category_value = isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';
            $is_shop = ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) || is_search(); 
            $is_all_active = class_exists( 'WooCommerce' ) && is_shop() && ! is_product_category() && empty( $filter_value ) && ! is_search();
            $is_new_active = 'new' === $filter_value;
            $current_cat_slug = is_product_category() ? get_queried_object()->slug : '';
            ?>
            <div id="mobile-header-shop" class="<?php echo $is_shop ? 'flex' : 'hidden'; ?> items-center gap-3 w-full h-full">
                <button onclick="window.history.back()" title="Go Back" aria-label="Go Back" class="text-[#C8A46A] hover:text-[#F7F4EE] transition-colors p-1 shrink-0">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <div class="flex-1 relative">
                    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="flex w-full rounded-sm overflow-hidden border border-[#C8A46A]/40 focus-within:border-[#C8A46A] bg-[#111] h-9">
                        <input type="text" name="s" id="mobile-search-input" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" class="flex-1 bg-transparent text-[#F7F4EE] px-3 outline-none placeholder:text-[#F7F4EE]/30 text-xs font-light" autocomplete="new-password" spellcheck="false" value="<?php echo esc_attr( get_search_query() ); ?>">
                        <input type="hidden" name="post_type" value="product">
                        <button type="submit" title="Search" aria-label="Search" class="bg-gradient-to-r from-[#b08d55] to-[#d8ba82] text-black px-3 flex items-center justify-center">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
                <div data-bag-toggle class="relative p-1 cursor-pointer shrink-0">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#C8A46A]"></i>
                    <span class="cart-badge absolute top-0 right-0 bg-[#C8A46A] text-black text-[8px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full <?php echo $cart_count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html( $cart_count ); ?></span>
                </div>
            </div>

            <!-- LAYOUT 3: PRODUCT & INNER PAGES (Back + centered logo + cart) -->
            <?php $is_inner = ! is_front_page() && ! $is_shop; ?>
            <div id="mobile-header-product" class="<?php echo $is_inner ? 'flex' : 'hidden'; ?> items-center justify-between w-full h-full relative">
                <button onclick="window.history.back()" title="Go Back" aria-label="Go Back" class="text-[#C8A46A] hover:text-[#F7F4EE] transition-colors p-1 z-10 shrink-0">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <div onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-auto cursor-pointer">
                    <?php if ( $logo_url ) : 
                        $mobile_height = max( 24, intval( $logo_height ) * 0.8 );
                        ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="object-contain animate-logo" style="height: <?php echo esc_attr( $mobile_height ); ?>px;">
                    <?php else : ?>
                        <h1 class="font-serif text-lg tracking-widest text-[#C8A46A] font-bold"><?php bloginfo( 'name' ); ?></h1>
                        <h2 class="text-[0.32rem] tracking-[0.22em] text-[#C8A46A]/80 uppercase"><?php bloginfo( 'description' ); ?></h2>
                    <?php endif; ?>
                </div>
                <div data-bag-toggle class="relative p-1 cursor-pointer z-10 shrink-0">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-[#C8A46A]"></i>
                    <span class="cart-badge absolute top-0 right-0 bg-[#C8A46A] text-black text-[8px] font-bold w-3.5 h-3.5 flex items-center justify-center rounded-full <?php echo $cart_count > 0 ? '' : 'hidden'; ?>"><?php echo esc_html( $cart_count ); ?></span>
                </div>
            </div>
        </div>

        <!-- ── SUB-NAV ────────────────────────────────────────────────── -->
        <div class="sub-nav-wrap relative bg-[#0a0a0a] border-t border-[#C8A46A]/10">
            <div class="sub-nav-fade-left md:hidden pointer-events-none"></div>
            <div class="sub-nav-fade-right md:hidden pointer-events-none"></div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex bg-[#111] items-center justify-between px-8 h-10 text-[11px] font-medium uppercase tracking-wider">
                <div class="flex items-center gap-6 whitespace-nowrap">
                    <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ); ?>" class="flex items-center gap-1.5 text-[#C8A46A] hover:text-white transition-colors">
                        <i data-lucide="menu" class="w-4 h-4"></i> All Collection
                    </a>
                    <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? add_query_arg( 'filter', 'new', get_permalink( wc_get_page_id( 'shop' ) ) ) : home_url( '/shop?filter=new' ) ); ?>" class="hover:text-[#C8A46A] transition-colors">New Arrivals</a>
                    <?php
                    // Dynamic categories
                    if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
                        $main_cats = array_slice( $cats, 0, 4 );
                        foreach ( $main_cats as $cat ) {
                            echo '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="hover:text-[#C8A46A] transition-colors">' . esc_html( $cat->name ) . '</a>';
                        }
                    } else {
                        // Fallback static links
                        echo '<a href="' . esc_url( home_url( '/shop' ) ) . '?fabric=Banarasi" class="hover:text-[#C8A46A] transition-colors">Banarasi</a>';
                        echo '<a href="' . esc_url( home_url( '/shop' ) ) . '?fabric=Silk" class="hover:text-[#C8A46A] transition-colors">Kanjeevaram</a>';
                    }
                    ?>
                    <a href="<?php echo esc_url( $wishlist_url ); ?>" class="hover:text-[#C8A46A] transition-colors">Wishlist</a>
                    <a href="<?php echo esc_url( home_url( '/track-order' ) ); ?>" class="hover:text-[#C8A46A] transition-colors">Track Order</a>
                    <a href="<?php echo esc_url( home_url( '/contact-us' ) ); ?>" class="hover:text-[#C8A46A] transition-colors">Contact</a>
                </div>
                <div class="shrink-0 text-[#C8A46A]/80 text-[10px] tracking-widest font-semibold uppercase">
                    ⚡ <?php echo esc_html( dt_get_theme_option( 'sale_banner_text', 'Festive Sale — Code: FESTIVE40 for 40% Off' ) ); ?>
                </div>
            </div>

            <!-- Mobile: Pill Category Slider -->
            <div class="md:hidden">
                <div class="cat-slider flex items-center gap-2 overflow-x-auto no-scrollbar px-4 py-2.5 snap-x scroll-smooth">
                    <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ); ?>" class="cat-chip <?php echo $is_all_active ? 'cat-chip-primary' : ''; ?> snap-start">
                        <i data-lucide="grid-3x3" class="w-3.5 h-3.5"></i><span>All</span>
                    </a>
                    <a href="<?php echo esc_url( add_query_arg( 'filter', 'new', class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ) ); ?>" class="cat-chip <?php echo $is_new_active ? 'cat-chip-primary' : ''; ?> snap-start">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i><span>New</span>
                    </a>
                    <?php
                    $mobile_icons = array( 'gem', 'crown', 'feather', 'dot', 'wind', 'heart', 'star', 'tag' );
                    if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
                        $idx = 0;
                        foreach ( $cats as $cat ) {
                            $icon = isset( $mobile_icons[ $idx ] ) ? $mobile_icons[ $idx ] : 'chevron-right';
                            $is_cat_active = ( $current_cat_slug === $cat->slug );
                            $chip_class = $is_cat_active ? 'cat-chip cat-chip-primary snap-start' : 'cat-chip snap-start';
                            echo '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="' . esc_attr( $chip_class ) . '">';
                            echo '<i data-lucide="' . esc_attr( $icon ) . '" class="w-3.5 h-3.5"></i>';
                            echo '<span>' . esc_html( $cat->name ) . '</span>';
                            echo '</a>';
                            $idx++;
                        }
                    } else {
                        $fallback = array(
                            array( 'gem',    'Banarasi',   '?fabric=Banarasi' ),
                            array( 'crown',  'Kanjeevaram', '?fabric=Kanjeevaram' ),
                            array( 'feather','Organza',    '?fabric=Organza' ),
                            array( 'dot',    'Bandhani',   '?fabric=Bandhani' ),
                            array( 'wind',   'Chiffon',    '?fabric=Chiffon' ),
                            array( 'heart',  'Bridal',     '?category=Bridal' ),
                            array( 'star',   'Festive',    '?category=Festive' ),
                            array( 'tag',    'Sale 40%',   '?filter=sale' ),
                        );
                        foreach ( $fallback as $fb ) {
                            $is_fb_active = ( $fabric_value === $fb[1] ) || ( $category_value === $fb[1] ) || ( 'sale' === $filter_value && 'Sale 40%' === $fb[1] );
                            $chip_class = $is_fb_active ? 'cat-chip cat-chip-primary snap-start' : 'cat-chip snap-start';
                            echo '<a href="' . esc_url( ( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ) . $fb[2] ) . '" class="' . esc_attr( $chip_class ) . '">';
                            echo '<i data-lucide="' . esc_attr( $fb[0] ) . '" class="w-3.5 h-3.5"></i><span>' . esc_html( $fb[1] ) . '</span></a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </header><!-- end header -->

    <!-- ================================================================
         AUTH MODAL — Premium Login / Register / Forgot Password
    ================================================================ -->
    <?php if ( ! is_user_logged_in() ) :
        $logo_url_modal = dt_get_theme_option( 'logo_url' );
        $site_name_modal = get_bloginfo( 'name' );
        $wc_ajax_url = admin_url( 'admin-ajax.php' );
        $login_nonce = wp_create_nonce( 'dt_auth_login' );
        $reg_nonce   = wp_create_nonce( 'dt_auth_register' );
        $fp_nonce    = wp_create_nonce( 'dt_auth_forgot' );
    ?>
    <style>
    /* ── Auth Modal Core ─────────────────────────────────────── */
    #dt-auth-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        transition: opacity 0.35s ease;
    }
    #dt-auth-modal.dt-modal-open {
        display: flex;
        opacity: 1;
    }
    .dt-auth-box {
        background: linear-gradient(160deg, #0d0d0d 0%, #111 60%, #0a0a0a 100%);
        border: 1px solid rgba(200,164,106,0.28);
        box-shadow: 0 0 80px rgba(200,164,106,0.12), 0 40px 80px rgba(0,0,0,0.8);
        width: 100%;
        max-width: 440px;
        max-height: 92vh;
        overflow-y: auto;
        position: relative;
        border-radius: 2px;
        transform: translateY(28px) scale(0.97);
        transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), opacity 0.4s ease;
        opacity: 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(200,164,106,0.2) transparent;
    }
    #dt-auth-modal.dt-modal-open .dt-auth-box {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
    .dt-auth-box::-webkit-scrollbar { width: 4px; }
    .dt-auth-box::-webkit-scrollbar-track { background: transparent; }
    .dt-auth-box::-webkit-scrollbar-thumb { background: rgba(200,164,106,0.2); border-radius: 2px; }

    /* ── Header ─────────────────────────────────────────────── */
    .dt-auth-header {
        padding: 20px 28px 0;
        text-align: center;
        position: relative;
    }
    .dt-auth-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(200,164,106,0.15);
        color: #a3a3a3;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .dt-auth-close:hover { background: rgba(200,164,106,0.1); color: #C8A46A; border-color: rgba(200,164,106,0.4); transform: rotate(90deg); }
    .dt-auth-logo {
        max-height: 60px !important;
        height: 60px !important;
        width: auto !important;
        max-width: 200px !important;
        object-fit: contain;
        margin: 0 auto 8px;
        display: block;
    }
    .dt-auth-logo-text {
        font-family: 'Cormorant Garamond', serif;
        font-size: 20px;
        font-weight: 600;
        color: #C8A46A;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        display: block;
        margin-bottom: 2px;
    }
    .dt-auth-divider-line {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0 0;
    }
    .dt-auth-divider-line::before, .dt-auth-divider-line::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(200,164,106,0.3), transparent);
    }
    .dt-auth-diamond {
        width: 5px; height: 5px;
        background: #C8A46A;
        transform: rotate(45deg);
        flex-shrink: 0;
    }

    /* ── Tabs ───────────────────────────────────────────────── */
    .dt-auth-tabs {
        display: flex;
        border-bottom: 1px solid rgba(200,164,106,0.12);
        margin: 20px 0 0;
    }
    .dt-auth-tab {
        flex: 1;
        padding: 11px 8px;
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #555;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.25s;
        background: none;
        border-left: none;
        border-right: none;
        border-top: none;
        font-family: 'Inter', sans-serif;
    }
    .dt-auth-tab:hover { color: #a3a3a3; }
    .dt-auth-tab.active { color: #C8A46A; border-bottom-color: #C8A46A; }

    /* ── Form panels ────────────────────────────────────────── */
    .dt-auth-panel { display: none; padding: 24px 28px 28px; }
    .dt-auth-panel.active { display: block; animation: dtFadeUp 0.3s ease; }
    @keyframes dtFadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Panel heading ──────────────────────────────────────── */
    .dt-panel-heading {
        font-family: 'Cormorant Garamond', serif;
        font-size: 20px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 4px;
    }
    .dt-panel-sub {
        font-size: 11px;
        color: #666;
        margin-bottom: 20px;
        letter-spacing: 0.02em;
    }

    /* ── Field ──────────────────────────────────────────────── */
    .dt-field { margin-bottom: 14px; }
    .dt-label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #888;
        margin-bottom: 6px;
        font-family: 'Inter', sans-serif;
    }
    .dt-input-wrap { position: relative; }
    .dt-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(200,164,106,0.2);
        color: #F7F4EE;
        padding: 11px 14px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        border-radius: 2px;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        box-sizing: border-box;
    }
    .dt-input:focus {
        border-color: rgba(200,164,106,0.7);
        background: rgba(200,164,106,0.03);
        box-shadow: 0 0 0 3px rgba(200,164,106,0.08);
    }
    .dt-input::placeholder { color: #444; }
    .dt-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #555;
        pointer-events: none;
        transition: color 0.2s;
    }
    .dt-input.has-icon { padding-left: 38px; }
    .dt-input-wrap:focus-within .dt-input-icon { color: #C8A46A; }
    .dt-eye-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #555;
        cursor: pointer;
        padding: 2px;
        line-height: 1;
        transition: color 0.2s;
    }
    .dt-eye-btn:hover { color: #C8A46A; }
    .dt-input.has-icon-right { padding-right: 38px; }

    /* Role select */
    .dt-select {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(200,164,106,0.2);
        color: #F7F4EE;
        padding: 11px 14px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        border-radius: 2px;
        transition: border-color 0.2s;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23C8A46A' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
        box-sizing: border-box;
    }
    .dt-select:focus { border-color: rgba(200,164,106,0.7); box-shadow: 0 0 0 3px rgba(200,164,106,0.08); }
    .dt-select option { background: #111; color: #F7F4EE; }

    /* ── Submit Button ──────────────────────────────────────── */
    .dt-btn-gold {
        width: 100%;
        padding: 13px 20px;
        background: linear-gradient(135deg, #b08d55 0%, #C8A46A 50%, #d8ba82 100%);
        color: #000;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        border-radius: 2px;
        transition: filter 0.2s, transform 0.15s;
        font-family: 'Inter', sans-serif;
        margin-top: 4px;
        position: relative;
        overflow: hidden;
    }
    .dt-btn-gold::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.25) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.5s;
    }
    .dt-btn-gold:hover::after { transform: translateX(100%); }
    .dt-btn-gold:hover { filter: brightness(1.1); }
    .dt-btn-gold:active { transform: scale(0.98); }
    .dt-btn-gold:disabled { opacity: 0.6; cursor: not-allowed; }

    /* ── Footer links ───────────────────────────────────────── */
    .dt-auth-footer-links {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        font-size: 11px;
        gap: 8px;
    }
    .dt-auth-link {
        color: #C8A46A;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 11px;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.02em;
        padding: 0;
        transition: color 0.2s, text-decoration 0.2s;
        text-decoration: underline;
        text-decoration-color: transparent;
    }
    .dt-auth-link:hover { color: #d8ba82; text-decoration-color: #d8ba82; }

    /* ── Alert messages ─────────────────────────────────────── */
    .dt-auth-alert {
        padding: 10px 14px;
        border-radius: 2px;
        font-size: 12px;
        margin-bottom: 14px;
        display: none;
        font-family: 'Inter', sans-serif;
    }
    .dt-auth-alert.error { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; display: block; }
    .dt-auth-alert.success { background: rgba(200,164,106,0.08); border: 1px solid rgba(200,164,106,0.25); color: #C8A46A; display: block; }

    /* ── Loader spinner ─────────────────────────────────────── */
    .dt-btn-spinner {
        width: 14px; height: 14px;
        border: 2px solid rgba(0,0,0,0.3);
        border-top-color: #000;
        border-radius: 50%;
        animation: dtSpin 0.7s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 6px;
    }
    @keyframes dtSpin { to { transform: rotate(360deg); } }

    /* ── Decorative corner lines ────────────────────────────── */
    .dt-auth-corner {
        position: absolute;
        width: 18px; height: 18px;
        border-color: rgba(200,164,106,0.4);
        border-style: solid;
    }
    .dt-auth-corner.tl { top: 8px; left: 8px; border-width: 1px 0 0 1px; }
    .dt-auth-corner.tr { top: 8px; right: 8px; border-width: 1px 1px 0 0; }
    .dt-auth-corner.bl { bottom: 8px; left: 8px; border-width: 0 0 1px 1px; }
    .dt-auth-corner.br { bottom: 8px; right: 8px; border-width: 0 1px 1px 0; }
    </style>

    <!-- AUTH MODAL HTML -->
    <div id="dt-auth-modal" role="dialog" aria-modal="true" aria-label="Account Authentication">
        <div class="dt-auth-box" id="dt-auth-box" onclick="event.stopPropagation()">
            <!-- Decorative corners -->
            <span class="dt-auth-corner tl"></span>
            <span class="dt-auth-corner tr"></span>
            <span class="dt-auth-corner bl"></span>
            <span class="dt-auth-corner br"></span>

            <!-- Header -->
            <div class="dt-auth-header">
                <!-- Close button -->
                <button class="dt-auth-close" onclick="dtCloseAuthModal()" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><line x1="2" y1="2" x2="12" y2="12"/><line x1="12" y1="2" x2="2" y2="12"/></svg>
                </button>

                <!-- Logo / Brand -->
                <?php if ( $logo_url_modal ) : ?>
                    <img src="<?php echo esc_url( $logo_url_modal ); ?>" alt="<?php echo esc_attr( $site_name_modal ); ?>" class="dt-auth-logo">
                <?php else : ?>
                    <span class="dt-auth-logo-text"><?php echo esc_html( $site_name_modal ); ?></span>
                <?php endif; ?>

                <div class="dt-auth-divider-line"><span class="dt-auth-diamond"></span></div>

                <!-- Tabs -->
                <div class="dt-auth-tabs">
                    <button class="dt-auth-tab active" id="tab-login"   onclick="dtSwitchTab('login')"   type="button">Sign In</button>
                    <button class="dt-auth-tab"         id="tab-register" onclick="dtSwitchTab('register')" type="button">Register</button>
                    <button class="dt-auth-tab"         id="tab-forgot"  onclick="dtSwitchTab('forgot')"  type="button">Forgot?</button>
                </div>
            </div>

            <!-- ═══ LOGIN PANEL ═══ -->
            <div class="dt-auth-panel active" id="panel-login">
                <p class="dt-panel-heading">Welcome Back</p>
                <p class="dt-panel-sub">Sign in to your account to continue</p>

                <div class="dt-auth-alert" id="login-alert"></div>

                <form id="dt-login-form" onsubmit="dtHandleLogin(event)" autocomplete="on" novalidate>
                    <?php wp_nonce_field( 'dt_auth_login', 'login_nonce', false ); ?>

                    <!-- Email / Phone -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-login-email">Email or Phone Number</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                            <input type="text" id="dt-login-email" name="log" class="dt-input has-icon" placeholder="your@email.com or +91 9000000000" autocomplete="username" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-login-pass">Password</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" id="dt-login-pass" name="pwd" class="dt-input has-icon has-icon-right" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="dt-eye-btn" onclick="dtToggleEye('dt-login-pass',this)" aria-label="Toggle password visibility">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="dt-btn-gold" id="btn-login">Sign In to Account</button>

                    <div class="dt-auth-footer-links">
                        <button type="button" class="dt-auth-link" onclick="dtSwitchTab('forgot')">Forgot password?</button>
                        <button type="button" class="dt-auth-link" onclick="dtSwitchTab('register')">New here? Register</button>
                    </div>
                </form>
            </div>

            <!-- ═══ REGISTER PANEL ═══ -->
            <div class="dt-auth-panel" id="panel-register">
                <p class="dt-panel-heading">Create Account</p>
                <p class="dt-panel-sub">Join us to unlock exclusive prices &amp; offers</p>

                <div class="dt-auth-alert" id="register-alert"></div>

                <form id="dt-register-form" onsubmit="dtHandleRegister(event)" autocomplete="on" novalidate>
                    <?php wp_nonce_field( 'dt_auth_register', 'register_nonce', false ); ?>

                    <!-- Full Name -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-reg-name">Full Name *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            <input type="text" id="dt-reg-name" name="reg_name" class="dt-input has-icon" placeholder="e.g. Rahul Sharma" autocomplete="name" required>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-reg-phone">Phone Number *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 2.09 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <input type="tel" id="dt-reg-phone" name="reg_phone" class="dt-input has-icon" placeholder="+91 9000000000" autocomplete="tel" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-reg-email">Email Address *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                            <input type="email" id="dt-reg-email" name="email" class="dt-input has-icon" placeholder="your@email.com" autocomplete="email" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-reg-pass">Password *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" id="dt-reg-pass" name="password" class="dt-input has-icon has-icon-right" placeholder="Minimum 6 characters" autocomplete="new-password" required minlength="6">
                            <button type="button" class="dt-eye-btn" onclick="dtToggleEye('dt-reg-pass',this)" aria-label="Toggle password visibility">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Account Role -->
                    <div class="dt-field">
                        <label class="dt-label" for="dt-reg-role">Account Type *</label>
                        <select id="dt-reg-role" name="dt_user_role" class="dt-select" required>
                            <option value="">— Select Account Type —</option>
                            <option value="dt_customer">Customer (Retail)</option>
                            <option value="dt_wholesaler">Wholesaler</option>
                            <option value="dt_retailer">Retailer</option>
                            <option value="dt_reseller">Reseller</option>
                        </select>
                    </div>

                    <button type="submit" class="dt-btn-gold" id="btn-register">Create My Account</button>

                    <div class="dt-auth-footer-links">
                        <span style="color:#555;font-size:11px;">Already have an account?</span>
                        <button type="button" class="dt-auth-link" onclick="dtSwitchTab('login')">Sign In</button>
                    </div>
                </form>
            </div>

            <!-- ═══ FORGOT PASSWORD PANEL ═══ -->
            <div class="dt-auth-panel" id="panel-forgot">
                <p class="dt-panel-heading">Reset Password</p>
                <p class="dt-panel-sub">Enter your email and we'll send you a reset link</p>

                <div class="dt-auth-alert" id="forgot-alert"></div>

                <form id="dt-forgot-form" onsubmit="dtHandleForgot(event)" novalidate>
                    <?php wp_nonce_field( 'dt_auth_forgot', 'forgot_nonce', false ); ?>

                    <div class="dt-field">
                        <label class="dt-label" for="dt-forgot-email">Email Address</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                            <input type="email" id="dt-forgot-email" name="forgot_email" class="dt-input has-icon" placeholder="your@email.com" autocomplete="email" required>
                        </div>
                    </div>

                    <button type="submit" class="dt-btn-gold" id="btn-forgot">Send Reset Link</button>

                    <div class="dt-auth-footer-links">
                        <button type="button" class="dt-auth-link" onclick="dtSwitchTab('login')">&larr; Back to Sign In</button>
                    </div>
                </form>
            </div>

        </div><!-- /.dt-auth-box -->
    </div><!-- /#dt-auth-modal -->

    <script>
    /* ─── Auth Modal JS ───────────────────────────────────────── */
    var dtAjaxUrl = '<?php echo esc_js( admin_url( "admin-ajax.php" ) ); ?>';

    function dtOpenAuthModal(tab) {
        const modal = document.getElementById('dt-auth-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function(){
            requestAnimationFrame(function(){
                modal.classList.add('dt-modal-open');
            });
        });
        if (tab) dtSwitchTab(tab);
    }

    function dtCloseAuthModal() {
        const modal = document.getElementById('dt-auth-modal');
        if (!modal) return;
        modal.classList.remove('dt-modal-open');
        setTimeout(function(){ modal.style.display = 'none'; }, 380);
        document.body.style.overflow = '';
    }

    function dtSwitchTab(tab) {
        ['login','register','forgot'].forEach(function(t) {
            const btn = document.getElementById('tab-'+t);
            const panel = document.getElementById('panel-'+t);
            if (btn) btn.classList.toggle('active', t===tab);
            if (panel) panel.classList.toggle('active', t===tab);
        });
    }

    function dtToggleEye(inputId, btn) {
        const inp = document.getElementById(inputId);
        if (!inp) return;
        const isPass = inp.type === 'password';
        inp.type = isPass ? 'text' : 'password';
        const svg = btn.querySelector('svg');
        if (svg) {
            if (isPass) {
                svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    }

    function dtShowAlert(panelId, msg, type) {
        const el = document.getElementById(panelId+'-alert');
        if (!el) return;
        el.textContent = msg;
        el.className = 'dt-auth-alert ' + type;
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    function dtClearAlert(panelId) {
        const el = document.getElementById(panelId+'-alert');
        if (el) { el.textContent=''; el.className='dt-auth-alert'; }
    }
    function dtSetBtnLoading(btnId, loading, label) {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        if (loading) {
            btn.disabled = true;
            btn.innerHTML = '<span class="dt-btn-spinner"></span> Please wait...';
        } else {
            btn.disabled = false;
            btn.innerHTML = label;
        }
    }

    /* ── LOGIN ── */
    function dtHandleLogin(e) {
        e.preventDefault();
        dtClearAlert('login');
        const email = document.getElementById('dt-login-email').value.trim();
        const pass  = document.getElementById('dt-login-pass').value;
        if (!email || !pass) { dtShowAlert('login','Please fill in all fields.','error'); return; }
        dtSetBtnLoading('btn-login', true, 'Sign In to Account');
        const fd = new FormData();
        fd.append('action','dt_ajax_login');
        fd.append('log', email);
        fd.append('pwd', pass);
        fd.append('nonce', document.querySelector('#dt-login-form input[name="login_nonce"]').value);
        fetch(dtAjaxUrl, { method:'POST', body: fd })
            .then(r=>r.json()).then(function(res) {
                if (res.success) {
                    dtShowAlert('login', res.data.message || 'Logged in! Redirecting...', 'success');
                    setTimeout(function(){ window.location.href = res.data.redirect || window.location.href; }, 1000);
                } else {
                    dtShowAlert('login', res.data.message || 'Login failed. Please try again.', 'error');
                    dtSetBtnLoading('btn-login', false, 'Sign In to Account');
                }
            }).catch(function(){
                dtShowAlert('login','Network error. Please try again.','error');
                dtSetBtnLoading('btn-login', false, 'Sign In to Account');
            });
    }

    /* ── REGISTER ── */
    function dtHandleRegister(e) {
        e.preventDefault();
        dtClearAlert('register');
        const name  = document.getElementById('dt-reg-name').value.trim();
        const phone = document.getElementById('dt-reg-phone').value.trim();
        const email = document.getElementById('dt-reg-email').value.trim();
        const pass  = document.getElementById('dt-reg-pass').value;
        const role  = document.getElementById('dt-reg-role').value;
        if (!name||!phone||!email||!pass||!role) { dtShowAlert('register','Please fill in all required fields.','error'); return; }
        if (pass.length < 6) { dtShowAlert('register','Password must be at least 6 characters.','error'); return; }
        dtSetBtnLoading('btn-register', true, 'Create My Account');
        const fd = new FormData();
        fd.append('action','dt_ajax_register');
        fd.append('reg_name', name);
        fd.append('reg_phone', phone);
        fd.append('email', email);
        fd.append('password', pass);
        fd.append('dt_user_role', role);
        fd.append('nonce', document.querySelector('#dt-register-form input[name="register_nonce"]').value);
        fetch(dtAjaxUrl, { method:'POST', body: fd })
            .then(r=>r.json()).then(function(res) {
                if (res.success) {
                    dtShowAlert('register', res.data.message || 'Account created! Redirecting...', 'success');
                    setTimeout(function(){ window.location.href = res.data.redirect || window.location.href; }, 1200);
                } else {
                    dtShowAlert('register', res.data.message || 'Registration failed. Please try again.', 'error');
                    dtSetBtnLoading('btn-register', false, 'Create My Account');
                }
            }).catch(function(){
                dtShowAlert('register','Network error. Please try again.','error');
                dtSetBtnLoading('btn-register', false, 'Create My Account');
            });
    }

    /* ── FORGOT PASSWORD ── */
    function dtHandleForgot(e) {
        e.preventDefault();
        dtClearAlert('forgot');
        const email = document.getElementById('dt-forgot-email').value.trim();
        if (!email) { dtShowAlert('forgot','Please enter your email address.','error'); return; }
        dtSetBtnLoading('btn-forgot', true, 'Send Reset Link');
        const fd = new FormData();
        fd.append('action','dt_ajax_forgot_password');
        fd.append('forgot_email', email);
        fd.append('nonce', document.querySelector('#dt-forgot-form input[name="forgot_nonce"]').value);
        fetch(dtAjaxUrl, { method:'POST', body: fd })
            .then(r=>r.json()).then(function(res) {
                if (res.success) {
                    dtShowAlert('forgot', res.data.message || 'Reset link sent! Check your email.', 'success');
                    dtSetBtnLoading('btn-forgot', false, 'Send Reset Link');
                } else {
                    dtShowAlert('forgot', res.data.message || 'Could not send reset link. Try again.', 'error');
                    dtSetBtnLoading('btn-forgot', false, 'Send Reset Link');
                }
            }).catch(function(){
                dtShowAlert('forgot','Network error. Please try again.','error');
                dtSetBtnLoading('btn-forgot', false, 'Send Reset Link');
            });
    }

    /* ── Wire up trigger buttons ── */
    document.addEventListener('DOMContentLoaded', function() {
        // Close on backdrop click
        const modal = document.getElementById('dt-auth-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) dtCloseAuthModal();
            });
        }
        // Wire all [data-user-toggle] buttons to open modal
        document.querySelectorAll('[data-user-toggle]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dtOpenAuthModal('login');
            });
        });
        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') dtCloseAuthModal();
        });
    });
    </script>
    <?php endif; // end guest check ?>

    <!-- ================================================================
         MOBILE MENU DRAWER
    ================================================================ -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[90] hidden opacity-0 transition-opacity duration-300">
        <div id="mobile-menu-drawer" class="absolute top-0 left-0 w-[85vw] max-w-sm h-full bg-[#080808] border-r border-[#C8A46A]/20 flex flex-col -translate-x-full transition-transform duration-300">
            <!-- Drawer Header -->
            <div class="flex items-center justify-between p-5 border-b border-[#1a1a1a]">
                <div>
                    <h2 class="font-serif text-xl text-[#C8A46A]"><?php bloginfo( 'name' ); ?></h2>
                    <p class="text-[9px] text-[#C8A46A]/60 uppercase tracking-widest"><?php bloginfo( 'description' ); ?></p>
                </div>
                <button onclick="toggleMobileMenuDrawer(false)" class="text-[#a3a3a3] hover:text-white p-1" aria-label="Close Menu">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <!-- User Quick Info -->
            <div class="p-4 border-b border-[#1a1a1a]">
                <?php if ( is_user_logged_in() ) :
                    $u = wp_get_current_user();
                    ?>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#C8A46A] text-black font-serif font-bold flex items-center justify-center text-sm"><?php echo esc_html( strtoupper( substr( $u->display_name, 0, 1 ) ) ); ?></div>
                        <div>
                            <p class="text-white text-sm font-medium"><?php echo esc_html( $u->display_name ); ?></p>
                            <a href="<?php echo esc_url( $my_account_url ); ?>" class="text-[#C8A46A] text-[10px] uppercase tracking-widest">My Account</a>
                        </div>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url( $my_account_url ); ?>" class="flex items-center gap-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A]">
                        <i data-lucide="user" class="w-5 h-5 text-[#C8A46A]"></i>
                        <span>Sign In / Register</span>
                    </a>
                <?php endif; ?>
            </div>
            <!-- Nav Links -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="home" class="w-4 h-4"></i> Home</a>
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="shopping-bag" class="w-4 h-4"></i> Shop All</a>
                <?php
                if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
                    foreach ( $cats as $cat ) {
                        echo '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all pl-8">';
                        echo '<i data-lucide="gem" class="w-4 h-4 text-[#C8A46A]/40"></i> ' . esc_html( $cat->name ) . '</a>';
                    }
                }
                ?>
                <div class="border-t border-[#1a1a1a] pt-2 mt-2">
                    <a href="<?php echo esc_url( $wishlist_url ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="heart" class="w-4 h-4"></i> Wishlist</a>
                    <a href="<?php echo esc_url( home_url( '/track-order' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="package" class="w-4 h-4"></i> Track Order</a>
                    <a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="info" class="w-4 h-4"></i> About Us</a>
                    <a href="<?php echo esc_url( home_url( '/contact-us' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="phone" class="w-4 h-4"></i> Contact Us</a>
                    <a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="help-circle" class="w-4 h-4"></i> FAQ</a>
                    <a href="<?php echo esc_url( home_url( '/shipping-policy' ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#F7F4EE] hover:text-[#C8A46A] hover:bg-[#C8A46A]/5 border-l-2 border-transparent hover:border-[#C8A46A] transition-all"><i data-lucide="truck" class="w-4 h-4"></i> Shipping Policy</a>
                </div>
                <?php if ( is_user_logged_in() ) : ?>
                <div class="border-t border-[#1a1a1a] pt-2 mt-2">
                    <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/5 border-l-2 border-transparent transition-all"><i data-lucide="log-out" class="w-4 h-4"></i> Logout</a>
                </div>
                <?php endif; ?>
            </nav>
            <!-- Sale Banner -->
            <div class="p-4 border-t border-[#1a1a1a] bg-[#C8A46A]/5">
                <p class="text-[10px] text-[#C8A46A] uppercase tracking-widest text-center font-semibold">⚡ FESTIVE SALE — 40% OFF · Use code FESTIVE40</p>
            </div>
        </div>
    </div>

    <!-- ================================================================
         MOBILE SEARCH OVERLAY
    ================================================================ -->
    <div id="mobile-search-overlay" class="fixed inset-0 bg-black/95 backdrop-blur-lg z-[90] hidden flex flex-col p-4 pt-6">
        <div class="flex items-center justify-between mb-6">
            <span class="text-[#C8A46A] uppercase tracking-widest text-xs font-semibold">Search</span>
            <button onclick="toggleMobileSearchOverlay(false)" class="text-[#a3a3a3] hover:text-white" aria-label="Close">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="flex gap-2 mb-6">
            <input type="text" name="s" id="mobile-overlay-search" placeholder="Search sarees, fabrics, colors..." class="flex-1 bg-[#111] border border-[#C8A46A]/40 focus:border-[#C8A46A] text-white px-4 py-3 outline-none text-sm placeholder:text-[#555]" autocomplete="off">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" class="bg-[#C8A46A] text-black px-5 flex items-center justify-center">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>
        </form>
        <div class="space-y-3">
            <p class="text-[10px] uppercase tracking-widest text-[#666]">Popular Searches</p>
            <div class="flex flex-wrap gap-2">
                <?php
                $popular = array( 'Banarasi Silk', 'Kanjeevaram', 'Bridal Saree', 'Organza', 'Festive Collection' );
                foreach ( $popular as $tag ) {
                    $search_url = add_query_arg( array( 's' => urlencode( $tag ), 'post_type' => 'product' ), home_url( '/' ) );
                    echo '<a href="' . esc_url( $search_url ) . '" class="px-3 py-1.5 border border-[#333] text-xs text-[#a3a3a3] hover:border-[#C8A46A] hover:text-[#C8A46A] transition-all">' . esc_html( $tag ) . '</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- ================================================================
         SCRIPTS: Mobile Menu / Search toggles
    ================================================================ -->
    <script>
    function toggleMobileMenuDrawer(open) {
        const overlay = document.getElementById('mobile-menu-overlay');
        const drawer  = document.getElementById('mobile-menu-drawer');
        if (!overlay || !drawer) return;
        if (open) {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
                drawer.classList.remove('-translate-x-full');
            });
            document.body.style.overflow = 'hidden';
        } else {
            overlay.classList.add('opacity-0');
            overlay.classList.remove('opacity-100');
            drawer.classList.add('-translate-x-full');
            setTimeout(() => { overlay.classList.add('hidden'); }, 300);
            document.body.style.overflow = '';
        }
    }

    function toggleMobileSearchOverlay(open) {
        const el = document.getElementById('mobile-search-overlay');
        if (!el) return;
        if (open) {
            el.style.display = 'flex';
            el.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => { const inp = document.getElementById('mobile-overlay-search'); if (inp) inp.focus(); }, 100);
        } else {
            el.style.display = 'none';
            el.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Close mobile menu overlay on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('mobile-menu-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) toggleMobileMenuDrawer(false);
            });
        }
        // Note: [data-user-toggle] buttons are now handled by the Auth Modal JS above
    });
    </script>
