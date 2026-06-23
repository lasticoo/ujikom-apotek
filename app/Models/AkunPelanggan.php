<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * AkunPelanggan extends Authenticatable (bukan Model biasa) karena tabel ini
 * dipakai sebagai guard auth terpisah ("pelanggan") -- lihat config/auth.php di Tahap 4.
 */
class AkunPelanggan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'akun_pelanggans';

    protected $fillable = [
        'kd_pelanggan',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Data profil pelanggan terkait (nama, alamat, dll). */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan');
    }
}
