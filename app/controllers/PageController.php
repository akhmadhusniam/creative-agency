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
            SELECT p.*, sc.name AS category_name, sc.slug AS category_slug
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            WHERE p.is_featured = 1 AND p.is_published = 1
            ORDER BY p.sort_order ASC LIMIT 6
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
            SELECT p.*, sc.name AS category_name, sc.slug AS category_slug
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            WHERE p.is_published = 1
            ORDER BY p.sort_order, p.id DESC
        ')->fetchAll();

        $title = 'Portfolio';
        require VIEW_PATH . '/portfolio.php';
    }

    // ---------- PORTFOLIO DETAIL (STUDI KASUS) ----------
    public function portfolioDetail(string $slug): void
    {
        $stmt = $this->db->prepare('
            SELECT p.*, sc.name AS category_name, sc.slug AS category_slug
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            WHERE p.slug = ? AND p.is_published = 1
        ');
        $stmt->execute([$slug]);
        $portfolio = $stmt->fetch();

        if (!$portfolio) {
            http_response_code(404);
            $title = 'Karya Tidak Ditemukan';
            require VIEW_PATH . '/partials/404.php';
            return;
        }

        // Daftar layanan yang dipakai (dipisah koma di database)
        $portfolio['services_list'] = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) ($portfolio['services_used'] ?? ''))
        )));

        // Galeri gambar (JSON array path relatif terhadap /uploads)
        $portfolio['gallery'] = [];
        if (!empty($portfolio['images'])) {
            $decoded = json_decode($portfolio['images'], true);
            if (is_array($decoded)) {
                $portfolio['gallery'] = array_values(array_filter(array_map(
                    fn($img) => is_string($img) ? trim($img) : '',
                    $decoded
                )));
            }
        }

        // Karya terkait: kategori sama dulu, lalu lengkapi dari kategori lain
        $relatedStmt = $this->db->prepare('
            SELECT p.id, p.title, p.slug, p.client_name, p.cover_image, p.category_id,
                   sc.name AS category_name, sc.slug AS category_slug
            FROM portfolio p
            LEFT JOIN service_categories sc ON p.category_id = sc.id
            WHERE p.is_published = 1 AND p.id <> ?
            ORDER BY (p.category_id = ?) DESC, p.sort_order ASC, p.id DESC
            LIMIT 3
        ');
        $relatedStmt->execute([$portfolio['id'], $portfolio['category_id']]);
        $related = $relatedStmt->fetchAll();

        $coverUrl = $portfolio['cover_image'] !== ''
            ? APP_URL . '/uploads/' . ltrim($portfolio['cover_image'], '/')
            : '';

        $canonical = APP_URL . '/portfolio/' . rawurlencode($portfolio['slug']);

        $title = $portfolio['title'];
        $seo = [
            'title'       => $portfolio['meta_title'] ?: $portfolio['title'],
            'description' => $portfolio['meta_description']
                ?: mb_substr(strip_tags((string) $portfolio['description']), 0, 160),
            'canonical'   => $canonical,
            'type'        => 'article',
            'image'       => $coverUrl,
            'jsonLd'      => [
                [
                    '@context'      => 'https://schema.org',
                    '@type'         => 'CreativeWork',
                    'name'          => $portfolio['title'],
                    'headline'      => $portfolio['title'],
                    'description'   => $portfolio['meta_description'] ?: strip_tags((string) $portfolio['description']),
                    'url'           => $canonical,
                    'image'         => $coverUrl ?: null,
                    'dateCreated'   => $portfolio['year'] ? (string) $portfolio['year'] : null,
                    'keywords'      => $portfolio['tags'] ?: null,
                    'creator'       => [
                        '@type' => 'Organization',
                        'name'  => site_setting('site_name', APP_NAME),
                        'url'   => APP_URL . '/',
                    ],
                    'about'         => $portfolio['category_name'] ?: null,
                ],
                [
                    '@context'        => 'https://schema.org',
                    '@type'           => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => APP_URL . '/'],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Portfolio', 'item' => APP_URL . '/portfolio'],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $portfolio['title'], 'item' => $canonical],
                    ],
                ],
            ],
        ];

        require VIEW_PATH . '/portfolio-detail.php';
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

    // ---------- SEO: ROBOTS.TXT ----------
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');

        echo "User-agent: *\n";
        echo "Allow: /\n";

        foreach ([
            '/admin', '/dashboard', '/designer', '/manager', '/finance',
            '/notifications', '/login', '/register', '/forgot-password',
            '/reset-password', '/verify-email',
        ] as $path) {
            echo 'Disallow: ' . $path . "\n";
        }

        echo "\nSitemap: " . APP_URL . "/sitemap.xml\n";
    }

    // ---------- SEO: SITEMAP.XML ----------
    public function sitemap(): void
    {
        $urls = [
            ['loc' => APP_URL . '/',          'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => APP_URL . '/services',  'changefreq' => 'weekly',  'priority' => '0.9'],
            ['loc' => APP_URL . '/portfolio', 'changefreq' => 'weekly',  'priority' => '0.9'],
            ['loc' => APP_URL . '/about',     'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => APP_URL . '/contact',   'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        $services = $this->db->query('
            SELECT slug, created_at FROM services WHERE is_active = 1 ORDER BY sort_order
        ')->fetchAll();

        foreach ($services as $svc) {
            $urls[] = [
                'loc'        => APP_URL . '/services/' . $svc['slug'],
                'lastmod'    => !empty($svc['created_at']) ? date('Y-m-d', strtotime($svc['created_at'])) : null,
                'changefreq' => 'monthly',
                'priority'   => '0.8',
            ];
        }

        $projects = $this->db->query('
            SELECT slug, created_at FROM portfolio
            WHERE is_published = 1
            ORDER BY sort_order, id DESC
        ')->fetchAll();

        foreach ($projects as $project) {
            $urls[] = [
                'loc'        => APP_URL . '/portfolio/' . rawurlencode($project['slug']),
                'lastmod'    => !empty($project['created_at']) ? date('Y-m-d', strtotime($project['created_at'])) : null,
                'changefreq' => 'monthly',
                'priority'   => '0.7',
            ];
        }

        header('Content-Type: application/xml; charset=UTF-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            echo "  <url>\n";
            echo '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            if (!empty($u['lastmod'])) {
                echo '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            echo '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            echo '    <priority>' . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
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
