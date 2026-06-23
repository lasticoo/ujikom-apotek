<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'telpon',
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

    // ── Helper role ──────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isApoteker(): bool
    {
        return $this->role === 'apoteker';
    }

    // ── Relasi ───────────────────────────────────────────────

    /** Transaksi penjualan yang diinput oleh user (admin/apoteker) ini. */
    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'id_user');
    }

    /** Transaksi pembelian (ke suplier) yang diinput oleh user ini. */
    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'id_user');
    }
}
