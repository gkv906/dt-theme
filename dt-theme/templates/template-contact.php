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

$success_sent = false;
if ( isset( $_POST['dt_contact_nonce'] ) && wp_verify_nonce( $_POST['dt_contact_nonce'], 'dt_send_contact_action' ) ) {
    // Process contact form submission (simulated email or saved to DB)
    $name = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    
    // In production, you would call wp_mail()
    $success_sent = true;
}
?>

<!-- HERO SECTION -->
<section class="py-16 text-center border-b border-white/10 bg-[#050505]">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-serif text-4xl md:text-5xl font-bold text-[#C8A46A] mb-3"><?php the_title(); ?></h2>
        <p class="text-white/60 max-w-lg mx-auto"><?php esc_html_e( 'We\'d love to hear from you. Reach out to us for custom saree inquiries, orders, or styling advice.', 'dt-ecommerce-theme' ); ?></p>
    </div>
</section>

<!-- CONTACT CONTENT -->
<section class="py-16 max-w-7xl mx-auto px-4 md:px-8 bg-black">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Form Column -->
        <div class="lg:col-span-2 bg-[#111111] border border-white/10 rounded-lg p-8">
            
            <?php if ( $success_sent ) : ?>
                <div id="contact-success" class="text-center py-12 flex flex-col items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-16 h-16 text-[#C8A46A] mb-4"></i>
                    <h3 class="font-serif text-2xl font-semibold text-[#C8A46A] mb-2"><?php esc_html_e( 'Thank You!', 'dt-ecommerce-theme' ); ?></h3>
                    <p class="text-white/60 mb-6 max-w-md"><?php esc_html_e( 'Your message has been sent successfully. We\'ll get back to you within 24 hours.', 'dt-ecommerce-theme' ); ?></p>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="px-6 py-2 border border-[#C8A46A] text-[#C8A46A] hover:bg-[#C8A46A] hover:text-black transition-colors rounded-sm text-sm"><?php esc_html_e( 'Send Another Message', 'dt-ecommerce-theme' ); ?></a>
                </div>
            <?php else : ?>
                <form id="contact-form" action="" method="post" class="space-y-6">
                    <input type="hidden" name="dt_contact_nonce" value="<?php echo esc_attr( wp_create_nonce( 'dt_send_contact_action' ) ); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="fullname" class="block text-sm font-medium text-white/80 mb-2"><?php esc_html_e( 'Full Name', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span></label>
                            <input type="text" id="fullname" name="fullname" required class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700" placeholder="Priya Sharma" />
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white/80 mb-2"><?php esc_html_e( 'Email Address', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span></label>
                            <input type="email" id="email" name="email" required class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700" placeholder="priya@sharma.com" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-white/80 mb-2"><?php esc_html_e( 'Phone Number', 'dt-ecommerce-theme' ); ?></label>
                            <input type="tel" id="phone" name="phone" class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700" placeholder="+91 XXXXX XXXXX" />
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-white/80 mb-2"><?php esc_html_e( 'Subject', 'dt-ecommerce-theme' ); ?></label>
                            <input type="text" id="subject" name="subject" class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700" placeholder="Product Inquiry / General" />
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-white/80 mb-2"><?php esc_html_e( 'Your Message', 'dt-ecommerce-theme' ); ?> <span class="text-[#C8A46A]">*</span></label>
                        <textarea id="message" name="message" required rows="6" class="w-full bg-[#050505] border border-white/10 focus:border-[#C8A46A] text-white rounded p-3 text-sm outline-none transition-colors placeholder:text-gray-700" placeholder="Write your message here..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#b08d55] via-[#C8A46A] to-[#d8ba82] hover:brightness-110 text-black py-4 uppercase tracking-widest text-xs font-bold flex items-center justify-center gap-2 rounded-sm cursor-pointer">
                        <i data-lucide="send" class="w-4 h-4"></i> <?php esc_html_e( 'Send Message', 'dt-ecommerce-theme' ); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Info Column -->
        <div class="space-y-8">
            <div class="bg-[#111111] border border-white/10 rounded-lg p-8 space-y-6">
                <h3 class="font-serif text-2xl text-[#C8A46A] tracking-wider uppercase border-b border-white/10 pb-3"><?php esc_html_e( 'Atelier Info', 'dt-ecommerce-theme' ); ?></h3>
                
                <div class="flex gap-4">
                    <i data-lucide="map-pin" class="w-6 h-6 text-[#C8A46A] shrink-0"></i>
                    <div>
                        <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Our Boutique', 'dt-ecommerce-theme' ); ?></h5>
                        <p class="text-sm text-gray-400 leading-relaxed"><?php esc_html_e( 'Arshman Designs House, MG Road, Colaba, Mumbai 400001', 'dt-ecommerce-theme' ); ?></p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <i data-lucide="phone" class="w-6 h-6 text-[#C8A46A] shrink-0"></i>
                    <div>
                        <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Phone Inquiries', 'dt-ecommerce-theme' ); ?></h5>
                        <p class="text-sm text-gray-400 leading-relaxed"><?php esc_html_e( '+91 22 9876 5432', 'dt-ecommerce-theme' ); ?><br><?php esc_html_e( '+91 98765 43210', 'dt-ecommerce-theme' ); ?></p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <i data-lucide="mail" class="w-6 h-6 text-[#C8A46A] shrink-0"></i>
                    <div>
                        <h5 class="text-white font-medium mb-1"><?php esc_html_e( 'Email Support', 'dt-ecommerce-theme' ); ?></h5>
                        <p class="text-sm text-gray-400 leading-relaxed"><?php esc_html_e( 'atelier@arshmandesigns.com', 'dt-ecommerce-theme' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
