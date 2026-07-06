<?php

namespace App\Http\Controllers\KP;

use App\Models\Admin;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends \App\Http\Controllers\Controller
{

    public function loginMahasiswa()
    {
        return view('kp.pages.mahasiswa.login', [
            'title' => 'Login Mahasiswa',
        ]);
    }

    public function cekMahasiswa(Request $request)
    {
        $credentials = $request->validate([
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $nim = trim($credentials['nim']);
        $password = $credentials['password'];
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (! $mahasiswa || ! $this->passwordMatches($mahasiswa, $password)) {
            return back()
                ->withInput($request->only('nim'))
                ->with('error', 'NIM atau password salah');
        }

        Auth::guard('mahasiswa')->login($mahasiswa);
        $request->session()->regenerate();

        return redirect()->intended('dashboard-mahasiswa');
    }

    public function loginProdi()
    {
        return view('kp.pages.prodi.login', [
            'title' => 'Login Prodi',
        ]);
    }

    public function cekProdi(Request $request)
    {
        $credentials = $request->validate([
            'kode' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('prodi')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-prodi');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginAdmin()
    {
        return view('kp.pages.admin.login', [
            'title' => 'Login Admin',
        ]);
    }

    public function cekAdmin(Request $request)
    {
        $request->validate([
            'kode' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt(['kode' => $request->kode, 'password' => $request->password, 'type' => Admin::TYPE_SUPER_ADMIN])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-admin');
        } elseif (Auth::guard('admin')->attempt(['kode' => $request->kode, 'password' => $request->password, 'type' => Admin::TYPE_ADMIN_FOTOCOPY])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-fotokopi');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginDosen()
    {
        return view('kp.pages.dosen.login', [
            'title' => 'Login Dosen',
        ]);
    }

    public function cekDosen(Request $request)
    {
        $credentials = $request->validate([
            'nidn' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('dosen')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-dosen');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginHimpunan()
    {
        return view('kp.pages.himpunan.login', [
            'title' => 'Login Himpunan',
        ]);
    }

    public function cekHimpunan(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('himpunan')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('kp.dashboard.himpunan');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function logoutMahasiswa()
    {
        Auth::guard('mahasiswa')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.mahasiswa');
    }

    public function logoutProdi()
    {
        Auth::guard('prodi')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.prodi');
    }

    public function logoutAdmin()
    {
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.admin');
    }

    public function logoutDosen()
    {
        Auth::guard('dosen')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.dosen');
    }

    public function logoutHimpunan()
    {
        if (Auth::guard('himpunan')->check()) {
            Auth::guard('himpunan')->logout();
        }

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('kp.login.himpunan');
    }

    private function passwordMatches(Mahasiswa $mahasiswa, string $plainPassword): bool
    {
        $storedPassword = (string) $mahasiswa->password;

        try {
            if ($storedPassword !== '' && Hash::check($plainPassword, $storedPassword)) {
                if (Hash::needsRehash($storedPassword)) {
                    $mahasiswa->forceFill([
                        'password' => Hash::make($plainPassword),
                    ])->save();
                }

                return true;
            }
        } catch (\Throwable $exception) {
            // Lanjut ke fallback data lama jika password di database belum berupa hash valid.
        }

        if (! hash_equals($storedPassword, $plainPassword)) {
            return false;
        }

        $mahasiswa->forceFill([
            'password' => Hash::make($plainPassword),
        ])->save();

        return true;
    }
}

