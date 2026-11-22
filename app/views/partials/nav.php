<?php
/**
 * partials/nav.php — navigasi global
 * Variabel yang dibutuhkan: $activeNav (string: 'services'|'portfolio'|'about'|'contact')
 */
$activeNav = $activeNav ?? '';
?>
<a href="#main" class="skip-link">Lewati ke konten utama</a>
<nav class="nav" id="main-nav" aria-label="Navigasi utama">
  <a href="<?= APP_URL ?>/" class="nav-logo" aria-label="Creative Studio — beranda">creative<span>.</span></a>
  <ul class="nav-links">
    <li><a href="<?= APP_URL ?>/services"  <?= $activeNav==='services'  ? 'class="active" aria-current="page"' : '' ?>>Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio" <?= $activeNav==='portfolio' ? 'class="active" aria-current="page"' : '' ?>>Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/about"     <?= $activeNav==='about'     ? 'class="active" aria-current="page"' : '' ?>>Tentang</a></li>
    <li><a href="<?= APP_URL ?>/contact"   <?= $activeNav==='contact'   ? 'class="active" aria-current="page"' : '' ?>>Kontak</a></li>
  </ul>
  <div class="nav-right">
    <?php if (Session::isLoggedIn()): ?>
      <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline btn-sm">Dashboard</a>
    <?php endif; ?>
    <?php if ($activeNav !== 'contact'): ?>
      <a href="<?= e(site_start_project_url()) ?>"
         class="btn btn-primary btn-sm" data-cta="nav-start-project">Mulai Proyek</a>
    <?php endif; ?>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="main-nav">
    <span></span><span></span><span></span>
  </button>
</nav>
