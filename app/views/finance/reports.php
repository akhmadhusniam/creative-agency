<?php
$monthlyRevenue = $monthlyRevenue ?? [];
$revenueByService = $revenueByService ?? [];
$topClients = $topClients ?? [];
$maxMonthly = $monthlyRevenue ? max(array_column($monthlyRevenue, 'total')) : 0;
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
        .section { margin-top: 1.5rem; background: white; padding: 2rem; border-radius: 8px; }
        .section h2 { margin-top: 0; border-bottom: 2px solid #E4E9E5; padding-bottom: 1rem; margin-bottom: 1.2rem; }

        .bar-row { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.8rem; }
        .bar-label { width: 90px; font-size: 0.82rem; color: #666; flex-shrink: 0; }
        .bar-track { flex: 1; background: #E4E9E5; border-radius: 4px; height: 26px; position: relative; overflow: hidden; }
        .bar-fill { background: #122A1C; height: 100%; border-radius: 4px; }
        .bar-amount { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.78rem; color: #1a1a1a; font-weight: 600; }

        .rank-table { width: 100%; border-collapse: collapse; }
        .rank-table th { text-align: left; font-size: 0.8rem; text-transform: uppercase; color: #999; padding: 0.6rem 0; border-bottom: 2px solid #122A1C; }
        .rank-table td { padding: 0.8rem 0; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .rank-table .amount-col { text-align: right; }

        .empty-state { text-align: center; padding: 2rem; color: #999; }
    </style>
</head>
<body>
    <div class="fin-page">
        <div class="dashboard-header">
            <h1><?= e($title) ?></h1>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/finance/dashboard">Dashboard</a>
                <a href="<?= APP_URL ?>/finance/payments">Pembayaran</a>
            </div>
        </div>

        <div class="section">
            <h2>Revenue 12 Bulan Terakhir</h2>
            <?php if (empty($monthlyRevenue)): ?>
                <div class="empty-state">Belum ada data revenue</div>
            <?php else: ?>
                <?php foreach ($monthlyRevenue as $row): ?>
                    <?php $pct = $maxMonthly > 0 ? max(6, ($row['total'] / $maxMonthly) * 100) : 0; ?>
                    <div class="bar-row">
                        <div class="bar-label"><?= date('M Y', strtotime($row['month'] . '-01')) ?></div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?= $pct ?>%"></div>
                            <div class="bar-amount"><?= formatRupiah((float) $row['total']) ?> (<?= (int) $row['count'] ?>x)</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Revenue per Layanan</h2>
            <table class="rank-table">
                <thead><tr><th>Layanan</th><th>Transaksi</th><th class="amount-col">Total</th></tr></thead>
                <tbody>
                    <?php foreach ($revenueByService as $row): ?>
                        <tr>
                            <td><?= e($row['service_name']) ?></td>
                            <td><?= (int) $row['count'] ?></td>
                            <td class="amount-col"><?= formatRupiah((float) $row['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($revenueByService)): ?><div class="empty-state">Belum ada data</div><?php endif; ?>
        </div>

        <div class="section">
            <h2>Klien dengan Revenue Tertinggi</h2>
            <table class="rank-table">
                <thead><tr><th>Klien</th><th>Transaksi</th><th class="amount-col">Total</th></tr></thead>
                <tbody>
                    <?php foreach ($topClients as $row): ?>
                        <tr>
                            <td><?= e($row['client_name']) ?></td>
                            <td><?= (int) $row['count'] ?></td>
                            <td class="amount-col"><?= formatRupiah((float) $row['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($topClients)): ?><div class="empty-state">Belum ada data</div><?php endif; ?>
        </div>
    </div>
</body>
</html>
