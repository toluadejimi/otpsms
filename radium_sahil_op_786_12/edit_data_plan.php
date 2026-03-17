<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM data_plans WHERE id='$id'")
);

$networks = mysqli_query($conn, "SELECT id, name FROM networks WHERE status = 1");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Data Plan</title>
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

            <div class="container-fluid" id="container-wrapper">

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Edit Data Plan</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Data Plan</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-6">

                        <div class="card" id="loading">
                            <div class="card-body">

                                <input type="hidden" id="id" value="<?= $data['id']; ?>">

                                <div class="form-group">
                                    <label>Network</label>
                                    <select id="network_id" class="form-control">
                                        <option value="">-- Select Network --</option>
                                        <?php while ($row = mysqli_fetch_assoc($networks)) { ?>
                                            <option value="<?= $row['id']; ?>"
                                                <?= ($row['id'] == $data['network_id']) ? 'selected' : ''; ?>>
                                                <?= $row['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Plan Type</label>
                                    <input type="text" id="plan_type" class="form-control"
                                           value="<?= $data['plan_type']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Plan Name</label>
                                    <input type="text" id="plan_name" class="form-control"
                                           value="<?= $data['plan_name']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>API Plan ID</label>
                                    <input type="number" id="api_plan_id" class="form-control"
                                           value="<?= $data['api_plan_id']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Cost Price</label>
                                    <input type="number" step="0.01" id="cost_price" class="form-control"
                                           value="<?= $data['cost_price']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Selling Price</label>
                                    <input type="number" step="0.01" id="selling_price" class="form-control"
                                           value="<?= $data['selling_price']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Validity</label>
                                    <input type="text" id="validity" class="form-control"
                                           value="<?= $data['validity']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="status" class="form-control">
                                        <option value="1" <?= $data['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?= $data['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>

                                <button id="update" class="btn btn-primary w-100">
                                    Update Data Plan
                                </button>

                            </div>
                        </div>

                        <?php include("include/copyright.php"); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>

<script>
    $("#update").click(function () {

        Notiflix.Block.Dots('#loading', 'Updating...');

        $.post("ajax/edit_data_plan.php", {
            id: $("#id").val(),
            network_id: $("#network_id").val(),
            plan_type: $("#plan_type").val(),
            plan_name: $("#plan_name").val(),
            api_plan_id: $("#api_plan_id").val(),
            cost_price: $("#cost_price").val(),
            selling_price: $("#selling_price").val(),
            validity: $("#validity").val(),
            status: $("#status").val()
        })
        .done(function () {
            Notiflix.Notify.Success('Data plan updated successfully!');
        })
        .fail(function () {
            Notiflix.Notify.Failure('Update failed!');
        })
        .always(function () {
            Notiflix.Block.Remove('#loading');
        });
    });
</script>

</body>
</html>
