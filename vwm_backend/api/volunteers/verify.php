<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$token = trim($input['token'] ?? '');

if (!$token) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Verification token is required']));
}

$db = getDB();

$stmt = $db->prepare(
    'SELECT id, name, email, email_verified FROM volunteer_registrations WHERE verification_token = ? LIMIT 1'
);
$stmt->execute([$token]);
$reg = $stmt->fetch();

if (!$reg) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid or expired verification link']));
}

// Already verified — treat as success (idempotent), so refreshes don't error.
if ((int) $reg['email_verified'] === 1) {
    echo json_encode([
        'success'  => true,
        'message'  => 'Your email is already verified. Your details are awaiting admin review.',
        'verified' => true,
    ]);
    exit();
}

$update = $db->prepare(
    'UPDATE volunteer_registrations SET email_verified = 1, verified_at = NOW() WHERE id = ?'
);
$update->execute([$reg['id']]);

echo json_encode([
    'success'  => true,
    'message'  => 'Email verified successfully. Your details are awaiting admin review.',
    'verified' => true,
]);
