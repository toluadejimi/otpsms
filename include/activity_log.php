<?php
/**
 * Global site activity feed (deposits + purchases) for dashboard "Recent Activity".
 * Table is created automatically on first use.
 */

if (!function_exists('site_activities_ensure_table')) {
    function site_activities_ensure_table(mysqli $conn): bool
    {
        static $done = false;
        if ($done) {
            return true;
        }
        $sql = "CREATE TABLE IF NOT EXISTS `site_activities` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id` int(11) UNSIGNED NOT NULL,
            `direction` enum('credit','debit') NOT NULL,
            `activity_type` varchar(32) NOT NULL,
            `amount` decimal(14,2) NOT NULL DEFAULT 0.00,
            `status` tinyint(4) NOT NULL DEFAULT 1,
            `summary` varchar(512) NOT NULL DEFAULT '',
            `ref` varchar(191) DEFAULT NULL,
            `dedupe_key` varchar(128) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_dedupe_key` (`dedupe_key`),
            KEY `idx_created_at` (`created_at`),
            KEY `idx_user_created` (`user_id`,`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        if (!$conn->query($sql)) {
            return false;
        }
        $done = true;
        return true;
    }
}

if (!function_exists('site_activity_log')) {
    /**
     * @param array $p user_id, direction (credit|debit), activity_type, amount, status (1=ok,2=pending,3=failed),
     *               summary, ref (optional), dedupe_key (required for idempotency, e.g. user_transaction:123)
     */
    function site_activity_log(mysqli $conn, array $p): bool
    {
        if (!site_activities_ensure_table($conn)) {
            return false;
        }
        $user_id = (int)($p['user_id'] ?? 0);
        if ($user_id <= 0) {
            return false;
        }
        $direction = (($p['direction'] ?? '') === 'debit') ? 'debit' : 'credit';
        $activity_type = substr((string)($p['activity_type'] ?? 'Activity'), 0, 32);
        $amount = (float)($p['amount'] ?? 0);
        $status = (int)($p['status'] ?? 1);
        if ($status < 0 || $status > 9) {
            $status = 1;
        }
        $summary = substr((string)($p['summary'] ?? ''), 0, 512);
        $ref = substr((string)($p['ref'] ?? ''), 0, 191);
        $dedupe_key = substr((string)($p['dedupe_key'] ?? ''), 0, 128);
        if ($dedupe_key === '') {
            $dedupe_key = uniqid('act_', true);
        }

        $stmt = $conn->prepare(
            'INSERT INTO `site_activities`
            (`user_id`,`direction`,`activity_type`,`amount`,`status`,`summary`,`ref`,`dedupe_key`,`created_at`)
            VALUES (?,?,?,?,?,?,?,?,NOW())
            ON DUPLICATE KEY UPDATE `id` = `id`'
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param(
            'issdisss',
            $user_id,
            $direction,
            $activity_type,
            $amount,
            $status,
            $summary,
            $ref,
            $dedupe_key
        );
        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
    }
}
