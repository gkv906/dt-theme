<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive — 100% matches shop.html
 *
 * @package DT_Ecommerce_Theme
 * @version 8.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

$current_cat_slug = is_product_category() ? get_queried_object()->slug : '';
$current_max_price = isset( $_GET['max_price'] ) ? intval( wp_unslash( $_GET['max_price'] ) ) : 35000;
$current_orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';

$clear_filters_url = get_permalink( wc_get_page_id( 'shop' ) );
?>

<!-- Promo Ribbon -->
<div class="px-4 md:px-8 pt-5 md:pt-6 max-w-[1800px] mx-auto mt-6 bg-[#050505]">
    <div class="relative rounded-sm overflow-hidden border border-[#C8A46A]/30 bg-gradient-to-r from-[#1a1408] via-[#0f0d08] to-[#1a1408]">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_20%_50%,#C8A46A_0%,transparent_60%)]"></div>
        <div class="relative flex items-center justify-center gap-3 py-3.5 px-4 text-center">
            <i data-lucide="sparkles" class="w-4 h-4 text-[#C8A46A]"></i>
            <p class="text-xs md:text-sm tracking-[0.1em] md:tracking-[0.15em] uppercase text-[#F7F4EE]">
                <span class="text-[#C8A46A] font-semibold"><?php esc_html_e( 'Festive Sale', 'dt-ecommerce-theme' ); ?></span> — Up to 40% Off · Code <span class="text-[#C8A46A] font-semibold">FESTIVE40</span>
            </p>
        </div>
    </div>
</div>

<!-- MAIN CONTENT AREA -->
<div class="flex relative px-4 md:px-8 py-8 pb-40 md:pb-8 max-w-[1800px] mx-auto bg-black min-h-[80vh]">
    
    <!-- FILTER DRAWER (Sidebar on large screens) -->
    <aside class="hidden lg:block w-64 xl:w-72 shrink-0 pr-8 sticky top-28 h-[calc(100vh-8rem)] overflow-y-auto no-scrollbar">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-serif text-2xl text-[#C8A46A] tracking-wider uppercase"><?php esc_html_e( 'Filters', 'dt-ecommerce-theme' ); ?></h3>
            <a href="<?php echo esc_url( $clear_filters_url ); ?>" class="text-[10px] text-[#F7F4EE]/50 uppercase tracking-widest border-b border-transparent hover:border-white transition-all"><?php esc_html_e( 'Clear All', 'dt-ecommerce-theme' ); ?></a>
        </div>

        <div class="space-y-8">
            <!-- Fabric Filter -->
            <div class="border-b border-[#C8A46A]/10 pb-6">
                <h4 class="text-xs font-semibold mb-4 text-[#F7F4EE] uppercase tracking-[0.15em]"><?php esc_html_e( 'Fabric Category', 'dt-ecommerce-theme' ); ?></h4>
                <div class="space-y-3">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => false,
                    ) );
                    if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                        foreach ( $categories as $cat ) {
                            $is_active = ( $current_cat_slug === $cat->slug );
                            $link = $is_active ? $clear_filters_url : get_term_link( $cat );
                            // Preserve current parameters
                            if ( isset( $_GET['max_price'] ) ) {
                                $link = add_query_arg( 'max_price', $current_max_price, $link );
                            }
                            if ( isset( $_GET['orderby'] ) ) {
                                $link = add_query_arg( 'orderby', $current_orderby, $link );
                            }
                            ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" <?php checked( $is_active ); ?> onchange="window.location.href='<?php echo esc_url( $link ); ?>'" class="rounded border-[#C8A46A]/40 bg-black text-[#C8A46A] focus:ring-0 w-4 h-4" />
                                <span class="text-sm text-[#F7F4EE]/70 group-hover:text-[#C8A46A] transition-colors"><?php echo esc_html( $cat->name ); ?></span>
                            </label>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Price Range Filter -->
            <div class="border-b border-[#C8A46A]/10 pb-6">
                <h4 class="text-xs font-semibold mb-4 text-[#F7F4EE] uppercase tracking-[0.15em]"><?php esc_html_e( 'Max Price', 'dt-ecommerce-theme' ); ?></h4>
                <input type="range" id="price-range" min="9000" max="35000" step="1000" value="<?php echo esc_attr( $current_max_price ); ?>" class="w-full accent-[#C8A46A] bg-[#1a1a1a] h-1 rounded-lg cursor-pointer" />
                <div class="flex justify-between text-xs text-[#F7F4EE]/50 mt-2">
                    <span>₹9,000</span>
                    <span id="price-range-val" class="text-[#C8A46A] font-semibold">₹<?php echo number_format( $current_max_price ); ?></span>
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT COLUMN: Grid & Sort -->
    <div class="flex-1 min-w-0">
        <?php
        $shop_cols = dt_get_theme_option( 'shop_columns', '4' );
        $grid_class = 'grid grid-cols-2';
        if ( '3' === $shop_cols ) {
            $grid_class .= ' md:grid-cols-3';
        } elseif ( '2' === $shop_cols ) {
            $grid_class .= ' md:grid-cols-2';
        } else {
            $grid_class .= ' md:grid-cols-3 xl:grid-cols-4';
        }
        ?>
        <!-- Sort Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 md:mb-8 gap-4 border-b border-[#C8A46A]/10 pb-4">
            <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                <span class="text-xs text-[#F7F4EE]/50 tracking-wide">
                    <?php
                    $total = wc_get_loop_prop( 'total' );
                    printf( esc_html( _n( '%d Item found', '%d Items found', $total, 'dt-ecommerce-theme' ) ), $total );
                    ?>
                </span>
            </div>

            <!-- Sort Select (hidden on mobile — handled by bottom Filter/Sort bar) -->
            <div class="hidden sm:flex items-center gap-3 w-full sm:w-auto justify-end">
                <span class="text-xs text-[#F7F4EE]/50 uppercase tracking-widest hidden sm:inline"><?php esc_html_e( 'Sort By', 'dt-ecommerce-theme' ); ?></span>
                <form class="woocommerce-ordering" method="get">
                    <select name="orderby" class="bg-[#111] border border-[#C8A46A]/30 text-[#F7F4EE] text-xs uppercase tracking-wider py-2 px-3 focus:outline-none focus:border-[#C8A46A]" onchange="this.form.submit()">
                        <?php
                        $catalog_orderby_options = apply_filters(
                            'woocommerce_catalog_orderby',
                            array(
                                'menu_order' => __( 'Default sorting', 'woocommerce' ),
                                'popularity' => __( 'Sort by popularity', 'woocommerce' ),
                                'rating'     => __( 'Sort by average rating', 'woocommerce' ),
                                'date'       => __( 'Sort by latest', 'woocommerce' ),
                                'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
                                'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
                            )
                        );

                        foreach ( $catalog_orderby_options as $id => $name ) {
                            echo '<option value="' . esc_attr( $id ) . '" ' . selected( $current_orderby, $id, false ) . '>' . esc_html( $name ) . '</option>';
                        }
                        ?>
                    </select>
                    <?php if ( isset( $_GET['max_price'] ) ) : ?>
                        <input type="hidden" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" />
                    <?php endif; ?>
                    <input type="hidden" name="paged" value="1" />
                    <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page', 'max_price' ) ); ?>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="shop-products-grid" class="<?php echo esc_attr( $grid_class ); ?> gap-x-4 gap-y-12 md:gap-x-8">
            <?php
            if ( woocommerce_product_loop() ) {
                while ( have_posts() ) {
                    the_post();
                    get_template_part( 'template-parts/product-card' );
                }
            } else {
                echo '<p class="text-center text-[#a3a3a3] col-span-full">' . esc_html__( 'No products found matching your filters.', 'dt-ecommerce-theme' ) . '</p>';
            }
            ?>
        </div>
        
        <div class="mt-12 text-center">
            <?php the_posts_pagination( array(
                'prev_text' => '<i data-lucide="arrow-left" class="w-4 h-4"></i>',
                'next_text' => '<i data-lucide="arrow-right" class="w-4 h-4"></i>',
                'class'     => 'dt-pagination'
            ) ); ?>
        </div>
    </div>
</div>

<!-- Mobile Bottom Bar for Shop (Filter & Sort) -->
<div class="fixed left-0 w-full bg-[#0a0a0a]/95 backdrop-blur-lg border-t border-[#C8A46A]/30 md:hidden z-50" style="bottom: env(safe-area-inset-bottom, 0px);">
    <div class="grid grid-cols-2 divide-x divide-[#C8A46A]/20 py-3.5 text-center">
        <button onclick="toggleMobileFilterDrawer(true)" class="flex items-center justify-center gap-2 text-gray-300 hover:text-[#C8A46A] active:text-[#C8A46A] transition-colors py-1">
            <i data-lucide="sliders-horizontal" class="w-4 h-4 text-[#C8A46A]"></i>
            <span class="text-xs uppercase tracking-widest font-semibold"><?php esc_html_e( 'Filter', 'dt-ecommerce-theme' ); ?></span>
        </button>
        <button onclick="toggleMobileSortModal(true)" class="flex items-center justify-center gap-2 text-gray-300 hover:text-[#C8A46A] active:text-[#C8A46A] transition-colors py-1">
            <i data-lucide="arrow-up-down" class="w-4 h-4 text-[#C8A46A]"></i>
            <span class="text-xs uppercase tracking-widest font-semibold"><?php esc_html_e( 'Sort By', 'dt-ecommerce-theme' ); ?></span>
        </button>
    </div>
</div>

<!-- Mobile Filter Drawer -->
<div id="mob-filter-drawer" class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0" onclick="toggleMobileFilterDrawer(false)"></div>
    <!-- Drawer panel -->
    <div id="mob-filter-panel" class="absolute left-0 bottom-0 w-full h-[80vh] bg-[#0a0a0a] border-t border-[#C8A46A]/30 rounded-t-2xl shadow-2xl flex flex-col transform translate-y-full transition-transform duration-300 ease-in-out">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40 rounded-t-2xl">
            <h3 class="font-serif text-xl text-[#C8A46A] tracking-wider uppercase"><?php esc_html_e( 'Filters', 'dt-ecommerce-theme' ); ?></h3>
            <button onclick="toggleMobileFilterDrawer(false)" title="Close" aria-label="Close" class="text-[#a3a3a3] hover:text-[#C8A46A] transition-colors p-1">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <!-- Body -->
        <div class="flex-1 overflow-y-auto p-6 space-y-8 text-left">
            <!-- Fabric Filter -->
            <div class="border-b border-[#C8A46A]/10 pb-6">
                <h4 class="text-xs font-semibold mb-4 text-[#F7F4EE] uppercase tracking-[0.15em]"><?php esc_html_e( 'Fabric Category', 'dt-ecommerce-theme' ); ?></h4>
                <div class="space-y-4">
                    <?php
                    if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
                        foreach ( $categories as $cat ) {
                            $is_active = ( $current_cat_slug === $cat->slug );
                            $link = $is_active ? $clear_filters_url : get_term_link( $cat );
                            if ( isset( $_GET['max_price'] ) ) {
                                $link = add_query_arg( 'max_price', $current_max_price, $link );
                            }
                            if ( isset( $_GET['orderby'] ) ) {
                                $link = add_query_arg( 'orderby', $current_orderby, $link );
                            }
                            ?>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" <?php checked( $is_active ); ?> onchange="window.location.href='<?php echo esc_url( $link ); ?>'" class="rounded border-[#C8A46A]/40 bg-black text-[#C8A46A] focus:ring-0 w-4 h-4" />
                                <span class="text-sm text-[#F7F4EE]/70 group-hover:text-[#C8A46A]"><?php echo esc_html( $cat->name ); ?></span>
                            </label>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Price Range Filter -->
            <div class="border-b border-[#C8A46A]/10 pb-6">
                <h4 class="text-xs font-semibold mb-4 text-[#F7F4EE] uppercase tracking-[0.15em]"><?php esc_html_e( 'Max Price', 'dt-ecommerce-theme' ); ?></h4>
                <input type="range" id="mob-price-range" min="9000" max="35000" step="1000" value="<?php echo esc_attr( $current_max_price ); ?>" class="w-full accent-[#C8A46A] bg-[#1a1a1a] h-1 rounded-lg cursor-pointer" />
                <div class="flex justify-between text-xs text-[#F7F4EE]/50 mt-2">
                    <span>₹9,000</span>
                    <span id="mob-price-range-val" class="text-[#C8A46A] font-semibold">₹<?php echo number_format( $current_max_price ); ?></span>
                </div>
            </div>
        </div>
        <!-- Footer actions -->
        <div class="p-5 border-t border-[#C8A46A]/20 bg-black/40 grid grid-cols-2 gap-4">
            <button onclick="window.location.href='<?php echo esc_url( $clear_filters_url ); ?>'" class="border border-[#C8A46A]/40 text-[#C8A46A] hover:bg-[#C8A46A]/10 py-3 uppercase tracking-widest text-xs font-semibold rounded-sm transition-all"><?php esc_html_e( 'Clear All', 'dt-ecommerce-theme' ); ?></button>
            <button onclick="applyMobilePriceFilter()" class="btn-gold-shimmer py-3 uppercase tracking-widest text-xs font-semibold text-center rounded-sm"><?php esc_html_e( 'Apply', 'dt-ecommerce-theme' ); ?></button>
        </div>
    </div>
</div>

<!-- Mobile Sort Modal (Bottom Sheet) -->
<div id="mob-sort-modal" class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0" onclick="toggleMobileSortModal(false)"></div>
    <!-- Bottom sheet panel -->
    <div id="mob-sort-panel" class="absolute left-0 bottom-0 w-full bg-[#0a0a0a] border-t border-[#C8A46A]/30 rounded-t-2xl shadow-2xl flex flex-col transform translate-y-full transition-transform duration-300 ease-in-out">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-[#C8A46A]/20 bg-black/40 rounded-t-2xl">
            <h3 class="font-serif text-lg text-[#C8A46A] tracking-wider uppercase"><?php esc_html_e( 'Sort By', 'dt-ecommerce-theme' ); ?></h3>
            <button onclick="toggleMobileSortModal(false)" title="Close" aria-label="Close" class="text-[#a3a3a3] hover:text-[#C8A46A] transition-colors p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <!-- Options list -->
        <div class="p-4 space-y-1 text-left">
            <?php
            $mobile_sorts = array(
                'menu_order' => __( 'Default sorting', 'woocommerce' ),
                'popularity' => __( 'Sort by popularity', 'woocommerce' ),
                'rating'     => __( 'Sort by average rating', 'woocommerce' ),
                'date'       => __( 'Sort by latest', 'woocommerce' ),
                'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
                'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
            );

            foreach ( $mobile_sorts as $key => $name ) :
                $is_selected = ( $current_orderby === $key );
                $sort_url = add_query_arg( 'orderby', $key );
                ?>
                <button onclick="window.location.href='<?php echo esc_url( $sort_url ); ?>'" class="mob-sort-option w-full py-3.5 px-4 flex items-center justify-between text-sm <?php echo $is_selected ? 'text-white bg-[#C8A46A]/10 border-[#C8A46A]/30' : 'text-[#F7F4EE]/80 border-transparent'; ?> hover:bg-[#C8A46A]/10 hover:text-white transition-all font-medium rounded-sm border">
                    <span><?php echo esc_html( $name ); ?></span>
                    <?php if ( $is_selected ) : ?>
                        <i data-lucide="check" class="w-4 h-4 text-[#C8A46A]"></i>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    // Toggle Mobile Filter Drawer
    function toggleMobileFilterDrawer(open) {
        const drawer = document.getElementById('mob-filter-drawer');
        const panel = document.getElementById('mob-filter-panel');
        if (!drawer || !panel) return;
        if (open) {
            drawer.classList.remove('hidden');
            setTimeout(() => panel.classList.remove('translate-y-full'), 10);
        } else {
            panel.classList.add('translate-y-full');
            setTimeout(() => drawer.classList.add('hidden'), 300);
        }
    }

    // Toggle Mobile Sort modal
    function toggleMobileSortModal(open) {
        const modal = document.getElementById('mob-sort-modal');
        const panel = document.getElementById('mob-sort-panel');
        if (!modal || !panel) return;
        if (open) {
            modal.classList.remove('hidden');
            setTimeout(() => panel.classList.remove('translate-y-full'), 10);
        } else {
            panel.classList.add('translate-y-full');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    }

    function applyMobilePriceFilter() {
        const val = document.getElementById('mob-price-range').value;
        const url = new URL(window.location.href);
        url.searchParams.set('max_price', val);
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Sync sliders
        const desktopSlider = document.getElementById('price-range');
        const desktopLabel = document.getElementById('price-range-val');
        const mobileSlider = document.getElementById('mob-price-range');
        const mobileLabel = document.getElementById('mob-price-range-val');

        if (desktopSlider && desktopLabel) {
            desktopSlider.addEventListener('input', (e) => {
                desktopLabel.textContent = '₹' + Number(e.target.value).toLocaleString('en-IN');
            });
            desktopSlider.addEventListener('change', (e) => {
                const url = new URL(window.location.href);
                url.searchParams.set('max_price', e.target.value);
                window.location.href = url.toString();
            });
        }

        if (mobileSlider && mobileLabel) {
            mobileSlider.addEventListener('input', (e) => {
                mobileLabel.textContent = '₹' + Number(e.target.value).toLocaleString('en-IN');
            });
        }
    });
</script>

<?php
get_footer();
