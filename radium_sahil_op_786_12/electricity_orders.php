<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$sql = mysqli_query($conn, "
SELECT e.*, u.email, p.name AS disco
FROM electricity_orders e
JOIN user_data u ON u.id = e.user_id
JOIN electricity_providers p ON p.id = e.electricity_provider_id
ORDER BY e.id DESC
LIMIT 200
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Electricity Orders</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $("#electricity_orders").addClass("active");
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
                            <h6 class="m-0 font-weight-bold text-primary">Electricity Orders</h6>
                        </div>

                        <div class="table-responsive p-3">
                            <table class="table table-flush" id="dataTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>Disco</th>
                                        <th>Meter</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Token</th>
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
                                            <td><?= $row['disco']; ?></td>
                                            <td><?= $row['meter_number']; ?></td>
                                            <td><?= strtoupper($row['meter_type']); ?></td>
                                            <td>₦<?= number_format($row['amount']); ?></td>
                                            <td><?= $row['token']; ?></td>
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