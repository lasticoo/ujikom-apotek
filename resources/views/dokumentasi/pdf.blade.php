<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi Teknis - Sistem Informasi Apotek Digital</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            line-height: 1.6;
        }
        /* Cover Page */
        .cover {
            background: linear-gradient(135deg, #0f3460, #16213e, #1a1a2e);
            color: white;
            height: 297mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px;
            page-break-after: always;
        }
        .cover-icon { font-size: 80px; margin-bottom: 30px; }
        .cover-title {
            font-size: 28px;
            font-weight: bold;
            color: #e94560;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        .cover-subtitle {
            font-size: 16px;
            color: #a8dadc;
            margin-bottom: 40px;
        }
        .cover-divider {
            width: 100px;
            height: 3px;
            background: #e94560;
            margin: 20px auto;
        }
        .cover-meta {
            font-size: 12px;
            color: #a8dadc;
            line-height: 2;
        }
        .cover-badge {
            display: inline-block;
            background: rgba(233,69,96,0.2);
            border: 1px solid #e94560;
            color: #e94560;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 10px;
            margin: 8px 5px;
        }

        /* Content Pages */
        .page {
            padding: 25mm 20mm 25mm 20mm;
        }
        .page-header {
            border-bottom: 3px solid #0f3460;
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .page-header-title {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .page-header-logo {
            font-size: 10px;
            font-weight: bold;
            color: #0f3460;
        }

        /* TOC */
        .toc-page { page-break-after: always; }
        .toc-title {
            font-size: 20px;
            color: #0f3460;
            border-bottom: 3px solid #e94560;
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .toc-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dotted #ccc;
            padding: 5px 0;
            font-size: 10px;
        }
        .toc-item.section {
            font-weight: bold;
            color: #0f3460;
            font-size: 11px;
            margin-top: 8px;
        }
        .toc-item.subsection {
            padding-left: 20px;
            color: #444;
        }

        /* Sections */
        h1 {
            font-size: 22px;
            color: #0f3460;
            border-bottom: 3px solid #e94560;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        h2 {
            font-size: 16px;
            color: #16213e;
            border-left: 4px solid #e94560;
            padding-left: 12px;
            margin: 20px 0 12px 0;
            font-weight: bold;
        }
        h3 {
            font-size: 12px;
            color: #0f3460;
            border-left: 2px solid #a8dadc;
            padding-left: 8px;
            margin: 14px 0 8px 0;
            font-weight: bold;
        }
        h4 {
            font-size: 10px;
            color: #333;
            margin: 10px 0 5px 0;
            font-weight: bold;
            font-style: italic;
        }
        p {
            margin-bottom: 8px;
            text-align: justify;
        }
        ul, ol {
            margin: 5px 0 10px 20px;
        }
        li {
            margin-bottom: 3px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 9px;
        }
        thead th {
            background: #0f3460;
            color: white;
            padding: 7px 8px;
            text-align: left;
            font-size: 9px;
        }
        tbody tr:nth-child(even) { background: #f0f4ff; }
        tbody tr:nth-child(odd) { background: #ffffff; }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }

        /* Code blocks */
        .code-block {
            background: #1a1a2e;
            color: #a8dadc;
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 4px solid #e94560;
            font-size: 8.5px;
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            white-space: pre-wrap;
            word-break: break-all;
            margin: 10px 0;
        }

        /* Callout boxes */
        .callout {
            padding: 10px 15px;
            border-radius: 4px;
            margin: 12px 0;
            font-size: 9.5px;
        }
        .callout.info {
            background: #e8f4fd;
            border-left: 4px solid #2196f3;
            color: #1565c0;
        }
        .callout.warning {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            color: #e65100;
        }
        .callout.success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }
        .callout.danger {
            background: #fce4ec;
            border-left: 4px solid #e91e63;
            color: #880e4f;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-blue { background: #0f3460; color: white; }
        .badge-red { background: #e94560; color: white; }
        .badge-green { background: #4caf50; color: white; }
        .badge-orange { background: #ff9800; color: white; }

        /* Flow diagram text */
        .flow-box {
            background: #f0f4ff;
            border: 1px solid #0f3460;
            border-radius: 6px;
            padding: 8px 12px;
            margin: 4px 0;
            font-size: 9px;
            font-family: 'DejaVu Sans Mono', monospace;
            white-space: pre;
        }

        .section-divider {
            border: none;
            border-top: 2px solid #e0e0e0;
            margin: 25px 0;
        }

        .page-break { page-break-before: always; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e0e0e0;
            font-size: 8px;
            color: #aaa;
            text-align: center;
        }

        /* Role cards */
        .role-grid {
            width: 100%;
            margin: 10px 0;
        }
        .role-card {
            display: inline-block;
            width: 30%;
            margin-right: 3%;
            background: #f0f4ff;
            border: 1px solid #0f3460;
            border-radius: 8px;
            padding: 12px;
            vertical-align: top;
        }
        .role-card-title {
            font-weight: bold;
            font-size: 12px;
            color: #0f3460;
            margin-bottom: 5px;
        }

        /* Tech stack pills */
        .tech-pill {
            display: inline-block;
            background: #0f3460;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8px;
            margin: 2px;
        }

        .highlight { color: #e94560; font-weight: bold; }
    </style>
</head>
<body>

{{-- ===== COVER PAGE ===== --}}
<div class="cover">
    <div class="cover-icon">💊</div>
    <div class="cover-title">APOTEK DIGITAL</div>
    <div class="cover-subtitle">Sistem Informasi Manajemen Apotek</div>
    <div class="cover-divider"></div>
    <div style="font-size: 20px; font-weight: bold; color: white; margin-bottom: 8px;">Dokumentasi Teknis</div>
    <div style="font-size: 13px; color: #a8dadc; margin-bottom: 30px;">Flow Sistem · Arsitektur · Implementasi Kode</div>
    <div>
        <span class="cover-badge">Laravel 12</span>
        <span class="cover-badge">PHP 8.2+</span>
        <span class="cover-badge">MySQL</span>
        <span class="cover-badge">Blade</span>
        <span class="cover-badge">DomPDF</span>
    </div>
    <div class="cover-divider"></div>
    <div class="cover-meta">
        <div>Versi: 1.0</div>
        <div>Tanggal: {{ now()->format('d F Y') }}</div>
        <div>Bahasa: Indonesia</div>
    </div>
</div>

{{-- ===== TABLE OF CONTENTS ===== --}}
<div class="page toc-page">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">Dokumentasi Teknis</span>
    </div>
    <div class="toc-title">📑 Daftar Isi</div>

    <div class="toc-item section"><span>1. Gambaran Umum Sistem</span><span>3</span></div>
    <div class="toc-item section"><span>2. Tech Stack &amp; Dependensi</span><span>3</span></div>
    <div class="toc-item section"><span>3. Arsitektur Sistem (MVC)</span><span>4</span></div>
    <div class="toc-item section"><span>4. Skema Database &amp; Relasi Tabel</span><span>5</span></div>
    <div class="toc-item section"><span>5. Autentikasi &amp; Multi-Guard</span><span>7</span></div>
    <div class="toc-item section"><span>6. Routing &amp; Middleware</span><span>8</span></div>
    <div class="toc-item section"><span>7. Fitur Per Role</span><span>9</span></div>
    <div class="toc-item subsection"><span>7.1 Role Admin</span><span>9</span></div>
    <div class="toc-item subsection"><span>7.2 Role Apoteker</span><span>10</span></div>
    <div class="toc-item subsection"><span>7.3 Role Pelanggan</span><span>11</span></div>
    <div class="toc-item section"><span>8. Alur Utama (Flowchart)</span><span>12</span></div>
    <div class="toc-item subsection"><span>8.1 Penjualan Offline (Apoteker)</span><span>12</span></div>
    <div class="toc-item subsection"><span>8.2 Pembelian Online (Pelanggan)</span><span>13</span></div>
    <div class="toc-item subsection"><span>8.3 Konfirmasi Pesanan Online (Apoteker)</span><span>14</span></div>
    <div class="toc-item subsection"><span>8.4 Restok Obat / Pembelian</span><span>15</span></div>
    <div class="toc-item subsection"><span>8.5 Manajemen Data Master Obat</span><span>16</span></div>
    <div class="toc-item section"><span>9. Mekanisme Fitur Utama (Detail Teknis &amp; Code)</span><span>16</span></div>
    <div class="toc-item subsection"><span>9.1 Sistem Keranjang Belanja (Session Cart)</span><span>16</span></div>
    <div class="toc-item subsection"><span>9.2 Manajemen Stok Otomatis</span><span>18</span></div>
    <div class="toc-item subsection"><span>9.3 Laporan &amp; Ekspor PDF/Excel</span><span>19</span></div>
    <div class="toc-item subsection"><span>9.4 Restok Berbasis Supplier (Filter JS)</span><span>20</span></div>
    <div class="toc-item subsection"><span>9.5 Notifikasi Pesanan Online</span><span>21</span></div>
    <div class="toc-item section"><span>10. Konvensi Penomoran Nota Otomatis</span><span>21</span></div>
    <div class="toc-item section"><span>11. Ringkasan Prinsip Desain Sistem</span><span>22</span></div>
</div>

{{-- ===== SECTION 1: OVERVIEW ===== --}}
<div class="page">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">1. Gambaran Umum Sistem</span>
    </div>

    <h1>1. Gambaran Umum Sistem</h1>
    <p>
        <strong>Sistem Informasi Apotek Digital</strong> adalah aplikasi web manajemen apotek berbasis <span class="highlight">Laravel 12</span> yang dirancang untuk mengelola operasional apotek secara terpadu. Sistem ini menyediakan alur kerja yang komprehensif mulai dari manajemen stok dan supplier, transaksi penjualan (offline &amp; online), restok obat, hingga laporan analitik dengan kemampuan ekspor PDF dan Excel.
    </p>

    <h2>Tiga Jenis Pengguna (Role)</h2>
    <table>
        <thead>
            <tr>
                <th>Role</th>
                <th>Deskripsi</th>
                <th>Akses Utama</th>
                <th>Guard Auth</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-blue">Admin</span></td>
                <td>Pemilik/pengelola apotek</td>
                <td>Master data, laporan analitik, manajemen user &amp; suplier</td>
                <td><code>web</code> (role=admin)</td>
            </tr>
            <tr>
                <td><span class="badge badge-orange">Apoteker</span></td>
                <td>Staf operasional harian</td>
                <td>Penjualan offline, konfirmasi pesanan online, restok stok</td>
                <td><code>web</code> (role=apoteker)</td>
            </tr>
            <tr>
                <td><span class="badge badge-green">Pelanggan</span></td>
                <td>Konsumen akhir belanja online</td>
                <td>Katalog, keranjang, checkout, upload bukti bayar</td>
                <td><code>pelanggan</code> (guard terpisah)</td>
            </tr>
        </tbody>
    </table>

    <div class="callout info">
        <strong>ℹ️ Filosofi Sistem:</strong> Sistem menggunakan satu guard terpisah untuk pelanggan sehingga pelanggan tidak bisa mengakses area admin/apoteker dan sebaliknya. Staf internal menggunakan satu tabel <code>users</code> dengan kolom <code>role</code> untuk membedakan Admin dan Apoteker.
    </div>

    <hr class="section-divider">

    <h1>2. Tech Stack &amp; Dependensi</h1>

    <h3>Backend</h3>
    <table>
        <thead>
            <tr><th>Komponen</th><th>Teknologi</th><th>Versi</th><th>Fungsi</th></tr>
        </thead>
        <tbody>
            <tr><td>Framework PHP</td><td>Laravel</td><td>12.0</td><td>Core framework (routing, ORM, auth, dll.)</td></tr>
            <tr><td>Bahasa Pemrograman</td><td>PHP</td><td>≥ 8.2</td><td>Runtime server-side</td></tr>
            <tr><td>Database</td><td>MySQL</td><td>—</td><td>Penyimpanan data persisten</td></tr>
            <tr><td>ORM</td><td>Eloquent ORM</td><td>bawaan Laravel</td><td>Query builder &amp; relasi model</td></tr>
            <tr><td>Template Engine</td><td>Blade</td><td>bawaan Laravel</td><td>Render HTML views</td></tr>
            <tr><td>Auth Scaffolding</td><td>Laravel Breeze</td><td>2.4</td><td>Login, register, password reset</td></tr>
            <tr><td>PDF Generator</td><td>barryvdh/laravel-dompdf</td><td>3.1</td><td>Ekspor laporan ke PDF (download)</td></tr>
        </tbody>
    </table>

    <h3>Frontend</h3>
    <table>
        <thead>
            <tr><th>Komponen</th><th>Teknologi</th><th>Fungsi</th></tr>
        </thead>
        <tbody>
            <tr><td>CSS Framework</td><td>Bootstrap 5 / Tabler UI</td><td>Layout, komponen UI, dashboard</td></tr>
            <tr><td>Icon Set</td><td>Tabler Icons (ti-*)</td><td>Ikon seluruh halaman</td></tr>
            <tr><td>Chart Library</td><td>Chart.js</td><td>Grafik penjualan di Dashboard Admin</td></tr>
            <tr><td>JavaScript</td><td>Vanilla JS</td><td>Keranjang dinamis, filter supplier, form interaktif</td></tr>
        </tbody>
    </table>

    <h3>Dev Tools &amp; Lingkungan</h3>
    <table>
        <thead>
            <tr><th>Komponen</th><th>Teknologi</th><th>Fungsi</th></tr>
        </thead>
        <tbody>
            <tr><td>Build Tool</td><td>Vite</td><td>Asset bundling CSS/JS</td></tr>
            <tr><td>Package Manager (PHP)</td><td>Composer</td><td>Manajemen library PHP</td></tr>
            <tr><td>Package Manager (JS)</td><td>NPM</td><td>Manajemen library JavaScript</td></tr>
            <tr><td>Dev Environment</td><td>Laragon</td><td>Local server (PHP + MySQL + Nginx)</td></tr>
            <tr><td>Testing</td><td>PestPHP</td><td>Framework unit testing PHP</td></tr>
        </tbody>
    </table>
</div>

{{-- ===== SECTION 3: ARSITEKTUR MVC ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">3. Arsitektur Sistem (MVC)</span>
    </div>

    <h1>3. Arsitektur Sistem (MVC)</h1>
    <p>Aplikasi mengikuti pola <strong>Model-View-Controller (MVC)</strong> yang merupakan standar Laravel 12:</p>

    <div class="flow-box">BROWSER (User: Admin / Apoteker / Pelanggan)
        |
        | HTTP Request
        v
ROUTING (routes/web.php)
  ├─ Middleware autentikasi (auth:web / pelanggan.auth)
  ├─ Middleware role (role:admin / role:apoteker)
  └─ Pengelompokan URL per prefix (/admin, /apoteker, /pelanggan)
        |
        v
CONTROLLER (app/Http/Controllers)
  ├─ Admin/*Controller.php      ← fitur admin
  ├─ Apoteker/*Controller.php   ← fitur apoteker
  └─ Pelanggan/*Controller.php  ← fitur pelanggan
  (Validasi input → logika bisnis → panggil model)
        |
     ┌──┴──────────────────────┐
     v                         v
  MODEL                      VIEW
  (app/Models)          (resources/views)
  ├─ Eloquent ORM        ├─ admin/...
  ├─ Relasi tabel        ├─ apoteker/...
  └─ Scope & Accessor    └─ pelanggan/...
     |
     v
DATABASE (MySQL)
  obats, pelanggans, penjualans, pembelians, supliers, ...</div>

    <h2>Struktur Direktori Penting</h2>
    <div class="code-block">app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Controller fitur admin
│   │   │   ├── DashboardController.php
│   │   │   ├── ObatController.php
│   │   │   ├── PembelianController.php
│   │   │   ├── PenjualanController.php
│   │   │   ├── SuplierController.php
│   │   │   ├── PelangganController.php
│   │   │   └── ApotekerController.php
│   │   ├── Apoteker/        # Controller fitur apoteker
│   │   │   ├── DashboardController.php
│   │   │   ├── ObatController.php
│   │   │   ├── PembelianController.php
│   │   │   └── PenjualanController.php
│   │   └── Pelanggan/       # Controller fitur pelanggan
│   │       ├── CartController.php
│   │       ├── ObatController.php
│   │       └── PenjualanController.php
│   └── Middleware/
│       ├── EnsureUserRole.php        # Cek role admin/apoteker
│       └── EnsurePelangganGuard.php  # Cek login pelanggan
├── Models/
│   ├── Obat.php, Penjualan.php, Pembelian.php
│   ├── Pelanggan.php, AkunPelanggan.php, Suplier.php
│   └── PenjualanDetail.php, PembelianDetail.php, KategoriObat.php
resources/views/
├── admin/, apoteker/, pelanggan/
└── layouts/
routes/ web.php
database/ migrations/, seeders/</div>
</div>

{{-- ===== SECTION 4: DATABASE SCHEMA ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">4. Skema Database &amp; Relasi Tabel</span>
    </div>

    <h1>4. Skema Database &amp; Relasi Tabel</h1>

    <h2>Tabel-Tabel Database</h2>

    <h3>Tabel: users</h3>
    <table>
        <thead><tr><th>Kolom</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>id</td><td>bigint (PK, AI)</td><td>Primary key</td></tr>
            <tr><td>nama</td><td>varchar</td><td>Nama lengkap user</td></tr>
            <tr><td>email</td><td>varchar (UNIQUE)</td><td>Email untuk login</td></tr>
            <tr><td>password</td><td>varchar</td><td>Password terenkripsi (bcrypt)</td></tr>
            <tr><td>role</td><td>enum('admin','apoteker')</td><td>Menentukan hak akses</td></tr>
        </tbody>
    </table>

    <h3>Tabel: obats</h3>
    <table>
        <thead><tr><th>Kolom</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>kd_obat</td><td>varchar(20) PK</td><td>Format: OBT-001 (auto-generated)</td></tr>
            <tr><td>nm_obat</td><td>varchar</td><td>Nama obat</td></tr>
            <tr><td>id_kategori</td><td>FK → kategori_obats (nullable)</td><td>Kategori jenis obat</td></tr>
            <tr><td>satuan</td><td>varchar</td><td>Contoh: Strip, Botol, Tablet</td></tr>
            <tr><td>harga_beli</td><td>decimal(12,2) default 0</td><td><strong>Hanya diupdate via Restok/Pembelian</strong></td></tr>
            <tr><td>harga_jual</td><td>decimal(12,2) default 0</td><td>Harga yang ditampilkan ke pelanggan</td></tr>
            <tr><td>stok</td><td>integer default 0</td><td><strong>Hanya berubah via transaksi (otomatis)</strong></td></tr>
            <tr><td>tgl_kadaluarsa</td><td>date (nullable)</td><td>Alert kadaluarsa di dashboard</td></tr>
            <tr><td>status</td><td>enum('aktif','nonaktif','kadaluarsa')</td><td>Mengontrol visibilitas di katalog</td></tr>
            <tr><td>foto_obat</td><td>varchar (nullable)</td><td>Path file gambar obat</td></tr>
            <tr><td>kd_suplier</td><td>FK → supliers (nullable)</td><td>Supplier default obat ini</td></tr>
        </tbody>
    </table>

    <h3>Tabel: penjualans</h3>
    <table>
        <thead><tr><th>Kolom</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>nota</td><td>varchar(30) PK</td><td>Format: PJL-00001</td></tr>
            <tr><td>tgl_nota</td><td>date</td><td>Tanggal transaksi</td></tr>
            <tr><td>kd_pelanggan</td><td>FK → pelanggans (nullable)</td><td>NULL = walk-in offline</td></tr>
            <tr><td>id_user</td><td>FK → users (nullable)</td><td>Apoteker yang memproses</td></tr>
            <tr><td>diskon</td><td>decimal</td><td>Potongan harga</td></tr>
            <tr><td>alamat_kirim</td><td>text (nullable)</td><td><strong>Ada = pesanan online</strong></td></tr>
            <tr><td>status_pembayaran</td><td>string</td><td>belum_bayar | menunggu_konfirmasi | lunas | dibatalkan</td></tr>
            <tr><td>bukti_pembayaran</td><td>varchar (nullable)</td><td>Path file bukti transfer pelanggan</td></tr>
        </tbody>
    </table>

    <h3>Tabel: pembelians (Restok)</h3>
    <table>
        <thead><tr><th>Kolom</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>nota</td><td>varchar(30) PK</td><td>Format: PO-00001</td></tr>
            <tr><td>tgl_nota</td><td>date</td><td>Tanggal pembelian ke supplier</td></tr>
            <tr><td>kd_suplier</td><td>FK → supliers</td><td>Supplier yang memasok</td></tr>
            <tr><td>id_user</td><td>FK → users</td><td>Admin/Apoteker yang menginput</td></tr>
            <tr><td>diskon</td><td>decimal</td><td>Diskon dari supplier</td></tr>
        </tbody>
    </table>

    <h2>Diagram Relasi Tabel (ERD)</h2>
    <div class="flow-box">supliers ──── obats ──── kategori_obats
   |              |
   |              +──── pembelian_details ──── pembelians
   |              |         (harga_beli, jumlah)
   |              |
   |              +──── penjualan_details ──── penjualans
   |                        (jumlah)              |
   |                                              |
   |                                         pelanggans ──── akun_pelanggans
   |                                              |           (email, password)
   +──────────────────────────────────────────────+
                users (Admin, Apoteker)</div>

    <h2>Relasi Kunci Model Eloquent</h2>
    <table>
        <thead><tr><th>Relasi</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>Obat → Suplier</td><td>BelongsTo</td><td>Setiap obat memiliki satu supplier utama</td></tr>
            <tr><td>Obat → KategoriObat</td><td>BelongsTo</td><td>Pengelompokan jenis obat</td></tr>
            <tr><td>Penjualan → Pelanggan</td><td>BelongsTo (nullable)</td><td>NULL = walk-in / offline</td></tr>
            <tr><td>Penjualan → User</td><td>BelongsTo (nullable)</td><td>Apoteker yang memproses</td></tr>
            <tr><td>Penjualan → PenjualanDetail</td><td>HasMany</td><td>Detail item transaksi penjualan</td></tr>
            <tr><td>Pembelian → Suplier</td><td>BelongsTo</td><td>Supplier yang memasok barang restok</td></tr>
            <tr><td>Pembelian → PembelianDetail</td><td>HasMany</td><td>Detail obat yang dibeli per PO</td></tr>
            <tr><td>Pelanggan → AkunPelanggan</td><td>HasOne</td><td>Pemisahan data profil &amp; kredensial login</td></tr>
        </tbody>
    </table>
</div>

{{-- ===== SECTION 5 & 6: AUTH & ROUTING ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">5. Autentikasi &amp; 6. Routing</span>
    </div>

    <h1>5. Autentikasi &amp; Multi-Guard</h1>
    <p>Sistem menggunakan <strong>dua guard autentikasi terpisah</strong> untuk membedakan staf internal dan pelanggan:</p>

    <h2>Guard <code>web</code> — Admin &amp; Apoteker</h2>
    <div class="code-block">// config/auth.php
'guards' => [
    'web' => [
        'driver'   => 'session',
        'provider' => 'users',   // tabel: users
    ],
    'pelanggan' => [
        'driver'   => 'session',
        'provider' => 'akun_pelanggans',  // tabel: akun_pelanggans
    ],
]</div>

    <h3>Middleware EnsureUserRole.php</h3>
    <p>Setelah login via guard <code>web</code>, middleware ini memeriksa kolom <code>role</code> untuk membedakan admin dari apoteker:</p>
    <div class="code-block">class EnsureUserRole {
    public function handle(Request $request, Closure $next, string ...$roles): Response {
        $user = Auth::guard('web')->user();
        if (!$user || !in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}</div>

    <h2>Guard <code>pelanggan</code> — Pelanggan Online</h2>
    <p>Pelanggan login menggunakan tabel <code>akun_pelanggans</code> yang terpisah dari tabel <code>users</code>. Data profil (nama, alamat) disimpan di tabel <code>pelanggans</code> yang berbeda, terhubung via <code>kd_pelanggan</code>.</p>

    <h3>Middleware EnsurePelangganGuard.php</h3>
    <div class="code-block">class EnsurePelangganGuard {
    public function handle(Request $request, Closure $next): Response {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}</div>

    <hr class="section-divider">

    <h1>6. Routing &amp; Middleware</h1>

    <h2>Rute URL Penting</h2>
    <table>
        <thead><tr><th>Method</th><th>URL</th><th>Controller</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>GET</td><td>/admin/dashboard</td><td>Admin\DashboardController@index</td><td>Dashboard analitik admin</td></tr>
            <tr><td>CRUD</td><td>/admin/obat</td><td>Admin\ObatController</td><td>CRUD master data obat</td></tr>
            <tr><td>CRUD</td><td>/admin/suplier</td><td>Admin\SuplierController</td><td>CRUD data suplier</td></tr>
            <tr><td>GET</td><td>/admin/penjualan</td><td>Admin\PenjualanController@index</td><td>Laporan semua penjualan</td></tr>
            <tr><td>GET</td><td>/admin/penjualan/download-pdf</td><td>Admin\PenjualanController@downloadPdf</td><td>Unduh PDF laporan</td></tr>
            <tr><td>GET</td><td>/admin/penjualan/export-excel</td><td>Admin\PenjualanController@exportExcel</td><td>Ekspor CSV/Excel</td></tr>
            <tr><td>CRUD</td><td>/admin/pembelian</td><td>Admin\PembelianController</td><td>CRUD restok/PO obat</td></tr>
            <tr><td>GET</td><td>/admin/pembelian/report</td><td>Admin\PembelianController@report</td><td>Laporan pengeluaran restok</td></tr>
            <tr><td>CRUD</td><td>/apoteker/penjualan</td><td>Apoteker\PenjualanController</td><td>Penjualan offline apoteker</td></tr>
            <tr><td>GET</td><td>/apoteker/pesanan-online</td><td>Apoteker\PenjualanController@onlineOrders</td><td>Daftar pesanan online masuk</td></tr>
            <tr><td>POST</td><td>/apoteker/pesanan/{id}/konfirmasi</td><td>@confirmPayment</td><td>Konfirmasi pembayaran pelanggan</td></tr>
            <tr><td>POST</td><td>/apoteker/pesanan/{id}/tolak</td><td>@rejectPayment</td><td>Tolak &amp; kembalikan stok</td></tr>
            <tr><td>GET</td><td>/pelanggan/cart</td><td>Pelanggan\CartController@index</td><td>Lihat keranjang belanja</td></tr>
            <tr><td>POST</td><td>/pelanggan/cart/add/{obat}</td><td>@add</td><td>Tambah item ke keranjang</td></tr>
            <tr><td>GET/POST</td><td>/pelanggan/checkout</td><td>@checkout / @processCheckout</td><td>Proses pesanan</td></tr>
            <tr><td>POST</td><td>/pelanggan/pesanan/{id}/bayar</td><td>@uploadBuktiPembayaran</td><td>Upload bukti transfer</td></tr>
        </tbody>
    </table>
</div>

{{-- ===== SECTION 7: FITUR PER ROLE ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">7. Fitur Per Role</span>
    </div>

    <h1>7. Fitur Per Role</h1>

    <h2>7.1 Role Admin</h2>

    <h3>a. Dashboard Analitik</h3>
    <table>
        <thead><tr><th>Komponen Dashboard</th><th>Sumber Data</th><th>Keterangan</th></tr></thead>
        <tbody>
            <tr><td>Statistik Ringkas</td><td>COUNT(*) dari tabel relevan</td><td>Total obat, pelanggan, apoteker, suplier</td></tr>
            <tr><td>Obat Hampir Kadaluarsa</td><td>WHERE tgl_kadaluarsa BETWEEN now, now+30d</td><td>Alert merah untuk stok yang perlu perhatian</td></tr>
            <tr><td>Obat Stok Rendah</td><td>WHERE stok &lt; 10</td><td>Alert kuning untuk pemantauan</td></tr>
            <tr><td>Grafik 7 Hari</td><td>GROUP BY DATE(tgl_nota) + Chart.js</td><td>Visualisasi tren penjualan</td></tr>
            <tr><td>Top 5 Obat Terlaris</td><td>withCount SUM(jumlah) penjualan_details</td><td>Obat dengan total penjualan tertinggi</td></tr>
            <tr><td>Transaksi Terbaru</td><td>LIMIT 6 orderBy tgl_nota DESC</td><td>Preview 6 transaksi paling baru</td></tr>
        </tbody>
    </table>

    <h3>b. Manajemen Master Data Obat</h3>
    <ul>
        <li><strong>Tambah Obat Baru</strong>: Mendaftarkan jenis obat (nama, kategori, satuan, harga jual, supplier, foto). Stok awal = 0, harga_beli = 0 (read-only di form).</li>
        <li><strong>Edit Obat</strong>: Kolom <code>stok</code> dan <code>harga_beli</code> bersifat readonly — hanya bisa berubah via transaksi.</li>
        <li><strong>Detail Obat</strong>: Menampilkan riwayat restok dari <code>pembelian_details</code>.</li>
        <li><strong>Monitor Kadaluarsa</strong>: Filter obat yang sudah/hampir kadaluarsa.</li>
    </ul>

    <h3>c. Laporan Penjualan dengan Ekspor</h3>
    <ul>
        <li>Filter rentang tanggal, statistik total pendapatan, jumlah transaksi.</li>
        <li><strong>Unduh PDF</strong>: Via DomPDF (tidak membuka print dialog browser).</li>
        <li><strong>Ekspor Excel/CSV</strong>: Streaming download dengan UTF-8 BOM agar kompatibel dengan Excel.</li>
    </ul>

    <h3>d. Restok Obat (Purchase Order)</h3>
    <ul>
        <li>Input PO baru dengan baris item dinamis.</li>
        <li><strong>Filter obat berbasis supplier</strong>: Dropdown obat hanya menampilkan obat dari supplier yang dipilih.</li>
        <li>Setelah disimpan: stok bertambah &amp; <code>harga_beli</code> obat diperbarui.</li>
        <li>Laporan restok terpisah dengan statistik &amp; ekspor PDF/Excel.</li>
    </ul>

    <h2>7.2 Role Apoteker</h2>

    <h3>a. Penjualan Offline (Walk-In)</h3>
    <ul>
        <li>Form transaksi dengan item dinamis (tambah/hapus baris).</li>
        <li>Pelanggan bersifat <strong>opsional</strong> (bisa NULL untuk pelanggan umum).</li>
        <li>Status pembayaran otomatis = <span class="badge badge-green">lunas</span> untuk semua transaksi offline.</li>
        <li>Validasi stok di sisi server; transaksi dibatalkan jika stok tidak cukup.</li>
        <li>Stok berkurang otomatis dalam DB transaction setelah tersimpan.</li>
    </ul>

    <h3>b. Pesanan Online (Verifikasi &amp; Konfirmasi)</h3>
    <ul>
        <li>Melihat semua pesanan online dengan filter status pembayaran.</li>
        <li><strong>Notifikasi Badge</strong>: Angka merah di navbar menunjukkan pesanan <span class="badge badge-orange">menunggu_konfirmasi</span>.</li>
        <li>Detail pesanan: info pelanggan, alamat, obat dipesan, foto bukti transfer.</li>
        <li><strong>Konfirmasi</strong>: Status → lunas. Apoteker dicatat sebagai <code>id_user</code>.</li>
        <li><strong>Tolak</strong>: Status → dibatalkan &amp; stok semua item dikembalikan otomatis.</li>
    </ul>

    <h2>7.3 Role Pelanggan</h2>

    <h3>a. Keranjang Belanja (Session Cart)</h3>
    <ul>
        <li>Tambah obat ke keranjang dengan validasi stok real-time.</li>
        <li>Update kuantitas dengan batas maksimum sesuai stok aktual.</li>
        <li>Keranjang disimpan di <strong>Laravel Session</strong> (bukan database).</li>
    </ul>

    <h3>b. Checkout &amp; Upload Bukti Bayar</h3>
    <ul>
        <li>Input alamat pengiriman (wajib).</li>
        <li>Stok dikurangi saat checkout (reserved stock) untuk mencegah double order.</li>
        <li>Pesanan dibuat dengan status <span class="badge badge-orange">belum_bayar</span>.</li>
        <li>Upload bukti transfer → status → <span class="badge badge-orange">menunggu_konfirmasi</span>.</li>
    </ul>
</div>

{{-- ===== SECTION 8: FLOWCHARTS ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">8. Alur Utama (Flowchart)</span>
    </div>

    <h1>8. Alur Utama (Flowchart)</h1>

    <h2>8.1 Alur Penjualan Offline (Apoteker)</h2>
    <div class="flow-box">[ Apoteker Login ]
       |
       v
[ Buka Menu: Penjualan Offline → Tambah Penjualan ]
       |
       v
[ Form: Tanggal, Pelanggan (Opsional), Item Obat & Qty ]
       |
       v
[ Submit Form ]
       |
       v
[ Validasi Server (PHP) ]
  |                |
  v                v
[GAGAL]         [SUKSES]
  |                |
  v                v
[Kembali ke    [DB::transaction()]
 Form + Error]   |
                 +-- Buat Penjualan (status: lunas, id_user: apoteker)
                 +-- Loop tiap item:
                 |    ├─ Cek stok cukup? → jika tidak: throw Exception
                 |    ├─ Buat PenjualanDetail
                 |    └─ Obat::decrement('stok', jumlah)
                 |
                 v
         [ Redirect → Daftar Penjualan + Sukses ]</div>

    <h2>8.2 Alur Pembelian Online (Pelanggan)</h2>
    <div class="flow-box">[ Pelanggan Login ] → [ Browsing Katalog Obat (status=aktif) ]
       |
       v
[ Klik "Tambah ke Keranjang" ]
  |
  v
[ Validasi: Stok tersedia? ]
  |               |
  v               v
[TIDAK]         [YA]
  |               |
  v               v
[Error]   [ Item → Session Cart ]
                  |
                  v
          [ Lihat Keranjang → Update Qty / Hapus Item ]
                  |
                  v
          [ Lanjut ke Checkout ]
          ├─ Tampil ringkasan + total harga
          └─ Input Alamat Pengiriman (wajib)
                  |
                  v
          [ Submit Checkout ]
                  |
                  v
          [ DB::transaction() ]
          ├─ Generate Nota PJL-XXXXX
          ├─ Buat Penjualan (status: belum_bayar, alamat_kirim terisi)
          ├─ Loop tiap item di Cart:
          |   ├─ Cek stok final (server-side)
          |   ├─ Buat PenjualanDetail
          |   └─ Obat::decrement('stok', jumlah) ← Reserved Stock
          └─ Session Cart dikosongkan
                  |
                  v
          [ Redirect → Detail Pesanan ]
                  |
                  v
          [ Upload Bukti Transfer (JPG/PNG < 2MB) ]
                  |
                  v
          [ Status → menunggu_konfirmasi ] → Tunggu Apoteker</div>

    <h2>8.3 Alur Konfirmasi Pesanan Online (Apoteker)</h2>
    <div class="flow-box">[ Apoteker melihat notifikasi badge merah di navbar ]
       |
       v
[ Buka Menu: Pesanan Online → Filter Status ]
       |
       v
[ Buka Detail Pesanan ]
├─ Nama pelanggan, alamat kirim, daftar obat
└─ Foto bukti pembayaran yang diunggah pelanggan
       |
       +───────────────────────────────+
       |                               |
       v                               v
[ KONFIRMASI Pembayaran ]    [ TOLAK / BATALKAN ]
       |                               |
       v                               v
[ Update status → lunas ]   [ DB::transaction() ]
[ id_user = apoteker ]      ├─ Loop PenjualanDetail:
       |                    |   └─ Obat::increment(stok, jumlah)
       v                    └─ Status → dibatalkan
[ Obat Siap Dikirim ]               |
                                    v
                          [ Stok Dikembalikan ke Sistem ]</div>

    <h2>8.4 Alur Restok Obat (Admin/Apoteker)</h2>
    <div class="flow-box">[ Admin/Apoteker Login ]
       |
       v
[ Buka Restok Obat → Tambah PO Baru ]
       |
       v
[ Form PO: Nota auto-generated (PO-XXXXX) ]
├─ Pilih Tanggal
└─ Pilih SUPPLIER ← wajib
       |
       v (JavaScript di browser)
[ Filter Obat Dinamis ]
└─ Hanya obat dengan kd_suplier = supplier terpilih tampil
   (jika supplier diubah → konfirmasi reset baris)
       |
       v
[ Tambah Baris Item: Obat, Harga Beli, Qty Masuk ]
       |
       v
[ Submit Form ]
       |
       v
[ DB::transaction() ]
├─ Generate Nota PO
├─ Buat record Pembelian
└─ Loop tiap item:
   ├─ Buat PembelianDetail (simpan harga_beli per baris)
   ├─ Obat::increment('stok', jumlah)  ← Tambah Stok
   └─ Obat::update(['harga_beli' => harga])  ← Update HPP
       |
       v
[ Redirect → Daftar PO + Sukses ]</div>
</div>

{{-- ===== SECTION 9: DETAIL TEKNIS ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">9. Mekanisme Fitur Utama (Detail Teknis &amp; Code)</span>
    </div>

    <h1>9. Mekanisme Fitur Utama</h1>

    <h2>9.1 Sistem Keranjang Belanja (Session Cart)</h2>
    <p><strong>File</strong>: <code>app/Http/Controllers/Pelanggan/CartController.php</code></p>
    <p>Keranjang menggunakan Laravel Session (server-side) — tidak disimpan ke database, bertahan selama sesi browser aktif.</p>

    <h3>Struktur Data Session Cart:</h3>
    <div class="code-block">// session('cart') = array asosiatif berindeks kd_obat:
[
    'OBT-001' => [
        'kd_obat'    => 'OBT-001',
        'nm_obat'    => 'Paracetamol',
        'harga_jual' => 5000.0,
        'jumlah'     => 3,
        'satuan'     => 'Strip',
        'foto_obat'  => 'obats/paracetamol.jpg',
        'stok'       => 100,    // stok saat ditambahkan
    ],
    'OBT-002' => [...],
]</div>

    <h3>Tambah ke Keranjang:</h3>
    <div class="code-block">public function add(Request $request, Obat $obat): RedirectResponse {
    $qty = (int) $request->input('jumlah', 1);

    // 1. Validasi stok di database (bukan dari session!)
    if ($obat->stok < $qty) {
        return back()->with('error', "Stok tidak mencukupi.");
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$obat->kd_obat])) {
        // Item sudah ada: validasi total qty tidak melebihi stok
        $newQty = $cart[$obat->kd_obat]['jumlah'] + $qty;
        if ($obat->stok < $newQty) { /* return error */ }
        $cart[$obat->kd_obat]['jumlah'] = $newQty;
    } else {
        // Item baru: simpan ke session
        $cart[$obat->kd_obat] = [...];
    }

    session()->put('cart', $cart);
}</div>

    <h3>Proses Checkout:</h3>
    <div class="code-block">DB::transaction(function () use ($request, $cart, $pelanggan, $nota) {
    // 1. Buat header Penjualan
    $penjualan = Penjualan::create([
        'nota'              => $nota,
        'alamat_kirim'      => $request->alamat_kirim,  // ← menandai ini pesanan online
        'status_pembayaran' => 'belum_bayar',
        'kd_pelanggan'      => $pelanggan->kd_pelanggan,
        'id_user'           => null,  // belum diproses apoteker
    ]);

    foreach ($cart as $item) {
        $obat = Obat::findOrFail($item['kd_obat']);

        // 2. Re-check stok (final validation server-side)
        if ($obat->stok < $item['jumlah']) {
            throw new \Exception("Stok {$obat->nm_obat} tidak mencukupi.");
        }

        // 3. Simpan detail item
        PenjualanDetail::create(['nota' => $nota, 'kd_obat' => ..., 'jumlah' => ...]);

        // 4. Kurangi stok SEKARANG (reserved stock)
        $obat->decrement('stok', $item['jumlah']);
    }
});
session()->forget('cart');  // Kosongkan keranjang</div>

    <div class="callout warning">
        <strong>⚠️ Kenapa stok dikurangi saat checkout, bukan saat konfirmasi?</strong><br>
        Untuk mencegah dua pelanggan memesan barang yang sama secara bersamaan (race condition). Stok yang sudah direservasi akan dikembalikan jika apoteker menolak pesanan.
    </div>

    <h2>9.2 Manajemen Stok Otomatis</h2>
    <table>
        <thead><tr><th>Event Transaksi</th><th>Efek pada Stok</th><th>File Controller</th></tr></thead>
        <tbody>
            <tr><td>Checkout oleh Pelanggan</td><td>Berkurang (reserved)</td><td>Pelanggan\CartController@processCheckout</td></tr>
            <tr><td>Penjualan Offline oleh Apoteker</td><td>Berkurang</td><td>Apoteker\PenjualanController@store</td></tr>
            <tr><td>Konfirmasi Pesanan Online</td><td>Tidak berubah</td><td>Apoteker\PenjualanController@confirmPayment</td></tr>
            <tr><td>Penolakan/Pembatalan Pesanan Online</td><td>Bertambah (dikembalikan)</td><td>Apoteker\PenjualanController@rejectPayment</td></tr>
            <tr><td>Restok / Purchase Order (PO)</td><td>Bertambah</td><td>Admin\PembelianController@store</td></tr>
        </tbody>
    </table>

    <h3>Pengembalian Stok saat Pesanan Ditolak:</h3>
    <div class="code-block">public function rejectPayment(Penjualan $penjualan): RedirectResponse {
    DB::transaction(function () use ($penjualan) {
        if ($penjualan->status_pembayaran !== 'dibatalkan') {
            // Loop semua item dan kembalikan stok
            foreach ($penjualan->details as $detail) {
                $obat = Obat::findOrFail($detail->kd_obat);
                $obat->increment('stok', $detail->jumlah);  // ← kembalikan
            }
        }
        $penjualan->update(['status_pembayaran' => 'dibatalkan', ...]);
    });
}</div>

    <h2>9.3 Laporan &amp; Ekspor PDF/Excel</h2>

    <h3>Ekspor PDF (DomPDF):</h3>
    <div class="code-block">use Barryvdh\DomPDF\Facade\Pdf;

public function downloadPdf(Request $request) {
    $penjualans = Penjualan::with([...])->get();

    // Load view khusus PDF (CSS minimal, tabel bersih)
    $pdf = Pdf::loadView('admin.penjualan.pdf', compact('penjualans', ...));

    // Trigger file download (tidak membuka print dialog browser)
    return $pdf->download('laporan_penjualan_' . now()->format('Ymd') . '.pdf');
}</div>

    <h3>Ekspor Excel/CSV:</h3>
    <div class="code-block">public function exportExcel(Request $request) {
    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="laporan.csv"',
    ];

    $callback = function() use ($penjualans) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel

        fputcsv($file, ['No. Nota', 'Tanggal', 'Pelanggan', 'Total', ...]);
        foreach ($penjualans as $p) {
            fputcsv($file, [$p->nota, $p->tgl_nota, ..., $p->total]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}</div>

    <h2>9.4 Filter Obat Berbasis Supplier (Vanilla JS)</h2>
    <p><strong>File</strong>: <code>resources/views/admin/pembelian/create.blade.php</code></p>
    <div class="code-block">// Data obat dari Controller dikirim ke JS via Blade:
// const allObats = &#64;json($obats->map(fn($o) => [...]));
// (Di template Blade nyata, gunakan: &#64;json() directive)

const obats = [
  { kd_obat: 'OBT-001', nm_obat: 'Paracetamol', harga_beli: 5000, stok: 100, kd_suplier: 'SUP-001' },
  { kd_obat: 'OBT-002', nm_obat: 'Amoxicillin',  harga_beli: 8000, stok: 50,  kd_suplier: 'SUP-002' },
  // ... semua obat dari database
];

// Saat supplier dipilih, filter obat:
supplierSelect.addEventListener('change', (e) => {
    const supplierBaru = e.target.value;
    if (rowCount > 0 && previousSupplier !== "") {
        if (!confirm("Mengubah supplier akan mereset daftar obat. Lanjutkan?")) {
            e.target.value = previousSupplier;  // batalkan perubahan
            return;
        }
    }
    previousSupplier = supplierBaru;
    resetTable();  // hapus semua baris
    addRow();      // tambah baris baru dengan filter supplier baru
});

// Fungsi addRow: filter obat berdasarkan supplier terpilih:
function addRow() {
    const selectedSupplier = supplierSelect.value;
    const filteredObats = obats.filter(o => o.kd_suplier === selectedSupplier);
    // Populate dropdown dengan obat yang terfilter saja
}</div>

    <h2>9.5 Notifikasi Pesanan Online di Navbar</h2>
    <p><strong>File</strong>: <code>resources/views/layouts/apoteker.blade.php</code></p>
    <div class="code-block">&#64;php
    // Hitung pesanan menunggu konfirmasi (dieksekusi di Blade layout)
    $pendingOnlineOrders = \App\Models\Penjualan::whereNotNull('alamat_kirim')
        ->where('status_pembayaran', 'menunggu_konfirmasi')
        ->count();
&#64;endphp

&lt;!-- Tampilkan badge di menu sidebar --&gt;
&lt;a href="&#123;&#123; route('apoteker.penjualan.online') &#125;&#125;"&gt;
    Pesanan Online
    &#64;if($pendingOnlineOrders &gt; 0)
        &lt;span class="badge bg-danger"&gt;&#123;&#123; $pendingOnlineOrders &#125;&#125;&lt;/span&gt;
    &#64;endif
&lt;/a&gt;</div>
</div>

{{-- ===== SECTION 10 & 11 ===== --}}
<div class="page page-break">
    <div class="page-header">
        <span class="page-header-logo">💊 Apotek Digital</span>
        <span class="page-header-title">10. Nota &amp; 11. Prinsip Desain</span>
    </div>

    <h1>10. Konvensi Penomoran Nota Otomatis</h1>
    <p>Semua nomor nota dibuat otomatis oleh controller menggunakan pola <strong>prefix + angka berurutan 5 digit</strong>:</p>

    <table>
        <thead><tr><th>Jenis Dokumen</th><th>Format</th><th>Contoh</th><th>Metode Generator</th></tr></thead>
        <tbody>
            <tr><td>Transaksi Penjualan</td><td>PJL-XXXXX</td><td>PJL-00001</td><td>Logic di CartController / Apoteker\PenjualanController</td></tr>
            <tr><td>Purchase Order Restok</td><td>PO-XXXXX</td><td>PO-00001</td><td><code>Pembelian::generateNota()</code> (static method di Model)</td></tr>
            <tr><td>Kode Obat</td><td>OBT-XXX</td><td>OBT-001</td><td><code>Obat::generateKdObat()</code> (static method di Model)</td></tr>
        </tbody>
    </table>

    <h3>Mekanisme Generate Nota (Contoh Penjualan):</h3>
    <div class="code-block">// Ambil nomor terakhir menggunakan CAST untuk sorting numerik yang benar:
$lastNota = Penjualan::orderByRaw('CAST(SUBSTRING(nota, 5) AS UNSIGNED) DESC')->first();
$nextNum  = $lastNota ? ((int) substr($lastNota->nota, 4)) + 1 : 1;
$nota     = 'PJL-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
// Hasil: PJL-00001 → PJL-00002 → ... → PJL-01000</div>

    <h3>Mekanisme Generate Nota PO (via Static Method di Model):</h3>
    <div class="code-block">// app/Models/Pembelian.php
public static function generateNota(): string {
    $lastPO  = self::orderByRaw('CAST(SUBSTRING(nota, 4) AS UNSIGNED) DESC')->first();
    $nextNum = $lastPO ? ((int) substr($lastPO->nota, 3)) + 1 : 1;
    return 'PO-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
}</div>

    <div class="callout info">
        <strong>ℹ️ Penting:</strong> Semua generate nota dilakukan <strong>di dalam DB::transaction()</strong> untuk menghindari race condition jika ada dua transaksi yang terjadi bersamaan pada waktu yang sangat berdekatan.
    </div>

    <hr class="section-divider">

    <h1>11. Ringkasan Prinsip Desain Sistem</h1>

    <table>
        <thead><tr><th>Prinsip</th><th>Implementasi</th><th>Manfaat</th></tr></thead>
        <tbody>
            <tr>
                <td><strong>Separation of Concerns</strong></td>
                <td>Master data obat (harga jual, info) terpisah dari inventaris (stok, harga beli)</td>
                <td>Stok hanya berubah via transaksi; ada audit trail penuh</td>
            </tr>
            <tr>
                <td><strong>Single Source of Truth</strong></td>
                <td>Penambahan stok HANYA melalui Pembelian/Restok</td>
                <td>Setiap perubahan stok memiliki bukti anggaran (PO)</td>
            </tr>
            <tr>
                <td><strong>Pessimistic Stock Reserve</strong></td>
                <td>Stok dikurangi saat checkout (bukan saat konfirmasi)</td>
                <td>Mencegah double order saat stok terbatas</td>
            </tr>
            <tr>
                <td><strong>Multi-Guard Authentication</strong></td>
                <td>Guard <code>web</code> untuk staf, guard <code>pelanggan</code> untuk konsumen</td>
                <td>Isolasi keamanan penuh antara staf dan pelanggan</td>
            </tr>
            <tr>
                <td><strong>Audit Trail</strong></td>
                <td>Field <code>id_user</code> di setiap transaksi</td>
                <td>Setiap transaksi dapat dilacak siapa yang menginputnya</td>
            </tr>
            <tr>
                <td><strong>Supplier Consistency</strong></td>
                <td>Obat memiliki supplier default; restok difilter berdasarkan supplier</td>
                <td>Konsistensi data harga beli dan hubungan suplier</td>
            </tr>
            <tr>
                <td><strong>Database Transaction</strong></td>
                <td>Semua operasi multi-tabel dibungkus DB::transaction()</td>
                <td>Data selalu konsisten meski terjadi error di tengah proses</td></tr>
        </tbody>
    </table>

    <div class="callout success">
        <strong>✅ Hasil Akhir:</strong> Sistem ini mampu menangani seluruh siklus operasional apotek — dari pengadaan barang (PO ke supplier), manajemen stok, penjualan offline (walk-in), penjualan online (e-commerce sederhana), hingga pelaporan keuangan dan analitik — dalam satu platform yang terintegrasi.
    </div>

    <div class="footer">
        Dokumentasi Teknis Sistem Informasi Apotek Digital &nbsp;|&nbsp; Generated: {{ now()->format('d F Y H:i') }} &nbsp;|&nbsp; Laravel 12 + PHP 8.2
    </div>
</div>

</body>
</html>
