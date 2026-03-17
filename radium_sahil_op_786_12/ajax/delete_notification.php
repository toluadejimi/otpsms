<?php
session_start();
include("../auth.php");

if(!isset($_SESSION['admin'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if(!$id){
    echo json_encode(['status'=>'error','message'=>'Invalid ID']);
    exit;
}

mysqli_query($conn, "DELETE FROM user_notifications WHERE notification_id = '$id'");
mysqli_query($conn, "DELETE FROM notifications WHERE id = '$id'");
echo json_encode(['status'=>'ok']);
