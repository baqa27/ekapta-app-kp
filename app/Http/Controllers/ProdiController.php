<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

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
}
