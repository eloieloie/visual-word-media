<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);
$token = trim($input['token'] ?? '');
$password = $input['password'] ?? '';

if (!$token || !$password) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Token and password are required']));
}

if (strlen($password) < 8) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
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
$stmt = $db->prepare(
    'SELECT * FROM password_resets WHERE reset_token = ? AND used_at IS NULL AND expires_at > NOW() LIMIT 1'
);
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid or expired reset token']));
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$updateUser = $db->prepare(
    'UPDATE users SET password_hash = ?, auth_token = NULL, token_expires_at = NULL WHERE id = ?'
);
$updateUser->execute([$passwordHash, $reset['user_id']]);

if ($updateUser->rowCount() === 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Account not found for this reset token']));
}

$markUsed = $db->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?');
$markUsed->execute([$reset['id']]);

$cleanup = $db->prepare('DELETE FROM password_resets WHERE user_id = ? AND used_at IS NULL');
$cleanup->execute([$reset['user_id']]);

echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
