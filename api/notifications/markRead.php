<?php
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

$token = mysqli_real_escape_string($conn, $_POST['token'] ?? '');
$id = (int) ($_POST['id'] ?? 0);


$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo json_encode(["status" => 500, "message" => "Token Expired"]);
    exit;
}

if (!$id) {
    echo json_encode(["status" => 400, "message" => "Invalid notification ID"]);
    exit;
}

mysqli_query($conn, "
    UPDATE user_notifications 
    SET is_read = 1 
    WHERE id = '$id' 
      AND user_id = '$user_id'
");

echo json_encode(["status" => "ok"]);
