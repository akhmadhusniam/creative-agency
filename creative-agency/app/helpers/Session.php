<?php
// ============================================================
//  app/helpers/Session.php
// ============================================================

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/',
                'secure'   => APP_ENV === 'production',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Flash: simpan sekali, baca sekali */
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        $val = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        $role = $_SESSION['user_role'] ?? '';
        return in_array($role, ['admin', 'owner']);
    }

    public static function userRole(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    public static function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    /**
     * Get user data from session
     */
    public static function user(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'id'    => self::userId(),
            'name'  => $_SESSION['user_name'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'role'  => self::userRole(),
            'department' => $_SESSION['user_department'] ?? null,
            'is_active' => $_SESSION['user_is_active'] ?? true,
        ];
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /** Require login — redirect jika belum login */
    public static function requireLogin(string $redirect = ''): void
    {
        if (!self::isLoggedIn()) {
            $redirect = $redirect ?: APP_URL . '/login';
            redirect($redirect);
        }
    }

    /** Require admin atau owner */
    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Akses ditolak.');
        }
    }

    /**
     * Require specific role dengan flexible check
     */
    public static function requireRole(string|array $roles): void
    {
        self::requireLogin();
        $userRole = self::userRole();
        $allowedRoles = (array) $roles;
        
        if (!in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            die('Akses ditolak untuk role ini.');
        }
    }

    /**
     * Require permission
     */
    public static function requirePermission(string $permission): void
    {
        self::requireLogin();
        
        if (!class_exists('RolePermission')) {
            require_once ROOT_PATH . '/app/helpers/RolePermission.php';
        }
        
        if (!RolePermission::can($permission)) {
            http_response_code(403);
            die('Tidak memiliki permission untuk aksi ini.');
        }
    }
}
