<?php
/**
 * Edit account form
 *
 * Override template: woocommerce/myaccount/form-edit-account.php
 *
 * @package DT_Ecommerce_Theme
 * @version 10.5.0
 */

defined( 'ABSPATH' ) || exit;

$user = isset( $user ) ? $user : wp_get_current_user();

do_action( 'woocommerce_before_edit_account_form' );
?>

<style>
/* ─── My Account Details Form — Premium Styled ──────────────────────────── */
.dt-account-wrap {
    font-family: 'Inter', sans-serif;
}
.dt-account-h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px; font-weight: 600; color: #fff;
    margin: 0 0 4px; letter-spacing: 0.04em;
}
.dt-account-sub { font-size: 12px; color: #555; margin: 0 0 22px; }

/* Form card styling */
.dt-addr-card {
    background: rgba(255,255,255,0.02) !important;
    border: 1px solid rgba(200,164,106,0.18) !important;
    padding: 28px !important; position: relative !important;
}
.dt-addr-card::before, .dt-addr-card::after {
    content: '' !important; position: absolute !important; width: 14px !important; height: 14px !important;
    border-color: rgba(200,164,106,0.28) !important; border-style: solid !important;
}
.dt-addr-card::before { top: 8px !important; left: 8px !important; border-width: 1px 0 0 1px !important; }
.dt-addr-card::after  { bottom: 8px !important; right: 8px !important; border-width: 0 1px 1px 0 !important; }

/* Custom fieldset/legend styling */
.dt-addr-card fieldset {
    border: 1px solid rgba(200, 164, 106, 0.15) !important;
    padding: 24px !important;
    margin: 28px 0 20px 0 !important;
    border-radius: 2px !important;
}
.dt-addr-card legend {
    font-family: 'Cormorant Garamond', serif !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #C8A46A !important;
    padding: 0 12px !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase !important;
}

/* Labels styling */
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
    margin-left: 2px !important;
}

/* Text inputs styling */
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
    -webkit-appearance: none !important; appearance: none !important;
}
.dt-addr-card .form-row input.input-text:focus {
    border-color: rgba(200,164,106,0.7) !important;
    box-shadow: 0 0 0 3px rgba(200,164,106,0.07) !important;
    background: rgba(200,164,106,0.02) !important;
}
.dt-addr-card .form-row input.input-text::placeholder { color: #444 !important; }

/* Info description styling */
#account_display_name_description em {
    display: block;
    font-size: 11px;
    color: #666;
    margin-top: 6px;
    font-style: normal;
    line-height: 1.4;
}

/* Save button wrapper and style */
.dt-account-save {
    clear: both; padding-top: 12px; overflow: hidden;
}
.dt-account-save button[type="submit"] {
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
.dt-account-save button[type="submit"]::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
    transform: translateX(-100%); transition: transform 0.45s;
}
.dt-account-save button[type="submit"]:hover::after { transform: translateX(100%); }
.dt-account-save button[type="submit"]:hover { filter: brightness(1.1) !important; }
.dt-account-save button[type="submit"]:active { transform: scale(0.98) !important; }
.dt-account-save button[type="submit"] svg { width: 14px; height: 14px; flex-shrink: 0; }
</style>

<div class="dt-account-wrap dt-addr-wrap">
    <h2 class="dt-account-h2"><?php esc_html_e( 'Account Details', 'woocommerce' ); ?></h2>
    <p class="dt-account-sub"><?php esc_html_e( 'Manage your personal details and change your password.', 'woocommerce' ); ?></p>

    <div class="dt-addr-card">
        <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

            <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" /> 
                <span id="account_display_name_description"><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
            </p>

            <?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

            <fieldset>
                <legend><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" />
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" />
                </p>
            </fieldset>
            <div class="clear"></div>

            <?php do_action( 'woocommerce_edit_account_form' ); ?>

            <div class="dt-account-save">
                <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
                <button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <?php esc_html_e( 'Save changes', 'woocommerce' ); ?>
                </button>
                <input type="hidden" name="action" value="save_account_details" />
            </div>

            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
