<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\RevisiPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Validation\Rule;

class PengajuanController extends Controller
{

    public function pengajuanProdi()
    {
        $pengajuans = Pengajuan::where('status', Pengajuan::REVIEW)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        $pengajuans_acc = Pengajuan::where('status', Pengajuan::DITERIMA)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        $pengajuans_revisi = Pengajuan::where('status', Pengajuan::REVISI)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        $pengajuans_ditolak = Pengajuan::where('status', Pengajuan::DITOLAK)->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        return view('pages.prodi.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
            'pengajuans' => $pengajuans,
            'sidebar' => 'partials.sidebarProdi',
            'active' => 'pengajuan',
            'pengajuans_acc' => $pengajuans_acc,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
        ]);
    }

    public function pengajuanMahasiswa()
    {
        $pengajuans = Pengajuan::orderBy('created_at','desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->get();
        $pengajuans_acc = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->get();
        return view('pages.mahasiswa.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
            'pengajuans' => $pengajuans,
            'pengajuans_acc' => $pengajuans_acc,
        ]);
    }

    public function pengajuanAdmin()
    {
        $pengajuans = Pengajuan::orderBy('created_at', 'desc')->get();
        return view('pages.admin.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'sidebar' => 'partials.sidebarAdmin',
            'active' => 'pengajuan',
            'pengajuans' => $pengajuans,
        ]);
    }

    public function create()
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();
        if ($pengajuan) {
            return back()->with('warning', 'Pengajuan anda sudah diterima');
        }
        return view('pages.mahasiswa.pengajuan.create', [
            'title' => 'Form Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
        ]);
    }

    public function pengajuanDetail($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        if ($pengajuan->nim != Auth::guard('mahasiswa')->user()->nim) {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        return view('pages.mahasiswa.pengajuan.detail', [
            'title' => 'Detail pengajuan',
            'active' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function pengajuanReview($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $mahasiswa = $pengajuan->mahasiswa;
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $dosens = Dosen::where('kodeprodi', Auth::guard('prodi')->user()->kode)->get();
        $pengajuanCekIsPlagiat = Pengajuan::where('judul', 'LIKE', '%' . $pengajuan->judul . '%')->get();
        return view('pages.prodi.pengajuan.review', [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'dosens' => $dosens,
            'sidebar' => 'partials.sidebarProdi',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'mahasiswa' => $mahasiswa,
            'pengajuanCekIsPlagiat' => $pengajuanCekIsPlagiat,
        ]);
    }

    public function pengajuanReviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('pages.admin.pengajuan.review', [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'sidebar' => 'partials.sidebarAdmin',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->whereIn('status', [Pengajuan::REVIEW, Pengajuan::REVISI, Pengajuan::DITERIMA])->get();
        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => ['required', 'min:5'],
                'deskripsi' => ['required', 'min:100'],
                'lampiran' => ['required', 'mimes:pdf'],
            ]);
            if ($request->file('lampiran')) {
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-pengajuan');
            }
            $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
            $validatedData['prodi'] = Auth::guard('mahasiswa')->user()->prodi;
            Pengajuan::create($validatedData);
            return redirect('pengajuan-mahasiswa')->with('success', 'Berhasil melakukan pengajuan tugas akhir');
        } else {
            return redirect('pengajuan-mahasiswa')->with('warning', 'Menunggu review dari prodi');
        }
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        if ($pengajuan->status == Pengajuan::REVIEW || $pengajuan->status == Pengajuan::DITERIMA) {
            return back()->with('warning', 'Pengajuan tidak bisa diedit');
        }
        return view('pages.mahasiswa.pengajuan.edit', [
            'title' => 'Form Edit Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        $validatedData = $request->validate([
            'judul' => ['required', 'min:5'],
            'deskripsi' => ['required', 'min:100'],
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf']
        ]);

        if ($request->file('lampiran')) {
            AppHelper::instance()->deleteLampiran($pengajuan->lampiran);
            $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-pengajuan');
        }

        $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
        $validatedData['status'] = Pengajuan::REVIEW;

        $pengajuan->update($validatedData);
        return redirect('pengajuan-mahasiswa')->with('success', 'Berhasil melakukan pengajuan tugas akhir');
        return $validatedData;
    }

    public function delete(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($pengajuan->lampiran);

        foreach ($pengajuan->revisis as $revisi) {
            AppHelper::instance()->deleteLampiran($revisi->lampiran);
        }

        $pengajuan->revisis->each->delete();
        $pengajuan->delete();
        return back()->with('success', 'Pengajuan berhasil dihapus');
    }

    public function accPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('error', 'Pengajuan tidak bisa diedit');
        } else {
            if ($pengajuan->status == Pengajuan::REVIEW) {
                $pengajuan->update([
                    'status' => Pengajuan::DITERIMA,
                    'tanggal_acc' => now(),
                ]);
                return back()->with('success', 'Pengajuan berhasil diacc');
            }
        }
    }

    public function cancelAcc(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status != Pengajuan::DITERIMA) {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        $pengajuan->update([
            'status' => Pengajuan::REVIEW,
            'tanggal_acc' => null,
        ]);
        return back()->with('success', 'Acc pengajuan berhasil dibatalkan');
    }

    public function tolakPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITERIMA || $pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('warning', 'pengajuan sudah tidak bisa ditolak');
        } else {
            $revisi = new RevisiPengajuan;
            if ($request->catatan) {
                $revisi->catatan = $request->catatan;

                $request->validate([
                    'lampiran' => [Rule::requiredIf(function () {
                        if (empty($this->request->lampiran)) {
                            return false;
                        }
                        return true;
                    }), 'mimes:pdf']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
                }

                $pengajuan->revisis()->save($revisi);
            }

            $pengajuan->update([
                'status' => Pengajuan::DITOLAK,
            ]);

            return redirect('pengajuan-prodi')->with('success', 'Pengajuan berhasil ditolak');
        }
    }

    public function cancelTolak(Request $request)
    {
        $cekPengajuan = Pengajuan::where('mahasiswa_id', $request->mahasiswa_id)->whereIn('status', [Pengajuan::DITERIMA, Pengajuan::REVIEW, Pengajuan::REVISI])->first();
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($cekPengajuan) {
            return back()->with('warning', 'Pengajuan tidak bisa dibatalkan, karena sudah ada pengajuan dengan status Diterima/Direview/Direvisi');
        } else {
            if ($pengajuan->status != Pengajuan::DITOLAK) {
                return back()->with('warning', 'Pengajuan tidak ditemukan');
            }
            $pengajuan->update([
                'status' => Pengajuan::REVIEW,
            ]);
            return back()->with('success', 'Tolak pengajuan berhasil dibatalkan');
        }
    }

    public function revisiPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == Pengajuan::DITERIMA || $pengajuan->status == Pengajuan::DITOLAK) {
            return back()->with('warning', 'Pengajuan sudah tidak bisa direvisi');
        } else {
            if ($request->catatan) {
                $revisi = new RevisiPengajuan;
                $revisi->catatan = $request->catatan;

                $request->validate([
                    'lampiran' => [Rule::requiredIf(function () {
                        if (empty($this->request->lampiran)) {
                            return false;
                        }
                        return true;
                    }), 'mimes:pdf,docx']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
                }

                $pengajuan->update([
                    'status' => Pengajuan::REVISI,
                ]);

                $pengajuan->revisis()->save($revisi);
            }

            return redirect('pengajuan-prodi')->with('success', 'Pengajuan berhasil direvisi');
        }
    }

    public function deleteRevisiPengajuan(Request $request)
    {
        $revisi = RevisiPengajuan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');;
    }

    public function editJudulPengajuan(Request $request, $id){
        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->update([
            'judul' => $request->judul,
        ]);

        return back()->with('success','Judul tugas akhir berhasil di update.');
    }
}
