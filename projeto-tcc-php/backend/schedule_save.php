<?php
require __DIR__ . '/config.php';
$userId = require_login();

$body = json_input();
$slotKey = (string) ($body['slot_key'] ?? '');
$subject = (string) ($body['subject'] ?? '');

if ($slotKey === '' || $subject === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO schedule (user_id, slot_key, subject) VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE subject = VALUES(subject), updated_at = CURRENT_TIMESTAMP'
);
$stmt->execute([$userId, $slotKey, $subject]);

echo json_encode(['success' => true]);
