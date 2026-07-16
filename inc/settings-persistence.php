<?php
/**
 * Settings Persistence System
 *
 * Keeps theme settings safe across theme deletions and reinstalls by:
 *  1. Auto-backing up to wp-content/uploads/dt-theme/ on every save
 *     (outside the theme folder — survives deletion/reinstall)
 *  2. Auto-restoring from backup on theme activation when DB is empty
 *  3. Migrating legacy key names written by older theme versions
 *
 * @package DT_Ecommerce_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── Constants ────────────────────────────────────────────────────────────────

/** Directory inside wp-content/uploads where the backup lives. */
define( 'DT_BACKUP_DIR_NAME', 'dt-theme' );

/** Backup filename. */
define( 'DT_BACKUP_FILE_NAME', 'settings-backup.json' );

// ── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Return the absolute path to the backup directory, creating it if needed.
 * Returns false if it can't be created or written.
 *
 * @return string|false
 */
function dt_get_backup_dir() {
    $upload_dir = wp_upload_dir( null, false );
    if ( ! empty( $upload_dir['error'] ) ) {
        return false;
    }
    $dir = trailingslashit( $upload_dir['basedir'] ) . DT_BACKUP_DIR_NAME;
    if ( ! file_exists( $dir ) ) {
        wp_mkdir_p( $dir );
        // Drop a blank index.php for security
        file_put_contents( $dir . '/index.php', '<?php // Silence is golden.' );
    }
    return is_writable( $dir ) ? $dir : false;
}

/**
 * Return the absolute path to the backup JSON file (may not exist yet).
 *
 * @return string|false
 */
function dt_get_backup_path() {
    $dir = dt_get_backup_dir();
    return $dir ? $dir . '/' . DT_BACKUP_FILE_NAME : false;
}

// ── Backup ───────────────────────────────────────────────────────────────────

/**
 * Write the current dt_theme_options to the external backup file.
 * Called after every successful save.
 *
 * @param array $options The options array to back up.
 * @return bool True on success.
 */
function dt_backup_settings( array $options ): bool {
    $path = dt_get_backup_path();
    if ( ! $path ) {
        return false;
    }
    $json = wp_json_encode(
        array(
            '_backup_version' => '2.0',
            '_backup_time'    => gmdate( 'c' ),
            '_site_url'       => get_option( 'siteurl' ),
            'options'         => $options,
        ),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    return (bool) file_put_contents( $path, $json, LOCK_EX );
}

/**
 * Read options from the external backup file.
 *
 * @return array|false The options array on success, false if no valid backup.
 */
function dt_read_backup(): array|false {
    $path = dt_get_backup_path();
    if ( ! $path || ! file_exists( $path ) ) {
        return false;
    }
    $raw = file_get_contents( $path );
    if ( ! $raw ) {
        return false;
    }
    $data = json_decode( $raw, true );
    if ( ! is_array( $data ) || empty( $data['options'] ) || ! is_array( $data['options'] ) ) {
        return false;
    }
    return $data['options'];
}

// ── Key migration ─────────────────────────────────────────────────────────────

/**
 * Map of OLD option keys (written by older theme versions) to CORRECT new keys.
 * Add to this list whenever a key is renamed in future versions.
 */
function dt_get_legacy_key_map(): array {
    return array(
        // Old demo-import.php used wrong key names
        'primary_color'       => 'color_primary',
        'bg_color'            => 'color_bg_main',
        'text_color'          => 'color_text_primary',
        'heading_font'        => 'headings_font_family',
        'body_font'           => 'body_font_family',
        'accent_color'        => 'color_primary',
        'primary_dark'        => 'color_primary_dark',
        'primary_light'       => 'color_primary_light',
        'card_bg'             => 'color_bg_card',
        'header_bg'           => 'color_bg_header',
        'footer_bg'           => 'color_bg_footer',
        'text_secondary'      => 'color_text_secondary',
        'heading_color'       => 'color_text_heading',
        'btn_color'           => 'color_btn_bg',
        'btn_text_color'      => 'color_btn_text',
        'announcement_bg'     => 'color_announcement_bg',
        'announcement_color'  => 'color_announcement_text',
    );
}

/**
 * Migrate an options array — renames legacy keys to their current equivalents.
 * Non-conflicting: only renames if the new key doesn't already have a value.
 *
 * @param array $options Raw options from DB or backup.
 * @return array Migrated options.
 */
function dt_migrate_option_keys( array $options ): array {
    $map = dt_get_legacy_key_map();
    foreach ( $map as $old_key => $new_key ) {
        if ( isset( $options[ $old_key ] ) && ! isset( $options[ $new_key ] ) ) {
            $options[ $new_key ] = $options[ $old_key ];
            unset( $options[ $old_key ] );
        }
    }
    return $options;
}

// ── On-save backup hook ───────────────────────────────────────────────────────

/**
 * Automatically back up settings whenever the WordPress option is updated.
 * Hooked into the option update action so it fires for both AJAX saves
 * and regular form-submit saves.
 *
 * @param mixed  $old_value Previous value.
 * @param mixed  $new_value New value being saved.
 */
function dt_auto_backup_on_save( $old_value, $new_value ): void {
    if ( is_array( $new_value ) && ! empty( $new_value ) ) {
        dt_backup_settings( $new_value );
    }
}
add_action( 'update_option_dt_theme_options', 'dt_auto_backup_on_save', 10, 2 );

// ── On-activation restore ─────────────────────────────────────────────────────

/**
 * Run on theme activation (after_switch_theme).
 * 1. Migrates any legacy key names already in the DB.
 * 2. If the DB option is empty, restores from backup file.
 * 3. Sets factory defaults if nothing else is available.
 */
function dt_on_theme_activated(): void {
    $current = get_option( 'dt_theme_options', array() );

    // Step 1 — Migrate legacy keys regardless of whether settings exist
    if ( ! empty( $current ) ) {
        $migrated = dt_migrate_option_keys( $current );
        if ( $migrated !== $current ) {
            update_option( 'dt_theme_options', $migrated );
            $current = $migrated;
        }
    }

    // Step 2 — If DB is empty (new install / DB wiped), try backup file
    if ( empty( $current ) ) {
        $backup = dt_read_backup();
        if ( $backup && is_array( $backup ) ) {
            // Migrate backup keys too (backup may be from old version)
            $backup = dt_migrate_option_keys( $backup );
            update_option( 'dt_theme_options', $backup );
            // Store a flag so admin can show a notice
            update_option( 'dt_settings_restored_from_backup', '1' );
            return;
        }
    }

    // Step 3 — Nothing in DB and no backup: write factory defaults
    if ( empty( $current ) ) {
        if ( function_exists( 'dt_set_default_theme_options' ) ) {
            dt_set_default_theme_options();
        }
    }
}
add_action( 'after_switch_theme', 'dt_on_theme_activated' );

// ── Admin notice after auto-restore ──────────────────────────────────────────

/**
 * Show a friendly admin notice when settings were restored from backup.
 */
function dt_show_settings_restored_notice(): void {
    if ( get_option( 'dt_settings_restored_from_backup' ) !== '1' ) {
        return;
    }
    delete_option( 'dt_settings_restored_from_backup' );
    ?>
    <div class="notice notice-success is-dismissible" style="border-left-color:#C8A46A;padding:12px 16px;">
        <p>
            <strong>DT Theme:</strong>
            Your previous settings were automatically restored from the backup file.
            All your custom colours, fonts, and configuration are back.
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=dt-theme-options' ) ); ?>">
                Review settings →
            </a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'dt_show_settings_restored_notice' );

// ── AJAX: manual backup download ──────────────────────────────────────────────

/**
 * AJAX handler — returns the backup file path info for diagnostic purposes.
 * Used by the admin panel to show backup status.
 */
function dt_ajax_get_backup_status(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }
    $path   = dt_get_backup_path();
    $exists = $path && file_exists( $path );
    $time   = '';
    if ( $exists ) {
        $raw  = file_get_contents( $path );
        $data = json_decode( $raw, true );
        $time = isset( $data['_backup_time'] ) ? $data['_backup_time'] : '';
    }
    wp_send_json_success( array(
        'exists' => $exists,
        'time'   => $time,
        'path'   => $path ? basename( dirname( $path ) ) . '/' . basename( $path ) : '',
    ) );
}
add_action( 'wp_ajax_dt_get_backup_status', 'dt_ajax_get_backup_status' );

// ── AJAX: run key migration now ───────────────────────────────────────────────

/**
 * AJAX handler — migrates legacy key names in the DB immediately.
 * Can be called from the admin panel if user upgraded from an old version.
 */
function dt_ajax_migrate_settings(): void {
    check_ajax_referer( 'dt_admin_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }
    $current  = get_option( 'dt_theme_options', array() );
    $migrated = dt_migrate_option_keys( $current );
    $changed  = array_keys( array_diff_key(
        dt_get_legacy_key_map(),
        array_flip( array_keys( $current ) )
    ) );
    if ( $migrated !== $current ) {
        update_option( 'dt_theme_options', $migrated );
        wp_send_json_success( array(
            'message' => 'Settings migrated successfully. ' . count( $changed ) . ' key(s) updated.',
            'updated' => $changed,
        ) );
    } else {
        wp_send_json_success( array(
            'message' => 'Settings are already up to date — no migration needed.',
            'updated' => array(),
        ) );
    }
}
add_action( 'wp_ajax_dt_migrate_settings', 'dt_ajax_migrate_settings' );
