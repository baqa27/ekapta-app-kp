<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginMahasiswa()
    {
        return view('pages.mahasiswa.login', [
            'title' => 'Login Mahasiswa',
        ]);
    }

    public function cekMahasiswa(Request $request)
    {
        dd($request->all());
    }
}
