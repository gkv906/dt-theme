<?php
/**
 * Empty cart page
 * Custom override for DT Ecommerce Theme — Premium Arshman Design
 *
 * @package DT_Ecommerce_Theme
 * @see     https://woocommerce.com/document/template-structure/
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- ===== EMPTY CART PAGE ===== -->
<div class="dt-empty-cart-page">

    <!-- Animated Hero Section -->
    <div class="ec-hero">
        <!-- Floating particles background -->
        <div class="ec-particles" aria-hidden="true">
            <span class="ec-particle" style="--x:15%;--y:20%;--d:3.2s;--s:0.6;"></span>
            <span class="ec-particle" style="--x:80%;--y:15%;--d:4.1s;--s:0.4;"></span>
            <span class="ec-particle" style="--x:60%;--y:70%;--d:2.8s;--s:0.7;"></span>
            <span class="ec-particle" style="--x:30%;--y:60%;--d:5.0s;--s:0.5;"></span>
            <span class="ec-particle" style="--x:90%;--y:50%;--d:3.6s;--s:0.3;"></span>
            <span class="ec-particle" style="--x:10%;--y:80%;--d:4.5s;--s:0.6;"></span>
        </div>

        <!-- Animated Shopping Bag -->
        <div class="ec-bag-wrap" aria-hidden="true">
            <div class="ec-bag-glow"></div>
            <div class="ec-bag-anim">
                <!-- Shopping bag SVG -->
                <svg class="ec-bag-svg" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Bag body -->
                    <path class="ec-bag-body" d="M15 40 L105 40 L115 130 H5 Z" stroke="#C8A46A" stroke-width="2.5" fill="rgba(200,164,106,0.06)" stroke-linejoin="round"/>
                    <!-- Bag handle left -->
                    <path class="ec-bag-handle" d="M40 40 C40 20 50 10 60 10 C70 10 80 20 80 40" stroke="#C8A46A" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    <!-- Decorative stripe on bag -->
                    <line x1="15" y1="65" x2="105" y2="65" stroke="#C8A46A" stroke-width="1" stroke-dasharray="4 4" opacity="0.4"/>
                    <!-- Stars / sparkle on bag -->
                    <g class="ec-bag-sparkle" opacity="0">
                        <path d="M60 80 L62 86 L68 86 L63 90 L65 96 L60 92 L55 96 L57 90 L52 86 L58 86 Z" fill="#C8A46A"/>
                    </g>
                    <!-- sad face on bag -->
                    <circle cx="48" cy="85" r="3" fill="#C8A46A" opacity="0.5"/>
                    <circle cx="72" cy="85" r="3" fill="#C8A46A" opacity="0.5"/>
                    <path d="M48 100 Q60 94 72 100" stroke="#C8A46A" stroke-width="2" fill="none" stroke-linecap="round" opacity="0.5"/>
                </svg>

                <!-- Floating items around bag -->
                <div class="ec-float-item ec-float-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="22" height="22">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="ec-float-item ec-float-2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="16" height="16">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="ec-float-item ec-float-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="20" height="20">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="ec-float-item ec-float-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="14" height="14">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Text content -->
        <div class="ec-text">
            <div class="ec-badge">
                <span class="ec-badge-dot"></span>
                <span><?php esc_html_e( 'Oops! Nothing Here Yet', 'dt-ecommerce-theme' ); ?></span>
            </div>
            <h1 class="ec-title"><?php esc_html_e( 'Your Cart is Empty', 'dt-ecommerce-theme' ); ?></h1>
            <p class="ec-subtitle"><?php esc_html_e( 'Looks like you haven\'t added any exquisite pieces to your cart yet. Explore our handcrafted collection of silk sarees and find your perfect drape.', 'dt-ecommerce-theme' ); ?></p>

            <!-- CTA Buttons -->
            <div class="ec-cta-row">
                <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="ec-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/><path d="M16 10a4 4 0 01-8 0" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php esc_html_e( 'Explore Collection', 'dt-ecommerce-theme' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ec-btn-outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round"/><polyline points="9 22 9 12 15 12 15 22" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php esc_html_e( 'Back to Home', 'dt-ecommerce-theme' ); ?>
                </a>
            </div>

            <!-- Trust badges -->
            <div class="ec-trust-row">
                <div class="ec-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="18" height="18"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span><?php esc_html_e( 'Free Shipping', 'dt-ecommerce-theme' ); ?></span>
                </div>
                <span class="ec-trust-sep">✦</span>
                <div class="ec-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="18" height="18"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span><?php esc_html_e( '7-Day Returns', 'dt-ecommerce-theme' ); ?></span>
                </div>
                <span class="ec-trust-sep">✦</span>
                <div class="ec-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="18" height="18"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span><?php esc_html_e( 'Silk Mark Certified', 'dt-ecommerce-theme' ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TOP SELLING PRODUCTS CAROUSEL ===== -->
    <?php
    $best_ids = array();
    if ( class_exists( 'WooCommerce' ) ) {
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
            'fields'         => 'ids',
        );
        $best_ids = get_posts( $args );
        if ( empty( $best_ids ) ) {
            $args['orderby'] = 'rand';
            unset( $args['meta_key'] );
            $best_ids = get_posts( $args );
        }
    }

    if ( ! empty( $best_ids ) ) :
    ?>
    <div class="ec-topsellers-section">
        <!-- Section Header -->
        <div class="ec-section-header">
            <div class="ec-section-label">
                <span class="ec-section-label-text"><?php esc_html_e( 'Atelier Favorites', 'dt-ecommerce-theme' ); ?></span>
            </div>
            <h2 class="ec-section-title"><?php esc_html_e( 'Top Selling Styles', 'dt-ecommerce-theme' ); ?></h2>
            <div class="ec-section-divider">
                <div class="ec-divider-line"></div>
                <svg class="ec-divider-crown" viewBox="0 0 24 24" fill="none" stroke="#C8A46A" stroke-width="1.5" width="14" height="14">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="rgba(200,164,106,0.3)" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="ec-divider-line"></div>
            </div>
            <p class="ec-section-sub"><?php esc_html_e( 'Handpicked bestsellers — loved by thousands of drape connoisseurs', 'dt-ecommerce-theme' ); ?></p>

            <!-- Slider controls (desktop) -->
            <div class="ec-slider-controls" id="ec-slider-controls">
                <span class="ec-slider-progress" id="ec-slider-progress">01 / <?php echo str_pad( count( $best_ids ), 2, '0', STR_PAD_LEFT ); ?></span>
                <button class="ec-slider-btn" id="ec-prev-btn" onclick="ecSliderNav(-1)" aria-label="Previous">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="ec-slider-btn" id="ec-next-btn" onclick="ecSliderNav(1)" aria-label="Next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="ec-slider-btn ec-play-btn" id="ec-play-btn" onclick="ecToggleAutoplay()" aria-label="Pause autoplay">
                    <svg id="ec-pause-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                    <svg id="ec-play-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="display:none"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </button>
            </div>
        </div>

        <!-- Slider Track -->
        <div class="ec-slider-outer">
            <div class="ec-slider-fade-left"></div>
            <div class="ec-slider-fade-right"></div>
            <div class="ec-slider" id="ec-topsellers-slider">
                <?php
                foreach ( $best_ids as $idx => $pid ) :
                    $product = wc_get_product( $pid );
                    if ( ! $product ) continue;

                    $price   = $product->get_price();
                    $mrp     = $product->get_regular_price();
                    $rating  = $product->get_average_rating();
                    if ( ! $rating ) $rating = '4.8';
                    $img     = get_the_post_thumbnail_url( $pid, 'woocommerce_thumbnail' );
                    if ( ! $img ) $img = wc_placeholder_img_src();

                    $gallery_ids  = $product->get_gallery_image_ids();
                    $gallery_urls = array( $img );
                    foreach ( array_slice( $gallery_ids, 0, 2 ) as $gid ) {
                        $gu = wp_get_attachment_url( $gid );
                        if ( $gu ) $gallery_urls[] = $gu;
                    }

                    $terms       = get_the_terms( $pid, 'product_cat' );
                    $fabric_label = 'Silk Drape';
                    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                        $fabric_label = implode( ', ', wp_list_pluck( $terms, 'name' ) );
                    }

                    $discount = 0;
                    if ( $mrp > 0 && $price < $mrp ) {
                        $discount = round( ( ( $mrp - $price ) / $mrp ) * 100 );
                    }

                    $wishlist    = function_exists( 'dt_get_wishlist' ) ? dt_get_wishlist() : array();
                    $in_wishlist = in_array( $pid, $wishlist );

                    $stars_html = '';
                    for ( $s = 0; $s < 5; $s++ ) {
                        $fc = $s < round( $rating ) ? '#C8A46A' : '#444';
                        $stars_html .= '<svg width="10" height="10" viewBox="0 0 24 24" fill="' . $fc . '" stroke="none"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.252.583 1.828l-3.978 2.89a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.978-2.89a1 1 0 00-1.176 0l-3.978 2.89c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.978-2.89c-.777-.576-.378-1.828.583-1.828h4.907a1 1 0 00.95-.69l1.519-4.674z"/></svg>';
                    }
                ?>
                <div class="ec-card" onclick="window.location.href='<?php echo esc_url( get_permalink( $pid ) ); ?>'" style="animation-delay:<?php echo $idx * 60; ?>ms">
                    <!-- Image -->
                    <div class="ec-card-img-wrap">
                        <span class="ec-card-badge">
                            <svg viewBox="0 0 24 24" fill="#000" width="8" height="8"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                            Best
                        </span>
                        <img class="ec-card-img ec-img-main" src="<?php echo esc_url( $gallery_urls[0] ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy"/>
                        <?php if ( isset( $gallery_urls[1] ) ) : ?>
                        <img class="ec-card-img ec-img-hover" src="<?php echo esc_url( $gallery_urls[1] ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy"/>
                        <?php endif; ?>
                        <!-- Hover overlay -->
                        <div class="ec-card-overlay">
                            <button class="ec-card-view-btn" onclick="event.stopPropagation(); window.location.href='<?php echo esc_url( get_permalink( $pid ) ); ?>'">
                                View Details
                            </button>
                            <button class="ec-card-cart-btn" onclick="event.stopPropagation(); if(typeof addToCart==='function') addToCart(<?php echo esc_attr( $pid ); ?>, 1, 'black', '5.5m', this)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Add to Bag
                            </button>
                        </div>
                        <!-- Wishlist button -->
                        <button class="ec-card-wishlist" onclick="event.stopPropagation(); if(typeof toggleWishlist==='function') toggleWishlist(<?php echo esc_attr( $pid ); ?>, this)" aria-label="Wishlist">
                            <svg viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? '#C8A46A' : 'none'; ?>" stroke="#C8A46A" stroke-width="1.5" width="16" height="16"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    <!-- Card Info -->
                    <div class="ec-card-info">
                        <span class="ec-card-fabric"><?php echo esc_html( $fabric_label ); ?></span>
                        <h4 class="ec-card-name"><?php echo esc_html( $product->get_name() ); ?></h4>
                        <div class="ec-card-stars"><?php echo $stars_html; ?><span class="ec-card-rating">(<?php echo esc_html( $rating ); ?>)</span></div>
                        <div class="ec-card-price">
                            <span class="ec-card-price-now">₹<?php echo number_format( $price ); ?></span>
                            <?php if ( $mrp > 0 && $price < $mrp ) : ?>
                            <span class="ec-card-price-mrp">₹<?php echo number_format( $mrp ); ?></span>
                            <span class="ec-card-price-off"><?php echo $discount; ?>% OFF</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mobile swipe dots -->
        <div class="ec-slider-dots" id="ec-slider-dots">
            <?php foreach ( $best_ids as $idx => $pid ) : ?>
            <span class="ec-dot <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="ecGoToSlide(<?php echo $idx; ?>)"></span>
            <?php endforeach; ?>
        </div>

        <!-- CTA -->
        <div class="ec-topsellers-cta">
            <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' ) ); ?>" class="ec-btn-outline-gold">
                <?php esc_html_e( 'Shop All Bestsellers', 'dt-ecommerce-theme' ); ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>

    <script>
    (function() {
        'use strict';

        var slider     = document.getElementById('ec-topsellers-slider');
        var progressEl = document.getElementById('ec-slider-progress');
        var prevBtn    = document.getElementById('ec-prev-btn');
        var nextBtn    = document.getElementById('ec-next-btn');
        var playBtn    = document.getElementById('ec-play-btn');
        var pauseIcon  = document.getElementById('ec-pause-icon');
        var playIcon   = document.getElementById('ec-play-icon');
        var dots       = document.querySelectorAll('.ec-dot');

        var autoplayTimer = null;
        var isAutoplay    = true;
        var isPaused      = false;
        var currentIdx    = 0;
        var totalCards    = 0;
        var cardWidth     = 0;
        var visibleCount  = 4;

        function getCardWidth() {
            var cards = slider.querySelectorAll('.ec-card');
            totalCards = cards.length;
            if ( cards.length > 0 ) {
                cardWidth = cards[0].offsetWidth + 12; // gap
            }
            var w = window.innerWidth;
            if ( w >= 768 )   visibleCount = 4;
            else if ( w >= 480 )  visibleCount = 2;
            else                  visibleCount = 2;
        }

        function updateDots() {
            dots.forEach(function(d, i) { d.classList.toggle('active', i === currentIdx); });
        }

        function updateProgress() {
            if ( progressEl ) {
                var n = Math.min(totalCards, currentIdx + 1);
                progressEl.textContent = String(n).padStart(2,'0') + ' / ' + String(totalCards).padStart(2,'0');
            }
        }

        function scrollToIdx(idx) {
            getCardWidth();
            var maxIdx = Math.max(0, totalCards - visibleCount);
            currentIdx = Math.max(0, Math.min(idx, maxIdx));
            slider.scrollTo({ left: currentIdx * cardWidth, behavior: 'smooth' });
            updateDots();
            updateProgress();
        }

        window.ecSliderNav = function(dir) {
            scrollToIdx(currentIdx + dir);
            restartAutoplay();
        };

        window.ecGoToSlide = function(idx) {
            scrollToIdx(idx);
            restartAutoplay();
        };

        window.ecToggleAutoplay = function() {
            isAutoplay = !isAutoplay;
            if ( isAutoplay ) {
                startAutoplay();
                if (pauseIcon) pauseIcon.style.display = '';
                if (playIcon)  playIcon.style.display  = 'none';
            } else {
                stopAutoplay();
                if (pauseIcon) pauseIcon.style.display = 'none';
                if (playIcon)  playIcon.style.display  = '';
            }
        };

        function startAutoplay() {
            stopAutoplay();
            if ( !isAutoplay ) return;
            autoplayTimer = setInterval(function() {
                if ( !isPaused ) {
                    getCardWidth();
                    var maxIdx = Math.max(0, totalCards - visibleCount);
                    if ( currentIdx >= maxIdx ) {
                        scrollToIdx(0);
                    } else {
                        scrollToIdx(currentIdx + 1);
                    }
                }
            }, 2800);
        }

        function stopAutoplay() {
            clearInterval(autoplayTimer);
        }

        function restartAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        // Slider scroll sync
        if ( slider ) {
            slider.addEventListener('scroll', function() {
                getCardWidth();
                if ( cardWidth > 0 ) {
                    currentIdx = Math.round(slider.scrollLeft / cardWidth);
                }
                updateDots();
                updateProgress();
            }, { passive: true });

            slider.addEventListener('mouseenter', function() { isPaused = true; });
            slider.addEventListener('mouseleave', function() { isPaused = false; });

            // Touch swipe
            var touchStart = 0;
            slider.addEventListener('touchstart', function(e) { touchStart = e.touches[0].clientX; }, { passive: true });
            slider.addEventListener('touchend', function(e) {
                var diff = touchStart - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) {
                    ecSliderNav(diff > 0 ? 1 : -1);
                }
            }, { passive: true });
        }

        document.addEventListener('DOMContentLoaded', function() {
            getCardWidth();
            updateProgress();
            startAutoplay();

            // Gallery rotation for ec-cards
            document.querySelectorAll('.ec-card').forEach(function(card, ci) {
                var main  = card.querySelector('.ec-img-main');
                var hover = card.querySelector('.ec-img-hover');
                if (!main || !hover) return;
                var showing = 'main';
                setTimeout(function() {
                    setInterval(function() {
                        if (showing === 'main') {
                            main.style.opacity  = '0';
                            hover.style.opacity = '1';
                            showing = 'hover';
                        } else {
                            main.style.opacity  = '1';
                            hover.style.opacity = '0';
                            showing = 'main';
                        }
                    }, 2800);
                }, ci * 400 + 1200);
            });
        });

        window.addEventListener('resize', function() { getCardWidth(); scrollToIdx(currentIdx); });
    })();
    </script>

    <?php endif; // end if best_ids ?>
</div>
<?php
