<?php
require_once __DIR__ . '/../../include/config.php';

$id = mysqli_real_escape_string($conn, $_POST['id']);
$token = mysqli_real_escape_string($conn, $_POST['token']);

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) exit;

mysqli_query($conn,"
    UPDATE user_notifications
    SET dismissed = 1
    WHERE id='$id' AND user_id='$user_id'
");
