<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creative Studio — Identitas Visual yang Berbicara untuk Bisnis Anda</title>
<meta name="description" content="Studio desain profesional Indonesia. Branding, UI/UX, Social Media Kit, Motion Graphic — dikerjakan dengan presisi dan tujuan.">
<meta property="og:title" content="Creative Studio — Identitas Visual yang Berbicara">
<meta property="og:description" content="Studio desain profesional Indonesia untuk bisnis yang ingin tampil lebih kuat dan berkesan.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>

/* ── HERO ───────────────────────────────────────────────── */
.hero{
  min-height:100vh;
  background:var(--ink);
  display:flex;flex-direction:column;justify-content:center;
  padding:calc(var(--nav-h) + 3rem) clamp(1rem,4vw,3rem) 3rem;
  position:relative;overflow:hidden;
}
.hero-bg-word{
  position:absolute;bottom:-3rem;left:-1rem;
  font-family:var(--fh);font-weight:800;
  font-size:clamp(5rem,18vw,16rem);
  color:rgba(255,255,255,.025);
  line-height:1;pointer-events:none;
  white-space:nowrap;letter-spacing:-.04em;
  user-select:none;
}
.hero-inner{
  display:grid;grid-template-columns:1fr 1fr;
  gap:4rem;max-width:1200px;margin:0 auto;width:100%;
  align-items:center;position:relative;z-index:1;
}
.hero-tag{
  display:inline-flex;align-items:center;gap:.5rem;
  font-family:var(--fh);font-size:.72rem;font-weight:700;
  letter-spacing:.14em;text-transform:uppercase;
  color:var(--accent);margin-bottom:1.5rem;
}
.hero-tag-dot{
  width:6px;height:6px;border-radius:50%;
  background:var(--accent);
  animation:pulse 2s infinite;
}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.45;transform:scale(1.5)}}
.hero h1{
  font-family:var(--fh);font-weight:800;
  font-size:clamp(2.6rem,6vw,4.75rem);
  line-height:1.02;letter-spacing:-.035em;
  color:#fff;margin-bottom:1.5rem;
}
.h1-accent{color:var(--accent)}
.h1-outline{
  -webkit-text-stroke:1.5px rgba(255,255,255,.35);
  color:transparent;
}
.hero-desc{color:#666;font-size:.95rem;line-height:1.85;max-width:44ch;margin-bottom:2.5rem}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}
.btn-hero-p{
  padding:.85rem 1.85rem;background:var(--accent);color:var(--ink);border:2px solid var(--ink);
  border-radius:0;font-family:var(--fm);font-weight:700;font-size:.82rem;text-transform:uppercase;
  letter-spacing:.03em;transition:transform .18s,box-shadow .18s;
  display:inline-flex;align-items:center;gap:.5rem;
}
.btn-hero-p:hover{transform:translate(-2px,-2px);box-shadow:2px 2px 0 var(--ink)}
.btn-hero-g{
  padding:.85rem 1.85rem;border:2px solid #3E5747;color:#C7D4CB;
  border-radius:0;font-family:var(--fm);font-weight:700;font-size:.82rem;text-transform:uppercase;
  letter-spacing:.03em;transition:all .18s;
}
.btn-hero-g:hover{border-color:var(--accent);color:var(--accent)}

/* Hero dashboard cards */
.hero-visual{display:flex;flex-direction:column;gap:1rem}
.hv-card{border-radius:14px;padding:1.5rem;position:relative;overflow:hidden}
.hv-main{background:#161616;border:1px solid #222}
.hv-row{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
.hv-sm{background:#111;border:1px solid #1c1c1c;border-radius:12px;padding:1.1rem}
.hv-lbl{font-family:var(--fh);font-size:.62rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#3a3a3a;margin-bottom:.3rem}
.hv-ttl{font-family:var(--fh);font-weight:700;font-size:.95rem;color:#fff;line-height:1.3}
.hv-sub{font-size:.75rem;color:#444;margin-top:.2rem}
.hv-dot{width:7px;height:7px;border-radius:50%;background:var(--accent);display:inline-block;margin-right:.35rem}
.hv-num{font-family:var(--fh);font-weight:800;font-size:1.75rem;color:#fff;line-height:1}
.hv-num sup{font-size:.9rem;color:var(--accent)}
.hv-bar{height:3px;background:var(--accent);border-radius:2px;margin-top:.85rem;width:43%}
.hv-icon{font-size:1.8rem;margin-bottom:.5rem;opacity:.8}

/* Hero stats row */
.hero-stats{
  max-width:1200px;margin:3rem auto 0;width:100%;
  display:grid;grid-template-columns:repeat(4,1fr);
  border-top:1px solid #1a1a1a;
  position:relative;z-index:1;
}
.hero-stat{padding:1.5rem 0;border-right:1px solid #1a1a1a}
.hero-stat:first-child{padding-left:0}
.hero-stat:not(:first-child){padding-left:2rem}
.hero-stat:last-child{border-right:none}
.stat-num{font-family:var(--fh);font-weight:800;font-size:2rem;color:#fff;line-height:1}
.stat-num em{font-style:normal;color:var(--accent)}
.stat-lbl{font-size:.75rem;color:#444;margin-top:.3rem}

/* ── TICKER ─────────────────────────────────────────────── */
.ticker{background:var(--accent);overflow:hidden;padding:.7rem 0;border-top:var(--bw) solid var(--ink);border-bottom:var(--bw) solid var(--ink)}
.ticker-track{
  display:flex;gap:2.5rem;white-space:nowrap;
  animation:marquee 22s linear infinite;
}
.ticker-item{
  display:flex;align-items:center;gap:.65rem;flex-shrink:0;
  font-family:var(--fm);font-size:.75rem;font-weight:700;
  letter-spacing:.03em;text-transform:uppercase;color:var(--ink);
}
.ticker-sep{opacity:.6}
@keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}

/* ── SECTION BASE ───────────────────────────────────────── */
.section{padding:6rem clamp(1rem,4vw,3rem)}
.s-inner{max-width:1200px;margin:0 auto}
.eyebrow{
  display:inline-flex;align-items:center;gap:.5rem;
  font-family:var(--fh);font-size:.7rem;font-weight:700;
  letter-spacing:.14em;text-transform:uppercase;
  color:var(--accent);margin-bottom:1rem;
}
.eyebrow-line{width:2rem;height:2px;background:var(--accent);flex-shrink:0}
h2.stitle{
  font-family:var(--fh);font-weight:800;
  font-size:clamp(1.8rem,3.5vw,2.8rem);
  letter-spacing:-.025em;line-height:1.12;
  margin-bottom:.75rem;
}
.ssub{color:var(--muted);font-size:.95rem;max-width:52ch;line-height:1.8}

/* ── SERVICES GRID ──────────────────────────────────────── */
.svc-grid{
  display:grid;grid-template-columns:repeat(4,1fr);
  gap:1.25rem;margin-top:3rem;
}
.svc-card{
  background:var(--white);border:1px solid var(--cream);
  border-radius:12px;padding:2rem 1.5rem;
  position:relative;overflow:hidden;
  transition:transform .25s cubic-bezier(.2,.8,.2,1),box-shadow .25s;
}
.svc-card::before{
  content:'';position:absolute;left:0;top:0;bottom:0;
  width:4px;background:var(--c,var(--accent));
  transition:width .3s,opacity .3s;
}
.svc-card:hover{transform:translateY(-6px);box-shadow:0 18px 44px rgba(0,0,0,.09)}
.svc-card:hover::before{width:100%;opacity:.04}
.svc-num{
  font-family:var(--fh);font-size:2.75rem;font-weight:800;
  color:var(--cream);line-height:1;margin-bottom:.85rem;
  transition:color .25s,opacity .25s;
}
.svc-card:hover .svc-num{color:var(--c,var(--accent));opacity:.18}
.svc-icon{font-size:1.75rem;margin-bottom:.75rem}
.svc-name{font-family:var(--fh);font-weight:700;font-size:1.05rem;margin-bottom:.45rem}
.svc-desc{font-size:.82rem;color:var(--muted);line-height:1.7;margin-bottom:1.25rem}
.svc-link{
  font-family:var(--fh);font-size:.72rem;font-weight:700;
  letter-spacing:.06em;text-transform:uppercase;
  color:var(--c,var(--accent));
  display:inline-flex;align-items:center;gap:.3rem;
  transition:gap .18s;
}
.svc-card:hover .svc-link{gap:.6rem}

/* ── PORTFOLIO MOSAIC ───────────────────────────────────── */
.p-mosaic{
  display:grid;
  grid-template-columns:repeat(12,1fr);
  grid-template-rows:200px 200px;
  gap:1rem;margin-top:3rem;
}
.pm{border-radius:12px;overflow:hidden;position:relative}
.pm-1{grid-column:1/6;grid-row:1/3}
.pm-2{grid-column:6/9;grid-row:1/2}
.pm-3{grid-column:9/13;grid-row:1/2}
.pm-4{grid-column:6/10;grid-row:2/3}
.pm-5{grid-column:10/13;grid-row:2/3}
.pm-ph{
  width:100%;height:100%;
  display:flex;flex-direction:column;
  align-items:center;justify-content:center;gap:.5rem;
  background:var(--cream);
}
.pm-ph-icon{font-size:2.5rem;opacity:.25}
.pm-ph-cat{font-family:var(--fh);font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);opacity:.5}
.pm-img{width:100%;height:100%;object-fit:cover}
.pm-tag{
  position:absolute;top:.85rem;left:.85rem;
  background:rgba(200,65,43,.92);color:#fff;
  font-family:var(--fh);font-size:.62rem;font-weight:700;
  letter-spacing:.08em;text-transform:uppercase;
  padding:.2rem .65rem;border-radius:2rem;z-index:2;
}
.pm-overlay{
  position:absolute;inset:0;
  background:rgba(13,13,13,.88);
  display:flex;flex-direction:column;justify-content:flex-end;
  padding:1.5rem;opacity:0;transition:opacity .25s;z-index:1;
}
.pm:hover .pm-overlay{opacity:1}
.pm-overlay h3{font-family:var(--fh);font-weight:700;color:#fff;font-size:1rem;margin-bottom:.2rem}
.pm-overlay p{font-size:.75rem;color:#999}

/* ── NUMBERS (dark bg) ──────────────────────────────────── */
.num-section{background:var(--ink);padding:6rem clamp(1rem,4vw,3rem)}
.num-grid{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.num-list{display:flex;flex-direction:column;gap:1rem;margin-top:2rem}
.num-list-item{
  display:flex;align-items:center;gap:.75rem;
  font-size:.875rem;color:#666;
}
.num-list-item::before{content:'✓';color:var(--accent);font-weight:700;flex-shrink:0}
.num-cards{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.nc{background:#0e0e0e;border:1px solid #1e1e1e;border-radius:0;padding:1.75rem}
.nc.featured{background:var(--accent);border-color:var(--ink)}
.nc-val{
  font-family:var(--fh);font-size:2.2rem;
  color:#fff;line-height:1;margin-bottom:.3rem;
}
.nc-val em{font-style:normal;color:var(--accent)}
.nc.featured .nc-val{color:var(--ink)}
.nc.featured .nc-val em{color:rgba(18,42,28,.55)}
.nc-lbl{font-size:.78rem;color:#444;line-height:1.5}
.nc.featured .nc-lbl{color:rgba(18,42,28,.7)}

/* ── PROCESS ────────────────────────────────────────────── */
.proc-grid{
  display:grid;grid-template-columns:repeat(5,1fr);
  gap:0;margin-top:3.5rem;position:relative;
}
.proc-grid::before{
  content:'';position:absolute;
  top:1.4rem;left:10%;right:10%;height:1px;
  background:linear-gradient(to right,transparent,var(--cream) 20%,var(--cream) 80%,transparent);
}
.proc-step{text-align:center;padding:0 .75rem;position:relative;z-index:1}
.proc-ring{
  width:48px;height:48px;border-radius:0;
  background:var(--white);border:2px solid var(--ink);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 1rem;transition:all .25s;
}
.proc-step:hover .proc-ring{background:var(--accent);border-color:var(--ink)}
.proc-n{font-family:var(--fh);font-size:.85rem;color:var(--muted);transition:color .25s}
.proc-step:hover .proc-n{color:var(--ink)}
.proc-t{font-family:var(--fh);font-weight:700;font-size:.875rem;margin-bottom:.3rem}
.proc-d{font-size:.76rem;color:var(--muted);line-height:1.6}

/* ── TESTIMONIALS ───────────────────────────────────────── */
.testi-wrap{background:var(--cream)}
.testi-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;margin-top:3rem}
.testi-card{
  background:var(--white);border:1px solid rgba(0,0,0,.06);
  border-radius:12px;padding:1.75rem;
}
.testi-card.dark{background:var(--ink);border-color:var(--ink)}
.testi-q{font-size:2.25rem;line-height:1;color:var(--accent);font-family:Georgia,serif;margin-bottom:.6rem}
.testi-body{font-size:.875rem;line-height:1.8;color:var(--muted);margin-bottom:1.5rem}
.testi-card.dark .testi-body{color:#666}
.testi-stars{color:#F5A623;font-size:.8rem;margin-bottom:.6rem;letter-spacing:.1em}
.testi-author{display:flex;align-items:center;gap:.75rem}
.testi-av{
  width:40px;height:40px;border-radius:50%;
  background:var(--cream);display:flex;align-items:center;justify-content:center;
  font-family:var(--fh);font-weight:700;font-size:.8rem;color:var(--muted);
  flex-shrink:0;
}
.testi-card.dark .testi-av{background:#1a1a1a;color:#555}
.testi-nm{font-family:var(--fh);font-weight:700;font-size:.875rem}
.testi-card.dark .testi-nm{color:#fff}
.testi-role{font-size:.75rem;color:var(--muted)}

/* ── CTA BANNER ─────────────────────────────────────────── */
.cta-wrap{background:var(--ink);padding:6rem clamp(1rem,4vw,3rem);border-top:var(--bw) solid var(--ink);border-bottom:var(--bw) solid var(--ink)}
.cta-inner{
  max-width:1200px;margin:0 auto;
  display:grid;grid-template-columns:1fr auto;
  gap:4rem;align-items:center;
}
.cta-inner h2{
  font-family:var(--fh);
  font-size:clamp(1.9rem,3.2vw,2.9rem);
  letter-spacing:-.01em;line-height:1.05;color:#fff;text-transform:uppercase;
}
.cta-inner h2 em{font-style:normal;-webkit-text-stroke:1.5px #fff;color:var(--ink)}
.cta-inner p{color:#C7D4CB;font-size:.9rem;line-height:1.8;margin-top:.75rem;max-width:50ch}
.cta-btns{display:flex;flex-direction:column;gap:.85rem;flex-shrink:0}
.btn-cta-p{
  padding:.95rem 1.9rem;background:var(--accent);color:var(--ink);border:2px solid var(--accent);
  border-radius:0;font-family:var(--fm);font-weight:700;font-size:.85rem;text-transform:uppercase;
  letter-spacing:.03em;white-space:nowrap;transition:transform .15s,box-shadow .15s;display:block;text-align:center;
}
.btn-cta-p:hover{transform:translate(-2px,-2px);box-shadow:2px 2px 0 #fff}
.btn-cta-g{
  padding:.95rem 1.9rem;border:2px solid #3E5747;color:#C7D4CB;
  border-radius:0;font-family:var(--fm);font-weight:700;font-size:.85rem;text-transform:uppercase;
  letter-spacing:.03em;white-space:nowrap;transition:all .15s;display:block;text-align:center;
}
.btn-cta-g:hover{border-color:var(--accent);color:var(--accent)}

/* ── FOOTER ─────────────────────────────────────────────── */
.footer{background:#080808;padding:4rem clamp(1rem,4vw,3rem) 2rem}
.footer-top{
  max-width:1200px;margin:0 auto;
  display:grid;grid-template-columns:2fr 1fr 1fr 1fr;
  gap:3rem;padding-bottom:3rem;
  border-bottom:1px solid #111;margin-bottom:2rem;
}
.f-logo{font-family:var(--fh);font-weight:800;font-size:1.3rem;letter-spacing:-.02em;color:#fff;margin-bottom:.85rem}
.f-logo span{color:var(--accent)}
.f-tagline{font-size:.82rem;color:#333;line-height:1.75;max-width:26ch}
.f-col h4{font-family:var(--fh);font-size:.67rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#2a2a2a;margin-bottom:1.1rem}
.f-col ul{list-style:none}
.f-col li{margin-bottom:.6rem}
.f-col a{font-size:.82rem;color:#3a3a3a;transition:color .15s}
.f-col a:hover{color:#777}
.footer-bottom{
  max-width:1200px;margin:0 auto;
  display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;
}
.f-copy{font-size:.75rem;color:#222}
.f-social{display:flex;gap:.6rem}
.f-social a{
  width:32px;height:32px;border:1px solid #161616;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:.7rem;color:#2a2a2a;transition:all .15s;
}
.f-social a:hover{border-color:#333;color:#666}

/* ── FLASH ──────────────────────────────────────────────── */
.flash-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;padding:.85rem 1.25rem;border-radius:4px;margin:1rem 2rem;font-size:.875rem}

/* ── SCROLL REVEAL ──────────────────────────────────────── */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .65s ease,transform .65s ease}
.reveal.visible{opacity:1;transform:translateY(0)}

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media(max-width:1024px){
  .svc-grid{grid-template-columns:repeat(2,1fr)}
  .testi-grid{grid-template-columns:1fr 1fr}
  .proc-grid{grid-template-columns:repeat(3,1fr);gap:2rem}
  .proc-grid::before{display:none}
}
@media(max-width:768px){
  .hero-inner{grid-template-columns:1fr}
  .hero-visual{display:none}
  .hero-stats{grid-template-columns:repeat(2,1fr)}
  .num-grid{grid-template-columns:1fr}
  .cta-inner{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr 1fr}
  .p-mosaic{grid-template-columns:1fr 1fr;grid-template-rows:auto}
  .pm-1,.pm-2,.pm-3,.pm-4,.pm-5{grid-column:auto;grid-row:auto;aspect-ratio:4/3}
}
@media(max-width:480px){
  .svc-grid{grid-template-columns:1fr}
  .testi-grid{grid-template-columns:1fr}
  .num-cards{grid-template-columns:1fr}
  .hero-stats{grid-template-columns:repeat(2,1fr)}
  .footer-top{grid-template-columns:1fr}
  .proc-grid{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<!-- ── NAV ──────────────────────────────────────────────── -->
<?php require VIEW_PATH . '/partials/marquee.php'; ?>
<nav class="nav" id="main-nav">
  <a href="<?= APP_URL ?>/" class="nav-logo">creative<span>.</span></a>
  <ul class="nav-links">
    <li><a href="<?= APP_URL ?>/services">Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio">Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/about">Tentang</a></li>
    <li><a href="<?= APP_URL ?>/contact">Kontak</a></li>
  </ul>
  <div class="nav-right">
    <?php if (Session::isLoggedIn()): ?>
      <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline">Dashboard</a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/login" class="btn btn-outline">Masuk</a>
      <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin memulai proyek desain.')) ?>" class="btn btn-primary" target="_blank" rel="noopener">Mulai Proyek →</a>
    <?php endif; ?>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<?php if ($flash = Session::getFlash('success')): ?>
  <div class="flash-success" style="position:fixed;top:calc(var(--nav-h) + .5rem);left:50%;transform:translateX(-50%);z-index:300;min-width:300px;text-align:center">
    <?= e($flash) ?>
  </div>
<?php endif; ?>

<!-- ── HERO ──────────────────────────────────────────────── -->
<section class="hero">
  <div class="hero-bg-word" aria-hidden="true">CREATIVE</div>

  <div class="hero-inner">
    <!-- Left -->
    <div>
      <div class="hero-tag">
        <span class="hero-tag-dot"></span>
        Studio Desain Profesional — Indonesia
      </div>
      <h1>
        Kami Bangun Brand<br>
        <span class="h1-accent">yang Diingat,</span><br>
        <span class="h1-outline">Bukan Sekadar Dilihat</span>
      </h1>
      <p class="hero-desc">Kami membantu bisnis tumbuh lewat desain yang strategis — dari identitas merek hingga pengalaman digital, semua dikerjakan dengan presisi dan tujuan.</p>
      <div class="hero-actions">
        <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin memulai proyek desain.')) ?>" class="btn-hero-p" target="_blank" rel="noopener">
          Mulai Proyek →
        </a>
        <a href="<?= APP_URL ?>/portfolio" class="btn-hero-g">Lihat Portfolio</a>
      </div>
    </div>

    <!-- Right — dashboard preview cards -->
    <div class="hero-visual" aria-hidden="true">
      <div class="hv-card hv-main">
        <div class="hv-lbl">Proyek Aktif</div>
        <div class="hv-ttl">Brand Identity — Kedai Nusantara</div>
        <div class="hv-sub"><span class="hv-dot"></span>Sedang dikerjakan · Hari ke-3 dari 7</div>
        <div class="hv-bar"></div>
      </div>
      <div class="hv-row">
        <div class="hv-sm">
          <div class="hv-lbl">Proyek Selesai</div>
          <div class="hv-num">150<sup>+</sup></div>
          <div class="hv-sub" style="margin-top:.3rem">All time</div>
        </div>
        <div class="hv-sm">
          <div class="hv-lbl">Kepuasan Klien</div>
          <div class="hv-num">98<sup>%</sup></div>
          <div class="hv-sub" style="margin-top:.3rem">Rating rata-rata</div>
        </div>
        <div class="hv-sm">
          <div class="hv-lbl">Layanan</div>
          <div class="hv-icon">✦</div>
          <div class="hv-ttl" style="font-size:.82rem">Branding</div>
        </div>
        <div class="hv-sm">
          <div class="hv-lbl">Layanan</div>
          <div class="hv-icon">◈</div>
          <div class="hv-ttl" style="font-size:.82rem">UI/UX</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats row -->
  <div class="hero-stats">
    <div class="hero-stat">
      <div class="stat-num">150<em>+</em></div>
      <div class="stat-lbl">Proyek selesai</div>
    </div>
    <div class="hero-stat">
      <div class="stat-num">98<em>%</em></div>
      <div class="stat-lbl">Klien puas</div>
    </div>
    <div class="hero-stat">
      <div class="stat-num">5<em>★</em></div>
      <div class="stat-lbl">Rating rata-rata</div>
    </div>
    <div class="hero-stat">
      <div class="stat-num">3<em>hr</em></div>
      <div class="stat-lbl">Rata-rata respons pertama</div>
    </div>
  </div>
</section>

<!-- ── TICKER ─────────────────────────────────────────────── -->
<div class="ticker" aria-hidden="true">
  <div class="ticker-track">
    <?php
    $tags = ['Branding & Identity','UI/UX Design','Social Media Kit','Motion Graphic','Print & Packaging','Illustration','Typography','Visual Identity'];
    $doubled = array_merge($tags, $tags, $tags, $tags);
    foreach ($doubled as $t): ?>
      <span class="ticker-item"><span class="ticker-sep">✦</span><?= e($t) ?></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── SERVICES ───────────────────────────────────────────── -->
<section class="section">
  <div class="s-inner">
    <div class="reveal">
      <div class="eyebrow"><span class="eyebrow-line"></span>Layanan Kami</div>
      <h2 class="stitle">Apa yang Bisa<br>Kami Kerjakan</h2>
      <p class="ssub">Pilih paket yang sesuai, atau ceritakan kebutuhan Anda — kami bantu dari awal hingga akhir.</p>
    </div>

    <div class="svc-grid reveal" style="transition-delay:.1s">
      <?php
      $catColors = ['branding'=>'#C8412B','uiux'=>'#2B6CC8','socmed'=>'#15803D','motion'=>'#854D0E','print'=>'#5B21B6'];
      $catIcons  = ['branding'=>'✦','uiux'=>'◈','socmed'=>'◉','motion'=>'◎','print'=>'◆'];
      $nums      = ['01','02','03','04','05','06'];

      if (!empty($services)):
        foreach (array_slice($services,0,4) as $i => $svc):
          $slug  = $svc['category_slug'] ?? 'branding';
          $color = $catColors[$slug] ?? '#C8412B';
          $icon  = $catIcons[$slug]  ?? '★';
      ?>
        <div class="svc-card" style="--c:<?= $color ?>">
          <div class="svc-num"><?= $nums[$i] ?></div>
          <div class="svc-icon"><?= $icon ?></div>
          <div class="svc-name"><?= e($svc['name']) ?></div>
          <div class="svc-desc"><?= e($svc['short_desc']) ?></div>
          <a href="<?= APP_URL ?>/services/<?= e($svc['slug']) ?>" class="svc-link">Lihat paket →</a>
        </div>
      <?php endforeach; else:
        $defaults = [
          ['✦','#C8412B','01','Branding & Identity','Logo, color system, tipografi, dan panduan merek yang konsisten dan berkarakter.','branding-identity'],
          ['◈','#2B6CC8','02','UI/UX Design','Desain antarmuka web & aplikasi yang intuitif, estetis, dan siap handoff ke developer.','uiux-design'],
          ['◉','#15803D','03','Social Media Kit','Template feed, story, highlight cover yang konsisten dan langsung bisa diedit tim Anda.','social-media-kit'],
          ['◎','#854D0E','04','Motion & Video','Animasi logo, motion infografis, dan video pendek untuk iklan maupun konten organik.','motion-video'],
        ];
        foreach ($defaults as [$icon,$color,$num,$name,$desc,$sl]): ?>
          <div class="svc-card" style="--c:<?= $color ?>">
            <div class="svc-num"><?= $num ?></div>
            <div class="svc-icon"><?= $icon ?></div>
            <div class="svc-name"><?= $name ?></div>
            <div class="svc-desc"><?= $desc ?></div>
            <a href="<?= APP_URL ?>/services/<?= $sl ?>" class="svc-link">Lihat paket →</a>
          </div>
      <?php endforeach; endif; ?>
    </div>

    <div style="text-align:center;margin-top:2.5rem">
      <a href="<?= APP_URL ?>/services" class="btn btn-outline">Semua Layanan →</a>
    </div>
  </div>
</section>

<!-- ── PORTFOLIO ──────────────────────────────────────────── -->
<section class="section" style="background:var(--cream);padding-top:5rem;padding-bottom:5rem">
  <div class="s-inner">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem" class="reveal">
      <div>
        <div class="eyebrow"><span class="eyebrow-line"></span>Portfolio</div>
        <h2 class="stitle">Karya Terbaik<br>yang Sudah Kami Buat</h2>
      </div>
      <a href="<?= APP_URL ?>/portfolio" style="font-family:var(--fh);font-size:.8rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);display:inline-flex;align-items:center;gap:.4rem;margin-bottom:.5rem;transition:color .15s">
        Semua Portfolio →
      </a>
    </div>

    <div class="p-mosaic reveal" style="transition-delay:.12s">
      <?php
      $pmDemos = [
        ['pm-1','#1e1e1e','✦','Branding','Full Brand System — TechVenture','Logo · Color System · Guideline'],
        ['pm-2','#e8e4dc','◈','UI/UX','Landing Page — StartupX','Figma · Responsive'],
        ['pm-3','#d4e8d4','◉','Social Media','Social Kit — BeautyBrand','Feed · Story · Highlight'],
        ['pm-4','#f0e8d4','◎','Motion','Intro Animasi — EventKreatiF','After Effects · 30 detik'],
        ['pm-5','#e8d4d4','◆','Print','Stationery — PT Maju','Kartu Nama · Amplop · KOP'],
      ];
      $pItems = !empty($portfolio) ? array_slice($portfolio,0,5) : null;
      foreach ($pmDemos as $i => [$cls,$bg,$icon,$cat,$title,$sub]):
        $p = $pItems[$i] ?? null;
      ?>
        <div class="pm <?= $cls ?>" style="background:<?= $bg ?>">
          <?php if ($p && $p['cover_image']): ?>
            <img class="pm-img" src="<?= APP_URL ?>/uploads/<?= e($p['cover_image']) ?>" alt="<?= e($p['title']) ?>">
          <?php else: ?>
            <div class="pm-ph">
              <div class="pm-ph-icon"><?= $icon ?></div>
              <div class="pm-ph-cat"><?= $cat ?></div>
            </div>
          <?php endif; ?>
          <span class="pm-tag"><?= $cat ?></span>
          <div class="pm-overlay">
            <h3><?= $p ? e($p['title']) : $title ?></h3>
            <p><?= $p ? e($p['client_name'] ?? '') : $sub ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── NUMBERS ────────────────────────────────────────────── -->
<section class="num-section">
  <div class="num-grid">
    <div class="reveal">
      <div class="eyebrow" style="color:var(--accent)"><span class="eyebrow-line"></span>Mengapa Kami</div>
      <h2 class="stitle" style="color:#fff">Desain Bukan<br>Sekadar Estetika</h2>
      <p style="color:#555;font-size:.95rem;line-height:1.8;margin-top:.75rem;max-width:44ch">Setiap keputusan visual yang kami buat punya alasan strategis. Kami merancang untuk membantu bisnis Anda tumbuh, bukan sekadar terlihat bagus.</p>
      <div class="num-list">
        <div class="num-list-item">Brief & revisi tanpa batasan komunikasi</div>
        <div class="num-list-item">File lengkap siap pakai + source file disertakan</div>
        <div class="num-list-item">Pengerjaan tepat waktu, dijamin</div>
        <div class="num-list-item">Progress update berkala via dashboard klien</div>
        <div class="num-list-item">Konsultasi gratis sebelum mulai proyek</div>
      </div>
    </div>
    <div class="num-cards reveal" style="transition-delay:.15s">
      <div class="nc featured">
        <div class="nc-val">150<em>+</em></div>
        <div class="nc-lbl">Proyek selesai<br>sejak 2019</div>
      </div>
      <div class="nc">
        <div class="nc-val">98<em style="color:var(--accent)">%</em></div>
        <div class="nc-lbl">Klien menyatakan<br>puas dengan hasil</div>
      </div>
      <div class="nc">
        <div class="nc-val">5<em style="color:var(--accent)">★</em></div>
        <div class="nc-lbl">Rating rata-rata<br>semua layanan</div>
      </div>
      <div class="nc">
        <div class="nc-val">3<em style="color:var(--accent)">hr</em></div>
        <div class="nc-lbl">Rata-rata respons<br>pertama kami</div>
      </div>
    </div>
  </div>
</section>

<!-- ── PROCESS ────────────────────────────────────────────── -->
<section class="section">
  <div class="s-inner">
    <div style="text-align:center" class="reveal">
      <div class="eyebrow" style="justify-content:center">
        <span class="eyebrow-line"></span>Cara Kerja<span class="eyebrow-line"></span>
      </div>
      <h2 class="stitle" style="text-align:center">5 Langkah Simpel,<br>Hasil Maksimal</h2>
    </div>
    <div class="proc-grid reveal" style="transition-delay:.1s">
      <?php
      $steps = [
        ['01','Konsultasi','Ceritakan kebutuhan dan visi Anda secara gratis, tanpa komitmen'],
        ['02','Penawaran','Kami kirim proposal harga & timeline yang transparan dalam 1x24 jam'],
        ['03','Pembayaran','Bayar aman via Midtrans: QRIS, transfer bank, GoPay, OVO'],
        ['04','Pengerjaan','Tim kami bekerja & Anda bisa pantau progres via dashboard klien'],
        ['05','Selesai','File final diserahkan. Bayar hanya jika Anda benar-benar puas'],
      ];
      foreach ($steps as [$n,$t,$d]): ?>
        <div class="proc-step">
          <div class="proc-ring"><span class="proc-n"><?= $n ?></span></div>
          <div class="proc-t"><?= $t ?></div>
          <div class="proc-d"><?= $d ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── TESTIMONIALS ───────────────────────────────────────── -->
<section class="section testi-wrap">
  <div class="s-inner">
    <div class="reveal">
      <div class="eyebrow"><span class="eyebrow-line"></span>Testimoni</div>
      <h2 class="stitle">Yang Klien Katakan<br>Tentang Kami</h2>
    </div>
    <div class="testi-grid reveal" style="transition-delay:.12s">
      <?php if (!empty($testimonials)):
        foreach (array_slice($testimonials,0,3) as $i => $t): ?>
          <div class="testi-card <?= $i === 0 ? 'dark' : '' ?>">
            <div class="testi-q">"</div>
            <div class="testi-body"><?= e($t['content']) ?></div>
            <div class="testi-stars"><?= str_repeat('★',(int)$t['rating']) ?></div>
            <div class="testi-author">
              <div class="testi-av"><?= strtoupper(substr($t['client_name'],0,2)) ?></div>
              <div>
                <div class="testi-nm"><?= e($t['client_name']) ?></div>
                <div class="testi-role"><?= e($t['client_title'] ?? '') ?></div>
              </div>
            </div>
          </div>
        <?php endforeach;
      else:
        $demotesti = [
          ['RK','Rina Kusuma','CEO Skincare Brand','Hasilnya jauh melampaui ekspektasi kami. Tim ini benar-benar memahami karakter brand — bukan sekadar membuat logo cantik, tapi identitas yang punya makna.', true],
          ['DP','Danu Prasetyo','Founder App Startup','UI yang mereka buat langsung disukai investor di pitching. Prosesnya sangat terstruktur dan komunikatif. Akan terus pakai jasa mereka.', false],
          ['SD','Sari Dewanti','Marketing Manager','Template social media yang kami terima sangat premium. Konten jadi jauh lebih konsisten dan engagement naik signifikan bulan pertama.', false],
        ];
        foreach ($demotesti as [$av,$nm,$role,$body,$dark]): ?>
          <div class="testi-card <?= $dark ? 'dark' : '' ?>">
            <div class="testi-q">"</div>
            <div class="testi-body"><?= $body ?></div>
            <div class="testi-stars">★★★★★</div>
            <div class="testi-author">
              <div class="testi-av"><?= $av ?></div>
              <div>
                <div class="testi-nm"><?= $nm ?></div>
                <div class="testi-role"><?= $role ?></div>
              </div>
            </div>
          </div>
        <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>

<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="cta-wrap">
  <div class="cta-inner reveal">
    <div>
      <h2>Siap Wujudkan<br>Identitas Visual <em>Bisnis Anda</em>?</h2>
      <p>Konsultasi gratis, tanpa komitmen. Kami siap bantu dari brief pertama hingga file final diserahkan.</p>
    </div>
    <div class="cta-btns">
      <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin memulai proyek desain.')) ?>" class="btn-cta-p" target="_blank" rel="noopener">
        💬 Mulai Proyek via WhatsApp →
      </a>
      <a href="<?= APP_URL ?>/contact" class="btn-cta-g">
        Isi Form Konsultasi
      </a>
    </div>
  </div>
</section>

<!-- ── FOOTER ─────────────────────────────────────────────── -->
<footer class="footer">
  <div class="footer-top">
    <div>
      <div class="f-logo">creative<span>.</span></div>
      <div class="f-tagline">Mitra desain profesional untuk bisnis yang ingin tampil lebih kuat dan berkesan.</div>
    </div>
    <div class="f-col">
      <h4>Layanan</h4>
      <ul>
        <li><a href="<?= APP_URL ?>/services">Branding & Identity</a></li>
        <li><a href="<?= APP_URL ?>/services">UI/UX Design</a></li>
        <li><a href="<?= APP_URL ?>/services">Social Media Kit</a></li>
        <li><a href="<?= APP_URL ?>/services">Motion & Video</a></li>
        <li><a href="<?= APP_URL ?>/services">Print & Packaging</a></li>
      </ul>
    </div>
    <div class="f-col">
      <h4>Perusahaan</h4>
      <ul>
        <li><a href="<?= APP_URL ?>/about">Tentang Kami</a></li>
        <li><a href="<?= APP_URL ?>/portfolio">Portfolio</a></li>
        <li><a href="<?= APP_URL ?>/contact">Kontak</a></li>
      </ul>
    </div>
    <div class="f-col">
      <h4>Akun</h4>
      <ul>
        <li><a href="<?= APP_URL ?>/login">Masuk</a></li>
        <li><a href="<?= APP_URL ?>/register">Daftar</a></li>
        <li><a href="<?= APP_URL ?>/dashboard">Dashboard</a></li>
        <li><a href="<?= APP_URL ?>/dashboard/orders">Lacak Pesanan</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="f-copy">© <?= date('Y') ?> Creative Studio. Semua hak dilindungi.</div>
    <div class="f-social">
      <?php if (site_setting('site_instagram')): ?><a href="<?= e(site_setting('site_instagram')) ?>" title="Instagram" target="_blank" rel="noopener">ig</a><?php endif; ?>
      <a href="<?= e(site_wa_url()) ?>" title="WhatsApp" target="_blank" rel="noopener">wa</a>
      <?php if (site_setting('site_linkedin')): ?><a href="<?= e(site_setting('site_linkedin')) ?>" title="LinkedIn" target="_blank" rel="noopener">in</a><?php endif; ?>
    </div>
  </div>
</footer>

<script>
/* ── Nav scroll effect ──────────────────────────────────── */
const nav = document.getElementById('main-nav');
window.addEventListener('scroll', () => {
  nav.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* ── Scroll reveal ──────────────────────────────────────── */
const observer = new IntersectionObserver(entries => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      setTimeout(() => entry.target.classList.add('visible'), i * 80);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.07 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

/* ── Auto-dismiss flash ─────────────────────────────────── */
const flash = document.querySelector('.flash-success');
if (flash) setTimeout(() => flash.style.opacity = '0', 3500);
</script>

<?php require VIEW_PATH . '/partials/whatsapp-float.php'; ?>
<?php require VIEW_PATH . '/partials/nav-script.php'; ?>
</body>
</html>
