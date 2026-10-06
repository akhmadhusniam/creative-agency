<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — Creative Studio</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
  --ink:#0D0D0D; --paper:#F7F5F0; --cream:#EDEBE4;
  --accent:#C8412B; --muted:#7A7570; --white:#FFFFFF;
  --ff-head:'Syne',sans-serif; --ff-body:'Inter',sans-serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;}
.auth-wrap{width:100%;max-width:420px;}
.auth-logo{font-family:var(--ff-head);font-weight:800;font-size:1.5rem;letter-spacing:-.02em;margin-bottom:2rem;text-align:center;}
.auth-logo span{color:var(--accent);}
.auth-card{background:var(--white);border:1px solid var(--cream);border-radius:12px;padding:2.5rem;}
.auth-card h1{font-family:var(--ff-head);font-size:1.6rem;font-weight:800;margin-bottom:.3rem;}
.auth-card .sub{color:var(--muted);font-size:.875rem;margin-bottom:2rem;}
.form-group{margin-bottom:1.25rem;}
label{display:block;font-size:.8rem;font-weight:500;margin-bottom:.4rem;color:var(--ink);}
input{width:100%;padding:.75rem 1rem;border:1.5px solid var(--cream);border-radius:6px;font-family:var(--ff-body);font-size:.9rem;background:var(--white);color:var(--ink);transition:border-color .15s;}
input:focus{outline:none;border-color:var(--ink);}
.btn{display:block;width:100%;padding:.85rem;border:none;border-radius:6px;font-family:var(--ff-head);font-weight:700;font-size:.9rem;cursor:pointer;letter-spacing:.04em;transition:background .15s;}
.btn-primary{background:var(--accent);color:var(--white);}
.btn-primary:hover{background:#A83422;}
.auth-footer{text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted);}
.auth-footer a{color:var(--ink);font-weight:500;text-decoration:underline;}
.alert{padding:.8rem 1rem;border-radius:6px;font-size:.875rem;margin-bottom:1.25rem;}
.alert-error{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;}
.field-error{color:#b91c1c;font-size:.75rem;margin-top:.3rem;}
.forgot{text-align:right;font-size:.78rem;margin-top:.3rem;}
.forgot a{color:var(--muted);text-decoration:underline;}
</style>
</head>
<body>
<div class="auth-wrap">
  <a href="<?= APP_URL ?>/" class="auth-logo">creative<span>.</span></a>

  <div class="auth-card">
    <h1>Selamat Datang</h1>
    <p class="sub">Masuk ke akun Anda untuk melanjutkan</p>

    <?php if ($err = Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($err) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/login">
      <?= csrfField() ?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= e(Session::getFlash('old')['email'] ?? '') ?>"
               placeholder="nama@email.com" required autocomplete="email">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Masukkan password" required autocomplete="current-password">
        <div class="forgot"><a href="<?= APP_URL ?>/forgot-password">Lupa password?</a></div>
      </div>
      <button type="submit" class="btn btn-primary">Masuk</button>
    </form>

    <p class="auth-footer">
      Belum punya akun? <a href="<?= APP_URL ?>/register">Daftar sekarang</a>
    </p>
  </div>
</div>
</body>
</html>
