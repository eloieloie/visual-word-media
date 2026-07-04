<?php
require_once '_auth.php';
require_once __DIR__ . '/../includes/mailer.php';

$db = getDB();

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = intval($_POST['id'] ?? 0);

    if ($action === 'reset_password' && $id) {
        $row = $db->prepare("SELECT name, email FROM users WHERE id = ? AND role IN ('member','volunteer')");
        $row->execute([$id]);
        $user = $row->fetch();
        if ($user) {
            $db->prepare('UPDATE users SET force_password_reset = 1 WHERE id = ?')->execute([$id]);
            $safeName = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
            $html = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">Password Reset Required</h2>
  <p>Dear <strong>{$safeName}</strong>,</p>
  <p>An administrator has requested that you reset your Visual Word Media account password.
     Please sign in at <a href="https://visualword.in/#/login">visualword.in</a> — you will be
     prompted to set a new password.</p>
  <p style="margin-top:28px;color:#1a2d5a;font-weight:700">Visual Word Media Team</p>
</div>
HTML;
            sendMail(
                $user['name'] . ' <' . $user['email'] . '>',
                'Action required: Reset your Visual Word Media password',
                $html,
                null,
                ['fromName' => 'Visual Word Media', 'from' => MAIL_FROM_ADDRESS]
            );
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Password reset flag set and email sent to ' . htmlspecialchars($user['name']) . '.'];
        }
    } elseif ($action === 'delete' && $id) {
        if ($id !== (int)$adminUser['id']) {
            $db->prepare("DELETE FROM users WHERE id = ? AND role IN ('member','volunteer')")->execute([$id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Member deleted.'];
        }
    }

    header('Location: members.php');
    exit();
}

// ── Fetch members + volunteers ────────────────────────────────────────────────
$members = $db->query(
    "SELECT id, name, username, email, mobile, gender, church_name, role,
            force_password_reset, approved_at, created_at
     FROM users WHERE role IN ('member','volunteer')
     ORDER BY approved_at DESC, created_at DESC"
)->fetchAll();

$pageTitle   = 'Members';
$currentPage = 'members';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>Members &amp; Volunteers (<?= count($members) ?>)</h3>
    <p style="color:var(--text-light);font-size:0.88rem;margin-top:4px">
      Approved members. Volunteers are members who have also submitted the volunteer form.
    </p>
  </div>

  <?php if ($members): ?>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Email</th><th>Mobile</th>
        <th>Church</th><th>Role</th><th>Approved</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($members as $i => $m): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td style="font-weight:600">
          <?= htmlspecialchars($m['name']) ?>
          <?php if ($m['force_password_reset']): ?>
            <span style="font-size:0.7rem;background:#fff3cd;color:#856404;border-radius:4px;padding:2px 6px;margin-left:4px">pw reset</span>
          <?php endif; ?>
        </td>
        <td style="font-size:0.82rem;color:#555"><?= htmlspecialchars($m['email']) ?></td>
        <td style="font-size:0.82rem"><?= htmlspecialchars($m['mobile'] ?? '—') ?></td>
        <td style="font-size:0.82rem"><?= htmlspecialchars($m['church_name'] ?? '—') ?></td>
        <td><span class="badge badge-<?= $m['role'] ?>"><?= $m['role'] ?></span></td>
        <td style="font-size:0.8rem;color:#999">
          <?= $m['approved_at'] ? date('d M Y', strtotime($m['approved_at'])) : '—' ?>
        </td>
        <td>
          <div class="actions">
            <form method="POST" style="display:inline"
                  title="Flag account for password reset and notify user by email">
              <input type="hidden" name="action" value="reset_password">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <button type="submit" class="btn-edit-sm">🔑 Reset PW</button>
            </form>
            <?php if ($m['id'] != $adminUser['id']): ?>
            <form method="POST" style="display:inline"
                  onsubmit="return confirm('Delete member <?= htmlspecialchars(addslashes($m['name'])) ?>?')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <button type="submit" class="btn-danger-sm">Delete</button>
            </form>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="empty-state" style="padding:60px 0"><p>No members yet.</p></div>
  <?php endif; ?>
</div>

<?php require_once '_footer.php'; ?>
