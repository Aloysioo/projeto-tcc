<?php
require __DIR__ . '/config.php';
require_professor($pdo);

$alunoId = (int) ($_GET['aluno_id'] ?? 0);
if ($alunoId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Aluno inválido.']);
    exit;
}

$stmtUser = $pdo->prepare("SELECT id, name, email, streak_count, created_at FROM users WHERE id = ? AND role = 'aluno'");
$stmtUser->execute([$alunoId]);
$aluno = $stmtUser->fetch();

if (!$aluno) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Aluno não encontrado.']);
    exit;
}

$stmtHist = $pdo->prepare(
    'SELECT id, modo, area_label, acertos, total, nota, redacao_texto, nota_professor, feedback_professor, corrigido_em, created_at
     FROM simulados_historico WHERE user_id = ? ORDER BY created_at DESC'
);
$stmtHist->execute([$alunoId]);

echo json_encode([
    'success' => true,
    'aluno' => $aluno,
    'historico' => $stmtHist->fetchAll(),
]);
