<?php
$totalOrders = $totalOrders ?? 0;
$pendingOrders = $pendingOrders ?? 0;
$inProgressOrders = $inProgressOrders ?? 0;
$completedOrders = $completedOrders ?? 0;
$recentOrders = $recentOrders ?? [];
$designerWorkload = $designerWorkload ?? [];
$overdueOrders = $overdueOrders ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
    <style>
        .manager-dashboard {
            max-width: 1400px; margin: 2rem auto; padding: 2rem;
        }
        .dashboard-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;
        }
        .quick-nav {
            display: flex; gap: 1rem;
        }
        .quick-nav a {
            padding: 0.7rem 1.5rem; background: #2c2c2c; color: white; text-decoration: none;
            border-radius: 4px; font-size: 0.9rem;
        }
        .quick-nav a:hover {
            background: #1c1c1c;
        }

        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #f5f1eb 0%, #fff0e6 100%);
            border-left: 4px solid #2c2c2c; padding: 1.5rem; border-radius: 8px;
        }
        .stat-card h3 {
            font-size: 0.9rem; color: #666; margin: 0 0 0.5rem; text-transform: uppercase;
        }
        .stat-card .number {
            font-size: 2.5rem; font-weight: bold; color: #2c2c2c;
        }

        .section {
            margin-top: 2rem; background: white; padding: 2rem; border-radius: 8px;
        }
        .section h2 {
            margin-top: 0; border-bottom: 2px solid #f5f1eb; padding-bottom: 1rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
        .orders-table {
            width: 100%; border-collapse: collapse;
        }
        .orders-table thead {
            background: #2c2c2c; color: white;
        }
        .orders-table th {
            padding: 1rem; text-align: left; font-weight: 600; font-size: 0.9rem;
        }
        .orders-table td {
            padding: 0.8rem; border-bottom: 1px solid #e8e6e1; font-size: 0.9rem;
        }
        .orders-table tbody tr:hover {
            background: #fffaf5;
        }

        .badge {
            display: inline-block; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.8rem;
        }
        .badge-pending { background: #fff3e7; color: #cc6600; }
        .badge-in_progress { background: #e7f3ff; color: #0066cc; }
        .badge-revision { background: #ffe7f0; color: #cc0066; }
        .badge-completed { background: #e7f5e7; color: #006600; }
        
        .priority-low { background: #e7f5e7; }
        .priority-medium { background: #fff3e7; }
        .priority-high { background: #ffe7e7; }
        .priority-urgent { background: #f5e7ff; font-weight: bold; }

        .action-btn {
            padding: 0.4rem 0.8rem; background: #2c2c2c; color: white;
            border: none; cursor: pointer; border-radius: 3px; text-decoration: none; font-size: 0.8rem;
        }
        .action-btn:hover { background: #1c1c1c; }

        .empty-state {
            text-align: center; padding: 2rem; color: #999;
        }

        .danger-alert {
            background: #ffe7e7; border-left: 4px solid #cc0000; padding: 1rem; border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .danger-alert strong {
            color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="manager-dashboard">
        <div class="dashboard-header">
            <div>
                <h1><?= e($title) ?></h1>
                <p style="color: #666; margin-top: 0.5rem;">Kelola pesanan dan tim desainer</p>
            </div>
            <div class="quick-nav">
                <a href="<?= APP_URL ?>/manager/orders">Pesanan</a>
                <a href="<?= APP_URL ?>/manager/team">Tim</a>
                <a href="<?= APP_URL ?>/manager/analytics">Analytics</a>
                <a href="<?= APP_URL ?>/dashboard">← Profil</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pesanan</h3>
                <div class="number"><?= $totalOrders ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending</h3>
                <div class="number" style="color: #ff9900;"><?= $pendingOrders ?></div>
            </div>
            <div class="stat-card">
                <h3>In Progress</h3>
                <div class="number" style="color: #0066cc;"><?= $inProgressOrders ?></div>
            </div>
            <div class="stat-card">
                <h3>Completed</h3>
                <div class="number" style="color: #006600;"><?= $completedOrders ?></div>
            </div>
        </div>

        <?php if (!empty($overdueOrders)): ?>
            <div class="danger-alert">
                <strong>⚠️ <?= count($overdueOrders) ?> Pesanan Overdue!</strong>
                <p><?php foreach ($overdueOrders as $order): ?>
                    <?= e($order['order_code']) ?> (<?= e($order['client_name']) ?>) - Deadline: <?= date('d M Y', strtotime($order['deadline_date'])) ?><br>
                <?php endforeach; ?></p>
            </div>
        <?php endif; ?>

        <div class="section">
            <h2>Designer Workload</h2>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Designer</th>
                            <th>Total Orders</th>
                            <th>In Progress</th>
                            <th>Urgent</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($designerWorkload as $designer): ?>
                            <tr>
                                <td><strong><?= e($designer['name']) ?></strong></td>
                                <td><?= $designer['active_orders'] ?></td>
                                <td><?= $designer['in_progress'] ?></td>
                                <td><span class="badge priority-<?= $designer['urgent_orders'] > 0 ? 'urgent' : 'low' ?>"><?= $designer['urgent_orders'] ?></span></td>
                                <td>
                                    <?php if ($designer['active_orders'] > 5): ?>
                                        <span class="badge" style="background: #ffe7e7; color: #cc0000;">Overloaded</span>
                                    <?php elseif ($designer['active_orders'] > 3): ?>
                                        <span class="badge" style="background: #fff3e7; color: #cc6600;">Busy</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #e7f5e7; color: #006600;">Available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <h2>Recent Orders</h2>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order Code</th>
                            <th>Klien</th>
                            <th>Jasa</th>
                            <th>Designer</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><strong><?= e($order['order_code']) ?></strong></td>
                                <td><?= e($order['client_name']) ?></td>
                                <td><?= e($order['service_name']) ?></td>
                                <td><?= e($order['designer_name'] ?? 'Unassigned') ?></td>
                                <td><span class="badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                                <td><?= $order['payment_status'] ? ucfirst($order['payment_status']) : 'Pending' ?></td>
                                <td>
                                    <a href="<?= APP_URL ?>/manager/orders/<?= (int)$order['id'] ?>/edit" class="action-btn">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (empty($recentOrders)): ?>
                <div class="empty-state">Belum ada pesanan</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
