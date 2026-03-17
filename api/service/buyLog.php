<?php
require_once __DIR__ . '/../../include/config.php';

// Check if required parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_order') {
    // Sanitize input data
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $quantity = (int)$_POST['quantity'];
    $token = mysqli_real_escape_string($conn, $_POST['token']);

    // Validate token
    $find_token = new radiumsahil();
    $check_token = $find_token->check_token($token);
    $find_token->closeConnection();
    if ($check_token === false) {
        echo json_encode(['status' => '500', 'message' => 'Token Expired']);
        exit;
    }

    // Assume user_id is retrieved after validating the token
    $user_id = $check_token;

    // Fetch the price of the product from the products table
    $sql_product_info = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
    if (mysqli_num_rows($sql_product_info) == 0) {
        echo json_encode(['status' => '500', 'message' => 'Product not found']);
        exit;
    }
    $product_info = mysqli_fetch_assoc($sql_product_info);
    $price_per_unit = $product_info['price'];

    // Check if user has enough balance to place the order
    $sql_wallet = mysqli_query($conn, "SELECT * FROM user_wallet WHERE user_id = '$user_id'");
    $wallet_data = mysqli_fetch_assoc($sql_wallet);

    $total_price = $price_per_unit * $quantity;

    if ($wallet_data['balance'] < $total_price) {
        echo json_encode(['status' => '500', 'message' => 'Insufficient balance']);
        exit;
    }

    if($product_info['api_id'] == 0){
        // Begin transaction
        mysqli_query($conn, "START TRANSACTION");

        try {
            
            // Fetch the required number of unsold product_details (based on quantity)
            $sql_product = mysqli_query($conn, "SELECT * FROM product_details WHERE product_id = '$product_id' AND is_sold = 0 LIMIT $quantity");
            if (mysqli_num_rows($sql_product) < $quantity) {
                echo json_encode(['status' => '500', 'message' => 'Not enough available products']);
                exit;
            }
        
            // Fetch product details (since we now have the necessary product_details)
            $product_details = [];
            while ($row = mysqli_fetch_assoc($sql_product)) {
                $product_details[] = $row;
            }
    
            // Insert into orders table
            $order_date = date("Y-m-d H:i:s");
            $order_query = "INSERT INTO orders (user_id, total_amount, status) VALUES ('$user_id', '$total_price', 1)";
            if (!mysqli_query($conn, $order_query)) {
                throw new Exception("Failed to insert order.");
            }
    
            // Get the last inserted order ID
            $order_id = mysqli_insert_id($conn);
    
            // Insert into order_items table for each unit of the product ordered
            foreach ($product_details as $product_detail) {
                // Insert into order_items with the price from the products table
                $order_items_query = "INSERT INTO order_items (order_id, product_id, product_detail_id, price) 
                                      VALUES ('$order_id', '$product_id', '{$product_detail['id']}', '$price_per_unit')";
                if (!mysqli_query($conn, $order_items_query)) {
                    throw new Exception("Failed to insert order items.");
                }
    
                // Mark product as sold
                $update_product_query = "UPDATE product_details SET is_sold = 1 WHERE id = '{$product_detail['id']}'";
                if (!mysqli_query($conn, $update_product_query)) {
                    throw new Exception("Failed to update product status.");
                }
            }
    
            // Deduct from user's wallet balance
            $new_balance = $wallet_data['balance'] - $total_price;
            $update_balance_query = "UPDATE user_wallet SET balance = '$new_balance' WHERE user_id = '$user_id'";
            if (!mysqli_query($conn, $update_balance_query)) {
                throw new Exception("Failed to update user wallet balance.");
            }
    
            // Commit the transaction if all queries are successful
            mysqli_query($conn, "COMMIT");
    
            echo json_encode(['status' => '200', 'message' => 'Order placed successfully']);
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            mysqli_query($conn, "ROLLBACK");
            echo json_encode(['status' => '500', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }else{
        $api_provider_id = $product_info['api_provider_id'];
        // Fetch API provider info
        $sql_api = mysqli_query($conn, "SELECT * FROM api_providers WHERE id = '$api_provider_id'");
        if (mysqli_num_rows($sql_api) == 0) {
            echo json_encode(['status' => '500', 'message' => 'API Provider not found']);
            exit;
        }
        $api_provider = mysqli_fetch_assoc($sql_api);
        
        if($api_provider['type'] === "SHOPCLONE7"){
            // Call external API
            $api_url = $api_provider['domain'] . "/api/buy_product?action=buyProduct&api_key=" . $api_provider['token'] . "&id=" . $product_info['api_id'] . "&amount=" . $quantity;
            $response = file_get_contents($api_url);
            $api_response = json_decode($response, true);
            if (!$api_response || $api_response['status'] === 'error') {
                echo json_encode(['status' => '500', 'message' => $api_response['msg'] ?? 'API Error']);
                exit;
            }
        
            $api_trx_id = mysqli_real_escape_string($conn, $api_response['trans_id']);
            $accounts = $api_response['data'];
        }elseif($api_provider['type'] === "SHOPCLONE6"){
            // Call external API
            $api_url = $api_provider['domain'] . "/api/BResource.php?username=" . $api_provider['username'] . "&password=" . $api_provider['password'] . "&id=" . $product_info['api_id'] . "&amount=" . $quantity;
            $response = file_get_contents($api_url);
            $api_response = json_decode($response, true);
        
            if (!$api_response || $api_response['status'] === 'error') {
                echo json_encode(['status' => '500', 'message' => $api_response['msg'] ?? 'API Error']);
                exit;
            }
        
            $api_trx_id = mysqli_real_escape_string($conn, $api_response['data']['trans_id']);
            $accounts = [];
            
            if (!empty($api_response['data']['lists'])) {
                foreach ($api_response['data']['lists'] as $item) {
                    if (!empty($item['account'])) {
                        $accounts[] = $item['account'];
                    }
                }
            }
        }
    
        // Start transaction
        mysqli_query($conn, "START TRANSACTION");
    
        try {
            // Insert order
            $order_date = date("Y-m-d H:i:s");
            $order_query = "INSERT INTO orders (user_id, total_amount, status, api_id, api_trx_id, created_at) 
                            VALUES ('$user_id', '$total_price', 1, '{$product_info['api_provider_id']}', '$api_trx_id', '$order_date')";
            if (!mysqli_query($conn, $order_query)) {
                throw new Exception("Failed to create order");
            }
            $order_id = mysqli_insert_id($conn);
    
            // Insert product_details and order_items
            foreach ($accounts as $account) {
                $escaped_details = mysqli_real_escape_string($conn, $account);
                $insert_detail = "INSERT INTO product_details (product_id, is_sold, details) VALUES ('$product_id', 1, '$escaped_details')";
                if (!mysqli_query($conn, $insert_detail)) {
                    throw new Exception("Failed to insert product detail");
                }
                $product_detail_id = mysqli_insert_id($conn);
    
                $insert_item = "INSERT INTO order_items (order_id, product_id, product_detail_id, price) 
                                VALUES ('$order_id', '$product_id', '$product_detail_id', '$price_per_unit')";
                if (!mysqli_query($conn, $insert_item)) {
                    throw new Exception("Failed to insert order item");
                }
            }
    
            // Deduct from wallet
            $new_balance = $wallet_data['balance'] - $total_price;
            $update_wallet = "UPDATE user_wallet SET balance = '$new_balance' WHERE user_id = '$user_id'";
            if (!mysqli_query($conn, $update_wallet)) {
                throw new Exception("Failed to update wallet balance");
            }
    
            // Commit transaction
            mysqli_query($conn, "COMMIT");
            echo json_encode(['status' => '200', 'message' => 'Order placed successfully']);
        } catch (Exception $e) {
            mysqli_query($conn, "ROLLBACK");
            echo json_encode(['status' => '500', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => '500', 'message' => 'Invalid request']);
}
?>
