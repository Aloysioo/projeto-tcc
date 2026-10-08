<?php
require __DIR__ . '/config.php';
$professorId = require_professor($pdo);

$body = json_input();
$id = (int) ($body['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM activities WHERE id = ? AND professor_id = ?');
$stmt->execute([$id, $professorId]);

echo json_encode(['success' => true]);
