<?php
// ============================================================
//  app/helpers/MailHelper.php
//  Pengiriman email transaksional (verifikasi akun, reset password)
//
//  Default: PHP native mail() — tidak butuh dependency tambahan,
//  cocok untuk arsitektur no-framework proyek ini. Kalau nanti
//  butuh deliverability lebih baik (SMTP asli, DKIM, dsb), ganti
//  isi method send() di bawah untuk pakai PHPMailer/SMTP tanpa
//  mengubah pemanggil di AuthController.
// ============================================================

class MailHelper
{
    /**
     * Kirim email verifikasi akun setelah registrasi.
     */
    public static function sendVerification(string $toEmail, string $toName, string $token): bool
    {
        $link = APP_URL . '/verify-email?token=' . urlencode($token);

        $subject = 'Verifikasi Akun — ' . APP_NAME;
        $body = self::layout(
            'Verifikasi Email Anda',
            "Halo " . e($toName) . ",<br><br>" .
            "Terima kasih sudah mendaftar di " . e(APP_NAME) . ". Klik tombol di bawah untuk memverifikasi email Anda:",
            $link,
            'Verifikasi Email',
            'Link ini berlaku selama 24 jam. Jika Anda tidak merasa mendaftar, abaikan email ini.'
        );

        return self::send($toEmail, $toName, $subject, $body);
    }

    /**
     * Kirim email link reset password.
     */
    public static function sendReset(string $toEmail, string $toName, string $token): bool
    {
        $link = APP_URL . '/reset-password?token=' . urlencode($token);

        $subject = 'Reset Password — ' . APP_NAME;
        $body = self::layout(
            'Reset Password',
            "Halo " . e($toName) . ",<br><br>" .
            "Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru:",
            $link,
            'Reset Password',
            'Link ini berlaku selama 1 jam. Jika Anda tidak meminta reset password, abaikan email ini — password Anda tetap aman.'
        );

        return self::send($toEmail, $toName, $subject, $body);
    }

    /**
     * Kirim email mentah (HTML) via PHP native mail().
     * Ganti isi method ini jika ingin pindah ke PHPMailer/SMTP.
     */
    private static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $fromEmail = defined('MAIL_FROM') ? MAIL_FROM : 'no-reply@' . parse_url(APP_URL, PHP_URL_HOST);
        $fromName  = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : APP_NAME;

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::encodeHeader($fromName) . " <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";

        $encodedSubject = self::encodeHeader($subject);

        // Di local development, mail() sering tidak terkonfigurasi (tidak ada MTA).
        // Supaya alurnya tetap bisa diuji tanpa server mail, log ke file saat APP_DEBUG aktif.
        if (APP_DEBUG) {
            self::logToFile($toEmail, $subject, $htmlBody);
        }

        // Tetap panggil mail() agar di server produksi (dengan sendmail/MTA aktif) email benar-benar terkirim.
        return @mail($toEmail, $encodedSubject, $htmlBody, $headers);
    }

    private static function encodeHeader(string $text): string
    {
        return '=?UTF-8?B?' . base64_encode($text) . '?=';
    }

    /**
     * Simpan salinan email ke storage/mail-log saat mode development,
     * supaya bisa dicek isinya tanpa perlu mail server aktif.
     */
    private static function logToFile(string $to, string $subject, string $body): void
    {
        $dir = ROOT_PATH . '/storage/mail-log';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file = $dir . '/' . date('Y-m-d_His') . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $to) . '.html';
        @file_put_contents($file, "Subject: {$subject}\nTo: {$to}\n\n{$body}");
    }

    /**
     * Template HTML email sederhana, konsisten dengan warna brand di halaman auth.
     */
    private static function layout(string $heading, string $intro, string $ctaUrl, string $ctaLabel, string $footnote): string
    {
        $siteName = e(APP_NAME);
        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#F7F5F0;font-family:Arial,Helvetica,sans-serif;">
  <div style="max-width:480px;margin:0 auto;padding:32px 16px;">
    <div style="text-align:center;font-weight:800;font-size:20px;letter-spacing:-0.02em;margin-bottom:24px;">
      {$siteName}
    </div>
    <div style="background:#FFFFFF;border:1px solid #EDEBE4;border-radius:10px;padding:32px;">
      <h1 style="font-size:18px;margin:0 0 16px;">{$heading}</h1>
      <p style="font-size:14px;color:#0D0D0D;line-height:1.6;margin:0 0 24px;">{$intro}</p>
      <div style="text-align:center;margin:0 0 24px;">
        <a href="{$ctaUrl}" style="display:inline-block;background:#C8412B;color:#FFFFFF;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:700;font-size:14px;">{$ctaLabel}</a>
      </div>
      <p style="font-size:12.5px;color:#7A7570;line-height:1.6;margin:0;">{$footnote}</p>
      <p style="font-size:12px;color:#7A7570;word-break:break-all;margin:16px 0 0;">
        Atau salin link ini: {$ctaUrl}
      </p>
    </div>
  </div>
</body>
</html>
HTML;
    }
}
