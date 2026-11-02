<?php
// ============================================================
//  app/controllers/AdminController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/RolePermission.php';
require_once ROOT_PATH . '/app/helpers/Site.php';
require_once ROOT_PATH . '/app/helpers/NotificationHelper.php';
require_once ROOT_PATH . '/app/helpers/AuditLog.php';

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
        Session::requirePermission('view_all_dashboards');
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
        $unreadCount = NotificationHelper::unreadCount(Session::userId());
        $recentNotifications = NotificationHelper::recent(Session::userId());
        require VIEW_PATH . '/admin/dashboard.php';
    }

    /**
     * Kelola Orders dengan assignment
     */
    public function orders(): void
    {
        Session::requirePermission('manage_orders');
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
        Session::requirePermission('manage_orders');
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
        Session::requirePermission('manage_orders');
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

        logAudit('order', $id, 'update', $updates);

        Session::flash('success', 'Pesanan berhasil diperbarui.');
        redirect(APP_URL . '/admin/orders/' . $id . '/edit');
    }

    /**
     * Kelola Services
     */
    public function services(): void
    {
        Session::requirePermission('manage_services');
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
        Session::requirePermission('manage_portfolio');
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
     * Form tambah portfolio
     */
    public function createPortfolio(): void
    {
        Session::requirePermission('manage_portfolio');

        $categories = $this->db->query('
            SELECT id, name FROM service_categories ORDER BY sort_order, name
        ')->fetchAll();

        $portfolio  = null;
        $isEdit     = false;
        $formAction = APP_URL . '/admin/portfolio';
        $title      = 'Tambah Portfolio';

        require VIEW_PATH . '/admin/portfolio-form.php';
    }

    /**
     * Simpan portfolio baru
     */
    public function storePortfolio(): void
    {
        Session::requirePermission('manage_portfolio');
        verifyCsrf();

        $data = $this->portfolioPayload();
        if ($data === null) {
            redirect(APP_URL . '/admin/portfolio/create');
        }

        $upload = $this->handlePortfolioCover();
        if ($upload['error'] !== null) {
            Session::flash('errors', ['cover_image' => [$upload['error']]]);
            Session::flash('old', $_POST);
            redirect(APP_URL . '/admin/portfolio/create');
        }
        $data['cover_image'] = $upload['path'] ?? '';

        try {
            $stmt = $this->db->prepare('
                INSERT INTO portfolio
                    (category_id, title, slug, client_name, year, description,
                     challenge, solution, result, services_used, duration,
                     cover_image, tags, meta_title, meta_description,
                     is_featured, is_published, is_sample, sort_order)
                VALUES
                    (:category_id, :title, :slug, :client_name, :year, :description,
                     :challenge, :solution, :result, :services_used, :duration,
                     :cover_image, :tags, :meta_title, :meta_description,
                     :is_featured, :is_published, :is_sample, :sort_order)
            ');
            $stmt->execute($data);
        } catch (PDOException $e) {
            error_log('[portfolio.store] ' . $e->getMessage());
            Session::flash('error', 'Portfolio gagal disimpan. Periksa kembali data Anda.');
            Session::flash('old', $_POST);
            redirect(APP_URL . '/admin/portfolio/create');
        }

        $id = (int) $this->db->lastInsertId();
        logAudit('portfolio', $id, 'create', [
            'title'  => $data['title'],
            'slug'   => $data['slug'],
            'status' => $data['is_published'] ? 'published' : 'draft',
        ]);

        Session::flash('success', 'Portfolio berhasil ditambahkan.');
        redirect(APP_URL . '/admin/portfolio');
    }

    /**
     * Form edit portfolio
     */
    public function editPortfolio(int $id): void
    {
        Session::requirePermission('manage_portfolio');

        $stmt = $this->db->prepare('SELECT * FROM portfolio WHERE id = ?');
        $stmt->execute([$id]);
        $portfolio = $stmt->fetch();

        if (!$portfolio) {
            Session::flash('error', 'Portfolio tidak ditemukan.');
            redirect(APP_URL . '/admin/portfolio');
        }

        $categories = $this->db->query('
            SELECT id, name FROM service_categories ORDER BY sort_order, name
        ')->fetchAll();

        $isEdit     = true;
        $formAction = APP_URL . '/admin/portfolio/' . $id . '/update';
        $title      = 'Edit Portfolio';

        require VIEW_PATH . '/admin/portfolio-form.php';
    }

    /**
     * Simpan perubahan portfolio
     */
    public function updatePortfolio(int $id): void
    {
        Session::requirePermission('manage_portfolio');
        verifyCsrf();

        $stmt = $this->db->prepare('SELECT * FROM portfolio WHERE id = ?');
        $stmt->execute([$id]);
        $existing = $stmt->fetch();

        if (!$existing) {
            Session::flash('error', 'Portfolio tidak ditemukan.');
            redirect(APP_URL . '/admin/portfolio');
        }

        $data = $this->portfolioPayload($id);
        if ($data === null) {
            redirect(APP_URL . '/admin/portfolio/' . $id . '/edit');
        }

        $upload = $this->handlePortfolioCover();
        if ($upload['error'] !== null) {
            Session::flash('errors', ['cover_image' => [$upload['error']]]);
            Session::flash('old', $_POST);
            redirect(APP_URL . '/admin/portfolio/' . $id . '/edit');
        }

        $oldCover = (string) $existing['cover_image'];
        $newCover = $oldCover;

        if ($upload['path'] !== null) {
            $newCover = $upload['path'];
        } elseif (!empty($_POST['remove_cover'])) {
            $newCover = '';
        }
        $data['cover_image'] = $newCover;

        try {
            $stmt = $this->db->prepare('
                UPDATE portfolio SET
                    category_id = :category_id, title = :title, slug = :slug,
                    client_name = :client_name, year = :year, description = :description,
                    challenge = :challenge, solution = :solution, result = :result,
                    services_used = :services_used, duration = :duration,
                    cover_image = :cover_image, tags = :tags,
                    meta_title = :meta_title, meta_description = :meta_description,
                    is_featured = :is_featured, is_published = :is_published,
                    is_sample = :is_sample, sort_order = :sort_order
                WHERE id = :id
            ');
            $data['id'] = $id;
            $stmt->execute($data);
        } catch (PDOException $e) {
            error_log('[portfolio.update] ' . $e->getMessage());
            Session::flash('error', 'Perubahan gagal disimpan. Periksa kembali data Anda.');
            Session::flash('old', $_POST);
            redirect(APP_URL . '/admin/portfolio/' . $id . '/edit');
        }

        if ($newCover !== $oldCover) {
            $this->deletePortfolioCover($oldCover);
        }

        logAudit('portfolio', $id, 'update', [
            'title'  => $data['title'],
            'slug'   => $data['slug'],
            'status' => $data['is_published'] ? 'published' : 'draft',
        ], [
            'title'  => $existing['title'],
            'slug'   => $existing['slug'],
            'status' => $existing['is_published'] ? 'published' : 'draft',
        ]);

        Session::flash('success', 'Perubahan portfolio berhasil disimpan.');
        redirect(APP_URL . '/admin/portfolio');
    }

    /**
     * Hapus portfolio
     */
    public function deletePortfolio(int $id): void
    {
        Session::requirePermission('manage_portfolio');
        verifyCsrf();

        $stmt = $this->db->prepare('SELECT title, slug, cover_image FROM portfolio WHERE id = ?');
        $stmt->execute([$id]);
        $existing = $stmt->fetch();

        if (!$existing) {
            Session::flash('error', 'Portfolio tidak ditemukan.');
            redirect(APP_URL . '/admin/portfolio');
        }

        $this->db->prepare('DELETE FROM portfolio WHERE id = ?')->execute([$id]);
        $this->deletePortfolioCover((string) $existing['cover_image']);

        logAudit('portfolio', $id, 'delete', [], [
            'title' => $existing['title'],
            'slug'  => $existing['slug'],
        ]);

        Session::flash('success', 'Portfolio berhasil dihapus.');
        redirect(APP_URL . '/admin/portfolio');
    }

    /**
     * Toggle status publikasi portfolio
     */
    public function togglePortfolio(int $id): void
    {
        Session::requirePermission('manage_portfolio');
        verifyCsrf();

        $stmt = $this->db->prepare('SELECT is_published FROM portfolio WHERE id = ?');
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();

        if ($current === false) {
            Session::flash('error', 'Portfolio tidak ditemukan.');
            redirect(APP_URL . '/admin/portfolio');
        }

        $next = $current ? 0 : 1;
        $this->db->prepare('UPDATE portfolio SET is_published = ? WHERE id = ?')->execute([$next, $id]);

        logAudit('portfolio', $id, 'toggle_publish', ['is_published' => $next], ['is_published' => (int) $current]);

        Session::flash('success', $next ? 'Portfolio dipublikasikan.' : 'Portfolio dijadikan draft.');
        redirect(APP_URL . '/admin/portfolio');
    }

    /**
     * Validasi & normalisasi input portfolio.
     * Mengembalikan null (dan menyimpan flash error) bila tidak valid.
     */
    private function portfolioPayload(int $ignoreId = 0): ?array
    {
        $v = new Validator($_POST);
        $v->required(['title', 'description']);
        $errors = $v->errors();

        $year = null;
        $yearRaw = trim((string) ($_POST['year'] ?? ''));
        if ($yearRaw !== '') {
            if (!ctype_digit($yearRaw) || (int) $yearRaw < 1990 || (int) $yearRaw > 2100) {
                $errors['year'][] = 'Tahun harus berupa angka antara 1990 dan 2100.';
            } else {
                $year = (int) $yearRaw;
            }
        }

        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $sortOrder = max(0, min(255, $sortOrder));

        $categoryId = null;
        $categoryRaw = trim((string) ($_POST['category_id'] ?? ''));
        if ($categoryRaw !== '') {
            $check = $this->db->prepare('SELECT id FROM service_categories WHERE id = ?');
            $check->execute([(int) $categoryRaw]);
            if ($check->fetchColumn()) {
                $categoryId = (int) $categoryRaw;
            } else {
                $errors['category_id'][] = 'Kategori yang dipilih tidak valid.';
            }
        }

        if ($errors) {
            Session::flash('errors', $errors);
            Session::flash('old', $_POST);
            return null;
        }

        $title = trim((string) $_POST['title']);
        $slugInput = trim((string) ($_POST['slug'] ?? ''));
        $slug = $this->uniquePortfolioSlug($slugInput !== '' ? $slugInput : $title, $ignoreId);

        return [
            'category_id'      => $categoryId,
            'title'            => $title,
            'slug'             => $slug,
            'client_name'      => trim((string) ($_POST['client_name'] ?? '')) ?: null,
            'year'             => $year,
            'description'      => trim((string) $_POST['description']),
            'challenge'        => trim((string) ($_POST['challenge'] ?? '')) ?: null,
            'solution'         => trim((string) ($_POST['solution'] ?? '')) ?: null,
            'result'           => trim((string) ($_POST['result'] ?? '')) ?: null,
            'services_used'    => trim((string) ($_POST['services_used'] ?? '')) ?: null,
            'duration'         => trim((string) ($_POST['duration'] ?? '')) ?: null,
            'tags'             => trim((string) ($_POST['tags'] ?? '')) ?: null,
            'meta_title'       => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
            'is_featured'      => !empty($_POST['is_featured']) ? 1 : 0,
            'is_published'     => !empty($_POST['is_published']) ? 1 : 0,
            'is_sample'        => !empty($_POST['is_sample']) ? 1 : 0,
            'sort_order'       => $sortOrder,
        ];
    }

    /**
     * Buat slug unik dari judul/input, dengan suffix angka bila bentrok.
     */
    private function uniquePortfolioSlug(string $source, int $ignoreId = 0): string
    {
        $slug = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $source), '-'));
        if ($slug === '') {
            $slug = 'portfolio';
        }
        $slug = substr($slug, 0, 100);

        $base = $slug;
        $n = 2;
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM portfolio WHERE slug = ? AND id <> ?');

        do {
            $stmt->execute([$slug, $ignoreId]);
            $exists = (int) $stmt->fetchColumn() > 0;
            if ($exists) {
                $slug = $base . '-' . $n++;
            }
        } while ($exists);

        return $slug;
    }

    /**
     * Upload cover portfolio. Mengembalikan path relatif /uploads atau error.
     *
     * @return array{path: ?string, error: ?string}
     */
    private function handlePortfolioCover(): array
    {
        $file = $_FILES['cover_image'] ?? null;
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['path' => null, 'error' => null];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['path' => null, 'error' => 'Upload cover gagal. Coba lagi.'];
        }
        if ($file['size'] > MAX_UPLOAD_MB * 1024 * 1024) {
            return ['path' => null, 'error' => 'Ukuran cover melebihi ' . MAX_UPLOAD_MB . ' MB.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_IMAGES, true)) {
            return ['path' => null, 'error' => 'Format cover harus JPG, PNG, WEBP, atau GIF.'];
        }
        if (@getimagesize($file['tmp_name']) === false) {
            return ['path' => null, 'error' => 'File cover tidak dikenali sebagai gambar.'];
        }

        $dir = ROOT_PATH . '/public/uploads/portfolio';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return ['path' => null, 'error' => 'Folder upload tidak dapat dibuat.'];
        }

        $name = date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return ['path' => null, 'error' => 'Cover gagal disimpan di server.'];
        }

        return ['path' => 'portfolio/' . $name, 'error' => null];
    }

    /**
     * Hapus file cover lama (hanya di dalam public/uploads/portfolio).
     */
    private function deletePortfolioCover(string $path): void
    {
        if ($path === '' || !str_starts_with($path, 'portfolio/')) {
            return;
        }

        $full = ROOT_PATH . '/public/uploads/' . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }

    /**
     * Manage Users/Staff
     */
    public function users(): void
    {
        Session::requirePermission('manage_users');
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
        Session::requirePermission('manage_users');
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
        Session::requirePermission('manage_users');
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
        Session::requirePermission('manage_users');
        verifyCsrf();

        $user = $this->userModel->findById($id);
        if (!$user) {
            Session::flash('error', 'User tidak ditemukan.');
            redirect(APP_URL . '/admin/users');
        }

        if ($user['is_active']) {
            $this->userModel->deactivate($id);
            $msg = 'User berhasil dinonaktifkan.';
            logAudit('user', $id, 'deactivate', ['is_active' => 0], ['is_active' => 1]);
        } else {
            $this->userModel->activate($id);
            $msg = 'User berhasil diaktifkan.';
            logAudit('user', $id, 'activate', ['is_active' => 1], ['is_active' => 0]);
        }

        Session::flash('success', $msg);
        redirect(APP_URL . '/admin/users');
    }

    /**
     * Update status order
     */
    public function updateOrderStatus(int $id): void
    {
        Session::requirePermission('manage_orders');
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

    /**
     * Halaman pengaturan situs (identitas & kontak)
     */
    public function settings(): void
    {
        Session::requirePermission('manage_settings');
        $settings = site_settings();
        $title = 'Pengaturan Situs';
        require VIEW_PATH . '/admin/settings.php';
    }

    /**
     * Simpan perubahan pengaturan situs
     */
    public function updateSettings(): void
    {
        Session::requirePermission('manage_settings');
        verifyCsrf();

        // Whitelist key yang boleh diubah lewat form ini
        $allowedKeys = [
            'site_name', 'site_tagline', 'site_email', 'site_phone', 'site_whatsapp',
            'site_address', 'site_hours_office', 'site_hours_wa', 'site_maps_query',
            'site_meeting_url', 'site_instagram', 'site_linkedin',
        ];

        $stmt = $this->db->prepare('
            INSERT INTO settings (`key`, `value`) VALUES (:key, :value)
            ON DUPLICATE KEY UPDATE `value` = :value2
        ');

        foreach ($allowedKeys as $key) {
            if (!array_key_exists($key, $_POST)) {
                continue;
            }
            $value = trim($_POST[$key]);
            $stmt->execute(['key' => $key, 'value' => $value, 'value2' => $value]);
        }

        if (function_exists('logAudit')) {
            logAudit('settings', 0, 'update', ['keys' => $allowedKeys]);
        }

        Session::flash('success', 'Pengaturan berhasil disimpan.');
        redirect(APP_URL . '/admin/settings');
    }

    /**
     * Viewer audit log — untuk admin/owner memantau aksi sensitif
     */
    public function auditLogs(): void
    {
        Session::requirePermission('view_all_dashboards');
        $entityFilter = $_GET['entity_type'] ?? '';
        $dateFrom = $_GET['from'] ?? '';
        $dateTo = $_GET['to'] ?? '';

        $query = '
            SELECT a.*, u.name AS user_name, u.role AS user_role
            FROM audit_logs a
            JOIN users u ON a.user_id = u.id
            WHERE 1=1
        ';
        $params = [];

        if ($entityFilter) {
            $query .= ' AND a.entity_type = ?';
            $params[] = $entityFilter;
        }
        if ($dateFrom) {
            $query .= ' AND a.created_at >= ?';
            $params[] = $dateFrom . ' 00:00:00';
        }
        if ($dateTo) {
            $query .= ' AND a.created_at <= ?';
            $params[] = $dateTo . ' 23:59:59';
        }

        $query .= ' ORDER BY a.created_at DESC LIMIT 200';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $logs = $stmt->fetchAll();

        $entityTypes = $this->db->query('SELECT DISTINCT entity_type FROM audit_logs ORDER BY entity_type')
                                  ->fetchAll(PDO::FETCH_COLUMN);

        $title = 'Audit Log';
        require VIEW_PATH . '/admin/audit-logs.php';
    }

    /**
     * Inbox pesan dari form kontak publik
     */
    public function messages(): void
    {
        Session::requirePermission('manage_users');
        $typeFilter = $_GET['type'] ?? '';
        $statusFilter = $_GET['status'] ?? '';

        $query = 'SELECT * FROM messages WHERE 1=1';
        $params = [];

        if ($typeFilter) {
            $query .= ' AND inquiry_type = ?';
            $params[] = $typeFilter;
        }
        if ($statusFilter === 'unread') {
            $query .= ' AND is_read = 0';
        } elseif ($statusFilter === 'read') {
            $query .= ' AND is_read = 1';
        }

        $query .= ' ORDER BY created_at DESC LIMIT 200';

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $messages = $stmt->fetchAll();

        $unreadCount = $this->db->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();

        $title = 'Pesan Masuk';
        require VIEW_PATH . '/admin/messages.php';
    }

    /**
     * Detail satu pesan — otomatis ditandai sudah dibaca
     */
    public function viewMessage(int $id): void
    {
        Session::requirePermission('manage_users');
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE id = ?');
        $stmt->execute([$id]);
        $message = $stmt->fetch();

        if (!$message) {
            http_response_code(404);
            $title = 'Pesan Tidak Ditemukan';
            require VIEW_PATH . '/partials/404.php';
            exit;
        }

        if (!$message['is_read']) {
            $this->db->prepare('UPDATE messages SET is_read = 1 WHERE id = ?')->execute([$id]);
            $message['is_read'] = 1;
        }

        $title = 'Detail Pesan';
        require VIEW_PATH . '/admin/message-detail.php';
    }
}
