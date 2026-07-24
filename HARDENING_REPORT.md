# HARDENING_REPORT.md — Laporan Hardening & Panduan Produksi

## Ringkasan Eksekutif DevSecOps

Dokumen ini merupakan panduan teknis dan laporan hardening aplikasi **EKAPTA** (Laravel 8 / PHP 8.1 / MySQL) untuk penggelaran di server produksi.

Seluruh perbaikan kode telah diimplementasikan langsung pada source code. Panduan ini mencakup langkah-langkah konfigurasi server, file system, web server, dan monitoring yang perlu diselesaikan sebelum go-live.

---

## 1. Konfigurasi `.env` Produksi

Salin `.env.example` ke `.env` di server produksi dan isi dengan nilai yang sesuai:

```env
APP_NAME="EKAPTA FASTIKOM"
APP_ENV=production          # ← WAJIB production
APP_DEBUG=false             # ← WAJIB false di produksi
APP_URL=https://your-domain.ac.id

LOG_CHANNEL=daily           # Log per hari agar mudah dirotasi
LOG_LEVEL=info              # Jangan 'debug' di produksi

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true  # ← WAJIB true di produksi (HTTPS only)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_produksi
DB_USERNAME=nama_user_db
DB_PASSWORD=password_kuat_yang_unik

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-produksi@domain.ac.id
MAIL_PASSWORD=app-password-gmail-baru    # ← Buat App Password Gmail baru
MAIL_ENCRYPTION=tls

GOOGLE_DRIVE_CLIENT_SECRET=ganti-dengan-nilai-baru   # ← Rotate credentials!
GOOGLE_DRIVE_REFRESH_TOKEN=ganti-dengan-nilai-baru   # ← Rotate credentials!
```

> ⚠️ **PENTING:** Google Drive credentials yang ada di repositori sebelumnya harus di-revoke dan di-regenerate karena sudah terekspos ke publik.

---

## 2. Langkah Rotasi Credential Google Drive (WAJIB)

Karena credential Google Drive sebelumnya terekspos di `.env.example` di repositori publik:

1. Buka [Google Cloud Console](https://console.cloud.google.com)
2. Navigasi ke **APIs & Services > Credentials**
3. Temukan OAuth 2.0 Client ID yang digunakan EKAPTA
4. Klik **Regenerate Secret** untuk mendapatkan Client Secret baru
5. Revoke refresh token lama dari [Google Account Security](https://myaccount.google.com/permissions)
6. Lakukan re-authorize OAuth untuk mendapatkan Refresh Token baru
7. Update `.env` di server produksi dengan credential baru

---

## 3. Hak Akses File System (Linux/Unix Production Server)

```bash
# Set kepemilikan ke user web server (contoh: www-data untuk Nginx/Apache)
chown -R www-data:www-data /var/www/html/ekapta-app

# Hak akses standar direktori dan file
find /var/www/html/ekapta-app -type d -exec chmod 755 {} \;
find /var/www/html/ekapta-app -type f -exec chmod 644 {} \;

# Direktori yang perlu writable untuk Laravel
chmod -R 775 /var/www/html/ekapta-app/storage
chmod -R 775 /var/www/html/ekapta-app/bootstrap/cache

# Pastikan file .env hanya bisa dibaca oleh owner
chmod 600 /var/www/html/ekapta-app/.env
```

---

## 4. Konfigurasi Nginx Security (Hardening Web Server)

Tambahkan konfigurasi berikut pada blok `server` Nginx:

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.ac.id;

    # Sembunyikan versi Nginx
    server_tokens off;

    # Blokir eksekusi PHP di direktori upload/storage
    location ~ ^/(storage|public/lampirans?)/.*\.php$ {
        deny all;
        return 404;
    }

    # Blokir akses langsung ke file sensitif
    location ~ /\.(env|git|htaccess) {
        deny all;
        return 404;
    }

    # Blokir akses ke file backup lama
    location ~* \.(php\.old|php\.bak|zip|sql|log)$ {
        deny all;
        return 404;
    }

    # Limit ukuran request (mencegah DoS upload besar)
    client_max_body_size 10M;
}
```

---

## 5. Artisan Commands untuk Produksi

Jalankan perintah berikut setelah deploy ke server produksi:

```bash
# Generate application key (jika belum ada)
php artisan key:generate

# Cache konfigurasi untuk performa dan keamanan
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Buat symbolic link storage
php artisan storage:link

# Jalankan migrasi database
php artisan migrate --force
```

---

## 6. Pemeliharaan Keamanan Berkala

| Frekuensi | Tindakan |
|---|---|
| Setiap minggu | Review `storage/logs/laravel.log` untuk anomali login gagal |
| Setiap bulan | Jalankan `composer audit` untuk cek CVE dependency |
| Setiap 3 bulan | Rotate password database dan SMTP |
| Setiap 6 bulan | Review dan update dependency `composer update` |
| Setiap tahun | Re-audit keamanan menyeluruh |

---

## 7. Ringkasan Status Hardening

| Kategori | Status | Catatan |
|---|---|---|
| HTTP Security Headers | ✅ Selesai | Middleware SecurityHeaders aktif |
| Session Cookie Security | ✅ Selesai | secure=true, httpOnly=true, sameSite=lax |
| Path Traversal Protection | ✅ Selesai | Regex deterministik |
| File Upload Security | ✅ Selesai | Whitelist ekstensi + MIME server-side |
| Open Redirect | ✅ Selesai | safeRedirectUrl() aktif |
| Brute Force Protection | ✅ Selesai | throttle:5,1 pada semua login |
| IDOR Protection | ✅ Selesai | Ownership check di editJudul |
| Exception Handling | ✅ Selesai | Handler upload file friendly |
| Credential Exposure | ✅ Selesai | .env.example dibersihkan |
| Credential Rotation | 🔲 Diperlukan | Rotate Google Drive OAuth + SMTP |
| Logout CSRF | 🔲 Roadmap | GET logout perlu dimigrasi ke POST |
| Web Server Hardening | 🔲 Diperlukan | Konfigurasi Nginx sesuai panduan di atas |
