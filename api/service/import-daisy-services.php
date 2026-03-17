<?php
    require_once __DIR__ . '/../../include/config.php';
    $api_id = 22;
    // This code is for importing all services for Daisy SMS. You can change the API and URL variables below to manage the active API service to import
    $sql_api_details=mysqli_query($conn,"SELECT * FROM api_detail WHERE id='" . $api_id . "'");
    $api_data=mysqli_fetch_assoc($sql_api_details);
    $api_key = $api_data['api_key'];
    $api_name = $api_data['api_name'];
    $api_rate = (float) $api_data['api_rate'];
    $api_percentage_increase = (float) $api_data['api_percentage_increase'];

    $url = "https://daisysms.com/stubs/handler_api.php?api_key={$api_key}&action=getPrices";
    
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
    
    if ($response === false) {
        $error_message = curl_error($ch);
        // Handle the error appropriately
        die("cURL error: $error_message");
    }
    
    // Close cURL session
    curl_close($ch);

    
    $services = json_decode($response, true);
    
    foreach ($services['187'] as $key => $value) {
        $service_cost = (float) $value['cost'];
        $service_country = '187';
        $service_application = $key;
        $service_name = $value['name'];
        
        // According to current exchange rate, 1 Rusian Rubles = 0.011 USD
        // $service_price_in_dollars = round($service_cost * 0.011, 2);
        $service_price_in_dollars = $service_cost;
        // Interest is 100%
        $service_price_in_dollars_with_interest = round((($api_percentage_increase/100) * $service_price_in_dollars) + $service_price_in_dollars, 2);
        // $service_price_in_naira = 1500 * $service_price_in_dollars_with_interest;
        $service_price_in_naira = $api_rate * $service_price_in_dollars_with_interest;
            
        // Checking for existing service to avoid duplicates.
        $existing_service_sql = mysqli_query($conn, "SELECT service.id AS index_service_id, otp_server.id AS index_server_id, service.*, otp_server.* FROM service INNER JOIN otp_server ON service.server_id = otp_server.id WHERE service.service_id = '$service_application' AND otp_server.server_code = '$service_country' AND otp_server.api_id = '$api_id'");
        
        $sql_get_server_id_from_country_code = mysqli_query($conn, "SELECT id FROM otp_server WHERE server_code = '$service_country' AND api_id = '$api_id'");
        
        $server_id = mysqli_fetch_assoc($sql_get_server_id_from_country_code)['id'];
            
        if(mysqli_num_rows($existing_service_sql) == 0){
            // print_r('INSERT INTO service (service_name, service_id, server_id, service_price, status) VALUES ("' . $service_name .'", "' . $service_application .'", "' . $server_id .'", "' . $service_price_in_naira .'", "1")');
            // exit();
            
            $insert_service_sql = mysqli_query($conn, 'INSERT INTO service (service_name, service_id, server_id, service_price, status) VALUES ("' . $service_name .'", "' . $service_application .'", "' . $server_id .'", "' . $service_price_in_naira .'", "1")');
        }else{
            $existing_service_id = mysqli_fetch_assoc($existing_service_sql)['index_service_id'];

        //   print_r('UPDATE service SET service_name = "' . $service_name . '" , service_price = "' . $service_price_in_naira .'" WHERE id = "' . $existing_service_id .'"');
            
            $update_service_sql = mysqli_query($conn, 'UPDATE service SET service_name = "' . $service_name . '", service_price = "' . $service_price_in_naira . '" WHERE id = "' . $existing_service_id .'"');
        }
    }