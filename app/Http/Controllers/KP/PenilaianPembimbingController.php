<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Jilid;

// Import Shared Models
use App\Models\Dosen;
use App\Models\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianPembimbingController extends \App\Http\Controllers\Controller
{
    /**
     * Halaman daftar mahasiswa yang perlu dinilai oleh dosen pembimbing
     * Syarat: Semua bimbingan sudah ACC (untuk reguler dan karyawan)
     */
    public function index()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        // Ambil semua mahasiswa bimbingan KP dosen ini
        $mahasiswasRaw = $dosen->mahasiswasKP()
            ->wherePivot('status', 'pembimbing')
            ->with(['seminarKP', 'jilidKP', 'pengajuansKP' => function($q) {
                $q->where('status', 'diterima');
            }])
            ->get();

        // Filter mahasiswa yang layak dinilai (semua bimbingan sudah ACC)
        $mahasiswas = $mahasiswasRaw->filter(function ($mahasiswa) {
            return AppHelper::check_bimbingan_kp_is_complete($mahasiswa);
        });

        return view('kp.pages.dosen.penilaian.index', [
            'title' => 'Penilaian Pembimbing',
            'active' => 'penilaian-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    /**
     * Form penilaian untuk mahasiswa tertentu
     */
    public function create($mahasiswa_id)
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $mahasiswa = Mahasiswa::with(['seminarKP', 'jilidKP', 'pengajuansKP', 'dosens'])->findOrFail($mahasiswa_id);

        // Cek apakah dosen ini adalah pembimbing mahasiswa
        $isPembimbing = $mahasiswa->dosens()
            ->where('dosen_id', $dosen->id)
            ->whereIn('status', ['pembimbing', 'utama'])
            ->exists();

        if (!$isPembimbing) {
            return back()->with('warning', 'Anda bukan dosen pembimbing mahasiswa ini');
        }

        // Cek kelayakan penilaian (semua bimbingan harus ACC)
        if (!AppHelper::check_bimbingan_kp_is_complete($mahasiswa)) {
            return back()->with('warning', 'Mahasiswa belum menyelesaikan semua bimbingan KP');
        }

        $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();

        return view('kp.pages.dosen.penilaian.index', [
            'title' => 'Form Penilaian Pembimbing',
            'active' => 'penilaian-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'jilid' => $mahasiswa->jilidKP,
        ]);
    }

    /**
     * Simpan nilai pembimbing
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'nilai_pembimbing' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $mahasiswa = Mahasiswa::with(['jilidKP', 'seminarKP'])->findOrFail($request->mahasiswa_id);

        // Cek apakah dosen ini adalah pembimbing mahasiswa KP
        $isPembimbing = $mahasiswa->dosens()
            ->where('dosen_id', $dosen->id)
            ->where('status', 'pembimbing')
            ->exists();

        if (!$isPembimbing) {
            return back()->with('warning', 'Anda bukan dosen pembimbing KP mahasiswa ini');
        }

        // Cek kelayakan penilaian (semua bimbingan harus ACC)
        if (!AppHelper::check_bimbingan_kp_is_complete($mahasiswa)) {
            return back()->with('warning', 'Mahasiswa belum menyelesaikan semua bimbingan KP');
        }

        // Update atau buat jilid draft jika belum ada
        if ($mahasiswa->jilidKP) {
            $mahasiswa->jilidKP->update([
                'nilai_pembimbing' => $request->nilai_pembimbing,
                'catatan' => $request->catatan,
            ]);
            // Trigger hitung nilai akhir jika komponen lain lengkap
            $mahasiswa->jilidKP->hitungNilaiAkhir();
        } else {
            // Buat jilid draft (hanya nilai, belum ada dokumen)
            $jilid = Jilid::create([
                'mahasiswa_id' => $mahasiswa->id,
                'nilai_pembimbing' => $request->nilai_pembimbing,
                'catatan' => $request->catatan,
                'status' => Jilid::JILID_DRAFT, // Status draft
            ]);
            // Trigger hitung nilai akhir
            $jilid->hitungNilaiAkhir();
        }

        // Kirim email notifikasi ke mahasiswa
        if ($mahasiswa->email && $mahasiswa->email != '-') {
            try {
                AppHelper::instance()->send_mail([
                    'mail' => $mahasiswa->email,
                    'subject' => 'Nilai Pembimbing KP',
                    'title' => 'EKAPTA',
                    'message' => 'Dosen pembimbing telah memberikan nilai untuk Kerja Praktek Anda. Nilai: ' . $request->nilai_pembimbing,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return redirect()->route('kp.penilaian.pembimbing.index')->with('success', 'Nilai pembimbing berhasil disimpan');
    }
}

