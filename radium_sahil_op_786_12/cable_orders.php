<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$sql = mysqli_query($conn, "
SELECT c.*, u.email, p.plan_name, cp.name AS provider
FROM cable_tv_orders c
JOIN user_data u ON u.id = c.user_id
JOIN cable_tv_plans p ON p.id = c.cable_tv_plan_id
JOIN cable_tv_providers cp ON cp.id = c.cable_provider_id
ORDER BY c.id DESC
LIMIT 200
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cable TV Orders</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $("#cable_orders").addClass("active");
    });
</script>

<body id="page-top">
    <div id="wrapper">
        <?php include("include/slidebar.php"); ?>

        <div id="content-wrapper">
            <div id="content">
                <?php include("include/topbar.php"); ?>

                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Cable TV Orders</h6>
                        </div>

                        <div class="table-responsive p-3">
                            <table class="table table-flush" id="dataTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>Provider</th>
                                        <th>Plan</th>
                                        <th>Decoder</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($sql)) {
                                        $status = $row['status'] == "1" ? "badge badge-success" : "badge badge-danger";
                                        $text   = $row['status'] == "1" ? "Success" : "Failed";
                                    ?>
                                        <tr>
                                            <td><?= $row['email']; ?></td>
                                            <td><?= $row['provider']; ?></td>
                                            <td><?= $row['plan_name']; ?></td>
                                            <td><?= $row['smartcard_number']; ?></td>
                                            <td>₦<?= number_format($row['amount']); ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><span class="<?= $status; ?>"><?= $text; ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php include("include/copyright.php"); ?>
            </div>
        </div>
    </div>

    <?php include("include/script.php"); ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#dataTable').DataTable();
    </script>
</body>

</html>