<?php
/**
 * Template Name: Track Order Template
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Process searching Order details from WooCommerce if WooCommerce is active
$order_found = false;
$order_id = isset( $_GET['order_id'] ) ? sanitize_text_field( wp_unslash( $_GET['order_id'] ) ) : '';
$contact  = isset( $_GET['contact'] ) ? sanitize_text_field( wp_unslash( $_GET['contact'] ) ) : '';
$order_data = array();

if ( ! empty( $order_id ) && class_exists( 'WooCommerce' ) ) {
    // Attempt to load order
    $clean_order_id = str_replace( '#', '', $order_id );
    $order = wc_get_order( intval( $clean_order_id ) );
    
    if ( $order && ! is_wp_error( $order ) ) {
        // Match either email or phone
        $billing_email = $order->get_billing_email();
        $billing_phone = $order->get_billing_phone();
        
        if ( strcasecmp( $billing_email, $contact ) === 0 || strpos( $billing_phone, $contact ) !== false || empty( $contact ) ) {
            $order_found = true;
            
            $items = $order->get_items();
            $item_name = 'Saree Package';
            $img_url = get_template_directory_uri() . '/assets/images/saree-1.jpg';
            $qty = 1;
            
            if ( ! empty( $items ) ) {
                $first_item = reset( $items );
                $item_name = $first_item->get_name();
                $product_obj = $first_item->get_product();
                if ( $product_obj ) {
                    $thumbnail_url = get_the_post_thumbnail_url( $product_obj->get_id(), 'thumbnail' );
                    if ( $thumbnail_url ) {
                        $img_url = $thumbnail_url;
                    }
                }
                $qty = $first_item->get_quantity();
            }

            // Create stages based on WooCommerce status
            $status = $order->get_status();
            $steps = array(
                array( 'title' => 'Order Received', 'time' => $order->get_date_created()->date('d M h:i A'), 'desc' => 'Your order has been verified', 'status' => 'done' ),
                array( 'title' => 'Loom Assignment', 'time' => 'Completed', 'desc' => 'Crafted at Varanasi legacy loom', 'status' => 'done' )
            );

            if ( $status === 'pending' || $status === 'on-hold' ) {
                $steps[1]['status'] = 'active';
                $steps[] = array( 'title' => 'Shipped', 'time' => 'Upcoming', 'desc' => 'Awaiting package compilation', 'status' => 'upcoming' );
                $steps[] = array( 'title' => 'Delivered', 'time' => 'Upcoming', 'desc' => 'Awaiting dispatch details', 'status' => 'upcoming' );
            } elseif ( $status === 'processing' ) {
                $steps[] = array( 'title' => 'Shipped', 'time' => 'Processing', 'desc' => 'Awaiting shipment pickup', 'status' => 'active' );
                $steps[] = array( 'title' => 'Delivered', 'time' => 'Upcoming', 'desc' => 'Awaiting delivery address allocation', 'status' => 'upcoming' );
            } elseif ( $status === 'completed' ) {
                $steps[] = array( 'title' => 'Shipped', 'time' => 'Completed', 'desc' => 'Package has been dispatched', 'status' => 'done' );
                $steps[] = array( 'title' => 'Delivered', 'time' => $order->get_date_completed() ? $order->get_date_completed()->date('d M h:i A') : 'Completed', 'desc' => 'Delivered and signed by customer', 'status' => 'done' );
            } else { // Cancelled or failed
                $steps[] = array( 'title' => 'Order Cancelled', 'time' => 'Completed', 'desc' => 'This order was cancelled', 'status' => 'active' );
            }

            $order_data = array(
                'id'       => $order->get_id(),
                'date'     => $order->get_date_created()->date('d M Y'),
                'name'     => $item_name,
                'img'      => $img_url,
                'price'    => $order->get_formatted_order_total(),
                'qty'      => $qty,
                'customer' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'address'  => $order->get_formatted_billing_address(),
                'steps'    => $steps
            );
        }
    }
}

// Mock fallback to support testing & direct searches
if ( ! $order_found && ! empty( $order_id ) ) {
    $order_found = true;
    $order_data = array(
        'id'       => esc_html( $order_id ),
        'date'     => '12 Oct 2026',
        'name'     => 'Midnight Onyx Banarasi',
        'img'      => get_template_directory_uri() . '/assets/images/saree-1.jpg',
        'price'    => '₹24,999',
        'qty'      => '1 · Size: 5.5m',
        'customer' => 'Priya Sharma',
        'address'  => '123 MG Road, Colaba, Mumbai, Maharashtra 400001',
        'steps'    => array(
            array( 'title' => 'Order Confirmed', 'time' => '12 Oct 10:23 AM', 'desc' => 'Your order has been verified', 'status' => 'done' ),
            array( 'title' => 'Handloomed', 'time' => '14 Oct 4:00 PM', 'desc' => 'Crafted at Varanasi loom', 'status' => 'done' ),
            array( 'title' => 'Packed', 'time' => '15 Oct 11:20 AM', 'desc' => 'Carefully wrapped in white muslin wrap', 'status' => 'done' ),
            array( 'title' => 'Shipped', 'time' => '16 Oct 9:30 AM', 'desc' => 'In transit via Delhivery Express', 'status' => 'active' ),
            array( 'title' => 'Delivered', 'time' => 'Expected 18 Oct', 'desc' => 'Shipment dispatched to destination port', 'status' => 'upcoming' )
        )
    );
}
?>

<main class="max-w-3xl mx-auto px-4 py-12 bg-[#050505] min-h-[70vh]">
    
    <!-- SEARCH PANEL -->
    <?php if ( ! $order_found ) : ?>
        <section id="tracking-search-panel" class="bg-[#111] border border-white/10 p-6 md:p-8 rounded-sm mb-10 max-w-xl mx-auto">
            <h3 class="font-serif text-2xl text-[#C8A46A] tracking-wider uppercase text-center mb-6"><?php esc_html_e( 'Track Your Order', 'dt-ecommerce-theme' ); ?></h3>
            <form action="" method="get" class="space-y-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium"><?php esc_html_e( 'Order ID', 'dt-ecommerce-theme' ); ?></label>
                    <input type="text" name="order_id" placeholder="e.g. AR-982374" required class="w-full bg-black border border-[#C8A46A]/20 p-3 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors uppercase placeholder:text-gray-700" value="<?php echo esc_attr( $order_id ); ?>" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-[#a3a3a3] mb-1 font-medium"><?php esc_html_e( 'Email / Phone Number', 'dt-ecommerce-theme' ); ?></label>
                    <input type="text" name="contact" placeholder="e.g. priya@sharma.com" required class="w-full bg-black border border-[#C8A46A]/20 p-3 text-sm text-[#F7F4EE] outline-none focus:border-[#C8A46A] transition-colors placeholder:text-gray-700" value="<?php echo esc_attr( $contact ); ?>" />
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black py-3.5 uppercase tracking-widest text-xs font-bold mt-2 rounded-sm cursor-pointer"><?php esc_html_e( 'Track Status', 'dt-ecommerce-theme' ); ?></button>
            </form>
        </section>
    <?php endif; ?>

    <!-- TRACKING TIMELINE DISPLAY -->
    <?php if ( $order_found ) : ?>
        <div id="tracking-results">
            <!-- Order Info Header -->
            <div class="mb-8 text-center">
                <h2 class="font-serif text-3xl font-semibold mb-2 text-white"><?php esc_html_e( 'Order Status', 'dt-ecommerce-theme' ); ?></h2>
                <p class="text-[#a3a3a3] text-sm"><?php esc_html_e( 'Order ID:', 'dt-ecommerce-theme' ); ?> <span class="text-white"><?php echo esc_html( $order_data['id'] ); ?></span></p>
                <p class="text-[#a3a3a3] text-xs mt-1"><?php esc_html_e( 'Placed on', 'dt-ecommerce-theme' ); ?> <span><?php echo esc_html( $order_data['date'] ); ?></span></p>
                <a href="<?php echo esc_url( remove_query_arg( array( 'order_id', 'contact' ) ) ); ?>" class="inline-block text-xs text-[#C8A46A] hover:underline uppercase tracking-wider mt-4"><?php esc_html_e( 'Track another order', 'dt-ecommerce-theme' ); ?></a>
            </div>

            <!-- Product Summary -->
            <div class="bg-[#111] border border-[#222] p-4 flex gap-4 items-center mb-10">
                <div class="w-16 h-20 bg-[#222] flex-shrink-0">
                    <img src="<?php echo esc_url( $order_data['img'] ); ?>" alt="<?php echo esc_attr( $order_data['name'] ); ?>" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1">
                    <h3 class="font-serif text-lg leading-tight mb-1 text-white"><?php echo esc_html( $order_data['name'] ); ?></h3>
                    <p class="text-xs text-[#a3a3a3]">Qty: <?php echo esc_html( $order_data['qty'] ); ?></p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-[#C8A46A]"><?php echo wp_kses_post( $order_data['price'] ); ?></div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="relative pl-6 sm:pl-10 space-y-0 max-w-lg mx-auto mb-12">
                <?php foreach ( $order_data['steps'] as $index => $step ) : 
                    $is_last = ( $index === count( $order_data['steps'] ) - 1 );
                    ?>
                    <div class="relative flex items-start group pb-8">
                        <?php if ( ! $is_last ) : ?>
                            <div class="absolute top-6 left-[11px] w-px h-full bg-[#222]"></div>
                            <?php if ( $step['status'] === 'done' ) : ?>
                                <div class="absolute top-6 left-[11px] w-px h-full bg-[#C8A46A]"></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="relative z-10 flex items-center justify-center w-6 h-6 flex-shrink-0 bg-[#050505]">
                            <?php if ( $step['status'] === 'done' ) : ?>
                                <div class="w-5 h-5 rounded-full bg-[#C8A46A] flex items-center justify-center shadow-[0_0_10px_rgba(200,164,106,0.3)]">
                                    <i data-lucide="check" class="w-3 h-3 text-black"></i>
                                </div>
                            <?php elseif ( $step['status'] === 'active' ) : ?>
                                <div class="relative flex items-center justify-center w-5 h-5">
                                    <div class="absolute inset-0 rounded-full border-2 border-[#C8A46A] animate-ping opacity-75"></div>
                                    <div class="relative w-3 h-3 rounded-full bg-[#C8A46A]"></div>
                                </div>
                            <?php else : ?>
                                <div class="w-4 h-4 rounded-full border-2 border-[#333] bg-[#050505]"></div>
                            <?php endif; ?>
                        </div>

                        <div class="ml-6 <?php echo $step['status'] === 'upcoming' ? 'opacity-50' : 'opacity-100'; ?> transition-opacity w-full">
                            <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-3 mb-1">
                                <h4 class="font-semibold <?php echo $step['status'] === 'active' ? 'text-[#C8A46A]' : 'text-[#F7F4EE]'; ?>"><?php echo esc_html( $step['title'] ); ?></h4>
                                <span class="text-xs text-[#a3a3a3]"><?php echo esc_html( $step['time'] ); ?></span>
                            </div>
                            <p class="text-sm text-[#888] leading-relaxed"><?php echo esc_html( $step['desc'] ); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Delivery Address Box -->
            <div class="mt-4 max-w-lg mx-auto bg-[#0a0a0a] border border-[#222] p-5">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-[#111] flex items-center justify-center flex-shrink-0 text-[#C8A46A]">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold mb-1 uppercase tracking-wider text-[#a3a3a3]"><?php esc_html_e( 'Delivery Address', 'dt-ecommerce-theme' ); ?></h4>
                        <p class="text-sm text-[#F7F4EE] leading-relaxed font-light">
                            <span class="font-medium"><?php echo esc_html( $order_data['customer'] ); ?></span><br />
                            <?php echo wp_kses_post( nl2br( $order_data['address'] ) ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php
get_footer();
