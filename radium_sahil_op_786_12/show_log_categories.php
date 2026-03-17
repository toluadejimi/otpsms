<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}
$sql = mysqli_query($conn, "SELECT * FROM categories WHERE api_provider_id = 0 ORDER BY id DESC");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Logs Categories Except API - @radiumsahil</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#show_log_categories").addClass("active");
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
                            <li class="breadcrumb-item active" aria-current="page">Show Logs Categories Except API</li>
                        </ol>
                    </div>

                    <!---Container Fluid-->
                    <!-- Row -->
                    <div class="row">
                        <!-- Datatables -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Show Log Categories </h6>
                                    <a href="add_log_category" class="btn btn-sm btn-primary">Add Category</a>
                                </div>
                                <div class="table-responsive p-3">
                                    <?php


                                    if (isset($_POST['deactivate'])) {
                                        $id = $_POST['id'];
                                        $sql2 = mysqli_query($conn, "UPDATE categories SET status = 0 WHERE `id` ='" . $id . "'");
                                        echo '<div class="alert alert-success" role="alert">
       Deactivation Successful
    </div>';
                                        echo "<meta http-equiv='refresh' content='0'>";
                                    }
                                    if (isset($_POST['activate'])) {
                                        $id = $_POST['id'];
                                        $sql2 = mysqli_query($conn, "UPDATE categories SET status = 1 WHERE `id` ='" . $id . "'");
                                        echo '<div class="alert alert-success" role="alert">
       Activation Successful
    </div>';
                                        echo "<meta http-equiv='refresh' content='0'>";
                                    }
                                    ?>
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Products</th>
                                                <th>Status</th>
                                                <th>Edit</th>
                                                <th>Disable</th>
                                                <th>View Products</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($sql)) {
                                                $sql2 = mysqli_query($conn, "SELECT COUNT(*) as total_active_products FROM products WHERE status = 1 AND category_id='" . $data['id'] . "'");
                                                $sql3 = mysqli_fetch_assoc($sql2);
                                                if ($data['status'] == "1") {
                                                    $status = "badge badge-success";
                                                    $status1 = "Active";
                                                } else {
                                                    $status = "badge badge-danger";
                                                    $status1 = "Inactive";
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $data['name']; ?></td>
                                                    <td><?php echo $sql3['total_active_products'] ?? 0 ?></td>
                                                    <td><span class="<?php echo $status; ?>"><?php echo $status1; ?></span></td>
                                                    <td><a href="edit_log_category?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-primary">Edit</a></td>
                                                    <td>
                                                        <?php
                                                        if ($data['status'] == "1") {
                                                        ?>
                                                            <form method="post">
                                                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                                                <button class="btn btn-sm btn-danger" type="submit" name="deactivate">Deactivate</button>
                                                            </form>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <form method="post">
                                                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                                                <button class="btn btn-sm btn-success" type="submit" name="activate">Activate</button>
                                                            </form>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="show_log_products?category_id=<?php echo $data['id']; ?>" class="btn btn-sm btn-primary">View Products</a>
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
                    $('#dataTable').DataTable(); // ID From dataTable 
                    $('#dataTableHover').DataTable(); // ID From dataTable with Hover
                });
            </script>
</body>

</html>