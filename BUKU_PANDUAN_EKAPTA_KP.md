# BUKU PANDUAN SISTEM EKAPTA KERJA PRAKTEK (KP)

## Sistem Informasi Manajemen Kerja Praktek
### Fakultas Teknik dan Ilmu Komputer (FASTIKOM)
### Universitas Sains Al-Qur'an (UNSIQ) Jawa Tengah di Wonosobo

**Versi:** 1.0  
**Tanggal:** Maret 2026  
**URL Sistem:** `https://ekapta.fastikom-unsiq.ac.id`

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Arsitektur Sistem](#2-arsitektur-sistem)
3. [Peran Pengguna (User Roles)](#3-peran-pengguna-user-roles)
4. [Alur Proses Kerja Praktek](#4-alur-proses-kerja-praktek)
5. [Panduan Mahasiswa](#5-panduan-mahasiswa)
6. [Panduan Admin / Prodi](#6-panduan-admin--prodi)
7. [Panduan Dosen](#7-panduan-dosen)
8. [Panduan Himpunan](#8-panduan-himpunan)
9. [Master Data](#9-master-data)
10. [Dokumen Cetak (PDF)](#10-dokumen-cetak-pdf)
11. [FAQ & Troubleshooting](#11-faq--troubleshooting)

---

## 1. Pendahuluan

### 1.1 Tentang EKAPTA

**EKAPTA FASTIKOM** adalah sistem informasi berbasis web yang dirancang untuk mengelola seluruh proses Kerja Praktek (KP) mahasiswa di Fakultas Teknik dan Ilmu Komputer (FASTIKOM), Universitas Sains Al-Qur'an (UNSIQ) Jawa Tengah di Wonosobo.

Sistem ini mengotomasi alur kerja mulai dari pengajuan judul KP, pendaftaran, bimbingan, seminar, hingga pengumpulan akhir (jilid), dengan melibatkan berbagai peran pengguna: mahasiswa, dosen pembimbing, admin prodi, dan himpunan mahasiswa.

### 1.2 Tujuan Sistem

| No | Tujuan |
|----|--------|
| 1 | Mempermudah proses pengajuan dan validasi Kerja Praktek secara digital |
| 2 | Menyediakan sistem bimbingan online dan offline (manual) yang terintegrasi |
| 3 | Mengotomasi penjadwalan dan penilaian seminar KP |
| 4 | Menyediakan dokumen cetak (PDF) otomatis: Surat Tugas, Lembar Bimbingan, Berita Acara, dll |
| 5 | Memberikan transparansi status proses KP kepada seluruh stakeholder |
| 6 | Menyediakan countdown timer batas waktu bimbingan untuk kedisiplinan mahasiswa |

### 1.3 Kebutuhan Sistem

- **Browser:** Google Chrome, Mozilla Firefox, Microsoft Edge (versi terbaru)
- **Koneksi Internet:** Stabil
- **Akun:** Mahasiswa login menggunakan akun yang telah terdaftar di sistem

---

## 2. Arsitektur Sistem

### 2.1 Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel (PHP Framework) |
| Frontend | Blade Template + Bootstrap |
| Database | MySQL |
| PDF Generator | DomPDF |
| Authentication | Multi-role (Mahasiswa, Dosen, Admin, Prodi, Himpunan) |
| File Storage | Laravel Storage (public disk) |

### 2.2 Struktur Menu Berdasarkan Peran

#### Mahasiswa (Top Navbar)
```
Dashboard | Pengajuan KP | Pendaftaran KP | Bimbingan KP | Seminar KP | Jilid KP
```

#### Admin / Prodi (Sidebar)
```
├── Dashboard
├── Menu Kerja Praktek
│   ├── Validasi Pengajuan KP
│   ├── Validasi Pendaftaran KP / Bimbingan KP
│   ├── Progres Bimbingan KP
│   ├── Validasi Bimbingan KP
│   ├── Data Seminar KP / Seminar KP
│   └── Data Jilid KP
├── Menu Tugas Akhir
│   └── (menu TA terpisah)
└── Master Data
    ├── Prodi
    ├── Mahasiswa
    ├── Dosen
    ├── Fakultas
    └── Himpunan
```

#### Dosen (Sidebar)
```
├── Dashboard
└── Menu Kerja Praktek
    ├── Progress Bimbingan KP
    ├── Review Bimbingan KP
    ├── Review Seminar KP
    └── Penilaian Pembimbing
```

#### Himpunan (Sidebar)
```
├── Dashboard
└── Menu Kerja Praktek
    ├── Verifikasi Pendaftaran
    ├── Penjadwalan Seminar
    ├── Rekap Seminar KP
    └── Pengaturan Pembayaran
```

---

## 3. Peran Pengguna (User Roles)

### 3.1 Mahasiswa

Pengguna utama sistem. Mahasiswa bertanggung jawab untuk:
- Mengajukan judul KP
- Melakukan pendaftaran KP
- Melakukan bimbingan secara online maupun offline (manual)
- Mendaftar dan mengikuti seminar KP
- Mengumpulkan dokumen akhir (jilid KP)

### 3.2 Admin / Prodi

Administrator program studi yang bertanggung jawab untuk:
- Memvalidasi pengajuan KP mahasiswa
- Menentukan (plotting) dosen pembimbing
- Memvalidasi pendaftaran KP
- Memantau progres bimbingan
- Memvalidasi bimbingan manual (offline)
- Input nilai akhir KP (Nilai Instansi, Nilai Pembimbing, Nilai Penguji)
- Mengelola master data (prodi, mahasiswa, dosen, fakultas, himpunan)

### 3.3 Dosen

Dosen pembimbing dan/atau penguji KP yang bertanggung jawab untuk:
- Mereview dan memberikan feedback bimbingan mahasiswa (ACC/Revisi)
- Memberikan penilaian pembimbing
- Menjadi penguji seminar dan memberikan nilai seminar

### 3.4 Himpunan

Organisasi himpunan mahasiswa yang bertanggung jawab untuk:
- Memverifikasi pendaftaran seminar KP
- Mengatur metode pembayaran seminar
- Menjadwalkan sesi seminar (tanggal, waktu, tempat, penguji)
- Mengelola link penilaian dosen penguji
- Memvalidasi seminar selesai
- Merekap data seminar KP

---

## 4. Alur Proses Kerja Praktek

### 4.1 Diagram Alur Utama

```
┌──────────────┐     ┌──────────────────┐     ┌──────────────┐
│  PENGAJUAN   │────>│   PENDAFTARAN    │────>│  BIMBINGAN   │
│     KP       │     │       KP         │     │     KP       │
└──────────────┘     └──────────────────┘     └──────────────┘
                                                     │
                                                     ▼
┌──────────────┐     ┌──────────────────┐     ┌──────────────┐
│  SELESAI /   │<────│    JILID KP      │<────│  SEMINAR KP  │
│  WISUDA      │     │ (Pengumpulan     │     │              │
│              │     │  Akhir)          │     │              │
└──────────────┘     └──────────────────┘     └──────────────┘
```

### 4.2 Detail Status Per Tahap

#### Pengajuan KP
```
Mahasiswa Submit ──> [Review] ──> Prodi ACC ──> Plot Dosen Pembimbing
                         │
                         ├──> [Revisi] ──> Mahasiswa Submit Revisi
                         │
                         └──> [Ditolak] ──> Surat Penolakan
```

#### Pendaftaran KP
```
Mahasiswa Submit ──> [Review] ──> Admin ACC (+ input tahun masuk bimbingan)
                         │
                         └──> [Revisi] ──> Mahasiswa Submit Revisi
```

#### Bimbingan KP
```
Mahasiswa Submit Bab ──> [Review] ──> Dosen ACC ──> Lanjut Bab Berikutnya
                              │
                              └──> [Revisi] ──> Mahasiswa Submit Ulang
                              
* Tersedia juga: Bimbingan Manual (Offline)
  Mahasiswa Upload Lembar ──> Admin Validasi ──> ACC / Tolak
```

#### Seminar KP
```
Mahasiswa Daftar ──> Himpunan Verifikasi ──> Himpunan Jadwalkan
                          │                        │
                          └─ [Revisi Berkas]       ▼
                                             Dosen Penguji Nilai
                                                   │
                                                   ▼
                                          Himpunan Validasi Selesai
```

#### Jilid KP (Pengumpulan Akhir)
```
Mahasiswa Submit ──> [Review] ──> Admin/Fotokopi ACC
                         │              │
                         └─ [Revisi]    └──> Proses Jilid ──> Selesai
```

---

## 5. Panduan Mahasiswa

### 5.1 Login & Dashboard

1. Buka browser dan akses `https://ekapta.fastikom-unsiq.ac.id`
2. Pilih sistem **Kerja Praktek (KP)**
3. Login menggunakan NIM dan password yang telah diberikan
4. Setelah login, Anda akan diarahkan ke **Dashboard KP**

Dashboard menampilkan ringkasan status proses KP Anda secara keseluruhan.

### 5.2 Pengajuan KP

#### 5.2.1 Membuat Pengajuan Baru

1. Klik menu **Pengajuan KP** di navbar atas
2. Klik tombol **+ Buat Pengajuan KP** (jika belum ada pengajuan)
3. Isi formulir pengajuan:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Judul** | Judul Kerja Praktek yang akan dilakukan | ✅ |
| **Lokasi KP** | Nama instansi/perusahaan tempat KP | ✅ |
| **Alamat Instansi** | Alamat lengkap lokasi KP | ✅ |
| **Gambaran Singkat** | Deskripsi singkat tentang rencana KP | ✅ |
| **Bukti Diterima Instansi (.pdf)** | Surat/bukti penerimaan dari instansi (format PDF) | ✅ |
| **Dokumen Pendukung KP (.pdf, .zip, .rar)** | Proposal, surat permohonan, dll | ✅ |

4. Klik tombol **Submit**
5. Akan muncul notifikasi **"Berhasil melakukan pengajuan kerja Praktek"**

#### 5.2.2 Status Pengajuan

| Status | Warna | Keterangan |
|--------|-------|------------|
| **Review** | Abu-abu | Pengajuan sedang ditinjau oleh Prodi |
| **Revisi** | Kuning | Perlu perbaikan, lihat catatan Prodi |
| **Diterima** | Hijau | Pengajuan disetujui, lanjut ke Pendaftaran |
| **Ditolak** | Merah | Pengajuan ditolak, bisa download Surat Penolakan |

#### 5.2.3 Submit Revisi

Jika status **Revisi**:
1. Klik tombol **Submit Revisi** pada tabel pengajuan
2. Perbaiki data sesuai catatan dari Prodi
3. Klik **Submit**

#### 5.2.4 Edit Pengajuan

Jika masih berstatus **Revisi**:
1. Klik **Detail** pada pengajuan
2. Perbaiki field yang perlu diubah (Judul, Lokasi, Alamat, Gambaran, Dokumen)
3. Klik **Submit**

#### 5.2.5 Pengajuan Diterima

Jika status **Diterima**:
- Akan muncul tombol **Lembar Persetujuan** untuk mendownload PDF lembar persetujuan pembimbing
- Muncul notifikasi: *"Selamat! Pengajuan kerja Praktek anda sudah di Acc oleh Prodi, silahkan lakukan Pendaftaran Kerja Praktek."*
- Lanjutkan ke tahap **Pendaftaran KP**

### 5.3 Pendaftaran KP

#### 5.3.1 Mengisi Form Pendaftaran

1. Klik menu **Pendaftaran KP** di navbar
2. Halaman akan menampilkan info **Dosen Pembimbing** yang sudah di-plot oleh Prodi
3. Jika status masih kosong, klik form pendaftaran dan isi:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **NIM** | Otomatis terisi (read-only) | ✅ |
| **Nama Lengkap** | Otomatis terisi (read-only) | ✅ |
| **Prodi** | Otomatis terisi (read-only) | ✅ |
| **Dosen Pembimbing** | Otomatis terisi dari plotting (read-only) | ✅ |
| **Judul Kerja Praktek** | Otomatis terisi dari pengajuan (read-only) | ✅ |
| **Dokumen Acc. Kaprodi** | Download template, isi, lalu upload kembali | ✅ |
| **Bukti Transkrip Nilai** | Upload transkrip nilai terbaru | ✅ |
| **Lembar Persetujuan Pembimbing** | Upload lembar persetujuan yang sudah ditandatangani | ✅ |
| **Bukti Diterima Instansi** | Upload surat penerimaan dari instansi | ✅ |
| **Dokumen Pendukung** | Upload 2 sertifikat peserta seminar KP (dijadikan 1 file PDF) | ✅ |
| **Nomor Pembayaran** | Nomor PBXXXX pada Bukti Bayar FASTIKOM | ✅ |
| **Tanggal Pembayaran** | Tanggal transfer/bayar | ✅ |
| **Biaya KP** | Pilih jenis biaya yang sesuai | ✅ |

**Pilihan Biaya:**
- Program Reguler (S1 & D3): Rp. 400.000,-
- Perpanjang Program Reguler (S1 & D3): Rp. 200.000,-
- Program Kelas Karyawan: Rp. 400.000,-
- Perpanjang Program Kelas Karyawan: Rp. 200.000,-

4. Klik **Submit**

#### 5.3.2 Status Pendaftaran

| Status | Keterangan |
|--------|------------|
| **Review** | Sedang ditinjau Admin |
| **Revisi** | Perlu diperbaiki |
| **Diterima** | Pendaftaran disetujui, lanjut ke Bimbingan |

### 5.4 Bimbingan KP

Setelah pendaftaran disetujui, mahasiswa dapat melakukan bimbingan.

#### 5.4.1 Halaman Bimbingan

Halaman bimbingan menampilkan:
- **Countdown Timer**: Waktu tersisa sebelum batas berakhir bimbingan (6 bulan / 1 Semester)
- **Dosen Pembimbing**: Nama dosen pembimbing Anda
- **Tabel Bimbingan**: Daftar bab beserta status bimbingan
- **QR Code & Tracking Bimbingan**: Untuk melacak progres bimbingan

> ⚠️ **Perhatian**: Jika sampai batas waktu yang ditentukan belum menyelesaikan KP, maka KP dianggap gugur dan harus mengambil judul KP yang berbeda.

#### 5.4.2 Bagian Bimbingan

Bimbingan KP dibagi menjadi beberapa bagian (bab), contoh:

| No | Bagian | Status |
|----|--------|--------|
| 1 | Bab I | Diterima / Revisi / - |
| 2 | Bab II | Diterima / Revisi / - |
| 3 | Bab III | Review / Revisi / - |
| 4 | Bab IV | - |

#### 5.4.3 Submit Bimbingan Online

1. Pada tabel bimbingan, klik **Submit** pada bab yang akan di-submit
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Bagian Bimbingan** | Otomatis terisi (read-only) | ✅ |
| **Keterangan (Opsional)** | Catatan/keterangan untuk dosen | ❌ |
| **Dokumen Bimbingan (.pdf)** | Upload file bimbingan (maks. 5MB) | ✅ |

3. Klik **Submit**

#### 5.4.4 Submit Ulang (Jika Revisi)

Jika dosen memberikan status **Revisi**:
1. Klik **Submit Ulang** pada bab yang bersangkutan
2. Perbaiki file dan upload ulang
3. Klik **Submit**

#### 5.4.5 Bimbingan Manual (Offline)

Jika bimbingan dilakukan secara tatap muka/offline:

1. Pada tabel bimbingan, klik **Bimbingan Manual** pada bab yang tersedia
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Bagian Bimbingan** | Otomatis terisi (read-only) | ✅ |
| **File Laporan** | Link file laporan yang sudah diupload sebelumnya | - |
| **Lembar Bimbingan Manual** | Upload foto/scan lembar bimbingan offline (PDF, PNG, JPG, JPEG; maks. 5MB) | ✅ |
| **Tanggal Bimbingan Offline** | Tanggal dilaksanakannya bimbingan | ✅ |
| **Status Hasil Bimbingan Offline** | Pilih: ACC - Dosen menyetujui / Revisi - Masih ada perbaikan | ✅ |
| **Catatan** | Catatan hasil bimbingan offline dengan dosen | ✅ |

3. Klik **Submit**

> 📋 **Riwayat Bimbingan Manual**: Setelah submit, Anda bisa melihat riwayat pengajuan bimbingan manual beserta status (ACC/Ditolak) di bagian bawah halaman.

#### 5.4.6 Detail Bimbingan Manual

Setelah submit bimbingan manual, detail yang ditampilkan:

| Kolom | Keterangan |
|-------|------------|
| **Status Bimbingan** | ACC / Revisi |
| **Tanggal Bimbingan** | Tanggal bimbingan offline |
| **File Laporan** | File yang diupload |
| **Tanggal Ajuan** | Tanggal pengajuan di sistem |
| **Status Ajuan** | ACC / Ditolak oleh Admin |
| **Catatan Pembimbing** | Catatan dari dosen |
| **Catatan Ajuan** | Catatan dari mahasiswa |
| **File Lembar Bimbingan** | Scan lembar bimbingan offline |

#### 5.4.7 Notifikasi Selesai Bimbingan

Ketika seluruh bab sudah berstatus **Diterima**, akan muncul notifikasi:
> *"Selamat anda sudah bisa melakukan Pendaftaran Seminar KP"*

Dengan link langsung ke halaman **Pendaftaran Seminar KP**.

### 5.5 Seminar KP

#### 5.5.1 Mendaftar Seminar KP

1. Klik menu **Seminar KP** di navbar
2. Klik **Daftar Seminar** atau ikuti link dari notifikasi bimbingan
3. Isi formulir pendaftaran seminar:

**Bagian 1-4: Data & Dokumen**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Laporan KP** | Upload file laporan akhir KP (PDF/Word) | ✅ |
| **Lembar Pengesahan** | Upload lembar pengesahan yang sudah ditandatangani | ✅ |
| **Sertifikat Seminar KP 1-4** | Upload 4 sertifikat peserta seminar KP | ✅ |
| **Link Akses Produk** | Link ke produk/project KP (GitHub, Google Drive, dll) | ✅ |
| **Dokumen Penilaian (Opsional)** | Dokumen penilaian tambahan jika ada | ❌ |

**Bagian 5: Pembayaran Seminar**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Metode Pembayaran** | Pilih metode pembayaran yang tersedia (Cash/Transfer) | ✅ |
| **Upload Bukti Pembayaran** | Upload bukti transfer/pembayaran | ✅ |

> 💰 **Nominal Pembayaran**: Sesuai yang ditetapkan oleh Himpunan (contoh: Rp 25.000)  
> 💳 **Informasi Rekening**: Ditampilkan pada halaman pendaftaran

4. Klik **Submit Pendaftaran**

#### 5.5.2 Status Seminar

| Status | Warna | Keterangan |
|--------|-------|------------|
| **Review** | Abu-abu | Pendaftaran sedang diverifikasi Himpunan |
| **Revisi Berkas** | Kuning | Berkas perlu diperbaiki |
| **Menunggu jadwal** | - | Berkas diterima, menunggu dijadwalkan |
| **Dijadwalkan** | Biru | Jadwal sudah ditentukan |
| **Selesai** | Hijau | Seminar telah selesai |

#### 5.5.3 Submit Revisi Berkas

Jika status **Revisi Berkas**:
1. Akan muncul peringatan: *"Pendaftaran perlu direvisi. Silahkan perbaiki sesuai catatan dari Himpunan."*
2. Klik **Submit Revisi** dan perbaiki dokumen
3. Upload ulang dan klik **Submit Revisi**

#### 5.5.4 Melihat Jadwal Seminar

Jika status **Dijadwalkan**, detail jadwal ditampilkan:
- **Tanggal**: Hari & tanggal seminar
- **Waktu**: Jam pelaksanaan (WIB)
- **Lokasi/Link**: Tempat atau link online
- **Urutan**: Nomor urut presentasi

#### 5.5.5 Seminar Selesai

Setelah seminar selesai:
- Status berubah menjadi **Selesai** (badge hijau)
- Muncul notifikasi: *"Selamat! Seminar KP anda sudah selesai. Silahkan lanjut ke Jilid KP."*
- Lanjutkan ke tahap **Jilid KP**

### 5.6 Jilid KP (Pengumpulan Akhir)

#### 5.6.1 Submit Pengumpulan Akhir

1. Klik menu **Jilid KP** di navbar
2. Klik **+ Submit Jilid KP**
3. Isi formulir pengumpulan akhir:

**Data Mahasiswa** (otomatis terisi):

| Field | Keterangan |
|-------|------------|
| Email | Email mahasiswa |
| NIM | Nomor Induk Mahasiswa |
| Nama | Nama lengkap |
| Semester Pelaksanaan KP | Semester saat KP |
| Judul KP | Judul Kerja Praktek |
| Pembimbing KP | Nama dosen pembimbing |

**Informasi Penting (Batas Ukuran File):**
| Jenis File | Batas Ukuran |
|------------|-------------|
| Dokumen PDF (Lembar Pengesahan, Bimbingan, Revisi) | Maksimal 5MB per file |
| File Project (ZIP/RAR) | Maksimal 30MB |
| Laporan PDF/Word | Maksimal 10MB per file |
| Form Nilai & Berita Acara | Maksimal 1MB per file |

> 💡 **Tips**: Jika file terlalu besar, compress/kompres terlebih dahulu atau upload ke Google Drive dan gunakan link.

**Dokumen yang Harus Diupload:**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Laporan KP (PDF/Word)** | Laporan akhir KP | ✅ |
| **Link Repository** | Link GitHub/GitLab project | ✅ |
| **File Project / Program KP** | File project dikompres dalam .zip atau .rar (maks 100 MB) | ✅ |
| **Form Nilai KP** | Download formulir, isi oleh instansi, upload kembali | ✅ |
| **Berita Acara Serah Terima Produk** | Berita acara serah terima produk dengan instansi/tempat KP | ✅ |
| **Panduan Penggunaan Produk KP** | Format .docx atau Link Google Drive | ✅ |

4. Klik **Submit Jilid KP**

#### 5.6.2 Status Jilid KP

| Status | Warna | Keterangan |
|--------|-------|------------|
| **Review** | Abu-abu | Dokumen sedang ditinjau |
| **Revisi** | Kuning | Dokumen perlu diperbaiki |
| **Valid** | Biru | Dokumen sudah valid, menunggu proses jilid |
| **Selesai** | Hijau | Proses jilid selesai |

#### 5.6.3 Revisi Dokumen

Jika status **Revisi**:
- Akan muncul peringatan kuning: **"DOKUMEN PERLU REVISI"**
- Keterangan: *"Dokumen Anda perlu diperbaiki. Silakan submit ulang dengan mengklik tombol Submit Revisi."*
- Klik **Submit Revisi**, perbaiki dokumen, dan upload ulang

#### 5.6.4 Dokumen Valid

Jika status **Valid**:
- Muncul notifikasi biru: **"DOKUMEN VALID - MENUNGGU PROSES JILID"**
- Keterangan: *"Dokumen KP sudah dikonfirmasi oleh admin dan siap untuk dijilid. Silahkan konfirmasi dan melakukan pembayaran ke Fotokopi FASTIKOM dengan membawa dokumen-dokumen asli yang akan disertakan dalam penjilidan KP seperti lembar keaslian KP, lembar pengesahan, lembar bimbingan, lampiran-lampiran, dll."*

---

## 6. Panduan Admin / Prodi

### 6.1 Login Admin

1. Akses halaman login Admin
2. Masukkan username dan password admin
3. Pilih menu **Menu Kerja Praktek** di sidebar

### 6.2 Validasi Pengajuan KP

1. Klik **Validasi Pengajuan KP** di sidebar
2. Pilih pengajuan dengan status **Review**
3. Klik **Detail** untuk melihat detail pengajuan

**Informasi yang ditampilkan:**
- NIM, Nama, Prodi mahasiswa
- Judul KP + tombol **Check Plagiarism**
- Lokasi KP, Alamat Instansi
- Bukti Diterima Instansi (downloadable)
- File Pendukung (downloadable)
- Gambaran Singkat
- Tanggal pengajuan

**Aksi yang tersedia:**

| Tombol | Fungsi |
|--------|--------|
| **← Kembali** | Kembali ke daftar pengajuan |
| **Revisi Pengajuan** | Memberikan catatan revisi + lampiran opsional |
| **Acc Pengajuan** | Menyetujui pengajuan (dengan catatan opsional) |
| **Tolak Pengajuan** | Menolak pengajuan (dengan catatan wajib) |

#### 6.2.1 ACC Pengajuan

Saat klik **Acc Pengajuan**, muncul dialog:
- Informasi: *"Setelah ACC, lakukan Plotting Dosen Pembimbing untuk menentukan dosen yang akan membimbing mahasiswa."*
- **Catatan (Opsional)**: Catatan untuk mahasiswa
- Klik **Setujui Pengajuan**

#### 6.2.2 Plotting Dosen Pembimbing

Setelah ACC, klik **Dosen Pembimbing** untuk memilih dosen pembimbing:
1. Klik **Lihat Rekap Bimbingan Dosen** (opsional, untuk melihat beban bimbingan dosen)
2. Pilih **Dosen Pembimbing** dari dropdown
3. Klik **Simpan**

#### 6.2.3 Revisi Pengajuan

Saat klik **Revisi Pengajuan**, muncul dialog:
- **Catatan**: Tuliskan catatan revisi untuk mahasiswa
- **Lampiran**: Upload file lampiran (opsional)
- Klik **Simpan**

#### 6.2.4 Tolak Pengajuan

Saat klik **Tolak Pengajuan**, muncul dialog:
- **Catatan**: Tuliskan alasan penolakan (wajib)
- **Lampiran**: Upload file lampiran (opsional)
- Klik **Simpan**

### 6.3 Validasi Pendaftaran KP

1. Klik **Validasi Pendaftaran KP** di sidebar
2. Review data pendaftaran mahasiswa

**Aksi yang tersedia:**

| Tombol | Fungsi |
|--------|--------|
| **Revisi Pendaftaran** | Memberikan catatan revisi |
| **Acc Pendaftaran** | Menyetujui pendaftaran |

#### 6.3.1 ACC Pendaftaran

Saat ACC, muncul dialog **Konfirmasi Acc Pendaftaran Kerja Praktek**:
- Menampilkan NIM, Nama Lengkap, Prodi
- **Tahun masuk bimbingan**: Input tahun masuk (untuk menentukan bagian bimbingan)
- Catatan: *"Jika ingin mengubah tahun masuk bimbingan, maka ubah data tahun masuk mahasiswa!"*
- Klik **Konfirmasi**

### 6.4 Progres Bimbingan KP

1. Klik **Progres Bimbingan KP** di sidebar
2. Lihat tabel laporan bimbingan KP dengan filter **Bimbingan Aktif** / **Bimbingan Selesai**
3. Dapat mencetak **Surat Tugas Bimbingan KP**

**Kolom tabel:**
- Mahasiswa, Kontak (WhatsApp), Prodi, Judul KP
- Status Bimbingan (SELESAI / AKTIF)
- Terakhir Bimbingan, Tanggal Pendaftaran KP
- Dosen Pembimbing (beserta progres per bab)
- Penguji Seminar KP, Tanggal Seminar KP, Tanggal Jilid KP

**Aksi:** Detail Bimbingan, Surat Tugas Bimbingan KP (cetak PDF)

### 6.5 Validasi Bimbingan KP (Manual/Offline)

1. Klik **Validasi Bimbingan KP** di sidebar
2. Ditampilkan informasi mahasiswa dan tabel validasi bimbingan manual

**Kolom tabel:**
| Kolom | Keterangan |
|-------|------------|
| No | Nomor urut |
| Tanggal | Tanggal bimbingan offline |
| Detail | Bab & Status mahasiswa |
| Catatan Pembimbing | Catatan dari dosen |
| File Laporan | Link file laporan |
| Lembar Bimbingan | Foto/scan lembar bimbingan |
| Status | PENDING / ACC / DITOLAK |
| Aksi | ACC / Tolak |

### 6.6 Data Seminar KP

1. Klik **Data Seminar KP** / **Seminar KP** di sidebar
2. Lihat detail mahasiswa yang sedang/sudah mengikuti seminar
3. Input nilai manual:

**Input Manual Nilai KP:**

| Komponen Penilaian | Keterangan |
|-------------------|------------|
| **Nilai Instansi** | Mahasiswa upload bukti nilai instansi, prodi input manual |
| **Nilai Dosen Pembimbing** | Sudah diisi via sistem (oleh dosen) |
| **Nilai Dosen Penguji** | Sudah diisi via sistem (oleh dosen penguji) |
| **NILAI AKHIR** | Dihitung otomatis berdasarkan bobot penilaian |

> ⚠️ **Catatan Bobot Penilaian:**
> - **Reguler**: Penilaian terdiri dari 3 komponen (Pembimbing + Penguji + Instansi). Persentase mengikuti pengaturan admin.

Klik **Simpan Perubahan Nilai**

### 6.7 Data Jilid KP

1. Review dokumen pengumpulan akhir mahasiswa
2. Verifikasi kelengkapan: Link Project, Form Nilai, Berita Acara, Panduan Penggunaan
3. Berikan **Revisi** atau **Selesaikan Proses Jilid** (+ input jumlah pembayaran)

---

## 7. Panduan Dosen

### 7.1 Login Dosen

1. Akses halaman login Dosen
2. Masukkan NIDN/username dan password
3. Dashboard menampilkan ringkasan mahasiswa bimbingan

### 7.2 Review Bimbingan KP

1. Klik **Review Bimbingan KP** di sidebar (atau dari notifikasi)
2. Lihat detail bimbingan mahasiswa:
   - NIM, Nama, Prodi, Judul KP
   - Bagian (Bab) yang disubmit
   - Keterangan dari mahasiswa
   - Progress bar
   - Tanggal submit
   - File lampiran (downloadable)
   - **Bagian Bimbingan Kerja Praktek** (input text untuk catatan)

3. Pilih aksi:

| Tombol | Fungsi |
|--------|--------|
| **← Kembali** | Kembali ke daftar |
| **Revisi bimbingan** | Memberikan catatan revisi |
| **Acc bimbingan** | Menyetujui bimbingan bab tersebut |

### 7.3 Penilaian Pembimbing

1. Klik **Penilaian Pembimbing** di sidebar
2. Lihat daftar mahasiswa yang sudah **Lulus Seminar**
3. Klik **Beri Nilai** pada mahasiswa

**Dialog Penilaian Pembimbing:**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Nilai Pembimbing** | Nilai 0-100 | ✅ |
| **Catatan Akhir Pembimbing** | Catatan akhir (opsional) | ❌ |
| **Dokumen Penilaian (Opsional)** | Upload file PDF pendukung | ❌ |

Klik **Simpan Nilai**

### 7.4 Penilaian Seminar (Dosen Penguji)

Jika Anda ditunjuk sebagai dosen penguji seminar:
1. Buka link penilaian yang dikirim oleh Himpunan
2. Halaman **Penilaian Seminar Kerja Praktek** menampilkan:
   - Informasi sesi seminar (tanggal, waktu, tempat)
   - Daftar mahasiswa peserta
3. Untuk setiap mahasiswa, isi:
   - **Nilai (0-100)**: Nilai seminar
   - **Catatan (opsional)**: Catatan untuk mahasiswa
   - **Dokumen (opsional)**: Upload file pendukung

4. Klik **Submit Semua Penilaian**

> ⚠️ **Perhatian**: Setelah submit, link ini tidak bisa digunakan lagi!

---

## 8. Panduan Himpunan

### 8.1 Login Himpunan

1. Akses halaman login Himpunan
2. Masukkan username dan password himpunan (contoh: HIMTI)

### 8.2 Pengaturan Pembayaran

1. Klik **Pengaturan Pembayaran** di sidebar
2. Halaman menampilkan **Metode Pembayaran** yang aktif

**Menambah Metode Pembayaran:**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Tipe** | Bank / E-Wallet / Cash | ✅ |
| **Nama** | Nama bank/e-wallet (contoh: BNI) | ✅ |
| **Nomor** | Nomor rekening/e-wallet | ✅ |
| **Nama Pemilik** | Nama pemilik rekening (contoh: HIMATIF UNSIQ) | ✅ |

Klik **+ Tambah Metode Pembayaran**

**Preview Info Mahasiswa** (ditampilkan di sidebar kanan):
- Biaya Seminar: Rp XX.XXX
- Metode Pembayaran yang tersedia

### 8.3 Verifikasi Pendaftaran Seminar

1. Klik **Verifikasi Pendaftaran** di sidebar
2. Review pendaftaran seminar mahasiswa
3. Cek kelengkapan berkas:
   - Lembar Pengesahan
   - Bukti Pembayaran
   - Sertifikat Seminar KP (4 file)
   - Link Produk KP
   - Jumlah Bayar
4. Pilih aksi:

| Tombol | Fungsi |
|--------|--------|
| **Revisi Pendaftaran** | Minta mahasiswa memperbaiki berkas |
| **Acc Pendaftaran** | Setujui pendaftaran seminar |

### 8.4 Penjadwalan Seminar

1. Klik **Penjadwalan Seminar** di sidebar
2. Lihat daftar **Mahasiswa Siap Dijadwalkan** (badge hijau menunjukkan jumlah)
3. Klik **+ Buat Sesi Seminar**

**Dialog Buat Sesi Seminar Baru:**

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Tanggal Seminar** | Pilih tanggal seminar | ✅ |
| **Jam Mulai** | Waktu mulai | ✅ |
| **Jam Selesai** | Waktu selesai | ✅ |
| **Tempat / Link Online** | Lokasi atau link Zoom/Meet | ✅ |
| **Dosen Penguji** | Pilih dosen penguji dari dropdown | ✅ |
| **Jumlah Mahasiswa per Sesi** | Kapasitas per sesi (default: 8) | ✅ |
| **Catatan Teknis** | Catatan untuk penguji (opsional) | ❌ |
| **Pilih Mahasiswa** | Centang mahasiswa yang akan ikut sesi ini | ✅ |

Klik **Buat Sesi**

4. Setelah dibuat, sesi muncul di tabel **Daftar Sesi Seminar**:

| Kolom | Keterangan |
|-------|------------|
| Tanggal | Tanggal seminar |
| Waktu | Jam mulai - selesai |
| Tempat | Lokasi / link |
| Penguji | Nama dosen penguji |
| Peserta | Jumlah mahasiswa (badge) |
| Status Link | Aktif / Sudah Digunakan |
| Aksi | Lihat Detail / Duplikat / Hapus |

### 8.5 Detail Sesi Seminar

1. Klik ikon mata (👁) pada sesi seminar
2. Halaman **Detail Sesi Seminar** menampilkan:

**Informasi Sesi:**
- Tanggal, Waktu, Tempat, Penguji, Peserta

**Daftar Peserta Seminar:**
| Urutan | NIM | Nama | Judul | Nilai | Status |
|--------|-----|------|-------|-------|--------|
| 1 | test123 | mahasiswa testing | apa aja | 95.00 | Menunggu Validasi |

**Link Penilaian Dosen:**
- Status link (Aktif / Sudah Digunakan)
- Tanggal digunakan

3. Setelah dosen penguji submit nilai, klik **Validasi Seminar Selesai** untuk menyelesaikan sesi

### 8.6 Rekap Seminar KP

Klik **Rekap Seminar KP** untuk melihat rekap keseluruhan data seminar.

---

## 9. Master Data

Menu Master Data hanya tersedia untuk **Admin**.

### 9.1 Fakultas

**Setting Fakultas** (`/kp/fakultas/setting/1`):

| Pengaturan | Fungsi |
|-----------|--------|
| **Nama Fakultas** | Menampilkan nama fakultas |
| **Edit Stempel Fakultas** | Upload/ganti gambar stempel (untuk dokumen cetak PDF) |
| **Dekan** | Nama dekan aktif |
| **Edit TTD Dekan** | Upload/ganti gambar tanda tangan dekan (untuk dokumen cetak PDF) |

**Program Studi:**
- Daftar prodi yang terdaftar (Teknik Mesin, Teknik Sipil, Arsitektur, Teknik Informatika, Manajemen Informatika, TI)
- Dapat menambah/menghapus prodi

**Dekan Fakultas:**
- Mengelola data dekan (Nama, Periode, NIDN)
- Tambahkan / Import Dekan Fakultas

### 9.2 Prodi

Mengelola data program studi termasuk:
- Detail prodi
- Persentase bobot nilai KP
- Bagian Bimbingan (mengedit nama bab, tahun masuk, syarat seminar)

**Edit Bagian Bimbingan KP:**

| Field | Keterangan |
|-------|------------|
| Nama Bagian Bimbingan | Contoh: Bab I, Bab II, dll |
| Tahun Masuk | Tahun angkatan yang menggunakan bagian ini |
| Sebagai Syarat Seminar | Centang jika bab ini menjadi syarat seminar |
| Sebagai Syarat Seminar KP | Centang jika bab ini menjadi syarat seminar KP |

### 9.3 Mahasiswa

Mengelola data mahasiswa:
- Lihat, tambah, edit, hapus data mahasiswa
- Import data mahasiswa dari file Excel

### 9.4 Dosen

Mengelola data dosen:
- Lihat, tambah, edit, hapus data dosen
- Setting NIDN, nama, gelar, TTD (tanda tangan)

### 9.5 Himpunan

Mengelola data himpunan mahasiswa yang berwenang mengelola seminar.

---

## 10. Dokumen Cetak (PDF)

Sistem EKAPTA menghasilkan berbagai dokumen cetak PDF secara otomatis:

### 10.1 Daftar Dokumen

| No | Dokumen | Dicetak Oleh | Tahap |
|----|---------|-------------|-------|
| 1 | **Surat Tugas Pembimbingan KP** | Admin/Prodi | Setelah Pendaftaran ACC |
| 2 | **Lembar Bimbingan KP** | Admin/Prodi | Setelah Pendaftaran ACC |
| 3 | **Lembar Persetujuan Pembimbing** | Mahasiswa | Setelah Pengajuan ACC |
| 4 | **Lembar Pengesahan** | Mahasiswa | Untuk Seminar |
| 5 | **Lembar Pernyataan Keaslian** | Mahasiswa | Untuk Jilid |
| 6 | **Berita Acara Seminar** | Admin | Setelah Seminar Selesai |
| 7 | **Berita Acara Serah Terima** | Mahasiswa | Untuk Jilid |
| 8 | **Formulir Nilai Akhir** | Admin | Untuk Penilaian |
| 9 | **Surat Penolakan** | Admin | Jika Pengajuan Ditolak |

### 10.2 Surat Tugas Pembimbingan KP

Dokumen resmi 2 halaman berisi:

**Halaman 1 - Surat Tugas:**
- Kop Surat UNSIQ / FASTIKOM
- Nomor Surat: XXX/ST.KP/FASTIKOM-UNSIQ/[bulan romawi]/[tahun]
- QR Code verifikasi
- Data dosen pembimbing
- Data mahasiswa (Nama, NIM, Prodi, Tanggal Pembayaran, Judul KP)
- Paragraf penjelasan bimbingan dan batas waktu (6 bulan / 1 Semester)
- Tanda tangan Dekan + stempel fakultas
- NB. Batas Maksimal sampai pada: [tanggal]

**Halaman 2 - Lembar Bimbingan KP:**
- Kop Surat
- Data mahasiswa + QR Code
- Tabel bimbingan (No, Tanggal, Keterangan, Tanda Tangan)

### 10.3 Lembar Persetujuan Pembimbing

Dokumen yang berisi pernyataan dosen pembimbing menerima/tidak menerima bimbingan KP mahasiswa tertentu.
- Ditandatangani oleh Kaprodi dan Calon Dosen Pembimbing

---

## 11. FAQ & Troubleshooting

### 11.1 Pertanyaan Umum

**Q: Berapa lama batas waktu bimbingan KP?**  
A: Bimbingan KP maksimal dilakukan selama **6 bulan (1 Semester)**. Jika melewati batas waktu, KP dianggap gugur.

**Q: Apa yang terjadi jika KP melewati batas waktu?**  
A: KP dianggap gugur dan mahasiswa harus mengambil judul KP yang berbeda dari judul sebelumnya.

**Q: Bagaimana jika dosen pembimbing tidak merespon bimbingan online?**  
A: Gunakan fitur **Bimbingan Manual (Offline)** untuk merekam bimbingan tatap muka, lalu upload bukti lembar bimbingan ke sistem.

**Q: Format file apa saja yang diterima?**  
A: 
- Dokumen: PDF, DOC, DOCX
- Lampiran bimbingan: PDF (maks. 5MB)
- File project: ZIP, RAR (maks. 30-100MB)
- Foto lembar bimbingan: PDF, PNG, JPG, JPEG (maks. 5MB)

**Q: Berapa biaya KP?**  
A:
- Program Reguler (S1 & D3): Rp. 400.000,-
- Perpanjang Program Reguler: Rp. 200.000,-
- Program Kelas Karyawan: Rp. 400.000,-
- Perpanjang Kelas Karyawan: Rp. 200.000,-

**Q: Berapa biaya seminar KP?**  
A: Ditentukan oleh Himpunan. Cek informasi pembayaran di halaman pendaftaran seminar.

**Q: Bagaimana cara mengetahui progres bimbingan saya?**  
A: Klik menu **Bimbingan KP**, Anda bisa melihat tabel progres per bab dan countdown timer batas waktu. Anda juga bisa klik **Tracking Bimbingan** atau scan QR Code.

### 11.2 Troubleshooting

**Masalah: Tidak bisa login**  
- Pastikan NIM dan password benar
- Hubungi admin prodi untuk reset password

**Masalah: File gagal diupload**  
- Periksa ukuran file (tidak melebihi batas)
- Pastikan format file sesuai yang diminta
- Coba kompres file terlebih dahulu

**Masalah: Status pendaftaran tidak berubah**  
- Proses validasi memerlukan waktu
- Hubungi admin prodi untuk informasi lebih lanjut

**Masalah: Tombol Submit tidak aktif**  
- Pastikan semua field wajib (bertanda *) sudah diisi
- Pastikan format file sesuai ketentuan

**Masalah: Dokumen PDF tidak menampilkan stempel/TTD**  
- Stempel dan TTD dikelola oleh Admin di menu Master Data > Fakultas
- Hubungi Admin untuk memastikan gambar stempel dan TTD sudah diupload

---

## LAMPIRAN

### A. Alur Status Pengajuan KP

```
┌─────────┐    Submit     ┌─────────┐    ACC       ┌──────────┐
│  Draft  │ ──────────>  │ Review  │ ──────────> │ Diterima │
└─────────┘              └─────────┘              └──────────┘
                              │                        │
                    Revisi    │                  Plot Dosen
                              ▼                        │
                         ┌─────────┐                   ▼
                         │ Revisi  │           ┌──────────────┐
                         └─────────┘           │ Pendaftaran  │
                              │                └──────────────┘
                    Submit    │
                    Revisi    │
                              ▼
                         ┌─────────┐    Tolak    ┌──────────┐
                         │ Review  │ ──────────> │ Ditolak  │
                         └─────────┘              └──────────┘
```

### B. Alur Status Bimbingan KP

```
Per Bab:
┌─────────┐    Submit     ┌─────────┐    ACC       ┌──────────┐
│   Bab   │ ──────────>  │ Review  │ ──────────> │ Diterima │
└─────────┘              └─────────┘              └──────────┘
                              │
                    Revisi    │
                              ▼
                         ┌─────────┐  Submit Ulang  ┌─────────┐
                         │ Revisi  │ ──────────────> │ Review  │
                         └─────────┘                 └─────────┘

Semua Bab Diterima ──> Bisa Mendaftar Seminar KP
```

### C. Alur Status Seminar KP

```
┌─────────────┐  Submit  ┌─────────┐  Verifikasi  ┌────────────────┐
│ Pendaftaran │ ──────> │ Review  │ ──────────>  │ Menunggu       │
│   Seminar   │         └─────────┘               │ Jadwal         │
└─────────────┘              │                    └────────────────┘
                    Revisi   │                          │
                    Berkas   │                    Dijadwalkan
                             ▼                          │
                        ┌──────────┐                    ▼
                        │  Revisi  │             ┌──────────────┐
                        │  Berkas  │             │  Dijadwalkan │
                        └──────────┘             └──────────────┘
                                                        │
                                                 Dosen Nilai +
                                                 Validasi
                                                        │
                                                        ▼
                                                 ┌──────────┐
                                                 │ Selesai  │
                                                 └──────────┘
```

### D. Kontak & Dukungan

| Keperluan | Kontak |
|-----------|--------|
| Masalah Teknis Sistem | Admin EKAPTA FASTIKOM |
| Pembayaran KP | Mas Harri (Telegram: @harrrrrrrrrrr / WA: 085643647643) |
| Seminar KP | Himpunan Mahasiswa (HIMTI/HIMATIF) |
| Bimbingan | Dosen Pembimbing |

---

*Dokumen ini dibuat sebagai panduan penggunaan Sistem EKAPTA Kerja Praktek (KP) FASTIKOM UNSIQ. Untuk informasi lebih lanjut, silakan hubungi Admin EKAPTA.*
