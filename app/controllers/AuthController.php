<?php
// ============================================================
//  app/controllers/AuthController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/MailHelper.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        Session::start();
    }

    // ---------- REGISTER ----------
    public function showRegister(): void
    {
        if (Session::isLoggedIn()) {
            redirect(APP_URL . '/dashboard');
        }
        $title = 'Daftar Akun';
        require VIEW_PATH . '/auth/register.php';
    }

    public function register(): void
    {
        verifyCsrf();

        $v = new Validator($_POST);
        $v->required(['name','email','password','password_confirm'])
          ->email('email')
          ->minLength('password', 8)
          ->match('password', 'password_confirm', 'Konfirmasi password tidak cocok');

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            Session::flash('old', $_POST);
            redirect(APP_URL . '/register');
        }

        if ($this->userModel->findByEmail($_POST['email'])) {
            Session::flash('error', 'Email sudah terdaftar.');
            redirect(APP_URL . '/register');
        }

        $token = bin2hex(random_bytes(32));
        $userId = $this->userModel->create([
            'name'         => trim($_POST['name']),
            'email'        => strtolower(trim($_POST['email'])),
            'password'     => password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'phone'        => trim($_POST['phone'] ?? ''),
            'verify_token' => $token,
        ]);

        MailHelper::sendVerification(trim($_POST['email']), trim($_POST['name']), $token);

        Session::flash('success', 'Registrasi berhasil! Cek email Anda untuk verifikasi akun, lalu silakan login.');
        redirect(APP_URL . '/login');
    }

    // ---------- VERIFY EMAIL ----------
    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if (!$token || !$this->userModel->verifyEmail($token)) {
            Session::flash('error', 'Link verifikasi tidak valid atau sudah digunakan.');
            redirect(APP_URL . '/login');
        }

        Session::flash('success', 'Email berhasil diverifikasi. Silakan login.');
        redirect(APP_URL . '/login');
    }

    // ---------- LOGIN ----------
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            redirect(APP_URL . '/dashboard');
        }
        $title = 'Masuk';
        require VIEW_PATH . '/auth/login.php';
    }

    public function login(): void
    {
        verifyCsrf();

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email dan password wajib diisi.');
            redirect(APP_URL . '/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('error', 'Email atau password salah.');
            redirect(APP_URL . '/login');
        }

        // Check apakah user aktif
        if (!$user['is_active']) {
            Session::flash('error', 'Akun Anda telah dinonaktifkan.');
            redirect(APP_URL . '/login');
        }

        // Regenerate session ID untuk keamanan
        session_regenerate_id(true);

        Session::set('user_id',        $user['id']);
        Session::set('user_name',      $user['name']);
        Session::set('user_role',      $user['role']);
        Session::set('user_email',     $user['email']);
        Session::set('user_department',$user['department']);
        Session::set('user_is_active', $user['is_active']);

        // Load role permission
        require_once ROOT_PATH . '/app/helpers/RolePermission.php';
        
        // Redirect ke dashboard sesuai role
        $dashboardUrl = RolePermission::getDashboardUrl($user['role']);
        redirect($dashboardUrl);
    }

    // ---------- LOGOUT ----------
    public function logout(): void
    {
        Session::destroy();
        redirect(APP_URL . '/login');
    }

    // ---------- FORGOT PASSWORD ----------
    public function showForgotPassword(): void
    {
        $title = 'Lupa Password';
        require VIEW_PATH . '/auth/forgot-password.php';
    }

    public function forgotPassword(): void
    {
        verifyCsrf();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $user  = $this->userModel->findByEmail($email);

        // Selalu tampilkan pesan sukses (security: jangan bocorkan apakah email terdaftar)
        Session::flash('success', 'Jika email terdaftar, link reset password telah dikirim.');

        if ($user) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $this->userModel->setResetToken($user['id'], $token, $expires);
            MailHelper::sendReset($user['email'], $user['name'], $token);
        }

        redirect(APP_URL . '/forgot-password');
    }

    // ---------- RESET PASSWORD ----------
    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';
        $user  = $token ? $this->userModel->findByValidResetToken($token) : null;

        if (!$user) {
            Session::flash('error', 'Link reset password tidak valid atau sudah kedaluwarsa. Silakan minta link baru.');
            redirect(APP_URL . '/forgot-password');
        }

        $title = 'Reset Password';
        require VIEW_PATH . '/auth/reset-password.php';
    }

    public function resetPassword(): void
    {
        verifyCsrf();

        $token = $_POST['token'] ?? '';
        $user  = $token ? $this->userModel->findByValidResetToken($token) : null;

        if (!$user) {
            Session::flash('error', 'Link reset password tidak valid atau sudah kedaluwarsa. Silakan minta link baru.');
            redirect(APP_URL . '/forgot-password');
        }

        $v = new Validator($_POST);
        $v->required(['password', 'password_confirm'])
          ->minLength('password', 8)
          ->match('password', 'password_confirm', 'Konfirmasi password tidak cocok');

        if (!$v->passes()) {
            Session::flash('errors', $v->errors());
            redirect(APP_URL . '/reset-password?token=' . urlencode($token));
        }

        $this->userModel->resetPassword(
            $user['id'],
            password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12])
        );

        Session::flash('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
        redirect(APP_URL . '/login');
    }
}
