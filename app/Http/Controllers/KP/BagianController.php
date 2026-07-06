<?php

namespace App\Http\Controllers\KP;

use App\Imports\BagiansImport;

// Import KP Models
use App\Models\KP\Bagian;
use App\Models\KP\Bimbingan;
use App\Models\KP\Pendaftaran;

// Import Shared Models
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BagianController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
        $prodi = Prodi::findOrFail($request->input('prodi_id'));
        $validatedData = $request->validate([
            'bagian' => 'required',
            'tahun_masuk' => 'required',
        ]);
        $bagian = new Bagian;
        $bagian->bagian = $validatedData['bagian'];
        $bagian->tahun_masuk = $validatedData['tahun_masuk'];
        $bagian->is_seminar = $request->input('is_seminar') ? 1 : 0;
        $bagian->is_pendadaran = $request->input('is_pendadaran') ? 1 : 0;
        $prodi->bagiansKP()->save($bagian);
        return back()->with('success', 'Bagian berhasil dibuat');
    }

    public function update(Request $request)
    {
        $bagian = Bagian::findOrFail($request->input('id'));

        $validatedData = $request->validate([
            'bagian' => 'required',
            'tahun_masuk' => 'required',
        ]);

        $bagian->update([
            'bagian' => $validatedData['bagian'],
            'tahun_masuk' => $validatedData['tahun_masuk'],
            'is_seminar' => $request->input('is_seminar') ? 1 : 0,
            'is_pendadaran' => $request->input('is_pendadaran') ? 1 : 0,
        ]);

        return back()->with('success', 'Bagian berhasil diedit');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        Log::info('Delete bagian KP called', ['id' => $id]);

        $bagian = Bagian::findOrFail($id);

        // Cek apakah bagian sudah dipakai di bimbingan
        if ($bagian->bimbingans()->count() > 0) {
            return back()->with('warning', 'Tidak dapat menghapus bagian yang sudah digunakan di bimbingan');
        }

        $bagian->delete();

        Log::info('Bagian KP deleted successfully', ['id' => $id]);
        return back()->with('success', 'Bagian berhasil dihapus');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new BagiansImport($request->input('prodi')), $request->file('file'));
            return back()->with('success', 'Bagian berhasil diimport');
        } catch (Exception $e) {
            return back()->with('warning', 'Bagian gagal diimport');
        }
    }

    public function bagianActive(Request $request)
    {
        return back();
        $bagian = Bagian::findOrFail($request->input('id'));
        $prodi = Prodi::findOrFail($request->input('prodi_id'));
        $pendaftaransAcc = Pendaftaran::with(['mahasiswa'])
            ->where('status', 'diterima')
            ->whereHas('mahasiswa', function ($query) use ($prodi) {
                $query->where(function($q) use ($prodi) {
                    $q->where('prodi_id', $prodi->id)
                      ->orWhere('prodi', $prodi->namaprodi)
                      ->orWhere('prodi', $prodi->kode);
                });
            })
            ->get();
        if (count($pendaftaransAcc) == 0 || !$prodi) {
            return back()->with('warning', 'Pendaftaran mahasiswa tidak ditemukan');
        }
        foreach ($pendaftaransAcc as $pendaftaran) {
            $mahasiswa = $pendaftaran->mahasiswa()
                ->where(function($query) use ($prodi) {
                    $query->where('prodi_id', $prodi->id)
                          ->orWhere('prodi', $prodi->namaprodi)
                          ->orWhere('prodi', $prodi->kode);
                })
                ->first();
            if ($mahasiswa) {
                if ($mahasiswa->thmasuk == $bagian->tahun_masuk) {
                    foreach ($mahasiswa->dosens as $dosen) {
                        if ($dosen->pivot->status == 'utama') {
                            $bimbingan = Bimbingan::create([
                                'mahasiswa_id' => $mahasiswa->id,
                                'bagian_id' => $request->input('id'),
                                'pembimbing' => 'utama',
                            ]);
                            $bimbingan->dosens()->attach([$dosen->id]);
                        } else if ($dosen->pivot->status == 'pendamping') {
                            $bimbingan = Bimbingan::create([
                                'mahasiswa_id' => $mahasiswa->id,
                                'bagian_id' => $request->input('id'),
                                'pembimbing' => 'pendamping',
                            ]);
                            $bimbingan->dosens()->attach([$dosen->id]);
                        }
                    }
                }
            }
        }

        return back()->with('success', 'Bagian Bimbingan berhasil diaktifkan');
    }

    public function up($id)
    {
        $bagian = Bagian::with(['prodi'])->where('id', $id)->first();
        return $prodi = $bagian->prodi()->with(['bagians'])->get();
    }

    public function down($id)
    {
        $bagian = Bagian::with(['prodi'])->where('id', $id)->first();
        return $prodi = $bagian->prodi()->with(['bagians'])->get();
    }
}

