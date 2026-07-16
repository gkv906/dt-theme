/**
 * DT Ecommerce Theme – Admin Panel JavaScript
 *
 * @package DT_Ecommerce_Theme
 */
(function ($) {
    'use strict';

    // ── Wait for DOM ────────────────────────────────────────────────────────
    $(document).ready(function () {

        // ── Tab Switching ────────────────────────────────────────────────────
        function initTabs() {
            $('.dt-nav-tab').on('click', function (e) {
                e.preventDefault();
                var target = $(this).data('tab');

                // Update active tab button
                $('.dt-nav-tab').removeClass('active');
                $(this).addClass('active');

                // Show/hide tab content
                $('.dt-tab-content').addClass('hidden').hide();
                $('#dt-tab-' + target).removeClass('hidden').show();

                // Update URL hash without scroll
                if (window.history && window.history.pushState) {
                    history.pushState(null, null, '#' + target);
                }
            });

            // Activate tab from URL hash on load
            var hash = window.location.hash.replace('#', '');
            if (hash) {
                $('.dt-nav-tab[data-tab="' + hash + '"]').trigger('click');
            }
        }

        // ── Image Upload via WP Media Uploader ───────────────────────────────
        function initMediaUploaders() {
            $('[data-media-upload]').on('click', function (e) {
                e.preventDefault();
                var $btn     = $(this);
                var inputId  = $btn.data('input');
                var previewId = $btn.data('preview');

                var frame = wp.media({
                    title: dtAdminVars.mediaTitle || 'Select Image',
                    multiple: false,
                    library: { type: 'image' }
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#' + inputId).val(attachment.url);
                    if (previewId) {
                        $('#' + previewId)
                            .attr('src', attachment.url)
                            .removeClass('hidden')
                            .show();
                    }
                });

                frame.open();
            });

            // Remove image buttons
            $('[data-media-remove]').on('click', function (e) {
                e.preventDefault();
                var $btn      = $(this);
                var inputId   = $btn.data('input');
                var previewId = $btn.data('preview');
                $('#' + inputId).val('');
                if (previewId) {
                    $('#' + previewId).attr('src', '').addClass('hidden');
                }
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

        // ── Live Preview for Custom CSS / JS ─────────────────────────────────
        function initCodeEditors() {
            // If CodeMirror is available (WP >= 4.9)
            if (typeof wp !== 'undefined' && wp.codeEditor) {
                var cssTextarea = document.getElementById('dt-custom-css');
                var jsTextarea  = document.getElementById('dt-custom-js');

                if (cssTextarea) {
                    wp.codeEditor.initialize(cssTextarea, {
                        codemirror: { mode: 'css', lineNumbers: true, theme: 'monokai' }
                    });
                }
                if (jsTextarea) {
                    wp.codeEditor.initialize(jsTextarea, {
                        codemirror: { mode: 'javascript', lineNumbers: true, theme: 'monokai' }
                    });
                }
            }
        }

        // ── AJAX Save Settings ───────────────────────────────────────────────
        function initAjaxSave() {
            $('#dt-settings-form').on('submit', function (e) {
                if (!$(this).find('[name="dt_ajax_save"]').length) return; // Let normal submit work

                e.preventDefault();
                var $form   = $(this);
                var $btn    = $form.find('[type="submit"]');
                var origTxt = $btn.text();

                $btn.prop('disabled', true).text(dtAdminVars.savingText || 'Saving…');

                $.post(
                    dtAdminVars.ajaxUrl,
                    {
                        action  : 'dt_save_theme_options',
                        nonce   : dtAdminVars.nonce,
                        data    : $form.serialize()
                    },
                    function (response) {
                        $btn.prop('disabled', false).text(origTxt);
                        if (response.success) {
                            dtShowNotice('success', response.data.message || dtAdminVars.savedText || 'Settings saved!');
                        } else {
                            dtShowNotice('error', response.data || 'Error saving settings.');
                        }
                    }
                ).fail(function () {
                    $btn.prop('disabled', false).text(origTxt);
                    dtShowNotice('error', 'Network error. Please try again.');
                });
            });
        }

        // ── Admin Notice Helper ───────────────────────────────────────────────
        function dtShowNotice(type, message) {
            var cls = 'notice-' + (type === 'success' ? 'success' : 'error') + ' is-dismissible';
            var $notice = $('<div class="notice ' + cls + '" style="margin-top:10px;"><p>' + message + '</p></div>');
            $('.dt-admin-header').after($notice);
            setTimeout(function () {
                $notice.fadeOut(400, function () { $(this).remove(); });
            }, 4000);
        }

        // ── Role Pricing Table: quick inline edit ─────────────────────────────
        function initRolePricingWidget() {
            $(document).on('click', '.dt-role-price-edit', function () {
                var $row    = $(this).closest('tr');
                var $inputs = $row.find('.dt-role-price-field');
                $inputs.prop('readonly', false).focus();
                $(this).hide();
                $row.find('.dt-role-price-save').show();
            });

            $(document).on('click', '.dt-role-price-save', function () {
                var $row = $(this).closest('tr');
                var postId = $row.data('product-id');
                var prices = {};

                $row.find('.dt-role-price-field').each(function () {
                    prices[$(this).data('role')] = $(this).val();
                    $(this).prop('readonly', true);
                });

                $.post(dtAdminVars.ajaxUrl, {
                    action   : 'dt_save_role_prices',
                    nonce    : dtAdminVars.nonce,
                    post_id  : postId,
                    prices   : prices
                }, function (response) {
                    if (response.success) {
                        dtShowNotice('success', 'Prices updated.');
                    }
                });

                $(this).hide();
                $row.find('.dt-role-price-edit').show();
            });
        }

        // ── Setup Wizard Steps ────────────────────────────────────────────────
        function initSetupWizard() {
            var currentStep = 0;
            var $steps = $('.dt-wizard-step');
            var $dots  = $('.dt-wizard-dot');
            var $prev  = $('#dt-wizard-prev');
            var $next  = $('#dt-wizard-next');
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
                            alert('Setup failed. Please try again.');
                        }
                    });
                });
            }
        }

        // ── Settings Search ──────────────────────────────────────────────────
        function initSettingsSearch() {
            var $tabsNav = $('.dt-nav-tabs');
            var $tabContents = $('.dt-tab-content');
            
            $('#dt-settings-search').on('keyup input', function () {
                var query = $(this).val().toLowerCase().trim();
                
                if (query === '') {
                    // Restore tabs
                    $tabsNav.show();
                    $tabContents.addClass('hidden').hide();
                    
                    var activeTab = $('.dt-nav-tab.active').data('tab') || 'general';
                    $('#dt-tab-' + activeTab).removeClass('hidden').show();
                    
                    $('.dt-row, .dt-form-row').show();
                    $('.dt-section').show();
                    return;
                }
                
                // Hide tab navigation, show all contents for global search
                $tabsNav.hide();
                $tabContents.removeClass('hidden').show();
                
                $('.dt-section').each(function () {
                    var $section = $(this);
                    var visibleRows = 0;
                    
                    $section.find('.dt-row, .dt-form-row').each(function () {
                        var $row = $(this);
                        var labelText = $row.find('.dt-label, .dt-form-label').text().toLowerCase();
                        var descText = $row.find('small, .description').text().toLowerCase();
                        
                        if (labelText.indexOf(query) !== -1 || descText.indexOf(query) !== -1) {
                            $row.show();
                            visibleRows++;
                        } else {
                            $row.hide();
                        }
                    });
                    
                    if (visibleRows > 0) {
                        $section.show();
                    } else {
                        $section.hide();
                    }
                });
            });
        }

        // ── Init All ─────────────────────────────────────────────────────────
        initTabs();
        initMediaUploaders();
        initColorPickers();
        initCodeEditors();
        initAjaxSave();
        initRolePricingWidget();
        initSetupWizard();
        initSettingsSearch();

    }); // end ready

}(jQuery));
