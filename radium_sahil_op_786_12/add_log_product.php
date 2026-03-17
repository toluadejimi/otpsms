<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    exit;
}

$categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Log Product - @radiumsahil</title>
    <?php include("include/head.php"); ?>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>
            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Log Product</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4" id="loading">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">New Log Product</h6>
                            </div>
                            <div class="card-body">
                                <form id="addProductForm" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="Enter Product Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="priority">Numerical order:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="priority" required>
                                        <i class="lh-1" style="font-size: 12px; margin-top: 5px;">Note: The higher the priority, the higher the product will appear at the top.</i>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="category" required>
                                            <option value="">Select Category</option>
                                            <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" id="price" placeholder="Product Price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" id="description" placeholder="Enter Description" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <label for="accounts" class="mb-0">Accounts</label>
                                            <a href="/demo_logs_format.txt">Demo Format</a>
                                        </div>
                                        <input type="file" class="form-control" id="accounts">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 mb-2">Add Product</button>
                                </form>
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
    $(document).ready(function () {
        $('#addProductForm').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData();
            formData.append('stt', $('#priority').val());
            formData.append('name', $('#name').val());
            formData.append('category_id', $('#category').val());
            formData.append('price', $('#price').val());
            formData.append('description', $('#description').val());

            var accountsFile = $('#accounts')[0].files[0];
            if (accountsFile) {
                formData.append('accounts', accountsFile);
            }

            Notiflix.Block.Dots('#loading', 'Please Wait');

            $.ajax({
                type: 'POST',
                url: 'ajax/add_log_product.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Notiflix.Block.Remove('#loading');

                    if (response === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: 'Product created successfully',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(function () {
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
                error: function () {
                    Notiflix.Block.Remove('#loading');
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred.',
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
