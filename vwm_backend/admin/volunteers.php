<?php
require_once '_auth.php';

$db = getDB();

// ── Handle POST ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'status') {
            $id     = intval($_POST['id'] ?? 0);
            $allowed = ['new', 'contacted', 'active', 'declined'];
            $status = in_array($_POST['status'] ?? '', $allowed) ? $_POST['status'] : 'new';
            if ($id) {
                $db->prepare('UPDATE volunteer_registrations SET status=? WHERE id=?')->execute([$status, $id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status updated.'];
            }

        } elseif ($action === 'create_user') {
            $name     = trim($_POST['user_name']    ?? '');
            $email    = trim($_POST['user_email']   ?? '');
            $password = $_POST['user_password']     ?? '';
            $role     = in_array($_POST['role'] ?? '', ['user', 'admin']) ? $_POST['role'] : 'user';

            if (!$name || !$email || !$password) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Name, email, and password are required.'];
            } elseif (strlen($password) < 6) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Password must be at least 6 characters.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Invalid email address.'];
            } else {
                $chk = $db->prepare('SELECT id FROM users WHERE email = ?');
                $chk->execute([$email]);
                if ($chk->fetch()) {
                    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'A user with this email already exists.'];
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $db->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)')
                       ->execute([$name, $email, $hash, $role]);
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'User account created for ' . $name . ' (' . $email . ') as ' . $role . '.'];
                }
            }

        } elseif ($action === 'delete') {
            $id = intval($_POST['id'] ?? 0);
            if ($id) {
                $db->prepare('DELETE FROM volunteer_registrations WHERE id=?')->execute([$id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Registration deleted.'];
            }
        }
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Database error: ' . $e->getMessage()];
    }

    header('Location: volunteers.php');
    exit();
}

// ── Filters ───────────────────────────────────────────────────
$filterStatus = $_GET['status'] ?? '';
$search       = trim($_GET['q'] ?? '');

$where  = [];
$params = [];
if ($filterStatus) {
    $where[]  = 'vr.status = ?';
    $params[] = $filterStatus;
}
if ($search) {
    $where[]  = '(vr.name LIKE ? OR vr.email LIKE ? OR vr.mobile LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = 'SELECT vr.*, u.id AS user_id FROM volunteer_registrations vr'
     . ' LEFT JOIN users u ON u.email = vr.email'
     . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
     . ' ORDER BY vr.created_at DESC';

$tableError = false;
$tableMsg   = '';
$volunteers = [];
$counts     = [];
$total      = 0;

try {
    $stmt       = $db->prepare($sql);
    $stmt->execute($params);
    $volunteers = $stmt->fetchAll();
    $counts     = $db->query('SELECT status, COUNT(*) as c FROM volunteer_registrations GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
    $total      = array_sum($counts);
} catch (PDOException $e) {
    $tableError = true;
    $tableMsg   = $e->getMessage();
}

$pageTitle = 'Volunteers';
require_once '_header.php';

function statusBadge($s) {
    if ($s === 'new') {
        return '<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.73rem;font-weight:700;background:#e8edf8;color:#2d4a8a">New</span>';
    } elseif ($s === 'contacted') {
        return '<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.73rem;font-weight:700;background:#fdf5e0;color:#7a5c10">Contacted</span>';
    } elseif ($s === 'active') {
        return '<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.73rem;font-weight:700;background:#e8f5e9;color:#276027">Active</span>';
    } elseif ($s === 'declined') {
        return '<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.73rem;font-weight:700;background:#fdecea;color:#c62828">Declined</span>';
    }
    return htmlspecialchars($s);
}

function jsonList($raw) {
    $arr = json_decode($raw, true);
    if (!$arr || !is_array($arr)) return '-';
    return implode(', ', array_map('htmlspecialchars', $arr));
}
?>

<?php if ($tableError): ?>
<div class="flash flash-err" style="margin-bottom:24px">
  <strong>Table not found.</strong> The <code>volunteer_registrations</code> table does not exist yet.
  <a href="migrate.php" style="color:#c62828;font-weight:700;margin-left:8px">Run DB Migrate &rarr;</a>
  <?php if ($tableMsg): ?>
    <div style="font-size:0.8rem;margin-top:6px;opacity:0.8"><?= htmlspecialchars($tableMsg) ?></div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Stats -->
<div class="stat-grid" style="margin-bottom:20px">
  <div class="stat-card">
    <div class="stat-label">Total</div>
    <div class="stat-val"><?= $total ?></div>
    <div class="stat-sub">Registrations</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">New</div>
    <div class="stat-val"><?= $counts['new'] ?? 0 ?></div>
    <div class="stat-sub">Awaiting contact</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Contacted</div>
    <div class="stat-val"><?= $counts['contacted'] ?? 0 ?></div>
    <div class="stat-sub">In follow-up</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Active</div>
    <div class="stat-val"><?= $counts['active'] ?? 0 ?></div>
    <div class="stat-sub">Serving</div>
  </div>
</div>

<!-- Filters -->
<form method="GET" style="display:flex;gap:12px;align-items:center;margin-bottom:18px;flex-wrap:wrap">
  <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
         placeholder="Search name / email / mobile..."
         class="form-control" style="max-width:280px;padding:8px 12px">
  <select name="status" class="form-control" style="max-width:160px;padding:8px 12px">
    <option value="">All Statuses</option>
    <option value="new"       <?= $filterStatus === 'new'       ? 'selected' : '' ?>>New</option>
    <option value="contacted" <?= $filterStatus === 'contacted' ? 'selected' : '' ?>>Contacted</option>
    <option value="active"    <?= $filterStatus === 'active'    ? 'selected' : '' ?>>Active</option>
    <option value="declined"  <?= $filterStatus === 'declined'  ? 'selected' : '' ?>>Declined</option>
  </select>
  <button type="submit" class="btn btn-primary btn-sm">Filter</button>
  <?php if ($filterStatus || $search): ?>
    <a href="volunteers.php" class="btn btn-outline btn-sm">Clear</a>
  <?php endif; ?>
</form>

<!-- Table -->
<div class="card">
  <div class="card-head">
    <h3>Volunteer Registrations (<?= count($volunteers) ?>)</h3>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Location</th>
        <th>Ministry Areas</th>
        <th>Service Type</th>
        <th>Status</th>
        <th>Account</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($volunteers): ?>
        <?php foreach ($volunteers as $i => $v): ?>
        <tr>
          <td style="color:#ccc;font-size:0.78rem"><?= $i + 1 ?></td>
          <td>
            <div style="font-weight:700;color:#1a2d5a"><?= htmlspecialchars($v['name']) ?></div>
            <?php if ($v['gender']): ?>
              <div style="font-size:0.73rem;color:#999">
                <?= htmlspecialchars($v['gender']) ?>
                <?= $v['dob'] ? ' &middot; ' . date('d M Y', strtotime($v['dob'])) : '' ?>
              </div>
            <?php endif; ?>
          </td>
          <td>
            <div style="font-size:0.85rem"><?= htmlspecialchars($v['mobile']) ?></div>
            <div style="font-size:0.78rem;color:#666"><?= htmlspecialchars($v['email']) ?></div>
          </td>
          <td style="font-size:0.82rem;color:#666">
            <?= htmlspecialchars(implode(', ', array_filter([$v['city'], $v['state'], $v['country']])) ?: '-') ?>
          </td>
          <td style="max-width:200px">
            <div style="font-size:0.78rem;color:#555;line-height:1.5">
              <?= mb_strimwidth(jsonList($v['ministry_areas'] ?? '[]'), 0, 80, '...') ?>
            </div>
          </td>
          <td style="font-size:0.78rem;color:#555">
            <?= mb_strimwidth(jsonList($v['service_type'] ?? '[]'), 0, 50, '...') ?>
          </td>
          <td><?= statusBadge($v['status']) ?></td>
          <td style="text-align:center">
            <?php if ($v['user_id']): ?>
              <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:0.73rem;font-weight:700;background:#e8f5e9;color:#276027">&#10003; User</span>
            <?php else: ?>
              <span style="color:#ccc;font-size:0.78rem">-</span>
            <?php endif; ?>
          </td>
          <td style="font-size:0.78rem;color:#999;white-space:nowrap">
            <?= date('d M Y', strtotime($v['created_at'])) ?>
          </td>
          <td>
            <div class="actions">
              <button class="btn-edit-sm" onclick='openDetail(<?= htmlspecialchars(json_encode($v), ENT_QUOTES) ?>)'>View</button>
              <form method="POST" style="display:inline" onsubmit="return confirm('Delete this registration?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $v['id'] ?>">
                <button type="submit" class="btn-danger-sm">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="10"><?= $tableError ? 'Run DB Migrate to set up this table.' : 'No volunteer registrations yet.' ?></td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="modal-bg">
  <div class="modal" style="max-width:720px">
    <div class="modal-head">
      <h3 id="detail-name">Volunteer Details</h3>
      <button class="modal-close" onclick="closeDetail()">&times;</button>
    </div>
    <div class="modal-body">

      <!-- Status row -->
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:18px;border-bottom:1px solid #eee;flex-wrap:wrap">
        <form method="POST" id="status-form" style="display:flex;align-items:center;gap:10px">
          <input type="hidden" name="action" value="status">
          <input type="hidden" name="id" id="detail-id">
          <label style="font-size:0.82rem;font-weight:700;color:#1a2d5a">Status:</label>
          <select name="status" id="detail-status" class="form-control" style="max-width:160px;padding:7px 10px">
            <option value="new">New</option>
            <option value="contacted">Contacted</option>
            <option value="active">Active</option>
            <option value="declined">Declined</option>
          </select>
          <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>
        <div style="margin-left:auto">
          <button type="button" id="cu-toggle-btn" class="btn btn-gold btn-sm" onclick="toggleCreateUser()">
            Create User Account
          </button>
          <span id="cu-has-account" style="display:none;font-size:0.82rem;color:#276027;font-weight:600">&#10003; Already has a user account</span>
        </div>
      </div>

      <!-- Create User Form -->
      <div id="create-user-panel" style="display:none;background:#f7f9ff;border:1.5px solid #c5d0ec;border-radius:8px;padding:20px 22px;margin-bottom:20px">
        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#1a2d5a;margin-bottom:14px">Create User Account</div>
        <form method="POST" id="create-user-form">
          <input type="hidden" name="action" value="create_user">
          <input type="hidden" name="vol_id" id="cu-vol-id">
          <div class="form-row" style="margin-bottom:12px">
            <div class="form-group">
              <label>Name</label>
              <input type="text" name="user_name" id="cu-name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="user_email" id="cu-email" class="form-control" required>
            </div>
          </div>
          <div class="form-row" style="margin-bottom:16px">
            <div class="form-group">
              <label>Password <span style="font-weight:400;color:#999">(min. 6 chars)</span></label>
              <input type="password" name="user_password" id="cu-password" class="form-control" required minlength="6" placeholder="Set a temporary password">
            </div>
            <div class="form-group">
              <label>Role</label>
              <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end;gap:10px">
            <button type="button" class="btn btn-outline btn-sm" onclick="toggleCreateUser()">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Create Account</button>
          </div>
        </form>
      </div>

      <div id="detail-content"></div>
    </div>
  </div>
</div>

<style>
.detail-section { margin-bottom:18px }
.detail-section h4 { font-size:0.72rem;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;color:#fff;background:#1a2d5a;padding:7px 12px;border-radius:4px;margin-bottom:12px }
.detail-row { display:grid;grid-template-columns:160px 1fr;gap:6px 16px;margin-bottom:8px;font-size:0.87rem }
.detail-label { color:#999;font-weight:600 }
.detail-val { color:#2c2c2c }
.tag-list { display:flex;flex-wrap:wrap;gap:6px }
.tag { background:#fdf5e0;color:#7a5c10;border:1px solid #f0dfa0;border-radius:20px;padding:3px 10px;font-size:0.75rem;font-weight:700 }
</style>

<script>
var cuPanelOpen = false;

function openDetail(v) {
  document.getElementById('detail-name').textContent     = v.name;
  document.getElementById('detail-id').value             = v.id;
  document.getElementById('detail-status').value         = v.status;
  document.getElementById('cu-vol-id').value             = v.id;
  document.getElementById('cu-name').value               = v.name;
  document.getElementById('cu-email').value              = v.email;
  document.getElementById('cu-password').value           = '';
  document.getElementById('create-user-panel').style.display = 'none';
  cuPanelOpen = false;

  if (v.user_id) {
    document.getElementById('cu-toggle-btn').style.display  = 'none';
    document.getElementById('cu-has-account').style.display = 'inline';
  } else {
    document.getElementById('cu-toggle-btn').style.display  = 'inline-flex';
    document.getElementById('cu-has-account').style.display = 'none';
  }

  function row(label, val) {
    if (!val) return '';
    return '<div class="detail-row"><span class="detail-label">' + label + '</span><span class="detail-val">' + val + '</span></div>';
  }
  function tags(raw) {
    try {
      var arr = JSON.parse(raw);
      if (!arr || !arr.length) return '-';
      return '<div class="tag-list">' + arr.map(function(t){ return '<span class="tag">' + t + '</span>'; }).join('') + '</div>';
    } catch(e) { return '-'; }
  }
  function yn(val) { return val == 1 ? 'Yes' : 'No'; }
  function joinArr(raw) {
    try { var a = JSON.parse(raw); return a.join(', ') || '-'; } catch(e) { return '-'; }
  }

  var dobStr = '';
  if (v.dob) {
    var d = new Date(v.dob);
    dobStr = d.toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'});
  }

  var html = '';
  html += '<div class="detail-section"><h4>Personal Information</h4>';
  html += row('Gender', v.gender);
  html += row('Date of Birth', dobStr);
  html += row('Mobile', v.mobile);
  html += row('WhatsApp', v.whatsapp);
  html += row('Email', '<a href="mailto:' + v.email + '">' + v.email + '</a>');
  html += row('Location', [v.city, v.state, v.country].filter(Boolean).join(', '));
  html += '</div>';

  html += '<div class="detail-section"><h4>Spiritual Background</h4>';
  html += row('Follower of Jesus?', yn(v.is_believer));
  html += row('Church Active?', yn(v.church_active));
  html += row('Church Name', v.church_name);
  html += row('Pastor', v.pastor_name);
  if (v.personal_testimony) {
    html += '<div style="background:#fafbfc;border:1px solid #eee;border-radius:6px;padding:12px 16px;font-size:0.87rem;color:#555;line-height:1.75;margin-top:4px;font-style:italic">&ldquo;' + v.personal_testimony + '&rdquo;</div>';
  }
  html += '</div>';

  html += '<div class="detail-section"><h4>Ministry Interests</h4>';
  html += '<div style="margin-bottom:8px"><span class="detail-label" style="font-size:0.82rem;color:#999">Ministry Areas</span></div>';
  html += '<div style="margin-bottom:12px">' + tags(v.ministry_areas) + '</div>';
  html += row('Service Type', joinArr(v.service_type));
  html += row('Availability', joinArr(v.availability));
  html += '</div>';

  html += '<div class="detail-section"><h4>Additional Information</h4>';
  html += row('Occupation', v.occupation);
  html += row('Organization', v.organization);
  if (v.skills) html += '<div class="detail-row"><span class="detail-label">Skills</span><span class="detail-val" style="white-space:pre-wrap">' + v.skills + '</span></div>';
  if (v.motivation) html += '<div style="margin-top:8px"><span class="detail-label" style="font-size:0.82rem;color:#999;display:block;margin-bottom:4px">Why join?</span><div style="font-size:0.87rem;color:#555;line-height:1.75">' + v.motivation + '</div></div>';
  if (v.comments) html += '<div style="margin-top:8px"><span class="detail-label" style="font-size:0.82rem;color:#999;display:block;margin-bottom:4px">Comments</span><div style="font-size:0.87rem;color:#555;line-height:1.75">' + v.comments + '</div></div>';
  html += '</div>';

  html += '<div style="font-size:0.78rem;color:#aaa;text-align:right">Registered: ' + new Date(v.created_at).toLocaleString('en-IN', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}) + '</div>';

  document.getElementById('detail-content').innerHTML = html;
  document.getElementById('detail-modal').classList.add('open');
}

function toggleCreateUser() {
  cuPanelOpen = !cuPanelOpen;
  document.getElementById('create-user-panel').style.display = cuPanelOpen ? 'block' : 'none';
  if (cuPanelOpen) document.getElementById('cu-password').focus();
}

function closeDetail() {
  document.getElementById('detail-modal').classList.remove('open');
  document.getElementById('create-user-panel').style.display = 'none';
  cuPanelOpen = false;
}

document.getElementById('detail-modal').addEventListener('click', function(e) {
  if (e.target === e.currentTarget) closeDetail();
});
</script>

<?php require_once '_footer.php'; ?>
