<?php
// ============================================================
//  app/models/User.php
// ============================================================

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        // Try inserting with new role/department columns first (after migration)
        // Falls back to old columns if migration hasn't been run yet
        try {
            $stmt = $this->db->prepare('
                INSERT INTO users (name, email, password, phone, role, department, verify_token)
                VALUES (:name, :email, :password, :phone, :role, :department, :verify_token)
            ');
            $stmt->execute([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'password'     => $data['password'],
                'phone'        => $data['phone'] ?? null,
                'role'         => $data['role'] ?? 'client',
                'department'   => $data['department'] ?? null,
                'verify_token' => $data['verify_token'] ?? null,
            ]);
        } catch (\PDOException $e) {
            // Fallback: schema migration not run yet - use old column set
            if (strpos($e->getMessage(), 'Unknown column') !== false) {
                $stmt = $this->db->prepare('
                    INSERT INTO users (name, email, password, phone, verify_token)
                    VALUES (:name, :email, :password, :phone, :verify_token)
                ');
                $stmt->execute([
                    'name'         => $data['name'],
                    'email'        => $data['email'],
                    'password'     => $data['password'],
                    'phone'        => $data['phone'] ?? null,
                    'verify_token' => $data['verify_token'] ?? null,
                ]);
            } else {
                throw $e;
            }
        }
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];
        foreach ($data as $col => $val) {
            $fields[] = "$col = :$col";
            $params[$col] = $val;
        }
        $params['id'] = $id;
        $sql  = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function setResetToken(int $id, string $token, string $expires): void
    {
        $this->update($id, [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);
    }

    public function verifyEmail(string $token): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM users WHERE verify_token = ? AND is_verified = 0'
        );
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        if (!$user) return false;

        return $this->update($user['id'], [
            'is_verified'  => 1,
            'verify_token' => null,
        ]);
    }

    /**
     * Cari user berdasarkan reset_token yang masih berlaku (belum expired).
     */
    public function findByValidResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE reset_token = ? AND reset_expires IS NOT NULL AND reset_expires > NOW()'
        );
        $stmt->execute([$token]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Set password baru dan bersihkan token reset agar tidak bisa dipakai ulang.
     */
    public function resetPassword(int $id, string $hashedPassword): bool
    {
        return $this->update($id, [
            'password'      => $hashedPassword,
            'reset_token'   => null,
            'reset_expires' => null,
        ]);
    }

    // ========== ROLE & STAFF MANAGEMENT ==========

    /**
     * Get user dengan role tertentu
     */
    public function findByRole(string $role): array
    {
        $stmt = $this->db->prepare('
            SELECT id, name, email, phone, department, is_active, hire_date
            FROM users
            WHERE role = ? AND is_active = 1
            ORDER BY name
        ');
        $stmt->execute([$role]);
        return $stmt->fetchAll() ?? [];
    }

    /**
     * Get semua staff (non-client)
     */
    public function getAllStaff(): array
    {
        $stmt = $this->db->prepare('
            SELECT id, name, email, phone, role, department, is_active, hire_date
            FROM users
            WHERE role IN ("designer", "manager", "finance", "admin", "owner")
            ORDER BY role DESC, name
        ');
        $stmt->execute();
        return $stmt->fetchAll() ?? [];
    }

    /**
     * Update user role
     */
    public function updateRole(int $id, string $role, ?string $department = null): bool
    {
        $data = ['role' => $role];
        if ($department !== null) {
            $data['department'] = $department;
        }
        return $this->update($id, $data);
    }

    /**
     * Deactivate user (suspend)
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, ['is_active' => 0]);
    }

    /**
     * Activate user (unsuspend)
     */
    public function activate(int $id): bool
    {
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Check user apakah designer available untuk di-assign order
     */
    public function isDesignerAvailable(int $id): bool
    {
        $stmt = $this->db->prepare('
            SELECT is_active FROM users WHERE id = ? AND role = "designer"
        ');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user && $user['is_active'];
    }
}
