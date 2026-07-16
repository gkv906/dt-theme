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
    
    // Submenus matching tabs for direct link integration
    add_submenu_page( 'dt-theme-options', __( 'General Settings', 'dt-ecommerce-theme' ),   __( 'General Settings',   'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options', 'dt_render_theme_options_page' );
    add_submenu_page( 'dt-theme-options', __( 'Header Builder',  'dt-ecommerce-theme' ),   __( 'Header Builder',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=header', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Footer Builder',  'dt-ecommerce-theme' ),   __( 'Footer Builder',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=footer', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Section Manager',  'dt-ecommerce-theme' ),  __( 'Section Manager',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=homepage', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Typography Settings','dt-ecommerce-theme' ),__( 'Typography Settings','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=typography', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'WooCommerce Controls','dt-ecommerce-theme' ),__( 'WooCommerce Controls','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=woocommerce', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Performance Settings','dt-ecommerce-theme' ),__( 'Performance Settings','dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=performance', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Custom Code',    'dt-ecommerce-theme' ),   __( 'Custom Code', 'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=code', '__return_false' );
    add_submenu_page( 'dt-theme-options', __( 'Import & Export',  'dt-ecommerce-theme' ),  __( 'Import & Export',    'dt-ecommerce-theme' ), 'manage_options', 'dt-theme-options&tab=backup', '__return_false' );
}
add_action( 'admin_menu', 'dt_theme_options_menu' );

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
        'general'     => array( 'label' => 'General Settings',   'icon' => 'settings' ),
        'header'      => array( 'label' => 'Header Builder',     'icon' => 'layout-template' ),
        'footer'      => array( 'label' => 'Footer Builder',     'icon' => 'layout-panel-bottom' ),
        'homepage'    => array( 'label' => 'Section Manager',    'icon' => 'home' ),
        'typography'  => array( 'label' => 'Typography Settings','icon' => 'type' ),
        'woocommerce' => array( 'label' => 'WooCommerce Controls','icon' => 'shopping-bag' ),
        'performance' => array( 'label' => 'Performance Settings','icon' => 'zap' ),
        'code'        => array( 'label' => 'Custom Code',        'icon' => 'code-2' ),
        'backup'      => array( 'label' => 'Import & Export',    'icon' => 'database' ),
    );
    ?>
    <div id="dt-admin-page" class="dt-admin-page">
        
        <!-- Dashboard Header -->
        <div class="dt-admin-header">
            <div>
                <h1>DT Ecommerce Theme.</h1>
                <p style="color:#666;font-size:11px;margin:4px 0 0;letter-spacing:0.08em;text-transform:uppercase;">Settings &amp; Configuration Dashboard</p>
            </div>
            <span class="dt-version-badge">v1.1</span>
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
                            <div class="dt-section">
                                <div class="dt-section-title">📦 Export Backup Configuration</div>
                                <p class="description">Copy and download the following configuration JSON object to store a backup copy of your settings.</p>
                                <textarea readonly onclick="this.select()" class="dt-textarea" style="font-family:monospace;font-size:11px;min-height:140px;width:100%;max-width:520px;margin-top:10px;"><?php echo esc_textarea( wp_json_encode( $opts ) ); ?></textarea>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title">📥 Import Restoration Settings</div>
                                <p class="description">Paste back a previously exported theme settings JSON string object to restore configuration parameters.</p>
                                <textarea name="dt_import_code" id="dt-import-code" class="dt-textarea" style="font-family:monospace;font-size:11px;min-height:140px;width:100%;max-width:520px;margin-top:10px;" placeholder="Paste JSON settings string here..."></textarea>
                                <div style="margin-top:12px;">
                                    <button type="submit" name="dt_options_import" value="1" class="button button-secondary" onclick="return confirm('WARNING: This will overwrite all your current settings. Proceed?');">
                                        Import Settings Configuration
                                    </button>
                                </div>
                            </div>
                            <div class="dt-section">
                                <div class="dt-section-title" style="color:#d63638;">⚠️ Reset Dashboard Settings</div>
                                <p class="description">Overwrites and resets all existing settings options parameters to default demo values template.</p>
                                <div style="margin-top:12px;">
                                    <button type="submit" name="dt_options_reset" value="1" class="button button-link-delete" onclick="return confirm('Are you sure you want to reset ALL theme settings to defaults? This cannot be undone.');" style="color:#d63638;border: 1px solid rgba(214, 54, 56, 0.3);padding:6px 14px;border-radius:2px;background:rgba(214, 54, 56, 0.05);">
                                        Reset to Defaults
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div><!-- end card -->

                    <!-- Save Button sticky bar -->
                    <div style="display:flex;align-items:center;gap:16px;margin-top:18px;background:#111;border:1px solid rgba(200,164,106,0.15);padding:16px 28px;">
                        <button type="submit" name="dt_options_save" value="1" class="dt-btn-primary">
                            💾 Save Settings
                        </button>
                        <span style="color:#666;font-size:11px;letter-spacing:0.04em;">All configurations are immediately stored in the theme options database registry.</span>
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
            <img id="<?php echo $preview_id; ?>" src="<?php echo esc_url( $val ); ?>" class="dt-upload-preview <?php echo $hidden_class; ?>" style="max-height:80px;border:1px solid #333;padding:4px;background:#111;">
        </div>
    </div>
    <?php
}
