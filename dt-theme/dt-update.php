<?php
/**
 * DT Theme One-Click Updater
 * Upload this file to: wp-content/themes/dt-theme/
 * Visit: yoursite.com/wp-content/themes/dt-theme/dt-update.php
 * DELETE this file immediately after running.
 */

// Basic security: only allow if WordPress is loaded OR via browser with secret
$secret = 'dtupdate2026';
if ( ! isset( $_GET['run'] ) || $_GET['run'] !== $secret ) {
    die( '<b>Access denied.</b> Add ?run=dtupdate2026 to the URL.<br>Example: <code>' . ( isset( $_SERVER['HTTP_HOST'] ) ? 'https://' . $_SERVER['HTTP_HOST'] : 'https://yoursite.com' ) . '/wp-content/themes/dt-theme/dt-update.php?run=dtupdate2026</code>' );
}

$base    = __DIR__;
$repo    = 'https://raw.githubusercontent.com/gkv906/dt-theme/master/dt-theme';

$files = [
    'footer.php'           => $repo . '/footer.php',
    'inc/theme-options.php'=> $repo . '/inc/theme-options.php',
    'inc/customizer.php'   => $repo . '/inc/customizer.php',
    // Root-level server copy (same theme, different repo path)
    // Note: only updates if this file exists at the same dir level
];

$results = [];

foreach ( $files as $dest => $url ) {
    $local = $base . '/' . $dest;
    $dir   = dirname( $local );

    // Ensure subdirectory exists
    if ( ! is_dir( $dir ) ) {
        mkdir( $dir, 0755, true );
    }

    // Download the file
    $ctx     = stream_context_create( [ 'http' => [ 'timeout' => 30 ] ] );
    $content = @file_get_contents( $url, false, $ctx );

    if ( $content === false ) {
        $results[] = [ 'file' => $dest, 'ok' => false, 'msg' => 'Download failed — check server has outbound HTTPS access.' ];
        continue;
    }

    // Write it
    $written = file_put_contents( $local, $content );
    if ( $written === false ) {
        $results[] = [ 'file' => $dest, 'ok' => false, 'msg' => 'Write failed — check file permissions on the theme directory.' ];
    } else {
        $results[] = [ 'file' => $dest, 'ok' => true, 'msg' => 'Updated (' . number_format( $written ) . ' bytes)' ];
    }
}

$all_ok = array_reduce( $results, fn( $c, $r ) => $c && $r['ok'], true );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DT Theme Updater</title>
<style>
  body{font-family:system-ui,sans-serif;max-width:640px;margin:60px auto;padding:0 20px;background:#0f0f0f;color:#e5e5e5}
  h1{color:#C8A46A;font-size:1.4rem;margin-bottom:4px}
  p.sub{color:#666;font-size:.85rem;margin:0 0 28px}
  .item{display:flex;align-items:center;gap:12px;padding:12px 16px;margin:8px 0;border-radius:6px;border:1px solid #222}
  .ok{border-color:#166534;background:#052e16}.ok .icon{color:#4ade80}
  .fail{border-color:#7f1d1d;background:#1c0a0a}.fail .icon{color:#f87171}
  .icon{font-size:1.3rem;flex-shrink:0}
  .fname{font-family:monospace;font-size:.9rem;color:#C8A46A}
  .msg{font-size:.8rem;color:#888;margin-top:2px}
  .banner{margin-top:28px;padding:16px 20px;border-radius:8px;font-size:.9rem}
  .banner.success{background:#052e16;border:1px solid #166534;color:#4ade80}
  .banner.error{background:#1c0a0a;border:1px solid #7f1d1d;color:#f87171}
  .warn{margin-top:20px;padding:14px 18px;background:#1c1400;border:1px solid #92400e;border-radius:6px;color:#fbbf24;font-size:.85rem}
  code{background:#1a1a1a;padding:2px 6px;border-radius:3px;font-family:monospace;font-size:.85rem}
</style>
</head>
<body>
<h1>🏷️ DT Theme Updater</h1>
<p class="sub">Pulling latest files from GitHub → gkv906/dt-theme</p>

<?php foreach ( $results as $r ) : ?>
<div class="item <?php echo $r['ok'] ? 'ok' : 'fail'; ?>">
  <span class="icon"><?php echo $r['ok'] ? '✅' : '❌'; ?></span>
  <div>
    <div class="fname"><?php echo htmlspecialchars( $r['file'] ); ?></div>
    <div class="msg"><?php echo htmlspecialchars( $r['msg'] ); ?></div>
  </div>
</div>
<?php endforeach; ?>

<?php if ( $all_ok ) : ?>
<div class="banner success">
  ✅ All 3 files updated successfully!<br>
  Go to <strong>WP Admin → DT Theme Options → Footer</strong> — you will now see the <strong>Footer Brand Identity</strong> section with logo upload, brand name, and tagline fields.
</div>
<div class="warn">
  ⚠️ <strong>Delete this file now!</strong><br>
  Remove <code>dt-update.php</code> from your theme folder (via Hostinger File Manager) to keep your site secure.
</div>
<?php else : ?>
<div class="banner error">
  ❌ Some files failed to update. Check the errors above.<br>
  Try running the <code>curl</code> commands via Hostinger SSH Terminal instead.
</div>
<?php endif; ?>

</body>
</html>
