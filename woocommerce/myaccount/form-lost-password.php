<?php
/**
 * Lost password form
 *
 * Override template: woocommerce/myaccount/form-lost-password.php
 *
 * @package DT_Ecommerce_Theme
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );

$logo_url  = dt_get_theme_option( 'logo_url' );
$site_name = get_bloginfo( 'name' );
?>

<style>
/* ─── Lost Password Auth Box — Premium Tabbed Layout ──────────────────────── */
.dt-auth-page-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: 45vh;
    margin-top: 10px;
    margin-bottom: 30px;
    box-sizing: border-box;
}

.dt-page-auth-box {
    background: linear-gradient(160deg, #0d0d0d 0%, #111 60%, #0a0a0a 100%) !important;
    border: 1px solid rgba(200,164,106,0.28) !important;
    box-shadow: 0 0 80px rgba(200,164,106,0.12), 0 40px 80px rgba(0,0,0,0.8) !important;
    width: 100% !important;
    max-width: 440px !important;
    position: relative !important;
    border-radius: 2px !important;
    padding: 0 !important;
    box-sizing: border-box !important;
}

/* Decorative corners */
.dt-auth-corner {
    position: absolute;
    width: 14px;
    height: 14px;
    border-color: rgba(200,164,106,0.28);
    border-style: solid;
    pointer-events: none;
}
.dt-auth-corner.tl { top: 8px; left: 8px; border-width: 1px 0 0 1px; }
.dt-auth-corner.tr { top: 8px; right: 8px; border-width: 1px 1px 0 0; }
.dt-auth-corner.bl { bottom: 8px; left: 8px; border-width: 0 0 1px 1px; }
.dt-auth-corner.br { bottom: 8px; right: 8px; border-width: 0 1px 1px 0; }

/* Header & Brand */
.dt-auth-header {
    padding: 28px 28px 0;
    text-align: center;
    position: relative;
}
.dt-auth-logo {
    max-height: 52px !important;
    height: 52px !important;
    width: auto !important;
    max-width: 200px !important;
    object-fit: contain;
    margin: 0 auto 8px;
    display: block;
}
.dt-auth-logo-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    font-weight: 600;
    color: #C8A46A;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    display: block;
    margin-bottom: 2px;
}
.dt-auth-divider-line {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px 0 0;
}
.dt-auth-divider-line::before, .dt-auth-divider-line::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(200,164,106,0.3), transparent);
}
.dt-auth-diamond {
    width: 5px; height: 5px;
    background: #C8A46A;
    transform: rotate(45deg);
    flex-shrink: 0;
}

/* Tabs */
.dt-auth-tabs {
    display: flex;
    border-bottom: 1px solid rgba(200,164,106,0.12);
    margin: 20px 0 0;
}
.dt-auth-tab {
    flex: 1;
    padding: 11px 8px;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #555;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: all 0.25s;
    background: none;
    border: none;
    font-family: 'Inter', sans-serif;
}
.dt-auth-tab.active { color: #C8A46A; border-bottom-color: #C8A46A; }

/* Panels */
.dt-auth-panel { padding: 24px 28px 28px; text-align: left; }

/* Headings */
.dt-panel-heading {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px 0 !important;
}
.dt-panel-sub {
    font-size: 11px;
    color: #666;
    margin: 0 0 20px 0 !important;
    letter-spacing: 0.02em;
}

/* Form Fields */
.dt-field { margin-bottom: 14px; text-align: left; }
.dt-label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 6px;
    font-family: 'Inter', sans-serif;
}
.dt-input-wrap { position: relative; }
.dt-input {
    width: 100%;
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    color: #F7F4EE !important;
    padding: 11px 14px 11px 38px !important;
    font-size: 13px !important;
    font-family: 'Inter', sans-serif !important;
    outline: none !important;
    border-radius: 2px !important;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s !important;
    box-sizing: border-box !important;
}
.dt-input:focus {
    border-color: rgba(200,164,106,0.7) !important;
    background: rgba(200,164,106,0.03) !important;
    box-shadow: 0 0 0 3px rgba(200,164,106,0.08) !important;
}
.dt-input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #555;
    pointer-events: none;
    transition: color 0.2s;
}
.dt-input-wrap:focus-within .dt-input-icon { color: #C8A46A; }

/* Gold Button */
.dt-btn-gold {
    width: 100% !important;
    padding: 13px 20px !important;
    background: linear-gradient(135deg, #b08d55 0%, #C8A46A 50%, #d8ba82 100%) !important;
    color: #000 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.18em !important;
    text-transform: uppercase !important;
    border: none !important;
    cursor: pointer !important;
    border-radius: 2px !important;
    transition: filter 0.2s, transform 0.15s !important;
    font-family: 'Inter', sans-serif !important;
    margin-top: 18px !important;
    position: relative !important;
    overflow: hidden !important;
}
.dt-btn-gold::after {
    content: '' !important;
    position: absolute !important;
    inset: 0 !important;
    background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.25) 50%, transparent 70%) !important;
    transform: translateX(-100%) !important;
    transition: transform 0.5s !important;
}
.dt-btn-gold:hover::after { transform: translateX(100%) !important; }
.dt-btn-gold:hover { filter: brightness(1.1) !important; }
.dt-btn-gold:active { transform: scale(0.98) !important; }

/* Footer Links */
.dt-auth-footer-links {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    font-size: 11px;
}
.dt-auth-link {
    background: none !important;
    border: none !important;
    color: #888 !important;
    cursor: pointer !important;
    padding: 0 !important;
    font-family: 'Inter', sans-serif !important;
    font-size: 11px !important;
    transition: color 0.2s !important;
}
.dt-auth-link:hover { color: #C8A46A !important; text-decoration: underline !important; }
</style>

<div class="dt-auth-page-container">
    <div class="dt-page-auth-box">
        <!-- Decorative corners -->
        <span class="dt-auth-corner tl"></span>
        <span class="dt-auth-corner tr"></span>
        <span class="dt-auth-corner bl"></span>
        <span class="dt-auth-corner br"></span>

        <!-- Header -->
        <div class="dt-auth-header">
            <!-- Logo -->
            <?php if ( $logo_url ) : ?>
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>" class="dt-auth-logo">
            <?php else : ?>
                <span class="dt-auth-logo-text"><?php echo esc_html( $site_name ); ?></span>
            <?php endif; ?>

            <div class="dt-auth-divider-line"><span class="dt-auth-diamond"></span></div>

            <!-- Tabs -->
            <div class="dt-auth-tabs">
                <button class="dt-auth-tab active" type="button">Reset Password</button>
            </div>
        </div>

        <!-- ═══ FORGOT PASSWORD PANEL ═══ -->
        <div class="dt-auth-panel">
            <p class="dt-panel-heading">Reset Password</p>
            <p class="dt-panel-sub">Enter your email or username and we'll send you a reset link</p>
            
            <form method="post" class="woocommerce-ResetPassword lost_reset_password">
                <div class="dt-field">
                    <label class="dt-label" for="user_login"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?> *</label>
                    <div class="dt-input-wrap">
                        <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                        <input type="text" class="dt-input" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
                    </div>
                </div>

                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                <input type="hidden" name="wc_reset_password" value="true" />
                <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
                <button type="submit" class="dt-btn-gold" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Send Reset Link', 'woocommerce' ); ?>
                </button>

                <div class="dt-auth-footer-links">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="dt-auth-link">&larr; Back to Sign In</a>
                </div>
            </form>
        </div>

    </div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );
