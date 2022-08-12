<?php

namespace App\Http\Controllers;

use App\Models\DosenMahasiswa;
use App\Models\Pengajuan;
use App\Models\RevisiPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{

    public function pengajuanProdi()
    {
        $pengajuans = Pengajuan::where('status', 'review')->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        $pengajuans_acc = Pengajuan::where('status', 'diterima')->where('prodi', Auth::guard('prodi')->user()->namaprodi)->orderBy('created_at', 'desc')->get();
        return view('pages.prodi.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
            'pengajuans' => $pengajuans,
            'sidebar' => 'partials.sidebarProdi',
            'active' => 'pengajuan',
            'pengajuans_acc' => $pengajuans_acc,
        ]);
    }

    public function pengajuanMahasiswa()
    {
        $pengajuans = Pengajuan::where('nim', Auth::guard('mahasiswa')->user()->nim)->orderBy('created_at', 'desc')->get();
        return view('pages.mahasiswa.pengajuan.pengajuan', [
            'title' => 'Pengajuan Tugas Akhir',
            'active' => 'pengajuan',
            'pengajuans' => $pengajuans,
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
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(3),
        ]);
    }

    public function pengajuanReview($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        if ($pengajuan->status == 'ditolak' || $pengajuan->status == 'revisi') {
            return back()->with('warning', 'Pengajuan tidak ditemukan');
        }
        $mahasiswa = Mahasiswa::where('nim', $pengajuan->nim)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $dosens = Dosen::all();
        return view('pages.prodi.pengajuan.review', [
            'title' => 'Review pengajuan',
            'active' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'dosens' => $dosens,
            'sidebar' => 'partials.sidebarProdi',
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(3),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'mahasiswa' => $mahasiswa,
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
            'revisis' => $pengajuan->revisis()->orderBy('created_at', 'desc')->paginate(3),
        ]);
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('nim', Auth::guard('mahasiswa')->user()->nim)->whereIn('status', ['review', 'revisi', 'diterima'])->get();
        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => ['required', 'min:5'],
                'deskripsi' => ['required', 'min:100'],
                'lampiran' => ['required', 'mimes:pdf'],
            ]);
            if ($request->file('lampiran')) {
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-pengajuan');
            }
            $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
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
        $validatedData['status'] = 'review';

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
        if ($pengajuan->status == 'ditolak') {
            return back()->with('error', 'Pengajuan tidak bisa diedit');
        } else {
            Dosen::findOrFail($request->dosen_utama);
            Dosen::findOrFail($request->dosen_pendamping);
            $mahasiswa = Mahasiswa::where(['nim' => $request->nim])->first();

            if ($pengajuan->status == 'review') {
                $pengajuan->update([
                    'status' => 'diterima',
                    'tanggal_acc' => now(),
                ]);

                if ($mahasiswa->dosens()->get()->isEmpty()) {
                    $mahasiswa->dosens()->attach([
                        $request->dosen_utama => ['status' => 'utama'],
                        $request->dosen_pendamping => ['status' => 'pendamping'],
                    ]);
                } else {
                    DB::table('dosen_mahasiswas')->where(['mahasiswa_id' => $mahasiswa->id])->whereIn('status', ['utama', 'pendamping'])->delete();
                    $mahasiswa->dosens()->attach([
                        $request->dosen_utama => ['status' => 'utama'],
                        $request->dosen_pendamping => ['status' => 'pendamping'],
                    ]);
                }
                return redirect('pengajuan-prodi')->with('success', 'Pengajuan berhasil diacc');
            }

            if (count($mahasiswa->bimbingans) == 0) {
                if ($mahasiswa->dosens()->get()->isEmpty()) {
                    $mahasiswa->dosens()->attach([
                        $request->dosen_utama => ['status' => 'utama'],
                        $request->dosen_pendamping => ['status' => 'pendamping'],
                    ]);
                } else {
                    DB::table('dosen_mahasiswas')->where(['mahasiswa_id' => $mahasiswa->id])->whereIn('status', ['utama', 'pendamping'])->delete();
                    $mahasiswa->dosens()->attach([
                        $request->dosen_utama => ['status' => 'utama'],
                        $request->dosen_pendamping => ['status' => 'pendamping'],
                    ]);
                }
            }

            return redirect('pengajuan-prodi')->with('success', 'Dosen pendamping berhasil diedit');
        }
    }

    public function tolakPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') {
            return back()->with('warning', 'pengajuan sudah tidak bisa ditolak');
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
                    }), 'mimes:pdf']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
                }

                $pengajuan->update([
                    'status' => 'ditolak',
                ]);

                $pengajuan->revisis()->save($revisi);
            }

            return redirect('pengajuan-prodi')->with('success', 'Pengajuan berhasil ditolak');
        }
    }

    public function revisiPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') {
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
                    }), 'mimes:pdf']
                ]);

                if ($request->file('lampiran')) {
                    $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
                }

                $pengajuan->update([
                    'status' => 'revisi',
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
}
