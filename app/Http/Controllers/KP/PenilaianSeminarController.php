<?php

namespace App\Http\Controllers\KP;

// Import KP Models
use App\Models\KP\SesiSeminar;
use App\Models\KP\Seminar;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\PresentaseNilai;

use App\Helpers\AppHelper;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenilaianSeminarController extends \App\Http\Controllers\Controller
{
    /**
     * Halaman penilaian seminar untuk dosen penguji (tanpa login)
     */
    public function index($token)
    {
        // Cek apakah token untuk Penguji 1 atau Penguji 2
        $sesi = SesiSeminar::where('token_penilaian', $token)
            ->orWhere('token_penilaian_penguji_2', $token)
            ->first();

        if (!$sesi) {
            return view('kp.pages.public.penilaian-seminar.not-found', [
                'title'   => 'Link Tidak Valid',
                'message' => 'Link penilaian tidak ditemukan atau tidak valid.',
            ]);
        }

        // Tentukan penguji mana yang buka link
        $isPenguji1 = ($sesi->token_penilaian === $token);
        $isPenguji2 = ($sesi->token_penilaian_penguji_2 === $token);

        // Cek apakah token sudah digunakan
        if ($isPenguji1 && $sesi->is_token_used) {
            return view('kp.pages.public.penilaian-seminar.expired', [
                'title'   => 'Link Kadaluarsa',
                'message' => 'Link penilaian ini sudah digunakan dan tidak bisa dipakai lagi.',
                'used_at' => $sesi->token_used_at,
            ]);
        }

        if ($isPenguji2 && $sesi->is_token_penguji_2_used) {
            return view('kp.pages.public.penilaian-seminar.expired', [
                'title'   => 'Link Kadaluarsa',
                'message' => 'Link penilaian ini sudah digunakan dan tidak bisa dipakai lagi.',
                'used_at' => $sesi->token_penguji_2_used_at,
            ]);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)
            ->with(['mahasiswa', 'pengajuan', 'reviews'])
            ->orderBy('urutan_presentasi')
            ->get();

        // Tentukan dosen penguji yang sedang akses
        $dosenPenguji   = $isPenguji1 ? $sesi->dosenPenguji   : $sesi->dosenPenguji2;
        $dosenPengujiId = $isPenguji1 ? $sesi->dosen_penguji_id : $sesi->dosen_penguji_id_2;
        $currentToken   = $token;

        return view('kp.pages.public.penilaian-seminar.index', [
            'title'         => 'Penilaian Seminar KP',
            'sesi'          => $sesi,
            'seminars'      => $seminars,
            'dosenPenguji'  => $dosenPenguji,
            'dosenPengujiId'=> $dosenPengujiId,
            'isPenguji1'    => $isPenguji1,
            'isPenguji2'    => $isPenguji2,
            'currentToken'  => $currentToken,
        ]);
    }

    /**
     * Download laporan seminar (dosen penguji via link publik)
     * GET /penilaian-seminar/{token}/laporan/{seminarId}
     * Route name: kp.penilaian.seminar.laporan
     */
    public function laporan($token, $seminarId)
    {
        // Validasi token (Penguji 1 atau Penguji 2)
        $sesi = SesiSeminar::where('token_penilaian', $token)
            ->orWhere('token_penilaian_penguji_2', $token)
            ->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Link tidak valid.'
            ], 404);
        }

        // Cari seminar yang termasuk di sesi ini
        $seminar = Seminar::where('id', $seminarId)
            ->where('sesi_seminar_id', $sesi->id)
            ->first();

        if (!$seminar) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan seminar tidak ditemukan.'
            ], 404);
        }

        // Cek apakah file laporan ada
        if (empty($seminar->file_laporan)) {
            return response()->json([
                'success' => false,
                'message' => 'File laporan belum diunggah.'
            ], 404);
        }

        // Redirect ke storage.file route — StorageController handle local + Google Drive fallback
        $filePath = StorageHelper::kpSeminarPath($seminar->file_laporan);
        return redirect()->route('storage.file', ['path' => $filePath]);
    }

    /**
     * Submit semua penilaian (dosen penguji via link publik)
     * Support partial submit: Tidak harus semua mahasiswa dinilai.
     *
     * FIX RACE CONDITION: DB::transaction + lockForUpdate mencegah
     * double-submit bersamaan (dosen double-click) dari lolos cek is_token_used.
     */
    public function submit(Request $request, $token)
    {
        return DB::transaction(function () use ($request, $token) {
            // lockForUpdate: row di-lock sampai transaksi selesai
            $sesi = SesiSeminar::where('token_penilaian', $token)
                ->orWhere('token_penilaian_penguji_2', $token)
                ->lockForUpdate()
                ->first();

            if (!$sesi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link tidak valid.'
                ], 400);
            }

            // Tentukan penguji mana yang submit
            $isPenguji1 = ($sesi->token_penilaian === $token);
            $isPenguji2 = ($sesi->token_penilaian_penguji_2 === $token);

            // Cek apakah token sudah digunakan (setelah lock — race condition aman)
            if ($isPenguji1 && $sesi->is_token_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link sudah kadaluarsa.'
                ], 400);
            }

            if ($isPenguji2 && $sesi->is_token_penguji_2_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link sudah kadaluarsa.'
                ], 400);
            }

            $dosenPengujiId = $isPenguji1 ? $sesi->dosen_penguji_id : $sesi->dosen_penguji_id_2;
            $seminars       = Seminar::where('sesi_seminar_id', $sesi->id)->get();
            $penilaian      = $request->input('penilaian', []);

            $jumlahDinilai = 0;
            $jumlahSkip    = 0;

            // Simpan penilaian (hanya yang terisi dan valid)
            foreach ($seminars as $seminar) {
                if (isset($penilaian[$seminar->id]) &&
                    isset($penilaian[$seminar->id]['nilai']) &&
                    $penilaian[$seminar->id]['nilai'] !== '' &&
                    $penilaian[$seminar->id]['nilai'] >= 0 &&
                    $penilaian[$seminar->id]['nilai'] <= 100) {

                    $data = $penilaian[$seminar->id];

                    // Simpan review dari penguji
                    ReviewSeminar::create([
                        'seminar_id'     => $seminar->id,
                        'dosen_id'       => $dosenPengujiId,
                        'dosen_status'   => ReviewSeminar::DOSEN_PENGUJI,
                        'status'         => ReviewSeminar::DITERIMA,
                        'status_hasil'   => 'diterima',
                        'nilai_angka'    => $data['nilai'],
                        'catatan_penguji'=> $data['catatan'] ?? null,
                        'is_dinilai'     => true,
                        'tanggal_acc'    => now(),
                    ]);

                    // Update nilai di seminar
                    $seminar->update([
                        'nilai_seminar'  => $data['nilai'],
                        'status_seminar' => Seminar::STATUS_MENUNGGU_VALIDASI_HIMPUNAN,
                        'catatan_penguji'=> $data['catatan'] ?? null,
                    ]);

                    // Kirim notifikasi ke mahasiswa
                    try {
                        if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                            AppHelper::instance()->send_mail([
                                'mail'    => $seminar->mahasiswa->email,
                                'subject' => 'Seminar KP Dinilai',
                                'title'   => 'Seminar KP Dinilai',
                                'message' => "Seminar KP Anda telah dinilai oleh dosen penguji!<br>Nilai Seminar: <b>{$data['nilai']}</b><br><br>Menunggu validasi dari Himpunan untuk melanjutkan proses KP.",
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('Email notifikasi penilaian seminar gagal: ' . $e->getMessage());
                    }

                    $jumlahDinilai++;
                } else {
                    $jumlahSkip++;
                }
            }

            // Invalidate token setelah submit (meskipun tidak ada nilai yang disimpan)
            if ($isPenguji1) {
                $sesi->invalidateToken();
            } else {
                $sesi->invalidateTokenPenguji2();
            }

            // Response message
            if ($jumlahDinilai === 0) {
                $message = "Link berhasil ditutup. Tidak ada penilaian yang disimpan ({$jumlahSkip} mahasiswa di-skip).";
            } else {
                $message = "Penilaian berhasil disimpan untuk {$jumlahDinilai} mahasiswa.";
                if ($jumlahSkip > 0) {
                    $message .= " {$jumlahSkip} mahasiswa di-skip (belum dinilai).";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        });
    }

    // =========================================================================
    // PRODI METHODS
    // Digunakan oleh view: resources/views/kp/pages/prodi/seminar/
    // Route group: isProdi middleware, prefix /kp
    // =========================================================================

    /**
     * Halaman detail penilaian seminar untuk Prodi
     * GET /kp/seminar/penilaian/{id}
     * Route name: kp.seminar.penilaian
     */
    public function detailPenilaian($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'pengajuan', 'reviews'])
            ->findOrFail($id);

        // Ambil presentase nilai prodi
        $prodiMahasiswa = optional($seminar->mahasiswa)->prodi;
        $presentase_nilai = PresentaseNilai::when($prodiMahasiswa, function ($q) use ($prodiMahasiswa) {
            $q->where('prodi_id', $prodiMahasiswa->id ?? null);
        })->first();

        // Hitung nilai akhir
        $nilai_akhir = '-';
        if ($seminar->nilai_pembimbing !== null &&
            $seminar->nilai_instansi !== null &&
            $presentase_nilai) {
            $nilaiPenguji = $seminar->nilai_seminar ?? $seminar->nilai_penguji ?? 0;
            $nilaiAkhir = (
                ($seminar->nilai_pembimbing * ($presentase_nilai->bobot_pembimbing ?? $presentase_nilai->nilai_pembimbing ?? 40) / 100) +
                ($nilaiPenguji              * ($presentase_nilai->bobot_penguji    ?? $presentase_nilai->nilai_penguji    ?? 30) / 100) +
                ($seminar->nilai_instansi   * ($presentase_nilai->bobot_instansi   ?? $presentase_nilai->nilai_instansi   ?? 30) / 100)
            );
            $nilai_akhir = number_format($nilaiAkhir, 2);
        }

        return view('kp.pages.prodi.seminar.penilaian', [
            'title'            => 'Detail Penilaian Seminar KP',
            'active'           => 'seminar-kp',
            'sidebar'          => 'kp.partials.sidebarProdi',
            'module'           => 'kp',
            'seminar'          => $seminar,
            'presentase_nilai' => $presentase_nilai,
            'nilai_akhir'      => $nilai_akhir,
        ]);
    }

    /**
     * Update nilai (route: kp.seminar.penilaian.update.nilai)
     * POST /kp/seminar/penilaian/update-nilai
     * Proxy ke updateNilaiKomponen
     */
    public function updateNilai(Request $request)
    {
        return $this->updateNilaiKomponen($request);
    }

    /**
     * Update nilai komponen (pembimbing / penguji) via AJAX
     * POST /kp/seminar/penilaian/update-nilai-komponen
     * Dipanggil dari penilaian.blade.php
     */
    public function updateNilaiKomponen(Request $request)
    {
        $request->validate([
            'seminar_id'  => 'required|exists:seminar_kps,id',
            'field_name'  => 'required|in:nilai_pembimbing,nilai_penguji',
            'field_value' => 'required|numeric|min:0|max:100',
        ]);

        $seminar = Seminar::findOrFail($request->seminar_id);
        $seminar->update([
            $request->field_name => $request->field_value,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Nilai berhasil disimpan',
            'nilai_akhir' => $this->hitungNilaiAkhir($seminar),
        ]);
    }

    /**
     * Update nilai instansi via AJAX
     * POST /kp/seminar/penilaian/update-nilai-instansi
     * Dipanggil dari penilaian.blade.php
     */
    public function updateNilaiInstansi(Request $request)
    {
        $request->validate([
            'seminar_id'  => 'required|exists:seminar_kps,id',
            'field_name'  => 'required|in:nilai_instansi',
            'field_value' => 'required|numeric|min:0|max:100',
        ]);

        $seminar = Seminar::findOrFail($request->seminar_id);
        $seminar->update([
            'nilai_instansi'           => $request->field_value,
            'is_nilai_instansi_manual' => true,
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Nilai instansi berhasil disimpan',
            'nilai_akhir' => $this->hitungNilaiAkhir($seminar),
        ]);
    }

    /**
     * Update status lulus via AJAX
     * POST /kp/seminar/penilaian/update-status-lulus
     * Dipanggil dari penilaian.blade.php
     */
    public function updateStatusLulus(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:seminar_kps,id',
            'is_lulus'   => 'required|in:0,1',
        ]);

        $seminar = Seminar::findOrFail($request->seminar_id);
        $seminar->update([
            'is_lulus' => $request->is_lulus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kelulusan berhasil diupdate',
        ]);
    }

    /**
     * Helper: Hitung nilai akhir berdasarkan presentase prodi mahasiswa
     */
    private function hitungNilaiAkhir(Seminar $seminar): string
    {
        $seminar->refresh();

        $prodiMahasiswa = optional($seminar->mahasiswa)->prodi;
        $presentase = PresentaseNilai::when($prodiMahasiswa, function ($q) use ($prodiMahasiswa) {
            $q->where('prodi_id', $prodiMahasiswa->id ?? null);
        })->first();

        if (!$presentase || $seminar->nilai_pembimbing === null || $seminar->nilai_instansi === null) {
            return '-';
        }

        $nilaiPenguji = $seminar->nilai_seminar ?? $seminar->nilai_penguji ?? 0;

        $nilaiAkhir = (
            ($seminar->nilai_pembimbing * ($presentase->bobot_pembimbing ?? $presentase->nilai_pembimbing ?? 40) / 100) +
            ($nilaiPenguji              * ($presentase->bobot_penguji    ?? $presentase->nilai_penguji    ?? 30) / 100) +
            ($seminar->nilai_instansi   * ($presentase->bobot_instansi   ?? $presentase->nilai_instansi   ?? 30) / 100)
        );

        return number_format($nilaiAkhir, 2);
    }
}
