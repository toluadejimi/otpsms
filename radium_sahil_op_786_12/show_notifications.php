<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

$sql = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Notifications - Admin</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="vendor/summernote/summernote-bs4.min.css" rel="stylesheet">
</head>
<script>
    $(document).ready(function() {
        // Remove "active" class from all <a> elements
        $('#dashboard').removeClass("active");

        // Add "active" class to the specific element with ID "faq"
        $("#show_notifications").addClass("active");
    });
</script>

<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>

            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                    <a href="add_notification.php" class="btn btn-primary">Add Notification</a>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">All Notifications</h6>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table table-flush" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Preview</th>
                                            <th>Created At</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($sql)): ?>
                                        <tr>
                                            <td><?= $row['title'] ?></td>
                                            <td><?= $row['preview'] ?></td>
                                            <td><?= date("d/m/Y h:i A", strtotime($row['created_at'])) ?></td>
                                            <td><a href="edit_notification.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <a href="#" data-bs-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this notification? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();

    let deleteId = 0;
    $('.deleteBtn').click(function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        $.post('ajax/delete_notification.php', {id: deleteId}, function(res){
            if(res.status === 'ok'){
                location.reload();
            } else {
                alert(res.message || 'Error deleting');
            }
        }, 'json');
    });
});
</script>
</body>
</html>
