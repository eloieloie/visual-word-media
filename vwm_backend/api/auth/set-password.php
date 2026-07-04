<?php
/**
 * POST /api/auth/set-password.php
 *
 * For users with force_password_reset = 1.
 * Accepts the user's auth token + new password; clears the flag.
 */
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/auth.php';   // provides requireAuth()
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$authUser = requireAuth();   // 401 if not authenticated

$input    = json_decode(file_get_contents('php://input'), true);
$password = $input['password'] ?? '';
$confirm  = $input['confirm_password'] ?? '';

if (!$password || !$confirm) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Both password fields are required']));
}

if (strlen($password) < 8) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
}

if ($password !== $confirm) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Passwords do not match']));
}

$db   = getDB();
$hash = password_hash($password, PASSWORD_BCRYPT);

$db->prepare(
    'UPDATE users SET password_hash = ?, force_password_reset = 0 WHERE id = ?'
)->execute([$hash, $authUser['id']]);

echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
