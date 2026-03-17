<?php
require_once __DIR__ . '/../../include/config.php';
header('Content-Type: application/json');

function response($status, $data){
    echo json_encode(array_merge(["status"=>$status], $data));
    exit;
}

function generateOrderId($length=20){
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,$length);
}

/* 1️⃣ Read JSON body */
$data = json_decode(file_get_contents("php://input"), true);
if(!$data) response(500, ["message"=>"Invalid request"]);

$required = ['token','type','username','recipient_hash'];
foreach($required as $field){
    if(empty($data[$field])) response(500, ["message"=>"{$field} required"]);
}

$token = mysqli_real_escape_string($conn, $data['token']);
$type = strtolower(trim($data['type']));
$username = trim($data['username']);
$recipient_hash = trim($data['recipient_hash']);

/* 2️⃣ Validate token */
$wallet = new radiumsahil();
$user_id = $wallet->check_token($token);
$wallet->closeConnection();
if(!$user_id) response(500, ["message"=>"Token expired"]);

/* 3️⃣ Validate product rules & fetch markup/price */
$user_charge = 0;
$api_endpoint = '';
$payload = [
    "username"=>$username,
    "recipient_hash"=>$recipient_hash,
    "wallet_type"=>"TON"
];

if($type==="star"){
    if(empty($data['quantity'])) response(500, ["message"=>"Quantity required"]);
    $quantity = intval($data['quantity']);
    $product_q = mysqli_query($conn,"SELECT price_per_star_ngn, markup_percent, min_quantity, max_quantity FROM tg_products WHERE product_type='star' AND status=1 LIMIT 1");
    if(mysqli_num_rows($product_q)!=1) response(500, ["message"=>"Star product not configured"]);
    $product = mysqli_fetch_assoc($product_q);

    if($quantity < $product['min_quantity'] || $quantity > $product['max_quantity']){
        response(500, ["message"=>"Quantity out of range"]);
    }

    $price_per_star = floatval($product['price_per_star_ngn']);
    $markup_percent = floatval($product['markup_percent']);
    $user_charge = ceil($quantity * $price_per_star * (1 + $markup_percent/100));

    // Fragment API endpoint for stars
    $api_endpoint = "/orders/star";
    $payload['quantity'] = $quantity;

} elseif($type==="premium"){
    if(empty($data['months'])) response(500, ["message"=>"Months required"]);
    $months = intval($data['months']);

    // Get provider + USD to NGN
    $provider_q = mysqli_query($conn,"SELECT * FROM tg_providers WHERE status=1 LIMIT 1");
    if(mysqli_num_rows($provider_q)!=1) response(500, ["message"=>"Provider not configured"]);
    $provider = mysqli_fetch_assoc($provider_q);
    $api_key = $provider['api_key'];
    $base_url = rtrim($provider['base_url'],'/');

    $rate_q = mysqli_query($conn,"SELECT usd_to_ngn FROM settings LIMIT 1");
    if(mysqli_num_rows($rate_q)!=1) response(500, ["message"=>"Exchange rate missing"]);
    $rate_data = mysqli_fetch_assoc($rate_q);
    $usd_to_ngn = floatval($rate_data['usd_to_ngn']);

    // Fetch USD amount for selected package
    $ch = curl_init($base_url."/premium/packages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["API-Key: {$api_key}"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $pkg_result = curl_exec($ch);
    if ($pkg_result === false) response(500, ["message" => "Failed to fetch packages"]);
    $packages = json_decode($pkg_result, true);
    if (!is_array($packages)) response(500, ["message" => "Invalid provider response"]);

    $package_found = false;
    foreach ($packages as $pkg) {
        if (intval($pkg['months']) === $months) {
            $provider_usd = floatval($pkg['usd_value']);
            $package_found = true;

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

            // Convert USD → NGN + markup
            $base_ngn = $provider_usd * $usd_to_ngn;
            $user_charge = ceil($base_ngn + ($base_ngn * $markup_percent / 100));
            break;
        }
    }
    if (!$package_found) response(500, ["message" => "Selected premium package not found"]);

    // Fragment API endpoint for premium
    $api_endpoint = "/orders/premium";
    $payload['months'] = $months;

} else {
    response(500, ["message"=>"Invalid type"]);
}

/* 4️⃣ Check wallet and start transaction */
mysqli_begin_transaction($conn);
$wallet_q = mysqli_query($conn,"SELECT balance FROM user_wallet WHERE user_id='{$user_id}' FOR UPDATE");
$wallet_data = mysqli_fetch_assoc($wallet_q);
$current_balance = floatval($wallet_data['balance']);

if($current_balance < $user_charge){
    mysqli_rollback($conn);
    response(500, ["message"=>"Insufficient balance"]);
}

// Deduct wallet
$new_balance = $current_balance - $user_charge;
mysqli_query($conn,"UPDATE user_wallet SET balance='{$new_balance}' WHERE user_id='{$user_id}'");

/* 5️⃣ Place real order with Fragment API */
$ch = curl_init($base_url.$api_endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["API-Key: {$api_key}", "Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$api_result = curl_exec($ch);
if($api_result === false){
    mysqli_rollback($conn);
    response(500, ["message"=>"Failed to place order with provider"]);
}

$api_data = json_decode($api_result,true);
if(!isset($api_data['order_id'])){
    mysqli_rollback($conn);
    response(500, ["message"=>"Provider did not return order ID"]);
}

$provider_order_id = $api_data['order_id'];
$amount_usd = isset($api_data['amount']) ? floatval($api_data['amount']) : 0;

/* 6️⃣ Insert order locally */
$local_order_id = generateOrderId();
mysqli_query($conn,
    "INSERT INTO tg_orders
    (user_id, provider_id, local_order_id, provider_order_id, order_type, username, recipient_hash, quantity, months, user_charged_amount, amount_usd, wallet_type, status, provider_response)
    VALUES
    ('{$user_id}', '{$provider['id']}', '{$local_order_id}', '{$provider_order_id}', '{$type}', '{$username}', '{$recipient_hash}', "
    . ($type==='star'? "'{$quantity}'" : "NULL") . ","
    . ($type==='premium'? "'{$months}'" : "NULL") . ","
    . "'{$user_charge}', '{$amount_usd}', 'TON', 'pending', '".mysqli_real_escape_string($conn,$api_result)."')"
);

mysqli_commit($conn);

/* 7️⃣ Return response */
response(200, [
    "message"=>"Order placed successfully",
    "order_id"=>$local_order_id,
    "charged_ngn"=>$user_charge
]);