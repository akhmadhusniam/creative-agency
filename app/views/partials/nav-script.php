<script>
// partials/nav-script.php — toggle menu mobile, dipakai di semua halaman publik.
(function () {
  var hamburger = document.getElementById('hamburger');
  var nav = document.getElementById('main-nav');
  if (!hamburger || !nav) return;

  function setOpen(open) {
    nav.classList.toggle('mobile-open', open);
    hamburger.classList.toggle('active', open);
    hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
    hamburger.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    document.body.style.overflow = open ? 'hidden' : '';
  }

  hamburger.addEventListener('click', function () {
    setOpen(!nav.classList.contains('mobile-open'));
  });

  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () { setOpen(false); });
  });

  // Tutup dengan Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && nav.classList.contains('mobile-open')) {
      setOpen(false);
      hamburger.focus();
    }
  });

  // Klik di luar menu menutup drawer
  document.addEventListener('click', function (e) {
    if (!nav.classList.contains('mobile-open')) return;
    if (nav.contains(e.target) || hamburger.contains(e.target)) return;
    setOpen(false);
  });

  // Reset saat layar dibesarkan
  window.addEventListener('resize', function () {
    if (window.innerWidth > 768 && nav.classList.contains('mobile-open')) setOpen(false);
  });
})();

// ── Tandai CTA yang diklik (siap dihubungkan ke analytics) ──
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-cta]');
  if (!el) return;
  document.dispatchEvent(new CustomEvent('cta:click', {
    detail: { name: el.getAttribute('data-cta'), href: el.getAttribute('href') || '' }
  }));
});
</script>
