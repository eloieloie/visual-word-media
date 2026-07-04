<?php
require_once '_auth.php';

$db = getDB();

// ── Handle POST ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'set_role') {
        $id   = intval($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? '';
        if ($id && in_array($role, ['admin', 'user'])) {
            // Prevent removing own admin role
            if ($id === (int)$adminUser['id'] && $role !== 'admin') {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'You cannot remove your own admin role.'];
            } else {
                $stmt = $db->prepare('UPDATE users SET role = ? WHERE id = ?');
                $stmt->execute([$role, $id]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'User role updated.'];
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id && $id !== (int)$adminUser['id']) {
            $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'User deleted.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Cannot delete your own account.'];
        }
    }

    header('Location: users.php');
    exit();
}

// ── Fetch users ───────────────────────────────────────────────
$users = $db->query(
    'SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC'
)->fetchAll();

$pageTitle   = 'Users';
$currentPage = 'users';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>Registered Users (<?= count($users) ?>)</h3>
  </div>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($users): ?>
        <?php foreach ($users as $i => $u): ?>
        <tr>
          <td style="color:#ccc;font-size:0.78rem"><?= $i + 1 ?></td>
          <td style="font-weight:600"><?= htmlspecialchars($u['name']) ?></td>
          <td style="color:#666;font-size:0.82rem"><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge badge-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
          <td style="color:#999;font-size:0.8rem"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          <td>
            <div class="actions">
              <?php if ($u['role'] === 'user'): ?>
                <form method="POST" style="display:inline">
                  <input type="hidden" name="action" value="set_role">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <input type="hidden" name="role" value="admin">
                  <button type="submit" class="btn-edit-sm" title="Promote to admin">Make Admin</button>
                </form>
              <?php elseif ($u['id'] != $adminUser['id']): ?>
                <form method="POST" style="display:inline" onsubmit="return confirm('Remove admin role?')">
                  <input type="hidden" name="action" value="set_role">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <input type="hidden" name="role" value="user">
                  <button type="submit" class="btn-edit-sm">Revoke Admin</button>
                </form>
              <?php else: ?>
                <span style="font-size:0.75rem;color:#ccc">You</span>
              <?php endif; ?>

              <?php if ($u['id'] != $adminUser['id']): ?>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete user <?= htmlspecialchars(addslashes($u['name'])) ?>?')">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <button type="submit" class="btn-danger-sm">Delete</button>
                </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="6">No users registered yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once '_footer.php'; ?>
