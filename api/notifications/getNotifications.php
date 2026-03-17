<?php
require_once __DIR__ . '/../../include/config.php';

header('Content-Type: application/json');

$token = mysqli_real_escape_string($conn, $_GET['token'] ?? '');

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo '{"status":"500","message":"Token Expired"}';
    exit;
}

$notifications = [];

$q = mysqli_query($conn, "
    SELECT 
        un.id AS user_notification_id,
        n.title,
        n.preview,
        n.body,
        un.is_read,
        n.created_at
    FROM user_notifications un
    INNER JOIN notifications n ON un.notification_id = n.id
    WHERE un.user_id = '$user_id'
    ORDER BY n.created_at DESC
");

while ($r = mysqli_fetch_assoc($q)) {
    $notifications[] = [
        'user_notification_id' => $r['user_notification_id'],
        'title' => $r['title'],
        'preview' => $r['preview'],
        'body' => $r['body'],
        'is_read' => (int)$r['is_read'],
        'created_at' => date("d/m/Y h:i A", strtotime($r['created_at']))
    ];
}

echo json_encode($notifications);
