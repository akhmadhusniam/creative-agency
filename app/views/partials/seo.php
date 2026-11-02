<?php
// ============================================================
//  partials/seo.php — judul, meta, Open Graph, dan JSON-LD
//
//  Pemakaian:
//    $seo = [
//        'title'       => 'Judul halaman (tanpa nama situs)',
//        'description' => 'Ringkasan 140–160 karakter',
//        'canonical'   => APP_URL . '/portfolio/contoh',
//        'type'        => 'article',            // opsional
//        'image'       => APP_URL . '/uploads/portfolio/cover.jpg', // opsional
//        'noindex'     => false,                // opsional
//        'jsonLd'      => [...],                // satu objek atau daftar objek
//    ];
//    require VIEW_PATH . '/partials/seo.php';
//
//  Variabel $title dari view dipakai sebagai fallback judul.
// ============================================================

$seo         = $seo ?? [];
$siteName    = site_setting('site_name', APP_NAME);
$pageTitle   = trim((string) ($seo['title'] ?? ($title ?? $siteName)));
$fullTitle   = ($pageTitle !== '' && $pageTitle !== $siteName)
    ? $pageTitle . ' — ' . $siteName
    : $siteName;
$description = trim((string) ($seo['description'] ?? site_setting('site_tagline')));
$canonical   = (string) ($seo['canonical'] ?? APP_URL . '/');
$ogType      = (string) ($seo['type'] ?? 'website');
$ogImage     = trim((string) ($seo['image'] ?? ''));
$noindex     = !empty($seo['noindex']);
$jsonLd      = $seo['jsonLd'] ?? [];
?>
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta name="robots" content="<?= $noindex ? 'noindex,nofollow' : 'index,follow' ?>">

<meta property="og:locale" content="id_ID">
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:type" content="<?= e($ogType) ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<?php if ($ogImage !== ''): ?>
<meta property="og:image" content="<?= e($ogImage) ?>">
<?php endif; ?>

<meta name="twitter:card" content="<?= $ogImage !== '' ? 'summary_large_image' : 'summary' ?>">
<meta name="twitter:title" content="<?= e($fullTitle) ?>">
<meta name="twitter:description" content="<?= e($description) ?>">
<?php if ($ogImage !== ''): ?>
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<?php endif; ?>

<?php
if (is_array($jsonLd) && $jsonLd !== []) {
    $nodes = array_is_list($jsonLd) ? $jsonLd : [$jsonLd];

    foreach ($nodes as $node) {
        if (!is_array($node) || $node === []) {
            continue;
        }
        $encoded = json_encode(
            $node,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );
        if ($encoded === false) {
            continue;
        }
        echo '<script type="application/ld+json">' . $encoded . "</script>\n";
    }
}
?>
