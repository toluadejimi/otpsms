<?php
include("auth.php");
if (!isset($_SESSION['admin'])) {
    header('location: ../index');
    return;
}

// Ensure table exists (one-time)
mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS site_broadcast (
  id INT PRIMARY KEY DEFAULT 1,
  title VARCHAR(255) NOT NULL DEFAULT '',
  message TEXT NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 0,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
)");
mysqli_query($conn, "INSERT IGNORE INTO site_broadcast (id, title, message, enabled) VALUES (1, '', '', 0)");

$row = ['title' => '', 'message' => '', 'enabled' => 0];
$q = mysqli_query($conn, "SELECT title, message, enabled FROM site_broadcast WHERE id = 1 LIMIT 1");
if ($q && $r = mysqli_fetch_assoc($q)) {
    $row = $r;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Broadcast - Admin</title>
    <?php include("include/head.php"); ?>
</head>
<script>
    $(document).ready(function() {
        $('#dashboard').removeClass('active');
        $('#broadcast').addClass('active');
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
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login Broadcast</li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4" id="loading">
                            <div class="card-header">Vital message shown to users when they log in (once per session)</div>
                            <div class="card-body">
                                <form id="broadcastForm">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" id="title" value="<?= htmlspecialchars($row['title']) ?>" placeholder="e.g. Important Update">
                                    </div>
                                    <div class="form-group">
                                        <label>Message (HTML allowed)</label>
                                        <textarea class="form-control" id="message" rows="6" placeholder="Vital information for users..."><?= htmlspecialchars($row['message']) ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="enabled" <?= !empty($row['enabled']) ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="enabled">Show broadcast to users on login</label>
                                        </div>
                                    </div>
                                    <button type="button" id="saveBtn" class="btn btn-primary">Save Broadcast</button>
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
<script>
$(document).ready(function() {
    $('#saveBtn').on('click', function() {
        var title = $('#title').val();
        var message = $('#message').val();
        var enabled = $('#enabled').prop('checked') ? 1 : 0;

        Notiflix.Block.Dots('#loading', 'Saving...');
        $.post('ajax/save_broadcast.php', { title: title, message: message, enabled: enabled }, function(res) {
            Notiflix.Block.Remove('#loading');
            if (res.status === 'ok') {
                Swal.fire('Saved', 'Broadcast updated. Users will see it on their next login (once per session).', 'success');
            } else {
                Swal.fire('Error', res.message || 'Could not save', 'error');
            }
        }, 'json').fail(function() {
            Notiflix.Block.Remove('#loading');
            Swal.fire('Error', 'Request failed', 'error');
        });
    });
});
</script>
</body>
</html>
