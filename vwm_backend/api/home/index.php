<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$db = getDB();

// Daily verse
$verse = [];
foreach ($db->query("SELECT `key`, `value` FROM settings WHERE `key` IN ('daily_verse_text','daily_verse_ref')") as $row) {
    $verse[$row['key']] = $row['value'];
}

// Active banners
try {
    $banners = $db->query(
        "SELECT id, headline, subheading, media_path, media_type
         FROM banners WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC"
    )->fetchAll();
} catch (PDOException $e) {
    $banners = [];
}

// Featured events
try {
    $events = $db->query(
        "SELECT id, title, CONCAT(month, ' ', day) AS event_date, description
         FROM events WHERE is_featured = 1 AND is_active = 1
         ORDER BY FIELD(month,'JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'),
                  CAST(day AS UNSIGNED) LIMIT 6"
    )->fetchAll();
} catch (PDOException $e) {
    $events = [];
}

echo json_encode([
    'success' => true,
    'verse'   => [
        'text' => $verse['daily_verse_text'] ?? '',
        'ref'  => $verse['daily_verse_ref']  ?? '',
    ],
    'banners' => $banners,
    'featured_events' => $events,
]);
