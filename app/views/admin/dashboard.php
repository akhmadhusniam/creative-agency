<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}
.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}
.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}
.sidebar-logo span{color:var(--accent);}
.sidebar-nav{list-style:none;flex:1;}
.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}
.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}
.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}
.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}
.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;}
.topbar h1{font-family:var(--ff-head);font-size:1.5rem;font-weight:800;}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;}
.card h2{font-family:var(--ff-head);font-weight:700;font-size:1rem;margin-bottom:1rem;}
.table{width:100%;border-collapse:collapse;}
.table th{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);padding:.6rem 0;border-bottom:1px solid var(--cream);text-align:left;}
.table td{padding:.85rem 0;border-bottom:1px solid var(--cream);font-size:.875rem;}
.badge{display:inline-block;padding:.2rem .65rem;border-radius:2rem;font-size:.7rem;font-weight:700;}
.badge-pending{background:#fef9c3;color:#854d0e;}
.badge-confirmed{background:#dbeafe;color:#1e40af;}
.badge-in_progress{background:#ede9fe;color:#5b21b6;}
.badge-completed{background:#dcfce7;color:#15803d;}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/admin" class="active">📊 Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/admin/orders">📋 Pesanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/services">◈ Layanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/portfolio">◆ Portfolio</a></li><li><a href="<?= APP_URL ?>/admin/messages">✉ Pesan Masuk</a></li><li><a href="<?= APP_URL ?>/admin/settings">⚙ Pengaturan</a></li><li><a href="<?= APP_URL ?>/admin/audit-logs">📜 Audit Log</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <div class="topbar">
    <h1><?= e($title) ?></h1>
    <?php require VIEW_PATH . '/partials/notification-bell.php'; ?>
  </div>

  <div class="card">
    <h2>Admin Dashboard</h2>
    <p>Selamat datang di panel admin Creative Studio.</p>
    
    <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
      <div style="background: var(--paper); border: 2px solid var(--cream); padding: 1.5rem; border-radius: 0; text-align: center;">
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--ink);"><?= count($recentOrders) ?></div>
        <div style="font-size: .85rem; color: var(--muted); margin-top: .5rem;">Total Pesanan</div>
      </div>
      <div style="background: var(--paper); border: 2px solid var(--cream); padding: 1.5rem; border-radius: 0; text-align: center;">
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--ink);">0</div>
        <div style="font-size: .85rem; color: var(--muted); margin-top: .5rem;">Hari Ini</div>
      </div>
      <div style="background: var(--paper); border: 2px solid var(--cream); padding: 1.5rem; border-radius: 0; text-align: center;">
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--ink);">0</div>
        <div style="font-size: .85rem; color: var(--muted); margin-top: .5rem;">Bulan Ini</div>
      </div>
      <div style="background: var(--paper); border: 2px solid var(--cream); padding: 1.5rem; border-radius: 0; text-align: center;">
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--ink);">0</div>
        <div style="font-size: .85rem; color: var(--muted); margin-top: .5rem;">Menunggu Konfirmasi</div>
      </div>
    </div>
  </div>

  <div class="card">
    <h2>Pesanan Terbaru</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Kode</th>
          <th>User</th>
          <th>Layanan</th>
          <th>Status</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentOrders as $o): ?>
        <tr>
          <td><strong><?= e($o['order_code']) ?></strong></td>
          <td><?= e($o['name']) ?></td>
          <td><?= e($o['service_name']) ?></td>
          <td><span class="badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
          <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</main>

</body>
</html>
