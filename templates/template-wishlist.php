<?php
/**
 * Template Name: Wishlist Template
 * Myntra-style fully-featured wishlist page.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Fetch wishlist items and build data array up front.
$wishlist_ids = dt_get_wishlist();
$wishlist_count = count( $wishlist_ids );

$wishlist_items = array();
if ( ! empty( $wishlist_ids ) && class_exists( 'WooCommerce' ) ) {
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'post__in'       => $wishlist_ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    );
    $wish_query = new WP_Query( $args );
    if ( $wish_query->have_posts() ) {
        while ( $wish_query->have_posts() ) {
            $wish_query->the_post();
            global $product;

            $id            = get_the_ID();
            $title         = get_the_title();
            $permalink     = get_permalink();
            $price         = (float) $product->get_price();
            $mrp           = (float) $product->get_regular_price();
            $in_stock      = $product->is_in_stock();
            $rating        = $product->get_average_rating();
            $review_count  = $product->get_review_count();
            $img           = get_the_post_thumbnail_url( $id, 'woocommerce_single' );
            if ( ! $img ) {
                $img = wc_placeholder_img_src();
            }
            $img_thumb = get_the_post_thumbnail_url( $id, 'woocommerce_thumbnail' );
            if ( ! $img_thumb ) {
                $img_thumb = $img;
            }

            $cats = array();
            $terms = get_the_terms( $id, 'product_cat' );
            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                foreach ( $terms as $t ) {
                    $cats[] = $t->name;
                }
            }
            $cat_label = ! empty( $cats ) ? $cats[0] : 'Product';

            $discount = 0;
            if ( $mrp > 0 && $price < $mrp ) {
                $discount = round( ( ( $mrp - $price ) / $mrp ) * 100 );
            }

            $wishlist_items[] = array(
                'id'           => $id,
                'title'        => $title,
                'permalink'    => $permalink,
                'price'        => $price,
                'mrp'          => $mrp,
                'discount'     => $discount,
                'in_stock'     => $in_stock,
                'rating'       => round( $rating, 1 ),
                'reviews'      => $review_count,
                'img'          => $img,
                'img_thumb'    => $img_thumb,
                'category'     => $cat_label,
                'cats'         => $cats,
            );
        }
        wp_reset_postdata();
    }
}

$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
?>

<!-- ─── Wishlist Wrapper ────────────────────────────────────────────────────── -->
<div id="dt-wishlist-page" class="min-h-screen bg-[#050505]">

    <!-- ── Sticky Top Bar ──────────────────────────────────────────────────── -->
    <div id="wl-topbar" class="sticky top-0 z-40 bg-[#080808] border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-0">
            <!-- Breadcrumb row -->
            <div class="py-2 border-b border-white/5 hidden md:flex items-center gap-2 text-[10px] text-white/30 uppercase tracking-widest">
                <a href="<?php echo esc_url( home_url() ); ?>" class="hover:text-[#C8A46A] transition-colors">Home</a>
                <span>/</span>
                <span class="text-white/50">My Wishlist</span>
            </div>

            <!-- Main title + actions -->
            <div class="flex items-center justify-between py-4 gap-4">
                <div class="flex items-baseline gap-4">
                    <h1 class="font-serif text-2xl md:text-3xl text-white leading-none">My Wishlist</h1>
                    <span id="wl-count-badge" class="text-xs bg-[#C8A46A]/15 text-[#C8A46A] border border-[#C8A46A]/30 px-2.5 py-0.5 rounded-full font-medium tracking-wide">
                        <?php echo esc_html( $wishlist_count ); ?> item<?php echo $wishlist_count !== 1 ? 's' : ''; ?>
                    </span>
                </div>
                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Share -->
                    <button id="wl-btn-share" class="hidden md:flex items-center gap-1.5 text-[11px] uppercase tracking-widest text-white/50 hover:text-white transition-colors px-3 py-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        Share
                    </button>
                    <!-- Move All to Bag -->
                    <button id="wl-btn-move-all" class="<?php echo empty( $wishlist_items ) ? 'hidden' : ''; ?> hidden md:flex items-center gap-1.5 text-[11px] uppercase tracking-widest border border-[#C8A46A]/50 text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black transition-all px-4 py-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Move All to Bag
                    </button>
                    <!-- Clear -->
                    <button id="wl-btn-clear" class="<?php echo empty( $wishlist_items ) ? 'hidden' : ''; ?> text-[11px] uppercase tracking-widest text-white/30 hover:text-red-400 transition-colors px-3 py-2">
                        Clear
                    </button>
                </div>
            </div>

            <!-- Sort + Filter bar (only when items exist) -->
            <?php if ( ! empty( $wishlist_items ) ) : ?>
            <div class="flex items-center justify-between pb-3 gap-4 overflow-x-auto scrollbar-hide">
                <!-- Category filter tabs -->
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button class="wl-filter-btn active text-[11px] uppercase tracking-widest px-3 py-1.5 border transition-all" data-filter="all">All</button>
                    <?php
                    $all_cats = array();
                    foreach ( $wishlist_items as $item ) {
                        foreach ( $item['cats'] as $c ) {
                            $all_cats[$c] = true;
                        }
                    }
                    foreach ( array_keys( $all_cats ) as $cat ) :
                    ?>
                    <button class="wl-filter-btn text-[11px] uppercase tracking-widest px-3 py-1.5 border transition-all" data-filter="<?php echo esc_attr( strtolower( $cat ) ); ?>"><?php echo esc_html( $cat ); ?></button>
                    <?php endforeach; ?>
                </div>
                <!-- Sort dropdown -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-[10px] text-white/30 uppercase tracking-widest hidden md:block">Sort:</span>
                    <select id="wl-sort" class="bg-transparent border border-white/20 text-white/70 text-[11px] uppercase tracking-wider px-3 py-1.5 focus:outline-none focus:border-[#C8A46A] cursor-pointer">
                        <option value="default">Date Added</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="discount">% Discount</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Main Content ────────────────────────────────────────────────────── -->
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8">

        <?php if ( ! empty( $wishlist_items ) ) : ?>

        <!-- Product Grid -->
        <div id="wl-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">
            <?php foreach ( $wishlist_items as $item ) :
                $stars_filled = floor( $item['rating'] );
                $stars_half   = ( $item['rating'] - $stars_filled ) >= 0.5 ? 1 : 0;
                $stars_empty  = 5 - $stars_filled - $stars_half;
            ?>
            <div
                id="wl-item-<?php echo esc_attr( $item['id'] ); ?>"
                class="wl-card group relative flex flex-col bg-[#0e0e0e] border border-white/[0.07] overflow-hidden transition-all duration-300 hover:border-[#C8A46A]/40 hover:shadow-[0_4px_32px_rgba(200,164,106,0.1)] cursor-pointer"
                data-id="<?php echo esc_attr( $item['id'] ); ?>"
                data-price="<?php echo esc_attr( $item['price'] ); ?>"
                data-mrp="<?php echo esc_attr( $item['mrp'] ); ?>"
                data-discount="<?php echo esc_attr( $item['discount'] ); ?>"
                data-rating="<?php echo esc_attr( $item['rating'] ); ?>"
                data-cats="<?php echo esc_attr( strtolower( implode( ',', $item['cats'] ) ) ); ?>"
                onclick="window.location.href='<?php echo esc_url( $item['permalink'] ); ?>'"
            >
                <!-- Image Area -->
                <div class="relative overflow-hidden bg-white/5" style="aspect-ratio:3/4;">
                    <img
                        src="<?php echo esc_url( $item['img_thumb'] ); ?>"
                        data-fullsrc="<?php echo esc_url( $item['img'] ); ?>"
                        alt="<?php echo esc_attr( $item['title'] ); ?>"
                        class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105"
                        loading="lazy"
                    />

                    <!-- Out of stock overlay -->
                    <?php if ( ! $item['in_stock'] ) : ?>
                    <div class="absolute inset-0 bg-black/70 flex items-center justify-center z-10">
                        <span class="text-white/80 text-xs uppercase tracking-widest border border-white/30 px-3 py-1.5">Out of Stock</span>
                    </div>
                    <?php endif; ?>

                    <!-- Discount badge -->
                    <?php if ( $item['discount'] > 0 ) : ?>
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-green-500 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded-sm shadow">
                            <?php echo esc_html( $item['discount'] ); ?>% off
                        </span>
                    </div>
                    <?php endif; ?>

                    <!-- Remove heart button (always visible) -->
                    <button
                        class="wl-remove-btn absolute top-3 right-3 z-20 w-9 h-9 rounded-full bg-black/60 backdrop-blur-sm border border-white/20 flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-lg"
                        onclick="event.stopPropagation(); wlRemoveItem(<?php echo esc_js( $item['id'] ); ?>)"
                        title="Remove from Wishlist"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>

                    <!-- Hover: Move to Bag overlay -->
                    <div class="absolute bottom-0 left-0 right-0 z-20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out">
                        <button
                            onclick="event.stopPropagation(); wlMoveToBag(<?php echo esc_js( $item['id'] ); ?>, this)"
                            class="wl-move-btn w-full py-3.5 text-[11px] font-semibold uppercase tracking-widest flex items-center justify-center gap-2 transition-all <?php echo $item['in_stock'] ? 'bg-[#C8A46A] text-black hover:bg-[#b08d55]' : 'bg-white/10 text-white/40 cursor-not-allowed'; ?>"
                            <?php echo ! $item['in_stock'] ? 'disabled' : ''; ?>
                        >
                            <?php if ( $item['in_stock'] ) : ?>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                            Move to Bag
                            <?php else : ?>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Out of Stock
                            <?php endif; ?>
                        </button>
                    </div>
                </div>

                <!-- Info Area -->
                <div class="p-3 flex flex-col gap-1">
                    <!-- Category / Brand -->
                    <span class="text-[10px] uppercase tracking-widest text-[#C8A46A]/70 truncate">
                        <?php echo esc_html( $item['category'] ); ?>
                    </span>

                    <!-- Product Title -->
                    <h3 class="text-sm text-white/90 leading-snug line-clamp-2 group-hover:text-white transition-colors" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        <?php echo esc_html( $item['title'] ); ?>
                    </h3>

                    <!-- Rating -->
                    <?php if ( $item['rating'] > 0 ) : ?>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="flex items-center gap-0.5">
                            <?php for ( $s = 0; $s < $stars_filled; $s++ ) : ?>
                            <svg class="w-2.5 h-2.5 text-[#C8A46A]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                            <?php if ( $stars_half ) : ?>
                            <svg class="w-2.5 h-2.5 text-[#C8A46A]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" opacity=".5"/></svg>
                            <?php endif; ?>
                            <?php for ( $s = 0; $s < $stars_empty; $s++ ) : ?>
                            <svg class="w-2.5 h-2.5 text-white/20" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-[10px] text-white/30"><?php echo esc_html( $item['rating'] ); ?></span>
                        <?php if ( $item['reviews'] > 0 ) : ?>
                        <span class="text-[10px] text-white/20">(<?php echo esc_html( $item['reviews'] ); ?>)</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Price Row -->
                    <div class="flex items-baseline flex-wrap gap-x-2 gap-y-0.5 mt-1">
                        <span class="text-sm font-semibold text-white">₹<?php echo number_format( (int) $item['price'] ); ?></span>
                        <?php if ( $item['mrp'] > 0 && $item['price'] < $item['mrp'] ) : ?>
                        <span class="text-xs text-white/30 line-through">₹<?php echo number_format( (int) $item['mrp'] ); ?></span>
                        <span class="text-xs text-green-400 font-medium"><?php echo esc_html( $item['discount'] ); ?>% off</span>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile: Move to Bag (always visible on small screens) -->
                    <button
                        onclick="event.stopPropagation(); wlMoveToBag(<?php echo esc_js( $item['id'] ); ?>, this)"
                        class="wl-move-btn-mobile mt-2 w-full py-2.5 text-[11px] uppercase tracking-widest font-semibold border transition-all flex items-center justify-center gap-1.5 md:hidden <?php echo $item['in_stock'] ? 'border-[#C8A46A]/50 text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black' : 'border-white/10 text-white/20 cursor-not-allowed'; ?>"
                        <?php echo ! $item['in_stock'] ? 'disabled' : ''; ?>
                    >
                        <?php if ( $item['in_stock'] ) : ?>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Move to Bag
                        <?php else : ?>
                        Out of Stock
                        <?php endif; ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No results after filter -->
        <div id="wl-no-results" class="hidden col-span-full py-20 text-center">
            <p class="text-white/30 text-sm uppercase tracking-widest">No items match this filter.</p>
        </div>

        <?php else : ?>

        <!-- ── Empty State ─────────────────────────────────────────────────── -->
        <div id="wl-empty" class="flex flex-col items-center justify-center py-24 text-center">

            <!-- Animated heart -->
            <div class="relative mb-8">
                <div class="w-24 h-24 rounded-full bg-[#C8A46A]/5 border border-[#C8A46A]/20 flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#C8A46A]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-5 h-5 bg-[#C8A46A] rounded-full flex items-center justify-center text-black text-[10px] font-bold">0</div>
            </div>

            <h2 class="font-serif text-2xl md:text-3xl text-white mb-3">Your Wishlist is Empty</h2>
            <p class="text-sm text-white/40 max-w-xs mx-auto mb-8 leading-relaxed">
                Save items you love by tapping the ♡ heart on any product. They'll appear here for easy access.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <a href="<?php echo esc_url( $shop_url ); ?>" class="bg-[#C8A46A] text-black px-8 py-3.5 text-xs uppercase tracking-widest font-semibold hover:bg-[#b08d55] transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Explore Collection
                </a>
                <a href="<?php echo esc_url( home_url() ); ?>" class="border border-white/20 text-white/60 px-8 py-3.5 text-xs uppercase tracking-widest hover:border-white/50 hover:text-white transition-all">
                    Go Home
                </a>
            </div>

            <!-- Decorative features strip -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 w-full max-w-2xl border-t border-white/5 pt-10">
                <?php
                $features = array(
                    array( 'icon' => 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z', 'label' => 'Save Favorites', 'desc' => 'Keep your picks in one place' ),
                    array( 'icon' => 'M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0', 'label' => 'Move to Bag', 'desc' => 'One click to checkout' ),
                    array( 'icon' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 0l8 9 8-9', 'label' => 'Share List', 'desc' => 'Share with friends' ),
                    array( 'icon' => 'M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3', 'label' => 'Price Alerts', 'desc' => 'Get notified on drops' ),
                );
                foreach ( $features as $f ) :
                ?>
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#C8A46A]/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr( $f['icon'] ); ?>"/></svg>
                    </div>
                    <div class="text-[11px] font-medium text-white/50 uppercase tracking-wider"><?php echo esc_html( $f['label'] ); ?></div>
                    <div class="text-[10px] text-white/20 text-center"><?php echo esc_html( $f['desc'] ); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php endif; ?>
    </div>

    <!-- Recent shop products (always shown, for recommendations) -->
    <?php
    if ( class_exists( 'WooCommerce' ) ) :
        $rec_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
            'orderby'        => 'rand',
            'meta_query'     => array( array( 'key' => '_stock_status', 'value' => 'instock' ) ),
        );
        if ( ! empty( $wishlist_ids ) ) {
            $rec_args['post__not_in'] = $wishlist_ids;
        }
        $rec_query = new WP_Query( $rec_args );
        if ( $rec_query->have_posts() ) :
    ?>
    <div class="border-t border-white/5 mt-12">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="h-px flex-1 bg-gradient-to-r from-[#C8A46A]/30 to-transparent"></div>
                <h2 class="text-xs uppercase tracking-widest text-[#C8A46A] font-medium">
                    <?php echo empty( $wishlist_items ) ? esc_html__( 'Start with these picks', 'dt-ecommerce-theme' ) : esc_html__( 'You might also like', 'dt-ecommerce-theme' ); ?>
                </h2>
                <div class="h-px flex-1 bg-gradient-to-l from-[#C8A46A]/30 to-transparent"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                <?php while ( $rec_query->have_posts() ) : $rec_query->the_post(); global $product;
                    $rprice   = (float) $product->get_price();
                    $rmrp     = (float) $product->get_regular_price();
                    $rdisc    = ( $rmrp > 0 && $rprice < $rmrp ) ? round( ( ( $rmrp - $rprice ) / $rmrp ) * 100 ) : 0;
                    $rimg     = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' ) ?: wc_placeholder_img_src();
                ?>
                <a href="<?php the_permalink(); ?>" class="group relative flex flex-col overflow-hidden border border-white/[0.06] hover:border-[#C8A46A]/30 transition-all">
                    <div class="relative overflow-hidden" style="aspect-ratio:3/4;">
                        <img src="<?php echo esc_url( $rimg ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" loading="lazy"/>
                        <?php if ( $rdisc > 0 ) : ?>
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.5"><?php echo esc_html( $rdisc ); ?>% off</span>
                        <?php endif; ?>
                        <!-- Quick wishlist -->
                        <button class="dt-wishlist-toggle absolute top-2 right-2 w-7 h-7 rounded-full bg-black/60 border border-white/20 flex items-center justify-center text-white/50 hover:text-red-400 transition-all z-10 text-sm" data-product-id="<?php echo get_the_ID(); ?>" onclick="event.preventDefault(); event.stopPropagation(); if(typeof toggleWishlist==='function') toggleWishlist(<?php echo get_the_ID(); ?>)">♡</button>
                    </div>
                    <div class="p-2">
                        <p class="text-[11px] text-white/80 leading-snug truncate"><?php the_title(); ?></p>
                        <div class="flex items-baseline gap-1.5 mt-1">
                            <span class="text-xs text-white font-medium">₹<?php echo number_format( (int) $rprice ); ?></span>
                            <?php if ( $rmrp > 0 && $rprice < $rmrp ) : ?>
                            <span class="text-[10px] text-white/30 line-through">₹<?php echo number_format( (int) $rmrp ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endif; endif; ?>

</div><!-- /#dt-wishlist-page -->

<!-- ── Toast Notification ──────────────────────────────────────────────────── -->
<div id="wl-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] flex items-center gap-3 bg-[#111] border border-[#C8A46A]/40 text-white px-5 py-3.5 shadow-2xl rounded-sm text-sm translate-y-20 opacity-0 transition-all duration-300 pointer-events-none min-w-[240px]" role="alert">
    <span id="wl-toast-icon" class="text-[#C8A46A] font-bold text-base flex-shrink-0">✔</span>
    <span id="wl-toast-msg">Done</span>
</div>

<!-- ── Wishlist Page Styles ─────────────────────────────────────────────────── -->
<style>
/* Filter tab styles */
.wl-filter-btn {
    background: transparent;
    border-color: rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.4);
    white-space: nowrap;
    transition: all 0.2s;
}
.wl-filter-btn:hover {
    border-color: rgba(200,164,106,0.4);
    color: rgba(200,164,106,0.8);
}
.wl-filter-btn.active {
    background: rgba(200,164,106,0.12);
    border-color: rgba(200,164,106,0.6);
    color: #C8A46A;
}

/* Card removal animation */
@keyframes wl-card-out {
    from { opacity: 1; transform: scale(1); }
    to   { opacity: 0; transform: scale(0.88); }
}
.wl-card-removing {
    animation: wl-card-out 0.28s ease-in forwards;
    pointer-events: none;
}

/* Toast slide-in */
.wl-toast-show {
    transform: translate(-50%, 0) !important;
    opacity: 1 !important;
}

/* Hide scrollbar for filter tabs */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

/* Ensure move-btn always visible on touch devices */
@media (hover: none) {
    .wl-card .translate-y-full { transform: none !important; }
}

/* Move to bag btn loading state */
.wl-move-btn.loading,
.wl-move-btn-mobile.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>

<!-- ── Wishlist Page JavaScript ─────────────────────────────────────────────── -->
<script>
(function () {
    'use strict';

    /* ── Toast ─────────────────────────────────────────────────────────────── */
    function showToast(msg, icon, duration) {
        var t   = document.getElementById('wl-toast');
        var ti  = document.getElementById('wl-toast-icon');
        var tm  = document.getElementById('wl-toast-msg');
        if (!t) return;
        ti.textContent = icon || '✔';
        tm.textContent = msg;
        t.classList.add('wl-toast-show');
        clearTimeout(t._timer);
        t._timer = setTimeout(function () { t.classList.remove('wl-toast-show'); }, duration || 3500);
    }

    /* ── Update header count ───────────────────────────────────────────────── */
    function updateCountBadge() {
        var remaining = document.querySelectorAll('.wl-card').length;
        var badge = document.getElementById('wl-count-badge');
        if (badge) badge.textContent = remaining + (remaining === 1 ? ' item' : ' items');
        var moveAll = document.getElementById('wl-btn-move-all');
        var clearBtn = document.getElementById('wl-btn-clear');
        if (remaining === 0) {
            if (moveAll) moveAll.classList.add('hidden');
            if (clearBtn) clearBtn.classList.add('hidden');
            // Show empty state in grid
            var grid = document.getElementById('wl-grid');
            if (grid) {
                grid.innerHTML = '<div class="col-span-full py-20 text-center">' +
                    '<div class="flex flex-col items-center gap-4">' +
                    '<svg class="w-16 h-16 text-[#C8A46A]/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>' +
                    '<p class="text-white/30 text-sm uppercase tracking-widest">Your wishlist is empty</p>' +
                    '<a href="<?php echo esc_js( $shop_url ); ?>" class="bg-[#C8A46A] text-black px-6 py-3 text-xs uppercase tracking-widest font-semibold hover:bg-[#b08d55] transition-all">Explore Collection</a>' +
                    '</div></div>';
            }
        }
    }

    /* ── Remove item ───────────────────────────────────────────────────────── */
    window.wlRemoveItem = function (id) {
        var card = document.getElementById('wl-item-' + id);
        if (!card) return;
        card.classList.add('wl-card-removing');
        setTimeout(function () {
            card.style.display = 'none';
            // Also fire the global wishlist toggle so heart icons update
            if (typeof toggleWishlist === 'function') { toggleWishlist(id); }
            updateCountBadge();
            showToast('Removed from wishlist', '✕');
        }, 280);
    };

    /* ── Move to bag ───────────────────────────────────────────────────────── */
    window.wlMoveToBag = function (id, btn) {
        if (btn) { btn.classList.add('loading'); }

        // Try AJAX add-to-cart first
        if (typeof jQuery !== 'undefined' && typeof wc_add_to_cart_params !== 'undefined') {
            jQuery.post(wc_add_to_cart_params.ajax_url || '<?php echo esc_js( admin_url( "admin-ajax.php" ) ); ?>', {
                action: 'dt_quick_add_to_cart',
                product_id: id,
                quantity: 1,
                nonce: (typeof dtVars !== 'undefined' ? dtVars.nonce : '')
            }).always(function () {
                if (btn) btn.classList.remove('loading');
                window.wlRemoveItem(id);
                showToast('Added to bag!', '🛍');
                // Update cart count in header
                if (typeof dtUpdateCartCount === 'function') dtUpdateCartCount();
            });
        } else if (typeof addToCart === 'function') {
            addToCart(id, 1);
            if (btn) btn.classList.remove('loading');
            window.wlRemoveItem(id);
            showToast('Added to bag!', '🛍');
        } else {
            // Fallback: navigate to ?add-to-cart=id
            window.location.href = '<?php echo esc_js( home_url( "/" ) ); ?>?add-to-cart=' + id;
        }
    };

    /* ── Clear all ─────────────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {

        var clearBtn = document.getElementById('wl-btn-clear');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (!confirm('Remove all items from your wishlist?')) return;
                var cards = document.querySelectorAll('.wl-card');
                cards.forEach(function (c, i) {
                    setTimeout(function () {
                        c.classList.add('wl-card-removing');
                        setTimeout(function () { c.style.display = 'none'; }, 280);
                    }, i * 60);
                });
                setTimeout(function () {
                    if (typeof wishlist !== 'undefined') wishlist = [];
                    if (typeof saveWishlist === 'function') saveWishlist();
                    updateCountBadge();
                    showToast('Wishlist cleared', '✕');
                }, cards.length * 60 + 300);
            });
        }

        /* Move All to Bag */
        var moveAllBtn = document.getElementById('wl-btn-move-all');
        if (moveAllBtn) {
            moveAllBtn.addEventListener('click', function () {
                if (!confirm('Move all items to your bag?')) return;
                var cards = document.querySelectorAll('.wl-card');
                var delay = 0;
                cards.forEach(function (c) {
                    var id = parseInt(c.dataset.id, 10);
                    setTimeout(function () { window.wlMoveToBag(id, null); }, delay);
                    delay += 300;
                });
            });
        }

        /* Share */
        var shareBtn = document.getElementById('wl-btn-share');
        if (shareBtn) {
            shareBtn.addEventListener('click', function () {
                var url = window.location.href;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(function () {
                        showToast('Wishlist link copied!', '🔗');
                    });
                } else {
                    showToast('Copy this link: ' + url, '🔗', 5000);
                }
            });
        }

        /* ── Sort ──────────────────────────────────────────────────────────── */
        var sortSel = document.getElementById('wl-sort');
        if (sortSel) {
            sortSel.addEventListener('change', function () {
                var grid = document.getElementById('wl-grid');
                if (!grid) return;
                var cards = Array.from(grid.querySelectorAll('.wl-card'));
                var val = this.value;
                cards.sort(function (a, b) {
                    if (val === 'price_asc')  return parseFloat(a.dataset.price)    - parseFloat(b.dataset.price);
                    if (val === 'price_desc') return parseFloat(b.dataset.price)    - parseFloat(a.dataset.price);
                    if (val === 'discount')   return parseFloat(b.dataset.discount) - parseFloat(a.dataset.discount);
                    if (val === 'rating')     return parseFloat(b.dataset.rating)   - parseFloat(a.dataset.rating);
                    return 0; // default = date order (DOM order)
                });
                cards.forEach(function (c) { grid.appendChild(c); });
            });
        }

        /* ── Filter tabs ───────────────────────────────────────────────────── */
        document.querySelectorAll('.wl-filter-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.wl-filter-btn').forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');
                var filter = this.dataset.filter;
                var grid   = document.getElementById('wl-grid');
                if (!grid) return;
                var cards   = grid.querySelectorAll('.wl-card');
                var visible = 0;
                cards.forEach(function (c) {
                    var cats = (c.dataset.cats || '').split(',');
                    var show = filter === 'all' || cats.indexOf(filter) !== -1;
                    c.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                var noResults = document.getElementById('wl-no-results');
                if (noResults) noResults.classList.toggle('hidden', visible > 0);
            });
        });

        /* Re-initialise Lucide icons if present */
        if (typeof lucide !== 'undefined' && lucide.createIcons) { lucide.createIcons(); }
    });
}());
</script>

<?php get_footer(); ?>
