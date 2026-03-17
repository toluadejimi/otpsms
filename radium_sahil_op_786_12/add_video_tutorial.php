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
    <meta charset="UTF-8">
    <title>Add Video Tutorial</title>
    <?php include("include/head.php"); ?>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Add New Video Tutorial</h1>

                <div class="card mb-4" id="videoFormWrapper">
                    <div class="card-body">
                        <form id="addVideoForm" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="title">Tutorial Title</label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255">
                            </div>

                            <div class="form-group mb-3">
                                <label for="caption">Caption</label>
                                <textarea id="caption" name="caption" class="form-control" rows="5" required></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="video">Video File</label>
                                <input type="file" class="form-control-file" id="video" name="video" accept="video/*" required>
                                <div id="videoPreview" class="mt-2"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="thumbnail">Thumbnail Image</label>
                                <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*" required>
                                <div id="thumbPreview" class="mt-2"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="visibility">Visibility Status</label>
                                <select class="form-control" id="visibility" name="visibility" required>
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Submit Tutorial</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>

<script>
$(document).ready(function () {

    // Video preview
    $('#video').on('change', function () {
        const file = this.files[0];
        const preview = $('#videoPreview');
        preview.empty();
        if (!file) return;
        const url = URL.createObjectURL(file);
        if (file.type.startsWith('video/')) {
            preview.html(`<video controls style="max-height: 200px;" class="w-100">
                            <source src="${url}" type="${file.type}">
                          </video>`);
        } else {
            preview.html(`<p class="text-danger">Unsupported file type</p>`);
        }
    });

    // Thumbnail preview
    $('#thumbnail').on('change', function() {
        const file = this.files[0];
        const preview = $('#thumbPreview');
        preview.empty();
        if (!file) return;
        const url = URL.createObjectURL(file);
        if(file.type.startsWith('image/')) {
            preview.html(`<img src="${url}" class="img-fluid rounded" style="max-height:150px;">`);
        } else {
            preview.html(`<p class="text-danger">Unsupported image type</p>`);
        }
    });

    // Submit form via AJAX
    $('#addVideoForm').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        Notiflix.Block.Dots('#videoFormWrapper', 'Uploading...');

        $.ajax({
            type: 'POST',
            url: 'ajax/add_video_tutorial.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                Notiflix.Block.Remove('#videoFormWrapper');
                if (response.trim() === 'success') {
                    Swal.fire('Success', 'Video tutorial created successfully', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', response, 'error');
                }
            },
            error: function () {
                Notiflix.Block.Remove('#videoFormWrapper');
                Swal.fire('Error', 'An error occurred', 'error');
            }
        });
    });
});
</script>
</body>
</html>