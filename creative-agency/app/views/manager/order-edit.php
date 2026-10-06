<?php
$order = $order ?? [];
$designers = $designers ?? [];
$managers = $managers ?? [];
$priorities = ['low', 'medium', 'high', 'urgent'];
$statuses = ['pending', 'confirmed', 'in_progress', 'revision', 'completed', 'cancelled'];
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
            max-width: 900px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 8px;
        }
        .form-section {
            margin-bottom: 2rem;
        }
        .form-section h2 {
            font-size: 1.1rem; border-bottom: 2px solid #f5f1eb; padding-bottom: 0.5rem; margin-top: 0;
        }
        .form-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c2c2c;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;
            font-family: inherit;
        }
        .form-group textarea {
            resize: vertical; min-height: 100px;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: #2c2c2c;
        }
        .form-group.readonly input {
            background: #f5f1eb; color: #999;
        }
        .form-actions {
            display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end;
        }
        .btn {
            padding: 0.75rem 1.5rem; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer;
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
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #f5f1eb; padding: 1rem; border-radius: 4px; margin-bottom: 2rem;
        }
        .info-item strong {
            display: block; font-size: 0.85rem; color: #666; text-transform: uppercase; margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><?= e($title) ?></h1>

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

        <div class="info-grid">
            <div class="info-item">
                <strong>Klien:</strong>
                <?= e($order['client_name']) ?>
            </div>
            <div class="info-item">
                <strong>Jasa:</strong>
                <?= e($order['service_name']) ?>
            </div>
            <div class="info-item">
                <strong>Total:</strong>
                <?= formatRupiah($order['total']) ?>
            </div>
            <div class="info-item">
                <strong>Status Pembayaran:</strong>
                <?= $order['payment_id'] ? ucfirst($order['payment_status'] ?? 'Pending') : 'Belum dibayar' ?>
            </div>
        </div>

        <form method="POST" action="<?= APP_URL ?>/manager/orders/<?= (int)$order['id'] ?>/update">
            <?= csrf_field() ?>

            <div class="form-section">
                <h2>Assignment & Scheduling</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="assigned_to">Designer *</label>
                        <select id="assigned_to" name="assigned_to" required>
                            <option value="">-- Pilih Designer --</option>
                            <?php foreach ($designers as $designer): ?>
                                <option value="<?= $designer['id'] ?>" <?= $order['assigned_to'] === $designer['id'] ? 'selected' : '' ?>>
                                    <?= e($designer['name']) ?> (<?= e($designer['department'] ?? 'Design') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="manager_id">Manager</label>
                        <select id="manager_id" name="manager_id">
                            <option value="">-- Pilih Manager --</option>
                            <?php foreach ($managers as $manager): ?>
                                <option value="<?= $manager['id'] ?>" <?= $order['manager_id'] === $manager['id'] ? 'selected' : '' ?>>
                                    <?= e($manager['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority *</label>
                        <select id="priority" name="priority" required>
                            <?php foreach ($priorities as $priority): ?>
                                <option value="<?= $priority ?>" <?= $order['priority'] === $priority ? 'selected' : '' ?>>
                                    <?= ucfirst($priority) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="deadline_date">Deadline</label>
                        <input type="date" id="deadline_date" name="deadline_date" 
                               value="<?= $order['deadline_date'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Status</h2>
                <div class="form-group">
                    <label for="status">Status Pesanan *</label>
                    <select id="status" name="status" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= $order['status'] === $status ? 'selected' : '' ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h2>Brief Klien</h2>
                <div class="form-group">
                    <label for="brief">Brief (Read-only)</label>
                    <textarea id="brief" name="brief" readonly style="background: #f5f1eb; color: #999;><?= e($order['brief'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= APP_URL ?>/manager/orders" class="btn btn-secondary">← Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
