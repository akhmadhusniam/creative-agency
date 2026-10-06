<?php
// ============================================================
//  app/helpers/NotificationHelper.php
//  Membuat & membaca notifikasi in-app. Tabel `notifications`
//  sudah didefinisikan di database/migrations.sql.
// ============================================================

class NotificationHelper
{
    /**
     * Kirim notifikasi ke satu user.
     */
    public static function notify(
        int $userId,
        string $title,
        ?string $message = null,
        string $type = 'system',
        ?string $relatedType = null,
        ?int $relatedId = null
    ): void {
        try {
            $stmt = db()->prepare('
                INSERT INTO notifications (user_id, title, message, type, related_entity_type, related_entity_id)
                VALUES (:user_id, :title, :message, :type, :related_type, :related_id)
            ');
            $stmt->execute([
                'user_id'       => $userId,
                'title'         => $title,
                'message'       => $message,
                'type'          => $type,
                'related_type'  => $relatedType,
                'related_id'    => $relatedId,
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai kegagalan notifikasi menggagalkan alur utama (checkout, assign, dll)
            error_log('[NotificationHelper] notify gagal: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi yang sama ke semua user dengan role tertentu.
     * Berguna untuk "order baru masuk" -> semua manager.
     *
     * @param string|string[] $roles
     */
    public static function notifyRole(
        $roles,
        string $title,
        ?string $message = null,
        string $type = 'system',
        ?string $relatedType = null,
        ?int $relatedId = null
    ): void {
        $roles = (array) $roles;
        $placeholders = implode(',', array_fill(0, count($roles), '?'));

        try {
            $stmt = db()->prepare("SELECT id FROM users WHERE role IN ($placeholders) AND is_active = 1");
            $stmt->execute($roles);
            $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\Throwable $e) {
            error_log('[NotificationHelper] notifyRole gagal ambil user: ' . $e->getMessage());
            return;
        }

        foreach ($userIds as $userId) {
            self::notify((int) $userId, $title, $message, $type, $relatedType, $relatedId);
        }
    }

    public static function unreadCount(int $userId): int
    {
        try {
            $stmt = db()->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0');
            $stmt->execute([$userId]);
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            error_log('[NotificationHelper] unreadCount gagal: ' . $e->getMessage());
            return 0;
        }
    }

    public static function recent(int $userId, int $limit = 8): array
    {
        $limit = max(1, min(50, $limit));
        try {
            $stmt = db()->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            error_log('[NotificationHelper] recent gagal: ' . $e->getMessage());
            return [];
        }
    }

    public static function markRead(int $notificationId, int $userId): bool
    {
        $stmt = db()->prepare('
            UPDATE notifications SET is_read = 1, read_at = NOW()
            WHERE id = ? AND user_id = ?
        ');
        return $stmt->execute([$notificationId, $userId]);
    }

    public static function markAllRead(int $userId): bool
    {
        $stmt = db()->prepare('
            UPDATE notifications SET is_read = 1, read_at = NOW()
            WHERE user_id = ? AND is_read = 0
        ');
        return $stmt->execute([$userId]);
    }

    /**
     * Link tujuan saat notifikasi diklik, berdasarkan tipe & role user.
     */
    public static function linkFor(array $notification, string $userRole): string
    {
        $id = $notification['related_entity_id'];

        return match ($notification['related_entity_type']) {
            'order' => match ($userRole) {
                'client'   => APP_URL . '/dashboard',
                'designer' => APP_URL . '/designer/orders/' . $id,
                'manager'  => APP_URL . '/manager/orders/' . $id . '/edit',
                'admin', 'owner' => APP_URL . '/admin/orders',
                default    => APP_URL,
            },
            'payment' => match ($userRole) {
                'finance', 'admin', 'owner' => APP_URL . '/finance/payments',
                default => APP_URL . '/dashboard',
            },
            default => APP_URL,
        };
    }
}
