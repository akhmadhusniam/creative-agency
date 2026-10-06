<?php
// ============================================================
//  app/helpers/AuditLog.php
//  Fungsi global logAudit() — mencatat aksi sensitif ke tabel audit_logs.
//  Tabel sudah didefinisikan di database/migrations.sql.
// ============================================================

if (!function_exists('logAudit')) {
    /**
     * Catat satu entri audit log.
     *
     * @param string     $entityType  'order' | 'payment' | 'user' | 'settings' | dst
     * @param int        $entityId    ID entitas terkait (0 kalau tidak relevan, mis. settings global)
     * @param string     $action      'create' | 'update' | 'delete' | 'assign' | 'approve' | dst
     * @param array      $newValues   Data setelah perubahan (akan disimpan sebagai JSON)
     * @param array|null $oldValues   Data sebelum perubahan, opsional
     */
    function logAudit(
        string $entityType,
        int $entityId,
        string $action,
        array $newValues = [],
        ?array $oldValues = null
    ): void {
        // Audit log butuh actor yang jelas — kalau tidak ada user login, jangan catat apa pun
        if (!class_exists('Session') || !Session::isLoggedIn()) {
            return;
        }

        try {
            $stmt = db()->prepare('
                INSERT INTO audit_logs
                    (user_id, entity_type, entity_id, action, old_values, new_values, ip_address, user_agent)
                VALUES
                    (:user_id, :entity_type, :entity_id, :action, :old_values, :new_values, :ip_address, :user_agent)
            ');

            $stmt->execute([
                'user_id'     => Session::userId(),
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'action'      => $action,
                'old_values'  => $oldValues !== null ? json_encode($oldValues) : null,
                'new_values'  => !empty($newValues) ? json_encode($newValues) : null,
                'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent'  => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Audit logging tidak boleh sampai menggagalkan aksi utama pengguna.
            // Kalau tabelnya belum ter-migrate misalnya, cukup diamkan saja di sini.
            error_log('[logAudit] gagal mencatat audit log: ' . $e->getMessage());
        }
    }
}
