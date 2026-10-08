<?php
require __DIR__ . '/config.php';
$userId = require_login();

$stmt = $pdo->prepare('DELETE FROM schedule WHERE user_id = ?');
$stmt->execute([$userId]);

echo json_encode(['success' => true]);
