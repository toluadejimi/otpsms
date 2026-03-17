<?php
ini_set('max_execution_time', 1000);
ini_set('memory_limit', '512M');

require_once __DIR__ . '/../../include/config.php';

$api_id = 26;
$country_id = "usa";

$sql_api_details = mysqli_query($conn,"SELECT * FROM api_detail WHERE id='" . $api_id . "'");
$api_data = mysqli_fetch_assoc($sql_api_details);
$api_key = $api_data['api_key'];
$api_percentage_increase = (float) $api_data['api_percentage_increase'];
$api_rate = (float) $api_data['api_rate'];

$url = "https://api1.5sim.net/stubs/handler_api.php?api_key=7bb7bd9b938a4d90906d824a3354a0f0&action=getPrices&country=$country_id";

print_r($url);

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_TIMEOUT => 60,
    CURLOPT_MAXREDIRS => 10,
));

$response = curl_exec($ch);
if ($response === false) die("cURL error: " . curl_error($ch));
curl_close($ch);

$services = json_decode($response, true);
if (!$services) die("Invalid API response");

foreach ($services[$country_id] as $service_key => $service_pools) {
    $cheapest_cost = null;

    // Find cheapest pool with count > 0
    foreach ($service_pools as $pool => $data) {
        if (isset($data['count']) && $data['count'] > 0) {
            if ($cheapest_cost === null || $data['cost'] < $cheapest_cost) {
                $cheapest_cost = $data['cost'];
            }
        }
    }

    // If no pool has count > 0, pick the cheapest anyway
    if ($cheapest_cost === null) {
        foreach ($service_pools as $pool => $data) {
            if (isset($data['cost'])) {
                if ($cheapest_cost === null || $data['cost'] < $cheapest_cost) {
                    $cheapest_cost = $data['cost'];
                }
            }
        }
    }

    // $service_price_usd = round($cheapest_cost * 0.013, 2); // RUB -> USD
    $service_price_usd = round($cheapest_cost, 2); // RUB -> USD
    $percentage = $api_percentage_increase / 100;
    $service_price_usd_with_interest = round($service_price_usd * (1 + $percentage), 2);
    $service_price_naira = $api_rate * $service_price_usd_with_interest;

    $sql_get_server_id = mysqli_query($conn, "SELECT id FROM otp_server WHERE server_short_name = '$country_id' AND api_id = '$api_id'");
    $server_id = mysqli_fetch_assoc($sql_get_server_id)['id'];

    $sql_get_service_name = mysqli_query($conn, "
        SELECT platform_name 
        FROM platforms_data 
        WHERE api_service_code = '$service_key'
        AND api_id = '$api_id'
    ");

    $service_row = mysqli_fetch_assoc($sql_get_service_name);

    if (!$service_row) {
        continue;
    }

    $service_name = $service_row['platform_name'];

    // Check if service exists
    $existing_service_sql = mysqli_query($conn, "SELECT id FROM service WHERE service_id = '$service_key' AND server_id = '$server_id'");

    if (mysqli_num_rows($existing_service_sql) == 0) {
        mysqli_query($conn, "INSERT INTO service (service_name, service_id, server_id, service_price, status) 
            VALUES ('$service_name', '$service_key', '$server_id', '$service_price_naira', 1)");
    } else {
        $existing_service_id = mysqli_fetch_assoc($existing_service_sql)['id'];
        mysqli_query($conn, "UPDATE service SET service_name='$service_name', service_price='$service_price_naira' WHERE id='$existing_service_id'");
    }
}

echo "Finished importing services with cheapest price";
