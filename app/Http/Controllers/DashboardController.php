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
        $pengajuans = Pengajuan::all();
        $pengajuans_diterima = Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = Pengajuan::where('status', 'ditolak')->get();

        $bimbingans = Bimbingan::all();
        $bimbingans_diterima = Bimbingan::where('status', 'diterima')->get();
        $bimbingans_review = Bimbingan::where('status', 'review')->get();
        $bimbingans_revisi = Bimbingan::where('status', 'revisi')->get();

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

        $mahasiswas = Mahasiswa::all();
        $prodis = Prodi::all();
        $dosens = Dosen::all();
        $fakultas = Fakultas::all();

        return view('pages.admin.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarAdmin',
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
        ]);
    }

    public function dashboardDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $bimbingans = $dosen->bimbingans;
        $bimbingans_diterima = $dosen->bimbingans()->where('status', 'diterima')->get();
        $bimbingans_review = $dosen->bimbingans()->where('status', 'review')->get();
        $bimbingans_revisi = $dosen->bimbingans()->where('status', 'revisi')->get();

        return view('pages.dosen.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarDosen',
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
        ]);
    }

    public function dashboardMahasiswaTA()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran_acc = $pengajuan_acc ? $pengajuan_acc->pendaftaran()->where('status', Pendaftaran::DITERIMA)->first() : null;

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $seminars_acc = $mahasiswa->seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get();
        $ujians_acc = $mahasiswa->ujian->reviews()->where('status', ReviewUjian::DITERIMA)->get();
        $bagians_is_seminar = $prodi->bagians()->where('is_seminar', 1)->get();
        $bagians_is_ujian = $prodi->bagians()->where('is_pendadaran', 1)->get();

        $is_bimbingan_completed = false;
        if (count($bimbingans_acc ) - count($prodi->bagians) == count($prodi->bagians)){
            $is_bimbingan_completed = true;
        }

        $is_seminar_completed = false;
        if (count($seminars_acc) == 5){
            $is_seminar_completed = true;
        }

        $is_ujian_completed = false;
        if (count($ujians_acc) == 5){
            $is_ujian_completed = true;
        }

        return view('pages.mahasiswa.dashboard.home-ta', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'pendaftaran_acc' => $pendaftaran_acc,
            'is_bimbingan_completed' => $is_bimbingan_completed,
            'is_seminar_completed' => $is_seminar_completed,
            'is_ujian_completed' => $is_ujian_completed,
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
