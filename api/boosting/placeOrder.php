<?php
require_once __DIR__ . '/../../include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $service_id = (int)$_POST['service_id'];
    $quantity = (int)$_POST['quantity'];
    $total_quantity = (int)$_POST['total_quantity'];
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $runs = isset($_POST['runs']) ? (int)$_POST['runs'] : 0;
    $interval = isset($_POST['interval']) ? (int)$_POST['interval'] : 0;
    $is_drip_feed = isset($_POST['is_drip_feed']) ? 1 : 0;

    // Validate token
    $find_token = new radiumsahil();
    $user_id = $find_token->check_token($token);
    $find_token->closeConnection();

    if (!$user_id) {
        echo json_encode(['status' => '500', 'message' => 'Token Expired']);
        exit;
    }

    // Get service
    $sql_service = mysqli_query($conn, "SELECT * FROM boosting_services WHERE id = '$service_id'");
    if (mysqli_num_rows($sql_service) == 0) {
        echo json_encode(['status' => '500', 'message' => 'Invalid Service']);
        exit;
    }

    $service = mysqli_fetch_assoc($sql_service);

    // Get API provider
    $provider_id = $service['api_provider_id'];
    $sql_api = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE id = '$provider_id'");
    if (mysqli_num_rows($sql_api) == 0) {
        echo json_encode(['status' => '500', 'message' => 'API Provider not found']);
        exit;
    }

    $api = mysqli_fetch_assoc($sql_api);
    $api_key = $api['api_key'];
    $api_url = rtrim($api['api_url'], '/');
    $api_service_id = $service['api_service_id'];
    $api_percentage_increase = $api['api_percentage_increase'];

    // Get user wallet
    $wallet_q = mysqli_query($conn, "SELECT * FROM user_wallet WHERE user_id = '$user_id'");
    $wallet = mysqli_fetch_assoc($wallet_q);
    $user_balance = $wallet['balance'];

    // Price calculation
    $price_per_k = $service['price'];
    $total_price = ($quantity / 1000) * $price_per_k;

    if ($is_drip_feed && $service['dripfeed'] == 1) {
        $total_quantity = $quantity * $runs;
        $total_price = ($total_quantity / 1000) * $price_per_k;
    }

    if ($user_balance < $total_price) {
        echo json_encode(['status' => '500', 'message' => 'Insufficient balance']);
        exit;
    }

    // External API Call
    $post_data = [
        'key' => $api_key,
        'action' => 'add',
        'service' => $api_service_id,
        'link' => $link,
        'quantity' => $quantity,
    ];

    if ($is_drip_feed && $service['dripfeed'] == 1) {
        $post_data['runs'] = $runs;
        $post_data['interval'] = $interval;
    }

    // Make cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$api_url/api/v2");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $api_response_raw = curl_exec($ch);
    curl_close($ch);

    $api_response = json_decode($api_response_raw, true);

    if (!isset($api_response['order'])) {
        echo json_encode([
            'status' => '500',
            'message' => 'Error: ' . ($api_response['error'] ?? 'Unknown error'),
        ]);
        exit;
    }

    $api_order_id = $api_response['order'];

    $status_description = "order: {$api_response['order']}";

   // Get category name from boosting_categories
    $cate_id = (int)$service['cate_id'];
    $category_q = mysqli_query($conn, "SELECT name FROM boosting_categories WHERE id = '$cate_id'");
    $category = mysqli_fetch_assoc($category_q);
    $category_name = mysqli_real_escape_string($conn, $category['name'] ?? 'Unknown');

    // Get service name
    $service_name = mysqli_real_escape_string($conn, $service['name'] ?? 'Unknown');

    // Insert into boosting_orders with category_name and service_name
    $order_date = date("Y-m-d H:i:s");
    $insert_order = mysqli_query($conn, "INSERT INTO boosting_orders 
        (user_id, api_provider_id, category_name, service_name, api_order_id, link, quantity, price, status, status_description, drip_feed, runs, `interval`, added_on)
        VALUES (
            '$user_id',
            '$provider_id',
            '$category_name',
            '$service_name',
            '$api_order_id',
            '$link',
            '$quantity',
            '$total_price',
            'Pending',
            '$status_description',
            '$is_drip_feed',
            '$runs',
            '$interval',
            '$order_date'
        )");


    if (!$insert_order) {
        echo json_encode(['status' => '500', 'message' => 'Failed to insert order.']);
        exit;
    }

    // Deduct from wallet
    $new_balance = $user_balance - $total_price;
    mysqli_query($conn, "UPDATE user_wallet SET balance = '$new_balance' WHERE user_id = '$user_id'");

    echo json_encode([
        'status' => '200',
        'message' => 'Order Placed Successfully',
        // 'order_id' => mysqli_insert_id($conn),
        // 'api_order_id' => $api_order_id
    ]);
} else {
    echo json_encode(['status' => '500', 'message' => 'Invalid Request']);
}
?>
