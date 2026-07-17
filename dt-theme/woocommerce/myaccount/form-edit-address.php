<?php
/**
 * My Account — Edit Address form
 * Template override: woocommerce/myaccount/form-edit-address.php
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 9.3.0
 *
 * Variables injected by WooCommerce via wc_get_template() / extract():
 * @var string $load_address   'billing' or 'shipping'
 * @var bool   $has_address    Whether the user already has this address saved
 * @var array  $address_fields Field definitions array (keyed by field name)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Safety defaults — WooCommerce injects these via extract(), declare for IDE
/** @var string $load_address */
$load_address   = isset( $load_address )   ? $load_address   : '';
/** @var bool $has_address */
$has_address    = isset( $has_address )    ? $has_address    : false;
/** @var array $address_fields */
$address_fields = isset( $address_fields ) ? $address_fields : array();

// Infer address type if load_address is empty
if ( empty( $load_address ) && ! empty( $address_fields ) ) {
    $inferred = 'billing';
    foreach ( array_keys( $address_fields ) as $k ) {
        if ( 0 === strpos( $k, 'shipping_' ) ) {
            $inferred = 'shipping';
            break;
        }
    }
    $load_address = $inferred;
}
if ( empty( $load_address ) ) {
    $load_address = 'billing';
}

// ── CRITICAL FIX: If WooCommerce passed empty fields (customer has no country),
//    regenerate them from the shop's base country so form always has fields.
if ( empty( $address_fields ) ) {
    $customer       = new WC_Customer( get_current_user_id() );
    $getter         = "get_{$load_address}_country";
    $saved_country  = method_exists( $customer, $getter ) ? $customer->$getter() : '';
    $base_country   = ! empty( $saved_country ) ? $saved_country : WC()->countries->get_base_country();
    $address_fields = WC()->countries->get_address_fields( $base_country, $load_address . '_' );

    // Populate 'value' key so woocommerce_form_field() pre-fills data
    foreach ( $address_fields as $key => $field ) {
        $field_key    = str_replace( $load_address . '_', '', $key );
        $value_method = "get_{$load_address}_{$field_key}";
        if ( method_exists( $customer, $value_method ) ) {
            $field['value'] = $customer->$value_method();
        }
        $address_fields[ $key ] = $field;
    }
} else {
    // WooCommerce 9.x already populates $field['value']; also add a fallback
    $customer = new WC_Customer( get_current_user_id() );
    foreach ( $address_fields as $key => $field ) {
        if ( ! array_key_exists( 'value', $field ) || '' === $field['value'] ) {
            $field_key    = str_replace( $load_address . '_', '', $key );
            $value_method = "get_{$load_address}_{$field_key}";
            if ( method_exists( $customer, $value_method ) ) {
                $field['value'] = $customer->$value_method();
            }
        }
        $address_fields[ $key ] = $field;
    }
}

$is_billing = ( 'billing' === $load_address );
$page_title = $is_billing ? __( 'Billing Address', 'dt-ecommerce-theme' ) : __( 'Shipping Address', 'dt-ecommerce-theme' );
$page_sub   = $is_billing
    ? __( 'Update your billing details used for invoices and payments.', 'dt-ecommerce-theme' )
    : __( 'Update your shipping details used for delivering orders.', 'dt-ecommerce-theme' );
$back_url   = wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) );
?>

<style>
/* ─── WooCommerce Address Form — Premium Styled ─────────────────────────── */
.dt-addr-back {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.08em;
    text-transform: uppercase; color: #888; text-decoration: none;
    margin-bottom: 24px; font-family: 'Inter', sans-serif; transition: color 0.2s;
}
.dt-addr-back:hover { color: #C8A46A; }
.dt-addr-back svg { width: 14px; height: 14px; }
.dt-addr-h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px; font-weight: 600; color: #fff;
    margin: 0 0 4px; letter-spacing: 0.04em;
}
.dt-addr-sub { font-size: 12px; color: #555; margin: 0 0 22px; font-family: 'Inter', sans-serif; }

/* Notices from WooCommerce */
.dt-addr-wrap .woocommerce-message,
.dt-addr-wrap .woocommerce-error,
.dt-addr-wrap .woocommerce-info {
    list-style: none !important;
    padding: 12px 16px !important;
    margin: 0 0 20px 0 !important;
    font-size: 13px !important;
    font-family: 'Inter', sans-serif !important;
    border-radius: 0 2px 2px 0 !important;
}
.dt-addr-wrap .woocommerce-message {
    background: rgba(200,164,106,0.06) !important;
    border-left: 3px solid #C8A46A !important;
    color: #D4CFC8 !important;
}
.dt-addr-wrap .woocommerce-error {
    background: rgba(239,68,68,0.06) !important;
    border-left: 3px solid #ef4444 !important;
    color: #fca5a5 !important;
}

/* Form card */
.dt-addr-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.18);
    padding: 28px; position: relative;
}
.dt-addr-card::before, .dt-addr-card::after {
    content: ''; position: absolute; width: 14px; height: 14px;
    border-color: rgba(200,164,106,0.28); border-style: solid;
}
.dt-addr-card::before { top: 8px; left: 8px; border-width: 1px 0 0 1px; }
.dt-addr-card::after  { bottom: 8px; right: 8px; border-width: 0 1px 1px 0; }

/* ── WooCommerce field wrapper layout ─────────────────── */
.dt-addr-card .woocommerce-address-fields__field-wrapper { overflow: hidden; }

/* All form rows */
.dt-addr-card .form-row {
    margin-bottom: 16px !important;
    padding: 0 !important;
}
.dt-addr-card .form-row-first {
    float: left !important;
    width: 48% !important;
    margin-right: 4% !important;
    clear: none !important;
}
.dt-addr-card .form-row-last {
    float: right !important;
    width: 48% !important;
    margin-right: 0 !important;
    clear: none !important;
}
.dt-addr-card .form-row-wide {
    width: 100% !important;
    clear: both !important;
}
.dt-addr-card .clear { clear: both !important; display: block !important; height: 14px !important; }
@media (max-width: 600px) {
    .dt-addr-card .form-row-first,
    .dt-addr-card .form-row-last {
        float: none !important; width: 100% !important; margin-right: 0 !important;
    }
}

/* Labels */
.dt-addr-card .form-row label {
    display: block !important;
    font-size: 10px !important; font-weight: 600 !important;
    letter-spacing: 0.12em !important; text-transform: uppercase !important;
    color: #888 !important; margin-bottom: 7px !important;
    font-family: 'Inter', sans-serif !important;
}
.dt-addr-card .form-row label .required,
.dt-addr-card .form-row label abbr {
    color: #C8A46A !important;
    text-decoration: none !important;
    margin-left: 2px;
}

/* Text inputs */
.dt-addr-card .form-row input.input-text {
    width: 100% !important; box-sizing: border-box !important;
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    border-radius: 2px !important;
    color: #F7F4EE !important;
    padding: 11px 14px !important;
    font-size: 13px !important; font-family: 'Inter', sans-serif !important;
    outline: none !important; box-shadow: none !important;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s !important;
    -webkit-appearance: none; appearance: none;
}
.dt-addr-card .form-row input.input-text:focus {
    border-color: rgba(200,164,106,0.7) !important;
    box-shadow: 0 0 0 3px rgba(200,164,106,0.07) !important;
    background: rgba(200,164,106,0.02) !important;
}
.dt-addr-card .form-row input.input-text::placeholder { color: #444 !important; }

/* Native select */
.dt-addr-card .form-row select {
    width: 100% !important; box-sizing: border-box !important;
    background: rgba(255,255,255,0.03) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23C8A46A' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 14px center !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    border-radius: 2px !important;
    color: #F7F4EE !important;
    padding: 11px 36px 11px 14px !important;
    font-size: 13px !important; font-family: 'Inter', sans-serif !important;
    outline: none !important; box-shadow: none !important; cursor: pointer;
    -webkit-appearance: none; appearance: none;
    transition: border-color 0.2s !important;
}
.dt-addr-card .form-row select:focus {
    border-color: rgba(200,164,106,0.7) !important;
    box-shadow: 0 0 0 3px rgba(200,164,106,0.07) !important;
}
.dt-addr-card .form-row select option { background: #111 !important; color: #F7F4EE !important; }

/* Select2 container override (Make global and ultra-specific to beat WooCommerce default styling) */
.select2-container--default.select2-container,
.select2-container {
    width: 100% !important;
}
.select2-container--default .select2-selection--single,
.select2-container .select2-selection--single {
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    border-radius: 2px !important;
    height: 42px !important;
    box-shadow: none !important;
    display: flex !important;
    align-items: center !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single .select2-selection__rendered {
    color: #F7F4EE !important;
    line-height: 40px !important;
    padding-left: 14px !important;
    padding-right: 36px !important;
    font-size: 13px !important;
    font-family: 'Inter', sans-serif !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow,
.select2-container .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b,
.select2-container .select2-selection--single .select2-selection__arrow b {
    border-color: rgba(200,164,106,0.6) transparent transparent !important;
    border-width: 5px 4px 0 4px !important;
}
.select2-container--default.select2-container--open .select2-selection--single,
.select2-container.select2-container--open .select2-selection--single {
    border-color: rgba(200,164,106,0.7) !important;
    box-shadow: 0 0 0 3px rgba(200,164,106,0.07) !important;
}

/* Select2 dropdown menu */
.select2-dropdown {
    background: #111 !important;
    border: 1px solid rgba(200,164,106,0.25) !important;
    border-radius: 2px !important;
    box-shadow: 0 16px 40px rgba(0,0,0,0.6) !important;
    z-index: 999999 !important;
}
.select2-search--dropdown {
    padding: 8px !important;
    border-bottom: 1px solid rgba(200,164,106,0.1) !important;
}
.select2-search--dropdown .select2-search__field {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    color: #F7F4EE !important;
    border-radius: 2px !important;
    padding: 8px 12px !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 13px !important;
    outline: none !important;
}
.select2-results__option {
    color: #D4CFC8 !important;
    padding: 9px 14px !important;
    font-size: 13px !important;
    font-family: 'Inter', sans-serif !important;
}
.select2-results__option--highlighted {
    background: rgba(200,164,106,0.1) !important;
    color: #C8A46A !important;
}
.select2-results__option[aria-selected="true"] {
    background: rgba(200,164,106,0.08) !important;
    color: #C8A46A !important;
}

/* Validation errors inline */
.dt-addr-card .form-row.woocommerce-invalid input,
.dt-addr-card .form-row.woocommerce-invalid select {
    border-color: rgba(239,68,68,0.6) !important;
}
.dt-addr-card .form-row.woocommerce-invalid .select2-selection {
    border-color: rgba(239,68,68,0.6) !important;
}

/* Switcher Tabs */
.dt-address-tabs {
    display: inline-flex;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.14);
    padding: 4px;
    margin-bottom: 24px;
    border-radius: 2px;
    gap: 4px;
}
.dt-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #888;
    text-decoration: none;
    transition: all 0.25s;
    font-family: 'Inter', sans-serif;
    border-radius: 2px;
}
.dt-tab-btn:hover {
    color: #F7F4EE;
    background: rgba(255,255,255,0.02);
}
.dt-tab-btn.active {
    color: #000;
    background: linear-gradient(135deg, #b08d55, #C8A46A, #d8ba82);
}
.dt-tab-btn.active svg {
    color: #000;
}
.dt-tab-btn svg {
    width: 13px;
    height: 13px;
    transition: color 0.25s;
}

/* Save button */
.dt-addr-save {
    clear: both; padding-top: 12px; overflow: hidden;
}
.dt-addr-save button[type="submit"] {
    display: inline-flex !important; align-items: center !important;
    justify-content: center !important; gap: 9px !important;
    padding: 13px 32px !important;
    font-size: 11px !important; font-weight: 700 !important;
    letter-spacing: 0.16em !important; text-transform: uppercase !important;
    background: linear-gradient(135deg, #b08d55, #C8A46A, #d8ba82) !important;
    color: #000 !important; border: none !important; border-radius: 2px !important;
    cursor: pointer !important; font-family: 'Inter', sans-serif !important;
    transition: filter 0.2s, transform 0.15s !important;
    position: relative; overflow: hidden;
}
.dt-addr-save button[type="submit"]::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
    transform: translateX(-100%); transition: transform 0.45s;
}
.dt-addr-save button[type="submit"]:hover::after { transform: translateX(100%); }
.dt-addr-save button[type="submit"]:hover { filter: brightness(1.1) !important; }
.dt-addr-save button[type="submit"]:active { transform: scale(0.98) !important; }
.dt-addr-save button[type="submit"] svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>

<div class="dt-addr-wrap">

    <!-- Back -->
    <a href="<?php echo esc_url( $back_url ); ?>" class="dt-addr-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        <?php esc_html_e( 'Back to Addresses', 'dt-ecommerce-theme' ); ?>
    </a>

    <h2 class="dt-addr-h2"><?php esc_html_e( 'Addresses', 'dt-ecommerce-theme' ); ?></h2>
    <p class="dt-addr-sub"><?php esc_html_e( 'Update your billing or shipping address details below.', 'dt-ecommerce-theme' ); ?></p>

    <!-- Address Selector Tabs -->
    <div class="dt-address-tabs">
        <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) ) ); ?>" 
           class="dt-tab-btn <?php echo 'billing' === $load_address ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <rect x="2" y="5" width="20" height="14" rx="2"/>
                <path d="M2 10h20"/>
            </svg>
            <?php esc_html_e( 'Billing Address', 'dt-ecommerce-theme' ); ?>
        </a>
        <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', wc_get_page_permalink( 'myaccount' ) ) ); ?>" 
           class="dt-tab-btn <?php echo 'shipping' === $load_address ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <?php esc_html_e( 'Shipping Address', 'dt-ecommerce-theme' ); ?>
        </a>
    </div>

    <?php
    // Show WooCommerce notices (success / validation errors)
    if ( function_exists( 'wc_print_notices' ) ) {
        wc_print_notices();
    }
    ?>

    <?php if ( ! $has_address ) : ?>
    <div style="background:rgba(200,164,106,0.06);border-left:3px solid #C8A46A;color:#D4CFC8;padding:12px 16px;font-size:13px;margin-bottom:20px;font-family:'Inter',sans-serif;border-radius:0 2px 2px 0;display:flex;align-items:center;gap:10px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?php esc_html_e( 'Please fill in your address details below.', 'dt-ecommerce-theme' ); ?>
    </div>
    <?php endif; ?>

    <div class="dt-addr-card">
        <?php do_action( 'woocommerce_before_edit_account_address_form' ); ?>

        <form method="post">

            <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

            <div class="woocommerce-address-fields">

                <?php do_action( "woocommerce_before_address_fields_{$load_address}" ); ?>

                <div class="woocommerce-address-fields__field-wrapper">
                    <?php
                    // Use WooCommerce's own form field renderer — ensures proper
                    // field names, JS hooks (country/state), and save compatibility.
                    foreach ( $address_fields as $key => $field ) {
                        $value = isset( $field['value'] ) ? $field['value'] : '';
                        $value = wc_get_post_data_by_key( $key, $value );
                        woocommerce_form_field( $key, $field, $value );
                    }
                    ?>
                </div>

                <?php do_action( "woocommerce_after_address_fields_{$load_address}" ); ?>

            </div>

            <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

            <!-- Custom reliable save handler fields (dt_save_customer_address in functions.php) -->
            <?php
            // Custom nonce — bypasses WooCommerce nonce/caching issues
            wp_nonce_field( 'dt_save_address_' . get_current_user_id(), 'dt_address_nonce' );
            // Also keep WooCommerce's nonce so the built-in handler can fire too
            wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' );
            ?>
            <!-- Custom trigger flag -->
            <input type="hidden" name="dt_save_address" value="1">
            <!-- WooCommerce built-in trigger -->
            <input type="hidden" name="action" value="edit_address">
            <!-- Address type fallback (when URL query var unavailable) -->
            <input type="hidden" name="dt_address_type" value="<?php echo esc_attr( $load_address ); ?>">

            <div class="dt-addr-save">
                <button type="submit" name="save_address"
                        value="<?php esc_attr_e( 'Save address', 'dt-ecommerce-theme' ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <?php esc_html_e( 'Save Address', 'dt-ecommerce-theme' ); ?>
                </button>
            </div>

        </form>

        <?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
    </div>

</div>
