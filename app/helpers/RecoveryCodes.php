<?php
// ============================================================
//  app/helpers/RecoveryCodes.php
//  Kode pemulihan sekali pakai untuk 2FA.
//  Disimpan ter-hash (bcrypt) dalam bentuk JSON array.
// ============================================================

class RecoveryCodes
{
    /**
     * Buat $count kode recovery dengan format XXXX-XXXX.
     *
     * @return string[]
     */
    public static function generate(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $raw     = strtoupper(bin2hex(random_bytes(4))); // 8 karakter hex
            $codes[] = substr($raw, 0, 4) . '-' . substr($raw, 4, 4);
        }
        return $codes;
    }

    /**
     * Hash sekumpulan kode menjadi JSON yang siap disimpan di DB.
     */
    public static function hash(array $codes): string
    {
        $hashed = [];
        foreach ($codes as $code) {
            $hashed[] = password_hash(self::normalize($code), PASSWORD_BCRYPT, ['cost' => 10]);
        }
        return json_encode($hashed, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Verifikasi kode recovery.
     *
     * @return array{json:string}|false  JSON sisa kode (kode yang dipakai dihapus) bila cocok,
     *                                   atau false bila tidak ada yang cocok.
     */
    public static function verify(string $code, ?string $storedJson): array|false
    {
        if (!$storedJson) {
            return false;
        }

        $hashed = json_decode($storedJson, true);
        if (!is_array($hashed) || empty($hashed)) {
            return false;
        }

        $needle = self::normalize($code);
        foreach ($hashed as $index => $hash) {
            if (password_verify($needle, $hash)) {
                unset($hashed[$index]);
                return ['json' => json_encode(array_values($hashed), JSON_UNESCAPED_SLASHES)];
            }
        }

        return false;
    }

    /**
     * Normalisasi input user: buang spasi/strip dan jadikan huruf besar.
     */
    private static function normalize(string $code): string
    {
        return strtoupper(str_replace(['-', ' '], '', trim($code)));
    }
}
