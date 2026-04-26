<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewSeminar;
use App\Models\ReviewUjian;
use App\Models\Seminar;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => config('app.name'),
            'mahasiswa' => $mahasiswa,
            'active' => ''
        ];

        return view('pages.mahasiswa.home', $data);
    }

    public function dashboardProdi()
    {
        // TA Data
        $pengajuans = Pengajuan::all();
        $pengajuans_diterima = Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = Pengajuan::where('status', 'ditolak')->get();

        $bimbingans = Bimbingan::all();
        $bimbingans_diterima = Bimbingan::where('status', 'diterima')->get();
        $bimbingans_review = Bimbingan::where('status', 'review')->get();
        $bimbingans_revisi = Bimbingan::where('status', 'revisi')->get();

        $seminars = Seminar::all();
        $seminars_diterima = Seminar::where('is_valid', '1')->get();
        $seminars_review = Seminar::where('is_valid', '0')->get();
        $seminars_revisi = Seminar::where('is_valid', '2')->get();

        $ujians = Ujian::all();
        $ujians_diterima = Ujian::where('is_valid', '1')->get();
        $ujians_review = Ujian::where('is_valid', '3')->get();
        $ujians_revisi = Ujian::where('is_valid', '2')->get();

        // KP Data - using KP models
        $kp_pengajuans = \App\Models\KP\Pengajuan::all();
        $kp_pengajuans_diterima = \App\Models\KP\Pengajuan::where('status', 'diterima')->get();
        $kp_pengajuans_revisi = \App\Models\KP\Pengajuan::where('status', 'revisi')->get();
        $kp_pengajuans_review = \App\Models\KP\Pengajuan::where('status', 'review')->get();
        $kp_pengajuans_ditolak = \App\Models\KP\Pengajuan::where('status', 'ditolak')->get();

        $kp_bimbingans = \App\Models\KP\Bimbingan::all();
        $kp_bimbingans_diterima = \App\Models\KP\Bimbingan::where('status', 'diterima')->get();
        $kp_bimbingans_review = \App\Models\KP\Bimbingan::where('status', 'review')->get();
        $kp_bimbingans_revisi = \App\Models\KP\Bimbingan::where('status', 'revisi')->get();

        $kp_seminars = \App\Models\KP\Seminar::all();
        $kp_seminars_diterima = \App\Models\KP\Seminar::where('is_valid', '1')->get();
        $kp_seminars_review = \App\Models\KP\Seminar::where('is_valid', '0')->get();
        $kp_seminars_revisi = \App\Models\KP\Seminar::where('is_valid', '2')->get();

        $kp_pengumpulan_akhir = \App\Models\KP\Jilid::all();
        $kp_pengumpulan_akhir_diterima = \App\Models\KP\Jilid::where('status', 'terkumpul')->orWhere('status', 'selesai')->get();
        $kp_pengumpulan_akhir_review = \App\Models\KP\Jilid::where('status', 'review')->get();
        $kp_pengumpulan_akhir_revisi = \App\Models\KP\Jilid::where('status', 'revisi')->get();

        return view('pages.prodi.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarProdi',
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
            'ujians' => $ujians,
            'ujians_diterima' => $ujians_diterima,
            'ujians_revisi' => $ujians_revisi,
            'ujians_review' => $ujians_review,
            // Context info (default values for TA dashboard)
            'currentContext' => null,
            'isTA' => false,
            'isKP' => false,
            'contextLabel' => '',
            // Alias untuk view yang menggunakan prefix ta_
            'ta_pengajuans' => $pengajuans,
            'ta_pengajuans_diterima' => $pengajuans_diterima,
            'ta_pengajuans_review' => $pengajuans_review,
            'ta_pengajuans_revisi' => $pengajuans_revisi,
            'ta_pengajuans_ditolak' => $pengajuans_ditolak,
            'ta_bimbingans' => $bimbingans,
            'ta_bimbingans_diterima' => $bimbingans_diterima,
            'ta_bimbingans_review' => $bimbingans_review,
            'ta_bimbingans_revisi' => $bimbingans_revisi,
            'ta_seminars' => $seminars,
            'ta_seminars_diterima' => $seminars_diterima,
            'ta_seminars_review' => $seminars_review,
            'ta_seminars_revisi' => $seminars_revisi,
            'ta_ujians' => $ujians,
            'ta_ujians_diterima' => $ujians_diterima,
            'ta_ujians_review' => $ujians_review,
            'ta_ujians_revisi' => $ujians_revisi,
            // KP Data
            'kp_pengajuans' => $kp_pengajuans,
            'kp_pengajuans_diterima' => $kp_pengajuans_diterima,
            'kp_pengajuans_review' => $kp_pengajuans_review,
            'kp_pengajuans_revisi' => $kp_pengajuans_revisi,
            'kp_pengajuans_ditolak' => $kp_pengajuans_ditolak,
            'kp_bimbingans' => $kp_bimbingans,
            'kp_bimbingans_diterima' => $kp_bimbingans_diterima,
            'kp_bimbingans_review' => $kp_bimbingans_review,
            'kp_bimbingans_revisi' => $kp_bimbingans_revisi,
            'kp_seminars' => $kp_seminars,
            'kp_seminars_diterima' => $kp_seminars_diterima,
            'kp_seminars_review' => $kp_seminars_review,
            'kp_seminars_revisi' => $kp_seminars_revisi,
            'kp_pengumpulan_akhir' => $kp_pengumpulan_akhir,
            'kp_pengumpulan_akhir_diterima' => $kp_pengumpulan_akhir_diterima,
            'kp_pengumpulan_akhir_review' => $kp_pengumpulan_akhir_review,
            'kp_pengumpulan_akhir_revisi' => $kp_pengumpulan_akhir_revisi,
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

        $ujians = Ujian::all();
        $ujians_diterima = Ujian::where('is_valid', '1')->get();
        $ujians_review = Ujian::where('is_valid', '3')->get();
        $ujians_revisi = Ujian::where('is_valid', '2')->get();

        $mahasiswas = Mahasiswa::all();
        $prodis = Prodi::all();
        $dosens = Dosen::all();
        $fakultas = Fakultas::all();

        // KP Data - using KP models
        $kp_pengajuans = \App\Models\KP\Pengajuan::all();
        $kp_pengajuans_diterima = \App\Models\KP\Pengajuan::where('status', 'diterima')->get();
        $kp_pengajuans_revisi = \App\Models\KP\Pengajuan::where('status', 'revisi')->get();
        $kp_pengajuans_review = \App\Models\KP\Pengajuan::where('status', 'review')->get();
        $kp_pengajuans_ditolak = \App\Models\KP\Pengajuan::where('status', 'ditolak')->get();

        $kp_pendaftarans = \App\Models\KP\Pendaftaran::all();
        $kp_pendaftarans_diterima = \App\Models\KP\Pendaftaran::where('status', 'diterima')->get();
        $kp_pendaftarans_review = \App\Models\KP\Pendaftaran::where('status', 'review')->get();
        $kp_pendaftarans_revisi = \App\Models\KP\Pendaftaran::where('status', 'revisi')->get();

        $kp_bimbingans = \App\Models\KP\Bimbingan::all();
        $kp_bimbingans_diterima = \App\Models\KP\Bimbingan::where('status', 'diterima')->get();
        $kp_bimbingans_review = \App\Models\KP\Bimbingan::where('status', 'review')->get();
        $kp_bimbingans_revisi = \App\Models\KP\Bimbingan::where('status', 'revisi')->get();

        $kp_seminars = \App\Models\KP\Seminar::all();
        $kp_seminars_diterima = \App\Models\KP\Seminar::where('is_valid', '1')->get();
        $kp_seminars_review = \App\Models\KP\Seminar::where('is_valid', '0')->get();
        $kp_seminars_revisi = \App\Models\KP\Seminar::where('is_valid', '2')->get();

        $kp_pengumpulan_akhir = \App\Models\KP\Jilid::all();
        $kp_pengumpulan_akhir_diterima = \App\Models\KP\Jilid::where('status', 'terkumpul')->orWhere('status', 'selesai')->get();
        $kp_pengumpulan_akhir_review = \App\Models\KP\Jilid::where('status', 'review')->get();
        $kp_pengumpulan_akhir_revisi = \App\Models\KP\Jilid::where('status', 'revisi')->get();

        return view('pages.admin.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => '',
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
            'ujians' => $ujians,
            'ujians_diterima' => $ujians_diterima,
            'ujians_revisi' => $ujians_revisi,
            'ujians_review' => $ujians_review,
            // Context info
            'currentContext' => null,
            'isTA' => false,
            'isKP' => false,
            'contextLabel' => '',
            // Alias untuk view yang menggunakan prefix ta_
            'ta_pengajuans' => $pengajuans,
            'ta_pengajuans_diterima' => $pengajuans_diterima,
            'ta_pengajuans_review' => $pengajuans_review,
            'ta_pengajuans_revisi' => $pengajuans_revisi,
            'ta_pengajuans_ditolak' => $pengajuans_ditolak,
            'ta_pendaftarans' => $pendaftarans,
            'ta_pendaftarans_diterima' => $pendaftarans_diterima,
            'ta_pendaftarans_review' => $pendaftarans_review,
            'ta_pendaftarans_revisi' => $pendaftarans_revisi,
            'ta_seminars' => $seminars,
            'ta_seminars_diterima' => $seminars_diterima,
            'ta_seminars_review' => $seminars_review,
            'ta_seminars_revisi' => $seminars_revisi,
            'ta_ujians' => $ujians,
            'ta_ujians_diterima' => $ujians_diterima,
            'ta_ujians_review' => $ujians_review,
            'ta_ujians_revisi' => $ujians_revisi,
            // KP Data
            'kp_pengajuans' => $kp_pengajuans,
            'kp_pengajuans_diterima' => $kp_pengajuans_diterima,
            'kp_pengajuans_review' => $kp_pengajuans_review,
            'kp_pengajuans_revisi' => $kp_pengajuans_revisi,
            'kp_pengajuans_ditolak' => $kp_pengajuans_ditolak,
            'kp_pendaftarans' => $kp_pendaftarans,
            'kp_pendaftarans_diterima' => $kp_pendaftarans_diterima,
            'kp_pendaftarans_review' => $kp_pendaftarans_review,
            'kp_pendaftarans_revisi' => $kp_pendaftarans_revisi,
            'kp_bimbingans' => $kp_bimbingans,
            'kp_bimbingans_diterima' => $kp_bimbingans_diterima,
            'kp_bimbingans_review' => $kp_bimbingans_review,
            'kp_bimbingans_revisi' => $kp_bimbingans_revisi,
            'kp_seminars' => $kp_seminars,
            'kp_seminars_diterima' => $kp_seminars_diterima,
            'kp_seminars_review' => $kp_seminars_review,
            'kp_seminars_revisi' => $kp_seminars_revisi,
            'kp_pengumpulan_akhir' => $kp_pengumpulan_akhir,
            'kp_pengumpulan_akhir_diterima' => $kp_pengumpulan_akhir_diterima,
            'kp_pengumpulan_akhir_review' => $kp_pengumpulan_akhir_review,
            'kp_pengumpulan_akhir_revisi' => $kp_pengumpulan_akhir_revisi,
        ]);
    }

    public function dashboardDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $bimbingans = $dosen->bimbingans;
        $bimbingans_diterima = $dosen->bimbingans()->where('status', 'diterima')->get();
        $bimbingans_review = $dosen->bimbingans()->where('status', 'review')->get();
        $bimbingans_revisi = $dosen->bimbingans()->where('status', 'revisi')->get();

        // Ambil bimbingan KP (dari tabel bimbingan_kps)
        $kp_bimbingans = collect(); // Empty collection jika belum ada data KP
        $kp_bimbingans_diterima = collect();
        $kp_bimbingans_review = collect();
        $kp_bimbingans_revisi = collect();
        
        // Cek apakah tabel bimbingan_kps ada (migration KP sudah dijalankan)
        if (\Schema::hasTable('bimbingan_kps')) {
            // Ambil bimbingan KP dari relasi (jika model sudah ada)
            try {
                $kp_bimbingans = $dosen->bimbingansKP ?? collect();
                $kp_bimbingans_diterima = $dosen->bimbingansKP()->where('status', 'diterima')->get() ?? collect();
                $kp_bimbingans_review = $dosen->bimbingansKP()->where('status', 'review')->get() ?? collect();
                $kp_bimbingans_revisi = $dosen->bimbingansKP()->where('status', 'revisi')->get() ?? collect();
            } catch (\Exception $e) {
                // Jika relasi belum ada, gunakan empty collection
                $kp_bimbingans = collect();
                $kp_bimbingans_diterima = collect();
                $kp_bimbingans_review = collect();
                $kp_bimbingans_revisi = collect();
            }
        }

        return view('pages.dosen.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarDosen',
            // Variabel TA tanpa prefix (untuk backward compatibility)
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
            // Variabel TA dengan prefix ta_
            'ta_bimbingans' => $bimbingans,
            'ta_bimbingans_diterima' => $bimbingans_diterima,
            'ta_bimbingans_review' => $bimbingans_review,
            'ta_bimbingans_revisi' => $bimbingans_revisi,
            // Variabel KP dengan prefix kp_
            'kp_bimbingans' => $kp_bimbingans,
            'kp_bimbingans_diterima' => $kp_bimbingans_diterima,
            'kp_bimbingans_review' => $kp_bimbingans_review,
            'kp_bimbingans_revisi' => $kp_bimbingans_revisi,
        ]);
    }

    public function dashboardMahasiswaTA()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans','pengajuans','pendaftarans','seminar','ujians'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }elseif (substr($mahasiswa->hp, 0, 2) !== '62') {
            return redirect()->route('profile');
        }
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran_acc = $pengajuan_acc ? $pengajuan_acc->pendaftaran()->where('status', Pendaftaran::DITERIMA)->first() : null;

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $seminars_acc = $mahasiswa->seminar ? $mahasiswa->seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get() : null;
        $ujians_acc = $mahasiswa->ujian ?$mahasiswa->ujian->reviews()->where('status', ReviewUjian::DITERIMA)->get() : null;

        $is_bimbingan_completed = false;
        if (count($bimbingans_acc ) - count($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get()) == count($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get())){
            $is_bimbingan_completed = true;
        }

        $is_seminar_completed = false;
        if ($mahasiswa->seminar){
            // if (count($seminars_acc) == 5){
            //     $is_seminar_completed = true;
            // }
            if($mahasiswa->seminar->is_lulus){
                $is_seminar_completed = true;
            }
        }

        $is_ujian_completed = false;
        if ($mahasiswa->ujian){
            // if (count($ujians_acc) == 5){
            //     $is_ujian_completed = true;
            // }
            if($mahasiswa->ujian->is_lulus){
                $is_ujian_completed = true;
            }
        }

        return view('pages.mahasiswa.dashboard.home-ta', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'pendaftaran_acc' => $pendaftaran_acc,
            'is_bimbingan_completed' => count($mahasiswa->bimbingans) != 0 ? $is_bimbingan_completed : false,
            'is_seminar_completed' => $mahasiswa->seminar ? $is_seminar_completed : false,
            'is_ujian_completed' => $mahasiswa->ujian ? $is_ujian_completed : false,
        ]);
    }

    public function dashboardMahasiswaKP()
    {
        return "<center><h1>SEDANG DALAM TAHAP PENGEMBANGAN</h1></center>";
    }

    public function dashboardMahasiswaJilid()
    {
        return "<center><h1>SEDANG DALAM TAHAP PENGEMBANGAN</h1></center>";
    }
}
