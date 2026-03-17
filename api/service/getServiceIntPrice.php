<?php
require_once __DIR__ . '/../../include/config.php';

if (!isset($_GET['server']) || $_GET['server'] == "") {
    echo '{"status":"500","message":"Invalid Server"}';
    exit();
}

if (!isset($_GET['service']) || $_GET['service'] == "") {
    echo '{"status":"500","message":"Invalid Service"}';
    exit();
}

if (!isset($_GET['token']) || $_GET['token'] == "") {
    echo '{"status":"500","message":"Token Blank"}';
    exit();
}

$token = mysqli_real_escape_string($conn,$_GET['token']);
$find_token = new radiumsahil();
$check_token = $find_token->check_token($token);
$find_token->closeConnection();

if($check_token === false){
    echo '{"status":"500","message":"Token Expired Please Logout And Login Again"}';
    exit();
}

$server = $_GET['server'];
$service = $_GET['service'];

/* ================================
   GET SERVER DATA
================================ */

$sql = mysqli_query($conn,"SELECT * FROM otp_server WHERE id='".$server."'");
$server_data = mysqli_fetch_assoc($sql);

if(!$server_data){
    echo '{"status":"500","message":"Server not found"}';
    exit();
}

$sql2 = mysqli_query($conn,"SELECT * FROM api_detail WHERE id='".$server_data['api_id']."'");
$api_data = mysqli_fetch_assoc($sql2);

$api_name = $api_data['api_name'];
$api_key = $api_data['api_key'];
$api_url = $api_data['api_url'];
$api_rate = (float)$api_data['api_rate'];
$api_percentage_increase = (float)$api_data['api_percentage_increase'];

$server_code = $server_data['server_code'];
$server_short_name = $server_data['server_short_name'];

/* ================================
   GET SERVICE DATA
================================ */

$sql3 = mysqli_query($conn,"SELECT * FROM service WHERE service_id='".$service."' AND server_id='".$server."'");
$service_data = mysqli_fetch_assoc($sql3);

if(!$service_data){
    echo '{"status":"500","message":"Service not found"}';
    exit();
}

$service_id = $service_data['service_id'];

/* =====================================================
   5SIM PROVIDER (RETURN MULTIPLE POOLS)
===================================================== */

if($api_name == "5sim"){

    $url = "https://5sim.net/v1/guest/prices?country=".$server_code."&product=".$service_id;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);

    if($response === false){
        echo '{"status":"500","message":"Unable to connect to 5sim"}';
        exit();
    }

    curl_close($ch);

    $response = json_decode($response,true);

    if(!isset($response[$server_short_name][$service_id])){
        echo '{"status":"500","message":"Service not available"}';
        exit();
    }

    $service_pools = $response[$server_short_name][$service_id];

    $pools_output = [];

    foreach($service_pools as $pool => $data){

        if(!isset($data['cost'])) continue;

        $cost_usd = (float)$data['cost'];
        $count = (int)($data['count'] ?? 0);
        $rate = isset($data['rate']) ? (float)$data['rate'] : 0;

        // apply markup
        $price_usd = round((($api_percentage_increase/100) * $cost_usd) + $cost_usd,2);

        // convert to naira
        $price_naira = ceil($api_rate * $price_usd);

        $pools_output[] = [
            "pool"=>$pool,
            "price_naira"=>$price_naira,
            "count"=>$count,
            "rate"=>$rate
        ];
    }

    echo json_encode([
        "status"=>"200",
        "type"=>"multiple",
        "pools"=>$pools_output
    ]);

    exit();
}

/* =====================================================
   OTHER PROVIDERS (RETURN SINGLE PRICE)
===================================================== */

$url = "";

switch ($api_name) {

    case "sms-man":
        $url = "{$api_url}/control/get-prices?token={$api_key}&country_id={$server_code}";
    break;

    case "Daisysms":
        $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=getPrices&service={$service_id}&country={$server_code}";
    break;

    default:
        $url = "{$api_url}/stubs/handler_api.php?api_key={$api_key}&action=getPrices&service={$service_id}&country={$server_code}";
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);

$response = curl_exec($ch);

if($response === false){
    echo '{"status":"500","message":"Unable to fetch price"}';
    exit();
}

curl_close($ch);

$response = (array) json_decode($response);

$service_price_calc = 0;

if(isset($response[$server_code]->$service_id->cost)){
    $service_price_calc = $response[$server_code]->$service_id->cost;
}

if($service_price_calc <= 0){
    echo '{"status":"500","message":"Price not found"}';
    exit();
}

/* ================================
   CONVERT TO NAIRA
================================ */

$price_usd = round((($api_percentage_increase/100) * $service_price_calc) + $service_price_calc,2);

$service_price_naira = ceil($api_rate * $price_usd);

/* update cached price */

mysqli_query($conn,"
UPDATE service 
SET service_price='".$service_price_naira."' 
WHERE service_id='".$service_id."' 
AND server_id='".$server."'
");

echo json_encode([
    "status"=>"200",
    "price"=>$service_price_naira,
    "message"=>"The price of ".$service_data['service_name']." is ₦".number_format($service_price_naira,2)
]);