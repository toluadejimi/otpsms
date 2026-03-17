<?php
require_once __DIR__ . '/../../include/config.php';

$provider_id = (int) ($_GET['provider_id'] ?? 0);
$plans = [];

$q = mysqli_query($conn, "
    SELECT id, plan_name, selling_price
    FROM cable_tv_plans
    WHERE cable_id='$provider_id' AND status=1
");

while ($r = mysqli_fetch_assoc($q)) {
    $plans[] = [
        'id' => $r['id'],
        'name' => $r['plan_name'],
        'price' => $r['selling_price'],
    ];
}

echo json_encode($plans);
