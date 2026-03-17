<?php
require_once __DIR__ . '/include/config.php';

// Only redirect to dashboard if we have a valid token (avoid stale session blocking login page)
if (!empty($_SESSION['token'])) {
	$wallet = new radiumsahil();
	$user_id = $wallet->check_token($_SESSION['token']);
	$wallet->closeConnection();
	if ($user_id !== false) {
		header('Location: ' . rtrim(WEBSITE_URL, '/') . '/dashboard');
		exit;
	}
	// Invalid or expired token – clear session so user can log in again
	unset($_SESSION['token']);
	unset($_SESSION['admin']);
}

include_once __DIR__ . '/theam/' . THEAM . '/index.php';

?>