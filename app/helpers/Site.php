<?php
// ============================================================
//  app/helpers/Site.php — kontak & identitas studio
// ============================================================

function site_settings(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defaults = [
        'site_name'         => defined('APP_NAME') ? APP_NAME : 'Creative Studio',
        'site_tagline'      => 'Wujudkan Identitas Visual Bisnis Anda',
        'site_email'        => 'hello@creativeagency.id',
        'site_phone'        => '+62 812-3456-7890',
        'site_whatsapp'     => '6281234567890',
        'site_address'      => 'Jl. Kemang Raya No. 12B, Jakarta Selatan, 12730',
        'site_hours_office' => 'Senin–Jumat 09.00–17.00 WIB',
        'site_hours_wa'     => 'Setiap hari 08.00–21.00 WIB',
        'site_maps_query'   => 'Jl. Kemang Raya No. 12B Jakarta Selatan',
        'site_meeting_url'  => '',
        'site_instagram'    => '',
        'site_linkedin'     => '',
    ];

    try {
        $rows = db()->query('SELECT `key`, `value` FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
        if (is_array($rows)) {
            foreach ($rows as $key => $value) {
                if ($value !== null && $value !== '') {
                    $defaults[$key] = $value;
                }
            }
        }
    } catch (Throwable $e) {
        // tabel settings belum ada — pakai default
    }

    $wa = preg_replace('/\D+/', '', $defaults['site_whatsapp'] ?: $defaults['site_phone']);
    if (str_starts_with($wa, '0')) {
        $wa = '62' . substr($wa, 1);
    }
    $defaults['site_whatsapp'] = $wa;

    $cache = $defaults;
    return $cache;
}

function site_setting(string $key, string $fallback = ''): string
{
    $all = site_settings();
    return (string) ($all[$key] ?? $fallback);
}

function site_wa_url(string $text = ''): string
{
    $wa = site_setting('site_whatsapp');
    $url = 'https://wa.me/' . $wa;
    if ($text !== '') {
        $url .= '?text=' . rawurlencode($text);
    }
    return $url;
}

/**
 * Satu pintu masuk konversi utama: "Mulai Proyek".
 * Semua CTA primer publik diarahkan ke halaman kontak dengan konteks yang relevan.
 */
function site_start_project_url(?string $serviceSlug = null): string
{
    $url = APP_URL . '/contact?intent=project';

    if ($serviceSlug !== null && $serviceSlug !== '') {
        $url .= '&service=' . rawurlencode($serviceSlug);
    }

    return $url . '#form-konsultasi';
}

function site_maps_embed_src(): string
{
    $q = site_setting('site_maps_query', site_setting('site_address'));
    return 'https://maps.google.com/maps?q=' . rawurlencode($q) . '&output=embed';
}

/**
 * Statistik publik yang bisa dipertanggungjawabkan (dihitung dari database).
 * Dipakai untuk menggantikan klaim angka hardcoded di halaman publik.
 *
 * @return array{projects:int,sample_projects:int,categories:int,services:int,testimonials:int}
 */
function site_stats(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $stats = [
        'projects'        => 0,
        'sample_projects' => 0,
        'categories'      => 0,
        'services'        => 0,
        'testimonials'    => 0,
    ];

    try {
        $db = db();
        $stats['projects'] = (int) $db->query(
            'SELECT COUNT(*) FROM portfolio WHERE is_published = 1'
        )->fetchColumn();
        $stats['categories'] = (int) $db->query(
            'SELECT COUNT(*) FROM service_categories'
        )->fetchColumn();
        $stats['services'] = (int) $db->query(
            'SELECT COUNT(*) FROM services WHERE is_active = 1'
        )->fetchColumn();
        $stats['testimonials'] = (int) $db->query(
            'SELECT COUNT(*) FROM testimonials'
        )->fetchColumn();

        try {
            $stats['sample_projects'] = (int) $db->query(
                'SELECT COUNT(*) FROM portfolio WHERE is_published = 1 AND is_sample = 1'
            )->fetchColumn();
        } catch (Throwable $e) {
            // Kolom is_sample belum tersedia — anggap belum ada konten sampel.
        }
    } catch (Throwable $e) {
        // Tabel belum tersedia — biarkan nol, view memakai fallback yang aman.
    }

    $cache = $stats;
    return $cache;
}

/**
 * Label statistik portfolio yang tidak membaca konten sampel sebagai karya asli.
 */
function site_project_label(): string
{
    $stats = site_stats();
    $total = (int) ($stats['projects'] ?? 0);
    $samples = (int) ($stats['sample_projects'] ?? 0);

    if ($total > 0 && $samples >= $total) {
        return 'Contoh studi kasus';
    }
    if ($samples > 0) {
        return 'Studi kasus & sampel';
    }
    return 'Studi kasus';
}

/**
 * Profil paket untuk halaman layanan.
 *
 * Data inti tetap berasal dari tabel services. Profil ini melengkapi konteks
 * yang tidak disimpan di database: paket ini cocok untuk siapa, apa yang perlu
 * disiapkan klien, dan apa yang tidak termasuk. Dengan begitu, calon klien
 * bisa menilai kecocokan sebelum mengirim brief.
 *
 * @param array<string,mixed> $service
 * @return array{audience:string,included:array<int,string>,ideal_for:array<int,string>,requirements:array<int,string>,excludes:array<int,string>,fit_note:string}
 */
function site_service_profile(array $service): array
{
    static $profiles = null;

    if ($profiles === null) {
        $profiles = [
            'brand-identity-starter' => [
                'audience' => 'UMKM & brand baru',
                'included' => [
                    'Logo (3 konsep awal)',
                    'Revisi 2x',
                    'Color palette + tipografi',
                    'File PNG, SVG, PDF',
                ],
                'ideal_for' => [
                    'Bisnis baru atau UMKM yang butuh identitas dasar yang konsisten.',
                    'Brand yang sudah punya nama dan arah bisnis, tetapi belum punya logo atau panduan visual.',
                    'Tim internal kecil yang perlu file siap pakai untuk konten dan materi promosi.',
                ],
                'requirements' => [
                    'Nama brand, deskripsi bisnis, dan target audiens.',
                    'Referensi visual yang disukai dan tidak disukai.',
                    'Satu pengambil keputusan yang bisa menyetujui konsep.',
                    'Waktu feedback 2–3 hari kerja per putaran revisi.',
                ],
                'excludes' => [
                    'Brand guideline lengkap, stationery, dan mockup presentasi.',
                    'Copywriting, fotografi, dan produksi cetak.',
                    'Perubahan nama atau arah brand setelah konsep disetujui.',
                ],
                'fit_note' => 'Cocok jika nama brand dan arah bisnis sudah jelas. Jika belum, mulai dari sesi konsultasi.',
            ],
            'full-brand-system' => [
                'audience' => 'bisnis yang sedang tumbuh',
                'included' => [
                    'Semua fitur Brand Identity Starter',
                    'Brand Guideline PDF',
                    'Stationery design (kartu nama, amplop, KTP)',
                    'Mockup presentasi',
                    'Revisi sesuai paket',
                ],
                'ideal_for' => [
                    'Bisnis yang sudah berjalan dan ingin menyeragamkan identitas di semua touchpoint.',
                    'Brand yang butuh guideline agar tim internal dan vendor tetap konsisten.',
                    'Perusahaan yang siap meluangkan waktu untuk audit dan eksplorasi brand.',
                ],
                'requirements' => [
                    'Logo existing (jika ada), materi promosi, dan contoh penerapan saat ini.',
                    'Daftar kebutuhan stationery atau touchpoint yang ingin distandarkan.',
                    'Pengambil keputusan dari tim brand atau marketing.',
                    'Waktu untuk sesi kickoff dan review terstruktur.',
                ],
                'excludes' => [
                    'Produksi cetak, fotografi, dan belanja media.',
                    'Penambahan touchpoint di luar daftar yang disepakati.',
                    'Perubahan arah brand setelah tahap konsep disetujui.',
                ],
                'fit_note' => 'Harga mulai dari; lingkup final ditentukan setelah audit brief.',
            ],
            'landing-page-design' => [
                'audience' => 'startup & tim kampanye',
                'included' => [
                    'Hingga 5 section',
                    'File Figma',
                    'Responsive design (1 breakpoint mobile)',
                    'Revisi 2x',
                ],
                'ideal_for' => [
                    'Bisnis atau startup yang butuh landing page untuk validasi produk atau kampanye.',
                    'Tim marketing yang sudah punya copy dan tujuan konversi yang jelas.',
                    'Proyek yang butuh desain cepat tanpa development.',
                ],
                'requirements' => [
                    'Tujuan halaman, target audiens, dan CTA utama.',
                    'Copywriting final, logo, foto atau ilustrasi, dan referensi.',
                    'Satu PIC yang bisa memberi feedback terpusat.',
                    'Konten final sebelum tahap desain dimulai.',
                ],
                'excludes' => [
                    'Development atau coding, hosting, dan integrasi form/payment.',
                    'Copywriting dan pembuatan aset foto atau ilustrasi.',
                    'SEO teknis dan maintenance setelah handoff.',
                ],
                'fit_note' => 'Cocok jika copy dan tujuan halaman sudah siap. Jika belum, copywriting bisa ditambahkan sebagai lingkup terpisah.',
            ],
            'mobile-app-ui' => [
                'audience' => 'tim produk & startup',
                'included' => [
                    'Hingga 15 screen',
                    'Prototype Figma',
                    'Design system',
                    'Handoff notes untuk developer',
                    'Revisi sesuai paket',
                ],
                'ideal_for' => [
                    'Startup atau tim produk yang sudah punya alur dan fitur utama aplikasi.',
                    'Tim yang butuh UI konsisten dan siap di-handoff ke developer.',
                    'Produk yang ingin merapikan design system sebelum scale.',
                ],
                'requirements' => [
                    'User flow, daftar fitur prioritas, dan referensi aplikasi.',
                    'Existing brand atau logo dan preferensi visual.',
                    'Akses ke product owner atau pengambil keputusan.',
                    'Konten contoh dan data untuk layar utama.',
                ],
                'excludes' => [
                    'Development aplikasi, testing, dan deployment.',
                    'Riset pengguna mendalam dan usability testing.',
                    'Penambahan screen di luar kesepakatan (dihitung terpisah).',
                ],
                'fit_note' => 'Harga mulai dari; jumlah screen dan kompleksitas fitur menentukan estimasi final.',
            ],
            'social-media-pack' => [
                'audience' => 'UMKM & tim marketing',
                'included' => [
                    '10 template feed',
                    '5 template story',
                    'File Canva/Figma',
                    'Panduan posting',
                    'Revisi sesuai paket',
                ],
                'ideal_for' => [
                    'UMKM atau brand yang ingin konten visual konsisten tanpa tim desain internal.',
                    'Tim marketing yang butuh template siap edit untuk konten harian.',
                    'Bisnis yang sudah punya foto produk dan tone komunikasi.',
                ],
                'requirements' => [
                    'Logo, warna, dan font brand (jika ada).',
                    'Foto produk/kegiatan yang sudah ada dan contoh konten yang disukai.',
                    'Tone komunikasi dan jadwal konten.',
                    'Satu PIC untuk approval.',
                ],
                'excludes' => [
                    'Copywriting caption dan kalender posting.',
                    'Fotografi, videografi, dan editing konten.',
                    'Pengelolaan akun dan iklan.',
                ],
                'fit_note' => 'Cocok untuk template visual. Strategi konten dan copywriting bisa ditambahkan sebagai lingkup terpisah.',
            ],
            'motion-graphic-pendek' => [
                'audience' => 'kampanye & promosi produk',
                'included' => [
                    'Durasi 15–30 detik',
                    'Format MP4/GIF',
                    'Revisi 2x',
                    'File source After Effects',
                ],
                'ideal_for' => [
                    'Brand yang butuh video pendek untuk iklan, intro, atau promosi produk.',
                    'Tim marketing yang sudah punya naskah atau poin utama dan aset visual.',
                    'Kampanye yang butuh aset video cepat tanpa live shooting.',
                ],
                'requirements' => [
                    'Naskah atau poin utama video.',
                    'Logo, aset visual, dan referensi gaya.',
                    'Durasi target dan platform penempatan.',
                    'Satu PIC untuk approval.',
                ],
                'excludes' => [
                    'Live shooting, talent, dan voice over.',
                    'Lisensi musik berbayar.',
                    'Penambahan durasi di luar paket.',
                ],
                'fit_note' => 'Durasi tambahan dihitung per 15 detik.',
            ],
            'print-design' => [
                'audience' => 'acara & merchandise',
                'included' => [
                    'Desain siap cetak',
                    'PDF CMYK + bleed 3mm',
                    'Resolusi 300 dpi',
                    'Revisi sesuai paket',
                ],
                'ideal_for' => [
                    'Bisnis yang butuh materi cetak siap produksi.',
                    'Tim yang sudah punya spesifikasi bahan dan ukuran.',
                    'Acara atau kampanye dengan kebutuhan merchandise.',
                ],
                'requirements' => [
                    'Ukuran, bahan, dan jumlah cetak.',
                    'Logo, copy, dan aset visual.',
                    'Referensi layout dan posisi elemen penting.',
                    'Vendor cetak (opsional).',
                ],
                'excludes' => [
                    'Biaya cetak dan produksi.',
                    'Pengiriman merchandise.',
                    'Mockup fisik.',
                ],
                'fit_note' => 'Layanan ini hanya desain digital; produksi cetak dikelola vendor terpisah.',
            ],
        ];
    }

    $slug = (string) ($service['slug'] ?? '');
    $profile = $profiles[$slug] ?? null;

    if ($profile === null) {
        $profile = [
            'audience' => 'bisnis yang butuh solusi desain custom',
            'included' => [],
            'ideal_for' => [
                'Bisnis yang sudah punya tujuan dan target audiens yang jelas.',
                'Tim yang bisa menyediakan brief, aset, dan feedback terstruktur.',
                'Proyek dengan lingkup yang bisa disepakati sebelum pengerjaan.',
            ],
            'requirements' => [
                'Brief kebutuhan dan referensi visual.',
                'Aset pendukung: logo, foto, copy, atau data.',
                'Satu pengambil keputusan dan waktu review yang jelas.',
                'Kesiapan pembayaran penuh di awal sesuai kesepakatan.',
            ],
            'excludes' => [
                'Pekerjaan di luar lingkup yang disepakati.',
                'Produksi cetak, development, atau media buying.',
                'Perubahan arah setelah konsep disetujui.',
            ],
            'fit_note' => 'Lingkup, timeline, dan biaya final dikonfirmasi setelah brief.',
        ];
    }

    return array_merge([
        'audience'    => '',
        'included'    => [],
        'ideal_for'   => [],
        'requirements'=> [],
        'excludes'    => [],
        'fit_note'    => '',
    ], $profile);
}

/**
 * Daftar fitur paket yang dipakai kartu layanan dan halaman detail.
 * Sumber utama tetap service_features; jika kosong, pakai fitur dari profil paket.
 * Normalisasi kecil di sini menjaga istilah tetap sama di seluruh halaman.
 *
 * @param array<string,mixed> $service
 * @return array<int,string>
 */
function site_service_features(array $service): array
{
    $features = $service['features'] ?? [];
    if (!is_array($features)) {
        $features = [];
    }

    $features = array_values(array_filter($features, static function ($feature): bool {
        return is_string($feature) && trim($feature) !== '';
    }));
    $features = array_map(static fn(string $feature): string => trim($feature), $features);

    if (empty($features)) {
        $profile = site_service_profile($service);
        $features = $profile['included'];
    }

    $normalize = [
        // Teks lama di service_features; dipertahankan agar data production tetap tampil konsisten.
        'Semua fitur Starter' => 'Semua fitur Brand Identity Starter',
    ];

    return array_map(static fn(string $feature): string => $normalize[$feature] ?? $feature, $features);
}
