<?php
require __DIR__ . '/config.php';
$userId = require_login();

$stmt = $pdo->prepare('SELECT slot_key, subject FROM schedule WHERE user_id = ?');
$stmt->execute([$userId]);

echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
