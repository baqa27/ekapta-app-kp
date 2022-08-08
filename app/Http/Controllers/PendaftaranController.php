<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\RevisiPendaftaran;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::where('nim', 2020150031)->with(['revisis'])->get(); //Get data pendaftaran mahasiswa
        // $pendaftarans = Pendaftaran::with(['revisis'])->get(); //Get all data pendaftaran mahasiswa
        return $pendaftarans;
    }

    public function pendaftaranMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $pendaftarans = Pendaftaran::where('nim', Auth::guard('mahasiswa')->user()->nim)->with(['revisis'])->get();
        return view('pages.mahasiswa.pendaftaran.pendaftaran', [
            'title' => 'Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'pendaftarans' => $pendaftarans,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $pengajuan = Pengajuan::where('nim', Auth::guard('mahasiswa')->user()->nim)->where('status', 'diterima')->first();

        return view('pages.mahasiswa.pendaftaran.create', [
            'title' => 'Form Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('nim', Auth::guard('mahasiswa')->user()->nim)->where('status', 'diterima')->first();
        $cekPendaftaran = Pendaftaran::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        if ($cekPendaftaran) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Anda sudah melakukan pendaftaran');
        } else {
            $validatedData = $request->validate([
                'email' => ['required', 'email:dns', 'unique:pendaftarans'],
                'hp' => 'required',
                'semester' => 'required',
                'nomor_pembayaran' => 'required',
                'tanggal_pembayaran' => 'required',
                'biaya' => 'required',
                'lampiran_1' => ['required', 'mimes:pdf'],
                'lampiran_2' => ['required', 'mimes:pdf'],
                'lampiran_3' => ['required', 'mimes:pdf'],
                'lampiran_4' => ['required', 'mimes:pdf'],
                'lampiran_5' => ['required', 'mimes:pdf'],
            ]);

            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampiran-pendaftaran');
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampiran-pendaftaran');
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampiran-pendaftaran');
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampiran-pendaftaran');
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampiran-pendaftaran');

            $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
            $validatedData['judul'] = $pengajuan->judul;
            $validatedData['tanggal_pembayaran'] = $request->tanggal_pembayaran;

            Pendaftaran::create($validatedData);
            return redirect('pendaftaran-mahasiswa')->with('success', 'Berhasil melakukan pendaftaran');
        }
    }

    public function edit(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        return $pendaftaran;
    }

    public function pendaftaranDetail($id)
    {
        $pendaftaran = Pendaftaran::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        return view('pages.mahasiswa.pendaftaran.detail', [
            'title' => 'Detail Pendaftaran Tugas Akhir',
            'active' => 'pendaftaran',
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'pendaftaran' => $pendaftaran,
            'revisis' => $pendaftaran->revisis()->orderBy('created_at', 'desc')->paginate(3),
        ]);
    }

    public function update(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        $validatedData = $request->validate([
            'nim' => 'required',
            'judul' => 'required',
            'email' => 'required',
            'hp' => 'required',
            'semester' => 'required',
            'nomor_pembayaran' => 'required',
            'tanggal_pembayaran' => 'required',
            'biaya' => 'required',
        ]);

        if ($request->file('lampiran_1') || $request->file('lampiran_2') || $request->file('lampiran_3') || $request->file('lampiran_4') || $request->file('lampiran_5')) {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_1);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampiran-pendaftaran');
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampiran-pendaftaran');
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampiran-pendaftaran');
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampiran-pendaftaran');
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampiran-pendaftaran');
        }

        $validatedData['tanggal_pembayaran'] = $request->tanggal_pembayaran;

        $pendaftaran->update($validatedData);
        return $validatedData;
    }

    public function delete(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == 'diterima') {
            return back()->with('error', 'Pendaftaran gagal dihapus');
        } else {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_2);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_3);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_4);
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_5);
            $pendaftaran->delete();
            return back()->with('success', 'Pendaftaran berhasil dihapus');
        }
    }

    public function accPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == 'diterima') {
            return 'sudah di acc';
        } else {
            $pendaftaran->update([
                'status' => 'diterima',
                'tanggal_acc' => now(),
                'lampiran_acc' => AppHelper::instance()->uploadLampiran($request->lampiran_acc, 'lampiran-pendaftaran'),
            ]);
            return 'berhasil di acc';
        }
    }

    public function accPendaftaranUpdate(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status != 'diterima') {
            return 'failed';
        } else {
            AppHelper::instance()->deleteLampiran($pendaftaran->lampiran_acc);
            $pendaftaran->update([
                'lampiran_acc' => AppHelper::instance()->uploadLampiran($request->lampiran_acc, 'lampiran-pendaftaran'),
            ]);
            return 'berhasil di update';
        }
    }

    public function revisiPendaftaran(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);

        $revisi = new RevisiPendaftaran;
        $revisi->catatan = $request->catatan;

        if ($request->file('lampiran')) {
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampiran-revisi');
        }

        $pendaftaran->update([
            'status' => 'revisi',
        ]);

        $pendaftaran->revisis()->save($revisi);

        return $pendaftaran->revisis;
    }

    public function deleteRevisiPendaftaran(Request $request)
    {
        $revisi = RevisiPendaftaran::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return 'success';
    }
}
