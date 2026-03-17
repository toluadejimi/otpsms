<?php
include("../auth.php");

if (!isset($_SESSION['admin'])) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
$preview = mysqli_real_escape_string($conn, $_POST['preview'] ?? '');
$body = mysqli_real_escape_string($conn, $_POST['body'] ?? '');

if (!$id || !$title || !$preview || !$body) {
    echo json_encode(['status'=>'error','message'=>'All fields are required']);
    exit;
}

// Update notification
$res = mysqli_query($conn, "UPDATE notifications SET title='$title', preview='$preview', body='$body' WHERE id='$id'");

if ($res) {
    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','message'=>'DB Error']);
}