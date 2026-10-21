<?php
/** @var string $title */
/** @var string $secret */
/** @var array  $recoveryCodes */
/** @var string $otpauthUri */
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> — <?= e(APP_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#8A968F;--ff-body:'Inter',sans-serif;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--ff-body);background:var(--ink);color:var(--paper);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;}
.bg-grid{position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:44px 44px;pointer-events:none;}
.wrap{width:100%;max-width:560px;position:relative;z-index:1;}
.auth-logo{font-weight:800;font-size:1.35rem;letter-spacing:-.03em;margin-bottom:1.5rem;text-align:center;color:var(--paper);text-decoration:none;display:block;}
.auth-logo span{color:var(--accent);}
.card{background:#0D2015;border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:2.2rem;box-shadow:0 24px 60px rgba(0,0,0,.35);}
.card h1{font-size:1.35rem;font-weight:700;margin-bottom:.4rem;}
.card .sub{color:var(--muted);font-size:.85rem;margin-bottom:1.6rem;line-height:1.5;}
.step{display:flex;gap:1rem;align-items:flex-start;margin-bottom:1.4rem;}
.step-num{flex:0 0 26px;width:26px;height:26px;border-radius:50%;background:var(--accent);color:var(--ink);font-weight:700;font-size:.8rem;display:flex;align-items:center;justify-content:center;}
.step-body{flex:1;min-width:0;}
.step-body h3{font-size:.9rem;font-weight:600;margin-bottom:.5rem;}
.qr-box{background:#fff;border-radius:12px;padding:12px;display:inline-block;line-height:0;}
.secret{font-family:monospace;font-size:.82rem;background:rgba(255,255,255,.06);border:1px dashed rgba(255,255,255,.2);border-radius:8px;padding:.6rem .8rem;word-break:break-all;color:var(--accent);margin-top:.5rem;display:block;user-select:all;}
.recovery{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;margin-top:.5rem;}
.recovery code{font-family:monospace;font-size:.82rem;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:6px;padding:.45rem .6rem;text-align:center;letter-spacing:.04em;}
.note{font-size:.75rem;color:var(--muted);margin-top:.5rem;line-height:1.5;}
.code-input{width:100%;padding:.85rem 1rem;border:1.5px solid rgba(255,255,255,.14);border-radius:8px;font-family:var(--ff-body);font-size:1.4rem;letter-spacing:.4em;text-align:center;background:rgba(255,255,255,.04);color:var(--paper);}
.code-input:focus{outline:none;border-color:var(--accent);}
.btn{display:block;width:100%;padding:.85rem;margin-top:1rem;border:none;border-radius:8px;font-family:var(--ff-body);font-weight:700;font-size:.9rem;cursor:pointer;background:var(--accent);color:var(--ink);transition:background .15s;}
.btn:hover{background:#D6FF6E;}
.btn-ghost{display:block;width:100%;padding:.7rem;margin-top:.6rem;border:1px solid rgba(255,255,255,.16);border-radius:8px;background:none;color:#C7D2CB;font-family:var(--ff-body);font-size:.82rem;cursor:pointer;}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent);}
.alert{padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1.2rem;}
.alert-error{background:rgba(248,113,113,.12);color:#FCA5A5;border:1px solid rgba(248,113,113,.3);}
.alert-warn{background:rgba(250,204,21,.1);color:#FDE68A;border:1px solid rgba(250,204,21,.3);}
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
    <h1>Aktifkan Verifikasi Dua Faktor</h1>
    <p class="sub">Langkah ini wajib untuk akun admin/owner demi menjaga keamanan panel.</p>

    <?php if ($err = Session::getFlash('error')): ?>
      <div class="alert alert-error"><?= e($err) ?></div>
    <?php endif; ?>

    <div class="step">
      <div class="step-num">1</div>
      <div class="step-body">
        <h3>Scan QR code ini</h3>
        <div class="qr-box"><div id="qrcode"></div></div>
        <span class="secret"><?= e($secret) ?></span>
        <p class="note">Tidak bisa scan? Masukkan kode di atas secara manual di aplikasi authenticator (Google Authenticator, Authy, Microsoft Authenticator).</p>
      </div>
    </div>

    <div class="step">
      <div class="step-num">2</div>
      <div class="step-body">
        <h3>Simpan kode pemulihan</h3>
        <div class="alert alert-warn">Simpan 8 kode ini di tempat aman. Setiap kode hanya bisa dipakai <strong>satu kali</strong> jika Anda kehilangan authenticator.</div>
        <div class="recovery">
          <?php foreach ($recoveryCodes as $rc): ?>
            <code><?= e($rc) ?></code>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="step">
      <div class="step-num">3</div>
      <div class="step-body">
        <h3>Masukkan kode 6 digit untuk konfirmasi</h3>
        <form method="POST" action="<?= APP_URL ?>/admin/2fa/setup">
          <?= csrfField() ?>
          <input class="code-input" type="text" name="code" inputmode="numeric"
                 placeholder="000000" maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code"
                 required autofocus>
          <button type="submit" class="btn">Aktifkan 2FA</button>
        </form>
      </div>
    </div>

    <p class="footer"><a href="<?= APP_URL ?>/login">Batal &amp; kembali ke login</a></p>
  </div>
</div>
<script src="<?= APP_URL ?>/js/qrcode.min.js"></script>
<script>
(function () {
  var uri = <?= json_encode($otpauthUri, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
  var el = document.getElementById('qrcode');
  if (window.QRCode && el) {
    new QRCode(el, { text: uri, width: 190, height: 190, correctLevel: QRCode.CorrectLevel.M });
  } else if (el) {
    el.innerHTML = '<div style="padding:1rem;color:#122A1C;font-size:.75rem;max-width:190px;word-break:break-all;">QR tidak dapat dimuat. Gunakan kode manual di atas.</div>';
  }
})();
</script>
</body>
</html>
