<?php
$payments = $payments ?? [];
$allStatuses = $allStatuses ?? [];
$statusFilter = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .fin-page { max-width: 1400px; margin: 2rem auto; padding: 2rem; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .quick-nav a { padding: 0.7rem 1.5rem; background: #122A1C; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem; margin-left: 0.5rem; }
        .filter-bar { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .filter-bar a { padding: 0.4rem 1rem; border: 1px solid #D3DBD5; border-radius: 20px; text-decoration: none; color: #666; font-size: 0.85rem; }
        .filter-bar a.active { background: #122A1C; color: white; border-color: #122A1C; }
        .section { background: white; padding: 2rem; border-radius: 8px; }
        .table-responsive { overflow-x: auto; }
        .pay-table { width: 100%; border-collapse: collapse; }
        .pay-table thead { background: #122A1C; color: white; }
        .pay-table th { padding: 1rem; text-align: left; font-weight: 600; font-size: 0.9rem; }
        .pay-table td { padding: 0.8rem; border-bottom: 1px solid #D3DBD5; font-size: 0.9rem; vertical-align: middle; }
        .pay-table tbody tr:hover { background: #F7FBF3; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.8rem; }
        .badge-pending { background: #fff3e7; color: #cc6600; }
        .badge-paid { background: #e7f5e7; color: #006600; }
        .badge-expired { background: #f0f0f0; color: #666; }
        .badge-failed { background: #ffe7e7; color: #cc0000; }
        .badge-refunded { background: #e7f0ff; color: #0044cc; }
        .action-btn { padding: 0.4rem 0.8rem; background: #122A1C; color: white; border: none; cursor: pointer; border-radius: 3px; text-decoration: none; font-size: 0.8rem; }
        .action-btn:hover { background: #0A1911; }
        .action-btn-outline { padding: 0.4rem 0.8rem; background: white; color: #122A1C; border: 1px solid #122A1C; cursor: pointer; border-radius: 3px; text-decoration: none; font-size: 0.8rem; }
        .empty-state { text-align: center; padding: 2rem; color: #999; }
        .alert { padding: 0.8rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: #e7f5e7; color: #006600; }
        .actions-cell { display: flex; gap: 0.4rem; }
    </style>
</head>
<body>
    <div class="fin-page">
        <div class="dashboard-header">
            <h1><?= e($title) ?></h1>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/finance/dashboard">Dashboard</a>
                <a href="<?= APP_URL ?>/finance/reports">Laporan</a>
            </div>
        </div>

        <?php if ($success = Session::getFlash('success')): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <div class="filter-bar">
            <a href="<?= APP_URL ?>/finance/payments" class="<?= $statusFilter === '' ? 'active' : '' ?>">Semua</a>
            <?php foreach ($allStatuses as $s): ?>
                <a href="<?= APP_URL ?>/finance/payments?status=<?= $s ?>" class="<?= $statusFilter === $s ? 'active' : '' ?>"><?= ucfirst($s) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="section">
            <div class="table-responsive">
                <table class="pay-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Klien</th>
                            <th>Layanan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><strong><?= e($p['order_code']) ?></strong></td>
                                <td><?= e($p['client_name']) ?></td>
                                <td><?= e($p['service_name']) ?></td>
                                <td><?= formatRupiah((float) $p['amount']) ?></td>
                                <td><?= e($p['payment_method'] ?? '—') ?></td>
                                <td><span class="badge badge-<?= e($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
                                <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                                <td class="actions-cell">
                                    <?php if ($p['status'] !== 'paid'): ?>
                                        <form action="<?= APP_URL ?>/finance/payments/<?= (int) $p['id'] ?>/approve" method="POST" onsubmit="return confirm('Setujui pembayaran ini?');">
                                            <?= csrfField() ?>
                                            <button type="submit" class="action-btn">Approve</button>
                                        </form>
                                    <?php else: ?>
                                        <a href="<?= APP_URL ?>/finance/invoice/<?= (int) $p['id'] ?>" class="action-btn-outline" target="_blank">Invoice</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (empty($payments)): ?>
                    <div class="empty-state">Tidak ada pembayaran untuk filter ini</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
