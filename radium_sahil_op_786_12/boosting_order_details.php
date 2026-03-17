<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    exit;
}

if (!isset($_GET['order_id'])) {
    header('location: ../index');
    exit;
}

$order_id = intval($_GET['order_id']); // sanitize input

$sql = mysqli_query($conn, "
    SELECT 
        bo.api_provider_id AS api_id,
        bo.*, 
        u.name AS user_name, 
        u.email AS user_email
    FROM boosting_orders bo
    JOIN user_data u ON bo.user_id = u.id
    WHERE bo.id = '$order_id'
");

$order = mysqli_fetch_assoc($sql);

if (!$order) {
    echo "<h4 class='text-danger m-4'>Order not found.</h4>";
    exit;
}

function statusBadge($status)
{
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Boosting Order Details - Admin</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $("#show_number_log_orders").addClass("active");
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
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Boosting Order Details</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Boosting Order ID: #<?= $order['id'] ?></h6>
                                </div>

                                <div class="table-responsive p-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>User</th>
                                                <td>
                                                    <strong><?= htmlspecialchars($order['user_name']) ?></strong><br>
                                                    <small><?= htmlspecialchars($order['user_email']) ?></small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Service</th>
                                                <td><?= htmlspecialchars($order['service_name']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Category</th>
                                                <td><?= htmlspecialchars($order['category_name']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Quantity</th>
                                                <td><?= number_format($order['quantity']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Price</th>
                                                <td>₦<?= number_format($order['price'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Start Counter</th>
                                                <td><?= htmlspecialchars($order['start_counter'] ?? "0") ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remains</th>
                                                <td><?= htmlspecialchars($order['remains'] ?? "0") ?></td>
                                            </tr>
                                            <tr>
                                                <th>Link</th>
                                                <td>
                                                    <a href="<?= htmlspecialchars($order['link']) ?>" target="_blank"><?= htmlspecialchars($order['link']) ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td><?= statusBadge($order['status']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Order Date</th>
                                                <td><?= date("M d, Y H:i A", strtotime($order['added_on'])) ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="edit_boosting_api?id=<?= $order['api_id'] ?>&tab=orders" class="btn btn-secondary mt-3">Back to Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php //include("include/copyright.php"); ?>
                </div>
            </div>

            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
            <?php include("include/script.php"); ?>
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
        </div>
    </div>
</body>

</html>
