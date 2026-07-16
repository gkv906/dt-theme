<?php
/**
 * Login Form
 *
 * Override template: woocommerce/myaccount/form-login.php
 *
 * @package DT_Ecommerce_Theme
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' );

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$logo_url  = dt_get_theme_option( 'logo_url' );
$site_name = get_bloginfo( 'name' );
$active_panel = 'login';

if ( isset( $_POST['register'] ) ) {
    $active_panel = 'register';
} elseif ( isset( $_POST['wc_reset_password'] ) || isset( $_GET['show-reset-form'] ) || isset( $_GET['reset-link-sent'] ) ) {
    $active_panel = 'forgot';
}

if ( 'register' === $active_panel && ! $registration_enabled ) {
    $active_panel = 'login';
}

$lost_password_url = wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) );
?>

<style>
/* ─── My Account Auth Box — Premium Tabbed Layout ──────────────────────── */
.dt-auth-page-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: 55vh;
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
.dt-auth-tab:hover { color: #a3a3a3; }
.dt-auth-tab.active { color: #C8A46A; border-bottom-color: #C8A46A; }

/* Panels */
.dt-auth-panel { display: none; padding: 24px 28px 28px; text-align: left; }
.dt-auth-panel.active { display: block; animation: dtFadeUp 0.3s ease; }
@keyframes dtFadeUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

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
.dt-eye-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #555;
    cursor: pointer;
    padding: 2px;
    line-height: 1;
    transition: color 0.2s;
}
.dt-eye-btn:hover { color: #C8A46A; }
.dt-input.has-icon-right { padding-right: 38px !important; }

/* Select fields */
.dt-select {
    width: 100%;
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(200,164,106,0.2) !important;
    color: #F7F4EE !important;
    padding: 11px 36px 11px 14px !important;
    font-size: 13px !important;
    font-family: 'Inter', sans-serif !important;
    outline: none !important;
    border-radius: 2px !important;
    transition: border-color 0.2s !important;
    cursor: pointer !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23C8A46A' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    box-sizing: border-box !important;
}
.dt-select:focus { border-color: rgba(200,164,106,0.7) !important; box-shadow: 0 0 0 3px rgba(200,164,106,0.08) !important; }
.dt-select option { background: #111 !important; color: #F7F4EE !important; }

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

/* Checkbox */
.woocommerce-form-login__rememberme {
    display: inline-flex !important; align-items: center !important; gap: 8px !important;
    font-size: 11px !important; color: #888 !important;
    cursor: pointer;
}
.woocommerce-form-login__rememberme input[type="checkbox"] { accent-color: #C8A46A !important; }
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
                <button class="dt-auth-tab <?php echo 'login' === $active_panel ? 'active' : ''; ?>" id="page-tab-login" data-auth-tab="login" onclick="dtPageSwitchTab('login')" type="button">Sign In</button>
                <?php if ( $registration_enabled ) : ?>
                    <button class="dt-auth-tab <?php echo 'register' === $active_panel ? 'active' : ''; ?>" id="page-tab-register" data-auth-tab="register" onclick="dtPageSwitchTab('register')" type="button">Register</button>
                <?php endif; ?>
                <button class="dt-auth-tab <?php echo 'forgot' === $active_panel ? 'active' : ''; ?>" id="page-tab-forgot" data-auth-tab="forgot" onclick="dtPageSwitchTab('forgot')" type="button">Forgot?</button>
            </div>
        </div>

        <!-- ═══ LOGIN PANEL ═══ -->
        <div class="dt-auth-panel <?php echo 'login' === $active_panel ? 'active' : ''; ?>" id="page-panel-login">
            <p class="dt-panel-heading">Welcome Back</p>
            <p class="dt-panel-sub">Sign in to your account to continue</p>
            
            <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <!-- Username or Email -->
                <div class="dt-field">
                    <label class="dt-label" for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?> *</label>
                    <div class="dt-input-wrap">
                        <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                        <input type="text" class="dt-input" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
                    </div>
                </div>

                <!-- Password -->
                <div class="dt-field">
                    <label class="dt-label" for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> *</label>
                    <div class="dt-input-wrap">
                        <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input class="dt-input has-icon-right" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
                        <button type="button" class="dt-eye-btn" onclick="dtPageToggleEye('password', this)" aria-label="Toggle password visibility">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                    </label>
                </div>

                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <button type="submit" class="dt-btn-gold" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Sign In to Account', 'woocommerce' ); ?>
                </button>

                <div class="dt-auth-footer-links">
                    <button type="button" class="dt-auth-link" data-auth-tab="forgot" onclick="dtPageSwitchTab('forgot')">Forgot password?</button>
                    <?php if ( $registration_enabled ) : ?>
                        <button type="button" class="dt-auth-link" data-auth-tab="register" onclick="dtPageSwitchTab('register')">New here? Register</button>
                    <?php endif; ?>
                </div>

                <?php do_action( 'woocommerce_login_form_end' ); ?>
            </form>
        </div>

        <!-- ═══ REGISTER PANEL ═══ -->
        <?php if ( $registration_enabled ) : ?>
        <div class="dt-auth-panel <?php echo 'register' === $active_panel ? 'active' : ''; ?>" id="page-panel-register">
            <p class="dt-panel-heading">Create Account</p>
            <p class="dt-panel-sub">Join us to unlock exclusive prices &amp; offers</p>
            
            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                    <div class="dt-field">
                        <label class="dt-label" for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?> *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                            <input type="text" class="dt-input" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
                        </div>
                    </div>
                <?php endif; ?>

                <div class="dt-field">
                    <label class="dt-label" for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> *</label>
                    <div class="dt-input-wrap">
                        <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                        <input type="email" class="dt-input" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" />
                    </div>
                </div>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                    <div class="dt-field">
                        <label class="dt-label" for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?> *</label>
                        <div class="dt-input-wrap">
                            <svg class="dt-input-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" class="dt-input has-icon-right" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                            <button type="button" class="dt-eye-btn" onclick="dtPageToggleEye('reg_password', this)" aria-label="Toggle password visibility">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                <?php else : ?>
                    <p style="color: #666; font-size: 11px; margin: 10px 0 18px; line-height:1.5; font-family:'Inter',sans-serif;"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>
                <?php endif; ?>

                <?php do_action( 'woocommerce_register_form' ); ?>

                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit" class="dt-btn-gold" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Create My Account', 'woocommerce' ); ?>
                </button>

                <div class="dt-auth-footer-links">
                    <span style="color:#555;font-size:11px;">Already have an account?</span>
                    <button type="button" class="dt-auth-link" data-auth-tab="login" onclick="dtPageSwitchTab('login')">Sign In</button>
                </div>

                <?php do_action( 'woocommerce_register_form_end' ); ?>
            </form>
        </div>
        <?php endif; ?>

        <!-- ═══ FORGOT PASSWORD PANEL ═══ -->
        <div class="dt-auth-panel <?php echo 'forgot' === $active_panel ? 'active' : ''; ?>" id="page-panel-forgot">
            <p class="dt-panel-heading">Reset Password</p>
            <p class="dt-panel-sub">Enter your email and we'll send you a reset link</p>
            
            <form method="post" class="woocommerce-ResetPassword lost_reset_password" action="<?php echo esc_url( $lost_password_url ); ?>">
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
                    <button type="button" class="dt-auth-link" data-auth-tab="login" onclick="dtPageSwitchTab('login')">&larr; Back to Sign In</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function dtPageSwitchTab(tabId) {
    const container = document.querySelector('.dt-auth-page-container');
    if (!container || !tabId) {
        return;
    }

    // Deactivate all page tabs
    container.querySelectorAll('.dt-auth-tab').forEach(function(btn) {
        btn.classList.remove('active');
    });
    // Hide all page panels
    container.querySelectorAll('.dt-auth-panel').forEach(function(panel) {
        panel.classList.remove('active');
    });
    
    // Activate selected page tab & panel
    const tabBtn = document.getElementById('page-tab-' + tabId);
    if (tabBtn) {
        tabBtn.classList.add('active');
    }
    const panelEl = document.getElementById('page-panel-' + tabId);
    if (panelEl) {
        panelEl.classList.add('active');
        const firstField = panelEl.querySelector('input:not([type="hidden"]), select, textarea');
        if (firstField) {
            window.setTimeout(function() {
                firstField.focus({ preventScroll: true });
            }, 50);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.dt-auth-page-container');
    if (!container) {
        return;
    }

    container.addEventListener('click', function(event) {
        const trigger = event.target.closest('[data-auth-tab]');
        if (!trigger) {
            return;
        }

        event.preventDefault();
        dtPageSwitchTab(trigger.getAttribute('data-auth-tab'));
    });

    const activePanel = container.querySelector('.dt-auth-panel.active');
    if (!activePanel) {
        dtPageSwitchTab('login');
    }
});

function dtPageToggleEye(fieldId, btn) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
    } else {
        input.type = 'password';
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    }
}
</script>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
