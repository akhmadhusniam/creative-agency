<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun — Creative Studio</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#0D0D0D;--paper:#F7F5F0;--cream:#EDEBE4;--accent:#C8412B;--muted:#7A7570;--white:#FFFFFF;--ff-head:'Syne',sans-serif;--ff-body:'Inter',sans-serif;}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--ff-body);background:var(--paper);color:var(--ink);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;}
.auth-wrap{width:100%;max-width:460px;}
.auth-logo{font-family:var(--ff-head);font-weight:800;font-size:1.5rem;letter-spacing:-.02em;margin-bottom:2rem;text-align:center;}
.auth-logo span{color:var(--accent);}
.auth-card{background:var(--white);border:1px solid var(--cream);border-radius:12px;padding:2.5rem;}
.auth-card h1{font-family:var(--ff-head);font-size:1.6rem;font-weight:800;margin-bottom:.3rem;}
.auth-card .sub{color:var(--muted);font-size:.875rem;margin-bottom:2rem;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.form-group{margin-bottom:1.1rem;}
label{display:block;font-size:.8rem;font-weight:500;margin-bottom:.4rem;}
input{width:100%;padding:.75rem 1rem;border:1.5px solid var(--cream);border-radius:6px;font-family:var(--ff-body);font-size:.875rem;background:var(--white);color:var(--ink);transition:border-color .15s;}
input:focus{outline:none;border-color:var(--ink);}
input.error{border-color:#f87171;}
.field-error{color:#b91c1c;font-size:.72rem;margin-top:.25rem;}
.btn{display:block;width:100%;padding:.85rem;border:none;border-radius:6px;font-family:var(--ff-head);font-weight:700;font-size:.9rem;cursor:pointer;letter-spacing:.04em;margin-top:.5rem;transition:background .15s;}
.btn-primary{background:var(--accent);color:var(--white);}
.btn-primary:hover{background:#A83422;}
.auth-footer{text-align:center;margin-top:1.5rem;font-size:.85rem;color:var(--muted);}
.auth-footer a{color:var(--ink);font-weight:500;text-decoration:underline;}
.alert{padding:.8rem 1rem;border-radius:6px;font-size:.875rem;margin-bottom:1.25rem;}
.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;}
.password-hints{font-size:.72rem;color:var(--muted);margin-top:.3rem;}
@media(max-width:480px){.form-row{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="auth-wrap">
  <a href="<?= APP_URL ?>/" class="auth-logo">creative<span>.</span></a>

  <div class="auth-card">
    <h1>Buat Akun Baru</h1>
    <p class="sub">Daftar gratis dan mulai proyek desain Anda</p>

    <?php if ($ok = Session::getFlash('success')): ?>
      <div class="alert alert-success"><?= e($ok) ?></div>
    <?php endif; ?>

    <?php $errors = Session::getFlash('errors', []); $old = Session::getFlash('old', []); ?>

    <form method="POST" action="<?= APP_URL ?>/register">
      <?= csrfField() ?>
      <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name"
               value="<?= e($old['name'] ?? '') ?>"
               class="<?= isset($errors['name']) ? 'error' : '' ?>"
               placeholder="Nama Anda" required>
        <?php if (!empty($errors['name'])): ?>
          <div class="field-error"><?= e($errors['name'][0]) ?></div>
        <?php endif; ?>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email"
                 value="<?= e($old['email'] ?? '') ?>"
                 class="<?= isset($errors['email']) ? 'error' : '' ?>"
                 placeholder="nama@email.com" required>
          <?php if (!empty($errors['email'])): ?>
            <div class="field-error"><?= e($errors['email'][0]) ?></div>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="phone">No. HP <span style="color:var(--muted)">(opsional)</span></label>
          <input type="tel" id="phone" name="phone"
                 value="<?= e($old['phone'] ?? '') ?>"
                 placeholder="+62 812 ...">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password"
                 class="<?= isset($errors['password']) ? 'error' : '' ?>"
                 placeholder="Min. 8 karakter" required>
          <?php if (!empty($errors['password'])): ?>
            <div class="field-error"><?= e($errors['password'][0]) ?></div>
          <?php else: ?>
            <div class="password-hints">Minimal 8 karakter</div>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label for="password_confirm">Konfirmasi Password</label>
          <input type="password" id="password_confirm" name="password_confirm"
                 class="<?= isset($errors['password_confirm']) ? 'error' : '' ?>"
                 placeholder="Ulangi password" required>
          <?php if (!empty($errors['password_confirm'])): ?>
            <div class="field-error"><?= e($errors['password_confirm'][0]) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Buat Akun</button>
    </form>

    <p class="auth-footer">
      Sudah punya akun? <a href="<?= APP_URL ?>/login">Masuk di sini</a>
    </p>
  </div>
</div>
</body>
</html>
