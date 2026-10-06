<?php
$assignedOrders = $assignedOrders ?? [];
$statusCounts = $statusCounts ?? [];
$urgentCount = $urgentCount ?? 0;
$overdueCount = $overdueCount ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .designer-dashboard {
            max-width: 1200px; margin: 2rem auto; padding: 2rem;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #EAF5D0 0%, #DCEEDF 100%);
            border-left: 4px solid #122A1C; padding: 1.5rem; border-radius: 8px;
        }
        .stat-card h3 {
            font-size: 0.9rem; color: #666; margin: 0 0 0.5rem; text-transform: uppercase;
        }
        .stat-card .number {
            font-size: 2rem; font-weight: bold; color: #122A1C;
        }
        .urgent .number { color: #cc0000; }
        .overdue .number { color: #ff6600; }

        .orders-section {
            margin-top: 2rem;
        }
        .orders-table {
            width: 100%; border-collapse: collapse; background: white;
        }
        .orders-table thead {
            background: #122A1C; color: white;
        }
        .orders-table th {
            padding: 1rem; text-align: left; font-weight: 600;
        }
        .orders-table td {
            padding: 1rem; border-bottom: 1px solid #D3DBD5;
        }
        .orders-table tbody tr:hover {
            background: #F7FBF3;
        }
        .status-badge {
            display: inline-block; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.85rem;
        }
        .status-pending { background: #fff3e7; color: #cc6600; }
        .status-in_progress { background: #e7f3ff; color: #0066cc; }
        .status-revision { background: #ffe7f0; color: #cc0066; }
        .status-completed { background: #e7f5e7; color: #006600; }

        .priority-badge {
            display: inline-block; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;
        }
        .priority-low { background: #e7f5e7; color: #006600; }
        .priority-medium { background: #fff3e7; color: #cc6600; }
        .priority-high { background: #ffe7e7; color: #cc0000; }
        .priority-urgent { background: #f5e7ff; color: #660099; font-weight: bold; }

        .quick-action-btn {
            padding: 0.5rem 1rem; font-size: 0.85rem; background: #122A1C; color: white;
            border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;
        }
        .quick-action-btn:hover { background: #0A1911; }

        .empty-state {
            text-align: center; padding: 3rem; color: #999;
        }
    </style>
</head>
<body>
    <div class="designer-dashboard">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1><?= e($title) ?></h1>
                <p style="color: #666; margin-top: 0.5rem;">Kelola pesanan yang di-assign kepada Anda</p>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <?php require VIEW_PATH . '/partials/notification-bell.php'; ?>
                <a href="<?= APP_URL ?>/dashboard" class="quick-action-btn">← Profil</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pesanan</h3>
                <div class="number"><?= count($assignedOrders) ?></div>
            </div>
            <div class="stat-card">
                <h3>Sedang Dikerjakan</h3>
                <div class="number"><?= $statusCounts['in_progress'] ?? 0 ?></div>
            </div>
            <div class="stat-card urgent">
                <h3>Urgent</h3>
                <div class="number"><?= $urgentCount ?></div>
            </div>
            <div class="stat-card overdue">
                <h3>Overdue</h3>
                <div class="number"><?= $overdueCount ?></div>
            </div>
        </div>

        <div class="orders-section">
            <h2>Pesanan Saya</h2>
            
            <?php if (empty($assignedOrders)): ?>
                <div class="empty-state">
                    <p>Belum ada pesanan yang di-assign</p>
                    <p style="font-size: 0.9rem;">Hubungi manager untuk mendapatkan pesanan baru</p>
                </div>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order Code</th>
                            <th>Klien</th>
                            <th>Jasa</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignedOrders as $order): ?>
                            <tr>
                                <td><strong><?= e($order['order_code']) ?></strong></td>
                                <td><?= e($order['client_name']) ?></td>
                                <td><?= e($order['service_name']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $order['status'] ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                    <?php if ($order['priority'] !== 'medium'): ?>
                                        <span class="priority-badge priority-<?= $order['priority'] ?>">
                                            <?= ucfirst($order['priority']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($order['deadline_date']): ?>
                                        <span <?= strtotime($order['deadline_date']) < time() ? 'style="color: #cc0000; font-weight: bold;"' : '' ?>>
                                            <?= date('d M Y', strtotime($order['deadline_date'])) ?>
                                        </span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= APP_URL ?>/designer/orders/<?= (int)$order['id'] ?>" class="quick-action-btn">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
