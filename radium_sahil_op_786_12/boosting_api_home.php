<?php

include("auth.php");

if (!isset($_SESSION['admin'])) {
  header('location: ../');
  return;
}

function maskHalfText($text) {
    $len = strlen($text);
    $half = floor($len / 2);
    return substr($text, 0, $half) . str_repeat('*', $len - $half);
}

function strLimit($text, $limit = 20) {
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function showAmount($amount) {
    return '₦' . number_format((float)$amount, 2);
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

$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
$currentMonth = date('m');
$currentYear = date('Y');

// Full-time profits
$sql_full_time = "SELECT SUM(price) as total FROM boosting_orders WHERE status = 'Completed' AND api_provider_id != 0";
$result_full_time = mysqli_query($conn, $sql_full_time);
$full_time_profits = mysqli_fetch_assoc($result_full_time)['total'] ?? 0;

// Monthly profits
$sql_month = "SELECT SUM(price) as total FROM boosting_orders WHERE status = 'Completed' AND api_provider_id != 0 AND MONTH(added_on) = '$currentMonth' AND YEAR(added_on) = '$currentYear'";
$result_month = mysqli_query($conn, $sql_month);
$monthly_profits = mysqli_fetch_assoc($result_month)['total'] ?? 0;

// Weekly profits
$sql_week = "SELECT SUM(price) as total FROM boosting_orders WHERE status = 'Completed' AND api_provider_id != 0 AND DATE(added_on) BETWEEN '$startOfWeek' AND '$endOfWeek'";
$result_week = mysqli_query($conn, $sql_week);
$weekly_profits = mysqli_fetch_assoc($result_week)['total'] ?? 0;

// Daily profits
$sql_day = "SELECT SUM(price) as total FROM boosting_orders WHERE status = 'Completed' AND api_provider_id != 0 AND DATE(added_on) = '$today'";
$result_day = mysqli_query($conn, $sql_day);
$daily_profits = mysqli_fetch_assoc($result_day)['total'] ?? 0;

$sql_api_provider = "SELECT * FROM boosting_api_providers";
$boosting_api_providers = mysqli_query($conn, $sql_api_provider);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Boosting API - @radiumsahil</title>
  <?php include("include/head.php"); ?>
</head>

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
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Boosting API Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <!-- API Full-time Profit -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Full-time API Profits</div>
                      <div class="h5 mb-0 font-weight-bold text-primary">₦<?php echo number_format($full_time_profits, 2); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Monthly API Profit -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1"><?php echo date('F'); ?> API Profit</div>
                      <div class="h5 mb-0 font-weight-bold text-success">₦<?php echo number_format($monthly_profits, 2); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-alt fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Weekly API Profit -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">API Profits This Week</div>
                      <div class="h5 mb-0 font-weight-bold text-warning">₦<?php echo number_format($weekly_profits, 2); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-week fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Daily API Profit -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">API Profits Today</div>
                      <div class="h5 mb-0 font-weight-bold text-danger">₦<?php echo number_format($daily_profits, 2); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar-day fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- API Configuration -->
            <!-- <div class="col-lg-12">
                 Form Basic
                <div class="card mb-4" id="loading">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">API Configuration</h6>
                    </div>
                    <div class="card-body">
                        <form id="updateProductForm" enctype="multipart/form-data" onsubmit="submitUpdateProductForm(event)">
                        <form id="updateApiConfig">
                            <div class="form-group mb-2">
                                <label>Status</label>
                                <select class="form-control" name="status_connect_api">
                                    <option <?php echo $site_data['status_connect_api'] == 1 ? 'selected' : '' ?> value="1">ON
                                    </option>
                                    <option <?php echo $site_data['status_connect_api'] == 0 ? 'selected' : '' ?> value="0">OFF
                                    </option>
                                </select>
                                <i style="font-size: 12px; margin-top: 5px;">ON/OFF API product connection function.</i>
                            </div>
                            <div class="form-group">
                                <label>Default Category and Product status when connecting to API</label>
                                <select class="form-control" name="default_api_product_status">
                                    <option <?php echo $site_data['default_api_product_status'] == 1 ? 'selected' : '' ?>
                                        value="1">Show</option>
                                    <option <?php echo $site_data['default_api_product_status'] == 0 ? 'selected' : '' ?>
                                        value="0">Hide</option>
                                </select>
                                <i class="lh-1" style="font-size: 12px; margin-top: 5px;">If you select Hidden, the
                                    products when you connect to the default API will be hidden
                                    from the website.</i>
                            </div>
                            <div class="form-group">
                                <button type="button" id="updateConfig" class="btn btn-primary w-100 mb-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
            
            <!-- API LISTS -->
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">API LIST</h6>
                  <a class="m-0 float-right btn btn-primary btn-sm" href="add_boosting_api">Add Boosting API <i class="fas fa-chevron-right"></i></a>
                </div>
                <div class="table-responsive">
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Balance</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th>Operation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      while ($apiProvider = mysqli_fetch_array($boosting_api_providers)) {
                      ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo htmlspecialchars($apiProvider['api_name']); ?></td>
                            <td><?php echo htmlspecialchars($apiProvider['api_url']); ?></td>
                            <td><?php echo showAmount($apiProvider['balance']); ?></td>
                            <td><?php echo !empty($apiProvider['api_key'])? strLimit($apiProvider['api_key']) : ""; ?></td>
                            <td><?php echo getStatusBadge($apiProvider['status']); ?></td>
                            <td>
                              <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" id="actionButton" data-toggle="dropdown">
                                  <i class="las la-ellipsis-v"></i>Action
                                </button>
                                <div class="dropdown-menu p-0">
                                  <a href="edit_boosting_api.php?id=<?php echo $apiProvider['id']; ?>&tab=edit" class="dropdown-item">
                                    <i class="la la-pencil"></i> Edit
                                  </a>
                                  <a href="import_boosting_services.php?id=<?php echo $apiProvider['id']; ?>" class="dropdown-item">
                                    <i class="la la-pencil"></i> Import Services
                                  </a>
                                  <!-- <a href="javascript:void(0)" class="dropdown-item confirmationBtn"-->
                                  <!--   data-action="delete_api.php?id=<?php echo $apiProvider['id']; ?>"-->
                                  <!--   data-question="Are you sure to remove this API? Doing this would delete all API products and its categories from the site.">-->
                                  <!--  <i class="la la-eye-slash"></i> Remove-->
                                  <!--</a> -->
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
                <div class="card-footer"></div>
              </div>
            </div>
            <!--Row-->
          </div>

          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include("include/copyright.php"); ?>
        <!-- Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php include("include/script.php"); ?>
    <script>
        $("#updateConfig").click(function() {
            Notiflix.Block.Dots('#loading', 'Please Wait');
            var status_connect_api = $("select[name='status_connect_api']").val();
            var default_api_product_status = $("select[name='default_api_product_status']").val(); 
            var params = {
                status_connect_api: status_connect_api,
                default_api_product_status: default_api_product_status,
            };
    
            $.ajax({
                type: "POST",
                url: "ajax/edit_api_config.php",
                data: params,
                error: function (e) {
                    console.log(e);
                },
                success: function (data) {
                       Notiflix.Block.Remove('#loading');
                 $('#updateApiConfig').html(data);
                    $('#updateConfig').html("Submit");
                }
            });
        });
        
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
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>

</body>

</html>