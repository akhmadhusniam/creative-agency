<?php
// ============================================================
//  app/controllers/FinanceController.php
//  Dashboard untuk role Finance — payment approval, invoice, revenue report
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/NotificationHelper.php';
require_once ROOT_PATH . '/app/helpers/AuditLog.php';

class FinanceController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
        // Finance, admin, dan owner semua boleh akses (owner/admin inherit permission finance)
        Session::requireRole(['finance', 'admin', 'owner']);
    }

    /**
     * Dashboard Finance — ringkasan pembayaran & revenue
     */
    public function dashboard(): void
    {
        $totalRevenue = $this->db->query('
            SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = "paid"
        ')->fetchColumn();

        $monthRevenue = $this->db->query('
            SELECT COALESCE(SUM(amount), 0) FROM payments
            WHERE status = "paid" AND MONTH(paid_date) = MONTH(NOW()) AND YEAR(paid_date) = YEAR(NOW())
        ')->fetchColumn();

        $pendingCount = $this->db->query('
            SELECT COUNT(*) FROM payments WHERE status = "pending"
        ')->fetchColumn();

        $pendingAmount = $this->db->query('
            SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = "pending"
        ')->fetchColumn();

        $paidCount = $this->db->query('SELECT COUNT(*) FROM payments WHERE status = "paid"')->fetchColumn();

        $statusBreakdown = $this->db->query('
            SELECT status, COUNT(*) AS total, COALESCE(SUM(amount), 0) AS amount
            FROM payments
            GROUP BY status
        ')->fetchAll();

        $recentPayments = $this->db->query('
            SELECT p.*, o.order_code, u.name AS client_name, s.name AS service_name
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            ORDER BY p.created_at DESC
            LIMIT 10
        ')->fetchAll();

        $unreadCount = NotificationHelper::unreadCount(Session::userId());
        $recentNotifications = NotificationHelper::recent(Session::userId());

        $title = 'Finance Dashboard';
        require VIEW_PATH . '/finance/dashboard.php';
    }

    /**
     * Daftar semua pembayaran, dengan filter status
     */
    public function payments(): void
    {
        $statusFilter = $_GET['status'] ?? '';

        $query = '
            SELECT p.*, o.order_code, u.name AS client_name, s.name AS service_name
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            WHERE 1=1
        ';
        $params = [];

        if ($statusFilter) {
            $query .= ' AND p.status = ?';
            $params[] = $statusFilter;
        }

        $query .= ' ORDER BY p.created_at DESC';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $payments = $stmt->fetchAll();

        $allStatuses = ['pending', 'paid', 'expired', 'failed', 'refunded'];

        $title = 'Kelola Pembayaran';
        require VIEW_PATH . '/finance/payments.php';
    }

    /**
     * Approve/rekonsiliasi pembayaran secara manual
     * (untuk transfer manual, atau menandai ulang pembayaran gateway yang statusnya belum sinkron)
     */
    public function approvePayment(int $id): void
    {
        verifyCsrf();

        $stmt = $this->db->prepare('SELECT * FROM payments WHERE id = ?');
        $stmt->execute([$id]);
        $payment = $stmt->fetch();

        if (!$payment) {
            Session::flash('error', 'Pembayaran tidak ditemukan.');
            redirect(APP_URL . '/finance/payments');
        }

        $invoiceNumber = $payment['invoice_number'] ?: $this->generateInvoiceNumber((int) $payment['order_id']);

        $update = $this->db->prepare('
            UPDATE payments SET
                status = "paid",
                paid_date = COALESCE(paid_date, NOW()),
                approver_id = :approver_id,
                invoice_number = :invoice_number,
                notes = :notes
            WHERE id = :id
        ');
        $update->execute([
            'approver_id'    => Session::userId(),
            'invoice_number' => $invoiceNumber,
            'notes'          => trim($_POST['notes'] ?? $payment['notes'] ?? ''),
            'id'             => $id,
        ]);

        // Sinkronkan status order juga
        $this->db->prepare('UPDATE orders SET status = "confirmed" WHERE id = ? AND status = "pending"')
                  ->execute([$payment['order_id']]);

        $orderRow = $this->db->prepare('SELECT user_id, order_code FROM orders WHERE id = ?');
        $orderRow->execute([$payment['order_id']]);
        $orderRow = $orderRow->fetch();

        if ($orderRow) {
            NotificationHelper::notify(
                (int) $orderRow['user_id'],
                'Pembayaran dikonfirmasi',
                "Pembayaran untuk order {$orderRow['order_code']} sudah kami konfirmasi. Invoice {$invoiceNumber} tersedia.",
                'payment',
                'payment',
                $id
            );
        }

        if (function_exists('logAudit')) {
            logAudit('payment', $id, 'approve', ['invoice_number' => $invoiceNumber]);
        }

        Session::flash('success', 'Pembayaran disetujui dan invoice dibuat.');
        redirect(APP_URL . '/finance/payments');
    }

    /**
     * Tampilkan invoice (halaman print-friendly, bisa disimpan sebagai PDF via print browser)
     */
    public function invoice(int $id): void
    {
        $stmt = $this->db->prepare('
            SELECT p.*, o.order_code, o.brief, o.total AS order_total,
                   u.name AS client_name, u.email AS client_email, u.phone AS client_phone,
                   s.name AS service_name
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN users u ON o.user_id = u.id
            JOIN services s ON o.service_id = s.id
            WHERE p.id = ?
        ');
        $stmt->execute([$id]);
        $payment = $stmt->fetch();

        if (!$payment) {
            http_response_code(404);
            $title = 'Invoice Tidak Ditemukan';
            require VIEW_PATH . '/partials/404.php';
            exit;
        }

        // Generate nomor invoice on-the-fly kalau belum ada (misal untuk pratinjau sebelum approve)
        if (!$payment['invoice_number']) {
            $payment['invoice_number'] = $this->generateInvoiceNumber((int) $payment['order_id']);
            $this->db->prepare('UPDATE payments SET invoice_number = ? WHERE id = ?')
                      ->execute([$payment['invoice_number'], $id]);
        }

        $title = 'Invoice ' . $payment['invoice_number'];
        require VIEW_PATH . '/finance/invoice.php';
    }

    /**
     * Laporan revenue — bulanan & per layanan
     */
    public function reports(): void
    {
        $monthlyRevenue = $this->db->query('
            SELECT DATE_FORMAT(paid_date, "%Y-%m") AS month, SUM(amount) AS total, COUNT(*) AS count
            FROM payments
            WHERE status = "paid" AND paid_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(paid_date, "%Y-%m")
            ORDER BY month ASC
        ')->fetchAll();

        $revenueByService = $this->db->query('
            SELECT s.name AS service_name, SUM(p.amount) AS total, COUNT(*) AS count
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN services s ON o.service_id = s.id
            WHERE p.status = "paid"
            GROUP BY s.id
            ORDER BY total DESC
            LIMIT 10
        ')->fetchAll();

        $topClients = $this->db->query('
            SELECT u.name AS client_name, SUM(p.amount) AS total, COUNT(*) AS count
            FROM payments p
            JOIN orders o ON p.order_id = o.id
            JOIN users u ON o.user_id = u.id
            WHERE p.status = "paid"
            GROUP BY u.id
            ORDER BY total DESC
            LIMIT 10
        ')->fetchAll();

        $title = 'Laporan Revenue';
        require VIEW_PATH . '/finance/reports.php';
    }

    private function generateInvoiceNumber(int $orderId): string
    {
        return 'INV-' . date('Ymd') . '-' . str_pad((string) $orderId, 5, '0', STR_PAD_LEFT);
    }
}
