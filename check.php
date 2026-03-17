<?php
// echo "PHP OK";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read raw POST body
$webhookData = file_get_contents("php://input");

// Decode JSON
$data = json_decode($webhookData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "INVALID JSON";
    exit;
}

// Connect to database using your real credentials
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=otpsms;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo "DB CONNECTION FAILED: " . $e->getMessage();
    exit;
}

// Save raw webhook data
// $stmt = $pdo->prepare("INSERT INTO verify_payment (`key`) VALUES (:key)");
// $stmt->execute([':key' => $webhookData]);

// Extract relevant data
$activationId = $data['activationId'] ?? null;
$code = $data['code'] ?? null;

if (!$activationId || !$code) {
    echo "MISSING DATA";
    exit;
}

// Update order table
// $stmt = $pdo->prepare("UPDATE `active_number` SET sms_text = :code WHERE active_status = :status number_id = :activationId");
// $stmt->execute([':code' => $code, ':activationId' => $activationId]);

// Prepare and execute UPDATE to set active_status = 2
$stmt = $pdo->prepare("
    UPDATE `active_number` 
    SET sms_text = :code, active_status = 2
    WHERE active_status = :status AND number_id = :activationId
");

$stmt->execute([
    ':code' => $code,
    ':status' => 2,              // current status you want to match
    ':activationId' => $activationId
]);

// Optional: check how many rows were updated
$rowsUpdated = $stmt->rowCount();
if ($rowsUpdated > 0) {
    echo "active_status updated to 2 successfully.";
} else {
    echo "No matching number_id found.";
}

// echo "SUCCESS";
exit;
