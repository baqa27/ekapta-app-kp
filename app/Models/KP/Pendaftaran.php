<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use App\Models\KP\RevisiPendaftaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pendaftaran untuk Kerja Praktek
 * Tabel: pendaftaran_kps (dengan suffix _kp)
 */
class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_kps';

    // Status pendaftaran berkas
    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DISABLED = 'disabled';
    public const STATUS_PENDAFTARAN_BARU = 'baru';
    public const STATUS_PENDAFTARAN_PERPANJANG = 'perpanjang';

    // Status pembayaran
    public const BAYAR_BELUM = 'belum_bayar';
    public const BAYAR_MENUNGGU = 'menunggu_verifikasi';
    public const BAYAR_LUNAS = 'lunas';
    public const BAYAR_DITOLAK = 'ditolak';

    // Jenis mahasiswa (untuk biaya)
    public const JENIS_REGULER_S1 = 'reguler_s1';
    public const JENIS_REGULER_D3 = 'reguler_d3';
    public const JENIS_KARYAWAN = 'karyawan';

    // Biaya pendaftaran KP
    public const BIAYA_REGULER = 400000;
    public const BIAYA_KARYAWAN = 400000;
    public const BIAYA_PERPANJANGAN_PERSEN = 50;

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
        'jenis_mahasiswa',
        'kelas',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_6',
        'lampiran_7',
        'lampiran_8',
        'lampiran_acc',
        'tanggal_acc',
        'status',
        'status_pembayaran',
        'bukti_pembayaran',
        'tanggal_verifikasi_bayar',
        'nomor_surat_tugas',
        'tanggal_surat_tugas',
        'file_surat_tugas',
        'file_lembar_bimbingan',
        'jumlah_perpanjangan',
        'tanggal_perpanjangan_terakhir',
        'sertifikat_peserta_1',
        'sertifikat_peserta_2',
        'dokumen_pendukung',
        'dokumen_pendukung_kp',
        'masa_berlaku_surat',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'tanggal_acc' => 'datetime',
        'tanggal_verifikasi_bayar' => 'datetime',
        'tanggal_surat_tugas' => 'date',
        'tanggal_perpanjangan_terakhir' => 'date',
        'masa_berlaku_surat' => 'date',
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
                'value' => 400000,
                'label' => 'Baru : Rp. 400.000,-',
                'status_pendaftaran' => self::STATUS_PENDAFTARAN_BARU,
            ],
            [
                'value' => 200000,
                'label' => 'Perpanjang : Rp. 200.000,-',
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
            return '-';
        }

        return self::getStatusPendaftaranOptions()[$statusPendaftaran] ?? ucfirst($statusPendaftaran);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiPendaftaran::class, 'pendaftaran_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function isPembayaranLunas()
    {
        return $this->status_pembayaran === self::BAYAR_LUNAS;
    }

    public function isSuratTugasTerbit()
    {
        return !empty($this->nomor_surat_tugas) && !empty($this->file_surat_tugas);
    }

    public function bisaPerpanjangan()
    {
        return $this->jumlah_perpanjangan < 2;
    }

    public function getBiayaPerpanjangan()
    {
        $biayaAwal = $this->jenis_mahasiswa === self::JENIS_KARYAWAN
            ? self::BIAYA_KARYAWAN
            : self::BIAYA_REGULER;

        return $biayaAwal * (self::BIAYA_PERPANJANGAN_PERSEN / 100);
    }

    public static function getBiayaPendaftaran($jenisMahasiswa)
    {
        return $jenisMahasiswa === self::JENIS_KARYAWAN
            ? self::BIAYA_KARYAWAN
            : self::BIAYA_REGULER;
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
}
