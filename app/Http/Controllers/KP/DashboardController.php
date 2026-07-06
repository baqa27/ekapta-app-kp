<?php

namespace App\Http\Controllers\KP;

// Import KP Models
use App\Models\KP\Bimbingan;
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\Seminar;
use App\Models\KP\Jilid;

// Import Shared Models
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;

// Import TA Models (Alias)
use App\Models\Pengajuan as TAPengajuan;
use App\Models\Bimbingan as TABimbingan;
use App\Models\Seminar as TASeminar;
use App\Models\Ujian as TAUjian;
use App\Models\Pendaftaran as TAPendaftaran;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends \App\Http\Controllers\Controller
{

    public function dashboardMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => config('app.name'),
            'mahasiswa' => $mahasiswa,
            'active' => ''
        ];

        return view('kp.pages.mahasiswa.home', $data);
    }

    /**
     * Halaman pilih sistem untuk mahasiswa
     * Menampilkan pilihan antara Tugas Akhir (TA) dan Kerja Praktik (KP)
     */
    public function pilihSistem()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => 'Pilih Sistem - ' . config('app.name'),
            'mahasiswa' => $mahasiswa,
            'active' => 'pilih-sistem'
        ];

        return view('kp.pages.mahasiswa.pilih-sistem', $data);
    }

    public function dashboardProdi()
    {
        $prodi = Auth::guard('prodi')->user();
        $mahasiswaProdiScope = function ($query) use ($prodi) {
            $query->where(function ($q) use ($prodi) {
                $q->where('prodi_id', $prodi->id)
                    ->orWhere('prodi', $prodi->kode)
                    ->orWhere('prodi', $prodi->namaprodi);
            });
        };

        $pengajuanQuery = Pengajuan::where(function ($query) use ($prodi, $mahasiswaProdiScope) {
            $query->where('prodi_id', $prodi->id)
                ->orWhereHas('mahasiswa', $mahasiswaProdiScope);
        });
        $pengajuans = (clone $pengajuanQuery)->get();
        $pengajuans_diterima = (clone $pengajuanQuery)->where('status', 'diterima')->get();
        $pengajuans_revisi = (clone $pengajuanQuery)->where('status', 'revisi')->get();
        $pengajuans_review = (clone $pengajuanQuery)->where('status', 'review')->get();
        $pengajuans_ditolak = (clone $pengajuanQuery)->where('status', 'ditolak')->get();

        $bimbinganQuery = Bimbingan::whereHas('mahasiswa', $mahasiswaProdiScope);
        $bimbingans = (clone $bimbinganQuery)->get();
        $bimbingans_diterima = (clone $bimbinganQuery)->where('status', 'diterima')->get();
        $bimbingans_review = (clone $bimbinganQuery)->where('status', 'review')->get();
        $bimbingans_revisi = (clone $bimbinganQuery)->where('status', 'revisi')->get();

        $seminarQuery = Seminar::whereHas('mahasiswa', $mahasiswaProdiScope);
        $seminars = (clone $seminarQuery)->get();
        $seminars_diterima = (clone $seminarQuery)->where('is_valid', '1')->get();
        $seminars_review = (clone $seminarQuery)->where('is_valid', '0')->get();
        $seminars_revisi = (clone $seminarQuery)->where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (menggunakan model Jilid)
        $pengumpulanAkhirQuery = Jilid::whereHas('mahasiswa', $mahasiswaProdiScope);
        $pengumpulan_akhir = (clone $pengumpulanAkhirQuery)->get();
        $pengumpulan_akhir_diterima = (clone $pengumpulanAkhirQuery)
            ->where(function ($query) {
                $query->where('status', Jilid::JILID_VALID)
                    ->orWhere('status', Jilid::JILID_SELESAI);
            })
            ->get();
        $pengumpulan_akhir_revisi = (clone $pengumpulanAkhirQuery)->where('status', Jilid::JILID_REVISI)->get();
        $pengumpulan_akhir_review = (clone $pengumpulanAkhirQuery)->where('status', Jilid::JILID_REVIEW)->get();

        // --- DATA TUGAS AKHIR (TA) ---
        $taPengajuanQuery = TAPengajuan::where(function ($query) use ($prodi, $mahasiswaProdiScope) {
            $query->where('prodi_id', $prodi->id)
                ->orWhereHas('mahasiswa', $mahasiswaProdiScope);
        });
        $ta_pengajuans = (clone $taPengajuanQuery)->get();
        $ta_pengajuans_diterima = (clone $taPengajuanQuery)->where('status', 'diterima')->get();
        $ta_pengajuans_review = (clone $taPengajuanQuery)->where('status', 'review')->get();
        $ta_pengajuans_revisi = (clone $taPengajuanQuery)->where('status', 'revisi')->get();
        $ta_pengajuans_ditolak = (clone $taPengajuanQuery)->where('status', 'ditolak')->get();

        $taSeminarQuery = TASeminar::whereHas('mahasiswa', $mahasiswaProdiScope);
        $ta_seminars = (clone $taSeminarQuery)->get();
        $ta_seminars_diterima = (clone $taSeminarQuery)->where('is_valid', '1')->get();
        $ta_seminars_review = (clone $taSeminarQuery)->where('is_valid', '0')->get();
        $ta_seminars_revisi = (clone $taSeminarQuery)->where('is_valid', '2')->get();

        $taUjianQuery = TAUjian::whereHas('mahasiswa', $mahasiswaProdiScope);
        $ta_ujians = (clone $taUjianQuery)->get();
        $ta_ujians_diterima = (clone $taUjianQuery)->where('is_valid', '1')->get();
        $ta_ujians_review = (clone $taUjianQuery)->where('is_valid', '3')->get();
        $ta_ujians_revisi = (clone $taUjianQuery)->where('is_valid', '2')->get();

        $taBimbinganQuery = TABimbingan::whereHas('mahasiswa', $mahasiswaProdiScope);
        $ta_bimbingans = (clone $taBimbinganQuery)->get();
        $ta_bimbingans_diterima = (clone $taBimbinganQuery)->where('status', 'diterima')->get();
        $ta_bimbingans_review = (clone $taBimbinganQuery)->where('status', 'review')->get();
        $ta_bimbingans_revisi = (clone $taBimbinganQuery)->where('status', 'revisi')->get();

        return view('kp.pages.prodi.dashboard.home', [
            'title' => 'Dashboard KP - Prodi',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'pengajuans' => $pengajuans,
            'pengajuans_diterima' => $pengajuans_diterima,
            'pengajuans_review' => $pengajuans_review,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
            'pengumpulan_akhir' => $pengumpulan_akhir,
            'pengumpulan_akhir_diterima' => $pengumpulan_akhir_diterima,
            'pengumpulan_akhir_revisi' => $pengumpulan_akhir_revisi,
            'pengumpulan_akhir_review' => $pengumpulan_akhir_review,
            
            // Pass TA Data
            'ta_pengajuans' => $ta_pengajuans,
            'ta_pengajuans_diterima' => $ta_pengajuans_diterima,
            'ta_pengajuans_review' => $ta_pengajuans_review,
            'ta_pengajuans_revisi' => $ta_pengajuans_revisi,
            'ta_pengajuans_ditolak' => $ta_pengajuans_ditolak,

            'ta_bimbingans' => $ta_bimbingans,
            'ta_bimbingans_diterima' => $ta_bimbingans_diterima,
            'ta_bimbingans_review' => $ta_bimbingans_review,
            'ta_bimbingans_revisi' => $ta_bimbingans_revisi,

            'ta_seminars' => $ta_seminars,
            'ta_seminars_diterima' => $ta_seminars_diterima,
            'ta_seminars_review' => $ta_seminars_review,
            'ta_seminars_revisi' => $ta_seminars_revisi,

            'ta_ujians' => $ta_ujians,
            'ta_ujians_diterima' => $ta_ujians_diterima,
            'ta_ujians_review' => $ta_ujians_review,
            'ta_ujians_revisi' => $ta_ujians_revisi,
            'ta_pengajuans_review' => $ta_pengajuans_review,
            'ta_pengajuans_revisi' => $ta_pengajuans_revisi,
        ]);
    }

    public function dashboardAdmin()
    {
        $pengajuans = Pengajuan::all();
        $pengajuans_diterima = Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = Pengajuan::where('status', 'ditolak')->get();

        $pendaftarans = Pendaftaran::all();
        $pendaftarans_diterima = Pendaftaran::where('status', 'diterima')->get();
        $pendaftarans_review = Pendaftaran::where('status', 'review')->get();
        $pendaftarans_revisi = Pendaftaran::where('status', 'revisi')->get();

        $seminars = Seminar::all();
        $seminars_diterima = Seminar::where('is_valid', '1')->get();
        $seminars_review = Seminar::where('is_valid', '0')->get();
        $seminars_revisi = Seminar::where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (menggunakan model Jilid)
        $pengumpulan_akhir = Jilid::all();
        $pengumpulan_akhir_diterima = Jilid::where('status', Jilid::JILID_VALID)->orWhere('status', Jilid::JILID_SELESAI)->get();
        $pengumpulan_akhir_review = Jilid::where('status', Jilid::JILID_REVIEW)->get();
        $pengumpulan_akhir_revisi = Jilid::where('status', Jilid::JILID_REVISI)->get();

        // --- DATA TUGAS AKHIR (TA) ---
        // Pengajuan TA (All data for Admin)
        $ta_pengajuans = TAPengajuan::all();
        $ta_pengajuans_diterima = TAPengajuan::where('status', 'diterima')->get();
        $ta_pengajuans_review = TAPengajuan::where('status', 'review')->get();
        $ta_pengajuans_revisi = TAPengajuan::where('status', 'revisi')->get();
        $ta_pengajuans_ditolak = TAPengajuan::where('status', 'ditolak')->get();

        // Pendaftaran TA
        $ta_pendaftarans = TAPendaftaran::all();
        $ta_pendaftarans_diterima = TAPendaftaran::where('status', 'diterima')->get();
        $ta_pendaftarans_review = TAPendaftaran::where('status', 'review')->get();
        $ta_pendaftarans_revisi = TAPendaftaran::where('status', 'revisi')->get();

        // Seminar TA
        $ta_seminars = TASeminar::all();
        $ta_seminars_diterima = TASeminar::where('is_valid', '1')->get();
        $ta_seminars_review = TASeminar::where('is_valid', '0')->get();
        $ta_seminars_revisi = TASeminar::where('is_valid', '2')->get();

        // Ujian TA
        $ta_ujians = TAUjian::all();
        $ta_ujians_diterima = TAUjian::where('is_valid', '1')->get();
        $ta_ujians_review = TAUjian::where('is_valid', '3')->get();
        $ta_ujians_revisi = TAUjian::where('is_valid', '2')->get();

        $mahasiswas = Mahasiswa::all();
        $prodis = Prodi::all();
        $dosens = Dosen::all();
        $fakultas = Fakultas::all();

        return view('kp.pages.admin.dashboard.home', [
            'title' => 'Dashboard KP - Admin',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'pengajuans' => $pengajuans,
            'pengajuans_diterima' => $pengajuans_diterima,
            'pengajuans_review' => $pengajuans_review,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
            'pendaftarans' => $pendaftarans,
            'pendaftarans_diterima' => $pendaftarans_diterima,
            'pendaftarans_review' => $pendaftarans_review,
            'pendaftarans_revisi' => $pendaftarans_revisi,
            'mahasiswas' => $mahasiswas,
            'dosens' => $dosens,
            'prodis' => $prodis,
            'fakultas' => $fakultas,
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
            'pengumpulan_akhir' => $pengumpulan_akhir,
            'pengumpulan_akhir_diterima' => $pengumpulan_akhir_diterima,
            'pengumpulan_akhir_revisi' => $pengumpulan_akhir_revisi,
            'pengumpulan_akhir_review' => $pengumpulan_akhir_review,
            // TA Integration Data
            'ta_pengajuans' => $ta_pengajuans,
            'ta_pengajuans_diterima' => $ta_pengajuans_diterima,
            'ta_pengajuans_review' => $ta_pengajuans_review,
            'ta_pengajuans_revisi' => $ta_pengajuans_revisi,
            'ta_pengajuans_ditolak' => $ta_pengajuans_ditolak,
            'ta_pendaftarans' => $ta_pendaftarans,
            'ta_pendaftarans_diterima' => $ta_pendaftarans_diterima,
            'ta_pendaftarans_review' => $ta_pendaftarans_review,
            'ta_pendaftarans_revisi' => $ta_pendaftarans_revisi,
            'ta_seminars' => $ta_seminars,
            'ta_seminars_diterima' => $ta_seminars_diterima,
            'ta_seminars_review' => $ta_seminars_review,
            'ta_seminars_revisi' => $ta_seminars_revisi,
            'ta_ujians' => $ta_ujians,
            'ta_ujians_diterima' => $ta_ujians_diterima,
            'ta_ujians_review' => $ta_ujians_review,
            'ta_ujians_revisi' => $ta_ujians_revisi,
        ]);
    }

    public function dashboardDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $bimbingans = $dosen->bimbingansKP;
        $bimbingans_diterima = $dosen->bimbingansKP()->where('status', 'diterima')->get();
        $bimbingans_review = $dosen->bimbingansKP()->where('status', 'review')->get();
        $bimbingans_revisi = $dosen->bimbingansKP()->where('status', 'revisi')->get();

        // --- DATA TUGAS AKHIR (TA) ---
        $ta_bimbingans = $dosen->bimbingans;
        $ta_bimbingans_diterima = $dosen->bimbingans()->where('status', 'diterima')->get();
        $ta_bimbingans_review = $dosen->bimbingans()->where('status', 'review')->get();
        $ta_bimbingans_revisi = $dosen->bimbingans()->where('status', 'revisi')->get();


        
        // Helper: Hitung total bimbingan TA untuk display di widget
        $total_ta_bimbingans = count($ta_bimbingans);

        return view('kp.pages.dosen.dashboard.home', [
            'title' => 'Dashboard KP - Dosen',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
            
            // Pass TA Data
            'ta_bimbingans' => $ta_bimbingans,
            'ta_bimbingans_diterima' => $ta_bimbingans_diterima,
            'ta_bimbingans_review' => $ta_bimbingans_review,
            'ta_bimbingans_revisi' => $ta_bimbingans_revisi,
        ]);
    }

    /**
     * Dashboard Mahasiswa untuk Sistem Kerja Praktek (KP)
     * Alur: Pengajuan -> Pendaftaran -> Bimbingan -> Seminar KP -> Pengumpulan Akhir
     */
    public function dashboardMahasiswaKP()
    {
        // Set context session ke KP
        session(['ekapta_context' => 'kp']);
        
        // Pakai relationship KP (tabel dengan suffix _kp)
        $mahasiswa = Mahasiswa::with(['bimbingansKP','pengajuansKP','pendaftaransKP','seminarKP','jilidKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('kp.profile');
        }elseif (substr($mahasiswa->hp, 0, 2) !== '62') {
            return redirect()->route('kp.profile');
        }
        // Cari prodi berdasarkan kode atau nama prodi
        $prodiValue = Auth::guard('mahasiswa')->user()->prodi;
        $prodi = Prodi::where('kode', $prodiValue)->orWhere('namaprodi', $prodiValue)->first();
        
        $pengajuan_acc = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran_acc = $pengajuan_acc ? $pengajuan_acc->pendaftaran()->where('status', Pendaftaran::DITERIMA)->first() : null;

        $bimbingans_acc = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)->get();
        $seminars_acc = $mahasiswa->seminarKP ? $mahasiswa->seminarKP->reviews()->where('status', ReviewSeminar::DITERIMA)->get() : null;

        // Cek bimbingan completed (semua bab sudah ACC)
        $is_bimbingan_completed = false;
        $bagians_count = 0;
        if ($prodi) {
            $bagians_count = count($prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get());
        }
        if ($bagians_count > 0 && count($bimbingans_acc) >= $bagians_count) {
            $is_bimbingan_completed = true;
        }

        // Cek seminar completed (is_lulus = true setelah seminar selesai)
        $is_seminar_completed = false;
        if ($mahasiswa->seminarKP){
            if($mahasiswa->seminarKP->is_lulus){
                $is_seminar_completed = true;
            }
        }

        // Cek pengumpulan akhir completed
        $is_pengumpulan_akhir_completed = false;
        if ($mahasiswa->jilidKP){
            if($mahasiswa->jilidKP->status == Jilid::JILID_SELESAI || $mahasiswa->jilidKP->is_completed == Jilid::JILID_COMPLETED){
                $is_pengumpulan_akhir_completed = true;
            }
        }

        return view('kp.pages.mahasiswa.dashboard.home-kp', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'pendaftaran_acc' => $pendaftaran_acc,
            'is_bimbingan_completed' => count($mahasiswa->bimbingansKP) != 0 ? $is_bimbingan_completed : false,
            'is_seminar_completed' => $mahasiswa->seminarKP ? $is_seminar_completed : false,
            'is_pengumpulan_akhir_completed' => $mahasiswa->jilidKP ? $is_pengumpulan_akhir_completed : false,
        ]);
    }



    public function dashboardHimpunan()
    {
        $seminars = \App\Models\KP\Seminar::all();
        $seminars_diterima = \App\Models\KP\Seminar::where('is_valid', '1')->get();
        $seminars_review = \App\Models\KP\Seminar::where('is_valid', '0')->get();
        $seminars_revisi = \App\Models\KP\Seminar::where('is_valid', '2')->get();

        return view('kp.pages.himpunan.dashboard', [
            'title' => 'Dashboard Himpunan',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
        ]);
    }
}

