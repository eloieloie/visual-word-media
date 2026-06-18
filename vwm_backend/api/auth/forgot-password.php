<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);
$email = trim($input['email'] ?? '');

if (!$email) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Email is required']));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid email address']));
}

$db = getDB();
$db->exec("
    CREATE TABLE IF NOT EXISTS password_resets (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        user_id     INT          NOT NULL,
        email       VARCHAR(255) NOT NULL,
        reset_token VARCHAR(64)  NOT NULL UNIQUE,
        expires_at  DATETIME     NOT NULL,
        used_at     DATETIME     NULL,
        created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_password_resets_lookup (reset_token, used_at, expires_at),
        INDEX idx_password_resets_user (user_id),
        CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$stmt = $db->prepare('SELECT id, email FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

$response = [
    'success' => true,
    'message' => 'If your email is registered, a password reset link has been sent.',
];

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    $cleanStmt = $db->prepare('DELETE FROM password_resets WHERE user_id = ? AND used_at IS NULL');
    $cleanStmt->execute([$user['id']]);

    $insertStmt = $db->prepare(
        'INSERT INTO password_resets (user_id, email, reset_token, expires_at) VALUES (?, ?, ?, ?)'
    );
    $insertStmt->execute([$user['id'], $user['email'], $token, $expires]);

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (preg_match('/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/i', $origin)) {
        $response['reset_url'] = $origin . '/#/reset-password?token=' . rawurlencode($token);
    }
}

echo json_encode($response);
