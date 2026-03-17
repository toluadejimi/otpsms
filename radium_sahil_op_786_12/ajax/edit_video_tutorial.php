<?php
include("../auth.php");
include("../db.php");

if($_SERVER['REQUEST_METHOD']!=='POST'){
    echo "Invalid request!";
    exit;
}

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$caption = trim($_POST['caption'] ?? '');
$visibility = $_POST['visibility'] ?? 'public';

if($id<=0 || empty($title) || empty($caption) || empty($visibility)){
    echo "All fields are required!";
    exit;
}

$allowed_visibilities = ['public','private','draft'];
if(!in_array($visibility,$allowed_visibilities)){
    echo "Invalid visibility status!";
    exit;
}

// Fetch existing tutorial
$stmt = mysqli_prepare($conn,"SELECT video_path, thumbnail FROM video_tutorials WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tutorial = mysqli_fetch_assoc($result);
if(!$tutorial) { echo "Video tutorial not found!"; exit; }

// Handle video upload
$newVideo = $tutorial['video_path'];
if(isset($_FILES['video']) && $_FILES['video']['error']===0){
    $videoTmp = $_FILES['video']['tmp_name'];
    $videoName = basename($_FILES['video']['name']);
    $videoExt = strtolower(pathinfo($videoName,PATHINFO_EXTENSION));
    $allowedVideo = ['mp4','mov','webm','avi','mkv'];
    if(!in_array($videoExt,$allowedVideo)){ echo "Unsupported video format!"; exit; }

    $uploadDir = "../../uploads/video_tutorials/";
    if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);

    $newVideoName = uniqid('video_',true).".".$videoExt;
    if(!move_uploaded_file($videoTmp,$uploadDir.$newVideoName)){ echo "Failed to upload video!"; exit; }

    // Delete old video
    if(file_exists($uploadDir.$tutorial['video_path'])) unlink($uploadDir.$tutorial['video_path']);

    $newVideo = $newVideoName;
}

// Handle thumbnail upload
$newThumb = $tutorial['thumbnail'];
if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error']===0){
    $thumbTmp = $_FILES['thumbnail']['tmp_name'];
    $thumbName = basename($_FILES['thumbnail']['name']);
    $thumbExt = strtolower(pathinfo($thumbName,PATHINFO_EXTENSION));
    $allowedThumb = ['jpg','jpeg','png','webp'];
    if(!in_array($thumbExt,$allowedThumb)){ echo "Unsupported thumbnail format!"; exit; }

    $thumbDir = "../../uploads/video_tutorials/thumbnails/";
    if(!is_dir($thumbDir)) mkdir($thumbDir,0755,true);

    $newThumbName = uniqid('thumb_',true).".".$thumbExt;
    if(!move_uploaded_file($thumbTmp,$thumbDir.$newThumbName)){ echo "Failed to upload thumbnail!"; exit; }

    // Delete old thumbnail
    if(file_exists($thumbDir.$tutorial['thumbnail'])) unlink($thumbDir.$tutorial['thumbnail']);

    $newThumb = $newThumbName;
}

// Update database
$stmtUpdate = mysqli_prepare($conn,"UPDATE video_tutorials SET title=?, caption=?, video_path=?, thumbnail=?, visibility=?, updated_at=NOW() WHERE id=?");
mysqli_stmt_bind_param($stmtUpdate,"sssssi",$title,$caption,$newVideo,$newThumb,$visibility,$id);

if(mysqli_stmt_execute($stmtUpdate)){
    echo "success";
}else{
    echo "DB Error: ".mysqli_error($conn);
}