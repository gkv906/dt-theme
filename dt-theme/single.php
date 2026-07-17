<?php
/**
 * The template for displaying all single posts
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
                <span class="text-xs uppercase tracking-widest text-[#C8A46A] block mb-2"><?php echo get_the_date(); ?></span>
                <h1 class="font-serif text-3xl md:text-5xl text-white leading-tight max-w-3xl mx-auto"><?php the_title(); ?></h1>
                <div class="w-16 h-[1px] bg-[#C8A46A]/30 mx-auto mt-4"></div>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="max-w-4xl mx-auto mb-12 overflow-hidden aspect-[16/9]">
                    <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content text-sm text-[#F7F4EE]/90 leading-relaxed font-light max-w-3xl mx-auto space-y-6">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dt-ecommerce-theme' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
            
            <footer class="entry-footer max-w-3xl mx-auto mt-12 pt-6 border-t border-[#C8A46A]/10 text-xs text-[#a3a3a3]">
                <?php the_tags( '<span class="tag-links">' . esc_html__( 'Tagged: ', 'dt-ecommerce-theme' ) . '</span>', ', ' ); ?>
            </footer>
        </article>
        <?php
        
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile;
    ?>
</main>

<?php
get_footer();
