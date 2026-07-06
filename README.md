# EKAPTA - Sistem Informasi Tugas Akhir & Kerja Praktek
## UNSIQ Fakultas Teknik dan Ilmu Komputer

Sistem informasi untuk mengelola proses Tugas Akhir (TA) dan Kerja Praktek (KP) mahasiswa FASTIKOM UNSIQ.

> 📖 **Quick Start**: Lihat `QUICK_START.md` untuk panduan cepat memulai development

---

## FITUR UTAMA

### Tugas Akhir (TA)
- Pengajuan judul TA
- Pendaftaran seminar proposal
- Bimbingan TA (BAB I-V, Produk, Full Laporan)
- Seminar proposal
- Ujian TA
- Pengumpulan jilid

### Kerja Praktek (KP)
- Pengajuan KP
- Pendaftaran KP
- Bimbingan KP (BAB I-IV, Full Laporan, Produk)
- Seminar KP
- Pengumpulan jilid KP

---

## TEKNOLOGI

- **Framework**: Laravel 11
- **Database**: MySQL
- **Frontend**: Bootstrap 5, AdminLTE
- **Server**: PHP 8.2+

---

## INSTALASI

### 1. Clone Repository
```bash
git clone <repository-url>
cd ekapta-app
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database
```bash
# Buat database: unsiq_ekapta
# Edit .env sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD

# Migrate & Seed
php artisan migrate
php artisan db:seed --class=CompleteTestingSeeder
```

### 5. Run Server
```bash
php artisan serve --port=9000
```

Akses: `http://localhost:9000`

---

## AKUN TESTING

Lihat file: `AKUN_TESTING.md`

---

## DOKUMENTASI

| File | Deskripsi |
|------|-----------|
| `QUICK_START.md` | 🚀 Panduan cepat memulai development |
| `CODE_STANDARDS.md` | 📋 Standar penulisan kode |
| `CLEANUP_SUMMARY.md` | ✅ Status cleanup & checklist |
| `CLEANUP_REPORT.md` | 📊 Laporan cleanup lengkap |
| `AKUN_LOGIN.md` | 🔐 Akun login lengkap |
| `AKUN_TESTING.md` | 👤 Quick reference akun testing |

### Dokumentasi Lainnya
- **Setup Database**: `PANDUAN_SETUP_DATABASE_KP.md` (root folder)
- **Integrasi KP**: `README_INTEGRASI_KP.md` (root folder)

---

## STRUKTUR FOLDER

```
ekapta-app/
├── app/                # Application logic
│   ├── Http/          # Controllers, Middleware
│   ├── Models/        # Eloquent models
│   └── Helpers/       # Helper functions
├── database/          # Migrations, Seeders
├── resources/         # Views, Assets
├── routes/            # Route definitions
└── public/            # Public assets
```

---

## MAINTENANCE

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Reset Database
```bash
php artisan migrate:fresh
php artisan db:seed --class=CompleteTestingSeeder
```

### Code Quality
- Lihat `CODE_STANDARDS.md` untuk standar penulisan kode
- Lihat `CLEANUP_SUMMARY.md` untuk status cleanup

---

## SUPPORT

Untuk bantuan teknis, hubungi tim IT FASTIKOM UNSIQ.

---

**© 2024-2026 UNSIQ Fakultas Teknik dan Ilmu Komputer**
