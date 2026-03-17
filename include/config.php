<?php
// error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Africa/Lagos');
define('DB_SERVER', '127.0.0.1'); // localhost
define('DB_USERNAME', 'root');     // db username (local: usually root, no password)
define('DB_PASSWORD', '');        // db password – empty for local
define('DB_DATABASE', 'otpsms'); // db name – change if yours is different

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../class/class.control.php';
$site_sql = $conn->query("SELECT * FROM settings WHERE id='1'");
$site_data = $site_sql->fetch_assoc();
$theam = $site_data['theam'];

// Use correct protocol (http on localhost, https when TLS is on)
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
$protocol = $is_https ? 'https' : 'http';
$website_url = $protocol . '://' . $_SERVER['HTTP_HOST'];

// On localhost, ensure redirects and links use current host (e.g. http://localhost:9090)
$is_localhost = isset($_SERVER['HTTP_HOST'])
    && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
if ($is_localhost) {
    $site_data['web_url'] = $website_url;
}

$web_name = $site_data['web_name'];

define("THEAM", $theam);
define("WEBSITE_URL", $website_url);

?>