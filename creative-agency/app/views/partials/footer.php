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
      <a href="#" title="Instagram">ig</a>
      <a href="https://wa.me/62812345678" title="WhatsApp" target="_blank" rel="noopener">wa</a>
      <a href="#" title="LinkedIn">in</a>
      <a href="#" title="YouTube">yt</a>
    </div>
  </div>
</footer>

<script>
/* ── Nav scroll ─────────────────────────────────────────── */
const _nav = document.getElementById('main-nav');
if (_nav) {
  window.addEventListener('scroll', () => {
    _nav.classList.toggle('scrolled', window.scrollY > 50);
  }, { passive: true });
}
/* ── Hamburger ──────────────────────────────────────────── */
const _hbg = document.getElementById('hamburger');
if (_hbg) {
  _hbg.addEventListener('click', function () {
    const nl = document.querySelector('.nav-links');
    const nr = document.querySelector('.nav-right');
    const open = nl.style.display === 'flex';
    nl.style.cssText = open ? '' : 'display:flex;flex-direction:column;position:fixed;top:var(--nav-h);left:0;right:0;background:var(--paper);padding:1.5rem 2rem;gap:1.25rem;border-bottom:1px solid var(--cream);z-index:190';
    if (nr) nr.style.cssText = open ? '' : 'display:flex;flex-direction:column;position:fixed;top:calc(var(--nav-h) + 9rem);left:2rem;right:2rem;z-index:190;gap:.75rem';
  });
}
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
