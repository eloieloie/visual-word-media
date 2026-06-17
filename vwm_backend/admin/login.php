<?php
session_start();
require_once dirname(__DIR__) . '/config/db.php';

// Already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $db   = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        header('Location: dashboard.php');
        exit();
    }
    $error = 'Invalid credentials or insufficient permissions.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VWM Admin — Login</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0f1c3a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .card { background: #fff; border-radius: 14px; padding: 44px 40px; width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,0.35); }
    .brand { text-align: center; margin-bottom: 32px; }
    .brand .cross { font-size: 2rem; color: #c9a227; }
    .brand h1 { font-size: 1.2rem; font-weight: 700; color: #1a2d5a; margin-top: 8px; font-family: Georgia, serif; }
    .brand span { font-size: 0.72rem; letter-spacing: 0.2em; text-transform: uppercase; color: #c9a227; }
    h2 { font-size: 1.35rem; color: #1a2d5a; margin-bottom: 6px; font-family: Georgia, serif; }
    .sub { font-size: 0.85rem; color: #888; margin-bottom: 28px; }
    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 0.82rem; font-weight: 600; color: #1a2d5a; margin-bottom: 6px; }
    .field input { width: 100%; padding: 11px 14px; border: 1.5px solid #dde1ec; border-radius: 7px; font-size: 0.92rem; font-family: inherit; transition: border-color 0.15s; }
    .field input:focus { outline: none; border-color: #1a2d5a; }
    .error { background: #fdecea; border: 1px solid #f5c6c4; color: #c62828; padding: 10px 14px; border-radius: 7px; font-size: 0.85rem; margin-bottom: 18px; }
    .btn { width: 100%; padding: 13px; background: #1a2d5a; color: #fff; border: none; border-radius: 7px; font-size: 0.95rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.15s; }
    .btn:hover { background: #243470; }
  </style>
</head>
<body>
  <div class="card">
    <div class="brand">
      <div class="cross">✝</div>
      <h1>Visual Word Media</h1>
      <span>Admin Panel</span>
    </div>
    <h2>Sign In</h2>
    <p class="sub">Admin access only.</p>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" class="btn">Sign In</button>
    </form>
  </div>
</body>
</html>
