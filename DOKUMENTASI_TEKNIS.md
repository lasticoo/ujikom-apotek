# 📚 DOKUMENTASI TEKNIS - UJIKOM APOTEK APP

**Dibuat:** 2026-06-23  
**Status:** Complete Technical Documentation  
**Versi:** 1.0

---

## 📋 DAFTAR ISI

1. [Overview Project](#1-overview-project)
2. [Technology Stack](#2-technology-stack)
3. [Architecture & Design Pattern](#3-architecture--design-pattern)
4. [Database Schema & Relationships](#4-database-schema--relationships)
5. [Authentication & Authorization](#5-authentication--authorization)
6. [Role-Based Access Control (RBAC)](#6-role-based-access-control-rbac)
7. [Fitur & Flow Diagram](#7-fitur--flow-diagram)
8. [Detail Mekanik Per Fitur](#8-detail-mekanik-per-fitur)
9. [Struktur Code & File Organization](#9-struktur-code--file-organization)
10. [Implementasi Technical per Fitur](#10-implementasi-technical-per-fitur)

---

## 1. OVERVIEW PROJECT

### 📌 Definisi Singkat
Ujikom Apotek App adalah sistem informasi manajemen apotek berbasis web yang mengelola:
- **Inventory obat-obatan** dengan tracking stok dan tanggal kadaluarsa
- **Transaksi penjualan** (retail & online ordering)
- **Transaksi pembelian** (restocking dari supplier)
- **Manajemen pelanggan** dengan sistem akun terpisah
- **Laporan & analitik** untuk admin dan apoteker

### 🎯 Tujuan Sistem
1. Mengelola stok obat secara real-time
2. Memproses transaksi penjualan dengan akurat
3. Mengelola restocking dari supplier
4. Menyediakan platform online ordering untuk pelanggan
5. Menghasilkan laporan penjualan dan pembelian
6. Melacak obat kadaluarsa dan mengelola status

### 👥 Pengguna Sistem
| Role | Deskripsi |
|------|-----------|
| **Admin** | Mengelola obat, supplier, apoteker, laporan (full control) |
| **Apoteker** | Input penjualan & pembelian, kelola inventory & pesanan online |
| **Pelanggan** | Browse obat, online ordering, upload bukti pembayaran |

---

## 2. TECHNOLOGY STACK

### 🔧 Backend
| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **PHP** | ^8.2 | Server-side language |
| **Laravel** | ^12.0 | Web framework - MVC architecture |
| **MySQL/SQLite** | Latest | Database |
| **Composer** | Latest | PHP package manager |

### 🎨 Frontend
| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **Tailwind CSS** | ^3.1.0 | Utility-first CSS framework |
| **Alpine.js** | ^3.4.2 | Reactive frontend library |
| **Vite** | ^6.0.11 | Modern build tool |
| **Axios** | ^1.7.4 | HTTP client |

### 📚 Libraries & Tools
| Library | Fungsi |
|---------|--------|
| **Laravel Breeze** | Authentication scaffolding |
| **DOMPDF** | Generate PDF reports |
| **Laravel Tinker** | Interactive shell |
| **Pest/PHPUnit** | Testing framework |
| **FakerPHP** | Generate fake data untuk testing |

### 🔄 Build & Development
```
npm scripts:
  - "dev": vite (development server)
  - "build": vite build (production build)

laravel artisan commands:
  - migrate: menjalankan database migrations
  - seed: populate database dengan sample data
  - tinker: interactive shell
  - serve: run development server
```

---

## 3. ARCHITECTURE & DESIGN PATTERN

### 🏗️ MVC Architecture

```
REQUEST → ROUTE → CONTROLLER → MODEL/DB → VIEW → RESPONSE

Controller: Business logic layer
Model: Data access & relationships
View: Presentation layer (Blade templates)
```

### 📂 Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── ObatController.php
│   │   │   ├── SuplierController.php
│   │   │   ├── PelangganController.php
│   │   │   ├── ApotekerController.php
│   │   │   ├── PenjualanController.php
│   │   │   └── PembelianController.php
│   │   ├── Apoteker/           # Apoteker controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── ObatController.php
│   │   │   ├── PenjualanController.php
│   │   │   └── PembelianController.php
│   │   ├── Pelanggan/          # Customer controllers
│   │   │   ├── ObatController.php
│   │   │   ├── CartController.php
│   │   │   └── PenjualanController.php
│   │   ├── Auth/               # Auth controllers (Breeze)
│   │   └── ProfileController.php
│   ├── Middleware/
│   │   ├── EnsureUserRole.php  # Role validation
│   │   └── EnsurePelangganGuard.php
│   └── Requests/               # Form request validation
├── Models/
│   ├── User.php                # Admin/Apoteker
│   ├── AkunPelanggan.php       # Customer auth
│   ├── Obat.php
│   ├── KategoriObat.php
│   ├── Suplier.php
│   ├── Pelanggan.php           # Customer profile
│   ├── Penjualan.php
│   ├── PenjualanDetail.php
│   ├── Pembelian.php
│   └── PembelianDetail.php
├── View/
│   └── Components/             # Reusable components
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/                 # Database schema
├── factories/                  # Model factories
└── seeders/                    # Database seeding

resources/
├── views/
│   ├── admin/                  # Admin views
│   ├── apoteker/               # Apoteker views
│   ├── pelanggan/              # Customer views
│   ├── auth/                   # Auth views
│   ├── layouts/                # Base layouts
│   └── components/             # Shared components
├── css/                        # Stylesheets
└── js/                         # JavaScript files

routes/
├── web.php                     # Main routes
├── auth.php                    # Auth routes
└── console.php                 # Console commands
```

### 🔐 Design Patterns Used
1. **MVC Pattern** - Separation of concerns
2. **Repository Pattern** - Models handle data access
3. **Middleware Pattern** - Request/Response filtering
4. **Factory Pattern** - Model factories untuk testing
5. **Guard Pattern** - Multiple authentication guards (web & pelanggan)
6. **Observer Pattern** - Events & listeners (implicit dalam Laravel)

---

## 4. DATABASE SCHEMA & RELATIONSHIPS

### 📊 Entity Relationship Diagram (ERD)

```
┌─────────────────┐
│     users       │ (Admin/Apoteker)
├─────────────────┤
│ id (PK)         │
│ nama            │
│ email           │
│ password        │
│ role            │ → 'admin' | 'apoteker'
│ telpon          │
└────────┬────────┘
         │
    ┌────┼────┐
    │         │
    ↓         ↓
┌─────────────────────────┐   ┌─────────────────────────┐
│    penjualans (FK)      │   │   pembelians (FK)       │
├─────────────────────────┤   ├─────────────────────────┤
│ nota (PK)               │   │ nota (PK)               │
│ tgl_nota                │   │ tgl_nota                │
│ kd_pelanggan (FK)  ─┐   │   │ kd_suplier (FK)    ─┐   │
│ id_user (FK)       │   │   │ id_user (FK)       │   │
│ diskon              │   │   │ diskon              │   │
│ status_pembayaran   │   │   └─────────────────────────┘
│ bukti_pembayaran    │   │           │
└────────┬────────────┘   │           │ HasMany
         │                │           │
         │ HasMany        │           ↓
         │                │   ┌─────────────────┐
    ┌────▼─────────────────┼─→│   supliers      │
    │                      │   ├─────────────────┤
    ↓                      │   │ kd_suplier (PK) │
┌──────────────────────┐   │   │ nm_suplier      │
│ penjualan_details    │   │   │ alamat          │
├──────────────────────┤   │   │ telpon          │
│ id (PK)              │   │   │ email           │
│ nota (FK)  ◄─────────┘   │   └─────────────────┘
│ kd_obat (FK) ──┐         │
│ jumlah         │         │
└────────────────┼─────────┘
                 │
         ┌───────▼────────────┐
         │ obats              │
         ├────────────────────┤
         │ kd_obat (PK)       │
         │ nm_obat            │
         │ id_kategori (FK)   │
         │ satuan             │
         │ harga_beli         │
         │ harga_jual         │
         │ stok               │
         │ tgl_kadaluarsa     │
         │ status             │
         │ kd_suplier (FK)    │
         │ foto_obat          │
         └────────┬───────────┘
                  │
         ┌────────▼────────────┐
         │ kategori_obats      │
         ├────────────────────┤
         │ id (PK)            │
         │ nm_kategori        │
         └────────────────────┘

┌──────────────────────────┐
│ pelanggans (Profil)      │
├──────────────────────────┤
│ kd_pelanggan (PK)        │
│ nm_pelanggan             │
│ alamat                   │
│ kota                     │
│ telpon                   │
└────────┬─────────────────┘
         │ HasOne
         ↓
┌──────────────────────────┐
│ akun_pelanggans (Auth)   │
├──────────────────────────┤
│ id (PK)                  │
│ kd_pelanggan (FK)        │
│ email                    │
│ password (hashed)        │
│ remember_token           │
└──────────────────────────┘
```

### 📋 Tabel & Detail

#### **1. users** (Admin & Apoteker)
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'apoteker') NOT NULL,
    telpon VARCHAR(20) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Menyimpan data admin dan apoteker untuk login dan role-based authorization

---

#### **2. kategori_obats**
```sql
CREATE TABLE kategori_obats (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nm_kategori VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Kategori obat (Antibiotik, Vitamin, Ramuan, dll)

---

#### **3. supliers**
```sql
CREATE TABLE supliers (
    kd_suplier VARCHAR(20) PRIMARY KEY,
    nm_suplier VARCHAR(255) NOT NULL,
    alamat TEXT NULL,
    telpon VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Data supplier/vendor obat

---

#### **4. obats** (Inventory)
```sql
CREATE TABLE obats (
    kd_obat VARCHAR(20) PRIMARY KEY,
    nm_obat VARCHAR(255) NOT NULL,
    id_kategori BIGINT UNSIGNED NULL FK REFERENCES kategori_obats(id),
    satuan VARCHAR(50) NULL,
    harga_beli DECIMAL(12,2) DEFAULT 0,
    harga_jual DECIMAL(12,2) DEFAULT 0,
    stok INT DEFAULT 0,
    tgl_kadaluarsa DATE NULL,
    status ENUM('aktif', 'nonaktif', 'kadaluarsa') DEFAULT 'aktif',
    kd_suplier VARCHAR(20) NULL FK REFERENCES supliers(kd_suplier),
    foto_obat VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Master data obat dengan stok dan pricing

**Relasi:**
- BelongsTo: kategori_obats, supliers
- HasMany: penjualan_details, pembelian_details

---

#### **5. pelanggans** (Customer Profile)
```sql
CREATE TABLE pelanggans (
    kd_pelanggan VARCHAR(20) PRIMARY KEY,
    nm_pelanggan VARCHAR(255) NOT NULL,
    alamat TEXT NULL,
    kota VARCHAR(100) NULL,
    telpon VARCHAR(20) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Profil pelanggan retail & online

**Relasi:**
- HasOne: akun_pelanggans (untuk online customers)
- HasMany: penjualans

---

#### **6. akun_pelanggans** (Customer Auth - Extends Authenticatable)
```sql
CREATE TABLE akun_pelanggans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    kd_pelanggan VARCHAR(20) FK REFERENCES pelanggans(kd_pelanggan),
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Akun login terpisah untuk pelanggan online ordering

**Guard:** 'pelanggan' (tersendiri dari 'web' guard)

**Relasi:**
- BelongsTo: pelanggans

---

#### **7. penjualans** (Sales Transactions)
```sql
CREATE TABLE penjualans (
    nota VARCHAR(30) PRIMARY KEY,              -- Format: PJL-XXXXX atau INV-YYYYMMDD-XXX
    tgl_nota DATE NOT NULL,
    kd_pelanggan VARCHAR(20) NULL FK,         -- NULL jika walk-in/retail
    id_user BIGINT UNSIGNED NULL FK,          -- Admin/Apoteker yang input
    diskon DECIMAL(12,2) DEFAULT 0,
    alamat_kirim TEXT NULL,                   -- Untuk pesanan online
    status_pembayaran VARCHAR(30) DEFAULT 'belum_bayar',  -- belum_bayar, lunas, confirmed
    bukti_pembayaran VARCHAR(255) NULL,       -- Foto/scan bukti pembayaran
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Mencatat transaksi penjualan (retail & online)

**Relasi:**
- BelongsTo: pelanggan (nullable), users
- HasMany: penjualan_details

**Status Pembayaran:**
- `belum_bayar` - Pesanan belum dibayar (online)
- `lunas` - Pembayaran lunas (retail/confirmed)
- `confirmed` - Admin sudah verifikasi bukti

---

#### **8. penjualan_details** (Sales Line Items)
```sql
CREATE TABLE penjualan_details (
   x
**Relasi:**
- BelongsTo: penjualan, obat

**Calculated:**
- `subtotal = jumlah × harga_jual_obat` (dihitung di Model)

---

#### **9. pembelians** (Purchase/Restocking Transactions)
```sql
CREATE TABLE pembelians (
    nota VARCHAR(30) PRIMARY KEY,              -- Format: PB-XXXXX
    tgl_nota DATE NOT NULL,
    kd_suplier VARCHAR(20) NULL FK,
    id_user BIGINT UNSIGNED NULL FK,           -- Admin/Apoteker yang input
    diskon DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Mencatat transaksi pembelian/restocking dari supplier

**Relasi:**
- BelongsTo: suplier, users
- HasMany: pembelian_details

---

#### **10. pembelian_details** (Purchase Line Items)
```sql
CREATE TABLE pembelian_details (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nota VARCHAR(30) FK REFERENCES pembelians(nota),
    kd_obat VARCHAR(20) FK REFERENCES obats(kd_obat),
    jumlah INT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Fungsi:** Detail obat dalam satu transaksi pembelian

**Relasi:**
- BelongsTo: pembelian, obat

---

### 🔗 Relationship Summary

| Model | Relasi | Tipe | Related Model |
|-------|--------|------|---------------|
| User | penjualans | HasMany | Penjualan |
| | pembelians | HasMany | Pembelian |
| Obat | kategori | BelongsTo | KategoriObat |
| | suplier | BelongsTo | Suplier |
| | penjualanDetails | HasMany | PenjualanDetail |
| | pembelianDetails | HasMany | PembelianDetail |
| Pelanggan | akun | HasOne | AkunPelanggan |
| | penjualans | HasMany | Penjualan |
| AkunPelanggan | pelanggan | BelongsTo | Pelanggan |
| Penjualan | pelanggan | BelongsTo | Pelanggan |
| | user | BelongsTo | User |
| | details | HasMany | PenjualanDetail |
| PenjualanDetail | penjualan | BelongsTo | Penjualan |
| | obat | BelongsTo | Obat |
| Pembelian | suplier | BelongsTo | Suplier |
| | user | BelongsTo | User |
| | details | HasMany | PembelianDetail |
| PembelianDetail | pembelian | BelongsTo | Pembelian |
| | obat | BelongsTo | Obat |

---

## 5. AUTHENTICATION & AUTHORIZATION

### 🔐 Dual Authentication System

Project ini menggunakan **2 authentication guards** yang berbeda:

#### **Guard 1: "web"** (Admin & Apoteker)
```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    ...
]

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
]
```

**Tabel:** users  
**Model:** App\Models\User (extends Authenticatable)  
**Login:** Email + Password  
**Features:**
- Password hashing (bcrypt)
- Email verification
- Remember me token
- Password reset

**Routes:** /login, /register, /forgot-password  
(via Laravel Breeze)

---

#### **Guard 2: "pelanggan"** (Customer)
```php
// config/auth.php
'guards' => [
    'pelanggan' => [
        'driver' => 'session',
        'provider' => 'akun_pelanggans',
    ],
]

'providers' => [
    'akun_pelanggans' => [
        'driver' => 'eloquent',
        'model' => App\Models\AkunPelanggan::class,
    ],
]
```

**Tabel:** akun_pelanggans  
**Model:** App\Models\AkunPelanggan (extends Authenticatable)  
**Relasi:** 1 AkunPelanggan → 1 Pelanggan (profile)  
**Login:** Email + Password  

**Middleware:** `pelanggan.auth` di routes/web.php untuk protect customer routes

---

### 🛡️ Authentication Flow

#### **Login Admin/Apoteker**
```
User input email & password
    ↓
POST /login (Auth\AuthenticatedSessionController)
    ↓
Validate credentials against users table
    ↓
Create session dengan guard='web'
    ↓
Redirect ke /admin/dashboard atau /apoteker/dashboard (based on role)
```

#### **Login Pelanggan (Online Ordering)**
```
Customer input email & password
    ↓
POST /pelanggan/login (via Breeze or custom controller)
    ↓
Validate credentials against akun_pelanggans table
    ↓
Create session dengan guard='pelanggan'
    ↓
Redirect ke /pelanggan/dashboard
```

---

### 🔑 How to Check Current User

```php
// Check Admin/Apoteker logged in
if (Auth::guard('web')->check()) {
    $user = Auth::guard('web')->user(); // App\Models\User
    echo $user->nama;
    echo $user->role; // 'admin' atau 'apoteker'
}

// Check Pelanggan logged in
if (Auth::guard('pelanggan')->check()) {
    $customer = Auth::guard('pelanggan')->user(); // App\Models\AkunPelanggan
    $profile = $customer->pelanggan; // Akses profil pelanggan
    echo $profile->nm_pelanggan;
}
```

---

## 6. ROLE-BASED ACCESS CONTROL (RBAC)

### 👥 Roles & Permissions

#### **Role 1: ADMIN**
```
Permissions:
✓ Dashboard (overview semua transaksi)
✓ Obat: CRUD (create, read, update, delete)
✓ Obat: View expired & update status
✓ Kategori: CRUD
✓ Supplier: CRUD
✓ Pelanggan: Read-only (view list & detail)
✓ Apoteker: CRUD (manage staff)
✓ Penjualan: View reports & export (Excel/PDF)
✓ Pembelian: CRUD & View reports

Routes Prefix: /admin/*
Middleware: auth:web, role:admin
```

#### **Role 2: APOTEKER**
```
Permissions:
✓ Dashboard (summary)
✓ Obat: Create & Read & Delete (not edit generic fields)
✓ Obat: View & delete expired
✓ Penjualan: CRUD (input manual sales)
✓ Penjualan: View online orders & confirm/reject payment
✓ Pembelian: CRUD (input restocking)
✗ Not allowed: Manage users, suppliers, categories

Routes Prefix: /apoteker/*
Middleware: auth:web, role:apoteker
```

#### **Role 3: PELANGGAN (Customer)**
```
Permissions:
✓ Dashboard: View catalog/list obat
✓ Obat: Read (view detail)
✓ Cart: Add, update, remove items
✓ Checkout: Create order (penjualan record)
✓ Upload bukti pembayaran
✓ View pesanan (order history)
✗ Not allowed: Admin/Apoteker functions

Routes Prefix: /pelanggan/*
Middleware: pelanggan.auth
```

---

### 🚨 Middleware untuk Authorization

#### **File: app/Http/Middleware/EnsureUserRole.php**
```php
/**
 * Memvalidasi bahwa user (web guard) memiliki role tertentu.
 * Digunakan untuk protect admin & apoteker routes.
 * 
 * Usage: middleware(['auth:web', 'role:admin'])
 */
namespace App\Http\Middleware;

class EnsureUserRole {
    public function handle($request, Closure $next, $role) {
        if (!Auth::guard('web')->check()) {
            return redirect('/login');
        }
        
        if (Auth::guard('web')->user()->role !== $role) {
            abort(403, 'Unauthorized'); // Forbidden
        }
        
        return $next($request);
    }
}
```

#### **File: app/Http/Middleware/EnsurePelangganGuard.php**
```php
/**
 * Memastikan pelanggan sudah login sebelum akses /pelanggan/* routes.
 */
namespace App\Http\Middleware;

class EnsurePelangganGuard {
    public function handle($request, Closure $next) {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
```

---

### 📝 Route Protection Examples

```php
// routes/web.php

// ADMIN ROUTES - memerlukan auth:web + role:admin
Route::middleware(['auth:web', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Only admin dapat akses
        Route::resource('obat', Admin\ObatController::class);
    });

// APOTEKER ROUTES - memerlukan auth:web + role:apoteker
Route::middleware(['auth:web', 'role:apoteker'])
    ->prefix('apoteker')
    ->name('apoteker.')
    ->group(function () {
        // Only apoteker dapat akses
        Route::resource('penjualan', Apoteker\PenjualanController::class);
    });

// PELANGGAN ROUTES - memerlukan pelanggan.auth
Route::middleware(['pelanggan.auth'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        // Only logged-in pelanggan dapat akses
        Route::post('checkout', [Pelanggan\CartController::class, 'processCheckout']);
    });
```

---

## 7. FITUR & FLOW DIAGRAM

### 🎯 FITUR UTAMA PROJECT

Berikut adalah 9 fitur utama dengan flow diagram:

---

### **FITUR 1: INVENTORY MANAGEMENT (Master Obat)**

#### **Deskripsi**
Kelola master data obat-obatan: tambah, ubah, hapus, dengan tracking harga beli/jual, stok, tanggal kadaluarsa.

#### **Entities Terlibat**
- **Obat** (Model)
- **KategoriObat** (Model)
- **Suplier** (Model)
- **Admin** (Role)
- **Apoteker** (Role - limited)

#### **Flow Diagram - Admin Menambah Obat**

```
┌─────────────────────────────────────────┐
│ Admin login & ke /admin/obat/create    │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ Form tampil:                            │
│ • Kd_obat (AUTO-GENERATED)             │
│ • Nm_obat                              │
│ • Kategori (dropdown)                  │
│ • Satuan                               │
│ • Harga Beli                           │
│ • Harga Jual                           │
│ • Tgl Kadaluarsa                       │
│ • Kd_suplier                           │
│ • Foto                                 │
└──────────────┬──────────────────────────┘
               │
               ▼ Admin submit form
┌─────────────────────────────────────────┐
│ POST /admin/obat (ObatController@store) │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│ Validate input:                         │
│ • kd_obat: required, unique, max:20   │
│ • nm_obat: required, string, max:100  │
│ • harga_beli/jual: numeric, min:0     │
│ • kategori: exists di db              │
│ • suplier: exists di db               │
└──────────────┬──────────────────────────┘
               │
               ├─→ Valid ▼
               │ ┌───────────────────────┐
               │ │ Insert ke tabel obats │
               │ │ with stok = 0         │
               │ │ status = 'aktif'      │
               │ └───────────┬───────────┘
               │             │
               │             ▼
               │ ┌───────────────────────┐
               │ │ Redirect ke index     │
               │ │ Success message       │
               │ └───────────────────────┘
               │
               ├─→ Error ▼
               │ ┌────────────────────────┐
               │ │ Kembali ke form       │
               │ │ Show error messages   │
               │ └────────────────────────┘
               │
               └─→ Selesai
```

#### **Flow Diagram - Update Status Obat (Kadaluarsa)**

```
SCENARIO: Obat sudah kadaluarsa, perlu dimark sebagai kadaluarsa

┌──────────────────────────────────────────┐
│ Admin ke /admin/obat/kadaluarsa         │
│ (View list obat dengan tgl >= hari ini) │
└──────────────┬───────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────┐
│ Show list obat expired:                 │
│ • Obat A (tgl: 2026-06-20)              │
│ • Obat B (tgl: 2026-06-15)              │
│ • Tombol "Mark as Expired" per item     │
└──────────────┬───────────────────────────┘
               │
               ▼ Admin klik button
┌──────────────────────────────────────────┐
│ PATCH /admin/obat/{id}/status            │
│ (request: status='kadaluarsa')           │
└──────────────┬───────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────┐
│ ObatController@updateStatus:             │
│ • Find obat by kd_obat                  │
│ • Update status = 'kadaluarsa'          │
│ • Save ke DB                            │
└──────────────┬───────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────┐
│ Redirect back dengan success message    │
│ Obat tidak lagi tampil di penjualan     │
└──────────────────────────────────────────┘
```

---

### **FITUR 2: PENJUALAN RETAIL (Sales by Apoteker)**

#### **Deskripsi**
Apoteker input transaksi penjualan manual untuk customer di apotek.

#### **Entities Terlibat**
- **Penjualan** (Model)
- **PenjualanDetail** (Model)
- **Obat** (Model)
- **Pelanggan** (Model - nullable)
- **User** (Apoteker)

#### **Flow Diagram - Apoteker Input Penjualan**

```
┌────────────────────────────────────────┐
│ Apoteker ke /apoteker/penjualan/create │
└─────────────┬──────────────────────────┘
              │
              ▼
┌────────────────────────────────────────┐
│ Tampil Form:                           │
│ • Tgl Nota (required)                  │
│ • Pelanggan (dropdown, nullable)       │
│ • Tabel items (dinamis)                │
│   - Obat (search/select)               │
│   - Jumlah                             │
│   - Harga satuan (auto dari master)    │
│   - Subtotal (calculated)              │
│ • Diskon (optional)                    │
│ • Total (calculated)                   │
│ • Tombol: Tambah Item, Simpan, Cancel  │
└─────────────┬──────────────────────────┘
              │
              ▼ Apoteker pilih obat & qty
┌────────────────────────────────────────┐
│ JavaScript (Alpine.js) validasi:       │
│ • Qty <= stok tersedia                 │
│ • Show real-time subtotal              │
│ • Show total + tax calculation         │
└─────────────┬──────────────────────────┘
              │
              ▼ Submit form
┌────────────────────────────────────────┐
│ POST /apoteker/penjualan               │
│ (PenjualanController@store)             │
└─────────────┬──────────────────────────┘
              │
              ▼
┌────────────────────────────────────────┐
│ Validate:                              │
│ • tgl_nota: required, date             │
│ • items: array, min 1                  │
│ • items[].kd_obat: exists              │
│ • items[].jumlah: int, min 1           │
│ • items[].jumlah <= obat.stok          │
│ • diskon: numeric, min 0               │
└─────────────┬──────────────────────────┘
              │
    ┌─────────┴─────────┐
    │                   │
    ▼ Valid            ▼ Invalid
┌────────────────┐  ┌──────────────┐
│ Database       │  │ Return form  │
│ Transaction:   │  │ with errors  │
│                │  └──────────────┘
│ 1. Generate    │
│    Nota:       │
│    "PJL-"      │
│    + increment │
│                │
│ 2. Create      │
│    Penjualan:  │
│    • nota      │
│    • tgl_nota  │
│    • kd_pelang │
│    • id_user   │
│    • diskon    │
│    • status    │
│      ='lunas'  │
│                │
│ 3. For each    │
│    item:       │
│    • Create    │
│      Detail    │
│    • Kurangi   │
│      stok obat │
│                │
│ 4. If error    │
│    → Rollback  │
│       semua    │
└────────────────┘
      │
      ▼
┌────────────────┐
│ Success →      │
│ Redirect ke    │
│ penjualan show │
│ (print invoice)│
└────────────────┘
```

#### **Code Mechanics - Penjualan Store**

```php
// app/Http/Controllers/Apoteker/PenjualanController.php

public function store(Request $request): RedirectResponse {
    // Step 1: Validasi
    $request->validate([
        'tgl_nota' => 'required|date',
        'kd_pelanggan' => 'nullable|exists:pelanggans,kd_pelanggan',
        'diskon' => 'nullable|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.kd_obat' => 'required|exists:obats,kd_obat',
        'items.*.jumlah' => 'required|integer|min:1',
    ]);

    try {
        // Step 2: Transaction (atomic)
        DB::transaction(function () use ($request) {
            // Generate Nota
            $lastNota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first();
            $nextNum = $lastNota ? ((int) substr($lastNota->nota, 4)) + 1 : 1;
            $nota = 'PJL-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            // Create Penjualan
            $penjualan = Penjualan::create([
                'nota' => $nota,
                'tgl_nota' => $request->tgl_nota,
                'kd_pelanggan' => $request->kd_pelanggan ?: null,
                'id_user' => Auth::guard('web')->id(),
                'diskon' => $request->diskon ?? 0,
                'status_pembayaran' => 'lunas', // Retail = langsung lunas
            ]);

            // Step 3: Create Detail & Update Stok
            foreach ($request->items as $item) {
                $obat = Obat::findOrFail($item['kd_obat']);

                // Check stok
                if ($obat->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$obat->nm_obat} tidak cukup.");
                }

                // Create detail
                PenjualanDetail::create([
                    'nota' => $nota,
                    'kd_obat' => $obat->kd_obat,
                    'jumlah' => $item['jumlah'],
                ]);

                // Kurangi stok
                $obat->decrement('stok', $item['jumlah']);
            }
        });

        return redirect()->route('apoteker.penjualan.show', $nota)
                       ->with('success', 'Penjualan berhasil disimpan');

    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

---

### **FITUR 3: ONLINE ORDERING (Pelanggan Order)**

#### **Deskripsi**
Customer browse obat, tambah ke keranjang, checkout, upload bukti pembayaran, dan apoteker verify.

#### **Entities Terlibat**
- **AkunPelanggan** (Authenticatable)
- **Pelanggan** (Profile)
- **Obat** (Catalog)
- **Penjualan** (Order)
- **PenjualanDetail** (Order items)
- **Session** (Cart storage)

#### **Flow Diagram - Full Online Ordering Journey**

```
┌──────────────────────────────────────────┐
│ PELANGGAN TIDAK LOGIN                    │
└─────────────────┬────────────────────────┘
                  │
         ┌────────┴────────┐
         │                 │
         ▼                 ▼
    ┌─────────┐       ┌──────────┐
    │ Register│       │  Login   │
    │ buat    │       │ email +  │
    │ akun    │       │ password │
    │ baru    │       └──────────┘
    └────┬────┘             │
         │           ┌──────┴──────┐
         └──────┬────┘             │
                │                  ▼
                │      ┌──────────────────────┐
                │      │ Auth::guard('pelang- │
                │      │ gan')->check() = true│
                │      │ Session created      │
                │      └──────────┬───────────┘
                │                 │
                └────────┬────────┘
                         │
                         ▼
            ┌──────────────────────────┐
            │ /pelanggan/dashboard     │
            │ (view obat catalog)      │
            └──────────┬───────────────┘
                       │
                       ▼
            ┌──────────────────────────┐
            │ Browse & click obat      │
            │ Lihat detail:            │
            │ • Nama, harga            │
            │ • Stok tersedia          │
            │ • Foto & deskripsi       │
            └──────────┬───────────────┘
                       │
                       ▼
            ┌──────────────────────────┐
            │ Input qty & klik         │
            │ "Tambah ke Keranjang"    │
            └──────────┬───────────────┘
                       │
                       ▼
         POST /pelanggan/cart/add/{obat}
         (CartController@add)
                       │
                       ▼
            ┌──────────────────────────┐
            │ Validasi:                │
            │ • qty > 0                │
            │ • qty <= stok            │
            │ • obat status = 'aktif'  │
            └──────────┬───────────────┘
                       │
         ┌─────────────┴──────────────┐
         │ Valid                      │ Invalid
         ▼                            ▼
    ┌────────────────┐          ┌──────────────┐
    │ session()->get │          │ Redirect     │
    │ ('cart', [])   │          │ with error   │
    │ Add item       │          │ message      │
    │ Save to        │          └──────────────┘
    │ session        │
    └────────┬───────┘
             │
             ▼
    ┌────────────────────────────┐
    │ Back to catalog / cart page│
    │ Cart item count updated    │
    │ Item bisa diedit/dihapus   │
    └────────┬───────────────────┘
             │
             ▼ Repeat: browsing & tambah item
    ┌────────────────────────────┐
    │ Pelanggan klik "Checkout"  │
    │ GET /pelanggan/checkout    │
    └────────┬───────────────────┘
             │
             ▼
    ┌────────────────────────────┐
    │ Tampil checkout form:      │
    │ • Alamat kirim (input)     │
    │ • Cart summary             │
    │ • Total price              │
    │ • Tombol: Confirm, Cancel  │
    └────────┬───────────────────┘
             │
             ▼ Klik "Confirm Order"
    POST /pelanggan/checkout
    (CartController@processCheckout)
             │
             ▼
    ┌────────────────────────────────┐
    │ Validasi & create Penjualan:   │
    │ • status_pembayaran            │
    │   = 'belum_bayar'              │
    │ • Stok dicek & dikurangi       │
    │ • Session cart dihapus         │
    │ • Redirect to upload bukti     │
    └────────┬───────────────────────┘
             │
             ▼
    ┌────────────────────────────┐
    │ Show upload form:          │
    │ • Preview order detail     │
    │ • Upload bukti pembayaran  │
    │   (foto/scan transfer)     │
    │ • Alamat kirim             │
    └────────┬───────────────────┘
             │
             ▼ Upload & submit
    POST /pelanggan/pesanan/{nota}/bayar
    (PenjualanController@uploadBuktiPembayaran)
             │
             ▼
    ┌────────────────────────────┐
    │ Validasi file              │
    │ • File type: image         │
    │ • Max size: 2MB            │
    │ • Store ke storage/        │
    │   bukti_pembayaran/        │
    └────────┬───────────────────┘
             │
             ▼
    ┌────────────────────────────┐
    │ Update Penjualan:          │
    │ • bukti_pembayaran = path  │
    │ • status_pembayaran        │
    │   = 'belum_confirmed'      │
    │ OR sent to admin/apoteker  │
    │   for manual verification  │
    └────────┬───────────────────┘
             │
             ▼
    ┌────────────────────────────┐
    │ Success message:           │
    │ "Bukti terkirim, tunggu    │
    │  verifikasi apoteker"      │
    └────────────────────────────┘

APOTEKER SIDE:
             │
             ▼
    ┌────────────────────────────┐
    │ GET /apoteker/             │
    │ pesanan-online             │
    │ (show pending orders)      │
    └────────┬───────────────────┘
             │
    ┌────────┴────────┐
    │                 │
    ▼                 ▼
┌────────────┐  ┌─────────────┐
│ View detail│  │ Lihat bukti │
│ order &    │  │ pembayaran  │
│ bukti      │  │ (foto)      │
└────┬───────┘  └──────┬──────┘
     │                 │
     ▼                 ▼
┌────────────────────────────┐
│ Klik "Konfirmasi Pembayaran"│
│ atau "Tolak Pembayaran"    │
└────────┬───────────────────┘
         │
    ┌────┴────┐
    │          │
    ▼ Confirm  ▼ Reject
┌──────────┐ ┌─────────┐
│ PATCH    │ │ PATCH   │
│ penjualan│ │ penjualan│
│/confirm  │ │/reject  │
│          │ │         │
│status →  │ │status → │
│confirmed │ │rejected │
└────┬─────┘ └────┬────┘
     │            │
     ▼            ▼
┌─────────────────────────────┐
│ Penjualan final status set  │
│ Email notification sent     │
│ (optional)                  │
└─────────────────────────────┘
```

---

### **FITUR 4: PEMBELIAN / RESTOCKING**

#### **Deskripsi**
Apoteker/Admin input pembelian dari supplier untuk restocking.

#### **Entities Terlibat**
- **Pembelian** (Order to supplier)
- **PembelianDetail** (Items)
- **Obat** (Update stok)
- **Suplier** (Vendor)
- **User** (Admin/Apoteker)

#### **Flow Diagram - Pembelian Restocking**

```
┌─────────────────────────────────┐
│ Apoteker ke /apoteker/pembelian │
│ /create                         │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Form tampil:                    │
│ • Tgl Nota                      │
│ • Supplier (dropdown)           │
│ • Tabel items:                  │
│   - Obat (search)               │
│   - Qty                         │
│   - Harga satuan                │
│   - Subtotal                    │
│ • Diskon (opt)                  │
│ • Total                         │
└────────────┬────────────────────┘
             │
             ▼ Submit
┌─────────────────────────────────┐
│ POST /apoteker/pembelian        │
│ (PembelianController@store)     │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Validasi & DB transaction:      │
│ 1. Generate nota: PB-XXXXX      │
│ 2. Create Pembelian record      │
│ 3. Create Detail items          │
│ 4. ADD stok obat !!!            │
│    (beda dari penjualan)        │
│    obat->increment('stok', qty) │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│ Success:                        │
│ Redirect to pembelian.show      │
│ Stok sudah updated              │
└─────────────────────────────────┘
```

---

### **FITUR 5: MANAJEMEN PELANGGAN**

#### **Deskripsi**
Admin view customer profile dan transaction history. Customer data bisa from walk-in (pelanggan retail) atau online account.

#### **Entities Terlibat**
- **Pelanggan** (Customer profile)
- **AkunPelanggan** (Online login)
- **Penjualan** (Transaction history)
- **Admin** (User)

#### **Flow Diagram - Admin View Pelanggan**

```
GET /admin/pelanggan
        │
        ▼
┌──────────────────────────────────┐
│ ObatController@index:            │
│ Query all pelanggans with:       │
│ • Count penjualan records        │
│ • Last transaction date          │
│ • Total spent (calculated)       │
└──────────┬───────────────────────┘
           │
           ▼
┌──────────────────────────────────┐
│ Display list:                    │
│ • Kd_pelanggan                   │
│ • Nm_pelanggan                   │
│ • Telpon                         │
│ • Alamat                         │
│ • Total transaksi                │
│ • Tombol: View detail            │
└──────────┬───────────────────────┘
           │
           ▼ Click "View Detail"
GET /admin/pelanggan/{kd_pelanggan}
           │
           ▼
┌──────────────────────────────────┐
│ Show detail:                     │
│ • Profile info                   │
│ • Akun online (if ada)           │
│ • Riwayat transaksi penjualan   │
│   (dengan detail obat)           │
└──────────────────────────────────┘
```

---

### **FITUR 6: LAPORAN PENJUALAN (Reporting)**

#### **Deskripsi**
Admin generate report penjualan, dengan opsi export Excel atau PDF.

#### **Entities Terlibat**
- **Penjualan** (Transactions)
- **PenjualanDetail** (Line items)
- **DOMPDF** (PDF generation)

#### **Flow Diagram - Export Laporan Penjualan**

```
┌────────────────────────────────────┐
│ Admin ke /admin/penjualan          │
└────────────┬───────────────────────┘
             │
             ▼
┌────────────────────────────────────┐
│ Display list penjualan dengan:     │
│ • Filter (date range, customer)    │
│ • Tombol: Export Excel, Export PDF │
└────────────┬───────────────────────┘
             │
         ┌───┴────┐
         │        │
         ▼ Excel  ▼ PDF
    ┌─────────┐ ┌──────────┐
    │ GET     │ │ GET      │
    │ /export │ │ /pdf     │
    │ -excel  │ │ -download│
    └────┬────┘ └────┬─────┘
         │           │
         ▼           ▼
    ┌────────────────────────────┐
    │ Build query dengan filter: │
    │ • Penjualans::with(        │
    │   'pelanggan','details')   │
    │ • Apply date filter        │
    │ • Include all items        │
    └────┬───────────────────────┘
         │
         ├─→ Excel ▼
         │   ┌──────────────────┐
         │   │ Use Laravel Excel│
         │   │ or raw CSV       │
         │   │ generation       │
         │   │ Return download  │
         │   └──────────────────┘
         │
         └─→ PDF ▼
             ┌──────────────────────┐
             │ Use DOMPDF:          │
             │ 1. Load blade view   │
             │    with data         │
             │ 2. Render to PDF     │
             │ 3. Download file     │
             └──────────────────────┘
```

---

### **FITUR 7: MANAJEMEN SUPPLIER**

#### **Deskripsi**
Admin manage supplier/vendor data (CRUD).

#### **Flow Diagram - Simple CRUD**

```
GET /admin/suplier
  → List semua supplier
  
POST /admin/suplier
  → Create supplier baru
  
GET /admin/suplier/{id}
  → View detail
  
PATCH /admin/suplier/{id}
  → Update supplier
  
DELETE /admin/suplier/{id}
  → Delete supplier (soft/hard)
```

---

### **FITUR 8: MANAJEMEN KATEGORI OBAT**

#### **Deskripsi**
Admin manage kategori (CRUD).

---

### **FITUR 9: MANAJEMEN APOTEKER (Staff)**

#### **Deskripsi**
Admin manage apoteker staff (CRUD) - tambah, ubah, hapus user apoteker.

#### **Entities Terlibat**
- **User** (Model - dengan role='apoteker')

#### **Flow Diagram - Create Apoteker**

```
GET /admin/apoteker/create
        │
        ▼
┌────────────────────────────────┐
│ Form:                          │
│ • Nama                         │
│ • Email (unique)               │
│ • Password                     │
│ • Telpon                       │
│ (role = 'apoteker' default)    │
└────────┬───────────────────────┘
         │
         ▼ Submit
POST /admin/apoteker
         │
         ▼
┌────────────────────────────────┐
│ Create user record:            │
│ • Hash password                │
│ • Set role='apoteker'          │
│ • Save ke DB                   │
└────────┬───────────────────────┘
         │
         ▼
Success → Apoteker dapat login
dengan email & password
```

---

## 8. DETAIL MEKANIK PER FITUR

### 📋 FITUR 1: INVENTORY (Obat)

#### **CRUD Operations**

##### **CREATE (Tambah Obat)**
```
Admin → /admin/obat/create
Form tampil dengan field:
  - Kd_obat: AUTO GENERATED (bukan input)
  - Nm_obat: required, string, max 100
  - Kategori: select from dropdown
  - Satuan: input, contoh "box", "tablet", "botol"
  - Harga Beli: decimal, min 0
  - Harga Jual: decimal, min 0
  - Stok: initial = 0 (nanti di-update via pembelian)
  - Tgl Kadaluarsa: date picker, nullable
  - Supplier: select from dropdown
  - Foto: file upload (optional)

Validation:
  - kd_obat unique di DB (auto-generated, jadi tidak error)
  - nm_obat required
  - harga_beli >= 0
  - harga_jual >= harga_beli (business logic)
  - kategori_id must exist in kategori_obats

Database Operation:
  INSERT INTO obats (kd_obat, nm_obat, ...) VALUES (...)
```

##### **READ (Lihat Obat)**
```
Admin → /admin/obat
Query: SELECT * FROM obats with relationships (kategori, suplier)
Apply filters:
  - Search by nm_obat or kd_obat
  - Filter by status (aktif/nonaktif/kadaluarsa)
  - Pagination: 15 items per page

Display:
  - Table dengan column: kd_obat, nm_obat, kategori, harga_jual, stok, status
  - Tombol: Edit, Delete, View Detail
```

##### **UPDATE (Edit Obat)**
```
Admin → /admin/obat/{kd_obat}/edit
Pre-fill form dengan data existing
Allow edit:
  - nm_obat, kategori, satuan, harga_beli, harga_jual, tgl_kadaluarsa, foto
NOT allow edit:
  - kd_obat (primary key)
  - stok (hanya via pembelian/penjualan)

Database Operation:
  UPDATE obats SET ... WHERE kd_obat = ?
```

##### **DELETE (Hapus Obat)**
```
Admin → /admin/obat/{kd_obat}
Soft delete atau hard delete:
  - Soft delete: mark as 'nonaktif' (recommended)
  - Hard delete: DELETE dari DB (risk: foreign key violation)

Database Operation:
  UPDATE obats SET status='nonaktif' WHERE kd_obat = ?
  OR
  DELETE FROM obats WHERE kd_obat = ?
```

#### **Special Operations**

##### **Mark Expired (Tandai Kadaluarsa)**
```
Admin/Apoteker → /admin/obat/kadaluarsa
Query: SELECT * FROM obats WHERE tgl_kadaluarsa <= TODAY() AND status != 'kadaluarsa'
Display: List expired medicines

Klik "Mark as Expired":
  PATCH /admin/obat/{kd_obat}/status
  Request body: { status: 'kadaluarsa' }
  
Database Operation:
  UPDATE obats SET status='kadaluarsa' WHERE kd_obat = ?

Result: Obat tidak akan tampil di daftar penjualan (filter status='aktif')
```

##### **Auto-Generate Medicine Code**
```
Method: Obat::generateKdObat() (static method)

Logic:
  1. Get last kd_obat dari DB
  2. Parse prefix (misal "OBT") dan number
  3. Increment number
  4. Format: "OBT" + zero-padded number (e.g., OBT-000001)

Implementation:
  public static function generateKdObat(): string {
      $last = self::latest('kd_obat')->first();
      if (!$last) {
          return 'OBT-000001';
      }
      $num = (int) substr($last->kd_obat, 4) + 1;
      return 'OBT-' . str_pad($num, 6, '0', STR_PAD_LEFT);
  }
```

---

### 📦 FITUR 2: PENJUALAN RETAIL

#### **Sales Transaction Flow**

##### **Create Sales (Input Penjualan)**
```
Apoteker → /apoteker/penjualan/create

Form Dynamic dengan JavaScript (Alpine.js):
  1. Tgl_nota: date input (required)
  2. Pelanggan: dropdown (optional, nullable untuk walk-in)
  3. Items table (dynamic add/remove rows):
     - Column: Obat, Qty, Harga Satuan, Subtotal
     - "+" button: add new row
     - "-" button: remove row
  4. Diskon: input decimal
  5. Total: calculated = sum(subtotal) - diskon

Validasi Real-time (JavaScript):
  - Qty > 0
  - Qty <= available stock (show max available)
  - Subtotal = qty * harga_jual
  - Total = sum(subtotal) - diskon
```

##### **Server-side Store (Save Penjualan)**
```
POST /apoteker/penjualan

Validation:
  - tgl_nota: required|date
  - kd_pelanggan: nullable|exists:pelanggans,kd_pelanggan
  - diskon: nullable|numeric|min:0
  - items: required|array|min:1
  - items[].kd_obat: required|exists:obats,kd_obat
  - items[].jumlah: required|integer|min:1

Database Transaction (Atomic):
  1. Generate Nota:
     last_nota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first()
     next_num = last_nota ? ((int)substr(last_nota->nota, 4)) + 1 : 1
     nota = 'PJL-' + padded(next_num, 5, '0')
     
  2. Create Penjualan:
     INSERT INTO penjualans (nota, tgl_nota, kd_pelanggan, id_user, diskon, status_pembayaran)
     VALUES (?, ?, ?, ?, ?, 'lunas')  ← retail = langsung lunas
     
  3. For each item:
     a. Check stok:
        if obat.stok < qty → ROLLBACK + error
     b. Create PenjualanDetail:
        INSERT INTO penjualan_details (nota, kd_obat, jumlah)
        VALUES (?, ?, ?)
     c. Kurangi stok:
        UPDATE obats SET stok = stok - ? WHERE kd_obat = ?

Result:
  - Penjualan record created dengan status='lunas'
  - Stok berkurang
  - If error: ROLLBACK semua changes
```

##### **Calculate Total**
```
Method: Custom calculation di view atau controller

Formula:
  subtotal_per_item = obat.harga_jual * qty
  total_subtotal = sum(subtotal_per_item untuk semua item)
  grand_total = total_subtotal - diskon

Implementation (Blade template):
  @php
    $subtotal = collect($items)->sum(fn($item) => $item['qty'] * $item['harga_jual']);
    $total = $subtotal - $diskon;
  @endphp
  <p>Total: {{ $total }}</p>
```

---

### 🛒 FITUR 3: ONLINE ORDERING (Cart & Checkout)

#### **Cart Management (Session-Based)**

##### **Add to Cart**
```
POST /pelanggan/cart/add/{kd_obat}

Request body: { jumlah: 5 }

Validation:
  - jumlah > 0 (else error: "Qty minimal 1")
  - jumlah <= obat.stok (else error: "Stok tidak mencukupi")
  - obat.status = 'aktif' (not expired)

Logic:
  cart = session()->get('cart', [])
  
  if (cart already has obat) {
    newQty = cart[kd_obat]['jumlah'] + jumlah
    if (newQty > obat.stok) {
      error: "Total di cart melebihi stok"
    } else {
      cart[kd_obat]['jumlah'] = newQty
    }
  } else {
    cart[kd_obat] = {
      'kd_obat': kd_obat,
      'nm_obat': obat.nm_obat,
      'harga_jual': obat.harga_jual,
      'jumlah': jumlah,
      'satuan': obat.satuan,
      'foto_obat': obat.foto_obat,
      'subtotal': jumlah * obat.harga_jual
    }
  }
  
  session()->put('cart', cart)
  return redirect()->back()->with('success', 'Added to cart')

Result:
  - Item in session['cart']
  - Cart count updated in header
```

##### **View Cart**
```
GET /pelanggan/cart

Logic:
  cart = session()->get('cart', [])
  total = sum(item['subtotal'] for item in cart)
  
Display:
  - Table: Item, Qty, Price, Subtotal, Action (edit/remove)
  - Total price at bottom
  - Buttons: Continue Shopping, Checkout, Clear Cart
```

##### **Update Cart Item**
```
PATCH /pelanggan/cart/update/{kd_obat}

Request: { jumlah: 10 }

Validation:
  - jumlah > 0
  - jumlah <= obat.stok

Logic:
  cart[kd_obat]['jumlah'] = jumlah
  cart[kd_obat]['subtotal'] = jumlah * harga_jual
  session()->put('cart', cart)
```

##### **Remove from Cart**
```
DELETE /pelanggan/cart/remove/{kd_obat}

Logic:
  unset(cart[kd_obat])
  session()->put('cart', cart)
```

---

#### **Checkout & Order Creation**

##### **Checkout Page**
```
GET /pelanggan/checkout

Logic:
  cart = session()->get('cart', [])
  if (empty(cart)) {
    redirect to cart with error
  }
  
  total = sum(subtotals)

Display:
  - Form:
    * Alamat Kirim (textarea)
    * Cart Summary (readonly)
    * Total Price (readonly)
    * Buttons: Confirm Order, Cancel
```

##### **Process Checkout & Create Order**
```
POST /pelanggan/checkout

Request:
  {
    'alamat_kirim': 'Jln Merdeka No 123, Jakarta',
  }

Validation:
  - alamat_kirim: required|string
  - cart: not empty (check session)

Logic:
  cart = session()->get('cart', [])
  kd_pelanggan = Auth::guard('pelanggan')->user()->pelanggan->kd_pelanggan
  
  DB::transaction(function() {
    1. Generate nota:
       nota = 'INV-' + date('Ymd') + '-' + increment
    
    2. Create Penjualan:
       INSERT INTO penjualans
       (nota, tgl_nota, kd_pelanggan, id_user, alamat_kirim, 
        status_pembayaran, diskon)
       VALUES
       (?, today(), ?, null, ?, 'belum_bayar', 0)
       ← null id_user: online order tidak ada apoteker yang input
       ← status = 'belum_bayar': tunggu customer upload bukti
    
    3. For each item in cart:
       a. Create PenjualanDetail
       b. UPDATE stok (decrement)
          ⚠️ PENTING: Stok sudah dikurangi saat checkout!
          (bukan saat payment confirmed)
    
    4. Clear session:
       session()->forget('cart')
  })

Result:
  - Penjualan created dengan status='belum_bayar'
  - Stok sudah berkurang
  - Redirect to payment upload page
  
URL: /pelanggan/pesanan/{nota}/bayar
```

##### **Upload Payment Proof**
```
GET /pelanggan/pesanan/{nota}

Display:
  - Order detail
  - Status: "Menunggu pembayaran"
  - Form upload:
    * File input (image only)
    * Max 2MB
    * Supported: jpg, png, pdf

POST /pelanggan/pesanan/{nota}/bayar

Request:
  FormData with file: bukti_pembayaran

Validation:
  - bukti_pembayaran: required|file|mimes:jpg,png,pdf|max:2048

Logic:
  1. Store file:
     path = $request->file('bukti_pembayaran')->store('bukti_pembayaran')
  
  2. Update Penjualan:
     UPDATE penjualans
     SET bukti_pembayaran = ?, status_pembayaran = 'belum_confirmed'
     WHERE nota = ?
  
  3. Notify apoteker (optional email)

Result:
  - File stored
  - Status = 'belum_confirmed'
  - Redirect with message: "Menunggu verifikasi apoteker"
```

---

#### **Apoteker Verify Payment**

##### **View Online Orders**
```
GET /apoteker/pesanan-online

Query:
  penjualans
    ->where('status_pembayaran', 'belum_confirmed')
    ->orWhere('status_pembayaran', 'belum_bayar')
    ->with(['pelanggan', 'details.obat'])
    ->orderByDesc('created_at')
    ->paginate(15)

Display:
  - Table: Nota, Tgl, Pelanggan, Total, Status, Actions
  - Buttons: View Detail, Confirm, Reject
```

##### **Confirm Payment**
```
POST /apoteker/pesanan-online/{nota}/konfirmasi

Logic:
  penjualan = Penjualan::findOrFail(nota)
  
  penjualan.update({
    'status_pembayaran': 'confirmed'  ← atau 'lunas'
  })
  
  // Optional: Send SMS/email to customer
  
Result:
  - Status updated
  - Customer notified
  - Order ready for shipping/pickup
```

##### **Reject Payment**
```
POST /apoteker/pesanan-online/{nota}/tolak

Logic:
  penjualan = Penjualan::findOrFail(nota)
  
  DB::transaction(function() {
    1. REVERT stok (karena ditolak):
       For each detail in penjualan->details:
         obat->increment('stok', detail->jumlah)
    
    2. Update status:
       penjualan.update({
         'status_pembayaran': 'rejected',
         'bukti_pembayaran': null  ← clear bukti
       })
    
    3. Clear penjualan_details (or keep record for audit)
  })
  
Result:
  - Stok restored
  - Order marked as rejected
  - Customer notified
```

---

### 🔄 FITUR 4: PEMBELIAN / RESTOCKING

#### **Create Purchase Order**
```
POST /apoteker/pembelian

Similar structure like penjualan:

1. Generate nota: PB-XXXXX

2. Create Pembelian:
   INSERT INTO pembelians
   (nota, tgl_nota, kd_suplier, id_user, diskon)
   VALUES (?, ?, ?, ?, ?)

3. For each item:
   a. Create PembelianDetail
   b. UPDATE stok (INCREMENT!)
      obat->increment('stok', qty)
      
⚠️ PENTING: Stok BERTAMBAH dari pembelian, BERKURANG dari penjualan
```

---

### 📊 FITUR 5-9: Standard CRUD Operations

Fitur 5-9 (Pelanggan, Laporan, Supplier, Kategori, Apoteker) adalah standard CRUD atau read-only operations tanpa special logic.

---

## 9. STRUKTUR CODE & FILE ORGANIZATION

### 📂 File Organization Best Practices

#### **Controllers Location & Naming**
```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php      → GET /admin/dashboard
│   ├── ObatController.php           → GET/POST /admin/obat/*
│   ├── KategoriObatController.php   → GET/POST /admin/kategori/*
│   ├── SuplierController.php        → GET/POST /admin/suplier/*
│   ├── PelangganController.php      → GET /admin/pelanggan/*
│   ├── ApotekerController.php       → GET/POST /admin/apoteker/*
│   ├── PenjualanController.php      → GET /admin/penjualan/* (reports)
│   └── PembelianController.php      → GET/POST/DELETE /admin/pembelian/*
│
├── Apoteker/
│   ├── DashboardController.php
│   ├── ObatController.php           → create, store, destroy (no edit)
│   ├── PenjualanController.php      → CRUD + online orders
│   └── PembelianController.php      → CRUD
│
├── Pelanggan/
│   ├── ObatController.php           → index, show (catalog browse)
│   ├── CartController.php           → CRUD cart + checkout
│   └── PenjualanController.php      → create order, show pesanan
│
├── Auth/
│   └── (Breeze-provided controllers)
│
└── ProfileController.php
```

#### **Models Location & Structure**
```
app/Models/
├── User.php                 → Admin & Apoteker
├── AkunPelanggan.php        → Customer auth (extends Authenticatable)
├── Pelanggan.php            → Customer profile
├── Obat.php                 → Medicine master
├── KategoriObat.php
├── Suplier.php
├── Penjualan.php            → Sales transaction
├── PenjualanDetail.php      → Sales line item
├── Pembelian.php            → Purchase transaction
└── PembelianDetail.php      → Purchase line item
```

#### **Views Location & Structure**
```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── obat/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── kadaluarsa.blade.php
│   ├── suplier/
│   ├── pelanggan/
│   ├── apoteker/
│   ├── penjualan/
│   └── pembelian/
│
├── apoteker/
│   ├── dashboard.blade.php
│   ├── obat/
│   ├── penjualan/
│   ├── pesanan-online.blade.php
│   └── pembelian/
│
├── pelanggan/
│   ├── dashboard.blade.php
│   ├── obat/
│   │   ├── index.blade.php (catalog)
│   │   └── show.blade.php   (detail & add to cart)
│   ├── cart/
│   │   ├── index.blade.php
│   │   └── checkout.blade.php
│   └── penjualan/
│       ├── index.blade.php  (pesanan history)
│       └── show.blade.php   (pesanan detail + upload bukti)
│
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── (password reset, etc - Breeze)
│
├── layouts/
│   ├── app.blade.php        (main layout)
│   └── guest.blade.php      (auth layout)
│
└── components/
    ├── navbar.blade.php
    ├── sidebar.blade.php
    └── (reusable components)
```

#### **Routes Organization**
```
routes/web.php
├── // Auth routes (Breeze)
│   require __DIR__.'/auth.php';
│
├── // Admin routes (middleware: auth:web, role:admin)
│   Route::middleware(['auth:web', 'role:admin'])
│   ->prefix('admin')
│   ->group(...)
│
├── // Apoteker routes (middleware: auth:web, role:apoteker)
│   Route::middleware(['auth:web', 'role:apoteker'])
│   ->prefix('apoteker')
│   ->group(...)
│
└── // Pelanggan routes (middleware: pelanggan.auth)
    Route::middleware(['pelanggan.auth'])
    ->prefix('pelanggan')
    ->group(...)

routes/auth.php
├── // Login
│   Route::get('login', [AuthenticatedSessionController::class, 'create'])
│   Route::post('login', [AuthenticatedSessionController::class, 'store'])
│
├── // Register
│   Route::get('register', [RegisteredUserController::class, 'create'])
│   Route::post('register', [RegisteredUserController::class, 'store'])
│
└── // Password reset, etc (Breeze)
```

#### **Database Organization**
```
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_06_22_120407_create_kategori_obats_table.php
│   ├── 2026_06_22_120413_create_supliers_table.php
│   ├── 2026_06_22_120417_create_obats_table.php
│   ├── 2026_06_22_120422_create_pelanggans_table.php
│   ├── 2026_06_22_120432_create_akun_pelanggans_table.php
│   ├── 2026_06_22_120438_create_penjualans_table.php
│   ├── 2026_06_22_120445_create_penjualan_details_table.php
│   ├── 2026_06_22_120450_create_pembelians_table.php
│   └── 2026_06_22_120520_create_pembelian_details_table.php
│
├── factories/
│   └── UserFactory.php
│
└── seeders/
    ├── DatabaseSeeder.php
    ├── KategoriObatSeeder.php
    ├── SuplierSeeder.php
    ├── ObatSeeder.php
    ├── PelangganSeeder.php
    ├── AkunPelangganSeeder.php
    └── (etc)
```

---

## 10. IMPLEMENTASI TECHNICAL PER FITUR

### 🔧 FITUR 1: INVENTORY - Code Examples

#### **Model: Obat.php**
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obat extends Model
{
    protected $primaryKey = 'kd_obat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_obat', 'nm_obat', 'id_kategori', 'satuan',
        'harga_beli', 'harga_jual', 'stok', 'tgl_kadaluarsa',
        'status', 'kd_suplier', 'foto_obat',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kadaluarsa' => 'date',
            'harga_beli' => 'decimal:2',
            'harga_jual' => 'decimal:2',
        ];
    }

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriObat::class, 'id_kategori');
    }

    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class, 'kd_suplier');
    }

    public function penjualanDetails(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'kd_obat');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeKadaluarsa($query)
    {
        return $query->whereDate('tgl_kadaluarsa', '<=', today());
    }

    // Static Methods
    public static function generateKdObat(): string
    {
        $last = self::latest('kd_obat')->first();
        if (!$last) return 'OBT-000001';
        
        $num = (int) substr($last->kd_obat, 4) + 1;
        return 'OBT-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
```

#### **Controller: Admin/ObatController.php (partial)**
```php
<?php
namespace App\Http\Controllers\Admin;

use App\Models\Obat;
use App\Models\KategoriObat;
use App\Models\Suplier;
use Illuminate\Http\Request;

class ObatController
{
    public function index(Request $request)
    {
        $query = Obat::with(['kategori', 'suplier']);

        if ($search = $request->search) {
            $query->where('nm_obat', 'like', "%{$search}%")
                  ->orWhere('kd_obat', 'like', "%{$search}%");
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $obats = $query->orderBy('nm_obat')->paginate(15)->withQueryString();

        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        $kategoris = KategoriObat::orderBy('nm_kategori')->get();
        $supliers = Suplier::orderBy('nm_suplier')->get();
        $kd_obat = Obat::generateKdObat();

        return view('admin.obat.create', compact('kategoris', 'supliers', 'kd_obat'));
    }

    public function store(Request $request)
    {
        $kd_obat = Obat::generateKdObat();
        $request->merge(['kd_obat' => $kd_obat]);

        $validated = $request->validate([
            'nm_obat' => 'required|string|max:100',
            'id_kategori' => 'nullable|exists:kategori_obats,id',
            'satuan' => 'nullable|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'tgl_kadaluarsa' => 'nullable|date|after:today',
            'kd_suplier' => 'nullable|exists:supliers,kd_suplier',
            'foto_obat' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_obat')) {
            $validated['foto_obat'] = $request->file('foto_obat')
                ->store('obat_fotos', 'public');
        }

        Obat::create($validated);

        return redirect()->route('admin.obat.index')
                       ->with('success', 'Obat berhasil ditambahkan');
    }

    public function kadaluarsa()
    {
        $obats = Obat::kadaluarsa()->get();
        return view('admin.obat.kadaluarsa', compact('obats'));
    }

    public function updateStatus(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'status' => 'required|in:aktif,nonaktif,kadaluarsa',
        ]);

        $obat->update($validated);

        return back()->with('success', 'Status obat diperbarui');
    }
}
```

#### **View: admin/obat/index.blade.php (partial)**
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>Manajemen Obat</h2>
        <a href="{{ route('admin.obat.create') }}" class="btn btn-primary">+ Tambah Obat</a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari obat..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obats as $obat)
                <tr>
                    <td>{{ $obat->kd_obat }}</td>
                    <td>{{ $obat->nm_obat }}</td>
                    <td>{{ $obat->kategori->nm_kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                    <td>{{ $obat->stok }}</td>
                    <td>
                        <span class="badge bg-{{ $obat->status == 'aktif' ? 'success' : 'danger' }}">
                            {{ ucfirst($obat->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.obat.edit', $obat->kd_obat) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.obat.destroy', $obat->kd_obat) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $obats->links() }}
</div>
@endsection
```

---

### 🛒 FITUR 3: CART & CHECKOUT - Code Examples

#### **Controller: Pelanggan/CartController.php (partial)**
```php
<?php
namespace App\Http\Controllers\Pelanggan;

use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('pelanggan.cart.index', compact('cart'));
    }

    public function add(Request $request, Obat $obat)
    {
        $qty = (int) $request->input('jumlah', 1);

        if ($qty <= 0) {
            return back()->with('error', 'Jumlah obat harus minimal 1.');
        }

        if ($obat->stok < $qty) {
            return back()->with('error', "Stok obat {$obat->nm_obat} tidak mencukupi (Tersedia: {$obat->stok}).");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$obat->kd_obat])) {
            $newQty = $cart[$obat->kd_obat]['jumlah'] + $qty;
            if ($obat->stok < $newQty) {
                return back()->with('error', "Total di keranjang ({$newQty}) melebihi stok yang tersedia.");
            }
            $cart[$obat->kd_obat]['jumlah'] = $newQty;
            $cart[$obat->kd_obat]['subtotal'] = $newQty * (float) $obat->harga_jual;
        } else {
            $cart[$obat->kd_obat] = [
                'kd_obat' => $obat->kd_obat,
                'nm_obat' => $obat->nm_obat,
                'harga_jual' => (float) $obat->harga_jual,
                'jumlah' => $qty,
                'satuan' => $obat->satuan,
                'foto_obat' => $obat->foto_obat,
                'subtotal' => $qty * (float) $obat->harga_jual,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                           ->with('error', 'Keranjang kosong');
        }

        $total = collect($cart)->sum('subtotal');
        return view('pelanggan.cart.checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'alamat_kirim' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pelanggan.cart.index')
                           ->with('error', 'Keranjang kosong');
        }

        try {
            DB::transaction(function () use ($request, $cart) {
                $kd_pelanggan = Auth::guard('pelanggan')->user()->pelanggan->kd_pelanggan;

                // Generate Nota
                $lastNota = Penjualan::where('nota', 'like', 'INV-%')
                    ->orderByRaw('CAST(SUBSTRING(nota, -5) AS UNSIGNED) DESC')
                    ->first();
                $nextNum = $lastNota ? ((int) substr($lastNota->nota, -5)) + 1 : 1;
                $nota = 'INV-' . date('Ymd') . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

                // Create Penjualan
                $penjualan = Penjualan::create([
                    'nota' => $nota,
                    'tgl_nota' => today(),
                    'kd_pelanggan' => $kd_pelanggan,
                    'id_user' => null, // Online order
                    'alamat_kirim' => $request->alamat_kirim,
                    'diskon' => 0,
                    'status_pembayaran' => 'belum_bayar',
                ]);

                // Create Details & Update Stok
                foreach ($cart as $item) {
                    $obat = Obat::findOrFail($item['kd_obat']);

                    if ($obat->stok < $item['jumlah']) {
                        throw new \Exception("Stok {$obat->nm_obat} tidak cukup");
                    }

                    PenjualanDetail::create([
                        'nota' => $nota,
                        'kd_obat' => $item['kd_obat'],
                        'jumlah' => $item['jumlah'],
                    ]);

                    $obat->decrement('stok', $item['jumlah']);
                }

                session()->forget('cart');
            });

            return redirect()->route('pelanggan.penjualan.show', $nota)
                           ->with('success', 'Pesanan dibuat. Silakan upload bukti pembayaran.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
```

---

### 📋 VALIDATION RULES REFERENCE

```
Obat:
  - kd_obat: string|max:20|unique:obats (auto-generated)
  - nm_obat: required|string|max:100
  - id_kategori: nullable|exists:kategori_obats,id
  - satuan: nullable|string|max:50
  - harga_beli: required|numeric|min:0
  - harga_jual: required|numeric|min:0|gte:harga_beli
  - stok: integer|min:0 (auto from transaction)
  - tgl_kadaluarsa: nullable|date|after:today
  - status: in:aktif,nonaktif,kadaluarsa
  - kd_suplier: nullable|exists:supliers,kd_suplier
  - foto_obat: nullable|image|max:2048

Penjualan:
  - nota: string|max:30|unique:penjualans (auto-generated)
  - tgl_nota: required|date
  - kd_pelanggan: nullable|exists:pelanggans,kd_pelanggan
  - id_user: nullable|exists:users,id
  - diskon: nullable|numeric|min:0
  - status_pembayaran: in:belum_bayar,lunas,confirmed,rejected
  - alamat_kirim: nullable|string
  - bukti_pembayaran: nullable|image|mimes:jpg,png,pdf|max:2048

PenjualanDetail:
  - nota: required|exists:penjualans,nota
  - kd_obat: required|exists:obats,kd_obat
  - jumlah: required|integer|min:1|custom:check_stok

Pelanggan:
  - kd_pelanggan: string|max:20|unique (auto/manual)
  - nm_pelanggan: required|string|max:255
  - alamat: nullable|string
  - kota: nullable|string|max:100
  - telpon: nullable|string|max:20

AkunPelanggan:
  - email: required|email|unique:akun_pelanggans
  - password: required|string|min:8|confirmed
  - kd_pelanggan: required|exists:pelanggans,kd_pelanggan

User (Admin/Apoteker):
  - nama: required|string|max:255
  - email: required|email|unique:users
  - password: required|string|min:8
  - role: required|in:admin,apoteker
  - telpon: nullable|string|max:20
```

---

## 📌 KESIMPULAN & KEY POINTS

### ✅ Architecture Summary
- **Pattern:** MVC (Model-View-Controller)
- **Database:** 10 tables dengan relationships terstruktur
- **Auth System:** Dual guards (web: admin/apoteker, pelanggan: customers)
- **Authorization:** Role-based access control (RBAC)

### 🔑 Key Technical Mechanisms
1. **Inventory Management:** Master-detail model untuk obat
2. **Transaction Recording:** Penjualan & Pembelian with atomic transactions
3. **Stok Management:** Increment (pembelian) & Decrement (penjualan)
4. **Session-Based Cart:** Client-side cart storage
5. **Payment Verification:** File upload & manual confirmation
6. **Reporting:** Query aggregation + PDF/Excel export

### 💡 Best Practices Implemented
1. **Database Transactions:** Atomic operations untuk consistency
2. **Validation:** Server-side & client-side
3. **Authorization:** Middleware-based access control
4. **Error Handling:** Try-catch blocks dengan meaningful messages
5. **Relationships:** Proper Eloquent relationships dengan eager loading

### 🚀 Development Tips
1. Always use transactions untuk multi-step operations
2. Validate stok sebelum decrement
3. Use scopes untuk common queries (e.g., ->aktif())
4. Implement audit logging untuk sensitive operations
5. Use pagination untuk large result sets

---

**Dokumentasi ini menjadi referensi lengkap untuk understanding dan developing fitur-fitur di Ujikom Apotek App.**

