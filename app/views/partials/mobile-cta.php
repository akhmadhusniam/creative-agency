<?php
/**
 * partials/mobile-cta.php
 * Sticky CTA bar (mobile) — satu aksi utama "Mulai Proyek".
 * Variabel opsional:
 * - $mobileCtaLabel (string)
 * - $mobileCtaUrl   (string)
 * - $hideMobileCta  (bool)
 */
$mobileCtaLabel = $mobileCtaLabel ?? 'Mulai Proyek';
$mobileCtaUrl = $mobileCtaUrl ?? site_start_project_url();
$hideMobileCta = $hideMobileCta ?? false;

if ($hideMobileCta) {
    echo '<style>@media (max-width: 768px) { body { padding-bottom: 0 !important; } }</style>';
    return;
}
?>
<script>
/* Sembunyikan sticky CTA saat CTA utama di hero masih terlihat. */
(function () {
  var heroCta = document.querySelector('.hero-actions [data-cta="hero-start-project"]');
  if (!heroCta) return;
  var r = heroCta.getBoundingClientRect();
  if (r.bottom > 0 && r.top < window.innerHeight) {
    document.body.classList.add('hero-cta-inview');
  }
})();
</script>
<div class="mobile-cta-bar" role="group" aria-label="Aksi cepat">
  <a href="<?= e($mobileCtaUrl) ?>" class="mcb mcb-consult" data-cta="mobile-start-project">
    <?= e($mobileCtaLabel) ?> →
  </a>
</div>
