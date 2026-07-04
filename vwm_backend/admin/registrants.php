<?php
require_once '_auth.php';
require_once __DIR__ . '/../includes/mailer.php';

$db = getDB();

// ── Handle POST ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = intval($_POST['id'] ?? 0);

    if ($action === 'approve' && $id) {
        $row = $db->prepare('SELECT name, email FROM users WHERE id = ? AND role = ?');
        $row->execute([$id, 'registrant']);
        $user = $row->fetch();
        if ($user) {
            $db->prepare(
                "UPDATE users SET role = 'member', force_password_reset = 1,
                 approved_at = NOW(), approved_by = ? WHERE id = ?"
            )->execute([$adminUser['id'], $id]);

            // Send approval email
            $safeName = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
            $html = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">Your account has been approved!</h2>
  <p>Dear <strong>{$safeName}</strong>,</p>
  <p>Your Visual Word Media account has been approved. You can now sign in at
     <a href="https://visualword.in/#/login">visualword.in</a>.</p>
  <p>For security, you will be asked to set a new password on your first login.</p>
  <p style="margin-top:28px;color:#1a2d5a;font-weight:700">Visual Word Media Team</p>
</div>
HTML;
            sendMail(
                $user['name'] . ' <' . $user['email'] . '>',
                'Your Visual Word Media account has been approved',
                $html,
                null,
                ['fromName' => 'Visual Word Media', 'from' => MAIL_FROM_ADDRESS]
            );

            $_SESSION['flash'] = ['type' => 'success', 'msg' => htmlspecialchars($user['name']) . ' approved and emailed.'];
        }
    } elseif ($action === 'reject' && $id) {
        $db->prepare('DELETE FROM users WHERE id = ? AND role = ?')->execute([$id, 'registrant']);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Registrant rejected and removed.'];
    }

    header('Location: registrants.php');
    exit();
}

// ── Fetch registrants ─────────────────────────────────────────────────────────
$registrants = $db->query(
    "SELECT id, name, email, mobile, gender, church_name, referral_name, created_at
     FROM users WHERE role = 'registrant' ORDER BY created_at DESC"
)->fetchAll();

$pageTitle   = 'Registrants';
$currentPage = 'registrants';
require_once '_header.php';
?>

<div class="card">
  <div class="card-head">
    <h3>Pending Registrants (<?= count($registrants) ?>)</h3>
    <p style="color:var(--text-light);font-size:0.88rem;margin-top:4px">
      These accounts are awaiting approval. Approving a user promotes them to <strong>Member</strong> and sends a welcome email.
    </p>
  </div>

  <?php if ($registrants): ?>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Name</th><th>Email</th><th>Mobile</th>
        <th>Gender</th><th>Church</th><th>Referral</th><th>Submitted</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($registrants as $i => $r): ?>
      <tr>
        <td style="color:#ccc;font-size:0.78rem"><?= $i+1 ?></td>
        <td style="font-weight:600"><?= htmlspecialchars($r['name']) ?></td>
        <td style="font-size:0.82rem;color:#555"><?= htmlspecialchars($r['email']) ?></td>
        <td style="font-size:0.82rem"><?= htmlspecialchars($r['mobile'] ?? '—') ?></td>
        <td style="font-size:0.82rem;text-transform:capitalize"><?= htmlspecialchars($r['gender'] ?? '—') ?></td>
        <td style="font-size:0.82rem"><?= htmlspecialchars($r['church_name'] ?? '—') ?></td>
        <td style="font-size:0.82rem"><?= htmlspecialchars($r['referral_name'] ?? '—') ?></td>
        <td style="font-size:0.8rem;color:#999"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
        <td>
          <div class="actions">
            <form method="POST" style="display:inline">
              <input type="hidden" name="action" value="approve">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button type="submit" class="btn-edit-sm" style="background:#1a7a4a;color:#fff">✔ Approve</button>
            </form>
            <form method="POST" style="display:inline"
                  onsubmit="return confirm('Reject and remove <?= htmlspecialchars(addslashes($r['name'])) ?>?')">
              <input type="hidden" name="action" value="reject">
              <input type="hidden" name="id" value="<?= $r['id'] ?>">
              <button type="submit" class="btn-danger-sm">✖ Reject</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="empty-state" style="padding:60px 0">
    <p>No pending registrants.</p>
  </div>
  <?php endif; ?>
</div>

<?php require_once '_footer.php'; ?>
