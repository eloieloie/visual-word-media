<?php
/**
 * VWM Database Migration
 * Run once: https://your-domain.com/admin/migrate.php (must be logged in as admin)
 */
require_once '_auth.php';

$db  = getDB();
$log = [];

// ── settings ──────────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS settings (
        `key`   VARCHAR(100) NOT NULL PRIMARY KEY,
        `value` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = '✅ Table <strong>settings</strong> ready';

// ── youtube_cache ─────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS youtube_cache (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        data      LONGTEXT NOT NULL,
        cached_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = '✅ Table <strong>youtube_cache</strong> ready';

// ── audio_teachings ───────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS audio_teachings (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        title       VARCHAR(255) NOT NULL,
        speaker     VARCHAR(255) DEFAULT '',
        description TEXT         DEFAULT '',
        file_path   VARCHAR(500) NOT NULL,
        file_name   VARCHAR(255) NOT NULL,
        file_size   INT          DEFAULT 0,
        duration    VARCHAR(30)  DEFAULT '',
        is_active   TINYINT(1)   NOT NULL DEFAULT 1,
        sort_order  INT          DEFAULT 0,
        uploaded_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = '✅ Table <strong>audio_teachings</strong> ready';

// Seed default YouTube channel handle
$existing = $db->query("SELECT COUNT(*) FROM settings WHERE `key` = 'youtube_handle'")->fetchColumn();
if (!$existing) {
    $db->exec("INSERT INTO settings (`key`, `value`) VALUES ('youtube_handle', '@Network105')");
    $log[] = '✅ Seeded default YouTube handle: <strong>@Network105</strong>';
}

// ── testimonials ──────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS testimonials (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        name       VARCHAR(255) NOT NULL,
        location   VARCHAR(255) DEFAULT '',
        category   VARCHAR(100) NOT NULL DEFAULT 'General',
        testimony  TEXT         NOT NULL,
        is_active  TINYINT(1)   NOT NULL DEFAULT 1,
        sort_order INT          NOT NULL DEFAULT 0,
        created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = '✅ Table <strong>testimonials</strong> ready';

// ── volunteer_registrations ───────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS volunteer_registrations (
        id                 INT AUTO_INCREMENT PRIMARY KEY,
        name               VARCHAR(255) NOT NULL,
        gender             VARCHAR(20)  DEFAULT '',
        dob                DATE         DEFAULT NULL,
        mobile             VARCHAR(30)  NOT NULL,
        whatsapp           VARCHAR(30)  DEFAULT '',
        email              VARCHAR(255) NOT NULL,
        city               VARCHAR(100) DEFAULT '',
        state              VARCHAR(100) DEFAULT '',
        country            VARCHAR(100) DEFAULT '',
        is_believer        TINYINT(1)   DEFAULT 0,
        church_active      TINYINT(1)   DEFAULT 0,
        church_name        VARCHAR(255) DEFAULT '',
        pastor_name        VARCHAR(255) DEFAULT '',
        personal_testimony TEXT,
        ministry_areas     LONGTEXT,
        service_type       LONGTEXT,
        availability       LONGTEXT,
        skills             TEXT,
        occupation         VARCHAR(255) DEFAULT '',
        organization       VARCHAR(255) DEFAULT '',
        motivation         TEXT,
        comments           TEXT,
        declared           TINYINT(1)   DEFAULT 0,
        status             ENUM('new','contacted','active','declined') DEFAULT 'new',
        created_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$log[] = '✅ Table <strong>volunteer_registrations</strong> ready';

$count = $db->query('SELECT COUNT(*) FROM testimonials')->fetchColumn();
if ($count == 0) {
    $seed = [
        ['Priya R.',    '',          'Youth Testimonies',  'The 60x360 discipleship camp completely changed my life. I came in confused about my faith and left with a clear sense of God\'s calling. I\'m now actively serving in my village church.'],
        ['Samuel K.',   '',          'Media Recovery',     'I struggled with social media addiction for years. Through IMPACT\'s counseling, I learned to set boundaries and redirect my time toward God\'s purposes. My family relationships have been restored.'],
        ['Ananya D.',   '',          'Camp Experiences',   'The leadership camp taught me that my creativity is a gift from God meant to be used for His glory. I now lead a creative arts group in my church and mentor younger artists.'],
        ['David M.',    '',          'Creative Artists',   'Oculus gave me a community of believers who understand the tension between creativity and faith. I\'ve found my calling as a filmmaker who tells stories of redemption.'],
        ['Rebekah S.',  '',          'Media Recovery',     'After struggling with loneliness and depression amplified by social media comparison, New Life ministry gave me hope. I\'m now counseling others through similar struggles.'],
        ['Jonathan T.', '',          'Ministry Partners',  'Partnering with Visual Word Media Mission has transformed our church\'s media ministry. We now have a trained team producing content that reaches our entire city.'],
        ['Meera P.',    '',          'Youth Testimonies',  'At 17, I found purpose through the 60x360 movement. We conducted our first village outreach last month and saw 12 people come to faith. God is doing incredible things.'],
        ['Thomas A.',   '',          'Camp Experiences',   'The discipleship camp wasn\'t just a weekend event — it set me on a course of intentional spiritual growth. The mentorship I received has shaped my entire walk with God.'],
        ['Grace N.',    '',          'Creative Artists',   'Through Network105, my written reflections are now reaching thousands. I never thought my words could impact so many lives. God has blown my expectations away.'],
    ];
    $stmt = $db->prepare('INSERT INTO testimonials (name, location, category, testimony) VALUES (?,?,?,?)');
    foreach ($seed as $row) { $stmt->execute($row); }
    $log[] = '✅ Seeded <strong>' . count($seed) . ' testimonials</strong>';
} else {
    $log[] = 'ℹ️ Testimonials table already has data — skipped seeding';
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>VWM Migration</title>
<style>body{font-family:sans-serif;max-width:600px;margin:60px auto;line-height:1.8}
ul{list-style:none;padding:0}li{padding:6px 0;border-bottom:1px solid #eee}
.ok{margin-top:24px;padding:14px 18px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:6px;color:#276027}
a{color:#1a2d5a;font-weight:600}</style></head><body>
<h2>VWM Database Migration</h2>
<ul><?php foreach ($log as $l): ?><li><?= $l ?></li><?php endforeach; ?></ul>
<div class="ok">✅ All done. <a href="dashboard.php">Go to Dashboard →</a></div>
</body></html>
