<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $stmt = $db->query(
        "SELECT id, name, category, testimony, location
         FROM testimonials
         WHERE is_active = 1
         ORDER BY sort_order DESC, created_at DESC"
    );
    echo json_encode(['success' => true, 'testimonials' => $stmt->fetchAll()]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
