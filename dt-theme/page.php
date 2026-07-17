<?php
/**
 * The template for displaying all pages
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-16 bg-[#050505] min-h-[70vh]">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="text-center mb-12">
                <h1 class="font-serif text-4xl md:text-5xl text-white"><?php the_title(); ?></h1>
                <div class="w-16 h-[1px] bg-[#C8A46A]/30 mx-auto mt-4"></div>
            </header>

            <div class="entry-content text-sm text-[#F7F4EE]/90 leading-relaxed font-light max-w-4xl mx-auto space-y-6">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dt-ecommerce-theme' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
