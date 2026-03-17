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
$sql = mysqli_query($conn, "SELECT * FROM products WHERE id='" . $id . "'");
if (mysqli_num_rows($sql) == 0) {
    echo "invalid id";
    return;
}
$product_data = mysqli_fetch_assoc($sql);

$categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Logs Product - @radiumsahil</title>
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
                            <li class="breadcrumb-item active" aria-current="page">Edit Logs Product</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form Basic -->
                            <div class="card mb-4" id="loading">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Logs Product Details</h6>
                                </div>
                                <div class="card-body">
                                    <!-- <form id="updateProductForm" enctype="multipart/form-data" onsubmit="submitUpdateProductForm(event)"> -->
                                    <form id="updateProductForm" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" id="name" value="<?php echo $product_data['name']; ?>" placeholder="Enter Product Name">
                                            <input type="hidden" id="product_id" value="<?php echo $product_data['id']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="priority">Numerical order:</label>
                                            <input type="number" min="0" class="form-control" id="priority" value="<?php echo $product_data['stt']; ?>" required>
                                            <i class="lh-1" style="font-size: 12px; margin-top: 5px;">Note: The higher the priority, the higher the product will appear at the top.</i>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select class="form-control" id="category" required>
                                                <option value="">Select Category</option>
                                                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $product_data['category_id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($cat['name']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Price</label>
                                            <input type="number" class="form-control" id="price" value="<?php echo $product_data['price']; ?>" placeholder="Product Price">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Description</label>
                                            <input type="text" class="form-control" id="description" value="<?php echo $product_data['description']; ?>" placeholder="Enter Description">
                                        </div>
                                        <?php 
                                            if($product_data['api_id'] == 0){
                                        ?>
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <label for="exampleInputPassword1" class="mb-0">Accounts</label>
                                                    <a href="/demo_logs_format.txt">Demo Format</a>
                                                </div>
                                                <input type="file" class="form-control" id="accounts">
                                            </div>
                                        <?php
                                            }
                                        ?>
                                        <?php if ($product_data['api_id'] != 0) { ?>
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" id="update_price_from_api" <?php echo ($product_data['update_price_from_api']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="update_price_from_api">Update price from API</label>
                                            </div>
                                        <?php } ?>
                                        <button type="submit" id="update" class="btn btn-primary w-100 mb-2">Update</button>
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
        $(document).ready(function() {
            // Form submission using AJAX
            $('#updateProductForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                var formData = new FormData();
                formData.append('id', $('#product_id').val());
                formData.append('stt', $('#priority').val());
                formData.append('name', $('#name').val());
                formData.append('category_id', $('#category').val());
                formData.append('price', $('#price').val());
                formData.append('description', $('#description').val());
                
                if ($('#update_price_from_api').length) {
                    formData.append('update_price_from_api', $('#update_price_from_api').is(':checked') ? 1 : 0);
                }
                
                <?php 
                    if($product_data['api_id'] == 0){
                ?>
                    var accountsFile = $('#accounts')[0].files[0];
                    if (accountsFile) {
                        formData.append('accounts', accountsFile);
                    }
                <?php
                    }
                ?>

                // Show loading state
                Notiflix.Block.Dots('#loading', 'Please Wait');

                $.ajax({
                    type: 'POST',
                    url: 'ajax/edit_log_product.php', // The URL where the request is sent
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Hide loading state
                        Notiflix.Block.Remove('#loading');

                        // Display success or failure message
                        if (response === 'success') {
                            Swal.fire({
                                title: 'Success',
                                text: 'Product details updated successfully',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    },
                    error: function() {
                        Notiflix.Block.Remove('#loading');
                        Swal.fire({
                            title: 'Error',
                            text: 'There was an error processing your request.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>