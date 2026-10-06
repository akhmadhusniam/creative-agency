<?php
// ============================================================
//  app/helpers/Validator.php
// ============================================================

class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(array $fields): static
    {
        foreach ($fields as $f) {
            if (empty(trim($this->data[$f] ?? ''))) {
                $label = ucwords(str_replace('_', ' ', $f));
                $this->errors[$f][] = "$label wajib diisi.";
            }
        }
        return $this;
    }

    public function email(string $field): static
    {
        $val = $this->data[$field] ?? '';
        if ($val && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Format email tidak valid.';
        }
        return $this;
    }

    public function minLength(string $field, int $min): static
    {
        $val = $this->data[$field] ?? '';
        if ($val && mb_strlen($val) < $min) {
            $label = ucwords(str_replace('_', ' ', $field));
            $this->errors[$field][] = "$label minimal $min karakter.";
        }
        return $this;
    }

    public function match(string $field, string $fieldConfirm, string $msg = ''): static
    {
        if (($this->data[$field] ?? '') !== ($this->data[$fieldConfirm] ?? '')) {
            $this->errors[$field][] = $msg ?: 'Nilai tidak cocok.';
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

// ============================================================
//  Global helper functions
// ============================================================

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function formatRupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function generateOrderCode(): string
{
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrfToken(), $token)) {
        http_response_code(419);
        die('CSRF token mismatch.');
    }
}

function uploadFile(array $file, string $dir, array $allowed): string|false
{
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;
    if ($file['size'] > MAX_UPLOAD_MB * 1024 * 1024) return false;

    $dest = UPLOAD_PATH . '/' . $dir;
    if (!is_dir($dest)) mkdir($dest, 0755, true);

    $filename = uniqid('', true) . '.' . $ext;
    move_uploaded_file($file['tmp_name'], $dest . '/' . $filename);
    return $dir . '/' . $filename;
}
