<?php
/**
 * VWM Database Migration — Phase 4
 * Adds banner table and featured flag for events (Issue #21).
 */
require_once '_auth.php';

$db  = getDB();
$log = [];

// ── banners table ─────────────────────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS banners (
        id         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        headline   VARCHAR(255)  DEFAULT '',
        subheading VARCHAR(500)  DEFAULT '',
        media_path VARCHAR(500)  DEFAULT NULL,
        media_type ENUM('image','video') DEFAULT 'image',
        is_active  TINYINT(1)    NOT NULL DEFAULT 1,
        sort_order INT(11)       NOT NULL DEFAULT 0,
        created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = 'OK  banners table';

// ── events.is_featured ────────────────────────────────────────────────────────
$cols = $db->query("SHOW COLUMNS FROM events LIKE 'is_featured'")->fetchAll();
if ($cols) {
    $log[] = 'SKIP events.is_featured already exists';
} else {
    $db->exec("ALTER TABLE events ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active");
    $log[] = 'ADD  events.is_featured';
}

// ── Seed default daily verse in settings ─────────────────────────────────────
$exists = $db->query("SELECT COUNT(*) FROM settings WHERE `key` = 'daily_verse_text'")->fetchColumn();
if (!$exists) {
    $db->exec("INSERT INTO settings (`key`, `value`) VALUES ('daily_verse_text', 'The heavens declare the glory of God; the skies proclaim the work of His hands.')");
    $db->exec("INSERT INTO settings (`key`, `value`) VALUES ('daily_verse_ref', 'Psalm 19:1')");
    $log[] = 'ADD  default daily_verse settings';
} else {
    $log[] = 'SKIP daily_verse settings already seeded';
}

?><!DOCTYPE html><html><head><title>VWM Migrate 4</title>
<style>body{font-family:monospace;padding:32px;background:#f7f9ff} pre{background:#fff;border:1px solid #ddd;padding:20px;border-radius:6px}</style>
</head><body>
<h2>Migration Phase 4 — Results</h2>
<pre><?= implode("\n", array_map('htmlspecialchars', $log)) ?></pre>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
</body></html>
