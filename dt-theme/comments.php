<?php
/**
 * The template for displaying comments
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area max-w-3xl mx-auto mt-16 p-8 bg-[#0a0a0a] border border-[#C8A46A]/20">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title font-serif text-2xl text-white mb-8 pb-3 border-b border-[#C8A46A]/20">
            <?php
            $dt_comment_count = get_comments_number();
            if ( '1' === $dt_comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'dt-ecommerce-theme' ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $dt_comment_count, 'comments title', 'dt-ecommerce-theme' ) ),
                    number_format_i18n( $dt_comment_count ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 42,
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments text-xs text-[#666] mt-6 italic"><?php esc_html_e( 'Comments are closed.', 'dt-ecommerce-theme' ); ?></p>
            <?php
        endif;

    endif; // Check for have_comments().

    comment_form( array(
        'class_form' => 'space-y-4 mt-8',
        'title_reply_class' => 'font-serif text-xl text-[#C8A46A] mb-4 block',
        'submit_button' => '<button type="submit" id="submit" class="bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] text-black font-bold uppercase tracking-widest text-xs px-6 py-3 hover:brightness-110 transition-all rounded-sm cursor-pointer">%4$s</button>',
        'fields' => array(
            'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="comment-form-author"><label class="block text-[10px] uppercase tracking-widest text-[#a3a3a3] mb-1">' . __( 'Name', 'dt-ecommerce-theme' ) . '</label><input id="author" name="author" type="text" class="w-full bg-[#111] border border-[#C8A46A]/20 text-white px-3 py-2 text-xs outline-none" required /></div>',
            'email' => '<div class="comment-form-email"><label class="block text-[10px] uppercase tracking-widest text-[#a3a3a3] mb-1">' . __( 'Email', 'dt-ecommerce-theme' ) . '</label><input id="email" name="email" type="email" class="w-full bg-[#111] border border-[#C8A46A]/20 text-white px-3 py-2 text-xs outline-none" required /></div></div>',
        ),
        'comment_field' => '<div class="comment-form-comment"><label class="block text-[10px] uppercase tracking-widest text-[#a3a3a3] mb-1">' . __( 'Comment', 'dt-ecommerce-theme' ) . '</label><textarea id="comment" name="comment" rows="6" class="w-full bg-[#111] border border-[#C8A46A]/20 text-white p-3 text-xs outline-none" required></textarea></div>',
    ) );
    ?>

</div><!-- #comments -->
