<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['order_id']) || $_GET['order_id'] == "") {
  echo '{"status":"500","message":"Invalid Order id"}';
} elseif (!isset($_GET['token']) || $_GET['token'] == "") {
  echo '{"status":"500","message":"Token Blank"}';
} else {
  $token = mysqli_real_escape_string($conn, $_GET['token']);
  $find_token = new radiumsahil();
  $check_token = $find_token->check_token($token);
  $find_token->closeConnection();
  if ($check_token === false) {
    echo '{"status":"500","message":"Token Expired Please Logout And Login Again"}';
  } else {
    $user_id = $check_token;
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
    $sql1 = mysqli_query($conn, "SELECT * FROM active_number WHERE order_id='$order_id' and active_status='2'");
    if (mysqli_num_rows($sql1) == 1) {
      $active_data = mysqli_fetch_assoc($sql1);
      $sql2 = mysqli_query($conn, "SELECT * FROM otp_server WHERE id='" . $active_data['server_id'] . "'");
      $server_data = mysqli_fetch_assoc($sql2);
      $api_id = $server_data['api_id'];
      $sql3 = mysqli_query($conn, "SELECT * FROM api_detail WHERE id='$api_id'");
      $api_data = mysqli_fetch_assoc($sql3);
      $sql4 = mysqli_query($conn, "SELECT * FROM user_wallet WHERE user_id='$user_id'");
      $user_data = mysqli_fetch_assoc($sql4);
      $user_balance = $user_data['balance'];
      $user_otp = $user_data['total_otp'];
      $number_id = $active_data['number_id'];
      $api_url = $api_data['api_url'];
      $api_name = $api_data['api_name'];
      $api_key = $api_data['api_key'];
      $url = "";
      switch ($api_name) {
        case "sms-man":
          // sms-man sample rest-api: https://api.sms-man.com/control/get-sms?token=$token&request_id=$request_id
          $url = "{$api_url}/control/get-sms?token={$api_key}&request_id={$number_id}";
          break;
      case "5sim":
        $url = "{$api_url}/v1/user/check/{$number_id}";
        break;
        default:
          $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=getStatus&id={$number_id}";
      }
      $ch = curl_init();
      $ch = curl_init($url);
        if ($api_name == "5sim") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$api_key}",
                "Accept: application/json"
            ]);
    
        } elseif ($api_name == "Daisysms") {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $response = curl_exec($ch);
      curl_close($ch);

      if ($api_name == "sms-man") {
        $response = json_decode($response);
        // {"request_id":1,"country_id":1,"application_id":1,"number":"79002415539", "sms_code": "1243"};
        if ($response->sms_code) {
          $sms = $response->sms_code;
          if ($active_data['sms_text'] != $sms) {
            $sql5 = mysqli_query($conn, "UPDATE active_number SET sms_text='" . $sms . "', status='1' WHERE id='" . $active_data['id'] . "'");
            $url = "{$api_url}/control/set-status?token={$api_key}&request_id={$number_id}&status=used";
            $ch = curl_init($url);
            //   curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
          }
          echo '{"status":"200","message":"Code Received","sms":"' . $sms . '"}';
        } else {
          /*  if($active_data['status'] == 2){
    $add_balance = $user_data['balance'] + $active_data['service_price'];  
    $cut_otp = $user_data['total_otp'] - 1;            
           $sql5 = mysqli_query($conn,"UPDATE active_number SET active_status='1', status='3' WHERE id='".$active_data['id']."'");  
          $sql6 = mysqli_query($conn, "UPDATE user_wallet SET balance='$add_balance', total_otp='$cut_otp' WHERE user_id='$user_id'"); 
                echo'{"status":"500","message":"Number Canceled #2"}';                        
          }else{*/
            if ($active_data['sms_text'] != "") {
              echo '{"status":"200","message":"Code Receive","sms":"' . $active_data['sms_text'] . '"}';
            } else {
              echo '{"status":"300","message":"Waiting for SMS."}';
            };
          //}  
        }
      } elseif($api_name == "5sim"){
        // log_5sim_response($number_id, $response);

        $response = json_decode($response);
    
        if (isset($response->status) && $response->status == "RECEIVED" && !empty($response->sms)) {
    
            $smsData = $response->sms[0];   // first sms
            $sms = $smsData->code ?? $smsData->text;
    
            if ($active_data['sms_text'] != $sms) {
    
                mysqli_query($conn,
                    "UPDATE active_number 
                     SET sms_text='".$sms."', status='1' 
                     WHERE id='".$active_data['id']."'"
                );
            }
    
            echo '{"status":"200","message":"Code Received","sms":"'.$sms.'"}';
    
        } else {
    
            if ($active_data['sms_text'] != "") {
    
                echo '{"status":"200","message":"Code Receive","sms":"' . $active_data['sms_text'] . '"}';
    
            } else {
    
                echo '{"status":"300","message":"Waiting for SMS."}';
            }
        }
    } else {
        if ($response == 'STATUS_WAIT_CODE') {
          if ($active_data['sms_text'] != "") {
            echo '{"status":"200","message":"Code Receive","sms":"' . $active_data['sms_text'] . '"}';
          } else {
            echo '{"status":"300","message":"Waiting for SMS."}';
          }
        } else if ($response == 'STATUS_CANCEL') {
          if ($active_data['sms_text'] != "") {
            $sql5 = mysqli_query($conn, "UPDATE active_number SET active_status='1', status='1' WHERE id='" . $active_data['id'] . "'");
          } else {
            if ($active_data['status'] == 2) {
              $add_balance = $user_data['balance'] + $active_data['service_price'];
              $cut_otp = $user_data['total_otp'] - 1;
              $sql5 = mysqli_query($conn, "UPDATE active_number SET active_status='1', status='3' WHERE id='" . $active_data['id'] . "'");
              $sql6 = mysqli_query($conn, "UPDATE user_wallet SET balance='$add_balance', total_otp='$cut_otp' WHERE user_id='$user_id'");
              $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=setStatus&id={$number_id}&status=3";
              $ch = curl_init($url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              echo '{"status":"500","message":"Number Canceled #1"}';
            } else {
              echo '{"status":"500","message":"Error #3"}';
            }
          }
        } elseif (explode(':', $response)[0] == 'STATUS_OK') {
          $sms = explode('STATUS_OK:', $response)[1];
          if ($active_data['sms_text'] != $sms) {
            $sql5 = mysqli_query($conn, "UPDATE active_number SET sms_text='" . $sms . "', status='1' WHERE id='" . $active_data['id'] . "'");
            $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=setStatus&id={$number_id}&status=8";
            $ch = curl_init($url);
            //   curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
          }
          echo '{"status":"200","message":"Code Receive","sms":"' . $sms . '"}';
        } else {
          /*  if($active_data['status'] == 2){
    $add_balance = $user_data['balance'] + $active_data['service_price'];  
    $cut_otp = $user_data['total_otp'] - 1;            
           $sql5 = mysqli_query($conn,"UPDATE active_number SET active_status='1', status='3' WHERE id='".$active_data['id']."'");  
          $sql6 = mysqli_query($conn, "UPDATE user_wallet SET balance='$add_balance', total_otp='$cut_otp' WHERE user_id='$user_id'"); 
                echo'{"status":"500","message":"Number Canceled #2"}';                        
          }else{*/
          echo '{"status":"500","message":"Please Refresh Page"}';
          //}  
        }
      }
    } else {
      echo '{"status":"500","message":"Invalid Order Id Or Cancelled"}';
    }
  }
}
