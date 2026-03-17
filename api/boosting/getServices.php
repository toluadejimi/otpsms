<?php
require_once __DIR__ . '/../../include/config.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$services = [];

if ($category_id > 0) {
  $query = mysqli_query(
    $conn,
    "SELECT id, name, `desc`, price, min, max, refill, dripfeed
     FROM boosting_services
     WHERE cate_id = '$category_id'
       AND status = 1
     ORDER BY name ASC"
  );

  while ($row = mysqli_fetch_assoc($query)) {
    $services[] = [
      'id'       => (int)$row['id'],
      'name'     => $row['name'],
      'desc'     => $row['desc'],
      'price'    => (float)$row['price'],
      'min'      => (int)$row['min'],
      'max'      => (int)$row['max'],
      'refill'   => (int)$row['refill'],
      'dripfeed' => (int)$row['dripfeed'],
    ];
  }
}

echo json_encode($services);
