<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaDetailsImport;
use App\Imports\MahasiswasImport;
use App\Models\Mahasiswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{

    public function index()
    {
        $mahasiswas = Mahasiswa::all();
        return view('pages.admin.mahasiswa.mahasiswa', [
            'title' => 'Master Data Mahasiswa',
            'active' => 'mahasiswa',
            'sidebar' => 'partials.sidebarAdmin',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function profile()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        return view('pages.mahasiswa.profile', [
            'title' => 'Profil Mahasiswa',
            'active' => 'profile',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail($request->id);
        $validatedData = $request->validate([
            'email' => ['required', 'email:dns'],
            'hp' => 'required',
            'alamat' => 'required',
        ]);
        $mahasiswa->update($validatedData);
        return back()->with('success', 'Profil berhasil diupdate');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new MahasiswasImport, $request->file('file'));
            return back()->with('success', 'Data Mahasiswa berhasil di Import');
        } catch (Exception $e) {
            return back()->with('warning', 'Data Mahasiswa gagal di Import');
        }
    }

    public function importDetail(Request $request)
    {
        try {
            Excel::import(new MahasiswaDetailsImport, $request->file('file'));
            return back()->with('success', 'Data semester dan status mahasiswa berhasil di import');
        } catch (Exception $e) {
            return back()->with('warning', 'Data semester dan status mahasiswa gagal di import');
        }
    }

    function account(){
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => 'profile',
            'mahasiswa' => $mahasiswa,
        ];

        return view('pages.mahasiswa.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $mahasiwa = Mahasiswa::findOrFail($id);

        $request->validate([
           'password' => ['required','string' ,'min:6'],
        ]);

        $mahasiwa->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
