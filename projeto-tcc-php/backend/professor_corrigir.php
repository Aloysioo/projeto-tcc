<?php
require __DIR__ . '/config.php';
require_professor($pdo);

$body = json_input();
$historicoId = (int) ($body['historico_id'] ?? 0);
$notaProfessor = $body['nota_professor'] ?? null;
$feedback = trim((string) ($body['feedback_professor'] ?? ''));

if ($historicoId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}
if ($notaProfessor !== null && ($notaProfessor < 0 || $notaProfessor > 1000)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'A nota deve ser entre 0 e 1000.']);
    exit;
}

$stmt = $pdo->prepare(
    'UPDATE simulados_historico
     SET nota_professor = ?, feedback_professor = ?, corrigido_em = NOW()
     WHERE id = ?'
);
$stmt->execute([$notaProfessor, $feedback, $historicoId]);

echo json_encode(['success' => true]);
