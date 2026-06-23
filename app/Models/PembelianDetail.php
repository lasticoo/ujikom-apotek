<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota',
        'kd_obat',
        'jumlah',
        'harga_beli',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'nota');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'kd_obat');
    }

    /** Subtotal baris ini = harga_beli * jumlah. */
    public function subtotal(): float
    {
        return (float) $this->jumlah * (float) ($this->harga_beli ?? 0);
    }
}
