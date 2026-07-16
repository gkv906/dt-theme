<?php
/**
 * Theme Activation System (Security PIN: 98989898)
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if theme is activated
function dt_is_theme_activated() {
    return get_option( 'dt_theme_activated', false ) === 'yes';
}

// Register admin activation menu
function dt_activation_menu() {
    add_menu_page(
        __( 'Theme Activation', 'dt-ecommerce-theme' ),
        __( 'Theme Activation', 'dt-ecommerce-theme' ),
        'manage_options',
        'dt-theme-activation',
        'dt_render_activation_page',
        'dashicons-shield-lock',
        2
    );
}
add_action( 'admin_menu', 'dt_activation_menu' );

// Render activation page
function dt_render_activation_page() {
    $error = '';
    $success = '';

    if ( isset( $_POST['dt_activation_nonce'] ) && wp_verify_nonce( $_POST['dt_activation_nonce'], 'dt_activate_theme_action' ) ) {
        $pin = isset( $_POST['dt_security_pin'] ) ? sanitize_text_field( wp_unslash( $_POST['dt_security_pin'] ) ) : '';
        if ( $pin === '98989898' ) {
            update_option( 'dt_theme_activated', 'yes' );
            $success = __( 'Theme activated successfully! Proceeding to setup wizard...', 'dt-ecommerce-theme' );
            
            // Trigger One Click Setup Wizard automatic setup
            if ( function_exists( 'dt_run_one_click_setup' ) ) {
                dt_run_one_click_setup();
            }
            
            echo '<script>window.location.href = "' . admin_url( 'admin.php?page=dt-theme-activation&activated=true' ) . '";</script>';
            exit;
        } else {
            $error = __( 'Invalid Security PIN. Access Locked.', 'dt-ecommerce-theme' );
        }
    }

    $activated = isset( $_GET['activated'] ) ? sanitize_key( wp_unslash( $_GET['activated'] ) ) : '';
    if ( 'true' === $activated ) {
        $success = __( 'Theme is fully activated and setup wizard completed!', 'dt-ecommerce-theme' );
    }

    $is_active = dt_is_theme_activated();
    ?>
    <div class="wrap" style="max-width: 600px; margin: 50px auto; padding: 30px; background: #0a0a0a; color: #F7F4EE; border: 1px solid #C8A46A; border-radius: 4px; box-shadow: 0 4px 20px rgba(0,0,0,0.5); font-family: 'Inter', sans-serif;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #C8A46A; font-family: 'Cormorant Garamond', serif; font-size: 2.5em; margin-bottom: 5px; font-weight: bold; letter-spacing: 2px;">DT ECOMMERCE</h1>
            <p style="text-transform: uppercase; font-size: 10px; letter-spacing: 4px; color: #a3a3a3; margin: 0;">Theme Activation Panel</p>
        </div>

        <?php if ( ! empty( $error ) ) : ?>
            <div style="background: rgba(220, 38, 38, 0.2); border: 1px solid rgb(220, 38, 38); color: #ff8888; padding: 12px; margin-bottom: 20px; border-radius: 3px; font-size: 14px;">
                <?php echo esc_html( $error ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $success ) ) : ?>
            <div style="background: rgba(200, 164, 106, 0.2); border: 1px solid #C8A46A; color: #C8A46A; padding: 12px; margin-bottom: 20px; border-radius: 3px; font-size: 14px;">
                <?php echo esc_html( $success ); ?>
            </div>
        <?php endif; ?>

        <?php if ( $is_active ) : ?>
            <div style="text-align: center; padding: 20px 0;">
                <span style="font-size: 4em; color: #C8A46A;">✓</span>
                <h2 style="color: #fff; margin-top: 15px; font-size: 1.8em; font-family: 'Cormorant Garamond', serif;">Your Theme is Activated & Verified</h2>
                <p style="color: #a3a3a3; font-size: 14px; font-weight: 300;">All enterprise features, WooCommerce templates, and homepage configurations are unlocked.</p>
                <a href="<?php echo esc_url( home_url() ); ?>" class="button" style="display: inline-block; margin-top: 20px; background: #C8A46A; color: #000; border: none; border-radius: 0; padding: 10px 24px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; font-size: 12px; text-decoration: none;">Visit Frontend</a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=dt-theme-options' ) ); ?>" class="button" style="display: inline-block; margin-top: 20px; margin-left: 10px; background: transparent; border: 1px solid #C8A46A; color: #C8A46A; padding: 9px 24px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; font-size: 12px; text-decoration: none;">Theme Options</a>
            </div>
        <?php else : ?>
            <form method="post" action="">
                <?php wp_nonce_field( 'dt_activate_theme_action', 'dt_activation_nonce' ); ?>
                <div style="margin-bottom: 20px;">
                    <label for="dt_security_pin" style="display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #a3a3a3; margin-bottom: 8px;">Enter Security PIN</label>
                    <input type="password" id="dt_security_pin" name="dt_security_pin" style="width: 100%; background: #111; border: 1px solid #C8A46A; color: #fff; padding: 12px; font-size: 16px; border-radius: 0; outline: none; box-sizing: border-box; text-align: center; letter-spacing: 4px;" required autocomplete="current-password">
                </div>
                <button type="submit" style="width: 100%; background: #C8A46A; color: #000; border: none; border-radius: 0; padding: 14px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; cursor: pointer; font-size: 13px; transition: all 0.3s;" onmouseover="this.style.background='#b08d55'" onmouseout="this.style.background='#C8A46A'">Activate Theme</button>
            </form>
            <p style="text-align: center; font-size: 11px; color: #666; margin-top: 25px; letter-spacing: 1px;">Requires verification pin from documentation.</p>
        <?php endif; ?>
    </div>
    <?php
}

// Redirect frontend requests to PIN screen if theme is not activated
function dt_lock_frontend_if_not_activated() {
    if ( dt_is_theme_activated() ) {
        return;
    }

    // Do not redirect ajax or REST API or script loading
    if ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    // Keep the WordPress Customizer preview usable for administrators.
    if ( is_customize_preview() && current_user_can( 'customize' ) ) {
        return;
    }

    // Check if submitting activation PIN
    if ( isset( $_POST['dt_activation_nonce'] ) && wp_verify_nonce( $_POST['dt_activation_nonce'], 'dt_activate_theme_action' ) ) {
        $pin = isset( $_POST['dt_security_pin'] ) ? sanitize_text_field( wp_unslash( $_POST['dt_security_pin'] ) ) : '';
        if ( $pin === '98989898' ) {
            update_option( 'dt_theme_activated', 'yes' );
            if ( function_exists( 'dt_run_one_click_setup' ) ) {
                dt_run_one_click_setup();
            }
            wp_safe_redirect( home_url() );
            exit;
        }
    }

    // Display Frontend Lock Page
    wp_die(
        dt_get_frontend_lock_html(),
        __( 'DT Ecommerce - License Required', 'dt-ecommerce-theme' ),
        array( 'response' => 403 )
    );
}
add_action( 'template_redirect', 'dt_lock_frontend_if_not_activated', 1 );

// Lock Admin features if not activated
function dt_restrict_admin_if_not_activated() {
    if ( dt_is_theme_activated() ) {
        return;
    }

    global $pagenow;
    $current_page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

    // Allow the activation page itself (served under admin.php, NOT themes.php)
    if ( $pagenow === 'admin.php' && $current_page === 'dt-theme-activation' ) {
        return;
    }

    /*
     * Do not block normal WordPress administration. The theme lock should never
     * prevent adding pages/posts/products, opening Customizer, managing menus,
     * media, widgets, WooCommerce, or saving core settings.
     */
    $allowed_admin_pages = array(
        'index.php',
        'edit.php',
        'post.php',
        'post-new.php',
        'upload.php',
        'media-new.php',
        'edit-comments.php',
        'edit-tags.php',
        'term.php',
        'users.php',
        'user-new.php',
        'profile.php',
        'tools.php',
        'import.php',
        'export.php',
        'options-general.php',
        'options-writing.php',
        'options-reading.php',
        'options-discussion.php',
        'options-media.php',
        'options-permalink.php',
        'options.php',
        'themes.php',
        'customize.php',
        'nav-menus.php',
        'widgets.php',
        'plugins.php',
        'plugin-install.php',
        'plugin-editor.php',
        'update-core.php',
        'update.php',
        'admin-ajax.php',
        'admin-post.php',
    );

    if ( in_array( $pagenow, $allowed_admin_pages, true ) ) {
        return;
    }

    // Allow non-theme plugin/admin pages to avoid breaking WordPress admin.
    if ( 'admin.php' === $pagenow && ( '' === $current_page || strpos( $current_page, 'dt-' ) !== 0 ) ) {
        return;
    }

    // Lock only this theme's enterprise settings until activation.
    wp_safe_redirect( admin_url( 'admin.php?page=dt-theme-activation' ) );
    exit;
}
add_action( 'admin_init', 'dt_restrict_admin_if_not_activated' );

// Admin notices if not activated
function dt_admin_activation_notice() {
    if ( dt_is_theme_activated() ) {
        return;
    }
    ?>
    <div class="notice notice-warning is-dismissible" style="border-left-color: #C8A46A !important;">
        <p>
            <strong><?php esc_html_e( 'DT Ecommerce Theme is Locked!', 'dt-ecommerce-theme' ); ?></strong> 
            <?php esc_html_e( 'Please enter your Security PIN to unlock full WooCommerce templates, setup wizards, and the frontend.', 'dt-ecommerce-theme' ); ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=dt-theme-activation' ) ); ?>" class="button button-primary" style="background: #C8A46A; color: #000; border-color: #C8A46A; box-shadow: none; text-shadow: none; margin-left: 10px; font-weight: bold;"><?php esc_html_e( 'Activate Now', 'dt-ecommerce-theme' ); ?></a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'dt_admin_activation_notice' );

// Helper function to render locked frontend HTML
function dt_get_frontend_lock_html() {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>License Required - DT Ecommerce</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            gold: '#C8A46A',
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            serif: ['Cormorant Garamond', 'serif'],
                        }
                    }
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                background-color: #000;
                background-image: repeating-linear-gradient(45deg, #C8A46A 0, #C8A46A 1px, transparent 1px, transparent 22px);
                background-size: 100px 100px;
                opacity: 0.95;
            }
        </style>
    </head>
    <body class="min-h-screen flex items-center justify-center font-sans text-[#F7F4EE] px-4 selection:bg-[#C8A46A] selection:text-black">
        <div class="w-full max-w-md bg-[#0a0a0a]/95 border border-[#C8A46A]/30 p-8 shadow-2xl backdrop-blur-md">
            <div class="text-center mb-8">
                <h1 class="font-serif text-3xl md:text-4xl tracking-widest text-[#C8A46A] font-bold">ARSHMAN</h1>
                <p class="text-[9px] tracking-[0.3em] text-[#C8A46A]/60 uppercase mt-1">We Weave Your Dreams</p>
                <div class="w-16 h-[1px] bg-[#C8A46A]/30 mx-auto mt-6"></div>
            </div>

            <div class="text-center mb-6">
                <svg class="w-12 h-12 text-[#C8A46A] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <h2 class="font-serif text-xl text-white mb-2">Theme Activation Required</h2>
                <p class="text-xs text-[#a3a3a3] leading-relaxed">This luxury enterprise-level theme is locked. Please enter your theme security PIN to activate and view the frontend.</p>
            </div>

            <form method="post" action="">
                <input type="hidden" name="dt_activation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'dt_activate_theme_action' ) ); ?>">
                <div class="mb-5">
                    <input 
                        type="password" 
                        name="dt_security_pin" 
                        placeholder="••••••••" 
                        class="w-full bg-[#111] border border-[#C8A46A]/40 focus:border-[#C8A46A] text-white px-4 py-3 text-center text-lg outline-none tracking-widest rounded-sm placeholder:tracking-normal"
                        required
                    />
                </div>
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black font-bold uppercase tracking-widest text-xs py-3.5 transition-all rounded-sm cursor-pointer"
                >
                    Activate Access
                </button>
            </form>

            <div class="text-center mt-8 text-[10px] text-[#666] tracking-widest uppercase">
                Enterprise Theme Secure System
            </div>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}
