<?php
require __DIR__ . '/config.php';
$userId = require_login();

$stmt = $pdo->prepare(
    'SELECT modo, area_label, acertos, total, nota, nota_professor, feedback_professor, corrigido_em, created_at
     FROM simulados_historico WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->execute([$userId]);

echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
