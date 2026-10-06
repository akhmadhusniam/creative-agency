# Phase 1 & 2 RBAC Implementation Guide

## 🎯 Ringkasan Phase 1 & 2

Implementasi **Role-Based Access Control (RBAC)** untuk Creative Agency telah selesai. Sistem mendukung 6 role dengan akses berjenjang dan dashboard yang disesuaikan untuk setiap role.

---

## 📋 Role yang Tersedia

### Phase 1: Basic Roles
1. **Client** - Pengguna regular yang membeli layanan
2. **Admin** - Mengelola operasional, staff, layanan
3. **Owner** - Pemilik bisnis, akses penuh

### Phase 2: Extended Roles  
4. **Designer/Staff** - Menjalankan pekerjaan creative
5. **Project Manager** - Mengorganisir tim, assign order, approve hasil
6. **Finance** - Mengelola pembayaran dan financials *(Phase 3 - ready for future)*

---

## 🔧 File yang Dimodifikasi/Dibuat

### Database
- `database/migrations.sql` - Schema migration untuk Phase 1 & 2
  - Tambah kolom ke `users` table
  - Tambah kolom ke `orders` table  
  - Tambah kolom ke `payments` table
  - Buat `audit_logs` table
  - Buat `notifications` table

### Helpers
- `app/helpers/Session.php` - Updated dengan method `user()`, `requireRole()`, `requirePermission()`
- `app/helpers/RolePermission.php` - New! Permission matrix dan role hierarchy

### Models
- `app/models/User.php` - Updated dengan role management methods

### Controllers
- `app/controllers/AuthController.php` - Updated login untuk store role di session
- `app/controllers/AdminController.php` - Completely refactored dengan user/staff management
- `app/controllers/DesignerController.php` - New! Dashboard untuk Designer
- `app/controllers/ManagerController.php` - New! Dashboard untuk Project Manager

### Views
- `app/views/admin/users.php` - New! Manage staff
- `app/views/admin/user-edit.php` - New! Edit user role
- `app/views/designer/dashboard.php` - New! Designer workspace
- `app/views/manager/dashboard.php` - New! Manager overview
- `app/views/manager/order-edit.php` - New! Order assignment UI

### Router
- `public/index.php` - Added 12 new routes untuk designer dan manager

---

## 🚀 Installation Steps

### Step 1: Jalankan Migration Script

1. Buka MySQL/phpMyAdmin
2. Pilih database `creative_agency`
3. Copy-paste isi file `database/migrations.sql` ke SQL editor
4. Execute script

**Hasil:** 
- ✅ Users table diperluas dengan 5 kolom baru
- ✅ Orders table diperluas dengan 6 kolom baru
- ✅ Payments table diperluas dengan 4 kolom baru
- ✅ Audit logs & notifications table tercipta
- ✅ Test staff users dibuat (Rizki Designer, Sinta Manager, Budi Finance)
- ✅ Admin user diubah menjadi "owner"

### Step 2: Test Login dengan Different Roles

**Admin/Owner:**
```
Email: admin@creativeagency.id
Password: Admin@1234
→ Redirect ke: /admin (Owner/Admin dashboard)
```

**Designer:**
```
Email: rizki@creativeagency.id
Password: Admin@1234
→ Redirect ke: /designer/dashboard
```

**Project Manager:**
```
Email: sinta@creativeagency.id
Password: Admin@1234
→ Redirect ke: /manager/dashboard
```

**Finance:**
```
Email: budi@creativeagency.id
Password: Admin@1234
→ Redirect ke: /finance/dashboard (Phase 3)
```

**Regular Client:**
```
Email: client@example.com (dari database)
Password: (sesuai di database)
→ Redirect ke: /dashboard (Client dashboard existing)
```

---

## 📱 Dashboard Features per Role

### 1. **Client Dashboard** (`/dashboard`)
- Lihat order history mereka
- Track status pesanan
- Upload brief & file
- Edit profile
- Lihat payment status

### 2. **Designer Dashboard** (`/designer/dashboard`)
- **Stats:** Total orders, in progress, urgent, overdue
- **My Orders:** Tabel pesanan yang di-assign
- **Status Update:** Change order status (confirmed → in_progress → completed)
- **Upload Assets:** Submit design files untuk setiap order
- **Pending Review:** List order yang menunggu approval

**Akses:** Designer hanya bisa melihat order yang di-assign ke mereka

### 3. **Project Manager Dashboard** (`/manager/dashboard`)
- **Statistics:** Total/pending/in progress/completed orders
- **Danger Alert:** Tampilkan pesanan yang overdue
- **Designer Workload:** Table dengan workload per designer
  - Identifikasi designer yang overloaded
  - Suggest rebalancing assignments
- **Recent Orders:** Latest 10 orders dengan detail designer & status

**Manager Features:**
- Lihat semua orders (managed view)
- Assign/reassign order ke designer
- Set priority & deadline
- Approve order completion atau request revision
- View team workload & analytics
- Generate performance reports

### 4. **Admin/Owner Dashboard** (`/admin/dashboard`)
- **Full Statistics:** Total orders, pending, confirmed, paid, completed
- **Staff Stats:** Breakdown by role
- **Recent Orders:** Full detail dengan designer assignment
- **Management Access:** Manage users, services, portfolio, orders

**Admin Features:**
- Manage staff (add, edit, deactivate)
- Change user roles & departments
- View audit logs (Phase 3)
- System settings
- Full order & payment management

---

## 🔐 Permission Matrix

### RolePermission Helper

File: `app/helpers/RolePermission.php`

**Usage dalam Controller:**
```php
// Check permission
if (!RolePermission::can('view_own_orders')) {
    // Akses ditolak
}

// Check order access
if (!RolePermission::canAccessOrder($orderId)) {
    http_response_code(403);
    die('Akses ditolak');
}

// Get user role
$userRole = Session::userRole();

// Require role
Session::requireRole('designer');
Session::requireRole(['manager', 'admin']); // Multiple roles
```

**Permission Examples:**
```php
// Client dapat:
- view_own_orders, create_order, view_services, edit_profile

// Designer dapat:
- view_assigned_orders, edit_assigned_orders, upload_assets

// Manager dapat:
- view_all_orders, assign_orders, update_order_status, approve_completion

// Finance dapat:
- view_all_payments, approve_payments, generate_invoices

// Admin dapat:
- manage_users, manage_services, manage_orders, manage_payments

// Owner:
- Full access to everything (*)
```

---

## 🔄 Workflow: Order Assignment Process

```
1. CLIENT creates order
   Order created di database dengan status "pending"
   
2. MANAGER reviews order
   Login → /manager/dashboard
   Klik "Edit" pada order
   
3. MANAGER assigns designer + sets priority/deadline
   Form: Select designer, priority (low/medium/high/urgent), deadline
   Click "Simpan Perubahan"
   
4. DESIGNER sees assigned order
   Login → /designer/dashboard
   Order sudah visible di "Pesanan Saya"
   
5. DESIGNER updates status
   Klik order → View detail
   Update status: confirmed → in_progress → completed
   
6. DESIGNER can upload assets
   POST /designer/orders/{id}/upload
   Upload design files & deliverables
   
7. MANAGER approves or requests revision
   Review designer's work
   Approve (status = completed) atau Revise (status = revision)
   
8. ORDER completed
   Final status = "completed"
   Payment can be marked as "paid"
```

---

## 🎛️ Admin User Management

### Manage Users/Staff

**URL:** `/admin/users`

**Features:**
- List semua staff dengan role, department, status
- Edit button untuk ubah role/department
- Toggle aktivasi user (suspend/activate)
- Color-coded badges per role

### Edit User

**URL:** `/admin/users/{id}/edit`

**Form:**
- Role selector (dropdown)
- Department field (text)
- Show email dan registration date (read-only)

**After saving:**
- Redirect ke `/admin/users` - list updated
- User akan diarahkan ke dashboard yang sesuai dengan role baru mereka saat login ulang

---

## 🔐 Session & Authentication Flow

### Session Data Structure

Setiap user yang login akan store di session:
```php
$_SESSION['user_id']         // User ID
$_SESSION['user_name']       // Display name
$_SESSION['user_email']      // Email address
$_SESSION['user_role']       // Role (client, designer, manager, etc)
$_SESSION['user_department'] // Department (jika staff)
$_SESSION['user_is_active']  // Status aktivasi
```

### Session Helper Methods

```php
// Check login
Session::isLoggedIn() → bool

// Get user role
Session::userRole() → string

// Get full user data
Session::user() → array{id, name, email, role, department, is_active}

// Require login
Session::requireLogin()

// Require specific role(s)
Session::requireRole('designer')
Session::requireRole(['manager', 'admin'])

// Require permission
Session::requirePermission('view_all_orders')

// Is admin/owner
Session::isAdmin() → bool (checks for 'admin' or 'owner')
```

---

## 📊 Database Schema Changes

### Users Table
```sql
ALTER TABLE users
ADD COLUMN role ENUM('client','designer','manager','finance','admin','owner') 
ADD COLUMN department VARCHAR(50)
ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1
ADD COLUMN hire_date DATE
ADD COLUMN salary DECIMAL(12,2)
```

### Orders Table
```sql
ALTER TABLE orders
ADD COLUMN assigned_to INT (FK to users)
ADD COLUMN manager_id INT (FK to users)
ADD COLUMN priority ENUM('low','medium','high','urgent')
ADD COLUMN deadline_date DATE
ADD COLUMN revision_count TINYINT
ADD COLUMN max_revisions TINYINT DEFAULT 2
```

### Payments Table
```sql
ALTER TABLE payments
ADD COLUMN invoice_number VARCHAR(50)
ADD COLUMN paid_date DATETIME
ADD COLUMN approver_id INT (FK to users)
ADD COLUMN notes TEXT
```

### New Tables
- `audit_logs` - Track all changes (user, entity type, action, old/new values)
- `notifications` - Real-time alerts per user

---

## 🎨 UI/UX Improvements

### Dashboard Customization
✅ Each role has dedicated dashboard with relevant metrics
✅ Color-coded status & priority badges
✅ Quick-action buttons for common tasks
✅ Role-based navigation (different menu for each role)

### Error Handling
✅ 403 Forbidden jika user mencoba akses resource yang tidak diperbolehkan
✅ Flash messages untuk successes & errors
✅ Form validation dengan error display

### Responsive Design
✅ Mobile-friendly dashboards
✅ Proper form layouts
✅ Table responsive design

---

## ⚠️ Important Notes

1. **Password:** Semua test users menggunakan password `Admin@1234`
   - **CHANGE setelah deploy ke production!**

2. **Email Integration:** Phase 2 tidak include email notifications
   - Bisa ditambah di Phase 3 dengan notification table

3. **Audit Logs:** Table sudah ada tapi logging belum fully implemented
   - Helper function `logAudit()` bisa ditambah di future

4. **Financial Reports:** Phase 3 akan include comprehensive finance analytics

5. **Security Considerations:**
   - All queries use prepared statements (SQL injection prevention)
   - Session regeneration after login (CSRF prevention)
   - CSRF token on all forms
   - Password hashing dengan bcrypt
   - Role-based authorization on every route

---

## 🚦 Next Steps (Phase 3)

1. **Finance Dashboard** (`/finance/dashboard`)
   - Payment approval workflow
   - Invoice generation
   - Revenue reports

2. **Notifications System**
   - Real-time alerts untuk role-specific events
   - Email notifications (optional)
   - In-app notification center

3. **Audit Logging**
   - Implement `logAudit()` function di semua CRUD operations
   - Dashboard untuk view audit logs per entity

4. **Advanced Analytics**
   - Designer performance metrics
   - Order completion rates
   - Revenue tracking by service/client

5. **API Integration**
   - REST API untuk mobile apps
   - Webhook integrations

---

## 🆘 Troubleshooting

### User dapat login tapi diredirect ke page 404
- ✅ Check migration script sudah di-execute di database
- ✅ Check routes di `public/index.php` sudah ditambah
- ✅ Check controller file ada dan class name sesuai

### Dropdown designer kosong di order update
- ✅ Make sure lebih dari 1 user dengan role "designer" di database
- ✅ Check `RolePermission::getUsersByRole('designer')` returns data

### Session data tidak ter-save
- ✅ Check `Session::start()` dipanggil di constructor
- ✅ Check `Session::set()` dipanggil dalam login method

### Role tidak berubah setelah edit user
- ✅ Make sure user login ulang untuk session ter-update
- ✅ Check database sudah ter-update dengan query: `SELECT role FROM users WHERE id = X`

---

## 📞 Support

Untuk bantuan lebih lanjut atau modifikasi:
1. Review `RolePermission.php` untuk permission logic
2. Check `Session.php` untuk authentication helpers
3. Review controller files untuk implementation patterns
4. Check database schema dengan `DESCRIBE users / orders / payments`

**Last Updated:** August 26, 2026
**Version:** Phase 1 & 2 - Complete
