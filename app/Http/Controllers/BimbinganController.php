<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\RevisiBimbingan;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function index()
    {
        $bimbingans = Mahasiswa::where('id', 1)->with(['bimbingans', 'bagians', 'dosens'])->get();
        return $bimbingans;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $cekBimbingan = Bimbingan::where('mahasiswa_id', 1)
            ->whereIn('status', ['review', 'revisi'])
            ->get(); // cek apakah masih ada bimbingan dengan status review atau revisi 

        $bimbinganIfExists = Bimbingan::where(['mahasiswa_id' => 1, 'bagian_id' => $request->bagian_id, 'status' => 'diterima'])
            ->get(); // cek ketika bagian bimbingan yang sudah diterima sebelumnya di inputkan lagi 

        Bagian::findOrFail($request->bagian_id); //cek bagain bimbingan apakah ada 

        if ($cekBimbingan->isEmpty()) {
            if ($bimbinganIfExists->isEmpty()) {
                $mahasiswa = Mahasiswa::findOrFail(1);
                $request->validate([
                    'lampiran' => ['required', 'mimes:pdf'],
                    'bagian_id' => 'required',
                ]);
                $bimbingan = new Bimbingan;
                $bimbingan->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-bimbingan');
                $bimbingan->keterangan = $request->keterangan;
                $bimbingan->bagian_id = $request->bagian_id;

                $mahasiswa->bimbingans()->save($bimbingan);
                return $mahasiswa->bimbingans;
            } else {
                return 'failed';
            }
        } else {
            return 'failed';
        }
    }

    public function edit(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        return $bimbingan;
    }

    public function update(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
            return 'failed';
        } else {
            $validatedData = $request->validate([
                'bagian_id' => 'required',
            ]);
            if ($request->file('lampiran')) {
                $this->deleteLampiran($bimbingan->lampiran);
                $validatedData['lampiran'] = $this->uploadLampiran($request->lampiran, 'lampiran-bimbingan');
            }
            $validatedData['keterangan'] = $request->keterangan;
            $validatedData['bagian_id'] = $request->bagian_id;
            $bimbingan->update($validatedData);
            return $bimbingan;
        }
    }

    public function delete(Request $request)
    {
    }

    public function accBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return 'failed';
        } else {
            $revisi = new RevisiBimbingan;
            $revisi->catatan = $request->catatan;
            $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
            $revisi->dosen_id = 1;
            $bimbingan->update([
                'status' => 'diterima',
                'tanggal_acc' => now(),
            ]);
            $bimbingan->revisis()->save($revisi);
            return $bimbingan->revisis;
        }
    }

    public function revisiBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return 'failed';
        } else {
            $revisi = new RevisiBimbingan;
            $revisi->catatan = $request->catatan;
            $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
            $revisi->dosen_id = 1;
            $bimbingan->update([
                'status' => 'revisi',
            ]);
            $bimbingan->revisis()->save($revisi);
            return $bimbingan->revisis;
        }
    }

    public function deleteRevisiBimbingan(Request $request)
    {
        $revisi = RevisiBimbingan::findOrFail($request->id);
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
