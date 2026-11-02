<?php
// ============================================================
//  app/views/portfolio.php — daftar studi kasus
// ============================================================

$portfolioCount = count($portfolio);
$usedCategories = array_values(array_unique(array_filter(array_column($portfolio, 'category_id'))));

$seo = [
    'title'       => 'Portfolio',
    'description' => 'Studi kasus branding, UI/UX, social media, motion, dan print dari Creative Studio. Lihat proses, solusi, dan hasil setiap proyek.',
    'canonical'   => APP_URL . '/portfolio',
    'jsonLd'      => [
        [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            'name'        => 'Portfolio — ' . site_setting('site_name', APP_NAME),
            'description' => 'Kumpulan studi kasus proyek desain Creative Studio.',
            'url'         => APP_URL . '/portfolio',
        ],
        [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => APP_URL . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Portfolio', 'item' => APP_URL . '/portfolio'],
            ],
        ],
    ],
];
if ($portfolio) {
    $listItems = [];
    foreach ($portfolio as $i => $p) {
        $listItems[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'url'      => APP_URL . '/portfolio/' . rawurlencode($p['slug']),
            'name'     => $p['title'],
        ];
    }
    $seo['jsonLd'][] = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Studi kasus Creative Studio',
        'itemListElement' => $listItems,
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require VIEW_PATH . '/partials/seo.php'; ?>
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
.portfolio-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.p-item{
  display:block;border-radius:12px;overflow:hidden;
  background:var(--cream);
  position:relative;
  transition:transform .25s cubic-bezier(.2,.8,.2,1),box-shadow .25s;
}
.p-item:hover{transform:translateY(-4px);box-shadow:0 14px 36px rgba(0,0,0,.12)}
.p-item[hidden]{display:none}

/* Span variants */
.p-item.span2{grid-column:span 2}
.p-item.tall{grid-row:span 2}

.p-thumb{
  width:100%;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
  font-size:3rem;position:relative;overflow:hidden;
}
.p-item.span2 .p-thumb{aspect-ratio:16/8}
.p-item.tall .p-thumb{aspect-ratio:4/5}
.p-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transition:transform .35s}
.p-item:hover .p-thumb img{transform:scale(1.03)}
.p-ph{display:flex;flex-direction:column;align-items:center;gap:.5rem}
.p-ph-icon{font-size:2.75rem;opacity:.2}
.p-ph-lbl{font-family:var(--fh);font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);opacity:.5}

.p-tags{position:absolute;top:.85rem;left:.85rem;right:.85rem;z-index:2;display:flex;align-items:center;justify-content:space-between;gap:.5rem;pointer-events:none}
.p-cat-tag{
  background:rgba(18,42,28,.9);color:#fff;border:1px solid var(--accent);
  font-family:var(--fm);font-size:.62rem;font-weight:700;
  letter-spacing:.03em;text-transform:uppercase;
  padding:.2rem .65rem;border-radius:0;
}
.p-sample{
  background:var(--accent);color:var(--ink);
  font-family:var(--fm);font-size:.6rem;font-weight:700;
  letter-spacing:.06em;text-transform:uppercase;
  padding:.2rem .55rem;border-radius:999px;border:1px solid var(--ink);
}
.p-overlay{
  position:absolute;inset:0;z-index:1;
  background:linear-gradient(to top,rgba(13,13,13,.94) 0%,rgba(13,13,13,.4) 55%,transparent 100%);
  display:flex;flex-direction:column;justify-content:flex-end;
  padding:1.5rem;
  opacity:0;transition:opacity .25s;
}
.p-item:hover .p-overlay,.p-item:focus-visible .p-overlay{opacity:1}
.p-overlay h3{font-family:var(--fh);font-weight:700;color:#fff;font-size:1.05rem;line-height:1.15;margin-bottom:.2rem}
.p-overlay p{font-size:.78rem;color:#B9C4BD}
.p-overlay-action{
  display:inline-flex;align-items:center;gap:.4rem;
  margin-top:.85rem;
  font-family:var(--fh);font-size:.72rem;font-weight:700;
  letter-spacing:.06em;text-transform:uppercase;color:var(--accent);
}

/* ── Empty state ────────────────────────────────────────── */
.p-empty{grid-column:1/-1;text-align:center;padding:5rem 2rem;color:var(--muted)}
.p-empty .icon{font-size:3rem;margin-bottom:1rem;opacity:.3}
.p-empty h3{font-family:var(--fh);font-size:1.2rem;text-transform:uppercase;color:var(--ink);margin-bottom:.5rem}
.p-empty p{margin-bottom:1.5rem}

/* ── Stats mini ─────────────────────────────────────────── */
.port-stats{background:var(--ink);padding:3.5rem clamp(1rem,4vw,3rem)}
.port-stats-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:2rem}
.ps-item{text-align:center}
.ps-num{font-family:var(--fh);font-weight:800;font-size:1.75rem;color:#fff;line-height:1}
.ps-num em{font-style:normal;color:var(--accent)}
.ps-lbl{font-size:.75rem;color:#8FA396;margin-top:.25rem}

@media(max-width:768px){
  .portfolio-grid{grid-template-columns:1fr 1fr}
  .p-item.span2{grid-column:1/-1}
  .p-item.tall{grid-row:auto}
  .p-item.tall .p-thumb{aspect-ratio:4/3}
}
@media(max-width:480px){
  .portfolio-grid{grid-template-columns:1fr}
  .p-item.span2{grid-column:auto}
}
@media(hover:none){
  .p-overlay{opacity:1;background:linear-gradient(to top,rgba(13,13,13,.92) 0%,rgba(13,13,13,.25) 55%,transparent 100%)}
}
@media(prefers-reduced-motion:reduce){
  .p-item,.p-thumb img{transition:none}
}
</style>
</head>
<body>

<?php $activeNav = 'portfolio'; require VIEW_PATH . '/partials/nav.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero" id="main">
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
    $ps = [
        [ (string) $portfolioCount, 'Studi kasus'],
        [ (string) count($usedCategories), 'Kategori layanan'],
        [ 'Rp0', 'Konsultasi awal'],
    ];
    foreach ($ps as [$n, $l]): ?>
      <div class="ps-item">
        <div class="ps-num"><?= preg_replace('/(\d+)/', '<em>$1</em>', e($n)) ?></div>
        <div class="ps-lbl"><?= e($l) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- FILTER -->
<div class="filter-bar">
  <div class="filter-inner" role="group" aria-label="Filter kategori portfolio">
    <button class="fbtn active" data-filter="all" onclick="filterPortfolio(this,'all')">
      Semua <span class="fbtn-count"><?= $portfolioCount ?></span>
    </button>
    <?php foreach ($categories as $cat):
      $cnt = count(array_filter($portfolio, fn($p) => $p['category_id'] == $cat['id']));
      if ($cnt === 0) continue;
    ?>
      <button class="fbtn" data-filter="<?= $cat['id'] ?>" onclick="filterPortfolio(this,'<?= $cat['id'] ?>')">
        <?= e($cat['name']) ?> <span class="fbtn-count"><?= $cnt ?></span>
      </button>
    <?php endforeach; ?>
  </div>
</div>

<!-- PORTFOLIO GRID -->
<section class="portfolio-section">
  <div class="portfolio-inner">
    <div class="portfolio-grid" id="portfolio-grid">

      <?php
      $catColors = ['branding' => '#122A1C', 'uiux' => '#DCEEDF', 'socmed' => '#D4EEDC', 'motion' => '#EAF5D0', 'print' => '#D8EAE2'];
      $catIcons  = ['branding' => '✦', 'uiux' => '◈', 'socmed' => '◉', 'motion' => '◎', 'print' => '◆'];
      $catFg     = ['branding' => '#C8FF4D', 'uiux' => '#122A1C', 'socmed' => '#122A1C', 'motion' => '#122A1C', 'print' => '#122A1C'];

      if ($portfolio):
        foreach ($portfolio as $i => $item):
          $slug  = $item['category_slug'] ?? 'branding';
          $bg    = $catColors[$slug] ?? '#E4E9E5';
          $fg    = $catFg[$slug] ?? '#122A1C';
          $icon  = $catIcons[$slug] ?? '★';
          $span  = ($i === 0 || $i === 6) ? 'span2' : (($i === 2) ? 'tall' : '');
          $href  = APP_URL . '/portfolio/' . rawurlencode($item['slug']);
        ?>
          <a class="p-item <?= $span ?>" href="<?= e($href) ?>" data-cat="<?= (int) $item['category_id'] ?>"
             aria-label="Baca studi kasus <?= e($item['title']) ?>">
            <div class="p-thumb" style="background:<?= e($bg) ?>;color:<?= e($fg) ?>">
              <?php if ($item['cover_image']): ?>
                <img src="<?= APP_URL ?>/uploads/<?= e($item['cover_image']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
              <?php else: ?>
                <div class="p-ph"><div class="p-ph-icon"><?= $icon ?></div><div class="p-ph-lbl"><?= e($item['category_name'] ?? '') ?></div></div>
              <?php endif; ?>
              <div class="p-tags">
                <span class="p-cat-tag"><?= e($item['category_name'] ?? '') ?></span>
                <?php if (!empty($item['is_sample'])): ?>
                  <span class="p-sample">Sampel</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="p-overlay">
              <h3><?= e($item['title']) ?></h3>
              <p><?= e($item['client_name'] ?? '') ?><?= !empty($item['year']) ? ' · ' . e((string) $item['year']) : '' ?></p>
              <span class="p-overlay-action">Baca Studi Kasus →</span>
            </div>
          </a>
        <?php endforeach;
      endif; ?>

      <?php if (!$portfolio): ?>
        <div class="p-empty">
          <div class="icon">◈</div>
          <h3>Portfolio Segera Hadir</h3>
          <p>Kami sedang menyiapkan studi kasus terbaru. Sementara itu, ceritakan proyek Anda — kami akan tunjukkan karya yang paling relevan.</p>
          <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin melihat karya yang relevan dengan proyek saya.')) ?>"
             class="btn btn-primary" target="_blank" rel="noopener">💬 Minta Contoh Karya</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-strip">
  <div class="cta-strip-inner reveal">
    <div>
      <h2>Proyek Anda Bisa<br>Jadi Karya <em>Selanjutnya</em></h2>
      <p>Konsultasikan kebutuhan visual bisnis Anda. Gratis, tanpa komitmen, dan kami siap membantu.</p>
    </div>
    <div class="cta-strip-btns">
      <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin konsultasi proyek desain.')) ?>" class="btn-cta-p" target="_blank" rel="noopener" data-cta="portfolio-cta-wa">💬 Konsultasi Gratis →</a>
      <a href="<?= APP_URL ?>/services" class="btn-cta-g" data-cta="portfolio-cta-services">Lihat Layanan</a>
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

  items.forEach(item => {
    const match = filterId === 'all' || item.dataset.cat === filterId;
    item.hidden = !match;
  });
}

// ── Reveal ───────────────────────────────────────────────
const obs = new IntersectionObserver(entries => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      setTimeout(() => e.target.classList.add('visible'), i * 70);
      obs.unobserve(e.target);
    }
  });
}, { threshold: .07 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>
<?php require VIEW_PATH . '/partials/whatsapp-float.php'; ?>
<?php require VIEW_PATH . '/partials/nav-script.php'; ?>
</body>
</html>
