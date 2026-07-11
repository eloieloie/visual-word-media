<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';

$method   = $_SERVER['REQUEST_METHOD'];
$authUser = getAuthUser();

// ── GET — public (with auth) ─────────────────────────────────────────────────
if ($method === 'GET') {
    if (!$authUser) {
        http_response_code(401);
        die(json_encode(['success' => false, 'message' => 'Authentication required']));
    }
    $db    = getDB();
    $books = $db->query(
        'SELECT id, title, description, cover_image, price, currency
         FROM books WHERE is_active = 1 ORDER BY created_at DESC'
    )->fetchAll();
    echo json_encode(['success' => true, 'books' => $books]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
