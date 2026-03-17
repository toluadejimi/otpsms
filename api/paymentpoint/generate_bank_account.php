<?php
/**
 * PaymentPoint: Generate a bank account (if supported) or return existing.
 *
 * NOTE: In this codebase we only store/reuse `bank_accounts` rows.
 * If PaymentPoint's provider API is needed to generate accounts, configure it here.
 *
 * POST: token
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if (empty($_POST['token'])) {
    echo json_encode(['status' => '2', 'msg' => 'Missing required parameters']);
    exit;
}

$token = mysqli_real_escape_string($conn, $_POST['token']);
$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => '3', 'msg' => 'Token expired. Please log in again.']);
    exit;
}

$gateway_query = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'PaymentPoint' OR name = 'PayPoint' OR name LIKE '%PaymentPoint%' OR name LIKE '%PayPoint%') AND status = 1 LIMIT 1");
$gateway = $gateway_query ? $gateway_query->fetch_assoc() : null;
if (!$gateway) {
    echo json_encode(['status' => '2', 'msg' => 'PaymentPoint is not enabled.']);
    exit;
}

$gateway_id = (int) $gateway['id'];

// Return existing account if present
$stmt = $conn->prepare("SELECT account_number, account_name, bank_name FROM bank_accounts WHERE user_id = ? AND gateway_id = ? LIMIT 1");
$stmt->bind_param("ii", $user_id, $gateway_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
if ($existing) {
    echo json_encode(['status' => '1', 'msg' => 'Bank account retrieved.', 'data' => $existing]);
    exit;
}

// No provider integration found in this repo (PaymentPoint API files were removed previously).
// Admin can re-enable PaymentPoint and manually provision accounts into `bank_accounts`,
// or you can provide PaymentPoint API docs and we will integrate generation here.
echo json_encode([
    'status' => '2',
    'msg' => 'No PaymentPoint account found for your profile. Please use another gateway or contact support.',
]);

