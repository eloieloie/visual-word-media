<?php
require_once '_auth.php';

$db = getDB();

$results  = [];
$errors   = [];
$executed = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = trim($_POST['sql'] ?? '');

    if ($raw !== '') {
        // Split on semicolons but skip empty statements
        $statements = array_filter(
            array_map('trim', explode(';', $raw)),
            fn($s) => $s !== ''
        );

        foreach ($statements as $sql) {
            try {
                $stmt = $db->query($sql);
                $executed++;

                // SELECT / SHOW / DESCRIBE — fetch rows
                if ($stmt && $stmt->columnCount() > 0) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $results[] = [
                        'sql'  => $sql,
                        'rows' => $rows,
                        'cols' => $rows ? array_keys($rows[0]) : [],
                        'type' => 'select',
                    ];
                } else {
                    $results[] = [
                        'sql'      => $sql,
                        'affected' => $stmt ? $stmt->rowCount() : 0,
                        'type'     => 'write',
                    ];
                }
            } catch (PDOException $e) {
                $errors[] = ['sql' => $sql, 'error' => $e->getMessage()];
            }
        }
    }
}

$pageTitle   = 'SQL Runner';
$currentPage = 'sql';
require_once '_header.php';
?>

<style>
  .sql-wrap  { display:grid; grid-template-columns: 1fr 1fr; gap:22px; align-items:start; }
  @media(max-width:900px){ .sql-wrap{ grid-template-columns:1fr; } }
  .sql-panel { background:#fff; border:1px solid #e4e6ed; border-radius:10px; overflow:hidden; }
  .sql-head  { padding:14px 18px; border-bottom:1px solid #e4e6ed; display:flex; align-items:center; gap:10px; }
  .sql-head h3 { font-size:0.92rem; font-weight:700; color:#1a2d5a; margin:0; }
  .sql-body  { padding:16px 18px; }
  textarea.sql-input {
    width:100%; min-height:260px; font-family:'Courier New',Courier,monospace;
    font-size:0.85rem; border:1.5px solid #dde1ec; border-radius:6px; padding:12px;
    resize:vertical; background:#1e1e2e; color:#cdd6f4; outline:none; line-height:1.7;
  }
  textarea.sql-input:focus { border-color:#1a2d5a; }
  .result-block  { margin-bottom:18px; }
  .result-sql    { font-family:monospace; font-size:0.78rem; color:#666; background:#f7f8fc; border-radius:4px; padding:6px 10px; margin-bottom:8px; word-break:break-all; }
  .result-ok     { display:inline-block; padding:4px 12px; background:#e8f5e9; color:#276027; border-radius:5px; font-size:0.82rem; font-weight:600; }
  .result-err    { padding:10px 14px; background:#fdecea; color:#c62828; border-radius:6px; font-size:0.85rem; border:1px solid #f5c6c4; }
  .result-table  { width:100%; border-collapse:collapse; font-size:0.82rem; margin-top:4px; }
  .result-table th { background:#f0f2f8; padding:7px 10px; text-align:left; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#666; border:1px solid #e4e6ed; }
  .result-table td { padding:7px 10px; border:1px solid #f0f0f0; color:#333; max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .result-table tr:hover td { background:#fafbfc; }
  .row-count { font-size:0.75rem; color:#999; margin-top:4px; }
  .warn-box  { background:#fff8e1; border:1px solid #ffe082; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:0.84rem; color:#6d4c00; }
  .btn-run   { background:#1a2d5a; color:#fff; border:none; padding:10px 26px; border-radius:6px; font-size:0.88rem; font-weight:700; cursor:pointer; font-family:inherit; transition:background .15s; }
  .btn-run:hover { background:#243470; }
  .btn-clear { background:transparent; border:1.5px solid #dde1ec; color:#666; padding:9px 18px; border-radius:6px; font-size:0.85rem; cursor:pointer; font-family:inherit; margin-left:8px; }
  .btn-clear:hover { background:#f5f6fa; }
  .snippet-list { display:flex; flex-direction:column; gap:6px; }
  .snippet-btn  { background:#f7f8fc; border:1.5px solid #e4e6ed; color:#1a2d5a; padding:7px 12px; border-radius:6px; font-size:0.78rem; font-weight:600; cursor:pointer; text-align:left; transition:background .12s; font-family:inherit; }
  .snippet-btn:hover { background:#e8edf8; }
</style>

<div class="warn-box">
  ⚠️ <strong>Direct database access.</strong> Statements execute immediately with no undo. Double-check before running <code>UPDATE</code>, <code>DELETE</code>, or <code>ALTER</code> statements.
</div>

<div class="sql-wrap">

  <!-- ── Input panel ── -->
  <div class="sql-panel">
    <div class="sql-head">
      <span style="font-size:1.1rem">🗄️</span>
      <h3>SQL Statement</h3>
    </div>
    <div class="sql-body">
      <form method="POST" id="sql-form">
        <textarea name="sql" id="sql-input" class="sql-input"
                  placeholder="-- Enter one or more SQL statements separated by semicolons&#10;SELECT * FROM users LIMIT 10;"><?= htmlspecialchars($_POST['sql'] ?? '') ?></textarea>
        <div style="margin-top:12px;display:flex;align-items:center">
          <button type="submit" class="btn-run">▶ Run</button>
          <button type="button" class="btn-clear" onclick="clearSql()">Clear</button>
          <span style="margin-left:auto;font-size:0.75rem;color:#aaa">Ctrl+Enter to run</span>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Snippets panel ── -->
  <div class="sql-panel">
    <div class="sql-head">
      <span style="font-size:1.1rem">📋</span>
      <h3>Quick Snippets</h3>
    </div>
    <div class="sql-body">
      <div class="snippet-list">
        <button class="snippet-btn" onclick="setSnippet('show_tables')">List all tables</button>
        <button class="snippet-btn" onclick="setSnippet('desc_users')">Describe users table</button>
        <button class="snippet-btn" onclick="setSnippet('count_users')">Count users by role</button>
        <button class="snippet-btn" onclick="setSnippet('list_users')">List users (latest 20)</button>
        <button class="snippet-btn" onclick="setSnippet('migrate2')">Setup: add user profile columns (migrate 2)</button>
        <button class="snippet-btn" onclick="setSnippet('migrate3')">Setup: create resources &amp; books tables (migrate 3)</button>
        <button class="snippet-btn" onclick="setSnippet('migrate4')">Setup: create banners table + event featured flag (migrate 4)</button>
        <button class="snippet-btn" onclick="setSnippet('check_cols')">Check: which profile columns exist</button>
      </div>
    </div>
  </div>

</div>

<!-- ── Results ── -->
<?php if (!empty($errors) || !empty($results)): ?>
<div style="margin-top:22px">
  <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#999;margin-bottom:12px">
    Results — <?= $executed ?> statement<?= $executed !== 1 ? 's' : '' ?> executed
    <?php if ($errors): ?>, <span style="color:#c62828"><?= count($errors) ?> error<?= count($errors) !== 1 ? 's' : '' ?></span><?php endif; ?>
  </div>

  <?php foreach ($errors as $e): ?>
    <div class="result-block">
      <div class="result-sql"><?= htmlspecialchars($e['sql']) ?></div>
      <div class="result-err">❌ <?= htmlspecialchars($e['error']) ?></div>
    </div>
  <?php endforeach; ?>

  <?php foreach ($results as $r): ?>
    <div class="result-block sql-panel" style="overflow:hidden">
      <div style="padding:10px 16px;border-bottom:1px solid #f0f0f0;background:#fafbfc">
        <div class="result-sql" style="background:none;padding:0;margin:0"><?= htmlspecialchars($r['sql']) ?></div>
      </div>
      <div style="padding:12px 16px">
        <?php if ($r['type'] === 'select'): ?>
          <?php if ($r['rows']): ?>
            <div style="overflow-x:auto">
              <table class="result-table">
                <thead><tr><?php foreach ($r['cols'] as $col): ?><th><?= htmlspecialchars($col) ?></th><?php endforeach; ?></tr></thead>
                <tbody>
                  <?php foreach ($r['rows'] as $row): ?>
                    <tr><?php foreach ($row as $val): ?><td title="<?= htmlspecialchars((string)$val) ?>"><?= htmlspecialchars((string)($val ?? 'NULL')) ?></td><?php endforeach; ?></tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="row-count"><?= count($r['rows']) ?> row<?= count($r['rows']) !== 1 ? 's' : '' ?></div>
          <?php else: ?>
            <span style="color:#999;font-size:0.85rem">No rows returned.</span>
          <?php endif; ?>
        <?php else: ?>
          <span class="result-ok">✔ <?= $r['affected'] ?> row<?= $r['affected'] !== 1 ? 's' : '' ?> affected</span>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
var snippets = {
  show_tables: 'SHOW TABLES',
  desc_users: 'DESCRIBE users',
  count_users: "SELECT role, COUNT(*) AS total FROM users GROUP BY role ORDER BY total DESC",
  list_users:  "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 20",
  check_cols:  "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT\nFROM information_schema.COLUMNS\nWHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'\nORDER BY ORDINAL_POSITION",

  migrate2: [
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS username VARCHAR(80) DEFAULT '' AFTER name",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS mobile VARCHAR(30) DEFAULT '' AFTER email",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS gender ENUM('male','female','other') DEFAULT 'other' AFTER mobile",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS church_name VARCHAR(255) DEFAULT '' AFTER gender",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS church_address TEXT DEFAULT NULL AFTER church_name",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS referral_name VARCHAR(255) DEFAULT '' AFTER church_address",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS referral_mobile VARCHAR(30) DEFAULT '' AFTER referral_name",
    "ALTER TABLE users MODIFY COLUMN role ENUM('registrant','member','volunteer','admin') NOT NULL DEFAULT 'registrant'",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS force_password_reset TINYINT(1) NOT NULL DEFAULT 0 AFTER role",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_at DATETIME DEFAULT NULL AFTER force_password_reset",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS approved_by INT(11) DEFAULT NULL AFTER approved_at",
    "ALTER TABLE users ADD UNIQUE INDEX IF NOT EXISTS uniq_username (username)"
  ].join(';\n'),

  migrate3: [
    "CREATE TABLE IF NOT EXISTS resources (\n  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,\n  category ENUM('articles','bible_studies','media_awareness','family_guidance','youth_discipleship','creative_arts','video_teachings','audio_messages') NOT NULL,\n  title VARCHAR(255) NOT NULL,\n  description TEXT DEFAULT NULL,\n  file_path VARCHAR(500) NOT NULL,\n  file_name VARCHAR(255) NOT NULL,\n  file_type ENUM('video','audio','pdf') NOT NULL,\n  file_size BIGINT DEFAULT 0,\n  is_active TINYINT(1) NOT NULL DEFAULT 1,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS books (\n  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,\n  title VARCHAR(255) NOT NULL,\n  description TEXT DEFAULT NULL,\n  cover_image VARCHAR(500) DEFAULT NULL,\n  price DECIMAL(10,2) DEFAULT 0.00,\n  currency VARCHAR(10) DEFAULT 'INR',\n  is_active TINYINT(1) NOT NULL DEFAULT 1,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
  ].join(';\n'),

  migrate4: [
    "CREATE TABLE IF NOT EXISTS banners (\n  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,\n  headline VARCHAR(255) DEFAULT '',\n  subheading VARCHAR(500) DEFAULT '',\n  media_path VARCHAR(500) DEFAULT NULL,\n  media_type ENUM('image','video') DEFAULT 'image',\n  is_active TINYINT(1) NOT NULL DEFAULT 1,\n  sort_order INT(11) NOT NULL DEFAULT 0,\n  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "ALTER TABLE events ADD COLUMN IF NOT EXISTS is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active",
    "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('daily_verse_text', 'The heavens declare the glory of God; the skies proclaim the work of His hands.')",
    "INSERT IGNORE INTO settings (`key`, `value`) VALUES ('daily_verse_ref', 'Psalm 19:1')"
  ].join(';\n')
};

function setSnippet(key) {
  document.getElementById('sql-input').value = snippets[key];
  document.getElementById('sql-input').focus();
}

function clearSql() {
  document.getElementById('sql-input').value = '';
  document.getElementById('sql-input').focus();
}

document.getElementById('sql-input').addEventListener('keydown', function(e) {
  if (e.ctrlKey && e.key === 'Enter') {
    document.getElementById('sql-form').submit();
  }
});
</script>

<?php require_once '_footer.php'; ?>
