<?php

use App\Http\Controllers\BagianController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PlotingController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Login User
Route::get('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa');
Route::get('/login/prodi', [LoginController::class, 'loginProdi'])->name('login.prodi');
Route::post('/login/prodi', [LoginController::class, 'cekProdi'])->name('cek.prodi');
Route::get('/login/dosen', [LoginController::class, 'loginDosen'])->name('login.dosen');
Route::post('/login/dosen', [LoginController::class, 'cekDosen'])->name('cek.dosen');
Route::get('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin');
Route::post('/login/admin', [LoginController::class, 'cekAdmin'])->name('cek.admin');

// Dashboard
Route::get('/dashboard-mahasiswa', [DashboardController::class, 'dashboardMahasiswa'])->name('dashboard.mahasiswa');
Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi');
Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen');
Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin');

// Pengajuan TA
Route::get('/pengajuan-admin', [PengajuanController::class, 'pengajuanAdmin'])->name('pengajuan.admin');
Route::get('/pengajuan-prodi', [PengajuanController::class, 'pengajuanProdi'])->name('pengajuan.prodi');
Route::get('/pengajuan-mahasiswa', [PengajuanController::class, 'pengajuanMahasiswa'])->name('pengajuan.mahasiswa');
Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit']);
Route::post('/pengajuan/update', [PengajuanController::class, 'update'])->name('pengajuan.update');
Route::post('/pengajuan/delete', [PengajuanController::class, 'delete'])->name('pengajuan.delete');
Route::post('/pengajuan/acc', [PengajuanController::class, 'accPengajuan'])->name('pengajuan.acc');
Route::post('/pengajuan/revisi', [PengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi');
Route::post('/pengajuan/revisi/delete', [PengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete');
Route::post('/pengajuan/tolak', [PengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak');
Route::get('/pengajuan/detail/{id}', [PengajuanController::class, 'pengajuanDetail']);
Route::get('/pengajuan/review/{id}', [PengajuanController::class, 'pengajuanReview']);
Route::get('/pengajuan/review-admin/{id}', [PengajuanController::class, 'pengajuanReviewAdmin']);

// Ploting pembimbing
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji');

// Pendaftaran TA
Route::get('/pendaftarans', [PendaftaranController::class, 'index'])->name('pendaftarans');
Route::get('/pendaftaran-mahasiswa', [PendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::get('/pendaftaran/detail/{id}', [PendaftaranController::class, 'pendaftaranDetail']);
Route::get('/pendaftaran/review/{id}', [PendaftaranController::class, 'pendaftaranReview']);
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/edit/{id}', [PendaftaranController::class, 'edit']);
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete');
Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc');
Route::post('/pendaftaran/acc/update', [PendaftaranController::class, 'accPendaftaranUpdate'])->name('pendaftaran.acc.update');
Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi');
Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete');

// Bagian-bagian bimbingan
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete');

// Bimbingan TA
Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen');
Route::get('/bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa');
Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create');
Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store');
Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit']);
Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update');
Route::post('/bimbingan/delete', [BimbinganController::class, 'delete'])->name('bimbingan.delete');
Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc');
Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store');
Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete');
Route::get('/bimbingan/detail/{id}', [BimbinganController::class, 'bimbinganDetail']);
Route::get('/bimbingan/review/{id}', [BimbinganController::class, 'bimbinganReview']);

// Prodi
Route::get('/prodis', [ProdiController::class, 'index'])->name('prodis');
Route::get('/prodi/{id}', [ProdiController::class, 'detail']);
