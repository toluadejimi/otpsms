<?php
include("auth.php");

if (!isset($_SESSION['token'])) {
  if (isset($_COOKIE['remember_me'])) {
    $radium_token = $_COOKIE['remember_me'];
    $_SESSION['token'] = $radium_token;
  } else {
    header('location: login');
    exit;
  }
}

function curl_get($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function check_string($str){
    return htmlspecialchars(trim($str));
}

function format_currency($amount, $hasSymbol = false, $currencyType = null){
    $result = "";
    if($hasSymbol){
        if($currencyType == "₫"){
            $result = (removeCurrencySymbol($amount) * 0.000043) / 0.000555;
        }else{
            $result = removeCurrencySymbol($amount) / 0.000555;
        }
    }else{
        if($currencyType == "₫"){
            $result = ($amount * 0.000043) / 0.000555;
        }else{
            $result = $amount / 0.000555;
        }
    }
    
    return round($result);
}

function removeCurrencySymbol($string) {
    $cleanedString = preg_replace('/[\p{Sc}]/u', '', $string);
    $cleanedString = preg_replace('/[^\d.]/', '', $cleanedString);

    return (float) $cleanedString;
}

function balance_API_SHOPCLONE7($domain, $api_key){
    $url = rtrim($domain, '/') . "/api/profile.php?api_key={$api_key}";
    return curl_get($url);
}

function alert_back($msg){
    echo "<script>alert('{$msg}'); window.history.back();</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = check_string($_POST['name']);
    $domain = check_string($_POST['domain']);
    $price_increase_percentage = check_string($_POST['price_increase_percentage']);
    $currency = check_string($_POST['currency'] ?? 'USD');
    $price_rate = check_string($_POST['price_rate']);
    $api_key = check_string($_POST['api_key'] ?? '');

    // Validate required inputs
    if (empty($name) || empty($domain)) {
        alert_back("Name and Domain are required.");
        exit;
    }

    // Make sure domain has correct API endpoint
    $api_url = rtrim($domain, '/') . "/api/v2";

    // Prepare cURL request
    $post_fields = [
        'key' => $api_key,
        'action' => 'balance'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Handle cURL error
    if ($curl_error) {
        alert_back("Failed to connect to API: " . htmlspecialchars($curl_error));
        exit;
    }

    // Decode API response
    $api_response = json_decode($response, true);

    // Validate balance
    if (!isset($api_response['balance'])) {
        $error_msg = isset($api_response['error']) ? $api_response['error'] : 'Invalid API response. Please check your API key or URL.';
        alert_back("API Error: " . htmlspecialchars($error_msg));
        exit;
    }

    // Convert balance from USD to NGN
    $api_balance = floatval($api_response['balance']);
    $rate = floatval($price_rate);

    // Currency handling
    if ($currency === 'USD') {
        // Convert USD → NGN
        $final_balance = $api_balance * $rate;
    } else {
        // Store balance as-is for non-USD currencies
        $final_balance = $api_balance;
    }

    // Insert into database
    $insert_sql = "
        INSERT INTO boosting_api_providers 
        (api_name, api_url, api_key, api_percentage_increase, api_rate, balance, currency)
        VALUES 
        ('$name', '$domain', '$api_key', '$price_increase_percentage', '$price_rate', '$final_balance', '$currency')
    ";

    if (mysqli_query($conn, $insert_sql)) {
        echo "<script>alert('API provider added successfully. Balance: ₦" . number_format($final_balance, 2) . "'); window.location='boosting_api_home.php';</script>";
        exit;
    } else {
        alert_back("Insertion failed: " . mysqli_error($conn));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Boosting API Connection - @radiumsahil</title>
    <?php include("include/head.php"); ?>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>

            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Add New Boosting API Connection</h1>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4" id="loading">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add New API</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="Boosting API" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Domain <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="domain" placeholder="https://domain.com" required>
                                    </div>

                                    <div class="form-group">
                                        <label>API Key</label>
                                        <input type="text" class="form-control" name="api_key" placeholder="Enter your API key">
                                    </div>

                                    <div class="form-group">
                                        <label>Automatic Price Increase (%)</label>
                                        <input type="number" class="form-control" name="price_increase_percentage" placeholder="20%">
                                    </div>

                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control" required>
                                            <option value="USD">USD ($)</option>
                                            <option value="NGN">NGN (₦)</option>
                                        </select>
                                    </div>


                                    <div class="form-group" id="rateField">
                                        <label>Rate in Naira (₦)</label>
                                        <input type="number" class="form-control" name="price_rate" placeholder="1800">
                                    </div>


                                    <button type="submit" class="btn btn-success w-100">Add API</button>
                                </form>
                            </div>

                        </div>
                        <?php include("include/copyright.php"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const currencySelect = document.querySelector('select[name="currency"]');
        const rateField = document.getElementById("rateField");
        const rateInput = rateField.querySelector("input");

        function toggleRateField() {
            if (currencySelect.value === "NGN") {
                rateField.style.display = "none";
                rateInput.value = "";
                rateInput.removeAttribute("required");
            } else {
                rateField.style.display = "block";
                rateInput.setAttribute("required", "required");
            }
        }

        // Run on load
        toggleRateField();

        // Run on change
        currencySelect.addEventListener("change", toggleRateField);
    });
</script>

</body>
</html>
