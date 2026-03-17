<?php
require_once __DIR__ . '/../../include/config.php';
header('Content-Type: application/json');

$token = mysqli_real_escape_string($conn, $_GET['token'] ?? '');

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo json_encode(['status' => 401, 'message' => 'Unauthorized']);
    exit;
}

$q = @mysqli_query($conn, "
    SELECT title, message
    FROM site_broadcast
    WHERE id = 1 AND enabled = 1
    LIMIT 1
");

if (!$q || mysqli_num_rows($q) === 0) {
    echo json_encode(['status' => 204]);
    exit;
}

$row = mysqli_fetch_assoc($q);
$title = trim($row['title'] ?? '');
$message = trim($row['message'] ?? '');

if ($title === '' && $message === '') {
    echo json_encode(['status' => 204]);
    exit;
}

echo json_encode([
    'status' => 200,
    'data' => [
        'title' => $title ?: 'Announcement',
        'message' => $message,
    ],
]);
