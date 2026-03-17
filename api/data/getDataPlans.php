<?php
require_once __DIR__ . '/../../include/config.php';

$network_id = (int) ($_GET['network_id'] ?? 0);
$plans = [];

$q = mysqli_query($conn, "
    SELECT 
        dp.id,
        dp.plan_name,
        dp.plan_type,
        dp.selling_price,
        dp.validity,
        n.name AS network_name
    FROM data_plans dp
    INNER JOIN networks n ON dp.network_id = n.id
    WHERE dp.network_id = '$network_id'
      AND dp.status = 1
      AND n.status = 1
");

while ($r = mysqli_fetch_assoc($q)) {
    $plans[] = [
        'id' => $r['id'],
        'name' => $r['plan_name'],
        'type' => $r['plan_type'],
        'price' => $r['selling_price'],
        'validity' => $r['validity'],
        'network' => $r['network_name'],
    ];
}

echo json_encode($plans);
