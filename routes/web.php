<?php

/*
|--------------------------------------------------------------------------
| EKAPTA - ROUTING INTEGRASI TUGAS AKHIR & KERJA PRAKTEK
|--------------------------------------------------------------------------
|
| File ini berisi routing untuk sistem EKAPTA yang mengintegrasikan:
| - Tugas Akhir (TA) - Sistem lama yang sudah LIVE di hosting produksi
| - Kerja Praktek (KP) - Sistem baru yang diintegrasikan ke dalam TA
|
| ⚠️ PENTING UNTUK DEPLOYMENT KE HOSTING:
|
| 1. Jika routing TA di hosting lebih update dari file ini:
|    - HANYA ganti bagian "ROUTING TA" (baris 100-470)
|    - JANGAN hapus bagian "ROUTING KP" (baris 480-akhir)
|    - JANGAN hapus use statements controller KP (baris 60-80)
|
| 2. Cara aman update routing TA dari hosting:
|    a. Backup file web.php yang ada
|    b. Copy routing TA baru dari hosting
|    c. Paste HANYA di bagian "ROUTING TA"
|    d. Pastikan route redirect tetap ada (ditandai ✅ REDIRECT)
|
| 3. Route yang WAJIB DIPERTAHANKAN (✅ REDIRECT):
|    - /login → LoginSelectorController (KP)
|    - /dashboard-mahasiswa → redirect ke /kp/pilih-sistem
|
| STRUKTUR FILE:
| 1. Use Statements Controller TA (tanpa alias)
| 2. Use Statements Controller KP (dengan alias KP*)
| 3. Routing TA (tanpa prefix, route asli sistem lama)
| 4. Routing KP (prefix /kp, route sistem baru)
|
| Lihat file ROUTING_GUIDE.md untuk panduan lengkap deployment.
|
*/

// ============================================================================
// USE STATEMENTS - CONTROLLER TUGAS AKHIR (TA)
// ============================================================================
// Controller sistem TA yang sudah ada, digunakan tanpa alias
// Contoh: PengajuanController, BimbinganController, SeminarController
//
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PlotingController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ReviewSeminarController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\ReviewUjianController;
use App\Http\Controllers\DosenProdiController;
use App\Http\Controllers\JilidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// ============================================================================
// USE STATEMENTS - CONTROLLER KERJA PRAKTEK (KP)
// ============================================================================
// Controller sistem KP yang baru, digunakan dengan alias KP* untuk membedakan
// Contoh: KPPengajuanController, KPBimbinganController, KPSeminarController
//
// ⚠️ JANGAN HAPUS use statements ini saat update routing TA dari hosting!
//
use App\Http\Controllers\KP\AdminController as KPAdminController;
use App\Http\Controllers\KP\BagianController as KPBagianController;
use App\Http\Controllers\KP\BimbinganController as KPBimbinganController;
use App\Http\Controllers\KP\BimbinganManualController;
use App\Http\Controllers\KP\CetakController as KPCetakController;
use App\Http\Controllers\KP\DashboardController as KPDashboardController;
use App\Http\Controllers\KP\DosenController as KPDosenController;
use App\Http\Controllers\KP\FakultasController as KPFakultasController;
use App\Http\Controllers\KP\HimpunanController;
use App\Http\Controllers\KP\HimpunanMasterController;
use App\Http\Controllers\KP\LoginController as KPLoginController;
use App\Http\Controllers\KP\LoginSelectorController;
use App\Http\Controllers\KP\MahasiswaController as KPMahasiswaController;
use App\Http\Controllers\KP\PendaftaranController as KPPendaftaranController;
use App\Http\Controllers\KP\PengajuanController as KPPengajuanController;
use App\Http\Controllers\KP\PengumpulanAkhirController;
use App\Http\Controllers\KP\PenilaianPembimbingController;
use App\Http\Controllers\KP\PenilaianSeminarController;
use App\Http\Controllers\KP\PlotingController as KPPlotingController;
use App\Http\Controllers\KP\ProdiController as KPProdiController;
use App\Http\Controllers\KP\ReviewSeminarController as KPReviewSeminarController;
use App\Http\Controllers\KP\SeminarController as KPSeminarController;

/*
|--------------------------------------------------------------------------
| ROUTING TUGAS AKHIR (TA) - SISTEM LAMA YANG SUDAH LIVE
|--------------------------------------------------------------------------
|
| Routing di bawah ini adalah routing ASLI dari sistem TA yang sudah LIVE
| di hosting produksi.
|
| ⚠️ ATURAN DEPLOYMENT:
|
| 1. Jika ada update routing TA dari hosting produksi:
|    - BOLEH mengganti seluruh bagian ini dengan routing TA yang baru
|    - PASTIKAN route yang ditandai ✅ REDIRECT tetap dipertahankan
|    - JANGAN ubah controller yang sudah redirect ke KP
|
| 2. Karakteristik routing TA:
|    - Tidak menggunakan prefix URL (contoh: /pengajuan-mahasiswa)
|    - Menggunakan controller tanpa alias (contoh: PengajuanController)
|    - Menggunakan name tanpa prefix (contoh: pengajuan.mahasiswa)
|
| 3. Route yang DI-REDIRECT ke KP (✅ REDIRECT):
|    - /login → LoginSelectorController (KP) - Halaman pilih role
|    - /dashboard-mahasiswa → redirect ke /kp/pilih-sistem
|
| 4. Route TA lainnya TETAP AKTIF dan tidak diubah:
|    - Tetap menggunakan controller TA
|    - Tetap menggunakan view TA
|    - Tetap menggunakan model TA
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================================================
// LOGIN - LANGSUNG KE LOGIN MAHASISWA
// ============================================================================
Route::get('/login', [LoginController::class, 'loginMahasiswa'])->name('login')->middleware('isMahasiswaLogin');

// ========================================
// STORAGE FILE SERVING
// ========================================
// Route untuk serve file dari storage/app/public
// Digunakan oleh helper storage_url() untuk menampilkan file lampiran
Route::get('/storage-file/{path}', [App\Http\Controllers\StorageController::class, 'serveFile'])
    ->where('path', '.*')
    ->name('storage.file');

// Route storage-link dipindah ke bawah (UTILITY ROUTES)

// Route untuk handle old URL format: /ekapta-app/storage/app/public/...
// Redirect ke storage controller
Route::get('/ekapta-app/storage/app/public/{path}', function($path) {
    return redirect()->route('storage.file', ['path' => $path]);
})->where('path', '.*');

// Route untuk handle old URL format: /ekapta-app-new/storage/app/public/...
Route::get('/ekapta-app-new/storage/app/public/{path}', function($path) {
    return redirect()->route('storage.file', ['path' => $path]);
})->where('path', '.*');

// Backward compatibility untuk URL lama /lampiran/... -> cek file lama dan baru
Route::get('/lampiran/{path}', function($path) {
    return app(\App\Http\Controllers\StorageController::class)->serveLegacyLampiran($path);
})->where('path', '.*');

// Route untuk serve file lampirans langsung (tanpa symbolic link)
// Cek di 2 lokasi: public/lampirans/ (file lama) dan storage/app/public/lampirans/ (file baru)
Route::get('/lampirans/{path}', function($path) {
    return app(\App\Http\Controllers\StorageController::class)->serveLampirans($path);
})->where('path', '.*');

// ========================================
// LOGIN USER (TA - ASLI)
// ========================================
// Redirect /login/mahasiswa ke /login untuk backward compatibility
Route::get('/login/mahasiswa', function() {
    return redirect()->route('login');
})->name('login.mahasiswa');
// Throttle: maks 5 percobaan per menit per IP — mencegah brute-force
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa')->middleware(['isMahasiswaLogin', 'throttle:5,1']);
Route::post('/login', [LoginController::class, 'cekMahasiswa'])->middleware(['isMahasiswaLogin', 'throttle:5,1']);
Route::get('/login/prodi', [LoginController::class, 'loginProdi'])->name('login.prodi')->middleware('isProdiLogin');
Route::post('/login/prodi', [LoginController::class, 'cekProdi'])->name('cek.prodi')->middleware(['isProdiLogin', 'throttle:5,1']);
Route::get('/login/dosen', [LoginController::class, 'loginDosen'])->name('login.dosen')->middleware('isDosenLogin');
Route::post('/login/dosen', [LoginController::class, 'cekDosen'])->name('cek.dosen')->middleware(['isDosenLogin', 'throttle:5,1']);
Route::get('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin')->middleware('isAdminLogin');
Route::post('/login/admin', [LoginController::class, 'cekAdmin'])->name('cek.admin')->middleware(['isAdminLogin', 'throttle:5,1']);

// ✅ REDIRECT: Login himpunan ke KP
Route::get('/login/himpunan', function() {
    return redirect()->route('kp.login.himpunan');
});

// ========================================
// DASHBOARD (TA - REDIRECT KE KP)
// ========================================
// ✅ INTEGRASI: Semua dashboard redirect ke dashboard KP yang versi integrasi
Route::get('/dashboard-mahasiswa', function() {
    return redirect()->route('kp.pilih.sistem');
})->name('dashboard.mahasiswa')->middleware('isMahasiswa');

// Backward compatibility untuk tombol/menu dashboard mahasiswa versi lama
Route::get('/dashboard-mahasiswa-kp', function() {
    return redirect()->route('kp.dashboard.mahasiswa');
})->name('dashboard.mahasiswa.kp')->middleware('isMahasiswa');

Route::get('/dashboard-mahasiswa-jilid', function() {
    return redirect()->route('kp.pengumpulan-akhir.mahasiswa');
})->name('dashboard.mahasiswa.jilid')->middleware('isMahasiswa');

Route::get('/dashboard-prodi', function() {
    return redirect()->route('kp.dashboard.prodi');
})->name('dashboard.prodi')->middleware('isProdi');

Route::get('/dashboard-dosen', function() {
    return redirect()->route('kp.dashboard.dosen');
})->name('dashboard.dosen')->middleware('isDosen');

Route::get('/dashboard-admin', function() {
    return redirect()->route('kp.dashboard.admin');
})->name('dashboard.admin')->middleware('isAdmin');

// ========================================
// PENGAJUAN TA (TA - ASLI)
// ========================================
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
Route::post('pengajuan/cancel/acc', [PengajuanController::class, 'cancelAcc'])->name('pengajuan.cancel.acc')->middleware('isProdi');
Route::post('pengajuan/cancel/tolak', [PengajuanController::class, 'cancelTolak'])->name('pengajuan.cancel.tolak')->middleware('isProdi');
Route::put('pengajuan/edit/judul/{id}', [PengajuanController::class, 'editJudulPengajuan'])->name('pengajuan.edit.judul')->middleware('isProdi');

// ========================================
// PLOTING PEMBIMBING & PENGUJI (TA - ASLI)
// ========================================
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isAdminProdi');
Route::post('/ploting/penguji/ujian', [PlotingController::class, 'plotingPengujiUjian'])->name('ploting.penguji.ujian')->middleware('isAdminProdi');

// ========================================
// PENDAFTARAN TA (TA - ASLI)
// ========================================
Route::get('/pendaftaran-admin', [PendaftaranController::class, 'pendaftaranAdmin'])->name('pendaftaran.admin')->middleware('isAdmin');
Route::get('/pendaftaran-mahasiswa', [PendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('isMahasiswa');
Route::get('/pendaftaran/detail/{id}', [PendaftaranController::class, 'pendaftaranDetail'])->middleware('isMahasiswa');
Route::get('/pendaftaran/review/{id}', [PendaftaranController::class, 'pendaftaranReview'])->middleware('isAdmin');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('isMahasiswa');
Route::get('/pendaftaran/edit/{id}', [PendaftaranController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update')->middleware('isMahasiswa');
Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete')->middleware('isMahasiswa');
Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc')->middleware('isAdmin');
Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi')->middleware('isAdmin');
Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete')->middleware('isAdmin');
Route::post('/pendaftaran/cancel/acc', [PendaftaranController::class, 'cancelAcc'])->name('pendaftaran.cancel.acc')->middleware('isAdmin');
Route::get('/pendaftaran/disable/{id}', [PendaftaranController::class, 'disablePendaftaran'])->name('pendaftaran.disable')->middleware('isMahasiswa');

// Bagian-bagian bimbingan
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');
Route::post('/bagian/import', [BagianController::class, 'import'])->name('bagian.import')->middleware('isAdmin');
Route::post('/bagian/active', [BagianController::class, 'bagianActive'])->name('bagian.active')->middleware('isAdmin');
Route::get('/bagian/up/{id}', [BagianController::class, 'up'])->name('bagian.up')->middleware('isAdmin');
Route::get('/bagian/down/{id}', [BagianController::class, 'down'])->name('bagian.down')->middleware('isAdmin');

// Bimbingan TA
Route::get('/bimbingan-prodi', [BimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
Route::get('/bimbingan-dosen-progress', [BimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
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
Route::post('/bimbingan/cancel/acc', [BimbinganController::class, 'cancelAcc'])->name('bimbingan.cancel.acc')->middleware('isDosen');
Route::post('/bimbingan/cancel/revisi', [BimbinganController::class, 'cancelRevisi'])->name('bimbingan.cancel.revisi')->middleware('isDosen');
Route::get('/bimbingan/review-prodi/{id}', [BimbinganController::class, 'reviewProdi'])->name('bimbingan.review.prodi')->middleware('isProdi');
Route::get('/bimbingan/review-admin/{id}', [BimbinganController::class, 'reviewAdmin'])->name('bimbingan.review.admin')->middleware('isAdmin');
Route::get('/bimbingan/rekap-dosen', [BimbinganController::class, 'rekapDosen'])->name('bimbingan.rekap.dosen')->middleware('isProdi');
// TA Bimbingan Input - tetap pakai controller TA asli
Route::get('/bimbingan/input', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.admin.input')->middleware('isAdmin');
Route::get('/bimbingan/input-prodi', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.prodi.input')->middleware('isProdi');
Route::get('/bimbingan/input/{dosen_id}/{mahasiswa_id}', [BimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.admin.input.create')->middleware('isAdminProdi');
Route::get('/bimbingan/input-prodi/{dosen_id}/{mahasiswa_id}', [BimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.prodi.input.create')->middleware('isProdi');

// Route store tetap menggunakan controller TA asli karena logic backend sama,
// tapi view response-nya mungkin perlu penyesuaian jika menggunakan back().
// Namun untuk amannya kita biarkan dulu controller aslinya menangani proses simpan.
// Jika nanti bermasalah redirectnya, kita ganti ke controller KP.
Route::post('/bimbingan/store', [BimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.admin.input.store')->middleware('isAdminProdi');
Route::post('/bimbingan/store-prodi', [BimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.prodi.input.store')->middleware('isProdi');
Route::get('bimbingan/acc-submit-manual/{id}', [BimbinganController::class, 'bimbinganAccManual'])->name('bimbingan.acc.submit.manual')->middleware('isAdminProdi');
Route::put('bimbingan/reject-submit-manual/{id}', [BimbinganController::class, 'bimbinganRejectManual'])->name('bimbingan.reject.submit.manual')->middleware('isAdminProdi');

// Seminar TA
Route::get('seminar/review/{id}', [SeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin')->middleware('isAdminProdi');
Route::post('seminar/acc', [SeminarController::class, 'accSeminar'])->name('seminar.acc')->middleware('isAdminProdi');
Route::post('seminar/revisi', [SeminarController::class, 'revisiSeminar'])->name('seminar.revisi')->middleware('isAdminProdi');
Route::post('seminar/cancel-acc', [SeminarController::class, 'cancelAcc'])->name('seminar.cancel.acc')->middleware('isAdminProdi');
Route::post('/seminar/revisi/delete', [SeminarController::class, 'deleteRevisi'])->name('seminar.revisi.delete')->middleware('isAdminProdi');
Route::post('/seminar/set/date-exam', [SeminarController::class, 'setDateExam'])->name('seminar.set.date.exam')->middleware('isAdminProdi');
Route::get('seminar/rekap', [SeminarController::class,'rekapSeminar'])->name('seminar.rekap')->middleware('isAdminProdi');

// Ujian TA
Route::get('ujian/review/{id}', [UjianController::class,'ujianReviewAdmin'])->name('ujian.review.admin')->middleware('isAdminProdi');
Route::post('ujian/acc', [UjianController::class, 'accUjian'])->name('ujian.acc')->middleware('isAdminProdi');
Route::post('ujian/revisi', [UjianController::class, 'revisiUjian'])->name('ujian.revisi')->middleware('isAdminProdi');
Route::post('ujian/cancel-acc', [UjianController::class, 'cancelAcc'])->name('ujian.cancel.acc')->middleware('isAdminProdi');
Route::post('/ujian/revisi/delete', [UjianController::class, 'deleteRevisi'])->name('ujian.revisi.delete')->middleware('isAdminProdi');
Route::post('/ujian/set/date-exam', [UjianController::class, 'setDateExam'])->name('ujian.set.date.exam')->middleware('isAdminProdi');
Route::get('ujian/rekap', [UjianController::class,'rekapujian'])->name('ujian.rekap')->middleware('isAdminProdi');

Route::group(['middleware' => 'isMahasiswa'], function(){
    // Seminar TA - Mahasiswa
    Route::get('seminar-mahasiswa', [SeminarController::class, 'seminarMahasiswa'])->name('seminar.mahasiswa');
    Route::get('seminar/create', [SeminarController::class, 'create'])->name('seminar.create');
    Route::get('seminar/edit/{id}', [SeminarController::class, 'edit'])->name('seminar.edit');
    Route::get('seminar/detail/{id}', [SeminarController::class, 'detail'])->name('seminar.detail');
    Route::get('seminar/reviews/{id}', [SeminarController::class, 'seminarReviews'])->name('seminar.reviews');
    Route::post('seminar/store', [SeminarController::class, 'store'])->name('seminar.store');
    Route::put('seminar/update/{id}', [SeminarController::class, 'update'])->name('seminar.update');
    Route::get('seminar/edit/proposal/{id}', [SeminarController::class, 'editProposal'])->name('seminar.edit.proposal');
    Route::put('seminar/update/proposal/{id}', [SeminarController::class, 'updateProposal'])->name('seminar.update.proposal');
    Route::post('seminar/delete', [SeminarController::class, 'delete'])->name('seminar.delete');
    Route::get('review/seminar/edit/{id}', [ReviewSeminarController::class, 'edit'])->name('review.seminar.edit');
    Route::post('review/seminar/update', [ReviewSeminarController::class, 'update'])->name('review.seminar.update');
    Route::get('review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManual'])->name('review.seminar.submit.acc.manual');
    Route::put('review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManualStore'])->name('review.seminar.submit.acc.manual.store');

    // Ujian TA - Mahasiswa
    Route::get('ujian-mahasiswa', [UjianController::class, 'ujianMahasiswa'])->name('ujian.mahasiswa');
    Route::get('ujian/create', [UjianController::class, 'create'])->name('ujian.create');
    Route::post('ujian/store', [UjianController::class, 'store'])->name('ujian.store');
    Route::get('ujian/detail/{id}', [UjianController::class, 'detail'])->name('ujian.detail');
    Route::get('ujian/edit/{id}', [UjianController::class, 'edit'])->name('ujian.edit');
    Route::put('ujian/update/{id}', [UjianController::class, 'update'])->name('ujian.update');
    Route::get('ujian/reviews/{id}', [UjianController::class, 'ujianReviews'])->name('ujian.reviews');
    Route::get('review/ujian/edit/{id}', [ReviewUjianController::class, 'edit'])->name('review.ujian.edit');
    Route::post('review/ujian/update', [ReviewUjianController::class, 'update'])->name('review.ujian.update');
    Route::get('ujian/edit/proposal/{id}', [UjianController::class, 'editProposal'])->name('ujian.edit.proposal');
    Route::put('ujian/update/proposal/{id}', [UjianController::class, 'updateProposal'])->name('ujian.update.proposal');
    Route::get('review/ujian/submit-acc-manual/{id}', [ReviewUjianController::class, 'submitManual'])->name('review.ujian.submit.acc.manual');
    Route::put('review/ujian/submit-acc-manual/{id}', [ReviewUjianController::class, 'submitManualStore'])->name('review.ujian.submit.acc.manual.store');

    Route::get('bimbingan/submit-acc-manual/{id}', [BimbinganController::class, 'submitAccManual'])->name('bimbingan.submit.acc.manual');
    Route::post('bimbingan/submit-acc-manual-store', [BimbinganController::class, 'submitAccManualStore'])->name('bimbingan.submit.acc.manual.store');
});

Route::group(['middleware' => 'isAdmin'], function(){
    // Seminar TA
    Route::get('seminar-admin', [SeminarController::class,'seminarAdmin'])->name('seminar.admin');

    // Ujian TA
    Route::get('ujian-admin', [UjianController::class,'ujianAdmin'])->name('ujian.admin');

    // Dosen Prodi
    Route::post('/dosen-prodi/import', [DosenProdiController::class, 'import'])->name('dosen.prodi.import');
    Route::put('/dosen-prodi/update/{dosen}', [DosenProdiController::class, 'update'])->name('dosen.prodi.update');

    // Pengaturan Akun Admin
    Route::get('admin/account', [AdminController::class, 'account'])->name('admin.account');
    Route::put('admin/account/{id}', [AdminController::class, 'accountUpdate'])->name('admin.account.update');

    // Pembatalan Bimbingan
    Route::get('bimbingan/canceled/{id}', [BimbinganController::class, 'bimbinganCanceled'])->name('bimbingan.canceled');
});

Route::group(['middleware' => 'isDosen'], function(){
    // Seminar TA - Dosen
    Route::get('seminar-dosen', [SeminarController::class,'seminarDosen'])->name('seminar.dosen');
    Route::get('review/seminar/{id}', [ReviewSeminarController::class, 'reviewDosen'])->name('review.seminar.dosen');
    Route::post('review/seminar/revisi/store', [ReviewSeminarController::class, 'revisiStore'])->name('review.seminar.revisi.store');
    Route::post('review/seminar/revisi/delete', [ReviewSeminarController::class, 'revisiDelete'])->name('review.seminar.revisi.delete');
    Route::post('review/seminar/acc', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc');
    Route::post('review/seminar/nilai', [ReviewSeminarController::class, 'reviewNilai'])->name('review.seminar.nilai');
    Route::post('review/seminar/cancel/acc', [ReviewSeminarController::class, 'reviewCancelAcc'])->name('review.seminar.cancel.acc');

    // Ujian TA - Dosen
    Route::get('ujian-dosen', [UjianController::class,'ujianDosen'])->name('ujian.dosen');
    Route::get('review/ujian/{id}', [ReviewUjianController::class, 'reviewDosen'])->name('review.ujian.dosen');
    Route::post('review/ujian/revisi/store', [ReviewUjianController::class, 'revisiStore'])->name('review.ujian.revisi.store');
    Route::post('review/ujian/revisi/delete', [ReviewUjianController::class, 'revisiDelete'])->name('review.ujian.revisi.delete');
    Route::post('review/ujian/acc', [ReviewUjianController::class, 'reviewAcc'])->name('review.ujian.acc');
    Route::post('review/ujian/nilai', [ReviewUjianController::class, 'reviewNilai'])->name('review.ujian.nilai');
    Route::post('review/ujian/cancel/acc', [ReviewUjianController::class, 'reviewCancelAcc'])->name('review.ujian.cancel.acc');

    // Pengaturan Akun Dosen
    Route::get('dosen/account', [DosenController::class, 'account'])->name('dosen.account');
    Route::put('dosen/account/{id}', [DosenController::class, 'accountUpdate'])->name('dosen.account.update');
});

Route::group(['middleware' => 'isProdi'], function(){
    // Seminar TA - Prodi
    Route::get('seminar-prodi', [SeminarController::class, 'seminarProdi'])->name('seminar.prodi');
    Route::get('seminar/detail-prodi/{id}', [SeminarController::class, 'seminarProdiDetail'])->name('seminar.prodi.detail');
    Route::post('review/seminar/acc-prodi', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc.prodi');
    Route::post('seminar/update-status', [SeminarController::class, 'updateStatus'])->name('seminar.update.status');
    Route::post('review/seminar/update-nilai', [ReviewSeminarController::class, 'updateNilai'])->name('review.update.nilai');

    // Ujian TA - Prodi
    Route::get('ujian-prodi', [UjianController::class, 'ujianProdi'])->name('ujian.prodi');
    Route::get('ujian/detail-prodi/{id}', [UjianController::class, 'ujianProdiDetail'])->name('ujian.prodi.detail');
    Route::post('review/ujian/acc-prodi', [ReviewUjianController::class, 'reviewAcc'])->name('review.ujian.acc.prodi');
    Route::post('ujian/update-status', [UjianController::class, 'updateStatus'])->name('ujian.update.status');
    Route::post('review/ujian/update-nilai', [ReviewUjianController::class, 'updateNilai'])->name('review.update.nilai.ujian');

    // Pengaturan Akun Prodi
    Route::get('prodi/account', [ProdiController::class, 'account'])->name('prodi.account');
    Route::put('prodi/account/{id}', [ProdiController::class, 'accountUpdate'])->name('prodi.account.update');
});

// ✅ ROUTE ALIAS: Master Data KP - Untuk backward compatibility dengan view
// View KP menggunakan route tanpa prefix 'kp.' jadi kita buat alias
use App\Http\Controllers\KP\ProdiController as KPProdiController2;
use App\Http\Controllers\KP\MahasiswaController as KPMahasiswaController2;
use App\Http\Controllers\KP\DosenController as KPDosenController2;
use App\Http\Controllers\KP\FakultasController as KPFakultasController2;

Route::post('/prodi/store', [KPProdiController2::class, 'store'])->name('prodi.store')->middleware('isAdmin');
Route::post('/prodi/import', [KPProdiController2::class, 'import'])->name('prodi.import')->middleware('isAdmin');
// ⚠️ PENTING: Route presentase nilai TA menggunakan ProdiController TA, BUKAN KP!
// Route ini digunakan oleh view TA untuk mengelola presentase nilai TA
Route::get('/prodi/presentase-nilai/{id}', [ProdiController::class, 'presentaseNilai'])->name('prodi.presentase.nilai')->middleware('isAdmin');
Route::post('/prodi/presentase-nilai/store', [ProdiController::class, 'presentaseNilaiStore'])->name('prodi.presentase.nilai.store')->middleware('isAdmin');
Route::get('/prodi/reset-password/{id}', [KPProdiController2::class, 'resetPassword'])->name('prodi.reset.password')->middleware('isAdmin');

Route::post('/mahasiswa/store', [KPMahasiswaController2::class, 'store'])->name('mahasiswa.store')->middleware('isAdmin');
Route::post('/mahasiswa/import', [KPMahasiswaController2::class, 'import'])->name('mahasiswa.import')->middleware('isAdmin');
Route::post('/mahasiswa/detail/import', [KPMahasiswaController2::class, 'importDetail'])->name('mahasiswa.detail.import')->middleware('isAdmin');
Route::get('/mahasiswa/reset-password/{id}', [KPMahasiswaController2::class, 'resetPassword'])->name('mahasiswa.reset.password')->middleware('isAdmin');

Route::post('/dosen/store', [KPDosenController2::class, 'store'])->name('dosen.store')->middleware('isAdmin');
Route::put('/dosen/{id}', [KPDosenController2::class, 'update'])->name('dosen.update')->middleware('isAdmin');
Route::post('/dosen/import', [KPDosenController2::class, 'import'])->name('dosen.import')->middleware('isAdmin');
Route::get('/dosen/reset-password/{id}', [KPDosenController2::class, 'resetPassword'])->name('dosen.reset.password')->middleware('isAdmin');
Route::get('/dosen/change-manual/{id}', [KPDosenController2::class, 'changeManual'])->name('dosen.change.manual')->middleware('isAdmin');

Route::post('/fakultas/store', [KPFakultasController2::class, 'store'])->name('fakultas.store')->middleware('isAdmin');
Route::post('/fakultas/import', [KPFakultasController2::class, 'import'])->name('fakultas.import')->middleware('isAdmin');
Route::post('/fakultas/update', [KPFakultasController2::class, 'update'])->name('fakultas.update')->middleware('isAdmin');
Route::post('/fakultas/add/prodi', [KPFakultasController2::class, 'addProdi'])->name('fakultas.add.prodi')->middleware('isAdmin');
Route::post('/fakultas/delete/prodi', [KPFakultasController2::class, 'deleteProdi'])->name('fakultas.delete.prodi')->middleware('isAdmin');

// ✅ REDIRECT: Master Data TA → KP (Integrasi)
// Route TA di-redirect ke KP karena view KP sudah terintegrasi dengan data TA dan KP
Route::get('/prodis', function() {
    return redirect()->route('kp.prodi');
})->name('prodis')->middleware('isAdmin');

Route::get('/prodi/{id}', function($id) {
    return redirect()->route('kp.prodi.detail', $id);
})->middleware('isAdmin');

Route::get('mahasiswas', function() {
    return redirect()->route('kp.mahasiswa');
})->name('mahasiswas')->middleware('isAdmin');

Route::get('/dosens', function() {
    return redirect()->route('kp.dosen');
})->name('dosens')->middleware('isAdmin');

Route::get('dosen/{id}', function($id) {
    return redirect()->route('kp.dosen.edit', $id);
})->name('dosen.edit')->middleware('isAdmin');

Route::get('/fakultas', function() {
    return redirect()->route('kp.fakultas');
})->name('fakultas')->middleware('isAdmin');

Route::get('/fakultas/setting/{fakultas}', function($fakultas) {
    return redirect()->route('kp.fakultas.setting', $fakultas);
})->middleware('isAdmin');

// Route TA yang masih digunakan (tidak di-redirect)
// Semua POST route sudah dipindahkan ke KP

// ✅ REDIRECT: Route KP Presentase Nilai - Digunakan oleh view TA untuk tombol "Presentase KP"
// Route ini mengarah ke KP ProdiController karena mengelola presentase nilai KP
Route::get('/prodi/presentase-nilai-kp/{id}', [KPProdiController::class, 'presentaseNilai'])->name('prodi.presentase.nilai.kp')->middleware('isAdmin');
Route::post('/prodi/presentase-nilai-kp/store', [KPProdiController::class, 'presentaseNilaiStore'])->name('prodi.presentase.nilai.kp.store')->middleware('isAdmin');

// Mahasiswa
Route::get('mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('profile')->middleware('isMahasiswa');
Route::get('mahasiswa/account', [MahasiswaController::class, 'account'])->name('mahasiswa.account')->middleware('isMahasiswa');
Route::put('mahasiswa/account/{id}', [MahasiswaController::class, 'accountUpdate'])->name('mahasiswa.account.update')->middleware('isMahasiswa');
Route::post('profile/update', [MahasiswaController::class, 'update'])->name('profile.update')->middleware('isMahasiswa');

// Himpunan (Data Master - KP Only)
// HimpunanMasterController redirect ke route('himpunans') tanpa prefix kp.
Route::get('/himpunans', [HimpunanMasterController::class, 'index'])->name('himpunans')->middleware('isAdmin');

//Dekan
Route::post('/dekan/store', [DekanController::class, 'store'])->name('dekan.store')->middleware('isAdmin');
Route::post('/dekan/import', [DekanController::class, 'import'])->name('dekan.import')->middleware('isAdmin');
Route::post('/dekan/update', [DekanController::class, 'update'])->name('dekan.update')->middleware('isAdmin');
Route::post('/dekan/delete', [DekanController::class, 'delete'])->name('dekan.delete')->middleware('isAdmin');
Route::post('/dekan/enabled', [DekanController::class, 'enabled'])->name('dekan.enabled')->middleware('isAdmin');
Route::post('/dekan/disabled', [DekanController::class, 'disabled'])->name('dekan.disabled')->middleware('isAdmin');

// Jilid TA Admin
Route::get('dashboard-fotokopi', [JilidController::class, 'index'])->name('jilid.index')->middleware('isAdminFotokopi');
Route::get('jilid/detail/{id}',[JilidController::class, 'detail'])->name('jilid.detail')->middleware('isAdminFotokopi');
Route::get('jilid/detail-mahasiswa/{id}',[JilidController::class, 'detailMahasiswa'])->name('jilid.detail.mahasiswa')->middleware('isMahasiswa');
Route::put('jilid/acc/{id}',[JilidController::class, 'acc'])->name('jilid.acc')->middleware('isAdminFotokopi');
Route::get('jilid-mahasiswa',[JilidController::class, 'jilidMahasiswa'])->name('jilid.mahasiswa')->middleware('isMahasiswa');
Route::get('jilid/create',[JilidController::class, 'create'])->name('jilid.create')->middleware('isMahasiswa');
Route::post('jilid/store',[JilidController::class, 'store'])->name('jilid.store')->middleware('isMahasiswa');
Route::get('jilid/edit/{id}',[JilidController::class, 'edit'])->name('jilid.edit')->middleware('isMahasiswa');
Route::put('jilid/update/{id}',[JilidController::class, 'update'])->name('jilid.update')->middleware('isMahasiswa');
Route::get('jilid/confirm-completed/{id}',[JilidController::class, 'confirmCompleted'])->name('jilid.confirm.completed')->middleware('isAdmin');

// Jilid TA Prodi
Route::get('jilid-prodi', [JilidController::class, 'prodiIndex'])->name('jilid.prodi.index')->middleware('isProdi');
Route::get('jilid-prodi/detail/{id}', [JilidController::class, 'prodiDetail'])->name('jilid.prodi.detail')->middleware('isProdi');

// Cetak Dokumen
Route::group(['middleware' => 'isLogin'], function (){
    Route::get('/cetak/lembar-persetujuan-pembimbing-mahasiswa', [CetakController::class, 'cetakLembarPersetujuanMahasiswa'])->name('cetak.lembar.persetujuan.mahasiswa');
    Route::get('/cetak/lembar-pernyataan-keaslian', [CetakController::class, 'cetakLembarPernyataanKeaslian'])->name('cetak.lembar.pernyataan.keaslian');
    Route::get('/cetak/surat-tugas-bimbingan', [CetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
    Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [CetakController::class, 'cetakSuratTugasBimbingan']);
    Route::get('/cetak/berita-acara-ujian-proposal/{seminar}', [CetakController::class, 'cetakBeritaAcaraUjianProposal'])->name('cetak.berita.acara.ujian.proposal');
    Route::get('/cetak/berita-acara-ujian-proposal-blank/{ujian_or_seminar}/{type}', [CetakController::class, 'cetakBeritaAcaraUjianProposalBlank'])->name('cetak.berita.acara.ujian.proposal.blank');
    Route::get('/cetak/berita-acara-ujian-pendadaran/{ujian}', [CetakController::class, 'cetakBeritaAcaraUjianPendadaran'])->name('cetak.berita.acara.ujian.pendadaran');
    Route::get('/cetak/surat-riwayat-bimbingan-mahasiswa', [CetakController::class, 'cetakRiwayatBimbinganMahasiswa'])->name('cetak.riwayat.bimbingan.mahasiswa');
    Route::get('/cetak/surat-riwayat-bimbingan/{id}', [CetakController::class, 'cetakRiwayatBimbingan'])->name('cetak.riwayat.bimbingan');
    Route::get('/cetak/lembar-persetujuan/{type}', [CetakController::class, 'cetakLembarPersetujuan'])->name('cetak.lembar.persetujuan');
    Route::get('/cetak/lembar-pengesahan', [CetakController::class, 'cetakLembarPengesahan'])->name('cetak.lembar.pengesahan');
});

//Public
Route::get('/public/riwayat-bimbingan/{id}',[BimbinganController::class, 'public'])->name('bimbingan.public');

// Logout
Route::get('/logout/mahasiswa', function (Request $request) {
    if (Auth::guard('mahasiswa')->check()) {
        Auth::guard('mahasiswa')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.mahasiswa');
})->name('logout.mahasiswa');

Route::get('/logout/dosen', function (Request $request) {
    if (Auth::guard('dosen')->check()) {
        Auth::guard('dosen')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.dosen');
})->name('logout.dosen');

Route::get('/logout/prodi', function (Request $request) {
    if (Auth::guard('prodi')->check()) {
        Auth::guard('prodi')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.prodi');
})->name('logout.prodi');

Route::get('/logout/admin', function (Request $request) {
    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.admin');
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

Route::group(['middleware' => 'isAdmin'], function (){
    Route::get('laporan-bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganAdmin'])->name('bimbingan.admin');
});


/*
|--------------------------------------------------------------------------
| ROUTING KERJA PRAKTEK (KP) - SISTEM BARU YANG DIINTEGRASIKAN
|--------------------------------------------------------------------------
|
| Routing di bawah ini adalah routing BARU untuk sistem Kerja Praktek (KP)
| yang diintegrasikan ke dalam sistem TA.
|
| ⚠️ ATURAN DEPLOYMENT:
|
| 1. JANGAN HAPUS bagian routing KP ini saat update routing TA dari hosting
| 2. Bagian ini AMAN untuk ditambahkan ke routing TA yang sudah ada
| 3. Tidak akan bentrok dengan routing TA karena menggunakan prefix /kp
|
| KARAKTERISTIK ROUTING KP:
| - Semua route menggunakan PREFIX /kp (contoh: /kp/pengajuan-mahasiswa)
| - Semua route menggunakan NAME PREFIX kp. (contoh: kp.pengajuan.mahasiswa)
| - Controller menggunakan ALIAS KP* (contoh: KPPengajuanController)
| - View berada di folder resources/views/kp/
| - Model berada di folder app/Models/KP/
| - Database menggunakan tabel dengan suffix _kp (contoh: pengajuan_kps)
|
| PERBEDAAN TA vs KP:
| ┌─────────────────┬──────────────────────────┬──────────────────────────┐
| │ Aspek           │ TA (Sistem Lama)         │ KP (Sistem Baru)         │
| ├─────────────────┼──────────────────────────┼──────────────────────────┤
| │ URL             │ /pengajuan-mahasiswa     │ /kp/pengajuan-mahasiswa  │
| │ Route Name      │ pengajuan.mahasiswa      │ kp.pengajuan.mahasiswa   │
| │ Controller      │ PengajuanController      │ KPPengajuanController    │
| │ Model           │ App\Models\Pengajuan     │ App\Models\KP\Pengajuan  │
| │ View            │ pages/mahasiswa/...      │ kp/pages/mahasiswa/...   │
| │ Database Table  │ pengajuans               │ pengajuan_kps            │
| └─────────────────┴──────────────────────────┴──────────────────────────┘
|
| KEAMANAN INTEGRASI:
| - Sistem TA tidak terpengaruh sama sekali
| - Sistem KP berjalan independen dengan database terpisah (suffix _kp)
| - Tidak ada perubahan pada controller, model, view, atau migration TA
| - Routing TA tetap aktif dan berfungsi normal
|
*/

// ============================================================================
// ROUTE REDIRECT MAHASISWA KE SISTEM TA
// ============================================================================
// Route ini digunakan oleh halaman pilih-sistem untuk redirect mahasiswa
// ke dashboard TA yang sebenarnya (bukan langsung ke pengajuan).
// Route ini TIDAK menggunakan prefix 'kp.' karena diakses dari view KP.
//
Route::get('/dashboard-ta', [DashboardController::class, 'dashboardMahasiswaTA'])->name('dashboard.mahasiswa.ta')->middleware('isMahasiswa');

Route::prefix('kp')->name('kp.')->group(function () {
    Route::get('/mahasiswa/reset-password/{id}', [KPMahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset.password')->middleware('isAdmin');

    // ============================================================================
    // LOGIN HIMPUNAN (KHUSUS KP)
    // ============================================================================
    // Role Himpunan hanya ada di sistem KP, tidak ada di TA
    // Himpunan mengelola jadwal seminar dan verifikasi pembayaran
    //
    Route::get('/login/himpunan', [KPLoginController::class, 'loginHimpunan'])->name('login.himpunan');
    Route::post('/login/himpunan', [KPLoginController::class, 'cekHimpunan'])->name('cek.himpunan')->middleware('throttle:5,1');
    Route::get('/logout/himpunan', [KPLoginController::class, 'logoutHimpunan'])->name('logout.himpunan');

    // ============================================================================
    // HALAMAN PILIH SISTEM (MAHASISWA)
    // ============================================================================
    // Halaman ini menampilkan pilihan antara sistem TA atau KP untuk mahasiswa
    // Mahasiswa bisa memilih mau mengakses sistem TA atau KP
    //
    Route::get('/pilih-sistem', [KPDashboardController::class, 'pilihSistem'])->name('pilih.sistem')->middleware('isMahasiswa');

    // Dashboard KP
    Route::get('/dashboard', [KPDashboardController::class, 'dashboardMahasiswaKP'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');

    // Mahasiswa Profile & Account KP
    Route::get('/mahasiswa/profile', [KPMahasiswaController::class, 'profile'])->name('profile')->middleware('isMahasiswa');
    Route::post('/mahasiswa/profile/update', [KPMahasiswaController::class, 'update'])->name('profile.update')->middleware('isMahasiswa');
    Route::get('/mahasiswa/account', [KPMahasiswaController::class, 'account'])->name('mahasiswa.account')->middleware('isMahasiswa');
    Route::put('/mahasiswa/account/{id}', [KPMahasiswaController::class, 'accountUpdate'])->name('mahasiswa.account.update')->middleware('isMahasiswa');

    // Dashboard Integrasi (TA + KP)
    Route::get('/dashboard-admin', [KPAdminController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');
    Route::get('/dashboard-prodi', [KPDashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
    Route::get('/dashboard-dosen', [KPDashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
    Route::get('/dashboard-himpunan', [KPDashboardController::class, 'dashboardHimpunan'])->name('dashboard.himpunan')->middleware('isHimpunan');

    // Routes Himpunan - Verifikasi Seminar KP
    Route::group(['middleware' => 'isHimpunan', 'prefix' => 'himpunan'], function () {
        Route::get('/account', [HimpunanController::class, 'account'])->name('himpunan.account');
        Route::put('/account/{id}', [HimpunanController::class, 'accountUpdate'])->name('himpunan.account.update');
        Route::get('/seminar', [HimpunanController::class, 'seminarIndex'])->name('seminar.himpunan');
        Route::get('/seminar/review/{id}', [HimpunanController::class, 'seminarReview'])->name('seminar.himpunan.review');
        Route::post('/seminar/acc', [HimpunanController::class, 'seminarAcc'])->name('seminar.himpunan.acc');
        Route::post('/seminar/revisi', [HimpunanController::class, 'seminarRevisi'])->name('seminar.himpunan.revisi');
        Route::post('/seminar/toggle-pendaftaran', [HimpunanController::class, 'togglePendaftaranSeminar'])->name('seminar.himpunan.toggle');
        Route::get('/jadwal', [HimpunanController::class, 'jadwalIndex'])->name('jadwal.himpunan');
        Route::post('/jadwal/create-sesi', [HimpunanController::class, 'createSesi'])->name('jadwal.himpunan.create');
        Route::get('/jadwal/sesi/{id}', [HimpunanController::class, 'detailSesi'])->name('jadwal.himpunan.detail');
        Route::delete('/jadwal/sesi/{id}', [HimpunanController::class, 'deleteSesi'])->name('jadwal.himpunan.delete');
        Route::post('/jadwal/validasi-selesai', [HimpunanController::class, 'validasiSelesaiSeminar'])->name('jadwal.himpunan.validasi.selesai');
        Route::post('/seminar/validasi-revisi', [HimpunanController::class, 'validasiRevisiPasca'])->name('seminar.himpunan.validasi.revisi');
        Route::post('/seminar/finalisasi-nilai', [HimpunanController::class, 'finalisasiNilai'])->name('seminar.himpunan.finalisasi');
        Route::post('/seminar/input-nilai', [HimpunanController::class, 'inputNilaiManual'])->name('seminar.himpunan.input-nilai');
        Route::get('/seminar/rekap', [HimpunanController::class, 'rekapSeminar'])->name('seminar.himpunan.rekap');
        Route::get('/seminar/rekap-bulanan', [HimpunanController::class, 'rekapSeminarBulanan'])->name('seminar.himpunan.rekap.bulanan');

        // Payment Settings
        Route::get('/payment', [HimpunanController::class, 'paymentSettings'])->name('payment.himpunan');
        Route::post('/payment/update', [HimpunanController::class, 'updatePayment'])->name('payment.himpunan.update');

        // CRUD Metode Pembayaran
        Route::post('/payment/metode/store', [HimpunanController::class, 'storeMetodePembayaran'])->name('payment.metode.store');
        Route::delete('/payment/metode/{id}', [HimpunanController::class, 'deleteMetodePembayaran'])->name('payment.metode.delete');
    });

    // Pengajuan KP
    Route::get('/pengajuan-admin', [KPPengajuanController::class, 'pengajuanAdmin'])->name('pengajuan.admin')->middleware('isAdmin');
    Route::get('/pengajuan-prodi', [KPPengajuanController::class, 'pengajuanProdi'])->name('pengajuan.prodi')->middleware('isProdi');
    Route::get('/pengajuan-mahasiswa', [KPPengajuanController::class, 'pengajuanMahasiswa'])->name('pengajuan.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pengajuan/create', [KPPengajuanController::class, 'create'])->name('pengajuan.create')->middleware('isMahasiswa');
    Route::post('/pengajuan/store', [KPPengajuanController::class, 'store'])->name('pengajuan.store')->middleware('isMahasiswa');
    Route::get('/pengajuan/edit/{id}', [KPPengajuanController::class, 'edit'])->name('pengajuan.edit')->middleware('isMahasiswa');
    Route::post('/pengajuan/update', [KPPengajuanController::class, 'update'])->name('pengajuan.update')->middleware('isMahasiswa');
    Route::post('/pengajuan/delete', [KPPengajuanController::class, 'delete'])->name('pengajuan.delete')->middleware('isMahasiswa');
    Route::post('/pengajuan/acc', [KPPengajuanController::class, 'accPengajuan'])->name('pengajuan.acc')->middleware('isProdi');
    Route::post('/pengajuan/catatan', [KPPengajuanController::class, 'catatanPengajuan'])->name('pengajuan.catatan')->middleware('isProdi');
    Route::post('/pengajuan/revisi', [KPPengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi')->middleware('isProdi');
    Route::post('/pengajuan/revisi/delete', [KPPengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete')->middleware('isProdi');
    Route::post('/pengajuan/tolak', [KPPengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak')->middleware('isProdi');
    Route::get('/pengajuan/detail/{id}', [KPPengajuanController::class, 'pengajuanDetail'])->name('pengajuan.detail')->middleware('isMahasiswa');
    Route::get('/pengajuan/review/{id}', [KPPengajuanController::class, 'pengajuanReview'])->name('pengajuan.review')->middleware('isProdi');
    Route::get('/pengajuan/review-admin/{id}', [KPPengajuanController::class, 'pengajuanReviewAdmin'])->name('pengajuan.review.admin')->middleware('isAdmin');
    Route::post('/pengajuan/cancel/acc', [KPPengajuanController::class, 'cancelAcc'])->name('pengajuan.cancel.acc')->middleware('isProdi');
    Route::post('/pengajuan/cancel/tolak', [KPPengajuanController::class, 'cancelTolak'])->name('pengajuan.cancel.tolak')->middleware('isProdi');
    Route::put('/pengajuan/edit/judul/{id}', [KPPengajuanController::class, 'editJudulPengajuan'])->name('pengajuan.edit.judul')->middleware('isProdi');

    // Ploting pembimbing
    Route::post('/ploting/pembimbing', [KPPlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
    Route::post('/ploting/penguji', [KPPlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isAdminProdi');

    // Pendaftaran KP
    Route::get('/pendaftaran-admin', [KPPendaftaranController::class, 'pendaftaranAdmin'])->name('pendaftaran.admin')->middleware('isAdmin');
    Route::get('/pendaftaran-mahasiswa', [KPPendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pendaftaran/create', [KPPendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('isMahasiswa');
    Route::get('/pendaftaran/detail/{id}', [KPPendaftaranController::class, 'pendaftaranDetail'])->name('pendaftaran.detail')->middleware('isMahasiswa');
    Route::get('/pendaftaran/review/{id}', [KPPendaftaranController::class, 'pendaftaranReview'])->name('pendaftaran.review')->middleware('isAdmin');
    Route::post('/pendaftaran/store', [KPPendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('isMahasiswa');
    Route::get('/pendaftaran/edit/{id}', [KPPendaftaranController::class, 'edit'])->name('pendaftaran.edit')->middleware('isMahasiswa');
    Route::post('/pendaftaran/update', [KPPendaftaranController::class, 'update'])->name('pendaftaran.update')->middleware('isMahasiswa');
    Route::post('/pendaftaran/delete', [KPPendaftaranController::class, 'delete'])->name('pendaftaran.delete')->middleware('isMahasiswa');
    Route::post('/pendaftaran/acc', [KPPendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc')->middleware('isAdmin');
    Route::post('/pendaftaran/revisi', [KPPendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi')->middleware('isAdmin');
    Route::post('/pendaftaran/revisi/delete', [KPPendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete')->middleware('isAdmin');
    Route::post('/pendaftaran/cancel/acc', [KPPendaftaranController::class, 'cancelAcc'])->name('pendaftaran.cancel.acc')->middleware('isAdmin');
    Route::get('/pendaftaran/disable/{id}', [KPPendaftaranController::class, 'disablePendaftaran'])->name('pendaftaran.disable')->middleware('isMahasiswa');

    // Bimbingan KP
    Route::get('/bimbingan-prodi', [KPBimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
    Route::get('/bimbingan-dosen', [KPBimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
    Route::get('/bimbingan-dosen-progress', [KPBimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
    Route::get('/bimbingan-mahasiswa', [KPBimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
    Route::get('/bimbingan/create', [KPBimbinganController::class, 'create'])->name('bimbingan.create')->middleware('isMahasiswa');
    Route::get('/bimbingan/create-manual', [KPBimbinganController::class, 'createManual'])->name('bimbingan.create.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/store-manual', [KPBimbinganController::class, 'storeManual'])->name('bimbingan.store.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/store', [KPBimbinganController::class, 'store'])->name('kp.bimbingan.store')->middleware('isMahasiswa');
    Route::get('/bimbingan/edit/{id}', [KPBimbinganController::class, 'edit'])->name('bimbingan.edit')->middleware('isMahasiswa');
    Route::post('/bimbingan/update', [KPBimbinganController::class, 'update'])->name('bimbingan.update')->middleware('isMahasiswa');
    Route::post('/bimbingan/delete', [KPBimbinganController::class, 'delete'])->name('bimbingan.delete')->middleware('isMahasiswa');
    Route::post('/bimbingan/acc', [KPBimbinganController::class, 'accBimbingan'])->name('bimbingan.acc')->middleware('isDosen');
    Route::post('/bimbingan/revisi/store', [KPBimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store')->middleware('isDosen');
    Route::post('/bimbingan/revisi/delete', [KPBimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete')->middleware('isDosen');
    Route::get('/bimbingan/detail/{id}', [KPBimbinganController::class, 'bimbinganDetail'])->name('bimbingan.detail')->middleware('isMahasiswa');
    Route::get('/bimbingan/submit-acc-manual/{id}', [KPBimbinganController::class, 'submitAccManual'])->name('bimbingan.submit.acc.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/submit-acc-manual-store', [KPBimbinganController::class, 'submitAccManualStore'])->name('bimbingan.submit.acc.manual.store')->middleware('isMahasiswa');
    Route::get('/bimbingan/review/{id}', [KPBimbinganController::class, 'bimbinganReview'])->name('bimbingan.review')->middleware('isDosen');

    // Bimbingan Offline KP
    Route::get('/bimbingan-offline/create', [KPBimbinganController::class, 'createOffline'])->name('bimbingan-offline.create')->middleware('isMahasiswa');
    Route::post('/bimbingan-offline/store', [KPBimbinganController::class, 'storeOffline'])->name('bimbingan-offline.store')->middleware('isMahasiswa');
    Route::get('/bimbingan-offline/detail/{id}', [KPBimbinganController::class, 'detailOffline'])->name('bimbingan-offline.detail')->middleware('isMahasiswa');
    Route::post('/bimbingan-offline/verify', [KPBimbinganController::class, 'verifyOffline'])->name('bimbingan-offline.verify')->middleware('isProdi');
    Route::post('/bimbingan-offline/reject', [KPBimbinganController::class, 'rejectOffline'])->name('bimbingan-offline.reject')->middleware('isProdi');
    Route::post('/bimbingan/cancel/acc', [KPBimbinganController::class, 'cancelAcc'])->name('bimbingan.cancel.acc')->middleware('isDosen');
    Route::post('/bimbingan/cancel/revisi', [KPBimbinganController::class, 'cancelRevisi'])->name('bimbingan.cancel.revisi')->middleware('isDosen');
    Route::get('/bimbingan/review-prodi/{id}', [KPBimbinganController::class, 'reviewProdi'])->name('bimbingan.review.prodi')->middleware('isProdi');
    Route::get('/bimbingan/review-admin/{id}', [KPBimbinganController::class, 'reviewAdmin'])->name('bimbingan.review.admin')->middleware('isAdmin');
    Route::get('/bimbingan/rekap-dosen', [KPBimbinganController::class, 'rekapDosen'])->name('bimbingan.rekap.dosen')->middleware('isProdi');
    Route::get('/bimbingan-admin', [KPBimbinganController::class, 'bimbinganAdmin'])->name('bimbingan.admin')->middleware('isAdmin');
    Route::get('/bimbingan/input-admin', [KPBimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.admin.input')->middleware('isAdmin');
    Route::get('/bimbingan/input-prodi', [KPBimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.prodi.input')->middleware('isProdi');
    Route::get('/bimbingan/input/{dosen_id}/{mahasiswa_id}', [KPBimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.admin.input.create')->middleware('isAdminProdi');
    Route::get('/bimbingan/input-prodi/{dosen_id}/{mahasiswa_id}', [KPBimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.prodi.input.create')->middleware('isProdi');
    Route::post('/bimbingan/input/store', [KPBimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.admin.input.store')->middleware('isAdminProdi');
    Route::post('/bimbingan/acc-prodi', [KPBimbinganController::class, 'accBimbinganProdi'])->name('bimbingan.acc.prodi')->middleware('isAdminProdi');
    Route::post('/bimbingan/revisi-prodi', [KPBimbinganController::class, 'revisiBimbinganProdi'])->name('bimbingan.revisi.prodi')->middleware('isAdminProdi');

    // ==================== BIMBINGAN MANUAL KP BARU ====================
    // Mahasiswa - Bimbingan Manual
    Route::get('/bimbingan-manual/create/{bimbingan_id}', [BimbinganManualController::class, 'create'])->name('bimbingan-manual.create')->middleware('isMahasiswa');
    Route::post('/bimbingan-manual/store', [BimbinganManualController::class, 'store'])->name('bimbingan-manual.store')->middleware('isMahasiswa');
    Route::get('/bimbingan-manual/detail/{id}', [BimbinganManualController::class, 'detail'])->name('bimbingan-manual.detail')->middleware('isMahasiswa');

    // Admin/Prodi - Review Bimbingan Manual
    Route::group(['middleware' => 'isAdminProdi'], function(){
        Route::get('/bimbingan-manual/review', [BimbinganManualController::class, 'reviewIndex'])->name('bimbingan-manual.review');
        Route::get('/bimbingan-manual/review/{id}', [BimbinganManualController::class, 'reviewDetail'])->name('bimbingan-manual.review.detail');
        Route::post('/bimbingan-manual/acc', [BimbinganManualController::class, 'acc'])->name('bimbingan-manual.acc');
        Route::post('/bimbingan-manual/revisi', [BimbinganManualController::class, 'revisi'])->name('bimbingan-manual.revisi');
        Route::post('/bimbingan-manual/acc-prodi', [BimbinganManualController::class, 'acc'])->name('bimbingan-manual.acc.prodi'); // Alias
        Route::post('/bimbingan-manual/cancel-acc', [BimbinganManualController::class, 'cancelAcc'])->name('bimbingan-manual.cancel-acc');
    });
    // ==================================================================

    // Bimbingan TA Admin (Integrasi Interface)
    Route::get('/bimbingan/input', [KPAdminController::class, 'bimbinganInput'])->name('bimbingan.admin.input.ta')->middleware('isAdmin');
    Route::get('/bimbingan/input-ta/{dosen_id}/{mahasiswa_id}', [KPAdminController::class, 'bimbinganInputCreate'])->name('bimbingan.admin.input.create.ta')->middleware('isAdmin');
    Route::post('/bimbingan/input-ta/store', [KPAdminController::class, 'bimbinganInputStore'])->name('bimbingan.admin.input.store.ta')->middleware('isAdmin');

    // Seminar KP
    Route::get('/seminar/review/{id}', [KPSeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin')->middleware('isAdminProdi');
    Route::get('/seminar/rekap', [KPSeminarController::class,'rekapSeminar'])->name('seminar.rekap')->middleware('isAdminProdi');

    // Mahasiswa - Seminar KP
    Route::group(['middleware' => 'isMahasiswa'], function(){
        Route::get('/seminar-mahasiswa', [KPSeminarController::class, 'seminarMahasiswa'])->name('seminar.mahasiswa');
        Route::get('/seminar/create', [KPSeminarController::class, 'create'])->name('seminar.create');
        Route::get('/seminar/edit/{id}', [KPSeminarController::class, 'edit'])->name('seminar.edit');
        Route::get('/seminar/detail/{id}', [KPSeminarController::class, 'detail'])->name('seminar.detail');
        Route::get('/seminar/reviews/{id}', [KPSeminarController::class, 'seminarReviews'])->name('seminar.reviews');
        Route::post('/seminar/store', [KPSeminarController::class, 'store'])->name('seminar.store');
        Route::put('/seminar/update/{id}', [KPSeminarController::class, 'update'])->name('seminar.update');
        Route::get('/seminar/edit/proposal/{id}', [KPSeminarController::class, 'editProposal'])->name('seminar.edit.proposal');
        Route::put('/seminar/update/proposal/{id}', [KPSeminarController::class, 'updateProposal'])->name('seminar.update.proposal');
        Route::post('/seminar/delete', [KPSeminarController::class, 'delete'])->name('seminar.delete');
        Route::post('/seminar/upload-nilai-instansi/{id}', [KPSeminarController::class, 'uploadNilaiInstansi'])->name('seminar.upload.nilai.instansi');
        Route::get('/review/seminar/edit/{id}', [KPReviewSeminarController::class, 'edit'])->name('review.seminar.edit');
        Route::post('/review/seminar/update', [KPReviewSeminarController::class, 'update'])->name('review.seminar.update');
        Route::get('/review/seminar/submit-acc-manual/{id}', [KPReviewSeminarController::class, 'submitManual'])->name('review.seminar.submit.acc.manual');
        Route::put('/review/seminar/submit-acc-manual/{id}', [KPReviewSeminarController::class, 'submitManualStore'])->name('review.seminar.submit.acc.manual.store');
    });

    // Admin - Seminar KP
    Route::group(['middleware' => 'isAdmin'], function(){
        Route::get('/seminar-admin', [KPSeminarController::class,'seminarAdmin'])->name('seminar.admin');
        Route::get('/bimbingan/canceled/{id}', [KPBimbinganController::class, 'bimbinganCanceled'])->name('bimbingan.canceled');

        // Admin Account Settings
        Route::get('/admin/account', [KPAdminController::class, 'account'])->name('admin.account');
        Route::put('/admin/account/{id}', [KPAdminController::class, 'accountUpdate'])->name('admin.account.update');

        // Himpunan Master Data
        Route::get('/himpunans', [HimpunanMasterController::class, 'index'])->name('himpunans');
        Route::post('/himpunan/store', [HimpunanMasterController::class, 'store'])->name('himpunan.store');
        Route::post('/himpunan/update', [HimpunanMasterController::class, 'update'])->name('himpunan.update');
        Route::post('/himpunan/delete', [HimpunanMasterController::class, 'delete'])->name('himpunan.delete');
        Route::post('/himpunan/import', [HimpunanMasterController::class, 'import'])->name('himpunan.import');
        // Route update payment dihapus karena pengaturan pembayaran sudah ada di role Himpunan
    });

    // Dosen - Seminar KP
    Route::group(['middleware' => 'isDosen'], function(){
        Route::get('/seminar-dosen', [KPSeminarController::class,'seminarDosen'])->name('seminar.dosen');
        Route::get('/review/seminar/{id}', [KPReviewSeminarController::class, 'reviewDosen'])->name('review.seminar.dosen');
        Route::post('/review/seminar/revisi/store', [KPReviewSeminarController::class, 'revisiStore'])->name('review.seminar.revisi.store');
        Route::post('/review/seminar/revisi/delete', [KPReviewSeminarController::class, 'revisiDelete'])->name('review.seminar.revisi.delete');
        Route::post('/review/seminar/acc', [KPReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc');
        Route::post('/review/seminar/nilai', [KPReviewSeminarController::class, 'reviewNilai'])->name('review.seminar.nilai');
        Route::post('/review/seminar/cancel/acc', [KPReviewSeminarController::class, 'reviewCancelAcc'])->name('review.seminar.cancel.acc');

        // Penilaian Pembimbing KP
        Route::get('/penilaian-pembimbing', [PenilaianPembimbingController::class, 'index'])->name('penilaian.pembimbing.index');
        Route::post('/penilaian-pembimbing/store', [PenilaianPembimbingController::class, 'store'])->name('penilaian.pembimbing.store');
    });

    // Prodi - Seminar KP
    Route::group(['middleware' => 'isProdi'], function(){
        Route::get('/seminar-prodi', [KPSeminarController::class, 'seminarProdi'])->name('seminar.prodi');
        Route::get('/seminar/detail-prodi/{id}', [KPSeminarController::class, 'seminarProdiDetail'])->name('seminar.prodi.detail');
        Route::post('/review/seminar/acc-prodi', [KPReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc.prodi');
        Route::post('/seminar/update-status', [KPSeminarController::class, 'updateStatus'])->name('seminar.update.status');
        Route::post('/review/seminar/update-nilai', [KPReviewSeminarController::class, 'updateNilai'])->name('review.seminar.update.nilai');

        // Penilaian Seminar KP (mirip dengan TA)
        Route::get('/seminar/penilaian/{id}', [PenilaianSeminarController::class, 'detailPenilaian'])->name('seminar.penilaian');
        Route::post('/seminar/penilaian/update-nilai', [PenilaianSeminarController::class, 'updateNilai'])->name('seminar.penilaian.update.nilai');
        Route::post('/seminar/penilaian/update-nilai-komponen', [PenilaianSeminarController::class, 'updateNilaiKomponen'])->name('seminar.penilaian.update.nilai.komponen');
        Route::post('/seminar/penilaian/update-nilai-instansi', [PenilaianSeminarController::class, 'updateNilaiInstansi'])->name('seminar.penilaian.update.nilai.instansi');
        Route::post('/seminar/penilaian/update-status-lulus', [PenilaianSeminarController::class, 'updateStatusLulus'])->name('seminar.penilaian.update.status.lulus');
    });

    // Pengumpulan Akhir KP
    Route::get('/pengumpulan-akhir',[PengumpulanAkhirController::class, 'index'])->name('pengumpulan-akhir.index')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir/detail/{id}',[PengumpulanAkhirController::class, 'detail'])->name('pengumpulan-akhir.detail')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir/detail-mahasiswa/{id}',[PengumpulanAkhirController::class, 'detailMahasiswa'])->name('pengumpulan-akhir.detail.mahasiswa')->middleware('isMahasiswa');
    Route::put('/pengumpulan-akhir/acc/{id}',[PengumpulanAkhirController::class, 'acc'])->name('pengumpulan-akhir.acc')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir-mahasiswa',[PengumpulanAkhirController::class, 'mahasiswaIndex'])->name('pengumpulan-akhir.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/create',[PengumpulanAkhirController::class, 'create'])->name('pengumpulan-akhir.create')->middleware('isMahasiswa');
    Route::post('/pengumpulan-akhir/store',[PengumpulanAkhirController::class, 'store'])->name('pengumpulan-akhir.store')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/edit/{id}',[PengumpulanAkhirController::class, 'edit'])->name('pengumpulan-akhir.edit')->middleware('isMahasiswa');
    Route::put('/pengumpulan-akhir/update/{id}',[PengumpulanAkhirController::class, 'update'])->name('pengumpulan-akhir.update')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/confirm-completed/{id}',[PengumpulanAkhirController::class, 'confirmCompleted'])->name('pengumpulan-akhir.confirm.completed')->middleware('isAdmin');

    // Pengumpulan Akhir KP (Prodi)
    Route::get('/pengumpulan-akhir-prodi', [PengumpulanAkhirController::class, 'indexProdi'])->name('pengumpulan-akhir.prodi.index')->middleware('isProdi');
    Route::get('/pengumpulan-akhir-prodi/detail/{id}', [PengumpulanAkhirController::class, 'detailProdi'])->name('pengumpulan-akhir.prodi.detail')->middleware('isProdi');
    Route::put('/pengumpulan-akhir-prodi/update-nilai/{id}', [PengumpulanAkhirController::class, 'updateNilai'])->name('pengumpulan-akhir.prodi.update-nilai')->middleware('isProdi');

    // Pengumpulan Akhir TA - Detail dokumen untuk Admin dan Prodi
    Route::get('/pengumpulan-akhir-ta/detail/{id}', [PengumpulanAkhirController::class, 'detailTA'])->name('pengumpulan-akhir.detail.ta')->middleware('isAdmin');
    Route::get('/pengumpulan-akhir-ta-prodi/detail/{id}', [PengumpulanAkhirController::class, 'detailProdiTA'])->name('pengumpulan-akhir.prodi.detail.ta')->middleware('isProdi');

    // Cetak Dokumen KP
    Route::group(['middleware' => 'isLogin'], function (){
        Route::get('/cetak/lembar-persetujuan-pembimbing-mahasiswa', [KPCetakController::class, 'cetakLembarPersetujuanMahasiswa'])->name('cetak.lembar.persetujuan.mahasiswa');
        Route::get('/cetak/lembar-pernyataan-keaslian', [KPCetakController::class, 'cetakLembarPernyataanKeaslian'])->name('cetak.lembar.pernyataan.keaslian');
        Route::get('/cetak/surat-tugas-bimbingan', [KPCetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
        Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [KPCetakController::class, 'cetakSuratTugasBimbingan'])->name('cetak.surat.tugas.bimbingan.pendaftaran');
        Route::get('/cetak/berita-acara-seminar-kp/{seminar}', [KPCetakController::class, 'cetakBeritaAcaraUjianProposal'])->name('cetak.berita.acara.ujian.proposal');
        Route::get('/cetak/berita-acara-seminar-kp-blank/{ujian_or_seminar}/{type}', [KPCetakController::class, 'cetakBeritaAcaraUjianProposalBlank'])->name('cetak.berita.acara.ujian.proposal.blank');
        Route::get('/cetak/surat-penolakan/{pengajuan}', [KPCetakController::class, 'cetakSuratPenolakan'])->name('cetak.surat.penolakan');
        Route::get('/cetak/surat-riwayat-bimbingan-mahasiswa', [KPCetakController::class, 'cetakRiwayatBimbinganMahasiswa'])->name('cetak.riwayat.bimbingan.mahasiswa');
        Route::get('/cetak/surat-riwayat-bimbingan/{id}', [KPCetakController::class, 'cetakRiwayatBimbingan'])->name('cetak.riwayat.bimbingan');
        Route::get('/cetak/lembar-persetujuan/{type}', [KPCetakController::class, 'cetakLembarPersetujuan'])->name('cetak.lembar.persetujuan');
        Route::get('/cetak/lembar-pengesahan', [KPCetakController::class, 'cetakLembarPengesahan'])->name('cetak.lembar.pengesahan');
        Route::get('/cetak/formulir-nilai-akhir', [KPCetakController::class, 'cetakFormulirNilaiAkhir'])->name('cetak.formulir.nilai.akhir');
        Route::get('/cetak/berita-acara-serah-terima', [KPCetakController::class, 'cetakBeritaAcaraSerahTerima'])->name('cetak.berita.acara.serah.terima');
    });

    // Bagian Bimbingan KP (Admin)
    Route::post('/bagian/store', [KPBagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
    Route::post('/bagian/update', [KPBagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
    Route::post('/bagian/delete', [KPBagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');
    Route::post('/bagian/import', [KPBagianController::class, 'import'])->name('bagian.import')->middleware('isAdmin');
    Route::post('/bagian/active', [KPBagianController::class, 'bagianActive'])->name('bagian.active')->middleware('isAdmin');
    Route::get('/bagian/up/{id}', [KPBagianController::class, 'up'])->name('bagian.up')->middleware('isAdmin');
    Route::get('/bagian/down/{id}', [KPBagianController::class, 'down'])->name('bagian.down')->middleware('isAdmin');

    // Master Data KP (Admin) - Integrasi TA + KP
    Route::get('/prodi', [KPProdiController::class, 'index'])->name('prodi')->middleware('isAdmin');
    Route::get('/prodi/{id}', [KPProdiController::class, 'detail'])->name('prodi.detail')->middleware('isAdmin');
    Route::post('/prodi/store', [KPProdiController::class, 'store'])->name('prodi.store')->middleware('isAdmin');
    Route::post('/prodi/import', [KPProdiController::class, 'import'])->name('prodi.import')->middleware('isAdmin');
    Route::get('/prodi/presentase-nilai/{id}', [KPProdiController::class, 'presentaseNilai'])->name('prodi.presentase.nilai')->middleware('isAdmin');
    Route::post('/prodi/presentase-nilai/store', [KPProdiController::class, 'presentaseNilaiStore'])->name('prodi.presentase.nilai.store')->middleware('isAdmin');

    Route::get('/mahasiswa', [KPMahasiswaController::class, 'index'])->name('mahasiswa')->middleware('isAdmin');
    Route::post('/mahasiswa/store', [KPMahasiswaController::class, 'store'])->name('mahasiswa.store')->middleware('isAdmin');
    Route::post('/mahasiswa/import', [KPMahasiswaController::class, 'import'])->name('mahasiswa.import')->middleware('isAdmin');
    Route::post('/mahasiswa/detail/import', [KPMahasiswaController::class, 'importDetail'])->name('mahasiswa.detail.import')->middleware('isAdmin');
    // ponytail: reset-password sudah didefinisikan di line 448 (KPMahasiswaController2 alias)

    Route::get('/dosen', [KPDosenController::class, 'index'])->name('dosen')->middleware('isAdmin');
    Route::get('/dosen/{id}', [KPDosenController::class, 'edit'])->name('dosen.edit')->middleware('isAdmin');
    Route::post('/dosen/store', [KPDosenController::class, 'store'])->name('dosen.store')->middleware('isAdmin');
    Route::put('/dosen/{id}', [KPDosenController::class, 'update'])->name('dosen.update')->middleware('isAdmin');
    Route::post('/dosen/import', [KPDosenController::class, 'import'])->name('dosen.import')->middleware('isAdmin');
    // ponytail: reset-password sudah didefinisikan di line 453 (KPDosenController2 alias)
    Route::get('/dosen/change-manual/{id}', [KPDosenController::class, 'changeManual'])->name('dosen.change.manual')->middleware('isAdmin');

    Route::get('/fakultas', [KPFakultasController::class, 'index'])->name('fakultas')->middleware('isAdmin');
    Route::get('/fakultas/setting/{fakultas}', [KPFakultasController::class, 'setting'])->name('fakultas.setting')->middleware('isAdmin');
    Route::post('/fakultas/store', [KPFakultasController::class, 'store'])->name('fakultas.store')->middleware('isAdmin');
    Route::post('/fakultas/import', [KPFakultasController::class, 'import'])->name('fakultas.import')->middleware('isAdmin');
    Route::post('/fakultas/update', [KPFakultasController::class, 'update'])->name('fakultas.update')->middleware('isAdmin');
    Route::post('/fakultas/add/prodi', [KPFakultasController::class, 'addProdi'])->name('fakultas.add.prodi')->middleware('isAdmin');
    Route::post('/fakultas/delete/prodi', [KPFakultasController::class, 'deleteProdi'])->name('fakultas.delete.prodi')->middleware('isAdmin');

    // ponytail: reset-password sudah didefinisikan di line 443 (KPProdiController2 alias)

    // Public KP
    Route::get('/public/riwayat-bimbingan/{id}',[KPBimbinganController::class, 'public'])->name('bimbingan.public');
    Route::get('/public/review-seminar/{token}', [KPReviewSeminarController::class, 'reviewPublic'])->name('review.seminar.public');
    Route::post('/public/review-seminar/{token}', [KPReviewSeminarController::class, 'storePublic'])->name('review.seminar.public.store');

    // Penilaian Seminar KP (Public)
    Route::get('/penilaian-seminar/{token}', [PenilaianSeminarController::class, 'index'])->name('penilaian.seminar');
    Route::post('/penilaian-seminar/{token}/submit', [PenilaianSeminarController::class, 'submit'])->name('penilaian.seminar.submit');
});

// ============================================
// UTILITY ROUTES (untuk hosting tanpa SSH)
// ============================================

// Route 1: Buat storage:link via browser (tanpa SSH)
// Akses: /setup/storage-link
Route::get('/setup/storage-link', function () {
    $results = [];
    $links = config('filesystems.links', []);

    if (empty($links)) {
        $results[] = '❌ filesystems.links kosong, tidak ada link yang bisa dibuat.';
        return '<pre style="font-size:14px;padding:20px;">' . implode("\n", $results) . '</pre>';
    }

    $results[] = '=== KONFIGURASI LINK ===';
    foreach ($links as $link => $target) {
        $results[] = '';
        $results[] = 'Link name: ' . basename($link);
        $results[] = 'Link path: ' . $link;
        $results[] = 'Target path: ' . $target;
        $results[] = 'Target exists: ' . (file_exists($target) ? 'YES' : 'NO');
        $results[] = 'Link exists: ' . (file_exists($link) ? 'YES' : 'NO');
    }

    $results[] = '';
    $results[] = '=== COBA ARTISAN storage:link ===';
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $artisanOutput = trim(\Illuminate\Support\Facades\Artisan::output());
        $results[] = $artisanOutput !== '' ? $artisanOutput : 'Artisan dijalankan tanpa output.';
    } catch (\Throwable $e) {
        $results[] = 'Artisan gagal: ' . $e->getMessage();
    }

    $results[] = '';
    $results[] = '=== VERIFIKASI / FALLBACK MANUAL ===';
    foreach ($links as $link => $target) {
        $linkName = basename($link);

        if (file_exists($link) && is_link($link)) {
            $results[] = '✅ ' . $linkName . ' sudah aktif -> ' . readlink($link);
            continue;
        }

        if (file_exists($link) && !is_link($link)) {
            $results[] = '⚠️ ' . $linkName . ' ada tapi bukan symlink/folder biasa.';
            continue;
        }

        if (!file_exists($target)) {
            $results[] = '❌ Target ' . $linkName . ' tidak ditemukan: ' . $target;
            continue;
        }

        if (@symlink($target, $link)) {
            $results[] = '✅ Symlink manual berhasil dibuat untuk ' . $linkName;
        } else {
            $error = error_get_last();
            $results[] = '❌ Symlink manual gagal untuk ' . $linkName . ': ' . ($error['message'] ?? 'Unknown error');
        }
    }

    $results[] = '';
    $results[] = '=== HASIL AKHIR ===';
    foreach ($links as $link => $target) {
        $linkName = basename($link);
        if (file_exists($link) && is_link($link)) {
            $results[] = '✅ ' . $linkName . ' -> ' . readlink($link);
        } elseif (file_exists($link)) {
            $results[] = '⚠️ ' . $linkName . ' ada tapi bukan symlink.';
        } else {
            $results[] = '❌ ' . $linkName . ' belum berhasil dibuat.';
        }
    }

    return '<pre style="font-size:14px;padding:20px;">' . implode("\n", $results) . '</pre>';
});

// Route 2: Diagnostik stempel & image - cek apakah file bisa ditemukan
// Akses: /setup/check-images
Route::get('/setup/check-images', function () {
    $results = [];

    // Cek fakultas stempel
    $fakultas = \App\Models\Fakultas::all();
    foreach ($fakultas as $f) {
        $results[] = '=== Fakultas: ' . $f->namafakultas . ' ===';
        $results[] = 'Image path (DB): ' . ($f->image ?? 'NULL');

        if ($f->image) {
            $needle = 'storage/app/public/';
            $pos = strpos($f->image, $needle);
            if ($pos !== false) {
                $relativePath = substr($f->image, $pos + strlen($needle));
                $absPath = storage_path('app/public/' . $relativePath);
                $results[] = 'Relative path: ' . $relativePath;
                $results[] = 'Absolute path: ' . $absPath;
                $results[] = 'File exists: ' . (file_exists($absPath) ? '✅ YES' : '❌ NO');
            } else {
                $results[] = '⚠️ Path format tidak dikenali (tidak mengandung "storage/app/public/")';
            }

            $base64 = \App\Helpers\AppHelper::instance()->convertStorageImage($f->image);
            $results[] = 'convertStorageImage: ' . ($base64 ? '✅ OK (length: ' . strlen($base64) . ')' : '❌ GAGAL (null)');
        }
        $results[] = '';
    }

    // Cek dekan TTD
    $dekans = \App\Models\Dekan::where(function($q) {
        $q->where('status', 'active')->orWhere('status', '1');
    })->get();
    foreach ($dekans as $d) {
        $results[] = '=== Dekan: ' . $d->namadekan . ' ===';
        $results[] = 'Image path (DB): ' . ($d->image ?? 'NULL');
        if ($d->image) {
            $base64 = \App\Helpers\AppHelper::instance()->convertStorageImage($d->image);
            $results[] = 'convertStorageImage: ' . ($base64 ? '✅ OK (length: ' . strlen($base64) . ')' : '❌ GAGAL (null)');
        }
        $results[] = '';
    }

    return '<pre style="font-size:14px;padding:20px;">' . implode("\n", $results) . '</pre>';
});

// ========================================
// LAMPIRAN FILE BY FILENAME (CATCH-ALL)
// ========================================
// Route ini HARUS di paling bawah supaya tidak bentrok dengan route lain.
// Hanya match URL yang berakhiran ekstensi file (pdf, doc, docx, xls, xlsx, dll).
// Contoh: /abc123random.pdf -> StorageController cari di lampirans/ta/ dan lampirans/kp/
Route::get('/{filename}', [App\Http\Controllers\StorageController::class, 'serveByFilename'])
    ->where('filename', '.*\.(pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png|gif|zip|rar|7z)$');
