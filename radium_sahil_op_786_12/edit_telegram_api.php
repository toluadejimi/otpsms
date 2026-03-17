<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../');
    exit;
}

// ===== Helper Functions =====
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

function getOrderStatusBadge($status) {
    switch (strtolower($status)) {
        case 'pending': return '<span class="badge badge-warning">Pending</span>';
        case 'completed': return '<span class="badge badge-success">Completed</span>';
        case 'failed': return '<span class="badge badge-danger">Failed</span>';
        default: return '<span class="badge badge-secondary">Unknown</span>';
    }
}

// ===== Fetch Data =====
$provider_id = $_GET['provider_id'] ?? 0;

// Fetch provider info
$provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tg_providers WHERE id='$provider_id'"));

// Fetch products for provider
$products = mysqli_query($conn, "SELECT * FROM tg_products WHERE provider_id='$provider_id' ORDER BY id ASC");

// Fetch orders for provider
$orders = mysqli_query($conn, "SELECT * FROM tg_orders WHERE provider_id='$provider_id' ORDER BY id DESC");

// Calculate earnings
$sql_earnings = "SELECT 
                    SUM(user_charged_amount) as total,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN user_charged_amount ELSE 0 END) as daily,
                    SUM(CASE WHEN WEEK(created_at, 1) = WEEK(CURDATE(), 1) THEN user_charged_amount ELSE 0 END) as weekly,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN user_charged_amount ELSE 0 END) as monthly
                 FROM tg_orders 
                 WHERE provider_id='$provider_id' AND status='completed'";
$earnings_row = mysqli_fetch_assoc(mysqli_query($conn, $sql_earnings));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($provider['name']) ?> - TG Provider Dashboard</title>
    <?php include("include/head.php"); ?>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>

            <div class="container-fluid" id="container-wrapper">

                <!-- Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($provider['name']) ?> Dashboard</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="telegram_api_home.php">TG Providers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>

                <!-- Earnings Cards -->
                <div class="row mb-3">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Earnings</div>
                                        <div class="h5 mb-0 font-weight-bold text-primary"><?= showAmount($earnings_row['total']) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-hand-holding-usd fa-2x text-primary"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Monthly Earnings</div>
                                        <div class="h5 mb-0 font-weight-bold text-success"><?= showAmount($earnings_row['monthly']) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-success"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Weekly Earnings</div>
                                        <div class="h5 mb-0 font-weight-bold text-warning"><?= showAmount($earnings_row['weekly']) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar-week fa-2x text-warning"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Daily Earnings</div>
                                        <div class="h5 mb-0 font-weight-bold text-danger"><?= showAmount($earnings_row['daily']) ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-danger"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs for Products & Orders -->
                <ul class="nav nav-tabs mb-4" id="providerTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="orders-tab" data-toggle="tab" href="#orders" role="tab">Orders</a>
                    </li>
                </ul>
                <div class="tab-content" id="providerTabsContent">
                    <!-- Products Tab -->
                    <div class="tab-pane fade show active" id="products" role="tabpanel">
                        <div class="card">
                            <div class="card-header py-3 d-flex justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Products</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Months</th>
                                            <th>Min Qty</th>
                                            <th>Max Qty</th>
                                            <th>Markup %</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <!--<th>Actions</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; while($p=mysqli_fetch_assoc($products)) { ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= htmlspecialchars($p['product_type']) ?></td>
                                            <td><?= htmlspecialchars($p['title']) ?></td>
                                            <td><?= $p['months'] ?? '-' ?></td>
                                            <td><?= $p['min_quantity'] ?? '-' ?></td>
                                            <td><?= $p['max_quantity'] ?? '-' ?></td>
                                            <td><?= $p['markup_percent'] ?></td>
                                            <td><?= getStatusBadge($p['status']) ?></td>
                                            <td><?= $p['created_at'] ?></td>
                                            <!--<td>-->
                                            <!--    <a href="edit_tg_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>-->
                                            <!--    <a href="delete_tg_product.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger confirmationBtn">Delete</a>-->
                                            <!--</td>-->
                                        </tr>
                                    <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="card">
                            <div class="card-header py-3 d-flex justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-flush">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User ID</th>
                                            <th>Local Order ID</th>
                                            <th>Provider Order ID</th>
                                            <th>Order Type</th>
                                            <th>Username / Recipient</th>
                                            <th>Quantity</th>
                                            <th>Months</th>
                                            <th>Amount (USD)</th>
                                            <th>Charged Amount</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; while($o=mysqli_fetch_assoc($orders)) { ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $o['user_id'] ?></td>
                                            <td><?= $o['local_order_id'] ?></td>
                                            <td><?= $o['provider_order_id'] ?></td>
                                            <td><?= htmlspecialchars($o['order_type']) ?></td>
                                            <td><?= htmlspecialchars($o['username'] ?? $o['recipient_hash']) ?></td>
                                            <td><?= $o['quantity'] ?></td>
                                            <td><?= $o['months'] ?? '-' ?></td>
                                            <td><?= $o['amount_usd'] ?></td>
                                            <td><?= showAmount($o['user_charged_amount']) ?></td>
                                            <td><?= getOrderStatusBadge($o['status']) ?></td>
                                            <td><?= $o['created_at'] ?></td>
                                        </tr>
                                    <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Container Fluid-->
        </div>

        <?php include("include/copyright.php"); ?>
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
        const url = $(this).attr('href');
        Swal.fire({
            title: "Are you sure?",
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