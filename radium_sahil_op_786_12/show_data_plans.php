<?php
include("auth.php");
if (!isset($_SESSION['admin'])) exit;

$data = mysqli_query($conn, "
    SELECT d.*, n.name AS network
    FROM data_plans d
    JOIN networks n ON n.id = d.network_id
    ORDER BY d.id DESC
");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Plans</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $("#show_vtu_management").addClass("active");
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
                    <h1 class="h3 mb-0 text-gray-800">Data Plans</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Plans</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">

                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Data Plans</h6>
                                <a class="m-0 float-right btn btn-primary btn-sm" href="add_data_plan">Add Data Plans <i class="fas fa-chevron-right"></i></a>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Network</th>
                                                <th>Plan</th>
                                                <th>Price</th>
                                                <th>Validity</th>
                                                <th>API ID</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php while ($r = mysqli_fetch_assoc($data)) { ?>
                                            <tr>
                                                <td><?= $r['network']; ?></td>
                                                <td><?= $r['plan_name']; ?> (<?= $r['plan_type']; ?>)</td>
                                                <td>₦<?= number_format($r['selling_price'], 2); ?></td>
                                                <td><?= $r['validity']; ?></td>
                                                <td><?= $r['api_plan_id']; ?></td>
                                                <td>
                                                    <a href="edit_data_plan?id=<?= $r['id']; ?>" class="btn btn-sm btn-primary">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <?php include("include/copyright.php"); ?>
            </div>
            <!-- Container Fluid-->
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
        $('#dataTable').DataTable({
            "order": []
        });
    });
</script>

</body>
</html>
