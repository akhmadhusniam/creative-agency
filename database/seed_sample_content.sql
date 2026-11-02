-- ============================================================
--  Seed konten contoh (SAMPEL) — Portfolio & Testimoni
--  Semua baris ditandai is_sample = 1 dan ditampilkan dengan
--  badge "Sampel"/"Contoh" di situs publik.
--
--  Aman dijalankan berulang kali:
--   - Portfolio memakai slug UNIQUE (ON DUPLICATE KEY = no-op)
--   - Testimoni dicek dengan NOT EXISTS (client_name + is_sample)
--
--  Ganti atau hapus data ini lewat /admin/portfolio setelah
--  konten asli siap. Untuk menghapus semua sampel:
--    DELETE FROM portfolio    WHERE is_sample = 1;
--    DELETE FROM testimonials WHERE is_sample = 1;
-- ============================================================

-- ------------------------------------------------------------
--  1. Portfolio — 3 studi kasus contoh
-- ------------------------------------------------------------

INSERT INTO portfolio (
    category_id, title, slug, client_name, year, description,
    challenge, solution, result, services_used, duration,
    cover_image, images, tags, meta_title, meta_description,
    is_featured, is_published, is_sample, sort_order
) VALUES (
    (SELECT id FROM service_categories WHERE slug = 'branding' LIMIT 1),
    'Rebranding Kedai Kopi Senja',
    'rebranding-kedai-kopi-senja',
    'Kedai Kopi Senja',
    2025,
    'Peremajaan identitas visual kedai kopi lokal untuk 12 gerai, dari logo hingga kemasan dan signage.',
    'Setelah enam tahun beroperasi, identitas Kedai Kopi Senja terasa tidak konsisten di setiap gerai. Logo lama sulit diaplikasikan pada kemasan baru, warna antar-gerai berbeda, dan materi promosi dibuat sendiri-sendiri oleh tiap cabang. Pelanggan mengenali nama kedainya, tetapi belum punya gambaran visual yang kuat tentang brand ini.',
    'Kami memulai dengan audit visual seluruh touchpoint dan wawancara singkat dengan pelanggan setia. Dari situ kami menyusun ulang strategi brand: positioning "kopi rumahan yang dekat", palet warna hangat yang tetap kontras di ruang terbuka, sistem logo responsif, serta template kemasan dan signage yang bisa diproduksi mandiri oleh tim gerai.',
    'Sistem identitas baru diterapkan bertahap di 12 gerai dalam tiga bulan. Waktu produksi materi promosi turun karena tim gerai memakai template yang sama, dan merchandise edisi rebranding terjual habis dalam dua minggu pertama. Yang paling penting, pemilik kini punya panduan jelas untuk membuka gerai berikutnya.',
    'Brand Strategy, Logo & Identity, Packaging, Signage',
    '6 minggu',
    '',
    NULL,
    'Branding, F&B, Rebranding',
    'Rebranding Kedai Kopi Senja — Studi Kasus Branding',
    'Studi kasus rebranding Kedai Kopi Senja: strategi brand, identitas visual, kemasan, dan signage yang konsisten untuk 12 gerai.',
    1, 1, 1, 1
) ON DUPLICATE KEY UPDATE id = id;

INSERT INTO portfolio (
    category_id, title, slug, client_name, year, description,
    challenge, solution, result, services_used, duration,
    cover_image, images, tags, meta_title, meta_description,
    is_featured, is_published, is_sample, sort_order
) VALUES (
    (SELECT id FROM service_categories WHERE slug = 'uiux' LIMIT 1),
    'Aplikasi Mobile FinTrack',
    'aplikasi-mobile-fintrack',
    'FinTrack',
    2024,
    'Perancangan ulang alur onboarding dan dashboard aplikasi pencatat keuangan pribadi.',
    'FinTrack memiliki fitur lengkap, tetapi pengguna baru berhenti di tengah proses pendaftaran. Onboarding terdiri dari tujuh langkah, meminta terlalu banyak data di awal, dan tidak menjelaskan manfaat fitur utama. Tim produk juga belum memiliki design system, sehingga setiap layar baru dikerjakan dari nol.',
    'Kami melakukan riset terhadap 12 pengguna baru untuk memetakan titik drop-off, lalu menyederhanakan onboarding menjadi tiga langkah dengan opsi "mulai cepat". Dashboard disusun ulang mengikuti prioritas penggunaan nyata: saldo, transaksi terakhir, dan target tabungan. Kami juga membangun design system berisi 40 komponen agar tim internal bisa bergerak lebih cepat.',
    'Pada bulan pertama setelah rilis, tingkat penyelesaian onboarding meningkat signifikan dan waktu desain layar baru berkurang karena komponen sudah tersedia. Tim produk kini memakai satu sumber kebenaran untuk warna, tipografi, dan pola interaksi di seluruh aplikasi.',
    'UX Research, UI Design, Design System',
    '10 minggu',
    '',
    NULL,
    'UI/UX, Fintech, Mobile App',
    'Aplikasi Mobile FinTrack — Studi Kasus UI/UX',
    'Studi kasus UI/UX FinTrack: riset pengguna, penyederhanaan onboarding, dashboard baru, dan design system 40 komponen.',
    1, 1, 1, 2
) ON DUPLICATE KEY UPDATE id = id;

INSERT INTO portfolio (
    category_id, title, slug, client_name, year, description,
    challenge, solution, result, services_used, duration,
    cover_image, images, tags, meta_title, meta_description,
    is_featured, is_published, is_sample, sort_order
) VALUES (
    (SELECT id FROM service_categories WHERE slug = 'socmed' LIMIT 1),
    'Kampanye Sosial Media Lumiere',
    'kampanye-sosial-media-lumiere',
    'Lumiere Skincare',
    2025,
    'Sistem konten dan kampanye visual tiga bulan untuk peluncuran lini perawatan kulit baru.',
    'Lumiere akan meluncurkan lini produk baru, tetapi akun sosial medianya belum memiliki identitas konten yang konsisten. Jadwal posting bergantung pada tren harian, kualitas visual berbeda-beda, dan pesan produk sering tumpang tindih dengan konten edukasi.',
    'Kami menyusun pilar konten, nada visual, dan template feed serta story yang bisa dipakai tim internal. Untuk periode peluncuran, kami merancang rangkaian kampanye tiga fase: teaser, edukasi bahan, lalu ajakan mencoba. Setiap aset dibuat dalam format feed, story, dan video pendek agar tim tidak perlu mendesain ulang per kanal.',
    'Tim Lumiere kini memiliki kalender konten dan template yang konsisten. Produksi konten mingguan menjadi lebih cepat, dan kampanye peluncuran berjalan tanpa aset yang tertinggal. Sistem ini tetap dipakai setelah periode kampanye selesai.',
    'Content Strategy, Social Media Design, Motion Ads',
    '3 bulan',
    '',
    NULL,
    'Social Media, Skincare, Campaign',
    'Kampanye Sosial Media Lumiere — Studi Kasus',
    'Studi kasus kampanye sosial media Lumiere: pilar konten, template feed dan story, serta rangkaian peluncuran tiga fase.',
    1, 1, 1, 3
) ON DUPLICATE KEY UPDATE id = id;

-- ------------------------------------------------------------
--  2. Testimoni — 3 contoh (ditandai "Contoh" di situs)
-- ------------------------------------------------------------

INSERT INTO testimonials (client_name, client_title, avatar, content, rating, is_featured, is_sample)
SELECT 'Rina Kusuma', 'Founder, Kedai Kopi Senja', NULL,
       'Prosesnya rapi dan komunikatif. Kami tidak hanya mendapat logo baru, tetapi cara menjelaskan brand ke pelanggan dan tim gerai. Materi promosi sekarang konsisten di semua cabang.',
       5, 1, 1
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM testimonials WHERE client_name = 'Rina Kusuma' AND is_sample = 1
);

INSERT INTO testimonials (client_name, client_title, avatar, content, rating, is_featured, is_sample)
SELECT 'Danu Prasetyo', 'Product Lead, FinTrack', NULL,
       'Tim ini benar-benar membaca data sebelum mendesain. Onboarding yang tadinya membingungkan sekarang jauh lebih sederhana, dan design system-nya masih kami pakai sampai hari ini.',
       5, 1, 1
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM testimonials WHERE client_name = 'Danu Prasetyo' AND is_sample = 1
);

INSERT INTO testimonials (client_name, client_title, avatar, content, rating, is_featured, is_sample)
SELECT 'Sari Dewanti', 'Marketing Manager, Lumiere Skincare', NULL,
       'Kami akhirnya punya sistem konten yang bisa dijalankan tim internal. Peluncuran produk terasa jauh lebih tenang karena semua aset sudah siap sebelum jadwal tayang.',
       5, 1, 1
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM testimonials WHERE client_name = 'Sari Dewanti' AND is_sample = 1
);
