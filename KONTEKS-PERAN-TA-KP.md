# KONTEKS PERAN DAN ATURAN PERUBAHAN: EKAPTA (TA dan KP)

Dokumen ini wajib dibaca AI agent sebelum mengubah apa pun. Sumbernya jawaban pemilik proyek (4 Oktober 2026). Bagian 5 adalah hasil pembacaan kode statis, bukan keputusan.

## 1. Status produksi

- Modul TA dan KP sudah berjalan di production dan masih aktif dipakai.
- Server belum menerima satu pun perubahan dari sesi audit. Kondisi server adalah kondisi sebelum audit.
- Pemilik pernah mengedit server langsung, jadi isi server bisa berbeda dari GitHub. Sebelum upload, bandingkan manifest (`ekapta_audit.py manifest` dan `compare`).
- Basis kerja perbaikan adalah `origin/new-version`. Jangan memakai `main` atau `fix/security-hardening` sebagai basis.

## 2. Aturan perubahan (WAJIB)

1. Modul TA tidak boleh disentuh kecuali pemilik meminta secara eksplisit. Ini mencakup route, controller, view, middleware, dan URI TA.
2. Bagian yang sengaja digabung antara TA dan KP karena integrasi tidak boleh disentuh. Jika ragu apakah suatu route termasuk bagian gabungan, anggap iya dan tanyakan.
3. Jangan menyalin logika atau middleware dari TA ke KP atau sebaliknya. Aturan peran kedua modul berbeda (lihat bagian 3).
4. Jangan menghapus route, method, atau file. Usulkan dulu dan tunggu persetujuan. Pengecualian hanya untuk yang sudah disetujui tertulis oleh pemilik.
5. Jangan mengomentari route.
6. Pulihkan fitur yang hilang hanya jika terbukti seharusnya ada: ada di riwayat git, ada pemanggil di view, atau pemilik mengonfirmasi. Jika tidak terbukti, laporkan dan jangan membuat fitur baru.
7. Jangan membuat method, view, atau fitur baru untuk menenangkan hasil audit.
8. Setiap perubahan harus disertai output mentah `routes-diff` terhadap `origin/new-version` dan hasil `audit`. Gunakan `ekapta_audit.py` versi terbaru (nama route ganda tidak lagi saling menimpa). Setiap perbedaan tanda tangan route harus dijelaskan satu per satu.
9. Satu kelompok perbaikan satu commit. Commit hanya dengan path eksplisit, bukan `git add -A`. Jangan commit file alat audit, file `.ignore`, laporan sementara, atau dump SQL.
10. Laporan tidak boleh bertentangan dengan rinciannya. Klaim "tidak ada yang diubah" harus cocok dengan `git diff --stat`.

## 3. Peran KP (aturan resmi dari pemilik)

| Tahap atau fungsi | Siapa yang bertindak | Siapa yang hanya melihat |
|---|---|---|
| Master data, termasuk reset password | Admin saja (disengaja) | - |
| Pengajuan KP: ACC | Prodi | Admin |
| Pendaftaran KP: validasi | Admin | Prodi |
| Bimbingan online (dosen dengan `dosens.is_manual = 0`): mahasiswa mengirim laporan ke dosen | Dosen me-review (ACC atau revisi) lewat `kp.bimbingan.*`, halaman `review.blade.php` dipakai untuk jalur ini | Prodi melihat progres |
| Bimbingan offline (dosen dengan `dosens.is_manual = 1`): mahasiswa mengirim bukti bimbingan dan laporan | Admin atau Prodi me-review (ACC atau revisi) lewat `kp.bimbingan-manual.*` | Dosen tidak me-review jalur ini |
| Seminar KP: ACC, review, revisi, validasi | Himpunan saja | Admin, Prodi, Dosen (Dosen hanya melihat daftar `kp.seminar.dosen`) |
| Seminar KP: input nilai | Prodi (di halaman seminar). Dosen penguji memberi nilai di halaman penilaian bertoken `/kp/penilaian-seminar/{token}`, bukan di halaman seminar | Admin |
| Seminar KP: lihat hasil dan dokumen | - | Admin, Prodi |
| Ubah atau hapus pendaftaran | Mahasiswa hanya saat pendaftaran | - |

Pilihan online atau offline ditentukan per dosen (kolom `dosens.is_manual`). Data produksi (5 Oktober 2026) sejalan dengan aturan ini: 19 dosen berstatus manual dan 20 online; 288 catatan revisi bimbingan online seluruhnya dari dosen; 221 pengajuan bimbingan offline (`ajuan_bimbingan_manual_kps`) direview Prodi (212) dan Admin (9).

Pembatasan lintas prodi: Prodi dan Himpunan hanya boleh mengakses data prodinya sendiri. Himpunan Prodi A tidak boleh melihat atau mengubah data Prodi B.

## 4. Peran TA

TA aktif dan tidak boleh disentuh. Peran TA belum didokumentasikan di sini. Baca kode apa adanya dan jangan mengasumsikan sama dengan KP. Contoh perbedaan: di TA revisi seminar dilakukan Admin/Prodi (`isAdminProdi`), sedangkan di KP dilakukan Himpunan (`isHimpunan`).

## 5. Temuan kode yang berbeda dari aturan (laporkan, jangan ubah tanpa persetujuan)

1. Himpunan dan batas prodi. Daftar seminar sudah disaring per prodi. Aksi tulis tidak menyaring prodi: `seminarAcc`, `seminarRevisi`, dan `validasiRevisiPasca` memakai `Seminar::findOrFail($id)` tanpa cek prodi. `seminarReview`, `finalisasiNilai`, dan `inputNilaiManual` tampak sama. Akibatnya himpunan prodi lain bisa memproses seminar dengan mengganti id.
2. Prodi di seminar KP memiliki route POST `kp.review.seminar.acc.prodi` dan `kp.seminar.update.status`. Tidak ada view yang memanggil keduanya. Aturan: ACC dan review hanya Himpunan. Jangan menghapus tanpa persetujuan.
3. Dosen di seminar KP masih memiliki route `kp.review.seminar.dosen` (GET) serta POST `.acc`, `.revisi.store`, `.revisi.delete`, `.cancel.acc`, `.nilai` (middleware `isDosen`). Pemilik menegaskan Dosen di KP hanya me-review bimbingan online, hanya melihat seminar, dan memberi nilai di halaman terpisah. Bukti kode dan data: tidak ada view atau controller yang menautkan `kp.review.seminar.dosen` (halaman yatim), menu dosen hanya menautkan `kp.seminar.dosen`, dan 14 baris `review_seminar_kps` di produksi (semua `dosen_status=penguji`, `status=diterima`) dibuat oleh `PenilaianSeminarController::submit`, bukan oleh tombol ACC. Kesimpulan: route-route ini sisa dari TA dan menyimpang dari aturan. Statusnya dilaporkan, belum dihapus atau dibatasi. Pembatasan hanya setelah pemilik menyetujui, dan log akses web server dapat dipakai sebagai bukti tambahan.
4. `kp.seminar.upload.revisi` dan `KPSeminarController::uploadRevisi()`: tidak ada pemanggil, tidak pernah ada di riwayat git, dan menduplikasi `HimpunanController::seminarRevisi`. Rekomendasi: hapus route dan method. Menunggu persetujuan pemilik.
5. `ujian.delete` (TA): route lama dari 2023 menunjuk ke method yang tidak ada, tidak ada pemanggil di view. Rekomendasi: kembalikan ke kondisi production (jangan tambah method). Menunggu persetujuan.
6. Perubahan agent yang menyentuh TA atau bagian gabungan, perlu keputusan atau rollback:
   - URI `bimbingan.admin.input.store` dipindah dari `/bimbingan/store` ke `/bimbingan/input/store`.
   - KOREKSI: route reset password (`dosen`, `mahasiswa`, `prodi`) terdaftar dua kali di `new-version`. Definisi pertama (baris 446-456) memakai controller KP dan itulah yang berlaku, karena Laravel memakai route yang cocok lebih dulu. Definisi kedua (baris 616-618) memakai controller TA dan tidak pernah dijalankan. Kedua jenis `resetPassword` berisi logika yang sama. Jangan diubah. Perubahan apa pun yang mengalihkan definisi pertama ke controller TA dianggap menyimpang.
   - `/himpunans` dialihkan dari `KPAdminController@himpunans` (method tidak ada) ke `HimpunanMasterController@index`.
   - View baru `pages/pengumpulan-akhir/detail` dan `prodi-detail` (sekitar 980 baris) beserta method `detailTA` dan `detailProdiTA`.

7. Alur bimbingan offline ganda. Selain `kp.bimbingan-manual.*` (jalur yang dipakai: 221 baris di produksi), ada `kp.bimbingan-offline.*` (verify dan reject oleh `isProdi`) serta `kp.bimbingan.create.manual`, `.store.manual`, dan `.submit.acc.manual`. Di produksi `bimbingan_kps.tipe` seluruhnya `online`, `status_offline` seluruhnya NULL, dan `bukti_bimbingan_offline` kosong, jadi `kp.bimbingan-offline.*` tidak terpakai. Jangan dihapus, cukup dilaporkan.
8. Route `kp.bimbingan.acc.prodi` dan `kp.bimbingan.revisi.prodi` (`isAdminProdi`) memungkinkan Admin atau Prodi mengubah bimbingan online. Di produksi tidak ada jejak pemakaiannya (seluruh catatan revisi online berasal dari dosen). Perlu konfirmasi pemilik apakah ini override yang disengaja.

## 6. Keputusan yang sudah final (pemilik)

- `public/setup-links.php` dihapus dari repo (commit `0c5abae`). Symlink `storage` di production sudah terpasang, jadi file itu tidak diperlukan lagi. File ini tidak boleh diupload ke hosting, dan jika masih ada di server harus dihapus. Kunci `ekapta2026` yang ada di riwayat git tidak perlu ditulis ulang riwayatnya; ganti jika string yang sama dipakai di tempat lain.
- Dosen di KP hanya me-review bimbingan online (bimbingan offline direview Admin atau Prodi), hanya melihat seminar, dan memberi nilai lewat halaman penilaian bertoken (lihat bagian 3 dan 5.3).
- Route `kp.review.seminar.*` milik dosen dilaporkan sebagai penyimpangan dan tidak diubah tanpa persetujuan terpisah.

## 7. Keputusan tertunda (pemilik)

- Halaman penilaian bertoken `/kp/penilaian-seminar/{token}` tidak memakai login dan hanya dilindungi token sekali pakai per sesi. Itu rancangan yang ada; jangan diubah tanpa persetujuan.

- Navbar `resources/views/kp/partials/navbar.blade.php` memanggil `route('kp.login')` (tombol Login, baris 30) dan `route('kp.back.dashboard')` (tombol Dashboard, baris 28), keduanya tidak terdefinisi. File ini hanya di-include oleh `kp/pages/home/home.blade.php`, halaman landing KP yang di-render `KP\HomeController@index`, dan controller itu tidak dipakai route mana pun. Layout dashboard KP memakai `navbarMahasiswa`, file berbeda. Jadi navbar ini tidak bisa dibuka pengguna dan tidak berdampak di production. Rekomendasi: biarkan (kode yatim). Jangan membuat route baru untuk landing KP. Jika pemilik ingin audit bersih, cukup ganti `kp.login` menjadi `login` di file itu saja.
- `Pendaftaran::delete` (TA dan KP) sudah menolak status DITERIMA. Yang belum ada hanya cek kepemilikan (`mahasiswa_id`).

- Di production, apakah Admin atau Prodi bisa input bimbingan dari halaman TA tanpa error? (menentukan rollback URI `bimbingan.admin.input.store`)
- Apakah halaman `/himpunans` sisi admin bisa dibuka di production sekarang?
- Apakah menu atau tombol "detail TA" di halaman pengumpulan akhir memang harus berfungsi?
- "Mahasiswa hanya saat pendaftaran": sampai status apa boleh ubah atau hapus (misalnya hanya saat REVIEW)?

## 8. Fakta database produksi (dump 5 Oktober 2026, MariaDB 10.11)

Ringkasan agregat dari dump produksi. Dump berisi data pribadi: jangan dibagikan ke agent dan jangan di-commit.

- TA dan KP sama-sama aktif. Dalam 30 hari terakhir TA mencatat 128 bimbingan, 13 pengajuan, dan 13 pendaftaran baru; KP mencatat 127 bimbingan, 35 pengajuan, dan 23 pendaftaran baru.
- Tabel `migrations` produksi hanya mencatat 58 migrasi. Skema produksi sudah memuat efek hampir semua migrasi yang ditandai "belum jalan" oleh `new-version` (tabel `jilids`, `bimbingan_canceleds`, `seminar_canceleds`, `revisi_jilids`, serta kolom tambahan di `dosens`, `review_seminars`, `review_ujians`, `ujians`, `himpunan_kps`, `sesi_seminar_kps`). Menjalankan `php artisan migrate` di produksi akan gagal dengan "table already exists".
- ATURAN: agent dilarang menjalankan `php artisan migrate`, `migrate:fresh`, atau `migrate:refresh` terhadap database produksi atau salinannya tanpa persetujuan pemilik. Deploy perbaikan route/controller/view tidak memerlukan migrasi.
- Efek migrasi `remove_unique_nidn_from_dekans_table` sudah ada di produksi (tidak ada indeks unik pada `nidn`). Jangan memulihkan file migrasinya ke `new-version`.
- Kolom `pendaftaran_kps.kelas` ada di produksi, tetapi migrasinya hanya ada di `main` dan `fix/security-hardening`, tidak di `new-version`.
- Migrasi unique index `bimbingan_kps(mahasiswa_id, bagian_id)` dari `fix/security-hardening` GAGAL di produksi: ada 237 pasangan ganda. Hampir semuanya (235) adalah baris lama berstatus NULL tanpa lampiran, keterangan, atau tanggal, dibuat berminggu-minggu sebelum baris barunya. Baris baru memegang data (154 punya revisi, 85 punya ajuan manual). Setiap baris punya satu baris `dosen_bimbingan_kps`. Delapan pasangan perlu tinjau manual. Jangan jalankan migrasi itu atau skrip cleanup sebelum pemilik menyetujui rencana pembersihan.
- Tabel `pendaftaran_kps_backup_20261001` (2 baris) ada di produksi, menandakan bagian backup skrip cleanup pernah dijalankan. Masih ada satu pasangan `pendaftaran_kps` ganda (diterima, dibuat berselisih sekitar 11 detik).
