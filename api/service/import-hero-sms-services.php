<?php
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../include/config.php';

    // This code is for importing all services for sms-bus. You can change the API and URL variables below to manage the active API service to import
    $api_id = "25";
    $country="187";
    $sql_api_details=mysqli_query($conn,"SELECT * FROM api_detail WHERE id='" . $api_id . "'");
    $api_data=mysqli_fetch_assoc($sql_api_details);
    $api_key = $api_data['api_key'];
    $api_name = $api_data['api_name'];
    $api_rate = $api_data['api_rate'];
    $api_percentage_increase = $api_data['api_percentage_increase'];

    $url = "https://hero-sms.com/stubs/handler_api.php?action=getServicesList&country={$country}&lang=en&api_key={$api_key}&action=getPrices";
    
    $ch = curl_init(); 
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_TIMEOUT => 30,
        CURLOPT_MAXREDIRS => 10,
    ));
    
    $response = curl_exec($ch);
    
    // print_r($response);
    // exit();
    
    if ($response === false) {
        $error_message = curl_error($ch);
        // Handle the error appropriately
        die("cURL error: $error_message");
    }
    
    // Close cURL session
    curl_close($ch);
    
    $services = json_decode($response, true);
    
    // print_r($services);
    // exit();
    
    foreach ($services[(int) $country] as $service_code => $service_info) {
        $service_cost = round((float) $service_info['cost'], 2);
        $service_country = $country;
        $service_application = $service_code;
        
        // print_r($service_cost);
        // print_r($service_country);
        // print_r($service_application);
        // exit();
        
        // According to current exchange rate, 1 Rusian Rubles = 0.011 USD
        // $service_price_in_dollars = round($service_cost * 0.011, 2);
        $service_price_in_dollars = $service_cost;
        $service_price_in_dollars_with_interest = round((($api_percentage_increase / 100) * $service_price_in_dollars) + $service_price_in_dollars, 2);
        $service_price_in_naira = $api_rate * $service_price_in_dollars_with_interest;
            
        // Checking for existing service to avoid duplicates.
        $existing_service_sql = mysqli_query($conn, "SELECT service.id AS index_service_id, otp_server.id AS index_server_id, service.*, otp_server.* FROM service INNER JOIN otp_server ON service.server_id = otp_server.id WHERE service.service_id = '$service_application' AND otp_server.server_code = '$service_country' AND otp_server.api_id = '$api_id'");
        
        $sql_get_server_id_from_country_code = mysqli_query($conn, "SELECT id FROM otp_server WHERE server_code = '$service_country' AND api_id = '$api_id'");
        $sql_get_service_name_from_application_id = mysqli_query($conn, "SELECT platform_name FROM platforms_data WHERE platform_id = '$service_application' AND api_id = '$api_id'");
        
        $server_id = mysqli_fetch_assoc($sql_get_server_id_from_country_code)['id'];
        $service_name = mysqli_fetch_assoc($sql_get_service_name_from_application_id)['platform_name'];
        
        if(mysqli_num_rows($existing_service_sql) == 0){
            print_r('INSERT INTO service (service_name, service_id, server_id, service_price, status) VALUES ("' . $service_name .'", "' . $service_application .'", "' . $server_id .'", "' . $service_price_in_naira .'", "1")');
            // exit();
            
            $insert_service_sql = mysqli_query($conn, 'INSERT INTO service (service_name, service_id, server_id, service_price, status) VALUES ("' . $service_name .'", "' . $service_application .'", "' . $server_id .'", "' . $service_price_in_naira .'", "1")');
        }else{
            $existing_service_id = mysqli_fetch_assoc($existing_service_sql)['index_service_id'];
            // print($existing_service_id);
            // exit();
            print_r('UPDATE service SET service_name = "' . $service_name . '" , service_price = "' . $service_price_in_naira .'" WHERE id = "' . $existing_service_id .'"');
            
            $update_service_sql = mysqli_query($conn, 'UPDATE service SET service_name = "' . $service_name . '", service_price = "' . $service_price_in_naira . '" WHERE id = "' . $existing_service_id .'"');
        }
    }
    
    echo "Finished importing all services";
    
    
    // print_r($countries);
?>