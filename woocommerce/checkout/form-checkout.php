<?php
/**
 * WooCommerce Checkout Template Override
 * Matches the design of /frontend/public/checkout.html
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main class="dt-checkout-page max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

    <?php if ( ! WC()->cart->is_empty() ) : ?>

    <div class="dt-checkout-layout flex flex-col lg:flex-row gap-8 lg:gap-12">

        <!-- LEFT: WooCommerce Checkout Form -->
        <div class="dt-checkout-main flex-1 space-y-6">

            <?php woocommerce_checkout_login_form(); ?>

            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
                <?php if ( $checkout->get_checkout_fields() ) : ?>

                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <!-- STEP 1: Billing / Delivery Address -->
                    <div class="dt-checkout-step dt-checkout-step-active border border-[#C8A46A] bg-[#111]" data-checkout-step="1">
                        <div class="dt-checkout-step-head w-full flex items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <span class="dt-checkout-step-number flex items-center justify-center w-8 h-8 rounded-full bg-[#C8A46A] border-[#C8A46A] text-black font-semibold text-sm">1</span>
                                <div>
                                    <p class="dt-checkout-step-kicker"><?php esc_html_e( 'Step 1 of 4', 'dt-ecommerce-theme' ); ?></p>
                                    <h2 class="font-serif text-xl md:text-2xl font-semibold tracking-wide">
                                        <?php esc_html_e( 'Delivery Address', 'dt-ecommerce-theme' ); ?>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="dt-checkout-step-body px-6 pb-6 pt-2">
                            <div id="customer_details" class="space-y-4">
                                <div class="dt-checkout-fields woocommerce-billing-fields grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php
                                    foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) {
                                        // Add our dark style classes
                                        $field['input_class'][] = 'bg-transparent border border-[#333] p-3 text-sm focus:border-[#C8A46A] focus:outline-none transition-colors text-[#F7F4EE] placeholder:text-[#666] w-full';
                                        $field['label_class'][] = 'text-[#a3a3a3] text-xs uppercase tracking-widest block mb-1';
                                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                    }
                                    ?>
                                </div>

                                <!-- Shipping to different address? -->
                                <?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>
                                    <div class="pt-4">
                                        <p class="text-[#a3a3a3] text-xs">
                                            <?php esc_html_e( 'Shipping to your billing address.', 'dt-ecommerce-theme' ); ?>
                                        </p>
                                    </div>
                                <?php elseif ( WC()->cart->needs_shipping() ) : ?>
                                    <div class="dt-ship-different-wrap pt-4 border-t border-[#333]">
                                        <p class="form-row" id="ship-to-different-address">
                                            <label class="dt-ship-different-label woocommerce-form__label flex items-center gap-2 text-[#a3a3a3] text-sm cursor-pointer">
                                                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox" <?php checked( $checkout->get_value( 'ship_to_different_address' ), true ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                                                <span class="dt-ship-checkmark"></span>
                                                <span class="tracking-wide"><?php esc_html_e( 'Ship to a different address?', 'dt-ecommerce-theme' ); ?></span>
                                            </label>
                                        </p>
                                        <div class="dt-checkout-fields dt-shipping-address-panel shipping_address hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <?php
                                            foreach ( $checkout->get_checkout_fields( 'shipping' ) as $key => $field ) {
                                                $field['input_class'][] = 'bg-transparent border border-[#333] p-3 text-sm focus:border-[#C8A46A] focus:outline-none transition-colors text-[#F7F4EE] placeholder:text-[#666] w-full';
                                                $field['label_class'][] = 'text-[#a3a3a3] text-xs uppercase tracking-widest block mb-1';
                                                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                            <div class="dt-step-actions">
                                <button type="button" class="dt-step-next" data-next-step="2">
                                    <?php esc_html_e( 'Continue to Order Overview', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Order Overview -->
                    <div class="dt-checkout-step border border-[#333] bg-[#111]" data-checkout-step="2">
                        <div class="dt-checkout-step-head w-full flex items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <span class="dt-checkout-step-number flex items-center justify-center w-8 h-8 rounded-full border border-[#C8A46A] text-[#C8A46A] font-semibold text-sm">2</span>
                                <div>
                                    <p class="dt-checkout-step-kicker"><?php esc_html_e( 'Step 2 of 4', 'dt-ecommerce-theme' ); ?></p>
                                    <h2 class="font-serif text-xl md:text-2xl font-semibold tracking-wide">
                                        <?php esc_html_e( 'Order Overview', 'dt-ecommerce-theme' ); ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="dt-checkout-step-body px-6 pb-6 pt-2 space-y-4">
                            <div class="dt-order-overview-list">
                                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                                    if ( ! $_product || ! $_product->exists() || 0 === (int) $cart_item['quantity'] ) {
                                        continue;
                                    }
                                    $thumbnail = $_product->get_image( 'thumbnail', array( 'class' => 'dt-order-overview-img' ) );
                                    ?>
                                    <div class="dt-order-overview-item">
                                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="dt-order-overview-media">
                                            <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </a>
                                        <div class="dt-order-overview-info">
                                            <h3><?php echo esc_html( $_product->get_name() ); ?></h3>
                                            <p>
                                                <?php esc_html_e( 'Quantity', 'dt-ecommerce-theme' ); ?>:
                                                <strong><?php echo esc_html( $cart_item['quantity'] ); ?></strong>
                                            </p>
                                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </div>
                                        <div class="dt-order-overview-price">
                                            <?php echo wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="dt-order-overview-totals">
                                <div>
                                    <span><?php esc_html_e( 'Subtotal', 'dt-ecommerce-theme' ); ?></span>
                                    <strong><?php woocommerce_cart_totals_subtotal_html(); ?></strong>
                                </div>
                                <?php if ( WC()->cart->needs_shipping() ) : ?>
                                <div>
                                    <span><?php esc_html_e( 'Shipping', 'dt-ecommerce-theme' ); ?></span>
                                    <strong><?php woocommerce_cart_totals_shipping_html(); ?></strong>
                                </div>
                                <?php endif; ?>
                                <div class="dt-order-overview-grand">
                                    <span><?php esc_html_e( 'Payable Total', 'dt-ecommerce-theme' ); ?></span>
                                    <strong><?php woocommerce_cart_totals_order_total_html(); ?></strong>
                                </div>
                            </div>

                            <div class="dt-step-actions">
                                <button type="button" class="dt-step-back" data-next-step="1">
                                    <?php esc_html_e( 'Back', 'dt-ecommerce-theme' ); ?>
                                </button>
                                <button type="button" class="dt-step-next" data-next-step="3">
                                    <?php esc_html_e( 'Apply Coupon', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Coupon -->
                    <?php if ( wc_coupons_enabled() ) : ?>
                    <div class="dt-checkout-step border border-[#333] bg-[#111]" data-checkout-step="3">
                        <div class="dt-checkout-step-head w-full flex items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <span class="dt-checkout-step-number flex items-center justify-center w-8 h-8 rounded-full border border-[#666] text-[#666] font-semibold text-sm">3</span>
                                <div>
                                    <p class="dt-checkout-step-kicker"><?php esc_html_e( 'Step 3 of 4', 'dt-ecommerce-theme' ); ?></p>
                                    <h2 class="font-serif text-xl md:text-2xl font-semibold tracking-wide">
                                        <?php esc_html_e( 'Coupon & Offers', 'dt-ecommerce-theme' ); ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="dt-checkout-step-body px-6 pb-6 pt-2">
                            <div class="dt-checkout-coupon flex gap-2">
                                <input type="text" name="coupon_code" class="flex-1 bg-transparent border border-[#333] p-3 text-sm focus:border-[#C8A46A] focus:outline-none transition-colors text-[#F7F4EE] placeholder:text-[#666] uppercase" placeholder="<?php esc_attr_e( 'Enter Coupon Code', 'dt-ecommerce-theme' ); ?>" id="coupon_code" />
                                <button type="submit" class="px-6 bg-[#333] text-[#F7F4EE] hover:bg-[#444] transition-colors text-sm uppercase tracking-wider font-semibold" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'dt-ecommerce-theme' ); ?>">
                                    <?php esc_html_e( 'Apply', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                            <div class="dt-step-actions">
                                <button type="button" class="dt-step-back" data-next-step="2">
                                    <?php esc_html_e( 'Back', 'dt-ecommerce-theme' ); ?>
                                </button>
                                <button type="button" class="dt-step-next" data-next-step="4">
                                    <?php esc_html_e( 'Continue to Payment', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- STEP 4: Payment -->
                    <div class="dt-checkout-step border border-[#333] bg-[#111]" data-checkout-step="4">
                        <div class="dt-checkout-step-head w-full flex items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <span class="dt-checkout-step-number flex items-center justify-center w-8 h-8 rounded-full border border-[#666] text-[#666] font-semibold text-sm">4</span>
                                <div>
                                    <p class="dt-checkout-step-kicker"><?php esc_html_e( 'Step 4 of 4', 'dt-ecommerce-theme' ); ?></p>
                                    <h2 class="font-serif text-xl md:text-2xl font-semibold tracking-wide">
                                        <?php esc_html_e( 'Payment', 'dt-ecommerce-theme' ); ?>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="dt-checkout-step-body px-6 pb-6 pt-2">
                            <!-- Order notes -->
                            <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                                <?php
                                $field['input_class'][] = 'bg-transparent border border-[#333] p-3 text-sm focus:border-[#C8A46A] focus:outline-none transition-colors text-[#F7F4EE] placeholder:text-[#666] w-full';
                                $field['label_class'][] = 'text-[#a3a3a3] text-xs uppercase tracking-widest block mb-1';
                                woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                ?>
                            <?php endforeach; ?>

                            <!-- WooCommerce Payment Gateways -->
                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                            <div class="flex items-center gap-2 mt-4 text-xs text-[#666] border-t border-[#333] pt-4">
                                <i data-lucide="shield-check" class="w-4 h-4 text-[#C8A46A]"></i>
                                <?php esc_html_e( '100% Encrypted Safe Payments', 'dt-ecommerce-theme' ); ?>
                            </div>

                            <div class="dt-step-actions">
                                <button type="button" class="dt-step-back" data-next-step="<?php echo wc_coupons_enabled() ? '3' : '2'; ?>">
                                    <?php esc_html_e( 'Back', 'dt-ecommerce-theme' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </form>

        </div>

        <!-- RIGHT: Order Summary -->
        <aside class="dt-checkout-summary w-full lg:w-96 shrink-0 bg-[#0c0c0c] border border-[#C8A46A]/20 p-6 md:p-8 flex flex-col h-fit">
            <h3 class="font-serif text-2xl text-[#C8A46A] tracking-wider uppercase mb-6 pb-3 border-b border-[#C8A46A]/10">
                <?php esc_html_e( 'Order Summary', 'dt-ecommerce-theme' ); ?>
            </h3>

            <!-- Cart Items -->
            <div class="dt-checkout-summary-items max-h-64 overflow-y-auto space-y-4 mb-6 border-b border-[#333] pb-6">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] === 0 ) continue;
                    $thumbnail = $_product->get_image( 'thumbnail', array( 'class' => 'w-12 h-16 object-cover border border-[#C8A46A]/20 shrink-0' ) );
                    ?>
                    <div class="flex gap-4">
                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                            <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </a>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-semibold text-white truncate">
                                <?php echo esc_html( $_product->get_name() ); ?>
                            </h4>
                            <p class="text-[10px] text-gray-500 mt-0.5">
                                <?php esc_html_e( 'Qty:', 'dt-ecommerce-theme' ); ?> <?php echo esc_html( $cart_item['quantity'] ); ?>
                            </p>
                        </div>
                        <span class="text-xs font-semibold text-white whitespace-nowrap">
                            <?php echo wp_kses_post( WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ) ); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Totals -->
            <div class="space-y-3.5 text-sm font-light text-gray-300">
                <div class="flex justify-between">
                    <span><?php esc_html_e( 'Subtotal', 'dt-ecommerce-theme' ); ?></span>
                    <span class="text-white font-medium"><?php woocommerce_cart_totals_subtotal_html(); ?></span>
                </div>

                <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <div class="flex justify-between">
                    <span><?php echo esc_html( $fee->name ); ?></span>
                    <span class="text-white font-medium"><?php echo wp_kses_post( wc_price( $fee->total ) ); ?></span>
                </div>
                <?php endforeach; ?>

                <?php if ( WC()->cart->needs_shipping() ) : ?>
                <div class="flex justify-between">
                    <span><?php esc_html_e( 'Shipping', 'dt-ecommerce-theme' ); ?></span>
                    <span class="text-[#C8A46A] font-medium"><?php woocommerce_cart_totals_shipping_html(); ?></span>
                </div>
                <?php endif; ?>

                <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="flex justify-between text-green-400">
                    <span><?php esc_html_e( 'Coupon:', 'dt-ecommerce-theme' ); ?> <?php echo esc_html( strtoupper( $code ) ); ?></span>
                    <span>-<?php echo wp_kses_post( wc_price( WC()->cart->get_coupon_discount_amount( $code ) ) ); ?></span>
                </div>
                <?php endforeach; ?>

                <?php if ( WC()->cart->get_taxes_total() ) : ?>
                <div class="flex justify-between">
                    <span><?php esc_html_e( 'Tax', 'dt-ecommerce-theme' ); ?></span>
                    <span class="text-white font-medium"><?php echo wp_kses_post( wc_price( WC()->cart->get_taxes_total() ) ); ?></span>
                </div>
                <?php endif; ?>

                <div class="border-t border-[#C8A46A]/20 pt-4 flex justify-between text-base font-semibold text-white">
                    <span class="uppercase tracking-widest"><?php esc_html_e( 'Grand Total', 'dt-ecommerce-theme' ); ?></span>
                    <span class="text-lg text-[#C8A46A]"><?php woocommerce_cart_totals_order_total_html(); ?></span>
                </div>
            </div>
        </aside>
    </div>

    <?php else : ?>

        <!-- Empty Cart -->
        <div class="text-center py-24">
            <div class="text-[#C8A46A]/20 mb-6">
                <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="font-serif text-3xl text-white mb-3"><?php esc_html_e( 'Your bag is empty', 'dt-ecommerce-theme' ); ?></h2>
            <p class="text-[#a3a3a3] text-sm mb-8"><?php esc_html_e( 'Add some products to your bag to continue.', 'dt-ecommerce-theme' ); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-gold-shimmer px-10 py-4 uppercase tracking-widest text-sm font-semibold inline-block">
                <?php esc_html_e( 'Continue Shopping', 'dt-ecommerce-theme' ); ?>
            </a>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
