<?php
require __DIR__ . '/config.php';
$userId = require_login();

$stmt = $pdo->prepare('SELECT lesson_id FROM favorites WHERE user_id = ?');
$stmt->execute([$userId]);
$rows = $stmt->fetchAll();

echo json_encode(['success' => true, 'data' => array_column($rows, 'lesson_id')]);
