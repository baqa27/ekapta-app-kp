<?php

use App\Http\Controllers\BagianController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PlotingController;
use App\Http\Controllers\ProdiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Login User
Route::get('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa')->middleware('isMahasiswaLogin');
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa')->middleware('isMahasiswaLogin');;
Route::get('/login/prodi', [LoginController::class, 'loginProdi'])->name('login.prodi')->middleware('isProdiLogin');
Route::post('/login/prodi', [LoginController::class, 'cekProdi'])->name('cek.prodi')->middleware('isProdiLogin');
Route::get('/login/dosen', [LoginController::class, 'loginDosen'])->name('login.dosen')->middleware('isDosenLogin');
Route::post('/login/dosen', [LoginController::class, 'cekDosen'])->name('cek.dosen')->middleware('isDosenLogin');
Route::get('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin')->middleware('isAdminLogin');
Route::post('/login/admin', [LoginController::class, 'cekAdmin'])->name('cek.admin')->middleware('isAdminLogin');

// Dashboard
Route::get('/dashboard-mahasiswa', [DashboardController::class, 'dashboardMahasiswa'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');
Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');

// Pengajuan TA
Route::get('/pengajuan-admin', [PengajuanController::class, 'pengajuanAdmin'])->name('pengajuan.admin')->middleware('isAdmin');
Route::get('/pengajuan-prodi', [PengajuanController::class, 'pengajuanProdi'])->name('pengajuan.prodi')->middleware('isProdi');
Route::get('/pengajuan-mahasiswa', [PengajuanController::class, 'pengajuanMahasiswa'])->name('pengajuan.mahasiswa')->middleware('isMahasiswa');
Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create')->middleware('isMahasiswa');
Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store')->middleware('isMahasiswa');
Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/pengajuan/update', [PengajuanController::class, 'update'])->name('pengajuan.update')->middleware('isMahasiswa');
Route::post('/pengajuan/delete', [PengajuanController::class, 'delete'])->name('pengajuan.delete')->middleware('isMahasiswa');
Route::post('/pengajuan/acc', [PengajuanController::class, 'accPengajuan'])->name('pengajuan.acc')->middleware('isProdi');
Route::post('/pengajuan/revisi', [PengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi')->middleware('isProdi');
Route::post('/pengajuan/revisi/delete', [PengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete')->middleware('isProdi');
Route::post('/pengajuan/tolak', [PengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak')->middleware('isProdi');
Route::get('/pengajuan/detail/{id}', [PengajuanController::class, 'pengajuanDetail'])->middleware('isMahasiswa');
Route::get('/pengajuan/review/{id}', [PengajuanController::class, 'pengajuanReview'])->middleware('isProdi');
Route::get('/pengajuan/review-admin/{id}', [PengajuanController::class, 'pengajuanReviewAdmin'])->middleware('isAdmin');

// Ploting pembimbing
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isProdi');

// Pendaftaran TA
Route::get('/pendaftarans', [PendaftaranController::class, 'index'])->name('pendaftarans')->middleware('isAdmin');
Route::get('/pendaftaran-mahasiswa', [PendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('isMahasiswa');
Route::get('/pendaftaran/detail/{id}', [PendaftaranController::class, 'pendaftaranDetail'])->middleware('isMahasiswa');
Route::get('/pendaftaran/review/{id}', [PendaftaranController::class, 'pendaftaranReview'])->middleware('isAdmin');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('isMahasiswa');
Route::get('/pendaftaran/edit/{id}', [PendaftaranController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update')->middleware('isMahasiswa');
Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete')->middleware('isMahasiswa');
Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc')->middleware('isAdmin');
Route::post('/pendaftaran/acc/update', [PendaftaranController::class, 'accPendaftaranUpdate'])->name('pendaftaran.acc.update')->middleware('isMahasiswa');
Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi')->middleware('isAdmin');
Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete')->middleware('isAdmin');

// Bagian-bagian bimbingan
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');

// Bimbingan TA
Route::get('/bimbingan-prodi', [BimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
Route::get('/bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create')->middleware('isMahasiswa');
Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store')->middleware('isMahasiswa');
Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update')->middleware('isMahasiswa');
Route::post('/bimbingan/delete', [BimbinganController::class, 'delete'])->name('bimbingan.delete')->middleware('isMahasiswa');
Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc')->middleware('isDosen');
Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store')->middleware('isDosen');
Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete')->middleware('isDosen');
Route::get('/bimbingan/detail/{id}', [BimbinganController::class, 'bimbinganDetail'])->middleware('isMahasiswa');
Route::get('/bimbingan/review/{id}', [BimbinganController::class, 'bimbinganReview'])->middleware('isDosen');

// Prodi
Route::get('/prodis', [ProdiController::class, 'index'])->name('prodis')->middleware('isAdmin');
Route::get('/prodi/{id}', [ProdiController::class, 'detail'])->middleware('isAdmin');

// Mahasiswa
Route::get('profile', [MahasiswaController::class, 'profile'])->name('profile');
Route::post('profile/update', [MahasiswaController::class, 'update'])->name('profile.update');

// Logout
Route::get('/logout/mahasiswa', function (Request $request) {
    if (Auth::guard('mahasiswa')->check()) {
        Auth::guard('mahasiswa')->logout();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login.mahasiswa');
    }
})->name('logout.mahasiswa');

Route::get('/logout/dosen', function (Request $request) {
    if (Auth::guard('dosen')->check()) {
        Auth::guard('dosen')->logout();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login.dosen');
    }
})->name('logout.dosen');

Route::get('/logout/prodi', function (Request $request) {
    if (Auth::guard('prodi')->check()) {
        Auth::guard('prodi')->logout();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login.prodi');
    }
})->name('logout.prodi');

Route::get('/logout/admin', function (Request $request) {
    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login.admin');
    }
})->name('logout.admin');

// Back Dashboard
Route::get('/back/dashboard', function () {
    if (Auth::guard('mahasiswa')->check()) {
        return redirect('dashboard-mahasiswa');
    } elseif (Auth::guard('dosen')->check()) {
        return redirect('dashboard-dosen');
    } elseif (Auth::guard('prodi')->check()) {
        return redirect('dashboard-prodi');
    } elseif (Auth::guard('admin')->check()) {
        return redirect('dashboard-admin');
    } else {
        return redirect('login');
    }
})->name('back.dashboard');
