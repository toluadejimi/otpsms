<?php
require_once __DIR__ . '/../../include/config.php';

// Fetch all active API providers
$api_providers = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE status = 1");

while ($api_data = mysqli_fetch_assoc($api_providers)) {
    $api_id = (int)$api_data['id'];
    $api_key = $api_data['api_key'];
    $api_url = rtrim($api_data['api_url'], '/');
    $api_rate = (float)$api_data['api_rate'];
    $api_percentage_increase = (float)$api_data['api_percentage_increase'];
    $api_currency = $api_data['currency'] ?? 'USD';

    echo "Processing API ID {$api_id}...\n";

    /**
     * 1. Fetch and Update Balance
     */
    $balance_url = "{$api_url}/api/v2";
    $balance_data = [
        'key' => $api_key,
        'action' => 'balance'
    ];

    $ch_balance = curl_init();
    curl_setopt_array($ch_balance, [
        CURLOPT_URL => $balance_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($balance_data),
        CURLOPT_TIMEOUT => 30
    ]);
    $balance_response = curl_exec($ch_balance);

    if ($balance_response !== false) {
        $balance_json = json_decode($balance_response, true);
        if (isset($balance_json['balance'])) {
            $balance_usd = (float)$balance_json['balance'];
            $balance_converted = round($balance_usd * $api_rate, 2);

            mysqli_query($conn, "UPDATE boosting_api_providers SET balance = '$balance_converted' WHERE id = '$api_id'");
            echo " → Balance updated: \${$balance_usd} → ₦{$balance_converted}\n";
        } else {
            echo " → Failed to fetch balance: invalid format.\n";
        }
    } else {
        echo " → Balance fetch error: " . curl_error($ch_balance) . "\n";
    }
    curl_close($ch_balance);


    /**
     * 2. Fetch and Update Existing Services
     */
    $url = "{$api_url}/api/v2";
    $post_fields = [
        'key' => $api_key,
        'action' => 'services'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($post_fields),
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        echo " → Error fetching services: " . curl_error($ch) . "\n";
        curl_close($ch);
        continue;
    }
    curl_close($ch);

    $services = json_decode($response, true);
    if (!is_array($services)) {
        echo " → Invalid services response.\n";
        continue;
    }

    // Get existing services for this API
    $db_services_res = mysqli_query($conn, "SELECT id, api_service_id FROM boosting_services WHERE api_provider_id = '$api_id'");
    $db_services = [];
    while ($row = mysqli_fetch_assoc($db_services_res)) {
        $db_services[(int)$row['api_service_id']] = (int)$row['id'];
    }

    $current_api_service_ids = [];

    foreach ($services as $service) {
        $api_service_id = (int)$service['service'];
        $current_api_service_ids[] = $api_service_id;

        // Skip if service not already in DB
        if (!isset($db_services[$api_service_id])) {
            continue;
        }

        $service_id = $db_services[$api_service_id];

        $category_name = mysqli_real_escape_string($conn, $service['category']);
        $service_name = mysqli_real_escape_string($conn, $service['name']);
        $service_type = mysqli_real_escape_string($conn, $service['type']);
        $min = (int)$service['min'];
        $max = (int)$service['max'];
        $refill = !empty($service['refill']) ? 1 : 0;
        $refill_type = $refill;
        $dripfeed = isset($service['dripfeed']) ? (int)$service['dripfeed'] : 0;
        $base_rate = (float)$service['rate'];

        if ($api_currency === 'USD') {
        // USD → NGN
            $original_price = round($base_rate * $api_rate, 2);

            $rate_with_increase = $base_rate + (($api_percentage_increase / 100) * $base_rate);
            $price_naira = round($rate_with_increase * $api_rate, 2);
        } else {
            // NGN (or any non-USD currency) → already final
            $original_price = round($base_rate, 2);

            $rate_with_increase = $base_rate + (($api_percentage_increase / 100) * $base_rate);
            $price_naira = round($rate_with_increase, 2);
        }

        // Get or insert category (optional)
        $category_check = mysqli_query($conn, "SELECT id FROM boosting_categories WHERE name = '$category_name' LIMIT 1");
        if (mysqli_num_rows($category_check) > 0) {
            $category_id = mysqli_fetch_assoc($category_check)['id'];
        } else {
            mysqli_query($conn, "INSERT INTO boosting_categories (api_provider_id, name, `desc`, status) VALUES ('$api_id', '$category_name', '', 1)");
            $category_id = mysqli_insert_id($conn);
        }

        // Update service
        $update_sql = "
            UPDATE boosting_services SET 
                cate_id = '$category_id',
                name = '$service_name',
                `desc` = '$service_type',
                price = '$price_naira',
                original_price = '$original_price',
                refill = '$refill',
                refill_type = '$refill_type',
                min = '$min',
                max = '$max',
                type = '$service_type',
                dripfeed = '$dripfeed',
                status = 1
            WHERE id = '$service_id'
        ";
        mysqli_query($conn, $update_sql);
    }

    // Deactivate services removed from API
    $api_service_ids_in_response = array_map('intval', $current_api_service_ids);
    $existing_ids = array_keys($db_services);
    $to_deactivate = array_diff($existing_ids, $api_service_ids_in_response);

    if (!empty($to_deactivate)) {
        $ids_to_deactivate = implode(",", array_map('intval', array_map(fn($sid) => $db_services[$sid], $to_deactivate)));
        $deactivate_sql = "UPDATE boosting_services SET status = 0 WHERE id IN ($ids_to_deactivate)";
        mysqli_query($conn, $deactivate_sql);
        echo " → Deactivated " . count($to_deactivate) . " removed services.\n";
    }

    echo " → Updated " . count($current_api_service_ids) . " services for API ID {$api_id}.\n\n";
}

echo "✅ Cron complete.\n";
