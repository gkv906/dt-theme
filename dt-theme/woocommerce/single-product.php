<?php
/**
 * The Template for displaying all single products — 100% matches product.html
 *
 * @package DT_Ecommerce_Theme
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

// Safe defaults — will be properly set inside the while loop below.
$img1        = wc_placeholder_img_src();
$gallery_urls = array( $img1 );
?>

<main class="pb-24 md:pb-12 bg-black min-h-screen text-[#F7F4EE]">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">
        <?php
        while ( have_posts() ) :
            the_post();
            global $product;
            
            $product_id = $product->get_id();
            $is_variable = $product->is_type( 'variable' );
            $sku = $product->get_sku();
            $stock_status = $product->is_in_stock() ? 'In Stock' : 'Out of Stock';
            $price = $product->get_price();
            $mrp = $product->get_regular_price();
            $rating = $product->get_average_rating();
            if ( ! $rating ) $rating = '4.9';
            $reviews_count = $product->get_review_count();
            
            $img1 = get_the_post_thumbnail_url( $product_id, 'large' );
            if ( ! $img1 ) {
                $img1 = wc_placeholder_img_src();
            }
            
            $gallery_ids = $product->get_gallery_image_ids();
            $gallery_urls = array( $img1 );
            if ( ! empty( $gallery_ids ) ) {
                foreach ( $gallery_ids as $img_id ) {
                    $g_url = wp_get_attachment_url( $img_id );
                    if ( $g_url ) {
                        $gallery_urls[] = $g_url;
                    }
                }
            }

            $fabrics = array();
            $terms = get_the_terms( $product_id, 'product_cat' );
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
            
            <div class="flex flex-col md:flex-row gap-10 lg:gap-16">
                <!-- Left Column: Image Gallery -->
                <div class="w-full md:w-1/2 space-y-4">
                    <!-- Main Image Container -->
                    <div class="relative aspect-[3/4] w-full bg-[#111] rounded-sm overflow-hidden border border-[#C8A46A]/20 group">
                        <img 
                            id="product-main-img"
                            src="<?php echo esc_url( $img1 ); ?>" 
                            alt="<?php the_title_attribute(); ?>" 
                            class="w-full h-full object-cover object-top transition-transform duration-1000 group-hover:scale-105" 
                        />
                        <div class="absolute top-4 left-4 bg-black/60 backdrop-blur-sm px-3 py-1.5 rounded-sm border border-[#C8A46A]/30 flex items-center gap-2">
                            <i data-lucide="zoom-in" class="w-4 h-4 text-[#C8A46A]"></i>
                            <span class="text-[10px] uppercase tracking-widest text-[#C8A46A]">Zoom</span>
                        </div>
                        
                        <?php if ( count( $gallery_urls ) > 1 ) : ?>
                            <button 
                                onclick="prevImage()" 
                                title="Previous Image" 
                                aria-label="Previous Image" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-[#C8A46A] border border-[#C8A46A]/50 hover:border-[#C8A46A] text-[#C8A46A] hover:text-black w-10 h-10 rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-md"
                            >
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </button>
                            <button 
                                onclick="nextImage()" 
                                title="Next Image" 
                                aria-label="Next Image" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-[#C8A46A] border border-[#C8A46A]/50 hover:border-[#C8A46A] text-[#C8A46A] hover:text-black w-10 h-10 rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 backdrop-blur-md"
                            >
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Thumbnails -->
                    <?php if ( count( $gallery_urls ) > 1 ) : ?>
                        <div class="flex overflow-x-auto gap-3 no-scrollbar snap-x snap-mandatory py-1" id="product-thumbnails">
                            <?php foreach ( $gallery_urls as $idx => $url ) : ?>
                                <div onclick="changeMainImage(<?php echo $idx; ?>)" class="thumbnail-wrapper flex-shrink-0 w-[72px] snap-start aspect-[3/4] overflow-hidden border <?php echo 0 === $idx ? 'border-[#C8A46A]' : 'border-[#C8A46A]/20'; ?> hover:border-[#C8A46A] cursor-pointer bg-[#111] rounded-sm transition-colors">
                                    <img src="<?php echo esc_url( $url ); ?>" alt="Thumbnail <?php echo $idx; ?>" class="w-full h-full object-cover object-top">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Product Info -->
                <div class="w-full md:w-1/2 flex flex-col pt-2 md:pt-0">
                    <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A] block mb-2 font-medium"><?php echo esc_html( $fabric_label ); ?></span>
                    <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl text-[#F7F4EE] leading-[1.1] mb-4"><?php the_title(); ?></h1>
                    
                    <div class="flex items-center gap-4 text-xs tracking-wider mb-6 font-light uppercase">
                        <?php if ( $sku ) : ?>
                            <span class="text-gray-400">SKU: <span class="text-[#F7F4EE]"><?php echo esc_html( $sku ); ?></span></span>
                            <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                        <?php endif; ?>
                        <span class="flex items-center gap-1.5 text-[#C8A46A]">
                            <span class="w-2 h-2 rounded-full bg-[#C8A46A] shadow-[0_0_8px_rgba(200,164,106,0.6)]"></span>
                            <?php echo esc_html( $stock_status ); ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-[#C8A46A]/20">
                        <div class="flex text-[#C8A46A]" id="product-stars-container">
                            <?php for ( $idx = 0; $idx < 5; $idx++ ) : ?>
                                <i data-lucide="star" class="w-4 h-4 <?php echo $idx < round($rating) ? 'fill-[#C8A46A]' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm font-light text-[#F7F4EE]"><?php echo esc_html( $rating ); ?> <span class="text-gray-500">(<?php echo esc_html( $reviews_count ); ?> <?php esc_html_e( 'reviews', 'dt-ecommerce-theme' ); ?>)</span></span>
                        <span class="flex items-center gap-1 text-[#C8A46A] text-[10px] uppercase tracking-widest bg-[#C8A46A]/10 px-2 py-1 rounded-sm border border-[#C8A46A]/30">
                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                            <?php esc_html_e( 'Verified Loom', 'dt-ecommerce-theme' ); ?>
                        </span>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-6">
                        <div class="flex items-end gap-4 mb-3">
                            <span class="text-3xl md:text-4xl text-[#C8A46A] font-light tracking-wide">₹<?php echo number_format( $price ); ?></span>
                            <?php if ( $mrp > 0 && $price < $mrp ) : ?>
                                <span class="text-lg text-gray-500 line-through mb-1">₹<?php echo number_format( $mrp ); ?></span>
                                <span class="bg-[#631414] text-[#F7F4EE] text-xs uppercase tracking-widest px-3 py-1 rounded-full border border-[#C8A46A]/20 mb-1.5"><?php echo esc_html( $discount ); ?>% OFF</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-[#C8A46A] flex items-center gap-2 font-light">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            <?php esc_html_e( 'Extra 5% off on UPI payments', 'dt-ecommerce-theme' ); ?>
                        </p>
                    </div>

                    <!-- Delivery options -->
                    <div class="bg-[#111] border border-[#C8A46A]/20 rounded-sm p-5 mb-8 space-y-3">
                        <div class="flex justify-between text-sm font-light">
                            <span class="text-gray-400"><?php esc_html_e( 'Estimated Delivery:', 'dt-ecommerce-theme' ); ?></span>
                            <span class="text-[#F7F4EE]"><?php esc_html_e( 'Guaranteed delivery in 3-5 Business Days', 'dt-ecommerce-theme' ); ?></span>
                        </div>
                        <div class="flex justify-between text-sm font-light">
                            <span class="text-gray-400"><?php esc_html_e( 'Shipping Policy:', 'dt-ecommerce-theme' ); ?></span>
                            <span class="flex items-center gap-1.5 text-[#C8A46A]">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                <?php esc_html_e( 'Free Shipping on orders above ₹999', 'dt-ecommerce-theme' ); ?>
                            </span>
                        </div>
                    </div>

                    <?php if ( $is_variable ) : ?>
                        <div class="dt-real-variations mb-8">
                            <?php woocommerce_variable_add_to_cart(); ?>
                            <button type="button" id="variable-add-to-cart-btn" onclick="handleProductAction('cart')" class="dt-variable-add-to-cart w-full py-4 btn-premium-cart text-black uppercase tracking-widest text-xs font-bold flex items-center justify-center gap-3 rounded-sm group">
                                <i data-lucide="shopping-bag" class="w-4 h-4 icon-bag"></i>
                                <?php esc_html_e( 'Add to Bag', 'dt-ecommerce-theme' ); ?>
                            </button>
                            <button type="button" id="variable-buy-now-btn" onclick="handleProductAction('buy_now')" class="dt-variable-buy-now w-full py-4 mt-4 bg-transparent border border-[#C8A46A] text-[#C8A46A] uppercase tracking-widest text-xs font-bold hover:bg-[#C8A46A] hover:text-black transition-all rounded-sm gold-border-glow">
                                <?php esc_html_e( 'Buy Now', 'dt-ecommerce-theme' ); ?>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Quantity Selector -->
                    <div class="mb-10 flex items-center gap-6">
                        <span class="text-xs uppercase tracking-widest text-gray-400"><?php esc_html_e( 'Quantity', 'dt-ecommerce-theme' ); ?></span>
                        <div class="flex items-center border border-gray-800 rounded-sm overflow-hidden bg-[#111]">
                            <button onclick="decrementQty()" title="Decrease Quantity" aria-label="Decrease Quantity" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:bg-gray-800 hover:text-[#C8A46A] transition-colors">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span id="quantity-val" class="w-12 text-center text-sm font-light text-[#F7F4EE]">1</span>
                            <button onclick="incrementQty()" title="Increase Quantity" aria-label="Increase Quantity" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:bg-gray-800 hover:text-[#C8A46A] transition-colors">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <?php if ( ! $is_variable ) : ?>
                        <!-- Hidden WooCommerce Form for Native Add to Cart -->
                        <form class="cart hidden" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                            <input type="hidden" name="quantity" id="hidden-qty-input" value="1" />
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>" id="hidden-submit-btn" class="hidden"></button>
                        </form>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="flex flex-col gap-4 mb-8">
                        <?php if ( ! $is_variable ) : ?>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button id="add-to-cart-btn" onclick="handleProductAction('cart')" class="flex-1 py-4 btn-premium-cart text-black uppercase tracking-widest text-xs font-bold flex items-center justify-center gap-3 rounded-sm group">
                                    <i data-lucide="shopping-bag" class="w-4 h-4 icon-bag"></i>
                                    <?php esc_html_e( 'Add to Bag', 'dt-ecommerce-theme' ); ?>
                                </button>
                                <button id="buy-now-btn" onclick="handleProductAction('buy_now')" class="flex-1 py-4 bg-transparent border border-[#C8A46A] text-[#C8A46A] uppercase tracking-widest text-xs font-bold hover:bg-[#C8A46A] hover:text-black transition-all rounded-sm gold-border-glow">
                                    <?php esc_html_e( 'Buy It Now', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                        <button id="wishlist-toggle-btn" onclick="toggleProductWishlist()" class="flex items-center justify-center gap-2 text-xs uppercase tracking-widest text-gray-500 hover:text-[#C8A46A] py-3 transition-colors">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            <span><?php esc_html_e( 'Add to Wishlist', 'dt-ecommerce-theme' ); ?></span>
                        </button>
                    </div>

                    <!-- Product Tabs -->
                    <div class="border-t border-[#C8A46A]/20 pt-8 mt-4">
                        <div class="flex border-b border-[#C8A46A]/10 overflow-x-auto no-scrollbar gap-6 md:gap-8 pb-3">
                            <button onclick="switchTab('tab-desc')" id="btn-tab-desc" class="tab-btn pb-2 text-xs uppercase tracking-widest font-semibold border-b-2 border-[#C8A46A] text-[#C8A46A]">Description</button>
                            <button onclick="switchTab('tab-specs')" id="btn-tab-specs" class="tab-btn pb-2 text-xs uppercase tracking-widest font-semibold border-b-2 border-transparent text-gray-500 hover:text-white">Specifications</button>
                            <button onclick="switchTab('tab-care')" id="btn-tab-care" class="tab-btn pb-2 text-xs uppercase tracking-widest font-semibold border-b-2 border-transparent text-gray-500 hover:text-white">Wash Care</button>
                        </div>
                        <div class="pt-6 text-sm font-light text-gray-300 leading-relaxed min-h-[120px]">
                            <div id="tab-desc" class="tab-content space-y-4">
                                <p><?php the_content(); ?></p>
                            </div>
                            <div id="tab-specs" class="tab-content hidden space-y-2">
                                <p><strong>Fabric:</strong> <?php echo esc_html( $fabric_label ); ?></p>
                                <p><strong>Zari Type:</strong> Tested Gold & Silver Zari threads</p>
                                <p><strong>Length:</strong> 5.5 meters + 0.8 meter blouse fabric</p>
                                <p><strong>Weave Technique:</strong> Handloom Kadwa & Kadhua weave</p>
                                <p><strong>Origin:</strong> Varanasi (Benares), India</p>
                            </div>
                            <div id="tab-care" class="tab-content hidden space-y-4">
                                <p>To preserve the heavy handloom zari threads and natural luster of silk:</p>
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Dry Clean Only. Never wash with water or washing machines.</li>
                                    <li>Store in clean white cotton or muslin fabric wrap, folded properly. Avoid using plastic covers.</li>
                                    <li>Iron on low-medium warmth under a protective fabric sheet. Do not iron directly on zari cords.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="border-white/10 my-16" />

            <!-- CUSTOMER REVIEWS SECTION -->
            <section class="space-y-12">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php esc_html_e( 'True Stories', 'dt-ecommerce-theme' ); ?></span>
                        <h3 class="font-serif text-3xl md:text-4xl text-white"><?php esc_html_e( 'Loom Reviews', 'dt-ecommerce-theme' ); ?></h3>
                    </div>
                    <button onclick="openWriteReviewModal()" class="btn-gold-shimmer px-6 py-3 text-xs uppercase tracking-widest font-semibold rounded-sm">
                        + Write A Review
                    </button>
                </div>

                <!-- Rating Overview Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-[#0a0a0a] p-8 border border-[#C8A46A]/10 rounded-sm">
                    <div class="flex flex-col items-center justify-center text-center p-4 border-b lg:border-b-0 lg:border-r border-white/5">
                        <span class="text-6xl font-serif text-[#C8A46A] font-bold" id="avg-rating-val"><?php echo esc_html( $rating ); ?></span>
                        <div class="flex text-[#C8A46A] gap-1 my-3" id="avg-stars-container">
                            <?php for ( $idx = 0; $idx < 5; $idx++ ) : ?>
                                <i data-lucide="star" class="w-3.5 h-3.5 <?php echo $idx < round($rating) ? 'fill-[#C8A46A] text-[#C8A46A]' : 'text-gray-700'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Based on <span id="total-reviews-count"><?php echo esc_html( $reviews_count ); ?></span> Reviews</span>
                    </div>

                    <div class="space-y-3 p-4 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-white/5">
                        <div class="flex items-center gap-4 text-xs">
                            <span class="w-10 text-gray-400">5 star</span>
                            <div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-[#C8A46A] rounded-full w-[90%]"></div>
                            </div>
                            <span class="w-8 text-right text-gray-500">90%</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <span class="w-10 text-gray-400">4 star</span>
                            <div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-[#C8A46A] rounded-full w-[8%]"></div>
                            </div>
                            <span class="w-8 text-right text-gray-500">8%</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <span class="w-10 text-gray-400">3 star</span>
                            <div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-[#C8A46A] rounded-full w-[2%]"></div>
                            </div>
                            <span class="w-8 text-right text-gray-500">2%</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center text-center p-4">
                        <div class="w-16 h-16 rounded-full bg-[#C8A46A]/10 border border-[#C8A46A]/20 flex items-center justify-center text-[#C8A46A] mb-4 animate-pulse">
                            <i data-lucide="hand-heart" class="w-8 h-8"></i>
                        </div>
                        <span class="text-lg font-serif text-white font-medium">98% Recommendation</span>
                        <p class="text-xs text-gray-500 mt-2 max-w-xs font-light">Of surveyed customers reported extremely high satisfaction with weave weight and zari fidelity.</p>
                    </div>
                </div>

                <!-- Horizontal Carousel Container -->
                <div class="relative group">
                    <button onclick="scrollReviews(-1)" title="Previous Review" aria-label="Previous Review" class="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] bg-black/80 flex items-center justify-center z-20 hover:bg-[#C8A46A] hover:text-black transition-all">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button onclick="scrollReviews(1)" title="Next Review" aria-label="Next Review" class="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full border border-[#C8A46A]/40 text-[#C8A46A] bg-black/80 flex items-center justify-center z-20 hover:bg-[#C8A46A] hover:text-black transition-all">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>

                    <div id="reviews-carousel" class="flex overflow-x-auto gap-6 pb-6 no-scrollbar snap-x snap-mandatory">
                        <!-- Loaded dynamically via JS -->
                    </div>
                </div>
            </section>

            <!-- Divider -->
            <hr class="border-white/10 my-16" />

            <!-- RELATED PRODUCTS SECTION -->
            <section class="space-y-8">
                <div class="flex items-end justify-between">
                    <div>
                        <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A]/60 block mb-2 font-medium"><?php esc_html_e( 'Perfect Pairings', 'dt-ecommerce-theme' ); ?></span>
                        <h3 class="font-serif text-2xl md:text-3xl text-white"><?php esc_html_e( 'Related Masterpieces', 'dt-ecommerce-theme' ); ?></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="scrollRelated(-1)" title="Previous Related Product" aria-label="Previous Related Product" class="w-9 h-9 rounded-full border border-white/10 text-gray-400 hover:border-[#C8A46A] hover:text-[#C8A46A] flex items-center justify-center bg-black/40">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        </button>
                        <button onclick="scrollRelated(1)" title="Next Related Product" aria-label="Next Related Product" class="w-9 h-9 rounded-full border border-white/10 text-gray-400 hover:border-[#C8A46A] hover:text-[#C8A46A] flex items-center justify-center bg-black/40">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div id="related-carousel" class="flex overflow-x-auto gap-6 pb-4 no-scrollbar snap-x scroll-smooth">
                    <?php
                    $related_ids = wc_get_related_products( $product_id, 6 );
                    if ( ! empty( $related_ids ) ) {
                        foreach ( $related_ids as $rel_id ) {
                            echo '<div class="snap-start shrink-0 w-[240px]">';
                            $post = get_post( $rel_id );
                            setup_postdata( $post );
                            get_template_part( 'template-parts/product-card' );
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p class="text-xs text-gray-500">' . esc_html__( 'No related masterpieces found.', 'dt-ecommerce-theme' ) . '</p>';
                    }
                    ?>
                </div>
            </section>
            
            <?php
        endwhile;
        ?>
    </div>
</main>

<!-- Mobile Bottom Sticky Checkout Panel -->
<div class="fixed bottom-0 left-0 right-0 bg-[#0a0a0a]/95 backdrop-blur-md border-t border-[#C8A46A]/20 p-4 flex gap-4 md:hidden z-[60] safe-area-bottom">
    <button onclick="handleProductAction('cart')" class="flex-[1.5] py-4 btn-premium-cart text-black uppercase tracking-widest text-xs font-bold flex items-center justify-center gap-2 rounded-sm">
        <i data-lucide="shopping-bag" class="w-4 h-4"></i> <?php esc_html_e( 'Add to Bag', 'dt-ecommerce-theme' ); ?>
    </button>
    <button onclick="handleProductAction('buy_now')" class="flex-1 py-4 border border-[#C8A46A] text-[#C8A46A] uppercase tracking-widest text-xs font-bold hover:bg-[#C8A46A] hover:text-black text-center flex items-center justify-center rounded-sm bg-transparent">
        <?php esc_html_e( 'Buy Now', 'dt-ecommerce-theme' ); ?>
    </button>
</div>

<!-- Options Selection Modal Popup -->
<div id="options-selection-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <!-- Backdrop Close -->
    <div class="absolute inset-0" onclick="closeOptionsModal()"></div>
    
    <!-- Modal Card -->
    <div id="options-modal-card" class="relative w-full max-w-md bg-[#0a0a0a] border border-[#C8A46A]/30 rounded-lg overflow-hidden shadow-2xl flex flex-col transform scale-95 opacity-0 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40">
            <h3 class="font-serif text-sm text-[#C8A46A] tracking-widest uppercase"><?php esc_html_e( 'Select Saree Options', 'dt-ecommerce-theme' ); ?></h3>
            <button onclick="closeOptionsModal()" title="Close" aria-label="Close" class="text-gray-400 hover:text-white transition-colors p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 space-y-6">
            <div class="flex gap-4 items-center bg-[#111] p-3 rounded border border-gray-900">
                <img id="modal-product-img" src="<?php echo esc_url( $img1 ); ?>" alt="Saree Preview" class="w-16 h-20 object-cover rounded-sm border border-gray-800" />
                <div>
                    <h4 id="modal-product-name" class="font-serif text-sm text-white"><?php the_title(); ?></h4>
                    <p id="modal-product-price" class="text-xs text-[#C8A46A] mt-1 font-semibold">₹<?php echo number_format( $price ); ?></p>
                </div>
            </div>
            
            <div id="modal-variation-selectors" class="space-y-5"></div>

            <div class="flex items-center justify-between bg-[#111] border border-[#C8A46A]/15 p-3 rounded-sm">
                <span class="text-xs uppercase tracking-widest text-gray-400"><?php esc_html_e( 'Quantity', 'dt-ecommerce-theme' ); ?></span>
                <div class="flex items-center border border-gray-800 rounded-sm overflow-hidden bg-black">
                    <button type="button" onclick="decrementQty()" title="Decrease Quantity" aria-label="Decrease Quantity" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:bg-gray-900 hover:text-[#C8A46A] transition-colors">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <span id="modal-quantity-val" class="w-12 text-center text-sm font-light text-[#F7F4EE]">1</span>
                    <button type="button" onclick="incrementQty()" title="Increase Quantity" aria-label="Increase Quantity" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:bg-gray-900 hover:text-[#C8A46A] transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            
            <!-- Validation banner -->
            <div id="modal-validation-error" class="hidden flex items-center gap-2 bg-red-950/40 border border-red-500/30 p-3 text-red-400 text-xs rounded">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>
                <span id="validation-error-text"><?php esc_html_e( 'Please select available color and length size first.', 'dt-ecommerce-theme' ); ?></span>
            </div>
        </div>
        
        <!-- Footer action -->
        <div class="p-5 border-t border-[#C8A46A]/20 bg-black/40">
            <button id="modal-confirm-action-btn" onclick="confirmOptionsAndSubmit()" class="w-full py-3.5 btn-premium-cart text-black uppercase tracking-widest text-xs font-bold rounded-sm shadow-lg">
                <?php esc_html_e( 'Confirm & Add to Bag', 'dt-ecommerce-theme' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Write a Review -->
<div id="write-review-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="absolute inset-0" onclick="closeWriteReviewModal()"></div>
    <div id="write-review-card" class="relative w-full max-w-md max-h-[90vh] bg-[#0a0a0a] border border-[#C8A46A]/30 rounded-lg overflow-hidden shadow-2xl flex flex-col transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40">
            <h3 class="font-serif text-sm text-[#C8A46A] tracking-widest uppercase"><?php esc_html_e( 'Write a Review', 'dt-ecommerce-theme' ); ?></h3>
            <button onclick="closeWriteReviewModal()" title="Close" aria-label="Close" class="text-gray-400 hover:text-white transition-colors p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form id="write-review-form" onsubmit="submitProductReview(event)" class="p-6 space-y-4 text-left overflow-y-auto flex-1 min-h-0 custom-scrollbar">
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-2"><?php esc_html_e( 'Overall Rating', 'dt-ecommerce-theme' ); ?> <span class="text-red-400">*</span></label>
                <div class="flex gap-2 text-gray-600" id="rating-star-selector">
                    <?php for ( $idx = 1; $idx <= 5; $idx++ ) : ?>
                        <button type="button" onclick="selectReviewStars(<?php echo $idx; ?>)" class="star-btn transition-transform hover:scale-115 text-gray-600">
                            <i data-lucide="star" class="w-6 h-6"></i>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="selected-stars-count" required value="" />
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-2"><?php esc_html_e( 'Your Name', 'dt-ecommerce-theme' ); ?> <span class="text-red-400">*</span></label>
                <input required type="text" id="review-author" placeholder="E.g., Priya Sharma" class="w-full bg-white/5 border border-white/10 rounded-sm py-2 px-3 text-xs focus:outline-none focus:border-[#C8A46A]/50 text-white" />
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-2"><?php esc_html_e( 'Review Title', 'dt-ecommerce-theme' ); ?> <span class="text-red-400">*</span></label>
                <input required type="text" id="review-title" placeholder="E.g., Breathtakingly beautiful!" class="w-full bg-white/5 border border-white/10 rounded-sm py-2 px-3 text-xs focus:outline-none focus:border-[#C8A46A]/50 text-white" />
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-wider text-gray-400 font-semibold mb-2"><?php esc_html_e( 'Review Details', 'dt-ecommerce-theme' ); ?> <span class="text-red-400">*</span></label>
                <textarea required id="review-body" rows="4" placeholder="Wore this for a special occasion. The gold zari weight is premium..." class="w-full bg-white/5 border border-white/10 rounded-sm py-2 px-3 text-xs focus:outline-none focus:border-[#C8A46A]/50 text-white resize-none"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 btn-premium-cart text-black uppercase tracking-widest text-xs font-bold rounded-sm shadow-md transition-all mt-2">
                <?php esc_html_e( 'Submit Review', 'dt-ecommerce-theme' ); ?>
            </button>
        </form>
    </div>
</div>

<script>
    let galleryUrls = <?php echo json_encode( $gallery_urls ); ?>;
    let selectedImageIndex = 0;
    let selectedQty = 1;
    let currentSelColor = null;
    let currentSelSize = null;
    let actionType = 'cart'; // 'cart' or 'buy_now'
    const isVariableProduct = <?php echo $is_variable ? 'true' : 'false'; ?>;

    function changeMainImage(idx) {
        selectedImageIndex = idx;
        const mainImg = document.getElementById('product-main-img');
        if (mainImg && galleryUrls[idx]) {
            mainImg.src = galleryUrls[idx];
        }
        let activeThumb = null;
        document.querySelectorAll('#product-thumbnails .thumbnail-wrapper').forEach((thumb, i) => {
            if (i === idx) {
                thumb.classList.add('border-[#C8A46A]');
                thumb.classList.remove('border-[#C8A46A]/20');
                activeThumb = thumb;
            } else {
                thumb.classList.remove('border-[#C8A46A]');
                thumb.classList.add('border-[#C8A46A]/20');
            }
        });
        // Scroll active thumbnail into view inside the horizontal strip
        if (activeThumb) {
            activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    function prevImage() {
        let newIdx = selectedImageIndex - 1;
        if (newIdx < 0) newIdx = galleryUrls.length - 1;
        changeMainImage(newIdx);
    }

    function nextImage() {
        let newIdx = (selectedImageIndex + 1) % galleryUrls.length;
        changeMainImage(newIdx);
    }

    // ── Auto-slide gallery (3 s interval, pauses on hover) ──
    let _galleryTimer = null;
    function startGalleryAutoSlide() {
        if (galleryUrls.length <= 1) return;
        stopGalleryAutoSlide();
        _galleryTimer = setInterval(() => nextImage(), 3000);
    }
    function stopGalleryAutoSlide() {
        clearInterval(_galleryTimer);
        _galleryTimer = null;
    }

    function selectColor(colorId, colorLabel) {
        currentSelColor = colorId;
        
        // Update labels on desktop & modal
        const selectedColorLabel = document.getElementById('selected-color-label');
        if (selectedColorLabel) {
            selectedColorLabel.textContent = colorLabel;
            selectedColorLabel.classList.remove('text-red-400');
            selectedColorLabel.classList.add('text-white');
        }
        
        const modalSelectedColorLabel = document.getElementById('modal-selected-color-label');
        if (modalSelectedColorLabel) {
            modalSelectedColorLabel.textContent = colorLabel;
            modalSelectedColorLabel.classList.remove('text-red-400');
            modalSelectedColorLabel.classList.add('text-white');
        }

        // Update active class styles
        document.querySelectorAll('#color-selectors button, #modal-color-selectors button').forEach(btn => {
            if (btn.getAttribute('data-color-id') === colorId) {
                btn.classList.add('border-[#C8A46A]');
                btn.classList.remove('border-transparent');
            } else {
                btn.classList.remove('border-[#C8A46A]');
                btn.classList.add('border-transparent');
            }
        });
    }

    function selectSize(sizeVal) {
        currentSelSize = sizeVal;

        // Update labels on desktop & modal
        const selectedSizeLabel = document.getElementById('selected-size-label');
        if (selectedSizeLabel) {
            selectedSizeLabel.textContent = sizeVal;
            selectedSizeLabel.classList.remove('text-red-400');
            selectedSizeLabel.classList.add('text-white');
        }

        const modalSelectedSizeLabel = document.getElementById('modal-selected-size-label');
        if (modalSelectedSizeLabel) {
            modalSelectedSizeLabel.textContent = sizeVal;
            modalSelectedSizeLabel.classList.remove('text-red-400');
            modalSelectedSizeLabel.classList.add('text-white');
        }

        // Update active class styles
        document.querySelectorAll('#size-selectors button, #modal-size-selectors button').forEach(btn => {
            if (btn.getAttribute('data-size-val') === sizeVal) {
                btn.classList.add('border-[#C8A46A]', 'text-white');
                btn.classList.remove('border-white/10', 'text-gray-400');
            } else {
                btn.classList.remove('border-[#C8A46A]', 'text-white');
                btn.classList.add('border-white/10', 'text-gray-400');
            }
        });
    }

    function incrementQty() {
        selectedQty++;
        document.getElementById('quantity-val').textContent = selectedQty;
        const modalQty = document.getElementById('modal-quantity-val');
        if (modalQty) modalQty.textContent = selectedQty;
        syncProductQuantity();
    }

    function decrementQty() {
        if (selectedQty > 1) {
            selectedQty--;
            document.getElementById('quantity-val').textContent = selectedQty;
            const modalQty = document.getElementById('modal-quantity-val');
            if (modalQty) modalQty.textContent = selectedQty;
            syncProductQuantity();
        }
    }

    function syncProductQuantity() {
        const simpleQty = document.getElementById('hidden-qty-input');
        if (simpleQty) simpleQty.value = selectedQty;

        const variableQty = document.querySelector('.dt-real-variations form.cart input.qty');
        if (variableQty) variableQty.value = selectedQty;
    }

    function handleProductAction(type) {
        actionType = type;
        if (isVariableProduct) {
            if (!variableSelectionsComplete()) {
                openOptionsModal();
                return;
            }
            submitVariableAction();
            return;
        }

        submitForm();
    }

    function submitForm() {
        if (actionType === 'buy_now') {
            const checkoutUrl = "<?php echo esc_url( wc_get_checkout_url() ); ?>";
            const addCartParam = "add-to-cart=<?php echo $product_id; ?>";
            const qtyParam = "quantity=" + selectedQty;
            window.location.href = checkoutUrl + "?" + addCartParam + "&" + qtyParam;
        } else {
            // Submit standard WooCommerce cart form
            const hiddenSubmit = document.getElementById('hidden-submit-btn');
            if (hiddenSubmit) hiddenSubmit.click();
        }
    }

    function getVariableForm() {
        return document.querySelector('.dt-real-variations form.variations_form');
    }

    function variableSelectionsComplete() {
        const form = getVariableForm();
        if (!form) return false;

        return Array.from(form.querySelectorAll('table.variations select')).every((select) => !!select.value);
    }

    function submitVariableAction() {
        if (actionType === 'buy_now') {
            handleVariableBuyNow();
        } else {
            submitVariableAddToCart();
        }
    }

    function submitVariableAddToCart() {
        syncProductQuantity();
        const form = getVariableForm();
        if (!form) return;

        const buyNowInput = form.querySelector('input[name="dt_buy_now"]');
        if (buyNowInput) buyNowInput.remove();

        const addButton = form.querySelector('.single_add_to_cart_button');
        if (addButton) addButton.click();
    }

    function handleVariableBuyNow() {
        syncProductQuantity();
        const form = getVariableForm();
        if (!form) return;

        let buyNowInput = form.querySelector('input[name="dt_buy_now"]');
        if (!buyNowInput) {
            buyNowInput = document.createElement('input');
            buyNowInput.type = 'hidden';
            buyNowInput.name = 'dt_buy_now';
            form.appendChild(buyNowInput);
        }
        buyNowInput.value = '1';

        const addButton = form.querySelector('.single_add_to_cart_button');
        if (addButton) addButton.click();
    }

    function initVariationChoiceButtons() {
        const form = getVariableForm();
        if (!form) return;

        form.querySelectorAll('table.variations select').forEach((select) => {
            if (select.dataset.dtChoicesReady === '1') return;

            const row = select.closest('tr');
            const label = row ? row.querySelector('th.label label') : null;
            const attrName = (select.name || select.id || '').toLowerCase();
            const isColor = attrName.includes('color') || attrName.includes('colour');
            const isSize = attrName.includes('size') || attrName.includes('length');
            const choices = document.createElement('div');
            choices.className = isColor ? 'dt-variation-choices dt-variation-choices-color' : 'dt-variation-choices dt-variation-choices-size';
            choices.dataset.forSelect = select.name || select.id;

            Array.from(select.options).forEach((option) => {
                if (!option.value) return;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = isColor ? 'dt-variation-choice dt-color-choice' : 'dt-variation-choice dt-size-choice';
                btn.dataset.value = option.value;
                btn.setAttribute('aria-label', option.textContent.trim());

                if (isColor) {
                    const swatch = document.createElement('span');
                    swatch.className = 'dt-color-dot';
                    swatch.style.background = guessColorFromName(option.textContent);
                    btn.appendChild(swatch);
                }

                const text = document.createElement('span');
                text.textContent = option.textContent.trim();
                btn.appendChild(text);

                btn.addEventListener('click', () => {
                    select.value = option.value;
                    triggerVariationChange(select);
                    updateVariationChoiceState(select, choices);
                });

                choices.appendChild(btn);
            });

            select.insertAdjacentElement('afterend', choices);
            select.dataset.dtChoicesReady = '1';
            updateVariationChoiceState(select, choices);

            select.addEventListener('change', () => updateVariationChoiceState(select, choices));
            if (label && !label.querySelector('.dt-choice-hint')) {
                const hint = document.createElement('span');
                hint.className = 'dt-choice-hint';
                hint.textContent = isSize ? 'Available sizes' : 'Available colors';
                label.appendChild(hint);
            }
        });

        if (window.jQuery) {
            window.jQuery(form).on('reset_data', function() {
                form.querySelectorAll('table.variations select').forEach((select) => {
                    const choices = select.parentElement.querySelector('.dt-variation-choices');
                    if (choices) updateVariationChoiceState(select, choices);
                });
            });
        }

        buildModalVariationChoices();
    }

    function buildModalVariationChoices() {
        const form = getVariableForm();
        const modalWrap = document.getElementById('modal-variation-selectors');
        if (!form || !modalWrap) return;

        modalWrap.innerHTML = '';

        form.querySelectorAll('table.variations select').forEach((select) => {
            const row = select.closest('tr');
            const label = row ? row.querySelector('th.label label') : null;
            const title = label ? label.childNodes[0].textContent.trim() : (select.name || 'Option');
            const attrName = (select.name || select.id || '').toLowerCase();
            const isColor = attrName.includes('color') || attrName.includes('colour');
            const isSize = attrName.includes('size') || attrName.includes('length');
            const section = document.createElement('div');
            const status = document.createElement('span');
            const choices = document.createElement('div');

            section.className = 'dt-modal-variation-section';
            status.className = 'dt-modal-choice-status';
            status.textContent = select.value
                ? select.options[select.selectedIndex].textContent.trim()
                : (isColor ? 'Choose color' : isSize ? 'Choose size' : 'Choose option');

            section.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs uppercase tracking-wider text-gray-400">${isColor ? 'Available Colors' : isSize ? 'Available Sizes' : title}</span>
                </div>
            `;
            section.querySelector('div').appendChild(status);
            choices.className = isColor ? 'dt-variation-choices dt-variation-choices-color' : 'dt-variation-choices dt-variation-choices-size';

            Array.from(select.options).forEach((option) => {
                if (!option.value) return;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = isColor ? 'dt-variation-choice dt-color-choice' : 'dt-variation-choice dt-size-choice';
                btn.dataset.value = option.value;
                btn.setAttribute('aria-label', option.textContent.trim());

                if (isColor) {
                    const swatch = document.createElement('span');
                    swatch.className = 'dt-color-dot';
                    swatch.style.background = guessColorFromName(option.textContent);
                    btn.appendChild(swatch);
                }

                const text = document.createElement('span');
                text.textContent = option.textContent.trim();
                btn.appendChild(text);

                btn.addEventListener('click', () => {
                    select.value = option.value;
                    triggerVariationChange(select);
                    status.textContent = option.textContent.trim();
                    status.classList.remove('text-red-400');
                    status.classList.add('text-white');

                    const inlineChoices = select.parentElement.querySelector('.dt-variation-choices');
                    if (inlineChoices) updateVariationChoiceState(select, inlineChoices);
                    updateVariationChoiceState(select, choices);
                    buildModalVariationChoices();
                });

                choices.appendChild(btn);
            });

            section.appendChild(choices);
            modalWrap.appendChild(section);
            updateVariationChoiceState(select, choices);
        });
    }

    function updateVariationChoiceState(select, choices) {
        const availableValues = Array.from(select.options)
            .filter((option) => option.value && !option.disabled)
            .map((option) => option.value);

        choices.querySelectorAll('.dt-variation-choice').forEach((btn) => {
            const isSelected = btn.dataset.value === select.value;
            const isAvailable = availableValues.includes(btn.dataset.value);
            btn.classList.toggle('is-selected', isSelected);
            btn.classList.toggle('is-disabled', !isAvailable);
            btn.disabled = !isAvailable;
        });
    }

    function triggerVariationChange(select) {
        select.dispatchEvent(new Event('change', { bubbles: true }));
        if (window.jQuery) {
            window.jQuery(select).trigger('change');
        }
    }

    function guessColorFromName(name) {
        const key = String(name || '').toLowerCase();
        const map = {
            black: '#050505',
            blue: '#2458d3',
            brown: '#7a4528',
            cream: '#f1dec0',
            gold: '#c8a46a',
            green: '#16784a',
            grey: '#777',
            gray: '#777',
            ivory: '#f7f0df',
            maroon: '#6d1024',
            orange: '#e56522',
            pink: '#d83f87',
            purple: '#6b3ba8',
            red: '#b91c1c',
            white: '#f8f8f8',
            yellow: '#e6b82e'
        };

        const match = Object.keys(map).find((color) => key.includes(color));
        return match ? map[match] : 'linear-gradient(135deg, #C8A46A, #7c2d12)';
    }

    function openOptionsModal() {
        buildModalVariationChoices();
        const modalQty = document.getElementById('modal-quantity-val');
        if (modalQty) modalQty.textContent = selectedQty;
        const confirmBtn = document.getElementById('modal-confirm-action-btn');
        if (confirmBtn) {
            confirmBtn.textContent = actionType === 'buy_now' ? 'Confirm & Buy Now' : 'Confirm & Add to Bag';
        }

        const modal = document.getElementById('options-selection-modal');
        const card = document.getElementById('options-modal-card');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeOptionsModal() {
        const modal = document.getElementById('options-selection-modal');
        const card = document.getElementById('options-modal-card');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('modal-validation-error').classList.add('hidden');
        }, 300);
    }

    function confirmOptionsAndSubmit() {
        if (isVariableProduct) {
            if (!variableSelectionsComplete()) {
                document.getElementById('modal-validation-error').classList.remove('hidden');
                return;
            }

            closeOptionsModal();
            submitVariableAction();
            return;
        }

        closeOptionsModal();
        submitForm();
    }

    function toggleProductWishlist() {
        if (typeof toggleWishlist === 'function') {
            toggleWishlist(<?php echo $product_id; ?>);
            updateWishlistBtnState();
        }
    }

    function updateWishlistBtnState() {
        const btn = document.getElementById('wishlist-toggle-btn');
        const storedWish = JSON.parse(localStorage.getItem('arshman_wishlist')) || [];
        const isWishlisted = storedWish.includes(<?php echo $product_id; ?>);
        if (isWishlisted) {
            btn.innerHTML = `<svg class="w-4 h-4 text-[#C8A46A] fill-[#C8A46A]" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Saved in Wishlist`;
        } else {
            btn.innerHTML = `<svg class="w-4 h-4 text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Add to Wishlist`;
        }
    }

    // Tabs
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(tabId).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.add('border-transparent', 'text-gray-500');
            btn.classList.remove('border-[#C8A46A]', 'text-[#C8A46A]');
        });
        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
            activeBtn.classList.add('border-[#C8A46A]', 'text-[#C8A46A]');
        }
    }

    // Reviews (Simulated DB to match static client feedback list)
    const REVIEWS_DB = [
        { name: "Ananya R.", stars: 5, title: "Magnificent zari work!", text: "Wore this for my brother's wedding and got endless compliments. The silk is heavy and has a beautiful royal drape.", date: "2 weeks ago" },
        { name: "Meera K.", stars: 5, title: "Pure luxury fabric", text: "Truly authentic handcrafted silk. The weave details are incredibly clean and neat. Worth every rupee.", date: "3 weeks ago" },
        { name: "Priya S.", stars: 4, title: "Beautiful drape", text: "The color is absolutely gorgeous, dark onyx black shines elegantly under studio lights. Length was perfect.", date: "1 month ago" },
        { name: "Aditi G.", stars: 5, title: "Exceeded expectations!", text: "Highly recommend ARSHMAN DESIGNS. Shipping was super fast and signature packaging felt extremely premium.", date: "1 month ago" }
    ];

    function initReviews() {
        const carousel = document.getElementById('reviews-carousel');
        if (!carousel) return;
        
        carousel.innerHTML = REVIEWS_DB.map(r => {
            let starsStr = '';
            for (let i = 0; i < 5; i++) {
                starsStr += `<svg class="w-3.5 h-3.5 ${i < r.stars ? 'fill-[#C8A46A] text-[#C8A46A]' : 'text-gray-700'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>`;
            }
            return `
                <div class="snap-center shrink-0 w-80 bg-[#0d0d0d] p-6 rounded border border-white/5 flex flex-col justify-between h-[200px]">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-white uppercase tracking-wider">${r.name}</span>
                            <span class="text-[9px] text-[#C8A46A] uppercase tracking-widest bg-[#C8A46A]/10 px-1.5 py-0.5 rounded border border-[#C8A46A]/20">Verified</span>
                        </div>
                        <div class="flex text-[#C8A46A] gap-1 mb-2">
                            ${starsStr}
                        </div>
                        <h5 class="text-xs text-white font-semibold mb-1 uppercase tracking-wider line-clamp-1">${r.title}</h5>
                        <p class="text-xs text-gray-400 font-light leading-relaxed line-clamp-3">${r.text}</p>
                    </div>
                    <span class="text-[9px] text-gray-600 uppercase tracking-widest text-right mt-3">${r.date}</span>
                </div>
            `;
        }).join('');
    }

    function scrollReviews(dir) {
        const carousel = document.getElementById('reviews-carousel');
        if (carousel) carousel.scrollBy({ left: dir * 300, behavior: 'smooth' });
    }

    function scrollRelated(dir) {
        const carousel = document.getElementById('related-carousel');
        if (carousel) carousel.scrollBy({ left: dir * 240, behavior: 'smooth' });
    }

    // Write a review modal logic
    function openWriteReviewModal() {
        const modal = document.getElementById('write-review-modal');
        const card = document.getElementById('write-review-card');
        modal.classList.remove('hidden');
        setTimeout(() => card.classList.remove('scale-95', 'opacity-0'), 10);
    }

    function closeWriteReviewModal() {
        const modal = document.getElementById('write-review-modal');
        const card = document.getElementById('write-review-card');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function selectReviewStars(count) {
        document.getElementById('selected-stars-count').value = count;
        const btns = document.querySelectorAll('#rating-star-selector button');
        btns.forEach((btn, idx) => {
            if (idx < count) {
                btn.classList.add('text-[#C8A46A]');
                btn.classList.remove('text-gray-600');
            } else {
                btn.classList.remove('text-[#C8A46A]');
                btn.classList.add('text-gray-600');
            }
        });
    }

    function submitProductReview(event) {
        event.preventDefault();
        const stars = document.getElementById('selected-stars-count').value;
        const author = document.getElementById('review-author').value;
        const title = document.getElementById('review-title').value;
        const body = document.getElementById('review-body').value;
        
        if (!stars) {
            alert("Please select a star rating first.");
            return;
        }

        REVIEWS_DB.unshift({
            name: author,
            stars: Number(stars),
            title: title,
            text: body,
            date: "Just Now"
        });

        document.getElementById('write-review-form').reset();
        selectReviewStars(0);
        closeWriteReviewModal();
        initReviews();
        
        alert('Thank you! Your review has been submitted.');
    }

    document.addEventListener('DOMContentLoaded', () => {
        initReviews();
        updateWishlistBtnState();
        initVariationChoiceButtons();

        // Start auto-slide; pause when user hovers the main image or thumbnails
        startGalleryAutoSlide();
        const thumbsEl  = document.getElementById('product-thumbnails');
        const mainImgEl = document.getElementById('product-main-img');
        const mainWrap  = mainImgEl ? mainImgEl.closest('.group') : null;
        [thumbsEl, mainWrap].filter(Boolean).forEach(el => {
            el.addEventListener('mouseenter', stopGalleryAutoSlide);
            el.addEventListener('mouseleave', startGalleryAutoSlide);
        });
    });
</script>

<?php
get_footer();
?>
