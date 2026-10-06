<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
  --ink:     #0D0D0D;
  --paper:   #F7F5F0;
  --cream:   #EDEBE4;
  --accent:  #C8412B;
  --muted:   #7A7570;
  --white:   #FFFFFF;
  --radius:  6px;
  --gap:     clamp(1rem, 4vw, 2.5rem);
  --ff-head: 'Syne', sans-serif;
  --ff-body: 'Inter', sans-serif;
  --nav-h:   64px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--ff-body); background: var(--paper); color: var(--ink); line-height: 1.6; }
a { color: inherit; text-decoration: none; }

/* ── Nav ─────────────────────────────────────────────────── */
.nav { position: sticky; top: 0; z-index: 100; height: var(--nav-h); background: var(--paper); border-bottom: 1px solid var(--cream); display: flex; align-items: center; }
.nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); width: 100%; display: flex; align-items: center; justify-content: space-between; }
.nav-logo { font-family: var(--ff-head); font-weight: 800; font-size: 1.25rem; letter-spacing: -.02em; }
.nav-logo span { color: var(--accent); }
.nav-back { font-size: .9rem; color: var(--muted); cursor: pointer; transition: color .15s; }
.nav-back:hover { color: var(--ink); }

/* ── Container ───────────────────────────────────────────── */
.container { max-width: 1200px; margin: 0 auto; padding: 0 var(--gap); }

/* ── Layout ──────────────────────────────────────────────── */
.checkout-layout {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 2rem;
  padding: 3rem 0;
  align-items: start;
}

.checkout-form { min-width: 0; }

/* ── Card ────────────────────────────────────────────────── */
.card {
  background: var(--white);
  border: 1px solid var(--cream);
  border-radius: 10px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.card-title {
  font-family: var(--ff-head);
  font-weight: 700;
  font-size: 1.1rem;
  margin-bottom: 1.2rem;
}

/* ── Form ────────────────────────────────────────────────── */
.form-group {
  margin-bottom: 1.2rem;
}

label {
  display: block;
  font-size: .85rem;
  font-weight: 500;
  margin-bottom: .45rem;
  color: var(--muted);
}

input[type="text"],
input[type="email"],
input[type="tel"],
textarea,
select {
  font-family: var(--ff-body);
  width: 100%;
  padding: .6rem .75rem;
  border: 1px solid var(--cream);
  border-radius: var(--radius);
  font-size: .875rem;
}

textarea {
  resize: vertical;
  min-height: 120px;
  line-height: 1.5;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
textarea:focus,
select:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 2px var(--accent)22;
}

/* ── Summary Box ─────────────────────────────────────────── */
.summary-box {
  position: sticky;
  top: calc(var(--nav-h) + 1.5rem);
}

.summary-card {
  background: var(--white);
  border: 1px solid var(--cream);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.07);
}

.summary-header {
  background: var(--ink);
  color: var(--white);
  padding: 1.2rem;
}

.summary-service-name {
  font-family: var(--ff-head);
  font-weight: 700;
  font-size: 1rem;
  margin-bottom: .25rem;
}

.summary-service-desc {
  font-size: .8rem;
  color: #888;
}

.summary-body {
  padding: 1.2rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: .6rem 0;
  border-bottom: 1px solid var(--cream);
  font-size: .875rem;
}

.summary-row:last-child {
  border-bottom: none;
}

.summary-row-key {
  color: var(--muted);
}

.summary-row-val {
  font-weight: 600;
  color: var(--ink);
}

.summary-total {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid var(--cream);
  font-family: var(--ff-head);
  font-weight: 800;
  font-size: 1.1rem;
}

.summary-total-val {
  color: var(--accent);
}

/* ── Buttons ─────────────────────────────────────────────── */
.btn {
  display: inline-block;
  padding: .7rem 1.4rem;
  border-radius: var(--radius);
  border: none;
  font-family: var(--ff-head);
  font-weight: 700;
  font-size: .9rem;
  letter-spacing: .04em;
  cursor: pointer;
  transition: all .2s;
  text-align: center;
}

.btn-primary {
  width: 100%;
  background: var(--accent);
  color: var(--white);
}

.btn-primary:hover {
  background: #A83422;
  transform: translateY(-1px);
}

.btn-secondary {
  width: 100%;
  background: var(--paper);
  color: var(--muted);
  border: 1px solid var(--cream);
  margin-top: .75rem;
}

.btn-secondary:hover {
  background: var(--cream);
  color: var(--ink);
}

/* ── Alert ───────────────────────────────────────────────── */
.alert {
  padding: .8rem 1rem;
  border-radius: var(--radius);
  margin-bottom: 1rem;
  font-size: .875rem;
}

.alert-info {
  background: #dbeafe;
  color: #1e40af;
  border: 1px solid #93c5fd;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 960px) {
  .checkout-layout {
    grid-template-columns: 1fr;
  }
  .summary-box {
    position: static;
  }
}

@media (max-width: 640px) {
  .checkout-layout {
    padding: 1.5rem 0;
  }
  .card {
    padding: 1rem;
  }
}
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-inner">
    <a href="<?= APP_URL ?>/" class="nav-logo">creative<span>.</span></a>
    <a href="javascript:history.back()" class="nav-back">← Kembali</a>
  </div>
</nav>

<!-- MAIN -->
<div class="container">
  <div class="checkout-layout">

    <!-- LEFT — Form Checkout -->
    <div class="checkout-form">

      <!-- Info Header -->
      <div class="card alert alert-info">
        <strong>📝 Lengkapi Brief Proyek Anda</strong><br>
        Semakin detail briefing Anda, semakin baik kami memahami kebutuhan dan menghasilkan desain yang sesuai ekspektasi.
      </div>

      <!-- Form -->
      <form action="<?= APP_URL ?>/order" method="POST" class="card">
        <?= csrfField() ?>
        <input type="hidden" name="service_id" value="<?= e($service['id']) ?>">

        <!-- Data Kontak -->
        <div class="card-title">Data Kontak</div>

        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" value="<?= e($user['name'] ?? '') ?>" required readonly style="background: var(--cream);">
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= e($user['email'] ?? '') ?>" required readonly style="background: var(--cream);">
        </div>

        <div class="form-group">
          <label for="phone">Nomor Telepon</label>
          <input type="tel" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="+62 812...">
        </div>

        <!-- Brief Proyek -->
        <div class="card-title" style="margin-top: 1.8rem;">Brief Proyek</div>

        <div class="form-group">
          <label for="brief">Deskripsi Proyek & Kebutuhan Anda *</label>
          <textarea id="brief" name="brief" required placeholder="Jelaskan secara detail apa yang Anda butuhkan:&#10;- Target audiens Anda&#10;- Visi/tujuan proyek&#10;- Preferensi gaya desain&#10;- Deadline/batas waktu&#10;- Informasi lainnya yang relevan"></textarea>
        </div>

        <div class="form-group">
          <label for="additional">Informasi Tambahan (Opsional)</label>
          <textarea id="additional" name="additional" placeholder="Catatan atau pertanyaan tambahan..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
          Lanjut ke Pembayaran →
        </button>
        <a href="<?= APP_URL ?>/services/<?= e($service['slug']) ?>" class="btn btn-secondary">
          Batal
        </a>
      </form>

    </div>

    <!-- RIGHT — Summary Box -->
    <div class="summary-box">

      <div class="summary-card">
        <div class="summary-header">
          <div class="summary-service-name"><?= e($service['name']) ?></div>
          <div class="summary-service-desc"><?= e($service['category_name'] ?? 'Layanan') ?></div>
        </div>

        <div class="summary-body">
          <!-- Info -->
          <?php if ($service['delivery_days']): ?>
          <div class="summary-row">
            <span class="summary-row-key">⏱ Durasi</span>
            <span class="summary-row-val"><?= $service['delivery_days'] ?> hari kerja</span>
          </div>
          <?php endif; ?>

          <div class="summary-row">
            <span class="summary-row-key">🔁 Revisi</span>
            <span class="summary-row-val">Tersedia</span>
          </div>

          <div class="summary-row">
            <span class="summary-row-key">📁 Format File</span>
            <span class="summary-row-val">PNG, SVG, PDF</span>
          </div>

          <!-- Price -->
          <div class="summary-total">
            <span>Total</span>
            <span class="summary-total-val">
              <?php if ($service['price_type'] === 'custom'): ?>
                Diskusi
              <?php else: ?>
                <?= formatRupiah((float)$service['price']) ?>
              <?php endif; ?>
            </span>
          </div>

          <!-- Info tambahan -->
          <div style="margin-top: 1rem; padding: .8rem; background: var(--paper); border-radius: var(--radius); font-size: .8rem; color: var(--muted);">
            <strong>💡 Catatan:</strong><br>
            Setelah submit, Anda akan diarahkan ke halaman pembayaran untuk menyelesaikan transaksi.
          </div>
        </div>
      </div>

    </div>

  </div><!-- /.checkout-layout -->
</div><!-- /.container -->

</body>
</html>
