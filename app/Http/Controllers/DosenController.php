<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Imports\DosensImport;
use App\Models\Dosen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);

        return view('pages.admin.dosen.setting',[
            'title' => 'Setting Dosen',
            'active' => 'dosen',
            'sidebar' => 'partials.sidebarAdmin',
            'dosen' => $dosen,
        ]);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validatedData = $request->validate([
            'ttd' => [Rule::requiredIf(function () {
                if (empty($this->request->image)) {
                    return false;
                }
                return true;
            }), 'mimes:png,jpg,jpeg', 'max:300']
        ]);

        $dosen->update([
            'ttd' => AppHelper::instance()->uploadLampiran($request->ttd,'images'),
        ]);

        return back()->with('success','TTD Dosen berhasil di update.');
    }
}
