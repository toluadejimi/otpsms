<?php
/**
 * PaymentPoint webhook: credit wallet when provider notifies pay-in.
 * Supports common payload shapes with `amount` + (email or account number).
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

// Provider payload (example provided by user) includes:
// - transaction_status: "success"
// - settlement_amount / amount_paid
// - receiver.account_number: the virtual account number
// - customer.email: payer email (optional for lookup)
$status = strtoupper(trim((string)(
    $payload['transaction_status'] ?? $payload['status'] ?? $payload['paymentStatus'] ?? $payload['payment_status'] ??
    $body['transaction_status'] ?? $body['status'] ?? ''
)));
$amount = (float)($payload['settlement_amount'] ?? $payload['amount_settled'] ?? $payload['amount_paid'] ?? $payload['amount'] ?? $body['amount'] ?? 0);
$email = trim((string)(
    $payload['customer']['email'] ?? $payload['email'] ?? $payload['customer_email'] ??
    $body['customer']['email'] ?? $body['email'] ?? ''
));

// Prefer receiver.account_number for virtual account crediting
$accountNo = trim((string)(
    $payload['receiver']['account_number'] ??
    $payload['v_account_no'] ?? $payload['account_no'] ?? $payload['account_number'] ?? $payload['destinationAccount'] ??
    $body['receiver']['account_number'] ?? $body['account_no'] ?? $body['account_number'] ?? ''
));

$reference = trim((string)(
    $payload['transaction_id'] ?? $payload['reference'] ?? $payload['ref'] ?? $payload['order_id'] ?? $payload['transactionRef'] ??
    $body['transaction_id'] ?? $body['reference'] ?? ''
));
if ($reference === '') {
    $reference = 'pp-' . uniqid('', true);
}

// Only credit on success if status exists; if status missing, accept as success when payload is valid
// Provider example: transaction_status = "success" (uppercased => "SUCCESS")
$statusOk = in_array($status, ['SUCCESS', '1', 'COMPLETED', 'PAID', 'COMPLETE', 'PAYMENT_SUCCESSFUL', 'SUCCESSFUL'], true);
$noStatusButValid = ($status === '' && $amount > 0 && $accountNo !== '');
if (!$statusOk && !$noStatusButValid) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Status not success, skipped', 'status' => $status]);
    exit;
}

if ($amount <= 0 || !is_finite($amount)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

if (!$email && !$accountNo) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing email or account number']);
    exit;
}

// Idempotency
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
        $user_id = (int)$row['id'];
    }
}

if (!$user_id && $accountNo) {
    $stmt = $conn->prepare("SELECT user_id FROM bank_accounts WHERE account_number = ? LIMIT 1");
    $stmt->bind_param("s", $accountNo);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        $user_id = (int)$row['user_id'];
    }
}

if (!$user_id) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit;
}

// Get gateway id (ensure FK-safe)
$gq = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'PaymentPoint' OR name = 'PayPoint' OR name LIKE '%PaymentPoint%' OR name LIKE '%PayPoint%') AND status = 1 LIMIT 1");
$gateway_id = 0;
if ($gq && $gw = $gq->fetch_assoc()) {
    $gateway_id = (int)$gw['id'];
}
if ($gateway_id <= 0) {
    // fallback any gateway to satisfy FK constraint if schema enforces it
    $any = $conn->query("SELECT id FROM payment_gateways ORDER BY id ASC LIMIT 1");
    if ($any && $row = $any->fetch_assoc()) {
        $gateway_id = (int)$row['id'];
    }
}

$current_time = date('Y-m-d H:i:s');
$amount_safe = mysqli_real_escape_string($conn, $amount);
$user_id_safe = (int)$user_id;

$conn->query("INSERT INTO user_transaction (user_id, amount, date, type, gateway_id, txn_id, status)
              VALUES ('$user_id_safe', '$amount_safe', '$current_time', 'PaymentPoint', '$gateway_id', '$ref_safe', '1')");

$wallet_q = $conn->query("SELECT balance, total_recharge FROM user_wallet WHERE user_id = '$user_id_safe' LIMIT 1");
$wallet_data = $wallet_q ? $wallet_q->fetch_assoc() : null;
$new_balance = $wallet_data ? (float)$wallet_data['balance'] + $amount : $amount;
$new_total_rc = $wallet_data ? (float)$wallet_data['total_recharge'] + $amount : $amount;

$conn->query("UPDATE user_wallet SET balance = '$new_balance', total_recharge = '$new_total_rc' WHERE user_id = '$user_id_safe'");

http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Credited ₦' . number_format($amount, 2)]);

