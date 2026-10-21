<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — Halaman Tidak Ditemukan | Creative Studio</title>
<meta name="robots" content="noindex">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
body{background:var(--ink);color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:2rem;overflow:hidden}
.bg-num{position:fixed;font-family:var(--fh);font-weight:800;font-size:40vw;color:rgba(255,255,255,.03);line-height:1;pointer-events:none;user-select:none;z-index:0}
.content{position:relative;z-index:1;max-width:560px}
.icon{font-size:3.5rem;margin-bottom:1.5rem;animation:float 3s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.label{font-family:var(--fm);font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--accent);margin-bottom:1rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
.label-line{width:2rem;height:2px;background:var(--accent)}
h1{font-family:var(--fh);font-weight:800;font-size:clamp(2rem,5vw,3.4rem);letter-spacing:-.02em;line-height:1.08;margin-bottom:1rem}
p{font-size:.95rem;color:#B7C7BC;line-height:1.8;margin-bottom:2.25rem;max-width:42ch;margin-left:auto;margin-right:auto}
.actions{display:flex;gap:.85rem;justify-content:center;flex-wrap:wrap}
.btn-light{padding:.8rem 1.6rem;border:1.5px solid rgba(255,255,255,.35);color:#fff;border-radius:10px;font-family:var(--fm);font-weight:700;font-size:.8rem;letter-spacing:.03em;text-transform:uppercase;background:none;cursor:pointer;transition:background .15s,border-color .15s}
.btn-light:hover{background:rgba(255,255,255,.08);border-color:#fff}
.quick-links{margin-top:3rem;display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap}
.quick-links a{font-family:var(--fm);font-size:.78rem;color:#8FA697;text-decoration:none;transition:color .15s}
.quick-links a:hover{color:var(--accent)}
</style>
</head>
<body>
<div class="bg-num" aria-hidden="true">404</div>
<div class="content">
  <div class="icon" aria-hidden="true">◈</div>
  <div class="label"><span class="label-line"></span>Error 404<span class="label-line"></span></div>
  <h1>Halaman Tidak<br>Ditemukan</h1>
  <p>Sepertinya halaman yang Anda cari sudah dipindahkan, dihapus, atau memang tidak pernah ada. Tidak masalah — kami bantu arahkan Anda.</p>
  <div class="actions">
    <a href="<?= APP_URL ?>/" class="btn btn-accent">← Kembali ke Beranda</a>
    <a href="<?= e(function_exists('site_wa_url') ? site_wa_url('Halo, saya butuh bantuan menemukan halaman di website Creative Studio.') : APP_URL . '/contact') ?>" class="btn-light" target="_blank" rel="noopener">💬 Tanya via WhatsApp</a>
  </div>
  <div class="quick-links">
    <a href="<?= APP_URL ?>/services">Layanan</a>
    <a href="<?= APP_URL ?>/portfolio">Portfolio</a>
    <a href="<?= APP_URL ?>/contact">Kontak</a>
    <a href="<?= APP_URL ?>/login">Masuk</a>
  </div>
</div>
</body>
</html>
