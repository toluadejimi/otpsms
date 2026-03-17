<?php
require_once __DIR__ . '/include/config.php';
if(!isset($_SESSION['token'])){
	header('Location: ' . rtrim(WEBSITE_URL, '/') . '/');
	exit;
}
$wallet = new radiumsahil();
$data = $wallet->balancedata();
$userdata = $wallet->userdata();
$userwallet = $wallet->userwallet();
$referwallet = $wallet->refer_data();
$recent_history = $wallet->recent_history();
$unread_notifications=$wallet->unread_notifications_count();
if($data===false){
	unset($_SESSION['token']);
	session_destroy();
	header('Location: ' . rtrim(WEBSITE_URL, '/') . '/');
	exit;
}

$wallet->closeConnection();
include_once __DIR__ . '/theam/' . THEAM . '/dashboard.php';
?>