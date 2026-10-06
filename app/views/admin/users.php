<?php
$users = $users ?? [];
$errorMsg = Session::getFlash('error');
$successMsg = Session::getFlash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .users-container {
            max-width: 1200px; margin: 2rem auto; padding: 2rem;
        }
        .users-table {
            width: 100%; border-collapse: collapse; margin-top: 2rem;
        }
        .users-table thead {
            background: #E4E9E5; border-bottom: 2px solid #122A1C;
        }
        .users-table th {
            padding: 1rem; text-align: left; font-size: 0.95rem;
        }
        .users-table td {
            padding: 1rem; border-bottom: 1px solid #D3DBD5;
        }
        .users-table tbody tr:hover {
            background: #F7FBF3;
        }
        .badge {
            display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem;
        }
        .badge-designer { background: #e7f3ff; color: #0066cc; }
        .badge-manager { background: #fff3e7; color: #cc6600; }
        .badge-finance { background: #e7f5e7; color: #006600; }
        .badge-admin { background: #ffe7e7; color: #cc0000; }
        .badge-owner { background: #f5e7ff; color: #660099; }
        .badge-active { background: #e7f5e7; color: #006600; }
        .badge-inactive { background: #ffe7e7; color: #cc0000; }
        .action-links { display: flex; gap: 0.5rem; }
        .action-links a, .action-links button {
            padding: 0.4rem 0.8rem; font-size: 0.85rem; text-decoration: none;
            background: #122A1C; color: #fff; border: none; border-radius: 4px; cursor: pointer;
        }
        .action-links a:hover, .action-links button:hover {
            background: #0A1911;
        }
    </style>
</head>
<body>
    <div class="users-container">
        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1><?= e($title) ?></h1>
            <a href="<?= APP_URL ?>/admin" class="btn" style="padding: 0.7rem 1.5rem; text-decoration: none;">← Kembali</a>
        </div>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error"><?= e($errorMsg) ?></div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?= e($successMsg) ?></div>
        <?php endif; ?>

        <table class="users-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Hire Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($user['role']) ?>">
                                <?= e($user['role']) ?>
                            </span>
                        </td>
                        <td><?= e($user['department'] ?? '-') ?></td>
                        <td>
                            <span class="badge badge-<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $user['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td><?= $user['hire_date'] ? date('d M Y', strtotime($user['hire_date'])) : '-' ?></td>
                        <td>
                            <div class="action-links">
                                <a href="<?= APP_URL ?>/admin/users/<?= $user['id'] ?>/edit">Edit</a>
                                <form method="POST" action="<?= APP_URL ?>/admin/users/<?= $user['id'] ?>/toggle" style="display: inline; margin: 0;">
                                    <?= csrfField() ?>
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin?');">
                                        <?= $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($users)): ?>
            <div style="text-align: center; padding: 3rem; color: #999;">
                <p>Tidak ada staff tertaraf</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
