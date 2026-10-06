<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {--ink:#0D0D0D;--paper:#F7F5F0;--cream:#EDEBE4;--accent:#C8412B;--muted:#7A7570;--white:#FFFFFF;--ff-head:'Syne',sans-serif;--ff-body:'Inter',sans-serif;}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body { font-family: var(--ff-body); background: var(--paper); color: var(--ink); }
a { color: inherit; text-decoration: none; }
.container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
.header { margin-bottom: 2rem; }
.header a { color: var(--muted); font-size: .9rem; transition: color .15s; }
.header a:hover { color: var(--ink); }
.layout { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }
.card { background: var(--white); border: 1px solid var(--cream); border-radius: 10px; padding: 2rem; }
.card h2 { font-family: var(--ff-head); font-weight: 800; font-size: 1.2rem; margin-bottom: 1.5rem; }
.info-row { display: flex; justify-content: space-between; padding: .8rem 0; border-bottom: 1px solid var(--cream); }
.info-row:last-child { border-bottom: none; }
.info-key { color: var(--muted); font-size: .9rem; }
.info-val { font-weight: 600; color: var(--ink); }
.methods { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0; }
.method { padding: 1.5rem; border: 1px solid var(--cream); border-radius: 10px; text-align: center; cursor: pointer; transition: all .2s; }
.method:hover { border-color: var(--accent); background: var(--paper); }
.method-icon { font-size: 2rem; margin-bottom: .5rem; }
.method-name { font-family: var(--ff-head); font-weight: 700; margin-bottom: .25rem; }
.method-desc { font-size: .8rem; color: var(--muted); }
.btn { display: inline-block; width: 100%; padding: 1rem; background: var(--accent); color: var(--white); border: none; border-radius: 10px; font-family: var(--ff-head); font-weight: 700; font-size: 1rem; cursor: pointer; transition: background .2s; margin-top: 1.5rem; }
.btn:hover { background: #A83422; }
.summary-box { position: sticky; top: 2rem; }
.summary-card { background: var(--white); border: 1px solid var(--cream); border-radius: 10px; padding: 1.5rem; box-shadow: 0 8px 32px rgba(0,0,0,.07); }
.summary-service { font-family: var(--ff-head); font-weight: 700; font-size: 1.1rem; margin-bottom: .5rem; }
.summary-total { display: flex; justify-content: space-between; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--cream); font-family: var(--ff-head); font-weight: 800; font-size: 1.2rem; }
.summary-total-val { color: var(--accent); }
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <a href="<?= APP_URL ?>/dashboard">← Kembali ke Dashboard</a>
  </div>

  <div class="layout">
    <!-- Main -->
    <div class="card">
      <h2>Pilih Metode Pembayaran</h2>
      <p style="color: var(--muted); margin-bottom: 1.5rem; font-size: .95rem;">
        Silakan pilih metode pembayaran yang Anda inginkan untuk menyelesaikan pesanan ini.
      </p>

      <form action="<?= APP_URL ?>/payment/process" method="POST">
        <?= csrfField() ?>
        <input type="hidden" name="order_id" value="<?= e($order['id']) ?>">

        <div class="methods">
          <div class="method" onclick="selectMethod('bank_transfer', this)">
            <div class="method-icon">🏦</div>
            <div class="method-name">Transfer Bank</div>
            <div class="method-desc">BCA, Mandiri, BNI, BRI</div>
          </div>
          <div class="method" onclick="selectMethod('qris', this)">
            <div class="method-icon">📱</div>
            <div class="method-name">QRIS</div>
            <div class="method-desc">Scan dengan aplikasi apapun</div>
          </div>
          <div class="method" onclick="selectMethod('e_wallet', this)">
            <div class="method-icon">💳</div>
            <div class="method-name">E-Wallet</div>
            <div class="method-desc">GoPay, OVO, Dana</div>
          </div>
          <div class="method" onclick="selectMethod('credit_card', this)">
            <div class="method-icon">💰</div>
            <div class="method-name">Kartu Kredit</div>
            <div class="method-desc">Visa, Mastercard, Amex</div>
          </div>
        </div>

        <button type="submit" class="btn">Lanjutkan ke Pembayaran →</button>
      </form>
    </div>

    <!-- Summary -->
    <div class="summary-box">
      <div class="summary-card">
        <div class="summary-service"><?= e($order['service_name']) ?></div>
        
        <div class="info-row">
          <span class="info-key">Kode Pesanan</span>
          <span class="info-val"><?= e($order['order_code']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-key">Nama</span>
          <span class="info-val"><?= e($order['name']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-key">Email</span>
          <span class="info-val" style="font-size: .85rem;"><?= e($order['email']) ?></span>
        </div>

        <div class="summary-total">
          <span>Total Pembayaran</span>
          <span class="summary-total-val"><?= formatRupiah((float)$order['price']) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function selectMethod(method, el) {
  document.querySelectorAll('.method').forEach(m => m.style.borderColor = 'var(--cream)');
  el.style.borderColor = 'var(--accent)';
}
</script>

</body>
</html>
