<?php
require_once __DIR__ . '/../../include/config.php';

$socials = [];

$q = mysqli_query($conn, "
  SELECT id, name, image, starred
  FROM boosting_social_media
  WHERE status = 1
  ORDER BY starred DESC;
");

while ($row = mysqli_fetch_assoc($q)) {
  $socials[] = [
    'id' => (int)$row['id'],
    'name' => $row['name'],
    'image' => $row['image'],
    'is_starred' => (int)$row['starred'],
  ];
}

echo json_encode($socials);
