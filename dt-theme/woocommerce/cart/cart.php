<?php
/**
 * Cart Page Template — DT Ecommerce Theme (Premium Dark Luxury)
 * NOTE: This is a WooCommerce template override — DO NOT call get_header/get_footer.
 * It renders inside page.php → the_content() → WooCommerce shortcode.
 * Use the full-bleed breakout technique to escape the container constraint.
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.4.2
 */

defined( 'ABSPATH' ) || exit;

$cart_count   = WC()->cart->get_cart_contents_count();
$shop_url     = wc_get_page_permalink( 'shop' );
$checkout_url = wc_get_checkout_url();
?>

<!-- ===== CART PAGE (full-bleed breakout from page.php container) ===== -->
<div class="dt-cart-page">

    <!-- Page Header -->
    <div class="dt-cart-header">
        <div class="dt-cart-header-inner">
            <div class="dt-cart-breadcrumb">
                <a href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
                <span class="dt-bc-sep">/</span>
                <a href="<?php echo esc_url( $shop_url ); ?>">Shop</a>
                <span class="dt-bc-sep">/</span>
                <span>Cart</span>
            </div>
            <div class="dt-cart-title-row">
                <h1 class="dt-cart-title">
                    Shopping Bag
                    <?php if ( $cart_count > 0 ) : ?>
                    <span class="dt-cart-count-pill"><?php echo esc_html( $cart_count ); ?></span>
                    <?php endif; ?>
                </h1>
                <a href="<?php echo esc_url( $shop_url ); ?>" class="dt-cart-continue-link">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
                    <span class="dt-cart-step-label">Details</span>
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
    <?php wc_print_notices(); ?>

    <?php if ( ! WC()->cart->is_empty() ) : ?>

    <div class="dt-cart-body">
        <div class="dt-cart-layout">

            <!-- ── LEFT: Cart Items ──────────────────── -->
            <div class="dt-cart-main">

                <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <?php do_action( 'woocommerce_before_cart_table' ); ?>

                    <div class="dt-cart-items-wrap">

                        <!-- Column Headers -->
                        <div class="dt-cart-thead">
                            <div>Product</div>
                            <div>Item Details</div>
                            <div>Unit Price</div>
                            <div>Quantity</div>
                            <div>Subtotal</div>
                            <div></div>
                        </div>

                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( ! $_product || ! $_product->exists() || 0 === (int) $cart_item['quantity'] ) continue;

                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? get_permalink( $product_id ) : '', $cart_item, $cart_item_key );
                            $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail', ['class' => 'dt-cart-product-img'] ), $cart_item, $cart_item_key );
                            $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                            $unit_price        = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                            $subtotal          = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

                            $terms     = get_the_terms( $product_id, 'product_cat' );
                            $cat_label = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
                            $row_class = apply_filters( 'woocommerce_cart_item_class', 'dt-cart-row', $cart_item, $cart_item_key );
                        ?>
                        <div class="<?php echo esc_attr( $row_class ); ?>" data-key="<?php echo esc_attr( $cart_item_key ); ?>">

                            <!-- Image -->
                            <div class="dt-cc dt-cc-img">
                                <?php if ( $product_permalink ) : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>" class="dt-cart-img-link"><?php echo $thumbnail; // phpcs:ignore ?></a>
                                <?php else : echo $thumbnail; // phpcs:ignore ?>
                                <?php endif; ?>
                            </div>

                            <!-- Info -->
                            <div class="dt-cc dt-cc-info">
                                <?php if ( $cat_label ) : ?>
                                <span class="dt-cart-cat"><?php echo esc_html( $cat_label ); ?></span>
                                <?php endif; ?>
                                <h3 class="dt-cart-name">
                                    <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
                                    <?php else : echo wp_kses_post( $product_name ); ?>
                                    <?php endif; ?>
                                </h3>
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore ?>
                                <?php if ( ! $_product->is_in_stock() ) : ?>
                                <span class="dt-oos-badge">Out of Stock</span>
                                <?php endif; ?>
                                <div class="dt-mobile-prices">
                                    <span class="dt-mob-unit"><?php echo wp_kses_post( $unit_price ); ?></span>
                                    <span class="dt-mob-x">×<?php echo esc_html( $cart_item['quantity'] ); ?></span>
                                    <span class="dt-mob-sub"><?php echo wp_kses_post( $subtotal ); ?></span>
                                </div>
                            </div>

                            <!-- Unit Price -->
                            <div class="dt-cc dt-cc-price dt-dsk">
                                <?php echo wp_kses_post( $unit_price ); ?>
                            </div>

                            <!-- Qty -->
                            <div class="dt-cc dt-cc-qty">
                                <div class="dt-qty-stepper">
                                    <button type="button" class="dt-qs-btn" onclick="dtQty(this,-1)" aria-label="Minus">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" stroke-linecap="round"/></svg>
                                    </button>
                                    <input type="number"
                                        name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                        value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
                                        min="0"
                                        max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
                                        step="1"
                                        class="dt-qs-input"
                                        aria-label="Quantity"
                                    />
                                    <button type="button" class="dt-qs-btn" onclick="dtQty(this,1)" aria-label="Plus">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="dt-cc dt-cc-sub dt-dsk">
                                <span class="dt-row-sub"><?php echo wp_kses_post( $subtotal ); ?></span>
                            </div>

                            <!-- Remove -->
                            <div class="dt-cc dt-cc-rm">
                                <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                                   class="dt-rm-btn"
                                   aria-label="Remove <?php echo esc_attr( $product_name ); ?>"
                                   data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                   data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                                   data-product_sku="<?php echo esc_attr( $_product->get_sku() ); ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/></svg>
                                </a>
                            </div>

                        </div>
                        <?php endforeach; ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>
                    </div><!-- /.dt-cart-items-wrap -->

                    <!-- Actions row -->
                    <div class="dt-cart-actions">
                        <?php if ( wc_coupons_enabled() ) : ?>
                        <div class="dt-coupon-area">
                            <div class="dt-coupon-lbl">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                Have a coupon?
                            </div>
                            <div class="dt-coupon-row">
                                <input type="text" name="coupon_code" id="coupon_code" class="dt-ci" placeholder="Coupon code" />
                                <button type="submit" name="apply_coupon" value="Apply coupon" class="dt-cb">Apply</button>
                            </div>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                        <?php endif; ?>

                        <div class="dt-update-area">
                            <?php do_action( 'woocommerce_cart_actions' ); ?>
                            <button type="submit" name="update_cart" value="Update cart" class="dt-update-btn">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 4v6h6M23 20v-6h-6"/><path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Update Cart
                            </button>
                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>
                    </div>

                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>

                <!-- Applied coupons -->
                <?php $applied = WC()->cart->get_applied_coupons(); ?>
                <?php if ( ! empty( $applied ) ) : ?>
                <div class="dt-applied-coupons">
                    <?php foreach ( $applied as $code ) :
                        $discount = WC()->cart->get_coupon_discount_amount( $code );
                    ?>
                    <span class="dt-coupon-tag">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        <?php echo esc_html( strtoupper( $code ) ); ?> — −<?php echo wp_kses_post( wc_price( $discount ) ); ?>
                        <a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $code ), wc_get_cart_url() ) ); ?>" class="dt-coupon-x" aria-label="Remove">×</a>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php do_action( 'woocommerce_after_cart' ); ?>
            </div><!-- /.dt-cart-main -->

            <!-- ── RIGHT: Order Summary ──────────────── -->
            <aside class="dt-cart-aside">
                <div class="dt-cart-summary-box">

                    <div class="dt-sum-head">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.6"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <h2 class="dt-sum-title">Order Summary</h2>
                    </div>
                    <p class="dt-sum-meta"><?php echo esc_html( $cart_count ); ?> item<?php echo $cart_count !== 1 ? 's' : ''; ?> in your bag</p>

                    <!-- Totals -->
                    <div class="dt-sum-rows">
                        <div class="dt-sum-row">
                            <span>Subtotal</span>
                            <span><?php woocommerce_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                        <div class="dt-sum-row">
                            <span>Shipping</span>
                            <span class="dt-sum-ship"><?php woocommerce_cart_totals_shipping_html(); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <div class="dt-sum-row">
                            <span><?php echo esc_html( $fee->name ); ?></span>
                            <span><?php echo wp_kses_post( wc_price( $fee->total ) ); ?></span>
                        </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->get_taxes_total() ) : ?>
                        <div class="dt-sum-row">
                            <span>Tax</span>
                            <span><?php echo wp_kses_post( wc_price( WC()->cart->get_taxes_total() ) ); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="dt-sum-row dt-sum-disc">
                            <span>Discount (<?php echo esc_html( strtoupper( $code ) ); ?>)</span>
                            <span>−<?php echo wp_kses_post( wc_price( WC()->cart->get_coupon_discount_amount( $code ) ) ); ?></span>
                        </div>
                        <?php endforeach; ?>

                        <div class="dt-sum-grand">
                            <span>Grand Total</span>
                            <span><?php woocommerce_cart_totals_order_total_html(); ?></span>
                        </div>

                        <?php if ( WC()->cart->get_discount_total() > 0 ) : ?>
                        <div class="dt-sum-saving">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            You save <?php echo wp_kses_post( wc_price( WC()->cart->get_discount_total() ) ); ?>!
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Checkout CTA -->
                    <a href="<?php echo esc_url( $checkout_url ); ?>" class="dt-sum-checkout-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/><path d="M16 10a4 4 0 01-8 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Proceed to Checkout
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>

                    <!-- Trust -->
                    <div class="dt-sum-trust">
                        <div class="dt-st-item">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            100% Secure Payment
                        </div>
                        <span class="dt-st-sep">✦</span>
                        <div class="dt-st-item">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            7-Day Returns
                        </div>
                        <span class="dt-st-sep">✦</span>
                        <div class="dt-st-item">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m0 0h2l4 4v4h-6m0 0a2 2 0 11-4 0 2 2 0 014 0zm-10 0a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Free Shipping
                        </div>
                    </div>

                    <!-- Payment methods -->
                    <div class="dt-sum-pay">
                        <span class="dt-pay-lbl">We Accept:</span>
                        <div class="dt-pay-icons">
                            <span class="dt-pay-chip">UPI</span>
                            <span class="dt-pay-chip">CARD</span>
                            <span class="dt-pay-chip">COD</span>
                            <span class="dt-pay-chip">NET</span>
                        </div>
                    </div>

                </div><!-- /.dt-cart-summary-box -->
            </aside>

        </div><!-- /.dt-cart-layout -->
    </div><!-- /.dt-cart-body -->

    <?php else : ?>
        <?php wc_get_template( 'cart/cart-empty.php' ); ?>
    <?php endif; ?>

</div><!-- /.dt-cart-page -->

<style>
/* =================================================================
   CART PAGE — Full-bleed breakout + Desktop premium layout
   ================================================================= */

/* Full-bleed: escape page.php's "container mx-auto px-4 py-16" */
.dt-cart-page {
  position: relative;
  width: 100vw !important;
  max-width: none !important;
  left: 50%;
  margin-left: -50vw !important;
  margin-right: -50vw !important;
  right: auto;
  background: #050505;
  color: #F7F4EE;
  font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
  /* Override page.py padding */
  margin-top: -4rem !important;  /* undo py-16 top */
  padding-top: 0 !important;
}

/* ── Header ── */
.dt-cart-header {
  background: linear-gradient(180deg,#0c0c0c 0%,#050505 100%);
  border-bottom: 1px solid rgba(200,164,106,0.16);
}
.dt-cart-header-inner {
  max-width: 1400px;
  margin: 0 auto;
  padding: clamp(18px,2.5vw,30px) clamp(20px,4vw,60px);
}
.dt-cart-breadcrumb {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 10px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(247,244,238,0.32);
  margin-bottom: 12px;
}
.dt-cart-breadcrumb a { color: rgba(247,244,238,0.42); text-decoration: none; transition: color .2s; }
.dt-cart-breadcrumb a:hover { color: #C8A46A; }
.dt-bc-sep { opacity: .35; }

.dt-cart-title-row {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 18px;
}
.dt-cart-title {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(1.9rem,3vw,3rem);
  font-weight: 700;
  color: #F7F4EE;
  line-height: 1.1;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
}
.dt-cart-count-pill {
  font-family: 'Inter', sans-serif;
  font-size: 12px;
  font-weight: 700;
  color: #000;
  background: #C8A46A;
  padding: 2px 9px;
  border-radius: 99px;
  vertical-align: middle;
  line-height: 1.6;
}
.dt-cart-continue-link {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(200,164,106,0.75);
  text-decoration: none;
  transition: color .2s;
  white-space: nowrap;
}
.dt-cart-continue-link:hover { color: #C8A46A; }

/* Steps */
.dt-cart-steps {
  display: flex;
  align-items: center;
}
.dt-cart-step { display: flex; align-items: center; gap: 7px; }
.dt-cart-step-num {
  width: 26px; height: 26px;
  border-radius: 50%;
  border: 1px solid rgba(200,164,106,0.28);
  color: rgba(247,244,238,0.38);
  font-size: 10px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.dt-cart-step-label {
  font-size: 10px;
  letter-spacing: 0.09em;
  text-transform: uppercase;
  color: rgba(247,244,238,0.32);
}
.dt-cart-step-active .dt-cart-step-num { background: #C8A46A; border-color: #C8A46A; color: #000; }
.dt-cart-step-active .dt-cart-step-label { color: #C8A46A; font-weight: 700; }
.dt-cart-step-line {
  flex: 1;
  height: 1px;
  background: rgba(200,164,106,0.18);
  min-width: 36px;
  max-width: 90px;
  margin: 0 10px;
}

/* ── Body Layout ── */
.dt-cart-body {
  max-width: 1400px;
  margin: 0 auto;
  padding: clamp(24px,3vw,44px) clamp(20px,4vw,60px) clamp(48px,5vw,80px);
}
.dt-cart-layout {
  display: grid;
  grid-template-columns: minmax(0,1fr) 400px;
  gap: clamp(24px,3vw,52px);
  align-items: start;
}

/* ── Items Wrap ── */
.dt-cart-items-wrap {
  background: #0c0c0c;
  border: 1px solid rgba(200,164,106,0.18);
  margin-bottom: 0;
}

/* Table header */
.dt-cart-thead {
  display: grid;
  grid-template-columns: 96px 1fr 120px 140px 120px 48px;
  padding: 12px 18px;
  background: rgba(200,164,106,0.07);
  border-bottom: 1px solid rgba(200,164,106,0.13);
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: rgba(200,164,106,0.65);
  gap: 0;
}
.dt-cart-thead > div { padding: 0 6px; }
.dt-cart-thead > div:first-child { padding-left: 0; }

/* Row */
.dt-cart-row {
  display: grid;
  grid-template-columns: 96px 1fr 120px 140px 120px 48px;
  align-items: center;
  padding: 16px 18px;
  border-bottom: 1px solid rgba(255,255,255,0.045);
  transition: background .18s;
  gap: 0;
}
.dt-cart-row:last-child { border-bottom: 0; }
.dt-cart-row:hover { background: rgba(255,255,255,0.018); }

.dt-cc { padding: 0 6px; }
.dt-cc-img { padding-left: 0; }
.dt-cc-rm  { padding-right: 0; display: flex; justify-content: center; }

/* Image */
.dt-cart-img-link { display: block; }
.dt-cart-product-img {
  width: 78px !important;
  height: 100px !important;
  object-fit: cover !important;
  object-position: top center !important;
  border: 1px solid rgba(200,164,106,0.2);
  display: block;
  transition: border-color .2s;
}
.dt-cart-img-link:hover .dt-cart-product-img { border-color: rgba(200,164,106,0.55); }

/* Info */
.dt-cart-cat {
  display: block;
  font-size: 9px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: rgba(200,164,106,0.62);
  margin-bottom: 4px;
}
.dt-cart-name {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: 1.02rem;
  font-weight: 600;
  color: #F7F4EE;
  margin: 0 0 3px;
  line-height: 1.3;
}
.dt-cart-name a { color: inherit; text-decoration: none; transition: color .2s; }
.dt-cart-name a:hover { color: #C8A46A; }
.woocommerce-cart-item-variation,
dl.variation { font-size: 11px; color: rgba(247,244,238,0.42); margin-top: 3px; }
.dt-oos-badge {
  display: inline-block;
  font-size: 9px; letter-spacing: 0.08em; text-transform: uppercase;
  color: #f87171; border: 1px solid rgba(248,113,113,0.38);
  padding: 2px 6px; margin-top: 4px;
}

/* Unit price & subtotal */
.dt-cc-price, .dt-cc-sub {
  font-size: 0.9rem;
  font-weight: 600;
  color: rgba(247,244,238,0.85);
}
.dt-row-sub { color: #C8A46A; font-weight: 700; }

/* Qty stepper */
.dt-qty-stepper {
  display: inline-flex;
  align-items: center;
  border: 1px solid rgba(200,164,106,0.28);
  background: rgba(0,0,0,0.4);
  overflow: hidden;
}
.dt-qs-btn {
  width: 34px; height: 38px;
  background: transparent;
  border: none;
  color: rgba(200,164,106,0.75);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .18s, color .18s;
  flex-shrink: 0;
}
.dt-qs-btn:hover { background: rgba(200,164,106,0.12); color: #C8A46A; }
.dt-qs-input {
  width: 42px; height: 38px;
  text-align: center;
  background: transparent !important;
  border: none !important;
  border-left: 1px solid rgba(200,164,106,0.18) !important;
  border-right: 1px solid rgba(200,164,106,0.18) !important;
  color: #F7F4EE !important;
  font-size: 13px !important;
  font-weight: 600 !important;
  outline: none;
  -moz-appearance: textfield;
  padding: 0 !important;
  box-shadow: none !important;
}
.dt-qs-input::-webkit-inner-spin-button,
.dt-qs-input::-webkit-outer-spin-button { -webkit-appearance: none; }

/* Remove */
.dt-rm-btn {
  width: 30px; height: 30px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.09);
  background: transparent;
  color: rgba(247,244,238,0.38);
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all .18s;
}
.dt-rm-btn:hover { background: rgba(248,113,113,0.12); border-color: rgba(248,113,113,0.45); color: #f87171; }

/* Mobile prices (hidden desktop) */
.dt-mobile-prices { display: none; }

/* ── Actions ── */
.dt-cart-actions {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 14px;
  padding: 18px;
  background: rgba(0,0,0,0.28);
  border: 1px solid rgba(200,164,106,0.11);
  border-top: 0;
  margin-bottom: 14px;
}
.dt-coupon-area { flex: 1; min-width: 200px; max-width: 420px; }
.dt-coupon-lbl {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 10px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(200,164,106,0.75);
  font-weight: 700;
  margin-bottom: 9px;
}
.dt-coupon-row { display: flex; }
.dt-ci {
  flex: 1;
  height: 44px;
  background: #0a0a0a !important;
  border: 1px solid rgba(200,164,106,0.28) !important;
  border-right: 0 !important;
  color: #F7F4EE !important;
  font-size: 12px !important;
  letter-spacing: 0.07em;
  padding: 0 13px !important;
  outline: none;
  text-transform: uppercase;
  transition: border-color .18s;
}
.dt-ci:focus { border-color: #C8A46A !important; }
.dt-ci::placeholder { color: rgba(247,244,238,0.28) !important; text-transform: none; }
.dt-cb {
  height: 44px;
  padding: 0 18px;
  background: #C8A46A;
  color: #000;
  border: none;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  cursor: pointer;
  transition: background .18s;
  flex-shrink: 0;
}
.dt-cb:hover { background: #d8ba82; }

.dt-update-area { display: flex; align-items: flex-end; }
.dt-update-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  height: 44px;
  padding: 0 20px;
  background: transparent;
  border: 1px solid rgba(200,164,106,0.32);
  color: #C8A46A;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all .18s;
}
.dt-update-btn:hover { background: rgba(200,164,106,0.07); border-color: #C8A46A; }

/* Applied coupons */
.dt-applied-coupons { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
.dt-coupon-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(200,164,106,0.09);
  border: 1px solid rgba(200,164,106,0.32);
  color: #C8A46A;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  padding: 5px 10px;
}
.dt-coupon-x { color: rgba(200,164,106,0.55); text-decoration: none; font-size: 13px; margin-left: 3px; }
.dt-coupon-x:hover { color: #f87171; }

/* ── Aside / Summary ── */
.dt-cart-aside { position: sticky; top: 88px; }
.dt-cart-summary-box {
  background: linear-gradient(170deg,#0e0e0e 0%,#0a0a0a 100%);
  border: 1px solid rgba(200,164,106,0.22);
  padding: clamp(22px,2.5vw,34px);
}

.dt-sum-head {
  display: flex;
  align-items: center;
  gap: 9px;
  padding-bottom: 14px;
  border-bottom: 1px solid rgba(200,164,106,0.13);
  margin-bottom: 8px;
}
.dt-sum-title {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(1.25rem,1.2vw+0.8rem,1.7rem);
  font-weight: 700;
  color: #C8A46A;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin: 0;
  line-height: 1;
}
.dt-sum-meta {
  font-size: 10px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgba(247,244,238,0.35);
  margin: 0 0 20px;
}

.dt-sum-rows { display: flex; flex-direction: column; gap: 11px; margin-bottom: 20px; }
.dt-sum-row {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  gap: 10px;
  font-size: 12.5px;
  color: rgba(247,244,238,0.65);
}
.dt-sum-row span:last-child { color: #F7F4EE; font-weight: 600; }
.dt-sum-disc span:last-child { color: #4ade80; }
.dt-sum-ship { color: #C8A46A !important; }
.dt-sum-grand {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  gap: 10px;
  font-size: 14px;
  font-weight: 700;
  color: #F7F4EE;
  margin-top: 8px;
  padding-top: 13px;
  border-top: 1px solid rgba(200,164,106,0.2);
}
.dt-sum-grand span:last-child { font-size: 1.18rem; color: #C8A46A; }
.dt-sum-saving {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 10.5px;
  color: #4ade80;
  background: rgba(74,222,128,0.07);
  border: 1px solid rgba(74,222,128,0.22);
  padding: 7px 11px;
  margin-top: 6px;
  font-weight: 600;
}

/* Checkout CTA */
.dt-sum-checkout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 16px 20px;
  background: linear-gradient(110deg,#b08d55 0%,#d8ba82 38%,#C8A46A 55%,#d8ba82 78%,#b08d55 100%);
  background-size: 200% auto;
  color: #000 !important;
  font-size: 11px;
  font-weight: 900;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  text-decoration: none;
  margin-bottom: 16px;
  box-shadow: 0 8px 28px rgba(200,164,106,0.22);
  transition: background-position .4s ease, box-shadow .3s ease, transform .18s ease;
  position: relative;
  overflow: hidden;
}
.dt-sum-checkout-btn::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to right,transparent,rgba(255,255,255,0.22),transparent);
  transform: translateX(-120%) skewX(-18deg);
  transition: transform .6s ease;
}
.dt-sum-checkout-btn:hover {
  background-position: right center;
  box-shadow: 0 12px 34px rgba(200,164,106,0.34);
  transform: translateY(-1px);
}
.dt-sum-checkout-btn:hover::after { transform: translateX(200%) skewX(-18deg); }

/* Trust strip */
.dt-sum-trust {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 7px;
  padding: 12px;
  background: rgba(255,255,255,0.018);
  border: 1px solid rgba(200,164,106,0.1);
  margin-bottom: 14px;
}
.dt-st-item {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 9.5px;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  color: rgba(247,244,238,0.48);
}
.dt-st-sep { color: rgba(200,164,106,0.32); font-size: 8px; }

/* Payment */
.dt-sum-pay {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 7px;
}
.dt-pay-lbl {
  font-size: 9px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgba(247,244,238,0.28);
}
.dt-pay-icons { display: flex; gap: 5px; }
.dt-pay-chip {
  font-size: 8.5px;
  font-weight: 800;
  letter-spacing: 0.05em;
  padding: 3px 6px;
  border: 1px solid rgba(200,164,106,0.2);
  color: rgba(200,164,106,0.55);
  background: rgba(200,164,106,0.04);
}

/* ── WC default table — kill it ── */
body.woocommerce-cart table.cart,
body.woocommerce-cart .shop_table.cart,
body.woocommerce-cart .cart-collaterals,
body.woocommerce-cart .wc-proceed-to-checkout { display: none !important; }

/* ── Responsive ── */
@media (max-width: 1280px) {
  .dt-cart-layout { grid-template-columns: minmax(0,1fr) 360px; }
  .dt-cart-thead  { grid-template-columns: 84px 1fr 108px 128px 108px 42px; }
  .dt-cart-row    { grid-template-columns: 84px 1fr 108px 128px 108px 42px; }
}
@media (max-width: 1080px) {
  .dt-cart-layout { grid-template-columns: minmax(0,1fr) 320px; }
  .dt-cart-thead  { grid-template-columns: 76px 1fr 96px 116px 96px 38px; }
  .dt-cart-row    { grid-template-columns: 76px 1fr 96px 116px 96px 38px; }
}
@media (max-width: 900px) {
  .dt-cart-layout { grid-template-columns: 1fr; }
  .dt-cart-aside  { position: static; }
  .dt-cart-summary-box { max-width: 560px; }
}
@media (max-width: 767px) {
  .dt-cart-page { margin-top: -64px !important; }
  .dt-cart-body { padding-left: 14px; padding-right: 14px; }
  .dt-cart-header-inner { padding-left: 14px; padding-right: 14px; }
  .dt-cart-thead { display: none; }
  .dt-cart-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 14px;
    align-items: flex-start;
  }
  .dt-cc-img  { flex-shrink: 0; }
  .dt-cc-info { flex: 1; min-width: 0; }
  .dt-cc-price.dt-dsk, .dt-cc-sub.dt-dsk { display: none; }
  .dt-cc-qty  { order: 4; width: 100%; padding-left: 88px; }
  .dt-cc-rm   { order: 3; margin-left: auto; align-self: flex-start; }
  .dt-mobile-prices {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    font-size: 11.5px;
  }
  .dt-mob-unit { color: rgba(247,244,238,0.55); }
  .dt-mob-x    { color: rgba(247,244,238,0.28); font-size: 10px; }
  .dt-mob-sub  { color: #C8A46A; font-weight: 700; }
  .dt-cart-product-img { width: 68px !important; height: 88px !important; }
  .dt-cart-actions { flex-direction: column; }
  .dt-coupon-area { min-width: 0; max-width: none; width: 100%; }
  .dt-cart-steps { display: none; }
}
@media (max-width: 480px) {
  .dt-cart-title { font-size: 1.75rem; }
}
</style>

<script>
function dtQty(btn, dir) {
    var wrap  = btn.closest('.dt-qty-stepper');
    var input = wrap && wrap.querySelector('.dt-qs-input');
    if (!input) return;
    var next = Math.max(0, (parseInt(input.value) || 1) + dir);
    input.value = next;
    if (next === 0) {
        var key = btn.closest('.dt-cart-row') && btn.closest('.dt-cart-row').dataset.key;
        if (key) {
            var rb = document.querySelector('.dt-rm-btn[data-cart_item_key="' + key + '"]');
            if (rb) { window.location.href = rb.href; return; }
        }
    }
    clearTimeout(window._dtQtyTimer);
    window._dtQtyTimer = setTimeout(function() {
        var f = btn.closest('form');
        if (f) { var u = f.querySelector('[name="update_cart"]'); if (u) u.click(); }
    }, 900);
}
</script>
