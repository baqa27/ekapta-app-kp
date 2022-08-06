<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Login User
Route::get('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa');

// Pengajuan
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
