<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    exit;
}

// Fetch last 200 data orders with proper joins
$sql = mysqli_query($conn, "
SELECT d.*, u.email, n.name AS network, p.plan_name
FROM data_orders d
JOIN user_data u ON u.id = d.user_id
JOIN networks n ON n.id = d.network_id
JOIN data_plans p ON p.id = d.data_plan_id
ORDER BY d.id DESC
LIMIT 200
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Orders</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<script>
    $(document).ready(function() {
        $('#dashboard').removeClass("active");
        $("#data_orders").addClass("active");
    });
</script>

<body id="page-top">
    <div id="wrapper">
        <?php include("include/slidebar.php"); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include("include/topbar.php"); ?>

                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Data Orders (Last 200)</h6>
                        </div>

                        <div class="table-responsive p-3">
                            <table class="table table-flush" id="dataTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>Network</th>
                                        <th>Phone</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($sql)) {
                                        // Determine badge color and text
                                        switch ($row['status']) {
                                            case "1":
                                                $statusClass = "badge badge-success";
                                                $statusText  = "Success";
                                                break;
                                            case "2":
                                                $statusClass = "badge badge-danger";
                                                $statusText  = "Failed";
                                                break;
                                            default:
                                                $statusClass = "badge badge-warning";
                                                $statusText  = "Pending";
                                        }
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['email']); ?></td>
                                            <td><?= htmlspecialchars($row['network']); ?></td>
                                            <td><?= htmlspecialchars($row['phone']); ?></td>
                                            <td><?= htmlspecialchars($row['plan_name']); ?></td>
                                            <td>₦<?= number_format($row['amount']); ?></td>
                                            <td><?= $row['created_at']; ?></td>
                                            <td><span class="<?= $statusClass; ?>"><?= $statusText; ?></span></td>
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 25
            });
        });
    </script>
</body>
</html>
