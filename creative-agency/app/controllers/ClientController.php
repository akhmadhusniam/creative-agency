<?php
// ============================================================
//  app/controllers/ClientController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/models/User.php';

class ClientController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
        Session::start();
        Session::requireLogin();
    }

    public function dashboard(): void
    {
        $userId = Session::userId();
        $orders = $this->db->prepare('
            SELECT o.*, s.name AS service_name
            FROM orders o
            JOIN services s ON o.service_id = s.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
            LIMIT 10
        ');
        $orders->execute([$userId]);
        $orders = $orders->fetchAll();

        $title = 'Dashboard';
        require VIEW_PATH . '/client/dashboard.php';
    }

    public function orders(): void
    {
        $userId = Session::userId();
        $orders = $this->db->prepare('
            SELECT o.*, s.name AS service_name, p.status AS payment_status
            FROM orders o
            JOIN services s ON o.service_id = s.id
            LEFT JOIN payments p ON p.order_id = o.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ');
        $orders->execute([$userId]);
        $orders = $orders->fetchAll();

        $title = 'Pesanan Saya';
        require VIEW_PATH . '/client/orders.php';
    }

    public function profile(): void
    {
        $userId = Session::userId();
        $userModel = new User();
        $user = $userModel->findById($userId);

        $title = 'Profil Saya';
        $success = Session::getFlash('success');
        require VIEW_PATH . '/client/profile.php';
    }

    public function updateProfile(): void
    {
        verifyCsrf();
        $userId = Session::userId();

        $v = new Validator($_POST);
        $v->required(['name'])->email('email');

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            redirect(APP_URL . '/dashboard/profile');
        }

        $userModel = new User();
        $data = ['name' => trim($_POST['name'])];
        if (!empty($_POST['phone'])) {
            $data['phone'] = trim($_POST['phone']);
        }
        // Update password jika diisi
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 8) {
                Session::flash('error', 'Password minimal 8 karakter.');
                redirect(APP_URL . '/dashboard/profile');
            }
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $userModel->update($userId, $data);
        Session::set('user_name', $data['name']);
        Session::flash('success', 'Profil berhasil diperbarui.');
        redirect(APP_URL . '/dashboard/profile');
    }
}
