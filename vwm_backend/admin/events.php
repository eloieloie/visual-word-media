<?php
require_once '_auth.php';

$db = getDB();

$CATEGORIES = ['Youth Camps','Media Seminars','Creative Arts','Bible Studies','Training Workshops','Prayer Meetings','Leadership Sessions'];
$MONTHS     = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

// ── Handle POST actions ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id       = intval($_POST['id'] ?? 0);
        $month    = strtoupper(substr(trim($_POST['month'] ?? ''), 0, 3));
        $day      = trim($_POST['day'] ?? '');
        $title    = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $desc     = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $time     = trim($_POST['time'] ?? '');

        if (!$month || !$day || !$title || !$category || !$desc || !$location || !$time) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'All fields are required.'];
        } else {
            if ($id) {
                $stmt = $db->prepare('UPDATE events SET month=?,day=?,title=?,category=?,description=?,location=?,time=? WHERE id=?');
                $stmt->execute([$month,$day,$title,$category,$desc,$location,$time,$id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Event updated successfully.'];
            } else {
                $stmt = $db->prepare('INSERT INTO events (month,day,title,category,description,location,time) VALUES (?,?,?,?,?,?,?)');
                $stmt->execute([$month,$day,$title,$category,$desc,$location,$time]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Event created successfully.'];
            }
        }
    } elseif ($action === 'feature') {
        $id      = intval($_POST['id']      ?? 0);
        $feature = intval($_POST['feature'] ?? 0);
        if ($id) {
            $db->prepare('UPDATE events SET is_featured = ? WHERE id = ?')->execute([$feature, $id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => $feature ? 'Event marked as featured.' : 'Event unfeatured.'];
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $db->prepare('UPDATE events SET is_active = 0 WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Event removed.'];
        }
    }

    header('Location: events.php');
    exit();
}

// ── Fetch events ──────────────────────────────────────────────
$events = $db->query(
    'SELECT * FROM events WHERE is_active = 1
     ORDER BY FIELD(month,"JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"),
              CAST(day AS UNSIGNED)'
)->fetchAll();

$pageTitle   = 'Events';
$currentPage = 'events';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>All Events (<?= count($events) ?>)</h3>
    <button class="btn btn-gold" onclick="openModal()">+ Add Event</button>
  </div>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Title</th>
        <th>Category</th>
        <th>Location</th>
        <th>Time</th>
        <th>Featured</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($events): ?>
        <?php foreach ($events as $e): ?>
        <tr>
          <td><strong style="color:#1a2d5a"><?= htmlspecialchars($e['month']) ?> <?= htmlspecialchars($e['day']) ?></strong></td>
          <td style="max-width:220px">
            <div style="font-weight:600;color:#1a2d5a;font-size:0.87rem"><?= htmlspecialchars($e['title']) ?></div>
            <div style="font-size:0.75rem;color:#999;margin-top:2px"><?= mb_strimwidth(htmlspecialchars($e['description']), 0, 60, '…') ?></div>
          </td>
          <td><span class="badge badge-cat"><?= htmlspecialchars($e['category']) ?></span></td>
          <td style="color:#666"><?= htmlspecialchars($e['location']) ?></td>
          <td style="color:#666;font-size:0.82rem"><?= htmlspecialchars($e['time']) ?></td>
          <td>
            <?php $featured = !empty($e['is_featured']); ?>
            <?php if ($featured): ?>
              <span style="color:var(--gold);font-weight:700;font-size:0.82rem">★ Featured</span>
            <?php else: ?>
              <span style="color:var(--tx3);font-size:0.82rem">—</span>
            <?php endif; ?>
          </td>
          <td>
            <div class="actions">
              <button class="btn-edit-sm" onclick='editEvent(<?= json_encode($e) ?>)'>Edit</button>
              <form method="POST" style="display:inline">
                <input type="hidden" name="action" value="feature">
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <input type="hidden" name="feature" value="<?= $featured ? 0 : 1 ?>">
                <button type="submit" class="btn-edit-sm" style="<?= $featured ? '' : 'border-color:var(--gold);color:var(--gold)' ?>">
                  <?= $featured ? 'Unfeature' : '★ Feature' ?>
                </button>
              </form>
              <form method="POST" style="display:inline" onsubmit="return confirm('Remove this event?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                <button type="submit" class="btn-danger-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="7">No events yet. Add one above.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- ── Add / Edit Modal ───────────────────────────────────── -->
<div id="modal" class="modal-bg">
  <div class="modal">
    <div class="modal-head">
      <h3 id="modal-title">Add Event</h3>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <form method="POST" id="event-form">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" id="f-id">

        <div class="form-row3">
          <div class="form-group">
            <label>Month</label>
            <select name="month" id="f-month" class="form-control" required>
              <?php foreach ($MONTHS as $m): ?>
                <option value="<?= $m ?>"><?= $m ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Day</label>
            <input type="number" name="day" id="f-day" class="form-control" min="1" max="31" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select name="category" id="f-category" class="form-control" required>
              <?php foreach ($CATEGORIES as $c): ?>
                <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Title</label>
          <input type="text" name="title" id="f-title" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" id="f-desc" class="form-control" required></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" id="f-location" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Time</label>
            <input type="text" name="time" id="f-time" class="form-control" placeholder="e.g. 9 AM – 5 PM" required>
          </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:8px">
          <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submit-btn">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openModal(title) {
  document.getElementById('modal-title').textContent = title || 'Add Event';
  document.getElementById('modal').classList.add('open');
}
function closeModal() {
  document.getElementById('modal').classList.remove('open');
  document.getElementById('event-form').reset();
  document.getElementById('f-id').value = '';
  document.getElementById('modal-title').textContent = 'Add Event';
  document.getElementById('submit-btn').textContent = 'Save Event';
}
function editEvent(e) {
  document.getElementById('f-id').value       = e.id;
  document.getElementById('f-month').value    = e.month;
  document.getElementById('f-day').value      = e.day;
  document.getElementById('f-title').value    = e.title;
  document.getElementById('f-category').value = e.category;
  document.getElementById('f-desc').value     = e.description;
  document.getElementById('f-location').value = e.location;
  document.getElementById('f-time').value     = e.time;
  document.getElementById('submit-btn').textContent = 'Update Event';
  openModal('Edit Event');
}
// Close on backdrop click
document.getElementById('modal').addEventListener('click', function(ev) {
  if (ev.target === this) closeModal();
});
</script>

<?php require_once '_footer.php'; ?>
