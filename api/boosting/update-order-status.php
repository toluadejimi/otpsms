<?php
require_once __DIR__ . '/../../include/config.php';

echo "Starting update order status cron job...\n";

$sql = "SELECT bo.id, bo.api_order_id, bo.user_id, bo.price, bo.quantity, bo.remains,
               bo.api_provider_id, bap.api_url, bap.api_key
        FROM boosting_orders bo
        INNER JOIN boosting_api_providers bap ON bo.api_provider_id = bap.id
        WHERE bo.status NOT IN ('Completed', 'Refunded', 'Canceled')
        AND bo.api_provider_id IS NOT NULL
        AND bo.api_provider_id != 0
        AND bo.api_order_id IS NOT NULL";

$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "DB Error: " . mysqli_error($conn) . "\n";
    exit;
}

while ($order = mysqli_fetch_assoc($result)) {
    $order_id = $order['id'];
    $api_order_id = $order['api_order_id'];
    $api_url = rtrim($order['api_url'], '/');
    $api_key = $order['api_key'];
    $user_id = $order['user_id'];
    $price = (float)$order['price'];
    $quantity = (int)$order['quantity'];
    $remains = (int)$order['remains'];

    echo "Checking status for order ID $order_id, API order $api_order_id...\n";

    $post_data = [
        'key' => $api_key,
        'action' => 'status',
        'order' => $api_order_id
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$api_url/api/v2");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "Curl Error: $err\n";
        $error_msg = mysqli_real_escape_string($conn, $err);
        mysqli_query($conn, "UPDATE boosting_orders SET status_description = 'Curl Error: $error_msg' WHERE id = '$order_id'");
        continue;
    }

    $api_response = json_decode($response, true);
    if (!$api_response) {
        echo "Invalid JSON response for order $order_id\n";
        $error_msg = mysqli_real_escape_string($conn, "Invalid JSON response");
        mysqli_query($conn, "UPDATE boosting_orders SET status_description = '$error_msg' WHERE id = '$order_id'");
        continue;
    }

    if (isset($api_response['error'])) {
        $error_msg = mysqli_real_escape_string($conn, $api_response['error']);
        echo "API Error for order $order_id: $error_msg\n";
        mysqli_query($conn, "UPDATE boosting_orders SET status_description = 'Error: $error_msg' WHERE id = '$order_id'");
        continue;
    }

    $status = $api_response['status'] ?? null;
    $remains = isset($api_response['remains']) ? (int)$api_response['remains'] : null;
    $start_counter = isset($api_response['start_count']) ? (int)$api_response['start_count'] : null;

    if (!$status) {
        echo "No status in API response for order $order_id\n";
        continue;
    }

    // Determine new status
    $new_status = null;
    if (strtolower($status) === 'completed' || $remains === 0) {
        $new_status = 'Completed';
    } elseif (strtolower($status) === 'partial') {
        $new_status = 'Partial';
    } elseif (strtolower($status) === 'processing' || strtolower($status) === 'in progress') {
        $new_status = 'Processing';
    } elseif (strtolower($status) === 'refunded') {
        $new_status = 'Refunded';
    } elseif (strtolower($status) === 'canceled') {
        $new_status = 'Canceled';
    } else {
        $new_status = ucfirst(strtolower($status)); // fallback
    }

    // Update order status
    $update_sql = "UPDATE boosting_orders 
                   SET status = '" . mysqli_real_escape_string($conn, $new_status) . "', 
                       remains = " . intval($remains) . ", 
                       start_counter = " . intval($start_counter) . " 
                   WHERE id = '$order_id'";
    if (!mysqli_query($conn, $update_sql)) {
        echo "DB Update Error for order $order_id: " . mysqli_error($conn) . "\n";
        continue;
    }

    echo "Order $order_id updated to status $new_status with $remains remains.\n";

    $refunded_amount = 0;
    $refund_reason = null;

    if (strtolower($new_status) === 'refunded' && $remains > 0 && $quantity > 0) {
        $per_unit_price = $price / $quantity;
        $refunded_amount = round($per_unit_price * $remains, 4);
        $refund_reason = "Partial refund for order #$order_id";
    } elseif (strtolower($new_status) === 'canceled') {
        $refunded_amount = $price;
        $refund_reason = "Full refund for canceled order #$order_id";
    }

    if ($refunded_amount > 0) {
        echo "Refunding $refunded_amount to user $user_id for order $order_id...\n";

        // Update balance
        $update_wallet_sql = "UPDATE user_wallet SET balance = balance + $refunded_amount WHERE user_id = '$user_id'";
        if (!mysqli_query($conn, $update_wallet_sql)) {
            echo "Failed to refund user $user_id: " . mysqli_error($conn) . "\n";
        } else {
            echo "Refund successful for user $user_id.\n";
        }
    }
}

echo "Update order status cron job completed.\n";
