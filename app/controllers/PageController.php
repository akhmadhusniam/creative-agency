<?php
// ============================================================
//  app/controllers/PageController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/Site.php';

class PageController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
    }

    // ---------- HOME ----------
    public function home(): void
    {
        // Layanan featured (aktif, sort by sort_order)
        $services = $this->db->query('
            SELECT s.*, sc.name AS category_name
            FROM services s
            JOIN service_categories sc ON s.category_id = sc.id
            WHERE s.is_active = 1
            ORDER BY s.sort_order ASC
            LIMIT 6
        ')->fetchAll();

        // Portfolio featured
        $portfolio = $this->db->query('
            SELECT * FROM portfolio WHERE is_featured = 1
            ORDER BY sort_order ASC LIMIT 6
        ')->fetchAll();

        // Testimonial featured
        $testimonials = $this->db->query('
            SELECT * FROM testimonials WHERE is_featured = 1
            ORDER BY id DESC LIMIT 6
        ')->fetchAll();

        $title = 'Beranda';
        require VIEW_PATH . '/home.php';
    }

    // ---------- SERVICES ----------
    public function services(): void
    {
        $categories = $this->db->query('
            SELECT sc.*, COUNT(s.id) AS service_count
            FROM service_categories sc
            LEFT JOIN services s ON s.category_id = sc.id AND s.is_active = 1
            GROUP BY sc.id
            ORDER BY sc.sort_order ASC
        ')->fetchAll();

        $services = $this->db->query('
            SELECT s.*, sc.name AS category_name, sc.slug AS category_slug
            FROM services s
            JOIN service_categories sc ON s.category_id = sc.id
            WHERE s.is_active = 1
            ORDER BY sc.sort_order, s.sort_order
        ')->fetchAll();

        // Attach fitur ke setiap layanan
        foreach ($services as &$svc) {
            $fStmt = $this->db->prepare(
                'SELECT feature FROM service_features WHERE service_id = ? ORDER BY id LIMIT 4'
            );
            $fStmt->execute([$svc['id']]);
            $svc['features'] = $fStmt->fetchAll(PDO::FETCH_COLUMN);
        }
        unset($svc);

        $title = 'Layanan Kami';
        require VIEW_PATH . '/services.php';
    }

    // ---------- SERVICE DETAIL ----------
    public function serviceDetail(string $slug): void
    {
        $stmt = $this->db->prepare('
            SELECT s.*, sc.name AS category_name, sc.slug AS category_slug
            FROM services s
            JOIN service_categories sc ON s.category_id = sc.id
            WHERE s.slug = ? AND s.is_active = 1
        ');
        $stmt->execute([$slug]);
        $service = $stmt->fetch();

        if (!$service) {
            http_response_code(404);
            require VIEW_PATH . '/partials/404.php';
            return;
        }

        $features = $this->db->prepare(
            'SELECT feature FROM service_features WHERE service_id = ? ORDER BY id'
        );
        $features->execute([$service['id']]);
        $service['features'] = $features->fetchAll(PDO::FETCH_COLUMN);

        $title = $service['name'];
        require VIEW_PATH . '/service-detail.php';
    }

    // ---------- PORTFOLIO ----------
    public function portfolio(): void
    {
        $categories = $this->db->query('
            SELECT * FROM service_categories ORDER BY sort_order
        ')->fetchAll();

        $portfolio = $this->db->query('
            SELECT p.*, sc.name AS category_name
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            ORDER BY p.sort_order, p.id DESC
        ')->fetchAll();

        $title = 'Portfolio';
        require VIEW_PATH . '/portfolio.php';
    }

    // ---------- ABOUT ----------
    public function about(): void
    {
        $title = 'Tentang Kami';
        require VIEW_PATH . '/about.php';
    }

    // ---------- CONTACT ----------
    public function contact(): void
    {
        $title = 'Hubungi Kami';
        require VIEW_PATH . '/contact.php';
    }

    public function sendContact(): void
    {
        verifyCsrf();

        $inquiryType = trim($_POST['inquiry_type'] ?? 'Klien Baru') ?: 'Klien Baru';

        $v = new Validator($_POST);
        $v->required(['name','email','body'])->email('email');
        if ($inquiryType === 'Klien Baru') {
            $v->required(['subject']);
        }

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            Session::flash('old', $_POST);
            redirect(APP_URL . '/contact');
        }

        $payload = [
            'user_id'      => Session::userId(),
            'inquiry_type' => $inquiryType,
            'name'         => trim($_POST['name']),
            'email'        => strtolower(trim($_POST['email'])),
            'company'      => trim($_POST['company'] ?? '') ?: null,
            'phone'        => trim($_POST['phone'] ?? '') ?: null,
            'subject'      => trim($_POST['subject'] ?? '') ?: null,
            'budget'       => trim($_POST['budget'] ?? '') ?: null,
            'body'         => trim($_POST['body']),
        ];

        try {
            $stmt = $this->db->prepare('
                INSERT INTO messages (user_id, inquiry_type, name, email, company, phone, subject, budget, body)
                VALUES (:user_id, :inquiry_type, :name, :email, :company, :phone, :subject, :budget, :body)
            ');
            $stmt->execute($payload);
        } catch (PDOException $e) {
            $stmt = $this->db->prepare('
                INSERT INTO messages (user_id, name, email, subject, body)
                VALUES (:user_id, :name, :email, :subject, :body)
            ');
            $stmt->execute([
                'user_id' => $payload['user_id'],
                'name'    => $payload['name'],
                'email'   => $payload['email'],
                'subject' => $payload['subject'] ?? $payload['inquiry_type'],
                'body'    => $payload['body'],
            ]);
        }

        Session::flash('success', 'Pesan Anda telah terkirim. Pilih cara konsultasi yang paling nyaman.');
        Session::flash('consult_modal', '1');
        redirect(APP_URL . '/contact#konsultasi');
    }
}
