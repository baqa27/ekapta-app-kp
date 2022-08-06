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
        // $pengajuans = Pengajuan::where(['nim' => 2020150031])->with(['revisis'])->get(); // Get pengajuan mahasiswa
        $pengajuans = Pengajuan::with(['revisis'])->get(); // Get semua pengajuan
        return $pengajuans;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $cekPengajuan = Pengajuan::where('nim', $request->nim)->get();
        if ($cekPengajuan->isEmpty()) {
            $validatedData = $request->validate([
                'judul' => 'required',
                'deskripsi' => 'required',
                'lampiran' => ['required', 'mimes:pdf'],
            ]);
            if ($request->file('lampiran')) {
                $lampiran = $request->file('lampiran');
                $lampiranName = uniqid() . '.' . $lampiran->extension();
                $lampiran->move(public_path('/lampiran-pengajuan'), $lampiranName);
                $lampiranPath = '/lampiran-pengajuan/' . $lampiranName;
                $validatedData['lampiran'] = $lampiranPath;
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
            if (file_exists(public_path($pengajuan->lampiran))) {
                unlink(public_path($pengajuan->lampiran));
            }
            $lampiran = $request->file('lampiran');
            $lampiranName = uniqid() . '.' . $lampiran->extension();
            $lampiran->move(public_path('/lampiran-pengajuan'), $lampiranName);
            $lampiranPath = '/lampiran-pengajuan/' . $lampiranName;
            $validatedData['lampiran'] = $lampiranPath;
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
        if ($pengajuan->status == 'diterima') {
            return 'pengajuan sudah di acc';
        } else {
            $pengajuan->update([
                'status' => 'diterima',
                'tanggal_acc' => now(),
            ]);

            return $pengajuan;
        }
    }

    public function tolakPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);
        $pengajuan->update([
            'status' => 'ditolak',
        ]);
        return $pengajuan;
    }

    public function revisiPengajuan(Request $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->id);

        $revisi = new RevisiPengajuan;
        $revisi->catatan = $request->catatan;
        if ($request->file('lampiran')) {
            $lampiran = $request->file('lampiran');
            $lampiranName = uniqid() . '.' . $lampiran->extension();
            $lampiran->move(public_path('/lampiran-revisi'), $lampiranName);
            $lampiranPath = '/lampiran-revisi/' . $lampiranName;
            $revisi->lampiran = $lampiranPath;
        }

        $pengajuan->update([
            'status' => 'revisi',
        ]);

        $pengajuan->revisis()->save($revisi);

        return $pengajuan->revisis;
    }

    public function deleteRevisiPengajuan(Request $request)
    {
        $revisi = RevisiPengajuan::findOrFail($request->id);
        if (file_exists(public_path($revisi->lampiran))) {
            unlink(public_path($revisi->lampiran));
        }
        $revisi->delete();
        return 'revisi berhasil dihapus';
    }
}
