<?php
require_once '_auth.php';

$db         = getDB();
$UPLOAD_DIR = dirname(__DIR__) . '/uploads/audio/';
$ALLOWED    = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/mp4', 'audio/x-m4a', 'audio/aac', 'audio/webm'];
$MAX_MB     = 50;

// Ensure upload dir exists
if (!is_dir($UPLOAD_DIR)) {
    mkdir($UPLOAD_DIR, 0755, true);
    file_put_contents($UPLOAD_DIR . '.htaccess', "php_flag engine off\nOptions -Indexes\n");
}

// ── Handle POST ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload') {
        $title    = trim($_POST['title'] ?? '');
        $speaker  = trim($_POST['speaker'] ?? '');
        $desc     = trim($_POST['description'] ?? '');
        $file     = $_FILES['audio_file'] ?? null;

        if (!$title) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Title is required.'];
        } elseif (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'No file selected or upload error.'];
        } elseif ($file['size'] > $MAX_MB * 1024 * 1024) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => "File exceeds {$MAX_MB} MB limit."];
        } else {
            // Validate MIME type (check actual file, not just extension)
            $finfo    = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            if (!in_array($mimeType, $ALLOWED) && !str_starts_with($mimeType, 'audio/')) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Invalid file type. Only audio files are accepted.'];
            } else {
                $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
                $fileName = $safeName . '_' . time() . '.' . $ext;
                $destPath = $UPLOAD_DIR . $fileName;

                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    $stmt = $db->prepare(
                        'INSERT INTO audio_teachings (title, speaker, description, file_path, file_name, file_size)
                         VALUES (?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->execute([$title, $speaker, $desc, '/uploads/audio/' . $fileName, $fileName, $file['size']]);
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => "\"$title\" uploaded successfully."];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Failed to move uploaded file. Check server permissions.'];
                }
            }
        }

    } elseif ($action === 'update') {
        $id      = intval($_POST['id'] ?? 0);
        $title   = trim($_POST['title'] ?? '');
        $speaker = trim($_POST['speaker'] ?? '');
        $desc    = trim($_POST['description'] ?? '');
        $order   = intval($_POST['sort_order'] ?? 0);
        if ($id && $title) {
            $stmt = $db->prepare('UPDATE audio_teachings SET title=?, speaker=?, description=?, sort_order=? WHERE id=?');
            $stmt->execute([$title, $speaker, $desc, $order, $id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Media item updated.'];
        }

    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            $row = $db->prepare('SELECT file_name FROM audio_teachings WHERE id = ?');
            $row->execute([$id]);
            $audio = $row->fetch();
            if ($audio && file_exists($UPLOAD_DIR . $audio['file_name'])) {
                unlink($UPLOAD_DIR . $audio['file_name']);
            }
            $db->prepare('DELETE FROM audio_teachings WHERE id = ?')->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Media item deleted.'];
        }
    }

    header('Location: audio.php');
    exit();
}

// ── Fetch all audios ──────────────────────────────────────────
$audios = $db->query(
    'SELECT * FROM audio_teachings ORDER BY sort_order DESC, uploaded_at DESC'
)->fetchAll();

$pageTitle = 'Media';
require_once '_header.php';
?>

<!-- Upload form -->
<div class="card" style="margin-bottom:28px">
  <div class="card-head"><h3>Upload New Media</h3></div>
  <div style="padding:22px 24px">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="upload">
      <div class="form-row">
        <div class="form-group">
          <label>Title *</label>
          <input type="text" name="title" class="form-control" required placeholder="Title">
        </div>
        <div class="form-group">
          <label>Speaker / Pastor</label>
          <input type="text" name="speaker" class="form-control" placeholder="Speaker name">
        </div>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="2" placeholder="Brief description…"></textarea>
      </div>
      <div class="form-group">
        <label>Audio File * <span style="color:#999;font-weight:400">(MP3, M4A, WAV — max <?= $MAX_MB ?> MB)</span></label>
        <input type="file" name="audio_file" class="form-control" accept="audio/*" required>
      </div>
      <button type="submit" class="btn btn-gold">Upload</button>
    </form>
  </div>
</div>

<!-- Existing audios -->
<div class="card">
  <div class="card-head">
    <h3>All Media (<?= count($audios) ?>)</h3>
  </div>
  <table>
    <thead>
      <tr><th>Title</th><th>Speaker</th><th>File</th><th>Size</th><th>Uploaded</th><th>Order</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if ($audios): ?>
        <?php foreach ($audios as $a): ?>
        <tr>
          <td>
            <div style="font-weight:600;color:#1a2d5a"><?= htmlspecialchars($a['title']) ?></div>
            <?php if ($a['description']): ?>
              <div style="font-size:0.75rem;color:#999"><?= mb_strimwidth(htmlspecialchars($a['description']), 0, 60, '…') ?></div>
            <?php endif; ?>
          </td>
          <td style="color:#666"><?= htmlspecialchars($a['speaker'] ?: '—') ?></td>
          <td style="font-size:0.78rem;color:#888"><?= htmlspecialchars($a['file_name']) ?></td>
          <td style="font-size:0.8rem;color:#999"><?= $a['file_size'] ? round($a['file_size']/1048576,1).' MB' : '—' ?></td>
          <td style="font-size:0.78rem;color:#999"><?= date('d M Y', strtotime($a['uploaded_at'])) ?></td>
          <td style="color:#999;font-size:0.85rem"><?= $a['sort_order'] ?></td>
          <td>
            <div class="actions">
              <button class="btn-edit-sm" onclick="openEdit(<?= json_encode($a) ?>)">Edit</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this media item?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                <button type="submit" class="btn-danger-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="7">No media uploaded yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Edit modal -->
<div id="edit-modal" class="modal-bg">
  <div class="modal">
    <div class="modal-head">
      <h3>Edit Media</h3>
      <button class="modal-close" onclick="closeEdit()">×</button>
    </div>
    <div class="modal-body">
      <form method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="e-id">
        <div class="form-group"><label>Title</label><input type="text" name="title" id="e-title" class="form-control" required></div>
        <div class="form-row">
          <div class="form-group"><label>Speaker</label><input type="text" name="speaker" id="e-speaker" class="form-control"></div>
          <div class="form-group"><label>Sort Order (higher = first)</label><input type="number" name="sort_order" id="e-order" class="form-control" value="0"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" id="e-desc" class="form-control"></textarea></div>
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:6px">
          <button type="button" class="btn btn-outline" onclick="closeEdit()">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEdit(a) {
  document.getElementById('e-id').value      = a.id;
  document.getElementById('e-title').value   = a.title;
  document.getElementById('e-speaker').value = a.speaker || '';
  document.getElementById('e-desc').value    = a.description || '';
  document.getElementById('e-order').value   = a.sort_order || 0;
  document.getElementById('edit-modal').classList.add('open');
}
function closeEdit() { document.getElementById('edit-modal').classList.remove('open'); }
document.getElementById('edit-modal').addEventListener('click', e => { if (e.target === e.currentTarget) closeEdit(); });
</script>

<?php require_once '_footer.php'; ?>
