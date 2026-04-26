<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Dosen as Authenticatable;

// Import TA Models (di root)
use App\Models\Bimbingan as TABimbingan;
use App\Models\RevisiBimbingan as TARevisiBimbingan;
use App\Models\ReviewSeminar as TAReviewSeminar;
use App\Models\ReviewUjian as TAReviewUjian;
use App\Models\BimbinganCanceled as TABimbinganCanceled;

// Import KP Models (di folder KP/)
use App\Models\KP\Bimbingan as KPBimbingan;
use App\Models\KP\RevisiBimbingan as KPRevisiBimbingan;
use App\Models\KP\ReviewSeminar as KPReviewSeminar;
use App\Models\KP\BimbinganCanceled as KPBimbinganCanceled;

class Dosen extends Authenticatable
{
    use HasFactory;

    public const UTAMA = 'utama';
    public const PENDAMPING = 'pendamping';
    public const PENGUJI = 'penguji';

    protected $fillable = [
        'nidn',
        'nik',
        'nama',
        'gelar',
        'tgllahir',
        'tptlahir',
        'alamat',
        'email',
        'hp',
        'kodeprodi',
        'password',
        'ttd',
        'is_manual',
        'mode_bimbingan',
    ];

    protected $hidden = [
        'password'
    ];

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_mahasiswas', 'dosen_id', 'mahasiswa_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    /**
     * Mahasiswa KP yang dibimbing
     * KP menggunakan tabel yang sama (dosen_mahasiswas) tapi dengan status 'pembimbing'
     */
    public function mahasiswasKP()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_mahasiswas', 'dosen_id', 'mahasiswa_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    public function prodis()
    {
        return $this->belongsToMany(Prodi::class, 'dosen_prodis', 'dosen_id', 'prodi_id')
            ->withPivot(['kode','nidn']);
    }

    /**
     * Revisi bimbingan TA
     */
    public function revisis()
    {
        return $this->hasMany(TARevisiBimbingan::class);
    }

    /**
     * Revisi bimbingan KP
     */
    public function revisisKP()
    {
        return $this->hasMany(KPRevisiBimbingan::class);
    }

    /**
     * Bimbingan TA (tabel: dosen_bimbingans)
     * Default relationship untuk TA
     */
    public function bimbingans()
    {
        return $this->belongsToMany(TABimbingan::class, 'dosen_bimbingans', 'dosen_id', 'bimbingan_id')
            ->withTimestamps();
    }

    /**
     * Bimbingan KP (tabel: dosen_bimbingan_kps)
     */
    public function bimbingansKP()
    {
        return $this->belongsToMany(KPBimbingan::class, 'dosen_bimbingan_kps', 'dosen_id', 'bimbingan_id')
            ->withTimestamps();
    }

    /**
     * Review Seminar TA
     */
    public function seminars()
    {
        return $this->hasMany(TAReviewSeminar::class);
    }

    /**
     * Review Seminar KP
     */
    public function seminarsKP()
    {
        return $this->hasMany(KPReviewSeminar::class);
    }

    /**
     * Review Ujian TA
     */
    public function ujians()
    {
        return $this->hasMany(TAReviewUjian::class);
    }

    /**
     * Bimbingan canceled TA
     */
    public function bimbingan_canceleds()
    {
        return $this->hasMany(TABimbinganCanceled::class);
    }

    /**
     * Bimbingan canceled KP
     */
    public function bimbingan_canceledsKP()
    {
        return $this->hasMany(KPBimbinganCanceled::class);
    }
}

