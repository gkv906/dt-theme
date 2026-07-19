<?php
/**
 * Cart Page Template — DT Ecommerce Theme (Premium Dark Luxury)
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$cart_count  = WC()->cart->get_cart_contents_count();
$shop_url    = wc_get_page_permalink( 'shop' );
$checkout_url = wc_get_checkout_url();
?>

<!-- ===== CART PAGE ===== -->
<div class="dt-cart-page">

    <!-- Page Header -->
    <div class="dt-cart-header">
        <div class="dt-cart-header-inner">
            <div class="dt-cart-breadcrumb">
                <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
                <span>/</span>
                <a href="<?php echo esc_url( $shop_url ); ?>">Shop</a>
                <span>/</span>
                <span>Cart</span>
            </div>
            <div class="dt-cart-title-row">
                <h1 class="dt-cart-title">
                    Shopping Bag
                    <span class="dt-cart-count-pill"><?php echo esc_html( $cart_count ); ?></span>
                </h1>
                <a href="<?php echo esc_url( $shop_url ); ?>" class="dt-cart-continue-link">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Continue Shopping
                </a>
            </div>
            <!-- Progress Steps -->
            <div class="dt-cart-steps">
                <div class="dt-cart-step dt-cart-step-active">
                    <span class="dt-cart-step-num">1</span>
                    <span class="dt-cart-step-label">Cart</span>
                </div>
                <div class="dt-cart-step-line"></div>
                <div class="dt-cart-step">
                    <span class="dt-cart-step-num">2</span>
                    <span class="dt-cart-step-label">Address</span>
                </div>
                <div class="dt-cart-step-line"></div>
                <div class="dt-cart-step">
                    <span class="dt-cart-step-num">3</span>
                    <span class="dt-cart-step-label">Payment</span>
                </div>
            </div>
        </div>
    </div>

    <?php do_action( 'woocommerce_before_cart' ); ?>

    <?php if ( ! WC()->cart->is_empty() ) : ?>

    <div class="dt-cart-body">
        <div class="dt-cart-layout">

            <!-- ── LEFT: Cart Items ──────────────────────────────── -->
            <div class="dt-cart-main">

                <!-- WooCommerce Notices -->
                <?php wc_print_notices(); ?>

                <form class="woocommerce-cart-form dt-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <?php do_action( 'woocommerce_before_cart_table' ); ?>

                    <div class="dt-cart-items-wrap">
                        <!-- Column Headers (desktop only) -->
                        <div class="dt-cart-thead">
                            <span>Product</span>
                            <span>Details</span>
                            <span>Price</span>
                            <span>Qty</span>
                            <span>Total</span>
                            <span></span>
                        </div>

                        <!-- Cart Items -->
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? get_permalink( $product_id ) : '', $cart_item, $cart_item_key );

                            if ( ! $_product || ! $_product->exists() || 0 === (int) $cart_item['quantity'] ) continue;

                            $thumbnail   = $_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'dt-cart-product-img' ) );
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $unit_price   = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $subtotal     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

                            // Category / fabric label
                            $terms = get_the_terms( $product_id, 'product_cat' );
                            $cat_label = '';
                            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                                $cat_label = $terms[0]->name;
                            }

                            $row_class = apply_filters( 'woocommerce_cart_item_class', 'dt-cart-row', $cart_item, $cart_item_key );
                        ?>
                        <div class="<?php echo esc_attr( $row_class ); ?>" data-key="<?php echo esc_attr( $cart_item_key ); ?>">

                            <!-- Product Image -->
                            <div class="dt-cart-col dt-cart-col-img">
                                <?php if ( $product_permalink ) : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>" class="dt-cart-img-link">
                                    <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $thumbnail, $cart_item, $cart_item_key ); // phpcs:ignore ?>
                                </a>
                                <?php else : ?>
                                    <?php echo apply_filters( 'woocommerce_cart_item_thumbnail', $thumbnail, $cart_item, $cart_item_key ); // phpcs:ignore ?>
                                <?php endif; ?>
                            </div>

                            <!-- Product Details -->
                            <div class="dt-cart-col dt-cart-col-info">
                                <?php if ( $cat_label ) : ?>
                                <span class="dt-cart-product-cat"><?php echo esc_html( $cat_label ); ?></span>
                                <?php endif; ?>
                                <h3 class="dt-cart-product-name">
                                    <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
                                    <?php else : echo wp_kses_post( $product_name ); ?>
                                    <?php endif; ?>
                                </h3>
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore ?>
                                <?php if ( ! $_product->is_in_stock() ) : ?>
                                <span class="dt-cart-oos-badge">Out of Stock</span>
                                <?php endif; ?>
                                <!-- Mobile: show price + subtotal inline -->
                                <div class="dt-cart-mobile-prices">
                                    <span class="dt-cart-mobile-unit"><?php echo wp_kses_post( $unit_price ); ?></span>
                                    <span class="dt-cart-mobile-sep">×<?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                    <span class="dt-cart-mobile-sub"><?php echo wp_kses_post( $subtotal ); ?></span>
                                </div>
                            </div>

                            <!-- Unit Price (desktop) -->
                            <div class="dt-cart-col dt-cart-col-price dt-cart-desktop-only">
                                <span class="dt-cart-price"><?php echo wp_kses_post( $unit_price ); ?></span>
                            </div>

                            <!-- Quantity Stepper -->
                            <div class="dt-cart-col dt-cart-col-qty">
                                <div class="dt-qty-wrap">
                                    <button type="button" class="dt-qty-btn dt-qty-minus" onclick="dtCartQty(this,-1)" aria-label="Decrease quantity">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" stroke-linecap="round"/></svg>
                                    </button>
                                    <input
                                        type="number"
                                        name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                        value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
                                        min="0"
                                        max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
                                        step="1"
                                        class="dt-qty-input"
                                        aria-label="Quantity"
                                    />
                                    <button type="button" class="dt-qty-btn dt-qty-plus" onclick="dtCartQty(this,1)" aria-label="Increase quantity">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal (desktop) -->
                            <div class="dt-cart-col dt-cart-col-sub dt-cart-desktop-only">
                                <span class="dt-cart-subtotal"><?php echo wp_kses_post( $subtotal ); ?></span>
                            </div>

                            <!-- Remove -->
                            <div class="dt-cart-col dt-cart-col-remove">
                                <?php
                                $remove_url = apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="dt-cart-remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" title="Remove %s">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/></svg>
                                        </a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $cart_item_key ),
                                        esc_attr( $_product->get_sku() ),
                                        esc_attr( $product_name )
                                    ),
                                    $cart_item_key
                                );
                                echo $remove_url; // phpcs:ignore
                                ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>
                    </div>

                    <!-- Cart Actions: Coupon + Update -->
                    <div class="dt-cart-actions">
                        <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="dt-cart-coupon-wrap">
                            <div class="dt-cart-coupon-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                Have a coupon?
                            </div>
                            <div class="dt-cart-coupon-row">
                                <input type="text" name="coupon_code" id="coupon_code" class="dt-coupon-input" placeholder="Enter coupon code" value="" />
                                <button type="submit" name="apply_coupon" value="Apply coupon" class="dt-coupon-btn">
                                    Apply
                                </button>
                            </div>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                        <?php endif; ?>

                        <div class="dt-cart-update-wrap">
                            <?php do_action( 'woocommerce_cart_actions' ); ?>
                            <button type="submit" name="update_cart" value="Update cart" class="dt-cart-update-btn">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 4v6h6M23 20v-6h-6"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Update Cart
                            </button>
                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>
                    </div>

                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>

                <!-- Applied Coupons -->
                <?php if ( count( WC()->cart->get_applied_coupons() ) ) : ?>
                <div class="dt-applied-coupons">
                    <?php foreach ( WC()->cart->get_applied_coupons() as $code ) :
                        $coupon_obj = new WC_Coupon( $code );
                        $discount   = WC()->cart->get_coupon_discount_amount( $code );
                    ?>
                    <div class="dt-applied-coupon-tag">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        <?php echo esc_html( strtoupper( $code ) ); ?> — −<?php echo wc_price( $discount ); // phpcs:ignore ?>
                        <a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $code ), wc_get_cart_url() ) ); ?>" class="dt-coupon-remove" aria-label="Remove coupon">×</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php do_action( 'woocommerce_after_cart' ); ?>
            </div>

            <!-- ── RIGHT: Order Summary ──────────────────────────── -->
            <aside class="dt-cart-sidebar">
                <div class="dt-cart-summary">

                    <h2 class="dt-cart-summary-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Order Summary
                    </h2>

                    <!-- Items Count -->
                    <div class="dt-cart-summary-meta">
                        <?php echo esc_html( $cart_count ); ?> item<?php echo $cart_count !== 1 ? 's' : ''; ?> in your bag
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="dt-cart-totals">
                        <div class="dt-cart-total-row">
                            <span>Subtotal</span>
                            <span><?php woocommerce_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <div class="dt-cart-total-row">
                            <span>Shipping</span>
                            <span class="dt-cart-shipping">
                                <?php woocommerce_cart_totals_shipping_html(); ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <div class="dt-cart-total-row">
                            <span><?php echo esc_html( $fee->name ); ?></span>
                            <span><?php echo wc_price( $fee->total ); // phpcs:ignore ?></span>
                        </div>
                        <?php endforeach; ?>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="dt-cart-total-row dt-cart-discount-row">
                            <span>Discount (<?php echo esc_html( strtoupper( $code ) ); ?>)</span>
                            <span class="dt-discount-amount">−<?php echo wc_price( WC()->cart->get_coupon_discount_amount( $code ) ); // phpcs:ignore ?></span>
                        </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->get_taxes_total() ) : ?>
                        <div class="dt-cart-total-row">
                            <span>Tax</span>
                            <span><?php echo wc_price( WC()->cart->get_taxes_total() ); // phpcs:ignore ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="dt-cart-total-grand">
                            <span>Grand Total</span>
                            <span><?php woocommerce_cart_totals_order_total_html(); ?></span>
                        </div>

                        <?php if ( WC()->cart->get_discount_total() > 0 ) : ?>
                        <div class="dt-cart-savings-note">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            You save <?php echo wc_price( WC()->cart->get_discount_total() ); // phpcs:ignore ?> on this order!
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Checkout Button -->
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="dt-cart-checkout-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/><path d="M16 10a4 4 0 01-8 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Proceed to Checkout
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>

                    <!-- Security badges -->
                    <div class="dt-cart-trust">
                        <div class="dt-cart-trust-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>100% Secure Payment</span>
                        </div>
                        <div class="dt-cart-trust-sep">✦</div>
                        <div class="dt-cart-trust-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12" stroke-linecap="round"/></svg>
                            <span>7-Day Returns</span>
                        </div>
                        <div class="dt-cart-trust-sep">✦</div>
                        <div class="dt-cart-trust-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Free Shipping</span>
                        </div>
                    </div>

                    <!-- Payment Logos -->
                    <div class="dt-cart-payment-logos">
                        <span>We accept:</span>
                        <div class="dt-cart-payment-icons">
                            <div class="dt-pay-icon">UPI</div>
                            <div class="dt-pay-icon">CARD</div>
                            <div class="dt-pay-icon">COD</div>
                            <div class="dt-pay-icon">NET</div>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <?php else : ?>

    <!-- Empty Cart -->
    <?php wc_get_template( 'cart/cart-empty.php' ); ?>

    <?php endif; ?>

</div>

<style>
/* ====================================================================
   CART PAGE — DT Luxury Dark Theme
   ==================================================================== */

/* ── Base ── */
body.woocommerce-cart { background: #050505 !important; }

.dt-cart-page {
    min-height: 100vh;
    background: #050505;
    color: #F7F4EE;
    font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
}

/* ── Header ── */
.dt-cart-header {
    background: linear-gradient(180deg, #0a0a0a 0%, #050505 100%);
    border-bottom: 1px solid rgba(200,164,106,0.15);
}
.dt-cart-header-inner {
    max-width: 1360px;
    margin: 0 auto;
    padding: clamp(16px,2.5vw,28px) clamp(16px,3vw,40px);
}
.dt-cart-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(247,244,238,0.35);
    margin-bottom: 14px;
}
.dt-cart-breadcrumb a {
    color: rgba(247,244,238,0.45);
    text-decoration: none;
    transition: color 0.2s;
}
.dt-cart-breadcrumb a:hover { color: #C8A46A; }
.dt-cart-breadcrumb span { color: rgba(247,244,238,0.25); }

.dt-cart-title-row {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.dt-cart-title {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(2rem, 3.5vw + 0.5rem, 3.2rem);
    font-weight: 700;
    color: #F7F4EE;
    line-height: 1.1;
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 0;
}
.dt-cart-count-pill {
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #000;
    background: #C8A46A;
    padding: 2px 9px;
    border-radius: 99px;
    line-height: 1.6;
    vertical-align: middle;
}
.dt-cart-continue-link {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(200,164,106,0.8);
    text-decoration: none;
    transition: color 0.2s;
    white-space: nowrap;
}
.dt-cart-continue-link:hover { color: #C8A46A; }

/* ── Progress Steps ── */
.dt-cart-steps {
    display: flex;
    align-items: center;
    gap: 0;
}
.dt-cart-step {
    display: flex;
    align-items: center;
    gap: 8px;
}
.dt-cart-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid rgba(200,164,106,0.3);
    color: rgba(247,244,238,0.4);
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.2s;
}
.dt-cart-step-label {
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(247,244,238,0.35);
    transition: color 0.2s;
}
.dt-cart-step-active .dt-cart-step-num {
    background: #C8A46A;
    border-color: #C8A46A;
    color: #000;
}
.dt-cart-step-active .dt-cart-step-label { color: #C8A46A; font-weight: 700; }
.dt-cart-step-line {
    flex: 1;
    height: 1px;
    background: rgba(200,164,106,0.2);
    min-width: 40px;
    max-width: 100px;
    margin: 0 12px;
}

/* ── Layout ── */
.dt-cart-body {
    max-width: 1360px;
    margin: 0 auto;
    padding: clamp(20px,3vw,40px) clamp(16px,3vw,40px) clamp(40px,5vw,80px);
}
.dt-cart-layout {
    display: grid;
    grid-template-columns: minmax(0,1fr) 380px;
    align-items: start;
    gap: clamp(20px,3vw,48px);
}

/* ── Cart Items Wrap ── */
.dt-cart-items-wrap {
    background: #0b0b0b;
    border: 1px solid rgba(200,164,106,0.18);
    overflow: hidden;
}

/* ── Table Header ── */
.dt-cart-thead {
    display: grid;
    grid-template-columns: 100px 1fr 110px 130px 110px 44px;
    align-items: center;
    gap: 0;
    padding: 14px 20px;
    background: rgba(200,164,106,0.06);
    border-bottom: 1px solid rgba(200,164,106,0.14);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(200,164,106,0.7);
}

/* ── Cart Row ── */
.dt-cart-row {
    display: grid;
    grid-template-columns: 100px 1fr 110px 130px 110px 44px;
    align-items: center;
    gap: 0;
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    transition: background 0.2s;
}
.dt-cart-row:last-child { border-bottom: 0; }
.dt-cart-row:hover { background: rgba(255,255,255,0.02); }

.dt-cart-col { padding: 0 8px; }
.dt-cart-col-img { padding-left: 0; }
.dt-cart-col-remove { padding-right: 0; display: flex; justify-content: center; }

/* Image */
.dt-cart-img-link { display: block; }
.dt-cart-product-img {
    width: 80px !important;
    height: 104px !important;
    object-fit: cover !important;
    object-position: center !important;
    border: 1px solid rgba(200,164,106,0.2);
    display: block;
    transition: border-color 0.2s;
}
.dt-cart-img-link:hover .dt-cart-product-img { border-color: rgba(200,164,106,0.6); }

/* Info */
.dt-cart-product-cat {
    display: block;
    font-size: 10px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(200,164,106,0.7);
    margin-bottom: 5px;
}
.dt-cart-product-name {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: #F7F4EE;
    margin: 0 0 4px;
    line-height: 1.3;
}
.dt-cart-product-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}
.dt-cart-product-name a:hover { color: #C8A46A; }
.dt-cart-oos-badge {
    display: inline-block;
    font-size: 10px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #f87171;
    border: 1px solid rgba(248,113,113,0.4);
    padding: 2px 7px;
    margin-top: 4px;
}

/* Price & Subtotal */
.dt-cart-price, .dt-cart-subtotal {
    font-size: 0.95rem;
    font-weight: 600;
    color: #F7F4EE;
}
.dt-cart-subtotal { color: #C8A46A; }

/* Quantity Stepper */
.dt-qty-wrap {
    display: inline-flex;
    align-items: center;
    border: 1px solid rgba(200,164,106,0.3);
    background: rgba(0,0,0,0.4);
    overflow: hidden;
}
.dt-qty-btn {
    width: 36px;
    height: 40px;
    background: transparent;
    border: none;
    color: rgba(200,164,106,0.8);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
    flex-shrink: 0;
}
.dt-qty-btn:hover { background: rgba(200,164,106,0.12); color: #C8A46A; }
.dt-qty-input {
    width: 46px;
    height: 40px;
    text-align: center;
    background: transparent !important;
    border: none !important;
    border-left: 1px solid rgba(200,164,106,0.2) !important;
    border-right: 1px solid rgba(200,164,106,0.2) !important;
    color: #F7F4EE !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    outline: none;
    -moz-appearance: textfield;
    padding: 0 !important;
}
.dt-qty-input::-webkit-inner-spin-button,
.dt-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

/* Remove Button */
.dt-cart-remove {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.1);
    background: transparent;
    color: rgba(247,244,238,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
}
.dt-cart-remove:hover {
    background: rgba(248,113,113,0.12);
    border-color: rgba(248,113,113,0.5);
    color: #f87171;
}

/* Mobile prices (shown only on mobile) */
.dt-cart-mobile-prices { display: none; }
.dt-cart-desktop-only {}

/* ── Cart Actions ── */
.dt-cart-actions {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 20px;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(200,164,106,0.12);
    border-top: 0;
    margin-bottom: 16px;
}
.dt-cart-coupon-wrap { flex: 1; min-width: 220px; max-width: 420px; }
.dt-cart-coupon-label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(200,164,106,0.8);
    font-weight: 700;
    margin-bottom: 10px;
}
.dt-cart-coupon-row { display: flex; gap: 0; }
.dt-coupon-input {
    flex: 1;
    height: 46px;
    background: #0b0b0b !important;
    border: 1px solid rgba(200,164,106,0.3) !important;
    border-right: 0 !important;
    color: #F7F4EE !important;
    font-size: 13px !important;
    padding: 0 14px !important;
    outline: none;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: border-color 0.2s;
}
.dt-coupon-input:focus { border-color: #C8A46A !important; }
.dt-coupon-input::placeholder { color: rgba(247,244,238,0.3) !important; text-transform: none; }
.dt-coupon-btn {
    height: 46px;
    padding: 0 20px;
    background: #C8A46A;
    color: #000;
    border: none;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
}
.dt-coupon-btn:hover { background: #d8ba82; }

.dt-cart-update-wrap { display: flex; align-items: flex-end; }
.dt-cart-update-btn {
    display: flex;
    align-items: center;
    gap: 7px;
    height: 46px;
    padding: 0 22px;
    background: transparent;
    border: 1px solid rgba(200,164,106,0.35);
    color: #C8A46A;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.2s;
}
.dt-cart-update-btn:hover { background: rgba(200,164,106,0.08); border-color: #C8A46A; }

/* Applied Coupons */
.dt-applied-coupons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}
.dt-applied-coupon-tag {
    display: flex;
    align-items: center;
    gap: 7px;
    background: rgba(200,164,106,0.1);
    border: 1px solid rgba(200,164,106,0.35);
    color: #C8A46A;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 6px 12px;
}
.dt-coupon-remove {
    color: rgba(200,164,106,0.6);
    text-decoration: none;
    font-size: 14px;
    line-height: 1;
    margin-left: 4px;
    transition: color 0.2s;
}
.dt-coupon-remove:hover { color: #f87171; }

/* ── Cart Sidebar / Summary ── */
.dt-cart-sidebar { position: sticky; top: 92px; }
.dt-cart-summary {
    background: linear-gradient(180deg, #0e0e0e 0%, #0a0a0a 100%);
    border: 1px solid rgba(200,164,106,0.24);
    padding: clamp(22px,2.5vw,32px);
}
.dt-cart-summary-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.35rem, 1vw + 1rem, 1.75rem);
    font-weight: 700;
    color: #C8A46A;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin: 0 0 8px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(200,164,106,0.14);
}
.dt-cart-summary-meta {
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(247,244,238,0.38);
    margin-bottom: 20px;
}

/* Totals */
.dt-cart-totals { display: flex; flex-direction: column; gap: 12px; margin-bottom: 22px; }
.dt-cart-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: rgba(247,244,238,0.7);
}
.dt-cart-total-row span:last-child { color: #F7F4EE; font-weight: 600; }
.dt-cart-discount-row span:last-child { color: #4ade80; }
.dt-discount-amount { color: #4ade80 !important; }
.dt-cart-shipping { color: #C8A46A !important; }
.dt-cart-total-grand {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 700;
    color: #F7F4EE;
    margin-top: 6px;
    padding-top: 14px;
    border-top: 1px solid rgba(200,164,106,0.22);
}
.dt-cart-total-grand span:last-child { font-size: 1.2rem; color: #C8A46A; }

.dt-cart-savings-note {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #4ade80;
    background: rgba(74,222,128,0.08);
    border: 1px solid rgba(74,222,128,0.25);
    padding: 8px 12px;
    margin-top: 8px;
    font-weight: 600;
}

/* Checkout Button */
.dt-cart-checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 17px 24px;
    background: linear-gradient(110deg, #b08d55 0%, #d8ba82 40%, #C8A46A 60%, #d8ba82 80%, #b08d55 100%);
    background-size: 200% auto;
    color: #000 !important;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 8px 28px rgba(200,164,106,0.25);
    margin-bottom: 18px;
    position: relative;
    overflow: hidden;
}
.dt-cart-checkout-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.25) 50%, transparent 100%);
    transform: translateX(-100%) skewX(-15deg);
    transition: transform 0.6s ease;
}
.dt-cart-checkout-btn:hover { background-position: right center; box-shadow: 0 12px 36px rgba(200,164,106,0.35); }
.dt-cart-checkout-btn:hover::before { transform: translateX(200%) skewX(-15deg); }

/* Trust */
.dt-cart-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.1);
    margin-bottom: 16px;
}
.dt-cart-trust-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: rgba(247,244,238,0.5);
}
.dt-cart-trust-sep { color: rgba(200,164,106,0.4); font-size: 9px; }

/* Payment logos */
.dt-cart-payment-logos {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 10px;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: rgba(247,244,238,0.3);
}
.dt-cart-payment-icons { display: flex; gap: 6px; }
.dt-pay-icon {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.05em;
    padding: 4px 7px;
    border: 1px solid rgba(200,164,106,0.22);
    color: rgba(200,164,106,0.6);
    background: rgba(200,164,106,0.05);
}

/* ── WooCommerce notice override ── */
.woocommerce-cart .woocommerce-message,
.woocommerce-cart .woocommerce-info,
.woocommerce-cart .woocommerce-error {
    background: #101010 !important;
    border-top: 2px solid #C8A46A !important;
    color: #F7F4EE !important;
    border-radius: 0 !important;
    margin-bottom: 16px !important;
    padding: 14px 18px !important;
    list-style: none !important;
}
.woocommerce-cart .woocommerce-message a,
.woocommerce-cart .woocommerce-info a { color: #C8A46A !important; }

/* ── WC default cart table nuclear override ── */
.woocommerce-cart table.cart,
.woocommerce-cart .shop_table { display: none !important; }
/* (Our .dt-cart-items-wrap replaces it) */

/* ── Responsive ── */
@media (max-width: 1200px) {
    .dt-cart-layout { grid-template-columns: minmax(0,1fr) 340px; }
    .dt-cart-thead { grid-template-columns: 80px 1fr 100px 120px 100px 40px; }
    .dt-cart-row   { grid-template-columns: 80px 1fr 100px 120px 100px 40px; }
}

@media (max-width: 1023px) {
    .dt-cart-layout { grid-template-columns: 1fr; }
    .dt-cart-sidebar { position: static; }
    .dt-cart-summary { max-width: 540px; }
}

@media (max-width: 767px) {
    .dt-cart-body { padding-left: 14px; padding-right: 14px; }
    .dt-cart-thead { display: none; }
    .dt-cart-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-start;
        padding: 16px 14px;
    }
    .dt-cart-col-img { flex-shrink: 0; }
    .dt-cart-col-info { flex: 1; min-width: 0; }
    .dt-cart-col-price.dt-cart-desktop-only,
    .dt-cart-col-sub.dt-cart-desktop-only { display: none; }
    .dt-cart-col-qty { order: 4; width: 100%; padding-left: 92px; }
    .dt-cart-col-remove { order: 3; margin-left: auto; padding: 0; align-self: flex-start; }
    .dt-cart-mobile-prices {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        font-size: 12px;
    }
    .dt-cart-mobile-unit { color: rgba(247,244,238,0.6); }
    .dt-cart-mobile-sep { color: rgba(247,244,238,0.3); font-size: 11px; }
    .dt-cart-mobile-sub { color: #C8A46A; font-weight: 700; }
    .dt-cart-product-img { width: 72px !important; height: 92px !important; }
    .dt-cart-actions { flex-direction: column; }
    .dt-cart-coupon-wrap { min-width: 0; max-width: none; width: 100%; }
    .dt-cart-steps { display: none; }
}

@media (max-width: 480px) {
    .dt-cart-title { font-size: 1.8rem; }
}
</style>

<script>
/* Cart qty +/- buttons — auto-submit form after change */
function dtCartQty(btn, dir) {
    var wrap = btn.closest('.dt-qty-wrap');
    var input = wrap ? wrap.querySelector('.dt-qty-input') : null;
    if (!input) return;
    var cur = parseInt(input.value) || 1;
    var next = Math.max(0, cur + dir);
    input.value = next;
    // Trigger WooCommerce cart update
    var form = btn.closest('form');
    if (form) {
        if (next === 0) {
            // Remove item
            var row = btn.closest('.dt-cart-row');
            var key = row ? row.dataset.key : '';
            if (key) {
                var removeBtn = document.querySelector('.dt-cart-remove[data-cart_item_key="' + key + '"]');
                if (removeBtn) { window.location.href = removeBtn.href; return; }
            }
        }
        // Auto-update after small delay
        clearTimeout(window._dtCartUpdateTimer);
        window._dtCartUpdateTimer = setTimeout(function() {
            var updateBtn = form.querySelector('[name="update_cart"]');
            if (updateBtn) updateBtn.click();
        }, 800);
    }
}
</script>

<?php get_footer(); ?>
