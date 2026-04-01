<?php
require_once __DIR__ . '/include/config.php';
if (!isset($_SESSION['token'])) {
    header('location: index');
    exit;
}

$wallet = new radiumsahil();
$userdata = $wallet->userdata();
if ($userdata === false) {
    unset($_SESSION['token']);
    session_destroy();
    header('location: index');
    exit;
}

$user_id = (int)($userdata['id'] ?? 0);
$virtualCard = null;

if ($user_id > 0) {
    // Ensure table exists (lightweight, future-proof for real API wiring)
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `virtual_cards` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` int(11) UNSIGNED NOT NULL,
        `card_token` varchar(191) NOT NULL,
        `last4` varchar(8) NOT NULL,
        `currency` varchar(8) NOT NULL DEFAULT 'NGN',
        `status` varchar(16) NOT NULL DEFAULT 'active',
        `nickname` varchar(64) DEFAULT NULL,
        `created_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_user` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $vcq = mysqli_query(
        $conn,
        "SELECT * FROM virtual_cards WHERE user_id = '{$user_id}' ORDER BY id DESC LIMIT 1"
    );
    if ($vcq && $vcq->num_rows > 0) {
        $virtualCard = $vcq->fetch_assoc();
    }
}

$wallet->closeConnection();
include_once __DIR__ . '/theam/' . THEAM . '/virtual-card.php';

