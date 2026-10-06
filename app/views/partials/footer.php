<?php
/**
 * partials/footer.php — footer global
 */
?>
<footer class="footer">
  <div class="footer-top">
    <div>
      <div class="f-logo">creative<span>.</span></div>
      <div class="f-tagline">Mitra desain profesional untuk bisnis yang ingin tampil lebih kuat dan berkesan.</div>
    </div>
    <div class="f-col">
      <h4>Layanan</h4>
      <ul>
        <li><a href="<?= APP_URL ?>/services">Branding &amp; Identity</a></li>
        <li><a href="<?= APP_URL ?>/services">UI/UX Design</a></li>
        <li><a href="<?= APP_URL ?>/services">Social Media Kit</a></li>
        <li><a href="<?= APP_URL ?>/services">Motion &amp; Video</a></li>
        <li><a href="<?= APP_URL ?>/services">Print &amp; Packaging</a></li>
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
/* ── Scroll reveal ──────────────────────────────────────── */
if (typeof IntersectionObserver !== 'undefined') {
  const _obs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => e.target.classList.add('visible'), i * 80);
        _obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.07 });
  document.querySelectorAll('.reveal').forEach(el => _obs.observe(el));
}
</script>
