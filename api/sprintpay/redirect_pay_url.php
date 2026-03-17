<?php
/**
 * SprintPay: Get redirect URL for "pay any amount" flow.
 * GET or POST: token, amount (NGN)
 * Returns JSON with pay_url to redirect user to SprintPay.
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

$token = $_POST['token'] ?? $_GET['token'] ?? '';
$amount = (float) ($_POST['amount'] ?? $_GET['amount'] ?? 0);

if (!$token || $amount < 100) {
    echo json_encode(['status' => '2', 'msg' => 'Minimum amount is ₦100']);
    exit;
}

$wallet = new radiumsahil();
$user_id = $wallet->check_token($token);
$wallet->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => '3', 'msg' => 'Session expired']);
    exit;
}

$gq = $conn->query("SELECT api_key FROM payment_gateways WHERE (name = 'SprintPay' OR name LIKE '%SprintPay%') AND status = 1 LIMIT 1");
$gateway = $gq ? $gq->fetch_assoc() : null;
if (!$gateway) {
    echo json_encode(['status' => '2', 'msg' => 'SprintPay is not configured']);
    exit;
}

$uq = $conn->query("SELECT email FROM user_data WHERE id = '" . (int) $user_id . "' LIMIT 1");
$user = $uq ? $uq->fetch_assoc() : null;
if (!$user || empty($user['email'])) {
    echo json_encode(['status' => '2', 'msg' => 'Profile email required']);
    exit;
}

$ref = 'sp-' . $user_id . '-' . time();
$base = 'https://web.sprintpay.online/pay';
$pay_url = $base . '?amount=' . (int) round($amount) . '&key=' . rawurlencode($gateway['api_key']) . '&ref=' . rawurlencode($ref) . '&email=' . rawurlencode($user['email']);

echo json_encode(['status' => '1', 'pay_url' => $pay_url, 'ref' => $ref]);
