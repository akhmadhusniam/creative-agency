<?php
/**
 * partials/nav.php — navigasi global
 * Variabel yang dibutuhkan: $activeNav (string: 'services'|'portfolio'|'about'|'contact')
 */
$activeNav = $activeNav ?? '';
?>
<nav class="nav" id="main-nav">
  <a href="<?= APP_URL ?>/" class="nav-logo">creative<span>.</span></a>
  <ul class="nav-links">
    <li><a href="<?= APP_URL ?>/services"  <?= $activeNav==='services'  ? 'class="active"' : '' ?>>Layanan</a></li>
    <li><a href="<?= APP_URL ?>/portfolio" <?= $activeNav==='portfolio' ? 'class="active"' : '' ?>>Portfolio</a></li>
    <li><a href="<?= APP_URL ?>/about"     <?= $activeNav==='about'     ? 'class="active"' : '' ?>>Tentang</a></li>
    <li><a href="<?= APP_URL ?>/contact"   <?= $activeNav==='contact'   ? 'class="active"' : '' ?>>Kontak</a></li>
  </ul>
  <div class="nav-right">
    <?php if (Session::isLoggedIn()): ?>
      <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline">Dashboard</a>
    <?php else: ?>
      <a href="<?= APP_URL ?>/login"    class="btn btn-outline">Masuk</a>
      <a href="<?= APP_URL ?>/register" class="btn btn-primary">Mulai Proyek →</a>
    <?php endif; ?>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
