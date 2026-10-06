<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — Halaman Tidak Ditemukan | Creative Studio</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#122A1C;--paper:#FFFFFF;--cream:#E4E9E5;--accent:#C8FF4D;--muted:#5C6862;--fh:'Archivo Black',sans-serif;--fb:'Inter',sans-serif}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--fb);background:var(--ink);color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:2rem;overflow:hidden}
.bg-num{position:fixed;font-family:var(--fh);font-weight:800;font-size:40vw;color:rgba(255,255,255,.025);line-height:1;pointer-events:none;user-select:none;z-index:0}
.content{position:relative;z-index:1;max-width:520px}
.icon{font-size:3.5rem;margin-bottom:1.5rem;animation:float 3s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.label{font-family:var(--fh);font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--accent);margin-bottom:1rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
.label-line{width:2rem;height:2px;background:var(--accent)}
h1{font-family:var(--fh);font-weight:800;font-size:clamp(2rem,5vw,3.5rem);letter-spacing:-.03em;line-height:1.1;margin-bottom:1rem}
p{font-size:.95rem;color:#555;line-height:1.75;margin-bottom:2.5rem;max-width:40ch;margin-left:auto;margin-right:auto}
.actions{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
.btn-home{padding:.85rem 2rem;background:var(--accent);color:var(--ink);border:2px solid var(--ink);border-radius:0;font-family:var(--fh);font-weight:700;font-size:.9rem;letter-spacing:.04em;text-decoration:none;transition:background .2s,color .2s}
.btn-home:hover{background:var(--ink);color:var(--accent)}
.btn-back{padding:.85rem 2rem;border:1.5px solid #222;color:#555;border-radius:4px;font-family:var(--fh);font-weight:700;font-size:.9rem;letter-spacing:.04em;text-decoration:none;transition:all .2s;cursor:pointer;background:none}
.btn-back:hover{border-color:#444;color:#888}
.quick-links{margin-top:3rem;display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap}
.quick-links a{font-size:.8rem;color:#333;text-decoration:none;transition:color .15s}
.quick-links a:hover{color:#666}
</style>
</head>
<body>
<div class="bg-num" aria-hidden="true">404</div>
<div class="content">
  <div class="icon">◈</div>
  <div class="label"><span class="label-line"></span>Error 404<span class="label-line"></span></div>
  <h1>Halaman Tidak<br>Ditemukan</h1>
  <p>Sepertinya halaman yang Anda cari sudah dipindahkan, dihapus, atau memang tidak pernah ada. Tidak masalah — kami bantu arahkan Anda.</p>
  <div class="actions">
    <a href="<?= APP_URL ?>/" class="btn-home">← Kembali ke Beranda</a>
    <button onclick="history.back()" class="btn-back">Halaman Sebelumnya</button>
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
