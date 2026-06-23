<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'kd_suplier';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_suplier',
        'nm_suplier',
        'alamat',
        'kota',
        'telpon',
    ];

    /** Semua obat yang dipasok oleh suplier ini. */
    public function obats(): HasMany
    {
        return $this->hasMany(Obat::class, 'kd_suplier');
    }

    /** Riwayat pembelian (apotek beli ke suplier ini). */
    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'kd_suplier');
    }

    /** Generate next supplier code, format: SUP-XXX */
    public static function generateKdSuplier(): string
    {
        $last = self::orderByRaw('CAST(SUBSTRING(kd_suplier, 5) AS UNSIGNED) DESC')->first();
        $nextNumber = $last ? ((int) substr($last->kd_suplier, 4)) + 1 : 1;
        return 'SUP-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
