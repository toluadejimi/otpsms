<?php
/**
 * SprintPay: Generate or return existing virtual account for the logged-in user.
 * POST: token (session token)
 */
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if (!isset($_POST['token']) || empty($_POST['token'])) {
    echo json_encode(['status' => '2', 'msg' => 'Missing required parameters']);
    exit;
}

$token = mysqli_real_escape_string($conn, $_POST['token']);
$input_name = trim((string)($_POST['name'] ?? ''));
$input_phone = trim((string)($_POST['phone_number'] ?? $_POST['phone'] ?? ''));
$wallet = new radiumsahil();
$user_id = $wallet->check_token($token);
$wallet->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => '3', 'msg' => 'Token expired. Please log in again.']);
    exit;
}

// Get SprintPay gateway (by name)
$gateway_query = $conn->query("SELECT id, api_key, secret_key FROM payment_gateways WHERE (name = 'SprintPay' OR name LIKE '%SprintPay%') AND status = 1 LIMIT 1");
$gateway = $gateway_query ? $gateway_query->fetch_assoc() : null;

if (!$gateway) {
    echo json_encode(['status' => '2', 'msg' => 'SprintPay is not configured. Please contact support.']);
    exit;
}

$gateway_id = (int) $gateway['id'];
$api_key = $gateway['api_key'];
$api_secret = $gateway['secret_key'];

// Optionally update profile fields required for virtual account (name, phone_number)
if ($input_name !== '') {
    $name_safe = mysqli_real_escape_string($conn, $input_name);
    $conn->query("UPDATE user_data SET name = '$name_safe' WHERE id = '" . (int) $user_id . "'");
}
if ($input_phone !== '') {
    $phone_safe = mysqli_real_escape_string($conn, $input_phone);
    // Ensure phone_number is unique (matches update_phone_number.php behavior)
    $check_query = $conn->query("SELECT id FROM user_data WHERE phone_number = '$phone_safe' AND id != '" . (int) $user_id . "' LIMIT 1");
    if ($check_query && $check_query->num_rows > 0) {
        echo json_encode(['status' => '2', 'msg' => 'Phone Number Already In Use By Another User']);
        exit;
    }
    $conn->query("UPDATE user_data SET phone_number = '$phone_safe' WHERE id = '" . (int) $user_id . "'");
}

// Return existing virtual account if already created
$stmt = $conn->prepare("SELECT account_number, account_name, bank_name FROM bank_accounts WHERE user_id = ? AND gateway_id = ?");
$stmt->bind_param("ii", $user_id, $gateway_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();

if ($existing) {
    echo json_encode([
        'status' => '1',
        'msg' => 'Virtual account retrieved.',
        'data' => [
            'account_number' => $existing['account_number'],
            'account_name' => $existing['account_name'],
            'bank_name' => $existing['bank_name'],
        ],
    ]);
    exit;
}

// Fetch user details
$user_query = $conn->query("SELECT email, name, phone_number FROM user_data WHERE id = '" . (int) $user_id . "'");
$user = $user_query->fetch_assoc();
if (!$user || empty($user['email'])) {
    echo json_encode(['status' => '2', 'msg' => 'Please complete your profile (email and name) to generate a virtual account.']);
    exit;
}

$account_name = !empty(trim((string)$user['name'])) ? trim((string)$user['name']) : 'User ' . $user_id;
if ($account_name === 'User ' . $user_id) {
    echo json_encode(['status' => '2', 'msg' => 'Please provide your name to generate a virtual account.']);
    exit;
}
if (empty(trim((string)($user['phone_number'] ?? '')))) {
    echo json_encode(['status' => '2', 'msg' => 'Please provide your phone number to generate a virtual account.']);
    exit;
}

// Call SprintPay API: POST https://web.sprintpay.online/api/generate-virtual-account
$url = 'https://web.sprintpay.online/api/generate-virtual-account';
$body = json_encode([
    'email' => $user['email'],
    'account_name' => $account_name,
    'key' => $api_key,
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'api-key: ' . $api_key,
    'Authorization: Bearer ' . $api_secret,
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['status' => '2', 'msg' => 'Unable to connect to payment provider.']);
    exit;
}

$data = json_decode($response, true);

// Handle response: status true and data with account_number, account_name, bank_name
$account_number = null;
$account_name_va = $account_name;
$bank_name = 'SprintPay';

if (!empty($data['status']) && ($data['status'] === true || $data['status'] === 'success') && !empty($data['data'])) {
    $d = $data['data'];
    $account_number = $d['account_number'] ?? $data['account_number'] ?? null;
    $account_name_va = $d['account_name'] ?? $data['account_name'] ?? $account_name_va;
    $bank_name = $d['bank_name'] ?? $data['bank_name'] ?? $bank_name;
}
if (!$account_number && !empty($data['account_number'])) {
    $account_number = $data['account_number'];
    $account_name_va = $data['account_name'] ?? $account_name_va;
    $bank_name = $data['bank_name'] ?? $bank_name;
}

if (!$account_number) {
    $errMsg = 'Could not create virtual account.';
    if (!empty($data['message'])) {
        $errMsg = $data['message'];
    } elseif (!empty($data['error'])) {
        $errMsg = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
    }
    echo json_encode(['status' => '2', 'msg' => $errMsg]);
    exit;
}

$account_number_safe = mysqli_real_escape_string($conn, $account_number);
$account_name_safe = mysqli_real_escape_string($conn, $account_name_va);
$bank_name_safe = mysqli_real_escape_string($conn, $bank_name);

$insert = "INSERT INTO bank_accounts (user_id, gateway_id, bank_code, account_number, account_name, bank_name, reserved_account_id) 
           VALUES ('" . (int) $user_id . "', '" . $gateway_id . "', 'sprintpay', '$account_number_safe', '$account_name_safe', '$bank_name_safe', '')";

if ($conn->query($insert)) {
    echo json_encode([
        'status' => '1',
        'msg' => 'Virtual account generated successfully!',
        'data' => [
            'account_number' => $account_number,
            'account_name' => $account_name_va,
            'bank_name' => $bank_name,
        ],
    ]);
} else {
    echo json_encode(['status' => '2', 'msg' => 'Failed to save virtual account.']);
}
