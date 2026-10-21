<?php
// ============================================================
//  app/controllers/AuthController.php
// ============================================================

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/helpers/Session.php';
require_once ROOT_PATH . '/app/helpers/Validator.php';
require_once ROOT_PATH . '/app/helpers/MailHelper.php';
require_once ROOT_PATH . '/app/helpers/Totp.php';
require_once ROOT_PATH . '/app/helpers/RecoveryCodes.php';

class AuthController
{
    /** Role staff (non-klien) — dipakai untuk opt-in 2FA & pengalihan dashboard */
    private const STAFF_ROLES = ['designer', 'manager', 'finance', 'admin', 'owner'];

    /** Role yang WAJIB mengaktifkan 2FA */
    private const MANDATORY_2FA_ROLES = ['admin', 'owner'];

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
            $this->redirectToDashboard();
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
            Session::flash('old', ['email' => $email]);
            redirect(APP_URL . '/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('error', 'Email atau password salah.');
            Session::flash('old', ['email' => $email]);
            redirect(APP_URL . '/login');
        }

        // Check apakah user aktif
        if (!$user['is_active']) {
            Session::flash('error', 'Akun Anda telah dinonaktifkan.');
            Session::flash('old', ['email' => $email]);
            redirect(APP_URL . '/login');
        }

        // Lanjut: 2FA (tantangan / wajib setup untuk owner & admin) atau langsung masuk
        $this->continueAfterCredentials($user);
    }

    // ---------- STAFF / ADMIN LOGIN (dialihkan ke halaman login tunggal) ----------
    public function showStaffLogin(): void
    {
        redirect(APP_URL . '/login');
    }

    // ---------- 2FA CHALLENGE ----------
    public function showTwoFactor(): void
    {
        if ($this->pendingTwoFactorUser() === null) {
            Session::flash('error', 'Silakan masuk terlebih dahulu.');
            redirect(APP_URL . '/login');
        }
        $title = 'Verifikasi Dua Faktor';
        require VIEW_PATH . '/auth/admin-2fa.php';
    }

    public function verifyTwoFactor(): void
    {
        verifyCsrf();

        $userId = $this->pendingTwoFactorUser();
        if ($userId === null) {
            Session::flash('error', 'Sesi verifikasi kedaluwarsa. Silakan masuk kembali.');
            redirect(APP_URL . '/login');
        }

        $code = trim($_POST['code'] ?? '');
        if ($code === '') {
            Session::flash('error', 'Masukkan kode 2FA.');
            redirect(APP_URL . '/admin/2fa');
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            Session::flash('error', 'Akun tidak ditemukan. Silakan masuk kembali.');
            redirect(APP_URL . '/login');
        }

        $verified = false;

        // Coba kode TOTP
        if (!empty($user['twofa_secret']) && Totp::verify($user['twofa_secret'], $code)) {
            $verified = true;
        } else {
            // Coba kode recovery (sekali pakai)
            $result = RecoveryCodes::verify($code, $user['twofa_recovery'] ?? null);
            if ($result !== false) {
                $this->userModel->updateRecoveryCodes($user['id'], $result['json']);
                $verified = true;
            }
        }

        if (!$verified) {
            Session::flash('error', 'Kode 2FA tidak valid.');
            redirect(APP_URL . '/admin/2fa');
        }

        $this->completeLogin($user);
    }

    // ---------- 2FA SETUP / ENABLE ----------
    public function showSetupTwoFactor(): void
    {
        $userId = $this->resolveSetupUserId();
        if ($userId === null) {
            Session::flash('error', 'Silakan masuk terlebih dahulu.');
            redirect(APP_URL . '/login');
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            redirect(APP_URL . '/login');
        }

        // Kalau sudah aktif, tidak perlu setup lagi
        if ($this->userModel->isTwoFactorEnabled($user)) {
            Session::flash('success', '2FA sudah aktif.');
            redirect($this->roleDashboardUrl($user['role']));
        }

        // Secret & recovery digenerate sekali, lalu disimpan di session sampai diverifikasi
        $secret = Session::get('2fa_setup_secret');
        if (!$secret) {
            $secret = Totp::generateSecret();
            Session::set('2fa_setup_secret', $secret);
        }

        $recoveryCodes = Session::get('2fa_setup_recovery');
        if (!is_array($recoveryCodes)) {
            $recoveryCodes = RecoveryCodes::generate(8);
            Session::set('2fa_setup_recovery', $recoveryCodes);
        }

        $otpauthUri = Totp::provisioningUri(APP_NAME, $user['email'], $secret);
        $title      = 'Aktifkan 2FA';
        require VIEW_PATH . '/auth/admin-2fa-setup.php';
    }

    public function enableTwoFactor(): void
    {
        verifyCsrf();

        $userId = $this->resolveSetupUserId();
        if ($userId === null) {
            Session::flash('error', 'Silakan masuk terlebih dahulu.');
            redirect(APP_URL . '/login');
        }

        $secret        = Session::get('2fa_setup_secret');
        $recoveryCodes = Session::get('2fa_setup_recovery');
        $code          = trim($_POST['code'] ?? '');

        if (!$secret || !is_array($recoveryCodes)) {
            redirect(APP_URL . '/admin/2fa/setup');
        }

        if (!Totp::verify($secret, $code)) {
            Session::flash('error', 'Kode tidak cocok. Pastikan jam perangkat sudah benar, lalu coba lagi.');
            redirect(APP_URL . '/admin/2fa/setup');
        }

        $this->userModel->setTwoFactor($userId, $secret, RecoveryCodes::hash($recoveryCodes));

        // Bersihkan data setup
        Session::remove('2fa_setup_secret');
        Session::remove('2fa_setup_recovery');
        Session::remove('2fa_setup_user_id');
        Session::remove('2fa_setup_expires');

        $user = $this->userModel->findById($userId);
        Session::flash('success', '2FA berhasil diaktifkan.');

        // Kalau user belum login penuh (alur dari halaman login), selesaikan login
        if (!Session::isLoggedIn()) {
            $this->completeLogin($user);
        }

        redirect($this->roleDashboardUrl($user['role']));
    }

    // ---------- 2FA MANAGEMENT (staff yang sudah login) ----------
    public function twoFactorSettings(): void
    {
        if (!Session::isLoggedIn()) {
            redirect(APP_URL . '/login');
        }

        $user = $this->userModel->findById(Session::userId());
        if (!$user) {
            redirect(APP_URL . '/login');
        }

        $enabled   = $this->userModel->isTwoFactorEnabled($user);
        $mandatory = in_array($user['role'], self::MANDATORY_2FA_ROLES, true);
        $dashboardUrl = $this->roleDashboardUrl($user['role']);

        $title = 'Keamanan Akun (2FA)';
        require VIEW_PATH . '/auth/admin-2fa-settings.php';
    }

    public function disableTwoFactor(): void
    {
        if (!Session::isLoggedIn()) {
            redirect(APP_URL . '/login');
        }
        verifyCsrf();

        $user = $this->userModel->findById(Session::userId());
        if (!$user) {
            redirect(APP_URL . '/login');
        }

        // Admin/owner wajib 2FA → tidak boleh dinonaktifkan
        if (in_array($user['role'], self::MANDATORY_2FA_ROLES, true)) {
            Session::flash('error', '2FA wajib untuk role ' . ucfirst($user['role']) . ' sehingga tidak dapat dinonaktifkan.');
            redirect(APP_URL . '/admin/2fa/settings');
        }

        $password = $_POST['current_password'] ?? '';
        if ($password === '' || !password_verify($password, $user['password'])) {
            Session::flash('error', 'Password salah.');
            redirect(APP_URL . '/admin/2fa/settings');
        }

        $this->userModel->clearTwoFactor($user['id']);
        Session::flash('success', '2FA berhasil dinonaktifkan.');
        redirect(APP_URL . '/admin/2fa/settings');
    }

    // ---------- LOGOUT ----------
    public function logout(): void
    {
        Session::destroy();
        redirect(APP_URL . '/login');
    }

    // ---------- INTERNAL HELPERS ----------

    /**
     * Lanjutan setelah kredensial (email+password) valid.
     * - 2FA aktif → minta kode
     * - owner/admin tanpa 2FA → paksa setup (berlaku di portal klien maupun staff)
     * - selain itu → langsung login
     */
    private function continueAfterCredentials(array $user): never
    {
        if ($this->userModel->isTwoFactorEnabled($user)) {
            $this->beginTwoFactorChallenge($user);
        }

        if (in_array($user['role'], self::MANDATORY_2FA_ROLES, true)) {
            Session::set('2fa_setup_user_id', $user['id']);
            Session::set('2fa_setup_expires', time() + 300);
            redirect(APP_URL . '/admin/2fa/setup');
        }

        $this->completeLogin($user);
    }

    /**
     * Selesaikan login: set session user + redirect ke dashboard sesuai role.
     */
    private function completeLogin(array $user): never
    {
        session_regenerate_id(true);

        Session::set('user_id',         $user['id']);
        Session::set('user_name',       $user['name']);
        Session::set('user_role',       $user['role']);
        Session::set('user_email',      $user['email']);
        Session::set('user_department', $user['department']);
        Session::set('user_is_active',  $user['is_active']);

        // Bersihkan state autentikasi sementara
        foreach (['2fa_pending_user_id', '2fa_pending_expires',
                  '2fa_setup_user_id', '2fa_setup_expires',
                  '2fa_setup_secret', '2fa_setup_recovery'] as $key) {
            Session::remove($key);
        }

        redirect($this->roleDashboardUrl($user['role']));
    }

    /**
     * Mulai tantangan 2FA: simpan user sementara, redirect ke halaman kode.
     */
    private function beginTwoFactorChallenge(array $user): never
    {
        Session::set('2fa_pending_user_id', $user['id']);
        Session::set('2fa_pending_expires', time() + 300); // 5 menit
        redirect(APP_URL . '/admin/2fa');
    }

    /**
     * Ambil user id dari sesi tantangan 2FA (null bila tidak ada / kedaluwarsa).
     */
    private function pendingTwoFactorUser(): ?int
    {
        $userId  = Session::get('2fa_pending_user_id');
        $expires = Session::get('2fa_pending_expires');

        if (!$userId || !$expires || (int) $expires < time()) {
            Session::remove('2fa_pending_user_id');
            Session::remove('2fa_pending_expires');
            return null;
        }

        return (int) $userId;
    }

    /**
     * Tentukan user id untuk setup 2FA: dari alur login (pending) atau
     * staff yang sudah login dan ingin mengaktifkan secara sukarela.
     */
    private function resolveSetupUserId(): ?int
    {
        $userId  = Session::get('2fa_setup_user_id');
        $expires = Session::get('2fa_setup_expires');

        if ($userId && $expires && (int) $expires >= time()) {
            return (int) $userId;
        }

        Session::remove('2fa_setup_user_id');
        Session::remove('2fa_setup_expires');

        // Staff yang sudah login boleh mengaktifkan 2FA dari pengaturan
        if (Session::isLoggedIn() && in_array(Session::userRole(), self::STAFF_ROLES, true)) {
            return Session::userId();
        }

        return null;
    }

    /**
     * Redirect user yang sudah login ke dashboard sesuai role.
     */
    private function redirectToDashboard(): never
    {
        redirect($this->roleDashboardUrl(Session::userRole() ?? 'client'));
    }

    /**
     * URL dashboard sesuai role.
     */
    private function roleDashboardUrl(string $role): string
    {
        require_once ROOT_PATH . '/app/helpers/RolePermission.php';
        return RolePermission::getDashboardUrl($role);
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
