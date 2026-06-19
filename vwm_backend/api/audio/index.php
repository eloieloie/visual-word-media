<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

$siteUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

if ($method === 'GET') {
    requireAuth();
    $stmt   = $db->query(
        "SELECT id, title, speaker, description, file_path, file_name, file_size, duration, uploaded_at
         FROM audio_teachings
         WHERE is_active = 1
         ORDER BY sort_order DESC, uploaded_at DESC"
    );
    $audios = $stmt->fetchAll();

    foreach ($audios as &$a) {
        $a['url']       = $siteUrl . '/uploads/audio/' . rawurlencode($a['file_name']);
        $a['size_mb']   = $a['file_size'] ? round($a['file_size'] / 1048576, 1) . ' MB' : '';
        $a['date']      = date('d M Y', strtotime($a['uploaded_at']));
    }
    unset($a);

    echo json_encode(['success' => true, 'audios' => $audios]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
