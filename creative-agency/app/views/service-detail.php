<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($service['name']) ?> — Creative Studio</title>
<meta name="description" content="<?= e($service['short_desc']) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
  --ink:     #0D0D0D;
  --paper:   #F7F5F0;
  --cream:   #EDEBE4;
  --accent:  #C8412B;
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

/* ── Nav ─────────────────────────────────────────────────── */
.nav { position: sticky; top: 0; z-index: 100; height: var(--nav-h); background: var(--paper); border-bottom: 1px solid var(--cream); display: flex; align-items: center; }
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

/* ── Container ───────────────────────────────────────────── */
.container { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); }

/* ── Breadcrumb ──────────────────────────────────────────── */
.breadcrumb-bar { padding: 1.25rem 0; border-bottom: 1px solid var(--cream); }
.breadcrumb { display: flex; align-items: center; gap: .5rem; }
.breadcrumb a { font-size: .8rem; color: var(--muted); transition: color .15s; }
.breadcrumb a:hover { color: var(--ink); }
.breadcrumb-sep { color: var(--cream); font-size: .8rem; }
.breadcrumb span { font-size: .8rem; color: var(--ink); font-weight: 500; }

/* ── Layout 2 kolom ──────────────────────────────────────── */
.detail-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 3rem;
  padding: 3rem 0 5rem;
  align-items: start;
}

/* ── LEFT — konten utama ──────────────────────────────────── */
.detail-main { min-width: 0; }

/* Hero layanan */
.service-hero {
  background: var(--ink);
  border-radius: 16px;
  padding: 3rem;
  margin-bottom: 2.5rem;
  position: relative; overflow: hidden;
}
<?php
$catColors = [
  'branding' => ['#C8412B', '✦'],
  'uiux'     => ['#2B6CC8', '◈'],
  'socmed'   => ['#15803D', '◉'],
  'motion'   => ['#854D0E', '◎'],
  'print'    => ['#5B21B6', '◆'],
];
$catSlug   = $service['category_slug'] ?? 'default';
[$accentColor, $heroIcon] = $catColors[$catSlug] ?? ['#7A7570','★'];
?>
.service-hero::before {
  content: '<?= $heroIcon ?>';
  position: absolute; right: 2rem; top: 50%; transform: translateY(-50%);
  font-size: 9rem; color: rgba(255,255,255,.04); line-height: 1;
  pointer-events: none;
}
.service-hero::after {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 80% 50%, <?= $accentColor ?>22 0%, transparent 65%);
}
.service-hero-inner { position: relative; z-index: 1; }
.service-category-tag {
  display: inline-block;
  font-family: var(--ff-head); font-size: .7rem; font-weight: 700;
  letter-spacing: .12em; text-transform: uppercase;
  color: <?= $accentColor ?>;
  background: <?= $accentColor ?>22;
  border: 1px solid <?= $accentColor ?>44;
  padding: .25rem .75rem; border-radius: 2rem;
  margin-bottom: 1rem;
}
.service-hero h1 {
  font-family: var(--ff-head); font-weight: 800;
  font-size: clamp(1.6rem, 3.5vw, 2.5rem);
  line-height: 1.15; letter-spacing: -.025em;
  color: var(--white);
  margin-bottom: .85rem;
}
.service-hero p {
  color: #888; font-size: .95rem; line-height: 1.75;
  max-width: 54ch;
}
.service-hero-meta {
  display: flex; gap: 1.5rem; margin-top: 2rem; flex-wrap: wrap;
}
.hero-meta-pill {
  display: flex; align-items: center; gap: .4rem;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 2rem; padding: .35rem .9rem;
  font-size: .8rem; color: #aaa;
}
.hero-meta-pill strong { color: var(--white); font-weight: 600; }

/* Section umum */
.detail-section { margin-bottom: 2.5rem; }
.section-label {
  font-family: var(--ff-head); font-size: .72rem; font-weight: 700;
  letter-spacing: .12em; text-transform: uppercase;
  color: var(--muted); margin-bottom: 1.1rem;
  display: flex; align-items: center; gap: .6rem;
}
.section-label::after { content: ''; flex: 1; height: 1px; background: var(--cream); }

/* Deskripsi lengkap */
.detail-desc {
  font-size: .95rem; color: var(--ink); line-height: 1.8;
}
.detail-desc p + p { margin-top: 1rem; }

/* Fitur yang didapat */
.features-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: .6rem;
}
.feature-item {
  display: flex; align-items: flex-start; gap: .65rem;
  background: var(--white); border: 1px solid var(--cream);
  border-radius: var(--radius); padding: .85rem 1rem;
}
.feature-check {
  width: 20px; height: 20px; border-radius: 50%;
  background: <?= $accentColor ?>18;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: .05rem;
  font-size: .7rem; color: <?= $accentColor ?>;
}
.feature-text { font-size: .875rem; color: var(--ink); line-height: 1.4; }

/* Proses pengerjaan */
.process-list { display: flex; flex-direction: column; gap: 0; }
.process-item {
  display: flex; gap: 1.25rem; padding: 1.25rem 0;
  border-bottom: 1px solid var(--cream);
}
.process-item:last-child { border-bottom: none; }
.process-num {
  width: 36px; height: 36px; border-radius: 50%;
  background: <?= $accentColor ?>15;
  border: 2px solid <?= $accentColor ?>30;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--ff-head); font-weight: 800;
  font-size: .8rem; color: <?= $accentColor ?>;
  flex-shrink: 0;
}
.process-content h4 { font-family: var(--ff-head); font-weight: 700; font-size: .95rem; margin-bottom: .25rem; }
.process-content p { font-size: .85rem; color: var(--muted); line-height: 1.6; }

/* FAQ */
.faq-list { display: flex; flex-direction: column; gap: .6rem; }
.faq-item {
  background: var(--white); border: 1px solid var(--cream);
  border-radius: 10px; overflow: hidden;
  transition: border-color .2s;
}
.faq-item.open { border-color: <?= $accentColor ?>44; }
.faq-q {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.1rem 1.25rem;
  cursor: pointer; user-select: none;
}
.faq-q-text {
  font-family: var(--ff-head); font-weight: 700;
  font-size: .95rem; color: var(--ink); flex: 1; padding-right: 1rem;
  line-height: 1.4;
}
.faq-icon {
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--cream); display: flex; align-items: center;
  justify-content: center; font-size: 1rem; color: var(--muted);
  flex-shrink: 0; transition: background .2s, transform .3s;
}
.faq-item.open .faq-icon { background: <?= $accentColor ?>18; color: <?= $accentColor ?>; transform: rotate(45deg); }
.faq-a {
  max-height: 0; overflow: hidden;
  transition: max-height .35s cubic-bezier(.2,.8,.2,1), padding .3s;
}
.faq-a-inner { padding: 0 1.25rem 1.1rem; font-size: .875rem; color: var(--muted); line-height: 1.75; }
.faq-item.open .faq-a { max-height: 300px; }

/* ── RIGHT — sticky sidebar ──────────────────────────────── */
.detail-sidebar { position: sticky; top: calc(var(--nav-h) + 1.5rem); }

/* Kartu order */
.order-card {
  background: var(--white); border: 1px solid var(--cream);
  border-radius: 14px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.07);
}
.order-card-header {
  background: var(--ink); padding: 1.5rem;
  position: relative; overflow: hidden;
}
.order-card-header::after {
  content: '<?= $heroIcon ?>';
  position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
  font-size: 4rem; color: rgba(255,255,255,.06);
}
.order-price-label { font-size: .72rem; color: #666; margin-bottom: .2rem; text-transform: uppercase; letter-spacing: .08em; font-family: var(--ff-head); }
.order-price-value { font-family: var(--ff-head); font-weight: 800; font-size: 2rem; color: var(--white); line-height: 1; }
.order-price-type { font-size: .8rem; color: #666; margin-top: .2rem; }
.order-card-body { padding: 1.5rem; }

/* Meta info di sidebar */
.order-meta { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 1.5rem; }
.order-meta-row {
  display: flex; align-items: center; justify-content: space-between;
  padding-bottom: .75rem; border-bottom: 1px solid var(--cream);
}
.order-meta-row:last-child { border-bottom: none; padding-bottom: 0; }
.meta-key { font-size: .8rem; color: var(--muted); display: flex; align-items: center; gap: .4rem; }
.meta-val { font-size: .875rem; font-weight: 600; color: var(--ink); }

/* CTA utama */
.order-cta-main {
  display: flex; align-items: center; justify-content: center; gap: .5rem;
  width: 100%; padding: .95rem;
  background: var(--accent); color: var(--white);
  border: none; border-radius: var(--radius);
  font-family: var(--ff-head); font-weight: 700; font-size: 1rem;
  letter-spacing: .04em; cursor: pointer;
  transition: background .2s, transform .15s;
  text-align: center;
}
.order-cta-main:hover { background: #A83422; transform: translateY(-1px); }
.order-cta-secondary {
  display: block; text-align: center;
  padding: .75rem;
  border: 1.5px solid var(--cream); border-radius: var(--radius);
  font-family: var(--ff-head); font-weight: 700; font-size: .875rem;
  color: var(--muted); margin-top: .75rem;
  transition: all .18s;
}
.order-cta-secondary:hover { border-color: var(--ink); color: var(--ink); }

/* Guarantee strip */
.guarantee-strip {
  display: flex; align-items: center; gap: .5rem;
  background: var(--paper); border-radius: var(--radius);
  padding: .75rem 1rem; margin-top: 1rem;
  font-size: .78rem; color: var(--muted);
}
.guarantee-icon { font-size: 1.1rem; }

/* Related services */
.related-section { margin-top: 1.5rem; }
.related-title { font-family: var(--ff-head); font-weight: 700; font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); margin-bottom: .85rem; }
.related-list { display: flex; flex-direction: column; gap: .5rem; }
.related-item {
  display: flex; align-items: center; justify-content: space-between;
  padding: .75rem 1rem;
  background: var(--white); border: 1px solid var(--cream);
  border-radius: var(--radius);
  font-size: .875rem; transition: all .18s;
}
.related-item:hover { border-color: var(--ink); }
.related-item-name { font-weight: 500; }
.related-item-price { font-family: var(--ff-head); font-weight: 700; font-size: .8rem; color: var(--accent); }

/* ── Layanan lain section ─────────────────────────────────── */
.other-section { padding: 4rem 0 5rem; border-top: 1px solid var(--cream); }
.other-section h2 { font-family: var(--ff-head); font-weight: 800; font-size: 1.6rem; margin-bottom: 2rem; }
.other-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
.other-card {
  background: var(--white); border: 1px solid var(--cream); border-radius: 10px;
  padding: 1.25rem; display: flex; align-items: center; gap: 1rem;
  transition: all .18s;
}
.other-card:hover { border-color: var(--ink); transform: translateX(4px); }
.other-card-icon { font-size: 1.5rem; flex-shrink: 0; }
.other-card-name { font-family: var(--ff-head); font-weight: 700; font-size: .9rem; margin-bottom: .1rem; }
.other-card-price { font-size: .78rem; color: var(--accent); font-weight: 600; }

/* ── Footer ──────────────────────────────────────────────── */
.footer { background: var(--ink); color: var(--white); padding: 2.5rem 0; }
.footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.footer-logo { font-family: var(--ff-head); font-weight: 800; }
.footer-logo span { color: var(--accent); }
.footer-copy { font-size: .78rem; color: #444; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 960px) {
  .detail-layout { grid-template-columns: 1fr; }
  .detail-sidebar { position: static; }
  .order-card { max-width: 480px; }
  .features-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .nav-links, .nav-cta { display: none; }
  .service-hero { padding: 2rem; }
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

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <a href="<?= APP_URL ?>/services">Layanan</a>
      <span class="breadcrumb-sep">›</span>
      <span><?= e($service['name']) ?></span>
    </div>
  </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container">
  <div class="detail-layout">

    <!-- LEFT COLUMN -->
    <main class="detail-main">

      <!-- Hero layanan -->
      <div class="service-hero">
        <div class="service-hero-inner">
          <span class="service-category-tag"><?= e($service['category_name']) ?></span>
          <h1><?= e($service['name']) ?></h1>
          <p><?= e($service['short_desc']) ?></p>
          <div class="service-hero-meta">
            <?php if ($service['delivery_days']): ?>
            <div class="hero-meta-pill">
              ⏱ Selesai dalam <strong>&nbsp;<?= $service['delivery_days'] ?> hari kerja</strong>
            </div>
            <?php endif; ?>
            <div class="hero-meta-pill">
              💬 Revisi <strong>&nbsp;tersedia</strong>
            </div>
            <div class="hero-meta-pill">
              📁 File source <strong>&nbsp;disertakan</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Deskripsi lengkap -->
      <div class="detail-section">
        <div class="section-label">Tentang Layanan Ini</div>
        <div class="detail-desc">
          <?php if ($service['description']): ?>
            <?= nl2br(e($service['description'])) ?>
          <?php else: ?>
            <p>Layanan <strong><?= e($service['name']) ?></strong> dirancang untuk membantu bisnis Anda tampil lebih profesional dan berkesan. Tim kami bekerja dengan pendekatan yang terstruktur — dimulai dari memahami visi dan target audiens Anda, hingga menghasilkan output yang sesuai ekspektasi.</p>
            <p>Setiap proyek dikerjakan secara personal, bukan menggunakan template generik. Kami percaya bahwa identitas visual yang kuat adalah investasi jangka panjang bagi pertumbuhan bisnis Anda.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Yang Anda Dapatkan -->
      <?php if (!empty($service['features'])): ?>
      <div class="detail-section">
        <div class="section-label">Yang Anda Dapatkan</div>
        <div class="features-grid">
          <?php foreach ($service['features'] as $feature): ?>
            <div class="feature-item">
              <div class="feature-check">✓</div>
              <span class="feature-text"><?= e($feature) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <!-- Default features jika tidak ada di DB -->
      <div class="detail-section">
        <div class="section-label">Yang Anda Dapatkan</div>
        <div class="features-grid">
          <?php
          $defaultFeatures = [
            'Konsultasi awal gratis',
            'Revisi sesuai paket yang dipilih',
            'File final siap pakai (PNG, PDF, SVG)',
            'Pengiriman tepat waktu sesuai deadline',
            'Komunikasi aktif selama pengerjaan',
            'Garansi kepuasan klien',
          ];
          foreach ($defaultFeatures as $f): ?>
            <div class="feature-item">
              <div class="feature-check">✓</div>
              <span class="feature-text"><?= $f ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Proses Pengerjaan -->
      <div class="detail-section">
        <div class="section-label">Proses Pengerjaan</div>
        <div class="process-list">
          <?php
          $processes = [
            ['Brief & Konsultasi',   'Anda mengisi form brief dan menjelaskan kebutuhan. Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan klarifikasi.'],
            ['Pembayaran',           'Lakukan pembayaran sesuai paket yang dipilih. Tersedia berbagai metode: transfer bank, QRIS, GoPay, OVO, dan lainnya.'],
            ['Pengerjaan',           'Tim kami mulai mengerjakan proyek. Anda akan mendapat update progres secara berkala melalui dashboard atau WhatsApp.'],
            ['Presentasi & Revisi',  'Kami mempresentasikan hasil awal. Anda dapat memberikan masukan dan meminta revisi sesuai ketentuan paket.'],
            ['Selesai & Serah Terima','Setelah disetujui, semua file final diserahkan melalui dashboard. Proyek selesai dan Anda mendapat file dalam format yang disepakati.'],
          ];
          foreach ($processes as $i => $p): ?>
            <div class="process-item">
              <div class="process-num"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>
              <div class="process-content">
                <h4><?= $p[0] ?></h4>
                <p><?= $p[1] ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- FAQ -->
      <div class="detail-section">
        <div class="section-label">Pertanyaan yang Sering Ditanyakan</div>
        <div class="faq-list" id="faq-list">
          <?php
          // FAQ umum + FAQ spesifik per kategori layanan
          $faqGeneral = [
            ['Berapa lama proses pengerjaan?', 'Estimasi waktu pengerjaan sudah tercantum di halaman ini. Namun waktu aktual bergantung pada kecepatan brief yang diberikan, jumlah revisi, dan kompleksitas proyek. Kami selalu berusaha menyelesaikan tepat atau lebih cepat dari estimasi.'],
            ['Berapa kali saya bisa revisi?', 'Jumlah revisi bergantung pada paket yang dipilih. Revisi yang dihitung adalah perubahan signifikan pada desain, bukan koreksi minor seperti typo atau perubahan warna kecil.'],
            ['Bagaimana cara melakukan pembayaran?', 'Pembayaran dilakukan melalui platform kami yang terintegrasi dengan Midtrans — mendukung transfer bank (BCA, Mandiri, BNI, BRI), QRIS, GoPay, OVO, dan kartu kredit. Aman dan terkonfirmasi otomatis.'],
            ['Apakah saya mendapat file source-nya?', 'Ya. Setelah proyek selesai, Anda akan mendapatkan file final dalam format yang disepakati (biasanya PNG, SVG, PDF, dan file source seperti AI, PSD, atau Figma), tergantung paket yang dipilih.'],
            ['Bagaimana jika saya tidak puas dengan hasilnya?', 'Kepuasan Anda adalah prioritas kami. Jika Anda tidak puas setelah revisi pertama, kami akan berdiskusi untuk menemukan solusi terbaik. Dalam kasus tertentu, pengembalian dana parsial dapat dilakukan sesuai kebijakan kami.'],
          ];

          $faqByCategory = [
            'branding' => [
              ['Apakah logo yang dibuat 100% unik?', 'Ya, setiap logo dibuat dari nol berdasarkan brief dan karakter bisnis Anda — bukan menggunakan template atau aset yang sudah ada. Anda mendapat hak penuh atas desain yang dihasilkan.'],
              ['Format file apa saja yang saya terima?', 'Anda akan mendapatkan: PNG (transparan & putih), SVG (vector scalable), PDF, dan file source AI/EPS. Untuk paket Full Brand System, termasuk juga file presentasi brand guideline.'],
            ],
            'uiux' => [
              ['Tools apa yang digunakan untuk desain UI?', 'Kami menggunakan Figma sebagai tools utama. Semua file Figma akan diserahkan sehingga tim developer Anda bisa langsung menggunakannya untuk implementasi.'],
              ['Apakah sudah termasuk desain responsif mobile?', 'Untuk paket Landing Page, termasuk 1 breakpoint mobile. Untuk paket Mobile App UI, otomatis sudah mobile-first. Paket lain bisa ditambahkan dengan biaya tambahan.'],
            ],
            'socmed' => [
              ['Template diedit di mana?', 'Template diserahkan dalam format Canva (link template) dan/atau Figma. Anda bisa mengedit sendiri kapan saja untuk konten baru tanpa biaya tambahan.'],
              ['Apakah termasuk copywriting?', 'Paket ini mencakup template visual saja. Penulisan caption atau copywriting dapat ditambahkan sebagai layanan terpisah.'],
            ],
            'motion' => [
              ['Format video apa yang diserahkan?', 'File diserahkan dalam format MP4 (H.264) untuk upload sosmed/website, dan GIF untuk keperluan tertentu. File source After Effects juga disertakan pada paket tertentu.'],
              ['Apakah bisa menambah durasi video?', 'Ya, penambahan durasi dapat dilakukan dengan biaya tambahan yang dihitung per 15 detik. Hubungi kami untuk penawaran khusus.'],
            ],
            'print' => [
              ['Apakah file siap cetak?', 'Ya, semua file desain print diserahkan dalam format PDF siap cetak dengan spesifikasi CMYK, bleed 3mm, dan resolusi 300 dpi sesuai standar percetakan.'],
              ['Apakah termasuk biaya cetak?', 'Tidak. Layanan ini hanya mencakup desain digital. Namun kami bisa merekomendasikan vendor cetak terpercaya jika Anda membutuhkan.'],
            ],
          ];

          $faqs = array_merge($faqGeneral, $faqByCategory[$catSlug] ?? []);
          foreach ($faqs as $i => $faq): ?>
            <div class="faq-item" id="faq-<?= $i ?>">
              <div class="faq-q" onclick="toggleFaq(<?= $i ?>)">
                <span class="faq-q-text"><?= e($faq[0]) ?></span>
                <span class="faq-icon">+</span>
              </div>
              <div class="faq-a">
                <div class="faq-a-inner"><?= e($faq[1]) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </main><!-- /.detail-main -->

    <!-- RIGHT SIDEBAR -->
    <aside class="detail-sidebar">

      <!-- Kartu order -->
      <div class="order-card">
        <div class="order-card-header">
          <div class="order-price-label">
            <?= $service['price_type'] === 'starting_from' ? 'Mulai dari' : ($service['price_type'] === 'custom' ? 'Harga' : 'Harga tetap') ?>
          </div>
          <div class="order-price-value">
            <?= $service['price_type'] === 'custom' ? 'Diskusi' : formatRupiah((float)$service['price']) ?>
          </div>
          <?php if ($service['price_type'] === 'starting_from'): ?>
            <div class="order-price-type">Harga akhir tergantung kebutuhan</div>
          <?php endif; ?>
        </div>

        <div class="order-card-body">
          <div class="order-meta">
            <?php if ($service['delivery_days']): ?>
            <div class="order-meta-row">
              <span class="meta-key">⏱ Estimasi selesai</span>
              <span class="meta-val"><?= $service['delivery_days'] ?> hari kerja</span>
            </div>
            <?php endif; ?>
            <div class="order-meta-row">
              <span class="meta-key">🔁 Revisi</span>
              <span class="meta-val">Tersedia</span>
            </div>
            <div class="order-meta-row">
              <span class="meta-key">📁 Format file</span>
              <span class="meta-val">PNG, SVG, PDF</span>
            </div>
            <div class="order-meta-row">
              <span class="meta-key">💬 Konsultasi</span>
              <span class="meta-val">Gratis</span>
            </div>
          </div>

          <?php if (Session::isLoggedIn()): ?>
            <a href="<?= APP_URL ?>/order?service=<?= e($service['slug']) ?>" class="order-cta-main">
              Pesan Layanan Ini →
            </a>
          <?php else: ?>
            <a href="<?= APP_URL ?>/register?redirect=<?= urlencode(APP_URL . '/order?service=' . $service['slug']) ?>" class="order-cta-main">
              Mulai Pesan Sekarang →
            </a>
          <?php endif; ?>

          <a href="<?= APP_URL ?>/contact" class="order-cta-secondary">
            💬 Tanya Sebelum Pesan
          </a>

          <div class="guarantee-strip">
            <span class="guarantee-icon">🛡</span>
            <span>Garansi kepuasan — hasil tidak sesuai brief? Kami revisi hingga Anda puas.</span>
          </div>
        </div>
      </div>

      <!-- Layanan terkait (dari kategori yang sama, maksimal 3) -->
      <?php
      // Ambil layanan lain di kategori yang sama
      $db_conn = db();
      $relStmt = $db_conn->prepare('
        SELECT id, name, slug, price, price_type
        FROM services
        WHERE category_id = ? AND id != ? AND is_active = 1
        LIMIT 3
      ');
      $relStmt->execute([$service['category_id'], $service['id']]);
      $relatedServices = $relStmt->fetchAll();
      ?>
      <?php if (!empty($relatedServices)): ?>
      <div class="related-section">
        <div class="related-title">Layanan Serupa</div>
        <div class="related-list">
          <?php foreach ($relatedServices as $rel): ?>
            <a href="<?= APP_URL ?>/services/<?= e($rel['slug']) ?>" class="related-item">
              <span class="related-item-name"><?= e($rel['name']) ?></span>
              <span class="related-item-price">
                <?= $rel['price_type'] === 'custom' ? 'Diskusi' : formatRupiah((float)$rel['price']) ?>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

    </aside>

  </div><!-- /.detail-layout -->
</div><!-- /.container -->

<!-- LAYANAN LAIN -->
<?php
$otherStmt = db()->prepare('
  SELECT s.id, s.name, s.slug, s.price, s.price_type, sc.slug AS cat_slug
  FROM services s
  JOIN service_categories sc ON s.category_id = sc.id
  WHERE s.category_id != ? AND s.is_active = 1
  ORDER BY RAND()
  LIMIT 4
');
$otherStmt->execute([$service['category_id']]);
$otherServices = $otherStmt->fetchAll();
?>
<?php if (!empty($otherServices)): ?>
<section class="other-section">
  <div class="container">
    <h2>Layanan Lainnya yang Mungkin Anda Butuhkan</h2>
    <div class="other-grid">
      <?php foreach ($otherServices as $other):
        [$oc, $oIcon] = $catColors[$other['cat_slug']] ?? ['#7A7570','★'];
      ?>
        <a href="<?= APP_URL ?>/services/<?= e($other['slug']) ?>" class="other-card">
          <span class="other-card-icon"><?= $oIcon ?></span>
          <div>
            <div class="other-card-name"><?= e($other['name']) ?></div>
            <div class="other-card-price">
              <?= $other['price_type'] === 'custom' ? 'Hubungi Kami' : formatRupiah((float)$other['price']) ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FOOTER -->
<footer class="footer">
  <div class="container footer-inner">
    <div class="footer-logo">creative<span>.</span></div>
    <p class="footer-copy">© <?= date('Y') ?> Creative Studio. Semua hak dilindungi.</p>
  </div>
</footer>

<script>
// ── FAQ accordion ─────────────────────────────────────────
function toggleFaq(i) {
  const item = document.getElementById('faq-' + i);
  const isOpen = item.classList.contains('open');
  // Tutup semua
  document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
  // Toggle yang diklik
  if (!isOpen) item.classList.add('open');
}

// Buka FAQ pertama secara default
document.querySelector('.faq-item')?.classList.add('open');

// ── Scroll reveal ─────────────────────────────────────────
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.08 });

document.querySelectorAll('.detail-section, .other-card').forEach((el, i) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(16px)';
  el.style.transition = `opacity .4s ease ${i * 0.05}s, transform .4s ease ${i * 0.05}s`;
  observer.observe(el);
});
</script>

</body>
</html>
