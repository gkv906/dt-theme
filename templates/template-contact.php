<?php
/**
 * Template Name: Contact Template
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// ── Admin options ──────────────────────────────────────────────────────────
$contact_subtitle    = dt_get_theme_option( 'contact_page_subtitle',    'We\'d love to hear from you. Reach out for custom saree inquiries, orders, or styling advice.' );
$contact_email       = dt_get_theme_option( 'contact_email',            'atelier@arshmandesigns.com' );
$contact_phone       = dt_get_theme_option( 'contact_phone',            '+91 22 9876 5432' );
$contact_phone2      = dt_get_theme_option( 'contact_page_phone2',      '+91 98765 43210' );
$contact_address     = dt_get_theme_option( 'contact_address',          'Arshman Designs House, MG Road, Colaba, Mumbai 400001' );
$contact_hours       = dt_get_theme_option( 'contact_hours',            'Mon–Sat, 10:00am – 7:00pm IST' );
$contact_whatsapp    = dt_get_theme_option( 'whatsapp_url',             '' );
$whatsapp_label      = dt_get_theme_option( 'contact_whatsapp_label',   'Chat on WhatsApp' );
$boutique_label      = dt_get_theme_option( 'contact_boutique_label',   'Our Boutique' );
$maps_embed          = dt_get_theme_option( 'google_maps_embed',        '' );
$success_msg         = dt_get_theme_option( 'contact_success_message',  'Your message has been sent successfully. We\'ll get back to you within 24 hours.' );

$success_sent = false;
if ( isset( $_POST['dt_contact_nonce'] ) && wp_verify_nonce( $_POST['dt_contact_nonce'], 'dt_send_contact_action' ) ) {
    $name    = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
    $email   = isset( $_POST['email'] )    ? sanitize_email( wp_unslash( $_POST['email'] ) )         : '';
    $phone   = isset( $_POST['phone'] )    ? sanitize_text_field( wp_unslash( $_POST['phone'] ) )    : '';
    $subject = isset( $_POST['subject'] )  ? sanitize_text_field( wp_unslash( $_POST['subject'] ) )  : '';
    $message = isset( $_POST['message'] )  ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    if ( $name && $email ) {
        $to      = $contact_email ?: get_option( 'admin_email' );
        $headers = array( 'Content-Type: text/plain; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>' );
        wp_mail( $to, 'Contact: ' . $subject, "Name: $name\nEmail: $email\nPhone: $phone\n\n$message", $headers );
        $success_sent = true;
    }
}
?>

<!-- HERO SECTION -->
<section class="py-16 text-center border-b border-white/10 bg-[#050505]">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="font-serif text-4xl md:text-5xl font-bold text-[#C8A46A] mb-3"><?php the_title(); ?></h1>
        <p class="text-white/60 max-w-lg mx-auto"><?php echo esc_html( $contact_subtitle ); ?></p>
    </div>
</section>

<!-- CONTACT CONTENT -->
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Form Column -->
            <div class="lg:col-span-2 bg-[#111111] border border-white/10 rounded-lg p-8">
                <?php if ( $success_sent ) : ?>
                <div class="text-center py-12 flex flex-col items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-16 h-16 text-[#C8A46A] mb-4"></i>
                    <h3 class="font-serif text-2xl font-semibold text-[#C8A46A] mb-2"><?php esc_html_e( 'Thank You!', 'dt-ecommerce-theme' ); ?></h3>
                    <p class="text-white/60 mb-6 max-w-md"><?php echo esc_html( $success_msg ); ?></p>
                    <a href="<?php echo esc_url( get_permalink() ); ?>"
                       class="px-6 py-2 border border-[#C8A46A] text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black transition-colors rounded-sm text-sm">
                        <?php esc_html_e( 'Send Another Message', 'dt-ecommerce-theme' ); ?>
                    </a>
                </div>
                <?php else : ?>
                <form action="" method="post" class="space-y-6">
                    <?php wp_nonce_field( 'dt_send_contact_action', 'dt_contact_nonce' ); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fullname" class="block text-sm font-medium text-white/80 mb-2">
                                <?php esc_html_e( 'Full Name', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span>
                            </label>
                            <input type="text" id="fullname" name="fullname" required
                                   class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700"
                                   placeholder="Priya Sharma" />
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white/80 mb-2">
                                <?php esc_html_e( 'Email Address', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span>
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700"
                                   placeholder="priya@email.com" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-white/80 mb-2">
                                <?php esc_html_e( 'Phone Number', 'dt-ecommerce-theme' ); ?>
                            </label>
                            <input type="tel" id="phone" name="phone"
                                   class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700"
                                   placeholder="+91 98765 43210" />
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-white/80 mb-2">
                                <?php esc_html_e( 'Subject', 'dt-ecommerce-theme' ); ?>
                            </label>
                            <select id="subject" name="subject"
                                    class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors">
                                <option value="Order Inquiry"><?php esc_html_e( 'Order Inquiry', 'dt-ecommerce-theme' ); ?></option>
                                <option value="Custom Saree"><?php esc_html_e( 'Custom Saree', 'dt-ecommerce-theme' ); ?></option>
                                <option value="Return / Refund"><?php esc_html_e( 'Return / Refund', 'dt-ecommerce-theme' ); ?></option>
                                <option value="Wholesale"><?php esc_html_e( 'Wholesale Inquiry', 'dt-ecommerce-theme' ); ?></option>
                                <option value="Other"><?php esc_html_e( 'Other', 'dt-ecommerce-theme' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-white/80 mb-2">
                            <?php esc_html_e( 'Your Message', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span>
                        </label>
                        <textarea id="message" name="message" required rows="6"
                                  class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700"
                                  placeholder="Write your message here..."></textarea>
                    </div>
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black py-4 uppercase tracking-widest text-xs font-bold flex items-center justify-center gap-2 rounded-sm cursor-pointer">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <?php esc_html_e( 'Send Message', 'dt-ecommerce-theme' ); ?>
                    </button>
                </form>
                <?php endif; ?>
            </div><!-- /form -->

            <!-- Info Column -->
            <div class="space-y-6">
                <div class="bg-[#111111] border border-white/10 rounded-lg p-8 space-y-6">
                    <h3 class="font-serif text-2xl text-[#C8A46A] tracking-wider uppercase border-b border-white/10 pb-3">
                        <?php esc_html_e( 'Atelier Info', 'dt-ecommerce-theme' ); ?>
                    </h3>

                    <!-- Address -->
                    <?php if ( $contact_address ) : ?>
                    <div class="flex gap-4">
                        <i data-lucide="map-pin" class="w-6 h-6 text-[#C8A46A] shrink-0 mt-0.5"></i>
                        <div>
                            <h5 class="text-white font-medium mb-1"><?php echo esc_html( $boutique_label ); ?></h5>
                            <p class="text-sm text-gray-400 leading-relaxed"><?php echo nl2br( esc_html( $contact_address ) ); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Phone -->
                    <?php if ( $contact_phone ) : ?>
                    <div class="flex gap-4">
                        <i data-lucide="phone" class="w-6 h-6 text-[#C8A46A] shrink-0 mt-0.5"></i>
                        <div>
                            <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Phone Inquiries', 'dt-ecommerce-theme' ); ?></h5>
                            <p class="text-sm text-gray-400 leading-relaxed">
                                <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php echo esc_html( $contact_phone ); ?></a>
                                <?php if ( $contact_phone2 ) : ?>
                                <br><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone2 ) ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php echo esc_html( $contact_phone2 ); ?></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Email -->
                    <?php if ( $contact_email ) : ?>
                    <div class="flex gap-4">
                        <i data-lucide="mail" class="w-6 h-6 text-[#C8A46A] shrink-0 mt-0.5"></i>
                        <div>
                            <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Email Support', 'dt-ecommerce-theme' ); ?></h5>
                            <p class="text-sm text-gray-400">
                                <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="hover:text-[#C8A46A] transition-colors"><?php echo esc_html( $contact_email ); ?></a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Hours -->
                    <?php if ( $contact_hours ) : ?>
                    <div class="flex gap-4">
                        <i data-lucide="clock" class="w-6 h-6 text-[#C8A46A] shrink-0 mt-0.5"></i>
                        <div>
                            <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Business Hours', 'dt-ecommerce-theme' ); ?></h5>
                            <p class="text-sm text-gray-400"><?php echo esc_html( $contact_hours ); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- WhatsApp -->
                    <?php if ( $contact_whatsapp ) : ?>
                    <a href="<?php echo esc_url( $contact_whatsapp ); ?>" target="_blank" rel="noopener"
                       class="flex items-center gap-3 bg-[#25D366]/10 border border-[#25D366]/30 text-[#25D366] px-4 py-3 rounded-sm hover:bg-[#25D366]/20 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <?php echo esc_html( $whatsapp_label ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div><!-- /info -->
        </div><!-- /grid -->

        <!-- Google Maps Embed -->
        <?php if ( $maps_embed ) : ?>
        <div class="mt-12 w-full overflow-hidden rounded-lg border border-white/10" style="height:360px;">
            <?php echo wp_kses( $maps_embed, array(
                'iframe' => array( 'src' => true, 'width' => true, 'height' => true, 'style' => true, 'allowfullscreen' => true, 'loading' => true, 'referrerpolicy' => true, 'frameborder' => true ),
            ) ); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
