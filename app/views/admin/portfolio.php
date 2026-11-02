<?php
// app/views/admin/portfolio.php — daftar portfolio + aksi CRUD
$activeAdmin   = 'portfolio';
$pageSubtitle  = 'Kelola studi kasus yang tampil di halaman publik.';
$pageActions   = '<a href="' . APP_URL . '/admin/portfolio/create" class="btn btn-accent">+ Tambah Portfolio</a>';
require VIEW_PATH . '/partials/admin-nav.php';
?>
<div class="card">
  <div class="card-head">
    <h2>Daftar Portfolio <span class="num">(<?= count($portfolio) ?>)</span></h2>
  </div>

  <?php if (empty($portfolio)): ?>
    <div class="empty">
      <div class="ic">✦</div>
      <h3>Belum ada portfolio</h3>
      <p>Tambahkan studi kasus pertama, atau jalankan <code>database/seed_sample_content.sql</code> untuk mengisi 3 contoh.</p>
      <div style="margin-top:1.25rem">
        <a href="<?= APP_URL ?>/admin/portfolio/create" class="btn btn-primary">Tambah Portfolio</a>
      </div>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Karya</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Urutan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($portfolio as $p): ?>
            <tr>
              <td>
                <div class="cell-title">
                  <div class="thumb" aria-hidden="true">
                    <?php if (!empty($p['cover_image'])): ?>
                      <img src="<?= APP_URL ?>/uploads/<?= e($p['cover_image']) ?>" alt="">
                    <?php else: ?>
                      ✦
                    <?php endif; ?>
                  </div>
                  <div>
                    <b><?= e($p['title']) ?></b>
                    <small>/portfolio/<?= e($p['slug']) ?><?= !empty($p['year']) ? ' · ' . e((string) $p['year']) : '' ?></small>
                  </div>
                </div>
              </td>
              <td><?= e($p['category_name'] ?? '—') ?></td>
              <td>
                <?php if (!empty($p['is_published'])): ?>
                  <span class="badge badge-live">Live</span>
                <?php else: ?>
                  <span class="badge badge-draft">Draft</span>
                <?php endif; ?>
                <?php if (!empty($p['is_featured'])): ?>
                  <span class="badge badge-featured">Featured</span>
                <?php endif; ?>
                <?php if (!empty($p['is_sample'])): ?>
                  <span class="badge badge-sample">Sampel</span>
                <?php endif; ?>
              </td>
              <td class="num"><?= (int) $p['sort_order'] ?></td>
              <td>
                <div class="actions">
                  <a href="<?= APP_URL ?>/admin/portfolio/<?= (int) $p['id'] ?>/edit" class="btn btn-sm btn-ghost">Edit</a>

                  <form method="post" action="<?= APP_URL ?>/admin/portfolio/<?= (int) $p['id'] ?>/toggle" style="display:inline">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-sm btn-ghost">
                      <?= !empty($p['is_published']) ? 'Jadikan Draft' : 'Publikasikan' ?>
                    </button>
                  </form>

                  <form method="post" action="<?= APP_URL ?>/admin/portfolio/<?= (int) $p['id'] ?>/delete" style="display:inline"
                        onsubmit="return confirm('Hapus portfolio “<?= e(addslashes($p['title'])) ?>”? Tindakan ini tidak bisa dibatalkan.');">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</main>
</body>
</html>
