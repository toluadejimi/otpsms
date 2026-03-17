<?php
/**
 * PaymentPoint: Get existing virtual/bank account for the logged-in user.
 * POST: token
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if (!isset($_POST['token'])) {
    echo json_encode(['status' => '2', 'msg' => 'Invalid request']);
    exit;
}

$token = $_POST['token'];
$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => '3', 'msg' => 'Session expired']);
    exit;
}

$gateway_query = $conn->query("SELECT id FROM payment_gateways WHERE (name = 'PaymentPoint' OR name = 'PayPoint' OR name LIKE '%PaymentPoint%' OR name LIKE '%PayPoint%') AND status = 1 LIMIT 1");
$gateway = $gateway_query ? $gateway_query->fetch_assoc() : null;
if (!$gateway) {
    echo json_encode(['status' => '0']);
    exit;
}

$gateway_id = (int) $gateway['id'];
$stmt = $conn->prepare("SELECT account_number, account_name, bank_name FROM bank_accounts WHERE user_id = ? AND gateway_id = ? LIMIT 1");
$stmt->bind_param("ii", $user_id, $gateway_id);
$stmt->execute();
$result = $stmt->get_result();
$account = $result ? $result->fetch_assoc() : null;

if ($account) {
    echo json_encode(['status' => '1', 'data' => $account]);
} else {
    echo json_encode(['status' => '0']);
}

