<?php

namespace App\Http\Controllers;

use App\Models\DosenMahasiswa;
use App\Models\Pengajuan;
use App\Models\RevisiPengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::where('nim', 2020150031)->with(['revisis'])->get(); // Get pengajuan mahasiswa
        // $pengajuans = Pengajuan::with(['revisis'])->get(); // Get semua pengajuan
        return $pengajuans;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('nim', $request->nim)->whereIn('status', ['review', 'revisi', 'diterima'])->get();
        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => 'required',
                'deskripsi' => 'required',
                'lampiran' => ['required', 'mimes:pdf'],
            ]);
            if ($request->file('lampiran')) {
                $validatedData['lampiran'] = $this->uploadLampiran($request->lampiran, 'lampiran-pengajuan');
            }
            $validatedData['nim'] = $request->nim;
            Pengajuan::create($validatedData);
            return $validatedData;
        } else {
            return 'Pengajuan sudah dibuat';
        }
    }

    public function edit(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        return $pengajuan;
    }

    public function update(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        $validatedData = $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($request->file('lampiran')) {
            $this->deleteLampiran($pengajuan->lampiran);
            $validatedData['lampiran'] = $this->uploadLampiran($request->lampiran, 'lampiran-pengajuan');
        }

        $validatedData['nim'] = 2020150031;
        $validatedData['status'] = 'review';

        $pengajuan->update($validatedData);
        return $pengajuan;
    }

    public function delete(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if (file_exists(public_path($pengajuan->lampiran))) {
            unlink(public_path($pengajuan->lampiran));
        }
        $pengajuan->delete();
        return 'Pengajuan berhasil dihapus';
    }

    public function accPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') {
            return 'pengajuan sudah tidak bisa di acc';
        } else {
            $revisi = new RevisiPengajuan;
            $revisi->catatan = $request->catatan;

            if ($request->file('lampiran')) {
                $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
            }

            $pengajuan->update([
                'status' => 'diterima',
                'tanggal_acc' => now(),
            ]);
            $pengajuan->revisis()->save($revisi);

            return $pengajuan;
        }
    }

    public function tolakPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') {
            return 'pengajuan sudah tidak bisa ditolak';
        } else {
            $revisi = new RevisiPengajuan;
            $revisi->catatan = $request->catatan;

            if ($request->file('lampiran')) {
                $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
            }

            $pengajuan->update([
                'status' => 'ditolak',
            ]);

            $pengajuan->revisis()->save($revisi);

            return $pengajuan;
        }
    }

    public function revisiPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        if ($pengajuan->status == 'diterima' || $pengajuan->status == 'ditolak') {
            return 'Pengajuan tidak bisa direvisi';
        } else {
            $revisi = new RevisiPengajuan;
            $revisi->catatan = $request->catatan;

            if ($request->file('lampiran')) {
                $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
            }

            $pengajuan->update([
                'status' => 'revisi',
            ]);

            $pengajuan->revisis()->save($revisi);

            return $pengajuan->revisis;
        }
    }

    public function deleteRevisiPengajuan(Request $request)
    {
        $revisi = RevisiPengajuan::findOrFail($request->id);
        $this->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return 'revisi berhasil dihapus';
    }

    public function uploadLampiran($lampiran, $path)
    {
        if ($lampiran) {
            $lampiranName = uniqid() . '.' . $lampiran->extension();
            $lampiran->move(public_path('/' . $path), $lampiranName);
            $lampiranPath = '/' . $path . '/' . $lampiranName;
            return $lampiranPath;
        }
    }

    public function deleteLampiran($lampiran)
    {
        if (file_exists(public_path($lampiran))) {
            unlink(public_path($lampiran));
        }
    }
}
