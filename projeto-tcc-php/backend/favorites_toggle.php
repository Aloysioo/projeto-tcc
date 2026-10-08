<?php
require __DIR__ . '/config.php';
$userId = require_login();

$body = json_input();
$lessonId = (string) ($body['lesson_id'] ?? '');

if ($lessonId === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$check = $pdo->prepare('SELECT id FROM favorites WHERE user_id = ? AND lesson_id = ?');
$check->execute([$userId, $lessonId]);

if ($check->fetch()) {
    $del = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND lesson_id = ?');
    $del->execute([$userId, $lessonId]);
    echo json_encode(['success' => true, 'favorited' => false]);
} else {
    $ins = $pdo->prepare('INSERT INTO favorites (user_id, lesson_id) VALUES (?, ?)');
    $ins->execute([$userId, $lessonId]);
    echo json_encode(['success' => true, 'favorited' => true]);
}
