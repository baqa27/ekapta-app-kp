<?php

namespace App\Http\Controllers;

use App\Imports\ProdisImport;
use App\Models\PresentaseNilai;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        $validated = $request->validate([
            'presentase_1' => 'required|integer|min:0|max:100',
            'presentase_2' => 'required|integer|min:0|max:100',
            'presentase_3' => 'required|integer|min:0|max:100',
            'presentase_4' => 'required|integer|min:0|max:100',
            'presentase_5' => 'nullable|integer|min:0|max:100',
            'bobot_penguji' => 'required|integer|min:0|max:100',
            'bobot_pembimbing' => 'required|integer|min:0|max:100',
        ]);

        $total_presentase = $validated['presentase_1'] + $validated['presentase_2'] + $validated['presentase_3'] + $validated['presentase_4'] + ($validated['presentase_5'] ?? 0);

        if ($total_presentase != 100 || $validated['bobot_penguji'] + $validated['bobot_pembimbing'] != 100) {
            return back()->with('error', 'Total Presentase Nilai Harus 100%');
        }

        if ($presentase_nilai) {
            $presentase_nilai->update([
                'presentase_1' => $validated['presentase_1'],
                'presentase_2' => $validated['presentase_2'],
                'presentase_3' => $validated['presentase_3'],
                'presentase_4' => $validated['presentase_4'],
                'presentase_5' => $validated['presentase_5'] ?? 0,
                'bobot_penguji' => $validated['bobot_penguji'],
                'bobot_pembimbing' => $validated['bobot_pembimbing'],
            ]);
        }

        PresentaseNilai::create([
            'prodi_id' => $prodi->id,
            'presentase_1' => $validated['presentase_1'],
            'presentase_2' => $validated['presentase_2'],
            'presentase_3' => $validated['presentase_3'],
            'presentase_4' => $validated['presentase_4'],
            'presentase_5' => $validated['presentase_5'] ?? 0,
            'bobot_penguji' => $validated['bobot_penguji'],
            'bobot_pembimbing' => $validated['bobot_pembimbing'],
        ]);

        return back()->with('success', 'Presentase Nilai Berhasil Disimpan');
    }

    function account(){
        $prodi = Auth::guard('prodi')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'partials.sidebarProdi',
            'prodi' => $prodi,
        ];

        return view('pages.prodi.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'password' => ['required','string' ,'min:6'],
        ]);

        $prodi->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    function resetPassword($id){
        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'password' => Hash::make($prodi->kode)
        ]);
        return back()->with('success', 'Password berhasil direset');
    }
}
