# EKAPTA — Sistem Informasi Kerja Praktek FASTIKOM

**Sistem informasi kerja praktek untuk mahasiswa FASTIKOM UNSIQ.**

## Deskripsi

EKAPTA adalah aplikasi web untuk mengelola proses kerja praktek mahasiswa di FASTIKOM UNSIQ. Fitur utama:

- Pendaftaran KP
- Pengajuan proposal
- Pengumpulan laporan
- Penilaian seminar
- Manajemen dosen pembimbing

## Instalasi

### Prasyarat

- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js (untuk frontend)

### Langkah-langkah

1. Clone repo:
   ```bash
   git clone https://github.com/baqa27/ekapta-app-kp.git
   cd ekapta-app-kp
   ```

2. Install dependency:
   ```bash
   composer install
   npm install
   ```

3. Konfigurasi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Migrasi database:
   ```bash
   php artisan migrate --seed
   ```

5. Jalankan server:
   ```bash
   php artisan serve
   npm run dev
   ```

## Dokumentasi Keamanan

Lihat [docs/php2shell-assessment.md](docs/php2shell-assessment.md) untuk detail penanganan CVE-2026-109618/109619 (PHP2Shell).

## Kontribusi

Silakan buat issue untuk laporan bug atau permintaan fitur. Pull request diterima untuk perbaikan bug.

## Lisensi

MIT License. Lihat [LICENSE](LICENSE) untuk detail.
