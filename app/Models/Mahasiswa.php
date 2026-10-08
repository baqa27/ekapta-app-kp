<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Mahasiswa as Authenticatable;

// Import TA Models (di root)
use App\Models\Bimbingan as TABimbingan;
use App\Models\Pengajuan as TAPengajuan;
use App\Models\Pendaftaran as TAPendaftaran;
use App\Models\Seminar as TASeminar;
use App\Models\Ujian as TAUjian;
use App\Models\Bagian as TABagian;
use App\Models\BimbinganCanceled as TABimbinganCanceled;
use App\Models\SeminarCanceled as TASeminarCanceled;
use App\Models\Jilid as TAJilid;

// Import KP Models (di folder KP/)
use App\Models\KP\Bimbingan as KPBimbingan;
use App\Models\KP\Pengajuan as KPPengajuan;
use App\Models\KP\Pendaftaran as KPPendaftaran;
use App\Models\KP\Seminar as KPSeminar;
use App\Models\KP\BimbinganCanceled as KPBimbinganCanceled;
use App\Models\KP\SeminarCanceled as KPSeminarCanceled;
use App\Models\KP\Jilid as KPJilid;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $hidden = [
        'password',
    ];

    // Status KP keseluruhan
    public const STATUS_KP_BELUM_MULAI = 'belum_mulai';
    public const STATUS_KP_PENGAJUAN_KERANGKA = 'pengajuan_kerangka';
    public const STATUS_KP_PENGAJUAN_JUDUL = 'pengajuan_judul';
    public const STATUS_KP_PENDAFTARAN = 'pendaftaran';
    public const STATUS_KP_PEMBAYARAN = 'pembayaran';
    public const STATUS_KP_BIMBINGAN = 'bimbingan';
    public const STATUS_KP_SEMINAR = 'seminar';
    public const STATUS_KP_PENGUMPULAN_AKHIR = 'pengumpulan_akhir';
    public const STATUS_KP_SELESAI = 'selesai';

    protected $fillable = [
        'nim',
        'nama',
        'thmasuk',
        'prodi_id',
        'prodi',
        'tptlahir',
        'tgllahir',
        'jeniskelamin',
        'kodedosenwali',
        'nik',
        'kelas',
        'email',
        'hp',
        'alamat',
        'password',
        'status_kp',
        'tanggal_mulai_kp',
        'tanggal_selesai_kp',
    ];

    protected $casts = [
        'tanggal_mulai_kp' => 'date',
        'tanggal_selesai_kp' => 'date',
    ];

    /**
     * Accessor untuk prodi - return nama prodi lengkap
     * Jika prodi_id ada, ambil dari relasi. Jika tidak, gunakan kolom prodi (backward compatibility)
     */
    public function getProdiAttribute($value)
    {
        // Jika ada prodi_id dan relasi loaded, return nama prodi lengkap
        if ($this->prodi_id && $this->relationLoaded('prodiRelation') && $this->prodiRelation) {
            return $this->prodiRelation->namaprodi;
        }
        
        // Jika ada prodi_id tapi relasi belum loaded, load dulu
        if ($this->prodi_id && !$this->relationLoaded('prodiRelation')) {
            $prodi = $this->prodiRelation;
            if ($prodi) {
                return $prodi->namaprodi;
            }
        }
        
        // Fallback ke kolom prodi asli (kode seperti "TI")
        return $value;
    }

    /**
     * Get kode prodi (TI, MI, TS, dll)
     */
    public function getKodeProdiAttribute()
    {
        if ($this->prodi_id && $this->prodiRelation) {
            return $this->prodiRelation->kode;
        }
        
        // Fallback ke kolom prodi asli
        return $this->attributes['prodi'] ?? null;
    }

    // =====================================================
    // RELATIONSHIP TUGAS AKHIR (TA) - Tabel tanpa suffix
    // =====================================================

    /**
     * Bimbingan TA (tabel: bimbingans)
     */
    public function bimbingans()
    {
        return $this->hasMany(TABimbingan::class);
    }

    /**
     * Pengajuan TA (tabel: pengajuans)
     */
    public function pengajuans()
    {
        return $this->hasMany(TAPengajuan::class);
    }

    /**
     * Pendaftaran TA (tabel: pendaftarans)
     */
    public function pendaftarans()
    {
        return $this->hasMany(TAPendaftaran::class);
    }

    /**
     * Seminar TA (tabel: seminars)
     */
    public function seminar()
    {
        return $this->hasOne(TASeminar::class);
    }

    /**
     * Ujian TA - hasMany (tabel: ujians)
     */
    public function ujians()
    {
        return $this->hasMany(TAUjian::class);
    }

    /**
     * Ujian TA - hasOne (tabel: ujians)
     */
    public function ujian()
    {
        return $this->hasOne(TAUjian::class);
    }

    /**
     * Jilid TA (tabel: jilids)
     */
    public function jilid()
    {
        return $this->hasOne(TAJilid::class);
    }

    /**
     * Bagian TA via bimbingan
     */
    public function bagians()
    {
        return $this->belongsToMany(TABagian::class, 'bimbingans', 'mahasiswa_id', 'bagian_id');
    }

    // =====================================================
    // RELATIONSHIP KERJA PRAKTEK (KP) - Tabel dengan suffix _kp
    // =====================================================

    /**
     * Bimbingan KP (tabel: bimbingan_kps)
     */
    public function bimbingansKP()
    {
        return $this->hasMany(KPBimbingan::class);
    }

    /**
     * Pengajuan KP (tabel: pengajuan_kps)
     */
    public function pengajuansKP()
    {
        return $this->hasMany(KPPengajuan::class);
    }

    /**
     * Pendaftaran KP (tabel: pendaftaran_kps)
     */
    public function pendaftaransKP()
    {
        return $this->hasMany(KPPendaftaran::class);
    }

    /**
     * Seminar KP (tabel: seminar_kps)
     */
    public function seminarKP()
    {
        return $this->hasOne(KPSeminar::class);
    }

    /**
     * Alias untuk seminarKP() - nama lebih deskriptif
     */
    public function pengumpulanAkhir()
    {
        return $this->hasOne(KPJilid::class);
    }

    /**
     * Jilid KP (tabel: jilid_kps)
     */
    public function jilidKP()
    {
        return $this->hasOne(KPJilid::class);
    }

    // =====================================================
    // RELATIONSHIP UMUM (Shared)
    // =====================================================

    /**
     * Prodi relationship
     */
    public function prodiRelation()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Dosen pembimbing (shared untuk TA dan KP)
     */
    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_mahasiswas', 'mahasiswa_id', 'dosen_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    /**
     * Bimbingan canceled TA (tabel: bimbingan_canceleds)
     */
    public function bimbingan_canceleds()
    {
        return $this->hasMany(TABimbinganCanceled::class);
    }

    /**
     * Seminar canceled TA (tabel: seminar_canceleds)
     */
    public function seminar_canceleds()
    {
        return $this->hasMany(TASeminarCanceled::class);
    }

    /**
     * Bimbingan canceled KP (tabel: bimbingan_canceled_kps)
     */
    public function bimbingan_canceledsKP()
    {
        return $this->hasMany(KPBimbinganCanceled::class);
    }

    /**
     * Seminar canceled KP (tabel: seminar_canceled_kps)
     */
    public function seminar_canceledsKP()
    {
        return $this->hasMany(KPSeminarCanceled::class);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get label status KP
     */
    public function getStatusKpLabelAttribute()
    {
        $labels = [
            self::STATUS_KP_BELUM_MULAI => 'Belum Mulai',
            self::STATUS_KP_PENGAJUAN_KERANGKA => 'Pengajuan Kerangka Pikir',
            self::STATUS_KP_PENGAJUAN_JUDUL => 'Pengajuan Judul KP',
            self::STATUS_KP_PENDAFTARAN => 'Pendaftaran KP',
            self::STATUS_KP_PEMBAYARAN => 'Pembayaran',
            self::STATUS_KP_BIMBINGAN => 'Bimbingan KP',
            self::STATUS_KP_SEMINAR => 'Seminar KP',
            self::STATUS_KP_PENGUMPULAN_AKHIR => 'Pengumpulan Akhir',
            self::STATUS_KP_SELESAI => 'Selesai KP',
        ];

        return $labels[$this->status_kp] ?? $this->status_kp;
    }

    /**
     * Dosen pembimbing KP (single pembimbing)
     */
    public function dosenPembimbing()
    {
        return $this->dosens()->where('status', 'pembimbing')->first();
    }
}
