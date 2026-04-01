<?php
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 500, 'message' => 'Method not allowed']);
    exit;
}

$token = isset($_POST['token']) ? trim((string)$_POST['token']) : '';
if ($token === '') {
    http_response_code(400);
    echo json_encode(['status' => 500, 'message' => 'Missing token']);
    exit;
}

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['status' => 500, 'message' => 'Session expired']);
    exit;
}

$user_id = (int)$user_id;

// Ensure table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `virtual_cards` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` int(11) UNSIGNED NOT NULL,
    `card_token` varchar(191) NOT NULL,
    `last4` varchar(8) NOT NULL,
    `currency` varchar(8) NOT NULL DEFAULT 'NGN',
    `status` varchar(16) NOT NULL DEFAULT 'active',
    `nickname` varchar(64) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// If card already exists, just return it (dummy behaviour for now)
$existing = mysqli_query(
    $conn,
    "SELECT * FROM virtual_cards WHERE user_id = '{$user_id}' ORDER BY id DESC LIMIT 1"
);
if ($existing && $existing->num_rows > 0) {
    $card = $existing->fetch_assoc();
    echo json_encode([
        'status' => 200,
        'message' => 'Card already exists',
        'card' => $card
    ]);
    exit;
}

// Create a dummy virtual card – later you will replace this with real provider API
$last4 = str_pad((string)rand(0, 9999), 4, '0', STR_PAD_LEFT);
$card_token = 'dummy_' . bin2hex(random_bytes(8));
$currency = 'NGN';
$status = 'active';
$nickname = 'Main Virtual Card';
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare(
    "INSERT INTO virtual_cards (user_id, card_token, last4, currency, status, nickname, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Could not prepare statement']);
    exit;
}

$stmt->bind_param(
    'issssss',
    $user_id,
    $card_token,
    $last4,
    $currency,
    $status,
    $nickname,
    $now
);

if (!$stmt->execute()) {
    $stmt->close();
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Unable to create virtual card']);
    exit;
}

$new_id = (int)$stmt->insert_id;
$stmt->close();

$card_q = mysqli_query(
    $conn,
    "SELECT * FROM virtual_cards WHERE id = '{$new_id}' LIMIT 1"
);
$card = $card_q ? $card_q->fetch_assoc() : null;

echo json_encode([
    'status' => 200,
    'message' => 'Virtual card created (demo)',
    'card' => $card
]);

