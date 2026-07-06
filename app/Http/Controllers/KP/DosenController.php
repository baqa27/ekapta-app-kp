<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Imports\DosensImport;
use App\Models\Dosen;
use App\Models\Prodi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $dosens = Dosen::all();
        return view('kp.pages.admin.dosen.dosen', [
            'title' => 'Master Data Dosen',
            'active' => 'dosen',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required|string|max:20|unique:dosens,nidn',
            'nama' => 'required|string|max:255',
        ]);

        $password = $request->password ?: $request->nidn;

        Dosen::create([
            'nidn' => $request->nidn,
            'nama' => $request->nama,
            'gelar' => $request->gelar,
            'kodeprodi' => $request->kodeprodi ?: '-',
            'nik' => $request->nik ?: '-',
            'email' => $request->email,
            'hp' => $request->hp,
            'alamat' => '-',
            'tptlahir' => '-',
            'tgllahir' => now()->format('Y-m-d'),
            'password' => Hash::make($password),
        ]);

        return redirect()->route('dosens')->with('success', 'Dosen berhasil ditambahkan');
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

        $dosen_prodi_id = [];
        foreach ($dosen->prodis as $prodi){
            $dosen_prodi_id[] = $prodi->id;
        }

        if (count($dosen->prodis) == 0){
            $prodis = Prodi::all();
        }else{
            $prodis = Prodi::whereNotIn('id', $dosen_prodi_id)->get();
        }

        return view('kp.pages.admin.dosen.setting',[
            'title' => 'Setting Dosen',
            'active' => 'dosen',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'dosen' => $dosen,
            'prodis' => $prodis,
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

    public function account(){
        $dosen = Auth::guard('dosen')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'dosen' => $dosen,
        ];

        return view('kp.pages.dosen.account', $data);
    }

    public function accountUpdate(Request $request, $id){
        $dosen = Dosen::findOrFail($id);

        if (Auth::guard('dosen')->user()->id != $dosen->id) {
            abort(403);
        }

        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('dosens', 'email')->ignore($dosen->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'ttd' => ['nullable', 'mimes:png,jpg,jpeg', 'max:1024'],
        ]);

        $updateData = [
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'] ?: '-',
        ];

        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $oldTtd = null;
        if ($request->file('ttd')) {
            $newTtd = AppHelper::instance()->uploadLampiran($request->file('ttd'), 'images');
            if ($newTtd) {
                $oldTtd = $dosen->ttd;
                $updateData['ttd'] = $newTtd;
            }
        }

        $dosen->update($updateData);

        if ($oldTtd && !empty($updateData['ttd']) && $oldTtd !== $updateData['ttd']) {
            AppHelper::instance()->deleteLampiran($oldTtd);
        }

        return back()->with('success', 'Akun dosen berhasil diperbarui');
    }

    public function resetPassword($id){
        $dosen = Dosen::findOrFail($id);
        $dosen->update([
            'password' => Hash::make($dosen->nidn)
        ]);
        return back()->with('success', 'Password berhasil direset');
    }

    public function changeManual($id){
        $dosen = Dosen::findOrFail($id);
        $isManual = $dosen->is_manual == 1 ? 0 : 1;
        $dosen->update([
            'is_manual' => $isManual,
        ]);
        return back()->with('success', 'Password berhasil di' . ($isManual == 1 ? 'enable' : 'disable'));
    }

}

