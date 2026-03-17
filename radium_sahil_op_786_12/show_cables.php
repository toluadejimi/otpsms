<?php
include("auth.php");
if(!isset($_SESSION['admin'])) exit;

$data = mysqli_query($conn,"SELECT * FROM cable_tv_providers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cables</title>
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

            <!-- Container Fluid -->
            <div class="container-fluid" id="container-wrapper">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Cables</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cables</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">

                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Cables</h6>
                                <a class="m-0 float-right btn btn-primary btn-sm" href="add_cable">Add Cable <i class="fas fa-chevron-right"></i></a>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>API ID</th>
                                                <th>Edit</th>
                                                <!-- <th>Delete</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php while($row=mysqli_fetch_assoc($data)){ ?>
                                            <tr>
                                                <td><?= $row['name']; ?></td>
                                                <td><?= $row['api_cable_id']; ?></td>
                                                <td>
                                                    <a href="edit_cable?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                </td>
                                                <!-- <td>
                                                    <form method="post" onsubmit="return confirm('Delete this cable?')">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <button name="delete" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td> -->
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
            <!-- Container Fluid -->

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

<?php
// Handle deletion
if(isset($_POST['delete'])){
    mysqli_query($conn,"DELETE FROM cable_tv_providers WHERE id='".$_POST['id']."'");
    echo "<meta http-equiv='refresh' content='0'>";
}
?>
