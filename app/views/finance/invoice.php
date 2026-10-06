<?php
$payment = $payment ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; color: #1a1a1a; background: #f5f5f5; padding: 2rem; }
        .invoice-box { max-width: 720px; margin: 0 auto; background: white; padding: 3rem; border-radius: 4px; }
        .invoice-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #122A1C; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
        .invoice-head .brand { font-size: 1.4rem; font-weight: 800; }
        .invoice-head .meta { text-align: right; font-size: 0.85rem; color: #666; }
        .invoice-head .meta strong { color: #1a1a1a; font-size: 1rem; display: block; margin-bottom: 0.2rem; }
        .status-tag { display: inline-block; padding: 0.3rem 0.9rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; margin-top: 0.5rem; }
        .status-paid { background: #e7f5e7; color: #006600; }
        .status-pending { background: #fff3e7; color: #cc6600; }
        .status-other { background: #f0f0f0; color: #666; }

        .parties { display: flex; justify-content: space-between; margin-bottom: 2rem; gap: 2rem; }
        .parties div { flex: 1; }
        .parties h3 { font-size: 0.75rem; text-transform: uppercase; color: #999; margin-bottom: 0.4rem; letter-spacing: 0.05em; }
        .parties p { font-size: 0.9rem; line-height: 1.5; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        table.items th { text-align: left; font-size: 0.8rem; text-transform: uppercase; color: #999; padding: 0.6rem 0; border-bottom: 2px solid #122A1C; }
        table.items td { padding: 0.9rem 0; border-bottom: 1px solid #eee; font-size: 0.9rem; }
        table.items .amount-col { text-align: right; }

        .total-row { display: flex; justify-content: flex-end; padding-top: 1rem; }
        .total-row .total-box { text-align: right; }
        .total-row .total-label { font-size: 0.85rem; color: #666; }
        .total-row .total-amount { font-size: 1.6rem; font-weight: 800; }

        .footnote { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #eee; font-size: 0.78rem; color: #999; line-height: 1.6; }

        .print-btn { display: block; max-width: 720px; margin: 0 auto 1rem; text-align: right; }
        .print-btn button { padding: 0.6rem 1.4rem; background: #122A1C; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }

        @media print {
            body { background: white; padding: 0; }
            .print-btn { display: none; }
            .invoice-box { box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="print-btn"><button onclick="window.print()">🖨 Cetak / Simpan sebagai PDF</button></div>

    <div class="invoice-box">
        <div class="invoice-head">
            <div class="brand">creative<span style="color:#C8412B;">.</span></div>
            <div class="meta">
                <strong><?= e($payment['invoice_number']) ?></strong>
                Order: <?= e($payment['order_code']) ?><br>
                Tanggal: <?= date('d M Y', strtotime($payment['created_at'])) ?>
                <div>
                    <?php $st = $payment['status']; ?>
                    <span class="status-tag <?= $st === 'paid' ? 'status-paid' : ($st === 'pending' ? 'status-pending' : 'status-other') ?>">
                        <?= strtoupper($st) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="parties">
            <div>
                <h3>Ditagihkan Kepada</h3>
                <p>
                    <strong><?= e($payment['client_name']) ?></strong><br>
                    <?= e($payment['client_email']) ?><br>
                    <?= e($payment['client_phone'] ?? '-') ?>
                </p>
            </div>
            <div>
                <h3>Rincian Order</h3>
                <p>
                    Kode: <?= e($payment['order_code']) ?><br>
                    Layanan: <?= e($payment['service_name']) ?><br>
                    Metode: <?= e($payment['payment_method'] ?? '—') ?>
                </p>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="amount-col">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?= e($payment['service_name']) ?><br>
                        <span style="color:#999;font-size:0.82rem;"><?= e(mb_strimwidth($payment['brief'] ?? '', 0, 120, '…')) ?></span>
                    </td>
                    <td class="amount-col"><?= formatRupiah((float) $payment['amount']) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="total-row">
            <div class="total-box">
                <div class="total-label">Total Dibayar</div>
                <div class="total-amount"><?= formatRupiah((float) $payment['amount']) ?></div>
            </div>
        </div>

        <div class="footnote">
            Invoice ini dibuat otomatis oleh sistem. Untuk pertanyaan terkait pembayaran, hubungi tim finance kami.
        </div>
    </div>

</body>
</html>
