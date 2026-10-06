<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tentang Kami — Creative Studio</title>
<meta name="description" content="Kami adalah studio desain profesional Indonesia yang membantu bisnis tumbuh lewat visual yang strategis dan berkarakter.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<?php require VIEW_PATH . '/partials/base-styles.php'; ?>
<style>
/* ── About-specific ─────────────────────────────────────── */

/* Story section */
.story-grid{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.story-year{font-family:var(--fh);font-weight:800;font-size:6rem;line-height:1;color:var(--cream);margin-bottom:-.5rem}
.story-body{font-size:.95rem;color:var(--muted);line-height:1.9}
.story-body p+p{margin-top:1.1rem}
.story-visual{position:relative}
.story-card-stack{position:relative;height:380px}
.sc{border-radius:14px;padding:2rem;position:absolute}
.sc-main{background:var(--ink);color:#fff;width:80%;left:10%;top:0;z-index:2}
.sc-accent{background:var(--accent);color:#fff;width:65%;right:0;bottom:0;z-index:3}
.sc-bg{background:var(--cream);width:70%;left:0;bottom:1rem;z-index:1}
.sc-lbl{font-family:var(--fh);font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;opacity:.5;margin-bottom:.5rem}
.sc-val{font-family:var(--fh);font-weight:800;font-size:1.75rem;line-height:1}
.sc-sub{font-size:.8rem;opacity:.6;margin-top:.3rem}
.sc-icon{font-size:2.5rem;opacity:.15;position:absolute;right:1.5rem;top:1.5rem}

/* Values */
.values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem}
.value-card{background:var(--white);border:1px solid var(--cream);border-radius:12px;padding:2rem}
.value-icon{font-size:2rem;margin-bottom:1rem}
.value-name{font-family:var(--fh);font-weight:700;font-size:1rem;margin-bottom:.5rem}
.value-desc{font-size:.85rem;color:var(--muted);line-height:1.75}

/* Team */
.team-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:3rem}
.team-card{background:var(--white);border:1px solid var(--cream);border-radius:12px;overflow:hidden;transition:transform .25s,box-shadow .25s}
.team-card:hover{transform:translateY(-5px);box-shadow:0 14px 36px rgba(0,0,0,.08)}
.team-photo{height:200px;background:var(--cream);display:flex;align-items:center;justify-content:center;font-size:3.5rem;position:relative;overflow:hidden}
.team-photo img{width:100%;height:100%;object-fit:cover}
.team-dept{position:absolute;bottom:.75rem;left:.75rem;background:var(--accent);color:#fff;font-family:var(--fh);font-size:.6rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:.2rem .6rem;border-radius:2rem}
.team-body{padding:1.25rem}
.team-name{font-family:var(--fh);font-weight:700;font-size:.95rem;margin-bottom:.15rem}
.team-role{font-size:.78rem;color:var(--muted)}

/* Stats bar */
.stats-bar{background:var(--ink);padding:4rem clamp(1rem,4vw,3rem)}
.stats-bar-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:0;border:1px solid #1e1e1e;border-radius:14px;overflow:hidden}
.sb-item{padding:2rem;border-right:1px solid #1e1e1e;text-align:center}
.sb-item:last-child{border-right:none}
.sb-num{font-family:var(--fh);font-weight:800;font-size:2.5rem;color:#fff;line-height:1}
.sb-num em{font-style:normal;color:var(--accent)}
.sb-lbl{font-size:.78rem;color:#444;margin-top:.4rem}

/* Journey / timeline */
.timeline{display:flex;flex-direction:column;gap:0;margin-top:2.5rem;position:relative}
.timeline::before{content:'';position:absolute;left:1.25rem;top:.5rem;bottom:.5rem;width:2px;background:var(--cream)}
.tl-item{display:flex;gap:2rem;padding-bottom:2.5rem;position:relative}
.tl-item:last-child{padding-bottom:0}
.tl-dot{width:2.5rem;height:2.5rem;border-radius:50%;background:var(--white);border:2px solid var(--cream);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:var(--fh);font-weight:800;font-size:.7rem;color:var(--muted);position:relative;z-index:1;transition:all .3s}
.tl-item:hover .tl-dot{background:var(--accent);border-color:var(--accent);color:#fff}
.tl-year{font-family:var(--fh);font-weight:800;font-size:.85rem;color:var(--accent);margin-bottom:.3rem}
.tl-title{font-family:var(--fh);font-weight:700;font-size:.95rem;margin-bottom:.3rem}
.tl-desc{font-size:.85rem;color:var(--muted);line-height:1.7}

/* Clients logo strip */
.clients-strip{background:var(--cream);padding:4rem clamp(1rem,4vw,3rem)}
.clients-inner{max-width:1200px;margin:0 auto}
.clients-label{font-family:var(--fh);font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);text-align:center;margin-bottom:2rem}
.clients-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:1rem}
.client-logo{background:var(--white);border-radius:8px;padding:1.25rem;display:flex;align-items:center;justify-content:center;font-family:var(--fh);font-weight:700;font-size:.85rem;color:var(--muted);border:1px solid rgba(0,0,0,.05);transition:all .2s}
.client-logo:hover{border-color:var(--ink);color:var(--ink)}

@media(max-width:1024px){
  .team-grid{grid-template-columns:repeat(2,1fr)}
  .values-grid{grid-template-columns:repeat(2,1fr)}
  .story-grid{grid-template-columns:1fr;gap:3rem}
  .story-card-stack{height:260px}
  .clients-grid{grid-template-columns:repeat(3,1fr)}
}
@media(max-width:768px){
  .stats-bar-inner{grid-template-columns:1fr 1fr}
  .sb-item{border-right:none;border-bottom:1px solid #1e1e1e}
  .sb-item:nth-child(odd){border-right:1px solid #1e1e1e}
  .sb-item:last-child{border-bottom:none}
  .clients-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:480px){
  .team-grid{grid-template-columns:1fr}
  .values-grid{grid-template-columns:1fr}
  .stats-bar-inner{grid-template-columns:1fr}
  .sb-item{border-right:none!important}
}
</style>
</head>
<body>

<?php $activeNav = 'about'; require VIEW_PATH . '/partials/nav.php'; ?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="s-inner">
    <div class="breadcrumb">
      <a href="<?= APP_URL ?>/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span>Tentang Kami</span>
    </div>
    <h1>Studio Desain yang<br><em>Punya Tujuan</em></h1>
    <p>Kami bukan sekadar membuat tampilan yang bagus — kami merancang visual yang bekerja untuk bisnis Anda, setiap hari.</p>
  </div>
</section>

<!-- STORY -->
<section class="section">
  <div class="s-inner">
    <div class="story-grid reveal">
      <div>
        <div class="story-year">2019</div>
        <div class="eyebrow" style="margin-top:.5rem"><span class="eyebrow-line"></span>Cerita Kami</div>
        <h2 class="stitle">Dari Kamar Kos<br>ke Studio Profesional</h2>
        <div class="story-body">
          <p>Creative Studio dimulai tahun 2019 dari sebuah kamar kos di Jakarta — satu laptop, satu monitor pinjaman, dan keinginan kuat untuk membuktikan bahwa desain berkualitas studio internasional bisa hadir untuk UMKM Indonesia.</p>
          <p>Kami percaya setiap bisnis, sebesar apa pun, berhak mendapatkan identitas visual yang kuat. Bukan template, bukan generik — tapi desain yang benar-benar mencerminkan siapa mereka dan apa yang mereka tawarkan.</p>
          <p>Hari ini kami telah menyelesaikan lebih dari 150 proyek untuk klien dari berbagai industri — dari startup teknologi, brand kecantikan, hingga perusahaan properti dan FMCG.</p>
        </div>
      </div>
      <div class="story-visual">
        <div class="story-card-stack">
          <div class="sc sc-bg">
            <div class="sc-lbl">Mulai</div>
            <div class="sc-val">2019</div>
            <div class="sc-sub">Jakarta, Indonesia</div>
          </div>
          <div class="sc sc-main">
            <div class="sc-icon">◈</div>
            <div class="sc-lbl">Total proyek selesai</div>
            <div class="sc-val">150+</div>
            <div class="sc-sub">Dari 12+ industri berbeda</div>
          </div>
          <div class="sc sc-accent">
            <div class="sc-lbl">Kepuasan klien</div>
            <div class="sc-val">98%</div>
            <div class="sc-sub">Rating rata-rata 5★</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="stats-bar-inner reveal">
    <?php
    $stats = [
      ['150+','Proyek selesai'],
      ['98%', 'Klien puas'],
      ['12+', 'Industri dilayani'],
      ['5★',  'Rating rata-rata'],
    ];
    foreach ($stats as [$n,$l]): ?>
      <div class="sb-item">
        <div class="sb-num"><?= preg_replace('/([+%★])/', '<em>$1</em>', $n) ?></div>
        <div class="sb-lbl"><?= $l ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- VALUES -->
<section class="section">
  <div class="s-inner">
    <div class="reveal">
      <div class="eyebrow"><span class="eyebrow-line"></span>Nilai Kami</div>
      <h2 class="stitle">Prinsip yang Menggerakkan<br>Setiap Karya</h2>
    </div>
    <div class="values-grid reveal" style="transition-delay:.1s">
      <?php
      $values = [
        ['🎯','Tujuan di Atas Estetika','Desain yang baik harus bekerja — menarik perhatian, menyampaikan pesan, dan mendorong aksi. Kecantikan visual hanyalah alat, bukan tujuan akhir.'],
        ['🤝','Kolaborasi Terbuka','Kami percaya hasil terbaik lahir dari kolaborasi, bukan instruksi satu arah. Klien adalah mitra — bukan sekadar pemberi tugas.'],
        ['⚡','Ketepatan Waktu','Deadline adalah komitmen, bukan estimasi. Kami membangun sistem kerja yang memastikan setiap proyek selesai tepat waktu tanpa mengorbankan kualitas.'],
        ['🔍','Detail Tanpa Kompromi','Piksel, spasi, ketebalan garis — semua diperhatikan. Karena dalam desain, hal-hal kecil adalah yang membuat perbedaan besar.'],
        ['📈','Berorientasi Hasil','Kami mengukur kesuksesan dari dampak yang dihasilkan desain terhadap bisnis klien — bukan dari penghargaan atau pujian semata.'],
        ['🔄','Terus Berkembang','Dunia desain bergerak cepat. Kami selalu belajar, bereksperimen, dan memperbarui pendekatan agar selalu relevan dan kompetitif.'],
      ];
      foreach ($values as [$icon,$name,$desc]): ?>
        <div class="value-card">
          <div class="value-icon"><?= $icon ?></div>
          <div class="value-name"><?= $name ?></div>
          <div class="value-desc"><?= $desc ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section" style="background:var(--cream);padding-top:5rem;padding-bottom:5rem">
  <div class="s-inner">
    <div class="reveal">
      <div class="eyebrow"><span class="eyebrow-line"></span>Tim Kami</div>
      <h2 class="stitle">Orang-orang di Balik<br>Setiap Karya</h2>
      <p class="ssub">Tim kecil yang solid, masing-masing ahli di bidangnya dan berdedikasi penuh pada setiap proyek.</p>
    </div>
    <div class="team-grid reveal" style="transition-delay:.1s">
      <?php
      $team = [
        ['👤','Ariel Pratama','Creative Director & Co-founder','Branding'],
        ['👤','Sinta Maharani','Lead UI/UX Designer','UI/UX'],
        ['👤','Budi Setiawan','Motion & Multimedia','Motion'],
        ['👤','Reza Aulia','Brand Strategist','Strategy'],
      ];
      foreach ($team as [$avatar,$name,$role,$dept]): ?>
        <div class="team-card">
          <div class="team-photo">
            <?= $avatar ?>
            <span class="team-dept"><?= $dept ?></span>
          </div>
          <div class="team-body">
            <div class="team-name"><?= $name ?></div>
            <div class="team-role"><?= $role ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TIMELINE -->
<section class="section">
  <div class="s-inner">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start" class="reveal">
      <div>
        <div class="eyebrow"><span class="eyebrow-line"></span>Perjalanan</div>
        <h2 class="stitle">Milestone<br>yang Membentuk Kami</h2>
        <p class="ssub" style="margin-top:.5rem">Dari proyek pertama hingga hari ini — setiap langkah mengajarkan kami sesuatu yang baru.</p>
      </div>
      <div class="timeline">
        <?php
        $milestones = [
          ['2019','Studio Didirikan','Memulai dari proyek logo pertama untuk teman, selesai semalam, dibayar makan malam. Dari situ segalanya dimulai.'],
          ['2020','10 Klien Pertama','Mendapat kepercayaan dari 10 klien berbayar pertama, termasuk brand kecantikan lokal yang kini sudah go national.'],
          ['2021','Tim Bertambah','Merekrut desainer UI/UX pertama. Studio resmi beroperasi dari co-working space, bukan lagi kamar kos.'],
          ['2022','100 Proyek Selesai','Mencapai milestone 100 proyek dengan 0 refund. Rating rata-rata 4.9/5 dari semua klien.'],
          ['2023','Platform Online Diluncurkan','Meluncurkan sistem pemesanan online terintegrasi — klien bisa order, bayar, dan pantau progres secara digital.'],
          ['2024','Ekspansi Layanan','Menambahkan layanan Motion & Video serta Print Design. Tim tumbuh menjadi 6 orang tetap.'],
          ['2025+','Terus Berkembang','Fokus membangun ekosistem kreatif yang membantu lebih banyak bisnis Indonesia tampil profesional di dunia.'],
        ];
        foreach ($milestones as [$year,$title,$desc]): ?>
          <div class="tl-item">
            <div class="tl-dot"></div>
            <div>
              <div class="tl-year"><?= $year ?></div>
              <div class="tl-title"><?= $title ?></div>
              <div class="tl-desc"><?= $desc ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- CLIENTS -->
<div class="clients-strip">
  <div class="clients-inner reveal">
    <div class="clients-label">Beberapa klien yang telah mempercayai kami</div>
    <div class="clients-grid">
      <?php
      $clients = ['TechVenture','Kedai Nusantara','BeautyBrand','PT Maju Bersama','StartupX','EventKreatiF','GreenFood','UrbanSpace','MediCare','Archipelago','FreshMart','BuildCo'];
      foreach ($clients as $c): ?>
        <div class="client-logo"><?= $c ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- CTA -->
<section class="cta-strip">
  <div class="cta-strip-inner reveal">
    <div>
      <h2>Siap Jadi Bagian dari<br><em>Cerita Selanjutnya?</em></h2>
      <p>Bergabunglah dengan 150+ bisnis yang sudah mempercayakan identitas visual mereka kepada kami.</p>
    </div>
    <div class="cta-strip-btns">
      <a href="<?= APP_URL ?>/services" class="btn-cta-p">Lihat Layanan Kami →</a>
      <a href="<?= APP_URL ?>/contact"  class="btn-cta-g">💬 Hubungi Kami</a>
    </div>
  </div>
</section>

<?php require VIEW_PATH . '/partials/footer.php'; ?>
</body>
</html>
