<?php
require_once __DIR__ . '/../../include/config.php';

$token = mysqli_real_escape_string($conn, $_POST['token']);
$provider_id = (int) $_POST['provider_id'];
$meter_type = mysqli_real_escape_string($conn, $_POST['meter_type']);
$meter_number = mysqli_real_escape_string($conn, $_POST['meter_number']);
$amount = (float) $_POST['amount'];

/* Auth */
$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if (!$user_id) {
    echo json_encode(['status' => '500', 'message' => 'Session expired']);
    exit;
}

/* Get provider */
$pq = mysqli_query($conn, "
    SELECT * FROM electricity_providers
    WHERE id='$provider_id' AND status=1
");
if (mysqli_num_rows($pq) == 0) {
    echo json_encode(['status' => '500', 'message' => 'Invalid electricity provider']);
    exit;
}
$provider = mysqli_fetch_assoc($pq);

/* Wallet */
$wq = mysqli_query($conn, "SELECT balance FROM user_wallet WHERE user_id='$user_id'");
$wallet = mysqli_fetch_assoc($wq);

if ($wallet['balance'] < $amount) {
    echo json_encode(['status' => '500', 'message' => 'Insufficient balance']);
    exit;
}

/* Create pending order */
$ref = uniqid("ELEC_");
mysqli_query($conn, "
    INSERT INTO electricity_orders
    (user_id, electricity_provider_id, meter_number, meter_type, amount, api_reference, status, created_at)
    VALUES
    ('$user_id','$provider_id','$meter_number','$meter_type','$amount','$ref','0',NOW())
");

$order_id = mysqli_insert_id($conn);

/* External API */
$post_data = [
    'disco' => $provider['disco_id'],
    'meter_number' => $meter_number,
    'meter_type' => $meter_type,
    'amount' => $amount,
    'request-id' => $ref,
    'bypass' => false
];

$ch = curl_init("https://n3tdata.com/api/bill");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = [
    "Authorization: Token 488eb5766139d640efff6a2bfa71068c1610b4af16adf4e0d6eb812e2d70",
    'Content-Type: application/json'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!isset($response['status']) || $response['status'] != 'success') {
    mysqli_query($conn, "
        UPDATE electricity_orders
        SET status=2, status_description='{$response['message']}'
        WHERE id='$order_id'
    ");
    echo json_encode(['status' => '500', 'message' => $response['message']]);
    exit;
}

/* Deduct wallet */
$new_balance = $wallet['balance'] - $amount;
mysqli_query($conn, "
    UPDATE user_wallet SET balance='$new_balance'
    WHERE user_id='$user_id'
");

/* Success update */
$token_value = $response['token'] ?? null;
$status_desc = $token_value ? "Token: $token_value" : "Successful";

mysqli_query($conn, "
    UPDATE electricity_orders
    SET status=1, token='$token_value', status_description='$status_desc'
    WHERE id='$order_id'
");

if (function_exists('site_activity_log')) {
    $pname = $provider['name'] ?? 'Provider';
    $last4 = substr((string)$meter_number, -4);
    site_activity_log($conn, [
        'user_id' => (int)$user_id,
        'direction' => 'debit',
        'activity_type' => 'Electricity',
        'amount' => $amount,
        'status' => 1,
        'summary' => 'Electricity ' . $pname . ' · ****' . $last4,
        'ref' => $ref,
        'dedupe_key' => 'electricity_orders:' . (int)$order_id,
    ]);
}

echo json_encode([
    'status' => '200',
    'message' => 'Electricity payment successful',
    'token' => $token_value
]);
