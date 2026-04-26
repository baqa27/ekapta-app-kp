<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Seminar;
use App\Models\KP\SesiSeminar;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiSeminar;
use App\Models\KP\MetodePembayaran;

// Import Shared Models
use App\Models\KP\Himpunan;
use App\Models\Dosen;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HimpunanController extends \App\Http\Controllers\Controller
{
    public function account()
    {
        $himpunan = Auth::guard('himpunan')->user();

        return view('kp.pages.himpunan.account', [
            'title' => 'Pengaturan Akun',
            'active' => 'account',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'module' => 'kp',
            'himpunan' => $himpunan,
        ]);
    }

    public function accountUpdate(Request $request, $id)
    {
        $himpunan = Auth::guard('himpunan')->user();

        abort_unless($himpunan && (int) $himpunan->id === (int) $id, 403);

        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('himpunan_kps', 'email')->ignore($himpunan->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $updateData = [
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'] ?: null,
        ];

        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $himpunan->forceFill($updateData)->save();
        Auth::guard('himpunan')->setUser($himpunan->fresh());

        return back()->with('success', 'Akun himpunan berhasil diperbarui');
    }

    /**
     * Menampilkan daftar seminar untuk verifikasi himpunan
     */
    public function seminarIndex()
    {
        $seminars_review = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVIEW)->get();
        $seminars_revisi = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVISI)->get();
        $seminars_acc = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->get();
        
        $himpunan = Auth::guard('himpunan')->user();
        $is_pendaftaran_open = $himpunan ? $himpunan->is_pendaftaran_seminar_open : true;

        return view('kp.pages.himpunan.seminar.index', [
            'title' => 'Verifikasi Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars_review' => $seminars_review,
            'seminars_revisi' => $seminars_revisi,
            'seminars_acc' => $seminars_acc,
            'is_pendaftaran_open' => $is_pendaftaran_open,
        ]);
    }

    /**
     * Toggle buka/tutup pendaftaran seminar
     */
    public function togglePendaftaranSeminar()
    {
        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        $himpunan->is_pendaftaran_seminar_open = !$himpunan->is_pendaftaran_seminar_open;
        $himpunan->save();

        $status = $himpunan->is_pendaftaran_seminar_open ? 'DIBUKA' : 'DITUTUP';
        return back()->with('success', "Pendaftaran Seminar KP sekarang $status");
    }

    /**
     * Menampilkan detail seminar untuk review
     */
    public function seminarReview($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'pengajuan', 'revisis'])->findOrFail($id);
        $revisis = $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5);

        return view('kp.pages.himpunan.seminar.review', [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminar' => $seminar,
            'revisis' => $revisis,
        ]);
    }

    /**
     * ACC/Validasi seminar oleh himpunan
     */
    public function seminarAcc(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([
            'is_valid' => Seminar::DITERIMA,
            'status_seminar' => Seminar::STATUS_DITERIMA,
            'tanggal_acc' => now(),
        ]);

        // Kirim email notifikasi ke mahasiswa (optional)
        try {
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'subject' => 'Seminar KP Divalidasi',
                    'title' => 'Seminar KP Divalidasi',
                    'message' => 'Selamat pendaftaran Seminar Kerja Praktek anda sudah divalidasi oleh Himpunan. Silahkan tunggu jadwal seminar.',
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Email notifikasi ACC seminar gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Seminar berhasil divalidasi');
    }

    /**
     * Revisi seminar oleh himpunan
     */
    public function seminarRevisi(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([
            'is_valid' => Seminar::REVISI,
            'status_seminar' => Seminar::STATUS_REVISI,
        ]);

        RevisiSeminar::create([
            'seminar_id' => $seminar->id,
            'catatan' => $request->catatan,
        ]);

        // Kirim email notifikasi ke mahasiswa (optional, tidak akan mengganggu proses jika gagal)
        try {
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'subject' => 'Seminar KP Perlu Revisi',
                    'title' => 'Seminar KP Perlu Revisi',
                    'message' => 'Pendaftaran Seminar Kerja Praktek anda perlu direvisi. Silahkan cek catatan revisi di sistem.',
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Email notifikasi revisi seminar gagal: ' . $e->getMessage());
        }

        return back()->with('success', 'Revisi berhasil disimpan');
    }

    /**
     * Set jadwal seminar (tanggal, tempat, penguji)
     */
    public function setJadwalSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required',
        ]);
        
        $validatedData['tanggal_ujian'] = Carbon::parse($request->tanggal_ujian);
        $seminar->update($validatedData);

        // Kirim email notifikasi ke mahasiswa (optional)
        try {
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'subject' => 'Jadwal Seminar KP',
                    'title' => 'Jadwal Seminar KP',
                    'message' => 'Jadwal Seminar Kerja Praktek anda sudah ditentukan. <br>Tanggal: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat: <b>' . $request->tempat_ujian . '</b>',
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Email notifikasi jadwal seminar gagal: ' . $e->getMessage());
        }

        // Kirim email ke dosen penguji
        foreach ($seminar->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review) {
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'subject' => 'Penunjukan Penguji Seminar KP',
                    'title' => 'Penunjukan Penguji Seminar KP',
                    'message' => 'Anda ditunjuk sebagai penguji Seminar KP. <br>Mahasiswa: <b>' . $seminar->mahasiswa->nama . '</b><br>Judul: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat: <b>' . $request->tempat_ujian . '</b>',
                ]);
            }
        }

        return back()->with('success', 'Jadwal seminar berhasil disimpan');
    }

    /**
     * Ploting dosen penguji seminar
     */
    public function plotingPenguji(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Cek apakah sudah ada penguji yang sudah memberikan nilai
        $reviews_check = $seminar->reviews()
            ->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])
            ->where('dosen_status', 'penguji')
            ->get();

        if (count($reviews_check) != 0) {
            return back()->with('warning', 'Dosen penguji sudah memberikan penilaian, tidak bisa diubah');
        }

        // Hapus penguji lama
        $dosens_penguji = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get();
        foreach ($dosens_penguji as $dosen) {
            $dosen->delete();
        }

        // Tambah penguji baru
        for ($i = 0; $i < count($request->dosen_penguji); $i++) {
            if ($request->dosen_penguji[$i] != null) {
                ReviewSeminar::create([
                    'seminar_id' => $seminar->id,
                    'dosen_id' => $request->dosen_penguji[$i],
                    'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                    'status' => ReviewSeminar::REVIEW,
                ]);
            }
        }

        return back()->with('success', 'Ploting dosen penguji berhasil');
    }

    /**
     * Rekap seminar untuk himpunan
     */
    public function rekapSeminar()
    {
        $seminars = Seminar::with(['mahasiswa', 'pengajuan'])->orderBy('created_at', 'desc')->get();

        return view('kp.pages.himpunan.seminar.rekap', [
            'title' => 'Rekap Seminar KP',
            'active' => 'rekap-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars' => $seminars,
        ]);
    }

    /**
     * Halaman penjadwalan sesi seminar
     */
    public function jadwalIndex()
    {
        $sesi_seminars = SesiSeminar::with(['seminars.mahasiswa', 'dosenPenguji'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Mahasiswa yang siap dijadwalkan (diterima tapi belum ada sesi)
        // EXCLUDE mahasiswa karyawan (tidak perlu seminar)
        $seminars_siap = Seminar::where('is_valid', Seminar::DITERIMA)
            ->whereNull('sesi_seminar_id')
            ->whereHas('mahasiswa.pendaftaransKP', function($q) {
                $q->where('status', 'diterima')
                  ->where('jenis_mahasiswa', '!=', 'karyawan');
            })
            ->with(['mahasiswa', 'pengajuan'])
            ->get();

        // Filter dosen berdasarkan prodi himpunan yang login
        $himpunan = Auth::guard('himpunan')->user();
        $dosens = Dosen::whereHas('prodis', function($q) use ($himpunan) {
            $q->where('prodis.id', $himpunan->prodi_id);
        })->orderBy('nama')->get();

        return view('kp.pages.himpunan.seminar.jadwal', [
            'title' => 'Penjadwalan Seminar KP',
            'active' => 'jadwal-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'sesi_seminars' => $sesi_seminars,
            'seminars_siap' => $seminars_siap,
            'dosens' => $dosens,
        ]);
    }

    /**
     * Buat sesi seminar baru
     */
    public function createSesi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tempat' => 'required|string',
            'dosen_penguji_id' => 'required|exists:dosens,id',
            'jumlah_mahasiswa' => 'required|integer|min:1|max:20',
            'seminars' => 'required|array|min:1',
        ]);

        // Generate nama sesi otomatis
        $tanggal = Carbon::parse($request->tanggal);
        $nama_sesi = 'Sesi ' . $tanggal->translatedFormat('d M Y') . ' - ' . $request->jam_mulai;

        // Buat sesi seminar (token_penilaian auto-generated di model boot)
        $sesi = SesiSeminar::create([
            'nama_sesi' => $nama_sesi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tempat' => $request->tempat,
            'dosen_penguji_id' => $request->dosen_penguji_id,
            'jumlah_mahasiswa' => $request->jumlah_mahasiswa,
            'catatan_teknis' => $request->catatan_teknis,
        ]);

        // Assign mahasiswa ke sesi dan set urutan presentasi
        $urutan = 1;
        foreach ($request->seminars as $seminar_id) {
            $seminar = Seminar::find($seminar_id);
            if ($seminar) {
                $seminar->update([
                    'sesi_seminar_id' => $sesi->id,
                    'urutan_presentasi' => $urutan,
                    'tanggal_ujian' => $request->tanggal . ' ' . $request->jam_mulai,
                    'tempat_ujian' => $request->tempat,
                    'status_seminar' => Seminar::STATUS_DIJADWALKAN,
                ]);

                // Kirim notifikasi ke mahasiswa
                if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                    $dosenPenguji = $sesi->dosenPenguji;
                    $namaPenguji = $dosenPenguji ? $dosenPenguji->nama . ', ' . $dosenPenguji->gelar : '-';
                    
                    AppHelper::instance()->send_mail([
                        'mail' => $seminar->mahasiswa->email,
                        'subject' => 'Jadwal Seminar KP',
                        'title' => 'Jadwal Seminar KP',
                        'message' => "Jadwal Seminar KP Anda sudah ditentukan.<br><br>
                            Tanggal: <b>" . Carbon::parse($request->tanggal)->translatedFormat('l, d F Y') . "</b><br>
                            Waktu: <b>{$request->jam_mulai} - {$request->jam_selesai} WIB</b><br>
                            Tempat/Link Online: <b>{$request->tempat}</b><br>
                            Dosen Penguji: <b>{$namaPenguji}</b><br>
                            Urutan Presentasi: <b>{$urutan}</b>",
                    ]);
                }

                $urutan++;
            }
        }

        return back()->with('success', 'Sesi seminar berhasil dibuat. Link penilaian: ' . $sesi->link_penilaian);
    }

    /**
     * Detail sesi seminar
     */
    public function detailSesi($id)
    {
        $sesi = SesiSeminar::with(['seminars.mahasiswa', 'seminars.pengajuan', 'dosenPenguji'])->findOrFail($id);

        return view('kp.pages.himpunan.seminar.detail-sesi', [
            'title' => 'Detail Sesi Seminar',
            'active' => 'jadwal-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'sesi' => $sesi,
        ]);
    }

    /**
     * Validasi selesai seminar setelah link penilaian digunakan
     * Menandai seminar sebagai selesai dan mahasiswa bisa lanjut ke tahap berikutnya
     */
    public function validasiSelesaiSeminar(Request $request)
    {
        $sesi = SesiSeminar::findOrFail($request->sesi_id);

        // Cek apakah link sudah digunakan
        if (!$sesi->is_token_used) {
            return back()->with('warning', 'Link penilaian belum digunakan oleh dosen penguji.');
        }

        // Update status semua seminar dalam sesi ini menjadi selesai
        foreach ($sesi->seminars as $seminar) {
            // Cek apakah seminar sudah dinilai (status menunggu validasi himpunan)
            if ($seminar->status_seminar !== Seminar::STATUS_MENUNGGU_VALIDASI_HIMPUNAN) {
                continue;
            }

            $seminar->update([
                'status_seminar' => Seminar::STATUS_SELESAI_SEMINAR,
                'is_lulus' => 1,
            ]);

            // Kirim notifikasi ke mahasiswa
            try {
                if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $seminar->mahasiswa->email,
                        'subject' => 'Seminar KP Selesai',
                        'title' => 'Seminar KP Selesai',
                        'message' => "Selamat! Seminar KP Anda telah selesai dan divalidasi oleh Himpunan.<br>
                            Silahkan upload nilai dari instansi untuk melengkapi nilai akhir KP Anda.",
                    ]);
                }
            } catch (\Exception $e) {
                \Log::warning('Email notifikasi selesai seminar gagal: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Seminar berhasil divalidasi selesai. Mahasiswa dapat melanjutkan ke tahap berikutnya.');
    }

    /**
     * Hapus sesi seminar (hanya jika belum digunakan)
     */
    public function deleteSesi($id)
    {
        $sesi = SesiSeminar::findOrFail($id);

        // Cek apakah token sudah digunakan
        if ($sesi->is_token_used) {
            return back()->with('error', 'Sesi seminar tidak bisa dihapus karena sudah digunakan untuk penilaian.');
        }

        // Reset seminar yang terkait ke status sebelumnya
        foreach ($sesi->seminars as $seminar) {
            $seminar->update([
                'sesi_seminar_id' => null,
                'urutan_presentasi' => null,
                'tanggal_ujian' => null,
                'tempat_ujian' => null,
                'status_seminar' => Seminar::STATUS_DITERIMA,
            ]);
        }

        // Hapus sesi
        $sesi->delete();

        return back()->with('success', 'Sesi seminar berhasil dihapus.');
    }

    /**
     * Validasi revisi pasca seminar
     */
    public function validasiRevisiPasca(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        if ($request->action === 'setuju') {
            $seminar->update([
                'status_seminar' => Seminar::STATUS_REVISI_DISETUJUI,
            ]);
            $message = 'Revisi pasca seminar disetujui';
        } else {
            RevisiSeminar::create([
                'seminar_id' => $seminar->id,
                'catatan' => $request->catatan,
            ]);
            $message = 'Catatan revisi berhasil dikirim';
        }

        return back()->with('success', $message);
    }

    /**
     * Finalisasi nilai akhir
     */
    public function finalisasiNilai(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        $seminar->hitungNilaiAkhir();
        $seminar->update([
            'status_seminar' => Seminar::STATUS_SELESAI,
            'tanggal_selesai' => now(),
        ]);

        return back()->with('success', 'Nilai akhir berhasil ditetapkan: ' . $seminar->nilai_akhir);
    }

    /**
     * Halaman pengaturan pembayaran seminar
     */
    public function paymentSettings()
    {
        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);

        return view('kp.pages.himpunan.payment', [
            'title' => 'Pengaturan Pembayaran',
            'active' => 'payment',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'himpunan' => $himpunan,
        ]);
    }

    /**
     * Update info pembayaran seminar
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'biaya_seminar' => 'required|integer|min:0',
        ]);

        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        
        $himpunan->biaya_seminar = $validated['biaya_seminar'];
        $saved = $himpunan->save();

        if ($saved) {
            return redirect()->route('kp.payment.himpunan')->with('success', 'Biaya seminar berhasil diupdate menjadi Rp ' . number_format($validated['biaya_seminar'], 0, ',', '.'));
        }

        return back()->withErrors(['biaya_seminar' => 'Gagal menyimpan biaya seminar'])->withInput();
    }

    /**
     * Tambah metode pembayaran baru (AJAX)
     */
    public function storeMetodePembayaran(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:bank,ewallet',
            'nama' => 'required|string|max:255',
            'nomor' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        
        // Hitung urutan terakhir
        $lastUrutan = $himpunan->metodePembayarans()->max('urutan') ?? 0;

        $metode = MetodePembayaran::create([
            'himpunan_id' => $himpunan->id,
            'tipe' => $request->tipe,
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'nama_pemilik' => $request->nama_pemilik,
            'urutan' => $lastUrutan + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil ditambahkan',
            'data' => $metode,
        ]);
    }

    /**
     * Hapus metode pembayaran (AJAX)
     */
    public function deleteMetodePembayaran($id)
    {
        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        $metode = MetodePembayaran::where('himpunan_id', $himpunan->id)->findOrFail($id);
        
        $metode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Metode pembayaran berhasil dihapus',
        ]);
    }

    /**
     * Input manual nilai KP (Nilai Instansi, Pembimbing, Penguji)
     */
    public function inputNilaiManual(Request $request)
    {
        $request->validate([
            'seminar_id' => 'required|exists:seminar_kps,id',
            'nilai_instansi' => 'required|numeric|min:0|max:100',
            'nilai_pembimbing' => 'required|numeric|min:0|max:100',
            'nilai_penguji' => 'required|numeric|min:0|max:100',
            'catatan_nilai' => 'nullable|string',
        ]);

        $seminar = Seminar::findOrFail($request->seminar_id);

        // Cek apakah seminar sudah selesai (lulus)
        if ($seminar->is_lulus != 1) {
            return back()->with('warning', 'Seminar belum selesai. Tidak bisa input nilai.');
        }

        // Update nilai di tabel seminar
        $seminar->update([
            'nilai_instansi' => $request->nilai_instansi,
            'nilai_pembimbing' => $request->nilai_pembimbing,
            'nilai_penguji' => $request->nilai_penguji,
            'catatan_nilai' => $request->catatan_nilai,
        ]);

        // Hitung nilai akhir berdasarkan presentase
        $presentase = \App\Models\PresentaseNilai::where('jenis', 'kp')->first();
        
        $nilaiAkhir = 0;
        if ($presentase) {
            $nilaiAkhir = (
                ($request->nilai_instansi * $presentase->nilai_instansi / 100) +
                ($request->nilai_pembimbing * $presentase->nilai_pembimbing / 100) +
                ($request->nilai_penguji * $presentase->nilai_penguji / 100)
            );
        }

        // Tentukan grade
        $grade = 'E';
        if ($nilaiAkhir >= 85) $grade = 'A';
        elseif ($nilaiAkhir >= 80) $grade = 'A-';
        elseif ($nilaiAkhir >= 75) $grade = 'B+';
        elseif ($nilaiAkhir >= 70) $grade = 'B';
        elseif ($nilaiAkhir >= 65) $grade = 'B-';
        elseif ($nilaiAkhir >= 60) $grade = 'C+';
        elseif ($nilaiAkhir >= 55) $grade = 'C';
        elseif ($nilaiAkhir >= 50) $grade = 'D';

        // Kirim notifikasi ke mahasiswa dengan detail nilai
        if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
            try {
                $message = "
                    <h3>Nilai Kerja Praktek Anda Telah Diinput</h3>
                    <p>Berikut adalah rincian nilai Kerja Praktek Anda:</p>
                    
                    <table style='border-collapse: collapse; width: 100%; margin: 20px 0;'>
                        <tr style='background-color: #f8f9fa;'>
                            <th style='border: 1px solid #dee2e6; padding: 12px; text-align: left;'>Komponen Penilaian</th>
                            <th style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'>Nilai</th>
                            <th style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'>Bobot</th>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #dee2e6; padding: 12px;'>Nilai Instansi</td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'><strong>{$request->nilai_instansi}</strong></td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'>" . ($presentase ? $presentase->nilai_instansi : 0) . "%</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #dee2e6; padding: 12px;'>Nilai Dosen Pembimbing</td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'><strong>{$request->nilai_pembimbing}</strong></td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'>" . ($presentase ? $presentase->nilai_pembimbing : 0) . "%</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #dee2e6; padding: 12px;'>Nilai Dosen Penguji</td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'><strong>{$request->nilai_penguji}</strong></td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center;'>" . ($presentase ? $presentase->nilai_penguji : 0) . "%</td>
                        </tr>
                        <tr style='background-color: #e7f3ff; font-weight: bold;'>
                            <td style='border: 1px solid #dee2e6; padding: 12px;'>NILAI AKHIR</td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center; font-size: 18px; color: #0066cc;'>" . number_format($nilaiAkhir, 2) . "</td>
                            <td style='border: 1px solid #dee2e6; padding: 12px; text-align: center; font-size: 18px; color: #0066cc;'>{$grade}</td>
                        </tr>
                    </table>
                    
                    " . ($request->catatan_nilai ? "<p><strong>Catatan:</strong><br>" . nl2br(e($request->catatan_nilai)) . "</p>" : "") . "
                    
                    <p style='margin-top: 20px;'>Silakan login ke sistem EKAPTA untuk melihat detail lengkap nilai Anda.</p>
                    <p style='color: #666; font-size: 12px; margin-top: 30px;'>Email ini dikirim secara otomatis oleh sistem EKAPTA FASTIKOM.</p>
                ";

                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'subject' => 'Nilai Kerja Praktek Telah Diinput - ' . $seminar->mahasiswa->nama,
                    'title' => 'EKAPTA FASTIKOM',
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Nilai KP berhasil disimpan dan notifikasi email telah dikirim ke mahasiswa');
    }
}
