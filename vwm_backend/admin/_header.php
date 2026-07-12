<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VWM Admin — <?= ucfirst($currentPage) ?></title>
  <style>
    :root { --text-light: #666; }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f5f6fa; color: #2c2c2c; display: flex; min-height: 100vh; font-size: 15px; }

    /* ── Sidebar ───────────────────────────────────── */
    .sidebar { width: 240px; background: #1a2d5a; min-height: 100vh; display: flex; flex-direction: column; flex-shrink: 0; position: fixed; top: 0; left: 0; bottom: 0; }
    .sb-brand { padding: 26px 22px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sb-cross  { font-size: 1.5rem; color: #c9a227; display: block; }
    .sb-title  { font-size: 0.95rem; font-weight: 700; color: #fff; margin-top: 5px; }
    .sb-sub    { font-size: 0.68rem; letter-spacing: 0.22em; text-transform: uppercase; color: #c9a227; }
    .sb-nav    { padding: 14px 0; flex: 1; }
    .nav-link  { display: flex; align-items: center; gap: 11px; padding: 12px 22px; color: rgba(255,255,255,0.72); font-size: 0.88rem; font-weight: 500; text-decoration: none; transition: all 0.15s; border-left: 3px solid transparent; }
    .nav-link:hover { background: rgba(255,255,255,0.07); color: #fff; }
    .nav-link.active { background: rgba(201,162,39,0.13); color: #c9a227; border-left-color: #c9a227; }
    .nav-icon  { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
    .sb-footer { padding: 18px 22px; border-top: 1px solid rgba(255,255,255,0.1); }
    .sb-user   { font-size: 0.78rem; color: rgba(255,255,255,0.5); margin-bottom: 10px; }
    .sb-user strong { display: block; color: #fff; font-size: 0.85rem; margin-top: 2px; }
    .logout-link { display: block; padding: 8px 14px; background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.75); border-radius: 6px; text-align: center; text-decoration: none; font-size: 0.82rem; transition: background 0.15s; }
    .logout-link:hover { background: rgba(255,255,255,0.14); color: #fff; }

    /* ── Main ──────────────────────────────────────── */
    .main      { flex: 1; margin-left: 240px; display: flex; flex-direction: column; min-height: 100vh; }
    .topbar    { background: #fff; border-bottom: 1px solid #e4e6ed; padding: 15px 30px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10; }
    .topbar h2 { font-size: 1.15rem; font-weight: 700; color: #1a2d5a; font-family: Georgia, serif; }
    .topbar-meta { font-size: 0.78rem; color: #999; }
    .content   { padding: 28px 30px; flex: 1; }

    /* ── Stats ─────────────────────────────────────── */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .stat-card { background: #fff; border-radius: 10px; padding: 22px; border: 1px solid #e4e6ed; }
    .stat-label { font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #999; margin-bottom: 8px; }
    .stat-val   { font-size: 2.2rem; font-weight: 700; color: #1a2d5a; line-height: 1; }
    .stat-sub   { font-size: 0.78rem; color: #c9a227; margin-top: 5px; }

    /* ── Card / Table ──────────────────────────────── */
    .card { background: #fff; border-radius: 10px; border: 1px solid #e4e6ed; overflow: hidden; }
    .card-head { padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e4e6ed; }
    .card-head h3 { font-size: 0.97rem; font-weight: 700; color: #1a2d5a; font-family: Georgia, serif; }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 11px 16px; text-align: left; font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #999; background: #fafbfc; border-bottom: 1px solid #e4e6ed; white-space: nowrap; }
    td { padding: 13px 16px; font-size: 0.87rem; border-bottom: 1px solid #f2f2f2; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafbfc; }

    /* ── Badges ────────────────────────────────────── */
    .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.73rem; font-weight: 700; }
    .badge-admin      { background: #e8f4e8; color: #276027; }
    .badge-user       { background: #e8edf8; color: #2d4a8a; }
    .badge-registrant { background: #fff3cd; color: #856404; }
    .badge-member     { background: #d1ecf1; color: #0c5460; }
    .badge-volunteer  { background: #d4edda; color: #155724; }
    .badge-cat   { background: #fdf5e0; color: #7a5c10; border: 1px solid #f0dfa0; }

    /* ── Buttons ───────────────────────────────────── */
    .btn { display: inline-flex; align-items: center; gap: 5px; padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all 0.15s; font-family: inherit; }
    .btn-primary { background: #1a2d5a; color: #fff; }
    .btn-primary:hover { background: #243470; }
    .btn-gold { background: #c9a227; color: #fff; }
    .btn-gold:hover { background: #b8911f; }
    .btn-sm { padding: 5px 11px; font-size: 0.78rem; }
    .btn-outline { background: transparent; border: 1.5px solid #1a2d5a; color: #1a2d5a; }
    .btn-outline:hover { background: #1a2d5a; color: #fff; }
    .btn-danger { background: #dc3545; color: #fff; }
    .btn-danger:hover { background: #c82333; }
    .btn-danger-sm { background: transparent; border: 1.5px solid #e8a0a0; color: #c0392b; padding: 5px 10px; font-size: 0.78rem; border-radius: 5px; cursor: pointer; font-family: inherit; font-weight: 600; }
    .btn-danger-sm:hover { background: #dc3545; color: #fff; border-color: #dc3545; }
    .btn-edit-sm  { background: transparent; border: 1.5px solid #aac0e8; color: #1a2d5a; padding: 5px 10px; font-size: 0.78rem; border-radius: 5px; cursor: pointer; font-family: inherit; font-weight: 600; }
    .btn-edit-sm:hover { background: #1a2d5a; color: #fff; border-color: #1a2d5a; }
    .actions { display: flex; gap: 6px; }

    /* ── Forms ─────────────────────────────────────── */
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: #1a2d5a; margin-bottom: 5px; }
    .form-control { width: 100%; padding: 9px 13px; border: 1.5px solid #dde1ec; border-radius: 6px; font-size: 0.88rem; font-family: inherit; transition: border-color 0.15s; background: #fff; color: #2c2c2c; }
    .form-control:focus { outline: none; border-color: #1a2d5a; }
    textarea.form-control { resize: vertical; min-height: 80px; }
    .form-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-row3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

    /* ── Modal ─────────────────────────────────────── */
    .modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 900; display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-bg.open { display: flex; }
    .modal { background: #fff; border-radius: 12px; width: 100%; max-width: 580px; max-height: 92vh; overflow-y: auto; }
    .modal-head { padding: 22px 26px 0; display: flex; align-items: center; justify-content: space-between; }
    .modal-head h3 { font-size: 1.05rem; color: #1a2d5a; font-family: Georgia, serif; }
    .modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #aaa; line-height: 1; padding: 0 4px; }
    .modal-close:hover { color: #555; }
    .modal-body { padding: 18px 26px 26px; }

    /* ── Flash ─────────────────────────────────────── */
    .flash { padding: 11px 16px; border-radius: 8px; margin-bottom: 22px; font-size: 0.87rem; }
    .flash-ok  { background: #e8f5e9; border: 1px solid #a5d6a7; color: #276027; }
    .flash-err { background: #fdecea; border: 1px solid #f5c6c4; color: #c62828; }

    .empty-row td { text-align: center; color: #aaa; padding: 40px; font-size: 0.9rem; }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-240px); }
      .main { margin-left: 0; }
      .form-row, .form-row3 { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="sb-brand">
    <span class="sb-cross">✝</span>
    <div class="sb-title">Visual Word Media</div>
    <div class="sb-sub">Admin Panel</div>
  </div>
  <nav class="sb-nav">
    <a href="dashboard.php" class="nav-link <?= $currentPage==='dashboard'?'active':'' ?>">
      <span class="nav-icon">📊</span> Dashboard
    </a>
    <a href="home-content.php" class="nav-link <?= $currentPage==='home-content'?'active':'' ?>">
      <span class="nav-icon">🏠</span> Home Content
    </a>
    <a href="events.php" class="nav-link <?= $currentPage==='events'?'active':'' ?>">
      <span class="nav-icon">📅</span> Events
    </a>
    <div style="padding:10px 22px 4px;font-size:0.68rem;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.3)">People</div>
    <a href="registrants.php" class="nav-link <?= $currentPage==='registrants'?'active':'' ?>">
      <span class="nav-icon">📝</span> Registrants
    </a>
    <a href="members.php" class="nav-link <?= $currentPage==='members'?'active':'' ?>">
      <span class="nav-icon">👥</span> Members
    </a>
    <a href="volunteers.php" class="nav-link <?= $currentPage==='volunteers'?'active':'' ?>">
      <span class="nav-icon">🙏</span> Volunteers
    </a>
    <a href="users.php" class="nav-link <?= $currentPage==='users'?'active':'' ?>">
      <span class="nav-icon">🔐</span> All Users
    </a>
    <a href="audio.php" class="nav-link <?= $currentPage==='audio'?'active':'' ?>">
      <span class="nav-icon">🎥</span> Media
    </a>
    <a href="resources.php" class="nav-link <?= $currentPage==='resources'?'active':'' ?>">
      <span class="nav-icon">📚</span> Resources
    </a>
    <a href="books.php" class="nav-link <?= $currentPage==='books'?'active':'' ?>">
      <span class="nav-icon">📖</span> Books
    </a>
    <a href="testimonials.php" class="nav-link <?= $currentPage==='testimonials'?'active':'' ?>">
      <span class="nav-icon">✨</span> Testimonials
    </a>
    <div style="padding:10px 22px 4px;font-size:0.68rem;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.3)">System</div>
    <a href="sql.php" class="nav-link <?= $currentPage==='sql'?'active':'' ?>">
      <span class="nav-icon">🗄️</span> SQL Runner
    </a>
  </nav>
  <div class="sb-footer">
    <div class="sb-user">Signed in as<strong><?= htmlspecialchars($adminUser['name']) ?></strong></div>
    <a href="logout.php" class="logout-link">Sign Out</a>
  </div>
</div>

<div class="main">
  <div class="topbar">
    <h2><?= $pageTitle ?? ucfirst($currentPage) ?></h2>
    <span class="topbar-meta"><?= date('D, d M Y') ?></span>
  </div>
  <div class="content">
    <?php if ($flash): ?>
      <div class="flash flash-<?= $flash['type'] === 'success' ? 'ok' : 'err' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>
