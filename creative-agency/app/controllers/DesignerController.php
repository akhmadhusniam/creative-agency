<?php
// ============================================================
//  app/controllers/DesignerController.php
//  Dashboard untuk Designer/Staff
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/RolePermission.php';

class DesignerController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
        Session::requireRole('designer');
    }

    /**
     * Dashboard Designer - Tampilkan order yang di-assign
     */
    public function dashboard(): void
    {
        $userId = Session::userId();

        // Order yang di-assign ke designer ini
        $assignedOrders = $this->db->prepare('
            SELECT o.*, u.name AS client_name, s.name AS service_name,
                   m.name AS manager_name, p.status AS payment_status
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users m ON o.manager_id = m.id
            LEFT JOIN payments p ON o.id = p.order_id
            WHERE o.assigned_to = ? AND o.status != "cancelled"
            ORDER BY o.priority DESC, o.deadline_date ASC
        ');
        $assignedOrders->execute([$userId]);
        $assignedOrders = $assignedOrders->fetchAll();

        // Count orders by status
        $statusCounts = $this->db->prepare('
            SELECT status, COUNT(*) AS count
            FROM orders
            WHERE assigned_to = ? AND status != "cancelled"
            GROUP BY status
        ');
        $statusCounts->execute([$userId]);
        $statusCounts = $statusCounts->fetchAll(PDO::FETCH_KEY_PAIR);

        // Urgency count
        $urgentCount = $this->db->prepare('
            SELECT COUNT(*) FROM orders
            WHERE assigned_to = ? AND priority IN ("high", "urgent")
            AND status IN ("pending", "confirmed", "in_progress")
        ');
        $urgentCount->execute([$userId]);
        $urgentCount = $urgentCount->fetchColumn();

        // Overdue count
        $overdueCount = $this->db->prepare('
            SELECT COUNT(*) FROM orders
            WHERE assigned_to = ? AND deadline_date < NOW()
            AND status NOT IN ("completed", "cancelled")
        ');
        $overdueCount->execute([$userId]);
        $overdueCount = $overdueCount->fetchColumn();

        $title = 'Designer Dashboard - Pesanan Saya';
        require VIEW_PATH . '/designer/dashboard.php';
    }

    /**
     * Lihat detail order
     */
    public function viewOrder(int $id): void
    {
        $userId = Session::userId();

        // Cek apakah designer ini memiliki akses ke order ini
        if (!RolePermission::canAccessOrder($id)) {
            http_response_code(403);
            die('Anda tidak memiliki akses ke pesanan ini.');
        }

        $order = $this->db->prepare('
            SELECT o.*, u.name AS client_name, u.email AS client_email, u.phone,
                   s.name AS service_name, s.description, m.name AS manager_name,
                   p.status AS payment_status, p.id AS payment_id
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users m ON o.manager_id = m.id
            LEFT JOIN payments p ON o.id = p.order_id
            WHERE o.id = ? AND o.assigned_to = ?
        ');
        $order->execute([$id, $userId]);
        $order = $order->fetch();

        if (!$order) {
            Session::flash('error', 'Pesanan tidak ditemukan.');
            redirect(APP_URL . '/designer/dashboard');
        }

        $title = 'Pesanan #' . e($order['order_code']);
        require VIEW_PATH . '/designer/order-detail.php';
    }

    /**
     * Update status order
     */
    public function updateOrderStatus(int $id): void
    {
        verifyCsrf();
        $userId = Session::userId();

        // Cek akses
        $stmt = $this->db->prepare('SELECT assigned_to FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if (!$order || $order['assigned_to'] != $userId) {
            http_response_code(403);
            die('Anda tidak memiliki akses mengubah pesanan ini.');
        }

        $status = $_POST['status'] ?? '';
        $allowedStatuses = ['confirmed', 'in_progress', 'revision', 'completed'];

        if (!in_array($status, $allowedStatuses)) {
            Session::flash('error', 'Status tidak valid.');
            redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/designer/dashboard');
        }

        $stmt = $this->db->prepare('UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $id]);

        Session::flash('success', 'Status pesanan berhasil diperbarui.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Upload revisi/assets
     */
    public function uploadAsset(int $orderId): void
    {
        verifyCsrf();
        $userId = Session::userId();

        // Cek akses
        if (!RolePermission::canAccessOrder($orderId)) {
            http_response_code(403);
            die('Akses ditolak.');
        }

        if (!isset($_FILES['asset'])) {
            Session::flash('error', 'File wajib dipilih.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Simpan file ke folder uploads
        $uploadDir = ROOT_PATH . '/public/uploads/assets/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $_FILES['asset'];
        $fileName = time() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            Session::flash('error', 'Gagal upload file.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Simpan path ke database
        $stmt = $this->db->prepare('
            INSERT INTO order_attachments (order_id, uploaded_by, file_path, created_at)
            VALUES (?, ?, ?, NOW())
        ');
        $stmt->execute([$orderId, $userId, 'assets/' . $fileName]);

        Session::flash('success', 'File berhasil diunggah.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Lihat list pesanan yang pending review
     */
    public function pendingReview(): void
    {
        $userId = Session::userId();

        $orders = $this->db->prepare('
            SELECT o.*, u.name AS client_name, s.name AS service_name,
                   m.name AS manager_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            LEFT JOIN users m ON o.manager_id = m.id
            WHERE o.assigned_to = ? AND o.status IN ("in_progress", "revision")
            ORDER BY o.updated_at DESC
        ');
        $orders->execute([$userId]);
        $orders = $orders->fetchAll();

        $title = 'Pesanan Pending Review';
        require VIEW_PATH . '/designer/pending-review.php';
    }
}
