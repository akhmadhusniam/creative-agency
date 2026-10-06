<?php
/**
 * partials/whatsapp-float.php
 * Tombol WA mengambang, tampil di semua halaman publik.
 * Butuh site_wa_url() dari helpers/Site.php — pastikan controller sudah require itu.
 */
?>
<style>
.wa-float {
  position: fixed; bottom: 22px; right: 22px; z-index: 500;
  display: flex; align-items: center; justify-content: center;
  width: 56px; height: 56px; border-radius: 50%;
  background: #25D366; box-shadow: 0 4px 16px rgba(0,0,0,.22);
  transition: transform .2s ease, box-shadow .2s ease;
}
.wa-float:hover { transform: scale(1.08); box-shadow: 0 6px 20px rgba(0,0,0,.28); }
.wa-float svg { width: 28px; height: 28px; }
.wa-float-pulse {
  position: absolute; inset: 0; border-radius: 50%;
  background: #25D366; opacity: .55;
  animation: wa-pulse 2.2s ease-out infinite;
}
@keyframes wa-pulse {
  0%   { transform: scale(1);   opacity: .5; }
  100% { transform: scale(1.9); opacity: 0;  }
}
@media (max-width: 480px) {
  .wa-float { width: 50px; height: 50px; bottom: 16px; right: 16px; }
  .wa-float svg { width: 25px; height: 25px; }
}
</style>

<a href="<?= e(site_wa_url('Halo, saya ingin tanya-tanya soal layanan Creative Studio.')) ?>"
   class="wa-float" target="_blank" rel="noopener" aria-label="Chat via WhatsApp">
  <span class="wa-float-pulse"></span>
  <svg viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Zm5.8 14.02c-.24.68-1.4 1.32-1.93 1.4-.5.08-1.11.11-1.79-.11-.41-.13-.94-.3-1.62-.6-2.85-1.23-4.7-4.1-4.85-4.29-.14-.19-1.16-1.54-1.16-2.94 0-1.4.73-2.08 1-2.36.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.41-.07.64.48.24.58.81 2 .88 2.14.07.15.12.32.02.51-.1.19-.15.31-.29.48-.15.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.63 2.02 1.12 1 2.06 1.31 2.35 1.46.29.15.46.13.63-.05.17-.19.72-.84.92-1.13.19-.29.38-.24.64-.15.26.1 1.66.78 1.94.92.29.15.48.22.55.34.07.13.07.72-.17 1.4Z"/>
  </svg>
</a>
