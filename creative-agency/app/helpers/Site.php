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

function site_maps_embed_src(): string
{
    $q = site_setting('site_maps_query', site_setting('site_address'));
    return 'https://maps.google.com/maps?q=' . rawurlencode($q) . '&output=embed';
}
