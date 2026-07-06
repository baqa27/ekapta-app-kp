<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use App\Models\KP\Bimbingan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model AjuanBimbinganManual untuk Kerja Praktek
 * Tabel: ajuan_bimbingan_manual_kps
 *
 * Menyimpan pengajuan lembar bimbingan manual dari mahasiswa
 * Setiap pengiriman adalah pengajuan dengan status pending
 * Status final ditentukan oleh Admin/Prodi
 */
class AjuanBimbinganManualKP extends Model
{
    use HasFactory;

    protected $table = 'ajuan_bimbingan_manual_kps';

    // Status pengajuan (oleh Admin/Prodi): PENDING, ACC, DITOLAK
    public const PENDING = 'pending';
    public const ACC = 'acc';
    public const DITOLAK = 'ditolak';

    // Status yang dipilih mahasiswa (hasil bimbingan offline dengan dosen)
    public const STATUS_MAHASISWA_REVISI = 'revisi';
    public const STATUS_MAHASISWA_ACC = 'acc';

    protected $fillable = [
        'bimbingan_id',
        'mahasiswa_id',
        'foto_lembar_bimbingan',
        'tanggal_bimbingan',
        'status_mahasiswa',
        'status',
        'catatan_reviewer',
        'reviewed_by',
        'reviewer_type',
        'tanggal_review',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'date',
        'tanggal_review' => 'datetime',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi ke bimbingan (file laporan)
     */
    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }

    /**
     * Cek apakah pengajuan sudah di-ACC
     */
    public function isAccepted(): bool
    {
        return $this->status === self::ACC;
    }

    /**
     * Cek apakah pengajuan masih pending
     */
    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    /**
     * Cek apakah pengajuan ditolak
     */
    public function isDitolak(): bool
    {
        return $this->status === self::DITOLAK;
    }

    /**
     * Get badge class berdasarkan status
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::PENDING => 'bg-secondary',
            self::ACC => 'bg-success',
            self::DITOLAK => 'bg-danger',
            default => 'bg-danger',
        };
    }

    /**
     * Get label status
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::PENDING => 'Pending',
            self::ACC => 'ACC',
            self::DITOLAK => 'Ditolak',
            default => 'Ditolak',
        };
    }
}
