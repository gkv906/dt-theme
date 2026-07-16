<?php
/**
 * The front page template file — 100% matches index.html
 *
 * Template Name: Front Page
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Elementor page builder compatibility hook
if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) {
    ?>
    <main id="primary" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>
    <?php
    get_footer();
    exit;
}

// Get theme options values
$hero_bg = dt_get_theme_option( 'hero_image_url', get_template_directory_uri() . '/assets/images/hero-saree.jpg' );
if ( empty( $hero_bg ) ) {
    $hero_bg = get_template_directory_uri() . '/assets/images/hero-saree.jpg';
}
$hero_subtitle = dt_get_theme_option( 'hero_badge_text', 'The Royal Heritage' );
$hero_title    = dt_get_theme_option( 'hero_heading', 'Banarasi Elegance' );
$hero_desc     = dt_get_theme_option( 'hero_subtext', 'Discover our curated collection of handcrafted silk sarees. Woven with pure zari and centuries of tradition.' );

$hero_btn1_text = dt_get_theme_option( 'hero_btn1_text', 'Shop Now' );
$hero_btn1_url  = dt_get_theme_option( 'hero_btn1_url', class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' );
if ( empty( $hero_btn1_url ) ) {
    $hero_btn1_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';
}

$hero_btn2_text = dt_get_theme_option( 'hero_btn2_text', 'Explore' );
$hero_btn2_url  = dt_get_theme_option( 'hero_btn2_url', class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' );
if ( empty( $hero_btn2_url ) ) {
    $hero_btn2_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';
}

$show_new_arrivals   = dt_get_theme_option( 'show_new_arrivals', '1' ) !== '0';
$show_top_sellers    = dt_get_theme_option( 'show_top_sellers', '1' ) !== '0';
$show_reviews        = dt_get_theme_option( 'show_reviews', '1' ) !== '0';
$show_instagram_feed = dt_get_theme_option( 'show_instagram_feed', '1' ) !== '0';


// Render Product Card closure
$render_product_card = function( $post_id, $delay_ms = 0, $is_bestseller = false ) {
    $product = wc_get_product( $post_id );
    if ( ! $product ) return;

    $price = $product->get_price();
    $mrp = $product->get_regular_price();
    $rating = $product->get_average_rating();
    if ( ! $rating ) $rating = '4.8';
    $img1 = get_the_post_thumbnail_url( $post_id, 'large' );
    if ( ! $img1 ) $img1 = wc_placeholder_img_src();
    
    $gallery_ids = $product->get_gallery_image_ids();
    $gallery_urls = array($img1);
    if ( ! empty( $gallery_ids ) ) {
        foreach ( array_slice( $gallery_ids, 0, 3 ) as $g_id ) {
            $g_url = wp_get_attachment_url( $g_id );
            if ( $g_url ) {
                $gallery_urls[] = $g_url;
            }
        }
    }
    if ( count( $gallery_urls ) < 2 ) {
        $gallery_urls[] = $img1;
    }

    $fabrics = array();
    $terms = get_the_terms( $post_id, 'product_cat' );
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $fabrics[] = $term->name;
        }
    }
    $fabric_label = ! empty( $fabrics ) ? implode( ', ', $fabrics ) : 'Silk Drape';
    
    $discount = 0;
    if ( $mrp > 0 && $price < $mrp ) {
        $discount = round( ( ( $mrp - $price ) / $mrp ) * 100 );
    }
    
    $wishlist = function_exists( 'dt_get_wishlist' ) ? dt_get_wishlist() : array();
    $in_wishlist = in_array( $post_id, $wishlist );
    $heartIconHTML = $in_wishlist
        ? '<svg class="w-5 h-5 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>'
        : '<svg class="w-5 h-5 text-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';

    $stars_html = '';
    for ( $idx = 0; $idx < 5; $idx++ ) {
        $fill_class = $idx < round( $rating ) ? 'text-[#C8A46A] fill-[#C8A46A]' : 'text-gray-600';
        $stars_html .= '<svg class="w-3 h-3 ' . $fill_class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>';
    }
    
    $badge_html = '';
    if ( $is_bestseller ) {
        $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider flex items-center gap-1"><span>★</span> Bestseller</div>';
    } else {
        $is_new = ( time() - strtotime( $product->get_date_created() ) ) < ( 30 * DAY_IN_SECONDS );
        if ( $is_new ) {
            $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider">New</div>';
        }
    }
    
    ?>
    <div onclick="window.location.href='<?php echo esc_url( get_permalink( $post_id ) ); ?>'" class="arrival-card group cursor-pointer flex flex-col gap-4 w-[240px] shrink-0 lg:w-[260px] snap-center" style="animation-delay: <?php echo esc_attr( $delay_ms ); ?>ms;">
      <div class="relative aspect-[3/4] gallery-img-wrap bg-[#111]">
        <?php echo $badge_html; ?>
        <div class="absolute top-3 right-3 z-30">
          <button data-wishlist-btn="<?php echo esc_attr( $post_id ); ?>" onclick="event.stopPropagation(); toggleWishlistAction(<?php echo esc_attr( $post_id ); ?>, this)" class="w-8 h-8 bg-black/50 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:text-[#C8A46A] transition-all border border-white/10 hover:border-[#C8A46A] hover:scale-110">
            <?php echo $heartIconHTML; ?>
          </button>
        </div>
        <?php foreach ( $gallery_urls as $idx => $url ) : ?>
            <img data-gallery-slide src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?> — view <?php echo $idx+1; ?>" class="<?php echo 0 === $idx ? 'gallery-active' : ''; ?>" loading="lazy" />
        <?php endforeach; ?>
        <div class="gallery-dots">
            <?php foreach ( $gallery_urls as $idx => $url ) : ?>
                <span data-gallery-dot class="gallery-dot <?php echo 0 === $idx ? 'active' : ''; ?>"></span>
            <?php endforeach; ?>
        </div>
        <div class="absolute bottom-0 left-0 w-full p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 z-30 bg-gradient-to-t from-black/95 to-transparent flex flex-col gap-2">
          <button onclick="event.stopPropagation(); window.location.href='<?php echo esc_url( get_permalink( $post_id ) ); ?>'" class="w-full bg-[#111]/80 hover:bg-black text-[#C8A46A] border border-[#C8A46A]/30 py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm transition-colors">View Details</button>
          <button onclick="event.stopPropagation(); addCartItemAction(<?php echo esc_attr( $post_id ); ?>, this)" class="w-full btn-premium-cart text-black py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm flex items-center justify-center gap-2">
            <i data-lucide="shopping-bag" class="w-4 h-4 icon-bag"></i> Add to Bag
          </button>
        </div>
      </div>
      <div class="flex flex-col gap-1 items-center text-center">
        <span class="text-[#C8A46A] text-[10px] md:text-xs uppercase tracking-widest"><?php echo esc_html( $fabric_label ); ?></span>
        <h4 class="font-serif text-base md:text-lg text-[#F7F4EE] arrival-title transition-colors"><?php echo esc_html( $product->get_name() ); ?></h4>
        <div class="flex items-center gap-1 my-1">
          <?php echo $stars_html; ?>
          <span class="text-[10px] text-gray-400 ml-1">(<?php echo esc_html( $rating ); ?>)</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-center">
          <span class="text-[#F7F4EE] font-medium text-sm tracking-wider">₹<?php echo number_format( $price ); ?></span>
          <?php if ( $mrp > 0 && $price < $mrp ) : ?>
              <span class="text-gray-500 line-through text-[10px]">₹<?php echo number_format( $mrp ); ?></span>
              <span class="text-[#C8A46A] text-[10px] font-semibold"><?php echo esc_html( $discount ); ?>% OFF</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
};

$render_product_grid_card = function( $post_id, $delay_ms = 0, $is_bestseller = false ) {
    $product = wc_get_product( $post_id );
    if ( ! $product ) return;

    $price = $product->get_price();
    $mrp = $product->get_regular_price();
    $rating = $product->get_average_rating();
    if ( ! $rating ) $rating = '4.8';
    $img1 = get_the_post_thumbnail_url( $post_id, 'large' );
    if ( ! $img1 ) $img1 = wc_placeholder_img_src();
    
    $gallery_ids = $product->get_gallery_image_ids();
    $gallery_urls = array($img1);
    if ( ! empty( $gallery_ids ) ) {
        foreach ( array_slice( $gallery_ids, 0, 3 ) as $g_id ) {
            $g_url = wp_get_attachment_url( $g_id );
            if ( $g_url ) {
                $gallery_urls[] = $g_url;
            }
        }
    }
    if ( count( $gallery_urls ) < 2 ) {
        $gallery_urls[] = $img1;
    }

    $fabrics = array();
    $terms = get_the_terms( $post_id, 'product_cat' );
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $fabrics[] = $term->name;
        }
    }
    $fabric_label = ! empty( $fabrics ) ? implode( ', ', $fabrics ) : 'Silk Drape';
    
    $discount = 0;
    if ( $mrp > 0 && $price < $mrp ) {
        $discount = round( ( ( $mrp - $price ) / $mrp ) * 100 );
    }
    
    $wishlist = function_exists( 'dt_get_wishlist' ) ? dt_get_wishlist() : array();
    $in_wishlist = in_array( $post_id, $wishlist );
    $heartIconHTML = $in_wishlist
        ? '<svg class="w-5 h-5 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>'
        : '<svg class="w-5 h-5 text-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';

    $stars_html = '';
    for ( $idx = 0; $idx < 5; $idx++ ) {
        $fill_class = $idx < round( $rating ) ? 'text-[#C8A46A] fill-[#C8A46A]' : 'text-gray-600';
        $stars_html .= '<svg class="w-3 h-3 ' . $fill_class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>';
    }
    
    $badge_html = '';
    if ( $is_bestseller ) {
        $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider flex items-center gap-1"><span>★</span> Bestseller</div>';
    } else {
        $is_new = ( time() - strtotime( $product->get_date_created() ) ) < ( 30 * DAY_IN_SECONDS );
        if ( $is_new ) {
            $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider">New</div>';
        }
    }
    
    ?>
    <div onclick="window.location.href='<?php echo esc_url( get_permalink( $post_id ) ); ?>'" class="arrival-card group cursor-pointer flex flex-col gap-4 w-full" style="animation-delay: <?php echo esc_attr( $delay_ms ); ?>ms;">
      <div class="relative aspect-[3/4] gallery-img-wrap bg-[#111]">
        <?php echo $badge_html; ?>
        <div class="absolute top-3 right-3 z-30">
          <button data-wishlist-btn="<?php echo esc_attr( $post_id ); ?>" onclick="event.stopPropagation(); toggleWishlistAction(<?php echo esc_attr( $post_id ); ?>, this)" class="w-8 h-8 bg-black/50 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:text-[#C8A46A] transition-all border border-white/10 hover:border-[#C8A46A] hover:scale-110">
            <?php echo $heartIconHTML; ?>
          </button>
        </div>
        <?php foreach ( $gallery_urls as $idx => $url ) : ?>
            <img data-gallery-slide src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?> — view <?php echo $idx+1; ?>" class="<?php echo 0 === $idx ? 'gallery-active' : ''; ?>" loading="lazy" />
        <?php endforeach; ?>
        <div class="gallery-dots">
            <?php foreach ( $gallery_urls as $idx => $url ) : ?>
                <span data-gallery-dot class="gallery-dot <?php echo 0 === $idx ? 'active' : ''; ?>"></span>
            <?php endforeach; ?>
        </div>
        <div class="absolute bottom-0 left-0 w-full p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 z-30 bg-gradient-to-t from-black/95 to-transparent flex flex-col gap-2">
          <button onclick="event.stopPropagation(); window.location.href='<?php echo esc_url( get_permalink( $post_id ) ); ?>'" class="w-full bg-[#111]/80 hover:bg-black text-[#C8A46A] border border-[#C8A46A]/30 py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm transition-colors">View Details</button>
          <button onclick="event.stopPropagation(); addCartItemAction(<?php echo esc_attr( $post_id ); ?>, this)" class="w-full btn-premium-cart text-black py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm flex items-center justify-center gap-2">
            <i data-lucide="shopping-bag" class="w-4 h-4 icon-bag"></i> Add to Bag
          </button>
        </div>
      </div>
      <div class="flex flex-col gap-1 items-center text-center">
        <span class="text-[#C8A46A] text-[10px] md:text-xs uppercase tracking-widest"><?php echo esc_html( $fabric_label ); ?></span>
        <h4 class="font-serif text-base md:text-lg text-[#F7F4EE] arrival-title transition-colors"><?php echo esc_html( $product->get_name() ); ?></h4>
        <div class="flex items-center gap-1 my-1">
          <?php echo $stars_html; ?>
          <span class="text-[10px] text-gray-400 ml-1">(<?php echo esc_html( $rating ); ?>)</span>
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-center">
          <span class="text-[#F7F4EE] font-medium text-sm tracking-wider">₹<?php echo number_format( $price ); ?></span>
          <?php if ( $mrp > 0 && $price < $mrp ) : ?>
              <span class="text-gray-500 line-through text-[10px]">₹<?php echo number_format( $mrp ); ?></span>
              <span class="text-[#C8A46A] text-[10px] font-semibold"><?php echo esc_html( $discount ); ?>% OFF</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
};

// ── Homepage Dynamic Section Spacings & Buffers ─────────────────────────────
$padding_arrivals    = dt_get_theme_option( 'sec_padding_arrivals', '' );
$padding_topsellers  = dt_get_theme_option( 'sec_padding_topsellers', '' );
$padding_reviews     = dt_get_theme_option( 'sec_padding_reviews', '' );

$sections = array();

// 1. Hero Banner
ob_start();
?>
<!-- Hero Section -->
    <section class="relative w-full h-[50vw] min-h-[60vh] md:h-[70vh] bg-[#050505] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img 
                src="<?php echo esc_url( $hero_bg ); ?>" 
                alt="Luxury Saree" 
                class="w-full h-full object-cover animate-hero-img opacity-80"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-transparent to-black/80"></div>
        </div>

        <div class="absolute inset-0 z-10 flex flex-col items-center justify-center text-center px-4 pt-12 md:pt-20">
            <div class="reveal-on-scroll max-w-xl">
                <span class="text-[#C8A46A] uppercase tracking-[0.4em] text-[10px] md:text-sm font-medium mb-3 md:mb-6 block">
                    <?php echo esc_html( $hero_subtitle ); ?>
                </span>
                <h1 class="dt-hero-heading font-serif text-3xl md:text-7xl lg:text-8xl text-white mb-3 md:mb-6 leading-tight">
                    <?php echo wp_kses_post( nl2br( $hero_title ) ); ?>
                </h1>
                <p class="dt-hero-subtext text-[#F7F4EE]/80 max-w-md mx-auto font-light text-xs md:text-base mb-6 md:mb-10 leading-relaxed">
                    <?php echo esc_html( $hero_desc ); ?>
                </p>
                <div class="flex flex-row items-center justify-center gap-3 w-full max-w-xs sm:max-w-sm mx-auto">
                    <a href="<?php echo esc_url( $hero_btn1_url ); ?>" class="btn-gold-shimmer py-3 px-4 uppercase tracking-widest text-[10px] font-semibold flex-1 text-center rounded-sm">
                        <?php echo esc_html( $hero_btn1_text ); ?>
                    </a>
                    <a href="<?php echo esc_url( $hero_btn2_url ); ?>" class="border border-[#C8A46A] text-[#C8A46A] py-3 px-4 uppercase tracking-widest text-[10px] font-medium hover:bg-[#C8A46A] hover:text-black transition-all flex-1 text-center rounded-sm">
                        <?php echo esc_html( $hero_btn2_text ); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide Indicators -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
            <div class="w-12 h-0.5 bg-[#C8A46A]"></div>
            <div class="w-12 h-0.5 bg-white/20 hover:bg-white/50 transition-colors cursor-pointer"></div>
            <div class="w-12 h-0.5 bg-white/20 hover:bg-white/50 transition-colors cursor-pointer"></div>
        </div>
    </section>
<?php
$sections['hero'] = ob_get_clean();

// 2. Categories
ob_start();
?>
<!-- Fabrics Category Slider -->
    <section class="pt-6 pb-16 border-b border-[#C8A46A]/10 bg-black">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10 reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php esc_html_e( 'Artisan Textures', 'dt-ecommerce-theme' ); ?></span>
                <h3 class="font-serif text-3xl md:text-5xl font-bold animate-gold-gradient select-none"><?php esc_html_e( 'Shop by Fabric', 'dt-ecommerce-theme' ); ?></h3>
                <div class="flex items-center justify-center gap-4 mt-3">
                    <div class="w-12 h-px bg-gradient-to-r from-transparent to-[#C8A46A]/40"></div>
                    <i data-lucide="sparkles" class="w-3 h-3 text-[#C8A46A]/80 animate-pulse"></i>
                    <div class="w-12 h-px bg-gradient-to-l from-transparent to-[#C8A46A]/40"></div>
                </div>
            </div>
            
            <div class="flex overflow-x-auto md:justify-center gap-8 md:gap-12 pb-8 no-scrollbar snap-x px-4 -mx-4 md:px-0 md:mx-0 reveal-on-scroll">
                <?php
                // Build list of term IDs to exclude (Uncategorized / default category)
                $cat_exclude_ids = array();
                $default_cat_id  = (int) get_option( 'default_product_cat', 0 );
                if ( $default_cat_id ) {
                    $cat_exclude_ids[] = $default_cat_id;
                }
                $uncategorized_term = get_term_by( 'slug', 'uncategorized', 'product_cat' );
                if ( $uncategorized_term && ! is_wp_error( $uncategorized_term ) ) {
                    $cat_exclude_ids[] = $uncategorized_term->term_id;
                }
                $cat_exclude_ids = array_unique( array_filter( $cat_exclude_ids ) );

                $categories = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'exclude'    => $cat_exclude_ids,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                ) );

                // Fallback images — rotate through all available category images
                $cat_fallback_pool = array(
                    get_template_directory_uri() . '/assets/images/category-silk.jpg',
                    get_template_directory_uri() . '/assets/images/category-banarasi.jpg',
                    get_template_directory_uri() . '/assets/images/category-bandhani.jpg',
                    get_template_directory_uri() . '/assets/images/category-organza.jpg',
                    get_template_directory_uri() . '/assets/images/saree-1.jpg',
                    get_template_directory_uri() . '/assets/images/saree-2.jpg',
                    get_template_directory_uri() . '/assets/images/saree-3.jpg',
                    get_template_directory_uri() . '/assets/images/saree-4.jpg',
                );
                $cat_fallback_count = count( $cat_fallback_pool );
                $cat_idx = 0;

                if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) :
                    foreach ( $categories as $cat ) :
                        $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                        $image_url    = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : '';
                        if ( ! $image_url ) {
                            // Cycle through the fallback pool so each category shows a distinct image
                            $image_url = $cat_fallback_pool[ $cat_idx % $cat_fallback_count ];
                            $cat_idx++;
                        }
                        ?>
                        <div onclick="window.location.href='<?php echo esc_url( get_term_link( $cat ) ); ?>'" class="snap-center shrink-0 group cursor-pointer flex flex-col items-center gap-5 relative">
                            <div class="relative w-28 h-28 md:w-36 md:h-36 flex items-center justify-center">
                                <div class="outer-ring"></div>
                                <div class="w-full h-full rounded-full overflow-hidden p-1 bg-[#0a0a0a] border border-[#C8A46A]/30 group-hover:border-[#C8A46A] transition-all duration-500 shadow-xl group-hover:shadow-[0_0_25px_rgba(200,164,106,0.25)]">
                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" class="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                </div>
                            </div>
                            <span class="font-serif text-base md:text-lg text-[#F7F4EE] group-hover:text-[#C8A46A] transition-colors hover-underline"><?php echo esc_html( $cat->name ); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php
$sections['categories'] = ob_get_clean();

// 3. New Arrivals
if ( $show_new_arrivals ) {
    ob_start();
    ?>
    <section class="py-24 bg-[#050505] overflow-hidden" <?php echo ! empty( $padding_arrivals ) ? 'style="padding-top:' . esc_attr( $padding_arrivals ) . ';padding-bottom:' . esc_attr( $padding_arrivals ) . ';"': ""; ?>>
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12 reveal-on-scroll">
                <div class="text-center md:text-left">
                    <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php esc_html_e( 'Fresh Off The Loom', 'dt-ecommerce-theme' ); ?></span>
                    <h2 class="font-serif text-4xl md:text-5xl text-white leading-tight"><?php esc_html_e( 'New Arrivals', 'dt-ecommerce-theme' ); ?></h2>
                    <div class="flex items-center justify-center md:justify-start gap-3 mt-3">
                        <div class="w-16 h-px bg-gradient-to-r from-[#C8A46A]/60 to-transparent"></div>
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#C8A46A] animate-pulse"></i>
                        <div class="w-16 h-px bg-gradient-to-l from-[#C8A46A]/60 to-transparent md:hidden"></div>
                    </div>
                </div>
                <!-- Slider Navigation -->
                <div class="hidden md:flex items-center gap-3">
                    <span id="arrivals-progress" class="text-[10px] uppercase tracking-widest text-[#C8A46A]/60 font-medium mr-4">01 / 08</span>
                    <button onclick="scrollArrivals(-1)" id="arrivals-prev-btn" title="Previous Slide" aria-label="Previous Slide" class="slider-nav-btn w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] flex items-center justify-center bg-black/40">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </button>
                    <button onclick="scrollArrivals(1)" id="arrivals-next-btn" title="Next Slide" aria-label="Next Slide" class="slider-nav-btn w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] flex items-center justify-center bg-black/40">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <?php
            $p_ids = array();
            if ( class_exists( 'WooCommerce' ) ) {
                $args = array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => 8,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'fields'         => 'ids'
                );
                $p_ids = get_posts( $args );
            }
            ?>

            <!-- Mobile: 2-col grid | Desktop: 1-line horizontal slider -->
            <div id="new-arrivals-grid" class="grid grid-cols-2 gap-x-4 gap-y-10 md:hidden">
                <?php
                if ( ! empty( $p_ids ) ) {
                    $delay = 0;
                    foreach ( $p_ids as $pid ) {
                        $render_product_grid_card( $pid, $delay );
                        $delay += 80;
                    }
                }
                ?>
            </div>
            <div id="new-arrivals-slider" class="hidden md:flex arrivals-slider overflow-x-auto gap-6 pb-6 -mx-4 px-4 no-scrollbar">
                <?php
                if ( ! empty( $p_ids ) ) {
                    $delay = 0;
                    foreach ( $p_ids as $pid ) {
                        $render_product_card( $pid, $delay );
                        $delay += 80;
                    }
                }
                ?>
            </div>

            <div class="mt-14 text-center reveal-on-scroll">
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="inline-flex items-center gap-3 border border-[#C8A46A] text-[#C8A46A] px-10 py-4 uppercase tracking-widest text-sm hover:bg-[#C8A46A] hover:text-black transition-all group">
                    <?php esc_html_e( 'View All Arrivals', 'dt-ecommerce-theme' ); ?>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>
    <?php
    $sections['arrivals'] = ob_get_clean();
}

// 4. Feature Ticker Strip
ob_start();
?>
<!-- Feature Strip (Auto Ticker) -->
    <div class="feature-ticker-strip relative py-5 md:py-6 overflow-hidden">
        <div class="ticker-fade ticker-fade-left"></div>
        <div class="ticker-fade ticker-fade-right"></div>
        <div class="ticker-inner flex items-center animate-ticker-smooth">
            <div class="ticker-set">
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="award" class="w-4 h-4"></i></span><span class="ticker-text">Silk Mark Certified</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="truck" class="w-4 h-4"></i></span><span class="ticker-text">Free Worldwide Shipping</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="refresh-ccw" class="w-4 h-4"></i></span><span class="ticker-text">7-Day Easy Returns</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="hand-heart" class="w-4 h-4"></i></span><span class="ticker-text">Handloomed by 200+ Artisans</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="banknote" class="w-4 h-4"></i></span><span class="ticker-text">Cash on Delivery Available</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="messages-square" class="w-4 h-4"></i></span><span class="ticker-text">Personal Stylist Support</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="lock-keyhole" class="w-4 h-4"></i></span><span class="ticker-text">Secure SSL Checkout</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="package-check" class="w-4 h-4"></i></span><span class="ticker-text">Signature Gift Wrapping</span></div>
                <span class="ticker-sep">✦</span>
            </div>
            <div class="ticker-set" aria-hidden="true">
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="award" class="w-4 h-4"></i></span><span class="ticker-text">Silk Mark Certified</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="truck" class="w-4 h-4"></i></span><span class="ticker-text">Free Worldwide Shipping</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="refresh-ccw" class="w-4 h-4"></i></span><span class="ticker-text">7-Day Easy Returns</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="hand-heart" class="w-4 h-4"></i></span><span class="ticker-text">Handloomed by 200+ Artisans</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="banknote" class="w-4 h-4"></i></span><span class="ticker-text">Cash on Delivery Available</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="messages-square" class="w-4 h-4"></i></span><span class="ticker-text">Personal Stylist Support</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="lock-keyhole" class="w-4 h-4"></i></span><span class="ticker-text">Secure SSL Checkout</span></div>
                <span class="ticker-sep">✦</span>
                <div class="ticker-item"><span class="ticker-icon"><i data-lucide="package-check" class="w-4 h-4"></i></span><span class="ticker-text">Signature Gift Wrapping</span></div>
                <span class="ticker-sep">✦</span>
            </div>
        </div>
    </div>
<?php
$sections['ticker'] = ob_get_clean();

// 5. Promo Banners
ob_start();
?>
<!-- Premium Banners -->
    <section class="py-24 bg-[#050505]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 md:h-[70vh]">
                <!-- Banner 1 -->
                <div class="relative group overflow-hidden bg-[#111] reveal-on-scroll min-h-[55vw] md:min-h-0">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-bridal.jpg' ); ?>" alt="Bridal Collection" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-1000" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-10">
                        <h3 class="font-serif text-3xl md:text-4xl text-white mb-2"><?php esc_html_e( 'Bridal Collection', 'dt-ecommerce-theme' ); ?></h3>
                        <p class="text-[#C8A46A] text-sm uppercase tracking-widest mb-6"><?php esc_html_e( 'For your special day', 'dt-ecommerce-theme' ); ?></p>
                        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="flex items-center gap-2 text-white text-sm tracking-widest uppercase hover:text-[#C8A46A] transition-colors w-fit pb-1 border-b border-[#C8A46A]">
                            Explore <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Banner 2 -->
                <div class="relative group overflow-hidden bg-[#2a0808] reveal-on-scroll min-h-[55vw] md:min-h-0">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-festival.jpg' ); ?>" alt="Festival Specials" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-1000" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8 md:p-10">
                        <h3 class="font-serif text-3xl md:text-4xl text-white mb-2"><?php esc_html_e( 'Festival Specials', 'dt-ecommerce-theme' ); ?></h3>
                        <p class="text-[#C8A46A] text-sm uppercase tracking-widest mb-6"><?php esc_html_e( 'Celebrate in style', 'dt-ecommerce-theme' ); ?></p>
                        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="flex items-center gap-2 text-white text-sm tracking-widest uppercase hover:text-[#C8A46A] transition-colors w-fit pb-1 border-b border-[#C8A46A]">
                            Explore <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Banner 3 -->
                <div class="relative group overflow-hidden bg-black border border-[#C8A46A]/20 reveal-on-scroll min-h-[55vw] md:min-h-0">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-offer.jpg' ); ?>" alt="Exclusive Offer" class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-luminosity group-hover:scale-105 transition-transform duration-1000" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8">
                        <span class="text-[#C8A46A] uppercase tracking-[0.3em] text-xs mb-4"><?php esc_html_e( 'Limited Time', 'dt-ecommerce-theme' ); ?></span>
                        <h3 class="font-serif text-5xl md:text-6xl text-[#C8A46A] mb-4"><?php esc_html_e( '30% OFF', 'dt-ecommerce-theme' ); ?></h3>
                        <p class="text-white text-lg font-serif italic mb-8"><?php esc_html_e( 'on exclusive designer weaves', 'dt-ecommerce-theme' ); ?></p>
                        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="btn-gold-shimmer px-8 py-3 uppercase tracking-widest text-xs font-semibold">
                            Shop Sale
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$sections['banners'] = ob_get_clean();


// 6. Top Sellers
if ( $show_top_sellers ) {
    ob_start();
    ?>
    <section class="py-24 border-t border-[#C8A46A]/10 bg-black overflow-hidden" <?php echo ! empty( $padding_topsellers ) ? 'style="padding-top:' . esc_attr( $padding_topsellers ) . ';padding-bottom:' . esc_attr( $padding_topsellers ) . ';"': ""; ?>>
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12 reveal-on-scroll">
                <div class="text-center md:text-left">
                    <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php esc_html_e( 'Atelier Favorites', 'dt-ecommerce-theme' ); ?></span>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold animate-gold-gradient select-none leading-tight"><?php esc_html_e( 'Top Selling Styles', 'dt-ecommerce-theme' ); ?></h2>
                    <div class="flex items-center justify-center md:justify-start gap-3 mt-3">
                        <div class="w-16 h-px bg-gradient-to-r from-[#C8A46A]/60 to-transparent"></div>
                        <i data-lucide="crown" class="w-3.5 h-3.5 text-[#C8A46A] animate-pulse"></i>
                        <div class="w-16 h-px bg-gradient-to-l from-[#C8A46A]/60 to-transparent md:hidden"></div>
                    </div>
                </div>
                <!-- Slider Navigation -->
                <div class="hidden md:flex items-center gap-3">
                    <span id="topsellers-progress" class="text-[10px] uppercase tracking-widest text-[#C8A46A]/60 font-medium mr-4">01 / 08</span>
                    <button onclick="scrollTopSellers(-1)" id="topsellers-prev-btn" title="Previous Slide" aria-label="Previous Slide" class="slider-nav-btn w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] flex items-center justify-center bg-black/40">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </button>
                    <button onclick="scrollTopSellers(1)" id="topsellers-next-btn" title="Next Slide" aria-label="Next Slide" class="slider-nav-btn w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] flex items-center justify-center bg-black/40">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <?php
            $best_ids = array();
            if ( class_exists( 'WooCommerce' ) ) {
                $args = array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => 8,
                    'meta_key'       => 'total_sales',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                    'fields'         => 'ids'
                );
                $best_ids = get_posts( $args );
                if ( empty( $best_ids ) ) {
                    // Fallback to random sorting if sales are not populated
                    $args['orderby'] = 'rand';
                    unset( $args['meta_key'] );
                    $best_ids = get_posts( $args );
                }
            }
            ?>

            <!-- Mobile: 2-col grid | Desktop: 1-line horizontal slider -->
            <div id="top-sellers-grid" class="grid grid-cols-2 gap-x-4 gap-y-10 md:hidden">
                <?php
                if ( ! empty( $best_ids ) ) {
                    $delay = 0;
                    foreach ( $best_ids as $pid ) {
                        $render_product_grid_card( $pid, $delay, true );
                        $delay += 80;
                    }
                }
                ?>
            </div>
            <div id="top-sellers-slider" class="hidden md:flex arrivals-slider overflow-x-auto gap-6 pb-6 -mx-4 px-4 no-scrollbar">
                <?php
                if ( ! empty( $best_ids ) ) {
                    $delay = 0;
                    foreach ( $best_ids as $pid ) {
                        $render_product_card( $pid, $delay, true );
                        $delay += 80;
                    }
                }
                ?>
            </div>

            <div class="mt-14 text-center reveal-on-scroll">
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="inline-flex items-center gap-3 border border-[#C8A46A] text-[#C8A46A] px-10 py-4 uppercase tracking-widest text-sm hover:bg-[#C8A46A] hover:text-black transition-all group">
                    <?php esc_html_e( 'Shop All Bestsellers', 'dt-ecommerce-theme' ); ?>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>
    <?php
    $sections['bestsellers'] = ob_get_clean();
}

// 7. Why Choose Us (Promise)
ob_start();
?>
<!-- Why Choose Us - Redesigned Bento Grid -->
    <section class="why-choose-section relative py-24 md:py-32 overflow-hidden border-t border-b border-[#C8A46A]/10">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/2 left-0 -translate-y-1/2 w-96 h-96 bg-[#C8A46A]/[0.03] blur-3xl rounded-full"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-[#C8A46A]/[0.02] blur-3xl rounded-full"></div>
            <div class="absolute inset-0 opacity-[0.04] diagonal-thread-pattern"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-16 md:mb-20 reveal-on-scroll">
                <div class="max-w-xl">
                    <span class="text-xs uppercase tracking-[0.4em] text-[#C8A46A]/70 block mb-4 font-medium"><?php esc_html_e( 'The Arshman Promise', 'dt-ecommerce-theme' ); ?></span>
                    <h2 class="font-serif text-4xl md:text-6xl leading-[1.05] mb-6">
                        <span class="text-white">Why </span>
                        <span class="animate-gold-gradient">Choose Us</span>
                    </h2>
                    <div class="w-20 h-px bg-gradient-to-r from-[#C8A46A] to-transparent"></div>
                </div>
                <p class="text-gray-400 max-w-md text-base md:text-right leading-relaxed">
                    Six commitments that make every drape from our atelier truly one of a kind — 
                    <span class="text-[#C8A46A]">crafted with soul, delivered with care.</span>
                </p>
            </div>

            <!-- Feature Cards Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div class="wc-card group delay-0">
                    <div class="wc-number">01</div>
                    <div class="wc-icon-frame"><i data-lucide="gem" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">Premium Quality</h4>
                    <p class="wc-desc">Handpicked mulberry silks and pure zari, woven under strict quality supervision at every loom.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>

                <div class="wc-card group delay-08">
                    <div class="wc-number">02</div>
                    <div class="wc-icon-frame"><i data-lucide="shield-check" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">Direct From Weavers</h4>
                    <p class="wc-desc">Zero middlemen. Every ₹ you spend reaches the hands that spun the silk — fair wages, honest pricing.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>

                <div class="wc-card group delay-16">
                    <div class="wc-number">03</div>
                    <div class="wc-icon-frame"><i data-lucide="sparkles" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">Timeless Designs</h4>
                    <p class="wc-desc">Heritage motifs reimagined for the modern woman — created by an in-house design studio led by NIFT alumni.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>

                <div class="wc-card group delay-24">
                    <div class="wc-number">04</div>
                    <div class="wc-icon-frame"><i data-lucide="truck" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">Free Worldwide Shipping</h4>
                    <p class="wc-desc">Insured DHL Express delivery to 40+ countries — 3-5 business days in India, 5-10 abroad. On us.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>

                <div class="wc-card group delay-32">
                    <div class="wc-number">05</div>
                    <div class="wc-icon-frame"><i data-lucide="refresh-ccw" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">7-Day Easy Returns</h4>
                    <p class="wc-desc">Hassle-free returns and exchanges within a week — because we know a saree deserves the perfect moment.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>

                <div class="wc-card group delay-40">
                    <div class="wc-number">06</div>
                    <div class="wc-icon-frame"><i data-lucide="award" class="w-7 h-7"></i></div>
                    <h4 class="wc-title">Certified Authentic</h4>
                    <p class="wc-desc">Every drape ships with a Silk Mark Certificate of Authenticity — signed by the master weaver himself.</p>
                    <div class="wc-arrow"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="wc-stats grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 pt-14 border-t border-[#C8A46A]/10 reveal-on-scroll">
                <div class="text-center wc-stat">
                    <div class="font-serif text-4xl md:text-5xl text-[#C8A46A] mb-2" data-count="15">15+</div>
                    <div class="text-[10px] uppercase tracking-widest text-gray-400">Years of Craft</div>
                </div>
                <div class="text-center wc-stat">
                    <div class="font-serif text-4xl md:text-5xl text-[#C8A46A] mb-2" data-count="200">200+</div>
                    <div class="text-[10px] uppercase tracking-widest text-gray-400">Master Weavers</div>
                </div>
                <div class="text-center wc-stat">
                    <div class="font-serif text-4xl md:text-5xl text-[#C8A46A] mb-2" data-count="50000">50K+</div>
                    <div class="text-[10px] uppercase tracking-widest text-gray-400">Happy Customers</div>
                </div>
                <div class="text-center wc-stat">
                    <div class="font-serif text-4xl md:text-5xl text-[#C8A46A] mb-2" data-count="40">40+</div>
                    <div class="text-[10px] uppercase tracking-widest text-gray-400">Countries Served</div>
                </div>
            </div>
        </div>
    </section>
<?php
$sections['promise'] = ob_get_clean();

// 8. Reviews
if ( $show_reviews ) {
    ob_start();
    ?>
    <section class="reviews-section relative py-24 md:py-32 overflow-hidden" <?php echo ! empty( $padding_reviews ) ? 'style="padding-top:' . esc_attr( $padding_reviews ) . ';padding-bottom:' . esc_attr( $padding_reviews ) . ';"': ""; ?>>
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[900px] rounded-full bg-[#C8A46A]/[0.04] blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-[#C8A46A]/[0.03] blur-3xl"></div>
        </div>
        <div class="absolute top-16 left-1/2 -translate-x-1/2 pointer-events-none opacity-[0.03] select-none">
            <span class="font-serif text-[400px] leading-none text-[#C8A46A]">"</span>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16 md:mb-20 reveal-on-scroll">
                <span class="text-xs uppercase tracking-[0.4em] text-[#C8A46A]/70 block mb-4 font-medium"><?php esc_html_e( 'Voices from our Atelier', 'dt-ecommerce-theme' ); ?></span>
                <h2 class="font-serif text-4xl md:text-6xl text-white mb-5 leading-tight"><?php esc_html_e( 'What Our Customers Say', 'dt-ecommerce-theme' ); ?></h2>
                <div class="flex items-center justify-center gap-3">
                    <div class="w-16 h-px bg-gradient-to-r from-transparent to-[#C8A46A]/60"></div>
                    <i data-lucide="quote" class="w-4 h-4 text-[#C8A46A] rotate-180"></i>
                    <div class="w-16 h-px bg-gradient-to-l from-transparent to-[#C8A46A]/60"></div>
                </div>

                <div class="flex items-center justify-center gap-8 md:gap-14 mt-10 flex-wrap">
                    <div class="flex flex-col items-center">
                        <div class="flex gap-0.5 text-[#C8A46A] mb-1">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <div class="text-white font-serif text-2xl">4.9<span class="text-[#C8A46A] text-sm">/5</span></div>
                        <div class="text-[10px] uppercase tracking-widest text-gray-500 mt-1">Avg. Rating</div>
                    </div>
                    <div class="w-px h-14 bg-[#C8A46A]/20"></div>
                    <div class="flex flex-col items-center">
                        <div class="text-white font-serif text-3xl">12,400+</div>
                        <div class="text-[10px] uppercase tracking-widest text-gray-500 mt-1">Happy Patrons</div>
                    </div>
                    <div class="w-px h-14 bg-[#C8A46A]/20 hidden md:block"></div>
                    <div class="flex flex-col items-center">
                        <div class="text-white font-serif text-3xl">98%</div>
                        <div class="text-[10px] uppercase tracking-widest text-gray-500 mt-1">Would Recommend</div>
                    </div>
                </div>
            </div>

            <div class="relative reveal-on-scroll" id="reviews-carousel-wrap">
                <div id="reviews-track" class="reviews-track flex gap-6 md:gap-8">
                    <!-- Loaded dynamically via JS -->
                </div>
                <!-- Controls -->
                <button onclick="reviewsNav(-1)" id="reviews-prev" title="Previous Slide" aria-label="Previous Slide" class="hidden md:flex slider-nav-btn absolute left-0 top-1/2 -translate-y-1/2 -translate-x-16 lg:-translate-x-6 w-12 h-12 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] items-center justify-center bg-black/60 z-10">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <button onclick="reviewsNav(1)" id="reviews-next" title="Next Slide" aria-label="Next Slide" class="hidden md:flex slider-nav-btn absolute right-0 top-1/2 -translate-y-1/2 translate-x-16 lg:translate-x-6 w-12 h-12 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] items-center justify-center bg-black/60 z-10">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex flex-col items-center gap-4 mt-12">
                <div id="reviews-dots" class="flex items-center gap-3"></div>
                <div class="flex items-center gap-2 text-[10px] uppercase tracking-widest text-gray-500">
                    <span id="reviews-play-icon" class="w-1.5 h-1.5 rounded-full bg-[#C8A46A] animate-pulse"></span>
                    <span id="reviews-play-label">Auto-playing</span>
                </div>
            </div>
        </div>
    </section>
    <?php
    $sections['reviews'] = ob_get_clean();
}

// 9. Instagram Feed
if ( $show_instagram_feed ) {
    ob_start();
    ?>
    <section class="py-24 bg-[#0a0a0a]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 reveal-on-scroll">
                <h2 class="font-serif text-3xl md:text-4xl text-white mb-2"><?php esc_html_e( 'Follow Us', 'dt-ecommerce-theme' ); ?></h2>
                <p class="text-[#C8A46A] tracking-widest">@ArshmanDesigns</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2 reveal-on-scroll">
                <?php
                $insta_images = array(
                    'insta-1.jpg',
                    'insta-2.jpg',
                    'insta-3.jpg',
                    'insta-4.jpg',
                    'hero-saree.jpg',
                    'banner-bridal.jpg'
                );
                foreach ( $insta_images as $img_file ) :
                    $img_url = get_template_directory_uri() . '/assets/images/' . $img_file;
                    ?>
                    <div class="relative aspect-square group overflow-hidden bg-[#111] cursor-pointer">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="Instagram post" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <svg class="text-white w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    $sections['instagram'] = ob_get_clean();
}

// ── Section Sorting Logic ──────────────────────────────────────────────────
$order = array(
    'hero'          => intval( dt_get_theme_option( 'sec_order_hero', '10' ) ),
    'categories'    => intval( dt_get_theme_option( 'sec_order_categories', '20' ) ),
    'arrivals'      => intval( dt_get_theme_option( 'sec_order_arrivals', '30' ) ),
    'ticker'        => intval( dt_get_theme_option( 'sec_order_ticker', '40' ) ),
    'banners'       => intval( dt_get_theme_option( 'sec_order_banners', '50' ) ),
    'bestsellers'   => intval( dt_get_theme_option( 'sec_order_bestsellers', '60' ) ),
    'promise'       => intval( dt_get_theme_option( 'sec_order_promise', '70' ) ),
    'reviews'       => intval( dt_get_theme_option( 'sec_order_reviews', '80' ) ),
    'instagram'     => intval( dt_get_theme_option( 'sec_order_instagram', '90' ) ),
);

asort( $order );

echo '<main>';
foreach ( $order as $key => $weight ) {
    if ( isset( $sections[ $key ] ) ) {
        echo $sections[ $key ];
    }
}
echo '</main>';
?>

<script>
    // Custom triggers for frontend actions
    function toggleWishlistAction(id, button) {
        if (typeof toggleWishlist === 'function') {
            toggleWishlist(id, button);
        }
    }
    function addCartItemAction(id, button) {
        if (typeof addToCart === 'function') {
            addToCart(id, 1, 'black', '5.5m', button);
        }
    }
        const wcStats = document.querySelectorAll('.wc-stat [data-count]');
        const wcObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.dataset.count);
                    const label = el.textContent;
                    const duration = 1600;
                    const startTime = performance.now();
                    const start = 0;
                    function tick(now) {
                        const p = Math.min(1, (now - startTime) / duration);
                        const eased = 1 - Math.pow(1 - p, 3);
                        const val = Math.floor(start + (target - start) * eased);
                        if (target >= 1000) {
                            el.textContent = Math.floor(val / 1000) + 'K+';
                        } else {
                            el.textContent = val + '+';
                        }
                        if (p < 1) requestAnimationFrame(tick);
                        else el.textContent = label;
                    }
                    requestAnimationFrame(tick);
                    wcObserver.unobserve(el);
                }
            });
        }, { threshold: 0.3 });
        wcStats.forEach(s => wcObserver.observe(s));

        // Testimonials init
        const reviewsTrack = document.getElementById('reviews-track');
        const reviewsDots = document.getElementById('reviews-dots');
        const reviewsWrap = document.getElementById('reviews-carousel-wrap');
        if (reviewsTrack && reviewsDots) {
            reviewsTrack.innerHTML = REVIEWS.map((r, i) => buildReviewCard(r, i)).join('');
            reviewsDots.innerHTML = REVIEWS.map((_, i) => `<span class="review-dot ${i===0?'active':''}" onclick="setReviewIndex(${i}); restartReviewsAuto();"></span>`).join('');

            setTimeout(() => {
                setReviewIndex(0);
                startReviewsAuto();
            }, 200);

            if (reviewsWrap) {
                reviewsWrap.addEventListener('mouseenter', () => {
                    reviewsPaused = true;
                    document.querySelectorAll('.review-dot.active').forEach(d => d.classList.add('paused'));
                    document.getElementById('reviews-play-label').textContent = 'Paused';
                    document.getElementById('reviews-play-icon').classList.remove('animate-pulse');
                    document.getElementById('reviews-play-icon').style.background = '#666';
                });
                reviewsWrap.addEventListener('mouseleave', () => {
                    reviewsPaused = false;
                    document.querySelectorAll('.review-dot.active').forEach(d => d.classList.remove('paused'));
                    document.getElementById('reviews-play-label').textContent = 'Auto-playing';
                    document.getElementById('reviews-play-icon').classList.add('animate-pulse');
                    document.getElementById('reviews-play-icon').style.background = '#C8A46A';
                });
            }

            window.addEventListener('resize', () => setReviewIndex(reviewIdx));

            let touchStart = null;
            reviewsTrack.addEventListener('touchstart', (e) => { touchStart = e.touches[0].clientX; }, { passive: true });
            reviewsTrack.addEventListener('touchend', (e) => {
                if (touchStart == null) return;
                const diff = touchStart - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) { reviewsNav(diff > 0 ? 1 : -1); }
                touchStart = null;
            }, { passive: true });
        }
    });
</script>

<?php
get_footer();
