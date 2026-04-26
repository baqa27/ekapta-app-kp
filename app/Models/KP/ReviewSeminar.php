<?php

namespace App\Models\KP;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ReviewSeminar untuk Kerja Praktek
 * Tabel: review_seminar_kps (dengan suffix _kp)
 */
class ReviewSeminar extends Model
{
    use HasFactory;

    protected $table = 'review_seminar_kps';

    // Status review
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DITERIMA = 'diterima';

    // Dosen status
    public const DOSEN_PEMBIMBING = 'pembimbing';
    public const DOSEN_PENGUJI = 'penguji';

    protected $fillable = [
        'seminar_id',
        'dosen_id',
        'dosen_status',
        'status',
        'nilai_pembimbing',
        'nilai_penguji',
        'nilai_instansi',
        'nilai_akhir',
        'nilai_1',
        'nilai_2',
        'nilai_3',
        'nilai_4',
        'is_nilai_manual',
        'status_hasil',
        'catatan',
        'catatan_penguji',
        'lampiran',
        'lampiran_lembar_revisi',
        'tanggal_acc',
        'tanggal_acc_manual',
        'token',
        'is_dinilai',
    ];

    protected $casts = [
        'nilai_pembimbing' => 'decimal:2',
        'nilai_penguji' => 'decimal:2',
        'nilai_instansi' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'nilai_1' => 'integer',
        'nilai_2' => 'integer',
        'nilai_3' => 'integer',
        'nilai_4' => 'integer',
        'is_dinilai' => 'boolean',
        'is_nilai_manual' => 'boolean',
        'tanggal_acc' => 'datetime',
        'tanggal_acc_manual' => 'datetime',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiReviewSeminar::class, 'review_seminar_id');
    }

    /**
     * Calculate nilai akhir KP dari 3 komponen nilai
     * Formula: (nilai_pembimbing * bobot_pembimbing + nilai_penguji * bobot_penguji + nilai_instansi * bobot_instansi) / 100
     * 
     * @param float $nilaiPembimbing
     * @param float $nilaiPenguji
     * @param float $nilaiInstansi
     * @param mixed $presentaseNilai PresentaseNilai model atau null
     * @return float
     */
    public function calculateNilaiAkhir($nilaiPembimbing, $nilaiPenguji, $nilaiInstansi, $presentaseNilai = null)
    {
        if (!$presentaseNilai) {
            // Default weights if not provided
            $bobotPembimbing = 40;
            $bobotPenguji = 30;
            $bobotInstansi = 30;
        } else {
            $bobotPembimbing = $presentaseNilai->bobot_pembimbing ?? 40;
            $bobotPenguji = $presentaseNilai->bobot_penguji ?? 30;
            $bobotInstansi = $presentaseNilai->bobot_instansi ?? 30;
        }

        $nilaiAkhir = ($nilaiPembimbing * $bobotPembimbing / 100) + 
                      ($nilaiPenguji * $bobotPenguji / 100) + 
                      ($nilaiInstansi * $bobotInstansi / 100);
        
        return round($nilaiAkhir, 2);
    }

    /**
     * Update nilai via AJAX
     * Method ini dipanggil dari controller untuk update nilai secara real-time
     * 
     * @param string $fieldName Field yang akan diupdate (nilai_pembimbing, nilai_penguji, nilai_instansi)
     * @param float $fieldValue Nilai baru (0-100)
     * @return bool Success status
     */
    public function updateNilai($fieldName, $fieldValue)
    {
        // Validasi field name
        $allowedFields = ['nilai_pembimbing', 'nilai_penguji', 'nilai_instansi'];
        
        if (!in_array($fieldName, $allowedFields)) {
            return false;
        }

        // Validasi nilai (0-100)
        if ($fieldValue < 0 || $fieldValue > 100) {
            return false;
        }

        // Update field
        $this->$fieldName = $fieldValue;
        
        // Hitung nilai akhir jika semua komponen sudah ada
        if ($this->nilai_pembimbing > 0 && $this->nilai_penguji > 0 && $this->nilai_instansi > 0) {
            // Ambil presentase nilai dari prodi mahasiswa
            $presentaseNilai = null;
            if ($this->seminar && $this->seminar->pengajuan && $this->seminar->pengajuan->mahasiswa) {
                $presentaseNilai = PresentaseNilai::where('prodi_id', $this->seminar->pengajuan->mahasiswa->prodi_id)->first();
            }
            
            $this->nilai_akhir = $this->calculateNilaiAkhir(
                $this->nilai_pembimbing,
                $this->nilai_penguji,
                $this->nilai_instansi,
                $presentaseNilai
            );
            
            // Update status menjadi diterima jika semua nilai sudah diisi
            $this->status = self::DITERIMA;
        }
        
        return $this->save();
    }

    /**
     * Hitung nilai mean dari 4 komponen nilai (seperti di TA)
     * 
     * @return float|null
     */
    public function hitungNilaiMean()
    {
        if ($this->nilai_1 && $this->nilai_2 && $this->nilai_3 && $this->nilai_4) {
            return round(($this->nilai_1 + $this->nilai_2 + $this->nilai_3 + $this->nilai_4) / 4, 2);
        }
        return null;
    }
}
