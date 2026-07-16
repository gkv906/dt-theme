<?php
/**
 * The template for displaying search results pages
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-16 bg-[#050505] min-h-[60vh]">
    <div class="text-center mb-12">
        <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A] block mb-2"><?php esc_html_e( 'Search Results', 'dt-ecommerce-theme' ); ?></span>
        <h1 class="font-serif text-4xl md:text-5xl text-white">
            <?php
            /* translators: %s: search query. */
            printf( esc_html__( 'Search Results for: %s', 'dt-ecommerce-theme' ), '<span>' . get_search_query() . '</span>' );
            ?>
        </h1>
    </div>

    <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php
            while ( have_posts() ) :
                the_post();
                
                // If it is a product, use our custom product card style
                if ( get_post_type() === 'product' && class_exists( 'WooCommerce' ) ) :
                    global $product;
                    $product = wc_get_product( get_the_ID() );
                    if ( ! $product ) {
                        continue;
                    }
                    
                    $price = $product->get_price();
                    $mrp = $product->get_regular_price();
                    $rating = $product->get_average_rating();
                    if ( ! $rating ) $rating = '4.8';
                    $img1 = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    if ( ! $img1 ) $img1 = wc_placeholder_img_src();
                    $img2 = $img1;
                    
                    $attachment_ids = $product->get_gallery_image_ids();
                    if ( ! empty( $attachment_ids ) ) {
                        $img2 = wp_get_attachment_url( $attachment_ids[0] );
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
                    
                    $wishlist = dt_get_wishlist();
                    $in_wishlist = in_array( get_the_ID(), $wishlist );
                    ?>
                    <div class="product-card snap-center shrink-0 w-full group cursor-pointer relative flex flex-col justify-between">
                        <div class="relative w-full aspect-[3/4] overflow-hidden bg-[#111]">
                            <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
                                <?php if ( $discount > 0 ) : ?>
                                    <span class="bg-[#C8A46A] text-black text-[9px] uppercase tracking-widest px-2.5 py-1.5 font-bold shadow-md rounded-sm">Sale -<?php echo esc_html( $discount ); ?>%</span>
                                <?php endif; ?>
                            </div>
                            
                            <button 
                                onclick="event.stopPropagation(); toggleWishlistAction(<?php echo get_the_ID(); ?>);" 
                                data-wishlist-btn="<?php echo get_the_ID(); ?>"
                                title="Add to Wishlist"
                                class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/40 border border-[#C8A46A]/20 hover:border-[#C8A46A]/80 hover:bg-black/80 flex items-center justify-center transition-all duration-300 text-[#C8A46A]"
                            >
                                <?php if ( $in_wishlist ) : ?>
                                    <svg class="w-5 h-5 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <?php else : ?>
                                    <svg class="w-5 h-5 text-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <?php endif; ?>
                            </button>

                            <div onclick="window.location.href='<?php the_permalink(); ?>'" class="w-full h-full">
                                <img src="<?php echo esc_url( $img1 ); ?>" alt="<?php the_title(); ?>" class="product-image w-full h-full object-cover group-hover:opacity-0 absolute top-0 left-0 transition-opacity duration-700 ease-in-out" />
                                <img src="<?php echo esc_url( $img2 ); ?>" alt="<?php the_title(); ?>" class="product-image w-full h-full object-cover opacity-0 group-hover:opacity-100 absolute top-0 left-0 transition-opacity duration-700 ease-in-out" />
                            </div>

                            <button 
                                onclick="event.stopPropagation(); addCartItemAction(<?php echo get_the_ID(); ?>);"
                                class="absolute bottom-0 left-0 w-full bg-gradient-to-r from-[#b08d55] to-[#d8ba82] text-black font-semibold uppercase tracking-widest text-[10px] py-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out hover:brightness-110 flex items-center justify-center gap-2"
                            >
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Add to Bag
                            </button>
                        </div>

                        <div onclick="window.location.href='<?php the_permalink(); ?>'" class="mt-4 text-center">
                            <span class="text-[9px] uppercase tracking-[0.25em] text-[#C8A46A]/80 block mb-1"><?php echo esc_html( $fabric_label ); ?></span>
                            <h4 class="font-serif text-lg text-white group-hover:text-[#C8A46A] transition-colors leading-tight font-medium line-clamp-1"><?php the_title(); ?></h4>
                            
                            <div class="flex items-center justify-center gap-1 mt-2 mb-1.5 text-[#C8A46A]/90">
                                <i data-lucide="star" class="w-3 h-3 fill-[#C8A46A]"></i>
                                <span class="text-[10px] tracking-wider font-semibold"><?php echo esc_html( $rating ); ?></span>
                            </div>

                            <div class="flex items-center justify-center gap-3">
                                <span class="text-sm font-semibold text-[#C8A46A]">₹<?php echo number_format( $price ); ?></span>
                                <?php if ( $mrp > 0 && $price < $mrp ) : ?>
                                    <span class="text-xs line-through text-[#666] font-light">₹<?php echo number_format( $mrp ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <!-- Standard Post item -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-[#0a0a0a] border border-[#C8A46A]/20 p-6 flex flex-col justify-between hover:border-[#C8A46A] transition-all'); ?>>
                        <div>
                            <span class="text-[9px] uppercase tracking-widest text-[#C8A46A] block mb-2"><?php echo get_the_date(); ?></span>
                            <h2 class="font-serif text-xl text-white mb-3 hover:text-[#C8A46A] transition-colors">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="text-xs text-[#a3a3a3] font-light leading-relaxed mb-6">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="text-[10px] uppercase tracking-widest font-semibold text-[#C8A46A] hover:underline"><?php esc_html_e( 'Read More', 'dt-ecommerce-theme' ); ?> →</a>
                    </article>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
        
        <div class="mt-12">
            <?php the_posts_navigation(); ?>
        </div>

    <?php else : ?>
        <div class="text-center py-16 bg-[#0a0a0a] border border-[#C8A46A]/20 max-w-lg mx-auto">
            <i data-lucide="search-x" class="w-12 h-12 text-[#C8A46A] mx-auto mb-4"></i>
            <h3 class="font-serif text-xl text-white mb-2"><?php esc_html_e( 'No Results Found', 'dt-ecommerce-theme' ); ?></h3>
            <p class="text-xs text-[#a3a3a3] px-6"><?php esc_html_e( 'We couldn\'t find anything matching your search. Please check your keywords or explore other fabrics.', 'dt-ecommerce-theme' ); ?></p>
        </div>
    <?php endif; ?>
</main>

<script>
    function toggleWishlistAction(id) {
        if (typeof toggleWishlist === 'function') {
            toggleWishlist(id);
        }
    }
    function addCartItemAction(id) {
        if (typeof addToCart === 'function') {
            addToCart(id, 1);
        }
    }
</script>

<?php
get_footer();
