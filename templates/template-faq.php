<?php
/**
 * Template Name: FAQ Template
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// ── Admin options ──────────────────────────────────────────────────────────
$faq_subtitle   = dt_get_theme_option( 'faq_hero_subtitle',    'Everything you need to know before, during, and after your purchase.' );
$faq_contact_url = dt_get_theme_option( 'faq_contact_url',     home_url( '/contact-us/' ) );
$faq_btn_label   = dt_get_theme_option( 'faq_contact_btn_label', 'Contact Support' );
if ( empty( $faq_contact_url ) ) $faq_contact_url = home_url( '/contact-us/' );

// Build FAQ array from admin or fall back to defaults
$default_faqs = array(
    array( 'q' => 'How do I place an order?',               'a' => 'Simply browse our collection, add pieces to your bag, and proceed to Checkout. Fill in your delivery details and choose a payment method — UPI, Card, Net Banking, or COD.' ),
    array( 'q' => 'Are the sarees 100% authentic?',         'a' => 'Absolutely. Every ARSHMAN piece is handloomed by verified master weavers in Varanasi, Kanchipuram, or Mysore. Each order ships with a Certificate of Authenticity.' ),
    array( 'q' => 'What is your shipping policy?',          'a' => 'We offer FREE shipping on all orders above ₹999 within India (3-5 business days) and worldwide shipping to 40+ countries. View our Shipping Policy page for full details.' ),
    array( 'q' => 'How do I care for my saree?',            'a' => 'Silk sarees are best dry-cleaned only. Store in a clean white muslin wrap, avoid direct sunlight, and iron on low heat with a protective cloth on top.' ),
    array( 'q' => 'Can I return or exchange a saree?',      'a' => 'Yes — we offer easy 7-day returns/exchanges on unused items with tags intact. Custom or clearance items are non-returnable. Please contact us to initiate a return.' ),
    array( 'q' => 'Do you offer Cash on Delivery (COD)?',   'a' => 'Yes, COD is available for orders within India. An additional processing fee of ₹49 applies to COD shipments.' ),
    array( 'q' => 'How do I track my order?',               'a' => 'You\'ll receive a tracking link via email and SMS as soon as your order ships. You can also visit the Track Order page anytime with your Order ID.' ),
    array( 'q' => 'Can I customize a saree?',               'a' => 'Absolutely. We offer bespoke bridal and festive commissions. Reach out to our atelier with your requirements — lead time 4-6 weeks.' ),
    array( 'q' => 'How do I apply a coupon code?',          'a' => 'Add items to your bag, proceed to Checkout, and enter your coupon code in the field on the payment step. You\'ll instantly see the discount applied.' ),
    array( 'q' => 'Are prices inclusive of taxes?',         'a' => 'All prices displayed are inclusive of applicable GST for India. International orders may incur customs duties which are the buyer\'s responsibility.' ),
);

$faqs = array();
for ( $i = 1; $i <= 10; $i++ ) {
    $q = dt_get_theme_option( "faq_{$i}_q", '' );
    $a = dt_get_theme_option( "faq_{$i}_a", '' );
    if ( ! empty( $q ) && ! empty( $a ) ) {
        $faqs[] = array( 'q' => $q, 'a' => $a );
    } elseif ( empty( $q ) && empty( $a ) && isset( $default_faqs[ $i - 1 ] ) ) {
        // Use default only if this slot was never saved (key not present in options at all)
        $opts = get_option( 'dt_theme_options', array() );
        if ( ! array_key_exists( "faq_{$i}_q", $opts ) ) {
            $faqs[] = $default_faqs[ $i - 1 ];
        }
    }
}
// If nothing configured yet, use all defaults
if ( empty( $faqs ) ) {
    $faqs = $default_faqs;
}
?>

<!-- Hero -->
<section class="py-16 md:py-20 text-center border-b border-[#C8A46A]/10 bg-[#050505]">
    <div class="max-w-3xl mx-auto px-4">
        <i data-lucide="help-circle" class="w-10 h-10 text-[#C8A46A] mx-auto mb-4"></i>
        <h1 class="font-serif text-4xl md:text-5xl text-white mb-4"><?php the_title(); ?></h1>
        <p class="text-gray-400"><?php echo esc_html( $faq_subtitle ); ?></p>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="py-16 md:py-20 bg-black">
    <div class="max-w-3xl mx-auto px-4 md:px-8 space-y-4" id="faq-list">
        <?php foreach ( $faqs as $idx => $faq ) : ?>
        <div class="border border-[#222] bg-[#0c0c0c] transition-colors hover:border-[#C8A46A]/40">
            <button onclick="toggleFaqAction(<?php echo esc_attr( $idx ); ?>)"
                    class="w-full flex items-center justify-between text-left p-5 md:p-6 gap-4 group">
                <span class="font-serif text-lg md:text-xl text-white group-hover:text-[#C8A46A] transition-colors">
                    <?php echo esc_html( $faq['q'] ); ?>
                </span>
                <i data-lucide="plus" id="faq-icon-<?php echo esc_attr( $idx ); ?>" class="w-5 h-5 text-[#C8A46A] shrink-0 transition-transform"></i>
            </button>
            <div id="faq-answer-<?php echo esc_attr( $idx ); ?>"
                 class="hidden px-5 md:px-6 pb-6 -mt-2 text-gray-400 leading-relaxed font-light text-sm md:text-base">
                <?php echo esc_html( $faq['a'] ); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="max-w-2xl mx-auto text-center mt-16 px-4">
        <p class="text-gray-400 text-sm mb-4"><?php esc_html_e( 'Still have questions?', 'dt-ecommerce-theme' ); ?></p>
        <a href="<?php echo esc_url( $faq_contact_url ); ?>"
           class="inline-block bg-[#C8A46A] text-black px-8 py-3 uppercase tracking-widest text-xs font-semibold hover:bg-[#b08d55] transition-all rounded-sm">
            <?php echo esc_html( $faq_btn_label ); ?>
        </a>
    </div>
</section>

<script>
function toggleFaqAction(idx) {
    var answer = document.getElementById('faq-answer-' + idx);
    var icon   = document.getElementById('faq-icon-' + idx);
    if (!answer) return;
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        if (icon) icon.setAttribute('data-lucide', 'minus');
    } else {
        answer.classList.add('hidden');
        if (icon) icon.setAttribute('data-lucide', 'plus');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
</script>

<?php get_footer(); ?>
