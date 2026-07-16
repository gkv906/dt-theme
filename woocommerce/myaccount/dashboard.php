<?php
/**
 * My Account Dashboard override
 * Template override: woocommerce/myaccount/dashboard.php
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$current_user = wp_get_current_user();
$username     = $current_user->display_name ? $current_user->display_name : $current_user->user_nicename;
$role_label   = function_exists( 'dt_get_user_role_label' ) ? dt_get_user_role_label() : 'Customer';
/** @var string $logout_url */
$logout_url   = wc_logout_url();
$orders_url   = wc_get_endpoint_url( 'orders' );
$address_url  = wc_get_endpoint_url( 'edit-address' );
$account_url  = wc_get_endpoint_url( 'edit-account' );
?>

<style>
/* ─── Premium Dashboard Aesthetics ─────────────────────────────────── */
.dt-dash-welcome {
    background: rgba(255,255,255,0.01);
    border: 1px solid rgba(200,164,106,0.15);
    padding: 32px; position: relative;
    margin-bottom: 32px; overflow: hidden;
}
.dt-dash-welcome::before {
    content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
    background: linear-gradient(to bottom, #b08d55, #C8A46A, #d8ba82);
}
.dt-dash-welcome::after {
    content: ''; position: absolute; bottom: 8px; right: 8px; width: 12px; height: 12px;
    border-right: 1px solid rgba(200,164,106,0.25);
    border-bottom: 1px solid rgba(200,164,106,0.25);
}

.dt-dash-greet {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px; font-weight: 600; color: #fff;
    margin-bottom: 10px; letter-spacing: 0.03em;
}
.dt-dash-greet span { color: #C8A46A; }
.dt-dash-desc {
    font-size: 13px; color: #777; font-family: 'Inter', sans-serif;
    line-height: 1.6; max-width: 680px; margin: 0;
}
.dt-dash-desc a {
    color: #C8A46A; text-decoration: none; font-weight: 600;
    border-bottom: 1px dashed rgba(200,164,106,0.4);
    transition: all 0.2s;
}
.dt-dash-desc a:hover {
    color: #d8ba82; border-bottom-style: solid;
}

/* Dashboard Action Cards Grid */
.dt-dash-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px; margin-bottom: 32px;
}

.dt-dash-card {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(200,164,106,0.12);
    padding: 24px; position: relative;
    display: flex; flex-direction: column;
    justify-content: space-between;
    transition: border-color 0.25s, transform 0.2s, box-shadow 0.25s;
    text-decoration: none; color: inherit;
    min-height: 160px;
}
.dt-dash-card:hover {
    border-color: rgba(200,164,106,0.4);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(200,164,106,0.05);
}
.dt-dash-card::before, .dt-dash-card::after {
    content: ''; position: absolute; width: 10px; height: 10px;
    border-color: rgba(200,164,106,0.2); border-style: solid;
    opacity: 0; transition: opacity 0.25s;
}
.dt-dash-card::before { top: 6px; left: 6px; border-width: 1px 0 0 1px; }
.dt-dash-card::after  { bottom: 6px; right: 6px; border-width: 0 1px 1px 0; }
.dt-dash-card:hover::before, .dt-dash-card:hover::after { opacity: 1; }

.dt-dash-card-icon {
    width: 42px; height: 42px; border-radius: 50%;
    background: rgba(200,164,106,0.05);
    border: 1px solid rgba(200,164,106,0.18);
    display: flex; align-items: center; justify-content: center;
    color: #C8A46A; margin-bottom: 18px;
    transition: background 0.25s, color 0.25s;
}
.dt-dash-card:hover .dt-dash-card-icon {
    background: #C8A46A; color: #000;
}

.dt-dash-card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px; font-weight: 600; color: #fff;
    margin-bottom: 6px; letter-spacing: 0.02em;
}
.dt-dash-card-text {
    font-size: 11px; color: #555; font-family: 'Inter', sans-serif;
    line-height: 1.5; margin-bottom: 14px;
}
.dt-dash-card-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase; color: #C8A46A;
    font-family: 'Inter', sans-serif; transition: color 0.2s;
}
.dt-dash-card:hover .dt-dash-card-link { color: #d8ba82; }
.dt-dash-card-link svg { width: 12px; height: 12px; transition: transform 0.2s; }
.dt-dash-card:hover .dt-dash-card-link svg { transform: translateX(3px); }

/* Quick Status Panel */
.dt-dash-info-panel {
    background: rgba(200,164,106,0.03);
    border: 1px dashed rgba(200,164,106,0.2);
    padding: 20px 24px; display: flex;
    justify-content: space-between; align-items: center;
    gap: 16px; flex-wrap: wrap;
}
.dt-dash-info-title {
    font-size: 11px; font-weight: 600; color: #888;
    text-transform: uppercase; letter-spacing: 0.12em;
    font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 8px;
}
.dt-dash-info-title svg { color: #C8A46A; }
.dt-dash-info-meta {
    font-size: 11px; font-weight: 700; color: #C8A46A;
    text-transform: uppercase; letter-spacing: 0.1em;
    font-family: 'Inter', sans-serif;
    background: rgba(200,164,106,0.08);
    border: 1px solid rgba(200,164,106,0.18);
    padding: 4px 12px; border-radius: 2px;
}
</style>

<!-- Welcome Section -->
<div class="dt-dash-welcome">
    <h2 class="dt-dash-greet">Hello, <span><?php echo esc_html( $username ); ?></span></h2>
    <p class="dt-dash-desc">
        Welcome to your exclusive dashboard. From here, you can easily monitor your shopping journey, manage your shipping addresses, update credentials, and explore personalized pricing tiers.
        Not you? <a href="<?php echo esc_url( $logout_url ); ?>">Sign Out Safely</a>.
    </p>
</div>

<!-- Quick Actions Grid -->
<div class="dt-dash-grid">

    <!-- Card 1: Orders -->
    <a href="<?php echo esc_url( $orders_url ); ?>" class="dt-dash-card">
        <div>
            <div class="dt-dash-card-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                </svg>
            </div>
            <h3 class="dt-dash-card-title"><?php esc_html_e( 'Recent Orders', 'dt-ecommerce-theme' ); ?></h3>
            <p class="dt-dash-card-text"><?php esc_html_e( 'View your history, track shipments, and re-order previous items.', 'dt-ecommerce-theme' ); ?></p>
        </div>
        <span class="dt-dash-card-link">
            <?php esc_html_e( 'View Orders', 'dt-ecommerce-theme' ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </span>
    </a>

    <!-- Card 2: Addresses -->
    <a href="<?php echo esc_url( $address_url ); ?>" class="dt-dash-card">
        <div>
            <div class="dt-dash-card-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <h3 class="dt-dash-card-title"><?php esc_html_e( 'Billing & Shipping', 'dt-ecommerce-theme' ); ?></h3>
            <p class="dt-dash-card-text"><?php esc_html_e( 'Configure default delivery addresses used for smooth checkouts.', 'dt-ecommerce-theme' ); ?></p>
        </div>
        <span class="dt-dash-card-link">
            <?php esc_html_e( 'Manage Addresses', 'dt-ecommerce-theme' ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </span>
    </a>

    <!-- Card 3: Account details -->
    <a href="<?php echo esc_url( $account_url ); ?>" class="dt-dash-card">
        <div>
            <div class="dt-dash-card-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <h3 class="dt-dash-card-title"><?php esc_html_e( 'Account Details', 'dt-ecommerce-theme' ); ?></h3>
            <p class="dt-dash-card-text"><?php esc_html_e( 'Edit password, verify profile details, and adjust preferences.', 'dt-ecommerce-theme' ); ?></p>
        </div>
        <span class="dt-dash-card-link">
            <?php esc_html_e( 'Edit Profile', 'dt-ecommerce-theme' ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </span>
    </a>

</div>

<!-- Status Footer -->
<div class="dt-dash-info-panel">
    <span class="dt-dash-info-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        <?php esc_html_e( 'Verified Account Level', 'dt-ecommerce-theme' ); ?>
    </span>
    <span class="dt-dash-info-meta">
        <?php echo esc_html( $role_label ); ?>
    </span>
</div>
