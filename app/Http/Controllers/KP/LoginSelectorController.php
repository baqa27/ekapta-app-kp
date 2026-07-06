<?php

namespace App\Http\Controllers\KP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controller untuk halaman pemilihan role login
 * Halaman ini menampilkan pilihan role: Mahasiswa, Dosen, Prodi, Admin, Himpunan
 * 
 * CATATAN INTEGRASI:
 * - Controller ini HANYA untuk KP
 * - View menggunakan resources/views/kp/auth/role-selector.blade.php
 * - Tidak mengubah controller TA sama sekali
 */
class LoginSelectorController extends Controller
{
    /**
     * Tampilkan halaman pemilihan role login
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('kp.auth.role-selector', [
            'title' => 'Login - EKAPTA'
        ]);
    }
}
