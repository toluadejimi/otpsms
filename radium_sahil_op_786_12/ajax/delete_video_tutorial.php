<?php
include("../auth.php"); // ensure user is logged in

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request!";
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo "Invalid tutorial ID.";
    exit;
}

// Get the video file path
$stmt = mysqli_prepare($conn, "SELECT video_path FROM video_tutorials WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tutorial = mysqli_fetch_assoc($result);

if (!$tutorial) {
    echo "Tutorial not found!";
    exit;
}

// Delete database record
$delete = mysqli_prepare($conn, "DELETE FROM video_tutorials WHERE id = ?");
mysqli_stmt_bind_param($delete, "i", $id);

if (!mysqli_stmt_execute($delete)) {
    echo "Failed to delete tutorial: " . mysqli_error($conn);
    exit;
}

// Delete video file from server
$videoFile = "../../uploads/video_tutorials/" . $tutorial['video_path'];
if (file_exists($videoFile)) {
    unlink($videoFile);
}

echo "success";
?>