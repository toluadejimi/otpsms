-- Login broadcast message (shown once per session when user lands on dashboard).
-- Run once: mysql -u root -p otpsms < sql/site_broadcast.sql

CREATE TABLE IF NOT EXISTS site_broadcast (
  id INT PRIMARY KEY DEFAULT 1,
  title VARCHAR(255) NOT NULL DEFAULT '',
  message TEXT NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 0,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO site_broadcast (id, title, message, enabled)
VALUES (1, 'Welcome', 'Important updates will appear here. Check back often.', 0);
