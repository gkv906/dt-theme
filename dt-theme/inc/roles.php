<?php
/**
 * Custom User Roles
 *
 * Registers Customer, Reseller, Retailer, Wholesaler roles.
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register all custom DT roles.
 * Called on after_switch_theme.
 */
function dt_register_user_roles() {
    // Base capabilities for all shop roles (mirrors WooCommerce customer)
    $base_caps = array(
        'read'                   => true,
        'edit_posts'             => false,
        'delete_posts'           => false,
        'upload_files'           => false,
        'edit_published_posts'   => false,
        'publish_posts'          => false,
        'read_private_posts'     => false,
        'manage_woocommerce'     => false,
    );

    // 1. Customer — standard retail buyer
    if ( ! get_role( 'dt_customer' ) ) {
        add_role(
            'dt_customer',
            __( 'Customer', 'dt-ecommerce-theme' ),
            array_merge( $base_caps, array( 'dt_role_customer' => true ) )
        );
    }

    // 2. Reseller — sells products to end users
    if ( ! get_role( 'dt_reseller' ) ) {
        add_role(
            'dt_reseller',
            __( 'Reseller', 'dt-ecommerce-theme' ),
            array_merge( $base_caps, array( 'dt_role_reseller' => true ) )
        );
    }

    // 3. Retailer — buys in small bulk for retail shop
    if ( ! get_role( 'dt_retailer' ) ) {
        add_role(
            'dt_retailer',
            __( 'Retailer', 'dt-ecommerce-theme' ),
            array_merge( $base_caps, array( 'dt_role_retailer' => true ) )
        );
    }

    // 4. Wholesaler — bulk buyer, lowest price tier
    if ( ! get_role( 'dt_wholesaler' ) ) {
        add_role(
            'dt_wholesaler',
            __( 'Wholesaler', 'dt-ecommerce-theme' ),
            array_merge( $base_caps, array( 'dt_role_wholesaler' => true ) )
        );
    }
}
add_action( 'after_switch_theme', 'dt_register_user_roles' );
add_action( 'init', 'dt_register_user_roles' );

/**
 * Remove custom roles on theme switch-away.
 */
function dt_remove_user_roles() {
    remove_role( 'dt_customer' );
    remove_role( 'dt_reseller' );
    remove_role( 'dt_retailer' );
    remove_role( 'dt_wholesaler' );
}
add_action( 'switch_theme', 'dt_remove_user_roles' );

/**
 * Make custom roles visible in WooCommerce reports & admin user lists.
 *
 * @param array $roles Existing roles.
 * @return array
 */
function dt_add_roles_to_woocommerce( array $roles ): array {
    $roles['dt_customer']    = __( 'Customer', 'dt-ecommerce-theme' );
    $roles['dt_reseller']    = __( 'Reseller', 'dt-ecommerce-theme' );
    $roles['dt_retailer']    = __( 'Retailer', 'dt-ecommerce-theme' );
    $roles['dt_wholesaler']  = __( 'Wholesaler', 'dt-ecommerce-theme' );
    return $roles;
}
add_filter( 'woocommerce_get_customer_available_roles', 'dt_add_roles_to_woocommerce' );

/**
 * Add role selector to registration form front-end and WP admin user creation.
 */
function dt_register_role_field(): void {
    ?>
    <p class="form-row form-row-wide" style="margin: 10px 0;">
        <label for="dt_user_role"><?php esc_html_e( 'Account Type', 'dt-ecommerce-theme' ); ?> <span class="required">*</span></label>
        <select name="dt_user_role" id="dt_user_role" class="input-text" style="width:100%;padding:8px;margin-top:4px;">
            <option value=""><?php esc_html_e( '-- Select Account Type --', 'dt-ecommerce-theme' ); ?></option>
            <option value="dt_customer"><?php esc_html_e( 'Customer (Retail)', 'dt-ecommerce-theme' ); ?></option>
            <option value="dt_reseller"><?php esc_html_e( 'Reseller', 'dt-ecommerce-theme' ); ?></option>
            <option value="dt_retailer"><?php esc_html_e( 'Retailer', 'dt-ecommerce-theme' ); ?></option>
            <option value="dt_wholesaler"><?php esc_html_e( 'Wholesaler', 'dt-ecommerce-theme' ); ?></option>
        </select>
    </p>
    <?php
}
add_action( 'woocommerce_register_form', 'dt_register_role_field' );

/**
 * Save the selected role on WooCommerce registration.
 *
 * @param int $customer_id New user ID.
 */
function dt_save_registration_role( int $customer_id ): void {
    $allowed_roles = array( 'dt_customer', 'dt_reseller', 'dt_retailer', 'dt_wholesaler' );
    $selected_role = isset( $_POST['dt_user_role'] ) ? sanitize_key( $_POST['dt_user_role'] ) : 'dt_customer';

    if ( in_array( $selected_role, $allowed_roles, true ) ) {
        $user = new WP_User( $customer_id );
        $user->set_role( $selected_role );
    }
}
add_action( 'woocommerce_created_customer', 'dt_save_registration_role' );

/**
 * Helper: get readable role label for a user.
 *
 * @param int|null $user_id WP user ID (defaults to current user).
 * @return string
 */
function dt_get_user_role_label( ?int $user_id = null ): string {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return __( 'Guest', 'dt-ecommerce-theme' );
    }
    $role_labels = array(
        'dt_customer'   => __( 'Customer', 'dt-ecommerce-theme' ),
        'dt_reseller'   => __( 'Reseller', 'dt-ecommerce-theme' ),
        'dt_retailer'   => __( 'Retailer', 'dt-ecommerce-theme' ),
        'dt_wholesaler' => __( 'Wholesaler', 'dt-ecommerce-theme' ),
        'administrator' => __( 'Administrator', 'dt-ecommerce-theme' ),
        'editor'        => __( 'Editor', 'dt-ecommerce-theme' ),
        'customer'      => __( 'Customer', 'dt-ecommerce-theme' ),
        'subscriber'    => __( 'Customer', 'dt-ecommerce-theme' ),
    );
    foreach ( $user->roles as $role ) {
        if ( isset( $role_labels[ $role ] ) ) {
            return $role_labels[ $role ];
        }
    }
    return __( 'Customer', 'dt-ecommerce-theme' );
}
