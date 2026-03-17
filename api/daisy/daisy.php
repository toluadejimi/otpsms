<?php

// Read raw POST body
$webhookData = file_get_contents("php://input");

// Decode JSON
$data = json_decode($webhookData, true);

// Optional: check if JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON']);
    exit;
}

// Database connection (example using PDO)
$pdo = new PDO(
    "mysql:host=localhost;dbname=your_database;charset=utf8mb4",
    "db_user",
    "db_password",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

// Save raw webhook data to Verify_Payment table
$stmt = $pdo->prepare("INSERT INTO verify_payment (`key`) VALUES (:key)");
$stmt->execute([
    ':key' => $webhookData
]);

// Extract relevant data
$activationId = $data['activationId'] ?? null;
$code = $data['code'] ?? null;

// Update Order table
if ($activationId && $code) {
    $stmt = $pdo->prepare(
        "UPDATE `order`
         SET info = :code
         WHERE number_id = :activationId"
    );

    $stmt->execute([
        ':code' => $code,
        ':activationId' => $activationId
    ]);
}

// Return JSON response
http_response_code(200);
echo json_encode(['message' => 'Success']);
