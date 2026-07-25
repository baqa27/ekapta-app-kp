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
        'foto_profil',
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
     * Accessor URL foto profil — fallback ke default jika belum diisi
     */
    public function getFotoProfilUrlAttribute(): string
    {
        if (!empty($this->foto_profil)) {
            return storage_url($this->foto_profil);
        }
        return asset('ekapta/adminLTE/dist/img/default-profile.png');
    }

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
