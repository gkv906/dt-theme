/**
 * DT Ecommerce Theme – Admin Panel JavaScript v2.0
 * Next-level admin: AJAX save, keyboard shortcuts, unsaved-changes guard,
 * restore-defaults, live status, per-tab save persistence.
 *
 * @package DT_Ecommerce_Theme
 */
(function ($) {
    'use strict';

    /* ── Helpers ────────────────────────────────────────────────────────────── */
    function dtCurrentTime() {
        var d  = new Date();
        var hh = ('0' + d.getHours()).slice(-2);
        var mm = ('0' + d.getMinutes()).slice(-2);
        return 'at ' + hh + ':' + mm;
    }

    /* ── Toast Notification ─────────────────────────────────────────────────── */
    function dtToast(type, message, duration) {
        duration = duration || 4000;
        $('.dt-toast').remove();

        var icons = {
            success : '✔',
            error   : '✕',
            info    : 'ℹ'
        };
        var icon = icons[type] || icons.info;

        var $toast = $(
            '<div class="dt-toast dt-toast-' + type + '" role="alert">' +
                '<span style="font-size:16px;font-weight:700;flex-shrink:0;">' + icon + '</span>' +
                '<span>' + message + '</span>' +
            '</div>'
        );
        $('body').append($toast);

        // Animate in
        requestAnimationFrame(function () {
            $toast.css({ transform: 'translateY(80px)', opacity: '0' });
            requestAnimationFrame(function () {
                $toast.css({ transform: 'translateY(0)', opacity: '1' });
            });
        });

        setTimeout(function () {
            $toast.addClass('dt-toast-hidden');
            setTimeout(function () { $toast.remove(); }, 400);
        }, duration);
    }

    /* ── Status Indicator ───────────────────────────────────────────────────── */
    function setStatus(state, text) {
        var $status = $('#dt-save-status');
        $status.removeClass('saved unsaved').removeAttr('class');
        $status.addClass('dt-save-status');

        var $dot = $status.find('.dt-save-status-dot');
        if (!$dot.length) {
            $status.prepend('<span class="dt-save-status-dot"></span>');
            $dot = $status.find('.dt-save-status-dot');
        }

        if (state === 'saved') {
            $status.addClass('saved');
            $dot.removeClass('dt-pulse');
        } else if (state === 'unsaved') {
            $status.addClass('unsaved');
            $dot.addClass('dt-pulse');
        } else {
            $status.css('color', '');
            $dot.removeClass('dt-pulse');
        }
        $status.find('span:not(.dt-save-status-dot)').text(text || '');
    }

    /* ── Tab Switching ──────────────────────────────────────────────────────── */
    function initTabs() {
        $('.dt-options-sidebar').on('click', '.dt-nav-tab', function (e) {
            e.preventDefault();
            var target = $(this).data('tab');
            if (!target) return;

            $('.dt-nav-tab').removeClass('active');
            $(this).addClass('active');

            $('.dt-tab-content').addClass('hidden').hide();
            $('#dt-tab-' + target).removeClass('hidden').fadeIn(160);

            if (window.history && window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', target);
                history.replaceState(null, '', url.toString());
            }
        });

        // Activate from URL param or default
        var urlParams = new URLSearchParams(window.location.search);
        var initTab   = urlParams.get('tab') || window.location.hash.replace('#', '') || 'general';
        var $initBtn  = $('.dt-nav-tab[data-tab="' + initTab + '"]');
        if ($initBtn.length) {
            $initBtn.trigger('click');
        } else {
            $('.dt-nav-tab[data-tab="general"]').trigger('click');
        }
    }

    /* ── Image Upload via WP Media Uploader ─────────────────────────────────── */
    function initMediaUploaders() {
        $(document).on('click', '[data-media-upload]', function (e) {
            e.preventDefault();
            var $btn      = $(this);
            var inputId   = $btn.data('input');
            var previewId = $btn.data('preview');

            var frame = wp.media({
                title    : (typeof dtAdminVars !== 'undefined' && dtAdminVars.mediaTitle) || 'Select Image',
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

    /* ── Color Pickers ──────────────────────────────────────────────────────── */
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

    /* ── Code Editors ───────────────────────────────────────────────────────── */
    function initCodeEditors() {
        if (typeof wp !== 'undefined' && wp.codeEditor) {
            var cssTextarea = document.getElementById('dt-custom-css');
            var jsTextarea  = document.getElementById('dt-custom-js');
            if (cssTextarea) {
                wp.codeEditor.initialize(cssTextarea, {
                    codemirror: { mode: 'css', lineNumbers: true, theme: 'default' }
                });
            }
            if (jsTextarea) {
                wp.codeEditor.initialize(jsTextarea, {
                    codemirror: { mode: 'javascript', lineNumbers: true, theme: 'default' }
                });
            }
        }
    }

    /* ── AJAX Save ──────────────────────────────────────────────────────────── */
    var isSaving = false;

    function doAjaxSave($form, onComplete) {
        if (isSaving) return;
        isSaving = true;

        var $saveBtn   = $('#dt-btn-save-main, [name="dt_options_save"]');
        var scrollY    = window.scrollY;
        var origText   = $saveBtn.html();

        $saveBtn.prop('disabled', true).html(
            '<svg style="width:14px;height:14px;animation:spin 0.8s linear infinite;display:inline-block;vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Saving…'
        );
        setStatus('idle', 'Saving your settings…');

        $.post(
            dtAdminVars.ajaxUrl,
            {
                action : 'dt_save_theme_options',
                nonce  : dtAdminVars.nonce,
                data   : $form.serialize()
            },
            function (response) {
                isSaving = false;
                $saveBtn.prop('disabled', false).html(origText);
                window.scrollTo(0, scrollY);
                hasUnsaved = false;

                if (response.success) {
                    dtToast('success', '✔ Settings saved — frontend updated!');
                    setStatus('saved', 'Saved ' + dtCurrentTime());
                } else {
                    dtToast('error', response.data || 'Error saving. Please try again.');
                    setStatus('unsaved', 'Save failed — try again');
                }
                if (typeof onComplete === 'function') onComplete(response.success);
            }
        ).fail(function () {
            isSaving = false;
            $saveBtn.prop('disabled', false).html(origText);
            dtToast('error', 'Network error — please try again.');
            setStatus('unsaved', 'Network error');
            if (typeof onComplete === 'function') onComplete(false);
        });
    }

    function initAjaxSave() {
        $('#dt-settings-form').on('submit', function (e) {
            e.preventDefault();
            doAjaxSave($(this));
        });

        // Dedicated save button (in case it's outside the form)
        $(document).on('click', '#dt-btn-save-main', function (e) {
            e.preventDefault();
            doAjaxSave($('#dt-settings-form'));
        });

        // Header quick-save button
        $(document).on('click', '#dt-btn-header-save', function (e) {
            e.preventDefault();
            doAjaxSave($('#dt-settings-form'));
        });
    }

    /* ── Keyboard Shortcut: Ctrl+S / Cmd+S ──────────────────────────────────── */
    function initKeyboardShortcuts() {
        $(document).on('keydown', function (e) {
            var isMac  = navigator.platform.toUpperCase().indexOf('MAC') >= 0;
            var isSave = (isMac ? e.metaKey : e.ctrlKey) && e.key === 's';

            if (isSave) {
                e.preventDefault();
                doAjaxSave($('#dt-settings-form'));
            }

            if (e.key === 'Escape') {
                $('#dt-settings-search').val('').trigger('input');
            }
        });
    }

    /* ── Restore Defaults ───────────────────────────────────────────────────── */
    function initRestoreDefaults() {
        $(document).on('click', '#dt-btn-restore-defaults', function (e) {
            e.preventDefault();

            if (!confirm(
                'Reset ALL theme settings to default values?\n\n' +
                'This will overwrite your current configuration. This cannot be undone.\n\n' +
                'Click OK to continue.'
            )) {
                return;
            }

            var $btn     = $(this);
            var origHtml = $btn.html();
            $btn.prop('disabled', true).text('Resetting…');
            dtToast('info', 'Restoring default settings…', 2000);

            $.post(
                dtAdminVars.ajaxUrl,
                {
                    action : 'dt_restore_default_options',
                    nonce  : dtAdminVars.nonce
                },
                function (response) {
                    if (response.success) {
                        dtToast('success', '✔ Defaults restored! Reloading…', 3000);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $btn.prop('disabled', false).html(origHtml);
                        dtToast('error', response.data || 'Reset failed. Please try again.');
                    }
                }
            ).fail(function () {
                $btn.prop('disabled', false).html(origHtml);
                dtToast('error', 'Network error — could not reset settings.');
            });
        });
    }

    /* ── Copy Export JSON ───────────────────────────────────────────────────── */
    function initExportCopy() {
        $(document).on('click', '#dt-btn-copy-export', function (e) {
            e.preventDefault();
            var text = $('#dt-export-json').val();
            if (!text) {
                dtToast('error', 'Nothing to copy.');
                return;
            }
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function () {
                    dtToast('success', '✔ Configuration copied to clipboard!');
                });
            } else {
                // Fallback
                $('#dt-export-json').select();
                document.execCommand('copy');
                dtToast('success', '✔ Configuration copied!');
            }
        });
    }

    /* ── Role Pricing Quick Edit ─────────────────────────────────────────────── */
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

    /* ── Setup Wizard ────────────────────────────────────────────────────────── */
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
            $next.on('click', function () {
                showStep(Math.min(currentStep + 1, $steps.length - 1));
            });
            $prev.on('click', function () {
                showStep(Math.max(currentStep - 1, 0));
            });

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

    /* ── Settings Search ─────────────────────────────────────────────────────── */
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
    }

    /* ── Unsaved-Changes Detection ──────────────────────────────────────────── */
    var hasUnsaved = false;

    function initUnsavedTracking() {
        $(document).on(
            'input change',
            '#dt-settings-form .dt-input, #dt-settings-form .dt-textarea, #dt-settings-form .dt-select, #dt-settings-form input[type="checkbox"], #dt-settings-form .dt-color-picker',
            function () {
                if (!hasUnsaved) {
                    hasUnsaved = true;
                    setStatus('unsaved', 'Unsaved changes');
                    // Pulse the save button gently
                    $('#dt-btn-save-main').addClass('dt-pulse');
                    setTimeout(function () {
                        $('#dt-btn-save-main').removeClass('dt-pulse');
                    }, 2000);
                }
            }
        );

        $(window).on('beforeunload', function () {
            if (hasUnsaved) {
                return 'You have unsaved changes. Leave without saving?';
            }
        });

        $('#dt-settings-form').on('submit', function () {
            hasUnsaved = false;
        });
    }

    /* ── Inject spin keyframe if not already present ────────────────────────── */
    function injectSpinKeyframe() {
        if (!document.getElementById('dt-spin-style')) {
            var s = document.createElement('style');
            s.id = 'dt-spin-style';
            s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
            document.head.appendChild(s);
        }
    }

    /* ── Init All ────────────────────────────────────────────────────────────── */
    $(document).ready(function () {
        injectSpinKeyframe();
        initTabs();
        initMediaUploaders();
        initColorPickers();
        initCodeEditors();
        initAjaxSave();
        initKeyboardShortcuts();
        initRestoreDefaults();
        initExportCopy();
        initRolePricingWidget();
        initSetupWizard();
        initSettingsSearch();
        initUnsavedTracking();

        // Initialize Lucide icons (re-run after DOM ready)
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }

        // Initial status
        setStatus('idle', 'All settings saved to database.');
    });

}(jQuery));
