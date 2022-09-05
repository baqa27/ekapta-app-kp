<?php

namespace App\Http\Controllers;

use App\Imports\BagiansImport;
use App\Models\Bagian;
use App\Models\Prodi;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BagianController extends Controller
{
    public function store(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $cekBagian = $prodi->bagians()->where('bagian', $request->bagian)->first();
        if ($cekBagian) {
            return back()->with('warning', 'Nama bagian yang sama sudah dibuat');
        }
        $validatedData = $request->validate([
            'bagian' => 'required',
        ]);
        $bagian = new Bagian;
        $bagian->bagian = $validatedData['bagian'];
        $prodi->bagians()->save($bagian);
        return back()->with('success', 'Bagian berhasil dibuat');
    }

    public function update(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);
        if (Bagian::where(['id' => $request->id, 'bagian' => $request->bagian])->first()) {
            return back()->with('warning', 'Nama bagian yang sama sudah dibuat');
        }
        $validatedData = $request->validate([
            'bagian' => 'required',
        ]);
        $bagian->update($validatedData);
        return back()->with('success', 'Bagian berhasil diedit');
    }

    public function delete(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);
        if (count($bagian->bimbingans) != 0) {
            return back()->with('warning', 'Tidak dapat menghapus bagian bimbingan');
        }
        $bagian->delete();
        return back()->with('success', 'Bagian berhasil dihapus');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new BagiansImport($request->prodi), $request->file('file'));
            return back()->with('success', 'Bagian berhasil diimport');
        } catch (Exception $e) {
            return back()->with('warning', 'Bagian gagal diimport');
        }
    }
}