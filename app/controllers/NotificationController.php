<?php
// ============================================================
//  app/controllers/NotificationController.php
//  Halaman daftar notifikasi lengkap + aksi tandai dibaca.
//  Bisa diakses oleh user role manapun yang sudah login.
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/NotificationHelper.php';

class NotificationController
{
    public function __construct()
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            redirect(APP_URL . '/login');
        }
    }

    public function index(): void
    {
        $userId = Session::userId();
        $notifications = NotificationHelper::recent($userId, 50);
        $userRole = Session::userRole();
        $title = 'Notifikasi';
        require VIEW_PATH . '/notifications/index.php';
    }

    public function markRead(int $id): void
    {
        verifyCsrf();
        $userId = Session::userId();

        $stmt = db()->prepare('SELECT * FROM notifications WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        $notification = $stmt->fetch();

        if ($notification) {
            NotificationHelper::markRead($id, $userId);
            $target = NotificationHelper::linkFor($notification, Session::userRole());
            redirect($target);
        }

        redirect(APP_URL . '/notifications');
    }

    public function markAllRead(): void
    {
        verifyCsrf();
        NotificationHelper::markAllRead(Session::userId());
        redirect($_SERVER['HTTP_REFERER'] ?? APP_URL . '/notifications');
    }
}
