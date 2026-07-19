<?php
/**
 * WooCommerce My Account — View Order
 * Template override: woocommerce/myaccount/view-order.php
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 10.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Safety check
$order = isset( $order ) ? $order : null;
if ( ! $order ) {
    return;
}

$order_id      = $order->get_id();
$order_status  = $order->get_status();
$order_date    = wc_format_datetime( $order->get_date_created() );
$notes         = $order->get_customer_order_notes();

// Status classes
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
$back_url     = wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) );
?>

<style>
/* ─── View Order Premium Styles ────────────────────────────────────── */
.dt-view-back {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
    text-transform: uppercase; color: #888; text-decoration: none;
    margin-bottom: 24px; font-family: 'Inter', sans-serif; transition: color 0.2s;
}
.dt-view-back:hover { color: #C8A46A; }
.dt-view-back svg { width: 14px; height: 14px; }

.dt-view-header {
    display: flex; justify-content: space-between; align-items: center;
    border-bottom: 1px solid rgba(200,164,106,0.12);
    padding-bottom: 20px; margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
}
.dt-view-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px; font-weight: 600; color: #fff;
    margin: 0 0 4px; letter-spacing: 0.04em;
}
.dt-view-meta {
    font-size: 10px; font-weight: 600; color: #555;
    text-transform: uppercase; letter-spacing: 0.14em;
    font-family: 'Inter', sans-serif; margin: 0;
}
.dt-view-meta span { color: #C8A46A; }

.dt-view-status {
    font-size: 9px; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; padding: 4px 12px; border-radius: 2px;
    font-family: 'Inter', sans-serif; border: 1px solid transparent;
}

/* Order Details Card */
.dt-view-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.15);
    padding: 28px; position: relative; margin-bottom: 32px;
}
.dt-view-card::before, .dt-view-card::after {
    content: ''; position: absolute; width: 14px; height: 14px;
    border-color: rgba(200,164,106,0.22); border-style: solid;
}
.dt-view-card::before { top: 8px; left: 8px; border-width: 1px 0 0 1px; }
.dt-view-card::after  { bottom: 8px; right: 8px; border-width: 0 1px 1px 0; }

/* Table design */
.dt-view-table {
    width: 100%; border-collapse: collapse; margin-top: 10px;
    font-family: 'Inter', sans-serif; font-size: 13px; color: #D4CFC8;
}
.dt-view-table th {
    text-align: left; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.12em;
    color: #888; border-bottom: 1px solid rgba(200,164,106,0.18);
    padding: 12px 14px;
}
.dt-view-table td {
    padding: 16px 14px; border-bottom: 1px solid rgba(255,255,255,0.04);
}
.dt-view-table tr:last-child td { border-bottom: none; }

.dt-view-item-flex { display: flex; align-items: center; gap: 16px; }
.dt-view-item-thumb {
    width: 48px; height: 60px; object-fit: cover;
    border: 1px solid rgba(200,164,106,0.15); flex-shrink: 0;
}
.dt-view-item-name { color: #fff; text-decoration: none; font-weight: 500; }
.dt-view-item-name:hover { color: #C8A46A; }
.dt-view-item-meta { font-size: 11px; color: #666; margin-top: 4px; }
.dt-view-item-meta ul { list-style: none; padding: 0; margin: 0; }

.dt-view-subtotal-row td {
    padding: 10px 14px; border-bottom: 1px solid rgba(255,255,255,0.02);
}
.dt-view-subtotal-label { text-align: right; color: #888; font-weight: 500; }
.dt-view-subtotal-val { font-weight: 600; color: #fff; }
.dt-view-total-label { text-align: right; color: #C8A46A; font-weight: 600; font-size: 14px; }
.dt-view-total-val { color: #C8A46A; font-weight: 700; font-size: 16px; font-family: 'Cormorant Garamond', serif; }

/* Updates / Notes Section */
.dt-view-notes { margin-bottom: 32px; }
.dt-view-section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px; font-weight: 600; color: #fff;
    margin-bottom: 18px; letter-spacing: 0.04em;
    display: flex; align-items: center; gap: 8px;
}
.dt-view-section-title svg { color: #C8A46A; }

.dt-note-list { display: flex; flex-direction: column; gap: 14px; }
.dt-note-item {
    background: rgba(255,255,255,0.01);
    border: 1px solid rgba(255,255,255,0.04);
    padding: 16px 20px; border-radius: 2px;
    position: relative;
}
.dt-note-content { font-size: 13px; color: #D4CFC8; line-height: 1.6; margin-bottom: 6px; font-family: 'Inter', sans-serif; }
.dt-note-date { font-size: 10px; color: #555; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.08em; }

/* Address Grid */
.dt-view-addr-grid {
    display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 10px;
}
@media (min-width: 640px) { .dt-view-addr-grid { grid-template-columns: 1fr 1fr; } }
.dt-view-addr-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.12);
    padding: 24px; position: relative;
}
.dt-view-addr-card::before {
    content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%;
    background: rgba(200,164,106,0.3);
}
.dt-view-addr-h4 {
    font-size: 11px; font-weight: 700; color: #C8A46A;
    text-transform: uppercase; letter-spacing: 0.12em;
    font-family: 'Inter', sans-serif; margin: 0 0 14px;
}
.dt-view-addr-body {
    font-style: normal; font-size: 13px; line-height: 1.8;
    color: #D4CFC8; font-family: 'Inter', sans-serif;
}
.dt-view-addr-body p { margin: 0 0 2px; }
</style>

<!-- Back button -->
<a href="<?php echo esc_url( $back_url ); ?>" class="dt-view-back">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
    <?php esc_html_e( 'Back to Orders', 'dt-ecommerce-theme' ); ?>
</a>

<!-- Header Row -->
<div class="dt-view-header">
    <div>
        <h2 class="dt-view-title"><?php esc_html_e( 'Order Details', 'dt-ecommerce-theme' ); ?></h2>
        <p class="dt-view-meta">
            Order <span>#<?php echo esc_html( $order->get_order_number() ); ?></span> was placed on <span><?php echo esc_html( $order_date ); ?></span>
        </p>
    </div>
    <span class="dt-view-status <?php echo esc_attr( $status_class ); ?>">
        <?php echo esc_html( $status_label ); ?>
    </span>
</div>

<!-- Customer Order Updates (Notes) -->
<?php if ( ! empty( $notes ) ) : ?>
<div class="dt-view-notes">
    <h3 class="dt-view-section-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        <?php esc_html_e( 'Order Updates', 'dt-ecommerce-theme' ); ?>
    </h3>
    <div class="dt-note-list">
        <?php foreach ( $notes as $note ) : ?>
            <div class="dt-note-item">
                <div class="dt-note-content"><?php echo wp_kses_post( $note->comment_content ); ?></div>
                <div class="dt-note-date"><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $note->comment_date ) ) ); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Order Details Card -->
<div class="dt-view-card">
    <h3 class="dt-view-section-title" style="margin-bottom:12px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <?php esc_html_e( 'Items Purchased', 'dt-ecommerce-theme' ); ?>
    </h3>

    <div style="overflow-x:auto;">
        <table class="dt-view-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Product', 'dt-ecommerce-theme' ); ?></th>
                    <th style="text-align:right;"><?php esc_html_e( 'Total', 'dt-ecommerce-theme' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $order->get_items() as $item_id => $item ) :
                    $_product = $item->get_product();
                    if ( ! $_product ) {
                        continue;
                    }
                    $img_url = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
                    if ( ! $img_url ) {
                        $img_url = wc_placeholder_img_src();
                    }
                    $is_visible = $_product && $_product->is_visible();
                    $product_permalink = $is_visible ? $_product->get_permalink() : '';
                ?>
                    <tr>
                        <td>
                            <div class="dt-view-item-flex">
                                <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $item->get_name() ); ?>" class="dt-view-item-thumb">
                                <div>
                                    <?php if ( $is_visible ) : ?>
                                        <a href="<?php echo esc_url( $product_permalink ); ?>" class="dt-view-item-name">
                                            <?php echo esc_html( $item->get_name() ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="dt-view-item-name"><?php echo esc_html( $item->get_name() ); ?></span>
                                    <?php endif; ?>
                                    <strong class="product-quantity">×&nbsp;<?php echo esc_html( $item->get_quantity() ); ?></strong>
                                    
                                    <?php
                                    // Meta info
                                    $item_meta = new WC_Order_Item_Product( $item_id );
                                    if ( method_exists( $item_meta, 'get_formatted_meta_data' ) ) {
                                        $meta_data = $item_meta->get_formatted_meta_data();
                                        if ( ! empty( $meta_data ) ) {
                                            echo '<div class="dt-view-item-meta"><ul>';
                                            foreach ( $meta_data as $meta ) {
                                                echo '<li><strong>' . wp_kses_post( $meta->display_key ) . ':</strong> ' . wp_kses_post( $meta->display_value ) . '</li>';
                                            }
                                            echo '</ul></div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:right; font-weight:600; color:#fff;">
                            <?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Totals rows -->
                <?php foreach ( $order->get_order_item_totals() as $key => $total ) :
                    $is_total = ( 'order_total' === $key );
                    $label_class = $is_total ? 'dt-view-total-label' : 'dt-view-subtotal-label';
                    $val_class   = $is_total ? 'dt-view-total-val' : 'dt-view-subtotal-val';
                ?>
                    <tr class="dt-view-subtotal-row">
                        <td class="<?php echo esc_attr( $label_class ); ?>"><?php echo esc_html( $total['label'] ); ?></td>
                        <td style="text-align:right;" class="<?php echo esc_attr( $val_class ); ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Billing / Shipping Addresses -->
<div class="dt-view-addr-grid">

    <!-- Billing Address -->
    <div class="dt-view-addr-card">
        <h4 class="dt-view-addr-h4"><?php esc_html_e( 'Billing Address', 'dt-ecommerce-theme' ); ?></h4>
        <address class="dt-view-addr-body">
            <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
            <?php if ( $order->get_billing_phone() ) : ?>
                <p style="margin-top:10px;"><strong style="color:#C8A46A;"><?php esc_html_e( 'Phone:', 'dt-ecommerce-theme' ); ?></strong> <?php echo esc_html( $order->get_billing_phone() ); ?></p>
            <?php endif; ?>
            <?php if ( $order->get_billing_email() ) : ?>
                <p><strong style="color:#C8A46A;"><?php esc_html_e( 'Email:', 'dt-ecommerce-theme' ); ?></strong> <?php echo esc_html( $order->get_billing_email() ); ?></p>
            <?php endif; ?>
        </address>
    </div>

    <!-- Shipping Address -->
    <?php if ( $order->get_formatted_shipping_address() ) : ?>
    <div class="dt-view-addr-card">
        <h4 class="dt-view-addr-h4"><?php esc_html_e( 'Shipping Address', 'dt-ecommerce-theme' ); ?></h4>
        <address class="dt-view-addr-body">
            <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
            <?php if ( method_exists( $order, 'get_shipping_phone' ) && $order->get_shipping_phone() ) : ?>
                <p style="margin-top:10px;"><strong style="color:#C8A46A;"><?php esc_html_e( 'Phone:', 'dt-ecommerce-theme' ); ?></strong> <?php echo esc_html( $order->get_shipping_phone() ); ?></p>
            <?php endif; ?>
        </address>
    </div>
    <?php endif; ?>

</div>
