<?php
require_once __DIR__ . '/../../include/config.php';

echo "Starting auto refill cron job...\n";

// Fetch refillable, completed orders that haven't been refilled yet
$sql = "SELECT bo.id, bo.api_order_id, bo.user_id, bo.service_id, bs.api_provider_id, bap.api_url, bap.api_key
        FROM boosting_orders bo
        INNER JOIN boosting_services bs ON bo.service_id = bs.id
        INNER JOIN boosting_api_providers bap ON bs.api_provider_id = bap.id
        WHERE bo.status = 'Completed'
        AND bs.refill = 1
        AND bs.refill_type = 1
        AND (bo.refill_status IS NULL OR bo.refill_status = '')
        AND (bo.refilled_at IS NULL OR bo.refilled_at = '0000-00-00 00:00:00')
        LIMIT 50"; // Limit so it doesn’t overload API at once

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

    echo "Processing refill for order ID $order_id, API order $api_order_id...\n";

    $post_data = [
        'key' => $api_key,
        'action' => 'refill',
        'order' => $api_order_id
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "Curl Error: $err\n";
        continue; // skip to next order
    }

    $api_response = json_decode($response, true);

    if (!$api_response) {
        echo "Invalid JSON response for order $order_id\n";
        continue;
    }

    if (isset($api_response['refill']) || (isset($api_response['status']) && strtolower($api_response['status']) === 'success')) {
        // Success - update refill_status and refilled_at
        $update_sql = "UPDATE boosting_orders SET refill_status = 'Requested', refilled_at = NOW() WHERE id = '$order_id'";
        if (!mysqli_query($conn, $update_sql)) {
            echo "DB Update Error for order $order_id: " . mysqli_error($conn) . "\n";
        } else {
            echo "Refill requested successfully for order $order_id\n";
        }
    } else {
        $error_msg = $api_response['error'] ?? 'Unknown error';
        echo "API Refill error for order $order_id: $error_msg\n";
    }
}

echo "Auto refill cron job completed.\n";
