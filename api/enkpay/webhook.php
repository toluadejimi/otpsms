<?php
/**
 * Enkpay / EnKash webhook: receive payment notifications and fund user wallet.
 * Resolve user by email or reference; idempotency by reference/quickCollectRequestId.
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$inputData = file_get_contents('php://input');
$body = json_decode($inputData, true);

if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$payload = $body['payload'] ?? $body['data'] ?? $body;

// EnKash-style: quickCollectRequestId, type, amount, paymentStatus, paymentDate
// Also support: order_id, session_id, account_no, email, amount (no status = assume success)
$paymentStatus = strtoupper(trim((string) (
    $payload['paymentStatus'] ?? $payload['status'] ?? $payload['transaction_status'] ?? $payload['payment_status'] ??
    $body['paymentStatus'] ?? $body['status'] ?? $body['transaction_status'] ?? $body['payment_status'] ?? ''
)));
$amount = (float) ($payload['amount'] ?? $payload['amount_paid'] ?? $payload['total'] ?? $body['amount'] ?? 0);
$email = trim((string) ($payload['email'] ?? $payload['customer_email'] ?? $body['email'] ?? ''));
$orderId = trim((string) ($payload['order_id'] ?? $payload['ref'] ?? $payload['reference'] ?? $body['order_id'] ?? ''));
$quickCollectId = trim((string) ($payload['quickCollectRequestId'] ?? $body['quickCollectRequestId'] ?? ''));
$accountNo = trim((string) ($payload['account_no'] ?? $body['account_no'] ?? ''));
$reference = $orderId ?: $quickCollectId ?: ('enk-' . ($body['transaction_id'] ?? uniqid('', true)));

// Only credit on success. If no status field, treat as success when we have a valid payment payload (Enkpay often only calls webhook on success).
$statusOk = in_array($paymentStatus, ['SUCCESS', '1', 'COMPLETED', 'PAID', 'COMPLETE'], true);
$noStatusButValidPayload = ($paymentStatus === '' && $amount > 0 && ($orderId !== '' || $quickCollectId !== ''));

if (!$statusOk && !$noStatusButValidPayload) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Status not success, skipped', 'status' => $paymentStatus]);
    exit;
}

if ($amount <= 0 || !is_finite($amount)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

if (!$email && !$accountNo) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing email or account_no in webhook payload']);
    exit;
}

// Idempotency: skip if we already processed this reference
$ref_safe = mysqli_real_escape_string($conn, $reference);
$check = $conn->query("SELECT id FROM user_transaction WHERE txn_id = '$ref_safe' LIMIT 1");
if ($check && $check->num_rows > 0) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Already processed']);
    exit;
}

$user_id = null;

if ($email) {
    $email_safe = mysqli_real_escape_string($conn, $email);
    $uq = $conn->query("SELECT id FROM user_data WHERE email = '$email_safe' LIMIT 1");
    if ($uq && $row = $uq->fetch_assoc()) {
        $user_id = (int) $row['id'];
    }
}

if (!$user_id && $accountNo) {
    $acc_safe = mysqli_real_escape_string($conn, $accountNo);
    $stmt = $conn->prepare("SELECT user_id FROM bank_accounts WHERE account_number = ? LIMIT 1");
    $stmt->bind_param("s", $acc_safe);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        $user_id = (int) $row['user_id'];
    }
}

if (!$user_id) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found for email/account_no']);
    exit;
}

$current_time = date('Y-m-d H:i:s');
$amount_safe = mysqli_real_escape_string($conn, $amount);
$user_id_safe = (int) $user_id;

$gq = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'Enkpay' OR name = 'EnKash' OR name LIKE '%Enkpay%' OR name LIKE '%EnKash%') AND status = 1 LIMIT 1");
$gateway_id = 0;
if ($gq && $gw = $gq->fetch_assoc()) {
    $gateway_id = (int) $gw['id'];
}

$gateway_id_safe = 0;
if ($gateway_id > 0) {
    $gateway_id_safe = (int) $gateway_id;
} else {
    // Ensure gateway_id is a valid FK reference (never use 0).
    // Some schemas require business_id (NOT NULL). Reuse an existing business_id if present, else default to 1.
    $biz_id = 1;
    $biz_q = mysqli_query($conn, "SELECT business_id FROM payment_gateways WHERE business_id IS NOT NULL LIMIT 1");
    if ($biz_q && $biz_row = mysqli_fetch_assoc($biz_q)) {
        $biz_id = (int) ($biz_row['business_id'] ?? 1);
        if ($biz_id <= 0) $biz_id = 1;
    }

    $name_s = mysqli_real_escape_string($conn, 'Enkpay');
    @mysqli_query($conn, "INSERT INTO payment_gateways (business_id, name, api_key, secret_key, status) VALUES ('$biz_id', '$name_s', '', '', 1)");
    $new_id = (int) mysqli_insert_id($conn);
    if ($new_id > 0) {
        $gateway_id_safe = $new_id;
    } else {
        // Absolute fallback: use any existing gateway id to satisfy FK.
        $any = $conn->query("SELECT id FROM payment_gateways ORDER BY id ASC LIMIT 1");
        if ($any && $row = $any->fetch_assoc()) {
            $gateway_id_safe = (int) $row['id'];
        }
    }
}

$gateway_id_sql = $gateway_id_safe > 0 ? (string) $gateway_id_safe : 'NULL';

$conn->query("INSERT INTO user_transaction (user_id, amount, date, type, gateway_id, txn_id, status) 
              VALUES ('$user_id_safe', '$amount_safe', '$current_time', 'Enkpay', $gateway_id_sql, '$ref_safe', '1')");
$ut_insert_id = (int)$conn->insert_id;
if ($ut_insert_id > 0 && function_exists('site_activity_log')) {
    site_activity_log($conn, [
        'user_id' => $user_id_safe,
        'direction' => 'credit',
        'activity_type' => 'Deposit',
        'amount' => (float)$amount,
        'status' => 1,
        'summary' => 'Wallet top-up via Enkpay · Success',
        'ref' => $ref_safe,
        'dedupe_key' => 'user_transaction:' . $ut_insert_id,
    ]);
}

$wallet_q = $conn->query("SELECT balance, total_recharge FROM user_wallet WHERE user_id = '$user_id_safe' LIMIT 1");
$wallet_data = $wallet_q ? $wallet_q->fetch_assoc() : null;
$new_balance = $wallet_data ? (float) $wallet_data['balance'] + $amount : $amount;
$new_total_rc = $wallet_data ? (float) $wallet_data['total_recharge'] + $amount : $amount;

$conn->query("UPDATE user_wallet SET balance = '$new_balance', total_recharge = '$new_total_rc' WHERE user_id = '$user_id_safe'");

// Referral bonus (same as SprintPay)
$refer_q = $conn->query("SELECT refer_by FROM refer_data WHERE user_id = '$user_id_safe' LIMIT 1");
$refer_data = $refer_q ? $refer_q->fetch_assoc() : null;
if ($refer_data && !empty($refer_data['refer_by'])) {
    $by_code = mysqli_real_escape_string($conn, $refer_data['refer_by']);
    $ref_by_q = $conn->query("SELECT user_id, balance, total_earn FROM refer_data WHERE own_code = '$by_code' LIMIT 1");
    if ($ref_by_q && $ref_by_data = $ref_by_q->fetch_assoc()) {
        $add_refer_bal = 100;
        $by_balance = (float) $ref_by_data['balance'] + $add_refer_bal;
        $by_total_earn = (float) $ref_by_data['total_earn'] + $add_refer_bal;
        $conn->query("UPDATE refer_data SET balance = '$by_balance', total_earn = '$by_total_earn' WHERE own_code = '$by_code'");
        $ref_user_id = (int) $ref_by_data['user_id'];
        $ref_wallet = $conn->query("SELECT balance, total_recharge FROM user_wallet WHERE user_id = '$ref_user_id' LIMIT 1")->fetch_assoc();
        if ($ref_wallet) {
            $ref_new_bal = (float) $ref_wallet['balance'] + $add_refer_bal;
            $ref_new_rc = (float) $ref_wallet['total_recharge'] + $add_refer_bal;
            $conn->query("UPDATE user_wallet SET balance = '$ref_new_bal', total_recharge = '$ref_new_rc' WHERE user_id = '$ref_user_id'");
        }
    }
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Credited ₦' . number_format($amount, 2),
    'new_balance' => $new_balance,
]);
