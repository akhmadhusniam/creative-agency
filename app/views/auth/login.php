<?php
/** @var string $title */
$old     = Session::getFlash('old', []);
$error   = Session::getFlash('error');
$success = Session::getFlash('success');
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> — <?= e(APP_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#122A1C; --paper:#FFFFFF; --cream:#E4E9E5;
  --accent:#C8FF4D; --muted:#5C6862; --line:#DFE5E1;
  --fh:'Syne',sans-serif; --fb:'Inter',sans-serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;}
body{font-family:var(--fb);background:var(--paper);color:var(--ink);}
a{color:inherit;}

.split{display:grid;grid-template-columns:1.05fr .95fr;min-height:100vh;}

/* ── Brand panel ─────────────────────────────────────────── */
.brand{position:relative;background:var(--ink);color:#fff;padding:3rem clamp(2rem,4vw,4.5rem);display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;}
.brand::before{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:46px 46px;pointer-events:none;}
.brand-glow{position:absolute;width:440px;height:440px;border-radius:50%;background:radial-gradient(circle,rgba(200,255,77,.20),transparent 62%);top:-130px;right:-130px;pointer-events:none;}
.brand>*{position:relative;z-index:1;}
.brand-logo{font-family:var(--fh);font-weight:800;font-size:1.5rem;letter-spacing:-.02em;text-decoration:none;color:#fff;align-self:flex-start;}
.brand-logo span{color:var(--accent);}
.brand-mid{max-width:36ch;}
.brand-tag{display:inline-flex;align-items:center;gap:.5rem;font-size:.68rem;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--accent);border:1px solid rgba(200,255,77,.3);border-radius:999px;padding:.35rem .85rem;margin-bottom:1.5rem;}
.brand-mid h2{font-family:var(--fh);font-weight:800;font-size:clamp(1.9rem,3vw,2.85rem);line-height:1.06;letter-spacing:-.03em;margin-bottom:1.25rem;color:#fff;}
.brand-mid h2 em{font-style:normal;color:var(--accent);}
.brand-mid p{color:#9FB0A6;font-size:.92rem;line-height:1.75;margin-bottom:1.9rem;}
.brand-list{list-style:none;display:grid;gap:.7rem;}
.brand-list li{display:flex;align-items:center;gap:.7rem;font-size:.86rem;color:#C7D4CB;}
.brand-list .tick{flex:0 0 20px;width:20px;height:20px;border-radius:50%;background:rgba(200,255,77,.14);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:.62rem;font-weight:700;}
.brand-foot{font-size:.75rem;color:#6E7E74;}

/* ── Form panel ──────────────────────────────────────────── */
.form-side{display:flex;align-items:center;justify-content:center;padding:3rem clamp(1.5rem,4vw,4rem);}
.form-wrap{width:100%;max-width:400px;}
.form-head{margin-bottom:2rem;}
.form-head .eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin-bottom:.6rem;}
.form-head h1{font-family:var(--fh);font-weight:800;font-size:2rem;letter-spacing:-.02em;margin-bottom:.5rem;}
.form-head p{color:var(--muted);font-size:.9rem;line-height:1.6;}

.alert{padding:.8rem 1rem;border-radius:10px;font-size:.85rem;margin-bottom:1.3rem;line-height:1.5;}
.alert-error{background:#FDECEC;color:#B42318;border:1px solid #F6C6C6;}
.alert-success{background:#EAFBE0;color:#2E6B1F;border:1px solid #CDEEBB;}

.form-group{margin-bottom:1.15rem;}
label{display:block;font-size:.8rem;font-weight:600;margin-bottom:.45rem;}
.input-wrap{position:relative;}
input{width:100%;padding:.82rem 1rem;border:1.5px solid var(--line);border-radius:10px;font-family:var(--fb);font-size:.92rem;background:#fff;color:var(--ink);transition:border-color .15s,box-shadow .15s;}
input::placeholder{color:#9AA79F;}
input:focus{outline:none;border-color:var(--ink);box-shadow:0 0 0 3px rgba(18,42,28,.08);}
input.has-toggle{padding-right:4.4rem;}
.toggle-pw{position:absolute;right:.55rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-family:var(--fb);font-size:.7rem;font-weight:700;letter-spacing:.05em;padding:.35rem .5rem;border-radius:6px;transition:background .15s,color .15s;}
.toggle-pw:hover{color:var(--ink);background:var(--cream);}
.row-between{display:flex;justify-content:flex-end;margin-top:.5rem;}
.row-between a{font-size:.78rem;color:var(--muted);text-decoration:none;}
.row-between a:hover{color:var(--ink);text-decoration:underline;}

.btn{display:flex;align-items:center;justify-content:center;gap:.55rem;width:100%;padding:.9rem;border:2px solid var(--ink);border-radius:10px;font-family:var(--fb);font-weight:700;font-size:.9rem;letter-spacing:.02em;cursor:pointer;background:var(--accent);color:var(--ink);transition:background .15s,color .15s;}
.btn:hover{background:var(--ink);color:var(--accent);}
.btn:disabled{cursor:progress;background:var(--ink);color:var(--accent);opacity:.85;}
.btn .spinner{width:15px;height:15px;border:2px solid rgba(200,255,77,.3);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite;display:none;}
.btn.is-loading .spinner{display:inline-block;}
@keyframes spin{to{transform:rotate(360deg)}}

.form-foot{text-align:center;font-size:.85rem;color:var(--muted);margin-top:1.5rem;}
.form-foot a{font-weight:600;color:var(--ink);text-decoration:underline;}
.back-site{display:inline-flex;align-items:center;gap:.35rem;margin-top:1.6rem;font-size:.8rem;color:var(--muted);text-decoration:none;}
.back-site:hover{color:var(--ink);}

@media(max-width:880px){
  .split{grid-template-columns:1fr;}
  .brand{padding:2rem 1.5rem;justify-content:flex-start;gap:1.25rem;}
  .brand-mid h2{font-size:1.7rem;}
  .brand-mid p{display:none;}
  .brand-list{display:none;}
  .brand-foot{display:none;}
  .form-side{padding:2.5rem 1.5rem;}
}
</style>
</head>
<body>
<div class="split">
  <aside class="brand">
    <div class="brand-glow"></div>
    <a href="<?= APP_URL ?>/" class="brand-logo">creative<span>.</span></a>
    <div class="brand-mid">
      <span class="brand-tag">Studio Desain Profesional</span>
      <h2>Masuk &amp; lanjutkan <em>karya Anda.</em></h2>
      <p>Satu pintu untuk klien dan tim. Kelola proyek, pantau progres, dan koordinasi desain dengan rapi dari satu dasbor.</p>
      <ul class="brand-list">
        <li><span class="tick">✓</span> Branding &amp; Identitas Visual</li>
        <li><span class="tick">✓</span> UI/UX &amp; Web Design</li>
        <li><span class="tick">✓</span> Social Media Kit</li>
        <li><span class="tick">✓</span> Motion Graphic</li>
      </ul>
    </div>
    <div class="brand-foot">© <?= date('Y') ?> <?= e(APP_NAME) ?>. Dibuat dengan presisi.</div>
  </aside>

  <main class="form-side">
    <div class="form-wrap">
      <div class="form-head">
        <div class="eyebrow">Selamat datang kembali</div>
        <h1>Masuk ke Akun</h1>
        <p>Masukkan email dan password untuk melanjutkan.</p>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-error" role="alert" aria-live="polite"><?= e($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success" role="status" aria-live="polite"><?= e($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= APP_URL ?>/login" id="loginForm">
        <?= csrfField() ?>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
                 value="<?= e($old['email'] ?? '') ?>"
                 placeholder="nama@email.com" required autocomplete="email"
                 autocapitalize="none" spellcheck="false" autofocus>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" class="has-toggle"
                   placeholder="Masukkan password" required autocomplete="current-password">
            <button type="button" class="toggle-pw" id="toggleBtn" aria-label="Tampilkan password">LIHAT</button>
          </div>
          <div class="row-between">
            <a href="<?= APP_URL ?>/forgot-password">Lupa password?</a>
          </div>
        </div>
        <button type="submit" class="btn" id="submitBtn">
          <span class="spinner" aria-hidden="true"></span>
          <span class="btn-label">Masuk</span>
        </button>
      </form>

      <p class="form-foot">
        Belum punya akun? <a href="<?= APP_URL ?>/register">Daftar sekarang</a>
      </p>
      <div style="text-align:center;">
        <a href="<?= APP_URL ?>/" class="back-site">← Kembali ke situs</a>
      </div>
    </div>
  </main>
</div>
<script>
(function () {
  var pw = document.getElementById('password');
  var toggle = document.getElementById('toggleBtn');
  if (toggle && pw) {
    toggle.addEventListener('click', function () {
      var show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      toggle.textContent = show ? 'SEMBUNYI' : 'LIHAT';
      toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
  }

  var form = document.getElementById('loginForm');
  var btn = document.getElementById('submitBtn');
  var label = btn ? btn.querySelector('.btn-label') : null;
  if (form && btn) {
    form.addEventListener('submit', function (ev) {
      if (form.dataset.submitting === '1') { ev.preventDefault(); return; }
      form.dataset.submitting = '1';
      btn.classList.add('is-loading');
      btn.disabled = true;
      if (label) { label.textContent = 'Memverifikasi…'; }
    });
  }
})();
</script>
</body>
</html>
