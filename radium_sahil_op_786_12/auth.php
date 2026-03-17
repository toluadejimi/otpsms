<?php
require_once __DIR__ . '/../include/config.php';

// Admin area guard:
// If user is logged in (has session token) and the token belongs to an admin user,
// ensure $_SESSION['admin'] is set so admin pages don't bounce incorrectly.
if (empty($_SESSION['admin']) && !empty($_SESSION['token']) && isset($conn)) {
    $token_safe = mysqli_real_escape_string($conn, (string) $_SESSION['token']);
    $sql = $conn->query("
        SELECT u.type
        FROM login_token lt
        JOIN user_data u ON u.id = lt.user_id
        WHERE lt.token = '$token_safe' AND lt.status = '1'
        LIMIT 1
    ");
    if ($sql && $row = $sql->fetch_assoc()) {
        $type = strtolower(trim((string) ($row['type'] ?? '')));
        if ($type === 'admin') {
            $_SESSION['admin'] = $_SESSION['token'];
        }
    }
}

?>