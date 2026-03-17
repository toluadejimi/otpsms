<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}
if ($_GET['id'] == "") {
    echo "invalid id";
    return;
} else {
    $id = $_GET['id'];
}
$sql = mysqli_query($conn, "SELECT * FROM boosting_categories WHERE id='" . $id . "'");
if (mysqli_num_rows($sql) == 0) {
    echo "invalid id";
    return;
}
$category_data = mysqli_fetch_assoc($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Boosting Category - @radiumsahil</title>
    <?php include("include/head.php"); ?>
</head>

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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Log Catgeory</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form Basic -->
                            <div class="card mb-4" id="loading">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
                                </div>
                                <div class="card-body">
                                    <!-- <form id="updateProductForm" enctype="multipart/form-data" onsubmit="submitUpdateProductForm(event)"> -->
                                    <form id="updateProductForm">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Priority</label>
                                            <input type="number" class="form-control" min="0" value="<?php echo $category_data['stt']; ?>" id="priority">
                                            <i class="lh-1" style="font-size: 12px; margin-top: 5px;">Note: The higher the priority, the higher the product will appear at the top.</i>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" id="name" value="<?php echo $category_data['name']; ?>" placeholder="Enter Category Name">
                                            <input type="hidden" id="id" value="<?php echo $category_data['id']; ?>">
                                        </div>
                                        <button type="button" id="update" class="btn btn-primary w-100 mb-2">Update</button>
                                    </form>
                                </div>

                                <!---Container Fluid-->
                            </div>
                            <!-- Footer -->
                            <?php include("include/copyright.php"); ?>
                            <!-- Footer -->
                        </div>
                    </div>

                    <!-- Scroll to top -->
                    <a class="scroll-to-top rounded" href="#page-top">
                        <i class="fas fa-angle-up"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include("include/script.php"); ?>
    <script>
        $("#update").click(function() {
            Notiflix.Block.Dots('#loading', 'Please Wait');
            var name = $("#name").val();
            var priority = $("#priority").val();
            var id = $("#id").val(); 
            var params = {
                stt: priority,
                name: name,
                id: id,
            };
    
            $.ajax({
                type: "POST",
                url: "ajax/edit_log_category.php",
                data: params,
                error: function (e) {
                    console.log(e);
                },
                success: function (data) {
                       Notiflix.Block.Remove('#loading');
                 $('#update').html(data);
                    $('#update').html("Update");
    
                }
            });
        });
    </script>

</body>

</html>