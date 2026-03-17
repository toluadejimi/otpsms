<?php
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

function response($status, $data) {
    echo json_encode(array_merge(["status" => $status], $data));
    exit;
}

$provider_query = mysqli_query($conn, "SELECT * FROM tg_providers WHERE status = 1 LIMIT 1");
if(mysqli_num_rows($provider_query) != 1){
    response(500, ["message" => "Provider not configured"]);
}

$provider = mysqli_fetch_assoc($provider_query);
$api_key = $provider['api_key'];
$base_url = rtrim($provider['base_url'], '/');


// Get USD to NGN rate
$rate_query = mysqli_query($conn, "SELECT usd_to_ngn FROM settings LIMIT 1");
if(mysqli_num_rows($rate_query) != 1){
    response(500, ["message" => "Exchange rate not configured"]);
}

$rate_data = mysqli_fetch_assoc($rate_query);
$usd_to_ngn = floatval($rate_data['usd_to_ngn']);

// Call Fragment API
$url = $base_url . "/premium/packages";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "API-Key: {$api_key}"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch);

if ($result === false) {
    response(500, ["message" => "Failed to fetch packages"]);
}

$packages = json_decode($result, true);

if (!is_array($packages)) {
    response(500, ["message" => "Invalid provider response"]);
}

$final_packages = [];

foreach ($packages as $pkg) {

    $months = intval($pkg['months']);
    $provider_usd = floatval($pkg['usd_value']);

    // Get markup from DB
    $product_query = mysqli_query(
        $conn,
        "SELECT markup_percent FROM tg_products 
         WHERE product_type='premium' AND months='{$months}' LIMIT 1"
    );

    $markup_percent = 0;
    if(mysqli_num_rows($product_query) == 1){
        $product_data = mysqli_fetch_assoc($product_query);
        $markup_percent = floatval($product_data['markup_percent']);
    }

    // Convert USD → NGN
    $base_ngn = $provider_usd * $usd_to_ngn;

    // Apply markup
    $final_ngn = $base_ngn + ($base_ngn * $markup_percent / 100);

    $final_packages[] = [
        "months" => $months,
        "provider_usd" => $provider_usd,
        "price_ngn" => ceil($final_ngn)
        // "markup_percent" => $markup_percent
    ];
}

response(200, ["packages" => $final_packages]);