<?php

namespace App\Http\Controllers;

use App\Imports\DosensImport;
use App\Models\Dosen;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{

    public function index()
    {
        $dosens = Dosen::all();
        return view('pages.admin.dosen.dosen', [
            'title' => 'Master Data Dosen',
            'active' => 'dosen',
            'sidebar' => 'partials.sidebarAdmin',
            'dosens' => $dosens,
        ]);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new DosensImport, $request->file('file'));
            return back()->with('success', 'Data Dosen berhasil di Import');
        } catch (Exception $e) {
            return back()->with('warning', 'Data Dosen gagal di Import');
        }
    }
}