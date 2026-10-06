<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title><?= e($title) ?></title><link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"><style>:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}.sidebar-logo span{color:var(--accent);}.sidebar-nav{list-style:none;flex:1;}.sidebar-nav a{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}.nav-badge{background:var(--accent);color:var(--ink);font-size:.68rem;font-weight:700;padding:.1rem .45rem;border-radius:10px;}.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}.topbar h1{font-family:var(--ff-head);font-size:1.5rem;font-weight:800;}
.filter-bar{display:flex;gap:0.8rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:flex-end;background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1rem 1.2rem;}
.filter-group{display:flex;flex-direction:column;gap:.3rem;}
.filter-group label{font-size:.75rem;color:var(--muted);}
.filter-group select{font-family:var(--ff-body);padding:.45rem .6rem;border:1px solid var(--cream);border-radius:6px;font-size:.82rem;}
.filter-btn{padding:.5rem 1.2rem;background:var(--ink);color:white;border:none;border-radius:6px;font-size:.82rem;cursor:pointer;font-family:var(--ff-head);font-weight:700;}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;overflow:hidden;}
.msg-row{display:flex;align-items:center;gap:1rem;padding:1rem 1.2rem;border-bottom:1px solid var(--cream);text-decoration:none;color:inherit;}
.msg-row:last-child{border-bottom:none;}
.msg-row:hover{background:#fafaf8;}
.msg-row.unread{background:#fdf6f0;}
.msg-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0;}
.msg-row.read .msg-dot{background:transparent;}
.msg-main{flex:1;min-width:0;}
.msg-top{display:flex;align-items:center;gap:.6rem;margin-bottom:.25rem;}
.msg-name{font-weight:700;font-size:.9rem;}
.msg-row.unread .msg-name{font-weight:800;}
.tag{display:inline-block;padding:.15rem .55rem;border-radius:4px;font-size:.7rem;font-weight:600;}
.tag-Klien{background:#e7f5e7;color:#006600;}
.tag-Kerja{background:#e7f0ff;color:#0044cc;}
.tag-Karir{background:#fff3e7;color:#cc6600;}
.tag-Lainnya{background:#f0ede7;color:#666;}
.msg-subject{font-size:.82rem;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-time{font-size:.75rem;color:var(--muted);flex-shrink:0;font-family:monospace;}
.empty-state{text-align:center;padding:3rem;color:var(--muted);}
</style></head><body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/admin">📊 Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/admin/orders">📋 Pesanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/services">◈ Layanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/portfolio">◆ Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/admin/users">◉ Staff</a></li>
    <li><a href="<?= APP_URL ?>/admin/messages" class="active">✉ Pesan Masuk <?php if ($unreadCount > 0): ?><span class="nav-badge"><?= (int) $unreadCount ?></span><?php endif; ?></a></li>
    <li><a href="<?= APP_URL ?>/admin/settings">⚙ Pengaturan</a></li>
    <li><a href="<?= APP_URL ?>/admin/audit-logs">📜 Audit Log</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <div class="topbar"><h1><?= e($title) ?></h1></div>

  <form class="filter-bar" method="GET">
    <div class="filter-group">
      <label for="type">Jenis Kebutuhan</label>
      <select name="type" id="type">
        <option value="">Semua</option>
        <option value="Klien Baru" <?= ($_GET['type']??'')==='Klien Baru'?'selected':'' ?>>Klien Baru</option>
        <option value="Kerja Sama" <?= ($_GET['type']??'')==='Kerja Sama'?'selected':'' ?>>Kerja Sama</option>
        <option value="Karir" <?= ($_GET['type']??'')==='Karir'?'selected':'' ?>>Karir</option>
        <option value="Lainnya" <?= ($_GET['type']??'')==='Lainnya'?'selected':'' ?>>Lainnya</option>
      </select>
    </div>
    <div class="filter-group">
      <label for="status">Status</label>
      <select name="status" id="status">
        <option value="">Semua</option>
        <option value="unread" <?= ($_GET['status']??'')==='unread'?'selected':'' ?>>Belum Dibaca</option>
        <option value="read" <?= ($_GET['status']??'')==='read'?'selected':'' ?>>Sudah Dibaca</option>
      </select>
    </div>
    <button type="submit" class="filter-btn">Filter</button>
  </form>

  <div class="card">
    <?php foreach ($messages as $m): ?>
      <a href="<?= APP_URL ?>/admin/messages/<?= (int) $m['id'] ?>" class="msg-row <?= $m['is_read'] ? 'read' : 'unread' ?>">
        <span class="msg-dot"></span>
        <div class="msg-main">
          <div class="msg-top">
            <span class="msg-name"><?= e($m['name']) ?></span>
            <?php $type = $m['inquiry_type'] ?? 'Klien Baru'; $tagClass = explode(' ', $type)[0]; ?>
            <span class="tag tag-<?= e($tagClass) ?>"><?= e($type) ?></span>
          </div>
          <div class="msg-subject"><?= e($m['subject'] ?? mb_strimwidth($m['body'], 0, 80, '…')) ?></div>
        </div>
        <span class="msg-time"><?= date('d M Y, H:i', strtotime($m['created_at'])) ?></span>
      </a>
    <?php endforeach; ?>
    <?php if (empty($messages)): ?>
      <div class="empty-state">Tidak ada pesan untuk filter ini</div>
    <?php endif; ?>
  </div>
</main>

</body>
</html>
