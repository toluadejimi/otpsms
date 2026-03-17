<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Notification - Admin</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/summernote/summernote-bs4.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>

            <div class="container-fluid">
                <h1 class="h3 mb-2 text-gray-800">Add Notification</h1>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4" id="loading">
                            <div class="card-header">Notification Details</div>
                            <div class="card-body">
                                <form id="notificationForm">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" id="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Preview</label>
                                        <input type="text" class="form-control" id="preview" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Body</label>
                                        <textarea class="form-control summernote" id="body"></textarea>
                                    </div>
                                    <button type="button" id="submitBtn" class="btn btn-primary w-100">Add Notification</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>
<script src="vendor/summernote/summernote-bs4.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({height: 200});

    $('#submitBtn').click(function() {
        const title = $('#title').val();
        const preview = $('#preview').val();
        const body = $('.summernote').val();

        var formData = new FormData();
        formData.append('title', title);
        formData.append('preview', preview);
        formData.append('body', body);

        Notiflix.Block.Dots('#loading', 'Please Wait');

        $.ajax({
            type: 'POST',
            url: 'ajax/add_notification.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Notiflix.Block.Remove('#loading');
                response = JSON.parse(response);

                if (response.status == 'ok') {
                    Swal.fire({
                        title: 'Success',
                        text: 'Notification added successfully',
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
