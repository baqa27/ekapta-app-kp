<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\RevisiPendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::where('nim', 2020150031)->with(['revisis'])->get(); //Get data pendaftaran mahasiswa
        // $pendaftarans = Pendaftaran::with(['revisis'])->get(); //Get all data pendaftaran mahasiswa
        return $pendaftarans;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $cekPendaftaran = Pendaftaran::where('nim', $request->nim)->get();
        if ($cekPendaftaran->isEmpty()) {
            $validatedData = $request->validate([
                'nim' => 'required',
                'judul' => 'required',
                'email' => 'required',
                'hp' => 'required',
                'semester' => 'required',
                'nomor_pembayaran' => 'required',
                // 'tanggal_pembayaran' => 'required',
                'biaya' => 'required',
                'lampiran_1' => ['required', 'mimes:pdf'],
                'lampiran_2' => ['required', 'mimes:pdf'],
                'lampiran_3' => ['required', 'mimes:pdf'],
                'lampiran_4' => ['required', 'mimes:pdf'],
                'lampiran_5' => ['required', 'mimes:pdf'],
            ]);

            $validatedData['lampiran_1'] = $this->uploadLampiran($request->file('lampiran_1'), 'lampiran-pendaftaran');
            $validatedData['lampiran_2'] = $this->uploadLampiran($request->file('lampiran_2'), 'lampiran-pendaftaran');
            $validatedData['lampiran_3'] = $this->uploadLampiran($request->file('lampiran_3'), 'lampiran-pendaftaran');
            $validatedData['lampiran_4'] = $this->uploadLampiran($request->file('lampiran_4'), 'lampiran-pendaftaran');
            $validatedData['lampiran_5'] = $this->uploadLampiran($request->file('lampiran_5'), 'lampiran-pendaftaran');


            $validatedData['tanggal_pembayaran'] = now();

            Pendaftaran::create($validatedData);
            return $validatedData;
        } else {
            return 'sudah melakukan pendaftaran';
        }
    }

    public function edit(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        return $pendaftaran;
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
            // 'tanggal_pembayaran' => 'required',
            'biaya' => 'required',
        ]);

        if ($request->file('lampiran_1') || $request->file('lampiran_2') || $request->file('lampiran_3') || $request->file('lampiran_4') || $request->file('lampiran_5')) {
            $this->deleteLampiran($pendaftaran->lampiran_1);
            $this->deleteLampiran($pendaftaran->lampiran_2);
            $this->deleteLampiran($pendaftaran->lampiran_3);
            $this->deleteLampiran($pendaftaran->lampiran_4);
            $this->deleteLampiran($pendaftaran->lampiran_5);
            $validatedData['lampiran_1'] = $this->uploadLampiran($request->lampiran_1, 'lampiran-pendaftaran');
            $validatedData['lampiran_2'] = $this->uploadLampiran($request->lampiran_2, 'lampiran-pendaftaran');
            $validatedData['lampiran_3'] = $this->uploadLampiran($request->lampiran_3, 'lampiran-pendaftaran');
            $validatedData['lampiran_4'] = $this->uploadLampiran($request->lampiran_4, 'lampiran-pendaftaran');
            $validatedData['lampiran_5'] = $this->uploadLampiran($request->lampiran_5, 'lampiran-pendaftaran');
        }

        $validatedData['tanggal_pembayaran'] = now();

        $pendaftaran->update($validatedData);
        return $validatedData;
    }

    public function delete(Request $request)
    {
        $pendaftaran = Pendaftaran::findOrFail($request->id);
        if ($pendaftaran->status == 'diterima') {
            return 'failed';
        } else {
            $this->deleteLampiran($pendaftaran->lampiran_2);
            $this->deleteLampiran($pendaftaran->lampiran_3);
            $this->deleteLampiran($pendaftaran->lampiran_4);
            $this->deleteLampiran($pendaftaran->lampiran_5);
            $pendaftaran->delete();
            return 'berhasil dihapus';
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
                'lampiran_acc' => $this->uploadLampiran($request->lampiran_acc, 'lampiran-pendaftaran'),
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
            $this->deleteLampiran($pendaftaran->lampiran_acc);
            $pendaftaran->update([
                'lampiran_acc' => $this->uploadLampiran($request->lampiran_acc, 'lampiran-pendaftaran'),
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
            $revisi->lampiran = $this->uploadLampiran($request->lampiran, 'lampiran-revisi');
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
        $this->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return 'success';
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
