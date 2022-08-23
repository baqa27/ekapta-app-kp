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
        ]);
    }

    public function bimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $cekPendaftaranAcc = Pendaftaran::where('nim', Auth::guard('mahasiswa')->user()->nim)->where('status', 'diterima')->first();
        if (!$cekPendaftaranAcc) {
            return back()->with('warning', 'Silahkan melakukan Pendaftaran Tugas Akhir terlebih dahulu');
        }
        return view('pages.mahasiswa.bimbingan.bimbingan', [
            'title' => 'Bimbingan Tugas Akhir',
            'active' => 'bimbingan',
            'bimbingans_utama' => $mahasiswa->bimbingans()->where('pembimbing', 'utama')->get(),
            'bimbingans_pendamping' => $mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get(),
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
        ]);
    }

    public function create()
    {
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
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)
            ->whereIn('status', ['review', 'revisi'])
            ->get(); // cek apakah masih ada bimbingan dengan status review atau revisi 

        $bimbinganIfExists = Bimbingan::where(['mahasiswa_id' => Auth::guard('mahasiswa')->user()->id, 'bagian_id' => $request->bagian_id, 'status' => 'diterima'])
            ->get(); // cek ketika bagian bimbingan yang sudah diterima sebelumnya di inputkan lagi 

        Bagian::findOrFail($request->bagian_id); //cek bagain bimbingan apakah ada 

        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        if ($cekBimbingan->isEmpty()) {
            if ($bimbinganIfExists->isEmpty()) {
                $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
                $request->validate([
                    'lampiran' => ['required', 'mimes:pdf'],
                    'bagian_id' => 'required',
                ]);
                // $bimbingan = new Bimbingan;
                // $bimbingan->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-bimbingan');
                // $bimbingan->keterangan = $request->keterangan;
                // $bimbingan->bagian_id = $request->bagian_id;

                // $mahasiswa->bimbingans()->save($bimbingan);

                // $bimbingan->dosens()->attach([$dosenUtama->id, $dosenPendamping->id]);

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
        $bimbingan = Bimbingan::findOrFail($id);
        if ($bimbingan->status == 'review' || $bimbingan->status == 'ditolak' || $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak dapat diedit');
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
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(3),
        ]);
    }

    public function bimbinganReview($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        if ($bimbingan->status == 'revisi' || $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        $mahasiswa = Mahasiswa::find($bimbingan->mahasiswa->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', 'diterima')->get();

        return view('pages.dosen.bimbingan.review', [
            'title' => 'Review Bimbingan Tugas Akhir',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(3),
            'bagians' => $prodi->bagians,
            'bimbingans_acc' => $bimbingans_acc,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'review')->get();
        if (count($cekBimbingan) >= 2) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Harap menunggu Acc bimbingan dari dosen Pembimbing');
        } else {
            if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan tidak bisa diedit');
            }
            $validatedData = $request->validate([
                'lampiran' => [Rule::requiredIf(function () {
                    if (empty($this->request->lampiran)) {
                        return false;
                    }
                    return true;
                }), 'mimes:pdf']
            ]);
            if ($request->file('lampiran')) {
                AppHelper::instance()->deleteLampiran($bimbingan->lampiran);
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-bimbingan');
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

        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () {
                if (empty($this->request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf']
        ]);
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        $bimbingan->update([
            'status' => 'diterima',
            'tanggal_acc' => now(),
        ]);
        $bimbingan->revisis()->save($revisi);
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
            }), 'mimes:pdf']
        ]);
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
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
}
