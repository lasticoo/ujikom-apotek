<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel penjualan - struktur dipertahankan sesuai "Penjualan" pada gambar relasi asli
     * (Nota, TglNota, KdPelanggan, Diskon).
     * Tambahan: id_user (FK ke users) -- mencatat apoteker/admin yang menginput transaksi.
     * Nota dibuat string PK dengan format custom (INV-YYYYMMDD-XXX), generate di controller.
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->string('nota', 30)->primary();
            $table->date('tgl_nota');
            $table->string('kd_pelanggan', 20)->nullable();
            $table->foreign('kd_pelanggan')->references('kd_pelanggan')->on('pelanggans')->nullOnDelete();
            $table->foreignId('id_user')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('diskon', 12, 2)->default(0);
            $table->text('alamat_kirim')->nullable();
            $table->string('status_pembayaran', 30)->default('belum_bayar');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
