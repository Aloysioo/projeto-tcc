<?php
require __DIR__ . '/config.php';

$body = json_input();
$name = trim((string) ($body['name'] ?? ''));
$email = trim(strtolower((string) ($body['email'] ?? '')));
$password = (string) ($body['password'] ?? '');
$inviteCode = (string) ($body['invite_code'] ?? '');

if ($name === '' || $email === '' || strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Preencha nome, e-mail e uma senha com pelo menos 8 caracteres.']);
    exit;
}
if (!preg_match('/^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/', $email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Digite um e-mail válido.']);
    exit;
}
if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'A senha precisa ter letras e números.']);
    exit;
}
if ($inviteCode !== PROFESSOR_INVITE_CODE) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Código de convite de professor inválido.']);
    exit;
}

$check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$check->execute([$email]);
if ($check->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Já existe uma conta com esse e-mail. Tente entrar.']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
$ins->execute([$name, $email, $hash, 'professor']);
$userId = (int) $pdo->lastInsertId();

$_SESSION['user_id'] = $userId;
$streak = bump_streak($pdo, $userId);

echo json_encode([
    'success' => true,
    'user' => [
        'id' => $userId,
        'name' => $name,
        'email' => $email,
        'role' => 'professor',
        'streak_count' => $streak['streak_count'],
        'last_active_date' => $streak['last_active_date'],
    ],
]);
