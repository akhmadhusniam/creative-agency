<?php
// ============================================================
//  app/controllers/PaymentController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/PaymentGateway.php';

class PaymentController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
    }

    /**
     * Tampilkan halaman metode pembayaran
     */
    public function show(): void
    {
        Session::requireLogin();

        $orderId = $_GET['order'] ?? null;

        if (!$orderId) {
            redirect(APP_URL . '/services');
        }

        // Cek order exists dan milik user
        $stmt = $this->db->prepare('
            SELECT o.*, s.name AS service_name, s.price, s.price_type, u.name, u.email
            FROM orders o
            JOIN services s ON o.service_id = s.id
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ? AND o.user_id = ?
        ');
        $stmt->execute([$orderId, Session::userId()]);
        $order = $stmt->fetch();

        if (!$order) {
            http_response_code(404);
            $title = 'Order Tidak Ditemukan';
            require VIEW_PATH . '/partials/404.php';
            exit;
        }

        $title = 'Pembayaran — ' . $order['service_name'];
        require VIEW_PATH . '/payment/show.php';
    }

    /**
     * Proses pembayaran (Midtrans Snap)
     */
    public function process(): void
    {
        Session::requireLogin();
        verifyCsrf();

        $orderId = $_POST['order_id'] ?? null;

        // Validasi order
        $stmt = $this->db->prepare('
            SELECT o.*, s.price, s.name AS service_name FROM orders o
            JOIN services s ON o.service_id = s.id
            WHERE o.id = ? AND o.user_id = ? AND o.status = "pending"
        ');
        $stmt->execute([$orderId, Session::userId()]);
        $order = $stmt->fetch();

        if (!$order) {
            Session::flash('error', 'Order tidak ditemukan atau sudah diproses.');
            redirect(APP_URL . '/dashboard/orders');
        }

        // Get user data
        $userStmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $userStmt->execute([Session::userId()]);
        $user = $userStmt->fetch();

        // Create token via Midtrans
        $response = PaymentGateway::midtransCreateToken($order, $user);

        if (isset($response['error'])) {
            Session::flash('error', 'Gagal membuat pembayaran: ' . $response['error']);
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Redirect ke Midtrans Snap
        if (isset($response['redirect_url'])) {
            // Catat transaksi di tabel payments (upsert, jaga-jaga user retry pembayaran)
            $upsert = $this->db->prepare('
                INSERT INTO payments (order_id, gateway, transaction_id, amount, status, gateway_payload)
                VALUES (:order_id, "midtrans", :transaction_id, :amount, "pending", :payload)
                ON DUPLICATE KEY UPDATE
                    transaction_id = VALUES(transaction_id),
                    amount = VALUES(amount),
                    gateway_payload = VALUES(gateway_payload)
            ');
            $upsert->execute([
                'order_id'       => $order['id'],
                'transaction_id' => $response['token'] ?? $order['order_code'],
                'amount'         => $order['total'],
                'payload'        => json_encode($response),
            ]);

            redirect($response['redirect_url']);
        }

        Session::flash('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Callback sukses pembayaran (Midtrans Snap)
     */
    public function finish(): void
    {
        Session::requireLogin();

        $orderId = $_GET['order_id'] ?? null;

        if (!$orderId) {
            redirect(APP_URL . '/dashboard');
        }

        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
        $stmt->execute([$orderId, Session::userId()]);
        $order = $stmt->fetch();

        if ($order) {
            $title = 'Pembayaran Berhasil!';
        } else {
            $title = 'Order Tidak Ditemukan';
            http_response_code(404);
        }

        require VIEW_PATH . '/payment/finish.php';
    }

    /**
     * Callback gagal pembayaran
     */
    public function error(): void
    {
        Session::requireLogin();

        $orderId = $_GET['order_id'] ?? null;

        $title = 'Pembayaran Gagal';
        require VIEW_PATH . '/payment/error.php';
    }

    /**
     * Status pembayaran pending
     */
    public function pending(): void
    {
        Session::requireLogin();

        $orderId = $_GET['order_id'] ?? null;

        $title = 'Pembayaran Menunggu';
        require VIEW_PATH . '/payment/pending.php';
    }

    /**
     * Webhook Midtrans
     */
    public function webhookMidtrans(): void
    {
        $json = file_get_contents('php://input');
        $notification = json_decode($json);

        $isSignatureValid = $this->verifyMidtransSignature($notification);

        if (!$isSignatureValid) {
            http_response_code(403);
            die('Invalid signature');
        }

        $orderId = $notification->order_id ?? null;
        $transactionStatus = $notification->transaction_status ?? null;

        if (!$orderId || !$transactionStatus) {
            http_response_code(400);
            die('Invalid notification');
        }

        $orderStatus = 'pending';
        $paymentStatus = 'pending';
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $orderStatus = 'confirmed';
            $paymentStatus = 'paid';
        } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
            $orderStatus = 'cancelled';
            $paymentStatus = 'failed';
        } elseif ($transactionStatus === 'expire') {
            $orderStatus = 'cancelled';
            $paymentStatus = 'expired';
        }

        $stmt = $this->db->prepare('UPDATE orders SET status = ? WHERE order_code = ?');
        $stmt->execute([$orderStatus, $orderId]);

        $orderRow = $this->db->prepare('SELECT id, total FROM orders WHERE order_code = ?');
        $orderRow->execute([$orderId]);
        $orderRow = $orderRow->fetch();

        if ($orderRow) {
            $paymentUpsert = $this->db->prepare('
                INSERT INTO payments (order_id, gateway, transaction_id, payment_method, amount, status, paid_date, gateway_payload)
                VALUES (:order_id, "midtrans", :transaction_id, :payment_method, :amount, :status, :paid_date, :payload)
                ON DUPLICATE KEY UPDATE
                    status = VALUES(status),
                    payment_method = VALUES(payment_method),
                    paid_date = VALUES(paid_date),
                    gateway_payload = VALUES(gateway_payload)
            ');
            $paymentUpsert->execute([
                'order_id'       => $orderRow['id'],
                'transaction_id' => $notification->transaction_id ?? $orderId,
                'payment_method' => $notification->payment_type ?? null,
                'amount'         => $orderRow['total'],
                'status'         => $paymentStatus,
                'paid_date'      => $paymentStatus === 'paid' ? date('Y-m-d H:i:s') : null,
                'payload'        => $json,
            ]);
        }

        http_response_code(200);
    }

    /**
     * Webhook Xendit (Invoice callback)
     * Docs: https://developers.xendit.co/api-reference/#invoice-callback
     */
    public function webhookXendit(): void
    {
        // Xendit mengirim token verifikasi di header, bukan HMAC seperti Midtrans.
        $callbackToken = $_SERVER['HTTP_X_CALLBACK_TOKEN'] ?? '';

        if (!hash_equals(XENDIT_WEBHOOK_TOKEN, $callbackToken)) {
            http_response_code(401);
            die('Invalid callback token');
        }

        $json = file_get_contents('php://input');
        $notification = json_decode($json);

        if (!$notification) {
            http_response_code(400);
            die('Invalid payload');
        }

        // external_id yang dikirim saat membuat invoice = order_code
        $orderCode = $notification->external_id ?? null;
        $status    = $notification->status ?? null; // PAID, EXPIRED, PENDING, dll

        if (!$orderCode || !$status) {
            http_response_code(400);
            die('Invalid notification');
        }

        $orderStmt = $this->db->prepare('SELECT id, total FROM orders WHERE order_code = ?');
        $orderStmt->execute([$orderCode]);
        $order = $orderStmt->fetch();

        if (!$order) {
            http_response_code(404);
            die('Order not found');
        }

        $orderStatus = match ($status) {
            'PAID', 'SETTLED' => 'confirmed',
            'EXPIRED'         => 'cancelled',
            default           => 'pending',
        };
        $paymentStatus = match ($status) {
            'PAID', 'SETTLED' => 'paid',
            'EXPIRED'         => 'expired',
            default           => 'pending',
        };

        $stmt = $this->db->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$orderStatus, $order['id']]);

        $paymentUpsert = $this->db->prepare('
            INSERT INTO payments (order_id, gateway, transaction_id, payment_method, amount, status, paid_date, gateway_payload)
            VALUES (:order_id, "xendit", :transaction_id, :payment_method, :amount, :status, :paid_date, :payload)
            ON DUPLICATE KEY UPDATE
                status = VALUES(status),
                payment_method = VALUES(payment_method),
                paid_date = VALUES(paid_date),
                gateway_payload = VALUES(gateway_payload)
        ');
        $paymentUpsert->execute([
            'order_id'       => $order['id'],
            'transaction_id' => $notification->id ?? $orderCode,
            'payment_method' => $notification->payment_channel ?? ($notification->payment_method ?? null),
            'amount'         => $order['total'],
            'status'         => $paymentStatus,
            'paid_date'      => $paymentStatus === 'paid' ? date('Y-m-d H:i:s') : null,
            'payload'        => $json,
        ]);

        http_response_code(200);
    }

    /**
     * Verify Midtrans signature
     */
    private function verifyMidtransSignature(object $notification): bool
    {
        $orderId = $notification->order_id ?? '';
        $statusCode = $notification->status_code ?? '';
        $grossAmount = $notification->gross_amount ?? '';
        $serverKey = MIDTRANS_SERVER_KEY;

        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($signatureKey, $notification->signature_key ?? '');
    }
}
