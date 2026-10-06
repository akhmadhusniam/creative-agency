<?php
// ============================================================
//  app/helpers/PaymentGateway.php
//  Mendukung: Midtrans Snap API & Xendit Invoice API
// ============================================================

class PaymentGateway
{
    // ----------------------------------------------------------
    // MIDTRANS — membuat Snap token untuk pembayaran
    // ----------------------------------------------------------
    public static function midtransCreateToken(array $order, array $user): array
    {
        $env     = MIDTRANS_ENV === 'production'
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id'     => $order['order_code'],
                'gross_amount' => (int) $order['total'],
            ],
            'customer_details' => [
                'first_name' => $user['name'],
                'email'      => $user['email'],
                'phone'      => $user['phone'] ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $order['service_id'],
                    'price'    => (int) $order['subtotal'],
                    'quantity' => 1,
                    'name'     => $order['service_name'] ?? 'Jasa Desain',
                ],
            ],
            'callbacks' => [
                'finish'  => APP_URL . '/payment/finish',
                'error'   => APP_URL . '/payment/error',
                'pending' => APP_URL . '/payment/pending',
            ],
        ];

        $response = self::curlPost($env, $payload, [
            'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':'),
            'Content-Type: application/json',
        ]);

        return $response;
    }

    // ----------------------------------------------------------
    // MIDTRANS — verifikasi notifikasi webhook
    // ----------------------------------------------------------
    public static function midtransVerifyNotification(array $payload): bool
    {
        $signatureKey = hash('sha512',
            $payload['order_id']
            . $payload['status_code']
            . $payload['gross_amount']
            . MIDTRANS_SERVER_KEY
        );
        return hash_equals($signatureKey, $payload['signature_key'] ?? '');
    }

    // ----------------------------------------------------------
    // XENDIT — membuat invoice
    // ----------------------------------------------------------
    public static function xenditCreateInvoice(array $order, array $user): array
    {
        $payload = [
            'external_id'      => $order['order_code'],
            'amount'           => (int) $order['total'],
            'payer_email'      => $user['email'],
            'description'      => 'Pembayaran ' . ($order['service_name'] ?? 'Jasa Desain'),
            'invoice_duration' => 86400,  // 24 jam
            'success_redirect_url' => APP_URL . '/payment/finish',
            'failure_redirect_url' => APP_URL . '/payment/error',
        ];

        $response = self::curlPost(
            'https://api.xendit.co/v2/invoices',
            $payload,
            ['Authorization: Basic ' . base64_encode(XENDIT_SECRET_KEY . ':')]
        );

        return $response;
    }

    // ----------------------------------------------------------
    // Shared cURL POST helper
    // ----------------------------------------------------------
    private static function curlPost(string $url, array $payload, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => APP_ENV === 'production',
        ]);

        $body  = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($errno) {
            return ['error' => 'cURL error: ' . curl_strerror($errno)];
        }

        return json_decode($body, true) ?? ['error' => 'Invalid JSON response'];
    }
}
