<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Saya — Creative Studio</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#0D0D0D;--paper:#F7F5F0;--cream:#EDEBE4;--accent:#C8412B;--muted:#7A7570;--white:#FFFFFF;--sidebar:240px;--ff-head:'Syne',sans-serif;--ff-body:'Inter',sans-serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh}
.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50}
.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem}
.sidebar-logo span{color:var(--accent)}
.sidebar-nav{list-style:none;flex:1}
.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none}
.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07)}
.sidebar-nav a .icon{font-size:1rem;width:20px;text-align:center}
.sidebar-footer{padding:1.5rem;border-top:1px solid #222}
.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none}
.sidebar-logout:hover{color:var(--accent)}
.main{margin-left:var(--sidebar);flex:1;padding:2.5rem}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem}
.topbar h1{font-family:var(--ff-head);font-size:1.5rem;font-weight:800}
.topbar-date{font-size:.85rem;color:var(--muted)}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.card-head h2{font-family:var(--ff-head);font-weight:700;font-size:1rem}
.btn-sm{display:inline-block;padding:.4rem .9rem;border-radius:4px;font-family:var(--ff-head);font-weight:600;font-size:.75rem;letter-spacing:.05em;text-decoration:none}
.btn-outline-sm{border:1.5px solid var(--cream);color:var(--muted)}
.btn-outline-sm:hover{border-color:var(--ink);color:var(--ink)}
.table{width:100%;border-collapse:collapse}
.table th{font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);padding:.6rem 0;border-bottom:1px solid var(--cream);text-align:left}
.table td{padding:.85rem 0;border-bottom:1px solid var(--cream);font-size:.875rem;vertical-align:middle}
.table tr:last-child td{border-bottom:none}
.badge{display:inline-block;padding:.2rem .65rem;border-radius:2rem;font-size:.7rem;font-weight:700;letter-spacing:.05em}
.badge-pending{background:#fef9c3;color:#854d0e}
.badge-confirmed{background:#dbeafe;color:#1e40af}
.badge-in_progress{background:#ede9fe;color:#5b21b6}
.badge-revision{background:#ffedd5;color:#c2410c}
.badge-completed{background:#dcfce7;color:#15803d}
.badge-cancelled{background:#fee2e2;color:#b91c1c}
.empty{text-align:center;padding:3rem 1rem;color:var(--muted)}
.empty .icon{font-size:2.5rem;margin-bottom:.75rem}
.btn-primary-sm{background:var(--accent);color:var(--white);padding:.5rem 1.1rem;border-radius:4px;font-family:var(--ff-head);font-weight:700;font-size:.8rem;text-decoration:none;display:inline-block}
@media(max-width:768px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}}
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/dashboard"><span class="icon">◉</span> Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/dashboard/orders" class="active"><span class="icon">📋</span> Pesanan Saya</a></li>
    <li><a href="<?= APP_URL ?>/services"><span class="icon">◈</span> Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio"><span class="icon">◆</span> Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/dashboard/profile"><span class="icon">👤</span> Profil</a></li>
    <li><a href="<?= APP_URL ?>/contact"><span class="icon">💬</span> Kontak</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<!-- Main -->
<main class="main">
  <div class="topbar">
    <h1>Pesanan Saya</h1>
    <span class="topbar-date"><?= date('l, d F Y') ?></span>
  </div>

  <div class="card">
    <div class="card-head">
      <h2>Semua Pesanan</h2>
      <a href="<?= APP_URL ?>/services" class="btn-sm btn-outline-sm">+ Pesan Baru</a>
    </div>

    <?php if (empty($orders)): ?>
      <div class="empty">
        <div class="icon">📭</div>
        <p>Belum ada pesanan ditemukan.<br>Coba pesan layanan kami sekarang.</p>
        <a href="<?= APP_URL ?>/services" class="btn-primary-sm">Lihat Layanan</a>
      </div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Layanan</th>
            <th>Total</th>
            <th>Metode / Pembayaran</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong><?= e($o['order_code'] ?? '-') ?></strong></td>
              <td><?= e($o['service_name'] ?? '-') ?></td>
              <td><?= formatRupiah((float)($o['total'] ?? 0)) ?></td>
              <td><?= e($o['payment_status'] ?? '-') ?></td>
              <td>
                <span class="badge badge-<?= $o['status'] ?? 'pending' ?>">
                  <?= match($o['status'] ?? 'pending') {
                    'pending'     => 'Menunggu',
                    'confirmed'   => 'Dikonfirmasi',
                    'in_progress' => 'Dikerjakan',
                    'revision'    => 'Revisi',
                    'completed'   => 'Selesai',
                    'cancelled'   => 'Dibatalkan',
                    default       => ($o['status'] ?? '-')
                  } ?>
                </span>
              </td>
              <td><?= date('d M Y', strtotime($o['created_at'] ?? 'now')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

</main>

</body>
</html>
