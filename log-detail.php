<?php
require_once __DIR__ . '/include/config.php';
if(!isset($_SESSION['token'])){
	header('location: index');
}
if(!isset($_GET['id'])){
	header('location: buy-logs');
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
// Fetch Log Details
$product = $wallet->log_detail($_GET['id']);
$wallet->closeConnection();
include_once __DIR__ . '/theam/' . THEAM . '/log-detail.php';
?>