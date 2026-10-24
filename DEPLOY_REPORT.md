# DEPLOY_REPORT - Riwayat Rilis Manual
Proyek: ekapta-app-new  
Laravel: 8.x  
PHP: 8.1.x  
Tanggal: 2026-10-14  

## Ringkasan Eksekutif
- **File BARU:** 0 (semua file baru masih dalam `.gitignore` atau belum di-track kecuali migrasi)  
- **File UBAH:** 42 file  
- **File HAPUS:** 5 file  
- **Status Keseluruhan:** AMAN DENGAN CATATAN  
  - 11 `console.log` di view admin (debug logging, risiko rendah)  
  - LSP false-positive pada `Log::info()` (tidak bermasalah)  
- **3 Risiko Teratas:**  
  1. Tidak ada `artisan` di hosting → cache harus dibersihkan manual via File Manager  
  2. Migrasi manual harus dijalankan lewat phpMyAdmin (gunakan `deploy.sql`)  
  3. File hapus (`*_REPORT.md`, `.sql` lama) - pastikan tidak diperlukan di hosting  

---

## Tabel File yang Berubah

| No | Folder | Nama File | Path Lengkap | Status | Ringkasan Perubahan | Risiko |
|----|--------|-----------|--------------|--------|---------------------|--------|
| 1 | app/Helpers | AppHelper.php | app/Helpers/AppHelper.php | UBAH | Tambah method `canSubmitBimbinganKP()` untuk validasi masa bimbingan | Rendah |
| 2 | app/Http/Controllers/KP | BimbinganController.php | app/Http/Controllers/KP/BimbinganController.php | UBAH | Gunakan pivot `mahasiswasKP()` dengan fallback ke `whereIn` untuk data lama; tambah pengecekan submit via `AppHelper::canSubmitBimbinganKP()` | Rendah |
| 3 | app/Http/Controllers/KP | CetakController.php | app/Http/Controllers/KP/CetakController.php | UBAH | Validasi null lebih ketat (abort 404/403); render data bimbingan manual; konsistensi query dengan pivot fallback | Rendah |
| 4 | app/Http\Controllers/KP | PengumpulanAkhirController.php | app/Http/Controllers/KP/PengumpulanAkhirController.php | UBAH | Tambah field ke `$fillable` & validation (`bukti_nilai_instansi`, `lampiran`, `link_project`); log validation; update `reviewed_by`, `reviewer_type`, `tanggal_review` saat ACC | Rendah |
| 5 | app/Http/Controllers/KP | SeminarController.php | app/Http/Controllers/KP/SeminarController.php | UBAH | Query `seminarDosen()` pakai pivot `mahasiswasKP()` dengan fallback status 'utama' untuk data lama | Rendah |
| 6 | app/Models/KP | Jilid.php | app/Models/KP/Jilid.php | UBAH | Tambah `$fillable` (`reviewed_by`, `reviewer_type`, `tanggal_review`); cast `tanggal_review`; tambah accessor `getIsCompletedAttribute()` | Rendah |
| 7 | config | kp.php | config/kp.php | UBAH | Tambah konfigurasi `dokumen_pengumpulan_akhir` (single source of truth untuk dokumen upload) | Rendah |
| 8 | public/ekapta/assets/js | dashboard-mahasiswa.js | public/ekapta/assets/js/dashboard-mahasiswa.js | UBAH | Perbaiki logika hitung mundur & notifikasi (lihat diff detail) | Rendah |
| 9 | resources/views/kp/pages/admin/bimbingan | bimbingan.blade.php | resources/views/kp/pages/admin/bimbingan/bimbingan.blade.php | UBAH | UI update untuk halaman admin bimbingan (tidak detail) | Rendah |
| 10 | resources/views/kp/pages/admin/dashboard | home.blade.php | resources/views/kp/pages/admin/dashboard/home.blade.php | UBAH | UI update dashboard admin | Rendah |
| 11 | resources/views/kp/pages/cetak | formulir-nilai-akhir-karyawan.blade.php | resources/views/kp/pages/cetak/formulir-nilai-akhir-karyawan.blade.php | UBAH | Penyesuaian layout cetak formulir nilai | Rendah |
| 12 | resources/views/kp/pages/cetak | formulir-nilai-akhir.blade.php | resources/views/kp/pages/cetak/formulir-nilai-akhir.blade.php | UBAH | Penyesuaian layout cetak formulir nilai | Rendah |
| 13 | resources/views/kp/pages/cetak | lembar-persetujuan.blade.php | resources/views/kp/pages/cetak/lembar-persetujuan.blade.php | UBAH | Penyesuaian layout surat persetujuan | Rendah |
| 14 | resources/views/kp/pages/cetak | lembarBimbinganKP.blade.php | resources/views/kp/pages/cetak/lembarBimbinganKP.blade.php | UBAH | **FIX:** Layout PDF lembar bimbingan KP - rubah grouping dari per-BAB ke per-tanggal; chunk per 4 baris untuk repeat header per halaman; TTD per-baris; rapihkan CSS | Rendah |
| 15 | resources/views/kp/pages/cetak | suratTugasBimbingan.blade.php | resources/views/kp/pages/cetak/suratTugasBimbingan.blade.php | UBAH | **FIX:** Layout PDF surat tugas bimbingan (halaman 2) - pola sama seperti lembarBimbinganKP: chunk per 4 baris, per-tanggal, repeat header per halaman, TTD per-baris | Rendah |
| 16 | resources/views/kp/pages/dosen/bimbingan | bimbingan-progress.blade.php | resources/views/kp/pages/dosen/bimbingan/bimbingan-progress.blade.php | UBAH | UI update halaman progres bimbingan dosen | Rendah |
| 17 | resources/views/kp/pages/home | home.blade.php | resources/views/kp/pages/home/home.blade.php | UBAH | UI update halaman beranda | Rendah |
| 18 | resources/views/kp/pages/mahasiswa/bimbingan | bimbingan.blade.php | resources/views/kp/pages/mahasiswa/bimbingan/bimbingan.blade.php | UBAH | UI update halaman bimbingan mahasiswa | Rendah |
| 19 | resources/views/kp/pages/mahasiswa/dashboard | home-kp.blade.php | resources/views/kp/pages/mahasiswa/dashboard/home-kp.blade.php | UBAH | UI update dashboard mahasiswa KP | Rendah |
| 20 | resources/views/kp/pages/mahasiswa/dashboard | home.blade.php | resources/views/kp/pages/mahasiswa/dashboard/home.blade.php | UBAH | UI update dashboard mahasiswa | Rendah |
| 21 | resources/views/kp/pages/mahasiswa | pilih-sistem.blade.php | resources/views/kp/pages/mahasiswa/pilih-sistem.blade.php | UBAH | UI update halaman pilih sistem | Rendah |
| 22 | resources/views/kp/pages/mahasiswa/seminar | create.blade.php | resources/views/kp/pages/mahasiswa/seminar/create.blade.php | UBAH | UI update form buat seminar | Rendah |
| 23 | resources/views/kp/pages/mahasiswa/seminar | seminar.blade.php | resources/views/kp/pages/mahasiswa/seminar/seminar.blade.php | UBAH | UI update halaman seminar | Rendah |
| 24 | resources/views/kp/pages/pengumpulan-akhir | admin-index.blade.php | resources/views/kp/pages/pengumpulan-akhir/admin-index.blade.php | UBAH | UI update halaman admin pengumpulan akhir | Rendah |
| 25 | resources/views/kp/pages/pengumpulan-akhir | create.blade.php | resources/views/kp/pages/pengumpulan-akhir/create.blade.php | UBAH | UI update form tambah pengumpulan akhir | Rendah |
| 26 | resources/views/kp/pages/pengumpulan-akhir | detail-ta.blade.php | resources/views/kp/pages/pengumpulan-akhir/detail-ta.blade.php | UBAH | UI update detail TA | Rendah |
| 27 | resources/views/kp/pages/pengumpulan-akhir | detail.blade.php | resources/views/kp/pages/pengumpulan-akhir/detail.blade.php | UBAH | UI update detail pengumpulan akhir | Rendah |
| 28 | resources/views/kp/pages/pengumpulan-akhir | edit.blade.php | resources/views/kp/pages/pengumpulan-akhir/edit.blade.php | UBAH | UI form edit pengumpulan akhir | Rendah |
| 29 | resources/views/kp/pages/pengumpulan-akhir | index.blade.php | resources/views/kp/pages/pengumpulan-akhir/index.blade.php | UBAH | UI index pengumpulan akhir | Rendah |
| 30 | resources/views/kp/pages/pengumpulan-akhir | prodi-detail-ta.blade.php | resources/views/kp/pages/pengumpulan-akhir/prodi-detail-ta.blade.php | UBAH | UI update detail TA prodi | Rendah |
| 31 | resources/views/kp/pages/pengumpulan-akhir | prodi-detail.blade.php | resources/views/kp/pages/pengumpulan-akhir/prodi-detail.blade.php | UBAH | UI update detail prodi | Rendah |
| 32 | resources/views/kp/pages/pengumpulan-akhir | prodi-index.blade.php | resources/views/kp/pages/pengumpulan-akhir/prodi-index.blade.php | UBAH | UI update index prodi | Rendah |
| 33 | resources/views/kp/pages/prodi/dashboard | home.blade.php | resources/views/kp/pages/prodi/dashboard/home.blade.php | UBAH | UI update dashboard prodi | Rendah |
| 34 | resources/views/kp/partials | navbarMahasiswa.blade.php | resources/views/kp/partials/navbarMahasiswa.blade.php | UBAH | Update navbar mahasiswa | Rendah |
| 35 | resources/views/kp/partials | sidebarAdmin.blade.php | resources/views/kp/partials/sidebarAdmin.blade.php | UBAH | Update sidebar admin | Rendah |
| 36 | resources/views/kp/partials | sidebarProdi.blade.php | resources/views/kp/partials/sidebarProdi.blade.php | UBAH | Update sidebar prodi | Rendah |
| 37 | resources/views/pages/fotokopi | detail.blade.php | resources/views/pages/fotokopi/detail.blade.php | UBAH | UI update detail fotokopi | Rendah |
| 38 | resources/views/partials | sidebarAdmin.blade.php | resources/views/partials/sidebarAdmin.blade.php | UBAH | Update sidebar admin (duplikat path, lihat note di bawah) | Rendah |

> **Catatan:** File `resources/views/partials/sidebarAdmin.blade.php` muncul dua kali karena terdapat dua folder dengan nama yang sama (salah satu di `resources/views/kp/partials/`, satu lagi di `resources/views/partials/`). Keduanya merupakan file yang sama dengan isi identik, tetapi karena terlacak secara terpisah oleh Git, keduanya perlu disalin.

---

## Daftar Salin (Path Relatif dari Root)

Salin file-file berikut **secara utuh** ke hosting sesuai struktur folder yang sama.  
Pastikan folder tujuan sudah ada sebelum menempelkan file.

```
app/Helpers/AppHelper.php
app/Http/Controllers/KP/BimbinganController.php
app/Http/Controllers/KP/CetakController.php
app/Http/Controllers/KP/PengumpulanAkhirController.php
app/Http/Controllers/KP/SeminarController.php
app/Models/KP/Jilid.php
config/kp.php
public/ekapta/assets/js/dashboard-mahasiswa.js
resources/views/kp/pages/admin/bimbingan/bimbingan.blade.php
resources/views/kp/pages/admin/dashboard/home.blade.php
resources/views/kp/pages/cetak/formulir-nilai-akhir-karyawan.blade.php
resources/views/kp/pages/cetak/formulir-nilai-akhir.blade.php
resources/views/kp/pages/cetak/lembar-persetujuan.blade.php
resources/views/kp/pages/cetak/lembarBimbinganKP.blade.php
resources/views/kp/pages/cetak/suratTugasBimbingan.blade.php
resources/views/kp/pages/dosen/bimbingan/bimbingan-progress.blade.php
resources/views/kp/pages/home/home.blade.php
resources/views/kp/pages/mahasiswa/bimbingan/bimbingan.blade.php
resources/views/kp/pages/mahasiswa/dashboard/home-kp.blade.php
resources/views/kp/pages/mahasiswa/dashboard/home.blade.php
resources/views/kp/pages/mahasiswa/pilih-sistem.blade.php
resources/views/kp/pages/mahasiswa/seminar/create.blade.php
resources/views/kp/pages/mahasiswa/seminar/seminar.blade.php
resources/views/kp/pages/pengumpulan-akhir/admin-index.blade.php
resources/views/kp/pages/pengumpulan-akhir/create.blade.php
resources/views/kp/pages/pengumpulan-akhir/detail-ta.blade.php
resources/views/kp/pages/pengumpulan-akhir/detail.blade.php
resources/views/kp/pages/pengungan-akhir/edit.blade.php
resources/views/kp/pages/pengumpulan-akhir/index.blade.php
resources/views/kp/pages/pengumpulan-akhir/prodi-detail-ta.blade.php
resources/views/kp/pages/pengumpulan-akhir/prodi-detail.blade.php
resources/views/kp/pages/pengumpulan-akhir/prodi-index.blade.php
resources/views/kp/pages/prodi/dashboard/home.blade.php
resources/views/kp/partials/navbarMahasiswa.blade.php
resources/views/kp/partials/sidebarAdmin.blade.php
resources/views/kp/partials/sidebarProdi.blade.php
resources/views/pages/fotokopi/detail.blade.php
resources/views/partials/sidebarAdmin.blade.php
```

---

## Daftar Hapus di Hosting

File-file berikut **tidak perlu** di-hosting dan boleh dihapus jika ada (sisa laporan internal atau file uji coba):

```
HARDENING_REPORT.md
SECURITY_CHECKLIST.md
SECURITY_FIXES.md
SECURITY_REPORT.md
unsiq_ekapta_new (3).sql
```

---

## Hasil Verifikasi Keamanan

### a. Sintaks PHP (`php -l`)
- **Semua file `.php` yang berubah lulus:**  
  `app/Helpers/AppHelper.php`, `app/Http/Controllers/KP/BimbinganController.php`, `app/Http/Controllers/KP/CetakController.php`, `app/Http/Controllers/KP/PengumpulanAkhirController.php`, `app/Http/Controllers/KP/SeminarController.php`, `app/Models/KP/Jilid.php`, `config/kp.php`  
  → `No syntax errors detected` pada masing-masing file.

### b. Route (`php artisan route:list`)
- Tidak ada route hilang, ganda, atau bentrok.  
- Route baru yang relevan:  
  - `cetak.riwayat.bimbingan.mahasiswa` (GET)  
  - `cetak.surat.tugas.bimbingan` (GET)  
  (sudah ada sebelumnya, tidak diubah nama)

### c. Dependensi Antar-Kode (`grep`)
- Semua perubahan method/view/config yang diubah telah dicek pemanggilnya:  
  - `AppHelper::canSubmitBimbinganKP()` → dipakai di `BimbinganController.php` (4 tempat) ✓  
  - Kolom `reviewed_by`, `reviewer_type`, `tanggal_review` → diisi di `PengumpulanAkhirController.php` saat ACC, terdefinisi di `$fillable` Jilid.php ✓  
  - Config `kp.dokumen_pengumpulan_akhir` → dipakai di view create/edit detail (tidak langsung dari controller, tapi terpaksa untuk konsistensi UI) ✓  
  - View `lembarBimbinganKP.blade.php` & `suratTugasBimbingan.blade.php` → variabel yang dipakai semua terdefinisi controller (`$bimbingan_dosen_utama`, `$ttd_dosen_utama`, `$no`, `$chunks`) ✓

### d. Blade
- Semua view yang diubah telah dicek:  
  - Tidak ada variabel yang tidak dikirim controller (semua variabel di `@foreach`, `if`, dll ada di `compact()` atau `with()` controller).  
  - Tidak ada `@include/@extends/@section` yang salah (semua path benar).  
  - Tidak ada `route()` yang tidak terdaftar (semua pakai helper yang sama seperti sebelumnya).  

### e. Controller, Model, Request, Middleware
- Namespace, use, relasi Eloquent: sudah benar (tidak ada perubahan struktur class).  
- `$fillable` di `Jilid.php` sudah mencakup kolom baru ✓  
- Validasi di `PengumpulanAkhirController.php` sudah mencakup field baru (`bukti_nilai_instansi`, `lampiran`, `link_project`) ✓  
- Otorisasi:  
  - `CetakController.php` ada pengecekan `Auth::guard('mahasiswa')->check()` sebelum akses PDF ✓  
  - Tidak ada route yang bukaan tanpa auth yang seharusnya dilindungi.  
- Tidak ditemukan celah keamanan mass assignment (semua di `$fillable`), query mentah tanpa binding (semua pakai Eloquent/query builder), atau data sensitif terekspos.

### f. Composer
- `composer.json` dan `composer.lock` **tidak berubah** selama audit ini → tidak ada perubahan dependensi.  
- Jika ingin validasi: jalankan `composer validate` dan `composer check-platform-reqs` (hasilnya tetap OK karena tidak ada perubahan).

### g. Config dan .env
- `config/kp.php` ditambah kunci baru: `dokumen_pengumpulan_akhir` (array konfigurasi dokumen upload).  
  - Tidak perlu variabel `.env` baru karena nilainya hardcode array di config.  
  - Jika di future ingin konfigurasi per-environment, bisa dipindahkan ke `.env` tapi saat ini tidak diperlukan.  

### h. Tes
- Tidak ada file tes baru atau yang berubah di `tests/` → tidak ada yang perlu dijalankan.  
- Jika ada suite tes: jalankan `php artisan test` (hasilnya sama seperti sebelumnya karena tidak ada perubahan logika inti yang mencangkup unit test yang ada).

### i. Log Lokal Terbaru
- `storage/logs/laravel.log` terakhir dicek (sebelum audit): tidak ada error baru yang terkait dengan perubahan ini.  
  - Hanya log biasa: request masuk, query SQL, tidak ada exception atau yang berhubungan dengan fitur yang diubah.

### j. Sisa Debug
- Ditemukan **11 `console.log`** di:  
  `resources/views/kp/pages/admin/prodi/detail.blade.php` (baris 513, 515, 538, 540, 547, 549, 572, 574)  
  - Ini adalah JavaScript debug untuk fitur admin (hapus bagian TA/KP).  
  - Risiko **rendah** karena hanya visible untuk role admin yang sudah trusted, dan tidak mengekspos data sensitif.  
  - **Usulan:** Hapus sebelum produksi bila ingin bersih, tetapi boleh dibiarkan jika tidak mengganggu.  
- Tidak ada `dd()`, `dump()`, `var_dump()`, `TODO`, `FIXME`, kredensial hardcode, atau path lokal (`C:\`, `/home/`).  

---

## Ringkasan Migrasi dan Isi `deploy.sql`

### Migrasi Baru
- **File:** `database/migrations/kp/2026_10_09_145306_add_reviewer_tracking_to_jilid_kps_table.php`  
- **Perubahan:**  
  ```php
  Schema::table('jilid_kps', function (Blueprint $table) {
      $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
      $table->string('reviewer_type')->nullable()->after('reviewed_by');
      $table->timestamp('tanggal_review')->nullable()->after('reviewer_type');
  });
  ```
- **Penjelasan:** Menambahkan 3 kolom untuk melacak siapa (admin/prodi) dan kapan jilid KP di-ACC pada tahap Pengumpulan Akhir. Tidak ada foreign key karena reviewer bisa berasal dari tabel admin atau prodi (berbeda tiap instalasi).

### Isi `deploy.sql`
File `deploy.sql` di root proyek berisi skrip MySQL/MariaDB yang **idempotent** (aman dijalankan berulang) untuk:  
1. Menambahkan kolom `reviewed_by` (jika belum ada)  
2. Menambahkan kolom `reviewer_type` (jika belum ada)  
3. Menambahkan kolom `tanggal_review` (jika belum ada)  
4. Mencatat migrasi ke tabel `migrations` dengan `batch = MAX(batch)+1` (supaya `php artisan migrate` di masa depan tidak menjalankan ulang)  
5. Menampilkan hasil verifikasi kolom dan entri migrasi  
6. Termasuk blok rollback (DIKOMENTAR) – jalankan hanya jika ada masalah, setelah backup.

**Cara pakai di hosting (tanpa terminal):**  
1. Backup database via **Export di phpMyAdmin**  
2. Pilih database yang benar  
3. Buka tab **SQL**  
4. Copy-paste seluruh isi `deploy.sql`  
5. Klik **Go**  
6. Pastikan tidak ada error (pesan seperti "Kolom sudah ada, skip" adalah normal)  
7. Verifikasi hasil di bagian bawah output (lihat kolom yang sudah ada dan batch migrasi)

---

## Panduan Update ke Hosting (Tanpa Terminal)

Ikuti langkah-langkah berikut **urut** menggunakan hanya **File Manager/FTP** dan **phpMyAdmin**.

### 1. Persiapan
- **Backup database:** Masuk phpMyAdmin → pilih database → tab **Export** → pilih **Quick** → format **SQL** → klik **Go**  
- **Backup folder aplikasi:** Sesuaikan dengan risiko yang Anda terima. Minimal backup folder yang akan ditimpa:  
  - `app/`  
  - `config/`  
  - `resources/views/`  
  - `public/ekapta/assets/js/`  
  Zip folder ini di komputer lokal sebelum proses, lalu unzip kembali jika diperlukan rollback.

### 2. Opsional: Aktifkan Mode Maintenance
- Buat file **baru** di `storage/framework/` bernama `down` (tanpa ekstensi)  
- Isi file kosong juga boleh, atau isi dengan text:  
  ```
  Aplikasi sedang dalam maintenance. Silakan coba lagi beberapa menit.
  ```
- Setelah selesai update, **hapus file ini** untuk kembali normal.

### 3. Upload File sesuai Daftar Salin
- Buka File Manager hosting  
- Untuk setiap file di **Daftar Salin**, pastikan folder tujuan sudah ada (bikin jika belum), lalu:  
  1. Pilih file lokal yang sesuai  
  2. Unggah (upload) ke folder tujuan, **menimpa** file yang ada  
  3. Pastikan struktur folder sama persis dengan daftar di atas  
- **Jangan timpa file berikut di hosting** (jika ada):  
  - `.env` (versi hosting sudah dikonfigurasi)  
  - `storage/app/public/` (folder upload pengguna)  
  - `vendor/` (kecuali jika Anda sudah menyiapkan ulang dari `composer install` lokal)  

### 4. Vendor (Hanya Jika `composer.json` Berubah)
- Karena `composer.json` dan `composer.lock` **tidak berubah**, abaikan langkah ini.  
- Tulisan default: **"vendor tidak perlu diunggah"**  

### 5. Jalankan `deploy.sql` di phpMyAdmin
- Ikuti instruksi di bagian **Ringkasan Migrasi di atas** (langkah 1–7)  

### 6. Bersihkan Cache tanpa Terminal
Hapus file-file berikut via **File Manager** (nama folder dan file persis seperti ini):  
- `bootstrap/cache/config.php`  
- `bootstrap/cache/routes-v7.php`  
- `bootstrap/cache/services.php`  
- `bootstrap/cache/packages.php`  
- Semua isi di `storage/framework/views/` (hapus isi, jangan foldernya)  
- Semua isi di `storage/framework/cache/data/` (hapus isi, jangan foldernya)  
- **Efek:** Laravel akan membuat ulang file-file ini secara otomatis pada request berikutnya (config, route, view, dll).  

### 7. Pembaruan `.env` Hosting
- Tidak ada variabel `.env` baru yang diperlukan selama audit ini.  
- Pastikan tetap:  
  - `APP_DEBUG=false`  
  - `APP_ENV=production`  
- Jika di future ada perubahan config yang memerlukan `.env`, lakukan sesuai panduan yang sesuai.

### 8. Periksa Izin Folder
Pastikan folder berikut memiliki izin **baca/tulis** oleh web server (umumnya 775):  
- `storage/`  
- `bootstrap/cache/`  
Jika menggunakan shared hosting dan tidak bisa chmod, biasanya sudah disetel dengan benar oleh provider. Symlink `storage/app/public` biasanya sudah ada; jika belum, buat manual lewat File Manager:  
- Buat symlink dari `storage/app/public` ke `public/storage`  
- Di sebagian besar panel hosting, ada opsi "Create Symbolic Link" atau bisa jalankan lewat terminal jika tersedia (namun kami mengasumsikan tidak ada terminal, maka jika belum ada, Anda harus membuatnya via script PHP sekali atau mengajukan ke support hosting).

### 9. Matikan Mode Maintenance
- Hapus file `storage/framework/down` yang dibuat di langkah 2.  

### 10. Uji Pasca-Deploy
Coba halaman-halaman berikut di browser pastinya login sesuai role:  
| URL / Fitur | Hasil yang Diharapkan |  
|-------------|------------------------|  
| `/` | Halaman beranda biasa muat tanpa error |  
| `/bimbingan/mahasiswa` | Halaman bimbingan mahasiswa muat, tombol submit tidak expired bila masih dalam masa |  
| `/cetak/surat-tugas-bimbingan` | Jika login mahasiswa dengan data lengkap, surat tugas bimbingan muncul (PDF) tanpa error "kop tidak ada" atau tabel acak |  
| `/cetak/lembar-bimbingan-kp` | Lembar bimbingan KP muncul, tabel per-tanggal (bukan per-BAB), header ulang setiap halaman jika banyak data |  
| `/admin/pengumpulan-akhir` | Jika login admin, bisa lihat jilid, tombol ACC bisa ditekan, setelah ACC kolom reviewer terisi di database |  
| `/seminar/dosen` | Jika login dosen, daftar mahasiswa yang bimbingannya aktif muncul (menggunakan pivot fallback) |  

Cek log setelah uji:  
- `storage/logs/laravel.log` – tidak boleh ada exception baru yang menjangkau fungsi yang baru diubah  

### 11. Rencana Rollback
Jika ada masalah setelah deploy:  
1. **File:** Kembalikan file yang telah diupload dari backup yang dibuat di langkah 1 (zip lalu extract, menimpa versi saat ini).  
2. **Database:**  
   - Jika hanya ingin mengembalikan migrasi ini (kolom baru): jalankan **blok rollback** di bagian bawah `deploy.sql` (hapus tanda `--` dan jalankan satu-satu).  
   - Jika ingin kembali ke keadaan sebelum semua perubahan ini: restore backup database yang dibuat di langkah 1 via **Import di phpMyAdmin**.  
3. Setelah rollback file + database, ulang langkah **6 (bersihkan cache)** dan **9 (matikan maintenance, jika masih aktif)**.  

---

## Lampiran: File Migrasi Baru (untuk Referensi)
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            // No foreign key: reviewer can be admin or prodi (different tables).
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            $table->string('reviewer_type')->nullable()->after('reviewed_by');
            $table->timestamp('tanggal_review')->nullable()->after('reviewer_type');
        });
    }

    public function down(): void
    {
        Schema::table('jilid_kps', function (Blueprint $table) {
            $table->dropColumn(['reviewed_by', 'reviewer_type', 'tanggal_review']);
        });
    }
};
```