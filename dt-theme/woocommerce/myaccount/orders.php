<?php
/**
 * WooCommerce My Account — Orders Tab
 * Template override: woocommerce/myaccount/orders.php
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Safety defaults — WooCommerce injects these via extract(), declare for IDE
/** @var array $orders */
$raw_orders = isset( $orders ) ? $orders : null;
/** @var bool $has_orders */
$raw_has_orders = isset( $has_orders ) ? $has_orders : null;
/** @var int $current_page */
$current_page  = isset( $current_page ) ? (int) $current_page : 1;
/** @var int $max_num_pages */
$max_num_pages = isset( $max_num_pages ) ? (int) $max_num_pages : 1;

// Direct DB fallback if WooCommerce variables are empty or null (e.g. HPOS or variable bugs)
if ( empty( $raw_orders ) && function_exists( 'wc_get_orders' ) ) {
    /** @var stdClass $paginated_orders */
    $paginated_orders = wc_get_orders( array(
        'customer' => get_current_user_id(),
        'limit'    => 15,
        'page'     => $current_page,
        'paginate' => true,
    ) );
    if ( ! empty( $paginated_orders ) && isset( $paginated_orders->orders ) ) {
        $raw_orders     = $paginated_orders->orders;
        $max_num_pages  = $paginated_orders->max_num_pages;
        $raw_has_orders = true;
    }
}

// Safety normalization
$orders     = ( is_array( $raw_orders ) || is_object( $raw_orders ) ) ? $raw_orders : array();
$has_orders = ! empty( $orders );

$order_count = 0;
if ( $has_orders ) {
    if ( is_array( $orders ) || $orders instanceof Countable ) {
        $order_count = count( $orders );
    } elseif ( $orders instanceof WP_Query ) {
        $order_count = $orders->post_count;
    } else {
        $order_count = 1;
    }
}
?>

<style>
/* ─── Premium My Orders Layout ─────────────────────────────────────── */
.dt-orders-title-row {
    display: flex; justify-content: space-between; align-items: center;
    border-bottom: 1px solid rgba(200,164,106,0.12);
    padding-bottom: 20px; margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
}
.dt-orders-h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px; font-weight: 600; color: #fff;
    margin: 0 0 4px; letter-spacing: 0.04em;
}
.dt-orders-sub {
    font-size: 10px; font-weight: 600; color: #555;
    text-transform: uppercase; letter-spacing: 0.14em;
    font-family: 'Inter', sans-serif; margin: 0;
}
.dt-orders-sub span { color: #C8A46A; }

.dt-orders-shop-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; font-size: 10px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    background: linear-gradient(135deg, #b08d55, #C8A46A, #d8ba82);
    color: #000; text-decoration: none; border-radius: 2px;
    font-family: 'Inter', sans-serif; transition: filter 0.2s, transform 0.15s;
}
.dt-orders-shop-btn:hover { filter: brightness(1.1); color: #000; }
.dt-orders-shop-btn:active { transform: scale(0.98); }
.dt-orders-shop-btn svg { width: 13px; height: 13px; }

/* Order Cards */
.dt-orders-list { display: flex; flex-direction: column; gap: 20px; }
.dt-order-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.15);
    padding: 24px; position: relative;
    transition: border-color 0.25s, box-shadow 0.25s;
}
.dt-order-card:hover {
    border-color: rgba(200,164,106,0.35);
    box-shadow: 0 8px 32px rgba(200,164,106,0.05);
}
.dt-order-card::before, .dt-order-card::after {
    content: ''; position: absolute; width: 12px; height: 12px;
    border-color: rgba(200,164,106,0.22); border-style: solid;
}
.dt-order-card::before { top: 6px; left: 6px; border-width: 1px 0 0 1px; }
.dt-order-card::after  { bottom: 6px; right: 6px; border-width: 0 1px 1px 0; }

/* Header row */
.dt-order-card-header {
    display: flex; justify-content: space-between; align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    padding-bottom: 14px; margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
}
.dt-order-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.dt-order-number {
    font-size: 13px; font-weight: 700; color: #C8A46A;
    font-family: 'Inter', sans-serif; letter-spacing: 0.05em;
}
.dt-order-date {
    font-size: 11px; color: #666; font-family: 'Inter', sans-serif;
}
.dt-order-status {
    font-size: 9px; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; padding: 3px 10px; border-radius: 2px;
    font-family: 'Inter', sans-serif; border: 1px solid transparent;
}
/* Status Colors */
.dt-status-completed  { background: rgba(16,185,129,0.06); border-color: rgba(16,185,129,0.2); color: #34d399; }
.dt-status-processing { background: rgba(59,130,246,0.06); border-color: rgba(59,130,246,0.2); color: #60a5fa; }
.dt-status-pending    { background: rgba(245,158,11,0.06); border-color: rgba(245,158,11,0.2); color: #fbbf24; }
.dt-status-on-hold    { background: rgba(249,115,22,0.06); border-color: rgba(249,115,22,0.2); color: #ff9d43; }
.dt-status-cancelled,
.dt-status-failed     { background: rgba(239,68,68,0.06); border-color: rgba(239,68,68,0.2); color: #fca5a5; }
.dt-status-default    { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.1); color: #aaa; }

.dt-order-total {
    font-family: 'Cormorant Garamond', serif;
    font-size: 19px; font-weight: 600; color: #fff;
}

/* Products Row */
.dt-order-products {
    display: flex; gap: 12px; overflow-x: auto; padding-bottom: 6px;
    margin-bottom: 16px; scrollbar-width: thin;
    scrollbar-color: rgba(200,164,106,0.15) transparent;
}
.dt-order-products::-webkit-scrollbar { height: 4px; }
.dt-order-products::-webkit-scrollbar-thumb { background: rgba(200,164,106,0.15); border-radius: 2px; }

.dt-order-thumb {
    width: 54px; height: 70px; object-fit: cover;
    border: 1px solid rgba(200,164,106,0.15);
    background: rgba(255,255,255,0.02); flex-shrink: 0;
}
.dt-order-details {
    font-size: 12px; color: #888; font-family: 'Inter', sans-serif;
    line-height: 1.5; margin: 0 0 16px;
}
.dt-order-details span { color: #D4CFC8; }

/* Buttons row */
.dt-order-actions {
    display: flex; gap: 10px; flex-wrap: wrap;
    border-top: 1px solid rgba(255,255,255,0.04);
    padding-top: 16px;
}
.dt-order-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; font-size: 10px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'Inter', sans-serif; text-decoration: none;
    border-radius: 2px; transition: all 0.25s;
}
.dt-btn-primary {
    background: rgba(200,164,106,0.08);
    border: 1px solid rgba(200,164,106,0.3);
    color: #C8A46A;
}
.dt-btn-primary:hover {
    background: rgba(200,164,106,0.18);
    border-color: rgba(200,164,106,0.5);
    color: #d8ba82;
}
.dt-btn-secondary {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.08);
    color: #aaa;
}
.dt-btn-secondary:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.18);
    color: #fff;
}
.dt-order-btn svg { width: 12px; height: 12px; }

/* Empty state */
.dt-orders-empty {
    text-align: center; padding: 60px 20px;
    background: rgba(255,255,255,0.01);
    border: 1px dashed rgba(200,164,106,0.15);
    position: relative;
}
.dt-orders-empty::before {
    content: ''; position: absolute; inset: 4px;
    border: 1px dashed rgba(200,164,106,0.06); pointer-events: none;
}
.dt-orders-empty-icon {
    width: 54px; height: 54px; border-radius: 50%;
    background: rgba(200,164,106,0.05);
    border: 1px solid rgba(200,164,106,0.18);
    display: flex; align-items: center; justify-content: center;
    color: #C8A46A; margin: 0 auto 20px;
}
.dt-orders-empty-h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px; font-weight: 600; color: #fff;
    margin: 0 0 8px; letter-spacing: 0.03em;
}
.dt-orders-empty-p {
    font-size: 13px; color: #555; font-family: 'Inter', sans-serif;
    max-width: 320px; margin: 0 auto 24px; line-height: 1.6;
}

/* Pagination */
.dt-orders-pagination {
    display: flex; gap: 6px; justify-content: center; margin-top: 32px;
}
.dt-page-num {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    border: 1px solid rgba(200,164,106,0.18); background: rgba(255,255,255,0.01);
    color: #888; font-size: 12px; font-family: 'Inter', sans-serif;
    text-decoration: none; font-weight: 600; transition: all 0.25s;
    border-radius: 2px;
}
.dt-page-num:hover {
    border-color: rgba(200,164,106,0.4); color: #fff;
}
.dt-page-num.active {
    color: #000; background: linear-gradient(135deg, #b08d55, #C8A46A, #d8ba82);
    border-color: transparent;
}
</style>

<div class="dt-orders-title-row">
    <div>
        <h2 class="dt-orders-h2"><?php esc_html_e( 'My Orders', 'dt-ecommerce-theme' ); ?></h2>
        <p class="dt-orders-sub">
            <?php
            printf(
                /* translators: %d: order count */
                esc_html( _n( 'Showing %d order', 'Showing %d orders', $order_count, 'dt-ecommerce-theme' ) ),
                esc_html( $order_count )
            );
            ?>
        </p>
    </div>
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="dt-orders-shop-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <?php esc_html_e( 'Shop Now', 'dt-ecommerce-theme' ); ?>
    </a>
</div>

<?php if ( $has_orders ) : ?>

    <div class="dt-orders-list">
        <?php
        foreach ( $orders as $customer_order ) :
            $order = $customer_order instanceof WC_Order ? $customer_order : wc_get_order( $customer_order );
            if ( ! $order ) {
                continue;
            }

            $order_id     = $order->get_id();
            $order_date   = wc_format_datetime( $order->get_date_created() );
            $order_status = $order->get_status();
            $order_total  = $order->get_formatted_order_total();
            $items        = $order->get_items();
            $item_count   = $order->get_item_count();

            // Match statuses
            $status_class = 'dt-status-default';
            if ( in_array( $order_status, array( 'completed' ), true ) ) {
                $status_class = 'dt-status-completed';
            } elseif ( in_array( $order_status, array( 'processing' ), true ) ) {
                $status_class = 'dt-status-processing';
            } elseif ( in_array( $order_status, array( 'pending' ), true ) ) {
                $status_class = 'dt-status-pending';
            } elseif ( in_array( $order_status, array( 'on-hold' ), true ) ) {
                $status_class = 'dt-status-on-hold';
            } elseif ( in_array( $order_status, array( 'cancelled', 'failed' ), true ) ) {
                $status_class = 'dt-status-cancelled';
            }
            $status_label = wc_get_order_status_name( $order_status );
        ?>
            <div class="dt-order-card">
                
                <!-- Card Header -->
                <div class="dt-order-card-header">
                    <div class="dt-order-meta">
                        <span class="dt-order-number">#<?php echo esc_html( $order->get_order_number() ); ?></span>
                        <span class="dt-order-date"><?php echo esc_html( $order_date ); ?></span>
                        <span class="dt-order-status <?php echo esc_attr( $status_class ); ?>">
                            <?php echo esc_html( $status_label ); ?>
                        </span>
                    </div>
                    <div class="dt-order-total"><?php echo wp_kses_post( $order_total ); ?></div>
                </div>

                <!-- Products list images -->
                <div class="dt-order-products">
                    <?php
                    foreach ( $items as $item_id => $item ) :
                        $_product = $item->get_product();
                        if ( ! $_product ) {
                            continue;
                        }
                        $img_url = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
                        if ( ! $img_url ) {
                            $img_url = wc_placeholder_img_src();
                        }
                    ?>
                        <img src="<?php echo esc_url( $img_url ); ?>" 
                             alt="<?php echo esc_attr( $item->get_name() ); ?>" 
                             title="<?php echo esc_attr( $item->get_name() ); ?>"
                             class="dt-order-thumb">
                    <?php endforeach; ?>
                </div>

                <!-- Products Summary Text -->
                <p class="dt-order-details">
                    <span>Products:</span> 
                    <?php
                    $names = array();
                    foreach ( $items as $item ) {
                        $names[] = $item->get_name() . ' (x' . $item->get_quantity() . ')';
                    }
                    echo esc_html( implode( ', ', $names ) );
                    ?>
                </p>

                <!-- Actions row -->
                <div class="dt-order-actions">
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="dt-order-btn dt-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <?php esc_html_e( 'View Order', 'dt-ecommerce-theme' ); ?>
                    </a>

                    <?php if ( $order->needs_payment() ) : ?>
                        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="dt-order-btn dt-btn-primary" style="background:#C8A46A;color:#000;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                            <?php esc_html_e( 'Pay Now', 'dt-ecommerce-theme' ); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $order->has_status( 'completed' ) && method_exists( $order, 'get_reorder_url' ) ) : ?>
                        <a href="<?php echo esc_url( $order->get_reorder_url() ); ?>" class="dt-order-btn dt-btn-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                            </svg>
                            <?php esc_html_e( 'Reorder', 'dt-ecommerce-theme' ); ?>
                        </a>
                    <?php endif; ?>

                    <!-- Custom Track Order templates redirect integration -->
                    <a href="<?php echo esc_url( home_url( '/track-order/?order_id=' . $order_id ) ); ?>" class="dt-order-btn dt-btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                        </svg>
                        <?php esc_html_e( 'Track Status', 'dt-ecommerce-theme' ); ?>
                    </a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ( 1 < $max_num_pages ) : ?>
        <div class="dt-orders-pagination">
            <?php for ( $i = 1; $i <= $max_num_pages; $i++ ) : ?>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $i ) ); ?>"
                   class="dt-page-num <?php echo $current_page === $i ? 'active' : ''; ?>">
                    <?php echo esc_html( $i ); ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

<?php else : ?>

    <!-- Empty state -->
    <div class="dt-orders-empty">
        <div class="dt-orders-empty-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
        </div>
        <h3 class="dt-orders-empty-h3"><?php esc_html_e( 'No orders yet', 'dt-ecommerce-theme' ); ?></h3>
        <p class="dt-orders-empty-p"><?php esc_html_e( 'You have not placed any orders yet. Explore our luxury collection to make your first purchase.', 'dt-ecommerce-theme' ); ?></p>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="dt-orders-shop-btn">
            <?php esc_html_e( 'Start Shopping', 'dt-ecommerce-theme' ); ?>
        </a>
    </div>

<?php endif; ?>
