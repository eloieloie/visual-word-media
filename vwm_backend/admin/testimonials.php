<?php
require_once '_auth.php';

$db         = getDB();
$CATEGORIES = ['Youth Testimonies','Media Recovery','Camp Experiences','Creative Artists','Ministry Partners','General'];

// ── Handle POST ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id        = intval($_POST['id'] ?? 0);
        $name      = trim($_POST['name'] ?? '');
        $location  = trim($_POST['location'] ?? '');
        $category  = trim($_POST['category'] ?? '');
        $testimony = trim($_POST['testimony'] ?? '');
        $order     = intval($_POST['sort_order'] ?? 0);
        $active    = isset($_POST['is_active']) ? 1 : 0;

        if (!$name || !$testimony || !$category) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Name, category, and testimony are required.'];
        } elseif ($id) {
            $stmt = $db->prepare('UPDATE testimonials SET name=?,location=?,category=?,testimony=?,sort_order=?,is_active=? WHERE id=?');
            $stmt->execute([$name,$location,$category,$testimony,$order,$active,$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Testimony updated.'];
        } else {
            $stmt = $db->prepare('INSERT INTO testimonials (name,location,category,testimony,sort_order,is_active) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$name,$location,$category,$testimony,$order,$active]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Testimony added.'];
        }

    } elseif ($action === 'toggle') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            $db->prepare('UPDATE testimonials SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Visibility toggled.'];
        }

    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            $db->prepare('DELETE FROM testimonials WHERE id = ?')->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Testimony deleted.'];
        }
    }

    header('Location: testimonials.php');
    exit();
}

// ── Fetch all ────────────────────────────────────────────────
$testimonials = $db->query(
    'SELECT * FROM testimonials ORDER BY sort_order DESC, created_at DESC'
)->fetchAll();

$pageTitle = 'Testimonials';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>All Testimonials (<?= count($testimonials) ?>)</h3>
    <button class="btn btn-gold" onclick="openModal()">+ Add Testimony</button>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Location</th>
        <th>Category</th>
        <th>Testimony</th>
        <th>Order</th>
        <th>Visible</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($testimonials): ?>
        <?php foreach ($testimonials as $i => $t): ?>
        <tr style="<?= !$t['is_active'] ? 'opacity:0.5' : '' ?>">
          <td style="color:#ccc;font-size:0.78rem"><?= $i + 1 ?></td>
          <td>
            <div style="font-weight:700;color:#1a2d5a"><?= htmlspecialchars($t['name']) ?></div>
            <?php if ($t['location']): ?>
              <div style="font-size:0.75rem;color:#999"><?= htmlspecialchars($t['location']) ?></div>
            <?php endif; ?>
          </td>
          <td style="font-size:0.8rem;color:#666"><?= htmlspecialchars($t['location'] ?: '—') ?></td>
          <td><span class="badge badge-cat"><?= htmlspecialchars($t['category']) ?></span></td>
          <td style="max-width:280px">
            <div style="font-size:0.82rem;color:#555;line-height:1.5"><?= mb_strimwidth(htmlspecialchars($t['testimony']), 0, 110, '…') ?></div>
          </td>
          <td style="color:#999;font-size:0.82rem;text-align:center"><?= $t['sort_order'] ?></td>
          <td style="text-align:center">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="toggle">
              <input type="hidden" name="id" value="<?= $t['id'] ?>">
              <button type="submit" style="background:none;border:none;cursor:pointer;font-size:1.1rem" title="Toggle visibility">
                <?= $t['is_active'] ? '✅' : '❌' ?>
              </button>
            </form>
          </td>
          <td>
            <div class="actions">
              <button class="btn-edit-sm" onclick='editTestimony(<?= json_encode($t) ?>)'>Edit</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this testimony?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button type="submit" class="btn-danger-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="8">No testimonials yet. Add one above.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- ── Add / Edit Modal ───────────────────────────────────── -->
<div id="modal" class="modal-bg">
  <div class="modal" style="max-width:620px">
    <div class="modal-head">
      <h3 id="modal-title">Add Testimony</h3>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <form method="POST" id="t-form">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" id="f-id">

        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" id="f-name" class="form-control" required placeholder="e.g. Priya R.">
          </div>
          <div class="form-group">
            <label>Location / Church</label>
            <input type="text" name="location" id="f-location" class="form-control" placeholder="e.g. Hyderabad">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Category *</label>
            <select name="category" id="f-category" class="form-control" required>
              <?php foreach ($CATEGORIES as $c): ?>
                <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Sort Order <span style="font-weight:400;color:#999">(higher = first)</span></label>
            <input type="number" name="sort_order" id="f-order" class="form-control" value="0">
          </div>
        </div>

        <div class="form-group">
          <label>Testimony *</label>
          <textarea name="testimony" id="f-testimony" class="form-control" rows="5" required placeholder="Write the full testimony here…"></textarea>
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:10px">
          <input type="checkbox" name="is_active" id="f-active" value="1" checked style="width:18px;height:18px">
          <label for="f-active" style="margin:0;font-weight:500;cursor:pointer">Visible on the website</label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px">
          <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submit-btn">Save Testimony</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('modal-title').textContent = 'Add Testimony';
  document.getElementById('submit-btn').textContent  = 'Save Testimony';
  document.getElementById('modal').classList.add('open');
}
function closeModal() {
  document.getElementById('modal').classList.remove('open');
  document.getElementById('t-form').reset();
  document.getElementById('f-id').value = '';
  document.getElementById('f-active').checked = true;
}
function editTestimony(t) {
  document.getElementById('f-id').value         = t.id;
  document.getElementById('f-name').value       = t.name;
  document.getElementById('f-location').value   = t.location || '';
  document.getElementById('f-category').value   = t.category;
  document.getElementById('f-testimony').value  = t.testimony;
  document.getElementById('f-order').value      = t.sort_order || 0;
  document.getElementById('f-active').checked   = t.is_active == 1;
  document.getElementById('modal-title').textContent = 'Edit Testimony';
  document.getElementById('submit-btn').textContent  = 'Update Testimony';
  document.getElementById('modal').classList.add('open');
}
document.getElementById('modal').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeModal();
});
</script>

<?php require_once '_footer.php'; ?>
