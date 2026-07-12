<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

function navSvg(string $name): string {
    $icons = [
        'dashboard'    => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
        'home-content' => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'events'       => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'registrants'  => '<path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><polyline points="9 14 11 16 15 12"/>',
        'members'      => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
        'volunteers'   => '<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>',
        'users'        => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'audio'        => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>',
        'resources'    => '<path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>',
        'books'        => '<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>',
        'testimonials' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'sql'          => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
    ];
    $d = $icons[$name] ?? $icons['dashboard'];
    return '<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VWM Admin — <?= htmlspecialchars(ucfirst($currentPage)) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* ── Reset ──────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    button { font-family: inherit; }

    /* ── Tokens ─────────────────────────────────────────── */
    :root {
      --brand:       #1a2d5a;
      --brand-hov:   #223472;
      --gold:        #c9a227;
      --gold-hov:    #b8911f;
      --gold-dim:    rgba(201,162,39,.13);

      --bg:          #edf0f7;
      --surface:     #ffffff;
      --surface-2:   #f6f7fb;
      --border:      #e3e6f0;
      --border-faint:#eef0f8;

      --tx1:         #16192b;
      --tx2:         #4b5568;
      --tx3:         #9ca3af;
      --text-light:  #6b7280;

      --ok-bg:   #f0fdf6; --ok-bd:  #a7f3d0; --ok-tx:  #065f46;
      --err-bg:  #fff1f2; --err-bd: #fecdd3; --err-tx: #9f1239;

      --sidebar-w: 244px;
      --topbar-h:  57px;

      --r:    10px;
      --r-sm:  7px;
      --r-xs:  5px;

      --sh-xs: 0 1px 2px rgba(26,45,90,.06);
      --sh:    0 1px 3px rgba(26,45,90,.08), 0 4px 12px rgba(26,45,90,.05);
      --sh-md: 0 4px 20px rgba(26,45,90,.13), 0 2px 6px rgba(26,45,90,.07);
    }

    /* ── Base ───────────────────────────────────────────── */
    body {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      background: var(--bg);
      color: var(--tx1);
      display: flex;
      min-height: 100vh;
      font-size: 14px;
      line-height: 1.55;
      -webkit-font-smoothing: antialiased;
    }

    /* ── Sidebar ────────────────────────────────────────── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--brand);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      overflow-y: auto;
      scrollbar-width: none;
    }
    .sidebar::-webkit-scrollbar { display: none; }

    .sb-brand {
      padding: 22px 18px 18px;
      border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .sb-cross { font-size: 1.3rem; color: var(--gold); display: block; margin-bottom: 9px; line-height: 1; }
    .sb-title { font-size: .88rem; font-weight: 700; color: #fff; letter-spacing: -.01em; }
    .sb-sub   { font-size: .62rem; letter-spacing: .18em; text-transform: uppercase; color: rgba(201,162,39,.8); margin-top: 3px; }

    .sb-nav { padding: 8px 0; flex: 1; }

    .nav-section {
      padding: 14px 18px 3px;
      font-size: .61rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255,255,255,.27);
      font-weight: 600;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px 9px 14px;
      color: rgba(255,255,255,.6);
      font-size: .835rem;
      font-weight: 500;
      text-decoration: none;
      transition: background .13s, color .13s;
      margin: 1px 8px;
      border-radius: var(--r-sm);
      position: relative;
      cursor: pointer;
    }
    .nav-link:hover  { background: rgba(255,255,255,.07); color: rgba(255,255,255,.9); }
    .nav-link.active { background: var(--gold-dim); color: var(--gold); font-weight: 600; }
    .nav-link.active::before {
      content: '';
      position: absolute;
      left: -8px; top: 50%;
      transform: translateY(-50%);
      width: 3px; height: 16px;
      background: var(--gold);
      border-radius: 0 2px 2px 0;
    }
    .nav-icon { flex-shrink: 0; display: flex; align-items: center; opacity: .8; }
    .nav-link.active .nav-icon,
    .nav-link:hover  .nav-icon { opacity: 1; }

    .sb-footer {
      padding: 14px 14px 16px;
      border-top: 1px solid rgba(255,255,255,.08);
    }
    .sb-user       { font-size: .73rem; color: rgba(255,255,255,.42); margin-bottom: 9px; line-height: 1.45; }
    .sb-user strong { display: block; color: rgba(255,255,255,.88); font-size: .82rem; font-weight: 600; margin-top: 2px; }
    .logout-link {
      display: block; padding: 7px 12px;
      background: rgba(255,255,255,.07); color: rgba(255,255,255,.62);
      border-radius: var(--r-sm); text-align: center;
      text-decoration: none; font-size: .78rem; font-weight: 500;
      transition: background .15s, color .15s; cursor: pointer;
    }
    .logout-link:hover { background: rgba(255,255,255,.13); color: #fff; }

    /* ── Main ───────────────────────────────────────────── */
    .main { flex: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-height: 100vh; }

    .topbar {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 28px;
      height: var(--topbar-h);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky; top: 0; z-index: 10;
      box-shadow: 0 1px 0 var(--border);
    }
    .topbar h2    { font-size: 1rem; font-weight: 700; color: var(--brand); letter-spacing: -.02em; }
    .topbar-meta  { font-size: .73rem; color: var(--tx3); font-weight: 500; }

    .content { padding: 24px 28px; flex: 1; }

    /* ── Stats ──────────────────────────────────────────── */
    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
      gap: 15px; margin-bottom: 22px;
    }
    .stat-card {
      background: var(--surface); border-radius: var(--r);
      padding: 18px 20px; border: 1px solid var(--border);
      box-shadow: var(--sh-xs); transition: box-shadow .15s, transform .15s;
    }
    .stat-card:hover { box-shadow: var(--sh); transform: translateY(-1px); }
    .stat-label { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--tx3); margin-bottom: 8px; }
    .stat-val   { font-size: 1.95rem; font-weight: 700; color: var(--brand); line-height: 1; letter-spacing: -.04em; }
    .stat-sub   { font-size: .72rem; color: var(--gold); margin-top: 5px; font-weight: 500; }

    /* ── Card / Table ───────────────────────────────────── */
    .card { background: var(--surface); border-radius: var(--r); border: 1px solid var(--border); overflow: hidden; box-shadow: var(--sh-xs); }
    .card-head {
      padding: 15px 20px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid var(--border);
    }
    .card-head h3 { font-size: .9rem; font-weight: 700; color: var(--brand); letter-spacing: -.01em; }

    table { width: 100%; border-collapse: collapse; }
    th {
      padding: 9px 16px; text-align: left;
      font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .09em;
      color: var(--tx3); background: var(--surface-2);
      border-bottom: 1px solid var(--border); white-space: nowrap;
    }
    td { padding: 11px 16px; font-size: .845rem; border-bottom: 1px solid var(--border-faint); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tbody tr { transition: background .1s; }
    tbody tr:hover td { background: #f5f7fd; }

    /* ── Badges ─────────────────────────────────────────── */
    .badge {
      display: inline-flex; align-items: center;
      padding: 2px 9px; border-radius: 20px;
      font-size: .67rem; font-weight: 700; letter-spacing: .03em; text-transform: capitalize;
    }
    .badge-admin      { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .badge-user       { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-registrant { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-member     { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-volunteer  { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
    .badge-cat        { background: #fefce8; color: #713f12; border: 1px solid #fef08a; }

    /* ── Buttons ────────────────────────────────────────── */
    .btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 8px 16px; border-radius: var(--r-sm);
      font-size: .82rem; font-weight: 600; cursor: pointer; border: none;
      text-decoration: none; transition: all .15s ease; font-family: inherit;
      line-height: 1; white-space: nowrap;
    }
    .btn:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }

    .btn-primary { background: var(--brand); color: #fff; }
    .btn-primary:hover { background: var(--brand-hov); box-shadow: 0 2px 10px rgba(26,45,90,.28); }

    .btn-gold { background: var(--gold); color: #fff; }
    .btn-gold:hover { background: var(--gold-hov); box-shadow: 0 2px 10px rgba(201,162,39,.32); }

    .btn-sm { padding: 5px 11px; font-size: .76rem; }

    .btn-outline {
      background: transparent; border: 1.5px solid var(--border); color: var(--tx2);
    }
    .btn-outline:hover { border-color: var(--brand); color: var(--brand); background: #f0f2f9; }

    .btn-danger { background: #dc2626; color: #fff; }
    .btn-danger:hover { background: #b91c1c; }

    .btn-danger-sm {
      background: transparent; border: 1.5px solid #fca5a5; color: #dc2626;
      padding: 4px 10px; font-size: .75rem; border-radius: var(--r-sm);
      cursor: pointer; font-weight: 600; transition: all .14s;
    }
    .btn-danger-sm:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

    .btn-edit-sm {
      background: transparent; border: 1.5px solid #bfdbfe; color: var(--brand);
      padding: 4px 10px; font-size: .75rem; border-radius: var(--r-sm);
      cursor: pointer; font-weight: 600; transition: all .14s;
    }
    .btn-edit-sm:hover { background: var(--brand); color: #fff; border-color: var(--brand); }

    .actions { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }

    /* ── Forms ──────────────────────────────────────────── */
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: .77rem; font-weight: 600; color: var(--tx2); margin-bottom: 5px; }
    .form-control {
      width: 100%; padding: 8px 12px;
      border: 1.5px solid var(--border); border-radius: var(--r-sm);
      font-size: .84rem; font-family: inherit;
      transition: border-color .15s, box-shadow .15s;
      background: var(--surface); color: var(--tx1); line-height: 1.5;
    }
    .form-control:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 3px rgba(26,45,90,.08); }
    textarea.form-control { resize: vertical; min-height: 80px; }
    select.form-control { cursor: pointer; }
    .form-row  { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-row3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

    /* ── Modal ──────────────────────────────────────────── */
    .modal-bg {
      position: fixed; inset: 0;
      background: rgba(10,15,40,.52);
      z-index: 900; display: none;
      align-items: center; justify-content: center;
      padding: 20px; backdrop-filter: blur(3px);
    }
    .modal-bg.open { display: flex; }
    .modal {
      background: var(--surface); border-radius: 14px;
      width: 100%; max-width: 580px; max-height: 92vh;
      overflow-y: auto; box-shadow: var(--sh-md);
      border: 1px solid var(--border);
    }
    .modal-head { padding: 20px 24px 0; display: flex; align-items: center; justify-content: space-between; }
    .modal-head h3 { font-size: .97rem; font-weight: 700; color: var(--brand); letter-spacing: -.01em; }
    .modal-close {
      background: var(--surface-2); border: none; cursor: pointer;
      color: var(--tx3); border-radius: var(--r-xs);
      transition: all .13s; display: flex; align-items: center; justify-content: center;
      width: 30px; height: 30px; font-size: 1.2rem; line-height: 1; flex-shrink: 0;
    }
    .modal-close:hover { background: var(--border); color: var(--tx1); }
    .modal-body { padding: 16px 24px 24px; }

    /* ── Flash ──────────────────────────────────────────── */
    .flash {
      padding: 11px 14px; border-radius: 8px; margin-bottom: 20px;
      font-size: .83rem; font-weight: 500; border: 1px solid transparent;
      display: flex; align-items: flex-start; gap: 8px; line-height: 1.5;
    }
    .flash-ok  { background: var(--ok-bg);  border-color: var(--ok-bd);  color: var(--ok-tx); }
    .flash-err { background: var(--err-bg); border-color: var(--err-bd); color: var(--err-tx); }

    /* ── Empty states ───────────────────────────────────── */
    .empty-row td { text-align: center; color: var(--tx3); padding: 44px; font-size: .87rem; }
    .empty-state  { display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--tx3); font-size: .88rem; }

    /* ── Responsive ─────────────────────────────────────── */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-244px); }
      .main { margin-left: 0; }
      .form-row, .form-row3 { grid-template-columns: 1fr; }
      .content { padding: 16px; }
      .topbar { padding: 0 16px; }
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
      <span class="nav-icon"><?= navSvg('dashboard') ?></span> Dashboard
    </a>
    <a href="home-content.php" class="nav-link <?= $currentPage==='home-content'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('home-content') ?></span> Home Content
    </a>
    <a href="events.php" class="nav-link <?= $currentPage==='events'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('events') ?></span> Events
    </a>
    <div class="nav-section">People</div>
    <a href="registrants.php" class="nav-link <?= $currentPage==='registrants'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('registrants') ?></span> Registrants
    </a>
    <a href="members.php" class="nav-link <?= $currentPage==='members'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('members') ?></span> Members
    </a>
    <a href="volunteers.php" class="nav-link <?= $currentPage==='volunteers'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('volunteers') ?></span> Volunteers
    </a>
    <a href="users.php" class="nav-link <?= $currentPage==='users'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('users') ?></span> All Users
    </a>
    <a href="audio.php" class="nav-link <?= $currentPage==='audio'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('audio') ?></span> Media
    </a>
    <a href="resources.php" class="nav-link <?= $currentPage==='resources'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('resources') ?></span> Resources
    </a>
    <a href="books.php" class="nav-link <?= $currentPage==='books'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('books') ?></span> Books
    </a>
    <a href="testimonials.php" class="nav-link <?= $currentPage==='testimonials'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('testimonials') ?></span> Testimonials
    </a>
    <div class="nav-section">System</div>
    <a href="sql.php" class="nav-link <?= $currentPage==='sql'?'active':'' ?>">
      <span class="nav-icon"><?= navSvg('sql') ?></span> SQL Runner
    </a>
  </nav>
  <div class="sb-footer">
    <div class="sb-user">Signed in as<strong><?= htmlspecialchars($adminUser['name']) ?></strong></div>
    <a href="logout.php" class="logout-link">Sign Out</a>
  </div>
</div>

<div class="main">
  <div class="topbar">
    <h2><?= htmlspecialchars($pageTitle ?? ucfirst($currentPage)) ?></h2>
    <span class="topbar-meta"><?= date('D, d M Y') ?></span>
  </div>
  <div class="content">
    <?php if ($flash): ?>
      <div class="flash flash-<?= $flash['type'] === 'success' ? 'ok' : 'err' ?>">
        <?= htmlspecialchars($flash['msg']) ?>
      </div>
    <?php endif; ?>
