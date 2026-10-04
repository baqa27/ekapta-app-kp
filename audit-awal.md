# Laporan audit proyek Laravel

Folder: `D:\ekapta final\ekapta-app-new`

Route terbaca: 473 (bernama: 446, closure: 27), alias middleware: 21

Temuan: **21 ERROR**, 17 WARN, 5 INFO

## ERROR (21)

- **[import]** use App\Enums\EkaptaContext; menunjuk class yang tidak ada (`app/Enums/EkaptaContext.php`)
    - `app/Helpers/ContextHelper.php:4`
- **[import]** use App\Models\DosenMahasiswa; menunjuk class yang tidak ada (`app/Models/DosenMahasiswa.php`)
    - `app/Policies/DosenMahasiswaPolicy.php:4`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\BagianController::down()` (BadMethodCallException)
    - `routes/web.php:277  GET /bagian/down/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\CetakController::cetakRiwayatBimbingan()` (BadMethodCallException)
    - `routes/web.php:544  GET /cetak/surat-riwayat-bimbingan/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\AdminController::himpunans()` (BadMethodCallException)
    - `routes/web.php:507  GET /himpunans`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\CetakController::cetakRiwayatBimbingan()` (BadMethodCallException)
    - `routes/web.php:940  GET /kp/cetak/surat-riwayat-bimbingan/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\FakultasController::store()` (BadMethodCallException)
    - `routes/web.php:456  POST /fakultas/store`
    - `routes/web.php:980  POST /kp/fakultas/store`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::detailPenilaian()` (BadMethodCallException)
    - `routes/web.php:902  GET /kp/seminar/penilaian/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilai()` (BadMethodCallException)
    - `routes/web.php:903  POST /kp/seminar/penilaian/update-nilai`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilaiInstansi()` (BadMethodCallException)
    - `routes/web.php:905  POST /kp/seminar/penilaian/update-nilai-instansi`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateNilaiKomponen()` (BadMethodCallException)
    - `routes/web.php:904  POST /kp/seminar/penilaian/update-nilai-komponen`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\PenilaianSeminarController::updateStatusLulus()` (BadMethodCallException)
    - `routes/web.php:906  POST /kp/seminar/penilaian/update-status-lulus`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\KP\SeminarController::uploadRevisi()` (BadMethodCallException)
    - `routes/web.php:852  POST /kp/seminar/upload-revisi/{id}`
- **[method]** Route menunjuk ke method yang tidak ada: `App\Http\Controllers\UjianController::delete()` (BadMethodCallException)
    - `routes/web.php:354  DELETE /ujian/delete/{id}`
- **[public-php]** File PHP di folder public selain index.php dapat dijalankan dari browser
    - `public/setup-links.php`
    - `public/test-symlink.php`
- **[route-ganda]** Nama route `dosen.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:453  /dosen/reset-password/{id}`
    - `routes/web.php:614  /dosen/reset-password/{id}`
- **[route-ganda]** Nama route `mahasiswa.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:448  /mahasiswa/reset-password/{id}`
    - `routes/web.php:613  /mahasiswa/reset-password/{id}`
- **[route-ganda]** Nama route `prodi.reset.password` didefinisikan 2 kali dengan tujuan berbeda
    - `routes/web.php:443  /prodi/reset-password/{id}`
    - `routes/web.php:615  /prodi/reset-password/{id}`
- **[route-hilang]** Route [kp.back.dashboard] tidak terdefinisi (RouteNotFoundException). Mirip/terdekat: `back.dashboard`
    - `resources/views/kp/partials/navbar.blade.php:28`
- **[route-hilang]** Route [kp.login] tidak terdefinisi (RouteNotFoundException). Mirip/terdekat: `login`
    - `resources/views/kp/partials/navbar.blade.php:30`
- **[view]** View [kp.pages.dosen.penilaian.create] tidak ditemukan di resources/views
    - `app/Http/Controllers/KP/PenilaianPembimbingController.php:74`

## WARN (17)

- **[cache]** Cache `packages.php` ada. Route/config yang diubah tidak akan terbaca sampai `php artisan optimize:clear`
    - `bootstrap/cache/packages.php`
- **[cache]** Cache `services.php` ada. Route/config yang diubah tidak akan terbaca sampai `php artisan optimize:clear`
    - `bootstrap/cache/services.php`
- **[class-ganda]** Class `App\Helpers\AppHelper` dideklarasikan di 3 file (rawan "Ambiguous class resolution")
    - `app/Helpers/AppHelper.php, app/Helpers/AppHelper_backup_2.php, app/Helpers/AppHelper_old.php`
- **[class-ganda]** Class `App\Helpers\StorageHelper` dideklarasikan di 2 file (rawan "Ambiguous class resolution")
    - `app/Helpers/StorageHelper.php, app/Helpers/StorageHelper_backup_2.php`
- **[dump]** File dump/arsip di root proyek; pastikan tidak ikut terupload ke hosting
    - `CLEANUP-DUPLICATES.sql`
- **[env]** APP_DEBUG=true (APP_ENV=local); di server produksi harus APP_DEBUG=false dan APP_ENV=production
    - `.env.example`
- **[file-sisa]** 26 file/folder backup atau bernama janggal di dalam kode aplikasi (tidak dipakai, menyulitkan audit)
    - `app/Helpers/AppHelper_backup_2.php, app/Helpers/StorageHelper.php.backup, app/Helpers/StorageHelper_backup_2.php, app/Http/Controllers/CetakController .php old, app/Http/Controllers/DashboardController.php.old, app/Http/Controllers/JilidController.php.backup`
- **[import]** use App\Models\Mail; menunjuk class yang tidak ada (`app/Models/Mail.php`) (tidak dipakai di file ini)
    - `app/Helpers/AppHelper_old.php:14`
- **[psr4]** File mendeklarasikan `App\Helpers\AppHelper` tetapi path mengharapkan `App\Helpers\AppHelper_backup_2` (tampak seperti file backup)
    - `app/Helpers/AppHelper_backup_2.php`
- **[psr4]** File mendeklarasikan `App\Helpers\AppHelper` tetapi path mengharapkan `App\Helpers\AppHelper_old` (tampak seperti file backup)
    - `app/Helpers/AppHelper_old.php`
- **[psr4]** File mendeklarasikan `App\Helpers\StorageHelper` tetapi path mengharapkan `App\Helpers\StorageHelper_backup_2` (tampak seperti file backup)
    - `app/Helpers/StorageHelper_backup_2.php`
- **[route-ganda]** Nama route `cetak.berita.acara.ujian.pendadaran` didefinisikan 2 kali
    - `routes/web.php:541  /cetak/berita-acara-ujian-pendadaran/{ujian}`
    - `routes/web.php:542  /cetak/berita-acara-ujian-pendadaran/{ujian}`
- **[uri-ganda]** URI sama `GET /cetak/berita-acara-ujian-pendadaran/{ujian}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:542  (pertama di baris 541)`
- **[uri-ganda]** URI sama `GET /dosen/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:614  (pertama di baris 453)`
- **[uri-ganda]** URI sama `GET /mahasiswa/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:613  (pertama di baris 448)`
- **[uri-ganda]** URI sama `GET /prodi/reset-password/{id}` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:615  (pertama di baris 443)`
- **[uri-ganda]** URI sama `POST /bimbingan/store` terdaftar lebih dari sekali (yang pertama menang)
    - `routes/web.php:308  (pertama di baris 285)`

## INFO (5)

- **[closure]** Ada 27 route berbentuk Closure; `php artisan route:cache` tidak bisa dipakai
- **[controller-tanpa-route]** Controller tidak dipakai oleh route mana pun (belum terhubung atau sudah usang)
    - `app/Http/Controllers/FakultasController.php`
    - `app/Http/Controllers/HimpunanMasterController.php`
    - `app/Http/Controllers/KP/DekanController.php`
    - `app/Http/Controllers/KP/DosenProdiController.php`
    - `app/Http/Controllers/KP/HomeController.php`
    - `app/Http/Controllers/KP/LoginSelectorController.php`
- **[env]** APP_DEBUG=true (APP_ENV=local); di server produksi harus APP_DEBUG=false dan APP_ENV=production
    - `.env`
- **[method-tanpa-route]** 323 method publik di controller yang dipakai tidak punya route (jalankan lagi dengan --orphans untuk daftar)
- **[route-hilang]** Route [register] tidak terdefinisi, tetapi view ini tidak pernah dipanggil dari mana pun (kemungkinan view bawaan/sisa)
    - `resources/views/welcome.blade.php:33`
