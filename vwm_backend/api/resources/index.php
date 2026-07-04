<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';

$method   = $_SERVER['REQUEST_METHOD'];
$authUser = getAuthUser();   // null if unauthenticated

// ── GET — public (with auth) ──────────────────────────────────────────────────
if ($method === 'GET') {
    if (!$authUser) {
        http_response_code(401);
        die(json_encode(['success' => false, 'message' => 'Authentication required']));
    }

    $db       = getDB();
    $category = trim($_GET['category'] ?? '');

    $validCategories = [
        'articles','bible_studies','media_awareness','family_guidance',
        'youth_discipleship','creative_arts','video_teachings','audio_messages',
    ];

    if ($category && !in_array($category, $validCategories, true)) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Invalid category']));
    }

    if ($category) {
        $stmt = $db->prepare(
            'SELECT id, category, title, description, file_path, file_type, file_size, created_at
             FROM resources WHERE category = ? AND is_active = 1 ORDER BY created_at DESC'
        );
        $stmt->execute([$category]);
    } else {
        $stmt = $db->query(
            'SELECT id, category, title, description, file_path, file_type, file_size, created_at
             FROM resources WHERE is_active = 1 ORDER BY category, created_at DESC'
        );
    }

    $items = $stmt->fetchAll();
    echo json_encode(['success' => true, 'resources' => $items]);
    exit;
}

// ── Remaining methods require admin ──────────────────────────────────────────
if (!$authUser || $authUser['role'] !== 'admin') {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Admin access required']));
}

// Admin endpoints handled in admin/resources.php
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
