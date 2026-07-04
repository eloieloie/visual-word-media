<?php
require_once '_auth.php';

$db = getDB();

$MAX_MB   = 200;
$UPLOAD_DIR = __DIR__ . '/../uploads/resources/';
if (!is_dir($UPLOAD_DIR)) { @mkdir($UPLOAD_DIR, 0755, true); }

const CATEGORY_LABELS = [
    'articles'          => 'Articles',
    'bible_studies'     => 'Bible Studies',
    'media_awareness'   => 'Media Awareness',
    'family_guidance'   => 'Family Guidance',
    'youth_discipleship'=> 'Youth Discipleship',
    'creative_arts'     => 'Creative Arts',
    'video_teachings'   => 'Video Teachings',
    'audio_messages'    => 'Audio Messages',
];

const ALLOWED_TYPES = [
    'articles'          => ['video','pdf'],
    'bible_studies'     => ['video','pdf'],
    'media_awareness'   => ['video','pdf'],
    'family_guidance'   => ['video'],
    'youth_discipleship'=> ['pdf'],
    'creative_arts'     => ['pdf'],
    'video_teachings'   => ['video'],
    'audio_messages'    => ['audio'],
];

const MIME_MAP = [
    'video/mp4'           => 'video',
    'video/webm'          => 'video',
    'video/ogg'           => 'video',
    'video/quicktime'     => 'video',
    'audio/mpeg'          => 'audio',
    'audio/mp4'           => 'audio',
    'audio/x-m4a'         => 'audio',
    'audio/wav'           => 'audio',
    'audio/ogg'           => 'audio',
    'application/pdf'     => 'pdf',
];

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload') {
        $category = $_POST['category'] ?? '';
        $title    = trim($_POST['title'] ?? '');
        $desc     = trim($_POST['description'] ?? '');

        if (!array_key_exists($category, CATEGORY_LABELS)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Invalid category.'];
        } elseif (!$title) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Title is required.'];
        } elseif (empty($_FILES['resource_file']['tmp_name'])) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Please select a file.'];
        } else {
            $f    = $_FILES['resource_file'];
            $mime = mime_content_type($f['tmp_name']);
            $type = MIME_MAP[$mime] ?? null;

            if (!$type) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Unsupported file type: ' . htmlspecialchars($mime)];
            } elseif (!in_array($type, ALLOWED_TYPES[$category], true)) {
                $allowed = implode(', ', ALLOWED_TYPES[$category]);
                $_SESSION['flash'] = ['type' => 'error', 'msg' => "Category '{$category}' only allows: {$allowed}"];
            } elseif ($f['size'] > $MAX_MB * 1024 * 1024) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => "File too large (max {$MAX_MB} MB)"];
            } else {
                $ext      = pathinfo($f['name'], PATHINFO_EXTENSION);
                $safeName = preg_replace('/[^a-z0-9_\-]/i', '_', pathinfo($f['name'], PATHINFO_FILENAME));
                $stored   = $safeName . '_' . time() . '.' . $ext;
                move_uploaded_file($f['tmp_name'], $UPLOAD_DIR . $stored);

                $db->prepare(
                    'INSERT INTO resources (category, title, description, file_path, file_name, file_type, file_size)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                )->execute([$category, $title, $desc, 'uploads/resources/' . $stored, $f['name'], $type, $f['size']]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Resource uploaded.'];
            }
        }
    } elseif ($action === 'toggle') {
        $id = intval($_POST['id'] ?? 0);
        $db->prepare('UPDATE resources SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status toggled.'];
    } elseif ($action === 'delete') {
        $id  = intval($_POST['id'] ?? 0);
        $row = $db->prepare('SELECT file_path FROM resources WHERE id = ?');
        $row->execute([$id]);
        $r   = $row->fetch();
        if ($r) {
            @unlink(__DIR__ . '/../' . $r['file_path']);
            $db->prepare('DELETE FROM resources WHERE id = ?')->execute([$id]);
        }
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Resource deleted.'];
    }

    header('Location: resources.php');
    exit();
}

// ── Fetch ─────────────────────────────────────────────────────────────────────
$resources = $db->query(
    'SELECT * FROM resources ORDER BY category, created_at DESC'
)->fetchAll();

$grouped = [];
foreach ($resources as $r) { $grouped[$r['category']][] = $r; }

$pageTitle   = 'Resources';
$currentPage = 'resources';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>Upload Resource</h3>
  </div>
  <form method="POST" enctype="multipart/form-data" class="form-grid">
    <input type="hidden" name="action" value="upload" />
    <div class="form-group">
      <label>Category *</label>
      <select name="category" required>
        <option value="">Select category</option>
        <?php foreach (CATEGORY_LABELS as $key => $label): ?>
          <option value="<?= $key ?>"><?= htmlspecialchars($label) ?>
            (<?= implode(', ', ALLOWED_TYPES[$key]) ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Title *</label>
      <input type="text" name="title" placeholder="Resource title" required />
    </div>
    <div class="form-group" style="grid-column:1/-1">
      <label>Description</label>
      <textarea name="description" rows="2" placeholder="Short description (optional)"></textarea>
    </div>
    <div class="form-group" style="grid-column:1/-1">
      <label>File * <span style="color:#999;font-weight:400">(Video: MP4/WebM | Audio: MP3/M4A | PDF — max <?= $MAX_MB ?> MB)</span></label>
      <input type="file" name="resource_file" accept="video/*,audio/*,application/pdf" required />
    </div>
    <div style="grid-column:1/-1">
      <button type="submit" class="btn btn-gold">Upload Resource</button>
    </div>
  </form>
</div>

<?php foreach (CATEGORY_LABELS as $key => $label): ?>
<?php $items = $grouped[$key] ?? []; ?>
<div class="card" style="margin-top:24px">
  <div class="card-head">
    <h3><?= htmlspecialchars($label) ?> (<?= count($items) ?>)
      <small style="font-size:0.75rem;color:#999;font-weight:400;margin-left:8px">
        Allowed: <?= implode(', ', ALLOWED_TYPES[$key]) ?>
      </small>
    </h3>
  </div>
  <?php if ($items): ?>
  <table>
    <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Size</th><th>Status</th><th>Uploaded</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($items as $i => $r): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td style="font-weight:600">
          <?= htmlspecialchars($r['title']) ?>
          <div style="font-size:0.76rem;color:#999"><?= htmlspecialchars($r['file_name']) ?></div>
        </td>
        <td><span class="badge badge-<?= $r['file_type'] === 'video' ? 'member' : ($r['file_type'] === 'audio' ? 'registrant' : 'volunteer') ?>"><?= strtoupper($r['file_type']) ?></span></td>
        <td style="font-size:0.8rem;color:#999"><?= round($r['file_size']/1048576, 1) ?> MB</td>
        <td><?= $r['is_active'] ? '<span style="color:#1a7a4a;font-weight:700">Active</span>' : '<span style="color:#c00">Inactive</span>' ?></td>
        <td style="font-size:0.8rem;color:#999"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
        <td>
          <div class="actions">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button type="submit" class="btn-edit-sm"><?= $r['is_active'] ? 'Deactivate' : 'Activate' ?></button>
            </form>
            <form method="POST" style="display:inline"
                  onsubmit="return confirm('Delete this resource? File will be removed.')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button type="submit" class="btn-danger-sm">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="empty-state" style="padding:32px 0"><p>No resources in this category yet.</p></div>
  <?php endif; ?>
</div>
<?php endforeach; ?>

<?php require_once '_footer.php'; ?>
