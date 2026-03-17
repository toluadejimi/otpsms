<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['token']) || $_GET['token'] == "") {
    echo json_encode(["status" => "500", "message" => "Token Blank"]);
    exit;
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$find_token = new radiumsahil();
$check_token = $find_token->check_token($token);
$find_token->closeConnection();

if ($check_token === false) {
    echo json_encode(["status" => "500", "message" => "Token Expired Please Logout And Login Again"]);
    exit;
}

$user_id = $check_token;
$final_data = [];

/** 🎯 1. Fetch regular product orders **/
$sql = "
    SELECT 
        p.name AS product_name, 
        o.created_at, 
        o.total_amount, 
        o.id AS order_id,
        COUNT(oi.id) as num_items
    FROM orders o
    INNER JOIN order_items oi ON o.id = oi.order_id
    INNER JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = '$user_id'
    GROUP BY o.id, p.id
";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $final_data[] = [
            "product_name" => $row['product_name'],
            "created_at" => $row['created_at'],
            "total_amount" => number_format($row['total_amount'], 0),
            "num_items" => $row['num_items'],
            "order_id" => $row['order_id'],
            "source" => "product"
        ];
    }
}

/** 🎯 2. Fetch log post product orders **/
// $log_sql = "
//     SELECT 
//         lp.title AS post_title, 
//         lpo.created_at, 
//         lpo.total_amount, 
//         lpo.id AS order_id,
//         COUNT(lpoi.id) as num_items
//     FROM log_post_orders lpo
//     INNER JOIN log_post_order_items lpoi ON lpo.id = lpoi.log_post_order_id
//     INNER JOIN log_posts lp ON lpoi.log_post_id = lp.id
//     WHERE lpo.user_id = '$user_id'
//     GROUP BY lpo.id, lp.id;
// ";

// $log_result = mysqli_query($conn, $log_sql);

// if ($log_result && mysqli_num_rows($log_result) > 0) {
//     while ($row = mysqli_fetch_assoc($log_result)) {
//         $final_data[] = [
//             "product_name" => $row['post_title'],
//             "created_at" => $row['created_at'],
//             "total_amount" => number_format($row['total_amount'], 0),
//             "num_items" => $row['num_items'],
//             "order_id" => $row['order_id'],
//             "source" => "post"
//         ];
//     }
// }

/** 🔃 3. Sort merged data by created_at descending **/
usort($final_data, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

/** 📤 4. Return data **/
echo json_encode([
    "status" => "200",
    "data" => $final_data
]);
