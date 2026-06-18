<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

requireAuth();

$db         = getDB();
$CACHE_TTL  = 3600; // 1 hour

// ── Serve from cache if still fresh ──────────────────────────
$cached = $db->query(
    "SELECT data, cached_at FROM youtube_cache ORDER BY id DESC LIMIT 1"
)->fetch();

if ($cached && (time() - strtotime($cached['cached_at'])) < $CACHE_TTL) {
    echo $cached['data'];
    exit();
}

// ── Resolve channel ID ────────────────────────────────────────
$channelId = $db->query(
    "SELECT value FROM settings WHERE `key` = 'youtube_channel_id'"
)->fetchColumn();

if (!$channelId) {
    $handle     = $db->query(
        "SELECT value FROM settings WHERE `key` = 'youtube_handle'"
    )->fetchColumn() ?: '@Network105';

    $channelUrl = "https://www.youtube.com/" . ltrim($handle, '@');
    $channelUrl = strpos($handle, '@') === 0
        ? "https://www.youtube.com/" . $handle
        : "https://www.youtube.com/@" . $handle;

    $ctx  = stream_context_create(['http' => [
        'user_agent' => 'Mozilla/5.0 (compatible; VWM/1.0)',
        'timeout'    => 10,
    ]]);
    $html = @file_get_contents($channelUrl, false, $ctx);

    if ($html) {
        // Extract channel ID from the RSS alternate link
        if (preg_match('/feeds\/videos\.xml\?channel_id=(UC[\w-]+)/', $html, $m)) {
            $channelId = $m[1];
        } elseif (preg_match('/"channelId":"(UC[\w-]+)"/', $html, $m)) {
            $channelId = $m[1];
        }

        if ($channelId) {
            $stmt = $db->prepare(
                "INSERT INTO settings (`key`,`value`) VALUES ('youtube_channel_id',?)
                 ON DUPLICATE KEY UPDATE `value`=?"
            );
            $stmt->execute([$channelId, $channelId]);
        }
    }
}

if (!$channelId) {
    echo json_encode(['success' => false, 'message' => 'Could not resolve YouTube channel ID. Run admin/migrate.php and check the channel handle.', 'videos' => []]);
    exit();
}

// ── Fetch RSS feed ────────────────────────────────────────────
$rssUrl = "https://www.youtube.com/feeds/videos.xml?channel_id={$channelId}";
$ctx    = stream_context_create(['http' => [
    'user_agent' => 'Mozilla/5.0 (compatible; VWM/1.0)',
    'timeout'    => 10,
]]);
$rssXml = @file_get_contents($rssUrl, false, $ctx);

if (!$rssXml) {
    // Return stale cache if available
    if ($cached) {
        echo $cached['data'];
        exit();
    }
    echo json_encode(['success' => false, 'message' => 'Failed to fetch YouTube feed', 'videos' => []]);
    exit();
}

// ── Parse XML ─────────────────────────────────────────────────
libxml_use_internal_errors(true);
$xml = simplexml_load_string($rssXml);
if (!$xml) {
    echo json_encode(['success' => false, 'message' => 'Failed to parse YouTube feed', 'videos' => []]);
    exit();
}

$NS_YT    = 'http://www.youtube.com/xml/schemas/2015';
$NS_MEDIA = 'http://search.yahoo.com/mrss/';

$videos = [];
foreach ($xml->entry as $entry) {
    $yt    = $entry->children($NS_YT);
    $media = $entry->children($NS_MEDIA);

    $videoId = (string)($yt->videoId ?? '');
    if (!$videoId) continue;

    $group       = $media->group;
    $thumbUrl    = (string)($group->thumbnail->attributes()['url'] ?? '');
    $description = trim((string)($group->description ?? ''));

    $published = (string)($entry->published ?? '');
    $formattedDate = $published ? date('d M Y', strtotime($published)) : '';

    $videos[] = [
        'id'          => $videoId,
        'title'       => (string)($entry->title ?? 'Untitled'),
        'published'   => $published,
        'date'        => $formattedDate,
        'thumbnail'   => $thumbUrl ?: "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
        'description' => mb_substr($description, 0, 220),
        'embed'       => "https://www.youtube.com/embed/{$videoId}",
    ];
}

// Already sorted latest→oldest by the RSS feed
$response = json_encode(['success' => true, 'videos' => $videos]);

// Update cache
$db->exec("DELETE FROM youtube_cache");
$stmt = $db->prepare("INSERT INTO youtube_cache (data) VALUES (?)");
$stmt->execute([$response]);

echo $response;
