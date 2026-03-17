<?php
include("../auth.php"); // Ensure logged in

if (isset($_SESSION['admin']) == "") {
    echo 'error';
}else{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "Invalid request!";
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $caption = trim($_POST['caption'] ?? '');
    $visibility = $_POST['visibility'] ?? 'public';

    if (empty($title) || empty($caption) || empty($visibility)) {
        echo "All fields are required!";
        exit;
    }

    $allowed_visibilities = ['public','private','draft'];
    if(!in_array($visibility, $allowed_visibilities)){
        echo "Invalid visibility status!";
        exit;
    }

    // Validate video
    if(!isset($_FILES['video']) || $_FILES['video']['error']!==0){
        echo "Video file is required!";
        exit;
    }

    $videoTmp = $_FILES['video']['tmp_name'];
    $videoName = basename($_FILES['video']['name']);
    $videoExt = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));
    $allowedVideo = ['mp4','mov','webm','avi','mkv'];
    if(!in_array($videoExt,$allowedVideo)){
        echo "Unsupported video format!";
        exit;
    }

    // Validate thumbnail
    if(!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error']!==0){
        echo "Thumbnail image is required!";
        exit;
    }

    $thumbTmp = $_FILES['thumbnail']['tmp_name'];
    $thumbName = basename($_FILES['thumbnail']['name']);
    $thumbExt = strtolower(pathinfo($thumbName, PATHINFO_EXTENSION));
    $allowedThumb = ['jpg','jpeg','png','webp'];
    if(!in_array($thumbExt,$allowedThumb)){
        echo "Unsupported thumbnail format!";
        exit;
    }

    // Upload directories
    $videoDir = "../../uploads/video_tutorials/";
    $thumbDir = "../../uploads/video_tutorials/thumbnails/";
    if(!is_dir($videoDir)) mkdir($videoDir,0755,true);
    if(!is_dir($thumbDir)) mkdir($thumbDir,0755,true);

    // Generate unique file names
    $newVideo = uniqid('video_',true).".".$videoExt;
    $newThumb = uniqid('thumb_',true).".".$thumbExt;

    if(!move_uploaded_file($videoTmp, $videoDir.$newVideo)){
        echo "Failed to upload video!";
        exit;
    }

    if(!move_uploaded_file($thumbTmp, $thumbDir.$newThumb)){
        echo "Failed to upload thumbnail!";
        exit;
    }

    // Insert into DB
    $stmt = mysqli_prepare($conn, "INSERT INTO video_tutorials (title, caption, video_path, thumbnail, visibility, created_at, updated_at) VALUES (?,?,?,?,?,NOW(),NOW())");
    mysqli_stmt_bind_param($stmt, "sssss", $title, $caption, $newVideo, $newThumb, $visibility);

    if(mysqli_stmt_execute($stmt)){
        echo "success";
    }else{
        echo "DB Error: ".mysqli_error($conn);
    }
}