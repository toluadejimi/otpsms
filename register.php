<?php
require_once __DIR__ . '/include/config.php';
if(isset($_SESSION['token'])){
	header('location: dashboard');
}
include_once __DIR__ . '/theam/' . THEAM . '/register.php';

?>