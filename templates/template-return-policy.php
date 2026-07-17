<?php
/**
 * Template Name: Return Policy
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$return_subtitle = dt_get_theme_option( 'return_hero_subtitle', 'Easy 7-day returns and hassle-free exchanges on all eligible orders.' );
$return_updated  = dt_get_theme_option( 'return_last_updated',  '' );
$return_content  = dt_get_theme_option( 'return_page_content',  '' );

$contact_email = dt_get_theme_option( 'contact_email', 'atelier@arshmandesigns.com' );

$default_content = '
<h3>Return Eligibility</h3>
<p>We accept returns and exchanges within <strong>7 days</strong> of delivery, provided the item is:</p>
<ul>
<li>Unused, unworn, and unwashed</li>
<li>In its original packaging with all tags intact</li>
<li>Accompanied by the original invoice / order ID</li>
</ul>

<h3>Non-Returnable Items</h3>
<ul>
<li>Custom / bespoke commissioned pieces</li>
<li>Clearance sale items marked as "Final Sale"</li>
<li>Items that have been worn, washed, or altered</li>
<li>Blouse pieces stitched to order</li>
</ul>

<h3>How to Initiate a Return</h3>
<ol>
<li>Email us at <strong>' . esc_html( $contact_email ) . '</strong> with your Order ID and reason for return.</li>
<li>Our team will respond within 24 hours with return instructions and pickup details.</li>
<li>Pack the item securely and hand it to our courier partner.</li>
</ol>

<h3>Refund Process</h3>
<p>Once the returned item is received and inspected, refunds are processed within <strong>5–7 business days</strong> to the original payment method. For COD orders, refunds are issued via bank transfer.</p>

<h3>Exchange Policy</h3>
<p>We are happy to exchange your saree for a different size, color, or style from the same price range, subject to availability. Exchanges are processed after the returned item clears quality inspection.</p>

<h3>Damaged or Incorrect Items</h3>
<p>If you received a damaged or incorrect item, please contact us within <strong>48 hours</strong> of delivery with photos. We will arrange a replacement or full refund at no additional cost.</p>

<h3>Contact Us</h3>
<p>For all return and refund queries, reach out at <strong>' . esc_html( $contact_email ) . '</strong> or visit our <a href="' . esc_url( home_url( '/contact-us/' ) ) . '">Contact Us</a> page.</p>
';
?>

<!-- Hero -->
<section class="py-16 md:py-20 text-center border-b border-[#C8A46A]/10 bg-[#050505]">
    <div class="max-w-3xl mx-auto px-4">
        <i data-lucide="package-open" class="w-10 h-10 text-[#C8A46A] mx-auto mb-4"></i>
        <h1 class="font-serif text-4xl md:text-5xl text-white mb-4"><?php the_title(); ?></h1>
        <p class="text-gray-400"><?php echo esc_html( $return_subtitle ); ?></p>
        <?php if ( $return_updated ) : ?>
        <p class="text-gray-600 text-xs mt-3"><?php printf( esc_html__( 'Last updated: %s', 'dt-ecommerce-theme' ), esc_html( $return_updated ) ); ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Quick Info Strip -->
<div class="bg-[#0a0a0a] border-y border-[#C8A46A]/10 py-6">
    <div class="max-w-4xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div>
            <div class="text-[#C8A46A] font-serif text-2xl mb-1">7 Days</div>
            <div class="text-gray-400 text-xs uppercase tracking-widest"><?php esc_html_e( 'Return Window', 'dt-ecommerce-theme' ); ?></div>
        </div>
        <div>
            <div class="text-[#C8A46A] font-serif text-2xl mb-1">5-7 Days</div>
            <div class="text-gray-400 text-xs uppercase tracking-widest"><?php esc_html_e( 'Refund Processing', 'dt-ecommerce-theme' ); ?></div>
        </div>
        <div>
            <div class="text-[#C8A46A] font-serif text-2xl mb-1">Free</div>
            <div class="text-gray-400 text-xs uppercase tracking-widest"><?php esc_html_e( 'Return Pickup', 'dt-ecommerce-theme' ); ?></div>
        </div>
    </div>
</div>

<!-- Content -->
<section class="py-16 md:py-20 bg-black">
    <div class="max-w-4xl mx-auto px-4 md:px-8">
        <div class="prose-policy text-gray-300 leading-relaxed font-light space-y-6">
            <?php echo wp_kses_post( ! empty( $return_content ) ? $return_content : $default_content ); ?>
        </div>
        <div class="mt-12 pt-8 border-t border-[#C8A46A]/10 flex flex-wrap gap-4 justify-center">
            <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"
               class="inline-block bg-[#C8A46A] text-black px-8 py-3 uppercase tracking-widest text-xs font-semibold hover:bg-[#b08d55] transition-all rounded-sm">
                <?php esc_html_e( 'Start a Return', 'dt-ecommerce-theme' ); ?>
            </a>
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
.prose-policy ol li{list-style-type:decimal;}
.prose-policy a{color:#C8A46A;text-decoration:underline;}
.prose-policy strong{color:#fff;font-weight:600;}
.prose-policy p{margin:.6rem 0;}
.prose-policy blockquote{border-left:3px solid #C8A46A;padding-left:1rem;color:#a3a3a3;font-style:italic;}
</style>

<?php get_footer(); ?>
