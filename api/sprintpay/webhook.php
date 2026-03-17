<?php
/**
 * SprintPay webhook: handles both redirect (pay any amount) and virtual account pay-ins.
 *
 * 1) Redirect / enter amount: {"payload":{"amount":30000,"email":"...","order_id":"...","session_id":"...","account_no":"...","url":"..."}}
 * 2) Virtual account pay-in: {"amount":1500,"amount_settled":1292.5,"email":"...","order_id":"...","session_id":"...","v_account_no":"6610695533","event":"pay_in",...}
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
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

// Virtual account pay-in: only process event "pay_in"; use amount_settled (after fees) or amount
$event = trim((string) ($payload['event'] ?? $body['event'] ?? ''));
$vAccountNo = trim((string) ($payload['v_account_no'] ?? $body['v_account_no'] ?? ''));

if ($event === 'pay_in') {
    // Virtual account flow: credit amount_settled (what user gets) or amount
    $amount = (float) ($payload['amount_settled'] ?? $payload['amount'] ?? $body['amount_settled'] ?? $body['amount'] ?? 0);
} else {
    // Redirect flow
    $amount = (float) ($payload['amount'] ?? $payload['amount_paid'] ?? $payload['total'] ?? $body['amount'] ?? 0);
}

$email = trim((string) ($payload['email'] ?? $payload['customer_email'] ?? $body['email'] ?? ''));
$orderId = trim((string) ($payload['order_id'] ?? $payload['ref'] ?? $payload['reference'] ?? $body['order_id'] ?? ''));
$sessionId = trim((string) ($payload['session_id'] ?? $body['session_id'] ?? ''));
$accountNo = trim((string) ($payload['account_no'] ?? $body['account_no'] ?? ''));
// Virtual account pay-in uses v_account_no; redirect may use account_no (session/order id)
$accountNoForLookup = $vAccountNo ?: $accountNo;
$reference = $orderId ?: $sessionId ?: $accountNo ?: ('sp-' . ($body['transaction_id'] ?? uniqid('', true)));

if ($amount <= 0 || !is_finite($amount)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

// Must identify user: email, or v_account_no/account_no (virtual account number in bank_accounts)
if (!$email && !$accountNoForLookup) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing email or account_no / v_account_no']);
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

if (!$user_id && $accountNoForLookup) {
    $acc_safe = mysqli_real_escape_string($conn, $accountNoForLookup);
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
    echo json_encode(['error' => 'User not found']);
    exit;
}

$current_time = date('Y-m-d H:i:s');
$amount_safe = mysqli_real_escape_string($conn, $amount);
$user_id_safe = (int) $user_id;

// Get gateway id for SprintPay (for transaction record)
$gq = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'SprintPay' OR name LIKE '%SprintPay%') AND status = 1 LIMIT 1");
$gateway_id = 0;
if ($gq && $gw = $gq->fetch_assoc()) {
    $gateway_id = (int) $gw['id'];
}

$conn->query("INSERT INTO user_transaction (user_id, amount, date, type, gateway_id, txn_id, status) 
              VALUES ('$user_id_safe', '$amount_safe', '$current_time', 'SprintPay', '$gateway_id', '$ref_safe', '1')");

$wallet_q = $conn->query("SELECT balance, total_recharge FROM user_wallet WHERE user_id = '$user_id_safe' LIMIT 1");
$wallet_data = $wallet_q ? $wallet_q->fetch_assoc() : null;
$new_balance = $wallet_data ? (float) $wallet_data['balance'] + $amount : $amount;
$new_total_rc = $wallet_data ? (float) $wallet_data['total_recharge'] + $amount : $amount;

$conn->query("UPDATE user_wallet SET balance = '$new_balance', total_recharge = '$new_total_rc' WHERE user_id = '$user_id_safe'");

// Referral bonus (same logic as PaymentPoint webhook)
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
        $ref_wallet_q = $conn->query("SELECT balance, total_recharge FROM user_wallet WHERE user_id = '$ref_user_id' LIMIT 1");
        $ref_wallet = $ref_wallet_q ? $ref_wallet_q->fetch_assoc() : null;
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
