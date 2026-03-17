<?php
require_once __DIR__ . '/include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id']) && isset($_GET['token'])) {
    $order_id = (int)$_GET['order_id'];
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    // Validate token
    $find_token = new radiumsahil();
    $user_id = $find_token->check_token($token);
    $find_token->closeConnection();

    if ($user_id === false) {
        http_response_code(403);
        echo "Invalid or expired token.";
        exit;
    }

    // Fetch the order and verify ownership and paid status
    $query = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id' AND status = 1";
    $order_result = mysqli_query($conn, $query);

    if (!$order_result || mysqli_num_rows($order_result) === 0) {
        http_response_code(404);
        echo "Order not found or not accessible.";
        exit;
    }

    // Get order items and their corresponding product details
    $details_content = "";
    $sql = "SELECT pd.details
            FROM order_items oi
            JOIN product_details pd ON oi.product_detail_id = pd.id
            WHERE oi.order_id = '$order_id' AND pd.is_sold = 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $details_content .= $row['details'] . "\n";
        }
    } else {
        http_response_code(404);
        echo "No product details found for this order.";
        exit;
    }

    // Set headers to force download
    $filename = "order_{$order_id}_accounts.txt";
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($details_content));
    echo $details_content;
    exit;
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
