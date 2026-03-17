<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../');
    return;
}

$currentMonth = date('m');
$currentYear  = date('Y');

function getMonthlyRevenue($conn, $table)
{
    $sql = mysqli_query($conn, "
        SELECT SUM(amount) AS total 
        FROM $table 
        WHERE status = 1 
        AND MONTH(created_at) = '" . date('m') . "' 
        AND YEAR(created_at) = '" . date('Y') . "'
    ");
    return mysqli_fetch_assoc($sql)['total'] ?? 0;
}

$airtimeRevenue     = getMonthlyRevenue($conn, 'airtime_orders');
$dataRevenue        = getMonthlyRevenue($conn, 'data_orders');
$cableRevenue       = getMonthlyRevenue($conn, 'cable_tv_orders');
$electricityRevenue = getMonthlyRevenue($conn, 'electricity_orders');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>VTU Management</title>
    <?php include("include/head.php"); ?>
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $('#show_vtu_management').addClass("active");
    });
</script>

<body id="page-top">
    <div id="wrapper">

        <?php include("include/slidebar.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <?php include("include/topbar.php"); ?>

                <div class="container-fluid" id="container-wrapper">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">VTU Management</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">VTU Management</li>
                        </ol>
                    </div>

                    <!-- REVENUE CARDS -->
                    <div class="row mb-4">

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase">Airtime Revenue (<?= date('F'); ?>)</div>
                                    <div class="h5 font-weight-bold text-success">₦<?= number_format($airtimeRevenue, 2); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase">Data Revenue (<?= date('F'); ?>)</div>
                                    <div class="h5 font-weight-bold text-primary">₦<?= number_format($dataRevenue, 2); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase">Cable Revenue (<?= date('F'); ?>)</div>
                                    <div class="h5 font-weight-bold text-warning">₦<?= number_format($cableRevenue, 2); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase">Electricity Revenue (<?= date('F'); ?>)</div>
                                    <div class="h5 font-weight-bold text-danger">₦<?= number_format($electricityRevenue, 2); ?></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- VTU CONFIGURATION -->
                    <div class="row">

                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header font-weight-bold text-primary">VTU Configuration</div>
                                <div class="card-body">

                                    <a href="show_networks.php" class="btn btn-outline-primary btn-block mb-2">Manage Networks</a>
                                    <a href="show_data_plans.php" class="btn btn-outline-primary btn-block mb-2">Manage Data Plans</a>
                                    <a href="show_cables.php" class="btn btn-outline-primary btn-block mb-2">Manage Cable Providers</a>
                                    <a href="show_cable_plans.php" class="btn btn-outline-primary btn-block mb-2">Manage Cable Plans</a>
                                    <a href="show_electricity.php" class="btn btn-outline-primary btn-block">Manage Electricity Providers</a>

                                </div>
                            </div>
                        </div>

                        <!-- ORDER MANAGEMENT -->
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header font-weight-bold text-success">VTU Orders</div>
                                <div class="card-body">

                                    <a href="airtime_orders.php" class="btn btn-outline-success btn-block mb-2">View Airtime Orders</a>
                                    <a href="data_orders.php" class="btn btn-outline-success btn-block mb-2">View Data Orders</a>
                                    <a href="cable_orders.php" class="btn btn-outline-success btn-block mb-2">View Cable Orders</a>
                                    <a href="electricity_orders.php" class="btn btn-outline-success btn-block">View Electricity Orders</a>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <?php include("include/copyright.php"); ?>
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include("include/script.php"); ?>
</body>

</html>