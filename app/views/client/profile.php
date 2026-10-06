<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya — Creative Studio</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--white:#FFFFFF;--sidebar:240px;--ff-head:'Archivo Black',sans-serif;--ff-body:'Inter',sans-serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;min-height:100vh}
    .sidebar{width:var(--sidebar);background:var(--ink);color:var(--white);display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;bottom:0;left:0;z-index:50}
    .sidebar-logo{font-family:var(--ff-head);font-weight:800;font-size:1.2rem;letter-spacing:-.02em;padding:0 1.5rem 2rem}
    .sidebar-logo span{color:var(--accent)}
    .sidebar-nav{list-style:none;flex:1}
    .sidebar-nav a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;font-size:.875rem;font-weight:500;color:#888;transition:all .15s;text-decoration:none}
    .sidebar-nav a:hover,.sidebar-nav a.active{color:var(--white);background:rgba(255,255,255,.07)}
    .sidebar-footer{padding:1.5rem;border-top:1px solid #222}
    .sidebar-user{font-size:.8rem;color:#666;margin-bottom:.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .sidebar-logout{display:block;font-size:.8rem;color:#888;text-decoration:none}
    .main{margin-left:var(--sidebar);flex:1;padding:2.5rem}
    .card{background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem}
    .form-row{display:flex;gap:1rem;flex-wrap:wrap}
    .form-group{flex:1;min-width:220px}
    label{display:block;font-size:.85rem;margin-bottom:.35rem;color:var(--muted)}
    input,button{font-family:var(--ff-body);padding:.6rem .75rem;border:1px solid var(--cream);border-radius:6px;width:100%}
    .btn-primary{background:var(--accent);color:var(--ink);border:2px solid var(--ink);padding:.6rem .9rem;border-radius:0;font-weight:700}
    .alert{padding:.6rem .9rem;border-radius:6px;margin-bottom:1rem}
    .alert-success{background:#dcfce7;color:#064e3b}
    .alert-error{background:#fee2e2;color:#7f1d1d}
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">creative<span>.</span></div>
  <ul class="sidebar-nav">
    <li><a href="<?= APP_URL ?>/dashboard"><span class="icon">◉</span> Dashboard</a></li>
    <li><a href="<?= APP_URL ?>/dashboard/orders"><span class="icon">📋</span> Pesanan Saya</a></li>
    <li><a href="<?= APP_URL ?>/services"><span class="icon">◈</span> Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio"><span class="icon">◆</span> Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/dashboard/profile" class="active"><span class="icon">👤</span> Profil</a></li>
    <li><a href="<?= APP_URL ?>/contact"><span class="icon">💬</span> Kontak</a></li>
  </ul>
  <div class="sidebar-footer">
    <div class="sidebar-user"><?= e(Session::get('user_name')) ?></div>
    <a href="<?= APP_URL ?>/logout" class="sidebar-logout">← Keluar</a>
  </div>
</aside>

<main class="main">
  <div class="card">
    <h2 style="margin-bottom:1rem">Profil Saya</h2>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($errors = Session::get('errors')): ?>
      <div class="alert alert-error"><?= e(implode(', ', (array)$errors)) ?></div>
    <?php endif; ?>

    <form action="<?= APP_URL ?>/dashboard/profile/update" method="post">
      <?= csrfField() ?>
      <div class="form-row">
        <div class="form-group">
          <label for="name">Nama</label>
          <input id="name" name="name" value="<?= e($user['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="<?= e($user['email'] ?? '') ?>" required>
        </div>
      </div>

      <div class="form-row" style="margin-top:1rem">
        <div class="form-group">
          <label for="phone">Telepon</label>
          <input id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="password">Ganti Password (kosongkan jika tidak diubah)</label>
          <input id="password" name="password" type="password" placeholder="Minimal 8 karakter">
        </div>
      </div>

      <div style="margin-top:1rem;display:flex;gap:1rem;align-items:center">
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
        <a href="<?= APP_URL ?>/dashboard" style="color:var(--muted);text-decoration:none">Kembali ke Dashboard</a>
      </div>
    </form>
  </div>
</main>

</body>
</html>
