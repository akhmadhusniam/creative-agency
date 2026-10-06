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

-- ============================================================
--  Status: Ready untuk Phase 1 & 2
--  Jalankan script ini di MySQL untuk setup database
-- ============================================================
