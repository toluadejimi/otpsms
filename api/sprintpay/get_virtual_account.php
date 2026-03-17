<?php
/**
 * SprintPay: Get existing virtual account for the logged-in user.
 * POST: token
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if (!isset($_POST['token'])) {
    echo json_encode(['status' => '2', 'msg' => 'Invalid request']);
    exit;
}

$token = $_POST['token'];
$wallet = new radiumsahil();
$user_id = $wallet->check_token($token);
$wallet->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => '3', 'msg' => 'Session expired']);
    exit;
}

$gateway_query = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'SprintPay' OR name LIKE '%SprintPay%') AND status = 1 LIMIT 1");
$gateway = $gateway_query ? $gateway_query->fetch_assoc() : null;
if (!$gateway) {
    echo json_encode(['status' => '0']);
    exit;
}

$gateway_id = (int) $gateway['id'];
$stmt = $conn->prepare("SELECT account_number, account_name, bank_name FROM bank_accounts WHERE user_id = ? AND gateway_id = ?");
$stmt->bind_param("ii", $user_id, $gateway_id);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();

if ($account) {
    echo json_encode(['status' => '1', 'data' => $account]);
} else {
    echo json_encode(['status' => '0']);
}
