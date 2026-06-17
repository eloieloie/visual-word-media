<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);

$name     = trim($input['name'] ?? '');
$email    = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$name || !$email || !$password) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Name, email, and password are required']));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid email address']));
}

if (strlen($password) < 8) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
}

$db   = getDB();
$stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);

if ($stmt->fetch()) {
    http_response_code(409);
    die(json_encode(['success' => false, 'message' => 'An account with this email already exists']));
}

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $db->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$name, $email, $hash]);

echo json_encode(['success' => true, 'message' => 'Account created successfully']);
