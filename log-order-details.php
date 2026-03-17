<?php
require_once __DIR__ . '/include/config.php';
if(!isset($_SESSION['token'])){
	header('location: index');
}
if(!isset($_GET['order_id'])){
	header('location: number-log-orders');
}
$wallet = new radiumsahil();
$userdata = $wallet->userdata();
$userwallet = $wallet->userwallet();
if($userdata===false){
unset($_SESSION['token']);
session_destroy();
	header('location: index');	
return;	
}elseif($userdata == "otp"){
	header('location: otp');	
return;	
}
$product_ordered = $wallet->product_ordered_detail($_GET['order_id'], $userdata['id']);
if(!$product_ordered){
	header('location: log-orders');
}                             
$wallet->closeConnection();
include_once __DIR__ . '/theam/' . THEAM . '/log-order-details.php';
