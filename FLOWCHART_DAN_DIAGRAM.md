# 📊 FLOWCHART & DIAGRAM VISUAL - UJIKOM APOTEK APP

---

## DAFTAR ISI
1. [System Architecture Diagram](#1-system-architecture-diagram)
2. [User Role Flow](#2-user-role-flow)
3. [Authentication System](#3-authentication-system)
4. [Inventory Flow](#4-inventory-flow)
5. [Sales Flow](#5-sales-flow)
6. [Online Ordering Flow](#6-online-ordering-flow)
7. [Restocking Flow](#7-restocking-flow)
8. [Payment Verification Flow](#8-payment-verification-flow)
9. [Database Relationship Diagram](#9-database-relationship-diagram)
10. [State Diagram - Penjualan Status](#10-state-diagram---penjualan-status)

---

## 1. SYSTEM ARCHITECTURE DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                      USER PRESENTATION LAYER                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │   ADMIN      │  │  APOTEKER    │  │ PELANGGAN    │          │
│  │   PORTAL     │  │  PORTAL      │  │ PORTAL       │          │
│  │              │  │              │  │              │          │
│  │ • Dashboard  │  │ • Dashboard  │  │ • Dashboard  │          │
│  │ • Inventory  │  │ • Penjualan  │  │ • Catalog    │          │
│  │ • Suppliers  │  │ • Pembelian  │  │ • Cart       │          │
│  │ • Reports    │  │ • Online Ord │  │ • Checkout   │          │
│  │ • Staff Mgmt │  │ • Expired    │  │ • Orders     │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
│         │                  │                 │                   │
└─────────┼──────────────────┼─────────────────┼───────────────────┘
          │                  │                 │
          │                  │                 │
┌─────────▼──────────────────▼─────────────────▼───────────────────┐
│                   ROUTING & MIDDLEWARE LAYER                      │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐   │
│  │ Guard: web       │  │ Guard: web       │  │ Guard:       │   │
│  │ Role: admin      │  │ Role: apoteker   │  │ pelanggan    │   │
│  │                  │  │                  │  │              │   │
│  │ /admin/*         │  │ /apoteker/*      │  │ /pelanggan/* │   │
│  │                  │  │                  │  │              │   │
│  │ Middleware:      │  │ Middleware:      │  │ Middleware:  │   │
│  │ - auth:web       │  │ - auth:web       │  │ - pelanggan. │   │
│  │ - role:admin     │  │ - role:apoteker  │  │   auth       │   │
│  └──────────────────┘  └──────────────────┘  └──────────────┘   │
│                                                                  │
└───────┬────────────────────────┬─────────────────────────┬──────┘
        │                        │                         │
        │                        │                         │
┌───────▼────────────────────────▼─────────────────────────▼──────┐
│                  CONTROLLER LAYER (Business Logic)               │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│ Admin Controllers:    Apoteker Controllers:  Pelanggan Controllers│
│ - ObatController      - PenjualanController  - ObatController     │
│ - SuplierController   - PembelianController  - CartController     │
│ - PelangganController - ObatController       - PenjualanController│
│ - ApotekerController  - DashboardController                       │
│ - PenjualanController                                             │
│ - PembelianController                                             │
│                                                                  │
└───────┬────────────────────────┬─────────────────────────┬──────┘
        │                        │                         │
        │                        │                         │
┌───────▼────────────────────────▼─────────────────────────▼──────┐
│                      MODEL & DATA LAYER                          │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  User              Obat               Penjualan    Pembelian    │
│  AkunPelanggan     KategoriObat       PenjualanDtl PembelianDtl │
│  Pelanggan         Suplier                                       │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │           Eloquent ORM - Relationships                 │    │
│  │  (hasMany, belongsTo, hasOne, etc)                     │    │
│  └────────────────────────────────────────────────────────┘    │
│                                                                  │
└───────┬────────────────────────┬─────────────────────────┬──────┘
        │                        │                         │
        │                        │                         │
┌───────▼────────────────────────▼─────────────────────────▼──────┐
│                     DATABASE LAYER                               │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  MySQL/SQLite Database                                          │
│  ├── users               ├── kategori_obats                      │
│  ├── akun_pelanggans     ├── obats                               │
│  ├── pelanggans          ├── supliers                            │
│  ├── penjualans          ├── pembelians                          │
│  └── penjualan_details   └── pembelian_details                   │
│                                                                  │
│  ┌──────────────────────────────────────────────────────┐       │
│  │     Foreign Keys & Constraints untuk Data Integrity  │       │
│  └──────────────────────────────────────────────────────┘       │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│              SUPPORTING COMPONENTS                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Session (Cart)     File Storage      Email/Notification       │
│  └── Session cache  └── bukti_pembayaran  └── Laravel Mail    │
│                     └── obat_fotos                             │
│                                                                 │
│  DOMPDF             Validation         Error Handling          │
│  └── PDF Reports    └── Form Request   └── Exception Handling  │
│                     └── Rules          └── Logging             │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. USER ROLE FLOW

```
                    ┌─────────────────────┐
                    │   VISIT APOTEK      │
                    │   WEBSITE           │
                    └──────────┬──────────┘
                               │
                    ┌──────────▼──────────┐
                    │   NOT LOGGED IN?    │
                    └──────────┬──────────┘
                               │
                ┌──────────────┴──────────────┐
                │                             │
                ▼                             ▼
    ┌───────────────────────┐      ┌──────────────────────┐
    │  LOGIN / REGISTER     │      │  LOGIN ADMIN/APOTEK  │
    │  (Pelanggan)          │      │  (Staff)             │
    │                       │      │                      │
    │ Guard: pelanggan      │      │ Guard: web           │
    │ Model: AkunPelanggan  │      │ Model: User          │
    │                       │      │                      │
    └───────────┬───────────┘      └──────────┬───────────┘
                │                            │
                ▼                            ▼
    ┌───────────────────────┐      ┌──────────────────────┐
    │ PELANGGAN DASHBOARD   │      │  CHECK USER ROLE     │
    │                       │      │                      │
    │ - View Catalog        │      │ role = 'admin'?      │
    │ - Add to Cart         │      │ role = 'apoteker'?   │
    │ - Checkout            │      └──────────┬───────────┘
    │ - Upload Bukti        │                 │
    │ - View Pesanan        │     ┌───────────┴────────────┐
    │                       │     │                        │
    └───────────────────────┘     ▼                        ▼
                        ┌──────────────────────┐  ┌──────────────────┐
                        │   ADMIN DASHBOARD    │  │ APOTEKER DASH.   │
                        │                      │  │                  │
                        │ • Inventory CRUD     │  │ • Input Penjualan│
                        │ • Supplier CRUD      │  │ • Input Pembelian│
                        │ • Staff Management   │  │ • Verify Payment │
                        │ • View Reports       │  │ • Manage Expired │
                        │ • Monitor Sales      │  │ • View Sales     │
                        │ • Monitor Purchase   │  │ • View Purchase  │
                        │                      │  │                  │
                        └──────────────────────┘  └──────────────────┘
```

---

## 3. AUTHENTICATION SYSTEM

```
LOGIN ATTEMPTS
        │
        ├─ Admin/Apoteker
        │       │
        │       ▼
        │  ┌────────────────────────────┐
        │  │ POST /login                │
        │  │ (Breeze Auth)              │
        │  └────────────┬───────────────┘
        │               │
        │               ▼
        │  ┌────────────────────────────────────┐
        │  │ Check credentials in users table   │
        │  │ Guard: web                         │
        │  │ Query: users WHERE email = ?       │
        │  └────────────┬───────────────────────┘
        │               │
        │     ┌─────────┴────────┐
        │     │ Valid           │ Invalid
        │     ▼                 ▼
        │  ┌──────────┐  ┌─────────────────┐
        │  │ Hash &   │  │ Redirect to     │
        │  │ Verify   │  │ login with error│
        │  │ password │  └─────────────────┘
        │  └────┬─────┘
        │       │ ✓ Match
        │       ▼
        │  ┌─────────────────────┐
        │  │ Create session:     │
        │  │ Guard = web         │
        │  │ Auth::login($user)  │
        │  └────────┬────────────┘
        │           │
        │           ▼
        │  ┌──────────────────────┐
        │  │ Check role:          │
        │  │ if role='admin'      │
        │  │   → /admin/dashboard │
        │  │ if role='apoteker'   │
        │  │   → /apoteker/dash   │
        │  └──────────────────────┘
        │
        └─ Pelanggan (Customer)
                │
                ▼
           ┌────────────────────────────┐
           │ POST /pelanggan/login      │
           │ (Custom or Breeze)         │
           └────────────┬───────────────┘
                        │
                        ▼
           ┌─────────────────────────────────────┐
           │ Check credentials in akun_pelanggans│
           │ Guard: pelanggan                    │
           │ Query: akun_pelanggans              │
           │        WHERE email = ?              │
           └────────────┬──────────────────────┘
                        │
              ┌─────────┴────────┐
              │ Valid           │ Invalid
              ▼                 ▼
           ┌──────────┐  ┌─────────────────┐
           │ Verify   │  │ Redirect with   │
           │ password │  │ error           │
           └────┬─────┘  └─────────────────┘
                │ ✓ Match
                ▼
           ┌──────────────────────┐
           │ Create session:      │
           │ Guard = pelanggan    │
           │ Auth::guard('pelang  │
           │  gan')->login($cust) │
           └────────┬─────────────┘
                    │
                    ▼
           ┌──────────────────────┐
           │ Redirect to:         │
           │ /pelanggan/dashboard │
           └──────────────────────┘

LOGGED-IN STATE
        │
        ├─ For protected routes:
        │  Example: /admin/obat
        │       │
        │       ▼
        │  ┌───────────────────────────────┐
        │  │ Middleware check:             │
        │  │ - auth:web                    │
        │  │ - role:admin                  │
        │  └───────────┬───────────────────┘
        │              │
        │     ┌────────┴────────┐
        │     │ Pass            │ Fail
        │     ▼                 ▼
        │  ┌─────────┐      ┌──────────┐
        │  │ Access  │      │ abort(403)│
        │  │ allowed │      │ or        │
        │  │         │      │ redirect  │
        │  └─────────┘      └──────────┘
        │
        └─ For pelanggan routes:
           Example: /pelanggan/cart
                │
                ▼
           ┌──────────────────────┐
           │ Middleware:          │
           │ pelanggan.auth       │
           └──────────┬───────────┘
                      │
             ┌────────┴────────┐
             │ Logged in       │ Not logged
             ▼                 ▼
          ┌──────────┐     ┌──────────────┐
          │ Access   │     │ Redirect to  │
          │ allowed  │     │ /pelanggan/  │
          │          │     │ login        │
          └──────────┘     └──────────────┘
```

---

## 4. INVENTORY FLOW

```
ADMIN INVENTORY MANAGEMENT

┌─────────────────────────────────────────┐
│  MASTER DATA OBAT (Inventory)           │
└─────────────┬───────────────────────────┘
              │
        ┌─────┴─────┐
        │           │
        ▼           ▼
    [CREATE]    [READ/VIEW]
        │           │
        │           ├─→ List all obat (with filter)
        │           │
        ▼           ├─→ View by kategori
        │           │
    ┌────────────┐  ├─→ View by supplier
    │ Input form:│  │
    │ • Nm_obat  │  └─→ View by status (aktif/nonaktif/kadaluarsa)
    │ • Kategori │
    │ • Satuan   │
    │ • Harga B  │      ┌────────────────────┐
    │ • Harga J  │  ┌───│ CHECK KADALUARSA   │
    │ • Tgl Exp  │  │   └────────────────────┘
    │ • Supplier │  │          │
    │ • Foto     │  │   ┌──────▼────────┐
    │            │  │   │ If tgl_exp ≤  │
    │ Generate:  │  │   │ hari ini      │
    │ • Kd_obat  │  │   │               │
    │ • Stok = 0 │  │   │ Show in list  │
    │ • Status=  │  │   │ "kadaluarsa"  │
    │   aktif    │  │   │               │
    └──────┬─────┘  │   ├─ Mark as exp  │
           │        │   │   (update     │
           │        │   │    status)    │
           ▼        │   │               │
        INSERT      │   ├─ Delete (soft)│
        DB          │   │               │
           │        │   └───────────────┘
           │        │
           ▼        │
      ┌──────────┐  │
      │ Success  │  │
      │ Redirect │  │
      │ list     │  │
      └──────────┘  │
                    │
                    ▼
            ┌──────────────┐
            │[UPDATE/EDIT] │
            ├──────────────┤
            │ Edit:        │
            │ • Nm_obat    │
            │ • Kategori   │
            │ • Harga B    │
            │ • Harga J    │
            │ • Tgl Exp    │
            │ • Foto       │
            │              │
            │ NOT Edit:    │
            │ • Kd_obat    │
            │ • Stok       │
            │              │
            │ (Stok update │
            │  via pemb/   │
            │  penjualan)  │
            │              │
            └──────┬───────┘
                   │
                   ▼
              UPDATE DB
                   │
                   ▼
            ┌──────────────┐
            │   Success    │
            └──────────────┘

STOK UPDATE FLOW

        ┌────────────────────┐
        │ PENJUALAN          │
        │ (Sales)            │
        └────────┬───────────┘
                 │
                 ▼
        ┌────────────────────┐
        │ Stok BERKURANG      │
        │ obat->decrement()   │
        └────────────────────┘

        ┌────────────────────┐
        │ PEMBELIAN          │
        │ (Restocking)       │
        └────────┬───────────┘
                 │
                 ▼
        ┌────────────────────┐
        │ Stok BERTAMBAH      │
        │ obat->increment()   │
        └────────────────────┘
```

---

## 5. SALES FLOW

```
APOTEKER INPUT PENJUALAN (RETAIL)

START
  │
  ▼
┌──────────────────────────────────┐
│ /apoteker/penjualan/create       │
└──────────────┬───────────────────┘
               │
               ▼
        ┌─────────────────┐
        │  Form tampil:   │
        │ 1. Tgl Nota     │
        │ 2. Pelanggan    │
        │ 3. Items table  │
        │ 4. Diskon       │
        │ 5. Total        │
        └────────┬────────┘
                 │
                 ▼ Input data & submit
        ┌──────────────────┐
        │ POST /apoteker/  │
        │ penjualan        │
        └────────┬─────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ SERVER VALIDATION    │
        │ Check:               │
        │ • tgl_nota date OK?  │
        │ • items not empty?   │
        │ • kd_obat exists?    │
        │ • jumlah > 0?        │
        │ • stok cukup?        │
        └────────┬─────────────┘
                 │
         ┌───────┴────────┐
         │ Pass           │ Fail
         ▼                ▼
    ┌─────────┐      ┌─────────────┐
    │DB Trans │      │Return form  │
    │action   │      │with errors  │
    └────┬────┘      └─────────────┘
         │
         │ Transaction begin
         │
         ▼
    ┌──────────────────┐
    │1. Generate Nota  │
    │   PJL-XXXXX      │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │2. Create         │
    │   Penjualan      │
    │   • nota         │
    │   • tgl_nota     │
    │   • kd_pelanggan │
    │   • id_user      │
    │   • diskon       │
    │   • status=lunas │
    │   (retail)       │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │3. For each item: │
    │   a. Create      │
    │      Detail      │
    │   b. Decrement   │
    │      stok        │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ If error:        │
    │ ROLLBACK all     │
    │ changes          │
    └────┬─────────────┘
         │ Success
         ▼
    ┌──────────────────┐
    │ COMMIT trans     │
    │                  │
    │ Penjualan saved  │
    │ Stok updated     │
    └────────┬─────────┘
             │
             ▼
    ┌──────────────────┐
    │ Redirect to      │
    │ penjualan.show   │
    │ (print invoice)  │
    └──────────────────┘

END
```

---

## 6. ONLINE ORDERING FLOW

```
PELANGGAN ONLINE ORDERING COMPLETE FLOW

START (Customer not logged in)
  │
  ▼
┌─────────────────────────────────┐
│ Customer ke website →            │
│ Check: Auth::guard('pelanggan')  │
│ Hasil: false (not logged)        │
└────────────────┬────────────────┘
                 │
         ┌───────┴───────┐
         │               │
         ▼               ▼
    ┌────────────┐  ┌─────────────┐
    │ Register   │  │ Login       │
    │ Create new │  │ existing    │
    │ account    │  │ account     │
    └────────┬───┘  └────────┬────┘
             │               │
             └───────┬───────┘
                     │
                     ▼
         ┌──────────────────────┐
         │ Session created      │
         │ Guard='pelanggan'    │
         │ Auth check: true     │
         └─────────┬────────────┘
                   │
                   ▼
         ┌──────────────────────┐
         │ /pelanggan/dashboard │
         │ (view obat catalog)  │
         └─────────┬────────────┘
                   │
    ┌──────────────▼──────────────┐
    │ BROWSE & SELECT MEDICINES   │
    │                             │
    │ Click obat → show detail:   │
    │ • Nama, harga              │
    │ • Stok tersedia            │
    │ • Foto, deskripsi          │
    │                             │
    │ Input qty → click           │
    │ "Add to Cart"               │
    └──────────────┬──────────────┘
                   │
    ┌──────────────▼──────────────┐
    │ POST /pelanggan/cart/add    │
    │ (CartController@add)        │
    │                             │
    │ Check:                      │
    │ • qty > 0?                  │
    │ • qty <= stok?              │
    │ • already in cart?          │
    │                             │
    │ Action:                     │
    │ • Store in session['cart']  │
    │ • Update cart count         │
    └──────────────┬──────────────┘
                   │
                   ├─→ Show cart page
                   │
    ┌──────────────▼──────────────┐
    │ REPEAT: Add more items      │
    │ OR                           │
    │ Click "Checkout"            │
    └──────────────┬──────────────┘
                   │
    ┌──────────────▼──────────────┐
    │ GET /pelanggan/checkout     │
    │                             │
    │ Display:                    │
    │ • Alamat Kirim form         │
    │ • Cart summary              │
    │ • Total price               │
    │ • Buttons: Confirm, Cancel  │
    └──────────────┬──────────────┘
                   │
                   ▼
    ┌──────────────────────────────┐
    │ POST /pelanggan/checkout     │
    │ (CartController@processCheck) │
    │                              │
    │ DB Transaction:              │
    │ 1. Generate nota (INV-...)   │
    │ 2. Create Penjualan:         │
    │    • status='belum_bayar'    │
    │ 3. Create Details            │
    │ 4. Decrement stok !!!        │
    │    (penting: stok berkurang) │
    │ 5. Clear session['cart']     │
    └──────────────┬───────────────┘
                   │
                   ▼
    ┌──────────────────────────────┐
    │ Redirect to payment upload:  │
    │ /pelanggan/pesanan/{nota}    │
    │ /bayar                       │
    └──────────────┬───────────────┘
                   │
    ┌──────────────▼───────────────┐
    │ PAYMENT PROOF UPLOAD         │
    │                              │
    │ Form:                        │
    │ • Order detail (readonly)    │
    │ • Status: "Menunggu bayar"   │
    │ • File upload:               │
    │   - Foto bukti transfer      │
    │   - Max 2MB                  │
    │                              │
    │ Click "Upload"               │
    └──────────────┬───────────────┘
                   │
    ┌──────────────▼───────────────┐
    │ POST /pelanggan/pesanan/     │
    │ {nota}/bayar                 │
    │                              │
    │ Validate file:               │
    │ • File exists?               │
    │ • Type: jpg/png/pdf?         │
    │ • Size <= 2MB?               │
    │                              │
    │ Action:                      │
    │ • Store file (storage/)      │
    │ • Update penjualan:          │
    │   bukti_pembayaran = path    │
    │   status = 'belum_confirmed' │
    └──────────────┬───────────────┘
                   │
                   ▼
    ┌──────────────────────────────┐
    │ Success page:                │
    │ "Bukti terkirim,             │
    │  tunggu verifikasi apoteker" │
    └──────────────┬───────────────┘
                   │
                   └──→ [WAIT FOR APOTEKER]
                           │
                           ▼
                   ┌────────────────────┐
                   │ APOTEKER DASHBOARD │
                   │ View: Pesanan Online│
                   │ (status pending)    │
                   └──────────┬─────────┘
                              │
                    ┌─────────┴────────┐
                    │ Confirm          │ Reject
                    ▼                  ▼
                ┌────────┐       ┌─────────────┐
                │Status: │       │Status:      │
                │confirmed       │rejected     │
                │                │ Stok revert │
                │Customer        │             │
                │notified        │ Customer    │
                │                │ notified    │
                └────────┘       └─────────────┘

END
```

---

## 7. RESTOCKING FLOW

```
APOTEKER INPUT PEMBELIAN (RESTOCKING)

START
  │
  ▼
┌─────────────────────────────────┐
│ /apoteker/pembelian/create      │
└─────────────────┬───────────────┘
                  │
                  ▼
        ┌──────────────────────┐
        │ Form:                │
        │ • Tgl Nota           │
        │ • Supplier (select)  │
        │ • Items table        │
        │ • Diskon             │
        │ • Total              │
        └─────────┬────────────┘
                  │
                  ▼
        ┌──────────────────────┐
        │ POST /apoteker/      │
        │ pembelian            │
        └─────────┬────────────┘
                  │
                  ▼
        ┌──────────────────────┐
        │ DB Transaction:      │
        │                      │
        │ 1. Generate nota     │
        │    PB-XXXXX          │
        │                      │
        │ 2. Create Pembelian  │
        │    • nota            │
        │    • tgl_nota        │
        │    • kd_suplier      │
        │    • id_user         │
        │    • diskon          │
        │                      │
        │ 3. For each item:    │
        │    a. Create Detail  │
        │    b. INCREMENT stok │
        │       (restocking!)  │
        │                      │
        │ 4. If error:         │
        │    ROLLBACK          │
        └─────────┬────────────┘
                  │
                  ▼
        ┌──────────────────────┐
        │ Redirect to show     │
        │ Pembelian saved      │
        │ Stok updated (+qty)  │
        └──────────────────────┘

END
```

---

## 8. PAYMENT VERIFICATION FLOW

```
APOTEKER VERIFY ONLINE ORDER PAYMENT

GET /apoteker/pesanan-online
  │
  ▼
┌──────────────────────────────────┐
│ Show pending online orders:      │
│ WHERE status_pembayaran IN       │
│ ('belum_bayar', 'belum_confirmed')
│                                  │
│ Display:                         │
│ • Nota, Tanggal                  │
│ • Pelanggan                      │
│ • Total                          │
│ • Status                         │
│ • Actions: View, Confirm, Reject │
└──────────────┬───────────────────┘
               │
    ┌──────────┴──────────┐
    │                     │
    ▼ Click View Detail   ▼ View bukti pembayaran
    │                     │
┌───┴──────────┐    ┌────┴────────┐
│ Order detail:│    │ Show image  │
│ • Nota       │    │ bukti transaksi
│ • Tgl        │    │ (stored in  │
│ • Pelanggan  │    │ /storage/)  │
│ • Items      │    │             │
│ • Alamat     │    │ Verify:     │
│ • Total      │    │ • Nama bank │
│              │    │ • No rekening
└──────┬───────┘    │ • Nominal   │
       │            │ • Tanggal   │
       ▼            └────┬────────┘
    ┌────────────────────┘
    │
    ▼
┌──────────────────────────────┐
│ APOTEKER DECISION:           │
│                              │
│ ✓ CONFIRMED                  │
│   (Bukti valid)              │
│                              │
│ × REJECTED                   │
│   (Bukti salah/tidak sesuai) │
│                              │
│ ? PENDING                    │
│   (Verifikasi kemudian)      │
└──────┬───────────────────────┘
       │
   ┌───┴──────┐
   │          │
   ▼ Confirm  ▼ Reject
   │          │
┌──┴────────────┐  ┌──────────────────┐
│ POST /apoteker│  │ POST /apoteker   │
│ /pesanan-  │  │ /pesanan-online/ │
│ online/{id}/  │  │ {id}/tolak       │
│ konfirmasi    │  │                  │
│               │  │ Action:          │
│ Action:       │  │ • REVERT stok    │
│ • Update      │  │   (increment)    │
│   status:     │  │ • Clear bukti    │
│   'confirmed' │  │ • Set status:    │
│ • Mark ready  │  │   'rejected'     │
│ • Notify      │  │ • Notify pelang  │
│   pelanggan   │  │   untuk bayar    │
│               │  │   ulang          │
└───────────────┘  └──────────────────┘

PELANGGAN SIDE:
        │
        ├─ Confirmed → Ready for pickup/shipping
        │            → Tidak bisa ubah/cancel
        │
        └─ Rejected  → Can re-upload bukti
                    → Atau contact support
```

---

## 9. DATABASE RELATIONSHIP DIAGRAM

```
┌──────────────────┐
│     users        │ Admin & Apoteker
├──────────────────┤
│ id (PK)          │
│ nama             │
│ email (unique)   │
│ password (hash)  │
│ role             │ → 'admin' | 'apoteker'
│ telpon           │
│ created_at       │
│ updated_at       │
└────────┬──────┬──┘
         │      │
    1:N  │      │  1:N
         │      │
         ▼      ▼
    ┌────────────────────────────┐      ┌────────────────────┐
    │  penjualans                │◄────┤ penjualan_details  │
    ├────────────────────────────┤ N:1 ├────────────────────┤
    │ nota (PK, string)          │      │ id (PK)            │
    │ tgl_nota                   │      │ nota (FK)          │
    │ kd_pelanggan (FK) ─┐       │      │ kd_obat (FK) ────┐│
    │ id_user (FK)       │       │      │ jumlah             │
    │ diskon             │       │      │ created_at         │
    │ alamat_kirim       │       │      │ updated_at         │
    │ status_pembayaran  │       │      └────────────────────┘
    │ bukti_pembayaran   │       │               │
    │ created_at         │       │               │ FK
    │ updated_at         │       │               ▼
    └────────┬───────────┘       │      ┌────────────────────┐
             │                   │      │ obats              │
             │ FK                │      ├────────────────────┤
             │                   │      │ kd_obat (PK)       │
             ▼                   │      │ nm_obat            │
    ┌──────────────────┐         │      │ id_kategori (FK)  ─┼─┐
    │ pelanggans       │         │      │ satuan             │ │
    ├──────────────────┤         │      │ harga_beli         │ │
    │ kd_pelanggan (PK)│         │      │ harga_jual         │ │
    │ nm_pelanggan     │         │      │ stok               │ │
    │ alamat           │         │      │ tgl_kadaluarsa     │ │
    │ kota             │         │      │ status             │ │
    │ telpon           │         │      │ kd_suplier (FK)  ──┼┐│
    │ created_at       │         │      │ foto_obat          │ │
    │ updated_at       │         │      │ created_at         │ │
    └────────┬──────────┘        │      │ updated_at         │ │
             │                   │      └────────┬───────────┘ │
             │ 1:1               │              │ FK            │
             │                   │              │               │
             ▼                   │      ┌───────▼──────────┐    │
    ┌──────────────────────┐     │      │kategori_obats    │    │
    │ akun_pelanggans      │     │      ├──────────────────┤    │
    │(extends Authenticatable)   │      │ id (PK)          │    │
    ├──────────────────────┤     │      │ nm_kategori      │    │
    │ id (PK)              │     │      │ created_at       │    │
    │ kd_pelanggan (FK) ───┼─────┴──┐   │ updated_at       │    │
    │ email (unique)       │        │   └──────────────────┘    │
    │ password (hash)      │        │                           │
    │ remember_token       │        │                           │
    │ created_at           │        │                           │
    │ updated_at           │        │                           │
    └──────────────────────┘        │                           │
                                    └──────────────────────────┘

    ┌──────────────────────────┐     (bersambung di bawah...)
    │ pembelians               │
    ├──────────────────────────┤
    │ nota (PK)                │
    │ tgl_nota                 │
    │ kd_suplier (FK) ─┐       │     ┌──────────────────────┐
    │ id_user (FK)     │       │     │pembelian_details     │
    │ diskon           │       │     ├──────────────────────┤
    │ created_at       │       └─────│ id (PK)              │
    │ updated_at       │             │ nota (FK)            │
    └────────┬──────────┘             │ kd_obat (FK) ───┐   │
             │                        │ jumlah          │   │
             │ FK                     │ created_at      │   │
             │                        │ updated_at      │   │
             ▼                        └────────┬────────┘   │
    ┌──────────────────┐                      │ FK         │
    │ supliers         │                      ▼            │
    ├──────────────────┤              (Points to obats)    │
    │ kd_suplier (PK)  │                      ▲            │
    │ nm_suplier       │                      │            │
    │ alamat           │                      └────────────┘
    │ telpon           │
    │ email            │
    │ created_at       │
    │ updated_at       │
    └──────────────────┘

PRIMARY KEY (PK) = Unique identifier setiap row
FOREIGN KEY (FK) = Link ke row di table lain
1:N relationship = Satu ke banyak (e.g., satu user banyak penjualan)
N:1 relationship = Banyak ke satu (e.g., banyak detail satu penjualan)
1:1 relationship = Satu ke satu (e.g., satu pelanggan satu akun)
```

---

## 10. STATE DIAGRAM - PENJUALAN STATUS

```
PENJUALAN STATUS TRANSITIONS

┌─────────────────────────────────────────────────────────────┐
│                  RETAIL SALES (Apoteker Input)              │
│                                                              │
│               ┌──────────────────┐                           │
│               │ Created with     │                           │
│               │ status = 'lunas' │                           │
│               │ (Instant payment)│                           │
│               └──────────────────┘                           │
│                      │                                       │
│                      ▼                                       │
│               ┌──────────────────┐                           │
│               │ Ready for pickup │                           │
│               │ OR shipping      │                           │
│               └──────────────────┘                           │
│                      │                                       │
│                      ▼                                       │
│               ┌──────────────────┐                           │
│               │ Completed        │                           │
│               │ (Delivered)      │                           │
│               └──────────────────┘                           │
└─────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│                 ONLINE SALES (Pelanggan Order)               │
│                                                               │
│  ┌──────────────────┐                                        │
│  │ Created with     │                                        │
│  │ status =         │                                        │
│  │ 'belum_bayar'    │                                        │
│  │ (Awaiting payment)                                        │
│  └────────┬─────────┘                                        │
│           │                                                  │
│  ┌────────┴──────────┐                                       │
│  │ Customer upload   │                                       │
│  │ bukti pembayaran  │                                       │
│  │ status →          │                                       │
│  │ 'belum_confirmed' │                                       │
│  └────────┬──────────┘                                       │
│           │                                                  │
│     ┌─────┴────────────┐                                     │
│     │                  │                                     │
│     ▼ Apoteker         ▼ Apoteker                            │
│   Confirm            Reject                                  │
│     │                  │                                     │
│     ▼                  ▼                                      │
│  ┌────────────┐   ┌──────────────┐                           │
│  │status=     │   │status=       │                           │
│  │'confirmed' │   │'rejected'    │                           │
│  │            │   │              │                           │
│  │Ready for   │   │Stok revert   │                           │
│  │shipping    │   │Customer can  │                           │
│  │            │   │re-upload     │                           │
│  └────────┬───┘   │ bukti        │                           │
│           │       └──────────────┘                           │
│           │              │                                   │
│           ▼              ▼                                    │
│  ┌──────────────┐  ┌──────────────┐                          │
│  │ Delivered    │  │ Back to      │                          │
│  │              │  │ 'belum_bayar'│                          │
│  │ FINAL STATE  │  │ untuk retry  │                          │
│  └──────────────┘  └──────────────┘                          │
│                          │                                   │
│                 ┌────────┴─────────┐                         │
│                 │                  │                         │
│                 ▼ If verified again▼ If customer            │
│          (Apoteker                 cancel:                   │
│           revert OK)               - Stok revert            │
│                 │                  - Status=cancelled       │
│                 ▼                  │                         │
│            ┌──────────┐           ▼                          │
│            │confirmed │      ┌──────────┐                    │
│            │           │     │cancelled │                    │
│            └───────────┘     └──────────┘                    │
│                 │                                            │
│                 ▼                                            │
│            ┌────────────┐                                    │
│            │ Delivered  │                                    │
│            │ FINAL      │                                    │
│            └────────────┘                                    │
│                                                               │
│  SUMMARY:                                                     │
│  ✓ belum_bayar: Waiting for payment proof                   │
│  ✓ belum_confirmed: Apoteker verifying                      │
│  ✓ confirmed/lunas: Payment OK, ready for delivery          │
│  ✓ rejected: Customer needs to re-upload                    │
│  ✓ cancelled: Order cancelled, stok reverted                │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

## TIPS & NOTES

### 🔍 Cara Membaca ERD
- **PK (Primary Key)**: Unique identifier, tidak boleh duplikat
- **FK (Foreign Key)**: Reference ke PK di table lain
- **1:N**: Satu record bisa punya banyak record di table lain
- **N:1**: Banyak record pointing ke satu record
- **1:1**: Satu record punya exactly satu record di table lain

### ✅ Implementasi Tips
1. Selalu gunakan transactions untuk multi-step operations
2. Check stock sebelum decrement
3. Validate data sebelum save ke DB
4. Use eager loading (with()) untuk avoid N+1 queries
5. Implement soft delete untuk audit trail

### 📍 Flow Diagram Legend
```
┌────┐ = Process/Action
├────┤ = Database operation
│    │ = Contains
▼    = Flow direction
├─→  = Decision point (if/else)
└──  = Junction
```

