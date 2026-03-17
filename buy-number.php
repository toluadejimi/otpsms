<?php
require_once __DIR__ . '/include/config.php';
if(!isset($_SESSION['token'])){
	header('location: index');
}
$wallet = new radiumsahil();
$userdata = $wallet->userdata();
$userwallet = $wallet->userwallet();
if($userdata===false){
unset($_SESSION['token']);
session_destroy();
	header('location: index');	
}
$server = $wallet->all_server();
$wallet->closeConnection();
include_once __DIR__ . '/theam/' . THEAM . '/buy-number.php';
?>