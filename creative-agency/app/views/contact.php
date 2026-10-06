<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kontak — Creative Studio</title>
<meta name="description" content="Hubungi Creative Studio — konsultasi gratis untuk kebutuhan desain branding, UI/UX, social media, dan motion graphic Anda.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
/* ── Contact layout ─────────────────────────────────────── */
.contact-layout{
  display:grid;grid-template-columns:1fr 1.4fr;
  gap:5rem;padding:5rem clamp(1rem,4vw,3rem);
  max-width:1200px;margin:0 auto;
}

/* ── Info panel (left) ──────────────────────────────────── */
.contact-info{display:flex;flex-direction:column;gap:2rem}
.ci-intro{font-size:.95rem;color:var(--muted);line-height:1.85;max-width:40ch}
.ci-cards{display:flex;flex-direction:column;gap:1rem}
.ci-card{
  background:var(--white);border:1px solid var(--cream);
  border-radius:10px;padding:1.25rem 1.5rem;
  display:flex;align-items:flex-start;gap:1rem;
  transition:border-color .2s;
}
.ci-card:hover{border-color:var(--ink)}
.ci-card-icon{
  width:40px;height:40px;border-radius:8px;
  background:var(--cream);display:flex;align-items:center;
  justify-content:center;font-size:1.1rem;flex-shrink:0;
}
.ci-card h4{font-family:var(--fh);font-weight:700;font-size:.82rem;margin-bottom:.2rem}
.ci-card p{font-size:.82rem;color:var(--muted);line-height:1.5}
.ci-card a{color:var(--ink);font-weight:500;transition:color .15s}
.ci-card a:hover{color:var(--accent)}

/* WhatsApp CTA card */
.wa-card{
  background:var(--ink);border-radius:12px;padding:1.75rem;
  display:flex;align-items:center;gap:1.25rem;
  text-decoration:none;transition:background .2s;
}
.wa-card:hover{background:#1a1a1a}
.wa-icon{font-size:2rem;flex-shrink:0}
.wa-title{font-family:var(--fh);font-weight:700;color:#fff;font-size:.95rem;margin-bottom:.2rem}
.wa-sub{font-size:.78rem;color:#555}
.wa-arrow{font-size:1.25rem;color:var(--accent);margin-left:auto;flex-shrink:0}

/* Response time badge */
.resp-badge{
  display:inline-flex;align-items:center;gap:.5rem;
  background:var(--cream);border-radius:2rem;
  padding:.4rem 1rem;font-size:.78rem;color:var(--muted);
  width:fit-content;
}
.resp-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;flex-shrink:0;animation:pulse 2s infinite}

/* ── Form panel (right) ─────────────────────────────────── */
.contact-form-card{
  background:var(--white);border:1px solid var(--cream);
  border-radius:16px;padding:2.5rem;
}
.cf-title{font-family:var(--fh);font-weight:800;font-size:1.4rem;margin-bottom:.4rem}
.cf-sub{font-size:.875rem;color:var(--muted);margin-bottom:2rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.1rem}
label{display:block;font-size:.78rem;font-weight:600;margin-bottom:.4rem;color:var(--ink)}
label .opt{color:var(--muted);font-weight:400}
input,textarea,select{
  width:100%;padding:.75rem 1rem;
  border:1.5px solid var(--cream);border-radius:6px;
  font-family:var(--fb);font-size:.875rem;
  background:var(--white);color:var(--ink);
  transition:border-color .15s,box-shadow .15s;
  outline:none;
}
input:focus,textarea:focus,select:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(13,13,13,.06)}
input.err,textarea.err{border-color:var(--accent)}
textarea{min-height:130px;resize:vertical}
select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237A7570' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center}

.btn-submit{
  width:100%;padding:.95rem;
  background:var(--accent);color:#fff;border:none;
  border-radius:6px;font-family:var(--fh);font-weight:700;
  font-size:.95rem;letter-spacing:.04em;cursor:pointer;
  transition:background .2s,transform .15s;
  display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.btn-submit:hover{background:#a83422;transform:translateY(-1px)}
.btn-submit:active{transform:translateY(0)}

.form-privacy{font-size:.75rem;color:var(--muted);text-align:center;margin-top:.85rem;line-height:1.6}

/* ── Map placeholder ────────────────────────────────────── */
.map-section{padding:0 clamp(1rem,4vw,3rem) 5rem}
.map-inner{max-width:1200px;margin:0 auto}
.map-wrap{
  border-radius:14px;overflow:hidden;
  height:320px;background:var(--cream);
  display:flex;align-items:center;justify-content:center;
  border:1px solid rgba(0,0,0,.06);
  position:relative;
}
.map-placeholder{text-align:center;color:var(--muted)}
.map-placeholder .icon{font-size:3rem;margin-bottom:.75rem;opacity:.3}
.map-placeholder p{font-size:.85rem}
.map-placeholder a{color:var(--accent);font-weight:600}

/* ── FAQ mini ───────────────────────────────────────────── */
.faq-mini{background:var(--cream);padding:5rem clamp(1rem,4vw,3rem)}
.faq-mini-inner{max-width:800px;margin:0 auto}
.faq-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:2.5rem}
.faq-mini-item{background:var(--white);border-radius:10px;padding:1.5rem;border:1px solid rgba(0,0,0,.05)}
.faq-mini-q{font-family:var(--fh);font-weight:700;font-size:.9rem;margin-bottom:.5rem}
.faq-mini-a{font-size:.82rem;color:var(--muted);line-height:1.7}

@media(max-width:960px){.contact-layout{grid-template-columns:1fr;gap:3rem}}
@media(max-width:600px){.form-row{grid-template-columns:1fr}.faq-mini-grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<?php $activeNav = 'contact'; require VIEW_PATH . '/partials/nav.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="s-inner">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span>Kontak</span>
    </div>
    <h1>Mari <em>Bicara</em><br>tentang Proyek Anda</h1>
    <p>Konsultasi pertama selalu gratis. Ceritakan kebutuhan Anda dan kami akan bantu menentukan solusi terbaik.</p>
  </div>
</section>

<!-- MAIN CONTACT LAYOUT -->
<div class="contact-layout">

  <!-- LEFT: Info -->
  <div class="contact-info reveal">
    <div>
      <div class="eyebrow"><span class="eyebrow-line"></span>Hubungi Kami</div>
      <h2 class="stitle" style="font-size:clamp(1.5rem,2.5vw,2rem)">Ada Pertanyaan?<br>Kami Siap Membantu</h2>
      <p class="ci-intro">Tim kami aktif pada hari Senin–Jumat (09.00–18.00 WIB). Kami biasanya membalas dalam 1–3 jam kerja.</p>
    </div>

    <div class="resp-badge">
      <span class="resp-dot"></span>
      Biasanya membalas dalam 1–3 jam
    </div>

    <div class="ci-cards">
      <div class="ci-card">
        <div class="ci-card-icon">📧</div>
        <div>
          <h4>Email</h4>
          <p><a href="mailto:hello@creativestudio.id">hello@creativestudio.id</a></p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon">📱</div>
        <div>
          <h4>Telepon / WhatsApp</h4>
          <p><a href="tel:+6281234567890">+62 812-3456-7890</a></p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon">📍</div>
        <div>
          <h4>Alamat Studio</h4>
          <p>Jl. Kemang Raya No. 12B,<br>Jakarta Selatan, 12730</p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon">🕐</div>
        <div>
          <h4>Jam Operasional</h4>
          <p>Senin – Jumat: 09.00 – 18.00 WIB<br>Sabtu: 09.00 – 13.00 WIB</p>
        </div>
      </div>
    </div>

    <a href="https://wa.me/6281234567890?text=Halo+Creative+Studio%2C+saya+ingin+konsultasi+proyek+desain" class="wa-card" target="_blank" rel="noopener">
      <span class="wa-icon">💬</span>
      <div>
        <div class="wa-title">Chat via WhatsApp</div>
        <div class="wa-sub">Cara tercepat menghubungi kami</div>
      </div>
      <span class="wa-arrow">→</span>
    </a>
  </div>

  <!-- RIGHT: Form -->
  <div class="contact-form-card reveal" style="transition-delay:.12s">
    <div class="cf-title">Kirim Pesan</div>
    <div class="cf-sub">Isi form di bawah dan kami akan segera menghubungi Anda.</div>

    <?php if ($ok = Session::getFlash('success')): ?>
      <div class="alert alert-success" style="margin-bottom:1.5rem">✓ <?= e($ok) ?></div>
    <?php endif; ?>

    <?php $errors = Session::getFlash('errors', []); $old = Session::getFlash('old', []); ?>

    <form method="POST" action="<?= APP_URL ?>/contact" id="contact-form">
      <?= csrfField() ?>

      <div class="form-row">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name"
                 value="<?= e($old['name'] ?? (Session::isLoggedIn() ? Session::get('user_name') : '')) ?>"
                 class="<?= isset($errors['name']) ? 'err' : '' ?>"
                 placeholder="Nama Anda" required>
          <?php if (!empty($errors['name'])): ?>
            <div class="field-error"><?= e($errors['name'][0]) ?></div>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
                 value="<?= e($old['email'] ?? (Session::isLoggedIn() ? Session::get('user_email') : '')) ?>"
                 class="<?= isset($errors['email']) ? 'err' : '' ?>"
                 placeholder="nama@email.com" required>
          <?php if (!empty($errors['email'])): ?>
            <div class="field-error"><?= e($errors['email'][0]) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="phone">No. WhatsApp <span class="opt">(opsional)</span></label>
          <input type="tel" id="phone" name="phone"
                 value="<?= e($old['phone'] ?? '') ?>"
                 placeholder="+62 812...">
        </div>
        <div class="form-group">
          <label for="budget">Estimasi Budget</label>
          <select id="budget" name="budget">
            <option value="" disabled selected>Pilih range budget</option>
            <option value="<1jt" <?= ($old['budget']??'')==='<1jt' ? 'selected' : '' ?>>Di bawah Rp 1.000.000</option>
            <option value="1-3jt" <?= ($old['budget']??'')==='1-3jt' ? 'selected' : '' ?>>Rp 1.000.000 – 3.000.000</option>
            <option value="3-5jt" <?= ($old['budget']??'')==='3-5jt' ? 'selected' : '' ?>>Rp 3.000.000 – 5.000.000</option>
            <option value="5-10jt" <?= ($old['budget']??'')==='5-10jt' ? 'selected' : '' ?>>Rp 5.000.000 – 10.000.000</option>
            <option value=">10jt" <?= ($old['budget']??'')=='>10jt' ? 'selected' : '' ?>>Di atas Rp 10.000.000</option>
            <option value="diskusi" <?= ($old['budget']??'')==='diskusi' ? 'selected' : '' ?>>Ingin diskusi dulu</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="subject">Jenis Layanan yang Dibutuhkan</label>
        <select id="subject" name="subject">
          <option value="" disabled selected>Pilih layanan</option>
          <option value="Branding & Identity" <?= ($old['subject']??'')==='Branding & Identity' ? 'selected' : '' ?>>Branding &amp; Identity</option>
          <option value="UI/UX Design" <?= ($old['subject']??'')==='UI/UX Design' ? 'selected' : '' ?>>UI/UX Design</option>
          <option value="Social Media Kit" <?= ($old['subject']??'')==='Social Media Kit' ? 'selected' : '' ?>>Social Media Kit</option>
          <option value="Motion & Video" <?= ($old['subject']??'')==='Motion & Video' ? 'selected' : '' ?>>Motion &amp; Video</option>
          <option value="Print Design" <?= ($old['subject']??'')==='Print Design' ? 'selected' : '' ?>>Print Design</option>
          <option value="Lainnya" <?= ($old['subject']??'')==='Lainnya' ? 'selected' : '' ?>>Lainnya / Belum Tahu</option>
        </select>
        <?php if (!empty($errors['subject'])): ?>
          <div class="field-error"><?= e($errors['subject'][0]) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="body">Ceritakan Proyek Anda</label>
        <textarea id="body" name="body"
                  class="<?= isset($errors['body']) ? 'err' : '' ?>"
                  placeholder="Ceritakan tentang bisnis Anda, kebutuhan desain, target audiens, timeline, dan hal lain yang relevan..."
                  required><?= e($old['body'] ?? '') ?></textarea>
        <?php if (!empty($errors['body'])): ?>
          <div class="field-error"><?= e($errors['body'][0]) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-submit" id="btn-submit">
        <span id="btn-text">Kirim Pesan →</span>
        <span id="btn-loader" style="display:none">Mengirim...</span>
      </button>
      <p class="form-privacy">Dengan mengirim pesan, Anda menyetujui bahwa data Anda digunakan untuk keperluan komunikasi. Kami tidak menjual atau membagikan data Anda kepada pihak ketiga.</p>
    </form>
  </div>

</div><!-- /.contact-layout -->

<!-- MAP -->
<div class="map-section">
  <div class="map-inner reveal">
    <div class="map-wrap">
      <!-- Ganti dengan embed Google Maps: iframe src="https://maps.google.com/..." -->
      <div class="map-placeholder">
        <div class="icon">📍</div>
        <p>Jl. Kemang Raya No. 12B, Jakarta Selatan<br>
        <a href="https://maps.google.com/?q=Kemang+Raya+Jakarta" target="_blank" rel="noopener">Buka di Google Maps →</a></p>
      </div>
    </div>
  </div>
</div>

<!-- FAQ MINI -->
<section class="faq-mini">
  <div class="faq-mini-inner reveal">
    <div style="text-align:center">
      <div class="eyebrow" style="justify-content:center"><span class="eyebrow-line"></span>Pertanyaan Umum<span class="eyebrow-line"></span></div>
      <h2 class="stitle" style="text-align:center;font-size:clamp(1.4rem,2.5vw,2rem)">Sebelum Menghubungi Kami</h2>
    </div>
    <div class="faq-mini-grid">
      <?php
      $faqs = [
        ['Apakah konsultasi pertama gratis?','Ya, 100% gratis dan tanpa komitmen. Kami ingin memahami kebutuhan Anda terlebih dahulu sebelum memberikan penawaran.'],
        ['Berapa lama proses pengerjaan?','Bergantung pada paket dan kompleksitas proyek — mulai dari 3 hari (social media kit) hingga 14 hari (full brand system). Semua tertera di halaman layanan.'],
        ['Bagaimana sistem pembayarannya?','Kami menggunakan sistem DP di muka, dengan metode pembayaran yang beragam: transfer bank, QRIS, GoPay, OVO, dan kartu kredit via Midtrans.'],
        ['Apakah bisa revisi?','Ya. Setiap paket sudah mencakup revisi. Jumlah revisi bergantung pada paket yang dipilih, dan komunikasi selalu terbuka selama proses.'],
        ['Apakah ada garansi kepuasan?','Kami berkomitmen untuk terus bekerja hingga Anda puas dengan hasilnya. Jika ada ketidaksesuaian dengan brief, kami wajib memperbaikinya.'],
        ['Apakah bisa custom paket?','Tentu. Hubungi kami dan ceritakan kebutuhan spesifik Anda — kami akan siapkan penawaran yang disesuaikan.'],
      ];
      foreach ($faqs as [$q,$a]): ?>
        <div class="faq-mini-item">
          <div class="faq-mini-q"><?= $q ?></div>
          <div class="faq-mini-a"><?= $a ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require VIEW_PATH . '/partials/footer.php'; ?>

<script>
// Submit loading state
document.getElementById('contact-form')?.addEventListener('submit', function () {
  const btn    = document.getElementById('btn-submit');
  const text   = document.getElementById('btn-text');
  const loader = document.getElementById('btn-loader');
  btn.disabled      = true;
  text.style.display   = 'none';
  loader.style.display = 'inline';
  btn.style.background = '#a83422';
});

// Reveal
const obs = new IntersectionObserver(entries => {
  entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*80);obs.unobserve(e.target);}});
},{threshold:.07});
document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
</script>
</body>
</html>
