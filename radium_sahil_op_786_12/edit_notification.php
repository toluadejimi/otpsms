<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

if (empty($_GET['id'])) {
    echo "Invalid ID";
    return;
}

$id = (int)$_GET['id'];
$sql = mysqli_query($conn, "SELECT * FROM notifications WHERE id='$id'");
if (mysqli_num_rows($sql) === 0) {
    echo "Notification not found";
    return;
}

$notification = mysqli_fetch_assoc($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Notification - Admin</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/summernote/summernote-bs4.css" rel="stylesheet">
</head>

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
                        <li class="breadcrumb-item active" aria-current="page">Edit Notification</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4" id="loading">
                            <div class="card-header">Edit Notification</div>
                            <div class="card-body">
                                <form id="editNotificationForm">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" id="title" value="<?= htmlspecialchars($notification['title']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Preview</label>
                                        <input type="text" class="form-control" id="preview" value="<?= htmlspecialchars($notification['preview']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Body</label>
                                        <textarea class="form-control summernote" id="body"><?= htmlspecialchars($notification['body']) ?></textarea>
                                    </div>
                                    <input type="hidden" id="id" value="<?= $notification['id'] ?>">
                                    <button type="button" id="updateBtn" class="btn btn-primary w-100">Update Notification</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>
<script src="vendor/summernote/summernote-bs4.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({height: 200});

    $('#updateBtn').click(function() {
        Notiflix.Block.Dots('#loading', 'Please Wait');

        const id = $('#id').val();
        const title = $('#title').val();
        const preview = $('#preview').val();
        const body = $('.summernote').val();

        var formData = new FormData();
        formData.append('id', title);
        formData.append('title', title);
        formData.append('preview', preview);
        formData.append('body', body);


        $.post('ajax/edit_notification.php', {id, title, preview, body}, function(res){
            Notiflix.Block.Remove('#loading');
            if(res.status === 'ok'){
                Swal.fire('Success','Notification updated','success');
                setTimeout(()=>window.location.href='show_notifications.php', 1000);
            } else {
                Swal.fire('Error', res.message || 'Something went wrong','error');
            }
        }, 'json');

        $.ajax({
            type: 'POST',
            url: 'ajax/edit_notification.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Notiflix.Block.Remove('#loading');

                if (response === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: 'Notificaton edited successfully',
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
