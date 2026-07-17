<?php
/**
 * The sidebar containing the main widget area
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area w-full md:w-1/4 shrink-0">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
