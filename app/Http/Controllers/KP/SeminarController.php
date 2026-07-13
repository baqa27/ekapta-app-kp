<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Helpers\StorageHelper;
use App\Models\KP\Bimbingan;
use App\Models\Dosen;
use App\Models\KP\Himpunan;
use App\Models\Mahasiswa;
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\Prodi;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiSeminar;
use App\Models\KP\Seminar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeminarController extends \App\Http\Controllers\Controller
{
    public function seminarMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP', 'seminarKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if ($mahasiswa->email == '-') {
            return redirect()->route('kp.profile');
        }

        // KARYAWAN: Redirect ke pengumpulan akhir (tanpa seminar)
        if (AppHelper::isKaryawanKP($mahasiswa)) {
            return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('info', 'Mahasiswa Karyawan tidak perlu mengikuti Seminar KP. Silahkan langsung ke tahap Pengumpulan Akhir setelah semua bimbingan di-ACC.');
        }

        // Cek tahapan: Bimbingan harus selesai dulu
        if (!AppHelper::canAccessSeminar($mahasiswa)) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Selesaikan tahap Bimbingan KP terlebih dahulu. Semua bagian bimbingan harus sudah di-ACC oleh Dosen Pembimbing.');
        }

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $seminar = $mahasiswa->seminarKP;

        // Cek status pendaftaran seminar
        $is_pendaftaran_open = Himpunan::isPendaftaranSeminarOpen();

        $data = [
            'title' => 'Seminar KP',
            'active' => 'seminar-kp',
            'dosen_utama' => $dosen_pembimbing,
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
            'dosens_penguji' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get() : collect(),
            'reviews_acc' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->where('status', ReviewSeminar::DITERIMA)->get() : collect(),
            'is_ujian' => false,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
            'reviews_has_acc' => false,
            'is_pendaftaran_open' => $is_pendaftaran_open,
        ];

        return view('kp.pages.mahasiswa.seminar.seminar', $data);
    }

    public function seminarAdmin()
    {
        // Admin bisa lihat SEMUA seminar termasuk karyawan (untuk penilaian)
        $seminars_review = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVIEW)->get();
        $seminars_revisi = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVISI)->get();
        $seminars_acc = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->get();

        $data = [
            'title' => 'Validasi Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'seminars_review' => $seminars_review,
            'seminars_revisi' => $seminars_revisi,
            'seminars_acc' => $seminars_acc,
        ];

        return view('kp.pages.admin.seminar.seminar', $data);
    }

    public function seminarDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $data = [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'seminars_review' => $dosen->seminarsKP()->where('status', ReviewSeminar::REVIEW)->get(),
            'seminars_acc' => $dosen->seminarsKP()->where('status', ReviewSeminar::DITERIMA)->get(),
            'seminars_revisi' => $dosen->seminarsKP()->where('status', ReviewSeminar::REVISI)->get(),
        ];

        return view('kp.pages.dosen.seminar.seminar', $data);
    }

    public function seminarProdi()
    {
        $prodi = Auth::guard('prodi')->user();
        
        // AUTO-CREATE SEMINAR UNTUK MAHASISWA KARYAWAN YANG SUDAH FULL ACC
        $this->autoCreateSeminarKaryawan($prodi);
        
        // Ambil semua seminar yang sudah diterima (is_valid = 1)
        // Prodi bisa lihat SEMUA termasuk karyawan (untuk penilaian)
        $seminars = Seminar::orderBy('created_at', 'desc')
            ->where('is_valid', Seminar::DITERIMA)
            ->with(['mahasiswa', 'pengajuan'])
            ->get();

        // Filter berdasarkan prodi
        $seminars_prodi = [];
        foreach ($seminars as $seminar) {
            if ($seminar->mahasiswa->prodi == $prodi->namaprodi || $seminar->mahasiswa->prodi == $prodi->kode) {
                $seminars_prodi[] = $seminar;
            }
        }

        // Pisahkan seminar aktif dan selesai
        $seminars_aktif = [];
        $seminars_selesai = [];
        
        foreach ($seminars_prodi as $seminar) {
            if ($seminar->is_lulus == 1) {
                // Seminar yang sudah selesai dan lulus
                $seminars_selesai[] = $seminar;
            } else {
                // Seminar yang masih aktif (belum dinilai/belum lulus)
                $seminars_aktif[] = $seminar;
            }
        }

        $data = [
            'title' => 'Daftar Seminar Mahasiswa',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'seminars_aktif' => $seminars_aktif,
            'seminars_selesai' => $seminars_selesai,
        ];

        return view('kp.pages.prodi.seminar.seminar', $data);
    }
    
    /**
     * Auto-create seminar entry untuk mahasiswa karyawan yang sudah full ACC bimbingan
     * Karyawan tidak seminar fisik, tapi tetap perlu entry untuk penilaian oleh prodi/admin
     */
    private function autoCreateSeminarKaryawan($prodi)
    {
        // Ambil mahasiswa dari prodi ini
        $mahasiswas = Mahasiswa::where('prodi', $prodi->namaprodi)
            ->orWhere('prodi', $prodi->kode)
            ->with(['pendaftaransKP', 'pengajuansKP', 'bimbingansKP.bagian', 'seminarKP'])
            ->get();
        
        foreach ($mahasiswas as $mahasiswa) {
            // Cek apakah karyawan
            $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($mahasiswa);
            
            if (!$is_karyawan) {
                continue;
            }
            
            // Cek apakah sudah punya seminar
            if ($mahasiswa->seminarKP) {
                continue;
            }
            
            // Ambil prodi mahasiswa
            $mhs_prodi = Prodi::where('kode', $mahasiswa->prodi)
                ->orWhere('namaprodi', $mahasiswa->prodi)
                ->first();
            
            if (!$mhs_prodi) {
                continue;
            }
            
            // Hitung total bagian yang harus dibimbing
            $bagians_is_seminar = $mhs_prodi->bagiansKP()
                ->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")
                ->where('is_seminar', 1)
                ->count();
            
            // Hitung bimbingan yang sudah ACC
            $bimbingans_acc_count = $mahasiswa->bimbingansKP()
                ->where('status', Bimbingan::DITERIMA)
                ->whereHas('bagian', function ($query) {
                    $query->where('is_seminar', 1);
                })
                ->count();
            
            // Jika semua bimbingan sudah ACC
            if ($bimbingans_acc_count >= $bagians_is_seminar && $bagians_is_seminar > 0) {
                // Ambil pengajuan yang diterima
                $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();
                
                if ($pengajuan) {
                    try {
                        // Create seminar entry (untuk penilaian, bukan seminar fisik)
                        $seminar = new Seminar();
                        $seminar->mahasiswa_id = $mahasiswa->id;
                        $seminar->pengajuan_id = $pengajuan->id;
                        $seminar->status_seminar = 'selesai';
                        $seminar->is_valid = Seminar::DITERIMA;
                        $seminar->is_lulus = 1; // Langsung lulus (masuk Seminar Selesai)
                        $seminar->save();
                        
                        \Log::info('Auto-created seminar for karyawan (for grading)', [
                            'mahasiswa_id' => $mahasiswa->id,
                            'mahasiswa_nim' => $mahasiswa->nim,
                            'seminar_id' => $seminar->id
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to auto-create seminar for karyawan', [
                            'mahasiswa_id' => $mahasiswa->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        
        // KARYAWAN: Redirect ke pengumpulan akhir (tanpa seminar)
        if (AppHelper::isKaryawanKP($mahasiswa)) {
            return redirect()->route('kp.pengumpulan-akhir.mahasiswa')->with('info', 'Mahasiswa Karyawan tidak perlu mengikuti Seminar KP. Silahkan langsung ke tahap Pengumpulan Akhir setelah semua bimbingan di-ACC.');
        }
        
        $pengajuan_acc = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        // Cek apakah pendaftaran seminar dibuka oleh himpunan
        if (!Himpunan::isPendaftaranSeminarOpen()) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Pendaftaran Seminar KP sedang DITUTUP. Silahkan tunggu pengumuman dari Himpunan.');
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        } else if ($pengajuan_acc->seminar) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar KP');
        } else if (count($bimbingans_is_acc_seminar) < count($bagians_is_seminar)) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        // Ambil data himpunan untuk info pembayaran
        $himpunan = Himpunan::first();

        $data = [
            'title' => 'Form Pendaftaran Seminar KP',
            'active' => 'seminar-kp',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'himpunan' => $himpunan,
        ];

        return view('kp.pages.mahasiswa.seminar.create', $data);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if (AppHelper::isBimbinganExpiredFromPendaftaran($pendaftaran_acc, 6)) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        } else if ($pengajuan->seminar) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar KP');
        }

        // Cek apakah pendaftaran seminar dibuka
        if (!Himpunan::isPendaftaranSeminarOpen()) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Pendaftaran Seminar KP belum dibuka.');
        }

        // Ambil himpunan untuk validasi metode pembayaran
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $himpunan = Himpunan::where('prodi_id', $mahasiswa->prodi_id)->first();
        
        // Fallback ke himpunan pertama jika tidak ada himpunan untuk prodi ini
        if (!$himpunan) {
            $himpunan = Himpunan::first();
        }
        
        $valid_metode_bayar = ['Cash'];
        if ($himpunan) {
            $metode_pembayaran = $himpunan->metodePembayarans()->active()->pluck('nama')->toArray();
            $valid_metode_bayar = array_merge($valid_metode_bayar, $metode_pembayaran);
        }

        $validatedData = $request->validate([
            'no_wa' => ['required', 'string', 'max:20'],
            'file_laporan' => ['required', 'mimes:pdf', 'max:10240'],
            'file_bimbingan' => ['required', 'mimes:pdf', 'max:10240'],
            'lampiran_1' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 1
            'lampiran_2' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 2
            'lampiran_3' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 3
            'lampiran_4' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 4
            'metode_bayar' => ['required', 'string', 'max:255', Rule::in($valid_metode_bayar)],
            'bukti_bayar' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:10240'],
            'link_akses_produk' => ['nullable', 'url'],
        ]);

        // Set jumlah bayar dari himpunan (pastikan tidak NULL)
        $biaya_seminar = 25000; // Default
        if ($himpunan && $himpunan->biaya_seminar) {
            $biaya_seminar = $himpunan->biaya_seminar;
        }
        $validatedData['jumlah_bayar'] = $biaya_seminar;

        // Judul laporan otomatis dari pengajuan
        $validatedData['judul_laporan'] = $pengajuan->judul;

        // Upload semua file
        $validatedData['file_laporan'] = StorageHelper::storeKpFile($request->file('file_laporan'), $mahasiswa->nim, 'seminar');
        $validatedData['file_bimbingan'] = StorageHelper::storeKpFile($request->file('file_bimbingan'), $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_1'] = StorageHelper::storeKpFile($request->file('lampiran_1'), $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_2'] = StorageHelper::storeKpFile($request->file('lampiran_2'), $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_3'] = StorageHelper::storeKpFile($request->file('lampiran_3'), $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_4'] = StorageHelper::storeKpFile($request->file('lampiran_4'), $mahasiswa->nim, 'seminar');
        $validatedData['bukti_bayar'] = StorageHelper::storeKpFile($request->file('bukti_bayar'), $mahasiswa->nim, 'seminar');

        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;
        $validatedData['is_valid'] = Seminar::REVIEW;
        $validatedData['status_seminar'] = Seminar::STATUS_MENUNGGU_VERIFIKASI;

        $seminar = Seminar::create($validatedData);

        // Kirim notifikasi email ke himpunan
        try {
            $himpunan = Himpunan::first();
            if ($himpunan && $himpunan->email && $himpunan->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $himpunan->email,
                    'subject' => 'Pendaftaran Seminar KP Baru',
                    'title' => 'Pendaftaran Seminar KP Baru',
                    'message' => 'Ada pendaftaran Seminar KP baru yang perlu diverifikasi.<br>
                        Mahasiswa: <b>' . $mahasiswa->nama . '</b> (' . $mahasiswa->nim . ')<br>
                        Judul: <b>' . $pengajuan->judul . '</b><br>
                        Silahkan login ke sistem untuk melakukan verifikasi.',
                ]);
            }
        } catch (\Exception $e) {
            \Log::warning('Email notifikasi ke himpunan gagal: ' . $e->getMessage());
        }

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Pendaftaran Seminar KP berhasil! Silahkan tunggu verifikasi dari Himpunan.');
    }

    public function edit($id)
    {
        $seminar = Seminar::findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($pendaftaran_acc->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($seminar->is_valid == Seminar::REVIEW) {
            return redirect()->route('kp.seminar.mahasiswa');
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        }

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Ambil data himpunan untuk info pembayaran
        $himpunan = Himpunan::first();

        $data = [
            'title' => 'Submit Seminar KP',
            'active' => 'seminar-kp',
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
            'himpunan' => $himpunan,
        ];

        return view('kp.pages.mahasiswa.seminar.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $validatedData = $request->validate([
            'no_wa' => ['required', 'string', 'max:20'],
            'link_akses_produk' => ['required', 'url'],
            'metode_bayar' => ['required', 'string', 'max:255'],
            'file_laporan' => ['nullable', 'mimes:pdf', 'max:10240'],
            'file_bimbingan' => ['nullable', 'mimes:pdf', 'max:10240'],
            'lampiran_1' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_2' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_3' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_4' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'bukti_bayar' => ['nullable', 'mimes:jpg,png,jpeg,pdf', 'max:10240'],
        ]);

        $mahasiswa = $seminar->mahasiswa;

        // Upload file jika ada yang baru
        if ($request->file('file_laporan')) {
            StorageHelper::deleteKpFile($seminar->file_laporan);
            $validatedData['file_laporan'] = StorageHelper::storeKpFile($request->file_laporan, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('file_bimbingan')) {
            StorageHelper::deleteKpFile($seminar->file_bimbingan);
            $validatedData['file_bimbingan'] = StorageHelper::storeKpFile($request->file_bimbingan, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_1')) {
            StorageHelper::deleteKpFile($seminar->lampiran_1);
            $validatedData['lampiran_1'] = StorageHelper::storeKpFile($request->lampiran_1, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_2')) {
            StorageHelper::deleteKpFile($seminar->lampiran_2);
            $validatedData['lampiran_2'] = StorageHelper::storeKpFile($request->lampiran_2, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_3')) {
            StorageHelper::deleteKpFile($seminar->lampiran_3);
            $validatedData['lampiran_3'] = StorageHelper::storeKpFile($request->lampiran_3, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_4')) {
            StorageHelper::deleteKpFile($seminar->lampiran_4);
            $validatedData['lampiran_4'] = StorageHelper::storeKpFile($request->lampiran_4, $mahasiswa->nim, 'seminar');
        }
        if ($request->file('bukti_bayar')) {
            StorageHelper::deleteKpFile($seminar->bukti_bayar);
            $validatedData['bukti_bayar'] = StorageHelper::storeKpFile($request->bukti_bayar, $mahasiswa->nim, 'seminar');
        }

        // Set status kembali ke review
        $validatedData['is_valid'] = Seminar::REVIEW;
        $validatedData['status_seminar'] = Seminar::STATUS_MENUNGGU_VERIFIKASI;

        $seminar->update($validatedData);

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Revisi Seminar KP berhasil disubmit, silahkan tunggu verifikasi dari Himpunan');
    }

    public function delete(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->delete();
        return 'Seminar has been deleted';
    }

    public function accSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);

        if ($seminar->is_valid == 1 || count($seminar->reviews) >= 3) {
            return back();
        }

        $mahasiswa = $seminar->mahasiswa;

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        if ($dosen_pembimbing) {
            ReviewSeminar::create([
                'seminar_id' => $seminar->id,
                'dosen_id' => $dosen_pembimbing->id,
                'status' => ReviewSeminar::REVIEW,
                'dosen_status' => ReviewSeminar::DOSEN_PEMBIMBING,
            ]);
        }

        $seminar->update([
            'is_valid' => 1,
            'tanggal_acc' => now(),
        ]);
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Selamat Pendaftaran  Seminar Kerja Praktek Anda Berstatus DITERIMA.',
            ]);
        }
        return back()->with('success', 'Pendaftaran Seminar KP berhasil di Acc.');
    }

    public function cancelAcc(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);

        if (count($seminar->reviews) == 5) {
            return back();
        }

        foreach ($seminar->reviews as $review) {
            $review->delete();
        }

        $seminar->update([
            'is_valid' => 0,
        ]);

        return back()->with('success', 'Acc Seminar KP berhasil dibatalkan.');
    }

    public function seminarReviewAdmin($id)
    {
        if (Auth::guard('prodi')->user()) {
            $sidebar = 'partials.sidebarProdi';
        } else {
            $sidebar = 'partials.sidebarAdmin';
        }
        $seminar = Seminar::findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        $dosens = $prodi ? $prodi->dosens : collect();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $reviews_check = $seminar->reviews()->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])->where('dosen_status', 'penguji')->get();

        $data = [
            'title' => 'Review Pendaftaran Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => $sidebar,
            'seminar' => $seminar,
            'dosens' => $dosens,
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pembimbing' => $dosen_pembimbing,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosens_penguji' => $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get(),
            'reviews_check' => $reviews_check,
        ];

        return view('kp.pages.admin.seminar.review', $data);
    }

    public function seminarReviews($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'seminar' => $seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.reviews', $data);
    }

    public function revisiSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $revisi = new RevisiSeminar();
        $revisi->catatan = $request->catatan;
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,docx', 'max:2048'
            ]
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = StorageHelper::storeKpFile($request->lampiran, $seminar->mahasiswa->nim, 'seminar');
        }
        if ($seminar->is_valid == Seminar::REVIEW) {
            $seminar->update([
                'is_valid' => Seminar::REVISI,
            ]);
            $seminar->revisis()->save($revisi);
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'message' => 'Pendaftaran Seminar Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!.<br><br>Catatan revisi: ' . $request->catatan,
                ]);
            }
            return redirect()->route('kp.seminar.admin')->with('success', 'Seminar KP berhasil direvisi');
        } elseif ($seminar->is_valid == Seminar::REVISI) {
            $seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function deleteRevisi(Request $request)
    {
        $revisi = RevisiSeminar::findOrFail($request->id);
        StorageHelper::deleteKpFile($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function detail($id)
    {
        $seminar = Seminar::with(['sesiSeminar.dosenPenguji', 'dosenPenguji'])->findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($seminar->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        }

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Get reviews untuk nilai dosen
        $review_pembimbing = $seminar->reviews()->where('dosen_status', 'pembimbing')->first();
        $review_penguji = $seminar->reviews()->where('dosen_status', 'penguji')->first();

        $data = [
            'title' => 'Detail Seminar KP',
            'active' => 'seminar-kp',
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->get(),
            'review_pembimbing' => $review_pembimbing,
            'review_penguji' => $review_penguji,
        ];

        return view('kp.pages.mahasiswa.seminar.detail', $data);
    }

    public function editProposal($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Submit Laporan Seminar Proposal',
            'active' => 'seminar-kp',
            'seminar' => $seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-proposal', $data);
    }

    public function updateProposal(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $request->validate([
            'lampiran_proposal' => ['required', 'mimes:pdf, docx', 'max:2048'],
        ]);

        $seminar->update([
            'lampiran_proposal' => StorageHelper::storeKpFile($request->lampiran_proposal, $seminar->mahasiswa->nim, 'seminar'),
        ]);

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Laporan Seminar Proposal Berhasil di submit.');
    }

    public function setDateExam(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required',
        ]);
        $validatedData['tanggal_ujian'] = Carbon::parse($request->tanggal_ujian);
        $seminar->update($validatedData);
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Selamat seminar Kerja Praktek anda sudah dijadwalkan. Berikut detail seminar Kerja Praktek Anda: <br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b>',
            ]);
        }
        // Kirim email ke dosen penguji
        foreach ($seminar->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review) {
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'message' => 'Kepada Yth Bapak/Ibu <b>' . $review->dosen->nama . ', ' . $review->dosen->gelar . '</b> anda di tunjuk sebagai penguji untuk seminar Kerja Praktek. Berikut detail dan jadwal seminar Kerja Praktek: <br>NIM/Nama Mahasiswa: <b>' . $seminar->mahasiswa->nim . '/' . $seminar->mahasiswa->nama . '</b><br>Judul KP: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b><br><br>Silahkan klik link berikut untuk melakukan penilaian: <a href="' . route('kp.review.seminar.public', $review->token) . '">Link Penilaian</a>',
                ]);
            }
        }
        // Kirim email ke dosen pembimbing
        foreach ($seminar->reviews()->where('dosen_status', 'pembimbing')->with(['dosen'])->get() as $review) {
            if ($review->dosen && $review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'message' => 'Kepada Yth Bapak/Ibu <b>' . $review->dosen->nama . ', ' . $review->dosen->gelar . '</b> sebagai dosen pembimbing untuk seminar Kerja Praktek. Berikut detail dan jadwal seminar Kerja Praktek: <br>NIM/Nama Mahasiswa: <b>' . $seminar->mahasiswa->nim . '/' . $seminar->mahasiswa->nama . '</b><br>Judul KP: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b><br><br>Silahkan klik link berikut untuk melakukan penilaian: <a href="' . route('kp.review.seminar.public', $review->token) . '">Link Penilaian</a>',
                ]);
            }
        }
        return back()->with('success', 'Jadwal dan Tempat Seminar Kerja Praktek berhasil disimpan');
    }

    public function seminarProdiDetail($id)
    {
        $seminar = Seminar::with(['mahasiswa.jilidKP', 'reviews', 'revisis'])->where('id', $id)->first();
        
        if (!$seminar) {
            return back()->with('warning', 'Seminar tidak ditemukan');
        }

        // Cek apakah mahasiswa karyawan
        $is_karyawan = AppHelper::isKaryawanKP($seminar->mahasiswa);

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $seminar->mahasiswa->prodi)
            ->orWhere('namaprodi', $seminar->mahasiswa->prodi)
            ->first();
        
        // KP: Tidak menggunakan presentase_nilai seperti TA
        // Cek apakah tabel presentase_nilais ada dan prodi punya relasi
        $presentase_nilai = null;
        $nilai = null;
        $nilai_penguji = null;
        $nilai_pembimbing = null;
        
        try {
            if ($prodi && method_exists($prodi, 'presentase_nilai')) {
                $presentase_nilai = $prodi->presentase_nilai;
            }
            
            // Hitung nilai jika ada cukup review dan presentase nilai
            if (count($seminar->reviews) >= 4 && $presentase_nilai) {
                $nilaiData = AppHelper::hitung_nilai_mahasiswa($seminar);
                $nilai = $nilaiData['nilai'] ?? null;
                $nilai_penguji = $nilaiData['nilai_penguji'] ?? null;
                $nilai_pembimbing = $nilaiData['nilai_pembimbing'] ?? null;
            }
        } catch (\Exception $e) {
            // Abaikan error jika tabel tidak ada
        }

        $data = [
            'title' => 'Detail Seminar Mahasiswa',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->paginate(5),
            'nilai' => $nilai,
            'nilai_dosen_penguji' => $nilai_penguji,
            'nilai_dosen_pembimbing' => $nilai_pembimbing,
            'is_karyawan' => $is_karyawan,
        ];

        return view('kp.pages.prodi.seminar.detail', $data);
    }

    public function rekapSeminar()
    {
        if (Auth::guard('prodi')->user()) {
            $sidebar = 'partials.sidebarProdi';
        } else {
            $sidebar = 'partials.sidebarAdmin';
        }
        
        // Exclude mahasiswa karyawan dari rekap seminar
        $seminars = Seminar::where('is_valid', Seminar::VALID)
            ->where('tanggal_ujian', null)
            ->whereHas('mahasiswa.pendaftaransKP', function($q) {
                $q->where('status', 'diterima')
                  ->where('jenis_mahasiswa', '!=', 'karyawan');
            })
            ->get();
            
        return view('kp.pages.admin.seminar.rekap', [
            'title' => 'Rekap Pendaftaran Seminar Mahasiswa',
            'sidebar' => $sidebar,
            'active' => 'seminar-kp',
            'seminars' => $seminars,
        ]);
    }

    /**
     * Update status lulus/tidak lulus via AJAX
     * Dipanggil dari halaman detail seminar prodi
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'seminar_id' => 'required|exists:seminar_kps,id',
                'is_lulus' => 'required|boolean',
            ]);
            
            $seminar = Seminar::findOrFail($validated['seminar_id']);
            
            // Update status lulus
            $seminar->is_lulus = $validated['is_lulus'];
            $seminar->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diupdate',
                'data' => [
                    'is_lulus' => $seminar->is_lulus,
                    'status_text' => $seminar->is_lulus ? 'Lulus' : 'Tidak Lulus',
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error updating status seminar KP: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Upload nilai instansi oleh mahasiswa
     * Setelah seminar selesai, mahasiswa upload nilai dari instansi
     */
    public function uploadNilaiInstansi(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        // Pastikan seminar milik mahasiswa yang login
        if ($seminar->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(403);
        }

        // Pastikan status seminar adalah selesai_seminar
        if ($seminar->status_seminar != Seminar::STATUS_SELESAI_SEMINAR) {
            return back()->with('warning', 'Anda belum bisa upload nilai instansi. Selesaikan seminar terlebih dahulu.');
        }

        $request->validate([
            'nilai_instansi' => ['required', 'numeric', 'min:0', 'max:100'],
            'file_nilai_instansi' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $mahasiswa = $seminar->mahasiswa;

        $seminar->nilai_instansi = $request->nilai_instansi;
        $seminar->file_nilai_instansi = StorageHelper::storeKpFile($request->file('file_nilai_instansi'), $mahasiswa->nim, 'seminar');

        // Hitung nilai akhir jika nilai seminar sudah ada
        if ($seminar->nilai_seminar) {
            $seminar->hitungNilaiAkhir();
            $seminar->status_seminar = Seminar::STATUS_SELESAI;
        }

        $seminar->save();

        return back()->with('success', 'Nilai instansi berhasil diupload. Nilai akhir KP: ' . $seminar->nilai_akhir);
    }
}


