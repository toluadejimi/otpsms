<?php
require_once __DIR__ . '/../../include/config.php';

$token = mysqli_real_escape_string($conn, $_POST['token']);
$provider_id = (int) $_POST['provider_id'];
$plan_id = (int) $_POST['plan_id'];
$smartcard = mysqli_real_escape_string($conn, $_POST['smartcard']);

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
        cp.*,
        ctp.api_cable_id
    FROM cable_tv_plans cp
    INNER JOIN cable_tv_providers ctp 
        ON cp.cable_id = ctp.id
    WHERE cp.id = '$plan_id'
      AND cp.status = 1
      AND ctp.status = 1
");

if (mysqli_num_rows($pq) == 0) {
    echo json_encode(['status' => '500', 'message' => 'Invalid cable plan']);
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
$ref = uniqid("CABLE_");
mysqli_query($conn, "
    INSERT INTO cable_tv_orders
    (user_id, cable_provider_id, cable_tv_plan_id, smartcard_number, amount, api_reference, status, created_at)
    VALUES
    ('$user_id','$provider_id','$plan_id','$smartcard','{$plan['selling_price']}','$ref','0',NOW())
");

$order_id = mysqli_insert_id($conn);

/* External API */
$post_data = [
    'cable' => $plan['api_cable_id'],
    'cable_plan' => $plan['api_plan_id'],
    'iuc' => $smartcard,
    'request-id' => $ref,
    'bypass' => false,
];

$ch = curl_init("https://n3tdata.com/api/cable");
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
        UPDATE cable_tv_orders
        SET status=2, status_description='{$response['message']}'
        WHERE id='$order_id'
    ");
    echo json_encode(['status' => '500', 'message' => $response['message']]);
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
    UPDATE cable_tv_orders
    SET status=1, status_description='Successful'
    WHERE id='$order_id'
");

echo json_encode(['status' => '200', 'message' => 'Cable subscription successful']);
