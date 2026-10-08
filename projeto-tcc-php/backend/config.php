<?php
declare(strict_types=1);

// ============================================================
// CONFIG — conexão com o banco + funções auxiliares
// Ajuste DB_USER e DB_PASS se o seu MySQL não usar o padrão do XAMPP
// (root sem senha). Se usar outro programa (Laragon, WAMP), confira
// o usuário/senha nas configurações dele.
// ============================================================

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');

// Se o front-end (HTML) e o back-end (PHP) rodarem em endereços
// diferentes, descomente a linha abaixo e ajuste o endereço:
// header('Access-Control-Allow-Origin: http://localhost:5500');

const DB_HOST = 'localhost';
const DB_NAME = 'ponto_de_virada';
const DB_USER = 'root';
const DB_PASS = '';

// "Senha" que a pessoa precisa saber para se cadastrar como professor.
// Troque por algo só seu e combine com os professores que vão usar o sistema.
const PROFESSOR_INVITE_CODE = 'PROFESSOR2026';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco. Confira usuário/senha em config.php.']);
    exit;
}

// Lê o corpo da requisição (JSON) enviado pelo front-end
function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

// Bloqueia o acesso se não houver login ativo, e devolve o id do usuário
function require_login(): int {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Não autenticado.']);
        exit;
    }
    return (int) $_SESSION['user_id'];
}

// Bloqueia o acesso se quem estiver logado não for professor
function require_professor(PDO $pdo): int {
    $userId = require_login();
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    if (!$row || $row['role'] !== 'professor') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso restrito a professores.']);
        exit;
    }
    return $userId;
}

// Atualiza a ofensiva (streak) de dias seguidos estudando
function bump_streak(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare('SELECT streak_count, last_active_date FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();

    $today = date('Y-m-d');
    $last = $row['last_active_date'];
    $streak = (int) $row['streak_count'];

    if ($last !== $today) {
        if ($last) {
            $diffDays = (int) round((strtotime($today) - strtotime($last)) / 86400);
            if ($diffDays === 1) {
                $streak = $streak + 1;
            } elseif ($diffDays <= 0) {
                $streak = max($streak, 1);
            } else {
                $streak = 1;
            }
        } else {
            $streak = 1;
        }
        $upd = $pdo->prepare('UPDATE users SET streak_count = ?, last_active_date = ? WHERE id = ?');
        $upd->execute([$streak, $today, $userId]);
    }

    return ['streak_count' => $streak, 'last_active_date' => $today];
}
