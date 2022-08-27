<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{

    public function profile()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        return view('pages.mahasiswa.profile', [
            'title' => 'Profil Mahasiswa',
            'active' => '',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail($request->id);
        $validatedData = $request->validate([
            'email' => ['required', 'email:dns'],
            'hp' => 'required',
            // 'semester' => 'required',
            'alamat' => 'required',
        ]);
        $mahasiswa->update($validatedData);
        return back()->with('success', 'Profil berhasil diupdate');
    }
}
