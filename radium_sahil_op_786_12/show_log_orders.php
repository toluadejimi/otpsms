<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$final_data = [];

$result = mysqli_query($conn, "SELECT 
            p.name AS product_name, 
            o.created_at, 
            o.total_amount, 
            o.id AS order_id,
            u.name AS user_name,
            u.email AS user_email,
            COUNT(oi.id) as num_items
        FROM orders o
        INNER JOIN order_items oi ON o.id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.id
		INNER JOIN user_data u ON o.user_id = u.id
		GROUP BY p.name, o.created_at, o.total_amount, o.id, u.name, u.email
		ORDER BY o.id DESC;");
		
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $final_data[] = [
            "user_name" => $row['user_name'],
            "user_email" => $row['user_email'],
            "product_name" => $row['product_name'],
            "created_at" => $row['created_at'],
            "total_amount" => $row['total_amount'],
            "num_items" => $row['num_items'],
            "order_id" => $row['order_id'],
            "source" => "product"
        ];
    }
}

// $log_result = mysqli_query($conn, "SELECT 
//         lp.title AS product_name, 
//         lpo.created_at, 
//         lpo.total_amount, 
//         lpo.id AS order_id,
//         u.name AS user_name,
//         u.email AS user_email,
//         COUNT(lpoi.id) as num_items
//     FROM log_post_orders lpo
//     INNER JOIN log_post_order_items lpoi ON lpo.id = lpoi.log_post_order_id
//     INNER JOIN log_posts lp ON lpoi.log_post_id = lp.id
//     INNER JOIN user_data u ON lpo.user_id = u.id
//     GROUP BY lp.title, lpo.created_at, lpo.total_amount, lpo.id, u.name, u.email
// 	ORDER BY lpo.id DESC;");

// if ($log_result && mysqli_num_rows($log_result) > 0) {
//     while ($row = mysqli_fetch_assoc($log_result)) {
//         $final_data[] = [
//             "user_name" => $row['user_name'],
//             "user_email" => $row['user_email'],
//             "product_name" => $row['product_name'],
//             "created_at" => $row['created_at'],
//             "total_amount" => $row['total_amount'],
//             "num_items" => $row['num_items'],
//             "order_id" => $row['order_id'],
//             "source" => "post"
//         ];
//     }
// }

usort($final_data, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Logs Orders - @radiumsahil</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#show_number_log_orders").addClass("active");
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
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Show Log Orders</li>
                        </ol>
                    </div>

                    <!---Container Fluid-->
                    <!-- Row -->
                    <div class="row">
                        <!-- Datatables -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Show Log Orders </h6>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>User</th>
                                                <th>Product Name</th>
                                                <th>Ordered At</th>
                                                <th>Amount</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($final_data as $data) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $data['user_name']; ?><br>
                                                        <?php echo $data['user_email']; ?>
                                                    </td>
                                                    <td><?php echo $data['product_name'] ?></td>
                                                    <td><?php echo $data['created_at'] ?></td>
                                                    <td>₦<?php echo number_format($data['total_amount'], 0); ?></td>
                                                    <td><?php echo $data['num_items'] ?></td>
                                                    <td>
                                                        <?php if($data['source'] === "post"): ?>
                                                            <a href="post_log_order_details?order_id=<?php echo $data['order_id']; ?>" class="btn btn-sm btn-primary">View Accounts</a>
                                                        <?php else: ?>
                                                            <a href="log_order_details?order_id=<?php echo $data['order_id']; ?>" class="btn btn-sm btn-primary">View Accounts</a>
                                                        <?php endif; ?>
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

                    </div>
                    <!-- Footer -->
                    <?php //include("include/copyright.php");
                    ?>
                    <!-- Footer -->
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
                     $('#dataTable').DataTable({
                        "order": [] // This disables any default sorting
                    }); // ID From dataTable 
                    $('#dataTableHover').DataTable(); // ID From dataTable with Hover
                });
            </script>
</body>

</html>