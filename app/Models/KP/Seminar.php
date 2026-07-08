<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Seminar untuk Kerja Praktek
 * Tabel: seminar_kps (dengan suffix _kp)
 */
class Seminar extends Model
{
    use HasFactory;

    protected $table = 'seminar_kps';

    // Status is_valid (verifikasi himpunan)
    public const REVIEW = 0;
    public const DITERIMA = 1;
    public const REVISI = 2;
    public const DITOLAK = 3;

    public const VALID = 1;
    public const NOT_VALID = 0;

    // Status seminar (alur keseluruhan)
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_REVISI = 'revisi';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DIJADWALKAN = 'dijadwalkan';
    public const STATUS_MENUNGGU_VALIDASI_HIMPUNAN = 'menunggu_validasi_himpunan';
    public const STATUS_SELESAI_SEMINAR = 'selesai_seminar';
    public const STATUS_SELESAI = 'selesai';

    // Metode pembayaran
    public const METODE_CASH = 'Cash';
    public const METODE_DANA = 'DANA';
    public const METODE_SEABANK = 'SeaBank';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'no_wa',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'jumlah_bayar',
        'metode_bayar',
        'nomor_pembayaran',
        'lampiran_proposal',
        'link_akses_produk',
        'is_valid',
        'is_lulus',
        'tanggal_acc',
        'tanggal_selesai',
        'tanggal_ujian',
        'tempat_ujian',
        'judul_laporan',
        'file_laporan',
        'file_bimbingan',
        'bukti_bayar',
        'file_laporan_revisi',
        'bukti_perbaikan',
        'nilai_instansi',
        'is_nilai_instansi_manual',
        'nilai_pembimbing',
        'nilai_penguji',
        'file_nilai_instansi',
        'nilai_seminar',
        'nilai_akhir',
        'status_nilai',
        'status_seminar',
        'catatan_himpunan',
        'catatan_penguji',
        'sesi_seminar_id',
        'urutan_presentasi',
    ];

    protected $casts = [
        'tanggal_acc' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_ujian' => 'datetime',
        'nilai_instansi' => 'decimal:2',
        'nilai_pembimbing' => 'decimal:2',
        'nilai_penguji' => 'decimal:2',
        'nilai_seminar' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiSeminar::class, 'seminar_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewSeminar::class, 'seminar_id');
    }

    public function sesiSeminar()
    {
        return $this->belongsTo(SesiSeminar::class);
    }

    public function dosenPenguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    public static function getMetodeBayarOptions()
    {
        return [
            self::METODE_CASH,
            self::METODE_DANA,
            self::METODE_SEABANK,
        ];
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_REVISI => 'Revisi Berkas',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DIJADWALKAN => 'Dijadwalkan',
            self::STATUS_MENUNGGU_VALIDASI_HIMPUNAN => 'Menunggu Validasi Himpunan',
            self::STATUS_SELESAI_SEMINAR => 'Selesai Seminar',
            self::STATUS_SELESAI => 'Selesai KP',
        ];

        return $labels[$this->status_seminar] ?? $this->status_seminar;
    }

    /**
     * Hitung nilai akhir KP dari 3 komponen nilai
     * Formula: (nilai_pembimbing * bobot) + (nilai_penguji * bobot) + (nilai_instansi * bobot)
     */
    public function hitungNilaiAkhir()
    {
        if ($this->nilai_pembimbing && $this->nilai_penguji && $this->nilai_instansi) {
            // Ambil presentase nilai dari prodi
            $presentaseNilai = $this->pengajuan->mahasiswa->prodi->presentase_nilai_kp ?? null;
            
            if ($presentaseNilai) {
                $bobotPembimbing = $presentaseNilai->bobot_pembimbing ?? 40;
                $bobotPenguji = $presentaseNilai->bobot_penguji ?? 30;
                $bobotInstansi = $presentaseNilai->bobot_instansi ?? 30;
            } else {
                // Default bobot
                $bobotPembimbing = 40;
                $bobotPenguji = 30;
                $bobotInstansi = 30;
            }
            
            $this->nilai_akhir = ($this->nilai_pembimbing * $bobotPembimbing / 100) + 
                                ($this->nilai_penguji * $bobotPenguji / 100) + 
                                ($this->nilai_instansi * $bobotInstansi / 100);
            $this->save();
        }
    }

    public function getNilaiHurufAttribute()
    {
        if (!$this->nilai_akhir) return null;
        
        if ($this->nilai_akhir > 85) return 'A';
        if ($this->nilai_akhir > 69) return 'B';
        if ($this->nilai_akhir > 55) return 'C';
        if ($this->nilai_akhir > 45) return 'D';
        return 'E';
    }
}
