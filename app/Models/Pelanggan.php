<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_pelanggan',
        'nm_pelanggan',
        'alamat',
        'kota',
        'telpon',
    ];

    /** Akun login (email/password) milik pelanggan ini -- one-to-one. */
    public function akun(): HasOne
    {
        return $this->hasOne(AkunPelanggan::class, 'kd_pelanggan');
    }

    /** Riwayat transaksi pembelian obat oleh pelanggan ini. */
    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'kd_pelanggan');
    }
}
