<?php
include("auth.php");
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cable_tv_providers WHERE id='$id'"));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Cable</title>
    <?php include("include/head.php"); ?>
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Cable
                            </li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form Card -->
                            <div class="card" id="loading">
                                <div class="card-body">

                                    <input type="hidden" id="id" value="<?= $id ?>">

                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" id="name" value="<?= $data['name']; ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>API Cable ID</label>
                                        <input type="number" id="api_cable_id" value="<?= $data['api_cable_id']; ?>" class="form-control">
                                    </div>

                                    <button id="update" class="btn btn-primary w-100">
                                        Update
                                    </button>

                                </div>
                            </div>

                            <!-- Footer -->
                            <?php include("include/copyright.php"); ?>
                            <!-- Footer -->
                        </div>
                    </div>
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

    <script>
        $("#update").click(function() {
            Notiflix.Block.Dots('#loading', 'Updating...');
            $.post("ajax/edit_cable.php", {
                id: $("#id").val(),
                name: $("#name").val(),
                api_cable_id: $("#api_cable_id").val()
            })
            .done(function() {
                Notiflix.Notify.Success('Cable updated successfully!');
            })
            .fail(function() {
                Notiflix.Notify.Failure('Update failed!');
            })
            .always(function() {
                Notiflix.Block.Remove('#loading');
            });
        });
    </script>

</body>
</html>
