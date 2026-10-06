<?php
// ============================================================
//  app/helpers/RolePermission.php
//  Role-Based Access Control (RBAC) Helper
// ============================================================

class RolePermission
{
    /**
     * Daftar role yang tersedia
     */
    const ROLES = [
        'client'    => 'Client',
        'designer'  => 'Designer/Staff',
        'manager'   => 'Project Manager',
        'finance'   => 'Finance',
        'admin'     => 'Admin',
        'owner'     => 'Owner',
    ];

    /**
     * Permission matrix: role => [permissions]
     */
    const PERMISSIONS = [
        'client' => [
            'view_own_orders' => true,
            'create_order' => true,
            'view_services' => true,
            'edit_profile' => true,
            'download_files' => true,
        ],
        'designer' => [
            'view_assigned_orders' => true,
            'edit_assigned_orders' => true,
            'upload_assets' => true,
            'view_own_profile' => true,
            'request_revision' => true,
        ],
        'manager' => [
            'view_all_orders' => true,
            'assign_orders' => true,
            'update_order_status' => true,
            'view_team_dashboard' => true,
            'approve_completion' => true,
            'manage_deadlines' => true,
        ],
        'finance' => [
            'view_all_payments' => true,
            'approve_payments' => true,
            'generate_invoices' => true,
            'view_financial_reports' => true,
            'record_transactions' => true,
        ],
        'admin' => [
            'manage_users' => true,
            'manage_services' => true,
            'manage_portfolio' => true,
            'manage_orders' => true,
            'manage_payments' => true,
            'manage_settings' => true,
            'view_all_dashboards' => true,
        ],
        'owner' => [
            // Owner memiliki akses penuh
            '*' => true,
        ],
    ];

    /**
     * Role hierarchy untuk inheritance permission
     */
    const ROLE_HIERARCHY = [
        'owner' => ['admin', 'finance', 'manager', 'designer'],
        'admin' => ['manager', 'finance'],
        'manager' => ['designer'],
        'finance' => [],
        'designer' => [],
        'client' => [],
    ];

    /**
     * Check apakah user memiliki permission tertentu
     */
    public static function can(string $permission, ?array $user = null): bool
    {
        if (!$user) {
            $user = Session::user();
        }

        if (!$user) {
            return false;
        }

        $role = $user['role'] ?? 'client';

        // Owner memiliki akses penuh
        if ($role === 'owner' || self::PERMISSIONS[$role]['*'] ?? false) {
            return true;
        }

        // Check permission di role current
        if (self::PERMISSIONS[$role][$permission] ?? false) {
            return true;
        }

        // Check permission di role yang diinherit
        foreach (self::ROLE_HIERARCHY[$role] ?? [] as $inheritedRole) {
            if (self::PERMISSIONS[$inheritedRole][$permission] ?? false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check user dapat mengakses order tertentu
     */
    public static function canAccessOrder(int $orderId, ?array $user = null): bool
    {
        if (!$user) {
            $user = Session::user();
        }

        if (!$user) {
            return false;
        }

        // Owner, admin, manager dapat akses semua order
        if (in_array($user['role'], ['owner', 'admin', 'manager'])) {
            return true;
        }

        // Designer hanya bisa akses order yang di-assign ke mereka
        if ($user['role'] === 'designer') {
            $db = db();
            $stmt = $db->prepare('
                SELECT assigned_to FROM orders WHERE id = ? AND assigned_to = ?
            ');
            $stmt->execute([$orderId, $user['id']]);
            return (bool) $stmt->fetch();
        }

        // Client hanya bisa akses order mereka sendiri
        if ($user['role'] === 'client') {
            $db = db();
            $stmt = $db->prepare('
                SELECT user_id FROM orders WHERE id = ? AND user_id = ?
            ');
            $stmt->execute([$orderId, $user['id']]);
            return (bool) $stmt->fetch();
        }

        return false;
    }

    /**
     * Get semua role yang tersedia
     */
    public static function getAllRoles(): array
    {
        return self::ROLES;
    }

    /**
     * Get label untuk role
     */
    public static function getRoleLabel(string $role): string
    {
        return self::ROLES[$role] ?? $role;
    }

    /**
     * Get dashboard URL untuk role
     */
    public static function getDashboardUrl(string $role): string
    {
        return match ($role) {
            'client'   => APP_URL . '/dashboard',
            'designer' => APP_URL . '/designer/dashboard',
            'manager'  => APP_URL . '/manager/dashboard',
            'finance'  => APP_URL . '/finance/dashboard',
            'admin'    => APP_URL . '/admin/dashboard',
            'owner'    => APP_URL . '/admin/dashboard',
            default    => APP_URL . '/dashboard',
        };
    }

    /**
     * Check apakah user aktif dan tidak suspended
     */
    public static function isActive(?array $user = null): bool
    {
        if (!$user) {
            $user = Session::user();
        }

        return $user && ($user['is_active'] ?? false);
    }

    /**
     * Get list user dengan role tertentu
     */
    public static function getUsersByRole(string $role): array
    {
        $db = db();
        $stmt = $db->prepare('
            SELECT id, name, email, phone, department, is_active
            FROM users
            WHERE role = ? AND is_active = 1
            ORDER BY name
        ');
        $stmt->execute([$role]);
        return $stmt->fetchAll() ?? [];
    }

    /**
     * Get filter HTML untuk designer selection (untuk manager)
     */
    public static function getDesignerSelectHTML(int $selectedId = 0): string
    {
        $designers = self::getUsersByRole('designer');
        $html = '<select name="assigned_to" class="form-control" required>';
        $html .= '<option value="">-- Pilih Designer --</option>';
        
        foreach ($designers as $designer) {
            $selected = $designer['id'] === $selectedId ? ' selected' : '';
            $html .= '<option value="' . $designer['id'] . '"' . $selected . '>';
            $html .= e($designer['name']) . ' (' . e($designer['department'] ?? 'Design') . ')';
            $html .= '</option>';
        }
        
        $html .= '</select>';
        return $html;
    }

    /**
     * Get filter HTML untuk manager selection
     */
    public static function getManagerSelectHTML(int $selectedId = 0): string
    {
        $managers = self::getUsersByRole('manager');
        $html = '<select name="manager_id" class="form-control">';
        $html .= '<option value="">-- Pilih Manager --</option>';
        
        foreach ($managers as $manager) {
            $selected = $manager['id'] === $selectedId ? ' selected' : '';
            $html .= '<option value="' . $manager['id'] . '"' . $selected . '>';
            $html .= e($manager['name']) . ' (' . e($manager['department'] ?? 'Operations') . ')';
            $html .= '</option>';
        }
        
        $html .= '</select>';
        return $html;
    }
}
