<?php
include("auth.php");

if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$product_id = $_GET['id'];

// Query to fetch product details for the specific product
$sql = mysqli_query($conn, "SELECT * FROM product_details WHERE product_id='$product_id'");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product Details - @radiumsahil</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Product Details</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                        </ol>
                    </div>

                    <!---Container Fluid-->
                    <!-- Row -->
                    <div class="row">
                        <!-- Datatables -->
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
                                </div>
                                <div class="table-responsive p-3">
                                    <table class="table align-items-center table-flush" id="dataTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Detail</th>
                                                <th>Is Sold</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($data = mysqli_fetch_array($sql)) {
                                                if ($data['is_sold'] == "1") {
                                                    $status = "badge badge-success";
                                                    $status1 = "Sold";
                                                } else {
                                                    $status = "badge badge-danger";
                                                    $status1 = "Unsold";
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $data['details']; ?></td>
                                                    <td><span class="<?php echo $status; ?>"><?php echo $status1; ?></span></td>
                                                    <td>
                                                        <button class="btn btn-primary edit-btn" data-id="<?= $data['id'] ?>" data-description="<?= $data['details'] ?>">Edit</button>
                                                        <button class="btn btn-danger delete-btn" data-id="<?= $data['id'] ?>">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal for editing product details -->
            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product Detail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editProductForm">
                                <input type="hidden" id="product_id" name="product_id">
                                <div class="form-group">
                                    <label for="product_description">Description</label>
                                    <textarea class="form-control" id="product_description" name="description" required></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for confirming product deletion -->
            <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this product?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                                Confirm
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll to top -->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
            <?php include("include/script.php"); ?>
        </div>
    </div>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable(); // ID From dataTable 

            // Open modal to edit product detail
            $(document).on('click', '.edit-btn', function() {
                const productId = $(this).data('id');
                const productDescription = $(this).data('description');

                $('#editProductModal').find('#product_id').val(productId);
                $('#editProductModal').find('#product_description').val(productDescription);

                $('#editProductModal').modal('show');
            });

            // Update product detail via AJAX
            $('#saveChangesBtn').click(function() {
                const productId = $('#product_id').val();
                const productDescription = $('#product_description').val();

                $.ajax({
                    url: 'ajax/update_log_product_detail.php', // This will be the file that handles the update in the backend
                    type: 'POST',
                    data: {
                        id: productId,
                        details: productDescription
                    },
                    success: function(response) {
                        if (response === 'success') {
                            $('#editProductModal').modal('hide');
                            location.reload(); // Reload page to reflect the changes
                        } else {
                            alert('Error updating product detail');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });

            // Open modal to confirm product deletion
            $(document).on('click', '.delete-btn', function() {
                const productId = $(this).data('id');
                $('#deleteProductModal').data('id', productId); // Save product ID in modal
                $('#deleteProductModal').modal('show');
            });

            // Confirm and delete the product
            $('#confirmDeleteBtn').click(function() {
                const productId = $('#deleteProductModal').data('id');
                const spinner = $(this).find('.spinner-border');
                spinner.show();
                $(this).prop('disabled', true);

                $.ajax({
                    url: 'ajax/delete_log_product_detail.php', // Backend PHP file that handles deletion
                    type: 'POST',
                    data: {
                        id: productId
                    },
                    success: function(response) {
                        if (response === 'success') {
                            location.reload(); // Reload the page to remove the deleted product
                        } else {
                            alert('Error deleting product');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    },
                    complete: function() {
                        spinner.hide();
                        $('#confirmDeleteBtn').prop('disabled', false);
                        $('#deleteProductModal').modal('hide');
                    }
                });
            });
        });
    </script>
</body>

</html>