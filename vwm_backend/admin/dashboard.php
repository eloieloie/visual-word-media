<?php
require_once '_auth.php';

$db = getDB();

$totalEvents  = $db->query("SELECT COUNT(*) FROM events WHERE is_active = 1")->fetchColumn();
$totalUsers   = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$adminUsers   = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$recentEvents = $db->query(
    "SELECT * FROM events WHERE is_active = 1 ORDER BY created_at DESC LIMIT 5"
)->fetchAll();
$recentUsers  = $db->query(
    "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5"
)->fetchAll();

$pageTitle = 'Dashboard';
require_once '_header.php';
?>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-label">Active Events</div>
    <div class="stat-val"><?= $totalEvents ?></div>
    <div class="stat-sub">Upcoming &amp; ongoing</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Registered Users</div>
    <div class="stat-val"><?= $totalUsers ?></div>
    <div class="stat-sub"><?= $adminUsers ?> admin(s)</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Categories</div>
    <div class="stat-val">7</div>
    <div class="stat-sub">Event types</div>
  </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px">

  <!-- Recent Events -->
  <div class="card">
    <div class="card-head">
      <h3>Recent Events</h3>
      <a href="events.php" class="btn btn-sm btn-outline">Manage</a>
    </div>
    <table>
      <thead>
        <tr><th>Date</th><th>Title</th><th>Category</th></tr>
      </thead>
      <tbody>
        <?php if ($recentEvents): ?>
          <?php foreach ($recentEvents as $e): ?>
          <tr>
            <td><strong><?= htmlspecialchars($e['month']) ?> <?= htmlspecialchars($e['day']) ?></strong></td>
            <td><?= htmlspecialchars($e['title']) ?></td>
            <td><span class="badge badge-cat"><?= htmlspecialchars($e['category']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr class="empty-row"><td colspan="3">No events yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Recent Users -->
  <div class="card">
    <div class="card-head">
      <h3>Recent Users</h3>
      <a href="users.php" class="btn btn-sm btn-outline">Manage</a>
    </div>
    <table>
      <thead>
        <tr><th>Name</th><th>Email</th><th>Role</th></tr>
      </thead>
      <tbody>
        <?php if ($recentUsers): ?>
          <?php foreach ($recentUsers as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td style="color:#888; font-size:0.8rem"><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge badge-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr class="empty-row"><td colspan="3">No users yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<?php require_once '_footer.php'; ?>
