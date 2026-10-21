<?php
/** @var string $title */
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> — <?= e(APP_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--ink:#122A1C;--paper:#FFFFFF;--accent:#C8FF4D;--muted:#8A968F;--ff-body:'Inter',sans-serif;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--ff-body);background:var(--ink);color:var(--paper);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;}
.bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:44px 44px;pointer-events:none;}
.auth-wrap{width:100%;max-width:420px;position:relative;z-index:1;}
.auth-logo{font-weight:800;font-size:1.35rem;letter-spacing:-.03em;margin-bottom:1.5rem;text-align:center;color:var(--paper);text-decoration:none;display:block;}
.auth-logo span{color:var(--accent);}
.auth-card{background:#0D2015;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:2.4rem;box-shadow:0 24px 60px rgba(0,0,0,.35);text-align:center;}
.auth-card h1{font-size:1.3rem;font-weight:700;margin-bottom:.4rem;}
.auth-card .sub{color:var(--muted);font-size:.85rem;margin-bottom:1.6rem;line-height:1.5;}
.code-input{width:100%;padding:.9rem 1rem;border:1.5px solid rgba(255,255,255,.14);border-radius:8px;font-family:var(--ff-body);font-size:1.5rem;letter-spacing:.4em;text-align:center;background:rgba(255,255,255,.04);color:var(--paper);text-transform:uppercase;}
.code-input:focus{outline:none;border-color:var(--accent);background:rgba(255,255,255,.07);}
.btn{display:block;width:100%;padding:.85rem;margin-top:1.1rem;border:none;border-radius:8px;font-family:var(--ff-body);font-weight:700;font-size:.9rem;cursor:pointer;background:var(--accent);color:var(--ink);transition:background .15s;}
.btn:hover{background:#D6FF6E;}
.alert{padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1.2rem;text-align:left;}
.alert-error{background:rgba(248,113,113,.12);color:#FCA5A5;border:1px solid rgba(248,113,113,.3);}
.hint{font-size:.75rem;color:var(--muted);margin-top:1rem;line-height:1.5;}
.auth-footer{text-align:center;margin-top:1.4rem;font-size:.8rem;}
.auth-footer a{color:#9FB0A6;text-decoration:none;}
.auth-footer a:hover{color:var(--accent);}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="auth-wrap">
  <a href="<?= APP_URL ?>/" class="auth-logo">creative<span>.</span></a>
  <div class="auth-card">
    <h1>Verifikasi Dua Faktor</h1>
    <p class="sub">Buka aplikasi authenticator Anda dan masukkan 6 digit kode.<br>Kode berganti setiap 30 detik.</p>

    <?php if ($err = Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($err) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/admin/2fa">
      <?= csrfField() ?>
      <input class="code-input" type="text" name="code" inputmode="text"
             placeholder="000000" autocomplete="one-time-code" required autofocus
             maxlength="9" pattern="[A-Za-z0-9\- ]{6,9}">
      <button type="submit" class="btn">Verifikasi &amp; Masuk</button>
    </form>

    <p class="hint">
      Kehilangan akses ke authenticator?<br>
      Masukkan salah satu <strong>kode recovery</strong> di kolom yang sama (format XXXX-XXXX).
    </p>

    <p class="auth-footer"><a href="<?= APP_URL ?>/login">← Kembali ke login</a></p>
  </div>
</div>
</body>
</html>
