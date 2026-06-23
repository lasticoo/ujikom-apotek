<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Apoteker;
use App\Http\Controllers\Pelanggan;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Halaman Utama (Welcome / Katalog publik) ──────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── Dokumentasi Teknis PDF (akses publik, hanya untuk dev) ────
Route::get('/dokumentasi/download-pdf', function () {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dokumentasi.pdf')
        ->setPaper('a4', 'portrait');
    return $pdf->download('Dokumentasi_Teknis_Apotek_Digital.pdf');
})->name('dokumentasi.pdf');

// ── Auth Routes (Breeze: login, register, password reset, dll) ─
require __DIR__.'/auth.php';

// ── Profile (untuk user web guard) ───────────────────────────
Route::middleware('auth:web')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ──────────────────────────────────────────────────────────────
// ADMIN ROUTES
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth:web', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Obat (CRUD + kadaluarsa + status)
    Route::get('obat/kadaluarsa', [Admin\ObatController::class, 'kadaluarsa'])->name('obat.kadaluarsa');
    Route::patch('obat/{obat}/status', [Admin\ObatController::class, 'updateStatus'])->name('obat.updateStatus');
    Route::resource('obat', Admin\ObatController::class);

    // Suplier (CRUD)
    Route::resource('suplier', Admin\SuplierController::class);

    // Pelanggan (read-only)
    Route::get('pelanggan', [Admin\PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('pelanggan/{pelanggan}', [Admin\PelangganController::class, 'show'])->name('pelanggan.show');

    // Apoteker (CRUD)
    Route::resource('apoteker', Admin\ApotekerController::class)->except(['show']);

    // Laporan Penjualan (read-only)
    Route::get('penjualan', [Admin\PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('penjualan/export-excel', [Admin\PenjualanController::class, 'exportExcel'])->name('penjualan.export');
    Route::get('penjualan/download-pdf', [Admin\PenjualanController::class, 'downloadPdf'])->name('penjualan.pdf');
    Route::get('penjualan/{penjualan}', [Admin\PenjualanController::class, 'show'])->name('penjualan.show');

    // Daftar Pembelian (CRUD / restok) & Laporan Pembelian
    Route::get('pembelian/report', [Admin\PembelianController::class, 'report'])->name('pembelian.report');
    Route::get('pembelian/report/export-excel', [Admin\PembelianController::class, 'reportExportExcel'])->name('pembelian.report.export');
    Route::get('pembelian/report/download-pdf', [Admin\PembelianController::class, 'reportDownloadPdf'])->name('pembelian.report.pdf');
    Route::resource('pembelian', Admin\PembelianController::class);
});

// ──────────────────────────────────────────────────────────────
// APOTEKER ROUTES
// ──────────────────────────────────────────────────────────────
Route::middleware(['auth:web', 'role:apoteker'])
    ->prefix('apoteker')
    ->name('apoteker.')
    ->group(function () {

    // Dashboard
    Route::get('dashboard', [Apoteker\DashboardController::class, 'index'])->name('dashboard');

    // Obat
    Route::get('obat/kadaluarsa', [Apoteker\ObatController::class, 'kadaluarsa'])->name('obat.kadaluarsa');
    Route::delete('obat/{obat}/kadaluarsa', [Apoteker\ObatController::class, 'destroyKadaluarsa'])->name('obat.destroyKadaluarsa');
    Route::resource('obat', Apoteker\ObatController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Penjualan
    Route::resource('penjualan', Apoteker\PenjualanController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('pesanan-online', [Apoteker\PenjualanController::class, 'onlineOrders'])->name('penjualan.online');
    Route::get('pesanan-online/{penjualan}', [Apoteker\PenjualanController::class, 'showOnlineOrder'])->name('penjualan.online.show');
    Route::post('pesanan-online/{penjualan}/konfirmasi', [Apoteker\PenjualanController::class, 'confirmPayment'])->name('penjualan.online.confirm');
    Route::post('pesanan-online/{penjualan}/tolak', [Apoteker\PenjualanController::class, 'rejectPayment'])->name('penjualan.online.reject');

    // Pembelian (restok)
    Route::resource('pembelian', Apoteker\PembelianController::class)->only(['index', 'create', 'store', 'show']);
});

// ──────────────────────────────────────────────────────────────
// PELANGGAN ROUTES
// ──────────────────────────────────────────────────────────────
Route::middleware(['pelanggan.auth'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

    // Dashboard / katalog obat
    Route::get('dashboard', [Pelanggan\ObatController::class, 'index'])->name('dashboard');

    // Obat (katalog)
    Route::get('obat', [Pelanggan\ObatController::class, 'index'])->name('obat.index');
    Route::get('obat/{obat}', [Pelanggan\ObatController::class, 'show'])->name('obat.show');

    // Keranjang (Cart)
    Route::get('cart', [Pelanggan\CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add/{obat}', [Pelanggan\CartController::class, 'add'])->name('cart.add');
    Route::patch('cart/update/{obat}', [Pelanggan\CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{obat}', [Pelanggan\CartController::class, 'remove'])->name('cart.remove');
    Route::get('checkout', [Pelanggan\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('checkout', [Pelanggan\CartController::class, 'processCheckout'])->name('cart.processCheckout');

    // Pesanan
    Route::get('pesanan', [Pelanggan\PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('pesanan/buat', [Pelanggan\PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('pesanan', [Pelanggan\PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('pesanan/{penjualan}', [Pelanggan\PenjualanController::class, 'show'])->name('penjualan.show');
    Route::post('pesanan/{penjualan}/bayar', [Pelanggan\PenjualanController::class, 'uploadBuktiPembayaran'])->name('penjualan.bayar');
});
