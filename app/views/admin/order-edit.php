<?php
$order = $order ?? [];
$designers = $designers ?? [];
$managers = $managers ?? [];
$priorities = ['low', 'medium', 'high', 'urgent'];
$statuses = ['pending', 'confirmed', 'in_progress', 'revision', 'completed', 'cancelled'];
$errors = Session::getFlash('errors') ?? [];
$errorMsg = Session::getFlash('error');
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title><?= e($title) ?></title><link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"><style>:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}.sidebar-logo span{color:var(--accent);}.sidebar-nav{list-style:none;flex:1;}.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}.topbar h1{font-family:var(--ff-head);font-size:1.3rem;font-weight:800;}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;max-width:760px;}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:var(--paper);padding:1rem;border-radius:6px;margin-bottom:1.5rem;}
.info-item strong{display:block;font-size:.72rem;color:var(--muted);text-transform:uppercase;margin-bottom:.25rem;letter-spacing:.03em;}
.form-section{margin-bottom:1.5rem;}
.form-section h2{font-family:var(--ff-head);font-size:.95rem;border-bottom:2px solid var(--paper);padding-bottom:.5rem;margin:0 0 1rem;}
.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;}
.form-group{margin-bottom:1rem;}
.form-group label{display:block;margin-bottom:.4rem;font-size:.8rem;font-weight:600;color:var(--muted);}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:.6rem .75rem;border:1px solid var(--cream);border-radius:6px;font-size:.875rem;font-family:inherit;}
.form-group textarea{resize:vertical;min-height:90px;background:var(--paper);color:var(--muted);}
.form-group input:focus,.form-group select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 2px var(--accent)22;}
.form-actions{display:flex;gap:1rem;margin-top:1.5rem;justify-content:flex-end;}
.btn{padding:.7rem 1.4rem;border:none;border-radius:6px;font-size:.875rem;cursor:pointer;font-family:var(--ff-head);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;}
.btn-primary{background:var(--accent);color:var(--ink);border:2px solid var(--ink);}
.btn-primary:hover{background:var(--ink);color:var(--accent);}
.btn-secondary{background:var(--paper);color:var(--ink);}
.btn-secondary:hover{background:var(--cream);}
.alert{padding:.9rem 1rem;border-radius:6px;margin-bottom:1.5rem;font-size:.875rem;max-width:760px;}
.alert-error{background:#fee2e2;color:#991b1b;}
.error-list{list-style:none;padding:0;margin:0;}
</style></head><body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/admin">📊 Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/admin/orders" class="active">📋 Pesanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/services">◈ Layanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/portfolio">◆ Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/admin/users">◉ Staff</a></li>
    <li><a href="<?= APP_URL ?>/admin/messages">✉ Pesan Masuk</a></li><li><a href="<?= APP_URL ?>/admin/settings">⚙ Pengaturan</a></li>
    <li><a href="<?= APP_URL ?>/admin/audit-logs">📜 Audit Log</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <div class="topbar"><h1><?= e($title) ?></h1></div>

  <?php if ($errorMsg): ?>
    <div class="alert alert-error"><?= e($errorMsg) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul class="error-list">
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="info-grid">
      <div class="info-item"><strong>Klien</strong><?= e($order['client_name']) ?></div>
      <div class="info-item"><strong>Layanan</strong><?= e($order['service_name']) ?></div>
      <div class="info-item"><strong>Total</strong><?= formatRupiah((float) $order['total']) ?></div>
      <div class="info-item"><strong>Dibuat</strong><?= date('d M Y', strtotime($order['created_at'])) ?></div>
    </div>

    <form method="POST" action="<?= APP_URL ?>/admin/orders/<?= (int) $order['id'] ?>/update">
      <?= csrfField() ?>

      <div class="form-section">
        <h2>Assignment & Jadwal</h2>
        <div class="form-grid">
          <div class="form-group">
            <label for="assigned_to">Designer</label>
            <select id="assigned_to" name="assigned_to">
              <option value="">-- Belum di-assign --</option>
              <?php foreach ($designers as $designer): ?>
                <option value="<?= $designer['id'] ?>" <?= $order['assigned_to'] == $designer['id'] ? 'selected' : '' ?>>
                  <?= e($designer['name']) ?> (<?= e($designer['department'] ?? 'Design') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="manager_id">Manager</label>
            <select id="manager_id" name="manager_id">
              <option value="">-- Pilih Manager --</option>
              <?php foreach ($managers as $manager): ?>
                <option value="<?= $manager['id'] ?>" <?= $order['manager_id'] == $manager['id'] ? 'selected' : '' ?>>
                  <?= e($manager['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
              <?php foreach ($priorities as $priority): ?>
                <option value="<?= $priority ?>" <?= $order['priority'] === $priority ? 'selected' : '' ?>><?= ucfirst($priority) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="deadline_date">Deadline</label>
            <input type="date" id="deadline_date" name="deadline_date" value="<?= e(substr($order['deadline_date'] ?? '', 0, 10)) ?>">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2>Status</h2>
        <div class="form-group">
          <label for="status">Status Pesanan</label>
          <select id="status" name="status">
            <?php foreach ($statuses as $status): ?>
              <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-section">
        <h2>Brief Klien</h2>
        <div class="form-group">
          <textarea readonly><?= e($order['brief'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="form-actions">
        <a href="<?= APP_URL ?>/admin/orders" class="btn btn-secondary">← Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</main>

</body>
</html>
