-- Dashboard "Recent Activity" reads ONLY from this table (see class.control.php recent_activities).
-- Old history is not shown unless you backfill. Trigger new rows via payments/orders after deploy.
--
-- Optional: run manually if automatic CREATE TABLE is disabled on your host.
CREATE TABLE IF NOT EXISTS `site_activities` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
