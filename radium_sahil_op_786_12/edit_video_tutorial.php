<?php
include("auth.php"); // ensure user is logged in

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("Invalid tutorial ID.");

// Fetch tutorial
$stmt = mysqli_prepare($conn, "SELECT * FROM video_tutorials WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tutorial = mysqli_fetch_assoc($result);
if (!$tutorial) die("Video tutorial not found.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Video Tutorial</title>
    <?php include("include/head.php"); ?>
</head>
<body id="page-top">
<div id="wrapper">
    <?php include("include/slidebar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include("include/topbar.php"); ?>
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Edit Video Tutorial</h1>

                <div class="card mb-4" id="videoFormWrapper">
                    <div class="card-body">
                        <form id="editVideoForm" enctype="multipart/form-data">
                            <input type="hidden" id="tutorial_id" value="<?= $tutorial['id'] ?>">

                            <div class="form-group mb-3">
                                <label for="title">Tutorial Title</label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255" value="<?= htmlspecialchars($tutorial['title']) ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label for="caption">Caption</label>
                                <textarea id="caption" name="caption" class="form-control" rows="5" required><?= htmlspecialchars($tutorial['caption']) ?></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="video">Video File (Leave empty to keep current)</label>
                                <input type="file" class="form-control-file" id="video" name="video" accept="video/*">
                                <div id="videoPreview" class="mt-2">
                                    <video controls style="max-height:200px;" class="w-100">
                                        <source src="../uploads/video_tutorials/<?= htmlspecialchars($tutorial['video_path']) ?>" type="video/mp4">
                                    </video>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="thumbnail">Thumbnail (Leave empty to keep current)</label>
                                <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept="image/*">
                                <div id="thumbPreview" class="mt-2">
                                    <img src="../uploads/video_tutorials/thumbnails/<?= htmlspecialchars($tutorial['thumbnail']) ?>" class="img-fluid rounded" style="max-height:150px;">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="visibility">Visibility Status</label>
                                <select class="form-control" id="visibility" name="visibility" required>
                                    <option value="public" <?= $tutorial['visibility']==='public'?'selected':'' ?>>Public</option>
                                    <option value="private" <?= $tutorial['visibility']==='private'?'selected':'' ?>>Private</option>
                                    <option value="draft" <?= $tutorial['visibility']==='draft'?'selected':'' ?>>Draft</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Tutorial</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("include/script.php"); ?>
<script>
$(document).ready(function(){

    // Video preview
    $('#video').on('change', function(){
        const file = this.files[0];
        const preview = $('#videoPreview');
        if(!file) return;
        const url = URL.createObjectURL(file);
        if(file.type.startsWith('video/')){
            preview.html(`<video controls style="max-height:200px;" class="w-100">
                            <source src="${url}" type="${file.type}">
                          </video>`);
        } else {
            preview.html(`<p class="text-danger">Unsupported file type</p>`);
        }
    });

    // Thumbnail preview
    $('#thumbnail').on('change', function(){
        const file = this.files[0];
        const preview = $('#thumbPreview');
        if(!file) return;
        const url = URL.createObjectURL(file);
        if(file.type.startsWith('image/')){
            preview.html(`<img src="${url}" class="img-fluid rounded" style="max-height:150px;">`);
        } else {
            preview.html(`<p class="text-danger">Unsupported image type</p>`);
        }
    });

    // AJAX form submit
    $('#editVideoForm').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id', $('#tutorial_id').val());

        Notiflix.Block.Dots('#videoFormWrapper', 'Updating...');

        $.ajax({
            type:'POST',
            url:'ajax/edit_video_tutorial.php',
            data: formData,
            contentType:false,
            processData:false,
            success:function(response){
                Notiflix.Block.Remove('#videoFormWrapper');
                if(response.trim() === 'success'){
                    Swal.fire('Success','Video tutorial updated successfully','success')
                        .then(()=> location.reload());
                } else {
                    Swal.fire('Error', response,'error');
                }
            },
            error:function(){
                Notiflix.Block.Remove('#videoFormWrapper');
                Swal.fire('Error','An error occurred','error');
            }
        });
    });

});
</script>
</body>
</html>