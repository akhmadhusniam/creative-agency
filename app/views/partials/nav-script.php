<script>
// partials/nav-script.php — toggle menu mobile, dipakai di semua halaman publik
// supaya perilakunya konsisten (sebelumnya cuma home.php yang punya ini).
(function () {
  var hamburger = document.getElementById('hamburger');
  var nav = document.getElementById('main-nav');
  if (!hamburger || !nav) return;

  hamburger.addEventListener('click', function () {
    var isOpen = nav.classList.toggle('mobile-open');
    hamburger.classList.toggle('active', isOpen);
    hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });

  // Tutup menu otomatis begitu salah satu link diklik
  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function () {
      nav.classList.remove('mobile-open');
      hamburger.classList.remove('active');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  });
})();

// ── Efek magnetic di tombol utama (sesuai gaya desain baru) ──
// Nonaktif otomatis di layar sentuh / preferensi reduced-motion, biar nggak ganggu.
(function () {
  if (window.matchMedia('(hover: none)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  document.querySelectorAll('.btn-primary, .btn-cta-p, .btn-outline, .btn-cta-g').forEach(function (el) {
    el.addEventListener('mousemove', function (e) {
      var r = el.getBoundingClientRect();
      var x = e.clientX - r.left - r.width / 2;
      var y = e.clientY - r.top - r.height / 2;
      el.style.transform = 'translate(' + (x * 0.12) + 'px,' + (y * 0.25) + 'px)';
    });
    el.addEventListener('mouseleave', function () {
      el.style.transform = '';
    });
  });
})();
</script>
