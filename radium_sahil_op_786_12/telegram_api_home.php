<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../');
    exit;
}

// ===== Helper Functions =====
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
    switch ($status) {
        case 1: return '<span class="badge badge-success">Active</span>';
        case 0: return '<span class="badge badge-danger">Inactive</span>';
        default: return '<span class="badge badge-warning">Unknown</span>';
    }
}

// ===== Earnings Calculations =====
$today = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
$currentMonth = date('m');
$currentYear = date('Y');

// Full-time profits
$sql_full_time = "SELECT SUM(user_charged_amount) as total FROM tg_orders WHERE status = 'Completed' AND provider_id != 0";
$result_full_time = mysqli_query($conn, $sql_full_time);
$full_time_profits = mysqli_fetch_assoc($result_full_time)['total'] ?? 0;

// Monthly profits
$sql_month = "SELECT SUM(user_charged_amount) as total FROM tg_orders WHERE status = 'Completed' AND provider_id != 0 AND MONTH(created_at) = '$currentMonth' AND YEAR(created_at) = '$currentYear'";
$result_month = mysqli_query($conn, $sql_month);
$monthly_profits = mysqli_fetch_assoc($result_month)['total'] ?? 0;

// Weekly profits
$sql_week = "SELECT SUM(user_charged_amount) as total FROM tg_orders WHERE status = 'Completed' AND provider_id != 0 AND DATE(created_at) BETWEEN '$startOfWeek' AND '$endOfWeek'";
$result_week = mysqli_query($conn, $sql_week);
$weekly_profits = mysqli_fetch_assoc($result_week)['total'] ?? 0;

// Daily profits
$sql_day = "SELECT SUM(user_charged_amount) as total FROM tg_orders WHERE status = 'Completed' AND provider_id != 0 AND DATE(created_at) = '$today'";
$result_day = mysqli_query($conn, $sql_day);
$daily_profits = mysqli_fetch_assoc($result_day)['total'] ?? 0;

// Fetch TG Providers
$sql_api_provider = "SELECT * FROM tg_providers";
$tg_providers = mysqli_query($conn, $sql_api_provider);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>TG Providers Dashboard</title>
    <?php include("include/head.php"); ?>
</head>

<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <?php include("include/slidebar.php"); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- TopBar -->
            <?php include("include/topbar.php"); ?>

            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">TG Providers Dashboard</li>
                    </ol>
                </div>

                <!-- Earnings Cards -->
                <div class="row mb-3">

                    <!-- Full-time Profits -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Full-time TG Profits</div>
                                        <div class="h5 mb-0 font-weight-bold text-primary"><?= showAmount($full_time_profits) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Profits -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1"><?= date('F') ?> TG Profit</div>
                                        <div class="h5 mb-0 font-weight-bold text-success"><?= showAmount($monthly_profits) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly Profits -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">TG Profits This Week</div>
                                        <div class="h5 mb-0 font-weight-bold text-warning"><?= showAmount($weekly_profits) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-week fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Profits -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">TG Profits Today</div>
                                        <div class="h5 mb-0 font-weight-bold text-danger"><?= showAmount($daily_profits) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-day fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TG Providers List -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">TG Providers</h6>
                                <a class="btn btn-primary btn-sm" href="add_tg_provider.php">Add TG Provider <i class="fas fa-chevron-right"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Domain</th>
                                            <th>Token</th>
                                            <th>Status</th>
                                            <th>Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; while($provider=mysqli_fetch_assoc($tg_providers)) { ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($provider['name']) ?></td>
                                            <td><?= htmlspecialchars($provider['base_url']) ?></td>
                                            <td><?= !empty($provider['api_key']) ? strLimit($provider['api_key']) : "" ?></td>
                                            <td><?= getStatusBadge($provider['status']) ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="las la-ellipsis-v"></i> Action
                                                    </button>
                                                    <div class="dropdown-menu p-0">
                                                        <a class="dropdown-item" href="edit_telegram_api.php?provider_id=<?= $provider['id'] ?>">
                                                            <i class="la la-pencil"></i> Edit
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Container Fluid-->
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
    $(document).on('click', '.confirmationBtn', function(e){
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
            if(result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>

</body>
</html>