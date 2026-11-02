<?php
// app/views/admin/portfolio-form.php — tambah/edit portfolio
$errors = Session::getFlash('errors') ?? [];
$old    = Session::getFlash('old') ?? [];

$defaults = [
    'title' => '', 'slug' => '', 'category_id' => '', 'client_name' => '',
    'year' => '', 'duration' => '', 'services_used' => '', 'description' => '',
    'challenge' => '', 'solution' => '', 'result' => '', 'tags' => '',
    'meta_title' => '', 'meta_description' => '', 'cover_image' => '',
    'is_featured' => 0, 'is_published' => 1, 'is_sample' => 0, 'sort_order' => 0,
];
$p = array_merge($defaults, $portfolio ?: [], $old);
if ($old) {
    foreach (['is_featured', 'is_published', 'is_sample'] as $flag) {
        $p[$flag] = !empty($old[$flag]) ? 1 : 0;
    }
}

$val = static function (string $key) use ($p) {
    return (string) ($p[$key] ?? '');
};
$checked = static function (string $key) use ($p) {
    return !empty($p[$key]) ? 'checked' : '';
};

$activeAdmin  = 'portfolio';
$pageTitle    = $isEdit ? 'Edit Portfolio' : 'Tambah Portfolio';
$pageSubtitle = $isEdit ? 'Perbarui studi kasus dan pengaturan publikasinya.' : 'Buat studi kasus baru untuk halaman portfolio publik.';
$pageActions  = '<a href="' . APP_URL . '/admin/portfolio" class="btn btn-ghost">← Kembali</a>';
require VIEW_PATH . '/partials/admin-nav.php';
?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error" role="alert">
    Periksa kembali isian berikut:
    <ul>
      <?php foreach ($errors as $field => $list): ?>
        <?php foreach ((array) $list as $error): ?><li><?= e((string) $error) ?></li><?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data" novalidate>
  <?= csrfField() ?>

  <div class="card">
    <h2>Informasi Utama</h2>
    <div class="form-grid">
      <div class="field full">
        <label for="title">Judul studi kasus <span class="req">*</span></label>
        <input id="title" name="title" type="text" maxlength="120" required value="<?= e($val('title')) ?>"
               placeholder="Contoh: Rebranding Kedai Kopi Senja">
      </div>

      <div class="field">
        <label for="slug">Slug URL</label>
        <input id="slug" name="slug" type="text" maxlength="120" value="<?= e($val('slug')) ?>"
               placeholder="otomatis-dari-judul">
        <span class="hint">Kosongkan untuk dibuat otomatis. URL: /portfolio/<b>slug</b></span>
      </div>

      <div class="field">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id">
          <option value="">— Tanpa kategori —</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= (int) $cat['id'] ?>" <?= (string) $val('category_id') === (string) $cat['id'] ? 'selected' : '' ?>>
              <?= e($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label for="client_name">Nama klien</label>
        <input id="client_name" name="client_name" type="text" maxlength="100" value="<?= e($val('client_name')) ?>">
      </div>

      <div class="field">
        <label for="year">Tahun</label>
        <input id="year" name="year" type="number" min="1990" max="2100" step="1" value="<?= e($val('year')) ?>" placeholder="<?= date('Y') ?>">
      </div>

      <div class="field">
        <label for="duration">Durasi pengerjaan</label>
        <input id="duration" name="duration" type="text" maxlength="50" value="<?= e($val('duration')) ?>" placeholder="Contoh: 6 minggu">
      </div>

      <div class="field">
        <label for="sort_order">Urutan tampil</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="255" step="1" value="<?= e($val('sort_order')) ?>">
        <span class="hint">Angka kecil tampil lebih dulu.</span>
      </div>

      <div class="field full">
        <label for="services_used">Layanan yang dipakai</label>
        <input id="services_used" name="services_used" type="text" maxlength="255" value="<?= e($val('services_used')) ?>"
               placeholder="Brand Strategy, Logo & Identity, Packaging">
        <span class="hint">Pisahkan dengan koma.</span>
      </div>

      <div class="field full">
        <label for="description">Ringkasan singkat <span class="req">*</span></label>
        <textarea id="description" name="description" maxlength="600" required placeholder="Satu atau dua kalimat tentang proyek ini."><?= e($val('description')) ?></textarea>
        <span class="hint">Tampil di bawah judul dan dipakai sebagai meta description jika kolom SEO kosong.</span>
      </div>
    </div>

    <div class="form-section">
      <h3>Studi Kasus</h3>
      <div class="form-grid">
        <div class="field full">
          <label for="challenge">Tantangan</label>
          <textarea id="challenge" name="challenge" placeholder="Apa masalah yang dihadapi klien sebelum proyek ini?"><?= e($val('challenge')) ?></textarea>
        </div>
        <div class="field full">
          <label for="solution">Solusi</label>
          <textarea id="solution" name="solution" placeholder="Pendekatan dan langkah yang Anda lakukan."><?= e($val('solution')) ?></textarea>
        </div>
        <div class="field full">
          <label for="result">Hasil</label>
          <textarea id="result" name="result" placeholder="Dampak setelah proyek selesai. Hindari klaim yang tidak bisa dibuktikan."><?= e($val('result')) ?></textarea>
        </div>
      </div>
    </div>

    <div class="form-section">
      <h3>SEO &amp; Tag</h3>
      <div class="form-grid">
        <div class="field full">
          <label for="meta_title">Meta title</label>
          <input id="meta_title" name="meta_title" type="text" maxlength="160" value="<?= e($val('meta_title')) ?>"
                 placeholder="Judul untuk hasil pencarian (maks. 60 karakter ideal)">
        </div>
        <div class="field full">
          <label for="meta_description">Meta description</label>
          <textarea id="meta_description" name="meta_description" maxlength="255" style="min-height:80px"
                    placeholder="Ringkasan 140–160 karakter untuk mesin pencari."><?= e($val('meta_description')) ?></textarea>
        </div>
        <div class="field full">
          <label for="tags">Tag</label>
          <input id="tags" name="tags" type="text" maxlength="255" value="<?= e($val('tags')) ?>"
                 placeholder="Branding, F&B, Rebranding">
          <span class="hint">Pisahkan dengan koma.</span>
        </div>
      </div>
    </div>

    <div class="form-section">
      <h3>Gambar Cover</h3>
      <?php if (!empty($p['cover_image'])): ?>
        <div class="cover-preview">
          <img src="<?= APP_URL ?>/uploads/<?= e($p['cover_image']) ?>" alt="Cover saat ini">
          <div>
            <strong style="font-size:.85rem">Cover saat ini</strong>
            <div class="hint"><?= e($p['cover_image']) ?></div>
            <label class="check" style="padding-top:.35rem">
              <input type="checkbox" name="remove_cover" value="1">
              <span>Hapus cover ini</span>
            </label>
          </div>
        </div>
      <?php endif; ?>
      <div class="field">
        <label for="cover_image">Unggah cover baru</label>
        <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
        <span class="hint">JPG, PNG, WEBP, atau GIF. Maks. <?= (int) MAX_UPLOAD_MB ?> MB. Rasio ideal 16:7.</span>
      </div>
    </div>

    <div class="form-section">
      <h3>Publikasi</h3>
      <div class="field-row-2">
        <div>
          <label class="check">
            <input type="checkbox" name="is_published" value="1" <?= $checked('is_published') ?>>
            <span>Tampilkan di situs publik<small>Jika tidak dicentang, studi kasus disimpan sebagai draft.</small></span>
          </label>
          <label class="check">
            <input type="checkbox" name="is_featured" value="1" <?= $checked('is_featured') ?>>
            <span>Tampilkan di beranda<small>Maksimal 6 karya featured tampil di halaman utama.</small></span>
          </label>
          <label class="check">
            <input type="checkbox" name="is_sample" value="1" <?= $checked('is_sample') ?>>
            <span>Tandai sebagai konten sampel<small>Badge "Sampel" akan tampil di halaman publik.</small></span>
          </label>
        </div>
        <div class="field">
          <label for="notes-preview">Pratinjau</label>
          <div class="hint" style="line-height:1.8">
            Status: <b><?= !empty($p['is_published']) ? 'Live' : 'Draft' ?></b><br>
            Featured: <b><?= !empty($p['is_featured']) ? 'Ya' : 'Tidak' ?></b><br>
            Sampel: <b><?= !empty($p['is_sample']) ? 'Ya' : 'Tidak' ?></b>
          </div>
          <?php if ($isEdit && !empty($p['slug'])): ?>
            <a href="<?= APP_URL ?>/portfolio/<?= e(rawurlencode($p['slug'])) ?>" class="btn btn-sm btn-ghost" target="_blank" rel="noopener" style="margin-top:.5rem">Lihat Halaman ↗</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <a href="<?= APP_URL ?>/admin/portfolio" class="btn btn-ghost">Batal</a>
      <div class="right">
        <button type="submit" class="btn btn-accent"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Portfolio' ?></button>
      </div>
    </div>
  </div>
</form>
</main>
</body>
</html>
