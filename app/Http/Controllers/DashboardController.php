<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardMahasiswa()
    {
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        return view('pages.mahasiswa.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'bagians' => $prodi->bagians,
        ]);
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
}