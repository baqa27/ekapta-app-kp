<?php

namespace App\Http\Controllers\KP;

use App\Helpers\StorageHelper;
use App\Helpers\AppHelper;
use App\Models\KP\AjuanBimbinganManualKP;
use App\Models\KP\Bimbingan;
use App\Models\KP\Pengajuan;
use App\Models\KP\Pendaftaran;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Bimbingan Manual KP
 *
 * ALUR BIMBINGAN MANUAL KP:
 *
 * 1. SUBMIT LAPORAN
 *    - Mahasiswa upload file laporan (sama seperti bimbingan online)
 *    - Masuk ke tabel bimbingan_kps dengan status pending/review
 *
 * 2. BIMBINGAN MANUAL
 *    - Setelah submit laporan berhasil, tombol "Bimbingan Manual" muncul
 *    - Mahasiswa upload foto lembar bimbingan dari dosen
 *    - Mahasiswa isi tanggal bimbingan
 *    - Mahasiswa pilih status (Revisi/ACC) dari hasil offline
 *
 * 3. PENGAJUAN
 *    - Setiap pengiriman lembar bimbingan = 1 pengajuan
 *    - Status awal: pending
 *    - Mahasiswa bisa kirim berkali-kali (history)
 *
 * 4. REVIEW ADMIN/PRODI
 *    - Di halaman yang sama, review kesesuaian file laporan dengan lembar bimbingan
 *    - ACC → pengajuan sah, masuk progres, bisa dicetak
 *    - Tolak → status berubah, mahasiswa bisa kirim ulang
 *
 * 5. LANJUT BAB
 *    - Jika bimbingan ACC, mahasiswa lanjut ke BAB berikutnya
 */
class BimbinganManualController extends \App\Http\Controllers\Controller
{
    /**
     * Form submit bimbingan manual
     * Mahasiswa upload foto lembar bimbingan dari dosen
     */
    public function create($bimbingan_id)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $bimbingan = Bimbingan::with(['bagian', 'dosens'])->findOrFail($bimbingan_id);

        // Pastikan bimbingan milik mahasiswa
        if ($bimbingan->mahasiswa_id != $mahasiswa->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        // Pastikan sudah submit laporan (bimbingan sudah ada)
        if (!$bimbingan->lampiran) {
            return back()->with('warning', 'Silahkan submit file laporan terlebih dahulu');
        }

        // Ambil dosen pembimbing
        $dosen = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosen) {
            $dosen = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Ambil riwayat pengajuan bimbingan manual untuk bimbingan ini
        $history = AjuanBimbinganManualKP::with(['bimbingan'])
            ->where('bimbingan_id', $bimbingan->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kp.pages.mahasiswa.bimbingan.bimbingan-manual', [
            'title' => 'Bimbingan Manual',
            'active' => 'bimbingan-kp',
            'bimbingan' => $bimbingan,
            'dosen' => $dosen,
            'history' => $history,
        ]);
    }

    /**
     * Store pengajuan bimbingan manual
     */
    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $bimbingan = Bimbingan::findOrFail($request->bimbingan_id);

        // Pastikan bimbingan milik mahasiswa
        if ($bimbingan->mahasiswa_id != $mahasiswa->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        $request->validate([
            'foto_lembar_bimbingan' => 'required|mimes:pdf,jpg,jpeg,png|max:5000',
            'tanggal_bimbingan' => 'required|date',
            'status_mahasiswa' => 'required|in:revisi,acc',
            'keterangan' => 'required|string|max:1000',
        ]);

        // Upload foto lembar bimbingan
        $fotoPath = StorageHelper::storeKpFile($request->file('foto_lembar_bimbingan'), $mahasiswa->nim, 'bimbingan');

        // Pastikan file berhasil di-upload
        if (!$fotoPath) {
            return back()->with('error', 'Gagal upload file lembar bimbingan');
        }

        // Buat pengajuan bimbingan manual
        $ajuan = new AjuanBimbinganManualKP;
        $ajuan->bimbingan_id = $bimbingan->id;
        $ajuan->mahasiswa_id = $mahasiswa->id;
        $ajuan->foto_lembar_bimbingan = $fotoPath;
        $ajuan->tanggal_bimbingan = $request->tanggal_bimbingan;
        $ajuan->status_mahasiswa = $request->status_mahasiswa;
        $ajuan->status = AjuanBimbinganManualKP::PENDING;
        $ajuan->keterangan = $request->keterangan;
        $ajuan->save();

        return redirect()->route('kp.bimbingan-manual.create', $bimbingan->id)
            ->with('success', 'Lembar bimbingan berhasil disubmit. Menunggu review dari Admin/Prodi.');
    }

    /**
     * Detail pengajuan bimbingan manual
     */
    public function detail($id)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $ajuan = AjuanBimbinganManualKP::with(['bimbingan', 'bimbingan.bagian'])->findOrFail($id);

        // Pastikan ajuan milik mahasiswa
        if ($ajuan->mahasiswa_id != $mahasiswa->id) {
            return back()->with('warning', 'Data tidak ditemukan');
        }

        return view('kp.pages.mahasiswa.bimbingan.detail-bimbingan-manual', [
            'title' => 'Detail Bimbingan Manual',
            'active' => 'bimbingan-kp',
            'ajuan' => $ajuan,
        ]);
    }

    // ==================== ADMIN/PRODI REVIEW ====================

    /**
     * Halaman list pengajuan bimbingan manual untuk review
     * Admin dan Prodi di halaman yang sama
     */
    public function reviewIndex()
    {
        // Cek HANYA berdasarkan route name atau referer untuk menentukan sidebar
        // JANGAN pakai Auth::guard karena bisa confusing (session berbagi)
        $routeName = request()->route()->getName();
        $referer = request()->headers->get('referer');

        // Cek apakah route/referer mengandung 'prodi'
        $isProdi = (str_contains($routeName, 'prodi') && !str_contains($routeName, 'admin'))
                   || ($referer && str_contains($referer, 'prodi') && !str_contains($referer, 'admin'))
                   || (Auth::guard('prodi')->check() && !Auth::guard('admin')->check());

        if ($isProdi) {
            $prodi = Auth::guard('prodi')->user();
            $sidebar = 'kp.partials.sidebarProdi';

            // Filter berdasarkan prodi
            $ajuan_pending = AjuanBimbinganManualKP::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where(function($query) use ($prodi) {
                    $query->where('prodi', $prodi->namaprodi)
                          ->orWhere('prodi', $prodi->kode)
                          ->orWhere('prodi_id', $prodi->id);
                });
            })->where('status', AjuanBimbinganManualKP::PENDING)
              ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
              ->orderBy('created_at', 'asc')
              ->get();

            $ajuan_acc = AjuanBimbinganManualKP::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where(function($query) use ($prodi) {
                    $query->where('prodi', $prodi->namaprodi)
                          ->orWhere('prodi', $prodi->kode)
                          ->orWhere('prodi_id', $prodi->id);
                });
            })->where('status', AjuanBimbinganManualKP::ACC)
              ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
              ->orderBy('tanggal_review', 'desc')
              ->get();

            $ajuan_revisi = AjuanBimbinganManualKP::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where(function($query) use ($prodi) {
                    $query->where('prodi', $prodi->namaprodi)
                          ->orWhere('prodi', $prodi->kode)
                          ->orWhere('prodi_id', $prodi->id);
                });
            })->where('status', AjuanBimbinganManualKP::DITOLAK)
              ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
              ->orderBy('tanggal_review', 'desc')
              ->get();
        } else {
            $sidebar = 'kp.partials.sidebarAdmin';

            // Admin: semua pengajuan
            $ajuan_pending = AjuanBimbinganManualKP::where('status', AjuanBimbinganManualKP::PENDING)
                ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
                ->orderBy('created_at', 'asc')
                ->get();

            $ajuan_acc = AjuanBimbinganManualKP::where('status', AjuanBimbinganManualKP::ACC)
                ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
                ->orderBy('tanggal_review', 'desc')
                ->get();

            $ajuan_revisi = AjuanBimbinganManualKP::where('status', AjuanBimbinganManualKP::DITOLAK)
                ->with(['mahasiswa', 'bimbingan', 'bimbingan.bagian'])
                ->orderBy('tanggal_review', 'desc')
                ->get();
        }

        return view('kp.pages.admin.bimbingan.review-bimbingan-manual', [
            'title' => 'Review Bimbingan Manual KP',
            'active' => 'bimbingan-manual-kp',
            'sidebar' => $sidebar,
            'module' => 'kp',
            'ajuan_pending' => $ajuan_pending,
            'ajuan_acc' => $ajuan_acc,
            'ajuan_revisi' => $ajuan_revisi,
        ]);
    }

    /**
     * Detail review pengajuan bimbingan manual
     * Admin/Prodi bisa melihat:
     * - File laporan
     * - Daftar lembar bimbingan per tanggal
     * - Status yang dipilih mahasiswa
     * - Catatan dari admin/prodi
     */
    public function reviewDetail($id)
    {
        $ajuan = AjuanBimbinganManualKP::with(['mahasiswa', 'bimbingan', 'bimbingan.bagian', 'bimbingan.dosens'])
            ->findOrFail($id);

        $mahasiswa = $ajuan->mahasiswa;
        $bimbingan = $ajuan->bimbingan;

        // Ambil dosen pembimbing
        $dosen = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosen) {
            $dosen = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Ambil pengajuan judul KP
        $pengajuan = Pengajuan::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'diterima')
            ->first();

        // Ambil semua history pengajuan bimbingan manual untuk bimbingan ini
        $history = AjuanBimbinganManualKP::with(['bimbingan'])
            ->where('bimbingan_id', $bimbingan->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Cek HANYA berdasarkan route name atau referer untuk menentukan sidebar
        // JANGAN pakai Auth::guard karena bisa confusing (session berbagi)
        $routeName = request()->route()->getName();
        $referer = request()->headers->get('referer');

        // Cek apakah route/referer mengandung 'prodi'
        $isProdi = (str_contains($routeName, 'prodi') && !str_contains($routeName, 'admin'))
                   || ($referer && str_contains($referer, 'prodi') && !str_contains($referer, 'admin'))
                   || (Auth::guard('prodi')->check() && !Auth::guard('admin')->check());

        if ($isProdi) {
            $sidebar = 'kp.partials.sidebarProdi';
        } else {
            $sidebar = 'kp.partials.sidebarAdmin';
        }

        return view('kp.pages.admin.bimbingan.detail-bimbingan-manual', [
            'title' => 'Review Bimbingan Manual',
            'active' => 'bimbingan-manual-kp',
            'sidebar' => $sidebar,
            'module' => 'kp',
            'ajuan' => $ajuan,
            'mahasiswa' => $mahasiswa,
            'bimbingan' => $bimbingan,
            'dosen' => $dosen,
            'pengajuan' => $pengajuan,
            'history' => $history,
        ]);
    }

    /**
     * ACC pengajuan bimbingan manual
     *
     * ALUR FINAL:
     * - Admin/Prodi ACC → Pengajuan sah, Status BAB = Diterima, Lanjut BAB berikutnya
     * - Status mahasiswa (revisi/acc) hanya info dari dosen offline
     */
    public function acc(Request $request)
    {
        $ajuan = AjuanBimbinganManualKP::findOrFail($request->id);

        if ($ajuan->status == AjuanBimbinganManualKP::ACC) {
            return back()->with('warning', 'Pengajuan sudah di-ACC');
        }

        // Tentukan reviewer
        if (Auth::guard('prodi')->check()) {
            $reviewer_type = 'prodi';
            $reviewed_by = Auth::guard('prodi')->user()->id;
        } else {
            $reviewer_type = 'admin';
            $reviewed_by = Auth::guard('admin')->user()->id;
        }

        $validated = $this->validateManualReviewRequest($request, true);

        // Update status pengajuan
        $ajuan->update([
            'status_mahasiswa' => $validated['status_mahasiswa'],
            'keterangan' => $validated['keterangan'],
            'status' => AjuanBimbinganManualKP::ACC,
            'catatan_reviewer' => $validated['catatan'],
            'reviewed_by' => $reviewed_by,
            'reviewer_type' => $reviewer_type,
            'tanggal_review' => now(),
        ]);

        $bimbingan = $ajuan->bimbingan;

        // ALUR FINAL:
        // Status BAB mengikuti pilihan mahasiswa (status_mahasiswa)
        // - Jika mahasiswa pilih ACC → Status BAB = diterima, bisa lanjut BAB
        // - Jika mahasiswa pilih Revisi → Status BAB = revisi, harus submit ulang
        $statusBimbingan = ($validated['status_mahasiswa'] == AjuanBimbinganManualKP::STATUS_MAHASISWA_ACC) ? 'diterima' : 'revisi';

        $bimbingan->update([
            'status' => $statusBimbingan,
            'tanggal_acc' => ($statusBimbingan == 'diterima') ? Carbon::parse($validated['tanggal_acc'])->setTime(now()->hour, now()->minute, now()->second) : null,
            'tanggal_manual_acc' => $ajuan->tanggal_bimbingan,
        ]);

        // Kirim email notifikasi
        if ($ajuan->mahasiswa->email != '-') {
            if ($statusBimbingan == 'diterima') {
                AppHelper::instance()->send_mail([
                    'mail' => $ajuan->mahasiswa->email,
                    'subject' => 'Bimbingan Manual Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Selamat! Bimbingan Manual Kerja Praktek Anda untuk <b>'.$bimbingan->bagian->bagian.'</b> telah di-ACC. Silahkan lanjutkan ke bab berikutnya.',
                ]);
            } else {
                AppHelper::instance()->send_mail([
                    'mail' => $ajuan->mahasiswa->email,
                    'subject' => 'Bimbingan Manual Kerja Praktek',
                    'title' => 'EKAPTA',
                    'message' => 'Bimbingan Manual Kerja Praktek Anda untuk <b>'.$bimbingan->bagian->bagian.'</b> telah diverifikasi. Status dari dosen: REVISI. Silahkan perbaiki dan submit ulang.',
                ]);
            }
        }

        $successMsg = ($statusBimbingan == 'diterima')
            ? 'Pengajuan bimbingan manual berhasil di-ACC. Mahasiswa bisa lanjut ke BAB berikutnya.'
            : 'Pengajuan bimbingan manual berhasil diverifikasi. Status dari dosen: REVISI. Mahasiswa harus submit ulang.';

        // Redirect ke URL yang dikirim dari form
        $redirectUrl = $request->input('redirect_url', url()->previous());
        return redirect($redirectUrl)->with('success', $successMsg);
    }

    /**
     * Revisi/Tolak pengajuan bimbingan manual
     */
    public function revisi(Request $request)
    {
        $ajuan = AjuanBimbinganManualKP::findOrFail($request->id);

        if ($ajuan->status == AjuanBimbinganManualKP::ACC) {
            return back()->with('warning', 'Pengajuan sudah di-ACC, tidak bisa direvisi');
        }

        $validated = $this->validateManualReviewRequest($request);

        // Tentukan reviewer
        if (Auth::guard('prodi')->check()) {
            $reviewer_type = 'prodi';
            $reviewed_by = Auth::guard('prodi')->user()->id;
        } else {
            $reviewer_type = 'admin';
            $reviewed_by = Auth::guard('admin')->user()->id;
        }

        // Update status pengajuan menjadi DITOLAK
        $ajuan->update([
            'status_mahasiswa' => $validated['status_mahasiswa'],
            'keterangan' => $validated['keterangan'],
            'status' => AjuanBimbinganManualKP::DITOLAK,
            'catatan_reviewer' => $validated['catatan'],
            'reviewed_by' => $reviewed_by,
            'reviewer_type' => $reviewer_type,
            'tanggal_review' => now(),
        ]);

        // TIDAK MENGUBAH STATUS BIMBINGAN
        // Jika ajuan ditolak karena lembar bimbingan salah, status tetap
        // Tapi jika admin/prodi bilang file laporan juga salah, 
        // maka status bimbingan harus diubah ke revisi agar mahasiswa bisa submit ulang
        // 
        // LOGIKA: Jika status bimbingan saat ini adalah 'review', 
        // berarti file laporan baru disubmit dan belum pernah di-ACC
        // Jika ditolak, kembalikan ke 'revisi' agar mahasiswa bisa submit ulang file laporan
        if ($ajuan->bimbingan->status == 'review') {
            $ajuan->bimbingan->update([
                'status' => 'revisi',
            ]);
            // Refresh untuk mendapatkan data terbaru
            $ajuan->bimbingan->refresh();
        }
        // Jika status sudah 'revisi' atau 'diterima', tidak perlu diubah

        // Kirim email notifikasi
        $bimbingan = $ajuan->bimbingan;
        $statusMessage = ($bimbingan->status == 'revisi') 
            ? 'Status BAB berubah ke REVISI. Silahkan perbaiki file laporan dan upload ulang.' 
            : 'Silahkan upload ulang lembar bimbingan yang benar.';
        
        if ($ajuan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $ajuan->mahasiswa->email,
                'subject' => 'Bimbingan Manual Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Pengajuan lembar bimbingan manual Anda untuk <b>'.$bimbingan->bagian->bagian.'</b> DITOLAK. Catatan: '.$validated['catatan'].'. '.$statusMessage,
            ]);
        }

        $successMsg = ($bimbingan->status == 'revisi')
            ? 'Pengajuan bimbingan manual berhasil ditolak. Status BAB berubah ke REVISI. Mahasiswa harus submit ulang file laporan.'
            : 'Pengajuan bimbingan manual berhasil ditolak. Mahasiswa harus upload ulang lembar bimbingan yang benar.';

        // Redirect ke URL yang dikirim dari form
        $redirectUrl = $request->input('redirect_url', url()->previous());
        return redirect($redirectUrl)->with('success', $successMsg);
    }

    /**
     * Cancel ACC pengajuan bimbingan manual
     */
    public function cancelAcc(Request $request)
    {
        $ajuan = AjuanBimbinganManualKP::findOrFail($request->id);

        if ($ajuan->status != AjuanBimbinganManualKP::ACC) {
            return back()->with('warning', 'Pengajuan tidak dalam status ACC');
        }

        // Update status pengajuan kembali ke pending
        $ajuan->update([
            'status' => AjuanBimbinganManualKP::PENDING,
            'catatan_reviewer' => null,
            'reviewed_by' => null,
            'reviewer_type' => null,
            'tanggal_review' => null,
        ]);

        // Update bimbingan kembali ke review
        $bimbingan = $ajuan->bimbingan;
        $bimbingan->update([
            'status' => 'review',
            'tanggal_acc' => null,
            'tanggal_manual_acc' => null,
        ]);

        // Redirect ke halaman sebelumnya (tetap di halaman yang sama)
        return redirect()->back()->with('success', 'ACC pengajuan berhasil dibatalkan');
    }

    /**
     * Validasi field review yang boleh disesuaikan oleh admin/prodi.
     */
    private function validateManualReviewRequest(Request $request, bool $requireTanggalAcc = false): array
    {
        $rules = [
            'status_mahasiswa' => 'required|in:'.implode(',', [
                AjuanBimbinganManualKP::STATUS_MAHASISWA_REVISI,
                AjuanBimbinganManualKP::STATUS_MAHASISWA_ACC,
            ]),
            'keterangan' => 'required|string|max:1000',
            'catatan' => 'required|string',
        ];

        if ($requireTanggalAcc) {
            $rules['tanggal_acc'] = 'required|date';
        }

        return $request->validate($rules);
    }
}
