<?php
require_once __DIR__ . '/../../include/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => '500', 'message' => 'Invalid request']);
    exit;
}

$token = mysqli_real_escape_string($conn, $_POST['token']);
$network_id = (int) $_POST['network_id'];
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$amount = (float) $_POST['amount'];

/* Validate token */
$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if (!$user_id) {
    echo json_encode(['status' => '500', 'message' => 'Session expired']);
    exit;
}

/* Get network */
$net_q = mysqli_query($conn, "SELECT * FROM networks WHERE id='$network_id' AND status=1");
if (mysqli_num_rows($net_q) == 0) {
    echo json_encode(['status' => '500', 'message' => 'Invalid network']);
    exit;
}
$network = mysqli_fetch_assoc($net_q);

/* Wallet */
$wallet_q = mysqli_query($conn, "SELECT * FROM user_wallet WHERE user_id='$user_id'");
$wallet = mysqli_fetch_assoc($wallet_q);

if ($wallet['balance'] < $amount) {
    echo json_encode(['status' => '500', 'message' => 'Insufficient balance']);
    exit;
}

/* Create pending order */
$ref = uniqid("AIR_");
mysqli_query($conn, "INSERT INTO airtime_orders 
    (user_id, network_id, phone, amount, api_reference, status, created_at)
    VALUES ('$user_id','$network_id','$phone','$amount','$ref','0',NOW())");

$order_id = mysqli_insert_id($conn);

/* Call N3Data API */
$post_data = [
    'network' => $network['api_network_id'],
    'plan_type' => "VTU",
    'phone' => $phone,
    'amount' => $amount,
    'request-id' => $ref,
    'bypass' => false
];

$ch = curl_init("https://n3tdata.com/api/topup");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch, CURLOPT_HTTPHEADER, [
        "Authorization: Token 488eb5766139d640efff6a2bfa71068c1610b4af16adf4e0d6eb812e2d70",
        'Content-Type: application/json'
    ]
);
$response_raw = curl_exec($ch);
curl_close($ch);

$response = json_decode($response_raw, true);

if (!isset($response['status']) || $response['status'] != 'success') {

    mysqli_query($conn, "UPDATE airtime_orders 
        SET status=2, status_description='{$response['message']}'
        WHERE id='$order_id'");

    echo json_encode(['status' => '500', 'message' => $response['message']]);
    exit;
}

/* Deduct wallet */
$new_balance = $wallet['balance'] - $amount;
mysqli_query($conn, "UPDATE user_wallet SET balance='$new_balance' WHERE user_id='$user_id'");

/* Update order */
mysqli_query($conn, "UPDATE airtime_orders 
    SET status=1, status_description='Successful'
    WHERE id='$order_id'");

if (function_exists('site_activity_log')) {
    $netLabel = $network['name'] ?? 'Network';
    $last4 = substr((string)$phone, -4);
    site_activity_log($conn, [
        'user_id' => (int)$user_id,
        'direction' => 'debit',
        'activity_type' => 'Airtime',
        'amount' => $amount,
        'status' => 1,
        'summary' => 'Airtime ' . $netLabel . ' · ****' . $last4,
        'ref' => $ref,
        'dedupe_key' => 'airtime_orders:' . (int)$order_id,
    ]);
}

echo json_encode(['status' => '200', 'message' => 'Airtime sent successfully']);
