<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title><?= e($title) ?></title><link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"><style>:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}.sidebar-logo span{color:var(--accent);}.sidebar-nav{list-style:none;flex:1;}.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}.topbar h1{font-family:var(--ff-head);font-size:1.5rem;font-weight:800;}
.filter-bar{display:flex;gap:0.8rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:flex-end;background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1rem 1.2rem;}
.filter-group{display:flex;flex-direction:column;gap:.3rem;}
.filter-group label{font-size:.75rem;color:var(--muted);}
.filter-group select,.filter-group input{font-family:var(--ff-body);padding:.45rem .6rem;border:1px solid var(--cream);border-radius:6px;font-size:.82rem;}
.filter-btn{padding:.5rem 1.2rem;background:var(--ink);color:white;border:none;border-radius:6px;font-size:.82rem;cursor:pointer;font-family:var(--ff-head);font-weight:700;}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;overflow:hidden;}
.log-table{width:100%;border-collapse:collapse;}
.log-table th{text-align:left;font-size:.75rem;text-transform:uppercase;color:var(--muted);padding:.8rem 1rem;border-bottom:2px solid var(--cream);letter-spacing:.03em;}
.log-table td{padding:.75rem 1rem;border-bottom:1px solid var(--cream);font-size:.85rem;vertical-align:top;}
.log-table tbody tr:hover{background:#fafaf8;}
.tag{display:inline-block;padding:.2rem .6rem;border-radius:4px;font-size:.72rem;font-weight:600;background:#f0ede7;color:#555;}
.tag-create{background:#e7f5e7;color:#006600;}
.tag-update,.tag-status_update{background:#e7f0ff;color:#0044cc;}
.tag-delete,.tag-deactivate{background:#ffe7e7;color:#cc0000;}
.tag-approve,.tag-activate{background:#e7f5e7;color:#006600;}
.tag-reject{background:#fff3e7;color:#cc6600;}
.tag-assign{background:#f0e7ff;color:#6600cc;}
.values-preview{font-size:.75rem;color:var(--muted);font-family:monospace;max-width:280px;overflow-x:auto;white-space:nowrap;}
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
    <li><a href="<?= APP_URL ?>/admin/messages">✉ Pesan Masuk</a></li><li><a href="<?= APP_URL ?>/admin/settings">⚙ Pengaturan</a></li>
    <li><a href="<?= APP_URL ?>/admin/audit-logs" class="active">📜 Audit Log</a></li>
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
      <label for="entity_type">Tipe Entitas</label>
      <select name="entity_type" id="entity_type">
        <option value="">Semua</option>
        <?php foreach ($entityTypes as $t): ?>
          <option value="<?= e($t) ?>" <?= ($_GET['entity_type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label for="from">Dari Tanggal</label>
      <input type="date" name="from" id="from" value="<?= e($_GET['from'] ?? '') ?>">
    </div>
    <div class="filter-group">
      <label for="to">Sampai Tanggal</label>
      <input type="date" name="to" id="to" value="<?= e($_GET['to'] ?? '') ?>">
    </div>
    <button type="submit" class="filter-btn">Filter</button>
  </form>

  <div class="card">
    <table class="log-table">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>User</th>
          <th>Entitas</th>
          <th>Aksi</th>
          <th>Perubahan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $log): ?>
          <tr>
            <td><?= date('d M Y, H:i', strtotime($log['created_at'])) ?></td>
            <td><?= e($log['user_name']) ?><br><span style="color:var(--muted);font-size:.75rem;"><?= e(ucfirst($log['user_role'])) ?></span></td>
            <td><?= e(ucfirst($log['entity_type'])) ?> #<?= (int) $log['entity_id'] ?></td>
            <td><span class="tag tag-<?= e($log['action']) ?>"><?= e($log['action']) ?></span></td>
            <td>
              <?php if ($log['new_values']): ?>
                <div class="values-preview"><?= e($log['new_values']) ?></div>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (empty($logs)): ?>
      <div class="empty-state">Tidak ada log untuk filter ini</div>
    <?php endif; ?>
  </div>
</main>

</body>
</html>
