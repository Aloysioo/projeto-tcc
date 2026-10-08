<?php
require __DIR__ . '/config.php';
require_professor($pdo);

$sql = "
  SELECT
    u.id, u.name, u.email, u.streak_count,
    COUNT(h.id) AS total_simulados,
    ROUND(AVG(h.nota)) AS media_nota,
    MAX(h.created_at) AS ultimo_simulado
  FROM users u
  LEFT JOIN simulados_historico h ON h.user_id = u.id
  WHERE u.role = 'aluno'
  GROUP BY u.id, u.name, u.email, u.streak_count
  ORDER BY u.name ASC
";
$stmt = $pdo->query($sql);

echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
