<?php

namespace App\Http\Controllers\KP;

// Import KP Models
use App\Models\KP\SesiSeminar;
use App\Models\KP\Seminar;
use App\Models\KP\ReviewSeminar;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class PenilaianSeminarController extends \App\Http\Controllers\Controller
{
    /**
     * Halaman penilaian seminar untuk dosen penguji (tanpa login)
     */
    public function index($token)
    {
        $sesi = SesiSeminar::where('token_penilaian', $token)->first();

        if (!$sesi) {
            return view('kp.pages.public.penilaian-seminar.not-found', [
                'title' => 'Link Tidak Valid',
                'message' => 'Link penilaian tidak ditemukan atau tidak valid.',
            ]);
        }

        if ($sesi->is_token_used) {
            return view('kp.pages.public.penilaian-seminar.expired', [
                'title' => 'Link Kadaluarsa',
                'message' => 'Link penilaian ini sudah digunakan dan tidak bisa dipakai lagi.',
                'used_at' => $sesi->token_used_at,
            ]);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('urutan_presentasi')
            ->get();

        return view('kp.pages.public.penilaian-seminar.index', [
            'title' => 'Penilaian Seminar KP',
            'sesi' => $sesi,
            'seminars' => $seminars,
        ]);
    }

    /**
     * Submit semua penilaian
     * KP: Hanya nilai, tidak ada status revisi dari penguji
     * Setelah dinilai, mahasiswa langsung upload nilai instansi
     */
    public function detailPenilaian($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'pengajuan'])->findOrFail($id);

        // Cek apakah mahasiswa karyawan
        $is_karyawan = AppHelper::isKaryawanKP($seminar->mahasiswa);

        // Ambil presentase nilai dari prodi
        $presentase_nilai = $seminar->mahasiswa->prodi->presentase_nilai_kp ?? null;

        // Ambil nilai dari seminar_kps
        $nilai_pembimbing = $seminar->nilai_pembimbing ?? 0;
        $nilai_penguji = $seminar->nilai_penguji ?? 0;
        $nilai_instansi = $seminar->nilai_instansi ?? 0;

        // Hitung bobot x nilai
        $bobot_pembimbing = $presentase_nilai->bobot_pembimbing ?? 40;
        $bobot_penguji = $presentase_nilai->bobot_penguji ?? 30;
        $bobot_instansi = $presentase_nilai->bobot_instansi ?? 30;

        $bobot_x_nilai_pembimbing = ($nilai_pembimbing * $bobot_pembimbing) / 100;
        $bobot_x_nilai_penguji = ($nilai_penguji * $bobot_penguji) / 100;
        $bobot_x_nilai_instansi = ($nilai_instansi * $bobot_instansi) / 100;

        // Hitung nilai akhir
        $nilai_akhir = $bobot_x_nilai_pembimbing + $bobot_x_nilai_penguji + $bobot_x_nilai_instansi;

        return view('kp.pages.prodi.seminar.penilaian', [
            'title' => 'Penilaian Seminar KP',
            'seminar' => $seminar,
            'is_karyawan' => $is_karyawan,
            'presentase_nilai' => $presentase_nilai,
            'nilai_pembimbing' => $nilai_pembimbing,
            'nilai_penguji' => $nilai_penguji,
            'nilai_instansi' => $nilai_instansi,
            'bobot_pembimbing' => $bobot_pembimbing,
            'bobot_penguji' => $bobot_penguji,
            'bobot_instansi' => $bobot_instansi,
            'nilai_akhir' => $nilai_akhir,
        ]);
    }

    public function updateNilai(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Validasi input
        $request->validate([
            'nilai_pembimbing' => 'required|numeric|min:0|max:100',
            'nilai_penguji' => 'required|numeric|min:0|max:100',
            'nilai_instansi' => 'required|numeric|min:0|max:100',
        ]);

        // Update nilai
        $seminar->nilai_pembimbing = $request->nilai_pembimbing;
        $seminar->nilai_penguji = $request->nilai_penguji;
        $seminar->nilai_instansi = $request->nilai_instansi;
        $seminar->is_nilai_instansi_manual = true;
        $seminar->save();

        // Hitung nilai akhir
        $seminar->hitungNilaiAkhir();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan',
            'nilai_akhir' => $seminar->nilai_akhir,
            'nilai_huruf' => $seminar->nilai_huruf,
        ]);
    }

    public function updateNilaiKomponen(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Validasi input
        $request->validate([
            'field_name' => 'required|string',
            'field_value' => 'required|numeric|min:0|max:100',
        ]);

        // Update nilai komponen
        if ($request->field_name == 'nilai_pembimbing') {
            $seminar->nilai_pembimbing = $request->field_value;
        } elseif ($request->field_name == 'nilai_penguji') {
            $seminar->nilai_penguji = $request->field_value;
        } elseif ($request->field_name == 'nilai_instansi') {
            $seminar->nilai_instansi = $request->field_value;
            $seminar->is_nilai_instansi_manual = true;
        }

        $seminar->save();

        // Hitung nilai akhir
        $seminar->hitungNilaiAkhir();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan',
            'nilai_akhir' => $seminar->nilai_akhir,
            'nilai_huruf' => $seminar->nilai_huruf,
        ]);
    }

    public function updateNilaiInstansi(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Validasi input
        $request->validate([
            'field_value' => 'required|numeric|min:0|max:100',
        ]);

        // Update nilai instansi
        $seminar->nilai_instansi = $request->field_value;
        $seminar->is_nilai_instansi_manual = true;
        $seminar->save();

        // Hitung nilai akhir
        $seminar->hitungNilaiAkhir();

        return response()->json([
            'success' => true,
            'message' => 'Nilai instansi berhasil disimpan',
            'nilai_akhir' => $seminar->nilai_akhir,
            'nilai_huruf' => $seminar->nilai_huruf,
        ]);
    }

    public function updateStatusLulus(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Validasi input
        $request->validate([
            'is_lulus' => 'required|boolean',
        ]);

        // Update status lulus
        $seminar->is_lulus = $request->is_lulus;
        $seminar->save();

        return response()->json([
            'success' => true,
            'message' => 'Status lulus berhasil diupdate',
        ]);
    }

    public function submit(Request $request, $token)
    {
        $sesi = SesiSeminar::where('token_penilaian', $token)->first();

        if (!$sesi || $sesi->is_token_used) {
            return response()->json([
                'success' => false,
                'message' => 'Link tidak valid atau sudah kadaluarsa.'
            ], 400);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)->get();
        $penilaian = $request->input('penilaian', []);

        // Validasi semua mahasiswa sudah dinilai
        foreach ($seminars as $seminar) {
            if (!isset($penilaian[$seminar->id]) || 
                !isset($penilaian[$seminar->id]['nilai']) || 
                $penilaian[$seminar->id]['nilai'] === '' || 
                $penilaian[$seminar->id]['nilai'] < 0 || 
                $penilaian[$seminar->id]['nilai'] > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua mahasiswa harus dinilai dengan nilai 0-100 sebelum submit.'
                ], 400);
            }
        }

        // Simpan penilaian
        foreach ($seminars as $seminar) {
            $data = $penilaian[$seminar->id];
             
            // Update nilai seminar - menunggu validasi himpunan
            $seminar->nilai_seminar = $data['nilai'];
            $seminar->status_seminar = Seminar::STATUS_MENUNGGU_VALIDASI_HIMPUNAN;
            $seminar->catatan_penguji = $data['catatan'] ?? null;
            $seminar->save();

            // Simpan review dari penguji
            ReviewSeminar::create([
                'seminar_id' => $seminar->id,
                'dosen_id' => $sesi->dosen_penguji_id,
                'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                'status' => ReviewSeminar::DITERIMA,
                'status_hasil' => 'diterima',
                'nilai_angka' => $data['nilai'],
                'catatan_penguji' => $data['catatan'] ?? null,
                'is_dinilai' => true,
                'tanggal_acc' => now(),
            ]);

            // Kirim notifikasi ke mahasiswa
            if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'message' => "Seminar KP Anda telah dinilai oleh dosen penguji!<br>Nilai Seminar: <b>{$data['nilai']}</b><br><br>Menunggu validasi dari Himpunan untuk melanjutkan proses KP.",
                ]);
            }
        }

        // Invalidate token
        $sesi->invalidateToken();

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil disimpan. Terima kasih.'
        ]);
    }
}
