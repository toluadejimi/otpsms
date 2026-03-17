<?php
require_once __DIR__ . '/../../include/config.php';

if(!isset($_GET['token']) || $_GET['token'] == "") {
echo'{"status":"500","message":"Token Blank"}';

} else {
$token = mysqli_real_escape_string($conn,$_GET['token']); 
$find_token = new radiumsahil();
$check_token = $find_token->check_token($token);
$find_token->closeConnection();
if($check_token === false){
echo'{"status":"500","message":"Token Expired Please Logout And Login Again"}';
}else{
$user_id = $check_token;
$sql = "SELECT active_number.*, otp_server.api_id AS api_id FROM active_number INNER JOIN otp_server ON active_number.server_id = otp_server.id WHERE active_number.user_id = '" . $user_id . "' AND active_number.active_status = '2' AND otp_server.api_id = '22' ORDER BY active_number.id DESC";
$result = mysqli_query($conn, $sql);
$currentTime = date("Y-m-d H:i:s");



if ($result === false) {
    echo '{"status":"500","message":"Database Query Error: ' . mysqli_error($conn) . '"}';
}else{ 
$final = array();   
    while ($row = mysqli_fetch_array($result)) {
$givenTime = strtotime($row['buy_time']);
$currentTime = time(); 

$twentyMinutesInSeconds = 10 * 60; 

if (($givenTime + $twentyMinutesInSeconds) <= $currentTime) {
    $left = "00:00";
} else {
$timeLeftSeconds = ($givenTime + $twentyMinutesInSeconds) - $currentTime;

        // Convert seconds to milliseconds
        $left = $timeLeftSeconds * 1000;

//$left = sprintf("%02d:%02d", $timeLeftMinutes, $remainingSeconds);

//list($minutes, $seconds) = explode(":", $left);
//$left = ($minutes * 60 + $seconds) * 1000;


}
 if($left == "00:00"){
if($row['active_status'] == 2){
$sql3=mysqli_query($conn,"SELECT * FROM otp_server WHERE id='".$row['server_id']."'");
$server_data=mysqli_fetch_assoc($sql3);
$api_id = $server_data['api_id'];
$sql4=mysqli_query($conn,"SELECT * FROM api_detail WHERE id='".$api_id."'");
$api_data=mysqli_fetch_assoc($sql4);
$sql5=mysqli_query($conn,"SELECT * FROM user_wallet WHERE user_id='".$user_id."'");
$user_data=mysqli_fetch_assoc($sql5);
if($row['sms_text'] == ""){
$api_url = $api_data['api_url'];
$api_name = $api_data['api_name'];
$api_key = $api_data['api_key'];
$number_id = $row['number_id'];
        $url = "";
          switch($api_name){
              case "sms-man":
                  // sms-man sample rest-api: https://api.sms-man.com/control/set-status?token=$token&request_id=$request_id&status=$status
                $url = "{$api_url}/control/set-status?token={$api_key}&request_id={$number_id}&status=used";
                break;
             case "sms-pool":
              //sms-pool sample rest-api https://api.smspool.net/sms/cancel
                $url = "{$api_url}/sms/cancel";
            break;
            default:
                $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=setStatus&id={$number_id}&status=8";
          }
        $ch = curl_init($url); 
        if($api_name == "sms-pool"){
            // POST data
            $postData = array(
                'key' => $api_key,
                'orderid' => $number_id
            );
            
            // Initialize cURL session
            $ch = curl_init();
            
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Set the POST data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
        }else{
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            if ($api_name == "Daisysms") {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $result = curl_exec($ch);
        curl_close($ch);
$add_balance = $user_data['balance'] + $row['service_price'];  
$cut_otp = $user_data['total_otp'] - 1;    
$sql5 = mysqli_query($conn,"UPDATE active_number SET active_status='1', status='3' WHERE id='".$row['id']."'");  
$sql6 = mysqli_query($conn, "UPDATE user_wallet SET balance='$add_balance', total_otp='$cut_otp' WHERE user_id='".$user_id."'");
}else{
$sql5 = mysqli_query($conn,"UPDATE active_number SET active_status='1', status='1' WHERE id='".$row['id']."'");  

}
continue;      
}

}       
        array_push($final, array(

            'id' => $row['order_id'],
            'number' => $row['number'], // Corrected column name
            'amount' => $row['service_price'],
            'left_time' => $left,
            'app' => $row['service_name'],
            'sms' => $row['sms_text'],
            'service_id' => $row['service_id'],
        ));

    }
    

$data = array(
'status' => '200',
'data' => $final
);

echo json_encode($data);
}
mysqli_close($conn);
}
}


