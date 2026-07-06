<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DISABLED = 'disabled';
    public const STATUS_PENDAFTARAN_BARU = 'baru';
    public const STATUS_PENDAFTARAN_PERPANJANG = 'perpanjang';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'email',
        'hp',
        'semester',
        'status_pendaftaran',
        'nomor_pembayaran',
        'tanggal_pembayaran',
        'biaya',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_acc',
        'tanggal_acc',
        'status',
    ];

    public static function getStatusPendaftaranOptions(): array
    {
        return [
            self::STATUS_PENDAFTARAN_BARU => 'Baru',
            self::STATUS_PENDAFTARAN_PERPANJANG => 'Perpanjang',
        ];
    }

    public static function getBiayaOptions(): array
    {
        return [
            [
                'value' => 1100000,
                'label' => 'Baru : Rp. 1.100.000,-',
                'status_pendaftaran' => self::STATUS_PENDAFTARAN_BARU,
            ],
            [
                'value' => 550000,
                'label' => 'Perpanjang : Rp. 550.000,-',
                'status_pendaftaran' => self::STATUS_PENDAFTARAN_PERPANJANG,
            ],
        ];
    }

    public static function resolveStatusPendaftaranFromBiaya($biaya): ?string
    {
        $selectedBiaya = (string) (int) $biaya;

        foreach (self::getBiayaOptions() as $option) {
            if ((string) $option['value'] === $selectedBiaya) {
                return $option['status_pendaftaran'];
            }
        }

        return null;
    }

    public static function isValidBiayaForStatus(?string $statusPendaftaran, $biaya): bool
    {
        if (!$statusPendaftaran) {
            return false;
        }

        return in_array((string) (int) $biaya, self::getValidBiayaByStatus($statusPendaftaran), true);
    }

    public function getResolvedStatusPendaftaranAttribute(): ?string
    {
        $resolvedFromBiaya = self::resolveStatusPendaftaranFromBiaya($this->biaya);

        if ($resolvedFromBiaya) {
            return $resolvedFromBiaya;
        }

        return $this->status_pendaftaran ?: null;
    }

    public function getStatusPendaftaranLabelAttribute(): string
    {
        $statusPendaftaran = $this->resolved_status_pendaftaran;

        if (!$statusPendaftaran) {
            // Default: anggap sebagai pendaftaran baru
            // karena data lama kemungkinan besar adalah pendaftaran pertama kali
            return 'Baru';
        }

        return self::getStatusPendaftaranOptions()[$statusPendaftaran] ?? ucfirst($statusPendaftaran);
    }

    protected static function getValidBiayaByStatus(string $statusPendaftaran): array
    {
        $biaya = [];

        foreach (self::getBiayaOptions() as $option) {
            if ($option['status_pendaftaran'] === $statusPendaftaran) {
                $biaya[] = (string) $option['value'];
            }
        }

        return $biaya;
    }

    public function revisis()
    {
        return $this->hasMany(RevisiPendaftaran::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
