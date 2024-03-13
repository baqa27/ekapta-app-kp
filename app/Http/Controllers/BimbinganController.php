<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\RevisiBimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\Dosen;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BimbinganController extends Controller
{

    public function bimbinganProdi()
    {
        $mahasiswas = Mahasiswa::where('prodi', Auth::guard('prodi')->user()->namaprodi)->with(['bimbingans'])->get();

        return view('pages.prodi.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function bimbinganDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        return view('pages.dosen.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'bimbingans' => $dosen->bimbingans()->where('status', 'review')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_diterima' => $dosen->bimbingans()->where('status', 'diterima')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_revisi' => $dosen->bimbingans()->where('status', 'revisi')->orderBy('tanggal_bimbingan', 'desc')->get(),
        ]);
    }

    public function bimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Silahkan melakukan Pendaftaran Tugas Akhir terlebih dahulu');
        }

        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where('is_seminar', 1)->get();
        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();

        $is_seminar = null;
        if (count($bimbingans_is_acc) - count($bagians_is_seminar) >= count($bagians_is_seminar)) {
            $is_seminar = true;
        }

        return view('pages.mahasiswa.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'bimbingans_utama' => $mahasiswa->bimbingans()->where('pembimbing', 'utama')->get(),
            'bimbingans_pendamping' => $mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get(),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'date_expired' => Carbon::parse($pendaftaran_acc->tanggal_acc)->addMonthsNoOverflow(12),
            'is_seminar' => $is_seminar,
            'is_expired' => AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc),
            'pendaftaran_acc' => $pendaftaran_acc,
            'mahasiswa' => $mahasiswa,
            'jilid' => $mahasiswa->jilid,
        ]);
    }

    public function create()
    {
        return back();
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.mahasiswa.bimbingan.create', [
            'title' => 'Form Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
        ]);
    }

    public function store(Request $request)
    {
        return back();
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)
            ->whereIn('status', ['review', 'revisi'])
            ->get();
        $bimbinganIfExists = Bimbingan::where(['mahasiswa_id' => Auth::guard('mahasiswa')->user()->id, 'bagian_id' => $request->bagian_id, 'status' => 'diterima'])
            ->get();
        Bagian::findOrFail($request->bagian_id);
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        if ($cekBimbingan->isEmpty()) {
            if ($bimbinganIfExists->isEmpty()) {
                $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
                $request->validate([
                    'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
                    'bagian_id' => 'required',
                ]);
                $bimbingan = new Bimbingan;
                $bimbingan->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
                $bimbingan->keterangan = $request->keterangan;
                $bimbingan->bagian_id = $request->bagian_id;

                $mahasiswa->bimbingans()->save($bimbingan);

                $bimbingan->dosens()->attach([$dosenUtama->id, $dosenPendamping->id]);

                return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil dibuat. Silahkan tunggu review dari dosen pembiming');
            } else {
                return back()->with('warning', 'Bimbingan dengan bagian yang sama sudah di Acc');
            }
        } else {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan dengan bagian yang sama sudah dibuat. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();

        $pendaftaran = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if(AppHelper::instance()->is_expired_in_one_year($pendaftaran->tanggal_acc)){
            return redirect('bimbingan-mahasiswa')->with('warning','Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
        }

        $bimbingan = Bimbingan::findOrFail($id);

        if ($bimbingan->status == 'review' || $bimbingan->status == 'ditolak' ||    $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak dapat disubmit');
        } elseif (count($mahasiswa->bimbingans()->where('status', 'review')->get()) >= 2) {
            return back()->with('warning', 'Tunggu sampai bimbingan di Acc oleh dosen');
        }

        return view('pages.mahasiswa.bimbingan.edit', [
            'title' => 'Form Edit Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
        ]);
    }

    public function bimbinganDetail($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        if ($bimbingan->mahasiswa->nim != Auth::guard('mahasiswa')->user()->nim) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }
        if ($bimbingan->status == null) {
            return back()->with('warning', 'Harap edit Bimbingan terlebih dahulu');
        }
        return view('pages.mahasiswa.bimbingan.detail', [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function bimbinganReview($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        $mahasiswa = Mahasiswa::find($bimbingan->mahasiswa->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', 'diterima')->first();

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', 'diterima')->get();

        return view('pages.dosen.bimbingan.review', [
            'title' => 'Review Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'bagians' => $prodi->bagians,
            'bimbingans_acc' => $bimbingans_acc,
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'review')->get();

        $pendaftaran = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if(AppHelper::instance()->is_expired_in_one_year($pendaftaran->tanggal_acc)){
            return redirect('bimbingan-mahasiswa')->with('warning','Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
        }

        if (count($cekBimbingan) >= 2) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Harap menunggu Acc bimbingan dari dosen Pembimbing');
        } else {
            if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan tidak bisa diedit');
            }
            $validatedData = $request->validate([
                'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
            ]);
            if ($request->file('lampiran')) {
                AppHelper::instance()->deleteLampiran($bimbingan->lampiran);
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
            }
            $validatedData['keterangan'] = $request->keterangan;
            if ($bimbingan->status == null) {
                $validatedData['tanggal_bimbingan'] = now();
            }
            $validatedData['status'] = 'review';
            $bimbingan->update($validatedData);
            return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil diupdate. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function delete(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if (count($bimbingan->revisis) != 0) {
            return back()->with('warning', 'Bimbingan tidak bisa dihapus');
        }
        AppHelper::instance()->deleteLampiran($bimbingan->lampiran);

        DB::table('dosen_bimbingans')->where('bimbingan_id', $bimbingan->id)->delete();

        $bimbingan->delete();
        return back()->with('success', 'Bimbingan berhasil dihapus');
    }

    public function accBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan sudah di Acc');
        }
        $bimbingan->update([
            'status' => 'diterima',
            'tanggal_acc' => now(),
        ]);
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil di Acc');
    }

    public function revisiBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'revisi') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan tidak bisa direvisi');
        }

        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);

        $revisi->catatan = $request->catatan;
        $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        $bimbingan->update([
            'status' => 'revisi',
        ]);
        $bimbingan->revisis()->save($revisi);
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil direvisi');
    }

    public function deleteRevisiBimbingan(Request $request)
    {
        $revisi = RevisiBimbingan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function cancelAcc(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
            'tanggal_acc' => null,
        ]);
        return back()->with('success', 'Acc bimbingan berhasil dibatalkan');
    }

    public function cancelRevisi(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
        ]);
        return back()->with('success', 'Revisi bimbingan berhasil dibatalkan');
    }

    public function reviewProdi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.prodi.bimbingan.detail', $data);
    }

    public function bimbinganAdmin()
    {
        $mahasiswas = Mahasiswa::with(['bimbingans'])->get();

        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Laporan Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function reviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.admin.bimbingan.detail', $data);
    }

    public function bimbinganDosenProgress()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        return view('pages.dosen.bimbingan.bimbingan-progress', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan-progress',
            'sidebar' => 'partials.sidebarDosen',
            'mahasiswas' => $dosen->mahasiswas()->with(['bimbingans'])->get(),
        ]);
    }

    public function rekapDosen(){
        $prodi =  Auth::guard('prodi')->user();
        return view('pages.prodi.bimbingan.rekap-dosen',[
            'title' => 'Rekap Bimbingan Dosen',
            'sidebar' => 'partials.sidebarProdi',
            'active' => 'dashboard',
            'dosens' => $prodi->dosens,
        ]);
    }

    public function bimbinganAdminInput(){
        $dosens = Dosen::with(['mahasiswas'])->where('is_manual', 1)->get();
        return view('pages.admin.bimbingan.bimbingan-input',[
            'title' => 'Bimbingan Dosen',
            'sidebar' => 'partials.sidebarAdmin',
            'active' => 'bimbingan-input',
            'dosens' => $dosens,
        ]);
    }

    public function bimbinganAdminInputCreate($dosen_id, $mahasiswa_id){
        $dosen = Dosen::findOrFail($dosen_id);
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);
        $bimbingans = $dosen->bimbingans()->where('mahasiswa_id', $mahasiswa->id)->get();
        return view('pages.admin.bimbingan.bimbingan-store',[
            'title' => 'Input Manual Bimbingan Dosen',
            'sidebar' => 'partials.sidebarAdmin',
            'active' => 'bimbingan-input',
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'bimbingans' => $bimbingans,
            'dosen_mahasiswa' => DB::table('dosen_mahasiswas')->where('mahasiswa_id', $mahasiswa->id)->where('dosen_id', $dosen->id)->first(),
        ]);
    }

    public function bimbinganAdminInputStore(Request $request){
        $request->validate([
            'lampiran' => ['mimes:pdf','max:1000'],
        ]);
        $lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        $dates = $request->dates;
        $ids = $request->ids;
        for ($i=0;$i < count($request->ids);$i++) {
            $bimbingan = Bimbingan::findOrFail($ids[$i]);
            $bimbingan->update([
                "status" => "diterima",
                "tanggal_acc" => $dates[$i],
            ]);
        }
        DB::table('dosen_mahasiswas')->where('mahasiswa_id', $request->mahasiswa_id)->where('dosen_id', $request->dosen_id)->update(['lampiran' => $lampiran]);
        return back()->with('success', 'Berhasil disimpan');
    }

    public function public($id){
        $mahasiswa = Mahasiswa::with(['bimbingans'])->where('id', $id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at','desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        return view('pages.public.detail',[
            'title' => 'Detail Riwayat Bimbingan Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'date_expired' => Carbon::parse($pendaftaran_acc->tanggal_acc)->addMonthsNoOverflow(12),
            'is_expired' => AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)
        ]);
    }

}
