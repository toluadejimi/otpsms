<?php
include("auth.php");
if (!isset($_SESSION['admin'])) exit;

$cables = mysqli_query($conn, "SELECT * FROM cable_tv_providers WHERE status = 1");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Cable Plan</title>
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
                <!-- Topbar -->
                <?php include("include/topbar.php"); ?>
                <!-- Topbar -->

                <!-- Container Fluid -->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Cable Plan</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form Card -->
                            <div class="card" id="loading">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Cable</label>
                                        <select id="cable_id" class="form-control">
                                            <?php while ($c = mysqli_fetch_assoc($cables)) { ?>
                                                <option value="<?= $c['id']; ?>">
                                                    <?= $c['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Plan Name</label>
                                        <input type="text" id="plan_name" class="form-control" placeholder="e.g. DSTV Padi">
                                    </div>

                                    <div class="form-group">
                                        <label>API Plan ID</label>
                                        <input type="number" id="api_plan_id" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Cost Price</label>
                                        <input type="number" id="cost_price" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="number" id="selling_price" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </div>

                                    <button id="submit" class="btn btn-primary w-100">
                                        Add Plan
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
            $.post("ajax/add_cable_plan.php", {
                cable_id: $("#cable_id").val(),
                plan_name: $("#plan_name").val(),
                api_plan_id: $("#api_plan_id").val(),
                cost_price: $("#cost_price").val(),
                selling_price: $("#selling_price").val(),
                status: $("#status").val()
            })
            .done(function(response) {
                Notiflix.Notify.Success('Cable plan added successfully!');
            })
            .fail(function() {
                Notiflix.Notify.Failure('Failed to add cable plan!');
            })
            .always(function() {
                Notiflix.Block.Remove('#loading');
            });
        });
    </script>

</body>
</html>
