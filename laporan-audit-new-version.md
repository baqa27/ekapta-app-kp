# Laporan audit proyek Laravel

Folder: `new-version (6eaa784)`

Route terbaca: 478 (bernama: 451, closure: 27), alias middleware: 21

Temuan: **23 ERROR**, 11 WARN, 4 INFO

## ERROR (23)

- **[import]** use App\Enums\EkaptaContext; menunjuk class yang tidak ada (`app/Enums/EkaptaContext.php`)
    - `app/Helpers/ContextHelper.php:4`
- **[import]** use App\Models\DosenMahasiswa; menunjuk class yang tidak ada (`app/Models/DosenMahasiswa.php`)
    - `app/Policies/DosenMahasiswaPolicy.php:4`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\BagianController::down()` (BadMethodCallException)
    - `routes/web.php:278  GET /bagian/down/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\CetakController::cetakRiwayatBimbingan()` (BadMethodCallException)
    - `routes/web.php:547  GET /cetak/surat-riwayat-bimbingan/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\AdminController::himpunans()` (BadMethodCallException)
    - `routes/web.php:510  GET /himpunans`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\CetakController::cetakRiwayatBimbingan()` (BadMethodCallException)
    - `routes/web.php:946  GET /kp/cetak/surat-riwayat-bimbingan/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\FakultasController::store()` (BadMethodCallException)
    - `routes/web.php:459  POST /fakultas/store`
    - `routes/web.php:986  POST /kp/fakultas/store`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PengumpulanAkhirController::detailProdiTA()` (BadMethodCallException)
    - `routes/web.php:934  GET /kp/pengumpulan-akhir-ta-prodi/detail/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PengumpulanAkhirController::detailTA()` (BadMethodCallException)
    - `routes/web.php:933  GET /kp/pengumpulan-akhir-ta/detail/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::detailPenilaian()` (BadMethodCallException)
    - `routes/web.php:908  GET /kp/seminar/penilaian/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilai()` (BadMethodCallException)
    - `routes/web.php:909  POST /kp/seminar/penilaian/update-nilai`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilaiInstansi()` (BadMethodCallException)
    - `routes/web.php:911  POST /kp/seminar/penilaian/update-nilai-instansi`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilaiKomponen()` (BadMethodCallException)
    - `routes/web.php:910  POST /kp/seminar/penilaian/update-nilai-komponen`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateStatusLulus()` (BadMethodCallException)
    - `routes/web.php:912  POST /kp/seminar/penilaian/update-status-lulus`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\SeminarController::uploadRevisi()` (BadMethodCallException)
    - `routes/web.php:858  POST /kp/seminar/upload-revisi/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\UjianController::delete()` (BadMethodCallException)
    - `routes/web.php:357  DELETE /ujian/delete/{id}`
- **[public-php]** File PHP di folder public selain index.php dapat dijalankan dari browser
    - `public/setup-links.php`
- **[route-ganda]** Nama route `dosen.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:456  /dosen/reset-password/{id}`
    - `routes/web.php:617  /dosen/reset-password/{id}`
- **[route-ganda]** Nama route `mahasiswa.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:451  /mahasiswa/reset-password/{id}`
    - `routes/web.php:616  /mahasiswa/reset-password/{id}`
- **[route-ganda]** Nama route `prodi.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:446  /prodi/reset-password/{id}`
    - `routes/web.php:618  /prodi/reset-password/{id}`
- **[route-hilang]** Route [kp.back.dashboard] tidak terdefinisi (RouteNotFoundException). Mirip/terdekat: `back.dashboard`
    - `resources/views/kp/partials/navbar.blade.php:28`
- **[route-hilang]** Route [kp.login] tidak terdefinisi (RouteNotFoundException). Mirip/terdekat: `login`
    - `resources/views/kp/partials/navbar.blade.php:30`
- **[view]** View [kp.pages.dosen.penilaian.create] tidak ditemukan di resources/views
    - `app/Http/Controllers/KP/PenilaianPembimbingController.php:74`

## WARN (11)

- **[class-ganda]** Class `App\Helpers\AppHelper` dideklarasikan di 2 file (rawan "Ambiguous class resolution")
    - `app/Helpers/AppHelper.php, app/Helpers/AppHelper_old.php`
- **[dump]** File dump/arsip di root proyek; pastikan tidak ikut terupload ke hosting
    - `unsiq_ekapta_new (3).sql`
- **[file-sisa]** 36 file/folder backup atau bernama janggal di dalam kode aplikasi (tidak dipakai, menyulitkan audit)
    - `unsiq_ekapta_new (3).sql, config/filesystems.php.backup, public/lampirans old, public/images backup, public/lampirans backup, app/Exceptions/Handler.php old`
- **[import]** use App\Models\Mail; menunjuk class yang tidak ada (`app/Models/Mail.php`) (tidak dipakai di file ini)
    - `app/Helpers/AppHelper_old.php:14`
- **[psr4]** File mendeklarasikan `App\Helpers\AppHelper` tetapi path mengharapkan `App\Helpers\AppHelper_old` (tampak seperti file backup)
    - `app/Helpers/AppHelper_old.php`
- **[route-ganda]** Nama route `cetak.berita.acara.ujian.pendadaran` didefinisikan 2 kali
    - `routes/web.php:544  /cetak/berita-acara-ujian-pendadaran/{ujian}`
    - `routes/web.php:545  /cetak/berita-acara-ujian-pendadaran/{ujian}`
- **[uri-ganda]** URI sama `GET /cetak/berita-acara-ujian-pendadaran/{ujian}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:545  (pertama di baris 544)`
- **[uri-ganda]** URI sama `GET /dosen/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:617  (pertama di baris 456)`
- **[uri-ganda]** URI sama `GET /mahasiswa/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:616  (pertama di baris 451)`
- **[uri-ganda]** URI sama `GET /prodi/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:618  (pertama di baris 446)`
- **[uri-ganda]** URI sama `POST /bimbingan/store` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:310  (pertama di baris 286)`

## INFO (4)

- **[closure]** Ada 27 route berbentuk Closure; `php artisan route:cache` tidak bisa dipakai
- **[controller-tanpa-route]** Controller tidak dipakai oleh route mana pun (belum terhubung atau sudah usang)
    - `app/Http/Controllers/FakultasController.php`
    - `app/Http/Controllers/HimpunanMasterController.php`
    - `app/Http/Controllers/KP/DekanController.php`
    - `app/Http/Controllers/KP/DosenProdiController.php`
    - `app/Http/Controllers/KP/HomeController.php`
    - `app/Http/Controllers/KP/LoginSelectorController.php`
- **[method-tanpa-route]** 323 method publik di controller yang dipakai tidak punya route (jalankan lagi dengan --orphans untuk daftar)
- **[route-hilang]** Route [register] tidak terdefinisi, tetapi view ini tidak pernah dipanggil dari mana pun (kemungkinan view bawaan/sisa)
    - `resources/views/welcome.blade.php:33`
