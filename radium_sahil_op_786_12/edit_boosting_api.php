<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
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

function getStatus($statusInWords){
    $result = "";

    switch($statusInWords){
        case "pending":
            $result = "2";
        break;
        case "success":
            $result = "1";
        break;
        case "rejected":
            $result = "3";
        break;
        default:
            $result = -1;
    }

    return $result;
}

function statusBadge($status) {
    switch ($status) {
        case 'Pending':
            return '<span class="badge badge-warning">Pending</span>';
        case 'Processing':
            return '<span class="badge badge-info">Processing</span>';
        case 'Completed':
            return '<span class="badge badge-success">Completed</span>';
        case 'Partial':
            return '<span class="badge badge-primary">Partial</span>';
        case 'Canceled':
            return '<span class="badge badge-danger">Canceled</span>';
        case 'Refunded':
            return '<span class="badge badge-secondary">Refunded</span>';
        default:
            return '<span class="badge badge-dark">Unknown</span>';
    }
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

function showAmount($amount) {
    return '₦' . number_format($amount, 2);
}
                                                    
function alert_back($msg){
    echo "<script>alert('{$msg}'); window.history.back();</script>";
    exit;
}

function strLimit($text, $limit = 20) {
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function getStatusBadge($status) {
    if ($status == 1) {
        return '<span class="badge badge-success">Active</span>';
    } elseif ($status == 0) {
        return '<span class="badge badge-danger">Inactive</span>';
    } else {
        return '<span class="badge badge-warning">Unknown</span>';
    }
}

$status = "";

if(isset($_GET['tab']) && !empty($_GET['tab'])){
    $status = $_GET['tab'];
    $accepted_status = ["edit", "categories", "products", "orders"];

    if(!in_array($status, $accepted_status)){
        header("Location: index.php");
    }
}else{
    header("Location: index.php");
}

if ($_GET['id'] == "") {
    alert_back("invalid id");
    return;
} else {
    $id = $_GET['id'];
}

$sql = mysqli_query($conn, "SELECT * FROM boosting_api_providers WHERE id='" . $id . "'");
if (mysqli_num_rows($sql) == 0) {
    alert_back("invalid id");
    return;
}
$api_data = mysqli_fetch_assoc($sql);

// Weekly profits
$sql_total_earnings = "SELECT SUM(price) as total FROM boosting_orders WHERE status = 'Completed' AND api_provider_id = '$id'";
$result_total_earnings = mysqli_query($conn, $sql_total_earnings);
$total_earning = mysqli_fetch_assoc($result_total_earnings)['total'] ?? 0;

// Daily profits
$sql_total_orders = "SELECT COUNT(price) as order_count FROM boosting_orders WHERE status = 'Completed' AND api_provider_id = '$id'";
$result_order_count = mysqli_query($conn, $sql_total_orders);
$total_order_count = mysqli_fetch_assoc($result_order_count)['order_count'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = check_string($_POST['name']);
    $domain = check_string($_POST['domain']);
    $price_increase_percentage = check_string($_POST['price_increase_percentage']);
    $price_rate = check_string($_POST['price_rate']);
    $currency = check_string($_POST['currency'] ?? 'USD');
    $api_key = check_string($_POST['api_key'] ?? '');
    $id = check_string($_POST['id']);

    // Check API key via cURL
    $api_url = rtrim($domain, '/') . "/api/v2";

    // Prepare cURL request to validate API key and get balance
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

    // Handle cURL errors
    if ($curl_error) {
        alert_back("Failed to connect to API: " . htmlspecialchars($curl_error));
        exit;
    }

    // Decode API response
    $api_response = json_decode($response, true);

    // Handle invalid response or invalid API key
    if (!isset($api_response['balance'])) {
        $error_msg = isset($api_response['error']) ? $api_response['error'] : 'Invalid API response. Please check your API key or URL.';
        alert_back("API Error: " . htmlspecialchars($error_msg));
        exit;
    }

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

    // Update database
    $update_sql = "
        UPDATE boosting_api_providers 
        SET 
            api_name = '$name',
            api_url = '$domain',
            api_key = '$api_key',
            api_percentage_increase = '$price_increase_percentage',
            api_rate = '$price_rate',
            balance = '$final_balance',
            currency = '$currency'
        WHERE id = '$id'
    ";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('API provider updated successfully. Balance: ₦" . number_format($converted_balance, 2) . "'); window.location='boosting_api_home.php';</script>";
        exit;
    } else {
        alert_back("Database update failed: " . mysqli_error($conn));
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>API Details - @radiumsahil</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#edit_boosting_api").addClass("active");
    });
</script>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("include/slidebar.php"); ?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include("include/topbar.php"); ?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">API Details</li>
                        </ol>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1">API Earnings</div>
                                  <div class="h5 mb-0 font-weight-bold text-primary">₦<?php echo number_format($total_earning, 2); ?></div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Monthly API Profit -->
                        <div class="col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1"><?php echo date('F'); ?> API Orders</div>
                                  <div class="h5 mb-0 font-weight-bold text-success"><?php echo $total_order_count; ?></div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-hand-holding-usd fa-2x text-success"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>

                    <!-- State Tabs -->
                     <div class="container my-4 card p-4">
                        <ul class="nav nav-pills nav-fill">
                            <li class="nav-item">
                                <a class="nav-link <?= $status === "edit"? "active bg-warning text-white" : "text-warning" ?>" href="?id=<?= $id ?>&tab=edit">Edit API</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $status === "categories"? "active bg-success text-white" : "text-success" ?>" href="?id=<?= $id ?>&tab=categories">Category Management</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $status === "products"? "active bg-danger text-white" : "text-danger" ?>" href="?id=<?= $id ?>&tab=products">Product Management</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $status === "orders"? "active bg-info text-white" : "text-info" ?>" href="?id=<?= $id ?>&tab=orders">Order Management</a>
                            </li>
                        </ul>
                     </div>
                    <!---Container Fluid-->
                    <!-- Row -->
                    <div class="row">
                        <?php
                            if($status === "edit"){
                        ?>
                            <div class="col-lg-12">
                             <div class="card mb-4" id="loading">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit API Connection</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $api_data['id'] ?>">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" value="<?= $api_data['api_name'] ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Domain</label>
                                            <input type="text" class="form-control" name="domain" value="<?= $api_data['api_url'] ?>" required>
                                        </div>
                    
                                        <div class="form-group" id="apiKeyField">
                                            <label>API Key</label>
                                            <input type="text" class="form-control" name="api_key" value="<?= $api_data['api_key'] ?>">
                                        </div>

                                        <div class="form-group" id="apiKeyField">
                                            <label>Balance</label>
                                            <input type="text" class="form-control" name="balance" value="<?= $api_data['balance'] ?>">
                                        </div>
                    
                                        <div class="form-group">
                                            <label>Automatic price increase (%)</label>
                                            <input type="number" class="form-control" name="price_increase_percentage" value="<?= $api_data['api_percentage_increase'] ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Currency</label>
                                            <?php
                                                $currency = $api_data['currency'];
                                            ?>
                                            <select name="currency" class="form-control" required>
                                                <option value="USD" <?= $currency === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                                <option value="NGN" <?= $currency === 'NGN' ? 'selected' : '' ?>>NGN (₦)</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="rateField">
                                            <label>Rate In Naira</label>
                                            <input type="number" class="form-control" name="price_rate" value="<?= $api_data['api_rate'] ?>">
                                        </div>
                    
                                        <button type="submit" class="btn btn-success w-100">Update API</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                            }elseif($status === "categories"){
                        ?>
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">API Categories</h6>
                                    </div>
                                    <div class="table-responsive p-3">
                                        <table class="table align-items-center table-flush dataTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $category_api_sql = mysqli_query($conn, "SELECT * FROM boosting_categories WHERE api_provider_id = $id");
                                                while ($category_api_data = mysqli_fetch_array($category_api_sql)) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $category_api_data['name']; ?></td>
                                                        <td><?php echo getStatusBadge($category_api_data['status']); ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" id="actionButton" data-toggle="dropdown">
                                                                  <i class="las la-ellipsis-v"></i>Action
                                                                </button>
                                                                <div class="dropdown-menu p-0">
                                                                <?php
                                                                    if($category_api_data['status'] == "1"){
                                                                ?>
                                                                  <a href="javascript:void(0)" class="dropdown-item confirmationBtn"
                                                                     data-action="change_api_item_status.php?type=boosting_category&id=<?php echo $category_api_data['id']; ?>"
                                                                     data-question="Are you sure to enable this item?">
                                                                    <i class="fas fa-eye-slash"></i> Disable
                                                                  </a>
                                                                 <?php 
                                                                    }else{
                                                                 ?>
                                                                    <a href="javascript:void(0)" class="dropdown-item confirmationBtn"
                                                                     data-action="change_api_item_status.php?type=boosting_category&id=<?php echo $category_api_data['id']; ?>"
                                                                     data-question="Are you sure to disable this item?">
                                                                    <i class="fas fa-eye"></i> Enabled
                                                                  </a>
                                                                 <?php
                                                                    }
                                                                 ?>
                                                                 <!-- <a href="edit_boosting_log_category?id=<?php echo $category_api_data['id']; ?>" class="dropdown-item">Edit</a> -->
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }elseif($status === "products"){
                        ?>
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">API products</h6>
                                    </div>
                                    <div class="table-responsive p-3">
                                        <table class="table align-items-center table-flush dataTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name | Category</th>
                                                    <th>Price | Cost</th>
                                                    <th>Minumum</th>
                                                    <th>Maximum</th>
                                                    <th>Dripfeed</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                // Products data for API
                                                $sql_qpi_products = "SELECT boosting_services.*, boosting_categories.name as category_name FROM boosting_services INNER JOIN boosting_categories ON boosting_services.cate_id = boosting_categories.id WHERE boosting_services.api_provider_id = '$id'";
                                                $result_api_products = mysqli_query($conn, $sql_qpi_products);
                                                while ($product = mysqli_fetch_array($result_api_products)) {
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <span class="d-block">Service Name: <?php echo strLimit($product['name'], 50); ?></span>
                                                            <span class="d-block">Category: <?php echo $product['category_name']; ?></span>
                                                        </td>
                                                         <td> 
                                                            <span class="d-block">Selling Price: <span class="fw-bold">₦<?php echo number_format($product['price'], 2) ?> </span></span>
                                                            <span class="d-block">API Price: ₦<?php echo number_format($product['original_price'], 2) ?> </span>
                                                        </td>
                                                        <td><?php echo $product['min']; ?></td>
                                                        <td><?php echo $product['max']; ?></td>
                                                        <td><?php echo $product['dripfeed'] == "0"? "Yes" : "No"; ?></td>
                                                        <td><?php echo getStatusBadge($product['status']); ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" id="actionButton" data-toggle="dropdown">
                                                                  <i class="las la-ellipsis-v"></i>Action
                                                                </button>
                                                                <div class="dropdown-menu p-0">
                                                                <?php
                                                                    if($product['status'] == "1"){
                                                                ?>
                                                                  <a href="javascript:void(0)" class="dropdown-item confirmationBtn"
                                                                     data-action="change_api_item_status.php?type=boosting_service&id=<?php echo $product['id']; ?>"
                                                                     data-question="Are you sure to disable this item??">
                                                                    <i class="fas fa-eye-slash"></i> Disable
                                                                  </a>
                                                                 <?php 
                                                                    }else{
                                                                 ?>
                                                                    <a href="javascript:void(0)" class="dropdown-item confirmationBtn"
                                                                     data-action="change_api_item_status.php?type=boosting_service&id=<?php echo $product['id']; ?>"
                                                                     data-question="Are you sure to disable this item?">
                                                                    <i class="fas fa-eye"></i> Enabled
                                                                  </a>
                                                                 <?php
                                                                    }
                                                                 ?>
                                                                 <!-- <a href="edit_log_products?id=<?php echo $product['id']; ?>" class="dropdown-item">Edit</a> -->
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }else{
                        ?>
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">API Orders</h6>
                                    </div>
                                    <div class="table-responsive p-3">
                                        <table class="table align-items-center table-flush dataTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Order Info</th>
                                                    <th>Target Link</th>
                                                    <th>Ordered At</th>
                                                    <th>Amount</th>
                                                    <th>Quantity</th>
                                                    <th>Status</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                // Products data for API
                                                $sql_orders = "
                                                SELECT 
                                                    bo.*, 
                                                    bo.price AS order_price, 
                                                    u.name AS user_name,
                                                    u.email AS user_email
                                                FROM boosting_orders bo
                                                JOIN user_data u ON bo.user_id = u.id
                                                WHERE bo.api_provider_id = '$id'
                                                ORDER BY bo.added_on DESC
                                                ";
                                                $result_api_orders = mysqli_query($conn, $sql_orders);
                                                while ($order = mysqli_fetch_array($result_api_orders)) {
                                                    $target_link = $order['link'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-bold"><?= htmlspecialchars($order['user_name']) ?></span><br>
                                                            <small><?= htmlspecialchars($order['user_email']) ?></small>
                                                        </td>

                                                        <td>
                                                            <span class="d-block">Order ID: #<?= $order['id'] ?></span>
                                                            <span class="d-block">API Order ID: #<?= $order['api_order_id'] ?></span>
                                                            <span class="d-block"><?= htmlspecialchars(strLimit($order['service_name'], 40)) ?></span>
                                                            <span class="d-block fw-semibold">Category: <?= htmlspecialchars($order['category_name']) ?></span>
                                                        </td>
                                                        
                                                         <td><a href="<?= $target_link ?>"><?= $target_link ?></a></td>

                                                        <td><?= date("M d, Y", strtotime($order['added_on'])) ?></td>

                                                        <td>₦<?= number_format($order['order_price'], 2) ?></td>

                                                        <td><?= htmlspecialchars($order['quantity']) ?></td>


                                                        <td><?= statusBadge($order['status']) ?></td>

                                                        <td>
                                                            <a href="boosting_order_details?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        ?>

                    </div>
                    <!-- Footer -->
                    <?php include("include/copyright.php"); ?>
                    <!-- Footer -->
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="actionModalLabel">Confirm Action</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to <span id="actionText"></span> this credit request?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmAction">
                                Confirm
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll to top -->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
            <?php include("include/script.php"); ?>
            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('.dataTable').DataTable({
                        "order": [] // This disables any default sorting
                    }); // ID From dataTable 
                    $('#dataTableHover').DataTable(); // ID From dataTable with Hover
                });
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
            <script>
                $(document).ready(function() {
                    let currentAction;
                    let currentId;
                    let currentRow;

                    $('#dataTable').on('click', '.approve-btn, .reject-btn', function() {
                        currentId = $(this).data('id');
                        currentAction = $(this).hasClass('approve-btn') ? 'approve' : 'reject';
                        currentRow = $(this).closest('tr');

                        $('#actionText').text(currentAction);
                        $('#actionModal').modal('show');
                    });

                    $('#confirmAction').on('click', function() {
                        if (currentAction === 'approve') {
                            currentRow.find('.badge').text('Approved');
                            currentRow.find('.badge').attr('class', 'badge badge-success');
                            currentRow.find('.approve-btn').prop('disabled', true);
                            currentRow.find('.reject-btn').prop('disabled', true);
                        } else {
                            currentRow.find('.badge').text('Rejected');
                            currentRow.find('.badge').attr('class', 'badge badge-danger');
                            currentRow.find('.approve-btn').prop('disabled', true);
                            currentRow.find('.reject-btn').prop('disabled', true);
                        }
                        $('#actionModal').modal('hide');
                    });

                    $('#confirmAction').on('click', function() {
                        const spinner = $(this).find('.spinner-border');
                        spinner.show();
                        $(this).prop('disabled', true);

                        $.ajax({
                            url: 'ajax/update_manual_payment.php',
                            type: 'POST',
                            data: {
                                id: currentId,
                                action: currentAction
                            },
                            success: function(response) {
                                if (currentAction === 'approve') {
                                    currentRow.find('.badge').text('Approved');
                                    currentRow.find('.badge').attr('class', 'badge badge-success');
                                } else {
                                    currentRow.find('.badge').text('Rejected');
                                    currentRow.find('.badge').attr('class', 'badge badge-danger');
                                }
                                currentRow.find('.approve-btn, .reject-btn').prop('disabled', true);
                            },
                            error: function(xhr, status, error) {
                                alert('An error occurred: ' + error);
                            },
                            complete: function() {
                                spinner.hide();
                                $('#confirmAction').prop('disabled', false);
                                $('#actionModal').modal('hide');
                            }
                        });
                    });
                });
            </script>
            <script>
                $(document).on('click', '.confirmationBtn', function (e) {
                    e.preventDefault();
                    const url = $(this).data('action');
                    const question = $(this).data('question') || "Are you sure?";
                
                    Swal.fire({
                        title: question,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if(result.isConfirmed){
                            window.location.href = url;
                        }
                    });
                });
            </script>
</body>

</html>