<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Himpunan as Authenticatable;
use App\Models\Prodi;

class Himpunan extends Authenticatable
{
    use HasFactory;

    /**
     * Nama tabel KP (dengan suffix _kps sesuai standar)
     */
    protected $table = 'himpunan_kps';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'prodi_id',
        'is_pendaftaran_seminar_open',
        'biaya_seminar',
        'nama_rekening',
        'nomor_rekening',
        'bank',
        'nomor_dana', // Legacy - untuk backward compatibility
        'nomor_seabank', // Legacy - untuk backward compatibility
    ];

    protected $casts = [
        'is_pendaftaran_seminar_open' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke Prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    /**
     * Relasi ke Metode Pembayaran
     */
    public function metodePembayarans()
    {
        return $this->hasMany(MetodePembayaran::class, 'himpunan_id');
    }

    /**
     * Cek apakah pendaftaran seminar dibuka
     */
    public static function isPendaftaranSeminarOpen(): bool
    {
        $himpunan = self::first();
        return $himpunan ? $himpunan->is_pendaftaran_seminar_open : true;
    }
}
