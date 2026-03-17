<?php
require_once __DIR__ . '/../../include/config.php';

$providers = [];

$q = mysqli_query($conn, "
  SELECT id, api_name 
  FROM boosting_api_providers 
  WHERE status = 1
  ORDER BY api_name ASC
");

while ($row = mysqli_fetch_assoc($q)) {
  $providers[] = [
    'id' => (int)$row['id'],
    'api_name' => $row['api_name']
  ];
}

echo json_encode($providers);
