<?php
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$inputData = file_get_contents('php://input');
$webhookData = json_decode($inputData, true);

if ($webhookData === null) {
    http_response_code(400);
    exit;
}

/* ===============================
   1️⃣ Verify Signature
================================= */

$provider_query = mysqli_query($conn, "SELECT webhook_secret FROM tg_providers WHERE status = 1 LIMIT 1");
$provider = mysqli_fetch_assoc($provider_query);
$webhook_secret = $provider['webhook_secret'];
$signature_header = $_SERVER['HTTP_X_ISTAR_SIGNATURE'] ?? '';

$calculated_signature = hash_hmac('sha256', $inputData, $webhook_secret);

if (!hash_equals($calculated_signature, $signature_header)) {
    http_response_code(403);
    exit;
}

/* ===============================
   2️⃣ Extract Webhook Data
================================= */

$event_type = $webhookData['event_type'] ?? null;
$order = $webhookData['order'] ?? null;

if (!$event_type || !$order) {
    http_response_code(400);
    exit;
}

$provider_order_id = $order['id'] ?? null;

if (!$provider_order_id) {
    http_response_code(400);
    exit;
}

/* ===============================
   3️⃣ Check Order Exists & Avoid Duplicate Processing
================================= */

$order_q = mysqli_query($conn, "SELECT * FROM tg_orders WHERE provider_order_id='{$provider_order_id}' LIMIT 1");
if (mysqli_num_rows($order_q) !== 1) {
    http_response_code(404);
    exit;
}

$local_order = mysqli_fetch_assoc($order_q);
if ($local_order['status'] === 'completed') {
    http_response_code(200);
    echo json_encode(['message' => 'Already processed']);
    exit;
}

/* ===============================
   4️⃣ Begin Transaction
================================= */

mysqli_begin_transaction($conn);

$status = ($event_type === 'order.completed') ? 'completed' : 'failed';

mysqli_query($conn, "
    UPDATE tg_orders 
    SET status='{$status}', provider_response='" . mysqli_real_escape_string($conn, json_encode($order)) . "', updated_at=NOW()
    WHERE id='{$local_order['id']}'
");

/* ===============================
   5️⃣ Refund on Failure
================================= */

if ($status === 'failed') {
    $user_id = $local_order['user_id'];
    $amount_to_refund = floatval($local_order['user_charged_amount']);

    // Lock wallet row
    $wallet_q = mysqli_query($conn, "SELECT balance FROM user_wallet WHERE user_id='{$user_id}' FOR UPDATE");
    $wallet_data = mysqli_fetch_assoc($wallet_q);

    $new_balance = $wallet_data['balance'] + $amount_to_refund;
    mysqli_query($conn, "UPDATE user_wallet SET balance='{$new_balance}' WHERE user_id='{$user_id}'");

    // Log transaction
    // mysqli_query($conn, "
    //     INSERT INTO wallet_transactions (user_id, type, amount, description) 
    //     VALUES ('{$user_id}', 'credit', '{$amount_to_refund}', 'Refund for failed Telegram order #{$local_order['local_order_id']}')
    // ");
}

/* ===============================
   6️⃣ Commit & Respond
================================= */

mysqli_commit($conn);

http_response_code(200);
echo json_encode(['message' => 'Webhook processed successfully']);