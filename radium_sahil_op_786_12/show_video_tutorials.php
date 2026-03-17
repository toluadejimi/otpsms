<?php
include("auth.php");

// Handle visibility toggle (POST)
if (isset($_POST['toggle_visibility'])) {
    $tutorialId = intval($_POST['tutorial_id']);
    
    $res = mysqli_query($conn, "SELECT visibility FROM video_tutorials WHERE id = $tutorialId");
    $row = mysqli_fetch_assoc($res);
    $current = $row['visibility'] ?? 'public';
    $newVisibility = $current === 'public' ? 'private' : 'public';

    $update = mysqli_query($conn, "UPDATE video_tutorials SET visibility='$newVisibility' WHERE id=$tutorialId");
    if ($update) {
        echo '<div class="alert alert-success">Visibility updated successfully.</div>';
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo '<div class="alert alert-danger">Failed to update visibility.</div>';
    }
}

// Fetch all video tutorials
$sql = mysqli_query($conn, "SELECT * FROM video_tutorials ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Video Tutorials</title>
    <?php include("include/head.php"); ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .media-preview, .thumb-preview {
            max-width: 150px;
            height: auto;
        }
        .caption-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
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
                        <li class="breadcrumb-item active">Video Tutorials</li>
                    </ol>
                    <a href="add_video_tutorial.php" class="btn btn-sm btn-primary">Add Video Tutorial</a>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Video Tutorials List</h6>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table align-items-center table-flush" id="dataTable">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Video</th>
                                        <th>Title</th>
                                        <th>Caption</th>
                                        <th>Visibility</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($tutorial = mysqli_fetch_assoc($sql)):
                                        $videoPath = "../uploads/video_tutorials/" . htmlspecialchars($tutorial['video_path']);
                                        $thumbPath = "../uploads/video_tutorials/thumbnails/" . htmlspecialchars($tutorial['thumbnail'] ?? 'default.png');
                                        $visibility = $tutorial['visibility'];
                                        $created = $tutorial['created_at'];
                                        $tutorialId = $tutorial['id'];
                                    ?>
                                        <tr id="tutorialRow_<?= $tutorialId ?>">
                                            <td>
                                                <img src="<?= $thumbPath ?>" class="thumb-preview rounded" alt="Thumbnail">
                                            </td>
                                            <td>
                                                <video class="media-preview" controls muted>
                                                    <source src="<?= $videoPath ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </td>
                                            <td><?= htmlspecialchars($tutorial['title']) ?></td>
                                            <td class="caption-text"><?= htmlspecialchars($tutorial['caption']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $visibility==='public'?'success':($visibility==='private'?'warning':'secondary') ?>">
                                                    <?= ucfirst($visibility) ?>
                                                </span>
                                            </td>
                                            <td><?= $created ?></td>
                                            <td>
                                                <a href="edit_video_tutorial.php?id=<?= $tutorialId ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="tutorial_id" value="<?= $tutorialId ?>">
                                                    <button type="submit" name="toggle_visibility" class="btn btn-sm btn-<?= $visibility==='public'?'danger':'success' ?>">
                                                        <?= $visibility==='public'?'Make Private':'Make Public' ?>
                                                    </button>
                                                </form>
                                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $tutorialId ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteTutorialModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">Are you sure you want to delete this tutorial?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                                    Confirm
                                    <span class="spinner-border spinner-border-sm ml-2" style="display: none;"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- /container-wrapper -->
        </div> <!-- /content -->
    </div> <!-- /content-wrapper -->
</div> <!-- /wrapper -->

<?php include("include/script.php"); ?>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();

    let selectedTutorialId = null;

    // Open delete modal
    $('.delete-btn').on('click', function() {
        selectedTutorialId = $(this).data('id');
        $('#deleteTutorialModal').modal('show');
    });

    // Confirm delete
    $('#confirmDeleteBtn').on('click', function() {
        if (!selectedTutorialId) return;

        const btn = $(this);
        const spinner = btn.find('.spinner-border');
        btn.prop('disabled', true);
        spinner.show();

        $.ajax({
            url: 'ajax/delete_video_tutorial.php',
            type: 'POST',
            data: { id: selectedTutorialId },
            success: function(response) {
                spinner.hide();
                btn.prop('disabled', false);
                $('#deleteTutorialModal').modal('hide');

                if (response.trim() === 'success') {
                    $('#tutorialRow_' + selectedTutorialId).fadeOut(400, function(){ $(this).remove(); });
                    selectedTutorialId = null;
                } else {
                    alert('Failed to delete tutorial: ' + response);
                }
            },
            error: function() {
                spinner.hide();
                btn.prop('disabled', false);
                $('#deleteTutorialModal').modal('hide');
                alert('Server error.');
            }
        });
    });
});
</script>
</body>
</html>