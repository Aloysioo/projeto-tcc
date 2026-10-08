<?php
require __DIR__ . '/config.php';
$userId = require_login();

// Calcula a segunda-feira desta semana às 00:00
$today = new DateTime('now');
$dayOfWeek = (int) $today->format('N'); // 1 = segunda ... 7 = domingo
$monday = (clone $today)->modify('-' . ($dayOfWeek - 1) . ' days')->setTime(0, 0, 0);

$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM simulados_historico WHERE user_id = ? AND created_at >= ?');
$stmt->execute([$userId, $monday->format('Y-m-d H:i:s')]);
$row = $stmt->fetch();

echo json_encode(['success' => true, 'count' => (int) $row['total']]);
