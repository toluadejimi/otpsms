<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$query = "SELECT * FROM products WHERE api_id = 0";

if (isset($_GET['category_id'])) {
    $query .= " AND category_id = " . $_GET['category_id'];
}

$query .= " ORDER BY id DESC";

$sql = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Logs Product Without API - @radiumsahil</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#show_number_log_products").addClass("active");
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
                            <li class="breadcrumb-item active" aria-current="page">Show Log Products</li>
                        </ol>
                    </div>

                    <!---Container Fluid-->
                    <!-- Row -->
                    <div class="row">
                        <!-- Datatables -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Show Log Products </h6>
                                    <a href="add_log_product" class="btn btn-sm btn-primary">Add New</a>
                                </div>
                                <div class="table-responsive p-3">
                                    <?php


                                    if (isset($_POST['deactivate'])) {
                                        $id = $_POST['id'];
                                        $sql2 = mysqli_query($conn, "UPDATE products SET status = 0 WHERE `id` ='" . $id . "'");
                                        echo '<div class="alert alert-success" role="alert">
       Deactivation Successful
    </div>';
                                        echo "<meta http-equiv='refresh' content='0'>";
                                    }
                                    if (isset($_POST['activate'])) {
                                        $id = $_POST['id'];
                                        $sql2 = mysqli_query($conn, "UPDATE products SET status = 1 WHERE `id` ='" . $id . "'");
                                        echo '<div class="alert alert-success" role="alert">
       Activation Successful
    </div>';
                                        echo "<meta http-equiv='refresh' content='0'>";
                                    }
                                    ?>
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>In Stock</th>
                                                <th>Status</th>
                                                <th>Edit</th>
                                                <th>Disable</th>
                                                <th>View Accounts</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($sql)) {
                                                $sql2 = mysqli_query($conn, "SELECT COUNT(*) as total_active_accounts FROM product_details WHERE is_sold = 0 AND product_id='" . $data['id'] . "'");
                                                $sql3 = mysqli_fetch_assoc($sql2);
                                                $sql4 = mysqli_query($conn, "SELECT name FROM categories WHERE id='" . $data['category_id'] . "'");
                                                $sql5 = mysqli_fetch_assoc($sql4);
                                                if ($data['status'] == "1") {
                                                    $status = "badge badge-success";
                                                    $status1 = "Active";
                                                } else {
                                                    $status = "badge badge-danger";
                                                    $status1 = "Inactive";
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $data['name'] . "<br><b>Category: </b>" . $sql5['name'] ?></td>
                                                    <td><?php echo number_format($data['price'], 0); ?></td>
                                                    <td><?php echo $sql3['total_active_accounts'] ?? 0 ?></td>
                                                    <td><span class="<?php echo $status; ?>"><?php echo $status1; ?></span></td>
                                                    <td><a href="edit_log_products?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-primary">Edit</a></td>
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
                                                        <a href="view_log_product_accounts?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-primary">View Accounts</a>
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