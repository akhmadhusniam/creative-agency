<?php
$isProjectIntent = $isProjectIntent ?? false;
$requestedService = $requestedService ?? null;
$isSent = $isSent ?? false;
$isSentProject = $isSentProject ?? false;
$isProjectContext = $isProjectIntent || $requestedService;
$hideFooterCta = true;
$hideMobileCta = true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $isSent ? ($isSentProject ? 'Brief Terkirim' : 'Pesan Terkirim') : ($isProjectContext ? 'Mulai Proyek' : 'Kontak') ?> — Creative Studio</title>
<meta name="description" content="Hubungi Creative Studio — konsultasi gratis untuk kebutuhan desain branding, UI/UX, social media, dan motion graphic Anda.">
<link rel="canonical" href="<?= APP_URL ?>/contact">
<?php if ($isSent): ?><meta name="robots" content="noindex,follow"><?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
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
.ci-card h3{font-family:var(--fh);font-weight:700;font-size:.82rem;margin-bottom:.2rem}
.ci-card p{font-size:.82rem;color:var(--muted);line-height:1.5}
.ci-card a{color:var(--ink);font-weight:500;text-decoration:underline;text-underline-offset:2px;transition:color .15s}
.ci-card a:hover{color:var(--accent2)}

/* WhatsApp CTA card — secondary path */
.wa-card{
  background:var(--cream);border:1px solid rgba(18,42,28,.12);
  border-radius:12px;padding:1.75rem;
  display:flex;align-items:center;gap:1.25rem;
  text-decoration:none;transition:background .2s,border-color .2s;
}
.wa-card:hover{background:var(--white);border-color:var(--ink)}
.wa-icon{font-size:2rem;flex-shrink:0}
.wa-title{font-family:var(--fh);font-weight:700;color:var(--ink);font-size:.95rem;margin-bottom:.2rem}
.wa-sub{font-size:.78rem;color:var(--muted)}
.wa-arrow{font-size:1.25rem;color:var(--ink);margin-left:auto;flex-shrink:0}

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
  scroll-margin-top:calc(var(--nav-h) + 1rem);
}
.cf-title{font-family:var(--fh);font-weight:800;font-size:1.4rem;margin-bottom:.4rem}
.cf-sub{font-size:.875rem;color:var(--muted);margin-bottom:2rem}

/* Post-submit confirmation */
.sent-state{text-align:center;padding:.25rem 0}
.sent-icon{width:56px;height:56px;border-radius:50%;background:var(--accent);border:2px solid var(--ink);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;margin:0 auto 1.1rem;color:var(--ink)}
.sent-title{font-family:var(--fh);font-weight:800;font-size:1.5rem;margin:0 0 .45rem}
.sent-lead{font-size:.9rem;color:var(--muted);line-height:1.75;max-width:50ch;margin:0 auto 1.5rem}
.sent-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;background:var(--cream);border:1px solid rgba(18,42,28,.1);border-radius:10px;padding:1rem;text-align:left;margin-bottom:1.5rem}
.sent-summary span{display:block;font-family:var(--fm);font-size:.62rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:.2rem}
.sent-summary strong{display:block;font-size:.82rem;color:var(--ink);line-height:1.4}
.sent-steps{list-style:none;text-align:left;margin:0 0 1.5rem;padding:0;display:flex;flex-direction:column;gap:.85rem}
.sent-steps li{display:flex;gap:.75rem;align-items:flex-start}
.sent-step-num{width:24px;height:24px;border-radius:50%;background:var(--ink);color:var(--accent);font-family:var(--fm);font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem}
.sent-steps b{display:block;font-size:.82rem;color:var(--ink);margin-bottom:.1rem}
.sent-steps span{display:block;font-size:.78rem;color:var(--muted);line-height:1.55}
.sent-actions{display:flex;flex-direction:column;gap:.65rem}
.sent-actions .btn-submit{text-decoration:none}
.sent-secondary{font-family:var(--fm);font-size:.72rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;color:var(--muted);text-decoration:none;padding:.55rem;transition:color .15s}
.sent-secondary:hover{color:var(--ink)}
.sent-note{font-size:.75rem;color:var(--muted);margin-top:1rem;line-height:1.6}
.sent-note a{color:var(--ink);text-decoration:underline;text-underline-offset:2px}
.quote-context{
  background:var(--cream);border:1px solid rgba(18,42,28,.12);
  border-radius:10px;padding:1rem 1.15rem;margin-bottom:1.5rem;
}
.qc-kicker{font-family:var(--fm);font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:.25rem}
.qc-title{font-family:var(--fh);font-weight:800;font-size:1rem;color:var(--ink)}
.qc-meta{font-size:.8rem;color:var(--muted);margin-top:.2rem}
.qc-note{font-size:.76rem;color:var(--muted);line-height:1.6;margin-top:.55rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.1rem}
label{display:block;font-size:.78rem;font-weight:600;margin-bottom:.4rem;color:var(--ink)}
label .opt{color:var(--muted);font-weight:400}
input,textarea,select{
  width:100%;min-height:46px;padding:.75rem 1rem;
  border:1.5px solid var(--cream);border-radius:6px;
  font-family:var(--fb);font-size:.875rem;
  background:var(--white);color:var(--ink);
  transition:border-color .15s,box-shadow .15s;
  outline:none;
}
input:focus,textarea:focus,select:focus{border-color:var(--ink);box-shadow:0 0 0 3px rgba(13,13,13,.06)}
input.err,textarea.err,select.err{border-color:#B91C1C;box-shadow:0 0 0 3px rgba(185,28,28,.1)}
textarea{min-height:130px;resize:vertical}
select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237A7570' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center}

.btn-submit{
  width:100%;padding:.95rem;
  background:var(--accent);color:var(--ink);border:1.5px solid var(--ink);
  border-radius:10px;font-family:var(--fm);font-weight:700;text-transform:uppercase;
  font-size:.85rem;letter-spacing:.03em;cursor:pointer;
  transition:background .15s,color .15s,box-shadow .15s;
  display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.btn-submit:hover{background:var(--ink);color:var(--accent);box-shadow:var(--shadow-sm)}
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
.map-placeholder a{color:#2F5D14;font-weight:600;text-decoration:underline;text-underline-offset:2px}

/* ── FAQ mini ───────────────────────────────────────────── */
.faq-mini{background:var(--cream);padding:5rem clamp(1rem,4vw,3rem)}
.faq-mini-inner{max-width:800px;margin:0 auto}
.faq-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:2.5rem}
.faq-mini-item{background:var(--white);border-radius:10px;padding:1.5rem;border:1px solid rgba(0,0,0,.05)}
.faq-mini-q{font-family:var(--fh);font-weight:700;font-size:.9rem;margin-bottom:.5rem}
.faq-mini-a{font-size:.82rem;color:var(--muted);line-height:1.7}

@media(max-width:960px){
  .contact-layout{grid-template-columns:1fr;gap:2.5rem}
  .contact-form-card{order:-1}
}
@media(max-width:600px){
  .contact-layout{padding-top:1.25rem;padding-bottom:3.5rem}
  .contact-form-card{padding:1.5rem}
  .cf-sub{margin-bottom:1.25rem}
  .form-row{grid-template-columns:1fr}
  .faq-mini-grid{grid-template-columns:1fr}
  .sent-state{text-align:left}
  .sent-icon{margin-left:0}
  .sent-lead{margin-left:0}
  .sent-summary{grid-template-columns:1fr;gap:.65rem}
}
</style>
</head>
<body>

<?php
$activeNav = 'contact';
$sEmail = site_setting('site_email');
$sPhone = site_setting('site_phone');
$sAddr  = site_setting('site_address');
$sHours = site_setting('site_hours_office');
$sWaHrs = site_setting('site_hours_wa');
$sMeet  = site_setting('site_meeting_url');
require VIEW_PATH . '/partials/nav.php';
?>

<!-- PAGE HERO -->
<section class="page-hero" id="main">
  <div class="s-inner">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span><?= $isSent ? 'Kontak' : ($isProjectContext ? 'Mulai Proyek' : 'Kontak') ?></span>
    </div>
    <h1><?= $isSent ? 'Terima <em>Kasih</em>' : 'Mari <em>Bicara</em><br>tentang Proyek Anda' ?></h1>
    <p><?= $isSent
        ? 'Tim kami akan meninjau kebutuhan Anda dan menghubungi dalam ≤24 jam. Tidak perlu mengirim ulang.'
        : 'Konsultasi pertama selalu gratis. Ceritakan kebutuhan Anda dan kami akan bantu menentukan solusi terbaik.' ?></p>
  </div>
</section>

<!-- MAIN CONTACT LAYOUT -->
<div class="contact-layout" id="konsultasi">

  <!-- LEFT: Info -->
  <div class="contact-info reveal">
    <div>
      <div class="eyebrow"><span class="eyebrow-line"></span>Hubungi Kami</div>
      <h2 class="stitle" style="font-size:clamp(1.5rem,2.5vw,2rem)">Ada Pertanyaan?<br>Kami Siap Membantu</h2>
      <p class="ci-intro">Tim kami aktif <?= e($sHours) ?>. Target respons ≤24 jam; biasanya kami membalas dalam 1–3 jam kerja.</p>
    </div>

    <div class="resp-badge">
      <span class="resp-dot"></span>
      Target respons ≤24 jam
    </div>

    <div class="ci-cards">
      <div class="ci-card">
        <div class="ci-card-icon" aria-hidden="true">📧</div>
        <div>
          <h3>Email</h3>
          <p><a href="mailto:<?= e($sEmail) ?>"><?= e($sEmail) ?></a></p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon" aria-hidden="true">📱</div>
        <div>
          <h3>Telepon / WhatsApp</h3>
          <p><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $sPhone)) ?>"><?= e($sPhone) ?></a><br>
             <span style="font-size:.76rem">WA aktif <?= e($sWaHrs) ?></span></p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon" aria-hidden="true">📍</div>
        <div>
          <h3>Alamat Studio</h3>
          <p><?= nl2br(e($sAddr)) ?></p>
        </div>
      </div>
      <div class="ci-card">
        <div class="ci-card-icon" aria-hidden="true">🕐</div>
        <div>
          <h3>Jam Operasional</h3>
          <p><?= e($sHours) ?></p>
        </div>
      </div>
      <?php if ($sMeet): ?>
      <div class="ci-card">
        <div class="ci-card-icon" aria-hidden="true">🗓</div>
        <div>
          <h3>Jadwalkan Meeting</h3>
          <p><a href="<?= e($sMeet) ?>" target="_blank" rel="noopener">Pilih waktu konsultasi →</a></p>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <?php if (!$isSent): ?>
    <a href="<?= e(site_wa_url('Halo Creative Studio, saya ingin konsultasi proyek desain')) ?>" class="wa-card" target="_blank" rel="noopener" data-cta="contact-wa">
      <span class="wa-icon" aria-hidden="true">💬</span>
      <div>
        <div class="wa-title">Chat via WhatsApp</div>
        <div class="wa-sub">Alternatif cepat jika ingin tanya dulu</div>
      </div>
      <span class="wa-arrow" aria-hidden="true">→</span>
    </a>
    <?php endif; ?>
  </div>

  <!-- RIGHT: Form / confirmation -->
  <div class="contact-form-card reveal" id="form-konsultasi" style="transition-delay:.12s">
    <?php
    $sent = Session::getFlash('contact_sent');
    $sent = is_array($sent) ? $sent : null;

    // On refresh the flash is gone; keep the confirmation state readable
    // instead of showing an empty form that invites a duplicate submission.
    if ($sent === null && $isSent) {
        $sent = [
            'name'         => '',
            'service'      => $requestedService['name'] ?? '',
            'inquiry_type' => '',
            'is_project'   => $isSentProject,
        ];
    }
    ?>
    <?php if ($sent): ?>
      <?php
      $sentName = trim((string) ($sent['name'] ?? ''));
      $sentService = trim((string) ($sent['service'] ?? ''));
      $sentType = trim((string) ($sent['inquiry_type'] ?? ''));
      $sentIsProject = !empty($sent['is_project']);
      $sentWaText = $sentIsProject
          ? 'Halo Creative Studio, saya sudah mengirim brief' . ($sentService !== '' ? ' untuk paket "' . $sentService . '"' : ' proyek') . '. Saya ingin menambahkan info.'
          : 'Halo Creative Studio, saya sudah mengirim pesan melalui website. Saya ingin menambahkan info.';
      ?>
      <div class="sent-state" role="status" aria-live="polite">
        <div class="sent-icon" aria-hidden="true">✓</div>
        <h2 class="sent-title"><?= $sentIsProject ? 'Brief terkirim' : 'Pesan terkirim' ?></h2>
        <p class="sent-lead">
          <?= $sentName !== '' ? 'Terima kasih, ' . e($sentName) . '.' : 'Terima kasih.' ?>
          <?= $sentIsProject ? 'Brief proyek Anda sudah masuk ke tim kami.' : 'Pesan Anda sudah masuk ke tim kami.' ?>
          Kami akan menghubungi Anda dalam ≤24 jam.
        </p>

        <div class="sent-summary">
          <div>
            <span>Layanan</span>
            <strong><?= e($sentService !== '' ? $sentService : ($sentIsProject ? 'Sesuai brief' : 'Tidak spesifik')) ?></strong>
          </div>
          <div>
            <span>Jenis</span>
            <strong><?= e($sentType !== '' ? $sentType : 'Sesuai pesan') ?></strong>
          </div>
          <div>
            <span>Respons</span>
            <strong>≤24 jam</strong>
          </div>
        </div>

        <ol class="sent-steps">
          <?php if ($sentIsProject): ?>
            <li>
              <span class="sent-step-num" aria-hidden="true">1</span>
              <div><b>Review brief</b><span>Tim memeriksa kebutuhan, lingkup, dan estimasi awal.</span></div>
            </li>
            <li>
              <span class="sent-step-num" aria-hidden="true">2</span>
              <div><b>Konfirmasi</b><span>Kami hubungi via email atau WhatsApp bila ada yang perlu diklarifikasi.</span></div>
            </li>
            <li>
              <span class="sent-step-num" aria-hidden="true">3</span>
              <div><b>Penawaran</b><span>Anda menerima langkah kerja, timeline, dan biaya final.</span></div>
            </li>
          <?php else: ?>
            <li>
              <span class="sent-step-num" aria-hidden="true">1</span>
              <div><b>Review pesan</b><span>Tim membaca kebutuhan dan konteks yang Anda kirim.</span></div>
            </li>
            <li>
              <span class="sent-step-num" aria-hidden="true">2</span>
              <div><b>Konfirmasi</b><span>Kami hubungi via email atau WhatsApp bila perlu klarifikasi.</span></div>
            </li>
            <li>
              <span class="sent-step-num" aria-hidden="true">3</span>
              <div><b>Tindak lanjut</b><span>Anda menerima langkah berikutnya sesuai jenis kebutuhan.</span></div>
            </li>
          <?php endif; ?>
        </ol>

        <div class="sent-actions">
          <a href="<?= e(site_wa_url($sentWaText)) ?>" class="btn-submit" target="_blank" rel="noopener" data-cta="contact-sent-wa">
            Chat via WhatsApp →
          </a>
          <a href="<?= APP_URL ?>/services" class="sent-secondary" data-cta="contact-sent-services">Lihat layanan</a>
        </div>
        <p class="sent-note">
          <?= $sentIsProject ? 'Untuk proyek lain,' : 'Untuk pesan lain,' ?>
          <a href="<?= APP_URL ?>/contact">kirim <?= $sentIsProject ? 'brief' : 'pesan' ?> baru</a>.
        </p>
      </div>
    <?php else: ?>
    <div class="cf-title"><?= $isProjectContext ? 'Mulai Proyek' : 'Kirim Pesan' ?></div>
    <div class="cf-sub">
      <?= $isProjectContext
          ? 'Isi brief singkat. Tim akan konfirmasi lingkup, timeline, dan biaya — target respons ≤24 jam.'
          : 'Isi form di bawah dan kami akan segera menghubungi Anda.' ?>
    </div>

    <?php
    $isQuoteRequest = $requestedService && in_array($requestedService['price_type'], ['starting_from', 'custom'], true);
    ?>
    <?php if ($requestedService): ?>
      <div class="quote-context">
        <div class="qc-kicker"><?= $isQuoteRequest ? 'Permintaan Penawaran' : 'Tanya Paket' ?></div>
        <div class="qc-title"><?= e($requestedService['name']) ?></div>
        <div class="qc-meta">
          <?= e($requestedService['category_name'] ?? 'Layanan') ?>
          <?php if ($requestedService['price_type'] === 'starting_from'): ?>
            · Mulai <?= formatRupiah((float) $requestedService['price']) ?>
          <?php elseif ($requestedService['price_type'] === 'custom'): ?>
            · Harga sesuai kebutuhan
          <?php else: ?>
            · <?= formatRupiah((float) $requestedService['price']) ?>
          <?php endif; ?>
        </div>
        <p class="qc-note">
          <?= $isQuoteRequest
              ? 'Kirim brief singkat. Tim akan konfirmasi lingkup dan harga final sebelum pengerjaan.'
              : 'Kirim pertanyaan atau brief singkat. Tim akan membantu langkah berikutnya.' ?>
        </p>
      </div>
    <?php endif; ?>

    <?php if ($err = Session::getFlash('error')): ?>
      <div class="alert alert-error" role="alert" style="margin-bottom:1.5rem"><?= e($err) ?></div>
    <?php endif; ?>

    <?php
    $errors = Session::getFlash('errors', []);
    $old = Session::getFlash('old', []);
    $requestedSubject = '';
    $prefillBody = '';

    if ($requestedService) {
        $subjectMap = [
            'branding' => 'Branding & Identity',
            'uiux'     => 'UI/UX Design',
            'socmed'   => 'Social Media Kit',
            'motion'   => 'Motion & Video',
            'print'    => 'Print Design',
        ];
        $requestedSubject = $subjectMap[$requestedService['category_slug'] ?? ''] ?? 'Lainnya';

        if (empty($old)) {
            $quoteIntent = $requestedService['price_type'] === 'fixed'
                ? 'memesan'
                : ($requestedService['price_type'] === 'starting_from' ? 'minta penawaran untuk' : 'berdiskusi tentang');
            $prefillBody = 'Halo Creative Studio, saya ingin ' . $quoteIntent . ' paket "' . $requestedService['name'] . '".';
            if ($requestedService['price_type'] === 'starting_from') {
                $prefillBody .= ' Harga mulai dari ' . formatRupiah((float) $requestedService['price']) . '.';
            } elseif ($requestedService['price_type'] === 'custom') {
                $prefillBody .= ' Saya ingin mendiskusikan kebutuhan dan estimasi biayanya.';
            }
            $prefillBody .= "\n\nBrief kebutuhan saya: ";
        }
    } elseif ($isProjectIntent && empty($old)) {
        $prefillBody = "Halo Creative Studio, saya ingin memulai proyek.\n\nBrief kebutuhan saya: ";
    }

    $selectedSubject = $old['subject'] ?? $requestedSubject;
    ?>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error" role="alert">Periksa kembali field yang ditandai di bawah.</div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_URL ?>/contact" id="contact-form">
      <?= csrfField() ?>
      <input type="hidden" name="service" value="<?= e($requestedService['slug'] ?? '') ?>">
      <input type="hidden" name="intent" value="<?= $isProjectIntent ? 'project' : '' ?>">

      <div class="form-group">
        <label for="inquiry_type">Jenis Kebutuhan</label>
        <select id="inquiry_type" name="inquiry_type">
          <option value="Klien Baru" <?= ($old['inquiry_type']??'Klien Baru')==='Klien Baru' ? 'selected' : '' ?>>Klien Baru — Saya Ingin Pesan Layanan</option>
          <option value="Kerja Sama" <?= ($old['inquiry_type']??'')==='Kerja Sama' ? 'selected' : '' ?>>Kerja Sama / Partnership</option>
          <option value="Karir" <?= ($old['inquiry_type']??'')==='Karir' ? 'selected' : '' ?>>Karir / Lowongan Kerja</option>
          <option value="Lainnya" <?= ($old['inquiry_type']??'')==='Lainnya' ? 'selected' : '' ?>>Lainnya</option>
        </select>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name"
                 value="<?= e($old['name'] ?? (Session::isLoggedIn() ? Session::get('user_name') : '')) ?>"
                 class="<?= isset($errors['name']) ? 'err' : '' ?>"
                 autocomplete="name"
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
                 autocomplete="email"
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
                 inputmode="tel" autocomplete="tel"
                 placeholder="+62 812...">
        </div>
        <div class="form-group" id="budget-group">
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

      <div class="form-group" id="subject-group">
        <label for="subject">Jenis Layanan yang Dibutuhkan</label>
        <select id="subject" name="subject">
          <option value="" disabled <?= $selectedSubject === '' ? 'selected' : '' ?>>Pilih layanan</option>
          <option value="Branding & Identity" <?= $selectedSubject === 'Branding & Identity' ? 'selected' : '' ?>>Branding &amp; Identity</option>
          <option value="UI/UX Design" <?= $selectedSubject === 'UI/UX Design' ? 'selected' : '' ?>>UI/UX Design</option>
          <option value="Social Media Kit" <?= $selectedSubject === 'Social Media Kit' ? 'selected' : '' ?>>Social Media Kit</option>
          <option value="Motion & Video" <?= $selectedSubject === 'Motion & Video' ? 'selected' : '' ?>>Motion &amp; Video</option>
          <option value="Print Design" <?= $selectedSubject === 'Print Design' ? 'selected' : '' ?>>Print Design</option>
          <option value="Lainnya" <?= $selectedSubject === 'Lainnya' ? 'selected' : '' ?>>Lainnya / Belum Tahu</option>
        </select>
        <?php if (!empty($errors['subject'])): ?>
          <div class="field-error"><?= e($errors['subject'][0]) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="body" id="body-label">Ceritakan Proyek Anda</label>
        <textarea id="body" name="body"
                  class="<?= isset($errors['body']) ? 'err' : '' ?>"
                  placeholder="Ceritakan tentang bisnis Anda, kebutuhan desain, target audiens, timeline, dan hal lain yang relevan..."
                  required><?= e($old['body'] ?? $prefillBody) ?></textarea>
        <?php if (!empty($errors['body'])): ?>
          <div class="field-error"><?= e($errors['body'][0]) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-submit" id="btn-submit" data-cta="contact-submit">
        <span id="btn-text"><?= $isProjectContext ? 'Kirim Brief →' : 'Kirim Pesan →' ?></span>
        <span id="btn-loader" style="display:none">Mengirim...</span>
      </button>
      <p class="form-privacy">Dengan mengirim pesan, Anda menyetujui bahwa data Anda digunakan untuk keperluan komunikasi. Kami tidak menjual atau membagikan data Anda kepada pihak ketiga.</p>
    </form>
    <?php endif; ?>
  </div>

</div><!-- /.contact-layout -->

<!-- MAP -->
<div class="map-section">
  <div class="map-inner reveal">
    <div class="map-wrap">
      <!-- Ganti dengan embed Google Maps: iframe src="https://maps.google.com/..." -->
      <div class="map-placeholder">
        <div class="icon" aria-hidden="true">📍</div>
        <p><?= nl2br(e($sAddr)) ?><br>
        <a href="https://maps.google.com/?q=<?= urlencode(site_setting('site_maps_query')) ?>" target="_blank" rel="noopener">Buka di Google Maps →</a></p>
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
        ['Bagaimana sistem pembayarannya?','Pembayaran dilakukan penuh di awal melalui Midtrans — transfer bank, QRIS, GoPay, OVO, atau kartu kredit. Pengerjaan dimulai setelah pembayaran terkonfirmasi.'],
        ['Apakah bisa revisi?','Ya. Jumlah revisi tercantum pada fitur setiap paket. Revisi berlaku untuk penyesuaian konsep yang disetujui; perubahan arah atau penambahan scope di luar paket dikenakan biaya tambahan.'],
        ['Apakah ada garansi?','Kami memberikan garansi kesesuaian brief. Jika hasil belum sesuai brief yang disetujui, kami revisi sesuai ketentuan paket. Perubahan di luar brief dihitung sebagai scope tambahan.'],
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
  btn.style.background = '#122A1C';
  btn.style.color = '#C8FF4D';
});

// Reveal
if (typeof IntersectionObserver !== 'undefined') {
  const obs = new IntersectionObserver(entries => {
    entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*80);obs.unobserve(e.target);}});
  },{threshold:.07});
  document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
}

// ── Sesuaikan field yang tampil berdasarkan jenis kebutuhan ──
const inquirySelect = document.getElementById('inquiry_type');
const subjectGroup   = document.getElementById('subject-group');
const budgetGroup    = document.getElementById('budget-group');
const subjectField   = document.getElementById('subject');
const bodyLabel      = document.getElementById('body-label');
const bodyField      = document.getElementById('body');

const bodyPlaceholders = {
  'Klien Baru': 'Ceritakan tentang bisnis Anda, kebutuhan desain, target audiens, timeline, dan hal lain yang relevan...',
  'Kerja Sama': 'Ceritakan bentuk kerja sama yang Anda tawarkan dan bagaimana kita bisa saling menguntungkan...',
  'Karir':      'Ceritakan posisi yang Anda minati, pengalaman, dan lampirkan link portofolio/CV jika ada...',
  'Lainnya':    'Ceritakan apa yang bisa kami bantu...',
};
const bodyLabels = {
  'Klien Baru': 'Ceritakan Proyek Anda',
  'Kerja Sama': 'Ceritakan Rencana Kerja Sama',
  'Karir':      'Ceritakan Diri Anda',
  'Lainnya':    'Pesan Anda',
};

if (inquirySelect && subjectGroup && budgetGroup && subjectField && bodyLabel && bodyField) {
  function applyInquiryType() {
    const val = inquirySelect.value;
    const needsService = (val === 'Klien Baru');
    subjectGroup.style.display = needsService ? '' : 'none';
    budgetGroup.style.display  = needsService ? '' : 'none';
    subjectField.required = needsService;
    bodyLabel.textContent = bodyLabels[val] || bodyLabels['Lainnya'];
    bodyField.placeholder = bodyPlaceholders[val] || bodyPlaceholders['Lainnya'];
  }
  inquirySelect.addEventListener('change', applyInquiryType);
  applyInquiryType();
}
</script>
<?php require VIEW_PATH . '/partials/mobile-cta.php'; ?>
<?php require VIEW_PATH . '/partials/nav-script.php'; ?>
</body>
</html>
