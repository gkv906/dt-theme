/**
 * DT Ecommerce Theme – Admin Panel JavaScript
 *
 * @package DT_Ecommerce_Theme
 */
(function ($) {
    'use strict';

    $(document).ready(function () {

        // ── Tab Switching ────────────────────────────────────────────────────
        function initTabs() {
            // On sidebar tab click: switch panel without page reload
            $('.dt-options-sidebar').on('click', '.dt-nav-tab', function (e) {
                e.preventDefault();
                var target = $(this).data('tab');
                if (!target) return;

                $('.dt-nav-tab').removeClass('active');
                $(this).addClass('active');

                $('.dt-tab-content').addClass('hidden').hide();
                $('#dt-tab-' + target).removeClass('hidden').fadeIn(180);

                if (window.history && window.history.replaceState) {
                    var url = new URL(window.location.href);
                    url.searchParams.set('tab', target);
                    history.replaceState(null, '', url.toString());
                }
            });

            // Activate tab from URL ?tab= param or hash on load
            var urlParams = new URLSearchParams(window.location.search);
            var initTab   = urlParams.get('tab') || window.location.hash.replace('#', '') || 'general';
            var $initBtn  = $('.dt-nav-tab[data-tab="' + initTab + '"]');
            if ($initBtn.length) {
                $initBtn.trigger('click');
            } else {
                $('.dt-nav-tab[data-tab="general"]').trigger('click');
            }
        }

        // ── Image Upload via WP Media Uploader ───────────────────────────────
        function initMediaUploaders() {
            $(document).on('click', '[data-media-upload]', function (e) {
                e.preventDefault();
                var $btn      = $(this);
                var inputId   = $btn.data('input');
                var previewId = $btn.data('preview');

                var frame = wp.media({
                    title    : (typeof dtAdminVars !== 'undefined' ? dtAdminVars.mediaTitle : '') || 'Select Image',
                    multiple : false,
                    library  : { type: 'image' }
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#' + inputId).val(attachment.url).trigger('input');
                    if (previewId) {
                        $('#' + previewId)
                            .attr('src', attachment.url)
                            .removeClass('hidden')
                            .show();
                    }
                });
                frame.open();
            });

            $(document).on('click', '[data-media-remove]', function (e) {
                e.preventDefault();
                var inputId   = $(this).data('input');
                var previewId = $(this).data('preview');
                $('#' + inputId).val('').trigger('input');
                if (previewId) $('#' + previewId).attr('src', '').addClass('hidden').hide();
            });
        }

        // ── Color Pickers ────────────────────────────────────────────────────
        function initColorPickers() {
            if ($.fn.wpColorPicker) {
                $('.dt-color-picker').wpColorPicker({
                    change: function (event, ui) {
                        $(this).val(ui.color.toString()).trigger('input');
                    },
                    clear: function () {
                        $(this).val('').trigger('input');
                    }
                });
            }
        }

        // ── Code Editors ─────────────────────────────────────────────────────
        function initCodeEditors() {
            if (typeof wp !== 'undefined' && wp.codeEditor) {
                var cssTextarea = document.getElementById('dt-custom-css');
                var jsTextarea  = document.getElementById('dt-custom-js');
                if (cssTextarea) wp.codeEditor.initialize(cssTextarea, { codemirror: { mode: 'css',        lineNumbers: true, theme: 'default' } });
                if (jsTextarea)  wp.codeEditor.initialize(jsTextarea,  { codemirror: { mode: 'javascript', lineNumbers: true, theme: 'default' } });
            }
        }

        // ── Toast Notification ────────────────────────────────────────────────
        function dtToast(type, message, duration) {
            duration = duration || 4000;
            $('.dt-toast').remove();

            var icon = type === 'success' ? '✓' : '✕';
            var $toast = $(
                '<div class="dt-toast dt-toast-' + type + '">' +
                    '<span style="font-size:15px;font-weight:700;">' + icon + '</span>' +
                    '<span>' + message + '</span>' +
                '</div>'
            );
            $('body').append($toast);

            setTimeout(function () {
                $toast.addClass('dt-toast-hidden');
                setTimeout(function () { $toast.remove(); }, 350);
            }, duration);
        }

        // ── AJAX Save ────────────────────────────────────────────────────────
        function initAjaxSave() {
            $('#dt-settings-form').on('submit', function (e) {
                e.preventDefault();

                var $form    = $(this);
                var $btn     = $form.find('[name="dt_options_save"]');
                var $status  = $('#dt-save-status');
                var scrollY  = window.scrollY;

                $btn.prop('disabled', true).text('Saving…');
                $status.text('').removeClass('saved');

                $.post(
                    dtAdminVars.ajaxUrl,
                    {
                        action : 'dt_save_theme_options',
                        nonce  : dtAdminVars.nonce,
                        data   : $form.serialize()
                    },
                    function (response) {
                        $btn.prop('disabled', false).text('💾 Save Settings');
                        window.scrollTo(0, scrollY);

                        if (response.success) {
                            dtToast('success', '✔ Settings saved — frontend updated!');
                            $status.text('Saved ' + dtCurrentTime()).addClass('saved');
                        } else {
                            dtToast('error', response.data || 'Error saving. Please try again.');
                        }
                    }
                ).fail(function () {
                    $btn.prop('disabled', false).text('💾 Save Settings');
                    dtToast('error', 'Network error — please try again.');
                });
            });
        }

        function dtCurrentTime() {
            var d = new Date();
            return 'at ' + d.getHours() + ':' + ('0' + d.getMinutes()).slice(-2);
        }

        // ── Role Pricing Quick Edit ───────────────────────────────────────────
        function initRolePricingWidget() {
            $(document).on('click', '.dt-role-price-edit', function () {
                var $row = $(this).closest('tr');
                $row.find('.dt-role-price-field').prop('readonly', false).focus();
                $(this).hide();
                $row.find('.dt-role-price-save').show();
            });

            $(document).on('click', '.dt-role-price-save', function () {
                var $row   = $(this).closest('tr');
                var postId = $row.data('product-id');
                var prices = {};

                $row.find('.dt-role-price-field').each(function () {
                    prices[$(this).data('role')] = $(this).val();
                    $(this).prop('readonly', true);
                });

                $.post(dtAdminVars.ajaxUrl, {
                    action  : 'dt_save_role_prices',
                    nonce   : dtAdminVars.nonce,
                    post_id : postId,
                    prices  : prices
                }, function (response) {
                    if (response.success) dtToast('success', 'Prices updated.');
                });

                $(this).hide();
                $row.find('.dt-role-price-edit').show();
            });
        }

        // ── Setup Wizard ──────────────────────────────────────────────────────
        function initSetupWizard() {
            var currentStep = 0;
            var $steps  = $('.dt-wizard-step');
            var $dots   = $('.dt-wizard-dot');
            var $prev   = $('#dt-wizard-prev');
            var $next   = $('#dt-wizard-next');
            var $finish = $('#dt-wizard-finish');

            function showStep(n) {
                $steps.addClass('hidden').hide();
                $steps.eq(n).removeClass('hidden').show();
                $dots.removeClass('active');
                $dots.eq(n).addClass('active');
                $prev.toggle(n > 0);
                $next.toggle(n < $steps.length - 1);
                $finish.toggle(n === $steps.length - 1);
                currentStep = n;
            }

            if ($steps.length) {
                showStep(0);
                $next.on('click', function () { showStep(Math.min(currentStep + 1, $steps.length - 1)); });
                $prev.on('click', function () { showStep(Math.max(currentStep - 1, 0)); });

                $finish.on('click', function () {
                    var $btn = $(this).prop('disabled', true).text('Setting up…');
                    $.post(dtAdminVars.ajaxUrl, {
                        action : 'dt_run_setup_wizard',
                        nonce  : dtAdminVars.nonce
                    }, function (response) {
                        if (response.success) {
                            window.location.href = dtAdminVars.adminUrl + 'admin.php?page=dt-theme-options&setup=done';
                        } else {
                            $btn.prop('disabled', false).text('Finish Setup');
                            dtToast('error', 'Setup failed. Please try again.');
                        }
                    });
                });
            }
        }

        // ── Settings Search ───────────────────────────────────────────────────
        function initSettingsSearch() {
            var $sidebar  = $('.dt-options-sidebar');
            var $contents = $('.dt-tab-content');

            $('#dt-settings-search').on('keyup input', function () {
                var query = $(this).val().toLowerCase().trim();

                if (query === '') {
                    $sidebar.show();
                    $contents.addClass('hidden').hide();
                    var active = $('.dt-nav-tab.active').data('tab') || 'general';
                    $('#dt-tab-' + active).removeClass('hidden').show();
                    $('.dt-form-row, .dt-section').show();
                    return;
                }

                $sidebar.hide();
                $contents.removeClass('hidden').show();

                $('.dt-section').each(function () {
                    var $section = $(this);
                    var visible  = 0;

                    $section.find('.dt-form-row').each(function () {
                        var text = $(this).find('.dt-form-label').text().toLowerCase() +
                                   $(this).find('small').text().toLowerCase();
                        if (text.indexOf(query) !== -1) {
                            $(this).show();
                            visible++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (visible > 0) $section.show(); else $section.hide();
                });
            });

            // Clear search on X key or Escape
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $('#dt-settings-search').val('').trigger('input');
                }
            });
        }

        // ── Live color preview on color picker change ─────────────────────────
        function initLiveColorPreview() {
            // When a color picker value changes, update the live indicator
            $(document).on('input', '.dt-color-picker', function () {
                $('#dt-save-status').text('Unsaved changes').removeClass('saved').css('color', '#d97706');
            });
            $(document).on('input change', '.dt-input, .dt-select, .dt-textarea', function () {
                $('#dt-save-status').text('Unsaved changes').removeClass('saved').css('color', '#d97706');
            });
        }

        // ── "Unsaved changes" warning on page leave ───────────────────────────
        var hasUnsaved = false;
        $(document).on('input change', '#dt-settings-form .dt-input, #dt-settings-form .dt-textarea, #dt-settings-form .dt-select, #dt-settings-form input[type="checkbox"]', function () {
            hasUnsaved = true;
        });
        $(window).on('beforeunload', function () {
            if (hasUnsaved) return 'You have unsaved changes. Leave anyway?';
        });
        $('#dt-settings-form').on('submit', function () {
            hasUnsaved = false;
        });

        // ── Init All ──────────────────────────────────────────────────────────
        initTabs();
        initMediaUploaders();
        initColorPickers();
        initCodeEditors();
        initAjaxSave();
        initRolePricingWidget();
        initSetupWizard();
        initSettingsSearch();
        initLiveColorPreview();

    }); // end ready

}(jQuery));
