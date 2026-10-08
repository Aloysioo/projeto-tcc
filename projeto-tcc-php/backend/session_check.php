<?php
require __DIR__ . '/config.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'user' => null]);
    exit;
}

$stmt = $pdo->prepare('SELECT id, name, email, role FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION = [];
    session_destroy();
    echo json_encode(['success' => true, 'user' => null]);
    exit;
}

$streak = bump_streak($pdo, (int) $user['id']);

echo json_encode([
    'success' => true,
    'user' => [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'] ?? 'aluno',
        'streak_count' => $streak['streak_count'],
        'last_active_date' => $streak['last_active_date'],
    ],
]);
