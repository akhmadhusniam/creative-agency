<?php
// ============================================================
//  app/helpers/Totp.php
//  Implementasi TOTP (RFC 6238) murni PHP — tanpa Composer.
//  Kompatibel dengan Google Authenticator, Authy, Microsoft
//  Authenticator, dsb.
// ============================================================

class Totp
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    private const TIME_STEP       = 30; // detik

    /**
     * Buat secret acak dalam format Base32 (default 20 byte = 160 bit).
     */
    public static function generateSecret(int $bytes = 20): string
    {
        return self::base32Encode(random_bytes($bytes));
    }

    /**
     * Hitung kode TOTP untuk time-slice tertentu.
     */
    public static function code(string $secret, ?int $timeSlice = null): string
    {
        $timeSlice ??= (int) floor(time() / self::TIME_STEP);
        $key  = self::base32Decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice); // 8-byte big-endian counter
        $hash = hash_hmac('sha1', $time, $key, true);

        $offset = ord($hash[19]) & 0x0F;
        $value  = ((ord($hash[$offset])     & 0x7F) << 24)
                | ((ord($hash[$offset + 1]) & 0xFF) << 16)
                | ((ord($hash[$offset + 2]) & 0xFF) << 8)
                |  (ord($hash[$offset + 3]) & 0xFF);

        return str_pad((string) ($value % 1000000), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verifikasi kode TOTP. Memberi toleransi ±$window langkah (30 detik).
     */
    public static function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = preg_replace('/\s+/', '', $code);
        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $now = (int) floor(time() / self::TIME_STEP);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals(self::code($secret, $now + $i), $code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * URI otpauth:// untuk QR code / input manual di aplikasi authenticator.
     */
    public static function provisioningUri(
        string $issuer,
        string $account,
        string $secret,
        int $digits = 6,
        int $period = self::TIME_STEP
    ): string {
        $label  = rawurlencode($issuer . ':' . $account);
        $params = http_build_query([
            'secret'    => $secret,
            'issuer'    => $issuer,
            'algorithm' => 'SHA1',
            'digits'    => $digits,
            'period'    => $period,
        ]);

        return 'otpauth://totp/' . $label . '?' . $params;
    }

    // ---------- Base32 ----------

    public static function base32Encode(string $data): string
    {
        if ($data === '') {
            return '';
        }

        $binary = '';
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }

        $encoded = '';
        foreach (str_split($binary, 5) as $chunk) {
            $chunk    = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $encoded .= self::BASE32_ALPHABET[bindec($chunk)];
        }

        return $encoded;
    }

    public static function base32Decode(string $b32): string
    {
        $b32 = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $b32));
        if ($b32 === '') {
            return '';
        }

        $binary = '';
        for ($i = 0, $len = strlen($b32); $i < $len; $i++) {
            $pos = strpos(self::BASE32_ALPHABET, $b32[$i]);
            if ($pos === false) {
                continue;
            }
            $binary .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $data = '';
        foreach (str_split($binary, 8) as $chunk) {
            if (strlen($chunk) < 8) {
                break;
            }
            $data .= chr(bindec($chunk));
        }

        return $data;
    }
}
