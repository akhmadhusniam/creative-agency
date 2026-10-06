-- ============================================================
--  Creative Agency Professional Services - Database Schema
--  Engine: MySQL 8.x | Charset: utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS creative_agency
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE creative_agency;

-- -------------------------------------------------------
-- 1. USERS (klien yang mendaftar)
-- -------------------------------------------------------
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    phone       VARCHAR(20)   DEFAULT NULL,
    avatar      VARCHAR(255)  DEFAULT NULL,
    role        ENUM('client','admin') NOT NULL DEFAULT 'client',
    is_verified TINYINT(1)    NOT NULL DEFAULT 0,
    verify_token VARCHAR(64)  DEFAULT NULL,
    reset_token  VARCHAR(64)  DEFAULT NULL,
    reset_expires DATETIME    DEFAULT NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 2. SERVICE CATEGORIES
-- -------------------------------------------------------
CREATE TABLE service_categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(80)   NOT NULL,
    slug        VARCHAR(80)   NOT NULL UNIQUE,
    icon        VARCHAR(50)   DEFAULT NULL,   -- FontAwesome class or emoji
    description TEXT          DEFAULT NULL,
    sort_order  TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 3. SERVICES (paket layanan)
-- -------------------------------------------------------
CREATE TABLE services (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED NOT NULL,
    name            VARCHAR(120) NOT NULL,
    slug            VARCHAR(120) NOT NULL UNIQUE,
    short_desc      VARCHAR(255) DEFAULT NULL,
    description     TEXT         DEFAULT NULL,
    price           DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    price_type      ENUM('fixed','starting_from','custom') NOT NULL DEFAULT 'fixed',
    delivery_days   TINYINT UNSIGNED DEFAULT NULL,
    thumbnail       VARCHAR(255) DEFAULT NULL,
    is_active       TINYINT(1)  NOT NULL DEFAULT 1,
    sort_order      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at      DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 4. SERVICE FEATURES (fitur per paket)
-- -------------------------------------------------------
CREATE TABLE service_features (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id  INT UNSIGNED NOT NULL,
    feature     VARCHAR(150) NOT NULL,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 5. ORDERS (pemesanan jasa)
-- -------------------------------------------------------
CREATE TABLE orders (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code      VARCHAR(30)  NOT NULL UNIQUE,  -- e.g. ORD-20250625-0001
    user_id         INT UNSIGNED NOT NULL,
    service_id      INT UNSIGNED NOT NULL,
    brief           TEXT         DEFAULT NULL,      -- brief dari klien
    attachments     JSON         DEFAULT NULL,      -- file paths JSON array
    subtotal        DECIMAL(12,2) NOT NULL,
    discount        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total           DECIMAL(12,2) NOT NULL,
    status          ENUM('pending','confirmed','in_progress','revision','completed','cancelled')
                    NOT NULL DEFAULT 'pending',
    deadline        DATE         DEFAULT NULL,
    notes           TEXT         DEFAULT NULL,      -- catatan admin
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE RESTRICT,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 6. PAYMENTS (integrasi payment gateway)
-- -------------------------------------------------------
CREATE TABLE payments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id        INT UNSIGNED NOT NULL UNIQUE,
    gateway         ENUM('midtrans','xendit','manual') NOT NULL DEFAULT 'midtrans',
    transaction_id  VARCHAR(100) DEFAULT NULL,      -- ID dari gateway
    payment_method  VARCHAR(50)  DEFAULT NULL,      -- bank_transfer, gopay, qris, dll
    amount          DECIMAL(12,2) NOT NULL,
    status          ENUM('pending','paid','expired','failed','refunded')
                    NOT NULL DEFAULT 'pending',
    paid_at         DATETIME     DEFAULT NULL,
    gateway_payload JSON         DEFAULT NULL,      -- raw response dari gateway
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 7. PORTFOLIO
-- -------------------------------------------------------
CREATE TABLE portfolio (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED DEFAULT NULL,
    title       VARCHAR(120) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    client_name VARCHAR(100) DEFAULT NULL,
    description TEXT         DEFAULT NULL,
    cover_image VARCHAR(255) NOT NULL,
    images      JSON         DEFAULT NULL,
    tags        VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1)  NOT NULL DEFAULT 0,
    sort_order  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 8. TESTIMONIALS
-- -------------------------------------------------------
CREATE TABLE testimonials (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED DEFAULT NULL,
    client_name VARCHAR(100) NOT NULL,
    client_title VARCHAR(100) DEFAULT NULL,
    avatar      VARCHAR(255) DEFAULT NULL,
    content     TEXT         NOT NULL,
    rating      TINYINT UNSIGNED NOT NULL DEFAULT 5,
    is_featured TINYINT(1)  NOT NULL DEFAULT 0,
    created_at  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 9. MESSAGES / CONTACT FORM
-- -------------------------------------------------------
CREATE TABLE messages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED DEFAULT NULL,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    company     VARCHAR(150) DEFAULT NULL,
    phone       VARCHAR(30)  DEFAULT NULL,
    subject     VARCHAR(200) DEFAULT NULL,
    budget      VARCHAR(50)  DEFAULT NULL,
    body        TEXT         NOT NULL,
    is_read     TINYINT(1)  NOT NULL DEFAULT 0,
    created_at  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- 10. SITE SETTINGS (key-value config)
-- -------------------------------------------------------
CREATE TABLE settings (
    `key`   VARCHAR(80)  NOT NULL PRIMARY KEY,
    `value` TEXT         DEFAULT NULL
) ENGINE=InnoDB;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: Admin@1234 — ganti setelah deploy!)
INSERT INTO users (name, email, password, role, is_verified) VALUES
('Admin Agency', 'admin@creativeagency.id',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxQfmA6w',
 'admin', 1);

-- Kategori layanan
INSERT INTO service_categories (name, slug, icon, sort_order) VALUES
('Branding & Identity',    'branding',    '✦', 1),
('UI/UX Design',           'uiux',        '◈', 2),
('Social Media Design',    'socmed',      '◉', 3),
('Motion & Video',         'motion',      '◎', 4),
('Print & Merchandise',    'print',       '◆', 5);

-- Contoh layanan
INSERT INTO services (category_id, name, slug, short_desc, price, price_type, delivery_days, is_active) VALUES
(1, 'Brand Identity Starter', 'brand-identity-starter',
 'Logo + color palette + typography guide', 1500000, 'fixed', 7, 1),
(1, 'Full Brand System', 'full-brand-system',
 'Logo, brand guideline lengkap, stationery, mockup', 4500000, 'starting_from', 14, 1),
(2, 'Landing Page Design', 'landing-page-design',
 'Desain UI landing page 1–5 section, Figma deliverable', 1200000, 'fixed', 5, 1),
(2, 'Mobile App UI', 'mobile-app-ui',
 'Desain UI aplikasi mobile hingga 15 screen', 3500000, 'starting_from', 14, 1),
(3, 'Social Media Pack', 'social-media-pack',
 '10 template feed + 5 story, editable Canva/Figma', 750000, 'fixed', 3, 1);

-- Fitur layanan (Brand Identity Starter)
INSERT INTO service_features (service_id, feature) VALUES
(1, 'Logo (3 konsep awal)'),
(1, 'Revisi 2x'),
(1, 'Color palette + tipografi'),
(1, 'File PNG, SVG, PDF');

-- Fitur layanan (Full Brand System)
INSERT INTO service_features (service_id, feature) VALUES
(2, 'Semua fitur Starter'),
(2, 'Brand Guideline PDF'),
(2, 'Stationery design (KTP, amplop, kartu nama)'),
(2, 'Mockup presentasi'),
(2, 'Revisi tak terbatas');

-- Settings default
INSERT INTO settings (`key`, `value`) VALUES
('site_name',         'Creative Studio'),
('site_tagline',      'Wujudkan Identitas Visual Bisnis Anda'),
('site_email',        'hello@creativeagency.id'),
('site_phone',        '+62 812-3456-7890'),
('site_whatsapp',     '6281234567890'),
('site_address',      'Jl. Kemang Raya No. 12B, Jakarta Selatan, 12730'),
('site_hours_office', 'Senin–Jumat 09.00–17.00 WIB'),
('site_hours_wa',     'Setiap hari 08.00–21.00 WIB'),
('site_maps_query',   'Jl. Kemang Raya No. 12B Jakarta Selatan'),
('site_meeting_url',  ''),
('midtrans_env',      'sandbox'),
('currency',          'IDR');
