<?php
/**
 * WooCommerce My Account Page Template Override
 * Matches the design of /frontend/public/my-account.html
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$current_user    = wp_get_current_user();
$initials        = strtoupper( substr( $current_user->display_name, 0, 1 ) );
$role_label      = function_exists( 'dt_get_user_role_label' ) ? dt_get_user_role_label() : 'Customer';
?>

<div class="max-w-7xl w-full mx-auto px-4 md:px-8 py-8 flex flex-col md:flex-row gap-8">

    <!-- LEFT SIDEBAR -->
    <aside class="w-full md:w-[260px] shrink-0">
        <div class="md:sticky md:top-28 space-y-6">

            <!-- Profile Card -->
            <div class="flex items-center gap-4 p-4 border border-white/10 bg-white/[0.02]">
                <div class="w-12 h-12 rounded-full bg-[#C8A46A] text-black font-serif text-xl font-bold shrink-0 flex items-center justify-center">
                    <?php echo esc_html( $initials ); ?>
                </div>
                <div class="overflow-hidden">
                    <h3 class="font-serif text-lg truncate text-white"><?php echo esc_html( $current_user->display_name ); ?></h3>
                    <p class="text-xs text-white/50 truncate"><?php echo esc_html( $current_user->user_email ); ?></p>
                    <span class="text-[10px] text-[#C8A46A] uppercase tracking-widest"><?php echo esc_html( $role_label ); ?></span>
                </div>
            </div>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex flex-col space-y-1">
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
                       class="flex items-center gap-3 px-4 py-3 text-sm transition-all border-l-2 text-left
                              <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>"
                       <?php echo $endpoint === wc_get_account_menu_item_classes( $endpoint ) ? 'aria-current="page"' : ''; ?>>
                        <?php
                        // Icon map
                        $icons = array(
                            'dashboard'       => 'layout-dashboard',
                            'orders'          => 'package',
                            'downloads'       => 'download',
                            'edit-address'    => 'map-pin',
                            'payment-methods' => 'credit-card',
                            'edit-account'    => 'user',
                            'customer-logout' => 'log-out',
                        );
                        $icon = isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : 'chevron-right';
                        ?>
                        <i data-lucide="<?php echo esc_attr( $icon ); ?>" class="w-4 h-4"></i>
                        <span class="tracking-wide"><?php echo esc_html( $label ); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Mobile Nav Tabs -->
            <nav class="md:hidden flex overflow-x-auto gap-2 pb-2 border-b border-white/10 no-scrollbar">
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                    $is_active = strpos( wc_get_account_menu_item_classes( $endpoint ), 'is-active' ) !== false;
                    $icons = array(
                        'dashboard'    => 'layout-dashboard',
                        'orders'       => 'package',
                        'downloads'    => 'download',
                        'edit-address' => 'map-pin',
                        'edit-account' => 'user',
                        'customer-logout' => 'log-out',
                    );
                    $icon = isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : 'chevron-right';
                    $active_class = $is_active
                        ? 'border-[#C8A46A] bg-[#C8A46A]/10 text-[#C8A46A]'
                        : 'border-white/10 text-white/70';
                    ?>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
                       class="flex items-center gap-2 px-4 py-2 rounded-full text-xs whitespace-nowrap border <?php echo esc_attr( $active_class ); ?> transition-all">
                        <i data-lucide="<?php echo esc_attr( $icon ); ?>" class="w-3.5 h-3.5"></i>
                        <span><?php echo esc_html( $label ); ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>

        </div>
    </aside>

    <!-- RIGHT MAIN AREA -->
    <main class="flex-1 min-w-0">
        <?php
        // Output WooCommerce notices (success messages, validation errors, etc.)
        if ( function_exists( 'wc_print_notices' ) ) {
            wc_print_notices();
        }
        do_action( 'woocommerce_account_content' );
        ?>
    </main>

</div>

<?php get_footer(); ?>
