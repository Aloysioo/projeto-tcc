<?php
require __DIR__ . '/config.php';

$body = json_input();
$email = trim(strtolower((string) ($body['email'] ?? '')));
$password = (string) ($body['password'] ?? '');

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Digite e-mail e senha.']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'E-mail ou senha incorretos.']);
    exit;
}

$_SESSION['user_id'] = (int) $user['id'];
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
