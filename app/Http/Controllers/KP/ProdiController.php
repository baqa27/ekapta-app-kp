<?php

namespace App\Http\Controllers\KP;

use App\Imports\ProdisImport;
use App\Models\KP\PresentaseNilai;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProdiController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $prodis = Prodi::orderBy('created_at', 'desc')->get();
        return view('kp.pages.admin.prodi.prodi', [
            'title' => 'Master Data Prodi',
            'active' => 'prodi',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'prodis' => $prodis,
        ]);
    }

    public function detail($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('kp.pages.admin.prodi.detail', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'prodi' => $prodi,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:prodis,kode',
            'namaprodi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:10',
            'password' => 'required|string|min:6',
        ]);

        Prodi::create([
            'kode' => $request->kode,
            'namaprodi' => $request->namaprodi,
            'jenjang' => $request->jenjang,
            'kodekaprodi' => $request->kodekaprodi,
            'password' => Hash::make($request->password),
            'fakultas_id' => $request->fakultas_id,
        ]);

        return redirect()->route('prodis')->with('success', 'Prodi berhasil ditambahkan');
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
        $presentase_nilai = $prodi->presentase_nilai_kp;

        return view('kp.pages.admin.prodi.presentase-nilai', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'prodi' => $prodi,
            'presentase_nilai' => $presentase_nilai ? $presentase_nilai : null,
        ]);
    }

    public function presentaseNilaiStore(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $presentase_nilai = $prodi->presentase_nilai_kp;

        // Validasi: Total 3 komponen harus = 100%
        $total = $request->bobot_instansi + $request->bobot_pembimbing + $request->bobot_penguji;
        if ($total != 100) {
            return back()->with('error', 'Total Persentase Nilai Harus 100% (saat ini: ' . $total . '%)');
        }

        if ($presentase_nilai) {
            // Update jika sudah ada
            $presentase_nilai->update([
                'bobot_instansi' => $request->bobot_instansi,
                'bobot_pembimbing' => $request->bobot_pembimbing,
                'bobot_penguji' => $request->bobot_penguji,
            ]);
        } else {
            // Create jika belum ada
            PresentaseNilai::create([
                'prodi_id' => $prodi->id,
                'bobot_instansi' => $request->bobot_instansi,
                'bobot_pembimbing' => $request->bobot_pembimbing,
                'bobot_penguji' => $request->bobot_penguji,
            ]);
        }

        return back()->with('success', 'Pengaturan Persentase Nilai KP Berhasil Disimpan');
    }

    function account(){
        $prodi = Auth::guard('prodi')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'prodi' => $prodi,
        ];

        return view('kp.pages.prodi.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $prodi = Auth::guard('prodi')->user();

        abort_unless($prodi && (int) $prodi->id === (int) $id, 403);

        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $prodi->forceFill([
            'password' => Hash::make($validatedData['password']),
        ])->save();

        Auth::guard('prodi')->setUser($prodi->fresh());

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

