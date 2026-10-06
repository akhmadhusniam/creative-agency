-- ============================================================
--  Creative Agency — Install Database (gabungan schema + migration)
--  Jalankan file ini SEKALI SAJA di MySQL (phpMyAdmin > tab SQL > Go)
--  Gabungan dari schema.sql + migrations.sql, urutan tetap dijaga.
-- ============================================================

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


-- ============================================================
--  Bagian di bawah ini isinya dari migrations.sql
--  (RBAC: role designer/manager/finance/owner, assignment, audit log,
--   notifications, dan kolom inquiry_type di form kontak)
-- ============================================================

-- ============================================================
--  Creative Agency RBAC Migration - Phase 1 & 2
--  Implementasi Role-Based Access Control (RBAC)
-- ============================================================

-- Phase 1: Client/Admin/Owner roles
-- -------------------------------------------------------

-- 1. Perluas tabel users dengan kolom RBAC
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('client','designer','manager','finance','admin','owner') NOT NULL DEFAULT 'client' AFTER is_verified;
ALTER TABLE users ADD COLUMN IF NOT EXISTS department VARCHAR(50) DEFAULT NULL AFTER role;
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER department;
ALTER TABLE users ADD COLUMN IF NOT EXISTS hire_date DATE DEFAULT NULL AFTER is_active;
ALTER TABLE users ADD COLUMN IF NOT EXISTS salary DECIMAL(12,2) DEFAULT NULL AFTER hire_date;

-- Create index untuk role dan department untuk query yang lebih cepat
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_department ON users(department);

-- -------------------------------------------------------
-- Phase 2: Designer/Manager roles dengan order assignment
-- -------------------------------------------------------

-- 2. Perluas tabel orders dengan assignment fields
ALTER TABLE orders ADD COLUMN IF NOT EXISTS assigned_to INT UNSIGNED DEFAULT NULL AFTER notes;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS manager_id INT UNSIGNED DEFAULT NULL AFTER assigned_to;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium' AFTER manager_id;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS deadline_date DATE DEFAULT NULL AFTER priority;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS revision_count TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER deadline_date;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS max_revisions TINYINT UNSIGNED NOT NULL DEFAULT 2 AFTER revision_count;

-- Foreign keys untuk assigned_to dan manager_id
ALTER TABLE orders 
ADD CONSTRAINT fk_orders_assigned_to FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL;
ALTER TABLE orders 
ADD CONSTRAINT fk_orders_manager_id FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL;

-- Create indexes untuk performance
CREATE INDEX idx_orders_assigned_to ON orders(assigned_to);
CREATE INDEX idx_orders_manager_id ON orders(manager_id);
CREATE INDEX idx_orders_priority ON orders(priority);

-- -------------------------------------------------------
-- 3. Perluas tabel payments dengan finance tracking
-- -------------------------------------------------------
ALTER TABLE payments ADD COLUMN IF NOT EXISTS invoice_number VARCHAR(50) DEFAULT NULL AFTER gateway_payload;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS paid_date DATETIME DEFAULT NULL AFTER invoice_number;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS approver_id INT UNSIGNED DEFAULT NULL AFTER paid_date;
ALTER TABLE payments ADD COLUMN IF NOT EXISTS notes TEXT DEFAULT NULL AFTER approver_id;

-- Foreign key untuk approver
ALTER TABLE payments 
ADD CONSTRAINT fk_payments_approver_id FOREIGN KEY (approver_id) REFERENCES users(id) ON DELETE SET NULL;

CREATE INDEX idx_payments_approver_id ON payments(approver_id);

-- -------------------------------------------------------
-- 4. Buat tabel audit log untuk tracking
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    entity_type     VARCHAR(50) NOT NULL,           -- 'order', 'payment', 'service', dll
    entity_id       INT UNSIGNED NOT NULL,
    action          VARCHAR(50) NOT NULL,           -- 'create', 'update', 'delete', 'assign', 'approve'
    old_values      JSON DEFAULT NULL,
    new_values      JSON DEFAULT NULL,
    ip_address      VARCHAR(45) DEFAULT NULL,
    user_agent      VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_entity_type (entity_type),
    INDEX idx_entity_id (entity_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 5. Buat tabel notifications untuk real-time alerts
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS notifications (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    title           VARCHAR(200) NOT NULL,
    message         TEXT DEFAULT NULL,
    type            ENUM('order','payment','assignment','approval','system') NOT NULL DEFAULT 'system',
    related_entity_type VARCHAR(50) DEFAULT NULL,
    related_entity_id   INT UNSIGNED DEFAULT NULL,
    is_read         TINYINT(1) NOT NULL DEFAULT 0,
    read_at         DATETIME DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_unread (user_id, is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 6. Seed data untuk staff (Phase 1 & 2)
-- -------------------------------------------------------

-- Tambahkan staff designer
INSERT INTO users (name, email, password, phone, role, department, is_active, is_verified, hire_date) 
VALUES (
    'Rizki Designer',
    'rizki@creativeagency.id',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxQfmA6w',
    '081234567890',
    'designer',
    'Design',
    1,
    1,
    '2024-01-15'
) ON DUPLICATE KEY UPDATE role='designer', department='Design', is_active=1;

-- Tambahkan staff project manager
INSERT INTO users (name, email, password, phone, role, department, is_active, is_verified, hire_date) 
VALUES (
    'Sinta Manager',
    'sinta@creativeagency.id',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxQfmA6w',
    '081234567891',
    'manager',
    'Operations',
    1,
    1,
    '2024-01-10'
) ON DUPLICATE KEY UPDATE role='manager', department='Operations', is_active=1;

-- Tambahkan staff finance
INSERT INTO users (name, email, password, phone, role, department, is_active, is_verified, hire_date) 
VALUES (
    'Budi Finance',
    'budi@creativeagency.id',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxQfmA6w',
    '081234567892',
    'finance',
    'Finance',
    1,
    1,
    '2024-01-12'
) ON DUPLICATE KEY UPDATE role='finance', department='Finance', is_active=1;

-- Update admin user role menjadi 'owner'
UPDATE users SET role = 'owner' WHERE email = 'admin@creativeagency.id' LIMIT 1;

-- Kategori jenis kebutuhan di form kontak (Klien Baru / Kerja Sama / Karir / Lainnya)
ALTER TABLE messages ADD COLUMN IF NOT EXISTS inquiry_type VARCHAR(50) DEFAULT NULL AFTER user_id;

-- ============================================================
--  Status: Ready untuk Phase 1 & 2
--  Jalankan script ini di MySQL untuk setup database
-- ============================================================
