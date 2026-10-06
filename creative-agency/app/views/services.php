<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layanan — Creative Studio</title>
<meta name="description" content="Jasa desain profesional: branding, UI/UX, social media, motion graphic, dan print design. Harga transparan, hasil berkualitas.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
/* ── Tokens (sama dengan home.php) ───────────────────────── */
:root {
  --ink:     #0D0D0D;
  --paper:   #F7F5F0;
  --cream:   #EDEBE4;
  --accent:  #C8412B;
  --accent2: #2B6CC8;
  --muted:   #7A7570;
  --white:   #FFFFFF;
  --radius:  6px;
  --gap:     clamp(1rem, 4vw, 2.5rem);
  --ff-head: 'Syne', sans-serif;
  --ff-body: 'Inter', sans-serif;
  --nav-h:   64px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--ff-body); background: var(--paper); color: var(--ink); line-height: 1.6; }
a { color: inherit; text-decoration: none; }
img { max-width: 100%; display: block; }

/* ── Nav ─────────────────────────────────────────────────── */
.nav {
  position: sticky; top: 0; z-index: 100;
  height: var(--nav-h);
  background: var(--paper);
  border-bottom: 1px solid var(--cream);
  display: flex; align-items: center;
}
.nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); width: 100%; display: flex; align-items: center; justify-content: space-between; }
.nav-logo { font-family: var(--ff-head); font-weight: 800; font-size: 1.25rem; letter-spacing: -.02em; }
.nav-logo span { color: var(--accent); }
.nav-links { display: flex; gap: 2rem; list-style: none; }
.nav-links a { font-size: .875rem; font-weight: 500; color: var(--muted); transition: color .15s; }
.nav-links a:hover, .nav-links a.active { color: var(--ink); }
.nav-cta { display: flex; gap: .75rem; }
.btn { display: inline-block; padding: .65rem 1.4rem; border-radius: var(--radius); font-family: var(--ff-head); font-weight: 700; font-size: .85rem; letter-spacing: .04em; cursor: pointer; border: 2px solid transparent; transition: all .2s; }
.btn-primary { background: var(--accent); color: var(--white); }
.btn-primary:hover { background: #A83422; }
.btn-outline { border-color: var(--ink); color: var(--ink); }
.btn-outline:hover { background: var(--ink); color: var(--white); }

/* ── Hero seksi kecil ────────────────────────────────────── */
.page-hero {
  background: var(--ink);
  padding: 5rem 0 4rem;
  position: relative;
  overflow: hidden;
}
.page-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 70% 50%, rgba(200,65,43,.18) 0%, transparent 65%);
}
.page-hero .container { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); position: relative; }
.breadcrumb { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; }
.breadcrumb a { font-size: .8rem; color: #666; transition: color .15s; }
.breadcrumb a:hover { color: #aaa; }
.breadcrumb-sep { color: #444; font-size: .75rem; }
.breadcrumb span { font-size: .8rem; color: #aaa; }
.page-hero h1 {
  font-family: var(--ff-head); font-weight: 800;
  font-size: clamp(2rem, 5vw, 3.5rem);
  line-height: 1.1; letter-spacing: -.03em;
  color: var(--white);
  margin-bottom: 1rem;
}
.page-hero h1 em { font-style: normal; color: var(--accent); }
.page-hero p { color: #888; font-size: 1rem; max-width: 52ch; line-height: 1.75; }
.hero-meta { display: flex; gap: 2.5rem; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #222; }
.hero-meta-item { }
.hero-meta-num { font-family: var(--ff-head); font-size: 1.6rem; font-weight: 800; color: var(--white); }
.hero-meta-label { font-size: .75rem; color: #555; margin-top: .15rem; }

/* ── Main layout ─────────────────────────────────────────── */
.main-wrap { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); }

/* ── Filter tabs ─────────────────────────────────────────── */
.filter-bar {
  position: sticky; top: var(--nav-h); z-index: 90;
  background: var(--paper);
  border-bottom: 1px solid var(--cream);
  padding: .85rem 0;
}
.filter-inner { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); display: flex; align-items: center; gap: .5rem; overflow-x: auto; scrollbar-width: none; }
.filter-inner::-webkit-scrollbar { display: none; }
.filter-btn {
  flex-shrink: 0;
  padding: .45rem 1.1rem;
  border-radius: 2rem;
  border: 1.5px solid var(--cream);
  background: var(--white);
  font-family: var(--ff-head); font-weight: 700; font-size: .78rem;
  letter-spacing: .05em; text-transform: uppercase;
  color: var(--muted); cursor: pointer;
  transition: all .18s;
  white-space: nowrap;
}
.filter-btn:hover { border-color: var(--ink); color: var(--ink); }
.filter-btn.active { background: var(--ink); border-color: var(--ink); color: var(--white); }
.filter-count {
  display: inline-block; margin-left: .35rem;
  background: rgba(255,255,255,.2); border-radius: 2rem;
  padding: .05rem .45rem; font-size: .7rem;
}
.filter-btn:not(.active) .filter-count { background: var(--cream); color: var(--muted); }

/* ── Grid layanan ────────────────────────────────────────── */
.services-section { padding: 3.5rem 0 5rem; }
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
}

/* ── Service card ────────────────────────────────────────── */
.service-card {
  background: var(--white);
  border: 1px solid var(--cream);
  border-radius: 14px;
  overflow: hidden;
  display: flex; flex-direction: column;
  transition: transform .22s cubic-bezier(.2,.8,.2,1), box-shadow .22s;
  position: relative;
}
.service-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.1); }
.service-card[data-hidden="true"] { display: none; }

/* Card top accent bar */
.card-accent-bar { height: 4px; }

/* Card thumbnail area */
.card-thumb {
  height: 160px;
  display: flex; align-items: center; justify-content: center;
  position: relative; overflow: hidden;
  background: var(--cream);
}
.card-thumb-icon {
  font-size: 3.5rem; line-height: 1;
  filter: drop-shadow(0 4px 12px rgba(0,0,0,.08));
  transition: transform .3s;
}
.service-card:hover .card-thumb-icon { transform: scale(1.12) rotate(-3deg); }
.card-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* Delivery badge */
.card-badge {
  position: absolute; top: .85rem; right: .85rem;
  background: var(--white); border-radius: 2rem;
  padding: .2rem .65rem;
  font-family: var(--ff-head); font-size: .68rem; font-weight: 700;
  color: var(--ink); letter-spacing: .04em;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
}

/* Card body */
.card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
.card-category {
  font-family: var(--ff-head); font-size: .68rem; font-weight: 700;
  letter-spacing: .1em; text-transform: uppercase;
  margin-bottom: .6rem;
}
.card-title {
  font-family: var(--ff-head); font-weight: 700;
  font-size: 1.1rem; line-height: 1.25;
  margin-bottom: .5rem;
}
.card-desc {
  font-size: .875rem; color: var(--muted);
  line-height: 1.65; margin-bottom: 1.25rem;
  flex: 1;
}

/* Feature list */
.card-features { list-style: none; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: .35rem; }
.card-features li {
  display: flex; align-items: flex-start; gap: .5rem;
  font-size: .8rem; color: var(--muted);
}
.card-features li::before { content: '✓'; color: var(--accent); font-weight: 700; flex-shrink: 0; margin-top: .05rem; }

/* Card footer */
.card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 1.1rem; border-top: 1px solid var(--cream); }
.card-price { }
.price-label { font-size: .7rem; color: var(--muted); margin-bottom: .1rem; }
.price-value { font-family: var(--ff-head); font-weight: 800; font-size: 1.05rem; color: var(--accent); }
.card-cta {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .55rem 1.1rem;
  border-radius: var(--radius);
  background: var(--ink); color: var(--white);
  font-family: var(--ff-head); font-weight: 700; font-size: .78rem;
  letter-spacing: .04em;
  transition: background .18s;
}
.card-cta:hover { background: var(--accent); }
.card-cta-arrow { font-size: .9rem; transition: transform .18s; }
.service-card:hover .card-cta-arrow { transform: translateX(3px); }

/* Popular badge */
.popular-badge {
  position: absolute; top: .85rem; left: .85rem;
  background: var(--accent); color: var(--white);
  font-family: var(--ff-head); font-size: .65rem; font-weight: 700;
  letter-spacing: .08em; text-transform: uppercase;
  padding: .2rem .65rem; border-radius: 2rem;
}

/* ── Empty state ─────────────────────────────────────────── */
.empty-state {
  grid-column: 1 / -1;
  text-align: center; padding: 5rem 2rem;
  color: var(--muted);
}
.empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
.empty-state p { font-size: .9rem; }

/* ── Konsultasi banner ───────────────────────────────────── */
.consult-banner {
  background: var(--ink); color: var(--white);
  border-radius: 16px;
  padding: 3rem 2.5rem;
  display: grid; grid-template-columns: 1fr auto;
  align-items: center; gap: 2rem;
  margin: 0 0 5rem;
  position: relative; overflow: hidden;
}
.consult-banner::after {
  content: '◈';
  position: absolute; right: 2rem; top: 50%;
  transform: translateY(-50%);
  font-size: 8rem; color: rgba(255,255,255,.03);
  pointer-events: none;
}
.consult-banner h2 { font-family: var(--ff-head); font-size: 1.5rem; font-weight: 800; margin-bottom: .5rem; }
.consult-banner p { color: #888; font-size: .9rem; max-width: 50ch; }
.consult-actions { display: flex; gap: .75rem; flex-shrink: 0; flex-wrap: wrap; }
.btn-white { background: var(--white); color: var(--ink); }
.btn-white:hover { background: var(--cream); }
.btn-ghost { border: 1.5px solid #333; color: var(--white); }
.btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

/* ── Footer ─────────────────────────────────────────────── */
.footer { background: var(--ink); color: var(--white); padding: 3rem 0 2rem; }
.footer-inner { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.footer-logo { font-family: var(--ff-head); font-weight: 800; font-size: 1.1rem; }
.footer-logo span { color: var(--accent); }
.footer-links { display: flex; gap: 1.5rem; list-style: none; }
.footer-links a { font-size: .82rem; color: #666; transition: color .15s; }
.footer-links a:hover { color: var(--white); }
.footer-copy { font-size: .78rem; color: #444; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
  .nav-links, .nav-cta { display: none; }
  .services-grid { grid-template-columns: 1fr; }
  .consult-banner { grid-template-columns: 1fr; }
  .hero-meta { gap: 1.5rem; }
}
@media (max-width: 480px) {
  .page-hero { padding: 3.5rem 0 3rem; }
  .hero-meta { flex-wrap: wrap; gap: 1rem; }
}
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-inner">
    <a href="<?= APP_URL ?>/" class="nav-logo">creative<span>.</span></a>
    <ul class="nav-links">
      <li><a href="<?= APP_URL ?>/services" class="active">Layanan</a></li>
      <li><a href="<?= APP_URL ?>/portfolio">Portfolio</a></li>
      <li><a href="<?= APP_URL ?>/about">Tentang</a></li>
      <li><a href="<?= APP_URL ?>/contact">Kontak</a></li>
    </ul>
    <div class="nav-cta">
      <?php if (Session::isLoggedIn()): ?>
        <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline">Dashboard</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/login" class="btn btn-outline">Masuk</a>
        <a href="<?= APP_URL ?>/register" class="btn btn-primary">Mulai Proyek</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span>Layanan</span>
    </div>
    <h1>Layanan <em>Desain</em><br>yang Kami Tawarkan</h1>
    <p>Dari identitas visual, antarmuka digital, hingga konten kreatif — semua dikerjakan oleh tim berpengalaman dengan standar profesional.</p>
    <div class="hero-meta">
      <div class="hero-meta-item">
        <div class="hero-meta-num"><?= count($services) ?>+</div>
        <div class="hero-meta-label">Paket layanan tersedia</div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num"><?= count($categories) ?></div>
        <div class="hero-meta-label">Kategori desain</div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num">150+</div>
        <div class="hero-meta-label">Proyek selesai</div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num">98%</div>
        <div class="hero-meta-label">Klien puas</div>
      </div>
    </div>
  </div>
</section>

<!-- FILTER TABS -->
<div class="filter-bar">
  <div class="filter-inner">
    <button class="filter-btn active" data-filter="all" onclick="filterServices(this, 'all')">
      Semua <span class="filter-count"><?= count($services) ?></span>
    </button>
    <?php foreach ($categories as $cat): ?>
      <?php
        $count = count(array_filter($services, fn($s) => $s['category_id'] == $cat['id']));
        if ($count === 0) continue;
      ?>
      <button class="filter-btn" data-filter="<?= $cat['id'] ?>" onclick="filterServices(this, '<?= $cat['id'] ?>')">
        <?= e($cat['name']) ?> <span class="filter-count"><?= $count ?></span>
      </button>
    <?php endforeach; ?>
  </div>
</div>

<!-- SERVICES GRID -->
<div class="main-wrap">
  <section class="services-section">

    <?php
    // Warna aksen per kategori
    $catColors = [
      'branding' => ['#C8412B', '#FDECEA', '✦'],
      'uiux'     => ['#2B6CC8', '#EBF2FD', '◈'],
      'socmed'   => ['#15803D', '#DCFCE7', '◉'],
      'motion'   => ['#854D0E', '#FEF9C3', '◎'],
      'print'    => ['#5B21B6', '#F5F3FF', '◆'],
    ];
    $defaultColors = ['#7A7570', '#F3F4F6', '★'];

    // Layanan "populer" — bisa dari DB flag atau hardcode
    $popularIds = [1, 3]; // ID layanan yang ditandai populer
    ?>

    <div class="services-grid" id="services-grid">

      <?php if (empty($services)): ?>
        <!-- Placeholder jika DB kosong -->
        <?php
        $placeholders = [
          ['Brand Identity Starter', 'Branding & Identity', 1, 'Mulai dari logo, color palette, hingga panduan penggunaan merek yang konsisten.', 1500000, 'fixed', 7, ['Logo 3 konsep', 'Revisi 2x', 'Color palette', 'File PNG/SVG/PDF'], 'branding', false],
          ['Full Brand System',      'Branding & Identity', 1, 'Paket branding lengkap: logo, brand guideline, stationery, dan mockup presentasi.', 4500000, 'starting_from', 14, ['Semua fitur Starter', 'Brand Guideline PDF', 'Stationery design', 'Revisi tak terbatas'], 'branding', true],
          ['Landing Page Design',    'UI/UX Design',        2, 'Desain UI landing page modern 1–5 section dengan deliverable file Figma siap handoff.', 1200000, 'fixed', 5, ['Hingga 5 section', 'File Figma', 'Responsive design', 'Revisi 2x'], 'uiux', true],
          ['Mobile App UI',          'UI/UX Design',        2, 'Desain antarmuka aplikasi mobile hingga 15 screen, lengkap dengan prototype interaktif.', 3500000, 'starting_from', 14, ['Hingga 15 screen', 'Prototype Figma', 'Design system', 'Handoff notes'], 'uiux', false],
          ['Social Media Pack',      'Social Media Design', 3, '10 template feed + 5 template story yang editable di Canva atau Figma.', 750000, 'fixed', 3, ['10 template feed', '5 template story', 'File Canva/Figma', 'Panduan posting'], 'socmed', false],
          ['Motion Graphic Pendek',  'Motion & Video',      4, 'Video animasi pendek 15–30 detik untuk konten iklan, intro, atau promosi produk.', 2000000, 'starting_from', 7, ['Durasi 15–30 detik', 'Format MP4/GIF', 'Revisi 2x', 'File source AE'], 'motion', false],
        ];
        foreach ($placeholders as [$name, $catName, $catId, $desc, $price, $ptype, $days, $features, $catSlug, $popular]):
          [$accentColor, $bgColor, $icon] = $catColors[$catSlug] ?? $defaultColors;
          $slug = strtolower(str_replace(' ', '-', $name));
        ?>
        <div class="service-card" data-category="<?= $catId ?>">
          <div class="card-accent-bar" style="background:<?= $accentColor ?>"></div>
          <div class="card-thumb" style="background:<?= $bgColor ?>">
            <div class="card-thumb-icon"><?= $icon ?></div>
            <?php if ($days): ?>
              <span class="card-badge">⏱ <?= $days ?> hari</span>
            <?php endif; ?>
            <?php if ($popular): ?>
              <span class="popular-badge">★ Populer</span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <div class="card-category" style="color:<?= $accentColor ?>"><?= $catName ?></div>
            <h3 class="card-title"><?= $name ?></h3>
            <p class="card-desc"><?= $desc ?></p>
            <ul class="card-features">
              <?php foreach (array_slice($features, 0, 3) as $f): ?>
                <li><?= $f ?></li>
              <?php endforeach; ?>
              <?php if (count($features) > 3): ?>
                <li style="color:var(--accent);font-style:italic">+<?= count($features) - 3 ?> fitur lainnya...</li>
              <?php endif; ?>
            </ul>
            <div class="card-footer">
              <div class="card-price">
                <div class="price-label"><?= $ptype === 'starting_from' ? 'Mulai dari' : 'Harga' ?></div>
                <div class="price-value"><?= formatRupiah($price) ?></div>
              </div>
              <a href="<?= APP_URL ?>/services/<?= $slug ?>" class="card-cta">
                Detail <span class="card-cta-arrow">→</span>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

      <?php else: ?>
        <!-- Data dari database -->
        <?php foreach ($services as $svc):
          $catSlug = $svc['category_slug'] ?? 'default';
          [$accentColor, $bgColor, $icon] = $catColors[$catSlug] ?? $defaultColors;
          $isPopular = in_array($svc['id'], $popularIds);
        ?>
        <div class="service-card" data-category="<?= $svc['category_id'] ?>">
          <div class="card-accent-bar" style="background:<?= $accentColor ?>"></div>
          <div class="card-thumb" style="background:<?= $bgColor ?>">
            <?php if ($svc['thumbnail']): ?>
              <img src="<?= APP_URL ?>/uploads/<?= e($svc['thumbnail']) ?>" alt="<?= e($svc['name']) ?>">
            <?php else: ?>
              <div class="card-thumb-icon"><?= $icon ?></div>
            <?php endif; ?>
            <?php if ($svc['delivery_days']): ?>
              <span class="card-badge">⏱ <?= $svc['delivery_days'] ?> hari</span>
            <?php endif; ?>
            <?php if ($isPopular): ?>
              <span class="popular-badge">★ Populer</span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <div class="card-category" style="color:<?= $accentColor ?>"><?= e($svc['category_name']) ?></div>
            <h3 class="card-title"><?= e($svc['name']) ?></h3>
            <p class="card-desc"><?= e($svc['short_desc']) ?></p>
            <?php if (!empty($svc['features'])): ?>
            <ul class="card-features">
              <?php foreach (array_slice($svc['features'], 0, 3) as $f): ?>
                <li><?= e($f) ?></li>
              <?php endforeach; ?>
              <?php if (count($svc['features']) > 3): ?>
                <li style="color:var(--accent);font-style:italic">+<?= count($svc['features']) - 3 ?> fitur lainnya...</li>
              <?php endif; ?>
            </ul>
            <?php endif; ?>
            <div class="card-footer">
              <div class="card-price">
                <div class="price-label"><?= $svc['price_type'] === 'starting_from' ? 'Mulai dari' : ($svc['price_type'] === 'custom' ? 'Harga' : 'Harga') ?></div>
                <div class="price-value">
                  <?= $svc['price_type'] === 'custom' ? 'Hubungi Kami' : formatRupiah((float)$svc['price']) ?>
                </div>
              </div>
              <a href="<?= APP_URL ?>/services/<?= e($svc['slug']) ?>" class="card-cta">
                Detail <span class="card-cta-arrow">→</span>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Empty state setelah filter -->
      <div class="empty-state" id="empty-state" style="display:none;">
        <div class="icon">◈</div>
        <p>Tidak ada layanan di kategori ini.<br>Coba pilih kategori lain.</p>
      </div>

    </div><!-- /.services-grid -->
  </section>

  <!-- KONSULTASI BANNER -->
  <div class="consult-banner">
    <div>
      <h2>Tidak Yakin Paket Mana yang Tepat?</h2>
      <p>Ceritakan kebutuhan Anda, kami akan bantu rekomendasikan layanan yang paling sesuai — gratis, tanpa komitmen.</p>
    </div>
    <div class="consult-actions">
      <a href="<?= APP_URL ?>/contact" class="btn btn-white">💬 Konsultasi Gratis</a>
      <a href="<?= APP_URL ?>/register" class="btn btn-ghost">Buat Akun</a>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-logo">creative<span>.</span></div>
    <ul class="footer-links">
      <li><a href="<?= APP_URL ?>/services">Layanan</a></li>
      <li><a href="<?= APP_URL ?>/portfolio">Portfolio</a></li>
      <li><a href="<?= APP_URL ?>/contact">Kontak</a></li>
    </ul>
    <p class="footer-copy">© <?= date('Y') ?> Creative Studio</p>
  </div>
</footer>

<script>
// ── Filter layanan ────────────────────────────────────────
function filterServices(btn, filterId) {
  // Update tombol aktif
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const cards  = document.querySelectorAll('.service-card');
  const empty  = document.getElementById('empty-state');
  let visible  = 0;

  cards.forEach(card => {
    const match = filterId === 'all' || card.dataset.category === filterId;
    card.dataset.hidden = match ? 'false' : 'true';
    card.style.display  = match ? '' : 'none';
    if (match) visible++;
  });

  empty.style.display = visible === 0 ? 'block' : 'none';
}

// ── Animasi masuk card ────────────────────────────────────
const observer = new IntersectionObserver(entries => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }, i * 60);
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.08 });

document.querySelectorAll('.service-card').forEach(card => {
  card.style.opacity = '0';
  card.style.transform = 'translateY(18px)';
  card.style.transition = 'opacity .4s ease, transform .4s ease';
  observer.observe(card);
});
</script>

</body>
</html>
