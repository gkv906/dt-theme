<?php
/**
 * Template Name: Privacy Policy
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$privacy_subtitle    = dt_get_theme_option( 'privacy_hero_subtitle', 'How we collect, use and protect your personal information.' );
$privacy_updated     = dt_get_theme_option( 'privacy_last_updated',  '' );
$privacy_content     = dt_get_theme_option( 'privacy_page_content',  '' );

$default_content = '
<h3>1. Information We Collect</h3>
<p>We collect information you provide when placing an order, creating an account, or contacting us — including your name, email address, phone number, shipping address, and payment details.</p>

<h3>2. How We Use Your Information</h3>
<ul>
<li>To process and fulfill your orders</li>
<li>To send order confirmations and shipping updates</li>
<li>To respond to your customer support requests</li>
<li>To send promotional emails (you may opt out at any time)</li>
<li>To improve our website and services</li>
</ul>

<h3>3. Data Security</h3>
<p>We implement industry-standard SSL encryption and secure payment gateways to protect your data. We do not store credit card details on our servers.</p>

<h3>4. Sharing Your Information</h3>
<p>We do not sell, rent, or trade your personal information to third parties. We share data only with trusted partners (shipping couriers, payment processors) strictly to fulfill your orders.</p>

<h3>5. Cookies</h3>
<p>Our website uses cookies to enhance your browsing experience, remember your preferences, and analyze site traffic. You may disable cookies in your browser settings.</p>

<h3>6. Your Rights</h3>
<p>You have the right to access, correct, or delete your personal data. To submit a request, contact us at the email below.</p>

<h3>7. Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us at <strong>' . esc_html( dt_get_theme_option( 'contact_email', 'atelier@arshmandesigns.com' ) ) . '</strong>.</p>
';
?>

<!-- Hero -->
<section class="py-16 md:py-20 text-center border-b border-[#C8A46A]/10 bg-[#050505]">
    <div class="max-w-3xl mx-auto px-4">
        <i data-lucide="shield-check" class="w-10 h-10 text-[#C8A46A] mx-auto mb-4"></i>
        <h1 class="font-serif text-4xl md:text-5xl text-white mb-4"><?php the_title(); ?></h1>
        <p class="text-gray-400"><?php echo esc_html( $privacy_subtitle ); ?></p>
        <?php if ( $privacy_updated ) : ?>
        <p class="text-gray-600 text-xs mt-3"><?php printf( esc_html__( 'Last updated: %s', 'dt-ecommerce-theme' ), esc_html( $privacy_updated ) ); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Content -->
<section class="py-16 md:py-20 bg-black">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        <div class="prose-policy text-gray-300 leading-relaxed font-light space-y-6">
            <?php echo wp_kses_post( ! empty( $privacy_content ) ? $privacy_content : $default_content ); ?>
        </div>
        <div class="mt-12 pt-8 border-t border-[#C8A46A]/10 text-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="inline-block border border-[#C8A46A]/40 text-[#C8A46A] px-8 py-3 uppercase tracking-widest text-xs font-medium hover:bg-[#C8A46A] hover:text-black transition-all rounded-sm">
                <?php esc_html_e( '← Back to Home', 'dt-ecommerce-theme' ); ?>
            </a>
        </div>
    </div>
</section>

<style>
.prose-policy h2,.prose-policy h3,.prose-policy h4{font-family:var(--font-serif,serif);color:#C8A46A;margin-top:2rem;margin-bottom:.75rem;}
.prose-policy h2{font-size:1.6rem;}
.prose-policy h3{font-size:1.25rem;}
.prose-policy ul,.prose-policy ol{padding-left:1.5rem;margin:.75rem 0;}
.prose-policy li{margin:.35rem 0;list-style-type:disc;}
.prose-policy a{color:#C8A46A;text-decoration:underline;}
.prose-policy strong{color:#fff;font-weight:600;}
.prose-policy p{margin:.6rem 0;}
.prose-policy blockquote{border-left:3px solid #C8A46A;padding-left:1rem;color:#a3a3a3;font-style:italic;}
</style>

<?php get_footer(); ?>
