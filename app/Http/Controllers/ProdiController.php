<?php

namespace App\Http\Controllers;

use App\Imports\ProdisImport;
use App\Models\PresentaseNilai;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProdiController extends Controller
{

    public function index()
    {
        $prodis = Prodi::orderBy('created_at', 'desc')->get();
        return view('pages.admin.prodi.prodi', [
            'title' => 'Master Data Prodi',
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodis' => $prodis,
        ]);
    }

    public function detail($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('pages.admin.prodi.detail', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodi' => $prodi,
        ]);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new ProdisImport, $request->file('file'));
            return back()->with('success', 'Data Prodi behasil di Import');
        } catch (\Throwable $e) {
            return back()->with('warning', 'Data prodi gagal diimport');
        }
    }

    public function presentaseNilai($prodi_id)
    {
        $prodi = Prodi::findOrFail($prodi_id);
        $presentase_nilai = $prodi->presentase_nilai;

        return view('pages.admin.prodi.presentase-nilai', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodi' => $prodi,
            'presentase_nilai' => $presentase_nilai ? $presentase_nilai : null,
        ]);
    }

    public function presentaseNilaiStore(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $presentase_nilai = $prodi->presentase_nilai;

        if ($request->presentase_1 + $request->presentase_2 + $request->presentase_3 + $request->presentase_4 != 100 || $request->bobot_penguji + $request->bobot_pembimbing != 100) {
            return back()->with('error', 'Total Presentase Nilai Harus 100%');
        }

        if ($presentase_nilai) {
            $presentase_nilai->update([
                'presentase_1' => $request->presentase_1,
                'presentase_2' => $request->presentase_2,
                'presentase_3' => $request->presentase_3,
                'presentase_4' => $request->presentase_4,
                'bobot_penguji' => $request->bobot_penguji,
                'bobot_pembimbing' => $request->bobot_pembimbing,
            ]);
        }

        PresentaseNilai::create([
            'prodi_id' => $prodi->id,
            'presentase_1' => $request->presentase_1,
            'presentase_2' => $request->presentase_2,
            'presentase_3' => $request->presentase_3,
            'presentase_4' => $request->presentase_4,
            'bobot_penguji' => $request->bobot_penguji,
            'bobot_pembimbing' => $request->bobot_pembimbing,
        ]);

        return back()->with('success', 'Presentase Nilai Berhasil Disimpan');
    }
}
