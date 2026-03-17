<?php
require_once __DIR__ . '/../../include/config.php';  


/**
 * Try to detect social media from service name or category
 * Returns social_media.id or NULL
 */
function findSocialMediaId(mysqli $conn, string $serviceName, string $categoryName): ?int
{
    $serviceName  = strtolower($serviceName);
    $categoryName = strtolower($categoryName);

    $q = mysqli_query($conn, "
        SELECT id, name 
        FROM boosting_social_media 
        WHERE status = 1
        ORDER BY starred DESC, name ASC
    ");

    $othersId = null;

    while ($row = mysqli_fetch_assoc($q)) {
        $mediaName = strtolower($row['name']);

        if ($mediaName === 'others') {
            $othersId = (int)$row['id'];
            continue;
        }

        if (
            strpos($serviceName, $mediaName) !== false ||
            strpos($categoryName, $mediaName) !== false
        ) {
            return (int)$row['id'];
        }
    }

    // fallback
    return $othersId; // can be NULL if not found
}


// Validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die("Method not allowed.");
}

$api_id = intval($_POST['api_id'] ?? 0);
$services_selected = $_POST['services'] ?? [];

if ($api_id <= 0 || !is_array($services_selected)) {
    die("Invalid input.");
}

// Fetch API details
$sql_api = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE id = '$api_id' AND status = 1");
$api_data = mysqli_fetch_assoc($sql_api);
if (!$api_data) {
    die("API not found or inactive.");
}

$api_key = $api_data['api_key'];
$api_url = rtrim($api_data['api_url'], '/');
$api_rate = (float) $api_data['api_rate'];
$api_percentage_increase = (float) $api_data['api_percentage_increase'];
$api_currency = $api_data['currency'] ?? 'USD';

// Fetch all services from remote API
$url = "{$api_url}/api/v2?key=" . urlencode($api_key) . "&action=services";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
]);
$response = curl_exec($ch);
if ($response === false) {
    die("cURL error: " . curl_error($ch));
}
curl_close($ch);

$services = json_decode($response, true);
if (!is_array($services)) {
    die("Invalid API response.");
}

// Prepare lookup of selected services
$selMap = array_flip(array_map('intval', $services_selected));

// Track which API‐service IDs we processed
$current_api_service_ids = [];

foreach ($services as $s) {
    $service_id_api = (int)$s['service'];
    if (!isset($selMap[$service_id_api])) {
        // This service was not selected → skip
        continue;
    }

    $current_api_service_ids[] = $service_id_api;

    $category_name = mysqli_real_escape_string($conn, $s['category']);
    $service_name = mysqli_real_escape_string($conn, $s['name']);
    $service_type = mysqli_real_escape_string($conn, $s['type']);
    $min = (int)$s['min'];
    $max = (int)$s['max'];
    $refill = (!empty($s['refill']) && $s['refill']) ? 1 : 0;
    $refill_type = $refill;
    $dripfeed = isset($s['dripfeed']) ? (int)$s['dripfeed'] : 0;
    $base_rate = (float)$s['rate'];

    // NOTE: original_price and price are always stored in NGN
    // Conversion happens ONLY when provider currency is USD

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


    // Detect social media
    $social_media_id = findSocialMediaId($conn, $service_name, $category_name);

    // Category insert/check
    $cat_q = mysqli_query($conn, "
        SELECT id 
        FROM boosting_categories 
        WHERE api_provider_id = '$api_id' 
        AND name = '$category_name'
        LIMIT 1
    ");

    if (mysqli_num_rows($cat_q) > 0) {

        $category_id = mysqli_fetch_assoc($cat_q)['id'];

    } else {

        $social_media_sql = $social_media_id !== null
            ? "'$social_media_id'"
            : "NULL";

        mysqli_query($conn, "
            INSERT INTO boosting_categories 
            (api_provider_id, name, `desc`, status, boosting_social_media_id)
            VALUES (
                '$api_id',
                '$category_name',
                '',
                1,
                $social_media_sql
            )
        ");

        $category_id = mysqli_insert_id($conn);
    }


    // Service check
    $svc_q = mysqli_query($conn, "SELECT id FROM boosting_services WHERE api_service_id = '$service_id_api' AND api_provider_id = '$api_id' LIMIT 1");
    if (mysqli_num_rows($svc_q) > 0) {
        $svc = mysqli_fetch_assoc($svc_q);
        $service_db_id = $svc['id'];
        $upd = "UPDATE boosting_services SET 
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
            WHERE id = '$service_db_id'";
        mysqli_query($conn, $upd);
    } else {
        $ins = "INSERT INTO boosting_services (
            cate_id, name, `desc`, price, original_price, refill, refill_type, min, max, type, api_service_id, api_provider_id, dripfeed, status
        ) VALUES (
            '$category_id', '$service_name', '$service_type', '$price_naira', '$original_price', '$refill', '$refill_type', '$min', '$max', '$service_type', '$service_id_api', '$api_id', '$dripfeed', 1
        )";
        mysqli_query($conn, $ins);
    }
}

// Optionally, you can deactivate services in DB that are no longer in selected list
// if (!empty($current_api_service_ids)) {
//     $ids = implode(",", array_map('intval', $current_api_service_ids));
//     // Deactivate other services for this API
//     $deact = "UPDATE boosting_services SET status = 0 WHERE api_provider_id = '$api_id' AND api_service_id NOT IN ($ids)";
//     mysqli_query($conn, $deact);
// }

echo "Import completed successfully.";
