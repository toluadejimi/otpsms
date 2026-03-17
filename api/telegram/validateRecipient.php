<?php
require_once __DIR__ . '/../../include/config.php';
header('Content-Type: application/json');

function response($status, $data) {
    echo json_encode(array_merge(["status" => $status], $data));
    exit;
}

/* ===============================
   1️⃣ Validate Required Params
================================= */
$required = ['token', 'type', 'username'];
foreach($required as $field){
    if(!isset($_GET[$field]) || empty($_GET[$field])){
        response(500, ["message" => ucfirst($field)." required"]);
    }
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$type = strtolower(trim($_GET['type']));
$username = trim($_GET['username']);

/* ===============================
   2️⃣ Validate Token
================================= */
$wallet = new radiumsahil();
$user_id = $wallet->check_token($token);
$wallet->closeConnection();

if ($user_id === false) {
    response(500, ["message" => "Token expired"]);
}

/* ===============================
   3️⃣ Validate Username Format
================================= */
if (!preg_match('/^[a-zA-Z0-9_]{5,32}$/', $username)) {
    response(500, ["message" => "Invalid Telegram username"]);
}

/* ===============================
   4️⃣ Get Provider + Exchange Rate
================================= */
$provider_query = mysqli_query($conn, "SELECT * FROM tg_providers WHERE status=1 LIMIT 1");
if (mysqli_num_rows($provider_query) != 1) response(500, ["message" => "Provider not configured"]);
$provider = mysqli_fetch_assoc($provider_query);
$api_key = $provider['api_key'];
$base_url = rtrim($provider['base_url'], '/');

$rate_q = mysqli_query($conn, "SELECT usd_to_ngn FROM settings LIMIT 1");
$rate_data = mysqli_fetch_assoc($rate_q);
$usd_to_ngn = floatval($rate_data['usd_to_ngn'] ?? 0);

/* ===============================
   5️⃣ Validate Product & Get Price
================================= */
if ($type === "star") {
    if (!isset($_GET['quantity']) || empty($_GET['quantity'])) response(500, ["message" => "Quantity required"]);
    $quantity = intval($_GET['quantity']);

    // Use provider API to get amount in USD for quantity
    $endpoint = "/star/recipient/search?username={$username}&quantity={$quantity}";
} elseif ($type === "premium") {
    if (!isset($_GET['months']) || empty($_GET['months'])) response(500, ["message" => "Months required"]);
    $months = intval($_GET['months']);

    $endpoint = "/premium/recipient/search?username={$username}&months={$months}";
} else {
    response(500, ["message" => "Invalid gift type"]);
}

/* ===============================
   6️⃣ Call Provider API to Validate Recipient
================================= */
$ch = curl_init($base_url . $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["API-Key: {$api_key}"]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$result = curl_exec($ch);
if ($result === false) response(500, ["message" => "Validation request failed"]);

$response_data = json_decode($result, true);

if (!isset($response_data['success']) || $response_data['success'] !== true) {
    response(500, ["message" => "Telegram user not found"]);
}

/* ===============================
   8️⃣ Return Response
================================= */
response(200, [
    "recipient" => $response_data['recipient'],
    "name" => $response_data['name'],
    "photo" => $response_data['photo'],
    "myself" => $response_data['myself'] ?? false,
]);