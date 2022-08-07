<?php

use App\Http\Controllers\BagianController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PlotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Login User
Route::get('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa');

// Pengajuan TA
Route::get('/pengajuans', [PengajuanController::class, 'index'])->name('pengajuans');
Route::post('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store');
Route::post('/pengajuan/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
Route::post('/pengajuan/update', [PengajuanController::class, 'update'])->name('pengajuan.update');
Route::post('/pengajuan/delete', [PengajuanController::class, 'delete'])->name('pengajuan.delete');
Route::post('/pengajuan/acc', [PengajuanController::class, 'accPengajuan'])->name('pengajuan.acc');
Route::post('/pengajuan/revisi', [PengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi');
Route::post('/pengajuan/tolak', [PengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak');
Route::post('/pengajuan/revisi/delete', [PengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete');

// Ploting pembimbing
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji');

// Pendaftaran TA
Route::get('/pendaftarans', [PendaftaranController::class, 'index'])->name('pendaftarans');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::post('/pendaftaran/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete');
Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc');
Route::post('/pendaftaran/acc/update', [PendaftaranController::class, 'accPendaftaranUpdate'])->name('pendaftaran.acc.update');
Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi');
Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete');

// Bagian-bagian bimbingan
Route::get('/bagians', [BagianController::class, 'index'])->name('bagians');
Route::get('/bagian/create', [BagianController::class, 'create'])->name('bagian.create');
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store');
Route::post('/bagian/edit', [BagianController::class, 'edit'])->name('bagians.edit');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagians.delete');

// Bimbingan TA
Route::get('/bimbingans', [BimbinganController::class, 'index'])->name('bimbingans');
Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create');
Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store');
Route::post('/bimbingan/edit', [BimbinganController::class, 'edit'])->name('bimbingan.edit');
Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update');
Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc');
Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store');
Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete');
