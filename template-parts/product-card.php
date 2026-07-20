<?php
/**
 * Template Part: Product Card — 100% matches index.html arrival-card style
 *
 * Usage: get_template_part( 'template-parts/product-card' );
 * Expects: $args['product'] (WC_Product object) or global $product
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

$product_id    = $product->get_id();
$product_title = $product->get_name();
$product_url   = get_permalink( $product_id );
$price         = $product->get_price();
$mrp           = $product->get_regular_price();
$rating        = $product->get_average_rating();
if ( ! $rating ) {
    $rating = '4.8';
}
$rating_count = $product->get_rating_count();

$img1 = get_the_post_thumbnail_url( $product_id, 'large' );
if ( ! $img1 ) {
    $img1 = wc_placeholder_img_src();
}

$gallery_ids = $product->get_gallery_image_ids();
$gallery_urls = array( $img1 );
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
$terms  = get_the_terms( $product_id, 'product_cat' );
if ( $terms && ! is_wp_error( $terms ) ) {
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
$in_wishlist = in_array( $product_id, $wishlist );
$heartIconHTML = $in_wishlist
    ? '<svg class="w-5 h-5 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>'
    : '<svg class="w-5 h-5 text-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';

$stars_html = '';
for ( $idx = 0; $idx < 5; $idx++ ) {
    $fill_class = $idx < round( $rating ) ? 'text-[#C8A46A] fill-[#C8A46A]' : 'text-gray-600';
    $stars_html .= '<svg class="w-3 h-3 ' . $fill_class . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>';
}

$is_bestseller = $product->get_total_sales() > 10;
$is_new = ( time() - strtotime( $product->get_date_created() ) ) < ( 30 * DAY_IN_SECONDS );

$badge_html = '';
if ( $is_bestseller ) {
    $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider flex items-center gap-1"><span>★</span> Bestseller</div>';
} elseif ( $is_new ) {
    $badge_html = '<div class="absolute top-3 left-3 z-20 bg-[#C8A46A] text-black text-[10px] font-bold px-2 py-1 uppercase tracking-wider">New</div>';
}
?>

<?php
$card_class = 'arrival-card group cursor-pointer flex flex-col gap-4 w-full';
if ( dt_get_theme_option( 'woo_hover_zoom', '1' ) === '1' ) {
    $card_class .= ' hover-zoom-enabled';
}
?>

<div onclick="window.location.href='<?php echo esc_url( $product_url ); ?>'" class="<?php echo esc_attr( $card_class ); ?>">
  <div class="card-corner-wrap">
    <span class="cc cc-tl" aria-hidden="true"></span>
    <span class="cc cc-tr" aria-hidden="true"></span>
    <span class="cc cc-bl" aria-hidden="true"></span>
    <span class="cc cc-br" aria-hidden="true"></span>
  <div class="relative aspect-[3/4] gallery-img-wrap bg-[#111] overflow-hidden">
    <?php echo $badge_html; ?>
    <div class="absolute top-3 right-3 z-20">
      <button data-wishlist-btn="<?php echo esc_attr( $product_id ); ?>" onclick="event.stopPropagation(); if (typeof toggleWishlist === 'function') { toggleWishlist(<?php echo esc_attr( $product_id ); ?>); }" class="w-8 h-8 bg-black/50 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:text-[#C8A46A] transition-all border border-white/10 hover:border-[#C8A46A] hover:scale-110">
        <?php echo $heartIconHTML; ?>
      </button>
    </div>
    <?php foreach ( $gallery_urls as $idx => $url ) : ?>
        <img data-gallery-slide src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $product_title ); ?> — view <?php echo $idx+1; ?>" class="<?php echo 0 === $idx ? 'gallery-active' : ''; ?> transition-transform duration-[800ms] ease-out" loading="lazy" />
    <?php endforeach; ?>
    <div class="gallery-dots">
        <?php foreach ( $gallery_urls as $idx => $url ) : ?>
            <span data-gallery-dot class="gallery-dot <?php echo 0 === $idx ? 'active' : ''; ?>"></span>
        <?php endforeach; ?>
    </div>
    <div class="absolute bottom-0 left-0 w-full p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 z-20 bg-gradient-to-t from-black/95 to-transparent flex flex-col gap-2">
      <?php if ( dt_get_theme_option( 'woo_quick_view', '1' ) !== '0' ) : ?>
      <button onclick="event.stopPropagation(); if (typeof openQuickView === 'function') { openQuickView(<?php echo esc_attr( $product_id ); ?>); } else { window.location.href='<?php echo esc_url( $product_url ); ?>'; }" class="w-full bg-[#111]/80 hover:bg-black text-[#C8A46A] border border-[#C8A46A]/30 py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm transition-colors">Quick View</button>
      <?php endif; ?>
      <button onclick="event.stopPropagation(); if (typeof addToCart === 'function') { addToCart(<?php echo esc_attr( $product_id ); ?>, 1); }" class="w-full btn-premium-cart text-black py-2.5 text-xs uppercase tracking-widest font-semibold rounded-sm flex items-center justify-center gap-2">
        <i data-lucide="shopping-bag" class="w-4 h-4 icon-bag"></i> Add to Bag
      </button>
    </div>
  </div><!-- /gallery-img-wrap -->
  </div><!-- /card-corner-wrap -->
  <div class="flex flex-col gap-1 items-center text-center">
    <span class="text-[#C8A46A] text-[10px] md:text-xs uppercase tracking-widest"><?php echo esc_html( $fabric_label ); ?></span>
    <h4 class="font-serif text-base md:text-lg text-[#F7F4EE] arrival-title transition-colors"><?php echo esc_html( $product_title ); ?></h4>
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
