<?php
// ============================================================
//  app/controllers/ManagerController.php
//  Dashboard untuk Project Manager
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/RolePermission.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';

class ManagerController
{
    private PDO $db;
    private User $userModel;

    public function __construct()
    {
        $this->db = db();
        $this->userModel = new User();
        Session::start();
        Session::requireRole('manager');
    }

    /**
     * Dashboard Manager - Overview semua order
     */
    public function dashboard(): void
    {
        // Statistics
        $totalOrders = $this->db->query('SELECT COUNT(*) FROM orders')->fetchColumn();
        $pendingOrders = $this->db->query(
            'SELECT COUNT(*) FROM orders WHERE status = "pending"'
        )->fetchColumn();
        $inProgressOrders = $this->db->query(
            'SELECT COUNT(*) FROM orders WHERE status = "in_progress"'
        )->fetchColumn();
        $completedOrders = $this->db->query(
            'SELECT COUNT(*) FROM orders WHERE status = "completed"'
        )->fetchColumn();

        // Recent orders
        $recentOrders = $this->db->query('
            SELECT o.*, u.name AS client_name, s.name AS service_name,
                   d.name AS designer_name, p.status AS payment_status
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users d ON o.assigned_to = d.id
            LEFT JOIN payments p ON o.id = p.order_id
            ORDER BY o.created_at DESC
            LIMIT 10
        ')->fetchAll();

        // Designer workload
        $designerWorkload = $this->db->query('
            SELECT u.id, u.name, COUNT(o.id) AS active_orders,
                   SUM(CASE WHEN o.status = "in_progress" THEN 1 ELSE 0 END) AS in_progress,
                   SUM(CASE WHEN o.priority = "urgent" THEN 1 ELSE 0 END) AS urgent_orders
            FROM users u
            LEFT JOIN orders o ON u.id = o.assigned_to AND o.status != "cancelled"
            WHERE u.role = "designer" AND u.is_active = 1
            GROUP BY u.id
            ORDER BY active_orders DESC
        ')->fetchAll();

        // Overdue orders
        $overdueOrders = $this->db->query('
            SELECT o.*, u.name AS client_name, d.name AS designer_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN users d ON o.assigned_to = d.id
            WHERE o.deadline_date < NOW() AND o.status NOT IN ("completed", "cancelled")
            ORDER BY o.deadline_date ASC
        ')->fetchAll();

        $title = 'Manager Dashboard';
        require VIEW_PATH . '/manager/dashboard.php';
    }

    /**
     * Kelola semua orders
     */
    public function orders(): void
    {
        $statusFilter = $_GET['status'] ?? '';
        $priorityFilter = $_GET['priority'] ?? '';

        $query = '
            SELECT o.*, u.name AS client_name, s.name AS service_name,
                   d.name AS designer_name, m.name AS manager_name,
                   COUNT(DISTINCT p.id) AS payment_count
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users d ON o.assigned_to = d.id
            LEFT JOIN users m ON o.manager_id = m.id
            LEFT JOIN payments p ON o.id = p.order_id
            WHERE 1=1
        ';

        $params = [];

        if ($statusFilter) {
            $query .= ' AND o.status = ?';
            $params[] = $statusFilter;
        }

        if ($priorityFilter) {
            $query .= ' AND o.priority = ?';
            $params[] = $priorityFilter;
        }

        $query .= ' GROUP BY o.id ORDER BY o.created_at DESC';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        $allStatuses = ['pending', 'confirmed', 'in_progress', 'revision', 'completed', 'cancelled'];
        $allPriorities = ['low', 'medium', 'high', 'urgent'];

        $title = 'Kelola Pesanan';
        require VIEW_PATH . '/manager/orders.php';
    }

    /**
     * Edit order dan assign designer
     */
    public function editOrder(int $id): void
    {
        $order = $this->db->prepare('
            SELECT o.*, u.name AS client_name, s.name AS service_name,
                   d.name AS designer_name, m.name AS manager_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users d ON o.assigned_to = d.id
            LEFT JOIN users m ON o.manager_id = m.id
            WHERE o.id = ?
        ');
        $order->execute([$id]);
        $order = $order->fetch();

        if (!$order) {
            Session::flash('error', 'Pesanan tidak ditemukan.');
            redirect(APP_URL . '/manager/orders');
        }

        $designers = $this->userModel->findByRole('designer');
        $managers = $this->userModel->findByRole('manager');

        $title = 'Edit Pesanan #' . e($order['order_code']);
        require VIEW_PATH . '/manager/order-edit.php';
    }

    /**
     * Update order
     */
    public function updateOrder(int $id): void
    {
        verifyCsrf();

        $v = new Validator($_POST);
        $v->required('assigned_to');

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            redirect(APP_URL . '/manager/orders/' . $id . '/edit');
        }

        $assigned_to = $_POST['assigned_to'];
        $manager_id = $_POST['manager_id'] ?? null;
        $priority = $_POST['priority'] ?? 'medium';
        $status = $_POST['status'] ?? '';
        $deadline_date = $_POST['deadline_date'] ?? null;

        // Cek designer valid
        if (!$this->userModel->isDesignerAvailable((int) $assigned_to)) {
            Session::flash('error', 'Designer tidak tersedia.');
            redirect(APP_URL . '/manager/orders/' . $id . '/edit');
        }

        $updates = [
            'assigned_to' => $assigned_to,
            'priority'    => $priority,
            'status'      => $status,
        ];

        if ($manager_id) {
            $updates['manager_id'] = $manager_id;
        }
        if ($deadline_date) {
            $updates['deadline_date'] = $deadline_date;
        }

        $fields = [];
        $params = [];
        foreach ($updates as $col => $val) {
            $fields[] = "$col = :$col";
            $params[$col] = $val;
        }
        $params['id'] = $id;

        $sql = 'UPDATE orders SET ' . implode(', ', $fields) . ', updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        Session::flash('success', 'Pesanan berhasil diperbarui dan di-assign.');
        redirect(APP_URL . '/manager/orders');
    }

    /**
     * Approve / Reject order completion
     */
    public function approveOrder(int $id): void
    {
        verifyCsrf();

        $action = $_POST['action'] ?? '';

        if ($action === 'approve') {
            $status = 'completed';
            $message = 'Pesanan telah disetujui sebagai selesai.';
        } elseif ($action === 'reject') {
            $status = 'revision';
            $message = 'Pesanan dikembalikan untuk revisi.';
        } else {
            Session::flash('error', 'Aksi tidak valid.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $stmt = $this->db->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $id]);

        Session::flash('success', $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * View designer workload
     */
    public function teamWorkload(): void
    {
        $designers = $this->db->query('
            SELECT u.id, u.name, u.department, u.email,
                   COUNT(DISTINCT CASE WHEN o.status != "cancelled" THEN o.id END) AS total_orders,
                   COUNT(DISTINCT CASE WHEN o.status = "pending" THEN o.id END) AS pending_orders,
                   COUNT(DISTINCT CASE WHEN o.status = "in_progress" THEN o.id END) AS in_progress_orders,
                   COUNT(DISTINCT CASE WHEN o.priority = "urgent" THEN o.id END) AS urgent_orders
            FROM users u
            LEFT JOIN orders o ON u.id = o.assigned_to
            WHERE u.role = "designer" AND u.is_active = 1
            GROUP BY u.id
            ORDER BY total_orders DESC
        ')->fetchAll();

        $title = 'Workload Tim Designer';
        require VIEW_PATH . '/manager/team-workload.php';
    }

    /**
     * Report dan analytics
     */
    public function analytics(): void
    {
        // Order completion rate
        $completeRate = $this->db->query('
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) AS completed,
                ROUND(SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) * 100 / COUNT(*), 2) AS rate
            FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ')->fetch();

        // Average turnaround time
        $avgTurnaround = $this->db->query('
            SELECT 
                ROUND(AVG(DATEDIFF(updated_at, created_at)), 1) AS avg_days
            FROM orders
            WHERE status = "completed" AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ')->fetch();

        // Monthly revenue
        $monthlyRevenue = $this->db->query('
            SELECT 
                DATE_FORMAT(p.created_at, "%Y-%m") AS month,
                SUM(p.amount) AS total
            FROM payments p
            WHERE p.status = "paid" AND p.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(p.created_at, "%Y-%m")
            ORDER BY month DESC
        ')->fetchAll();

        $title = 'Analytics & Reporting';
        require VIEW_PATH . '/manager/analytics.php';
    }
}
