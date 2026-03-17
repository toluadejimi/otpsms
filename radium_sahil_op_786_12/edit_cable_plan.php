<?php
include("auth.php");
if (!isset($_SESSION['admin'])) exit;

$id = $_GET['id'];

// Fetch the cable plan
$plan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM cable_tv_plans WHERE id='$id'")
);

// Fetch all active cable providers
$cables = mysqli_query($conn, "SELECT * FROM cable_tv_providers WHERE status = 1");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Cable Plan</title>
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
                                Edit Cable Plan
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
                                        <label>Cable</label>
                                        <select id="cable_id" class="form-control">
                                            <?php while ($c = mysqli_fetch_assoc($cables)) { ?>
                                                <option value="<?= $c['id']; ?>" <?= $plan['cable_id'] == $c['id'] ? 'selected' : ''; ?>>
                                                    <?= $c['name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Plan Name</label>
                                        <input type="text" id="plan_name" value="<?= $plan['plan_name']; ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>API Plan ID</label>
                                        <input type="number" id="api_plan_id" value="<?= $plan['api_plan_id']; ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Cost Price</label>
                                        <input type="number" id="cost_price" value="<?= $plan['cost_price']; ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="number" id="selling_price" value="<?= $plan['selling_price']; ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select id="status" class="form-control">
                                            <option value="1" <?= $plan['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?= $plan['status'] == 0 ? 'selected' : ''; ?>>Disabled</option>
                                        </select>
                                    </div>

                                    <button id="update" class="btn btn-primary w-100">Update Plan</button>

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
            $.post("ajax/edit_cable_plan.php", {
                id: $("#id").val(),
                cable_id: $("#cable_id").val(),
                plan_name: $("#plan_name").val(),
                api_plan_id: $("#api_plan_id").val(),
                cost_price: $("#cost_price").val(),
                selling_price: $("#selling_price").val(),
                status: $("#status").val()
            })
            .done(function() {
                Notiflix.Notify.Success('Cable plan updated successfully!');
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
