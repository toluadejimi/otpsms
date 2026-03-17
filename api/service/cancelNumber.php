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

      function check_wait($active_data, $conn)
      {
        if ($active_data['sms_text'] == "") {
          $sql30 = mysqli_query($conn, "SELECT * FROM time_wait WHERE server_id='" . $active_data['server_id'] . "' and service_id='" . $active_data['service_id'] . "'");
          if (mysqli_num_rows($sql30) == 1) {
            $wait_data = mysqli_fetch_assoc($sql30);


            $givenTime = strtotime($active_data['buy_time']);
            $currentTime = time();

            $twoMinutesInSeconds = $wait_data['wait_sec'];

            $timeLeftSeconds = ($givenTime + $twoMinutesInSeconds) - $currentTime;
            if ($timeLeftSeconds >= 0) {
              $minutes = $twoMinutesInSeconds / 60;

              return '{"status":"600","message":"You Can Cancel Number In ' . $minutes . ' Min"}';
            }
          }
        }
      }
      $waiting = check_wait($active_data, $conn);
      if ($waiting != "") {
        echo $waiting;
      } else {
        if ($active_data['sms_text'] == "") {
          $sql3 = mysqli_query($conn, "SELECT * FROM otp_server WHERE id='" . $active_data['server_id'] . "'");
          $server_data = mysqli_fetch_assoc($sql3);
          $api_id = $server_data['api_id'];
          $sql4 = mysqli_query($conn, "SELECT * FROM api_detail WHERE id='" . $api_id . "'");
          $api_data = mysqli_fetch_assoc($sql4);
          $sql5 = mysqli_query($conn, "SELECT * FROM user_wallet WHERE user_id='" . $user_id . "'");
          $user_data = mysqli_fetch_assoc($sql5);

          $api_url = $api_data['api_url'];
          $api_key = $api_data['api_key'];
          $api_name = $api_data['api_name'];
          $number_id = $active_data['number_id'];
          $url = "";
          switch ($api_name) {
            case "sms-man":
              // sms-man sample rest-api: https://api.sms-man.com/control/set-status?token=$token&request_id=$request_id&status=$status
              $url = "{$api_url}/control/set-status?token={$api_key}&request_id={$number_id}&status=reject";
              break;
            case "5sim":
                $url = "{$api_url}/v1/user/cancel/{$number_id}";
                break;
            default:
              $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=setStatus&id={$number_id}&status=8";
          }
          $ch = curl_init($url);
            if ($api_name == "5sim") {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer {$api_key}",
                    "Accept: application/json"
                ]);
    
            }elseif ($api_name == "Daisysms") {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
            }
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          $result = curl_exec($ch);
          curl_close($ch);
          $add_balance = $user_data['balance'] + $active_data['service_price'];
          $cut_otp = $user_data['total_otp'] - 1;
          $sql100 = mysqli_query($conn, "SELECT * FROM active_number WHERE order_id='$order_id'");
          $active_data1 = mysqli_fetch_assoc($sql100);

          if ($active_data1['active_status'] == 1) {
            echo '{"status":"500","message":"Already Cancelled"}';
          } else {
            $sql5 = mysqli_query($conn, "UPDATE active_number SET active_status='1', status='3' WHERE id='" . $active_data['id'] . "'");
            $sql6 = mysqli_query($conn, "UPDATE user_wallet SET balance='$add_balance', total_otp='$cut_otp' WHERE user_id='" . $user_id . "'");
            echo '{"status":"200","message":"Number Cancelled"}';
          }
        } else {
          $sql100 = mysqli_query($conn, "SELECT * FROM active_number WHERE order_id='$order_id'");
          $active_data1 = mysqli_fetch_assoc($sql100);

          if ($active_data1['active_status'] == 1) {
            echo '{"status":"500","message":"Already Cancelled"}';
          } else {
            $sql5 = mysqli_query($conn, "UPDATE active_number SET active_status='1', status='1' WHERE id='" . $active_data['id'] . "'");
            echo '{"status":"200","message":"Number Cancelled"}';
          }
        }
      }
    } else {
      echo '{"status":"500","message":"Invalid Order Id Or Already Cancelled"}';
    }
  }
}
