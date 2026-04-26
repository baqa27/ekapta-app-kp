<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Prodi as Authenticatable;
// Import TA Models (di root)
use App\Models\Bagian as TABagian;
use App\Models\Pengajuan as TAPengajuan;
use App\Models\PresentaseNilai as TAPresentaseNilai;

// Import KP Models (di folder KP/)
use App\Models\KP\Bagian as KPBagian;
use App\Models\KP\Pengajuan as KPPengajuan;
use App\Models\KP\PresentaseNilai as KPPresentaseNilai;

class Prodi extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'namaprodi',
        'jenjang',
        'kodekaprodi',
        'password',
        'fakultas_id',
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * Bagian TA (tabel: bagians)
     */
    public function bagians()
    {
        return $this->hasMany(Bagian::class);
    }

    /**
     * Bagian KP (tabel: bagian_kps)
     */
    public function bagiansKP()
    {
        return $this->hasMany(KPBagian::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function presentase_nilai()
    {
        return $this->hasOne(PresentaseNilai::class);
    }

    /**
     * Presentase Nilai KP (tabel: presentase_nilai_kps)
     */
    public function presentase_nilai_kp()
    {
        return $this->hasOne(\App\Models\KP\PresentaseNilai::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_prodis', 'prodi_id', 'dosen_id')
            ->withPivot(['kode','nidn']);
    }

    /**
     * Pengajuan TA (tabel: pengajuans)
     * Digunakan oleh controller TA
     */
    function pengajuans(){
        return $this->hasMany(TAPengajuan::class);
    }

    /**
     * Pengajuan KP (tabel: pengajuan_kps)
     * Digunakan oleh controller KP
     */
    function pengajuansKP(){
        return $this->hasMany(KPPengajuan::class);
    }
}
