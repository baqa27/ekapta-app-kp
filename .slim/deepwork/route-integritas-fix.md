# Laporan Perbaikan Akses Kondisional Bimbingan KP

## Masalah Awal

Mahasiswa KP yang bimbingannya sudah **selesai (complete)** tetapi **batas waktu habis (expired)**
tidak bisa mengakses seminar dan pengumpulan akhir. Penyebab utama: pemeriksaan
`isBimbinganExpiredFromPendaftaran()` dipakai di jalur yang salah.

## Akar Masalah (Penyebab Terkunci)

1. `app/Http/Controllers/KP/SeminarController.php` — method `store()` (baris ~324) memblokir
   submit seminar jika `isBimbinganExpiredFromPendaftaran()` bernilai true, lalu redirect ke
   pendaftaran KP. Ini memblokir mahasiswa yang bimbingannya sudah complete.
2. `app/Http/Controllers/KP/BimbinganController.php` — method `edit()` dan `update()`
   memakai expired check yang sama untuk memblokir **seluruh** akses, termasuk mahasiswa yang
   bimbingannya sudah complete.
3. `resources/views/kp/pages/mahasiswa/bimbingan/bimbingan.blade.php` — tombol tambah bimbingan
   dan peringatan perpanjangan tampil berdasarkan `$is_expired` saja, tanpa membedakan status
   complete.

## Fungsi Baru

`app/Helpers/AppHelper.php` — ditambah `canSubmitBimbinganKP($mahasiswa)`:

- Mengembalikan `true` jika KP sudah diperpanjang (`jumlah_perpanjangan > 0`).
- Mengembalikan `true` jika batas belum habis.
- Mengembalikan `true` jika bimbingan sudah complete.
- Mengembalikan `false` hanya jika **batas habis DAN bimbingan belum complete**.

## Aturan Akses yang Diterapkan

| Kondisi | Akses |
|---|---|
| Batas habis + belum complete | Terkunci (blokir submit/edit, arahkan perpanjangan) |
| Batas habis + complete | Buka riwayat (lihat/unduh), seminar, pengumpulan akhir |
| Batas belum habis / diperpanjang | Tidak berubah |

## Daftar File yang Diedit

### 1. `app/Helpers/AppHelper.php`
- **Alasan**: menambah fungsi `canSubmitBimbinganKP()` sebagai satu sumber kebenaran untuk
  keputusan boleh-tidaknya submit bimbingan.

### 2. `app/Http/Controllers/KP/BimbinganController.php`
- **Alasan**: mengganti expired check inline di `edit()` dan `update()` dengan
  `canSubmitBimbinganKP()`; menambah gate yang sama di `create()`, `store()`, `createManual()`,
  dan `storeManual()` agar mahasiswa expired+belum-complete tidak bisa submit via URL langsung.
- **Catatan**: definisikan `$mahasiswa` dari `Auth::guard('mahasiswa')` di `update()`.

### 3. `app/Http/Controllers/KP/SeminarController.php`
- **Alasan**: menghapus expired check di `store()` (blocker utama). Mahasiswa complete+expired
  kini bisa submit seminar.

### 4. `resources/views/kp/pages/mahasiswa/bimbingan/bimbingan.blade.php`
- **Alasan**: tombol tambah bimbingan kini pakai `$canSubmitBimbingan`; peringatan perpanjangan
  tampil hanya jika `$is_expired && !$canSubmitBimbingan` (yaitu expired DAN belum complete).

## File yang Sengaja TIDAK Diedit

- `app/Http/Controllers/BimbinganController.php` (root, tanpa prefix `/kp/`) — **shared TA+KP**,
  dilarang diedit.
- `app/Http/Controllers/KP/PengumpulanAkhirController.php` — diverifikasi bersih, tidak ada
  expired check yang salah sasaran.
- `app/Http/Controllers/KP/PendaftaranController.php` — alur perpanjangan tidak diubah.

## Verifikasi

- `php -l` pada `AppHelper.php`, `BimbinganController.php`, `SeminarController.php`,
  `bimbingan.blade.php` — **semua lolos** (tidak ada syntax error).
- `git diff --check` — tidak ada trailing whitespace/konflik.
- `php artisan route:list` — route seminar, bimbingan, pengumpulan akhir tetap terdaftar,
  middleware `IsMahasiswa` benar.
- Uji 4 kasus (matriks QA) — perilaku cocok untuk semua kondisi (expired+belum complete,
  expired+complete, aktif, diperpanjang).

## Hal yang Tidak Dikerjakan

- Tidak ada migrasi database baru.
- Alur perpanjangan KP tidak diubah.
- Peran lain (dosen, himpunan, admin, prodi) tidak disentuh.
