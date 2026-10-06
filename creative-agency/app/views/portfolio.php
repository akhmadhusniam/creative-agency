<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portfolio — Creative Studio</title>
<meta name="description" content="Lihat karya-karya terbaik kami — branding, UI/UX, social media, motion graphic, dan print design untuk berbagai industri.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
/* ── Filter ─────────────────────────────────────────────── */
.filter-bar{position:sticky;top:var(--nav-h);z-index:90;background:var(--paper);border-bottom:1px solid var(--cream);padding:.85rem clamp(1rem,4vw,3rem)}
.filter-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:.5rem;overflow-x:auto;scrollbar-width:none}
.filter-inner::-webkit-scrollbar{display:none}
.fbtn{flex-shrink:0;padding:.42rem 1.1rem;border-radius:2rem;border:1.5px solid var(--cream);background:var(--white);font-family:var(--fh);font-weight:700;font-size:.75rem;letter-spacing:.05em;text-transform:uppercase;color:var(--muted);cursor:pointer;transition:all .18s;white-space:nowrap}
.fbtn:hover{border-color:var(--ink);color:var(--ink)}
.fbtn.active{background:var(--ink);border-color:var(--ink);color:#fff}
.fbtn-count{display:inline-block;margin-left:.3rem;background:var(--cream);color:var(--muted);border-radius:2rem;padding:.05rem .45rem;font-size:.68rem;transition:all .18s}
.fbtn.active .fbtn-count{background:rgba(255,255,255,.15);color:rgba(255,255,255,.7)}

/* ── Portfolio grid ─────────────────────────────────────── */
.portfolio-section{padding:3rem clamp(1rem,4vw,3rem) 5rem}
.portfolio-inner{max-width:1200px;margin:0 auto}
.portfolio-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:1rem;
}
.p-item{
  border-radius:12px;overflow:hidden;
  background:var(--cream);
  position:relative;cursor:pointer;
  transition:transform .25s cubic-bezier(.2,.8,.2,1),box-shadow .25s;
}
.p-item:hover{transform:translateY(-4px);box-shadow:0 14px 36px rgba(0,0,0,.12)}
.p-item[data-hidden="true"]{display:none}

/* Span variants */
.p-item.span2{grid-column:span 2}
.p-item.tall{grid-row:span 2}

.p-thumb{
  width:100%;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
  font-size:3rem;position:relative;overflow:hidden;
}
.p-item.span2 .p-thumb{aspect-ratio:16/7}
.p-item.tall .p-thumb{aspect-ratio:4/5}
.p-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.p-ph{display:flex;flex-direction:column;align-items:center;gap:.5rem}
.p-ph-icon{font-size:2.75rem;opacity:.2}
.p-ph-lbl{font-family:var(--fh);font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);opacity:.5}

.p-cat-tag{
  position:absolute;top:.85rem;left:.85rem;z-index:2;
  background:rgba(13,13,13,.85);color:#fff;
  font-family:var(--fh);font-size:.62rem;font-weight:700;
  letter-spacing:.08em;text-transform:uppercase;
  padding:.2rem .65rem;border-radius:2rem;
}
.p-overlay{
  position:absolute;inset:0;z-index:1;
  background:linear-gradient(to top,rgba(13,13,13,.92) 0%,rgba(13,13,13,.3) 50%,transparent 100%);
  display:flex;flex-direction:column;justify-content:flex-end;
  padding:1.5rem;
  opacity:0;transition:opacity .25s;
}
.p-item:hover .p-overlay{opacity:1}
.p-overlay h3{font-family:var(--fh);font-weight:700;color:#fff;font-size:1rem;margin-bottom:.2rem}
.p-overlay p{font-size:.78rem;color:#999}
.p-overlay-action{
  display:inline-flex;align-items:center;gap:.4rem;
  margin-top:.85rem;
  font-family:var(--fh);font-size:.72rem;font-weight:700;
  letter-spacing:.06em;text-transform:uppercase;color:var(--accent);
}

/* ── Empty state ────────────────────────────────────────── */
.p-empty{grid-column:1/-1;text-align:center;padding:5rem 2rem;color:var(--muted)}
.p-empty .icon{font-size:3rem;margin-bottom:1rem;opacity:.3}

/* ── Lightbox ───────────────────────────────────────────── */
.lightbox{
  position:fixed;inset:0;z-index:500;
  background:rgba(0,0,0,.95);
  display:none;align-items:center;justify-content:center;
  padding:2rem;
}
.lightbox.open{display:flex}
.lb-close{
  position:absolute;top:1.5rem;right:1.5rem;
  width:42px;height:42px;border-radius:50%;
  background:#1a1a1a;border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:1.2rem;color:#888;transition:all .2s;
}
.lb-close:hover{background:#222;color:#fff}
.lb-content{max-width:860px;width:100%;text-align:center}
.lb-img-wrap{
  border-radius:12px;overflow:hidden;background:#111;
  min-height:300px;display:flex;align-items:center;justify-content:center;
  margin-bottom:1.5rem;font-size:5rem;
}
.lb-img-wrap img{width:100%;max-height:70vh;object-fit:contain}
.lb-title{font-family:var(--fh);font-weight:800;font-size:1.25rem;color:#fff;margin-bottom:.35rem}
.lb-meta{font-size:.82rem;color:#555}
.lb-nav{position:absolute;top:50%;transform:translateY(-50%);width:44px;height:44px;border-radius:50%;background:#111;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#666;transition:all .2s}
.lb-nav:hover{background:#222;color:#fff}
.lb-prev{left:1.5rem}
.lb-next{right:1.5rem}

/* ── Stats mini ─────────────────────────────────────────── */
.port-stats{background:var(--ink);padding:3.5rem clamp(1rem,4vw,3rem)}
.port-stats-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:2rem}
.ps-item{text-align:center}
.ps-num{font-family:var(--fh);font-weight:800;font-size:1.75rem;color:#fff;line-height:1}
.ps-num em{font-style:normal;color:var(--accent)}
.ps-lbl{font-size:.75rem;color:#444;margin-top:.25rem}

@media(max-width:768px){
  .portfolio-grid{grid-template-columns:1fr 1fr}
  .p-item.span2{grid-column:1/-1}
  .p-item.tall{grid-row:auto}
}
@media(max-width:480px){
  .portfolio-grid{grid-template-columns:1fr}
  .p-item.span2{grid-column:auto}
}
</style>
</head>
<body>

<?php $activeNav = 'portfolio'; require VIEW_PATH . '/partials/nav.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="s-inner">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span>Portfolio</span>
    </div>
    <h1>Karya yang Punya<br><em>Cerita di Baliknya</em></h1>
    <p>Setiap proyek dimulai dari percakapan, dibangun dengan proses yang terstruktur, dan diakhiri dengan hasil yang klien bangga miliki.</p>
  </div>
</section>

<!-- STATS -->
<div class="port-stats">
  <div class="port-stats-inner reveal">
    <?php
    $ps = [['150+','Total proyek'],['12+','Industri'],['5','Kategori layanan'],['98%','Klien puas']];
    foreach ($ps as [$n,$l]): ?>
      <div class="ps-item">
        <div class="ps-num"><?= preg_replace('/([+%★])/', '<em>$1</em>', $n) ?></div>
        <div class="ps-lbl"><?= $l ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- FILTER -->
<div class="filter-bar">
  <div class="filter-inner">
    <button class="fbtn active" data-filter="all" onclick="filterPortfolio(this,'all')">
      Semua <span class="fbtn-count" id="count-all"><?= count($portfolio) ?: 12 ?></span>
    </button>
    <?php foreach ($categories as $cat):
      $cnt = count(array_filter($portfolio ?? [], fn($p) => $p['category_id'] == $cat['id']));
      if ($cnt === 0 && !empty($portfolio)) continue;
    ?>
      <button class="fbtn" data-filter="<?= $cat['id'] ?>" onclick="filterPortfolio(this,'<?= $cat['id'] ?>')">
        <?= e($cat['name']) ?> <span class="fbtn-count"><?= $cnt ?: 2 ?></span>
      </button>
    <?php endforeach; ?>
  </div>
</div>

<!-- PORTFOLIO GRID -->
<section class="portfolio-section">
  <div class="portfolio-inner">
    <div class="portfolio-grid" id="portfolio-grid">

      <?php
      $catColors  = ['branding'=>'#1e1e1e','uiux'=>'#dce8f0','socmed'=>'#d4e8d4','motion'=>'#f0e8d4','print'=>'#e8d4e8'];
      $catIcons   = ['branding'=>'✦','uiux'=>'◈','socmed'=>'◉','motion'=>'◎','print'=>'◆'];

      // Demo items jika DB kosong
      $demoItems = [
        ['span2','branding','1', 'Full Brand System — TechVenture','Branding identity lengkap termasuk logo, color palette, dan brand guideline 40 halaman.','PT TechVenture Indonesia','Branding'],
        ['',     'uiux',   '2', 'Mobile App UI — FinTrack','Desain antarmuka aplikasi keuangan personal untuk 15 screen utama dengan design system.','FinTrack Startup','UI/UX'],
        ['tall', 'socmed', '3', 'Social Media Kit — BeautyBrand','100+ template konten untuk feed dan story Instagram yang sudah digunakan 8 bulan berturut-turut.','BeautyBrand Official','Social Media'],
        ['',     'motion', '4', 'Motion Graphic — EventKreatiF','Paket animasi opening, lower third, dan bumper 15 detik untuk acara tahunan.','EventKreatiF Indonesia','Motion'],
        ['',     'print',  '5', 'Corporate Stationery — PT Maju','Desain lengkap stationery perusahaan: letterhead, amplop, kartu nama, dan folder.','PT Maju Bersama','Print'],
        ['',     'uiux',   '6', 'Landing Page — GrowthAcademy','Halaman arahan kursus online dengan conversion rate 4.2% dalam bulan pertama.','GrowthAcademy.id','UI/UX'],
        ['span2','socmed', '7', 'Rebranding Campaign — FreshMart','Kampanye visual rebranding lengkap di semua touchpoint digital untuk supermarket lokal.','FreshMart Group','Social Media'],
        ['',     'branding','8','Logo Refresh — UrbanSpace','Modernisasi logo dan identitas visual coworking space tanpa kehilangan karakter aslinya.','UrbanSpace Cowork','Branding'],
        ['',     'motion', '9', 'Product Video — GreenFood','Video animasi produk 30 detik untuk iklan Instagram dan TikTok dengan 2M+ tayangan.','GreenFood Indonesia','Motion'],
      ];

      if (!empty($portfolio)):
        foreach ($portfolio as $i => $item):
          $slug = $item['category_slug'] ?? 'branding';
          $bg   = $catColors[$slug] ?? '#e8e8e8';
          $icon = $catIcons[$slug]  ?? '★';
          $span = ($i === 0 || $i === 6) ? 'span2' : (($i === 2) ? 'tall' : '');
        ?>
          <div class="p-item <?= $span ?>" data-cat="<?= $item['category_id'] ?>" onclick="openLightbox(<?= $i ?>)" role="button" tabindex="0">
            <div class="p-thumb" style="background:<?= $bg ?>">
              <?php if ($item['cover_image']): ?>
                <img src="<?= APP_URL ?>/uploads/<?= e($item['cover_image']) ?>" alt="<?= e($item['title']) ?>">
              <?php else: ?>
                <div class="p-ph"><div class="p-ph-icon"><?= $icon ?></div><div class="p-ph-lbl"><?= e($item['category_name'] ?? '') ?></div></div>
              <?php endif; ?>
              <span class="p-cat-tag"><?= e($item['category_name'] ?? '') ?></span>
            </div>
            <div class="p-overlay">
              <h3><?= e($item['title']) ?></h3>
              <p><?= e($item['client_name'] ?? '') ?></p>
              <span class="p-overlay-action">Lihat Detail →</span>
            </div>
          </div>
        <?php endforeach;
      else:
        foreach ($demoItems as $i => [$span,$slug,$num,$title,$desc,$client,$cat]):
          $bg   = $catColors[$slug] ?? '#e8e8e8';
          $icon = $catIcons[$slug]  ?? '★';
        ?>
          <div class="p-item <?= $span ?>" data-cat="<?= $slug ?>" data-title="<?= $title ?>" data-client="<?= $client ?>" onclick="openLightbox(<?= $i ?>)" role="button" tabindex="0">
            <div class="p-thumb" style="background:<?= $bg ?>">
              <div class="p-ph"><div class="p-ph-icon"><?= $icon ?></div><div class="p-ph-lbl"><?= $cat ?></div></div>
              <span class="p-cat-tag"><?= $cat ?></span>
            </div>
            <div class="p-overlay">
              <h3><?= $title ?></h3>
              <p><?= $client ?></p>
              <span class="p-overlay-action">Lihat Detail →</span>
            </div>
          </div>
        <?php endforeach;
      endif; ?>

      <div class="p-empty" id="p-empty" style="display:none">
        <div class="icon">◈</div>
        <p>Belum ada karya di kategori ini.</p>
      </div>
    </div><!-- /.portfolio-grid -->
  </div>
</section>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Preview karya">
  <button class="lb-close" onclick="closeLightbox()" aria-label="Tutup">✕</button>
  <button class="lb-nav lb-prev" onclick="lbNav(-1)" aria-label="Sebelumnya">←</button>
  <div class="lb-content">
    <div class="lb-img-wrap" id="lb-img-wrap"></div>
    <div class="lb-title" id="lb-title"></div>
    <div class="lb-meta" id="lb-meta"></div>
  </div>
  <button class="lb-nav lb-next" onclick="lbNav(1)" aria-label="Berikutnya">→</button>
</div>

<!-- CTA -->
<section class="cta-strip">
  <div class="cta-strip-inner reveal">
    <div>
      <h2>Proyek Anda Bisa<br>Jadi Karya <em>Selanjutnya</em></h2>
      <p>Konsultasikan kebutuhan visual bisnis Anda. Gratis, tanpa komitmen, dan kami siap membantu.</p>
    </div>
    <div class="cta-strip-btns">
      <a href="<?= APP_URL ?>/services" class="btn-cta-p">Lihat Layanan →</a>
      <a href="<?= APP_URL ?>/contact"  class="btn-cta-g">💬 Hubungi Kami</a>
    </div>
  </div>
</section>

<?php require VIEW_PATH . '/partials/footer.php'; ?>

<script>
// ── Filter ───────────────────────────────────────────────
function filterPortfolio(btn, filterId) {
  document.querySelectorAll('.fbtn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const items = document.querySelectorAll('.p-item');
  const empty = document.getElementById('p-empty');
  let visible = 0;
  items.forEach(item => {
    const match = filterId === 'all' || item.dataset.cat === filterId;
    item.dataset.hidden = match ? 'false' : 'true';
    item.style.display  = match ? '' : 'none';
    if (match) visible++;
  });
  empty.style.display = visible === 0 ? 'grid' : 'none';
}

// ── Lightbox ─────────────────────────────────────────────
let lbItems = [];
let lbIdx   = 0;

// Build data array from DOM
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.p-item').forEach(item => {
    lbItems.push({
      title:  item.querySelector('.p-overlay h3')?.textContent || '',
      client: item.querySelector('.p-overlay p')?.textContent  || '',
      icon:   item.querySelector('.p-ph-icon')?.textContent    || '◈',
      bg:     item.querySelector('.p-thumb')?.style.background || '#111',
    });
  });
});

function openLightbox(i) {
  lbIdx = i;
  renderLb();
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}

function lbNav(dir) {
  lbIdx = (lbIdx + dir + lbItems.length) % lbItems.length;
  renderLb();
}

function renderLb() {
  const d = lbItems[lbIdx] || {};
  document.getElementById('lb-img-wrap').innerHTML =
    `<div style="width:100%;height:320px;background:${d.bg};display:flex;align-items:center;justify-content:center;font-size:5rem;">${d.icon}</div>`;
  document.getElementById('lb-title').textContent = d.title  || '';
  document.getElementById('lb-meta').textContent  = d.client || '';
}

// Keyboard nav
document.addEventListener('keydown', e => {
  if (!document.getElementById('lightbox').classList.contains('open')) return;
  if (e.key === 'Escape')      closeLightbox();
  if (e.key === 'ArrowLeft')   lbNav(-1);
  if (e.key === 'ArrowRight')  lbNav(1);
});
// Click outside
document.getElementById('lightbox').addEventListener('click', function(e) {
  if (e.target === this) closeLightbox();
});

// ── Reveal ───────────────────────────────────────────────
const obs = new IntersectionObserver(entries => {
  entries.forEach((e,i) => { if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*70);obs.unobserve(e.target);} });
},{threshold:.07});
document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
</script>
</body>
</html>
