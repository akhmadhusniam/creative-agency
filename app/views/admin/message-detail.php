<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title><?= e($title) ?></title><link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"><style>:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}.sidebar-logo span{color:var(--accent);}.sidebar-nav{list-style:none;flex:1;}.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}.back-link{display:inline-block;margin-bottom:1.2rem;color:var(--muted);text-decoration:none;font-size:.85rem;}
.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:2rem;max-width:680px;}
.msg-head{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px solid var(--cream);padding-bottom:1.2rem;margin-bottom:1.2rem;}
.msg-head h1{font-family:var(--ff-head);font-size:1.3rem;font-weight:800;margin-bottom:.4rem;}
.msg-head .time{font-size:.78rem;color:var(--muted);}
.tag{display:inline-block;padding:.25rem .7rem;border-radius:5px;font-size:.75rem;font-weight:700;}
.tag-Klien{background:#e7f5e7;color:#006600;}
.tag-Kerja{background:#e7f0ff;color:#0044cc;}
.tag-Karir{background:#fff3e7;color:#cc6600;}
.tag-Lainnya{background:#f0ede7;color:#666;}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:.9rem;background:var(--paper);padding:1rem;border-radius:6px;margin-bottom:1.5rem;}
.info-item strong{display:block;font-size:.72rem;color:var(--muted);text-transform:uppercase;margin-bottom:.2rem;letter-spacing:.03em;}
.body-text{font-size:.92rem;line-height:1.7;white-space:pre-wrap;margin-bottom:1.5rem;}
.actions{display:flex;gap:.8rem;flex-wrap:wrap;}
.btn{padding:.7rem 1.3rem;border-radius:6px;font-size:.85rem;font-family:var(--ff-head);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;}
.btn-wa{background:#25D366;color:#fff;}
.btn-email{background:var(--ink);color:#fff;}
</style></head><body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/admin">📊 Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/admin/orders">📋 Pesanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/services">◈ Layanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/portfolio">◆ Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/admin/users">◉ Staff</a></li>
    <li><a href="<?= APP_URL ?>/admin/messages" class="active">✉ Pesan Masuk</a></li>
    <li><a href="<?= APP_URL ?>/admin/settings">⚙ Pengaturan</a></li>
    <li><a href="<?= APP_URL ?>/admin/audit-logs">📜 Audit Log</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <a href="<?= APP_URL ?>/admin/messages" class="back-link">← Kembali ke Pesan Masuk</a>

  <div class="card">
    <div class="msg-head">
      <div>
        <h1><?= e($message['name']) ?></h1>
        <div class="time"><?= date('d M Y, H:i', strtotime($message['created_at'])) ?></div>
      </div>
      <?php $type = $message['inquiry_type'] ?? 'Klien Baru'; $tagClass = explode(' ', $type)[0]; ?>
      <span class="tag tag-<?= e($tagClass) ?>"><?= e($type) ?></span>
    </div>

    <div class="info-grid">
      <div class="info-item"><strong>Email</strong><?= e($message['email']) ?></div>
      <div class="info-item"><strong>No. WhatsApp</strong><?= e($message['phone'] ?? '—') ?></div>
      <?php if (!empty($message['subject'])): ?>
        <div class="info-item"><strong>Jenis Layanan</strong><?= e($message['subject']) ?></div>
      <?php endif; ?>
      <?php if (!empty($message['budget'])): ?>
        <div class="info-item"><strong>Estimasi Budget</strong><?= e($message['budget']) ?></div>
      <?php endif; ?>
      <?php if (!empty($message['company'])): ?>
        <div class="info-item"><strong>Perusahaan</strong><?= e($message['company']) ?></div>
      <?php endif; ?>
    </div>

    <div class="body-text"><?= e($message['body']) ?></div>

    <div class="actions">
      <?php if (!empty($message['phone'])): ?>
        <a href="https://wa.me/<?= e(preg_replace('/\D+/', '', $message['phone'])) ?>" class="btn btn-wa" target="_blank" rel="noopener">💬 Balas via WhatsApp</a>
      <?php endif; ?>
      <a href="mailto:<?= e($message['email']) ?>" class="btn btn-email">✉ Balas via Email</a>
    </div>
  </div>
</main>

</body>
</html>
