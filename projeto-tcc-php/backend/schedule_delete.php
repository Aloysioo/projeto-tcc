<?php
require __DIR__ . '/config.php';
$userId = require_login();

$body = json_input();
$slotKey = (string) ($body['slot_key'] ?? '');

$stmt = $pdo->prepare('DELETE FROM schedule WHERE user_id = ? AND slot_key = ?');
$stmt->execute([$userId, $slotKey]);

echo json_encode(['success' => true]);
