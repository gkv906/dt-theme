<?php
/**
 * The blog list template file
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-16 bg-[#050505] min-h-[60vh]">
    <div class="text-center mb-12">
        <span class="text-xs uppercase tracking-[0.35em] text-[#C8A46A] block mb-2"><?php esc_html_e( 'Our Chronicles', 'dt-ecommerce-theme' ); ?></span>
        <h1 class="font-serif text-4xl md:text-5xl text-white"><?php esc_html_e( 'The Heritage Blog', 'dt-ecommerce-theme' ); ?></h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-[#0a0a0a] border border-[#C8A46A]/20 p-6 flex flex-col justify-between hover:border-[#C8A46A] transition-all'); ?>>
                    <div>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="mb-4 aspect-[16/10] overflow-hidden">
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-[9px] uppercase tracking-widest text-[#C8A46A] block mb-2"><?php echo get_the_date(); ?></span>
                        <h2 class="font-serif text-xl text-white mb-3 hover:text-[#C8A46A] transition-colors">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="text-xs text-[#a3a3a3] font-light leading-relaxed mb-6">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="text-[10px] uppercase tracking-widest font-semibold text-[#C8A46A] hover:underline"><?php esc_html_e( 'Read More', 'dt-ecommerce-theme' ); ?> →</a>
                </article>
                <?php
            endwhile;

            the_posts_navigation();

        else :
            echo '<p class="text-center text-[#a3a3a3]">' . esc_html__( 'No posts found.', 'dt-ecommerce-theme' ) . '</p>';
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
