<?php

namespace App\Http\Controllers;

use App\Imports\ProdisImport;
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
            'title' => 'Prodi : ' . $prodi->namaprodi,
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
}