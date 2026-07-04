<?php
/**
 * VWM Database Migration — Phase 3
 * Creates tables for the Resources + Books system (Issue #26).
 * Run once: https://your-domain.com/admin/migrate3.php
 */
require_once '_auth.php';

$db  = getDB();
$log = [];

// ── resources ─────────────────────────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS resources (
        id          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        category    ENUM(
                      'articles','bible_studies','media_awareness',
                      'family_guidance','youth_discipleship','creative_arts',
                      'video_teachings','audio_messages'
                    ) NOT NULL,
        title       VARCHAR(255) NOT NULL,
        description TEXT         DEFAULT NULL,
        file_path   VARCHAR(500) NOT NULL,
        file_name   VARCHAR(255) NOT NULL,
        file_type   ENUM('video','audio','pdf') NOT NULL,
        file_size   BIGINT       DEFAULT 0,
        is_active   TINYINT(1)   NOT NULL DEFAULT 1,
        created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = 'OK  resources table';

// ── books ─────────────────────────────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS books (
        id          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title       VARCHAR(255) NOT NULL,
        description TEXT         DEFAULT NULL,
        cover_image VARCHAR(500) DEFAULT NULL,
        price       DECIMAL(10,2) DEFAULT 0.00,
        currency    VARCHAR(10)  DEFAULT 'INR',
        is_active   TINYINT(1)   NOT NULL DEFAULT 1,
        created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = 'OK  books table';

?><!DOCTYPE html><html><head><title>VWM Migrate 3</title>
<style>body{font-family:monospace;padding:32px;background:#f7f9ff} pre{background:#fff;border:1px solid #ddd;padding:20px;border-radius:6px}</style>
</head><body>
<h2>Migration Phase 3 — Results</h2>
<pre><?= implode("\n", array_map('htmlspecialchars', $log)) ?></pre>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
</body></html>
