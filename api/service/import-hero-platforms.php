<?php
require_once __DIR__ . '/../../include/config.php';

$api_id = 25; // Hero SMS API ID

// Fetch API key from database
$sql_api_details = mysqli_query($conn, "SELECT * FROM api_detail WHERE id = '$api_id'");
$api_data = mysqli_fetch_assoc($sql_api_details);

if (!$api_data) {
    die("API details not found.");
}

$api_key = $api_data['api_key'];
$api_name = $api_data['api_name'];

// Build API URL
$url = "https://hero-sms.com/stubs/handler_api.php?action=getServicesList&lang=en&api_key={$api_key}";

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute API request
$result = curl_exec($ch);
curl_close($ch);

// Decode JSON
$response = json_decode($result, true);

// Validate response
if (!isset($response['status']) || $response['status'] !== 'success' || !isset($response['services'])) {
    die("Invalid response from Hero SMS API.");
}

// Process each service
foreach ($response['services'] as $service) {

    $platform_name = $conn->real_escape_string($service['name']);
    $platform_code = $conn->real_escape_string($service['code']);

    // Check if platform already exists for this API
    $check_sql = mysqli_query(
        $conn,
        "SELECT id FROM platforms_data 
         WHERE platform_id = '$platform_code' 
         AND api_id = '$api_id'"
    );

    if (mysqli_num_rows($check_sql) == 0) {

        $insert_sql = "
            INSERT INTO platforms_data (platform_name, platform_id, api_id)
            VALUES ('$platform_name', '$platform_code', '$api_id')
        ";

        mysqli_query($conn, $insert_sql);
    }
}

echo "Finished importing platforms from Hero SMS.";
?>
