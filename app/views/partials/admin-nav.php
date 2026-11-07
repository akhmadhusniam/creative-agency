<?php
// ============================================================
//  partials/admin-nav.php — shell admin (sidebar + style dasar)
//  Variabel: $activeAdmin (dashboard|orders|services|portfolio|messages|settings|audit)
//            $pageTitle (opsional, default $title)
// ============================================================
$activeAdmin = $activeAdmin ?? '';
$pageTitle   = $pageTitle ?? ($title ?? 'Admin');
$adminNav = [
    'dashboard' => ['Dashboard',  APP_URL . '/admin',           '▦'],
    'orders'    => ['Pesanan',    APP_URL . '/admin/orders',    '▤'],
    'services'  => ['Layanan',    APP_URL . '/admin/services',  '◈'],
    'portfolio' => ['Portfolio',  APP_URL . '/admin/portfolio', '✦'],
    'messages'  => ['Pesan Masuk', APP_URL . '/admin/messages', '✉'],
    'settings'  => ['Pengaturan', APP_URL . '/admin/settings',  '⚙'],
    'audit'     => ['Audit Log',  APP_URL . '/admin/audit-logs','◎'],
];
$successMsg = Session::getFlash('success');
$errorMsg   = Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--cream-2:#F3F6F4;
  --accent:#C8FF4D;--muted:#5C6862;--line:#E2E8E4;--danger:#B42318;
  --ff-head:'Plus Jakarta Sans','Inter',sans-serif;--ff-body:'Inter',sans-serif;--fm:'JetBrains Mono',monospace;
  --sidebar:240px;--radius:10px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;line-height:1.5}
a{text-decoration:none;color:inherit}
img{max-width:100%;display:block}

/* Sidebar */
.sidebar{width:var(--sidebar);background:var(--ink);color:#fff;display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;inset:0 auto 0 0;z-index:50}
.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;text-transform:uppercase}
.sidebar-logo span{color:var(--accent)}
.sidebar-nav{list-style:none;flex:1;overflow-y:auto}
.sidebar-nav a{display:flex;align-items:center;gap:.7rem;padding:.72rem 1.5rem;font-size:.85rem;font-weight:500;color:#8FA396;transition:all .15s;border-left:3px solid transparent}
.sidebar-nav a:hover{color:#fff;background:rgba(255,255,255,.05)}
.sidebar-nav a.active{color:#fff;background:rgba(255,255,255,.07);border-left-color:var(--accent)}
.sidebar-nav .nav-ic{width:18px;text-align:center;font-size:.9rem;opacity:.9}
.sidebar-footer{padding:1.25rem 1.5rem 0;border-top:1px solid rgba(255,255,255,.1);margin-top:.5rem}
.sidebar-user{font-size:.78rem;color:#8FA396;margin-bottom:.6rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.sidebar-user b{display:block;color:#fff;font-weight:600;font-size:.82rem}
.sidebar-logout{display:inline-flex;align-items:center;gap:.35rem;font-family:var(--fm);font-size:.68rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#8FA396}
.sidebar-logout:hover{color:var(--accent)}

/* Main */
.main{margin-left:var(--sidebar);flex:1;padding:2.25rem clamp(1rem,3vw,2.5rem) 4rem;min-width:0}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:2rem}
.topbar h1{font-family:var(--ff-head);font-size:clamp(1.3rem,2.5vw,1.7rem);font-weight:800;letter-spacing:-.02em;text-transform:uppercase}
.topbar .sub{font-size:.82rem;color:var(--muted);margin-top:.25rem}
.page-actions{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}

/* Alerts */
.alert{padding:.8rem 1rem;border-radius:var(--radius);font-size:.85rem;margin-bottom:1.25rem;border:1px solid}
.alert-success{background:#EFFBE3;border-color:#C8E6A0;color:#254A1E}
.alert-error{background:#FDF0EF;border-color:#F3C0BC;color:#7A1C14}
.alert ul{margin:.35rem 0 0 1.1rem}

/* Cards & table */
.card{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:1.5rem}
.card + .card{margin-top:1.25rem}
.card-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.1rem}
.card h2{font-family:var(--ff-head);font-weight:700;font-size:1.05rem;letter-spacing:-.01em;text-transform:uppercase}
.table-wrap{overflow-x:auto;margin:0 -1.5rem;padding:0 1.5rem}
.table{width:100%;border-collapse:collapse;min-width:720px}
.table th{font-family:var(--fm);font-size:.64rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);padding:.6rem .5rem;border-bottom:1px solid var(--line);text-align:left;white-space:nowrap}
.table td{padding:.8rem .5rem;border-bottom:1px solid var(--line);font-size:.85rem;vertical-align:middle}
.table tr:last-child td{border-bottom:0}
.table .num{font-family:var(--fm);font-size:.78rem;color:var(--muted)}
.cell-title{display:flex;align-items:center;gap:.75rem;min-width:220px}
.thumb{width:52px;height:40px;border-radius:6px;overflow:hidden;background:var(--cream);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:var(--ink)}
.thumb img{width:100%;height:100%;object-fit:cover}
.cell-title b{font-weight:600;display:block;line-height:1.25}
.cell-title small{color:var(--muted);font-size:.74rem}
.actions{display:flex;align-items:center;gap:.35rem;flex-wrap:wrap}

/* Badges */
.badge{display:inline-flex;align-items:center;gap:.3rem;font-family:var(--fm);font-size:.62rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;padding:.22rem .55rem;border-radius:999px;border:1px solid}
.badge-live{background:#EFFBE3;border-color:#C8E6A0;color:#254A1E}
.badge-draft{background:var(--cream-2);border-color:var(--line);color:var(--muted)}
.badge-sample{background:#FFF6E5;border-color:#EBCF9A;color:#7A4B0B}
.badge-featured{background:var(--accent);border-color:var(--ink);color:var(--ink)}

/* Buttons */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.4rem;padding:.55rem 1rem;border-radius:8px;border:1.5px solid var(--ink);background:#fff;color:var(--ink);font-family:var(--fm);font-size:.7rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;cursor:pointer;transition:all .15s;white-space:nowrap}
.btn:hover{background:var(--ink);color:#fff}
.btn-primary{background:var(--ink);color:#fff}
.btn-primary:hover{background:var(--accent);color:var(--ink)}
.btn-accent{background:var(--accent);color:var(--ink);border-color:var(--ink)}
.btn-accent:hover{background:var(--ink);color:var(--accent)}
.btn-ghost{border-color:var(--line);color:var(--muted)}
.btn-ghost:hover{border-color:var(--ink);background:#fff;color:var(--ink)}
.btn-danger{color:var(--danger);border-color:#E8B4AF}
.btn-danger:hover{background:var(--danger);color:#fff;border-color:var(--danger)}
.btn-sm{padding:.38rem .65rem;font-size:.64rem}
.btn-block{width:100%}
.btn:focus-visible,a:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible{outline:3px solid #2B6CC8;outline-offset:2px}

/* Forms */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem 1.25rem}
.field{display:flex;flex-direction:column;gap:.35rem;min-width:0}
.field.full{grid-column:1/-1}
.field label{font-family:var(--fm);font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.field label .req{color:var(--danger)}
.field input,.field select,.field textarea{font-family:var(--ff-body);font-size:.875rem;color:var(--ink);background:#fff;border:1px solid var(--line);border-radius:8px;padding:.65rem .75rem;width:100%;transition:border-color .15s}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--ink);outline:none;box-shadow:0 0 0 3px rgba(200,255,77,.35)}
.field textarea{resize:vertical;min-height:110px;line-height:1.65}
.field .hint{font-size:.72rem;color:var(--muted)}
.field-row-2{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.check{display:flex;align-items:flex-start;gap:.55rem;font-size:.82rem;color:var(--ink);padding:.55rem 0}
.check input{width:16px;height:16px;accent-color:var(--ink);margin-top:.15rem;flex-shrink:0}
.check small{display:block;color:var(--muted);font-size:.72rem;margin-top:.1rem}
.form-section{border-top:1px solid var(--line);margin-top:1.5rem;padding-top:1.5rem}
.form-section h3{font-family:var(--ff-head);font-size:.9rem;text-transform:uppercase;letter-spacing:.01em;margin-bottom:1rem}
.form-actions{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;border-top:1px solid var(--line);margin-top:1.75rem;padding-top:1.25rem}
.form-actions .right{display:flex;gap:.5rem;flex-wrap:wrap}
.cover-preview{display:flex;align-items:center;gap:1rem;padding:.75rem;border:1px dashed var(--line);border-radius:8px;background:var(--cream-2);margin-bottom:.75rem}
.cover-preview img{width:120px;height:80px;object-fit:cover;border-radius:6px;border:1px solid var(--line)}
.empty{text-align:center;padding:3rem 1.5rem;color:var(--muted)}
.empty .ic{font-size:2.5rem;opacity:.3;margin-bottom:.75rem}
.empty h3{font-family:var(--ff-head);font-size:1.05rem;text-transform:uppercase;color:var(--ink);margin-bottom:.35rem}

@media(max-width:900px){
  body{flex-direction:column}
  .sidebar{position:static;width:100%;padding:0;flex-direction:column}
  .sidebar-logo{padding:1rem 1.25rem .75rem;font-size:1.05rem}
  .sidebar-nav{display:flex;overflow-x:auto;gap:.15rem;padding:0 .5rem .5rem;scrollbar-width:none}
  .sidebar-nav::-webkit-scrollbar{display:none}
  .sidebar-nav a{border-left:0;border-bottom:3px solid transparent;padding:.55rem .75rem;white-space:nowrap;font-size:.78rem}
  .sidebar-nav a.active{border-left:0;border-bottom-color:var(--accent)}
  .sidebar-nav .nav-ic{display:none}
  .sidebar-footer{display:none}
  .main{margin-left:0;padding:1.5rem 1rem 3rem}
  .form-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <?php foreach ($adminNav as $key => [$label, $href, $glyph]): ?>
      <li>
        <a href="<?= $href ?>" class="<?= $activeAdmin === $key ? 'active' : '' ?>">
          <span class="nav-ic" aria-hidden="true"><?= $glyph ?></span><?= e($label) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <b><?= e(Session::get('user_name') ?: 'Admin') ?></b>
      <?= e(Session::get('user_role') ?: '') ?>
    </div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>
<main class="main">
  <div class="topbar">
    <div>
      <h1><?= e($pageTitle) ?></h1>
      <?php if (!empty($pageSubtitle)): ?><div class="sub"><?= e($pageSubtitle) ?></div><?php endif; ?>
    </div>
    <?php if (!empty($pageActions)): ?>
      <div class="page-actions"><?= $pageActions ?></div>
    <?php endif; ?>
  </div>

  <?php if ($successMsg): ?>
    <div class="alert alert-success" role="status"><?= e($successMsg) ?></div>
  <?php endif; ?>
  <?php if ($errorMsg): ?>
    <div class="alert alert-error" role="alert"><?= e($errorMsg) ?></div>
  <?php endif; ?>
