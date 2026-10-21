<?php
/**
 * partials/nav.php — navigasi global
 * Variabel yang dibutuhkan: $activeNav (string: 'services'|'portfolio'|'about'|'contact')
 */
$activeNav = $activeNav ?? '';
?>
<a href="#main" class="skip-link">Lewati ke konten utama</a>
<?php require VIEW_PATH . '/partials/marquee.php'; ?>
<nav class="nav" id="main-nav" aria-label="Navigasi utama">
  <a href="<?= APP_URL ?>/" class="nav-logo" aria-label="Creative Studio — beranda">creative<span>.</span></a>
  <ul class="nav-links">
    <li><a href="<?= APP_URL ?>/services"  <?= $activeNav==='services'  ? 'class="active"' : '' ?>>Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio" <?= $activeNav==='portfolio' ? 'class="active"' : '' ?>>Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/about"     <?= $activeNav==='about'     ? 'class="active"' : '' ?>>Tentang</a></li>
    <li><a href="<?= APP_URL ?>/contact"   <?= $activeNav==='contact'   ? 'class="active"' : '' ?>>Kontak</a></li>
  </ul>
  <div class="nav-right">
    <?php if (Session::isLoggedIn()): ?>
      <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline btn-sm">Dashboard</a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/login" class="btn btn-outline btn-sm">Masuk</a>
    <?php endif; ?>
    <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin konsultasi proyek desain.')) ?>"
       class="btn btn-primary btn-sm" target="_blank" rel="noopener" data-cta="nav-consult">Konsultasi</a>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="main-nav">
    <span></span><span></span><span></span>
  </button>
</nav>
