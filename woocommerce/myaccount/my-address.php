<?php
/**
 * My Account — Addresses overview
 * Template override: woocommerce/myaccount/my-address.php
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Correct WooCommerce function name (not wc_get_account_addresses_types)
if ( ! wc_shipping_enabled() ) {
    $address_types = array( 'billing' );
} else {
    $address_types = wc_get_account_address_types();
}
?>

<style>
.dt-address-header { margin-bottom: 28px; }
.dt-address-header h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px; font-weight: 600; color: #fff;
    letter-spacing: 0.05em; margin-bottom: 6px;
}
.dt-address-header p { font-size: 12px; color: #666; }
.dt-address-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
@media (min-width: 640px) { .dt-address-grid { grid-template-columns: 1fr 1fr; } }
.dt-address-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.18);
    position: relative; padding: 24px;
    transition: border-color 0.25s, box-shadow 0.25s;
}
.dt-address-card:hover { border-color: rgba(200,164,106,0.38); box-shadow: 0 8px 32px rgba(200,164,106,0.06); }
.dt-address-card::before,
.dt-address-card::after {
    content: ''; position: absolute; width: 14px; height: 14px;
    border-color: rgba(200,164,106,0.35); border-style: solid;
}
.dt-address-card::before { top: 6px; left: 6px; border-width: 1px 0 0 1px; }
.dt-address-card::after  { bottom: 6px; right: 6px; border-width: 0 1px 1px 0; }
.dt-card-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px; padding-bottom: 14px;
    border-bottom: 1px solid rgba(200,164,106,0.1);
}
.dt-card-title { display: flex; align-items: center; gap: 10px; }
.dt-card-icon {
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(200,164,106,0.08); border: 1px solid rgba(200,164,106,0.2);
    display: flex; align-items: center; justify-content: center;
    color: #C8A46A; flex-shrink: 0;
}
.dt-card-label {
    font-size: 11px; font-weight: 700; letter-spacing: 0.14em;
    text-transform: uppercase; color: #C8A46A; font-family: 'Inter', sans-serif;
}
.dt-address-edit-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; font-size: 10px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase; color: #C8A46A;
    border: 1px solid rgba(200,164,106,0.3); background: rgba(200,164,106,0.05);
    text-decoration: none; transition: all 0.2s; font-family: 'Inter', sans-serif; border-radius: 2px;
}
.dt-address-edit-btn:hover { background: rgba(200,164,106,0.12); border-color: rgba(200,164,106,0.5); color: #d8ba82; }
.dt-address-edit-btn svg { width: 12px; height: 12px; flex-shrink: 0; }
.dt-address-body address {
    font-style: normal; font-size: 13px; line-height: 1.8;
    color: #D4CFC8; font-family: 'Inter', sans-serif;
}
.dt-address-body address p { margin: 0 0 2px; }
.dt-address-empty {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 12px; padding: 28px 16px; text-align: center;
}
.dt-address-empty-icon {
    width: 48px; height: 48px; border-radius: 50%;
    background: rgba(200,164,106,0.06); border: 1px dashed rgba(200,164,106,0.25);
    display: flex; align-items: center; justify-content: center; color: rgba(200,164,106,0.5);
}
.dt-address-empty p { font-size: 12px; color: #555; font-family: 'Inter', sans-serif; line-height: 1.5; max-width: 180px; }
.dt-address-add-btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 20px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    background: linear-gradient(135deg, #b08d55, #C8A46A, #d8ba82); color: #000;
    text-decoration: none; border-radius: 2px;
    transition: filter 0.2s; font-family: 'Inter', sans-serif;
}
.dt-address-add-btn:hover { filter: brightness(1.08); color: #000; }
.dt-address-add-btn svg { width: 13px; height: 13px; flex-shrink: 0; }
</style>

<div class="dt-address-header">
    <h2><?php esc_html_e( 'My Addresses', 'dt-ecommerce-theme' ); ?></h2>
    <p><?php esc_html_e( 'The following addresses will be used on the checkout page by default.', 'dt-ecommerce-theme' ); ?></p>
</div>

<div class="dt-address-grid">
    <?php foreach ( $address_types as $type ) :
        $address    = wc_get_account_customer_address( $type );
        $name_map   = array(
            'billing'  => __( 'Billing Address', 'dt-ecommerce-theme' ),
            'shipping' => __( 'Shipping Address', 'dt-ecommerce-theme' ),
        );
        $card_title = isset( $name_map[ $type ] ) ? $name_map[ $type ] : ucfirst( $type ) . ' ' . __( 'Address', 'dt-ecommerce-theme' );
        $edit_url   = wc_get_endpoint_url( 'edit-address', $type, wc_get_page_permalink( 'myaccount' ) );
        $is_billing = ( 'billing' === $type );
        $icon_svg   = $is_billing
            ? '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>'
            : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>';
    ?>
    <div class="dt-address-card">
        <div class="dt-card-head">
            <div class="dt-card-title">
                <div class="dt-card-icon"><?php echo $icon_svg; ?></div>
                <span class="dt-card-label"><?php echo esc_html( $card_title ); ?></span>
            </div>
            <?php if ( $address ) : ?>
            <a href="<?php echo esc_url( $edit_url ); ?>" class="dt-address-edit-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <?php esc_html_e( 'Edit', 'dt-ecommerce-theme' ); ?>
            </a>
            <?php endif; ?>
        </div>

        <div class="dt-address-body">
            <?php if ( $address ) : ?>
                <address><?php echo wp_kses_post( $address ); ?></address>
            <?php else : ?>
                <div class="dt-address-empty">
                    <div class="dt-address-empty-icon"><?php echo $icon_svg; ?></div>
                    <p><?php printf( esc_html__( 'You have not set up a %s yet.', 'dt-ecommerce-theme' ), strtolower( $card_title ) ); ?></p>
                    <a href="<?php echo esc_url( $edit_url ); ?>" class="dt-address-add-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <?php printf( esc_html__( 'Add %s', 'dt-ecommerce-theme' ), $card_title ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
