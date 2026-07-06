<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran_kps';

    protected $fillable = [
        'himpunan_id',
        'tipe',
        'nama',
        'nomor',
        'nama_pemilik',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Himpunan
     */
    public function himpunan()
    {
        return $this->belongsTo(Himpunan::class);
    }

    /**
     * Scope untuk metode aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk bank
     */
    public function scopeBank($query)
    {
        return $query->where('tipe', 'bank');
    }

    /**
     * Scope untuk ewallet
     */
    public function scopeEwallet($query)
    {
        return $query->where('tipe', 'ewallet');
    }
}
