<?php
include("../auth.php");

if(!isset($_SESSION['admin'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
$preview = mysqli_real_escape_string($conn, $_POST['preview'] ?? '');
$body = mysqli_real_escape_string($conn, $_POST['body'] ?? '');

if(!$title || !$preview || !$body){
    echo json_encode(['status'=>'error','message'=>'All fields are required']);
    exit;
}

$sql = "INSERT INTO notifications (title, preview, body) VALUES ('$title', '$preview', '$body')";
$res = mysqli_query($conn, $sql);

if($res){
    $notification_id = mysqli_insert_id($conn);

    mysqli_begin_transaction($conn);

    // Push to all users
    mysqli_query($conn, "
        INSERT INTO user_notifications (user_id, notification_id)
        SELECT id, $notification_id
        FROM user_data
        WHERE status = 1
    ");

    mysqli_commit($conn);

    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','message'=>'DB Error']);
}
