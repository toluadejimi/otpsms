<?php
require_once __DIR__ . '/../../include/config.php';

if (isset($_POST['phone_number']) && isset($_POST['token']) && !empty($_POST['phone_number']) && !empty($_POST['token'])) {
    // Sanitize the inputs
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);

    // Validate token
    $find_token = new radiumsahil();
    $check_token = $find_token->check_token($token); // This returns the user ID if valid
    $find_token->closeConnection();

    if ($check_token === false) {
        echo '{"status": "3","msg": "Token Expired Please Logout And Login Again"}';
    } else {
        // Check if phone number is already used by another user
        $check_query = $conn->query("SELECT id FROM user_data WHERE phone_number = '$phone_number' AND id != '$check_token'");
        
        if ($check_query && $check_query->num_rows > 0) {
            echo '{"status": "2","msg": "Phone Number Already In Use By Another User"}';
        } else {
            // Update the phone number
            $update_query = $conn->query("UPDATE user_data SET phone_number = '$phone_number' WHERE id = '$check_token'");
            
            if ($update_query) {
                echo '{"status": "1","msg": "Phone Number Updated Successfully"}';
            } else {
                echo '{"status": "2","msg": "Failed to Update Phone Number"}';
            }
        }
    }
} else {
    echo '{"status": "2","msg": "Missing Required Parameters"}';
}
