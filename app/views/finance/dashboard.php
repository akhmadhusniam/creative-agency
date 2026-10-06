<?php
$totalRevenue = $totalRevenue ?? 0;
$monthRevenue = $monthRevenue ?? 0;
$pendingCount = $pendingCount ?? 0;
$pendingAmount = $pendingAmount ?? 0;
$paidCount = $paidCount ?? 0;
$statusBreakdown = $statusBreakdown ?? [];
$recentPayments = $recentPayments ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .fin-dashboard { max-width: 1400px; margin: 2rem auto; padding: 2rem; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .quick-nav { display: flex; gap: 1rem; }
        .quick-nav a { padding: 0.7rem 1.5rem; background: #122A1C; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        .quick-nav a:hover { background: #0A1911; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: linear-gradient(135deg, #EAF5D0 0%, #DCEEDF 100%); border-left: 4px solid #122A1C; padding: 1.5rem; border-radius: 8px; }
        .stat-card h3 { font-size: 0.9rem; color: #666; margin: 0 0 0.5rem; text-transform: uppercase; }
        .stat-card .number { font-size: 2.2rem; font-weight: bold; color: #122A1C; }

        .section { margin-top: 2rem; background: white; padding: 2rem; border-radius: 8px; }
        .section h2 { margin-top: 0; border-bottom: 2px solid #E4E9E5; padding-bottom: 1rem; }

        .table-responsive { overflow-x: auto; }
        .pay-table { width: 100%; border-collapse: collapse; }
        .pay-table thead { background: #122A1C; color: white; }
        .pay-table th { padding: 1rem; text-align: left; font-weight: 600; font-size: 0.9rem; }
        .pay-table td { padding: 0.8rem; border-bottom: 1px solid #D3DBD5; font-size: 0.9rem; }
        .pay-table tbody tr:hover { background: #F7FBF3; }

        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.8rem; }
        .badge-pending { background: #fff3e7; color: #cc6600; }
        .badge-paid { background: #e7f5e7; color: #006600; }
        .badge-expired { background: #f0f0f0; color: #666; }
        .badge-failed { background: #ffe7e7; color: #cc0000; }
        .badge-refunded { background: #e7f0ff; color: #0044cc; }

        .breakdown-row { display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #E4E9E5; font-size: 0.9rem; }
        .breakdown-row:last-child { border-bottom: none; }

        .empty-state { text-align: center; padding: 2rem; color: #999; }
    </style>
</head>
<body>
    <div class="fin-dashboard">
        <div class="dashboard-header">
            <div>
                <h1><?= e($title) ?></h1>
                <p style="color: #666; margin-top: 0.5rem;">Ringkasan pembayaran dan revenue</p>
            </div>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/finance/payments">Pembayaran</a>
                <a href="<?= APP_URL ?>/finance/reports">Laporan</a>
                <?php require VIEW_PATH . '/partials/notification-bell.php'; ?>
                <a href="<?= APP_URL ?>/dashboard">← Profil</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="number"><?= formatRupiah((float) $totalRevenue) ?></div>
            </div>
            <div class="stat-card">
                <h3>Revenue Bulan Ini</h3>
                <div class="number" style="color: #006600;"><?= formatRupiah((float) $monthRevenue) ?></div>
            </div>
            <div class="stat-card">
                <h3>Menunggu Approval</h3>
                <div class="number" style="color: #ff9900;"><?= (int) $pendingCount ?></div>
                <div style="font-size:0.8rem;color:#999;"><?= formatRupiah((float) $pendingAmount) ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Transaksi Lunas</h3>
                <div class="number"><?= (int) $paidCount ?></div>
            </div>
        </div>

        <div class="section">
            <h2>Ringkasan Status</h2>
            <?php if (empty($statusBreakdown)): ?>
                <div class="empty-state">Belum ada data pembayaran</div>
            <?php else: ?>
                <?php foreach ($statusBreakdown as $row): ?>
                    <div class="breakdown-row">
                        <span><span class="badge badge-<?= e($row['status']) ?>"><?= ucfirst($row['status']) ?></span></span>
                        <span><?= (int) $row['total'] ?> transaksi — <?= formatRupiah((float) $row['amount']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Pembayaran Terbaru</h2>
            <div class="table-responsive">
                <table class="pay-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Klien</th>
                            <th>Layanan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPayments as $p): ?>
                            <tr>
                                <td><strong><?= e($p['order_code']) ?></strong></td>
                                <td><?= e($p['client_name']) ?></td>
                                <td><?= e($p['service_name']) ?></td>
                                <td><?= formatRupiah((float) $p['amount']) ?></td>
                                <td><span class="badge badge-<?= e($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
                                <td><a href="<?= APP_URL ?>/finance/payments" class="badge badge-pending" style="text-decoration:none;">Kelola</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (empty($recentPayments)): ?>
                    <div class="empty-state">Belum ada transaksi</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
