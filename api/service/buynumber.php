<?php
require_once __DIR__ . '/../../include/config.php';
function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';

    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $random_string;
}
if (!isset($_GET['server']) || $_GET['server'] == "") {
echo'{"status":"500","message":"Invalid Server"}';
} elseif (!isset($_GET['service']) || $_GET['service'] == "") {
echo'{"status":"500","message":"Invalid Service"}';
} elseif (!isset($_GET['token']) || $_GET['token'] == "") {
echo'{"status":"500","message":"Token Blank"}';
} else {
$token = mysqli_real_escape_string($conn,$_GET['token']); 
$find_token = new radiumsahil();
$check_token = $find_token->check_token($token);
$find_token->closeConnection();
if($check_token === false){
echo'{"status":"500","message":"Token Expired Please Logout And Login Again"}';
}else{
$server = $_GET['server'];
$service = $_GET['service'];

$service_pool = "";
$service_id = $service;

if (strpos($service, ',') !== false) {
    $parts = explode(',', $service);
    $service_id = $parts[0];
    $service_pool = $parts[1];
}

$sql = mysqli_query($conn, "SELECT * FROM service WHERE service_id='" . $service_id . "' AND server_id='" . $server . "'");

if(mysqli_num_rows($sql) != 1){
echo'{"status":"500","message":"Service Not Found"}';
}else{
$user_id = $check_token;
$sql2=mysqli_query($conn,"SELECT * FROM user_wallet WHERE user_id='".$user_id."'");
$service_data = mysqli_fetch_assoc($sql);
$user_data=mysqli_fetch_assoc($sql2);
$user_balance = $user_data['balance'];
$service_price = $service_data['service_price'];

$multiplier = 1.0;
$queryParams = [];

// Area Code
if (!empty($_GET['area_code'])) {
    $areas = preg_replace('/\s+/', '', $_GET['area_code']); // clean
    $queryParams[] = "areas=" . urlencode($areas);
    $multiplier += 0.3;
}

// Carrier
if (!empty($_GET['carrier'])) {
    $carriers = strtolower(trim($_GET['carrier']));
    $queryParams[] = "carriers=" . urlencode($carriers);
    $multiplier += 0.3;
}

// Preferred Number
if (!empty($_GET['preferred_number'])) {
    $number = preg_replace('/[^0-9]/', '', $_GET['preferred_number']);
    $queryParams[] = "number=" . urlencode($number);
    $multiplier += 0.3;
}

$original_price = $service_price;
$service_price = ceil($service_price * $multiplier); // round up

if($user_balance >= $service_price){
$server_id = $service_data['server_id'];
$sql3=mysqli_query($conn,"SELECT * FROM otp_server WHERE id='".$server_id."'");
$server_data=mysqli_fetch_assoc($sql3);
$api_id = $server_data['api_id'];
$sql4 = mysqli_query($conn, "SELECT * FROM api_detail WHERE id='" . $api_id . "'");
                $api_data = mysqli_fetch_assoc($sql4);
                $api_key = $api_data['api_key'];
                $api_url = $api_data['api_url'];
                $api_name = $api_data['api_name'];
                $server_code = $server_data['server_code'];
                $server_short_name = $server_data['server_short_name'];
                $url = "";
                
                
        switch ($api_name) {
            case "5sim":
                // Re-fetch price from 5sim
                $price_url = "https://5sim.net/v1/guest/prices?country={$server_code}&product={$service_id}";
            
                $ch = curl_init($price_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $price_result = curl_exec($ch);
                curl_close($ch);
            
                $price_data = json_decode($price_result,true);
                
                if(!isset($price_data[$server_short_name][$service_id][$service_pool]['cost'])){
                    echo '{"status":"500","message":"Pool not available"}';
                    exit();
                }
            
                $cost_usd = $price_data[$server_short_name][$service_id][$service_pool]['cost'];
            
                // Convert price
                $api_rate = $api_data['api_rate'];
                $api_percentage_increase = $api_data['api_percentage_increase'];
            
                $price_usd = round((($api_percentage_increase/100) * $cost_usd) + $cost_usd,2);
            
                $service_price = ceil($api_rate * $price_usd * $multiplier);
            
                if($user_balance < $service_price){
                    echo '{"status":"500","message":"Insufficient Balance"}';
                    exit();
                }
            
                // Buy number
                $url = "https://5sim.net/v1/user/buy/activation/{$server_short_name}/{$service_pool}/{$service_id}";
                
                // print_r($url);
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer {$api_key}",
                    "Accept: application/json"
                ]);
            
                $result = curl_exec($ch);
                curl_close($ch);
                
                // print_r($result);
            
                $response = json_decode($result,true);
            
                if(isset($response['phone'])){
            
                    $number = $response['phone'];
                    $number_id = $response['id'];
            
                    $current_time_in_ist = date('Y-m-d H:i:s');
                    $cut_balance = $user_balance - $service_price;
                    $user_otp = $user_data['total_otp'] + 1;
            
                    $random_order = generateRandomString();
                    $service_name = $service_data['service_name'];
            
                    mysqli_query($conn,"UPDATE user_wallet SET balance='$cut_balance', total_otp='$user_otp' WHERE user_id='$user_id'");
            
                    mysqli_query($conn,"
                    INSERT INTO active_number
                    (user_id,number_id,number,server_id,service_id,order_id,buy_time,status,sms_text,service_price,service_name,active_status)
                    VALUES
                    ('$user_id','$number_id','$number','$server','$service','$random_order','$current_time_in_ist','2','','{$service_price}','$service_name','2')
                    ");
            
                    echo '{"status":"200","message":"Number Purchased","res":"ACCESS_NUMBER:' . $random_order . ':' . $number . '"}';
                    exit();
            
                }else{
            
                    echo '{"status":"500","message":"Error purchasing Number"}';
                    exit();
                }
            
            break;
                    case "sms-man":
                        // sms-man sample rest-api: https://api.sms-man.com/control/get-number?token=$token&country_id=$country_id&application_id=$application_id
                        $url = "{$api_url}/control/get-number?token={$api_key}&application_id={$service}&country_id={$server_code}";
                        break;
                    case "Daisysms":
                        $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=getNumber&service={$service}";
                        break;
                    default:
                        $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=getNumber&service={$service}&country={$server_code}";
                }
                
                $ch = curl_init($url);
                if ($api_name == "Daisysms") {
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                } else {
                    curl_setopt($ch, CURLOPT_POST, 1);
                }
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $result = curl_exec($ch);
                
        if ($result === false) {
                    // echo'{"status":"500","message":"cURL error' . curl_error($ch) . '"}';
                    echo '{"status":"500","message":"Sorry we\'re experiencing troubles generating numbers. Please check back later."}';
                    exit();
                } else {
                    $response = ($api_name === "sms-man")  ?  json_decode($result) : explode(':', $result);
                }

        if ($api_name == "sms-man") {
                    if ($response->error_msg) {
                        echo '{"status":"500","message":"Error : ' . $response->error_msg . '"}';
                    } else {
                        $current_time_in_ist = date('Y-m-d H:i:s');
                        $cut_balance = $user_balance - $service_price;
                        $user_otp = $user_data['total_otp'];
                        $add_otp = $user_otp + 1;
                        $service_name = $service_data['service_name'];
                        $random_order = generateRandomString();
                        $sql5 = mysqli_query($conn, "UPDATE user_wallet SET balance='$cut_balance', total_otp='$add_otp' WHERE user_id='$user_id'");
                        if ($sql5) {
                            $sql6 = mysqli_query($conn, "INSERT INTO active_number(user_id, number_id, number, server_id, service_id, order_id, buy_time, status, sms_text, service_price, service_name, active_status) VALUES ('" . $user_id . "','" . $response->request_id . "','" . $response->number . "','" . $server . "','" . $service . "','" . $random_order . "','" . $current_time_in_ist . "','2','','" . $service_price . "','" . $service_name . "','2')");
                            echo '{"status":"200","message":"Number Purchased","res":"ACCESS_NUMBER:' . $random_order . ':' . $response->number . '"}';
                        } else {
                            echo '{"status":"500","message":"Sql Error #1"}';
                        }
                    }
                } else {
                    if ($response[0] != "ACCESS_NUMBER") {
                        echo '{"status":"500","message":"Error : ' . $response[0] . '"}';
                    } else {

                        $current_time_in_ist = date('Y-m-d H:i:s');
                        $cut_balance = $user_balance - $service_price;
                        $user_otp = $user_data['total_otp'];
                        $add_otp = $user_otp + 1;
                        $service_name = $service_data['service_name'];
                        $random_order = generateRandomString();
                        $sql5 = mysqli_query($conn, "UPDATE user_wallet SET balance='$cut_balance', total_otp='$add_otp' WHERE user_id='$user_id'");
                        if ($sql5) {
                            $sql6 = mysqli_query($conn, "INSERT INTO active_number(user_id, number_id, number, server_id, service_id, order_id, buy_time, status, sms_text, service_price, service_name, active_status) VALUES ('" . $user_id . "','" . $response[1] . "','" . $response[2] . "','" . $server . "','" . $service . "','" . $random_order . "','" . $current_time_in_ist . "','2','','" . $service_price . "','" . $service_name . "','2')");
                            echo '{"status":"200","message":"Number Purchased","res":"ACCESS_NUMBER:' . $random_order . ':' . $response[2] . '"}';
                        } else {
                            echo '{"status":"500","message":"Sql Error #1"}';
                        }
                    }
                }
}else{
echo'{"status":"500","message":"Insufficient Balance"}';
}
}
}}