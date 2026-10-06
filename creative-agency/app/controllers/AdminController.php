<?php
// ============================================================
//  app/controllers/AdminController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/RolePermission.php';

class AdminController
{
    private PDO $db;
    private User $userModel;

    public function __construct()
    {
        $this->db = db();
        $this->userModel = new User();
        Session::start();
        Session::requireAdmin();
    }

    /**
     * Dashboard Admin/Owner dengan role-based view
     */
    public function dashboard(): void
    {
        $userRole = Session::userRole();
        
        // Statistik umum
        $totalOrdersStmt = $this->db->query('SELECT COUNT(*) AS total FROM orders');
        $totalOrders = $totalOrdersStmt->fetchColumn();

        $pendingOrdersStmt = $this->db->query('SELECT COUNT(*) AS total FROM orders WHERE status = "pending"');
        $pendingOrders = $pendingOrdersStmt->fetchColumn();

        $paidOrdersStmt = $this->db->query('
            SELECT COUNT(*) AS total FROM orders o
            JOIN payments p ON o.id = p.order_id
            WHERE p.status = "paid"
        ');
        $paidOrders = $paidOrdersStmt->fetchColumn();

        $recentOrders = $this->db->query('
            SELECT o.*, u.name, u.phone, s.name AS service_name, 
                   d.name AS designer_name, m.name AS manager_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users d ON o.assigned_to = d.id
            LEFT JOIN users m ON o.manager_id = m.id
            ORDER BY o.created_at DESC
            LIMIT 10
        ')->fetchAll();

        // Staff stats
        $staffStats = $this->db->query('
            SELECT role, COUNT(*) AS count
            FROM users
            WHERE role IN ("designer", "manager", "finance", "admin", "owner")
            AND is_active = 1
            GROUP BY role
        ')->fetchAll();

        $title = 'Admin Dashboard';
        require VIEW_PATH . '/admin/dashboard.php';
    }

    /**
     * Kelola Orders dengan assignment
     */
    public function orders(): void
    {
        $orders = $this->db->query('
            SELECT o.*, u.name AS client_name, s.name AS service_name, 
                   d.name AS designer_name, m.name AS manager_name,
                   COUNT(DISTINCT p.id) AS payment_count
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users d ON o.assigned_to = d.id
            LEFT JOIN users m ON o.manager_id = m.id
            LEFT JOIN payments p ON o.id = p.order_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ')->fetchAll();

        $title = 'Kelola Pesanan';
        require VIEW_PATH . '/admin/orders.php';
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
            redirect(APP_URL . '/admin/orders');
        }

        $designers = $this->userModel->findByRole('designer');
        $managers = $this->userModel->findByRole('manager');

        $title = 'Edit Pesanan #' . e($order['order_code']);
        require VIEW_PATH . '/admin/order-edit.php';
    }

    /**
     * Update order assignment
     */
    public function updateOrder(int $id): void
    {
        verifyCsrf();

        $assigned_to = $_POST['assigned_to'] ?? null;
        $manager_id = $_POST['manager_id'] ?? null;
        $priority = $_POST['priority'] ?? 'medium';
        $status = $_POST['status'] ?? '';
        $deadline_date = $_POST['deadline_date'] ?? null;

        $updates = [
            'priority' => $priority,
            'status'   => $status,
        ];

        if ($assigned_to) {
            $updates['assigned_to'] = $assigned_to;
        }
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

        Session::flash('success', 'Pesanan berhasil diperbarui.');
        redirect(APP_URL . '/admin/orders/' . $id . '/edit');
    }

    /**
     * Kelola Services
     */
    public function services(): void
    {
        $services = $this->db->query('
            SELECT s.*, sc.name AS category_name, COUNT(o.id) AS order_count
            FROM services s
            LEFT JOIN service_categories sc ON s.category_id = sc.id
            LEFT JOIN orders o ON s.id = o.service_id
            GROUP BY s.id
            ORDER BY s.sort_order, s.name
        ')->fetchAll();

        $title = 'Kelola Layanan';
        require VIEW_PATH . '/admin/services.php';
    }

    /**
     * Kelola Portfolio
     */
    public function portfolio(): void
    {
        $portfolio = $this->db->query('
            SELECT p.*, sc.name AS category_name
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            ORDER BY p.sort_order, p.id DESC
        ')->fetchAll();

        $title = 'Kelola Portfolio';
        require VIEW_PATH . '/admin/portfolio.php';
    }

    /**
     * Manage Users/Staff
     */
    public function users(): void
    {
        $users = $this->db->query('
            SELECT id, name, email, phone, role, department, is_active, hire_date
            FROM users
            WHERE role IN ("designer", "manager", "finance", "admin", "owner")
            ORDER BY role DESC, name
        ')->fetchAll();

        $title = 'Kelola Staff';
        require VIEW_PATH . '/admin/users.php';
    }

    /**
     * Edit user / change role
     */
    public function editUser(int $id): void
    {
        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::flash('error', 'User tidak ditemukan.');
            redirect(APP_URL . '/admin/users');
        }

        $roles = RolePermission::getAllRoles();
        $title = 'Edit User: ' . e($user['name']);
        require VIEW_PATH . '/admin/user-edit.php';
    }

    /**
     * Update user role
     */
    public function updateUser(int $id): void
    {
        verifyCsrf();

        $v = new Validator($_POST);
        $v->required(['role', 'department']);

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            redirect(APP_URL . '/admin/users/' . $id . '/edit');
        }

        $role = $_POST['role'];
        $department = trim($_POST['department']);
        $allowed_roles = array_keys(RolePermission::getAllRoles());

        if (!in_array($role, $allowed_roles)) {
            Session::flash('error', 'Role tidak valid.');
            redirect(APP_URL . '/admin/users/' . $id . '/edit');
        }

        $this->userModel->updateRole($id, $role, $department);

        // Log perubahan
        if (function_exists('logAudit')) {
            logAudit('user', $id, 'update_role', ['role' => $role, 'department' => $department]);
        }

        Session::flash('success', 'User berhasil diperbarui.');
        redirect(APP_URL . '/admin/users');
    }

    /**
     * Toggle aktivasi user
     */
    public function toggleUserStatus(int $id): void
    {
        verifyCsrf();

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::flash('error', 'User tidak ditemukan.');
            redirect(APP_URL . '/admin/users');
        }

        if ($user['is_active']) {
            $this->userModel->deactivate($id);
            $msg = 'User berhasil dinonaktifkan.';
        } else {
            $this->userModel->activate($id);
            $msg = 'User berhasil diaktifkan.';
        }

        Session::flash('success', $msg);
        redirect(APP_URL . '/admin/users');
    }

    /**
     * Update status order
     */
    public function updateOrderStatus(int $id): void
    {
        verifyCsrf();

        $status = $_POST['status'] ?? '';
        $allowedStatuses = ['pending', 'confirmed', 'in_progress', 'revision', 'completed', 'cancelled'];

        if (!in_array($status, $allowedStatuses)) {
            Session::flash('error', 'Status tidak valid.');
            redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/admin/orders');
        }

        $stmt = $this->db->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $id]);

        Session::flash('success', 'Status pesanan berhasil diperbarui.');
        redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/admin/orders');
    }
}
