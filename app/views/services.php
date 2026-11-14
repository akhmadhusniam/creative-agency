<?php $publicStats = site_stats(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layanan — Creative Studio</title>
<meta name="description" content="Paket desain branding, UI/UX, social media, motion, dan print dengan lingkup jelas. Lihat kecocokan paket dan kualifikasi klien sebelum mulai.">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
/* ── Token aliases — nama variabel di file ini beda dari base-styles.php,
   jadi di-alias di sini supaya nilainya tetap satu sumber (bukan didefinisikan ulang) ── */
:root { --ff-head: var(--fh); --ff-body: var(--fb); --radius: 6px; }

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
.breadcrumb a { font-size: .8rem; color: #8FA697; transition: color .15s; }
.breadcrumb a:hover { color: var(--accent); }
.breadcrumb-sep { color: #3E5747; font-size: .75rem; }
.breadcrumb span { font-size: .8rem; color: #C7D4CB; }
.page-hero h1 {
  font-family: var(--ff-head); font-weight: 800;
  font-size: clamp(2rem, 5vw, 3.5rem);
  line-height: 1.1; letter-spacing: -.03em;
  color: var(--white);
  margin-bottom: 1rem;
}
.page-hero h1 em { font-style: normal; color: var(--accent); }
.page-hero p { color: #C7D4CB; font-size: 1rem; max-width: 52ch; line-height: 1.75; }
.hero-meta { display: flex; gap: 2.5rem; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #26402F; }
.hero-meta-item { }
.hero-meta-num { font-family: var(--ff-head); font-size: 1.6rem; font-weight: 800; color: var(--white); }
.hero-meta-label { font-size: .75rem; color: #A9B8AE; margin-top: .15rem; }

/* ── Main layout ─────────────────────────────────────────── */
.main-wrap { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); }

/* ── Filter tabs ─────────────────────────────────────────── */
.filter-bar {
  position: sticky; top: var(--nav-h); z-index: 90;
  background: var(--paper);
  border-bottom: 1px solid var(--cream);
  padding: .85rem 0;
}
.filter-inner { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); display: flex; align-items: center; gap: .5rem; overflow-x: auto; scrollbar-width: none; scroll-snap-type:x proximity; -webkit-overflow-scrolling:touch; }
.filter-inner::-webkit-scrollbar { display: none; }
.filter-btn {
  flex-shrink: 0;
  min-height: 40px;
  scroll-snap-align:start;
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
.card-thumb img { width: 100%; height: 100%; object-fit: cover; }
.card-thumb-ill { background: linear-gradient(135deg, #F7FAF8 0%, #E4E9E5 100%); }
.card-thumb-ill .card-ill { object-fit: contain; padding: .55rem .75rem; transition: transform .3s; }
.service-card:hover .card-ill { transform: scale(1.04); }

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
  line-height: 1.65; margin-bottom: 1rem;
  flex: 1;
}

/* Konteks kecocokan paket */
.card-fit {
  display: flex; align-items: baseline; gap: .4rem; flex-wrap: wrap;
  background: var(--cream-2); border: 1px solid var(--line);
  border-radius: 6px; padding: .45rem .65rem;
  margin-bottom: 1rem;
  font-size: .76rem; line-height: 1.45; color: var(--ink);
}
.card-fit span {
  font-family: var(--fm); font-size: .58rem; font-weight: 700;
  letter-spacing: .06em; text-transform: uppercase; color: var(--muted);
  flex-shrink: 0;
}

/* Feature list */
.card-features { list-style: none; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: .35rem; }
.card-features li {
  display: flex; align-items: flex-start; gap: .5rem;
  font-size: .8rem; color: var(--muted);
}
.card-features li::before { content: '✓'; color: #2F5D14; font-weight: 700; flex-shrink: 0; margin-top: .05rem; }

/* Card footer */
.card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 1.1rem; border-top: 1px solid var(--cream); }
.card-price { }
.price-label { font-size: .7rem; color: var(--muted); margin-bottom: .1rem; }
.price-value { font-family: var(--ff-head); font-weight: 800; font-size: 1.05rem; color: var(--ink); }
.card-cta {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .55rem 1.1rem;
  border-radius: 0; border: 2px solid var(--ink);
  background: var(--ink); color: var(--white);
  font-family: var(--fm); font-weight: 700; font-size: .75rem; text-transform: uppercase;
  letter-spacing: .02em;
  transition: background .18s, color .18s;
}
.card-cta:hover { background: var(--accent); color: var(--ink); }
.card-cta-arrow { font-size: .9rem; transition: transform .18s; }
.service-card:hover .card-cta-arrow { transform: translateX(3px); }

/* Popular badge */
.popular-badge {
  position: absolute; top: .85rem; left: .85rem;
  background: var(--accent); color: var(--ink); border: 2px solid var(--ink);
  font-family: var(--fm); font-size: .65rem; font-weight: 700;
  letter-spacing: .04em; text-transform: uppercase;
  padding: .2rem .65rem; border-radius: 0;
}

/* ── Empty state ─────────────────────────────────────────── */
.empty-state {
  grid-column: 1 / -1;
  text-align: center; padding: 5rem 2rem;
  color: var(--muted);
}
.empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
.empty-state p { font-size: .9rem; }

/* ── Kualifikasi klien ───────────────────────────────────── */
.qual-section { padding: 0 0 4rem; }
.qual-head { max-width: 760px; margin-bottom: 1.75rem; }
.qual-title {
  font-family: var(--fh); font-weight: 800;
  font-size: clamp(1.6rem, 3vw, 2.25rem);
  line-height: 1.1; letter-spacing: -.02em;
  margin-bottom: .75rem;
}
.qual-sub { color: var(--muted); font-size: .92rem; line-height: 1.8; max-width: 62ch; }
.qual-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
.qual-card {
  background: var(--white); border: 1px solid var(--cream);
  border-radius: 12px; padding: 1.35rem;
}
.qual-card.bad { background: var(--cream-2); border-style: dashed; }
.qual-card h3 {
  font-family: var(--fh); font-size: .92rem; font-weight: 800;
  margin-bottom: .8rem;
}
.qual-card ul { list-style: none; display: flex; flex-direction: column; gap: .55rem; }
.qual-card li {
  display: flex; align-items: flex-start; gap: .5rem;
  font-size: .8rem; color: var(--muted); line-height: 1.55;
}
.qual-card li::before {
  content: '✓'; color: #2F5D14; font-weight: 800;
  flex-shrink: 0; margin-top: .02rem;
}
.qual-card.bad li::before { content: '×'; color: #B91C1C; }
.qual-note {
  margin-top: 1.25rem; font-size: .82rem; color: var(--muted); line-height: 1.65;
}
.qual-note a {
  color: var(--ink); font-weight: 700;
  text-decoration: underline; text-underline-offset: 3px;
}
.qual-note a:hover { color: #2F5D14; }

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
.consult-banner p { color: #C7D4CB; font-size: .9rem; max-width: 50ch; }
.consult-actions { display: flex; gap: .75rem; flex-shrink: 0; flex-wrap: wrap; }
.btn-white { background: var(--white); color: var(--ink); }
.btn-white:hover { background: var(--cream); }
.btn-ghost { border: 1.5px solid #3E5747; color: var(--white); }
.btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 900px) {
  .qual-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .services-grid { grid-template-columns: 1fr; }
  .consult-banner { grid-template-columns: 1fr; }
  .hero-meta { gap: 1.5rem; }
  .filter-bar::after {
    content: '→';
    position: absolute; right: .6rem; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; justify-content: center;
    width: 2rem; height: 2rem; border-radius: 50%;
    background: var(--paper); color: var(--ink);
    box-shadow: -12px 0 16px var(--paper);
    font-family: var(--fm); font-size: .85rem; pointer-events: none;
  }
  .filter-inner { padding-right: 3rem; }
  .filter-btn { min-height: 44px; }
}
@media (max-width: 480px) {
  .page-hero { padding: 3rem 0 2.5rem; }
  .hero-meta { flex-wrap: wrap; gap: 1rem; margin-top: 1.75rem; padding-top: 1.5rem; }
  .hero-meta-num { font-size: 1.35rem; }
}
</style>
</head>
<body>

<!-- NAV -->
<?php $activeNav = 'services'; require VIEW_PATH . '/partials/nav.php'; ?>

<!-- HERO -->
<section class="page-hero" id="main">
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
        <div class="hero-meta-num"><?= count($services) ?></div>
        <div class="hero-meta-label">Paket layanan tersedia</div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num"><?= count($categories) ?></div>
        <div class="hero-meta-label">Kategori desain</div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num"><?= (int) $publicStats['projects'] ?></div>
        <div class="hero-meta-label"><?= e(site_project_label()) ?></div>
      </div>
      <div class="hero-meta-item">
        <div class="hero-meta-num">Rp0</div>
        <div class="hero-meta-label">Konsultasi awal</div>
      </div>
    </div>
  </div>
</section>

<!-- FILTER TABS -->
<div class="filter-bar">
  <div class="filter-inner">
    <button class="filter-btn active" data-filter="all" aria-pressed="true" onclick="filterServices(this, 'all')">
      Semua <span class="filter-count"><?= count($services) ?></span>
    </button>
    <?php foreach ($categories as $cat): ?>
      <?php
        $count = count(array_filter($services, fn($s) => $s['category_id'] == $cat['id']));
        if ($count === 0) continue;
      ?>
      <button class="filter-btn" data-filter="<?= $cat['id'] ?>" aria-pressed="false" onclick="filterServices(this, '<?= $cat['id'] ?>')">
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

    // Ilustrasi isometrik per kategori — dipakai saat belum ada thumbnail upload.
    $catIllustrations = [
      'branding' => 'service-branding.svg',
      'uiux'     => 'service-uiux.svg',
      'socmed'   => 'service-social.svg',
      'motion'   => 'service-motion.svg',
      'print'    => 'service-print.svg',
    ];
    $defaultIllustration = 'service-branding.svg';

    // Layanan "populer" — bisa dari DB flag atau hardcode
    $popularIds = [1, 3]; // ID layanan yang ditandai populer
    ?>

    <div class="services-grid" id="services-grid">

      <?php if (empty($services)): ?>
        <!-- Placeholder jika DB kosong -->
        <?php
        $placeholders = [
          ['Brand Identity Starter', 'Branding & Identity', 1, 'Mulai dari logo, color palette, hingga panduan penggunaan merek yang konsisten.', 1500000, 'fixed', 7, ['Logo 3 konsep', 'Revisi 2x', 'Color palette', 'File PNG/SVG/PDF'], 'branding', false],
          ['Full Brand System',      'Branding & Identity', 1, 'Paket branding lengkap: logo, brand guideline, stationery, dan mockup presentasi.', 4500000, 'starting_from', 14, ['Semua fitur Starter', 'Brand Guideline PDF', 'Stationery design', 'Revisi sesuai paket'], 'branding', true],
          ['Landing Page Design',    'UI/UX Design',        2, 'Desain UI landing page modern 1–5 section dengan deliverable file Figma siap handoff.', 1200000, 'fixed', 5, ['Hingga 5 section', 'File Figma', 'Responsive design', 'Revisi 2x'], 'uiux', true],
          ['Mobile App UI',          'UI/UX Design',        2, 'Desain antarmuka aplikasi mobile hingga 15 screen, lengkap dengan prototype interaktif.', 3500000, 'starting_from', 14, ['Hingga 15 screen', 'Prototype Figma', 'Design system', 'Handoff notes'], 'uiux', false],
          ['Social Media Pack',      'Social Media Design', 3, '10 template feed + 5 template story yang editable di Canva atau Figma.', 750000, 'fixed', 3, ['10 template feed', '5 template story', 'File Canva/Figma', 'Panduan posting'], 'socmed', false],
          ['Motion Graphic Pendek',  'Motion & Video',      4, 'Video animasi pendek 15–30 detik untuk konten iklan, intro, atau promosi produk.', 2000000, 'starting_from', 7, ['Durasi 15–30 detik', 'Format MP4/GIF', 'Revisi 2x', 'File source AE'], 'motion', false],
        ];
        foreach ($placeholders as [$name, $catName, $catId, $desc, $price, $ptype, $days, $features, $catSlug, $popular]):
          [$accentColor, $bgColor] = $catColors[$catSlug] ?? $defaultColors;
          $slug = strtolower(str_replace(' ', '-', $name));
          $illustration = $catIllustrations[$catSlug] ?? $defaultIllustration;
          $profile = site_service_profile(['slug' => $slug, 'category_slug' => $catSlug]);
        ?>
        <div class="service-card" data-category="<?= $catId ?>">
          <div class="card-accent-bar" style="background:<?= $accentColor ?>"></div>
          <div class="card-thumb card-thumb-ill">
            <img class="card-ill" src="<?= APP_URL ?>/assets/illustrations/<?= e($illustration) ?>" alt="" width="320" height="180" loading="lazy" decoding="async">
            <?php if ($days): ?>
              <span class="card-badge">⏱ <?= $days ?> hari</span>
            <?php endif; ?>
            <?php if ($popular): ?>
              <span class="popular-badge">★ Populer</span>
            <?php endif; ?>
          </div>
          <div class="card-body">
            <div class="card-category" style="color:<?= $accentColor ?>"><?= $catName ?></div>
            <h2 class="card-title"><?= $name ?></h2>
            <p class="card-desc"><?= $desc ?></p>
            <?php if (!empty($profile['audience'])): ?>
              <div class="card-fit"><span>Cocok untuk</span> <?= e($profile['audience']) ?></div>
            <?php endif; ?>
            <ul class="card-features">
              <?php foreach (array_slice($features, 0, 3) as $f): ?>
                <li><?= $f ?></li>
              <?php endforeach; ?>
              <?php if (count($features) > 3): ?>
                <li style="color:var(--muted);font-style:italic;font-weight:600">+<?= count($features) - 3 ?> fitur lainnya...</li>
              <?php endif; ?>
            </ul>
            <div class="card-footer">
              <div class="card-price">
                <div class="price-label"><?= $ptype === 'starting_from' ? 'Mulai dari' : 'Harga' ?></div>
                <div class="price-value"><?= formatRupiah($price) ?></div>
              </div>
              <a href="<?= APP_URL ?>/services/<?= $slug ?>" class="card-cta" data-cta="service-card">
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
          [$accentColor, $bgColor] = $catColors[$catSlug] ?? $defaultColors;
          $illustration = $catIllustrations[$catSlug] ?? $defaultIllustration;
          $isPopular = in_array($svc['id'], $popularIds);
          $profile = site_service_profile($svc);
          $cardFeatures = !empty($svc['features']) ? $svc['features'] : $profile['included'];
        ?>
        <div class="service-card" data-category="<?= $svc['category_id'] ?>">
          <div class="card-accent-bar" style="background:<?= $accentColor ?>"></div>
          <div class="card-thumb<?= $svc['thumbnail'] ? '' : ' card-thumb-ill' ?>"<?= $svc['thumbnail'] ? ' style="background:' . e($bgColor) . '"' : '' ?>>
            <?php if ($svc['thumbnail']): ?>
              <img src="<?= APP_URL ?>/uploads/<?= e($svc['thumbnail']) ?>" alt="<?= e($svc['name']) ?>" loading="lazy">
            <?php else: ?>
              <img class="card-ill" src="<?= APP_URL ?>/assets/illustrations/<?= e($illustration) ?>" alt="" width="320" height="180" loading="lazy" decoding="async">
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
            <h2 class="card-title"><?= e($svc['name']) ?></h2>
            <p class="card-desc"><?= e($svc['short_desc']) ?></p>
            <?php if (!empty($profile['audience'])): ?>
              <div class="card-fit"><span>Cocok untuk</span> <?= e($profile['audience']) ?></div>
            <?php endif; ?>
            <?php if (!empty($cardFeatures)): ?>
            <ul class="card-features">
              <?php foreach (array_slice($cardFeatures, 0, 3) as $f): ?>
                <li><?= e($f) ?></li>
              <?php endforeach; ?>
              <?php if (count($cardFeatures) > 3): ?>
                <li style="color:var(--muted);font-style:italic;font-weight:600">+<?= count($cardFeatures) - 3 ?> fitur lainnya...</li>
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
              <a href="<?= APP_URL ?>/services/<?= e($svc['slug']) ?>" class="card-cta" data-cta="service-card">
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

  <!-- KUALIFIKASI KLIEN -->
  <section class="qual-section" id="kualifikasi-klien">
    <div class="qual-head reveal">
      <div class="eyebrow">Kualifikasi Klien</div>
      <h2 class="qual-title">Kami Ingin Memastikan<br>Proyek Ini Tepat untuk Anda</h2>
      <p class="qual-sub">Kami tidak mengambil semua proyek. Kriteria ini membantu Anda menilai kecocokan sebelum mengirim brief — dan membantu kami memberi hasil yang realistis.</p>
    </div>
    <div class="qual-grid reveal" style="transition-delay:.08s">
      <div class="qual-card">
        <h3>Cocok jika Anda</h3>
        <ul>
          <li>Sudah punya bisnis/produk dan target audiens yang jelas.</li>
          <li>Bisa mengambil keputusan atau mewakili decision-maker.</li>
          <li>Siap menyediakan brief, aset, dan feedback terstruktur.</li>
          <li>Siap pembayaran penuh di awal dan lingkup final sebelum mulai.</li>
        </ul>
      </div>
      <div class="qual-card">
        <h3>Yang kami butuhkan</h3>
        <ul>
          <li>Tujuan proyek dan ukuran keberhasilan.</li>
          <li>Referensi visual: yang disukai dan tidak disukai.</li>
          <li>Aset yang relevan: logo, foto, copy, atau data.</li>
          <li>Satu PIC dengan waktu review 2–3 hari kerja per putaran.</li>
        </ul>
      </div>
      <div class="qual-card bad">
        <h3>Belum cocok jika</h3>
        <ul>
          <li>Butuh hasil instan di bawah estimasi timeline paket.</li>
          <li>Belum punya arah bisnis atau brief yang bisa dijadikan dasar.</li>
          <li>Tidak bisa memberi feedback atau approval terpusat.</li>
          <li>Mencari harga termurah tanpa kesepakatan lingkup.</li>
        </ul>
      </div>
    </div>
    <p class="qual-note reveal">Belum yakin? <a href="<?= e(site_start_project_url()) ?>" data-cta="services-qualification-start-project">Diskusikan kecocokan proyek Anda →</a> Konsultasi awal gratis, tanpa komitmen.</p>
  </section>

  <!-- KONSULTASI BANNER -->
  <div class="consult-banner">
    <div>
      <h2>Tidak Yakin Paket Mana yang Tepat?</h2>
      <p>Ceritakan kebutuhan Anda, kami akan bantu rekomendasikan layanan yang paling sesuai — gratis, tanpa komitmen. Pembayaran penuh di awal via Midtrans; pengerjaan dimulai setelah pembayaran terkonfirmasi. Revisi dan garansi mengikuti ketentuan paket.</p>
    </div>
    <div class="consult-actions">
      <a href="<?= e(site_start_project_url()) ?>" class="btn btn-white" data-cta="services-start-project">Diskusikan Kebutuhan →</a>
      <a href="<?= e(site_wa_url('Halo, saya butuh rekomendasi layanan yang cocok untuk kebutuhan saya.')) ?>" class="btn btn-ghost" target="_blank" rel="noopener" data-cta="services-consult-wa">atau chat WhatsApp</a>
    </div>
  </div>
</div>

<!-- FOOTER -->
<?php require VIEW_PATH . '/partials/footer.php'; ?>

<script>
// ── Filter layanan ────────────────────────────────────────
function filterServices(btn, filterId) {
  // Update tombol aktif
  document.querySelectorAll('.filter-btn').forEach(b => {
    b.classList.remove('active');
    b.setAttribute('aria-pressed', 'false');
  });
  btn.classList.add('active');
  btn.setAttribute('aria-pressed', 'true');

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
if (typeof IntersectionObserver !== 'undefined') {
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
}

// ── Sinkronkan jumlah pada tombol filter dengan kartu yang tampil ──
document.addEventListener('DOMContentLoaded', function () {
  var cards = document.querySelectorAll('.service-card');
  document.querySelectorAll('.filter-btn').forEach(function (btn) {
    var f = btn.dataset.filter;
    var n = f === 'all' ? cards.length : Array.prototype.filter.call(cards, function (c) { return c.dataset.category === f; }).length;
    var badge = btn.querySelector('.filter-count');
    if (badge) badge.textContent = n;
  });
});
</script>

<?php require VIEW_PATH . '/partials/mobile-cta.php'; ?>
<?php require VIEW_PATH . '/partials/nav-script.php'; ?>
</body>
</html>
