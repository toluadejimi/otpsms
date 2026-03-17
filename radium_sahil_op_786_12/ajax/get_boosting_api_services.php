<?php
require_once __DIR__ . '/../../include/config.php'; 

header('Content-Type: application/json');

$api_id = isset($_POST['api_id']) ? intval($_POST['api_id']) : 0;

if ($api_id <= 0) {
    echo json_encode(['error' => 'Invalid API ID']);
    exit;
}

$sql = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE id = '$api_id' AND status = 1");
$api = mysqli_fetch_assoc($sql);

if (!$api) {
    echo json_encode(['error' => 'API not found or inactive']);
    exit;
}

$api_key = $api['api_key'];
$api_url = rtrim($api['api_url'], '/') . "/api/v2";

$post_fields = [
    'key' => $api_key,
    'action' => 'services'
];

// Make cURL request to external API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['error' => "cURL Error: $error"]);
    exit;
}

$decoded = json_decode($response, true);

if (!is_array($decoded)) {
    echo json_encode(['error' => 'Invalid API response']);
    exit;
}

echo json_encode(['services' => $decoded]);
