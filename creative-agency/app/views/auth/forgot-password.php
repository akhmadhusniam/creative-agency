<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {--ink:#0D0D0D;--paper:#F7F5F0;--cream:#EDEBE4;--accent:#C8412B;--muted:#7A7570;--white:#FFFFFF;--ff-head:'Syne',sans-serif;--ff-body:'Inter',sans-serif;}
*,*::before,*::after {box-sizing:border-box;margin:0;padding:0;}
html {scroll-behavior:smooth;}
body {font-family:var(--ff-body);background:var(--paper);color:var(--ink);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem;}
a {color:inherit;text-decoration:none;}
.container {max-width:420px;width:100%;}
.logo {font-family:var(--ff-head);font-weight:800;font-size:1.3rem;margin-bottom:2rem;text-align:center;}
.logo span {color:var(--accent);}
.card {background:var(--white);border:1px solid var(--cream);border-radius:10px;padding:2rem;}
.card-title {font-family:var(--ff-head);font-size:1.2rem;font-weight:800;margin-bottom:1rem;}
.card-desc {font-size:.9rem;color:var(--muted);margin-bottom:1.5rem;line-height:1.6;}
.form-group {margin-bottom:1.2rem;}
label {display:block;font-size:.85rem;font-weight:500;margin-bottom:.45rem;color:var(--muted);}
input[type="email"] {font-family:var(--ff-body);width:100%;padding:.6rem .75rem;border:1px solid var(--cream);border-radius:6px;font-size:.875rem;}
input[type="email"]:focus {outline:none;border-color:var(--accent);box-shadow:0 0 0 2px var(--accent)22;}
.btn-primary {width:100%;padding:.8rem;background:var(--accent);color:var(--white);border:none;border-radius:6px;font-family:var(--ff-head);font-weight:700;font-size:1rem;letter-spacing:.04em;cursor:pointer;transition:background .2s;}
.btn-primary:hover {background:#A83422;}
.alert {padding:.8rem 1rem;border-radius:6px;margin-bottom:1rem;font-size:.875rem;}
.alert-success {background:#dcfce7;color:#15803d;border:1px solid #86efac;}
.alert-error {background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}
.link-group {display:flex;gap:1rem;justify-content:center;margin-top:1.5rem;font-size:.85rem;}
.link-group a {color:var(--muted);transition:color .15s;}
.link-group a:hover {color:var(--ink);}
</style>
</head>
<body>

<div class="container">
  <div class="logo">creative<span>.</span></div>

  <div class="card">
    <h1 class="card-title">Lupa Password?</h1>
    <p class="card-desc">Masukkan email Anda dan kami akan mengirimkan link untuk mereset password Anda.</p>

    <?php if ($success = Session::getFlash('success')): ?>
      <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <form action="<?= APP_URL ?>/forgot-password" method="POST">
      <?php csrf_field(); ?>

      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" placeholder="nama@email.com" required>
      </div>

      <button type="submit" class="btn-primary">Kirim Link Reset</button>
    </form>

    <div class="link-group">
      <a href="<?= APP_URL ?>/login">← Kembali ke Login</a>
      <a href="<?= APP_URL ?>/register">Belum punya akun?</a>
    </div>
  </div>
</div>

</body>
</html>
