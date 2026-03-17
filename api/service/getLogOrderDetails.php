<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['token']) || $_GET['token'] == "") {
    echo '{"status":"500","message":"Token Blank"}';
} elseif (!isset($_GET['order_id']) || $_GET['order_id'] == "") {
    echo '{"status":"500","message":"Order ID is blank"}';
} else {
    $order_id = $_GET['order_id'];
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $find_token = new radiumsahil();
    $check_token = $find_token->check_token($token);
    $find_token->closeConnection();
    if ($check_token === false) {
        echo '{"status":"500","message":"Token Expired Please Logout And Login Again"}';
    } else {
        $user_id = $check_token;
        $sql = "SELECT 
                oi.id AS order_item_id, 
                p.name AS product_name,
                pd.details AS product_details
            FROM 
                order_items oi
            INNER JOIN
                orders o ON oi.order_id = o.id
            INNER JOIN 
                products p ON oi.product_id = p.id
            INNER JOIN 
                product_details pd ON oi.product_detail_id = pd.id
            WHERE 
                oi.order_id = '$order_id'
            AND o.user_id = '$user_id'";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Fetch data
        $order_data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $order_data[] = array(
                    "product_name" => $row['product_name'],
                    "product_details" => $row['product_details'],
                );
            }
        }

        // Return the order data
        echo json_encode([
            'status' => '200',
            'data' => $order_data
        ]);
    }
}
