<?php
require_once __DIR__ . '/../../include/config.php';
header('Content-Type: application/json');

$token = mysqli_real_escape_string($conn, $_GET['token'] ?? '');

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo json_encode(["status"=>500,"message"=>"Token Expired"]);
    exit;
}

$q = mysqli_query($conn, "
    SELECT 
        un.id AS user_notification_id,
        n.title,
        n.preview,
        n.body,
        n.created_at
    FROM user_notifications un
    INNER JOIN notifications n ON un.notification_id = n.id
    WHERE un.user_id = '$user_id'
      AND un.is_read = 0
      AND un.dismissed = 0
    ORDER BY n.created_at DESC
    LIMIT 1
");

if(mysqli_num_rows($q) > 0){
    $r = mysqli_fetch_assoc($q);
    echo json_encode([
        "status"=>200,
        "data"=>[
            "id"=>$r['user_notification_id'],
            "title"=>$r['title'],
            "preview"=>$r['preview'],
            "body"=>$r['body'],
            "created_at"=>date("d/m/Y h:i A", strtotime($r['created_at']))
        ]
    ]);
} else {
    echo json_encode(["status"=>204]);
}
