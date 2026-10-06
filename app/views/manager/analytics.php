<?php
$completeRate = $completeRate ?? [];
$avgTurnaround = $avgTurnaround ?? [];
$monthlyRevenue = $monthlyRevenue ?? [];
$designerPerformance = $designerPerformance ?? [];
$statusDistribution = $statusDistribution ?? [];
$maxMonthly = $monthlyRevenue ? max(array_column($monthlyRevenue, 'total')) : 0;
$maxStatus = $statusDistribution ? max(array_column($statusDistribution, 'total')) : 0;
$statusColors = [
    'pending' => '#cc9900', 'confirmed' => '#0066cc', 'in_progress' => '#cc6600',
    'revision' => '#9900cc', 'completed' => '#009933', 'cancelled' => '#999999',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .mg-page { max-width: 1400px; margin: 2rem auto; padding: 2rem; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .quick-nav { display: flex; align-items: center; gap: 1rem; }
        .quick-nav a { padding: 0.7rem 1.5rem; background: #122A1C; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        .quick-nav a:hover { background: #0A1911; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: linear-gradient(135deg, #EAF5D0 0%, #DCEEDF 100%); border-left: 4px solid #122A1C; padding: 1.5rem; border-radius: 8px; }
        .stat-card h3 { font-size: 0.9rem; color: #666; margin: 0 0 0.5rem; text-transform: uppercase; }
        .stat-card .number { font-size: 2.2rem; font-weight: bold; color: #122A1C; }
        .stat-card .sub { font-size: 0.8rem; color: #999; margin-top: 0.3rem; }

        .section { margin-top: 1.5rem; background: white; padding: 2rem; border-radius: 8px; }
        .section h2 { margin-top: 0; border-bottom: 2px solid #E4E9E5; padding-bottom: 1rem; margin-bottom: 1.2rem; }

        .bar-row { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.8rem; }
        .bar-label { width: 90px; font-size: 0.82rem; color: #666; flex-shrink: 0; }
        .bar-track { flex: 1; background: #E4E9E5; border-radius: 4px; height: 26px; position: relative; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 4px; }
        .bar-amount { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.78rem; color: #1a1a1a; font-weight: 600; }

        .rank-table { width: 100%; border-collapse: collapse; }
        .rank-table th { text-align: left; font-size: 0.8rem; text-transform: uppercase; color: #999; padding: 0.6rem 0; border-bottom: 2px solid #122A1C; }
        .rank-table td { padding: 0.8rem 0; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        .rate-badge { padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
        .rate-high { background: #e7f5e7; color: #006600; }
        .rate-mid { background: #fff3e7; color: #cc6600; }
        .rate-low { background: #ffe7e7; color: #cc0000; }

        .empty-state { text-align: center; padding: 2rem; color: #999; }
    </style>
</head>
<body>
    <div class="mg-page">
        <div class="dashboard-header">
            <h1><?= e($title) ?></h1>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/manager/dashboard">Dashboard</a>
                <a href="<?= APP_URL ?>/manager/team">Tim Designer</a>
                <?php require VIEW_PATH . '/partials/notification-bell.php'; ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Completion Rate (30 hari)</h3>
                <div class="number"><?= $completeRate['rate'] ?? 0 ?>%</div>
                <div class="sub"><?= (int) ($completeRate['completed'] ?? 0) ?> dari <?= (int) ($completeRate['total'] ?? 0) ?> order</div>
            </div>
            <div class="stat-card">
                <h3>Rata-rata Waktu Pengerjaan</h3>
                <div class="number"><?= $avgTurnaround['avg_days'] ?? '—' ?></div>
                <div class="sub">hari (order selesai, 30 hari terakhir)</div>
            </div>
        </div>

        <div class="section">
            <h2>Distribusi Status Order (90 hari terakhir)</h2>
            <?php if (empty($statusDistribution)): ?>
                <div class="empty-state">Belum ada data</div>
            <?php else: ?>
                <?php foreach ($statusDistribution as $row): ?>
                    <?php $pct = $maxStatus > 0 ? max(6, ($row['total'] / $maxStatus) * 100) : 0; ?>
                    <div class="bar-row">
                        <div class="bar-label"><?= ucfirst($row['status']) ?></div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?= $pct ?>%; background: <?= $statusColors[$row['status']] ?? '#122A1C' ?>"></div>
                            <div class="bar-amount"><?= (int) $row['total'] ?> order</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
                            <div class="bar-fill" style="width: <?= $pct ?>%; background: #122A1C;"></div>
                            <div class="bar-amount"><?= formatRupiah((float) $row['total']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Performa Designer (90 hari terakhir)</h2>
            <table class="rank-table">
                <thead>
                    <tr><th>Designer</th><th>Total Ditugaskan</th><th>Selesai</th><th>Completion Rate</th><th>Rata-rata Waktu</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($designerPerformance as $d): ?>
                        <?php
                            $rate = $d['completion_rate'] ?? null;
                            $rateClass = $rate === null ? '' : ($rate >= 80 ? 'rate-high' : ($rate >= 50 ? 'rate-mid' : 'rate-low'));
                        ?>
                        <tr>
                            <td><?= e($d['name']) ?></td>
                            <td><?= (int) $d['total_assigned'] ?></td>
                            <td><?= (int) $d['completed'] ?></td>
                            <td><?php if ($rate !== null): ?><span class="rate-badge <?= $rateClass ?>"><?= $rate ?>%</span><?php else: ?>—<?php endif; ?></td>
                            <td><?= $d['avg_days'] !== null ? $d['avg_days'] . ' hari' : '—' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($designerPerformance)): ?><div class="empty-state">Belum ada data designer</div><?php endif; ?>
        </div>
    </div>
</body>
</html>
