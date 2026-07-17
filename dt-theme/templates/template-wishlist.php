<?php
/**
 * Template Name: Wishlist Template
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main class="max-w-7xl mx-auto px-4 md:px-8 py-12 bg-[#050505] min-h-[70vh]">
    <div class="flex flex-col sm:flex-row sm:items-baseline justify-between mb-8 border-b border-white/10 pb-6 gap-4">
        <div>
            <h2 class="font-serif text-3xl md:text-4xl text-white"><?php esc_html_e( 'My Wishlist', 'dt-ecommerce-theme' ); ?></h2>
            <p class="text-xs text-[#C8A46A] mt-2 tracking-wide uppercase">
                <span id="wishlist-page-count"><?php echo esc_html( dt_get_wishlist_count() ); ?></span> <?php esc_html_e( 'Items Saved', 'dt-ecommerce-theme' ); ?>
            </p>
        </div>
        <button onclick="clearWishlistAction()" class="text-xs uppercase tracking-widest text-[#C8A46A] hover:text-white transition-colors self-start sm:self-auto">
            <?php esc_html_e( 'Clear Wishlist', 'dt-ecommerce-theme' ); ?>
        </button>
    </div>

    <!-- Wishlist Grid -->
    <div id="wishlist-page-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php
        $wishlist_ids = dt_get_wishlist();
        if ( ! empty( $wishlist_ids ) && class_exists( 'WooCommerce' ) ) :
            $args = array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'post__in'       => $wishlist_ids,
                'posts_per_page' => -1,
            );
            $wish_query = new WP_Query( $args );
            if ( $wish_query->have_posts() ) :
                while ( $wish_query->have_posts() ) :
                    $wish_query->the_post();
                    global $product;
                    
                    $price = $product->get_price();
                    $mrp = $product->get_regular_price();
                    $img1 = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
                    if ( ! $img1 ) {
                        $img1 = wc_placeholder_img_src();
                    }
                    
                    $fabrics = array();
                    $terms = get_the_terms( get_the_ID(), 'product_cat' );
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
                    ?>
                    <div id="wishlist-item-<?php echo get_the_ID(); ?>" class="group flex flex-col bg-white/[0.02] border border-white/10 overflow-hidden rounded-sm hover:border-white/30 transition-all duration-300 relative">
                        <div class="relative aspect-[3/4] overflow-hidden bg-white/5">
                            <img src="<?php echo esc_url( $img1 ); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700 ease-out" />
                            
                            <button onclick="event.stopPropagation(); removeWishlistItem(<?php echo get_the_ID(); ?>)" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white/80 hover:text-white hover:bg-black/80 hover:border-white/50 transition-all z-10 shadow-lg" title="Remove">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>

                            <?php if ( $discount > 0 ) : ?>
                                <div class="absolute bottom-3 left-3 bg-[#C8A46A] text-black text-[10px] font-bold uppercase tracking-wider px-2 py-1 shadow-lg"><?php echo esc_html( $discount ); ?>% OFF</div>
                            <?php endif; ?>
                        </div>

                        <div class="p-4 flex flex-col flex-grow relative" onclick="window.location.href='<?php the_permalink(); ?>'">
                            <span class="text-[10px] uppercase tracking-widest text-white/40 mb-1 block truncate"><?php echo esc_html( $fabric_label ); ?></span>
                            <h3 class="font-serif text-lg text-white mb-2 leading-tight truncate group-hover:text-[#C8A46A] transition-colors"><?php the_title(); ?></h3>
                            
                            <div class="flex items-baseline gap-2 mb-4 mt-auto">
                                <span class="text-sm font-medium text-[#C8A46A]">₹<?php echo number_format( $price ); ?></span>
                                <?php if ( $mrp > 0 && $price < $mrp ) : ?>
                                    <span class="text-xs text-white/40 line-through">₹<?php echo number_format( $mrp ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button onclick="event.stopPropagation(); moveToCart(<?php echo get_the_ID(); ?>)" class="w-full py-3.5 bg-white/5 border-t border-white/10 text-white text-xs uppercase tracking-widest hover:bg-[#C8A46A] hover:text-black hover:border-[#C8A46A] transition-all flex items-center justify-center gap-2 group/btn">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i> <?php esc_html_e( 'Move to Bag', 'dt-ecommerce-theme' ); ?>
                        </button>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
        else :
            ?>
            <div class="col-span-full py-16 text-center">
                <i data-lucide="heart" class="w-12 h-12 text-[#C8A46A]/40 mx-auto mb-4 animate-pulse"></i>
                <h3 class="font-serif text-xl text-white mb-2"><?php esc_html_e( 'Your Wishlist is Empty', 'dt-ecommerce-theme' ); ?></h3>
                <p class="text-xs text-[#a3a3a3] max-w-sm mx-auto mb-6"><?php esc_html_e( 'Explore our luxury sarees and save your favorites to view them later.', 'dt-ecommerce-theme' ); ?></p>
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ); ?>" class="bg-[#C8A46A] text-black px-6 py-3 uppercase tracking-widest text-xs font-semibold rounded-sm hover:bg-[#b08d55] transition-all"><?php esc_html_e( 'Start Shopping', 'dt-ecommerce-theme' ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function removeWishlistItem(id) {
        if (typeof toggleWishlist === 'function') {
            toggleWishlist(id);
            const item = document.getElementById('wishlist-item-' + id);
            if (item) {
                item.remove();
            }
            const countLabel = document.getElementById('wishlist-page-count');
            if (countLabel) {
                const currentCount = parseInt(countLabel.textContent);
                if (currentCount > 0) {
                    countLabel.textContent = currentCount - 1;
                }
            }
        }
    }
    
    function moveToCart(id) {
        if (typeof addToCart === 'function') {
            addToCart(id, 1);
            removeWishlistItem(id);
        }
    }

    function clearWishlistAction() {
        if (typeof wishlist !== 'undefined') {
            wishlist = [];
            if (typeof saveWishlist === 'function') {
                saveWishlist();
            }
            const grid = document.getElementById('wishlist-page-grid');
            if (grid) {
                grid.innerHTML = `
                    <div class="col-span-full py-16 text-center">
                        <i data-lucide="heart" class="w-12 h-12 text-[#C8A46A]/40 mx-auto mb-4"></i>
                        <h3 class="font-serif text-xl text-white mb-2"><?php esc_html_e( 'Your Wishlist is Empty', 'dt-ecommerce-theme' ); ?></h3>
                        <p class="text-xs text-[#a3a3a3] max-w-sm mx-auto mb-6"><?php esc_html_e( 'Explore our luxury sarees and save your favorites to view them later.', 'dt-ecommerce-theme' ); ?></p>
                        <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' ) ); ?>" class="bg-[#C8A46A] text-black px-6 py-3 uppercase tracking-widest text-xs font-semibold rounded-sm hover:bg-[#b08d55] transition-all"><?php esc_html_e( 'Start Shopping', 'dt-ecommerce-theme' ); ?></a>
                    </div>
                `;
            }
            const countLabel = document.getElementById('wishlist-page-count');
            if (countLabel) {
                countLabel.textContent = '0';
            }
        }
    }
</script>

<?php
get_footer();
