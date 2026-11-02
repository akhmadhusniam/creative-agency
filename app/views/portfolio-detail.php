<?php
// ============================================================
//  app/views/portfolio-detail.php — studi kasus portfolio
// ============================================================

$catSlug   = $portfolio['category_slug'] ?? 'branding';
$catColors = [
    'branding' => ['bg' => '#122A1C', 'fg' => '#C8FF4D'],
    'uiux'     => ['bg' => '#DCEEDF', 'fg' => '#122A1C'],
    'socmed'   => ['bg' => '#D4EEDC', 'fg' => '#122A1C'],
    'motion'   => ['bg' => '#EAF5D0', 'fg' => '#122A1C'],
    'print'    => ['bg' => '#D8EAE2', 'fg' => '#122A1C'],
];
$catIcons  = ['branding' => '✦', 'uiux' => '◈', 'socmed' => '◉', 'motion' => '◎', 'print' => '◆'];
$cover     = $catColors[$catSlug] ?? ['bg' => '#E4E9E5', 'fg' => '#122A1C'];
$icon      = $catIcons[$catSlug] ?? '★';

$paragraphs = static function (?string $text): array {
    $text = trim((string) $text);
    if ($text === '') {
        return [];
    }
    return array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', $text))));
};

$sections = [
    ['num' => '01', 'label' => 'Tantangan', 'text' => $portfolio['challenge'] ?? ''],
    ['num' => '02', 'label' => 'Solusi',    'text' => $portfolio['solution'] ?? ''],
];
$resultText = trim((string) ($portfolio['result'] ?? ''));

$metaItems = array_filter([
    'Klien'    => $portfolio['client_name'] ?? '',
    'Tahun'    => $portfolio['year'] ?? '',
    'Kategori' => $portfolio['category_name'] ?? '',
    'Durasi'   => $portfolio['duration'] ?? '',
]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php require VIEW_PATH . '/partials/seo.php'; ?>
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
/* ── Header studi kasus ─────────────────────────────────── */
.case-hero{padding:calc(var(--nav-h) + 3.5rem) clamp(1rem,4vw,3rem) 0;background:var(--paper)}
.case-wrap{max-width:var(--container);margin:0 auto}
.case-crumb{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;font-family:var(--fm);font-size:.72rem;color:var(--muted);margin-bottom:2rem}
.case-crumb a:hover{color:var(--ink)}
.case-crumb span{opacity:.5}

.case-eyebrow{display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:1.1rem}
.case-cat{font-family:var(--fm);font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:.32rem .7rem;border:1.5px solid var(--ink);border-radius:999px;background:var(--accent);color:var(--ink)}
.sample-flag{display:inline-flex;align-items:center;gap:.35rem;font-family:var(--fm);font-size:.66rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:.3rem .65rem;border:1.5px dashed var(--muted);border-radius:999px;color:var(--muted);background:var(--cream-2)}
.sample-flag::before{content:'●';color:#B7791F;font-size:.55rem}

.case-title{font-family:var(--fh);font-size:clamp(2.1rem,5vw,4rem);line-height:1.02;letter-spacing:-.02em;text-transform:uppercase;margin-bottom:1.25rem;max-width:18ch}
.case-lead{font-size:clamp(1rem,1.5vw,1.15rem);line-height:1.8;color:var(--muted);max-width:62ch}

.case-meta{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1.25rem 1.5rem;margin:2.5rem 0 0;padding:1.5rem 0;border-top:1.5px solid var(--ink);border-bottom:1px solid var(--line)}
.case-meta dt{font-family:var(--fm);font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:.3rem}
.case-meta dd{font-family:var(--fh);font-size:.95rem;font-weight:700;letter-spacing:-.01em}

/* ── Cover ──────────────────────────────────────────────── */
.case-cover{position:relative;margin:2.5rem 0 0;border-radius:var(--radius-lg);overflow:hidden;aspect-ratio:16/7;background:var(--ink);border:1.5px solid var(--ink)}
.case-cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.case-cover-ph{position:absolute;inset:0;display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;padding:clamp(1.5rem,4vw,3rem);color:var(--fg,#fff);background:var(--bg,var(--ink))}
.case-cover-ph::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(18,42,28,.09) 1px,transparent 1px),linear-gradient(90deg,rgba(18,42,28,.09) 1px,transparent 1px);background-size:48px 48px;opacity:.55;pointer-events:none}
.case-cover-cat{position:relative;font-family:var(--fm);font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;opacity:.75}
.case-cover-name{position:relative;font-family:var(--fh);font-size:clamp(1.5rem,3vw,2.6rem);font-weight:800;line-height:1.05;letter-spacing:-.02em;text-transform:uppercase;max-width:22ch}
.case-cover-icon{position:relative;font-size:clamp(4rem,10vw,7rem);line-height:1;opacity:.85;flex-shrink:0}

/* ── Body ───────────────────────────────────────────────── */
.case-main{padding:4rem clamp(1rem,4vw,3rem) 5rem}
.case-grid{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:clamp(2rem,4vw,4rem);align-items:start}
.case-flow{min-width:0}
.case-sec{margin-bottom:3rem}
.case-sec-head{display:flex;align-items:baseline;gap:.75rem;margin-bottom:1rem}
.case-sec-num{font-family:var(--fm);font-size:.72rem;font-weight:700;color:var(--muted);letter-spacing:.08em}
.case-sec h2{font-family:var(--fh);font-size:clamp(1.3rem,2.2vw,1.75rem);letter-spacing:-.01em;text-transform:uppercase}
.case-sec p{color:var(--muted);line-height:1.9;font-size:.97rem}
.case-sec p + p{margin-top:1rem}

.case-result{background:var(--ink);color:#fff;border-radius:var(--radius-lg);padding:clamp(1.5rem,3vw,2.25rem);position:relative;overflow:hidden;margin-bottom:3rem}
.case-result::after{content:'↗';position:absolute;right:1.25rem;top:.75rem;font-family:var(--fh);font-size:4rem;line-height:1;color:var(--accent);opacity:.35}
.case-result .case-sec-num{color:var(--accent)}
.case-result h2{color:#fff}
.case-result p{color:#C7D4CB}
.case-sample-note{margin-top:1.25rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,.15);font-family:var(--fm);font-size:.68rem;letter-spacing:.04em;color:#8FA396;text-transform:uppercase}

.case-tags{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1.5rem}
.case-tag{font-family:var(--fm);font-size:.68rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;padding:.35rem .7rem;border:1px solid var(--line);border-radius:999px;color:var(--muted);background:var(--cream-2)}

/* ── Gallery ────────────────────────────────────────────── */
.case-gallery{margin:3.5rem 0 0}
.case-gallery-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid var(--line)}
.case-gallery-head h2{font-family:var(--fh);font-size:1.1rem;letter-spacing:-.01em;text-transform:uppercase}
.case-gallery-head span{font-family:var(--fm);font-size:.66rem;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.case-gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.85rem}
.case-shot{position:relative;aspect-ratio:4/3;border-radius:var(--radius);overflow:hidden;background:var(--cream);border:1px solid var(--line)}
.case-shot img{width:100%;height:100%;object-fit:cover}
.case-shot-ph{position:absolute;inset:0;display:flex;flex-direction:column;align-items:flex-start;justify-content:space-between;padding:1rem;background:linear-gradient(145deg,var(--cream-2),var(--cream));color:var(--muted)}
.case-shot-ph b{font-family:var(--fm);font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
.case-shot-ph span{font-family:var(--fh);font-size:1.5rem;font-weight:800;color:var(--ink);opacity:.18;line-height:1}

/* ── Sidebar ────────────────────────────────────────────── */
.case-side{position:sticky;top:calc(var(--nav-h) + 1.5rem)}
.case-side-card{border:1.5px solid var(--ink);border-radius:var(--radius-lg);padding:1.5rem;background:var(--paper)}
.case-side-card h2{font-family:var(--fh);font-size:1.1rem;line-height:1.15;text-transform:uppercase;letter-spacing:-.01em;margin-bottom:.6rem}
.case-side-card p{font-size:.86rem;line-height:1.7;color:var(--muted);margin-bottom:1.1rem}
.case-side-list{list-style:none;margin:1.25rem 0;padding:1.1rem 0;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.case-side-list li{display:flex;justify-content:space-between;gap:1rem;font-size:.8rem;padding:.35rem 0}
.case-side-list span{color:var(--muted)}
.case-side-list b{font-weight:600;text-align:right}
.case-side-card .btn{width:100%}
.case-back{display:inline-flex;align-items:center;gap:.4rem;margin-top:1rem;font-family:var(--fm);font-size:.68rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--muted)}
.case-back:hover{color:var(--ink)}

/* ── Related ────────────────────────────────────────────── */
.case-related{padding:4.5rem clamp(1rem,4vw,3rem);background:var(--cream)}
.case-related .case-wrap{max-width:var(--container)}
.related-head{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem}
.related-head h2{font-family:var(--fh);font-size:clamp(1.6rem,3vw,2.3rem);letter-spacing:-.01em;text-transform:uppercase;line-height:1.05}
.related-head a{font-family:var(--fm);font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--muted)}
.related-head a:hover{color:var(--ink)}
.related-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.rel-card{background:var(--paper);border:1.5px solid var(--line);border-radius:var(--radius);overflow:hidden;transition:transform .2s,box-shadow .2s,border-color .2s;display:block}
.rel-card:hover{transform:translateY(-3px);box-shadow:var(--shadow);border-color:var(--ink)}
.rel-thumb{aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;font-size:2rem;position:relative;overflow:hidden}
.rel-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.rel-body{padding:1rem}
.rel-cat{font-family:var(--fm);font-size:.62rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.rel-title{font-family:var(--fh);font-size:.98rem;font-weight:700;line-height:1.2;letter-spacing:-.01em;margin-top:.3rem}
.rel-client{font-size:.78rem;color:var(--muted);margin-top:.2rem}

@media(max-width:960px){
  .case-grid{grid-template-columns:1fr}
  .case-side{position:static}
}
@media(max-width:768px){
  .case-cover{aspect-ratio:4/3}
  .case-cover-ph{flex-direction:column;align-items:flex-start;justify-content:flex-end;gap:1rem}
  .case-cover-icon{position:absolute;right:1rem;top:1rem;font-size:3.5rem}
  .related-grid{grid-template-columns:1fr 1fr}
  .case-gallery-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:520px){
  .case-hero{padding-top:calc(var(--nav-h) + 2rem)}
  .related-grid,.case-gallery-grid{grid-template-columns:1fr}
  .case-main{padding-top:3rem}
}
@media(prefers-reduced-motion:reduce){
  .rel-card{transition:none}
}
</style>
</head>
<body>

<?php $activeNav = 'portfolio'; require VIEW_PATH . '/partials/nav.php'; ?>

<main id="main">
  <!-- ── HERO ─────────────────────────────────────────────── -->
  <header class="case-hero">
    <div class="case-wrap">
      <nav class="case-crumb" aria-label="Breadcrumb">
        <a href="<?= APP_URL ?>/">Beranda</a>
        <span>/</span>
        <a href="<?= APP_URL ?>/portfolio">Portfolio</a>
        <span>/</span>
        <span aria-current="page"><?= e($portfolio['title']) ?></span>
      </nav>

      <div class="case-eyebrow">
        <?php if (!empty($portfolio['category_name'])): ?>
          <span class="case-cat"><?= e($portfolio['category_name']) ?></span>
        <?php endif; ?>
        <?php if (!empty($portfolio['is_sample'])): ?>
          <span class="sample-flag" title="Konten contoh, bukan proyek klien asli">Sampel</span>
        <?php endif; ?>
      </div>

      <h1 class="case-title"><?= e($portfolio['title']) ?></h1>

      <?php if (!empty($portfolio['description'])): ?>
        <p class="case-lead"><?= e($portfolio['description']) ?></p>
      <?php endif; ?>

      <?php if ($metaItems): ?>
        <dl class="case-meta">
          <?php foreach ($metaItems as $label => $value): ?>
            <div>
              <dt><?= e($label) ?></dt>
              <dd><?= e((string) $value) ?></dd>
            </div>
          <?php endforeach; ?>
          <?php if ($portfolio['services_list']): ?>
            <div>
              <dt>Layanan</dt>
              <dd><?= e(implode(' · ', $portfolio['services_list'])) ?></dd>
            </div>
          <?php endif; ?>
        </dl>
      <?php endif; ?>

      <div class="case-cover">
        <?php if ($coverUrl !== ''): ?>
          <img src="<?= e($coverUrl) ?>" alt="<?= e($portfolio['title']) ?>" loading="eager">
        <?php else: ?>
          <div class="case-cover-ph" style="--bg:<?= e($cover['bg']) ?>;--fg:<?= e($cover['fg']) ?>">
            <div>
              <div class="case-cover-cat"><?= e($portfolio['category_name'] ?? 'Portfolio') ?> · <?= e((string) ($portfolio['year'] ?? '')) ?></div>
              <div class="case-cover-name"><?= e($portfolio['title']) ?></div>
            </div>
            <div class="case-cover-icon" aria-hidden="true"><?= $icon ?></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- ── BODY ─────────────────────────────────────────────── -->
  <section class="case-main">
    <div class="case-wrap case-grid">
      <div class="case-flow">
        <?php foreach ($sections as $section):
          $paras = $paragraphs($section['text']);
          if (!$paras) continue;
        ?>
          <section class="case-sec">
            <div class="case-sec-head">
              <span class="case-sec-num"><?= $section['num'] ?></span>
              <h2><?= e($section['label']) ?></h2>
            </div>
            <?php foreach ($paras as $para): ?>
              <p><?= nl2br(e($para)) ?></p>
            <?php endforeach; ?>
          </section>
        <?php endforeach; ?>

        <?php if ($resultText !== ''): ?>
          <section class="case-result">
            <div class="case-sec-head">
              <span class="case-sec-num">03</span>
              <h2>Hasil</h2>
            </div>
            <?php foreach ($paragraphs($resultText) as $para): ?>
              <p><?= nl2br(e($para)) ?></p>
            <?php endforeach; ?>
            <?php if (!empty($portfolio['is_sample'])): ?>
              <div class="case-sample-note">Contoh konten — angka dan hasil bersifat ilustratif.</div>
            <?php endif; ?>
          </section>
        <?php endif; ?>

        <?php if ($portfolio['tags']): ?>
          <div class="case-tags" aria-label="Tag proyek">
            <?php foreach (array_filter(array_map('trim', explode(',', $portfolio['tags']))) as $tag): ?>
              <span class="case-tag"><?= e($tag) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Galeri -->
        <?php if ($portfolio['gallery'] || !empty($portfolio['is_sample'])): ?>
          <div class="case-gallery">
            <div class="case-gallery-head">
              <h2>Galeri Visual</h2>
              <span><?= $portfolio['gallery'] ? count($portfolio['gallery']) . ' aset' : 'Contoh tata letak' ?></span>
            </div>
            <div class="case-gallery-grid">
              <?php if ($portfolio['gallery']): ?>
                <?php foreach ($portfolio['gallery'] as $img): ?>
                  <figure class="case-shot">
                    <img src="<?= APP_URL ?>/uploads/<?= e(ltrim($img, '/')) ?>" alt="<?= e($portfolio['title']) ?> — visual" loading="lazy">
                  </figure>
                <?php endforeach; ?>
              <?php else: ?>
                <?php
                $shots = ['Identitas', 'Aplikasi', 'Kemasan'];
                foreach ($shots as $i => $shot): ?>
                  <div class="case-shot" aria-hidden="true">
                    <div class="case-shot-ph">
                      <b>Visual <?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></b>
                      <span><?= e($shot) ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <aside class="case-side" aria-label="Ringkasan proyek">
        <div class="case-side-card">
          <h2>Punya Proyek Serupa?</h2>
          <p>Ceritakan kebutuhan Anda. Kami bantu petakan langkah, estimasi waktu, dan kisaran biaya — tanpa komitmen.</p>

          <ul class="case-side-list">
            <?php if (!empty($portfolio['client_name'])): ?>
              <li><span>Klien</span><b><?= e($portfolio['client_name']) ?></b></li>
            <?php endif; ?>
            <?php if (!empty($portfolio['year'])): ?>
              <li><span>Tahun</span><b><?= e((string) $portfolio['year']) ?></b></li>
            <?php endif; ?>
            <?php if (!empty($portfolio['duration'])): ?>
              <li><span>Durasi</span><b><?= e($portfolio['duration']) ?></b></li>
            <?php endif; ?>
            <?php if (!empty($portfolio['category_name'])): ?>
              <li><span>Kategori</span><b><?= e($portfolio['category_name']) ?></b></li>
            <?php endif; ?>
          </ul>

          <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin konsultasi proyek seperti "' . $portfolio['title'] . '".')) ?>"
             class="btn btn-primary" target="_blank" rel="noopener" data-cta="case-wa">
            💬 Konsultasi via WhatsApp
          </a>
          <div style="height:.6rem"></div>
          <a href="<?= APP_URL ?>/contact#konsultasi" class="btn btn-outline btn-block" data-cta="case-form">
            Isi Form Konsultasi
          </a>

          <a href="<?= APP_URL ?>/portfolio" class="case-back">← Kembali ke Portfolio</a>
        </div>
      </aside>
    </div>
  </section>

  <!-- ── RELATED ──────────────────────────────────────────── -->
  <?php if ($related): ?>
    <section class="case-related">
      <div class="case-wrap">
        <div class="related-head">
          <h2>Karya Lainnya</h2>
          <a href="<?= APP_URL ?>/portfolio">Semua Portfolio →</a>
        </div>
        <div class="related-grid">
          <?php foreach ($related as $rel):
            $relSlug  = $rel['category_slug'] ?? '';
            $relColor = $catColors[$relSlug] ?? ['bg' => '#E4E9E5', 'fg' => '#122A1C'];
            $relIcon  = $catIcons[$relSlug] ?? '★';
          ?>
            <a class="rel-card" href="<?= APP_URL ?>/portfolio/<?= e(rawurlencode($rel['slug'])) ?>">
              <div class="rel-thumb" style="background:<?= e($relColor['bg']) ?>;color:<?= e($relColor['fg']) ?>">
                <?php if ($rel['cover_image']): ?>
                  <img src="<?= APP_URL ?>/uploads/<?= e($rel['cover_image']) ?>" alt="<?= e($rel['title']) ?>" loading="lazy">
                <?php else: ?>
                  <span aria-hidden="true"><?= $relIcon ?></span>
                <?php endif; ?>
              </div>
              <div class="rel-body">
                <div class="rel-cat"><?= e($rel['category_name'] ?? 'Portfolio') ?></div>
                <div class="rel-title"><?= e($rel['title']) ?></div>
                <div class="rel-client"><?= e($rel['client_name'] ?? '') ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>

<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="cta-strip">
  <div class="cta-strip-inner reveal">
    <div>
      <h2>Proyek Anda Bisa<br>Jadi Karya <em>Selanjutnya</em></h2>
      <p>Konsultasikan kebutuhan visual bisnis Anda. Gratis, tanpa komitmen, dan kami siap membantu.</p>
    </div>
    <div class="cta-strip-btns">
      <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin konsultasi proyek desain.')) ?>" class="btn-cta-p" target="_blank" rel="noopener" data-cta="case-cta-wa">💬 Konsultasi Gratis →</a>
      <a href="<?= APP_URL ?>/services" class="btn-cta-g" data-cta="case-cta-services">Lihat Layanan</a>
    </div>
  </div>
</section>

<?php require VIEW_PATH . '/partials/footer.php'; ?>

<script>
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
