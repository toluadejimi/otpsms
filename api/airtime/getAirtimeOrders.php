<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['token']) || $_GET['token'] == "") {
    echo '{"status":"500","message":"Token Blank"}';
    exit;
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

$auth = new radiumsahil();
$user_id = $auth->check_token($token);
$auth->closeConnection();

if ($user_id === false) {
    echo '{"status":"500","message":"Token Expired"}';
    exit;
}

$sql = "
SELECT a.*, n.name AS network_name
FROM airtime_orders a
JOIN networks n ON n.id = a.network_id
WHERE a.user_id = '$user_id'
ORDER BY a.id DESC
";

$result = mysqli_query($conn, $sql);
$final = [];
$i = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $date = date("M d, Y - h:i A", strtotime($row['created_at']));

    $final[] = [
        'id' => $i,
        'network' => $row['network_name'],
        'phone' => $row['phone'],
        'amount' => number_format($row['amount'], 0, '.', ','),
        'reference' => $row['api_reference'],
        'date' => $date,
        'status' => $row['status']
    ];
    $i++;
}

echo json_encode([
    'status' => '200',
    'data' => $final
]);

mysqli_close($conn);
