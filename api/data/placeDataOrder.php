<?php
require_once __DIR__ . '/../../include/config.php';

$token = mysqli_real_escape_string($conn, $_POST['token']);
$network_id = (int) $_POST['network_id'];
$plan_id = (int) $_POST['plan_id'];
$phone = mysqli_real_escape_string($conn, $_POST['phone']);

/* Auth */
$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if (!$user_id) {
    echo json_encode(['status' => '500', 'message' => 'Session expired']);
    exit;
}

/* Get plan */
$pq = mysqli_query($conn, "
    SELECT 
        dp.*,
        n.api_network_id
    FROM data_plans dp
    INNER JOIN networks n ON dp.network_id = n.id
    WHERE dp.id = '$plan_id'
      AND dp.status = 1
      AND n.status = 1
");

if (mysqli_num_rows($pq) == 0) {
    echo json_encode(['status' => '500', 'message' => 'Invalid data plan']);
    exit;
}
$plan = mysqli_fetch_assoc($pq);

/* Wallet */
$wq = mysqli_query($conn, "SELECT balance FROM user_wallet WHERE user_id='$user_id'");
$wallet = mysqli_fetch_assoc($wq);

if ($wallet['balance'] < $plan['selling_price']) {
    echo json_encode(['status' => '500', 'message' => 'Insufficient balance']);
    exit;
}

/* Create pending order */
$ref = uniqid("DATA_");
mysqli_query($conn, "
    INSERT INTO data_orders
    (user_id, network_id, data_plan_id, phone, amount, api_reference, status, created_at)
    VALUES
    ('$user_id','$network_id','$plan_id','$phone','{$plan['selling_price']}','$ref','0',NOW())
");

$order_id = mysqli_insert_id($conn);

/* External API */
$post_data = [
    'network' => (int) $plan['api_network_id'],
    'data_plan' => (int) $plan['api_plan_id'],
    'phone' => $phone,
    'request-id' => $ref,
    'bypass' => false
];

$ch = curl_init("https://n3tdata.com/api/data");
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
        UPDATE data_orders SET status=2, status_description='{$response['message']}'
        WHERE id='$order_id'
    ");
    echo json_encode(['status' => '500', 'message' => "Error purchasing data plan"]);
    exit;
}

/* Deduct wallet */
$new_balance = $wallet['balance'] - $plan['selling_price'];
mysqli_query($conn, "
    UPDATE user_wallet SET balance='$new_balance'
    WHERE user_id='$user_id'
");

/* Complete order */
mysqli_query($conn, "
    UPDATE data_orders SET status=1, status_description='Successful'
    WHERE id='$order_id'
");

echo json_encode(['status' => '200', 'message' => 'Data purchase successful']);
