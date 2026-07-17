/**
 * DT Ecommerce Theme Customizer Live Preview bindings.
 * Handles instant frontend rendering when fields are updated in the customizer pane.
 *
 * @package DT_Ecommerce_Theme
 */
(function ($) {
    'use strict';

    // Header: Deliver Location
    wp.customize('dt_theme_options[header_location]', function (value) {
        value.bind(function (newval) {
            $('.dt-header-location').text(newval);
        });
    });

    // Header: Tagline
    wp.customize('dt_theme_options[header_tagline]', function (value) {
        value.bind(function (newval) {
            $('.dt-logo-tagline').text(newval);
        });
    });

    // Header: Logo Height
    wp.customize('dt_theme_options[logo_height]', function (value) {
        value.bind(function (newval) {
            $('.animate-logo').css('height', (newval ? newval : 40) + 'px');
        });
    });

    // Hero: Title
    wp.customize('dt_theme_options[hero_heading]', function (value) {
        value.bind(function (newval) {
            $('.dt-hero-heading').html(newval.replace(/\n/g, '<br>'));
        });
    });

    // Hero: Subtext
    wp.customize('dt_theme_options[hero_subtext]', function (value) {
        value.bind(function (newval) {
            $('.dt-hero-subtext').text(newval);
        });
    });

    // Footer: Copyright
    wp.customize('dt_theme_options[footer_copyright]', function (value) {
        value.bind(function (newval) {
            $('.dt-footer-copyright').html(newval);
        });
    });

    // Footer: About Brand
    wp.customize('dt_theme_options[footer_about]', function (value) {
        value.bind(function (newval) {
            $('.dt-footer-about').text(newval);
        });
    });

})(jQuery);
