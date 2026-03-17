<?php
include("../auth.php");

if (!isset($_SESSION['admin'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
$message = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
$enabled = isset($_POST['enabled']) && (int)$_POST['enabled'] === 1 ? 1 : 0;

$ok = mysqli_query($conn, "
    UPDATE site_broadcast
    SET title = '$title', message = '$message', enabled = $enabled
    WHERE id = 1
");

header('Content-Type: application/json');
if ($ok) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
