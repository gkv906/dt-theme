<?php
/**
 * WordPress + WooCommerce Function Stubs for IDE support.
 *
 * This file is NEVER loaded at runtime. It exists only to help PHP language
 * servers (e.g. Intelephense, PHPCS) recognise WordPress core functions and
 * suppress false "Undefined function" diagnostics.
 *
 * @package DT_Ecommerce_Theme
 */

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Escaping & i18n
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'esc_html' ) ) { function esc_html( string $text ): string { return $text; } }
if ( ! function_exists( 'esc_html_e' ) ) { function esc_html_e( string $text, string $domain = 'default' ): void { echo $text; } }
if ( ! function_exists( 'esc_html__' ) ) { function esc_html__( string $text, string $domain = 'default' ): string { return $text; } }
if ( ! function_exists( 'esc_attr' ) ) { function esc_attr( string $text ): string { return $text; } }
if ( ! function_exists( 'esc_attr_e' ) ) { function esc_attr_e( string $text, string $domain = 'default' ): void { echo $text; } }
if ( ! function_exists( 'esc_attr__' ) ) { function esc_attr__( string $text, string $domain = 'default' ): string { return $text; } }
if ( ! function_exists( 'esc_url' ) ) { function esc_url( string $url, ?array $protocols = null, string $context = 'display' ): string { return $url; } }
if ( ! function_exists( 'esc_url_raw' ) ) { function esc_url_raw( string $url, ?array $protocols = null ): string { return $url; } }
if ( ! function_exists( 'esc_js' ) ) { function esc_js( string $text ): string { return $text; } }
if ( ! function_exists( 'esc_textarea' ) ) { function esc_textarea( string $text ): string { return $text; } }
if ( ! function_exists( 'wp_kses' ) ) { function wp_kses( string $string, array $allowed_html, array $allowed_protocols = [] ): string { return $string; } }
if ( ! function_exists( 'wp_kses_post' ) ) { function wp_kses_post( string $data ): string { return $data; } }
if ( ! function_exists( '__' ) ) { function __( string $text, string $domain = 'default' ): string { return $text; } }
if ( ! function_exists( '_e' ) ) { function _e( string $text, string $domain = 'default' ): void { echo $text; } }
if ( ! function_exists( '_n' ) ) { function _n( string $single, string $plural, int $number, string $domain = 'default' ): string { return $number === 1 ? $single : $plural; } }
if ( ! function_exists( '_nx' ) ) { function _nx( string $single, string $plural, int $number, string $context, string $domain = 'default' ): string { return $number === 1 ? $single : $plural; } }
if ( ! function_exists( '_x' ) ) { function _x( string $text, string $context, string $domain = 'default' ): string { return $text; } }
if ( ! function_exists( 'number_format_i18n' ) ) { function number_format_i18n( float $number, int $decimals = 0 ): string { return number_format( $number, $decimals ); } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Sanitisation
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'sanitize_text_field' ) ) { function sanitize_text_field( string $str ): string { return $str; } }
if ( ! function_exists( 'sanitize_email' ) ) { function sanitize_email( string $email ): string { return $email; } }
if ( ! function_exists( 'sanitize_textarea_field' ) ) { function sanitize_textarea_field( string $str ): string { return $str; } }
if ( ! function_exists( 'sanitize_key' ) ) { function sanitize_key( string $key ): string { return $key; } }
if ( ! function_exists( 'sanitize_title' ) ) { function sanitize_title( string $title, string $fallback_title = '', string $context = 'save' ): string { return $title; } }
if ( ! function_exists( 'wp_unslash' ) ) { function wp_unslash( mixed $value ): mixed { return $value; } }
if ( ! function_exists( 'absint' ) ) { function absint( mixed $maybeint ): int { return abs( (int) $maybeint ); } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: URLs & Paths
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'home_url' ) ) { function home_url( $path = '', $scheme = null ) { return 'http://localhost' . $path; } }
if ( ! function_exists( 'site_url' ) ) { function site_url( $path = '', $scheme = null ) { return 'http://localhost' . $path; } }
if ( ! function_exists( 'admin_url' ) ) { function admin_url( $path = '', $scheme = 'admin' ) { return 'http://localhost/wp-admin/' . $path; } }
if ( ! function_exists( 'get_template_directory_uri' ) ) { function get_template_directory_uri() { return ''; } }
if ( ! function_exists( 'get_template_directory' ) ) { function get_template_directory() { return ''; } }
if ( ! function_exists( 'get_stylesheet_directory_uri' ) ) { function get_stylesheet_directory_uri() { return ''; } }
if ( ! function_exists( 'get_stylesheet_directory' ) ) { function get_stylesheet_directory() { return ''; } }
if ( ! function_exists( 'get_permalink' ) ) { function get_permalink( $post = 0, $leavename = false ) { return ''; } }
if ( ! function_exists( 'remove_query_arg' ) ) { function remove_query_arg( $key, $query = false ) { return ''; } }
if ( ! function_exists( 'add_query_arg' ) ) { function add_query_arg( string|array $key, string|int|false $value = false, string|false $url = false ): string { return ''; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Template Tags
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'get_header' ) ) { function get_header( $name = null, $args = [] ) {} }
if ( ! function_exists( 'get_footer' ) ) { function get_footer( $name = null, $args = [] ) {} }
if ( ! function_exists( 'get_sidebar' ) ) { function get_sidebar( $name = null, $args = [] ) {} }
if ( ! function_exists( 'get_template_part' ) ) { function get_template_part( $slug, $name = null, $args = [] ) {} }
if ( ! function_exists( 'wp_head' ) ) { function wp_head() {} }
if ( ! function_exists( 'wp_footer' ) ) { function wp_footer() {} }
if ( ! function_exists( 'body_class' ) ) { function body_class( $class = '' ) {} }
if ( ! function_exists( 'bloginfo' ) ) { function bloginfo( $show = '' ) {} }
if ( ! function_exists( 'get_bloginfo' ) ) { function get_bloginfo( $show = '', $filter = 'raw' ) { return ''; } }
if ( ! function_exists( 'language_attributes' ) ) { function language_attributes( $doctype = 'html' ) {} }
if ( ! function_exists( 'charset_url' ) ) { function charset_url() {} }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: The Loop & Post
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'have_posts' ) ) { function have_posts() { return false; } }
if ( ! function_exists( 'the_post' ) ) { function the_post() {} }
if ( ! function_exists( 'the_content' ) ) { function the_content( $more_link_text = null, $strip_teaser = false ) {} }
if ( ! function_exists( 'the_title' ) ) { function the_title( $before = '', $after = '', $echo = true ) { return ''; } }
if ( ! function_exists( 'the_excerpt' ) ) { function the_excerpt() {} }
if ( ! function_exists( 'the_permalink' ) ) { function the_permalink( $post = 0 ) {} }
if ( ! function_exists( 'the_ID' ) ) { function the_ID() {} }
if ( ! function_exists( 'get_the_ID' ) ) { function get_the_ID() { return 0; } }
if ( ! function_exists( 'get_the_title' ) ) { function get_the_title( $post = 0 ) { return ''; } }
if ( ! function_exists( 'get_the_excerpt' ) ) { function get_the_excerpt( $post = null ) { return ''; } }
if ( ! function_exists( 'get_the_date' ) ) { function get_the_date( $format = '', $post = null ) { return ''; } }
if ( ! function_exists( 'get_the_time' ) ) { function get_the_time( $format = '', $post = null ) { return ''; } }
if ( ! function_exists( 'get_the_author' ) ) { function get_the_author() { return ''; } }
if ( ! function_exists( 'get_the_post_thumbnail_url' ) ) { function get_the_post_thumbnail_url( $post = null, $size = 'post-thumbnail' ) { return ''; } }
if ( ! function_exists( 'get_the_post_thumbnail' ) ) { function get_the_post_thumbnail( $post = null, $size = 'post-thumbnail', $attr = '' ) { return ''; } }
if ( ! function_exists( 'get_the_terms' ) ) { function get_the_terms( $post, $taxonomy ) { return []; } }
if ( ! function_exists( 'get_the_category' ) ) { function get_the_category( $id = false ) { return []; } }
if ( ! function_exists( 'wp_reset_postdata' ) ) { function wp_reset_postdata() {} }
if ( ! function_exists( 'wp_reset_query' ) ) { function wp_reset_query() {} }
if ( ! function_exists( 'setup_postdata' ) ) { function setup_postdata( $post ) { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Comments
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'have_comments' ) ) { function have_comments() { return false; } }
if ( ! function_exists( 'comments_open' ) ) { function comments_open( $post_id = null ) { return false; } }
if ( ! function_exists( 'get_comments_number' ) ) { function get_comments_number( $post_id = 0 ) { return 0; } }
if ( ! function_exists( 'wp_list_comments' ) ) { function wp_list_comments( $args = [], $comments = null ) {} }
if ( ! function_exists( 'comment_form' ) ) { function comment_form( $args = [], $post = null ) {} }
if ( ! function_exists( 'the_comments_navigation' ) ) { function the_comments_navigation( $args = [] ) {} }
if ( ! function_exists( 'post_password_required' ) ) { function post_password_required( $post = null ) { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Menus, Sidebars, Widgets
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wp_nav_menu' ) ) { function wp_nav_menu( $args = [] ) {} }
if ( ! function_exists( 'is_active_sidebar' ) ) { function is_active_sidebar( $index ) { return false; } }
if ( ! function_exists( 'dynamic_sidebar' ) ) { function dynamic_sidebar( $index = 1 ) { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Hooks
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'add_action' ) ) { function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) { return true; } }
if ( ! function_exists( 'add_filter' ) ) { function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) { return true; } }
if ( ! function_exists( 'do_action' ) ) { function do_action( $tag, ...$arg ) {} }
if ( ! function_exists( 'apply_filters' ) ) { function apply_filters( string $tag, mixed $value, mixed ...$args ): mixed { return $value; } }
if ( ! function_exists( 'remove_action' ) ) { function remove_action( $tag, $function_to_remove, $priority = 10 ) { return true; } }
if ( ! function_exists( 'remove_filter' ) ) { function remove_filter( $tag, $function_to_remove, $priority = 10 ) { return true; } }
if ( ! function_exists( 'has_action' ) ) { function has_action( $tag, $function_to_check = false ) { return false; } }
if ( ! function_exists( 'has_filter' ) ) { function has_filter( $tag, $function_to_check = false ) { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Options & Metadata
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'get_option' ) ) { function get_option( $option, $default = false ) { return $default; } }
if ( ! function_exists( 'update_option' ) ) { function update_option( $option, $value, $autoload = null ) { return false; } }
if ( ! function_exists( 'add_option' ) ) { function add_option( $option, $value = '', $deprecated = '', $autoload = 'yes' ) { return false; } }
if ( ! function_exists( 'delete_option' ) ) { function delete_option( $option ) { return false; } }
if ( ! function_exists( 'get_post_meta' ) ) { function get_post_meta( $post_id, $key = '', $single = false ) { return ''; } }
if ( ! function_exists( 'update_post_meta' ) ) { function update_post_meta( $post_id, $meta_key, $meta_value, $prev_value = '' ) { return false; } }
if ( ! function_exists( 'add_post_meta' ) ) { function add_post_meta( $post_id, $meta_key, $meta_value, $unique = false ) { return false; } }
if ( ! function_exists( 'get_user_meta' ) ) { function get_user_meta( $user_id, $key = '', $single = false ) { return ''; } }
if ( ! function_exists( 'update_user_meta' ) ) { function update_user_meta( $user_id, $meta_key, $meta_value, $prev_value = '' ) { return false; } }
if ( ! function_exists( 'get_theme_mod' ) ) { function get_theme_mod( $name, $default = false ) { return $default; } }
if ( ! function_exists( 'set_theme_mod' ) ) { function set_theme_mod( $name, $value ) {} }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Security
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wp_create_nonce' ) ) { function wp_create_nonce( $action = -1 ) { return ''; } }
if ( ! function_exists( 'wp_verify_nonce' ) ) { function wp_verify_nonce( $nonce, $action = -1 ) { return false; } }
if ( ! function_exists( 'check_ajax_referer' ) ) { function check_ajax_referer( $action = -1, $query_arg = false, $die = true ) { return 1; } }
if ( ! function_exists( 'wp_nonce_field' ) ) { function wp_nonce_field( $action = -1, $name = '_wpnonce', $referer = true, $echo = true ) { return ''; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: AJAX
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wp_send_json_success' ) ) { function wp_send_json_success( $data = null, $status_code = null, $flags = 0 ) {} }
if ( ! function_exists( 'wp_send_json_error' ) ) { function wp_send_json_error( $data = null, $status_code = null, $flags = 0 ) {} }
if ( ! function_exists( 'wp_send_json' ) ) { function wp_send_json( $response, $status_code = null, $flags = 0 ) {} }
if ( ! function_exists( 'wp_localize_script' ) ) { function wp_localize_script( $handle, $object_name, $l10n ) { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Scripts & Styles
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wp_enqueue_script' ) ) { function wp_enqueue_script( $handle, $src = '', $deps = [], $ver = false, $args = false ) {} }
if ( ! function_exists( 'wp_enqueue_style' ) ) { function wp_enqueue_style( $handle, $src = '', $deps = [], $ver = false, $media = 'all' ) {} }
if ( ! function_exists( 'wp_register_script' ) ) { function wp_register_script( $handle, $src, $deps = [], $ver = false, $args = false ) { return false; } }
if ( ! function_exists( 'wp_register_style' ) ) { function wp_register_style( $handle, $src, $deps = [], $ver = false, $media = 'all' ) { return false; } }
if ( ! function_exists( 'wp_dequeue_script' ) ) { function wp_dequeue_script( $handle ) {} }
if ( ! function_exists( 'wp_dequeue_style' ) ) { function wp_dequeue_style( $handle ) {} }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: Misc
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'is_wp_error' ) ) { function is_wp_error( $thing ) { return false; } }
if ( ! function_exists( 'is_user_logged_in' ) ) { function is_user_logged_in() { return false; } }
if ( ! function_exists( 'wp_get_current_user' ) ) { function wp_get_current_user() { return new stdClass(); } }
if ( ! function_exists( 'get_current_user_id' ) ) { function get_current_user_id() { return 0; } }
if ( ! function_exists( 'is_front_page' ) ) { function is_front_page() { return false; } }
if ( ! function_exists( 'is_home' ) ) { function is_home() { return false; } }
if ( ! function_exists( 'is_page' ) ) { function is_page( $page = '' ) { return false; } }
if ( ! function_exists( 'is_single' ) ) { function is_single( $post = '' ) { return false; } }
if ( ! function_exists( 'is_archive' ) ) { function is_archive() { return false; } }
if ( ! function_exists( 'is_search' ) ) { function is_search() { return false; } }
if ( ! function_exists( 'is_404' ) ) { function is_404() { return false; } }
if ( ! function_exists( 'is_tax' ) ) { function is_tax( $taxonomy = '', $term = '' ) { return false; } }
if ( ! function_exists( 'is_admin' ) ) { function is_admin() { return false; } }
if ( ! function_exists( 'current_user_can' ) ) { function current_user_can( $capability, ...$args ) { return false; } }
if ( ! function_exists( 'selected' ) ) { function selected( $selected, $current = true, $echo = true ) { return ''; } }
if ( ! function_exists( 'checked' ) ) { function checked( $checked, $current = true, $echo = true ) { return ''; } }
if ( ! function_exists( 'wp_redirect' ) ) { function wp_redirect( $location, $status = 302, $x_redirect_by = 'WordPress' ) { return false; } }
if ( ! function_exists( 'get_terms' ) ) { function get_terms( $args = [], $deprecated = '' ) { return []; } }
if ( ! function_exists( 'get_term_link' ) ) { function get_term_link( $term, $taxonomy = '' ) { return ''; } }
if ( ! function_exists( 'wp_get_attachment_url' ) ) { function wp_get_attachment_url( $attachment_id = 0 ) { return ''; } }
if ( ! function_exists( 'add_theme_support' ) ) { function add_theme_support( $feature, ...$args ) {} }
if ( ! function_exists( 'register_nav_menus' ) ) { function register_nav_menus( $locations = [] ) {} }
if ( ! function_exists( 'register_sidebar' ) ) { function register_sidebar( $args = [] ) { return ''; } }
if ( ! function_exists( 'load_theme_textdomain' ) ) { function load_theme_textdomain( $domain, $path = false ) { return false; } }
if ( ! function_exists( 'add_image_size' ) ) { function add_image_size( $name, $width = 0, $height = 0, $crop = false ) {} }
if ( ! function_exists( 'wp_mail' ) ) { function wp_mail( $to, $subject, $message, $headers = '', $attachments = [] ) { return false; } }
if ( ! function_exists( 'get_search_query' ) ) { function get_search_query( $escaped = true ) { return ''; } }
if ( ! function_exists( 'the_search_form' ) ) { function the_search_form( $echo = true ) { return ''; } }
if ( ! function_exists( 'previous_posts_link' ) ) { function previous_posts_link( $label = null ) {} }
if ( ! function_exists( 'next_posts_link' ) ) { function next_posts_link( $label = null, $max_page = 0 ) {} }
if ( ! function_exists( 'the_posts_navigation' ) ) { function the_posts_navigation( $args = [] ) {} }
if ( ! function_exists( 'wp_parse_args' ) ) { function wp_parse_args( $args, $defaults = [] ) { return []; } }

// ─────────────────────────────────────────────────────────────────────────────
// WooCommerce Core Functions
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wc_get_cart_url' ) ) { function wc_get_cart_url() { return ''; } }
if ( ! function_exists( 'wc_get_checkout_url' ) ) { function wc_get_checkout_url() { return ''; } }
if ( ! function_exists( 'wc_get_page_id' ) ) { function wc_get_page_id( $page ) { return 0; } }
if ( ! function_exists( 'wc_get_order' ) ) { function wc_get_order( $order_id = false ) { return false; } }
if ( ! function_exists( 'wc_placeholder_img_src' ) ) { function wc_placeholder_img_src( $size = 'woocommerce_thumbnail' ) { return ''; } }
if ( ! function_exists( 'wc_placeholder_img' ) ) { function wc_placeholder_img( $size = 'woocommerce_thumbnail', $atts = [] ) { return ''; } }
if ( ! function_exists( 'wc_get_loop_prop' ) ) { function wc_get_loop_prop( $prop, $default = null ) { return $default; } }
if ( ! function_exists( 'wc_set_loop_prop' ) ) { function wc_set_loop_prop( $prop, $value = null ) {} }
if ( ! function_exists( 'woocommerce_product_loop' ) ) { function woocommerce_product_loop() { return false; } }
if ( ! function_exists( 'wc_clean' ) ) { function wc_clean( mixed $var ): mixed { return $var; } }
if ( ! function_exists( 'wc_query_string_form_fields' ) ) { function wc_query_string_form_fields( $values = null, $exclude = [], $current_key = '', $return = false ) { return ''; } }
if ( ! function_exists( 'woocommerce_breadcrumb' ) ) { function woocommerce_breadcrumb( $args = [] ) {} }
if ( ! function_exists( 'woocommerce_output_content_wrapper' ) ) { function woocommerce_output_content_wrapper() {} }
if ( ! function_exists( 'woocommerce_output_content_wrapper_end' ) ) { function woocommerce_output_content_wrapper_end() {} }
if ( ! function_exists( 'woocommerce_get_template_part' ) ) { function woocommerce_get_template_part( $slug, $name = '' ) {} }
if ( ! function_exists( 'woocommerce_template_loop_product_link_open' ) ) { function woocommerce_template_loop_product_link_open() {} }
if ( ! function_exists( 'woocommerce_template_loop_product_link_close' ) ) { function woocommerce_template_loop_product_link_close() {} }
if ( ! function_exists( 'woocommerce_template_single_add_to_cart' ) ) { function woocommerce_template_single_add_to_cart() {} }
if ( ! function_exists( 'woocommerce_template_single_price' ) ) { function woocommerce_template_single_price() {} }
if ( ! function_exists( 'woocommerce_template_single_rating' ) ) { function woocommerce_template_single_rating() {} }
if ( ! function_exists( 'woocommerce_output_related_products' ) ) { function woocommerce_output_related_products() {} }
if ( ! function_exists( 'woocommerce_output_upsells' ) ) { function woocommerce_output_upsells() {} }
if ( ! function_exists( 'woocommerce_product_tabs' ) ) { function woocommerce_product_tabs() {} }
if ( ! function_exists( 'wc_get_product' ) ) { function wc_get_product( $the_product = false ) { return false; } }
if ( ! function_exists( 'wc_price' ) ) { function wc_price( $price, $args = [] ) { return ''; } }
if ( ! function_exists( 'wc_format_sale_price' ) ) { function wc_format_sale_price( $regular_price, $sale_price ) { return ''; } }
if ( ! function_exists( 'wc_get_rating_html' ) ) { function wc_get_rating_html( $rating, $count = 0 ) { return ''; } }
if ( ! function_exists( 'is_woocommerce' ) ) { function is_woocommerce() { return false; } }
if ( ! function_exists( 'is_shop' ) ) { function is_shop() { return false; } }
if ( ! function_exists( 'is_product_category' ) ) { function is_product_category( $term = '' ) { return false; } }
if ( ! function_exists( 'is_product' ) ) { function is_product() { return false; } }
if ( ! function_exists( 'is_cart' ) ) { function is_cart() { return false; } }
if ( ! function_exists( 'is_checkout' ) ) { function is_checkout() { return false; } }
if ( ! function_exists( 'is_account_page' ) ) { function is_account_page() { return false; } }
if ( ! function_exists( 'wc_add_to_cart_message' ) ) { function wc_add_to_cart_message( $products, $show_qty = false, $return = false ) { return ''; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Classes (type stubs)
// ─────────────────────────────────────────────────────────────────────────────
if ( ! class_exists( 'WP_Query' ) ) {
    class WP_Query {
        /** @var array */
        public $posts = [];
        /** @var int */
        public $post_count = 0;
        /** @var int */
        public $found_posts = 0;
        public function __construct( array $args = [] ) {}
        public function have_posts(): bool { return false; }
        public function the_post(): void {}
        public function get_posts(): array { return []; }
    }
}
if ( ! class_exists( 'WP_Error' ) ) {
    class WP_Error {
        public function __construct( $code = '', $message = '', $data = '' ) {}
        public function get_error_message( $code = '' ): string { return ''; }
        public function get_error_code(): string { return ''; }
    }
}
if ( ! class_exists( 'WP_User' ) ) {
    class WP_User {
        /** @var int */
        public $ID = 0;
        /** @var string */
        public $user_login = '';
        /** @var string */
        public $user_email = '';
        /** @var string */
        public $display_name = '';
        /** @var string */
        public $user_pass = '';
        /** @var array */
        public $roles = [];
        public function __construct( $id = 0, $name = '', $site_id = '' ) {}
        public function has_cap( string $cap ): bool { return false; }
        public function set_role( string $role ): void {}
        public function add_role( string $role ): void {}
        public function remove_role( string $role ): void {}
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// WooCommerce Classes (type stubs)
// ─────────────────────────────────────────────────────────────────────────────
if ( ! class_exists( 'WC_Product' ) ) {
    class WC_Product {
        public function get_id(): int { return 0; }
        public function get_price(): string { return ''; }
        public function get_regular_price(): string { return ''; }
        public function get_sale_price(): string { return ''; }
        public function get_average_rating(): float { return 0.0; }
        public function get_gallery_image_ids(): array { return []; }
        public function get_name(): string { return ''; }
        public function get_slug(): string { return ''; }
        public function get_permalink(): string { return ''; }
        public function get_stock_status(): string { return 'instock'; }
        public function get_stock_quantity(): ?int { return null; }
        public function is_in_stock(): bool { return true; }
        public function is_on_sale(): bool { return false; }
        public function set_price( float $price ): void {}
        public function get_type(): string { return 'simple'; }
        public function get_meta( string $key, bool $single = true, string $context = 'view' ): mixed { return ''; }
    }
}
if ( ! class_exists( 'WC_Cart' ) ) {
    class WC_Cart {
        public function get_cart(): array { return []; }
        public function get_cart_contents_count(): int { return 0; }
        public function get_cart_subtotal(): string { return ''; }
        public function get_total(): string { return ''; }
        public function is_empty(): bool { return true; }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Missing WordPress Core Stubs (Errors 1–8 + 1 info)
// ─────────────────────────────────────────────────────────────────────────────

// Error 1: Undefined function 'get_stylesheet_uri' — line 56
if ( ! function_exists( 'get_stylesheet_uri' ) ) { function get_stylesheet_uri(): string { return ''; } }

// Error 2 & 6: Undefined function 'wp_add_inline_style' — line 64
if ( ! function_exists( 'wp_add_inline_style' ) ) { function wp_add_inline_style( string $handle, string $data ): bool { return false; } }

// Error 3 & 7 (info): Undefined function 'wp_add_inline_script' — lines 85, 123
// Info: "Too many arguments. Expected 0. Found 3." — fixed by providing correct signature
if ( ! function_exists( 'wp_add_inline_script' ) ) { function wp_add_inline_script( string $handle, string $data, string $position = 'after' ): bool { return false; } }

// Error 4 & 5: Undefined function 'WC' — line 96
// WooCommerce global function returning WooCommerce instance
if ( ! function_exists( 'WC' ) ) {
    function WC(): WooCommerce {
        return WooCommerce::instance();
    }
}
if ( ! class_exists( 'WooCommerce' ) ) {
    class WooCommerce {
        /** @var WC_Cart|null */
        public $cart = null;
        /** @var WC_Session|null */
        public $session = null;
        /** @var WC_Customer|null */
        public $customer = null;
        public static function instance(): self { return new self(); }
    }
}

// Error 7: Undefined function 'get_page_by_path' — line 226
// Fix: define OBJECT constant + WP_Post class + WC_Session + WC_Customer stubs
if ( ! defined( 'OBJECT' ) )  { define( 'OBJECT',  'OBJECT' ); }
if ( ! defined( 'ARRAY_A' ) ) { define( 'ARRAY_A', 'ARRAY_A' ); }
if ( ! defined( 'ARRAY_N' ) ) { define( 'ARRAY_N', 'ARRAY_N' ); }

// WP_Post stub (Error 2)
if ( ! class_exists( 'WP_Post' ) ) {
    class WP_Post {
        /** @var int */
        public $ID = 0;
        /** @var string */
        public $post_title = '';
        /** @var string */
        public $post_name = '';
        /** @var string */
        public $post_status = 'publish';
        /** @var string */
        public $post_type = 'post';
        /** @var string */
        public $post_content = '';
        public function __construct( object $post = null ) {}
    }
}

// WC_Session stub (Info 1)
if ( ! class_exists( 'WC_Session' ) ) {
    class WC_Session {
        public function get( string $key, mixed $default = null ): mixed { return $default; }
        public function set( string $key, mixed $value ): void {}
        public function has_session(): bool { return false; }
    }
}

// WC_Customer stub (Info 2)
if ( ! class_exists( 'WC_Customer' ) ) {
    class WC_Customer {
        public function get_id(): int { return 0; }
        public function get_email(): string { return ''; }
        public function get_billing_country(): string { return ''; }
        public function get_shipping_country(): string { return ''; }
    }
}

if ( ! function_exists( 'get_page_by_path' ) ) {
    function get_page_by_path( string $page_path, string $output = OBJECT, string|array $post_type = 'page' ): WP_Post|array|null { return null; }
}

// Error 8: Undefined function 'wp_insert_post' — line 228
if ( ! function_exists( 'wp_insert_post' ) ) {
    function wp_insert_post( array $postarr, bool $wp_error = false, bool $fire_after_hooks = true ): int|\WP_Error { return 0; }
}

// Additional commonly-needed stubs
if ( ! function_exists( 'wp_body_open' ) ) { function wp_body_open(): void {} }
if ( ! function_exists( 'get_posts' ) ) { function get_posts( array $args = [] ): array { return []; } }
if ( ! function_exists( 'filemtime' ) ) { function filemtime( string $filename ): int|false { return 0; } }
if ( ! function_exists( 'is_product_tag' ) ) { function is_product_tag( string $term = '' ): bool { return false; } }
if ( ! function_exists( 'get_queried_object' ) ) { function get_queried_object(): mixed { return null; } }
if ( ! function_exists( 'wp_json_encode' ) ) { function wp_json_encode( mixed $data, int $options = 0, int $depth = 512 ): string|false { return json_encode( $data, $options, $depth ); } }
if ( ! function_exists( 'setcookie' ) ) { } // PHP built-in — suppress false undefined
if ( ! function_exists( 'add_menu_page' ) ) { function add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, ?callable $callback = null, string $icon_url = '', int|float|null $position = null ): string { return ''; } }
if ( ! function_exists( 'add_submenu_page' ) ) { function add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, ?callable $callback = null, int|float|null $position = null ): string|false { return ''; } }
if ( ! function_exists( 'wp_die' ) ) { function wp_die( string|WP_Error $message = '', string|int $title = '', string|array $args = [] ): void {} }
if ( ! function_exists( 'sanitize_hex_color' ) ) { function sanitize_hex_color( string $color ): ?string { return $color; } }
if ( ! function_exists( 'WP_Filesystem' ) ) { function WP_Filesystem( array|false $args = false, string|false $context = false, bool $allow_relaxed_file_ownership = false ): bool { return false; } }

// ─────────────────────────────────────────────────────────────────────────────
// WordPress Core: User / Authentication
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wp_logout_url' ) ) { function wp_logout_url( string $redirect = '' ): string { return ''; } }
if ( ! function_exists( 'wp_login_url' ) )  { function wp_login_url( string $redirect = '', bool $force_reauth = false ): string { return ''; } }
if ( ! function_exists( 'wp_lostpassword_url' ) ) { function wp_lostpassword_url( string $redirect = '' ): string { return ''; } }
if ( ! function_exists( 'is_email' ) )         { function is_email( string $email ): string|false { return false; } }
if ( ! function_exists( 'email_exists' ) )     { function email_exists( string $email ): int|false { return false; } }
if ( ! function_exists( 'username_exists' ) )  { function username_exists( string $username ): int|false { return false; } }
if ( ! function_exists( 'sanitize_user' ) )    { function sanitize_user( string $username, bool $strict = false ): string { return $username; } }
if ( ! function_exists( 'is_ssl' ) )           { function is_ssl(): bool { return false; } }
if ( ! function_exists( 'current_time' ) )     { function current_time( string $type, bool $gmt = false ): string|int { return ''; } }
if ( ! function_exists( 'get_users' ) )        { function get_users( array $args = [] ): array { return []; } }
if ( ! function_exists( 'get_userdata' ) )     { function get_userdata( int $user_id ): WP_User|false { return false; } }
if ( ! function_exists( 'get_user_by' ) )      { function get_user_by( string $field, mixed $value ): WP_User|false { return false; } }
if ( ! function_exists( 'wp_signon' ) )        { function wp_signon( array $credentials = [], bool $secure_cookie = false ): WP_User|WP_Error { return new WP_User(); } }
if ( ! function_exists( 'wp_create_user' ) )   { function wp_create_user( string $username, string $password, string $email = '' ): int|WP_Error { return 0; } }
if ( ! function_exists( 'wp_update_user' ) )   { function wp_update_user( array|WP_User $userdata ): int|WP_Error { return 0; } }
if ( ! function_exists( 'wp_set_current_user' ) ) { function wp_set_current_user( int|null $id, string $name = '' ): WP_User { return new WP_User(); } }
if ( ! function_exists( 'wp_set_auth_cookie' ) )  { function wp_set_auth_cookie( int $user_id, bool $remember = false, bool|string $secure = '', string $token = '' ): void {} }
if ( ! function_exists( 'get_password_reset_key' ) ) { function get_password_reset_key( WP_User $user ): string|WP_Error { return ''; } }
if ( ! function_exists( 'network_site_url' ) ) { function network_site_url( string $path = '', string|null $scheme = null ): string { return ''; } }
if ( ! function_exists( 'rawurlencode' ) ) {} // PHP built-in — suppress false undefined

// ─────────────────────────────────────────────────────────────────────────────
// WooCommerce: Account / User
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wc_get_account_endpoint_url' ) ) { function wc_get_account_endpoint_url( string $endpoint ): string { return ''; } }

// ─────────────────────────────────────────────────────────────────────────────
// WooCommerce: Template / URL Helpers
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wc_get_endpoint_url' ) )     { function wc_get_endpoint_url( string $endpoint, string $value = '', string $permalink = '' ): string { return ''; } }
if ( ! function_exists( 'wc_get_page_permalink' ) )   { function wc_get_page_permalink( string $page ): string { return ''; } }
if ( ! function_exists( 'wc_get_post_data_by_key' ) ) { function wc_get_post_data_by_key( string $key, mixed $default = '' ): mixed { return $default; } }
if ( ! function_exists( 'wc_address_to_edit' ) )      { function wc_address_to_edit( string $type ): string { return $type; } }
if ( ! function_exists( 'wc_edit_address_i18n' ) )    { function wc_edit_address_i18n( string $value, bool $reverse = false ): string { return $value; } }
if ( ! function_exists( 'wc_get_account_address_types' ) ) { function wc_get_account_address_types(): array { return array( 'billing', 'shipping' ); } }
if ( ! function_exists( 'wc_get_account_customer_address' ) ) { function wc_get_account_customer_address( string $address_type ): string { return ''; } }
if ( ! function_exists( 'wc_get_account_addresses' ) )       { function wc_get_account_addresses(): array { return array(); } }
if ( ! function_exists( 'wc_get_account_menu_items' ) )      { function wc_get_account_menu_items(): array { return array(); } }
if ( ! function_exists( 'wc_get_account_menu_item_classes' ) ){ function wc_get_account_menu_item_classes( string $endpoint ): string { return ''; } }
if ( ! function_exists( 'wc_get_account_orders_query_args' ) ){ function wc_get_account_orders_query_args(): array|false { return array(); } }
if ( ! function_exists( 'woocommerce_form_field' ) )  { function woocommerce_form_field( string $key, array $args, mixed $value = null ): void {} }
if ( ! function_exists( 'wc_clean' ) )                { function wc_clean( mixed $var ): mixed { return $var; } }
if ( ! function_exists( 'wc_shipping_enabled' ) )     { function wc_shipping_enabled(): bool { return true; } }
if ( ! function_exists( 'wc_price' ) )                { function wc_price( float $price, array $args = array() ): string { return ''; } }
if ( ! function_exists( 'wc_get_orders' ) )           { function wc_get_orders( array $args ): array { return array(); } }
if ( ! function_exists( 'wc_get_order' ) )            { function wc_get_order( int|bool $order_id = false ): WC_Order|bool { return false; } }
if ( ! function_exists( 'wc_add_notice' ) )           { function wc_add_notice( string $message, string $notice_type = 'success', array $data = array() ): void {} }
if ( ! function_exists( 'wc_print_notices' ) )        { function wc_print_notices(): void {} }

// ─────────────────────────────────────────────────────────────────────────────
// WooCommerce: Classes
// ─────────────────────────────────────────────────────────────────────────────

if ( ! class_exists( 'WC_Countries' ) ) {
    class WC_Countries {
        /**
         * Get address fields for a given country.
         * @param  string $country ISO2 country code
         * @param  string $type    Field prefix e.g. 'billing_'
         * @return array<string, array<string, mixed>>
         */
        public function get_address_fields( string $country = '', string $type = 'billing_' ): array { return array(); }
        /** @return array<string, string> Country code => Country name */
        public function get_allowed_countries(): array { return array(); }
        /** @return array<string, array<string, string>> Country code => [state code => state name] */
        public function get_states( string $country = '' ): array { return array(); }
        /** @return string ISO2 code of the shop's base country */
        public function get_base_country(): string { return 'IN'; }
        /** @return array<string, string> */
        public function get_shipping_countries(): array { return array(); }
        /**
         * @param array $address
         * @param string $separator
         * @return string
         */
        public function get_formatted_address( array $address, string $separator = '<br/>' ): string { return ''; }
    }
}

if ( ! class_exists( 'WC_Customer' ) ) {
    class WC_Customer {
        public function __construct( int $customer_id = 0, bool $is_session = false ) {}
        public function get_billing_first_name(): string  { return ''; }
        public function get_billing_last_name(): string   { return ''; }
        public function get_billing_company(): string     { return ''; }
        public function get_billing_address_1(): string   { return ''; }
        public function get_billing_address_2(): string   { return ''; }
        public function get_billing_city(): string        { return ''; }
        public function get_billing_state(): string       { return ''; }
        public function get_billing_postcode(): string    { return ''; }
        public function get_billing_country(): string     { return ''; }
        public function get_billing_phone(): string       { return ''; }
        public function get_billing_email(): string       { return ''; }
        public function get_shipping_first_name(): string { return ''; }
        public function get_shipping_last_name(): string  { return ''; }
        public function get_shipping_company(): string    { return ''; }
        public function get_shipping_address_1(): string  { return ''; }
        public function get_shipping_address_2(): string  { return ''; }
        public function get_shipping_city(): string       { return ''; }
        public function get_shipping_state(): string      { return ''; }
        public function get_shipping_postcode(): string   { return ''; }
        public function get_shipping_country(): string    { return ''; }
        public function get_shipping_phone(): string      { return ''; }
        public function save(): int                       { return 0; }
        public function get_id(): int                     { return 0; }
    }
}

if ( ! class_exists( 'WC_Order' ) ) {
    class WC_Order {
        public function get_id(): int                     { return 0; }
        public function get_status(): string              { return ''; }
        public function get_total( string $context = 'view' ): float { return 0.0; }
        public function get_date_created(): ?WC_DateTime  { return null; }
        public function get_items( string $type = 'line_item' ): array { return array(); }
        public function get_billing_first_name(): string  { return ''; }
        public function get_billing_last_name(): string   { return ''; }
        public function get_billing_email(): string       { return ''; }
        public function get_billing_phone(): string       { return ''; }
        public function get_order_number(): string        { return ''; }
        public function get_view_order_url(): string      { return ''; }
        public function get_formatted_order_total( string $tax_display = '', bool $display_refunded = true ): string { return ''; }
        public function get_item_count( string $type = '' ): int { return 0; }
        public function needs_payment(): bool { return false; }
        public function get_checkout_payment_url(): string { return ''; }
        public function has_status( mixed $status ): bool { return false; }
        public function get_formatted_billing_address( string $empty_content = '' ): string { return ''; }
        public function get_formatted_shipping_address( string $empty_content = '' ): string { return ''; }
        public function get_customer_order_notes(): array { return array(); }
        public function get_reorder_url(): string { return ''; }
        public function get_formatted_line_subtotal( mixed $item, string $tax_display = '' ): string { return ''; }
        public function get_order_item_totals( string $tax_display = '' ): array { return array(); }
    }
}

if ( ! class_exists( 'WC_DateTime' ) ) {
    class WC_DateTime extends \DateTime {
        public function date_i18n( string $format ): string { return ''; }
    }
}

// WooCommerce main singleton — ensures WC()->countries is typed
if ( ! class_exists( 'WooCommerce' ) ) {
    class WooCommerce {
        /** @var WC_Countries */
        public WC_Countries $countries;
        /** @var WC_Customer */
        public WC_Customer $customer;
        /** @var WC_Cart|null */
        public ?WC_Cart $cart = null;
        /** @var WC_Session|null */
        public ?WC_Session $session = null;

        /** @return static */
        public static function instance(): static { return new static(); }
        public function __get( string $name ): mixed { return null; }
    }
}

if ( ! class_exists( 'WC_Cart' ) ) {
    class WC_Cart {
        public function get_cart(): array           { return array(); }
        public function get_cart_contents_count(): int { return 0; }
        public function get_cart_total(): string    { return ''; }
        public function is_empty(): bool            { return true; }
        public function get_cart_url(): string      { return ''; }
    }
}

if ( ! function_exists( 'WC' ) ) {
    /** @return WooCommerce */
    function WC(): WooCommerce { return WooCommerce::instance(); }
}

// ─────────────────────────────────────────────────────────────────────────────
// Missing WordPress / WooCommerce Stubs
// ─────────────────────────────────────────────────────────────────────────────
if ( ! function_exists( 'wc_nocache_headers' ) ) { function wc_nocache_headers(): void {} }
if ( ! function_exists( 'wp_upload_dir' ) )     { function wp_upload_dir( string $time = null, bool $create_dir = true, bool $refresh_cache = false ): array { return array(); } }
if ( ! function_exists( 'get_query_var' ) )     { function get_query_var( string $var, mixed $default = '' ): mixed { return $default; } }
if ( ! function_exists( 'wp_safe_redirect' ) )   { function wp_safe_redirect( string $location, int $status = 302, string $x_redirect_by = 'WordPress' ): bool { return true; } }
if ( ! function_exists( 'wc_logout_url' ) )      { function wc_logout_url( string $redirect = '' ): string { return ''; } }
if ( ! function_exists( 'wc_format_datetime' ) )  { function wc_format_datetime( mixed $date, string $format = '' ): string { return ''; } }
if ( ! function_exists( 'wc_get_order_status_name' ) ) { function wc_get_order_status_name( string $status ): string { return $status; } }
if ( ! function_exists( 'date_i18n' ) )           { function date_i18n( string $format, int|bool $timestamp_with_offset = false, bool $gmt = false ): string { return ''; } }

if ( ! class_exists( 'WC_Order_Item_Product' ) ) {
    class WC_Order_Item_Product {
        public function __construct( int $item_id = 0 ) {}
        public function get_formatted_meta_data( string $hide_prefix = '_' ): array { return array(); }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// CSS note: 'text-size-adjust' — The -webkit- and -moz- prefixed versions are
// already present above it in the Tailwind base reset. This unprefixed property
// is for Chrome 54+/Edge 79+ future-proofing. The IDE warning is a known
// false-positive for Tailwind-generated files; it does not affect functionality.
// ─────────────────────────────────────────────────────────────────────────────

