<?php
/**
 * VWM Database Migration — Phase 2
 * Adds columns required for Issue #17 (Sign Up form) and Issue #19 (role management).
 * Run once: https://your-domain.com/admin/migrate2.php  (must be logged-in admin)
 */
require_once '_auth.php';

$db  = getDB();
$log = [];

// ── Helper: add a column if it doesn't already exist ─────────────────────────
function addColumnIfMissing(PDO $db, string $table, string $column, string $definition, array &$log): void {
    $cols = $db->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'")->fetchAll();
    if ($cols) {
        $log[] = "SKIP  {$table}.{$column} already exists";
    } else {
        $db->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
        $log[] = "ADD   {$table}.{$column}";
    }
}

// ── 1. users: new fields for Sign Up (Issue #17) ─────────────────────────────
addColumnIfMissing($db, 'users', 'username',            "VARCHAR(80) DEFAULT '' AFTER name",                $log);
addColumnIfMissing($db, 'users', 'mobile',              "VARCHAR(30) DEFAULT '' AFTER email",               $log);
addColumnIfMissing($db, 'users', 'gender',              "ENUM('male','female','other') DEFAULT 'other' AFTER mobile", $log);
addColumnIfMissing($db, 'users', 'church_name',         "VARCHAR(255) DEFAULT '' AFTER gender",             $log);
addColumnIfMissing($db, 'users', 'church_address',      "TEXT DEFAULT NULL AFTER church_name",              $log);
addColumnIfMissing($db, 'users', 'referral_name',       "VARCHAR(255) DEFAULT '' AFTER church_address",     $log);
addColumnIfMissing($db, 'users', 'referral_mobile',     "VARCHAR(30) DEFAULT '' AFTER referral_name",       $log);

// ── 2. users: role management (Issue #19) ────────────────────────────────────
// Change role column to proper ENUM if it isn't already
// (existing 'role' column is VARCHAR — we'll modify it)
$roleCol = $db->query("SHOW COLUMNS FROM users LIKE 'role'")->fetch();
if ($roleCol && stripos($roleCol['Type'], 'enum') === false) {
    $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('registrant','member','volunteer','admin') NOT NULL DEFAULT 'registrant'");
    $log[] = "MODIFY users.role → ENUM(registrant,member,volunteer,admin)";
} elseif (!$roleCol) {
    $db->exec("ALTER TABLE users ADD COLUMN role ENUM('registrant','member','volunteer','admin') NOT NULL DEFAULT 'registrant' AFTER password_hash");
    $log[] = "ADD   users.role";
} else {
    $log[] = "SKIP  users.role already ENUM";
}

addColumnIfMissing($db, 'users', 'force_password_reset', "TINYINT(1) NOT NULL DEFAULT 0 AFTER role",       $log);
addColumnIfMissing($db, 'users', 'approved_at',          "DATETIME DEFAULT NULL AFTER force_password_reset", $log);
addColumnIfMissing($db, 'users', 'approved_by',          "INT(11) DEFAULT NULL AFTER approved_at",          $log);

// ── 3. Unique index on username (nullable-safe) ───────────────────────────────
$idxExists = $db->query("SHOW INDEX FROM users WHERE Key_name = 'uniq_username'")->fetch();
if (!$idxExists) {
    $db->exec("ALTER TABLE users ADD UNIQUE INDEX uniq_username (username)");
    $log[] = "ADD   INDEX uniq_username on users.username";
} else {
    $log[] = "SKIP  INDEX uniq_username already exists";
}

?><!DOCTYPE html><html><head><title>VWM Migrate 2</title>
<style>body{font-family:monospace;padding:32px;background:#f7f9ff} pre{background:#fff;border:1px solid #ddd;padding:20px;border-radius:6px}</style>
</head><body>
<h2>Migration Phase 2 — Results</h2>
<pre><?= implode("\n", array_map('htmlspecialchars', $log)) ?></pre>
<p><a href="dashboard.php">← Back to Dashboard</a></p>
</body></html>
