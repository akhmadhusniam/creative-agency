<?php $designers = $designers ?? []; ?>
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
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.2rem; }
        .designer-card { background: white; border-radius: 8px; padding: 1.5rem; border-left: 4px solid #122A1C; }
        .designer-card h3 { font-size: 1.05rem; margin-bottom: 0.2rem; }
        .designer-card .dept { font-size: 0.8rem; color: #999; margin-bottom: 1rem; }
        .mini-stats { display: flex; gap: 1.2rem; flex-wrap: wrap; }
        .mini-stat { text-align: center; }
        .mini-stat .num { font-size: 1.4rem; font-weight: bold; }
        .mini-stat .lbl { font-size: 0.72rem; color: #999; text-transform: uppercase; }
        .mini-stat.urgent .num { color: #cc0000; }
        .mini-stat.progress .num { color: #cc6600; }
        .empty-state { text-align: center; padding: 3rem; color: #999; background: white; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="mg-page">
        <div class="dashboard-header">
            <h1><?= e($title) ?></h1>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/manager/dashboard">Dashboard</a>
                <a href="<?= APP_URL ?>/manager/analytics">Analytics</a>
                <?php require VIEW_PATH . '/partials/notification-bell.php'; ?>
            </div>
        </div>

        <div class="cards-grid">
            <?php foreach ($designers as $d): ?>
                <div class="designer-card">
                    <h3><?= e($d['name']) ?></h3>
                    <div class="dept"><?= e($d['department'] ?? 'Design') ?> — <?= e($d['email']) ?></div>
                    <div class="mini-stats">
                        <div class="mini-stat">
                            <div class="num"><?= (int) $d['total_orders'] ?></div>
                            <div class="lbl">Total</div>
                        </div>
                        <div class="mini-stat progress">
                            <div class="num"><?= (int) $d['in_progress_orders'] ?></div>
                            <div class="lbl">On Progress</div>
                        </div>
                        <div class="mini-stat">
                            <div class="num"><?= (int) $d['pending_orders'] ?></div>
                            <div class="lbl">Pending</div>
                        </div>
                        <div class="mini-stat urgent">
                            <div class="num"><?= (int) $d['urgent_orders'] ?></div>
                            <div class="lbl">Urgent</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($designers)): ?>
            <div class="empty-state">Belum ada designer aktif</div>
        <?php endif; ?>
    </div>
</body>
</html>
