<?php
/**
 * EMERGENCY cPanel Password Reset Script
 * USE ONCE, THEN DELETE
 */

// ================= CONFIG =================
$cpanelUser = 'otpsxxdu';
$apiToken   = 'UBBAK38F3G2UTQHMLBPNOFUCMFEAWY5D';
$cpanelHost = 'localhost'; // usually localhost
$secretKey  = 'kenny';
// ==========================================

// Basic protection
if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    http_response_code(403);
    exit('Access denied');
}

// New password (change before use)
$newPassword = 'Teniteno123';

// cPanel UAPI endpoint
$url = "https://{$cpanelHost}:2083/execute/Passwd/change_password";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: cpanel {$cpanelUser}:{$apiToken}"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'old_password' => '',
    'new_password' => $newPassword
]));
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    exit('Curl error: ' . curl_error($ch));
}

curl_close($ch);

$data = json_decode($response, true);

if (isset($data['status']) && $data['status'] == 1) {
    echo "SUCCESS: cPanel password changed.<br>";
    echo "<strong>DELETE THIS FILE NOW.</strong>";
} else {
    echo "FAILED:<br>";
    echo '<pre>' . htmlspecialchars($response) . '</pre>';
}
