<?php
require __DIR__ . '/config.php';
$professorId = require_professor($pdo);

$body = json_input();
$title = trim((string) ($body['title'] ?? ''));
$description = trim((string) ($body['description'] ?? ''));
$areaLabel = $body['area_label'] ?? null;

if ($title === '' || $description === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Preencha o título e a descrição da atividade.']);
    exit;
}

$stmt = $pdo->prepare('INSERT INTO activities (professor_id, title, description, area_label) VALUES (?, ?, ?, ?)');
$stmt->execute([$professorId, $title, $description, $areaLabel]);

echo json_encode(['success' => true, 'id' => (int) $pdo->lastInsertId()]);
