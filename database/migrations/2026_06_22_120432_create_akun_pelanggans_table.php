<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel akun_pelanggans -- BARU, terpisah dari data profil pelanggan (sesuai keputusan).
     * Relasi one-to-one ke pelanggans via kd_pelanggan.
     * Dipakai sebagai "guard" terpisah untuk login pelanggan (lihat Tahap 4 - multi auth guard).
     */
    public function up(): void
    {
        Schema::create('akun_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('kd_pelanggan', 20)->unique();
            $table->foreign('kd_pelanggan')->references('kd_pelanggan')->on('pelanggans')->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_pelanggans');
    }
};
