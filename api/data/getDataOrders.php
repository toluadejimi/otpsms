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
SELECT 
    o.*,
    p.plan_name,
    p.plan_type,
    p.validity,
    n.name AS network_name
FROM data_orders o
JOIN data_plans p ON p.id = o.data_plan_id
JOIN networks n ON n.id = p.network_id
WHERE o.user_id = '$user_id'
ORDER BY o.id DESC
";

$result = mysqli_query($conn, $sql);
$final = [];
$i = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $date = date("M d, Y - h:i A", strtotime($row['created_at']));

    $final[] = [
        'id' => $i,
        'network' => $row['network_name'],
        'plan' => $row['plan_name'] . ' ' . $row['plan_type'],
        'phone' => $row['phone'],
        'amount' => number_format($row['amount'], 0, '.', ','),
        'validity' => $row['validity'],
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
