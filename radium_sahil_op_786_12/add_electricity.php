<?php
include("auth.php");
if (!isset($_SESSION['admin'])) exit;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Electricity Provider</title>
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
                                Add Electricity Provider
                            </li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form Card -->
                            <div class="card" id="loading">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Provider Name</label>
                                        <input type="text" id="name" class="form-control" placeholder="e.g Ikeja Electricity">
                                    </div>

                                    <div class="form-group">
                                        <label>Disco ID</label>
                                        <input type="number" id="disco_id" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </div>

                                    <button id="submit" class="btn btn-primary w-100">
                                        Add Provider
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
        $("#submit").click(function() {
            Notiflix.Block.Dots('#loading', 'Please wait...');
            $.post("ajax/add_electricity.php", {
                name: $("#name").val(),
                disco_id: $("#disco_id").val(),
                status: $("#status").val()
            })
            .done(function() {
                Notiflix.Notify.Success('Provider added successfully!');
            })
            .fail(function() {
                Notiflix.Notify.Failure('Failed to add provider!');
            })
            .always(function() {
                Notiflix.Block.Remove('#loading');
            });
        });
    </script>

</body>
</html>
