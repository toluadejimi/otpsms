<?php
require_once __DIR__ . '/../../include/config.php';

$provider_id = isset($_GET['provider_id']) ? (int)$_GET['provider_id'] : 0;
$social_id   = isset($_GET['social_media_id']) ? (int)$_GET['social_media_id'] : 0;

$categories = [];

if ($provider_id > 0) {

  $whereSocial = '';
  if ($social_id > 0) {
    // Match tagged categories OR untagged ones
    $whereSocial = "AND (boosting_social_media_id = '$social_id' OR boosting_social_media_id IS NULL)";
  }

  $query = mysqli_query(
    $conn,
    "SELECT id, name
     FROM boosting_categories
     WHERE api_provider_id = '$provider_id'
       AND status = 1
       $whereSocial
     ORDER BY name ASC"
  );

  while ($row = mysqli_fetch_assoc($query)) {
    $categories[] = [
      'id'   => (int)$row['id'],
      'name' => $row['name'],
    ];
  }
}

echo json_encode($categories);
