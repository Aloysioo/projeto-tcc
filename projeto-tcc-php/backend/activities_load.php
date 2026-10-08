<?php
require __DIR__ . '/config.php';
require_login();

$sql = "
  SELECT a.id, a.title, a.description, a.area_label, a.created_at, u.name AS professor_nome
  FROM activities a
  JOIN users u ON u.id = a.professor_id
  ORDER BY a.created_at DESC
";
$stmt = $pdo->query($sql);

echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
