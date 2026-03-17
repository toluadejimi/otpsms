<?php
require_once __DIR__ . '/../../include/config.php';
header('Content-Type: application/json');

$product_q = mysqli_query($conn,"SELECT price_per_star_ngn, markup_percent FROM tg_products WHERE product_type='star' AND status=1 LIMIT 1");
if(mysqli_num_rows($product_q)!=1) exit(json_encode(['status'=>500,'message'=>'Star product not configured']));
$data = mysqli_fetch_assoc($product_q);

echo json_encode(['status'=>200,'price_per_star_ngn'=>floatval($data['price_per_star_ngn']),'markup_percent'=>floatval($data['markup_percent'])]);