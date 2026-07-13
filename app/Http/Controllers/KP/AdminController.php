<?php

namespace App\Http\Controllers\KP;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends \App\Http\Controllers\Controller
{
    /**
     * Dashboard Admin Integrasi (TA + KP)
     * Menampilkan data TA dan KP dalam satu dashboard
     */
    public function dashboardAdmin()
    {
        // TA Data
        $pengajuans = \App\Models\Pengajuan::all();
        $pengajuans_diterima = \App\Models\Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = \App\Models\Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = \App\Models\Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = \App\Models\Pengajuan::where('status', 'ditolak')->get();

        $pendaftarans = \App\Models\Pendaftaran::all();
        $pendaftarans_diterima = \App\Models\Pendaftaran::where('status', 'diterima')->get();
        $pendaftarans_review = \App\Models\Pendaftaran::where('status', 'review')->get();
        $pendaftarans_revisi = \App\Models\Pendaftaran::where('status', 'revisi')->get();

        $seminars = \App\Models\Seminar::all();
        $seminars_diterima = \App\Models\Seminar::where('is_valid', '1')->get();
        $seminars_review = \App\Models\Seminar::where('is_valid', '0')->get();
        $seminars_revisi = \App\Models\Seminar::where('is_valid', '2')->get();

        $ujians = \App\Models\Ujian::all();
        $ujians_diterima = \App\Models\Ujian::where('is_valid', '1')->get();
        $ujians_review = \App\Models\Ujian::where('is_valid', '3')->get();
        $ujians_revisi = \App\Models\Ujian::where('is_valid', '2')->get();

        $mahasiswas = \App\Models\Mahasiswa::all();
        $prodis = \App\Models\Prodi::all();
        $dosens = \App\Models\Dosen::all();
        $fakultas = \App\Models\Fakultas::all();

        // KP Data
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

        return view('kp.pages.admin.dashboard.home', [
            'title' => 'Dashboard Admin Integrasi',
            'active' => 'dashboard',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            // TA Data
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
            'ta_seminars' => $seminars,
            'ta_seminars_diterima' => $seminars_diterima,
            'ta_seminars_revisi' => $seminars_revisi,
            'ta_seminars_review' => $seminars_review,
            'ujians' => $ujians,
            'ujians_diterima' => $ujians_diterima,
            'ujians_revisi' => $ujians_revisi,
            'ujians_review' => $ujians_review,
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
            // Aliases for blade template (KP section uses these without prefix)
            'seminars' => $kp_seminars,
            'seminars_diterima' => $kp_seminars_diterima,
            'seminars_review' => $kp_seminars_review,
            'seminars_revisi' => $kp_seminars_revisi,
            'pengumpulan_akhir' => $kp_pengumpulan_akhir,
            'pengumpulan_akhir_diterima' => $kp_pengumpulan_akhir_diterima,
            'pengumpulan_akhir_review' => $kp_pengumpulan_akhir_review,
            'pengumpulan_akhir_revisi' => $kp_pengumpulan_akhir_revisi,
            // TA Aliases for Integrasi Section (ta_* prefix)
            'ta_pengajuans' => $pengajuans,
            'ta_pengajuans_diterima' => $pengajuans_diterima,
            'ta_pengajuans_review' => $pengajuans_review,
            'ta_pengajuans_revisi' => $pengajuans_revisi,
            'ta_pengajuans_ditolak' => $pengajuans_ditolak,
            'ta_pendaftarans' => $pendaftarans,
            'ta_pendaftarans_diterima' => $pendaftarans_diterima,
            'ta_pendaftarans_review' => $pendaftarans_review,
            'ta_pendaftarans_revisi' => $pendaftarans_revisi,
            'ta_ujians' => $ujians,
            'ta_ujians_diterima' => $ujians_diterima,
            'ta_ujians_review' => $ujians_review,
            'ta_ujians_revisi' => $ujians_revisi,
        ]);
    }

    function account(){
        $admin = Auth::guard('admin')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'admin' => $admin,
        ];

        return view('kp.pages.admin.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $admin = Auth::guard('admin')->user();

        abort_unless($admin && (int) $admin->id === (int) $id, 403);

        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $updateData = [
            'nama' => $validatedData['nama'],
        ];

        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $admin->forceFill($updateData)->save();
        Auth::guard('admin')->setUser($admin->fresh());

        return back()->with('success', 'Akun berhasil diubah');
    }

    function bimbinganInput(){
        // Ambil bimbingan dari mahasiswa yang dosennya is_manual = 1
        // Untuk TA (bukan KP, pakai model TA)
        $bimbingans_review = \App\Models\Bimbingan::whereHas('mahasiswa', function($q) {
            $q->whereHas('dosens', function($q2) {
                $q2->where('status', 'pembimbing')->where('is_manual', 1);
            });
        })->where('status', 'review')->with(['mahasiswa', 'bagian'])->get();

        // Gunakan view terpisah untuk TA karena structure data berbeda dengan KP
        return view('kp.pages.admin.bimbingan.bimbingan-input-ta', [
            'title' => 'Input Bimbingan Dosen (TA)',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'active' => 'bimbingan-input',
            'module' => 'kp',
            'bimbingans_review' => $bimbingans_review,
            'createRoute' => 'kp.bimbingan.admin.input.create.ta',
        ]);
    }

    function bimbinganInputCreate($dosen_id, $mahasiswa_id)
    {
        $dosen = \App\Models\Dosen::findOrFail($dosen_id);
        $mahasiswa = \App\Models\Mahasiswa::findOrFail($mahasiswa_id);
        $bimbingans = $dosen->bimbingans()->where('mahasiswa_id', $mahasiswa->id)->get();

        return view('kp.pages.admin.bimbingan.bimbingan-input-create', [
            'title' => 'Input Manual Bimbingan Dosen (TA)',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'active' => 'bimbingan-input',
            'module' => 'kp',
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'bimbingans' => $bimbingans,
            'dosen_mahasiswa' => \Illuminate\Support\Facades\DB::table('dosen_mahasiswas')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('dosen_id', $dosen->id)
                ->first(),
            'route' => 'kp.bimbingan.admin.input.ta',
        ]);
    }

    function bimbinganInputStore(Request $request)
    {
        $request->validate([
            'lampiran' => [\Illuminate\Validation\Rule::requiredIf(function() use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }) ,'mimes:pdf', 'max:5000'],
        ]);

        if($request->lampiran){
            $lampiran = \App\Helpers\AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
            \Illuminate\Support\Facades\DB::table('dosen_mahasiswas')
                ->where('mahasiswa_id', $request->mahasiswa_id)
                ->where('dosen_id', $request->dosen_id)
                ->update(['lampiran' => $lampiran]);
        }

        $dates = $request->dates;
        $ids = $request->ids;
        for ($i = 0; $i < count($request->dates); $i++) {
            if ($dates[$i] != null) {
                $bimbingan = \App\Models\Bimbingan::findOrFail($ids[$i]);
                $bimbingan->update([
                    "status" => "diterima",
                    "tanggal_acc" => $dates[$i],
                ]);

                if ($bimbingan->mahasiswa->email != '-') {
                     try {
                        \App\Helpers\AppHelper::instance()->send_mail([
                            'mail' => $bimbingan->mahasiswa->email,
                            'subject' => 'Bimbingan Tugas Akhir',
                            'title' => 'EKAPTA',
                            'message' => 'Selamat Bimbingan Tugas Akhir Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
                        ]);
                     } catch (\Exception $e) {
                         // Ignore
                     }
                }
            }
        }
        return back()->with('success', 'Berhasil disimpan');
    }
}




