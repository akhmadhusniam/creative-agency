<?php
// ============================================================
//  app/controllers/OrderController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';

class OrderController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
        Session::requireLogin();
    }

    /**
     * Tampilkan halaman checkout/pembayaran
     */
    public function show(): void
    {
        $serviceSlug = $_GET['service'] ?? null;

        if (!$serviceSlug) {
            redirect(APP_URL . '/services');
        }

        // Cari service berdasarkan slug
        $stmt = $this->db->prepare('
            SELECT s.*, sc.name AS category_name, sc.slug AS category_slug
            FROM services s
            LEFT JOIN service_categories sc ON s.category_id = sc.id
            WHERE s.slug = ? AND s.is_active = 1
            LIMIT 1
        ');
        $stmt->execute([$serviceSlug]);
        $service = $stmt->fetch();

        if (!$service) {
            http_response_code(404);
            $title = 'Layanan Tidak Ditemukan';
            require VIEW_PATH . '/partials/404.php';
            exit;
        }

        // Ambil data user untuk pre-fill form
        $userId = Session::userId();
        $userStmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch();

        $title = 'Checkout — ' . $service['name'];
        require VIEW_PATH . '/order/checkout.php';
    }

    /**
     * Proses pembuatan order
     */
    public function create(): void
    {
        verifyCsrf();
        $userId = Session::userId();

        $serviceId = $_POST['service_id'] ?? null;
        $briefDescription = $_POST['brief'] ?? '';

        if (!$serviceId || empty($briefDescription)) {
            Session::flash('error', 'Mohon isi semua field yang diperlukan.');
            redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/services');
        }

        // Validasi service
        $stmt = $this->db->prepare('SELECT id, price, price_type FROM services WHERE id = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$serviceId]);
        $service = $stmt->fetch();

        if (!$service) {
            Session::flash('error', 'Layanan tidak ditemukan.');
            redirect(APP_URL . '/services');
        }

        // Generate order code
        $orderCode = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);

        // Insert order
        $insertStmt = $this->db->prepare('
            INSERT INTO orders (user_id, service_id, order_code, brief, total, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ');

        $total = ($service['price_type'] === 'custom') ? 0 : (float)$service['price'];

        $insertStmt->execute([
            $userId,
            $serviceId,
            $orderCode,
            $briefDescription,
            $total,
            'pending'
        ]);

        $orderId = $this->db->lastInsertId();

        // Redirect ke payment page
        redirect(APP_URL . '/payment?order=' . $orderId);
    }
}
