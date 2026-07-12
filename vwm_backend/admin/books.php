<?php
require_once '_auth.php';

$db = getDB();
$UPLOAD_DIR = __DIR__ . '/../uploads/books/';
if (!is_dir($UPLOAD_DIR)) { @mkdir($UPLOAD_DIR, 0755, true); }

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title    = trim($_POST['title']       ?? '');
        $desc     = trim($_POST['description'] ?? '');
        $price    = floatval($_POST['price']   ?? 0);
        $currency = trim($_POST['currency']    ?? 'INR');

        if (!$title) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Title is required.'];
        } else {
            $coverPath = null;
            if (!empty($_FILES['cover_image']['tmp_name'])) {
                $f    = $_FILES['cover_image'];
                $mime = mime_content_type($f['tmp_name']);
                if (!in_array($mime, ['image/jpeg','image/png','image/webp','image/gif'], true)) {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Cover image must be JPEG, PNG, or WebP.'];
                    header('Location: books.php'); exit();
                }
                $ext  = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'][$mime];
                $fn   = preg_replace('/[^a-z0-9_]/i','_', pathinfo($f['name'], PATHINFO_FILENAME)) . '_' . time() . '.' . $ext;
                move_uploaded_file($f['tmp_name'], $UPLOAD_DIR . $fn);
                $coverPath = 'uploads/books/' . $fn;
            }
            $db->prepare(
                'INSERT INTO books (title, description, cover_image, price, currency) VALUES (?, ?, ?, ?, ?)'
            )->execute([$title, $desc, $coverPath, $price, $currency]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book added.'];
        }
    } elseif ($action === 'toggle') {
        $id = intval($_POST['id'] ?? 0);
        $db->prepare('UPDATE books SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book status toggled.'];
    } elseif ($action === 'delete') {
        $id  = intval($_POST['id'] ?? 0);
        $row = $db->prepare('SELECT cover_image FROM books WHERE id = ?');
        $row->execute([$id]);
        $b   = $row->fetch();
        if ($b && $b['cover_image']) { @unlink(__DIR__ . '/../' . $b['cover_image']); }
        $db->prepare('DELETE FROM books WHERE id = ?')->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Book deleted.'];
    }

    header('Location: books.php');
    exit();
}

$books       = $db->query('SELECT * FROM books ORDER BY created_at DESC')->fetchAll();
$pageTitle   = 'Books';
$currentPage = 'books';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head"><h3>Add New Book</h3></div>
  <form method="POST" enctype="multipart/form-data" class="form-grid">
    <input type="hidden" name="action" value="add" />
    <div class="form-group">
      <label>Book Title *</label>
      <input type="text" name="title" class="form-control" placeholder="Book title" required />
    </div>
    <div class="form-group">
      <label>Price</label>
      <div style="display:flex;gap:8px">
        <input type="number" name="price" min="0" step="0.01" placeholder="0.00" class="form-control" style="flex:1" />
        <select name="currency" class="form-control" style="width:90px">
          <option value="INR">₹ INR</option>
          <option value="USD">$ USD</option>
        </select>
      </div>
    </div>
    <div class="form-group" style="grid-column:1/-1">
      <label>Description</label>
      <textarea name="description" rows="3" class="form-control" placeholder="Short description"></textarea>
    </div>
    <div class="form-group" style="grid-column:1/-1">
      <label>Cover Image <span style="color:#999;font-weight:400">(JPEG, PNG, WebP)</span></label>
      <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp" />
    </div>
    <div style="grid-column:1/-1">
      <button type="submit" class="btn btn-gold">Add Book</button>
    </div>
  </form>
</div>

<div class="card" style="margin-top:24px">
  <div class="card-head"><h3>All Books (<?= count($books) ?>)</h3></div>
  <?php if ($books): ?>
  <table>
    <thead><tr><th>#</th><th>Cover</th><th>Title</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($books as $i => $b): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td>
          <?php if ($b['cover_image']): ?>
            <img src="/<?= htmlspecialchars($b['cover_image']) ?>" style="width:48px;height:66px;object-fit:cover;border-radius:4px;border:1px solid #eee" />
          <?php else: ?>
            <span style="font-size:0.75rem;color:#ccc">—</span>
          <?php endif; ?>
        </td>
        <td style="font-weight:600"><?= htmlspecialchars($b['title']) ?></td>
        <td style="font-size:0.82rem;color:#666;max-width:240px"><?= htmlspecialchars(mb_substr($b['description'] ?? '', 0, 80)) ?><?= strlen($b['description'] ?? '') > 80 ? '…' : '' ?></td>
        <td style="font-size:0.9rem;font-weight:600;color:#1a2d5a">
          <?= $b['currency'] ?> <?= number_format((float)$b['price'], 2) ?>
        </td>
        <td><?= $b['is_active'] ? '<span style="color:#1a7a4a;font-weight:700">Active</span>' : '<span style="color:#c00">Inactive</span>' ?></td>
        <td>
          <div class="actions">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= $b['id'] ?>">
              <button type="submit" class="btn-edit-sm"><?= $b['is_active'] ? 'Deactivate' : 'Activate' ?></button>
            </form>
            <form method="POST" style="display:inline"
                  onsubmit="return confirm('Delete book <?= htmlspecialchars(addslashes($b['title'])) ?>?')">
              <input type="hidden" name="action" value="delete">
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
  <div class="empty-state" style="padding:60px 0"><p>No books added yet.</p></div>
  <?php endif; ?>
</div>

<?php require_once '_footer.php'; ?>
