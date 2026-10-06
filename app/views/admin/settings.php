<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title><?= e($title) ?></title><link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet"><style>:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif;}*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh;}.sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50;}.sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem;}.sidebar-logo span{color:var(--accent);}.sidebar-nav{list-style:none;flex:1;}.sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none;}.sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07);}.sidebar-footer{padding:1.5rem;border-top:1px solid #222;}.sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none;}.main{margin-left:var(--sidebar);flex:1;padding:2.5rem;}.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;}.topbar h1{font-family:var(--ff-head);font-size:1.5rem;font-weight:800;}.card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;max-width:640px;}.card h2{font-family:var(--ff-head);font-weight:700;font-size:1rem;margin-bottom:1.2rem;}.form-group{margin-bottom:1.1rem;}label{display:block;font-size:.8rem;font-weight:500;margin-bottom:.4rem;color:var(--muted);}input[type="text"],input[type="email"],input[type="url"],textarea{font-family:var(--ff-body);width:100%;padding:.6rem .75rem;border:1px solid var(--cream);border-radius:6px;font-size:.875rem;}input:focus,textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 2px var(--accent)22;}textarea{resize:vertical;min-height:70px;}.btn-primary{padding:.75rem 1.6rem;background:var(--accent);color:var(--ink);border:2px solid var(--ink);border-radius:0;font-family:var(--ff-head);font-weight:700;font-size:.9rem;letter-spacing:.03em;cursor:pointer;}.btn-primary:hover{background:var(--ink);color:var(--accent);}.alert{padding:.8rem 1rem;border-radius:6px;margin-bottom:1.5rem;font-size:.875rem;max-width:640px;}.alert-success{background:#dcfce7;color:#15803d;border:1px solid #86efac;}.two-col{display:grid;grid-template-columns:1fr 1fr;gap:0 1rem;}</style></head><body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/admin">📊 Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/admin/orders">📋 Pesanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/services">◈ Layanan</a></li>
    <li><a href="<?= APP_URL ?>/admin/portfolio">◆ Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/admin/users">◉ Staff</a></li>
    <li><a href="<?= APP_URL ?>/admin/messages">✉ Pesan Masuk</a></li><li><a href="<?= APP_URL ?>/admin/settings" class="active">⚙ Pengaturan</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <div class="topbar"><h1><?= e($title) ?></h1></div>

  <?php if ($success = Session::getFlash('success')): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
  <?php endif; ?>

  <form action="<?= APP_URL ?>/admin/settings" method="POST">
    <?= csrfField() ?>

    <div class="card">
      <h2>Identitas Situs</h2>
      <div class="form-group">
        <label for="site_name">Nama Situs</label>
        <input type="text" id="site_name" name="site_name" value="<?= e($settings['site_name']) ?>">
      </div>
      <div class="form-group">
        <label for="site_tagline">Tagline</label>
        <input type="text" id="site_tagline" name="site_tagline" value="<?= e($settings['site_tagline']) ?>">
      </div>
    </div>

    <div class="card">
      <h2>Kontak</h2>
      <div class="two-col">
        <div class="form-group">
          <label for="site_email">Email</label>
          <input type="email" id="site_email" name="site_email" value="<?= e($settings['site_email']) ?>">
        </div>
        <div class="form-group">
          <label for="site_phone">Telepon</label>
          <input type="text" id="site_phone" name="site_phone" value="<?= e($settings['site_phone']) ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="site_whatsapp">Nomor WhatsApp (format 62xxx, tanpa spasi/strip)</label>
        <input type="text" id="site_whatsapp" name="site_whatsapp" value="<?= e($settings['site_whatsapp']) ?>">
      </div>
      <div class="form-group">
        <label for="site_address">Alamat</label>
        <textarea id="site_address" name="site_address"><?= e($settings['site_address']) ?></textarea>
      </div>
      <div class="form-group">
        <label for="site_maps_query">Query Google Maps</label>
        <input type="text" id="site_maps_query" name="site_maps_query" value="<?= e($settings['site_maps_query']) ?>">
      </div>
    </div>

    <div class="card">
      <h2>Jam Operasional</h2>
      <div class="two-col">
        <div class="form-group">
          <label for="site_hours_office">Jam Kantor</label>
          <input type="text" id="site_hours_office" name="site_hours_office" value="<?= e($settings['site_hours_office']) ?>">
        </div>
        <div class="form-group">
          <label for="site_hours_wa">Jam Respons WhatsApp</label>
          <input type="text" id="site_hours_wa" name="site_hours_wa" value="<?= e($settings['site_hours_wa']) ?>">
        </div>
      </div>
    </div>

    <div class="card">
      <h2>Tautan Lain</h2>
      <div class="form-group">
        <label for="site_meeting_url">Link Meeting (Zoom/Meet, opsional)</label>
        <input type="url" id="site_meeting_url" name="site_meeting_url" value="<?= e($settings['site_meeting_url']) ?>">
      </div>
      <div class="two-col">
        <div class="form-group">
          <label for="site_instagram">Instagram</label>
          <input type="url" id="site_instagram" name="site_instagram" value="<?= e($settings['site_instagram']) ?>">
        </div>
        <div class="form-group">
          <label for="site_linkedin">LinkedIn</label>
          <input type="url" id="site_linkedin" name="site_linkedin" value="<?= e($settings['site_linkedin']) ?>">
        </div>
      </div>
    </div>

    <button type="submit" class="btn-primary">Simpan Pengaturan</button>
  </form>
</main>

</body>
</html>
