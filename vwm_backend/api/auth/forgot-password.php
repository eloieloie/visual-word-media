<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/mailer.php';

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

    // Build the reset link. In local dev, honour the request origin so the
    // link points back at the dev server; otherwise use the configured app URL.
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (preg_match('/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/i', $origin)) {
        $base = $origin;
        $response['reset_url'] = $base . '/#/reset-password?token=' . rawurlencode($token);
    } else {
        $base = rtrim(APP_FRONTEND_URL, '/');
    }

    $resetUrl = $base . '/#/reset-password?token=' . rawurlencode($token);

    // Email the reset link to the user. Failures are logged but never leak
    // whether the address exists, so the response stays generic.
    $mail = sendMail(
        $user['email'],
        'Reset your Visual Word Media password',
        buildPasswordResetEmail($resetUrl)
    );
    if (!$mail['success']) {
        error_log('[forgot-password] mail failed for user ' . $user['id'] . ': ' . $mail['message']);
    }
}

echo json_encode($response);

/**
 * Build the HTML body for the password-reset email.
 */
function buildPasswordResetEmail(string $resetUrl): string
{
    $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');
    return <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a1a2e">Reset your password</h2>
  <p>We received a request to reset the password for your Visual Word Media account.</p>
  <p>Click the button below to choose a new password. This link expires in 30 minutes.</p>
  <p style="text-align:center;margin:28px 0">
    <a href="{$safeUrl}"
       style="background:#1a1a2e;color:#fff;text-decoration:none;padding:12px 28px;border-radius:6px;display:inline-block">
      Reset Password
    </a>
  </p>
  <p style="font-size:13px;color:#666">If the button doesn't work, copy and paste this link into your browser:</p>
  <p style="font-size:13px;word-break:break-all"><a href="{$safeUrl}">{$safeUrl}</a></p>
  <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
  <p style="font-size:12px;color:#999">If you didn't request a password reset, you can safely ignore this email.</p>
</div>
HTML;
}
