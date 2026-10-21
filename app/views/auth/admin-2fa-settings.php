<?php
/** @var string $title */
/** @var bool   $enabled */
/** @var bool   $mandatory */
/** @var string $dashboardUrl */
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
.wrap{width:100%;max-width:480px;position:relative;z-index:1;}
.auth-logo{font-weight:800;font-size:1.35rem;letter-spacing:-.03em;margin-bottom:1.5rem;text-align:center;color:var(--paper);text-decoration:none;display:block;}
.auth-logo span{color:var(--accent);}
.card{background:#0D2015;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:2.2rem;box-shadow:0 24px 60px rgba(0,0,0,.35);}
.card h1{font-size:1.3rem;font-weight:700;margin-bottom:.4rem;}
.card .sub{color:var(--muted);font-size:.85rem;margin-bottom:1.6rem;line-height:1.5;}
.status{display:flex;align-items:center;gap:.6rem;padding:.9rem 1rem;border-radius:10px;font-size:.88rem;margin-bottom:1.4rem;}
.status-on{background:rgba(200,255,77,.1);border:1px solid rgba(200,255,77,.3);}
.status-off{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.3);}
.dot{width:9px;height:9px;border-radius:50%;}
.dot-on{background:var(--accent);} .dot-off{background:#F87171;}
.form-group{margin-bottom:1rem;}
label{display:block;font-size:.78rem;font-weight:500;margin-bottom:.4rem;color:#C7D2CB;}
input{width:100%;padding:.75rem 1rem;border:1.5px solid rgba(255,255,255,.14);border-radius:8px;font-family:var(--ff-body);font-size:.9rem;background:rgba(255,255,255,.04);color:var(--paper);}
input:focus{outline:none;border-color:var(--accent);}
.btn{display:block;width:100%;padding:.85rem;border:none;border-radius:8px;font-family:var(--ff-body);font-weight:700;font-size:.9rem;cursor:pointer;text-decoration:none;text-align:center;}
.btn-primary{background:var(--accent);color:var(--ink);}
.btn-primary:hover{background:#D6FF6E;}
.btn-danger{background:transparent;color:#FCA5A5;border:1px solid rgba(248,113,113,.4);}
.btn-danger:hover{background:rgba(248,113,113,.12);}
.note{font-size:.75rem;color:var(--muted);margin-top:.9rem;line-height:1.5;}
.alert{padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1.2rem;}
.alert-error{background:rgba(248,113,113,.12);color:#FCA5A5;border:1px solid rgba(248,113,113,.3);}
.alert-success{background:rgba(200,255,77,.1);color:var(--accent);border:1px solid rgba(200,255,77,.3);}
.footer{text-align:center;margin-top:1.2rem;font-size:.78rem;}
.footer a{color:#9FB0A6;text-decoration:none;}
.footer a:hover{color:var(--accent);}
</style>
</head>
<body>
<div class="bg-grid"></div>
<div class="wrap">
  <a href="<?= APP_URL ?>/" class="auth-logo">creative<span>.</span></a>
  <div class="card">
    <h1>Keamanan Akun</h1>
    <p class="sub">Verifikasi dua faktor (2FA) menambah lapisan keamanan saat masuk panel.</p>

    <?php if ($err = Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($err) ?></div>
    <?php endif; ?>
    <?php if ($ok = Session::getFlash('success')): ?>
      <div class="alert alert-success"><?= e($ok) ?></div>
    <?php endif; ?>

    <div class="status <?= $enabled ? 'status-on' : 'status-off' ?>">
      <span class="dot <?= $enabled ? 'dot-on' : 'dot-off' ?>"></span>
      <span>2FA saat ini: <strong><?= $enabled ? 'Aktif' : 'Nonaktif' ?></strong><?= $mandatory ? ' (wajib)' : '' ?></span>
    </div>

    <?php if (!$enabled): ?>
      <a class="btn btn-primary" href="<?= APP_URL ?>/admin/2fa/setup">Aktifkan 2FA</a>
      <?php if ($mandatory): ?>
        <p class="note">Role Anda <strong>wajib</strong> mengaktifkan 2FA sebelum dapat mengakses panel.</p>
      <?php else: ?>
        <p class="note">Disarankan mengaktifkan 2FA, terutama jika Anda sering mengelola data penting.</p>
      <?php endif; ?>
    <?php elseif ($mandatory): ?>
      <p class="note">2FA bersifat <strong>wajib</strong> untuk role Anda dan tidak dapat dinonaktifkan. Pastikan Anda menyimpan kode pemulihan.</p>
    <?php else: ?>
      <form method="POST" action="<?= APP_URL ?>/admin/2fa/disable">
        <?= csrfField() ?>
        <div class="form-group">
          <label for="current_password">Konfirmasi password untuk menonaktifkan 2FA</label>
          <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-danger">Nonaktifkan 2FA</button>
      </form>
    <?php endif; ?>

    <p class="footer"><a href="<?= $dashboardUrl ?>">← Kembali ke dashboard</a></p>
  </div>
</div>
</body>
</html>
