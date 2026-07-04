<?php
require_once '_auth.php';

$db = getDB();
$UPLOAD_DIR = __DIR__ . '/../uploads/banners/';
if (!is_dir($UPLOAD_DIR)) { @mkdir($UPLOAD_DIR, 0755, true); }
$MAX_MB = 100;

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── Daily Verse ───────────────────────────────────────────────────────────
    if ($action === 'save_verse') {
        $text = trim($_POST['verse_text'] ?? '');
        $ref  = trim($_POST['verse_ref']  ?? '');
        $upsert = $db->prepare(
            "INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)"
        );
        $upsert->execute(['daily_verse_text', $text]);
        $upsert->execute(['daily_verse_ref',  $ref]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Daily verse updated.'];
    }

    // ── Banner upload ─────────────────────────────────────────────────────────
    elseif ($action === 'add_banner') {
        $headline   = trim($_POST['headline']   ?? '');
        $subheading = trim($_POST['subheading'] ?? '');
        $mediaPath  = null;
        $mediaType  = 'image';

        if (!empty($_FILES['banner_file']['tmp_name'])) {
            $f    = $_FILES['banner_file'];
            $mime = mime_content_type($f['tmp_name']);

            $allowed = [
                'image/jpeg' => ['image', 'jpg'],
                'image/png'  => ['image', 'png'],
                'image/webp' => ['image', 'webp'],
                'video/mp4'  => ['video', 'mp4'],
                'video/webm' => ['video', 'webm'],
            ];

            if (!isset($allowed[$mime])) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Unsupported file type.'];
                header('Location: home-content.php'); exit();
            }
            if ($f['size'] > $MAX_MB * 1024 * 1024) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => "File too large (max {$MAX_MB} MB)."];
                header('Location: home-content.php'); exit();
            }

            [$mediaType, $ext] = $allowed[$mime];
            $fn = 'banner_' . time() . '.' . $ext;
            move_uploaded_file($f['tmp_name'], $UPLOAD_DIR . $fn);
            $mediaPath = 'uploads/banners/' . $fn;
        }

        $db->prepare(
            'INSERT INTO banners (headline, subheading, media_path, media_type, is_active)
             VALUES (?, ?, ?, ?, 1)'
        )->execute([$headline, $subheading, $mediaPath, $mediaType]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Banner added.'];
    }

    // ── Banner toggle/delete ──────────────────────────────────────────────────
    elseif ($action === 'toggle_banner') {
        $id = intval($_POST['id'] ?? 0);
        $db->prepare('UPDATE banners SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Banner status toggled.'];
    } elseif ($action === 'delete_banner') {
        $id  = intval($_POST['id'] ?? 0);
        $row = $db->prepare('SELECT media_path FROM banners WHERE id = ?');
        $row->execute([$id]);
        $b   = $row->fetch();
        if ($b && $b['media_path']) { @unlink(__DIR__ . '/../' . $b['media_path']); }
        $db->prepare('DELETE FROM banners WHERE id = ?')->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Banner deleted.'];
    }

    // ── Featured events ───────────────────────────────────────────────────────
    elseif ($action === 'feature_event') {
        $id      = intval($_POST['id']      ?? 0);
        $feature = intval($_POST['feature'] ?? 0);
        $db->prepare('UPDATE events SET is_featured = ? WHERE id = ?')->execute([$feature, $id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Event updated.'];
    }

    header('Location: home-content.php');
    exit();
}

// ── Fetch data ────────────────────────────────────────────────────────────────
$settings = [];
foreach ($db->query("SELECT `key`, `value` FROM settings") as $row) {
    $settings[$row['key']] = $row['value'];
}

$banners = $db->query('SELECT * FROM banners ORDER BY sort_order ASC, created_at DESC')->fetchAll();

// Events with optional is_featured column
try {
    $events = $db->query(
        'SELECT id, title, event_date, is_featured FROM events ORDER BY event_date ASC'
    )->fetchAll();
} catch (PDOException $e) {
    $events = [];
}

$pageTitle   = 'Home Content';
$currentPage = 'home-content';
require_once '_header.php';
?>

<!-- ── Daily Verse ─────────────────────────────────────────── -->
<div class="card">
  <div class="card-head"><h3>📖 Daily Verse</h3></div>
  <form method="POST" style="padding:0 0 8px">
    <input type="hidden" name="action" value="save_verse" />
    <div class="form-row">
      <div class="form-group">
        <label>Verse Text</label>
        <textarea name="verse_text" rows="3" placeholder="Type the Bible verse here…"><?= htmlspecialchars($settings['daily_verse_text'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Reference (e.g. Psalm 19:1)</label>
        <input type="text" name="verse_ref" placeholder="Book Chapter:Verse"
               value="<?= htmlspecialchars($settings['daily_verse_ref'] ?? '') ?>" />
      </div>
    </div>
    <button type="submit" class="btn btn-gold" style="margin-top:12px">Save Verse</button>
  </form>
</div>

<!-- ── Banner Management ──────────────────────────────────────── -->
<div class="card" style="margin-top:24px">
  <div class="card-head"><h3>🖼️ Banner Management</h3></div>
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add_banner" />
    <div class="form-row">
      <div class="form-group">
        <label>Headline</label>
        <input type="text" name="headline" placeholder="Banner headline (optional)" />
      </div>
      <div class="form-group">
        <label>Sub-heading</label>
        <input type="text" name="subheading" placeholder="Banner sub-heading (optional)" />
      </div>
    </div>
    <div class="form-group" style="margin-top:12px">
      <label>Image or Video <span style="color:#999;font-weight:400">(JPEG/PNG/WebP/MP4/WebM — max <?= $MAX_MB ?> MB)</span></label>
      <input type="file" name="banner_file" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" />
    </div>
    <button type="submit" class="btn btn-gold" style="margin-top:12px">Add Banner</button>
  </form>

  <?php if ($banners): ?>
  <table style="margin-top:20px">
    <thead><tr><th>#</th><th>Preview</th><th>Headline</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($banners as $i => $b): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td>
          <?php if ($b['media_path']): ?>
            <?php if ($b['media_type'] === 'image'): ?>
              <img src="/<?= htmlspecialchars($b['media_path']) ?>" style="height:40px;width:72px;object-fit:cover;border-radius:4px" />
            <?php else: ?>
              <video src="/<?= htmlspecialchars($b['media_path']) ?>" style="height:40px;width:72px;object-fit:cover;border-radius:4px" muted></video>
            <?php endif; ?>
          <?php else: ?>
            <span style="color:#ccc;font-size:0.75rem">Text only</span>
          <?php endif; ?>
        </td>
        <td>
          <strong><?= htmlspecialchars($b['headline'] ?: '(no headline)') ?></strong>
          <?php if ($b['subheading']): ?>
            <div style="font-size:0.78rem;color:#999"><?= htmlspecialchars(mb_substr($b['subheading'], 0, 60)) ?></div>
          <?php endif; ?>
        </td>
        <td><span class="badge badge-<?= $b['media_type'] === 'video' ? 'member' : 'volunteer' ?>"><?= strtoupper($b['media_type']) ?></span></td>
        <td><?= $b['is_active'] ? '<span style="color:#1a7a4a;font-weight:700">Active</span>' : '<span style="color:#c00">Inactive</span>' ?></td>
        <td>
          <div class="actions">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="toggle_banner">
              <input type="hidden" name="id" value="<?= $b['id'] ?>">
              <button type="submit" class="btn-edit-sm"><?= $b['is_active'] ? 'Deactivate' : 'Activate' ?></button>
            </form>
            <form method="POST" style="display:inline" onsubmit="return confirm('Delete this banner?')">
              <input type="hidden" name="action" value="delete_banner">
              <input type="hidden" name="id" value="<?= $b['id'] ?>">
              <button type="submit" class="btn-danger-sm">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="empty-state" style="padding:32px 0"><p>No banners uploaded yet.</p></div>
  <?php endif; ?>
</div>

<!-- ── Featured Events ────────────────────────────────────────── -->
<div class="card" style="margin-top:24px">
  <div class="card-head">
    <h3>📅 Featured Events (shown on Home Page)</h3>
    <p style="color:var(--text-light);font-size:0.88rem;margin-top:4px">Toggle which events appear in the highlighted section on the home page.</p>
  </div>
  <?php if ($events): ?>
  <table>
    <thead><tr><th>#</th><th>Event</th><th>Date</th><th>Featured</th><th>Toggle</th></tr></thead>
    <tbody>
      <?php foreach ($events as $i => $ev): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td style="font-weight:600"><?= htmlspecialchars($ev['title']) ?></td>
        <td style="font-size:0.82rem;color:#666"><?= htmlspecialchars($ev['event_date'] ?? '—') ?></td>
        <td><?= $ev['is_featured'] ? '<span style="color:#c9a227;font-weight:700">★ Featured</span>' : '<span style="color:#ccc">—</span>' ?></td>
        <td>
          <form method="POST" style="display:inline">
            <input type="hidden" name="action" value="feature_event">
            <input type="hidden" name="id" value="<?= $ev['id'] ?>">
            <input type="hidden" name="feature" value="<?= $ev['is_featured'] ? 0 : 1 ?>">
            <button type="submit" class="btn-edit-sm" style="<?= $ev['is_featured'] ? '' : 'background:#c9a227;color:#fff' ?>">
              <?= $ev['is_featured'] ? 'Unfeature' : '★ Feature' ?>
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="empty-state" style="padding:32px 0"><p>No events yet. Add events first.</p></div>
  <?php endif; ?>
</div>

<?php require_once '_footer.php'; ?>
