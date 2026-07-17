<?php
/**
 * Popup System — Newsletter, Offer, Coupon, Exit Intent
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Store newsletter signups submitted by footer and popup forms.
 */
function dt_handle_newsletter_subscribe(): void {
    check_ajax_referer( 'dt_newsletter', 'nonce' );

    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'dt-ecommerce-theme' ) ) );
    }

    $subscribers = get_option( 'dt_newsletter_subscribers', array() );
    if ( ! is_array( $subscribers ) ) {
        $subscribers = array();
    }

    $subscribers[ $email ] = array(
        'email'      => $email,
        'created_at' => current_time( 'mysql' ),
    );

    update_option( 'dt_newsletter_subscribers', $subscribers, false );
    wp_send_json_success( array( 'message' => __( 'Subscribed successfully.', 'dt-ecommerce-theme' ) ) );
}
add_action( 'wp_ajax_dt_newsletter_subscribe', 'dt_handle_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_dt_newsletter_subscribe', 'dt_handle_newsletter_subscribe' );

/**
 * Render all popups HTML into the footer.
 */
function dt_render_popups(): void {
    // Check options
    $newsletter_enabled  = dt_get_theme_option( 'popup_newsletter_enabled', '1' ) === '1';
    $offer_enabled       = dt_get_theme_option( 'popup_offer_enabled', '1' ) === '1';
    $exit_enabled        = dt_get_theme_option( 'popup_exit_enabled', '1' ) === '1';
    $newsletter_delay    = (int) dt_get_theme_option( 'popup_newsletter_delay', '5' );
    $offer_delay         = (int) dt_get_theme_option( 'popup_offer_delay', '8' );
    $newsletter_title    = dt_get_theme_option( 'popup_title', __( 'Join The Heritage Circle', 'dt-ecommerce-theme' ) );
    $newsletter_desc     = dt_get_theme_option( 'popup_desc', __( 'Subscribe and be the first to receive exclusive collections, weave stories, and member-only offers.', 'dt-ecommerce-theme' ) );
    $offer_code          = dt_get_theme_option( 'popup_offer_code', 'ARSHMAN10' );
    $offer_text          = dt_get_theme_option( 'popup_offer_text', 'Get 10% OFF your first order!' );
    $exit_title          = dt_get_theme_option( 'popup_exit_title', __( 'Wait - Don\'t Leave Yet!', 'dt-ecommerce-theme' ) );
    $exit_code           = dt_get_theme_option( 'popup_exit_code', '' );
    ?>

    <!-- ===== NEWSLETTER POPUP ===== -->
    <?php if ( $newsletter_enabled ) : ?>
    <div id="dt-newsletter-popup" class="fixed inset-0 z-[200] bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4" style="display:none;">
        <div class="relative bg-[#0a0a0a] border border-[#C8A46A]/40 w-full max-w-lg shadow-2xl p-8 md:p-10 animate-scale-in">
            <!-- Close -->
            <button onclick="dtClosePopup('newsletter')" class="absolute top-4 right-4 text-[#a3a3a3] hover:text-[#C8A46A] transition-colors" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="text-center">
                <span class="text-[#C8A46A] uppercase tracking-[0.3em] text-[10px] block mb-3">Exclusive Access</span>
                <h2 class="font-serif text-3xl md:text-4xl text-white mb-3"><?php echo esc_html( $newsletter_title ); ?></h2>
                <p class="text-[#a3a3a3] text-sm font-light mb-6 leading-relaxed">
                    <?php echo esc_html( $newsletter_desc ); ?>
                </p>

                <form id="dt-newsletter-form" onsubmit="dtHandleNewsletterSubmit(event)" class="flex flex-col sm:flex-row gap-3">
                    <input
                        type="email"
                        id="dt-newsletter-email"
                        placeholder="<?php esc_attr_e( 'your@email.com', 'dt-ecommerce-theme' ); ?>"
                        required
                        class="flex-1 bg-[#111] border border-[#C8A46A]/30 focus:border-[#C8A46A] text-white px-4 py-3 outline-none text-sm font-light placeholder:text-[#444] transition-colors"
                    />
                    <button type="submit" class="bg-[#C8A46A] text-black px-6 py-3 uppercase tracking-widest text-xs font-bold hover:bg-[#b08d55] transition-colors whitespace-nowrap">
                        <?php esc_html_e( 'Subscribe', 'dt-ecommerce-theme' ); ?>
                    </button>
                </form>

                <p class="text-[10px] text-[#555] mt-4 tracking-wide"><?php esc_html_e( 'No spam. Unsubscribe at any time.', 'dt-ecommerce-theme' ); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== OFFER / COUPON POPUP ===== -->
    <?php if ( $offer_enabled ) : ?>
    <div id="dt-offer-popup" class="fixed bottom-6 right-6 z-[190] max-w-xs w-full hidden" style="display:none;">
        <div class="bg-[#0a0a0a] border border-[#C8A46A]/40 shadow-2xl p-5 relative animate-slide-in-right">
            <button onclick="dtClosePopup('offer')" class="absolute top-3 right-3 text-[#666] hover:text-[#C8A46A]" aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex items-start gap-3">
                <div class="text-[#C8A46A] mt-0.5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm mb-1"><?php echo esc_html( $offer_text ); ?></p>
                    <p class="text-[#a3a3a3] text-xs mb-3"><?php esc_html_e( 'Use code at checkout:', 'dt-ecommerce-theme' ); ?></p>
                    <div class="flex items-center gap-2">
                        <code class="bg-[#111] border border-[#C8A46A]/30 text-[#C8A46A] px-3 py-1.5 text-sm font-bold tracking-widest flex-1 text-center">
                            <?php echo esc_html( $offer_code ); ?>
                        </code>
                        <button onclick="dtCopyCoupon('<?php echo esc_js( $offer_code ); ?>')" class="bg-[#C8A46A] text-black px-3 py-1.5 text-[10px] uppercase tracking-widest font-bold hover:bg-[#b08d55] transition-colors whitespace-nowrap">
                            <?php esc_html_e( 'Copy', 'dt-ecommerce-theme' ); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== EXIT INTENT POPUP ===== -->
    <?php if ( $exit_enabled ) : ?>
    <div id="dt-exit-popup" class="fixed inset-0 z-[200] bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4" style="display:none;">
        <div class="relative bg-[#0a0a0a] border border-[#C8A46A]/40 w-full max-w-md shadow-2xl p-8 text-center animate-scale-in">
            <button onclick="dtClosePopup('exit')" class="absolute top-4 right-4 text-[#a3a3a3] hover:text-[#C8A46A]" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="text-[#C8A46A] mb-4">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h2 class="font-serif text-2xl md:text-3xl text-white mb-3"><?php echo esc_html( $exit_title ); ?></h2>
            <p class="text-[#a3a3a3] text-sm font-light mb-6 leading-relaxed">
                <?php esc_html_e( 'You have beautiful sarees waiting in your wishlist. Complete your order today and enjoy free shipping.', 'dt-ecommerce-theme' ); ?>
            </p>
            <?php if ( ! empty( $exit_code ) ) : ?>
                <div class="bg-[#111] border border-[#C8A46A]/30 text-[#C8A46A] px-4 py-2 text-sm font-bold tracking-widest mb-5 inline-block">
                    <?php echo esc_html( $exit_code ); ?>
                </div>
            <?php endif; ?>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>"
                   class="bg-[#C8A46A] text-black px-6 py-3 uppercase tracking-widest text-xs font-bold hover:bg-[#b08d55] transition-colors">
                    <?php esc_html_e( 'Continue Shopping', 'dt-ecommerce-theme' ); ?>
                </a>
                <button onclick="dtClosePopup('exit')" class="border border-[#333] text-[#666] px-6 py-3 uppercase tracking-widest text-xs hover:border-[#C8A46A]/30 transition-colors">
                    <?php esc_html_e( 'No Thanks', 'dt-ecommerce-theme' ); ?>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== POPUP JAVASCRIPT ===== -->
    <script>
    (function() {
        const NEWSLETTER_DELAY = <?php echo (int) $newsletter_delay * 1000; ?>;
        const OFFER_DELAY      = <?php echo (int) $offer_delay * 1000; ?>;
        const NEWSLETTER_ON    = <?php echo $newsletter_enabled ? 'true' : 'false'; ?>;
        const OFFER_ON         = <?php echo $offer_enabled ? 'true' : 'false'; ?>;
        const EXIT_ON          = <?php echo $exit_enabled ? 'true' : 'false'; ?>;

        function dtShowPopup(id) {
            const el = document.getElementById('dt-' + id + '-popup');
            if (!el) return;
            el.style.display = 'flex';
            el.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.dtClosePopup = function(id) {
            const el = document.getElementById('dt-' + id + '-popup');
            if (!el) return;
            el.style.display = 'none';
            el.classList.add('hidden');
            document.body.style.overflow = '';
            sessionStorage.setItem('dt_popup_' + id + '_closed', '1');
        };

        window.dtCopyCoupon = function(code) {
            navigator.clipboard.writeText(code).then(() => {
                const btn = document.querySelector('#dt-offer-popup button[onclick*="dtCopyCoupon"]');
                if (btn) { btn.textContent = '✓ Copied!'; setTimeout(() => { btn.textContent = 'Copy'; }, 2000); }
            });
        };

        window.dtHandleNewsletterSubmit = function(e) {
            e.preventDefault();
            const emailEl = document.getElementById('dt-newsletter-email');
            const email = emailEl ? emailEl.value : '';
            if (email) {
                fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=dt_newsletter_subscribe&nonce=<?php echo esc_js( wp_create_nonce( 'dt_newsletter' ) ); ?>&email=' + encodeURIComponent(email)
                }).finally(function() {
                    if (emailEl) emailEl.value = '';
                    dtClosePopup('newsletter');
                    sessionStorage.setItem('dt_newsletter_subscribed', '1');
                });
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Newsletter popup (time-based)
            if (NEWSLETTER_ON && !sessionStorage.getItem('dt_popup_newsletter_closed') && !sessionStorage.getItem('dt_newsletter_subscribed')) {
                setTimeout(function() { dtShowPopup('newsletter'); }, NEWSLETTER_DELAY);
            }

            // Offer popup (time-based, separate timer)
            if (OFFER_ON && !sessionStorage.getItem('dt_popup_offer_closed')) {
                setTimeout(function() {
                    const newsEl = document.getElementById('dt-newsletter-popup');
                    const newsVisible = newsEl && newsEl.style.display !== 'none';
                    if (!newsVisible) dtShowPopup('offer');
                }, OFFER_DELAY);
            }

            // Exit intent popup (on mouse leave top of page)
            if (EXIT_ON && !sessionStorage.getItem('dt_popup_exit_closed')) {
                let exitTriggered = false;
                document.addEventListener('mouseleave', function(e) {
                    if (e.clientY < 10 && !exitTriggered) {
                        exitTriggered = true;
                        dtShowPopup('exit');
                    }
                });

                // Mobile: back button or page hide
                window.addEventListener('pagehide', function() {
                    if (!exitTriggered && !sessionStorage.getItem('dt_popup_exit_closed')) {
                        exitTriggered = true;
                        dtShowPopup('exit');
                    }
                });
            }
        });
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'dt_render_popups', 100 );

// ================================================================
//  AUTH MODAL AJAX HANDLERS
// ================================================================

/**
 * AJAX Login Handler
 * action: dt_ajax_login
 */
function dt_ajax_login(): void {
    check_ajax_referer( 'dt_auth_login', 'nonce' );

    $log = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
    $pwd = isset( $_POST['pwd'] ) ? wp_unslash( $_POST['pwd'] ) : '';

    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter your email/phone and password.', 'dt-ecommerce-theme' ) ) );
    }

    // Allow login with phone number stored as meta
    $user = null;
    if ( ! is_email( $log ) && is_numeric( str_replace( array( '+', ' ', '-' ), '', $log ) ) ) {
        // Try phone number lookup
        $users_by_phone = get_users( array(
            'meta_key'   => 'billing_phone',
            'meta_value' => $log,
            'number'     => 1,
        ) );
        if ( ! empty( $users_by_phone ) ) {
            $log = $users_by_phone[0]->user_login;
        }
    }

    $credentials = array(
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => true,
    );

    $user = wp_signon( $credentials, is_ssl() );

    if ( is_wp_error( $user ) ) {
        $message = $user->get_error_message();
        // Friendlier error messages
        if ( strpos( $message, 'incorrect' ) !== false || strpos( $message, 'password' ) !== false ) {
            $message = __( 'Incorrect password. Please try again.', 'dt-ecommerce-theme' );
        } elseif ( strpos( $message, 'Invalid username' ) !== false || strpos( $message, 'registered' ) !== false ) {
            $message = __( 'No account found with that email or phone.', 'dt-ecommerce-theme' );
        }
        wp_send_json_error( array( 'message' => $message ) );
    }

    $account_url = class_exists( 'WooCommerce' ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : home_url( '/my-account/' );
    wp_send_json_success( array(
        'message'  => __( 'Welcome back! Redirecting...', 'dt-ecommerce-theme' ),
        'redirect' => $account_url,
    ) );
}
add_action( 'wp_ajax_nopriv_dt_ajax_login', 'dt_ajax_login' );

/**
 * AJAX Register Handler
 * action: dt_ajax_register
 */
function dt_ajax_register(): void {
    check_ajax_referer( 'dt_auth_register', 'nonce' );

    $name  = isset( $_POST['reg_name'] )  ? sanitize_text_field( wp_unslash( $_POST['reg_name'] ) )  : '';
    $phone = isset( $_POST['reg_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['reg_phone'] ) ) : '';
    $email = isset( $_POST['email'] )     ? sanitize_email( wp_unslash( $_POST['email'] ) )           : '';
    $pass  = isset( $_POST['password'] )  ? wp_unslash( $_POST['password'] )                          : '';
    $role  = isset( $_POST['dt_user_role'] ) ? sanitize_key( wp_unslash( $_POST['dt_user_role'] ) )  : 'dt_customer';

    // Validate
    if ( empty( $name ) || empty( $phone ) || empty( $email ) || empty( $pass ) ) {
        wp_send_json_error( array( 'message' => __( 'All fields are required.', 'dt-ecommerce-theme' ) ) );
    }
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'dt-ecommerce-theme' ) ) );
    }
    if ( strlen( $pass ) < 6 ) {
        wp_send_json_error( array( 'message' => __( 'Password must be at least 6 characters.', 'dt-ecommerce-theme' ) ) );
    }
    if ( email_exists( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'An account with this email already exists. Please sign in.', 'dt-ecommerce-theme' ) ) );
    }

    $allowed_roles = array( 'dt_customer', 'dt_reseller', 'dt_retailer', 'dt_wholesaler' );
    if ( ! in_array( $role, $allowed_roles, true ) ) {
        $role = 'dt_customer';
    }

    // Create user
    $username = sanitize_user( strtolower( str_replace( ' ', '.', $name ) ) . rand( 10, 99 ) );
    if ( username_exists( $username ) ) {
        $username = sanitize_user( strtolower( str_replace( ' ', '', $name ) ) . rand( 100, 999 ) );
    }

    $user_id = wp_create_user( $username, $pass, $email );
    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
    }

    // Set name, role, phone
    $name_parts = explode( ' ', $name );
    $last_name   = count( $name_parts ) > 1 ? end( $name_parts ) : '';
    wp_update_user( array(
        'ID'           => $user_id,
        'display_name' => $name,
        'first_name'   => $name_parts[0],
        'last_name'    => $last_name,
    ) );
    update_user_meta( $user_id, 'billing_phone', $phone );
    update_user_meta( $user_id, 'billing_first_name', $name_parts[0] );

    $user = new WP_User( $user_id );
    $user->set_role( $role );

    // Auto-login
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );
    do_action( 'wp_login', $username, $user );

    $account_url = class_exists( 'WooCommerce' ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : home_url( '/my-account/' );
    wp_send_json_success( array(
        'message'  => __( 'Account created! Welcome aboard.', 'dt-ecommerce-theme' ),
        'redirect' => $account_url,
    ) );
}
add_action( 'wp_ajax_nopriv_dt_ajax_register', 'dt_ajax_register' );

/**
 * AJAX Forgot Password Handler
 * action: dt_ajax_forgot_password
 */
function dt_ajax_forgot_password(): void {
    check_ajax_referer( 'dt_auth_forgot', 'nonce' );

    $email = isset( $_POST['forgot_email'] ) ? sanitize_email( wp_unslash( $_POST['forgot_email'] ) ) : '';

    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'dt-ecommerce-theme' ) ) );
    }

    $user = get_user_by( 'email', $email );
    if ( ! $user ) {
        // Don't reveal if email exists or not for security
        wp_send_json_success( array( 'message' => __( 'If this email is registered, a reset link has been sent.', 'dt-ecommerce-theme' ) ) );
    }

    // Generate reset key and send email
    $key = get_password_reset_key( $user );
    if ( is_wp_error( $key ) ) {
        wp_send_json_error( array( 'message' => __( 'Could not generate reset link. Please try again.', 'dt-ecommerce-theme' ) ) );
    }

    $reset_url = add_query_arg( array(
        'action' => 'rp',
        'key'    => $key,
        'login'  => rawurlencode( $user->user_login ),
    ), class_exists( 'WooCommerce' ) ? wc_get_account_endpoint_url( 'lost-password' ) : network_site_url( 'wp-login.php', 'login' ) );

    $subject = sprintf( __( '[%s] Password Reset Request', 'dt-ecommerce-theme' ), get_bloginfo( 'name' ) );
    $message = sprintf(
        __( "Hi %s,\n\nYou requested a password reset for your account.\n\nClick the link below to reset your password:\n\n%s\n\nThis link will expire in 24 hours.\n\nIf you did not request this, please ignore this email.\n\n— %s", 'dt-ecommerce-theme' ),
        $user->display_name,
        $reset_url,
        get_bloginfo( 'name' )
    );

    $sent = wp_mail( $email, $subject, $message );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => __( 'Password reset link sent! Check your inbox.', 'dt-ecommerce-theme' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Could not send email. Please contact support.', 'dt-ecommerce-theme' ) ) );
    }
}
add_action( 'wp_ajax_nopriv_dt_ajax_forgot_password', 'dt_ajax_forgot_password' );
