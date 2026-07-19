<?php
/**
 * Theme Options Admin Panel — Full DT Ecommerce Theme Settings
 * Uses admin/admin.css classes for premium dark UI
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── Helper: Get option ──────────────────────────────────────────────────────
function dt_get_theme_option( string $key, string $default = '' ) {
    $options = get_option( 'dt_theme_options', array() );
    return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

function dt_sanitize_theme_options( array $opts ): array {
    $code_fields = array(
        'custom_css',
        'custom_js',
        'font_preload_urls',
        'head_scripts',
    );

    $html_fields = array(
        'google_maps_embed',
        'header_html',
        'footer_html',
        'before_body_html',
        'after_body_html',
    );

    $sanitized = array();
    foreach ( $opts as $key => $val ) {
        $key = sanitize_key( $key );

        if ( in_array( $key, $code_fields, true ) ) {
            $sanitized[ $key ] = is_string( $val ) ? wp_unslash( $val ) : '';
        } elseif ( in_array( $key, $html_fields, true ) ) {
            $sanitized[ $key ] = is_string( $val ) ? wp_kses_post( wp_unslash( $val ) ) : '';
        } elseif ( substr( $key, -4 ) === '_url' ) {
            $sanitized[ $key ] = esc_url_raw( $val );
        } else {
            $sanitized[ $key ] = sanitize_text_field( $val );
        }
    }

    return $sanitized;
}

// ── Admin Menu ──────────────────────────────────────────────────────────────
function dt_theme_options_menu(): void {
    add_menu_page(
        __( 'DT Theme Options', 'dt-ecommerce-theme' ),
        __( 'DT Theme Options', 'dt-ecommerce-theme' ),
        'manage_options',
        'dt-theme-options',
        'dt_render_theme_options_page',
        'dashicons-admin-customizer',
        58
    );
    
    // Submenus — all correctly use 'dt-theme-options' slug so WordPress resolves them properly.
    // Tab switching is handled client-side via URL hash + JS; GET ?tab= is also supported server-side.
    add_submenu_page( 'dt-theme-options', __( 'General Settings',    'dt-ecommerce-theme' ), __( 'General Settings',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options',          'dt_render_theme_options_page' );
    add_submenu_page( 'dt-theme-options', __( 'Colors & Branding',   'dt-ecommerce-theme' ), __( 'Colors & Branding',   'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-colors',       'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Header Builder',      'dt-ecommerce-theme' ), __( 'Header Builder',      'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-header',       'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Footer Builder',      'dt-ecommerce-theme' ), __( 'Footer Builder',      'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-footer',       'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Section Manager',     'dt-ecommerce-theme' ), __( 'Section Manager',     'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-homepage',     'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Typography',          'dt-ecommerce-theme' ), __( 'Typography',          'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-typography',   'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'WooCommerce Controls','dt-ecommerce-theme' ), __( 'WooCommerce Controls','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-woocommerce',  'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Popups & Notifications','dt-ecommerce-theme' ),__( 'Popups & Notifications','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-popups',   'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Social & Contact',    'dt-ecommerce-theme' ), __( 'Social & Contact',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-social',       'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'SEO Settings',        'dt-ecommerce-theme' ), __( 'SEO Settings',        'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-seo',          'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Role Manager',        'dt-ecommerce-theme' ), __( 'Role Manager',        'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-roles',        'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Analytics & Tracking','dt-ecommerce-theme' ), __( 'Analytics & Tracking','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-analytics',    'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Performance',         'dt-ecommerce-theme' ), __( 'Performance',         'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-performance',  'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Custom Code',         'dt-ecommerce-theme' ), __( 'Custom Code',         'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-code',         'dt_render_theme_tab_redirect' );
    add_submenu_page( 'dt-theme-options', __( 'Import & Export',     'dt-ecommerce-theme' ), __( 'Import & Export',     'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-tab-backup',       'dt_render_theme_tab_redirect' );
}
add_action( 'admin_menu', 'dt_theme_options_menu' );

// ── Tab redirect helper: submenu entries point here and redirect with #hash ──
function dt_render_theme_tab_redirect(): void {
    $page    = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
    $tab_map = array(
        'dt-theme-tab-colors'      => 'colors',
        'dt-theme-tab-header'      => 'header',
        'dt-theme-tab-footer'      => 'footer',
        'dt-theme-tab-homepage'    => 'homepage',
        'dt-theme-tab-typography'  => 'typography',
        'dt-theme-tab-woocommerce' => 'woocommerce',
        'dt-theme-tab-popups'      => 'popups',
        'dt-theme-tab-social'      => 'social',
        'dt-theme-tab-seo'         => 'seo',
        'dt-theme-tab-roles'       => 'roles',
        'dt-theme-tab-analytics'   => 'analytics',
        'dt-theme-tab-performance' => 'performance',
        'dt-theme-tab-code'        => 'code',
        'dt-theme-tab-backup'      => 'backup',
    );
    $tab = $tab_map[ $page ] ?? 'general';
    wp_safe_redirect( admin_url( 'admin.php?page=dt-theme-options&tab=' . $tab ) );
    exit;
}

// ── AJAX Save ───────────────────────────────────────────────────────────────
function dt_ajax_save_theme_options(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }
    parse_str( isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '', $posted );
    $opts = $posted['dt_options'] ?? array();

    $sanitized = dt_sanitize_theme_options( $opts );

    $existing = get_option( 'dt_theme_options', array() );
    update_option( 'dt_theme_options', array_merge( $existing, $sanitized ) );
    wp_send_json_success( array( 'message' => 'Settings saved successfully!' ) );
}
add_action( 'wp_ajax_dt_save_theme_options', 'dt_ajax_save_theme_options' );

// ── AJAX Restore Defaults ────────────────────────────────────────────────────
function dt_ajax_restore_default_options(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }
    // dt_reset_theme_options() always writes defaults — no early-return guard.
    if ( function_exists( 'dt_reset_theme_options' ) ) {
        dt_reset_theme_options();
    } elseif ( function_exists( 'dt_get_default_theme_options' ) ) {
        update_option( 'dt_theme_options', dt_get_default_theme_options() );
    } else {
        // Fallback: delete the option so dt_get_theme_option() returns code defaults
        delete_option( 'dt_theme_options' );
    }
    wp_send_json_success( array( 'message' => 'All settings restored to factory defaults successfully!' ) );
}
add_action( 'wp_ajax_dt_restore_default_options', 'dt_ajax_restore_default_options' );

// ── AJAX Import Settings ─────────────────────────────────────────────────────
function dt_ajax_import_theme_options(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }
    $json_raw = isset( $_POST['import_json'] ) ? wp_unslash( $_POST['import_json'] ) : '';
    $data     = json_decode( $json_raw, true );
    if ( ! is_array( $data ) || empty( $data ) ) {
        wp_send_json_error( 'Invalid JSON — paste a valid exported settings string.' );
    }
    update_option( 'dt_theme_options', $data );
    wp_send_json_success( array( 'message' => 'Settings imported successfully!' ) );
}
add_action( 'wp_ajax_dt_import_theme_options', 'dt_ajax_import_theme_options' );

// ── Render Options Page ─────────────────────────────────────────────────────
function dt_render_theme_options_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Regular POST save
    if ( isset( $_POST['dt_options_save'] ) && check_admin_referer( 'dt_theme_options_nonce', 'dt_options_nonce' ) ) {
        $opts = isset( $_POST['dt_options'] ) ? wp_unslash( $_POST['dt_options'] ) : array();
        $sanitized = dt_sanitize_theme_options( $opts );
        $existing = get_option( 'dt_theme_options', array() );
        update_option( 'dt_theme_options', array_merge( $existing, $sanitized ) );
        echo '<div class="dt-notice dt-notice-success" style="margin:20px 0;"><i data-lucide="check-circle" style="width:18px;height:18px;display:inline-block;vertical-align:middle;"></i> &nbsp;<strong>Settings saved successfully!</strong></div>';
    }

    // Settings Import Handler
    if ( isset( $_POST['dt_options_import'] ) && ! empty( $_POST['dt_import_code'] ) && check_admin_referer( 'dt_theme_options_nonce', 'dt_options_nonce' ) ) {
        $import_data = json_decode( wp_unslash( $_POST['dt_import_code'] ), true );
        if ( is_array( $import_data ) ) {
            update_option( 'dt_theme_options', $import_data );
            echo '<div class="dt-notice dt-notice-success" style="margin:20px 0;"><i data-lucide="check-circle" style="width:18px;height:18px;display:inline-block;vertical-align:middle;"></i> &nbsp;<strong>Configuration settings imported successfully!</strong></div>';
        } else {
            echo '<div class="dt-notice dt-notice-error" style="margin:20px 0;"><i data-lucide="alert-triangle" style="width:18px;height:18px;display:inline-block;vertical-align:middle;"></i> &nbsp;<strong>Import failed: Invalid configuration string.</strong></div>';
        }
    }

    // Reset settings handler
    if ( isset( $_POST['dt_options_reset'] ) && check_admin_referer( 'dt_theme_options_nonce', 'dt_options_nonce' ) ) {
        if ( function_exists( 'dt_set_default_theme_options' ) ) {
            dt_set_default_theme_options();
        } else {
            delete_option( 'dt_theme_options' );
        }
        echo '<div class="dt-notice dt-notice-success" style="margin:20px 0;"><i data-lucide="check-circle" style="width:18px;height:18px;display:inline-block;vertical-align:middle;"></i> &nbsp;<strong>Theme settings reset to default demo values!</strong></div>';
    }

    $active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    $opts       = get_option( 'dt_theme_options', array() );

    $tabs = array(
        'general'     => array( 'label' => 'General',           'icon' => 'settings' ),
        'colors'      => array( 'label' => 'Colors & Brand',    'icon' => 'palette' ),
        'header'      => array( 'label' => 'Header',            'icon' => 'layout-template' ),
        'footer'      => array( 'label' => 'Footer',            'icon' => 'layout-panel-bottom' ),
        'homepage'    => array( 'label' => 'Sections',          'icon' => 'home' ),
        'typography'  => array( 'label' => 'Typography',        'icon' => 'type' ),
        'woocommerce' => array( 'label' => 'WooCommerce',       'icon' => 'shopping-bag' ),
        'popups'      => array( 'label' => 'Popups',            'icon' => 'bell' ),
        'social'      => array( 'label' => 'Social & Contact',  'icon' => 'share-2' ),
        'seo'         => array( 'label' => 'SEO',               'icon' => 'search' ),
        'roles'       => array( 'label' => 'Role Manager',      'icon' => 'users' ),
        'analytics'   => array( 'label' => 'Analytics',         'icon' => 'bar-chart-2' ),
        'performance' => array( 'label' => 'Performance',       'icon' => 'zap' ),
        'code'        => array( 'label' => 'Custom Code',       'icon' => 'code-2' ),
        'backup'      => array( 'label' => 'Import & Export',   'icon' => 'database' ),
    );
    ?>
    <div id="dt-admin-page" class="dt-admin-page">
        
        <!-- Dashboard Header -->
        <div class="dt-admin-header">
            <div class="dt-admin-header-left">
                <div>
                    <h1>DT <span>Ecommerce</span> Theme.</h1>
                    <p>Settings &amp; Configuration Dashboard</p>
                </div>
                <span class="dt-version-badge">v2.0</span>
            </div>
            <div class="dt-header-actions">
                <span class="dt-live-indicator">Live</span>
                <button type="button" id="dt-btn-header-save" class="dt-btn-save" style="padding:8px 20px;font-size:11px;">
                    <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save All
                </button>
            </div>
        </div>

        <!-- Search Container -->
        <div class="dt-search-container">
            <input type="text" id="dt-settings-search" class="dt-search-input" placeholder="🔍 Search options, settings, templates..." autocomplete="off">
        </div>

        <!-- Options Page Layout -->
        <form method="post" action="" id="dt-settings-form">
            <?php wp_nonce_field( 'dt_theme_options_nonce', 'dt_options_nonce' ); ?>
            <input type="hidden" name="dt_ajax_save" value="1">

            <div class="dt-options-container">
                <!-- Sidebar -->
                <div class="dt-options-sidebar">
                    <div class="dt-nav-tabs">
                        <?php foreach ( $tabs as $slug => $info ) :
                            $is_active = $active_tab === $slug;
                            $active_class = $is_active ? 'active' : '';
                            ?>
                            <button type="button" class="dt-nav-tab <?php echo $active_class; ?>" data-tab="<?php echo esc_attr( $slug ); ?>">
                                <i data-lucide="<?php echo esc_attr( $info['icon'] ); ?>" style="width:14px;height:14px;"></i>
                                <?php echo esc_html( $info['label'] ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Main Content Panel -->
                <div class="dt-options-main">
                    <div class="dt-card">

                        <!-- ==================== TAB: GENERAL ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'general' ) ? '' : 'hidden'; ?>" id="dt-tab-general">
                            <div class="dt-section">
                                <div class="dt-section-title">🏪 Site Identity</div>
                                <?php dt_option_media( 'logo_url',   'Logo Image',    'Select or upload your brand logo image (leaves blank for text logo)', $opts ); ?>
                                <?php dt_option_row( 'logo_height', 'Logo Height (px)', 'Define the height of the logo in pixels (default: 40)', 'number', $opts ); ?>
                                <?php dt_option_media( 'favicon_url','Favicon Icon', 'Select or upload a 32x32px .png or .ico favicon', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">⚡ Header Top Info &amp; Banner</div>
                                <?php dt_option_textarea( 'announcement_messages', 'Rotating Banner Messages', 'Comma-separated messages to rotate in the header bar (e.g. Free Shipping, 10% Off)', $opts ); ?>
                                <?php dt_option_row( 'sale_banner_text', 'Sub-Nav Promotion Text', 'Text snippet shown right under navigation on desktop', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📍 Contact Settings</div>
                                <?php dt_option_row( 'contact_email',   'Atelier Email',   'Email address displayed on footer/contact pages', 'email', $opts ); ?>
                                <?php dt_option_row( 'contact_phone',   'Atelier Phone',    'Phone number formatted with country code', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'contact_address', 'Atelier Address', 'Full physical store address', $opts ); ?>
                                <?php dt_option_row( 'contact_hours',   'Business Hours',  'e.g. Mon–Sat, 10:00am - 7:00pm IST', 'text', $opts ); ?>
                                <?php dt_option_row( 'whatsapp_url',    'WhatsApp URL',    'Full wa.me API url to contact directly', 'url', $opts ); ?>
                                <?php dt_option_textarea( 'google_maps_embed', 'Google Maps Code', 'Paste standard maps iframe embed code', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📲 Social Links</div>
                                <?php dt_option_row( 'instagram_url',  'Instagram', 'Instagram profile url', 'url', $opts ); ?>
                                <?php dt_option_row( 'facebook_url',   'Facebook',  'Facebook fan page url', 'url', $opts ); ?>
                                <?php dt_option_row( 'twitter_url',    'Twitter/X', 'Twitter profile url', 'url', $opts ); ?>
                                <?php dt_option_row( 'youtube_url',    'YouTube',   'YouTube channel url', 'url', $opts ); ?>
                                <?php dt_option_row( 'pinterest_url',  'Pinterest', 'Pinterest profile url', 'url', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔔 Overlay Popups</div>
                                <?php dt_option_checkbox( 'popup_newsletter_enabled', 'Enable Newsletter Popup', 'Show subscription popup for new visitors', $opts ); ?>
                                <?php dt_option_row( 'popup_newsletter_delay', 'Popup Delay (s)', 'Time delay in seconds before display', 'number', $opts ); ?>
                                <?php dt_option_row( 'popup_title',   'Newsletter Title', 'Popup headline', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'popup_desc', 'Newsletter Description', 'Short description message', $opts ); ?>
                                
                                <hr style="border-top:1px solid rgba(255,255,255,0.03);margin:20px 0;">
                                <?php dt_option_checkbox( 'popup_offer_enabled', 'Enable Offer Popup', 'Show timed discount offer banner', $opts ); ?>
                                <?php dt_option_row( 'popup_offer_delay', 'Offer Delay (s)', 'Time delay in seconds before display', 'number', $opts ); ?>
                                <?php dt_option_row( 'popup_offer_text', 'Offer Title', 'Offer description headline', 'text', $opts ); ?>
                                <?php dt_option_row( 'popup_offer_code', 'Coupon Code', 'Applicable WooCommerce Coupon', 'text', $opts ); ?>
                                
                                <hr style="border-top:1px solid rgba(255,255,255,0.03);margin:20px 0;">
                                <?php dt_option_checkbox( 'popup_exit_enabled', 'Enable Exit Intent Popup', 'Show exit offer when visitor moves to close page', $opts ); ?>
                                <?php dt_option_row( 'popup_exit_title', 'Exit Headline', 'Exit popup headline text', 'text', $opts ); ?>
                                <?php dt_option_row( 'popup_exit_code',  'Exit Coupon Code', 'Discount code to convince stay', 'text', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: HEADER BUILDER ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'header' ) ? '' : 'hidden'; ?>" id="dt-tab-header">
                            <div class="dt-section">
                                <div class="dt-section-title">🔝 Layout &amp; Styles</div>
                                <?php dt_option_select( 'header_layout', 'Header Layout Style', 'Select default navigation template', array(
                                    'layout-1' => 'Layout 1: Left Logo & Right Nav',
                                    'layout-2' => 'Layout 2: Center Logo & Spread Nav',
                                    'layout-3' => 'Layout 3: Left Logo with Sub-Nav Row'
                                ), $opts ); ?>
                                <?php dt_option_checkbox( 'sticky_header', 'Sticky Pinned Header', 'Keep navigation bar fixed on scroll', $opts ); ?>
                                <?php dt_option_checkbox( 'header_transparent', 'Transparent Nav (Home)', 'Header sits on top of hero on homepage', $opts ); ?>
                                <?php dt_option_checkbox( 'header_top_bar', 'Show Announcement Bar', 'Enable/disable announcement line', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🛍️ Navigation Icons</div>
                                <?php dt_option_checkbox( 'header_search', 'Show Search Bar', 'Display input search on header', $opts ); ?>
                                <?php dt_option_checkbox( 'header_account', 'Show Account Icon', 'Enable my account icon link', $opts ); ?>
                                <?php dt_option_checkbox( 'header_wishlist', 'Show Wishlist Icon', 'Enable direct wishlist page navigation link', $opts ); ?>
                                <?php dt_option_checkbox( 'header_compare', 'Show Compare Icon', 'Enable compare list popup link', $opts ); ?>
                                <?php dt_option_checkbox( 'header_cart', 'Show Shopping Cart Bag', 'Enable standard interactive sliding cart drawer widget', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔍 Details &amp; Mobile Settings</div>
                                <?php dt_option_row( 'header_tagline', 'Logo Tagline Subtext', 'Under text-logo description (e.g. Heirloom Weaves)', 'text', $opts ); ?>
                                <?php dt_option_row( 'header_location', 'Deliver Location Title', 'Deliver city name displayed (e.g. Mumbai 400001)', 'text', $opts ); ?>
                                <?php dt_option_row( 'search_placeholder', 'Search Placeholder Text', 'Inside input box placeholder text', 'text', $opts ); ?>
                                <?php dt_option_checkbox( 'ajax_search_enabled', 'Enable AJAX Suggestions', 'Trigger autocomplete search suggestion drop down', $opts ); ?>
                                <?php dt_option_row( 'megamenu_columns', 'Mega Menu Column Width', 'Default count of dropdown categories per column in megamenu (default: 4)', 'number', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: FOOTER BUILDER ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'footer' ) ? '' : 'hidden'; ?>" id="dt-tab-footer">
                            <div class="dt-section">
                                <div class="dt-section-title">🏷️ Footer Brand Identity</div>
                                <p class="description">Control the brand name, tagline, and logo shown in the footer column. If a logo image is uploaded it replaces the text brand name.</p>
                                <?php dt_option_media( 'footer_logo_url', 'Footer Brand Logo', 'Upload your brand logo for the footer (leave blank to show text brand name instead)', $opts ); ?>
                                <?php dt_option_row( 'footer_brand_name', 'Footer Brand Name', 'Brand name displayed in footer (e.g. Frenzy Fusion). Defaults to site name if empty.', 'text', $opts ); ?>
                                <?php dt_option_row( 'footer_brand_tagline', 'Footer Brand Tagline', 'Short tagline shown beside brand name (e.g. Designs, Studio, Official Store)', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🦶 Footer Column Layout</div>
                                <?php dt_option_select( 'footer_layout', 'Widget Columns Grid', 'Default footer column layouts style', array(
                                    'cols-4' => '4 Columns Layout (Recommended)',
                                    'cols-3' => '3 Columns Layout',
                                    'cols-2' => '2 Columns Layout'
                                ), $opts ); ?>
                                <?php dt_option_textarea( 'footer_about', 'Footer Brand Bio Description', 'Brand history paragraph text placed in column 1', $opts ); ?>
                                <?php dt_option_row( 'footer_copyright', 'Copyright Line Text', 'Copyright line displayed at the absolute bottom', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">💌 Footer Newsletter Block</div>
                                <?php dt_option_checkbox( 'newsletter_enabled', 'Show Newsletter Box', 'Display inline footer newsletter subscription block', $opts ); ?>
                                <?php dt_option_row( 'newsletter_title', 'Newsletter Title', 'e.g. Join the Atelier', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'newsletter_desc', 'Newsletter Tagline Description', 'Under headline signup instructions', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">💳 Social &amp; Payment Badges</div>
                                <?php dt_option_checkbox( 'footer_social', 'Display Social Channels Icons', 'Display social media handles', $opts ); ?>
                                <?php dt_option_checkbox( 'footer_payment_badges', 'Display Payment Badges Column', 'Show secure payment cards badge row (Visa, Mastercard, COD)', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: SECTION MANAGER ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'homepage' ) ? '' : 'hidden'; ?>" id="dt-tab-homepage">
                            <div class="dt-section">
                                <div class="dt-section-title">🏠 Homepage Hero Section</div>
                                <?php dt_option_media( 'hero_image_url', 'Hero Background Saree Image', 'Background banner image file', $opts ); ?>
                                <?php dt_option_row( 'hero_badge_text', 'Hero Badge Header Tag', 'Small banner title (e.g. Royal Heritage)', 'text', $opts ); ?>
                                <?php dt_option_row( 'hero_heading', 'Hero Main Large Headline', 'e.g. Banarasi Elegance', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'hero_subtext', 'Hero Supporting Subtext Description', 'Introductory paragraph descriptive text', $opts ); ?>
                                <?php dt_option_row( 'hero_btn1_text', 'Button 1 (Left) Label', 'e.g. Shop Collection', 'text', $opts ); ?>
                                <?php dt_option_row( 'hero_btn1_url', 'Button 1 Redirection Link', 'Navigation destination link', 'text', $opts ); ?>
                                <?php dt_option_row( 'hero_btn2_text', 'Button 2 (Right) Label', 'e.g. Our Story', 'text', $opts ); ?>
                                <?php dt_option_row( 'hero_btn2_url', 'Button 2 Redirection Link', 'Navigation destination link', 'text', $opts ); ?>
                            </div>
                            <!-- ── Premium Banners Section ───────────────────── -->
                            <div class="dt-section">
                                <div class="dt-section-title">🖼️ Premium Banners Section</div>
                                <p class="description" style="margin-bottom:16px;">Control the three promotional banner cards that appear on the homepage. Each banner has its own image, text, link and visibility toggle.</p>

                                <!-- Banner 1 -->
                                <div style="border:1px solid #2a2a2a;border-radius:6px;padding:16px 20px;margin-bottom:20px;background:#111;">
                                    <p style="font-weight:600;color:#C8A46A;margin:0 0 12px;font-size:13px;text-transform:uppercase;letter-spacing:.06em;">Banner 1 — Bridal Collection</p>
                                    <?php dt_option_checkbox( 'banner1_show',     'Show Banner 1',       'Uncheck to hide this banner card', $opts ); ?>
                                    <?php dt_option_media(   'banner1_image',    'Banner 1 Image',      'Upload or paste URL for the banner background photo', $opts ); ?>
                                    <?php dt_option_row(     'banner1_title',    'Banner 1 Title',      'Large heading text (e.g. Bridal Collection)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner1_subtitle', 'Banner 1 Subtitle',   'Small caption below the title (e.g. For your special day)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner1_btn_text', 'Banner 1 Button Label','Button / link text (e.g. Explore)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner1_link',     'Banner 1 Link URL',   'Where clicking the banner or button goes', 'url', $opts ); ?>
                                </div>

                                <!-- Banner 2 -->
                                <div style="border:1px solid #2a2a2a;border-radius:6px;padding:16px 20px;margin-bottom:20px;background:#111;">
                                    <p style="font-weight:600;color:#C8A46A;margin:0 0 12px;font-size:13px;text-transform:uppercase;letter-spacing:.06em;">Banner 2 — Festival Specials</p>
                                    <?php dt_option_checkbox( 'banner2_show',     'Show Banner 2',       'Uncheck to hide this banner card', $opts ); ?>
                                    <?php dt_option_media(   'banner2_image',    'Banner 2 Image',      'Upload or paste URL for the banner background photo', $opts ); ?>
                                    <?php dt_option_row(     'banner2_title',    'Banner 2 Title',      'Large heading text (e.g. Festival Specials)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner2_subtitle', 'Banner 2 Subtitle',   'Small caption below the title (e.g. Celebrate in style)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner2_btn_text', 'Banner 2 Button Label','Button / link text (e.g. Explore)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner2_link',     'Banner 2 Link URL',   'Where clicking the banner or button goes', 'url', $opts ); ?>
                                </div>

                                <!-- Banner 3 (Offer) -->
                                <div style="border:1px solid #2a2a2a;border-radius:6px;padding:16px 20px;background:#111;">
                                    <p style="font-weight:600;color:#C8A46A;margin:0 0 12px;font-size:13px;text-transform:uppercase;letter-spacing:.06em;">Banner 3 — Exclusive Offer</p>
                                    <?php dt_option_checkbox( 'banner3_show',       'Show Banner 3',          'Uncheck to hide this banner card', $opts ); ?>
                                    <?php dt_option_media(   'banner3_image',      'Banner 3 Background Image','Optional overlay image (shown at low opacity)', $opts ); ?>
                                    <?php dt_option_row(     'banner3_eyebrow',    'Banner 3 Eyebrow Tag',   'Small label above the discount (e.g. Limited Time)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner3_discount',   'Banner 3 Discount Text',  'Big bold discount text (e.g. 30% OFF)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner3_subtitle',   'Banner 3 Subtitle',       'Descriptive line below discount (e.g. on exclusive designer weaves)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner3_btn_text',   'Banner 3 Button Label',   'Button text (e.g. Shop Sale)', 'text', $opts ); ?>
                                    <?php dt_option_row(     'banner3_link',       'Banner 3 Link URL',       'Where the Shop Sale button goes', 'url', $opts ); ?>
                                </div>
                            </div>

                            <div class="dt-section">
                                <div class="dt-section-title">📦 Homepage Sections Toggles</div>
                                <?php dt_option_checkbox( 'show_new_arrivals',   'Show "New Arrivals" Carousel Section', 'Slider of the latest product items', $opts ); ?>
                                <?php dt_option_checkbox( 'show_top_sellers',    'Show "Top Sellers" Grid Section', 'Grid gallery of popular items', $opts ); ?>
                                <?php dt_option_checkbox( 'show_reviews',        'Show "Customer Reviews" Carousel', 'Sliding testimonial cards', $opts ); ?>
                                <?php dt_option_checkbox( 'show_instagram_feed', 'Show "Instagram Feed" Grid Gallery', 'Social media integration posts grid', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔢 Homepage Sections Order Weights</div>
                                <p class="description">Control the display order sequence of home page section modules (lower values load first).</p>
                                <?php dt_option_row( 'sec_order_hero', 'Hero Banner Weight', 'Order sequence index (default: 10)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_categories', 'Fabric Categories Weight', 'Order sequence index (default: 20)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_arrivals', 'New Arrivals Weight', 'Order sequence index (default: 30)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_ticker', 'Ticker Strip Weight', 'Order sequence index (default: 40)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_banners', 'Promo Banners Weight', 'Order sequence index (default: 50)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_bestsellers', 'Top Sellers Weight', 'Order sequence index (default: 60)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_promise', 'Our Promise (Why Choose Us) Weight', 'Order sequence index (default: 70)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_reviews', 'Testimonials Weight', 'Order sequence index (default: 80)', 'number', $opts ); ?>
                                <?php dt_option_row( 'sec_order_instagram', 'Instagram Gallery Weight', 'Order sequence index (default: 90)', 'number', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📑 Section Spacing Values</div>
                                <p class="description">Add custom padding values (e.g. 2rem or 50px) for homepage components.</p>
                                <?php dt_option_row( 'sec_padding_arrivals', 'New Arrivals Padding', 'Padding style value (top/bottom)', 'text', $opts ); ?>
                                <?php dt_option_row( 'sec_padding_topsellers', 'Top Sellers Padding', 'Padding style value (top/bottom)', 'text', $opts ); ?>
                                <?php dt_option_row( 'sec_padding_reviews', 'Reviews Padding', 'Padding style value (top/bottom)', 'text', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: TYPOGRAPHY SETTINGS ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'typography' ) ? '' : 'hidden'; ?>" id="dt-tab-typography">
                            <div class="dt-section">
                                <div class="dt-section-title">✍️ Body Typography</div>
                                <?php dt_option_select( 'body_font_family', 'Body Font Family', 'Google Web Font family applied globally', array(
                                    'Inter' => 'Inter (Modern Sans)',
                                    'Roboto' => 'Roboto',
                                    'Outfit' => 'Outfit (Clean Geometric)',
                                    'Open Sans' => 'Open Sans'
                                ), $opts ); ?>
                                <?php dt_option_row( 'body_font_size', 'Body Font Size', 'Default text height (e.g. 15px or 0.95rem)', 'text', $opts ); ?>
                                <?php dt_option_select( 'body_font_weight', 'Body Font Weight', 'Default weight class style', array(
                                    '300' => 'Light',
                                    '400' => 'Regular',
                                    '500' => 'Medium',
                                    '600' => 'Semi-Bold'
                                ), $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">👑 Headings Serif Font Settings</div>
                                <?php dt_option_select( 'headings_font_family', 'Heading Title Font Family', 'Serif font used on titles and headers', array(
                                    'Cormorant Garamond' => 'Cormorant Garamond (Royal Elegant)',
                                    'Playfair Display' => 'Playfair Display',
                                    'Cinzel' => 'Cinzel (Classical Roman)',
                                    'Lora' => 'Lora'
                                ), $opts ); ?>
                                <?php dt_option_row( 'headings_letter_spacing', 'Heading Letter Spacing', 'Letter distance spacing rule (e.g. 0.05em or 1px)', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📁 Custom Local Fonts</div>
                                <?php dt_option_row( 'custom_font_url_woff2', 'Custom Font File URL (.woff2)', 'Insert uploaded typography file url', 'url', $opts ); ?>
                                <?php dt_option_row( 'custom_font_family_name', 'Custom Font Family Name', 'Explicit font name to register (e.g. RoyalSilk)', 'text', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: WOOCOMMERCE CONTROLS ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'woocommerce' ) ? '' : 'hidden'; ?>" id="dt-tab-woocommerce">
                            <div class="dt-section">
                                <div class="dt-section-title">🛍️ Grid Archive Settings</div>
                                <?php dt_option_select( 'shop_columns', 'Products Column Grid', 'Count of columns rendered on category pages', array(
                                    '4' => '4 Columns Grid',
                                    '3' => '3 Columns Grid',
                                    '2' => '2 Columns Grid'
                                ), $opts ); ?>
                                <?php dt_option_row( 'shop_products_per_page', 'Products Per Page count', 'Limit of products listed before pagination (default: 12)', 'number', $opts ); ?>
                                <?php dt_option_select( 'product_card_style', 'Product Card Style Layout', 'Select custom design layout style', array(
                                    'classic' => 'Classic Saree Card Layout',
                                    'elegant' => 'Elegant Framed Card Layout',
                                    'minimal' => 'Minimal Clean Card Layout'
                                ), $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">⚡ Product Detailed Page Features</div>
                                <?php dt_option_checkbox( 'woo_quick_view', 'Enable Quick View Modal', 'Display quick preview product specifications modal', $opts ); ?>
                                <?php dt_option_checkbox( 'woo_sticky_cart', 'Enable Sticky Add-To-Cart bar', 'Displays small floating Add-To-Cart strip on scrolling down page', $opts ); ?>
                                <?php dt_option_checkbox( 'woo_hover_zoom', 'Enable hover image zoom transition', 'Allow zoom scaling effect on hover card thumbnail', $opts ); ?>
                                <?php dt_option_row( 'related_products_count', 'Related products list limit', 'Count of suggested product cards (default: 4)', 'number', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">👥 Role-Based Pricing Controls</div>
                                <?php dt_option_row( 'reseller_discount',   'Reseller Discount %',   'Percent discount applied (e.g. 15 for 15% off regular price)', 'number', $opts ); ?>
                                <?php dt_option_row( 'retailer_discount',   'Retailer Discount %',   'Percent discount applied (e.g. 20 for 20% off regular price)', 'number', $opts ); ?>
                                <?php dt_option_row( 'wholesaler_discount', 'Wholesaler Discount %', 'Percent discount applied (e.g. 30 for 30% off regular price)', 'number', $opts ); ?>
                                <hr style="border-top:1px solid rgba(255,255,255,0.03);margin:20px 0;">
                                <?php dt_option_row( 'reseller_moq',   'Reseller Minimum Order Quantity (MOQ)',   'e.g. 5', 'number', $opts ); ?>
                                <?php dt_option_row( 'retailer_moq',   'Retailer Minimum Order Quantity (MOQ)',   'e.g. 3', 'number', $opts ); ?>
                                <?php dt_option_row( 'wholesaler_moq', 'Wholesaler Minimum Order Quantity (MOQ)', 'e.g. 10', 'number', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: PERFORMANCE ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'performance' ) ? '' : 'hidden'; ?>" id="dt-tab-performance">
                            <div class="dt-section">
                                <div class="dt-section-title">⚡ Compression &amp; Defer options</div>
                                <?php dt_option_checkbox( 'html_minify_enabled', 'Minify Rendered HTML Output', 'Compress and strip tags spaces on server output', $opts ); ?>
                                <?php dt_option_checkbox( 'css_minify_enabled',  'Minify Theme Inline CSS', 'Compress loaded style.css rules inline', $opts ); ?>
                                <?php dt_option_checkbox( 'js_minify_enabled',   'Defer Theme Script Loading', 'Adds defer rules tags onto non-critical JS handles', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🖼️ Native Lazy Loading</div>
                                <?php dt_option_checkbox( 'lazy_load_images', 'Native Lazy Loading Toggles', 'Insert native loading="lazy" tag attribute to image tags', $opts ); ?>
                                <?php dt_option_checkbox( 'disable_gutenberg_css', 'Dequeue Gutenberg block styles', 'Remove wp-block-library stylesheets to save bandwidth', $opts ); ?>
                                <?php dt_option_checkbox( 'remove_emoji_scripts', 'Remove WordPress Emojis scripts', 'Stops loading WP core default emojis JS/CSS scripts (saves ~50KB)', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🌐 Asset Delivery Networks (CDN) &amp; Preloads</div>
                                <?php dt_option_row( 'cdn_domain_url', 'CDN Base Domain Address', 'e.g. https://cdn.arshmandesigns.com (replaces local uploads assets URL strings)', 'url', $opts ); ?>
                                <?php dt_option_textarea( 'font_preload_urls', 'Font Preload URL Resources', 'Paste direct fonts URLs to pre-download (one URL per line)', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: CUSTOM CODE ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'code' ) ? '' : 'hidden'; ?>" id="dt-tab-code">
                            <div class="dt-section">
                                <div class="dt-section-title">💻 Inject Global CSS Editor</div>
                                <div class="dt-form-row">
                                    <div class="dt-form-label">Custom Styling Rules<small>Loaded inline within header</small></div>
                                    <div>
                                        <textarea name="dt_options[custom_css]" id="dt-custom-css" class="dt-textarea" style="min-height:220px;font-family:monospace;font-size:12px;"><?php echo esc_textarea( $opts['custom_css'] ?? '' ); ?></textarea>
                                        <p class="description">Write regular raw CSS declarations. Do not wrap with &lt;style&gt; elements tags.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">⚡ Inject Global JavaScript Editor</div>
                                <div class="dt-form-row">
                                    <div class="dt-form-label">Custom Script Codes<small>Deferred before footer body tags</small></div>
                                    <div>
                                        <textarea name="dt_options[custom_js]" id="dt-custom-js" class="dt-textarea" style="min-height:220px;font-family:monospace;font-size:12px;"><?php echo esc_textarea( $opts['custom_js'] ?? '' ); ?></textarea>
                                        <p class="description">Write raw JavaScript code lines. Do not wrap with &lt;script&gt; elements tags.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📊 Analytics ID &amp; Scripts Injection</div>
                                <?php dt_option_row( 'google_analytics_id', 'Google Analytics ID (GA4)', 'e.g. G-XXXXXXXXXX', 'text', $opts ); ?>
                                <?php dt_option_row( 'facebook_pixel_id',   'Meta Pixel ID tracking',   'Meta Facebook Pixel identification token', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'head_scripts',   'Header HTML Injection Code (&lt;head&gt;)', 'Custom script snippets to insert right into header tags (verification, etc.)', $opts ); ?>
                                <?php dt_option_textarea( 'before_body_html', 'HTML code injection (start &lt;body&gt;)', 'Custom script snippets to output at start of body tag', $opts ); ?>
                                <?php dt_option_textarea( 'after_body_html',  'HTML code injection (end &lt;body&gt;)', 'Custom snippets to output at end of footer layout page before closures', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: BACKUP & RESTORE ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'backup' ) ? '' : 'hidden'; ?>" id="dt-tab-backup">

                            <!-- Auto-backup status banner -->
                            <div class="dt-section" style="margin-bottom:20px;padding:14px 20px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:8px;">
                                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <span style="font-size:20px;">🛡️</span>
                                        <div>
                                            <div style="font-weight:600;font-size:13px;color:#15803d;">Auto-Backup Protection Active</div>
                                            <div id="dt-backup-status-text" style="font-size:11px;color:#166534;margin-top:2px;">
                                                Your settings are automatically backed up outside the theme folder every time you save — so they survive theme deletions and reinstalls.
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:flex;gap:8px;flex-shrink:0;">
                                        <button type="button" id="dt-btn-check-backup" class="dt-btn-secondary" style="font-size:11px;padding:6px 14px;">
                                            Check Backup
                                        </button>
                                        <button type="button" id="dt-btn-migrate-keys" class="dt-btn-defaults" style="font-size:11px;padding:6px 14px;" title="Migrate settings from an older theme version">
                                            Fix Old Settings
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Export & Import side-by-side -->
                            <div class="dt-backup-grid">

                                <!-- EXPORT — green button -->
                                <div class="dt-backup-box">
                                    <div class="dt-section-title" style="margin-bottom:12px;">📦 Export Configuration</div>
                                    <p class="description" style="font-size:12px;color:var(--dt-text-muted);margin:0 0 12px;">Copy your current settings as a JSON backup you can save locally.</p>
                                    <textarea id="dt-export-json" readonly onclick="this.select()" class="dt-textarea" style="font-family:monospace;font-size:11px;min-height:130px;width:100%;max-width:100%;margin-bottom:12px;"><?php echo esc_textarea( wp_json_encode( $opts ) ); ?></textarea>
                                    <button type="button" id="dt-btn-copy-export" class="dt-btn-export">
                                        <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        Copy to Clipboard
                                    </button>
                                </div>

                                <!-- IMPORT — purple button -->
                                <div class="dt-backup-box">
                                    <div class="dt-section-title" style="margin-bottom:12px;">📥 Import Configuration</div>
                                    <p class="description" style="font-size:12px;color:var(--dt-text-muted);margin:0 0 12px;">Paste a previously exported JSON string to restore your settings.</p>
                                    <textarea name="dt_import_code" id="dt-import-code" class="dt-textarea" style="font-family:monospace;font-size:11px;min-height:130px;width:100%;max-width:100%;margin-bottom:12px;" placeholder="Paste JSON settings string here…"></textarea>
                                    <button type="button" id="dt-btn-import-json" class="dt-btn-import">
                                        <svg style="width:13px;height:13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Import Settings
                                    </button>
                                </div>
                            </div>

                            <!-- RESET — red solid, clearly destructive -->
                            <div class="dt-section" style="margin-top:24px;">
                                <div class="dt-section-title" style="background:#fef2f2;border-left-color:#dc2626;color:#dc2626;">⚠️ Factory Reset</div>
                                <p style="font-size:12px;color:var(--dt-text-muted);margin:0 0 16px;">This permanently overwrites <strong>all</strong> current settings with the default demo values. There is no undo — export a backup first if needed.</p>
                                <button type="button" id="dt-btn-factory-reset" class="dt-btn-danger-solid">
                                    <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.86"/></svg>
                                    Reset to Factory Defaults
                                </button>
                            </div>

                        </div>

                        <!-- ==================== TAB: COLORS & BRANDING ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'colors' ) ? '' : 'hidden'; ?>" id="dt-tab-colors">
                            <div class="dt-section">
                                <div class="dt-section-title">🎨 Primary Brand Colors</div>
                                <?php dt_option_color( 'color_primary',    'Primary Gold Color',     'Main accent/brand color used for buttons, icons, borders (default: #C8A46A)', $opts ); ?>
                                <?php dt_option_color( 'color_primary_dark','Primary Dark Shade',    'Darker variation of primary color used in hover states (default: #b08d55)', $opts ); ?>
                                <?php dt_option_color( 'color_primary_light','Primary Light Shade',  'Lighter variation of primary color for hover glows (default: #d8ba82)', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🖤 Background & Surface Colors</div>
                                <?php dt_option_color( 'color_bg_main',   'Main Background Color',   'Primary page background color (default: #000000)', $opts ); ?>
                                <?php dt_option_color( 'color_bg_card',   'Card Background Color',   'Background color for product cards and panels (default: #111111)', $opts ); ?>
                                <?php dt_option_color( 'color_bg_header', 'Header Background Color', 'Header/navigation bar background (default: #000000)', $opts ); ?>
                                <?php dt_option_color( 'color_bg_footer', 'Footer Background Color', 'Footer section background (default: #000000)', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">✍️ Text Colors</div>
                                <?php dt_option_color( 'color_text_primary',   'Primary Text Color',   'Main body text color (default: #F7F4EE)', $opts ); ?>
                                <?php dt_option_color( 'color_text_secondary', 'Secondary Text Color', 'Muted/description text (default: #a3a3a3)', $opts ); ?>
                                <?php dt_option_color( 'color_text_heading',   'Heading Text Color',   'H1-H6 heading elements color (default: #FFFFFF)', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔲 Button Styles</div>
                                <?php dt_option_color( 'color_btn_bg',   'Primary Button Background', 'Background of main CTA buttons (default: #C8A46A)', $opts ); ?>
                                <?php dt_option_color( 'color_btn_text', 'Primary Button Text Color', 'Text color on main CTA buttons (default: #000000)', $opts ); ?>
                                <?php dt_option_select( 'btn_border_radius', 'Button Border Radius', 'Corner rounding style for all buttons', array( '0' => 'Sharp (0px)', '2px' => 'Subtle (2px)', '4px' => 'Rounded (4px)', '8px' => 'More Rounded (8px)', '9999px' => 'Pill / Full Rounded' ), $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🎴 Announcement Bar</div>
                                <?php dt_option_color( 'color_announcement_bg',   'Announcement Bar Background', 'Background color of the top rotating announcement strip', $opts ); ?>
                                <?php dt_option_color( 'color_announcement_text', 'Announcement Bar Text Color', 'Text color inside the announcement strip', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: POPUPS & NOTIFICATIONS ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'popups' ) ? '' : 'hidden'; ?>" id="dt-tab-popups">
                            <div class="dt-section">
                                <div class="dt-section-title">📨 Newsletter Popup</div>
                                <?php dt_option_checkbox( 'popup_newsletter_enabled', 'Enable Newsletter Popup',    'Show email capture popup to visitors after delay', $opts ); ?>
                                <?php dt_option_row(      'popup_newsletter_delay',   'Popup Delay (seconds)',      'Seconds after page load to show popup (default: 5)', 'number', $opts ); ?>
                                <?php dt_option_row(      'popup_title',              'Popup Headline',             'Main heading text inside the newsletter popup', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'popup_desc',               'Popup Description',          'Short persuasive description shown below the headline', $opts ); ?>
                                <?php dt_option_media(    'popup_bg_image',           'Popup Background Image',     'Optional decorative background for the popup panel', $opts ); ?>
                                <?php dt_option_row(      'popup_cookie_days',        'Cookie Expiry (days)',        'Days before showing the popup again to the same visitor (default: 7)', 'number', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🏷️ Offer / Coupon Popup</div>
                                <?php dt_option_checkbox( 'popup_offer_enabled', 'Enable Offer Popup',      'Show discount coupon popup to first-time visitors', $opts ); ?>
                                <?php dt_option_row(      'popup_offer_delay',   'Offer Popup Delay (sec)', 'Seconds after load to show the offer popup (default: 8)', 'number', $opts ); ?>
                                <?php dt_option_row(      'popup_offer_text',    'Offer Text',              'Headline for the offer popup (e.g. Get 10% OFF your first order!)', 'text', $opts ); ?>
                                <?php dt_option_row(      'popup_offer_code',    'Coupon Code',             'Discount code displayed in the offer popup (e.g. ARSHMAN10)', 'text', $opts ); ?>
                                <?php dt_option_media(    'popup_offer_image',   'Offer Popup Image',       'Decorative image/banner for the offer popup', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🚪 Exit Intent Popup</div>
                                <?php dt_option_checkbox( 'popup_exit_enabled', 'Enable Exit Intent Popup',  'Show popup when user moves cursor toward the browser top bar', $opts ); ?>
                                <?php dt_option_row(      'popup_exit_title',   'Exit Intent Headline',      'Heading text (e.g. Wait - Don\'t Leave Yet!)', 'text', $opts ); ?>
                                <?php dt_option_row(      'popup_exit_code',    'Exit Coupon Code',          'Special coupon code shown on exit intent (optional)', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'popup_exit_desc',    'Exit Intent Description',   'Short message to keep visitor on site', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📧 Newsletter Subscribers</div>
                                <?php
                                $subscribers = get_option( 'dt_newsletter_subscribers', array() );
                                $count = is_array( $subscribers ) ? count( $subscribers ) : 0;
                                ?>
                                <div class="dt-form-row">
                                    <div class="dt-form-label">Subscriber Count<small>Total emails collected via newsletter forms</small></div>
                                    <div>
                                        <span style="font-size:28px;font-family:'Cormorant Garamond',serif;color:#C8A46A;font-weight:700;"><?php echo esc_html( $count ); ?></span>
                                        <span style="color:#666;font-size:11px;margin-left:8px;">subscribers</span>
                                        <?php if ( $count > 0 ) : ?>
                                            <div style="margin-top:12px;max-height:180px;overflow-y:auto;border:1px solid rgba(200,164,106,0.1);padding:10px;background:#0a0a0a;">
                                                <?php foreach ( array_keys( $subscribers ) as $email ) : ?>
                                                    <div style="font-size:11px;color:#a3a3a3;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.03);"><?php echo esc_html( $email ); ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB: SOCIAL & CONTACT ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'social' ) ? '' : 'hidden'; ?>" id="dt-tab-social">
                            <div class="dt-section">
                                <div class="dt-section-title">📲 Social Media Links</div>
                                <?php dt_option_row( 'facebook_url',   'Facebook Page URL',    'Full URL to your Facebook brand page', 'url', $opts ); ?>
                                <?php dt_option_row( 'instagram_url',  'Instagram Profile URL','Full URL to your Instagram brand profile', 'url', $opts ); ?>
                                <?php dt_option_row( 'twitter_url',    'Twitter/X Profile URL','Full URL to your Twitter/X profile', 'url', $opts ); ?>
                                <?php dt_option_row( 'youtube_url',    'YouTube Channel URL',  'Full URL to your YouTube channel', 'url', $opts ); ?>
                                <?php dt_option_row( 'pinterest_url',  'Pinterest Profile URL','Full URL to your Pinterest profile (optional)', 'url', $opts ); ?>
                                <?php dt_option_row( 'whatsapp_url',   'WhatsApp Business URL','wa.me API link for direct WhatsApp contact (e.g. https://wa.me/911234567890)', 'url', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📍 Contact Information</div>
                                <?php dt_option_row(      'contact_email',   'Store Email Address',  'Primary email shown in footer and contact page', 'email', $opts ); ?>
                                <?php dt_option_row(      'contact_phone',   'Store Phone Number',   'Phone number with country code (e.g. +91 98765 43210)', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'contact_address', 'Store Address',        'Full physical address of your store or atelier', $opts ); ?>
                                <?php dt_option_row(      'contact_hours',   'Business Hours',       'Operating hours (e.g. Mon–Sat, 10:00am – 7:00pm IST)', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'google_maps_embed','Google Maps Embed',   'Paste the full Google Maps iframe embed code here', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📰 Newsletter & Footer</div>
                                <?php dt_option_checkbox( 'newsletter_enabled', 'Show Footer Newsletter Block', 'Display email subscription section in footer', $opts ); ?>
                                <?php dt_option_row(      'newsletter_title',   'Newsletter Headline',           'Heading shown above footer newsletter form', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'newsletter_desc',    'Newsletter Description',        'Short description encouraging sign-ups', $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: SEO SETTINGS ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'seo' ) ? '' : 'hidden'; ?>" id="dt-tab-seo">
                            <div class="dt-section">
                                <div class="dt-section-title">🔍 Global SEO Defaults</div>
                                <?php dt_option_row( 'seo_site_title_suffix', 'Title Tag Suffix',        'Text appended to page titles (e.g. | Arshman Designs)', 'text', $opts ); ?>
                                <?php dt_option_textarea( 'seo_default_description', 'Default Meta Description', 'Global fallback meta description used when no specific one is set (160 chars max)', $opts ); ?>
                                <?php dt_option_media( 'seo_og_image', 'Default OG Social Share Image', 'Default Open Graph image shown when pages are shared on social media (1200×630px recommended)', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🌐 Indexing & Robots</div>
                                <?php dt_option_checkbox( 'seo_noindex_search',   'Noindex Search Result Pages', 'Prevent search result pages from being indexed by Google', $opts ); ?>
                                <?php dt_option_checkbox( 'seo_noindex_account',  'Noindex Account Pages',       'Prevent My Account, Cart, Checkout pages from appearing in search', $opts ); ?>
                                <?php dt_option_row(      'seo_robots_txt_extra', 'Extra robots.txt Rules',      'Additional directives appended to robots.txt (one per line)', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📊 Schema & Structured Data</div>
                                <?php dt_option_checkbox( 'seo_product_schema',  'Enable Product JSON-LD Schema',  'Outputs WooCommerce product schema for rich results in Google', $opts ); ?>
                                <?php dt_option_checkbox( 'seo_breadcrumb_schema','Enable Breadcrumb Schema',       'Output breadcrumb structured data for search results', $opts ); ?>
                                <?php dt_option_checkbox( 'seo_org_schema',       'Enable Organization Schema',     'Output brand/organization schema markup in site header', $opts ); ?>
                                <?php dt_option_row( 'seo_twitter_handle', 'Twitter/X @Handle', 'Your brand Twitter handle without @ (used for Twitter card meta tags)', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔗 Canonical & URLs</div>
                                <?php dt_option_checkbox( 'seo_canonical_enabled', 'Output Canonical Tags',  'Add canonical link tags to prevent duplicate content issues', $opts ); ?>
                                <?php dt_option_select( 'seo_trailing_slash', 'Trailing Slash Preference', 'How URLs are normalized (requires permalink flush after change)', array( 'default' => 'WordPress Default', 'always' => 'Always Add Trailing Slash', 'never' => 'Never Add Trailing Slash' ), $opts ); ?>
                            </div>
                        </div>

                        <!-- ==================== TAB: ROLE MANAGER ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'roles' ) ? '' : 'hidden'; ?>" id="dt-tab-roles">
                            <div class="dt-section">
                                <div class="dt-section-title">👥 User Role Overview</div>
                                <?php
                                $role_list = array(
                                    'dt_customer'   => array( 'label' => 'Customer (Retail)',  'desc' => 'Standard retail buyers paying full price', 'color' => '#4ade80' ),
                                    'dt_reseller'   => array( 'label' => 'Reseller',           'desc' => 'Sells products to end users at markup',    'color' => '#60a5fa' ),
                                    'dt_retailer'   => array( 'label' => 'Retailer',           'desc' => 'Bulk buyer for small retail shops',         'color' => '#f59e0b' ),
                                    'dt_wholesaler' => array( 'label' => 'Wholesaler',         'desc' => 'Large bulk buyer, lowest price tier',       'color' => '#a78bfa' ),
                                );
                                foreach ( $role_list as $role_key => $role_data ) :
                                    $user_count = count( get_users( array( 'role' => $role_key, 'fields' => 'ID' ) ) );
                                    ?>
                                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                                        <div style="display:flex;align-items:center;gap:12px;">
                                            <div style="width:8px;height:8px;border-radius:50%;background:<?php echo esc_attr( $role_data['color'] ); ?>;flex-shrink:0;"></div>
                                            <div>
                                                <div style="font-size:13px;font-weight:600;color:#F7F4EE;"><?php echo esc_html( $role_data['label'] ); ?></div>
                                                <div style="font-size:11px;color:#666;margin-top:2px;"><?php echo esc_html( $role_data['desc'] ); ?></div>
                                            </div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-size:20px;font-family:'Cormorant Garamond',serif;color:<?php echo esc_attr( $role_data['color'] ); ?>;font-weight:700;"><?php echo esc_html( $user_count ); ?></div>
                                            <div style="font-size:10px;color:#555;letter-spacing:0.05em;text-transform:uppercase;">users</div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">💰 Role-Based Discount Configuration</div>
                                <p style="color:#666;font-size:12px;margin-bottom:16px;">Set a global percentage discount applied automatically to each role's prices. Individual product override prices can be set per-product in the WooCommerce product editor.</p>
                                <?php dt_option_row( 'reseller_discount',    'Reseller Global Discount %',    'Percentage off regular price for all resellers (e.g. 15)', 'number', $opts ); ?>
                                <?php dt_option_row( 'retailer_discount',    'Retailer Global Discount %',    'Percentage off regular price for all retailers (e.g. 20)', 'number', $opts ); ?>
                                <?php dt_option_row( 'wholesaler_discount',  'Wholesaler Global Discount %',  'Percentage off regular price for all wholesalers (e.g. 30)', 'number', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📦 Minimum Order Quantities (MOQ)</div>
                                <?php dt_option_row( 'reseller_moq',   'Reseller MOQ',   'Minimum quantity per order for resellers (e.g. 5)',   'number', $opts ); ?>
                                <?php dt_option_row( 'retailer_moq',   'Retailer MOQ',   'Minimum quantity per order for retailers (e.g. 3)',   'number', $opts ); ?>
                                <?php dt_option_row( 'wholesaler_moq', 'Wholesaler MOQ', 'Minimum quantity per order for wholesalers (e.g. 10)', 'number', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">⚙️ Role Display Options</div>
                                <?php dt_option_checkbox( 'role_show_badge',      'Show Role Badge on My Account', 'Display the user\'s role as a badge in the My Account dashboard', $opts ); ?>
                                <?php dt_option_checkbox( 'role_show_price_label','Show Role Price Label',          'Display "Your special price" label next to discounted prices', $opts ); ?>
                                <?php dt_option_checkbox( 'role_registration_field','Show Role Selector on Register Form', 'Display account type selector on WooCommerce registration form', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">👤 User Management Quick Access</div>
                                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px;">
                                    <a href="<?php echo esc_url( admin_url( 'users.php?role=dt_customer' ) ); ?>" class="dt-btn-secondary" target="_blank">View Customers</a>
                                    <a href="<?php echo esc_url( admin_url( 'users.php?role=dt_reseller' ) ); ?>" class="dt-btn-secondary" target="_blank">View Resellers</a>
                                    <a href="<?php echo esc_url( admin_url( 'users.php?role=dt_retailer' ) ); ?>" class="dt-btn-secondary" target="_blank">View Retailers</a>
                                    <a href="<?php echo esc_url( admin_url( 'users.php?role=dt_wholesaler' ) ); ?>" class="dt-btn-secondary" target="_blank">View Wholesalers</a>
                                    <a href="<?php echo esc_url( admin_url( 'user-new.php' ) ); ?>" class="dt-btn-save" style="padding:8px 18px;font-size:12px;" target="_blank">+ Add New User</a>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== TAB: ANALYTICS & TRACKING ==================== -->
                        <div class="dt-tab-content <?php echo ( $active_tab === 'analytics' ) ? '' : 'hidden'; ?>" id="dt-tab-analytics">
                            <div class="dt-section">
                                <div class="dt-section-title">📊 Google Analytics</div>
                                <?php dt_option_checkbox( 'ga_enabled', 'Enable Google Analytics', 'Inject GA4 tracking script into all pages', $opts ); ?>
                                <?php dt_option_row( 'ga_measurement_id', 'GA4 Measurement ID', 'Your Google Analytics 4 Measurement ID (e.g. G-XXXXXXXXXX)', 'text', $opts ); ?>
                                <?php dt_option_checkbox( 'ga_anonymize_ip', 'Anonymize IP Addresses', 'Enable IP anonymization for GDPR compliance', $opts ); ?>
                                <?php dt_option_checkbox( 'ga_exclude_admin', 'Exclude Admin Users from Tracking', 'Do not track logged-in administrator sessions', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📘 Facebook / Meta Pixel</div>
                                <?php dt_option_checkbox( 'fb_pixel_enabled', 'Enable Facebook Pixel',      'Inject Meta/Facebook Pixel tracking on all pages', $opts ); ?>
                                <?php dt_option_row( 'fb_pixel_id',           'Facebook Pixel ID',           'Your 15-digit Meta Pixel ID', 'text', $opts ); ?>
                                <?php dt_option_checkbox( 'fb_pixel_purchase', 'Track Purchase Events',       'Fire Purchase event on WooCommerce order completion', $opts ); ?>
                                <?php dt_option_checkbox( 'fb_pixel_view',     'Track ViewContent Events',    'Fire ViewContent event on WooCommerce product pages', $opts ); ?>
                                <?php dt_option_checkbox( 'fb_pixel_cart',     'Track AddToCart Events',      'Fire AddToCart event when product is added to cart', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🎯 Google Tag Manager</div>
                                <?php dt_option_checkbox( 'gtm_enabled', 'Enable Google Tag Manager',  'Inject GTM container script on all pages', $opts ); ?>
                                <?php dt_option_row( 'gtm_container_id',  'GTM Container ID',          'Your GTM Container ID (e.g. GTM-XXXXXXX)', 'text', $opts ); ?>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">🔍 Search Console & Verification</div>
                                <?php dt_option_row( 'google_site_verification',  'Google Site Verification',  'Google Search Console verification meta content value', 'text', $opts ); ?>
                                <?php dt_option_row( 'bing_site_verification',    'Bing Webmaster Verification','Bing Webmaster Tools verification meta content value', 'text', $opts ); ?>
                            </div>
                        </div>

                    </div><!-- end card -->

                    <!-- Save Bar — distinct buttons for every action -->
                    <div class="dt-save-bar">
                        <!-- 1. SAVE — gold gradient, primary action -->
                        <button type="submit" id="dt-btn-save-main" name="dt_options_save" value="1" class="dt-btn-save" title="Save all settings (Ctrl+S / ⌘S)">
                            <svg style="width:15px;height:15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Save Settings
                        </button>

                        <span class="dt-kbd" title="Keyboard shortcut">
                            <span id="dt-shortcut-key">Ctrl</span>+S
                        </span>

                        <div class="dt-save-bar-divider"></div>

                        <!-- 2. RESTORE DEFAULTS — blue, calm/reversible feel -->
                        <button type="button" id="dt-btn-restore-defaults" class="dt-btn-defaults" title="Restore all settings to default demo values">
                            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.86"/></svg>
                            Restore Defaults
                        </button>

                        <!-- Status indicator (right-aligned) -->
                        <span id="dt-save-status" class="dt-save-status">
                            <span class="dt-save-status-dot"></span>
                            <span>All settings saved.</span>
                        </span>
                    </div>
                </div>
            </div>
        </form>

        <!-- Stats Info Cards -->
        <div class="dt-stats-grid" style="margin-top:36px;">
            <div class="dt-stat-card">
                <div class="dt-stat-number"><?php echo esc_html( get_option( 'blogname' ) ); ?></div>
                <div class="dt-stat-label">Active Site Name</div>
            </div>
            <div class="dt-stat-card">
                <div class="dt-stat-number"><?php echo ( class_exists('WooCommerce') && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0; ?></div>
                <div class="dt-stat-label">Items in Cart</div>
            </div>
            <div class="dt-stat-card">
                <div class="dt-stat-number"><?php echo esc_html( wp_count_posts( 'product' )->publish ?? 0 ); ?></div>
                <div class="dt-stat-label">Published Products</div>
            </div>
            <div class="dt-stat-card">
                <div class="dt-stat-number"><?php echo esc_html( wp_count_posts( 'shop_order' )->wc_processing ?? 0 ); ?></div>
                <div class="dt-stat-label">Processing Orders</div>
            </div>
        </div>

    </div><!-- end dt-admin-page -->

    <script>
    /* Detect Mac and update keyboard shortcut label */
    (function() {
        var el = document.getElementById('dt-shortcut-key');
        if (el && navigator.platform.toUpperCase().indexOf('MAC') >= 0) {
            el.textContent = '⌘';
        }
        /* Init Lucide icons after page render */
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    })();
    </script>
    <?php
}

// ── Field Helper Functions ──────────────────────────────────────────────────

function dt_option_row( string $key, string $label, string $desc, string $type = 'text', array $opts = [] ): void {
    $val = $opts[ $key ] ?? '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div>
            <input type="<?php echo esc_attr( $type ); ?>" id="dt_<?php echo esc_attr( $key ); ?>" name="dt_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" class="dt-input" style="width:100%;max-width:520px;">
        </div>
    </div>
    <?php
}

function dt_option_textarea( string $key, string $label, string $desc, array $opts = [] ): void {
    $val = $opts[ $key ] ?? '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div>
            <textarea id="dt_<?php echo esc_attr( $key ); ?>" name="dt_options[<?php echo esc_attr( $key ); ?>]" class="dt-textarea" rows="4" style="width:100%;max-width:520px;"><?php echo esc_textarea( $val ); ?></textarea>
        </div>
    </div>
    <?php
}

function dt_option_checkbox( string $key, string $label, string $desc, array $opts = [] ): void {
    $checked = ! empty( $opts[ $key ] ) ? 'checked' : '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div style="padding-top:4px;">
            <input type="hidden" name="dt_options[<?php echo esc_attr( $key ); ?>]" value="0">
            <label class="dt-toggle">
                <input type="checkbox" name="dt_options[<?php echo esc_attr( $key ); ?>]" value="1" <?php echo $checked; ?>>
                <span class="dt-toggle-track"></span>
                <span class="dt-toggle-label"><?php esc_html_e( 'Enabled', 'dt-ecommerce-theme' ); ?></span>
            </label>
        </div>
    </div>
    <?php
}

function dt_option_select( string $key, string $label, string $desc, array $options_list, array $opts = [] ): void {
    $val = $opts[ $key ] ?? '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div>
            <select id="dt_<?php echo esc_attr( $key ); ?>" name="dt_options[<?php echo esc_attr( $key ); ?>]" class="dt-select" style="width:100%;max-width:520px;">
                <?php foreach ( $options_list as $k => $v ) : ?>
                    <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $val, $k ); ?>><?php echo esc_html( $v ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php
}

function dt_option_color( string $key, string $label, string $desc, array $opts = [] ): void {
    $val = $opts[ $key ] ?? '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div style="display:flex;align-items:center;gap:12px;">
            <input type="text" id="dt_<?php echo esc_attr( $key ); ?>" name="dt_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" class="dt-input dt-color-picker" style="max-width:200px;" data-default-color="<?php echo esc_attr( $val ?: '#C8A46A' ); ?>">
        </div>
    </div>
    <?php
}

function dt_option_media( string $key, string $label, string $desc, array $opts = [] ): void {
    $val = $opts[ $key ] ?? '';
    $preview_id = 'preview_' . esc_attr( $key );
    $input_id = 'dt_' . esc_attr( $key );
    $hidden_class = empty( $val ) ? 'hidden' : '';
    ?>
    <div class="dt-form-row">
        <div class="dt-form-label"><?php echo esc_html( $label ); ?><small><?php echo wp_kses_post( $desc ); ?></small></div>
        <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <input type="text" id="<?php echo $input_id; ?>" name="dt_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" class="dt-input" style="width:100%;max-width:400px;">
                <button type="button" class="button button-secondary" data-media-upload data-input="<?php echo $input_id; ?>" data-preview="<?php echo $preview_id; ?>">
                    Upload
                </button>
                <button type="button" class="button button-link-delete" data-media-remove data-input="<?php echo $input_id; ?>" data-preview="<?php echo $preview_id; ?>" style="color:#d63638;">
                    Remove
                </button>
            </div>
            <img id="<?php echo esc_attr( $preview_id ); ?>" src="<?php echo esc_url( $val ); ?>" class="dt-upload-preview <?php echo esc_attr( $hidden_class ); ?>" style="max-height:80px;border:1px solid #333;padding:4px;background:#111;">
        </div>
    </div>
    <?php
}

// dt_set_default_theme_options() and dt_ajax_run_setup_wizard() are defined in setup/demo-import.php
