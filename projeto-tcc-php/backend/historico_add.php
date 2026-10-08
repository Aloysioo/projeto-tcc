<?php
require __DIR__ . '/config.php';
$userId = require_login();

$body = json_input();
$modo = (string) ($body['modo'] ?? '');
$areaLabel = $body['area_label'] ?? null;
$acertos = (int) ($body['acertos'] ?? 0);
$total = (int) ($body['total'] ?? 0);
$nota = (int) ($body['nota'] ?? 0);
$redacaoTexto = $body['redacao_texto'] ?? null;
if ($redacaoTexto !== null) $redacaoTexto = trim((string) $redacaoTexto);
if ($redacaoTexto === '') $redacaoTexto = null;

if ($modo === '' || $total <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO simulados_historico (user_id, modo, area_label, acertos, total, nota, redacao_texto) VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$userId, $modo, $areaLabel, $acertos, $total, $nota, $redacaoTexto]);

echo json_encode(['success' => true]);
