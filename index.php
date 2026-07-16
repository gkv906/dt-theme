<?php
/**
 * The main template file
 *
 * @package DT_Ecommerce_Theme
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">
    <?php
    if ( have_posts() ) :
        if ( is_home() && ! is_front_page() ) :
            ?>
            <header class="mb-8">
                <h1 class="page-title font-serif text-4xl text-[#C8A46A]"><?php single_post_title(); ?></h1>
            </header>
            <?php
        endif;

        /* Start the Loop */
        while ( have_posts() ) :
            the_post();
            get_template_part( 'template-parts/content', get_post_type() );
        endwhile;

        the_posts_navigation( array(
            'prev_text' => esc_html__( 'Previous', 'dt-ecommerce-theme' ),
            'next_text' => esc_html__( 'Next', 'dt-ecommerce-theme' ),
        ) );

    else :
        get_template_part( 'template-parts/content', 'none' );
    endif;
    ?>
</main>

<?php
get_sidebar();
get_footer();
