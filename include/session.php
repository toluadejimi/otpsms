<?php
// Ensure session cookie works on http://localhost (no Secure flag on HTTP)
$already_active = (session_status() === PHP_SESSION_ACTIVE);
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

// Only set cookie params / start session if not already active.
// Some legacy pages call session_start() before including config.php.
if (!$already_active) {
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'path'     => '/',
            'secure'   => $is_https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        session_set_cookie_params(0, '/', null, $is_https, true);
    }
    session_start();
}
?>