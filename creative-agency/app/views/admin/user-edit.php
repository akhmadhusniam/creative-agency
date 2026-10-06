<?php
$user = $user ?? [];
$roles = $roles ?? [];
$errors = Session::getFlash('errors') ?? [];
$errorMsg = Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .form-container {
            max-width: 600px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 8px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c2c2c;
        }
        .form-group input, .form-group select {
            width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none; border-color: #2c2c2c;
        }
        .form-actions {
            display: flex; gap: 1rem; margin-top: 2rem;
        }
        .btn {
            flex: 1; padding: 0.75rem; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer;
        }
        .btn-primary {
            background: #2c2c2c; color: white;
        }
        .btn-primary:hover {
            background: #1c1c1c;
        }
        .btn-secondary {
            background: #f5f1eb; color: #2c2c2c;
        }
        .btn-secondary:hover {
            background: #e8e6e1;
        }
        .alert {
            padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;
        }
        .alert-error {
            background: #ffe7e7; color: #cc0000;
        }
        .error-list {
            list-style: none; padding: 0; margin: 0;
        }
        .error-list li {
            margin-bottom: 0.5rem;
        }
        .info-box {
            background: #e7f3ff; border-left: 4px solid #0066cc; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><?= e($title) ?></h1>
        
        <div class="info-box">
            <p><strong>Email:</strong> <?= e($user['email']) ?></p>
            <p><strong>Terdaftar:</strong> <?= date('d M Y H:i', strtotime($user['created_at'] ?? 'now')) ?></p>
        </div>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error"><?= e($errorMsg) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/admin/users/<?= (int)$user['id'] ?>/update">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" value="<?= e($user['name']) ?>" readonly style="background: #f5f1eb; color: #999;">
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $roleKey => $roleLabel): ?>
                        <option value="<?= e($roleKey) ?>" <?= $user['role'] === $roleKey ? 'selected' : '' ?>>
                            <?= e($roleLabel) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="department">Department *</label>
                <input type="text" id="department" name="department" value="<?= e($user['department'] ?? '') ?>" 
                       placeholder="e.g. Design, Operations, Finance" required>
                <small style="color: #666;">Divisi kerja user</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= APP_URL ?>/admin/users" class="btn btn-secondary" style="text-align: center; text-decoration: none;">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
