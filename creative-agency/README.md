# Creative Studio — Website Jasa Desain Profesional

Aplikasi web jasa desain profesional berbasis PHP + MySQL dengan sistem order, autentikasi klien, dan integrasi payment gateway.

---

## Stack Teknologi

| Layer       | Teknologi                          |
|-------------|-------------------------------------|
| Backend     | PHP 8.1+ (no framework, MVC manual) |
| Database    | MySQL 8.x                           |
| Frontend    | HTML5, CSS3, Vanilla JS              |
| Payment     | Midtrans Snap + Xendit Invoice       |
| Web Server  | Apache 2.4 + mod_rewrite             |

---

## Struktur Direktori

```
creative-agency/
├── app/
│   ├── controllers/        # AuthController, PageController, ClientController, ...
│   ├── models/             # User, Order, Service, ...
│   ├── views/              # Template PHP (auth/, client/, admin/, partials/)
│   └── helpers/            # Session, Validator, PaymentGateway, ...
├── config/
│   ├── app.php             # Konstanta & konfigurasi global
│   └── database.php        # PDO singleton
├── database/
│   └── schema.sql          # DDL + seed data
├── public/                 # Document root (satu-satunya folder publik)
│   ├── index.php           # Front controller & router
│   ├── .htaccess
│   ├── css/
│   └── js/
└── uploads/                # File upload (di luar public/)
```

---

## Cara Setup

### 1. Clone & Konfigurasi

```bash
git clone <repo-url> creative-agency
cd creative-agency
```

Salin `.env.example` ke `.env` lalu isi:

```env
APP_ENV=development
APP_URL=http://localhost/creative-agency/public

DB_HOST=localhost
DB_PORT=3306
DB_NAME=creative_agency
DB_USER=root
DB_PASS=

MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_ENV=sandbox

XENDIT_SECRET_KEY=xnd_development_xxxx
```

### 2. Import Database

```bash
mysql -u root -p < database/schema.sql
```

### 3. Set Document Root Apache

```apache
DocumentRoot /var/www/html/creative-agency/public
```

Atau gunakan Virtual Host:

```apache
<VirtualHost *:80>
    ServerName creative.local
    DocumentRoot /var/www/html/creative-agency/public
    <Directory /var/www/html/creative-agency/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 4. Permission Upload

```bash
chmod -R 755 uploads/
```

---

## Akun Default

| Role  | Email                    | Password    |
|-------|--------------------------|-------------|
| Admin | admin@creativeagency.id  | Admin@1234  |

> **Ganti password admin setelah pertama login!**

---

## Fitur yang Sudah Ada

- [x] Landing page dengan hero, layanan, portfolio, testimonial, CTA
- [x] Registrasi & login klien
- [x] Session management + CSRF protection
- [x] Validasi input server-side
- [x] Dashboard klien
- [x] Skema database lengkap (users, services, orders, payments, portfolio, testimonials)
- [x] Helper PaymentGateway (Midtrans Snap + Xendit Invoice)
- [x] Router sederhana tanpa framework
- [x] Upload file helper
- [x] Format Rupiah & generate order code

## Roadmap Selanjutnya

- [ ] Halaman detail layanan + form order
- [ ] Proses checkout & integrasi pembayaran live
- [ ] Webhook handler Midtrans/Xendit
- [ ] Panel admin (CRUD layanan, kelola pesanan, upload portfolio)
- [ ] Email notifikasi (PHPMailer)
- [ ] Reset password via email
- [ ] Halaman portfolio dengan filter kategori
- [ ] Halaman profil klien dengan upload avatar

---

## Keamanan

- Password di-hash menggunakan `PASSWORD_BCRYPT` (cost 12)
- CSRF token pada setiap form POST
- Session regenerate setelah login
- Cookie `httponly` + `samesite=Lax`
- File sensitif diblokir via `.htaccess`
- Folder di luar `public/` tidak bisa diakses langsung
- Prepared statements PDO pada semua query

---

## Payment Gateway

### Midtrans

1. Daftar di [dashboard.midtrans.com](https://dashboard.midtrans.com)
2. Ambil **Server Key** & **Client Key** (mode Sandbox dulu)
3. Set di `.env`
4. Untuk production: ganti `MIDTRANS_ENV=production`

### Xendit

1. Daftar di [dashboard.xendit.co](https://dashboard.xendit.co)
2. Ambil **Secret Key**
3. Set `XENDIT_SECRET_KEY` di `.env`

---

## Lisensi

MIT — bebas digunakan untuk keperluan komersial.
