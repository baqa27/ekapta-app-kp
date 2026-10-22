# PHP2Shell Security Assessment — EKAPTA

Tanggal: 2026-10-22
Branch: `security/php2shell-hardening` (dari `kp/master` @ `67da32a`)
Penulis: audit keamanan internal (asisten AI), dieskalasi ke admin FASTIKOM untuk tindakan lanjutan.

---

## 1. Ringkasan eksekutif

**PHP2Shell** adalah eksploitasi kombinasi dua kerentanan di PHP CGI (`php-cgi`) yang berjalan di Apache:

| CVE | Nama | Efek |
|---|---|---|
| CVE-2024-4577 (prekursor, masih relevan sebagai pola) | Argument Injection | Modul `mod_cgi`/CGI di Windows memperlakukan teks dengan encoding `Shift-JIS`/`Big5` pada path info, memungkinkan penyisipan argumen (`-d`) ke binary `php-cgi` |
| CVE-2026-109618 | PHP2Shell primitif | Mengubah argumen injection menjadi **eksekusi kode jarak jauh (RCE)** melalui `php://input` stream wrapper, melewati `disable_functions` dan `open_basedir` |
| CVE-2026-109619 | PHP2Shell path traversal | **Traverse direktori** di server CGI, memungkinkan akses/eksekusi file di luar `open_basedir`, termasuk file di luar document root aplikasi |

**Status aplikasi:** kode aplikasi sendiri tidak mengandung kerentanan yang dieksploitasi PHP2Shell — PHP2Shell menyerang **PHP interpreter (php-cgi SAPI)**, bukan aplikasi Laravel. Namun server DirectAdmin `fastikom-unsiq.ac.id` menjalankan versi PHP yang **rentan dan tidak dapat dipatch** di versi saat ini (lihat §2), sehingga **seluruh server wajib upgrade PHP ke 8.2+**.

**Kesimpulan utama:**
1. **Tidak ada patch untuk PHP 8.1.** Rilis perbaikan hanya tersedia untuk PHP 8.2.35, 8.3.36, 8.4.27, dan 8.5.12. PHP 8.1 sudah end-of-life (Dec 2025) dan tidak menerima patch keamanan apa pun lagi, termasuk PHP2Shell.
2. **Laravel 8 sudah end-of-life** (security fixes berakhir 2023-01-31 untuk 8.x). Aplikasi berjalan di `laravel/framework` v8.83.23. Perencanaan upgrade framework ke 10.x/11.x wajib masuk backlog, tapi bukan prasyarat menutup PHP2Shell.
3. Kerentanan memerlukan `php-cgi` SAPI. Apache + `mod_fcgid`/`mod_proxy_fcgi` juga menggunakan binary `php-cgi`. **PHP-FPM tidak rentan terhadap CVE-2026-109618/109619** — jika opsi tersedia di DirectAdmin, switch ke PHP-FPM adalah mitigasi tercepat sebelum upgrade.

---

## 2. Temuan rinci (dengan bukti file:baris)

| # | Temuan | Bukti | Risiko | Status perbaikan di repo ini |
|---|---|---|---|---|
| F-1 | Versi PHP di server: 8.1 (per info admin), rentan PHP2Shell, tanpa patch tersedia | Konfigurasi hosting DirectAdmin (perlu verifikasi manual di panel) | **KRITIS** | Tidak dapat diperbaiki dari repo. Lihat runbook §5, langkah W-1 |
| F-2 | `composer.json` `"php": "^7.3|^8.0"` — constraint memungkinkan PHP rentan | `composer.json:8` | KRITIS | **Ditunda s.d. server 8.2+** (permintaan pemilik repo: tidak update dependency sekarang). Target: `"php": "^8.2"` + `config.platform.php` terkunci, lalu `composer update` |
| F-3 | `composer.lock` terkunci pada paket yang tidak kompatibel PHP 8.2 (`nette/schema v1.2.2`, `nette/utils v3.2.7` membatasi `php <8.2`) | `composer.lock` (cek `packages`) | TINGGI (menghalangi migrasi) | Menyertai F-2, dikerjakan bersama update dependency |
| F-4 | CORS `allowed_origins => ['*']` untuk `api/*` | `config/cors.php:22` (sebelum commit a912268) | SEDANG | **Diperbaiki** — commit `a912268`: origin diambil dari `CORS_ALLOWED_ORIGINS`/`APP_URL`, tanpa wildcard |
| F-5 | Tidak ada `.htaccess` root untuk mencegah akses jika document root salah mengarah ke direktori proyek, bukan `public/` | file `.htaccess` root (sebelumnya tidak ada) | SEDANG | **Diperbaiki** — commit `d0c6a3e`: `Require all denied` di root |
| F-6 | Skrip publik berbahaya pernah ter-commit (`public/setup-links.php`, `public/test-symlink(.php)`) — indikasi aktivitas berbahaya/tahi lalat di repo | Hilang di `kp/master` saat ini; riwayat commit lama (perlu verifikasi manual `git log --all -- public/`) | SEDANG→RENDah | Sudah tidak ada di tree. **Perlu rotasi kredensial** karena skrip sempat terekspos (lihat §6) |
| F-7 | `.env.example` bersih kredensial | `.env.example` (tidak ada nilai rahasia) | — | Tidak perlu tindakan |
| F-8 | `APP_DEBUG=true` di `.env.example` | `.env.example:4` | RENDAH (contoh saja) | Verifikasi manual `.env` produksi: harus `APP_DEBUG=false` |
| F-9 | Remote `origin` dan `kp` mengarah ke repo yang sama `https://github.com/baqa27/ekapta-app-kp.git` | `git remote -v` | INFO | Satukan menjadi satu remote untuk menghindari kebingungan sync |
| F-10 | Aplikasi tidak memakai `php-cgi` secara eksplisit; SAPI ditentukan server | n/a — konfigurasi hosting | KRITIS (bergantung server) | Verifikasi manual: pastikan PHP berjalan sebagai PHP-FPM, bukan CGI/FCGI (runbook W-2) |

---

## 3. Perubahan yang dilakukan di branch `security/php2shell-hardening`

| Commit | Isi |
|---|---|
| `a912268` | `config/cors.php`: `allowed_origins` dari `CORS_ALLOWED_ORIGINS` (fallback `APP_URL`), difilter dari string kosong; tidak ada lagi `['*']` |
| `d0c6a3e` | `.htaccess` baru di root: menolak semua akses HTTP langsung ke file proyek (kalau document root keliru tidak mengarah ke `public/`) |

**Tidak diubah (sesuai permintaan):** `composer.json` constraint PHP, `composer.lock`, dan dependency apapun. Ditunda sampai server naik ke PHP 8.2+, agar tidak memutus deploy di hosting 8.1 saat ini.

**Catatan deployment `.htaccess` root:** DirectAdmin Apache biasanya membaca `.htaccess` per direktori. Dengan document root yang benar (`.../public_html/public` atau setara), file ini tidak berefek pada trafik normal. Jika document root mengarah ke root proyek, seluruh aplikasi memang seharusnya 403 (lebih aman daripada membocorkan `.env`).

---

## 4. Perintah verifikasi pasca-deploy (jalankan di server produksi)

```bash
# 1. Pastikan PHP CLI versi fixed
php -v
# Harus >= 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12

# 2. Pastikan SAPI bukan cgi/fcgi
php -i | grep -i 'Server API'
# Aman: FPM-FCGI / Apache2handler dengan mod_php tidak rentan pola ini;
# CGI/FastCGI dengan php-cgi: rentan, wajib FPM atau upgrade.

# 3. Pastikan extension vulnerable tidak aktif (jika memungkinkan)
php -m | grep -i cgi

# 4. Uji .htaccess root: akses langsung file proyek harus 403
curl -sI https://fastikom-unsiq.ac.id/composer.json | head -1
curl -sI https://fastikom-unsiq.ac.id/.env | head -1
# Dua-duanya harus HTTP 403 atau 404. SELAIN itu = document root salah / .htaccess tidak terbaca.

# 5. Uji CORS: origin acak harus tidak dapat akses
curl -sI -H "Origin: https://evil.example" https://fastikom-unsiq.ac.id/api/... | grep -i access-control
# Tidak boleh ada Access-Control-Allow-Origin: *

# 6. Uji skrip lama (harus 404)
curl -sI https://fastikom-unsiq.ac.id/setup-links.php | head -1
curl -sI https://fastikom-unsiq.ac.id/test-symlink.php | head -1

# 7. Cek file config terlarang yang mungkin bocor
curl -sI https://fastikom-unsiq.ac.id/.env.example | head -1
```

Tanda-tanda kompromi yang harus diperiksa (perlu verifikasi manual via SSH/DirectAdmin file manager):

- `ls -la` di root proyek, `public/`, `storage/framework/views/`, `storage/logs/`, `bootstrap/cache/`: cari file dengan nama acak, ekstensi `.php`, tanggal modifikasi mencurigakan
- `crontab -l` dan cron DirectAdmin: entri tak dikenal
- `grep -rn "eval(" storage/logs` anomali
- Riwayat akses Apache (`/var/log/httpd/domains/fastikom-unsiq.ac.id.log`): cari pola `POST` ke `.php` dengan query string `%ADd` / `php://input` — sinyature PHP2Shell
- Ukuran/`mtime` `vendor/` yang tidak cocok dengan `composer.lock`

---

## 5. Runbook perbaikan server (untuk admin FASTIKOM, via DirectAdmin)

### W-1 — Upgrade PHP (wajib, satu-satunya patch nyata)

1. Login DirectAdmin → **Select PHP version** / **PHP Selector** (CloudLinux) atau **Admin → Software → CustomBuild**.
2. Pilih **PHP 8.2** (atau lebih baru; 8.3/8.4 lebih baik karena 8.2 juga mendekati EOL).
   - Jangan pilih 8.1 — tidak ada patch PHP2Shell untuk 8.1.
3. Set **PHP mode ke PHP-FPM** (bukan CGI/FastCGI) bila tersedia.
4. Setelah PHP berganti, jalankan dari SSH:
   ```bash
   cd /home/<user>/domains/fastikom-unsiq.ac.id/public_html
   php -v
   composer install --no-dev --optimize-autoloader
   php artisan config:cache; php artisan route:cache; php artisan view:cache
   ```
   **Catatan:** dengan `composer.lock` saat ini, `composer install` akan **gagal** di PHP 8.2 (temuan F-3). Maka setelah upgrade server, deploy commit F-2/F-3 (lihat §7 rencana lanjutan).
5. Uji verifikasi §4 poin 1–2.

### W-2 — Mitigasi sementara (kalau upgrade tidak bisa segera)

Pilih salah satu, urut dari paling efektif:

- **Matikan `mod_cgi`/`CGI` dan pindah ke PHP-FPM** (DirectAdmin: Setup → PHP/FPM). Ini memutus rantai PHP2Shell karena FPM tidak membaca argumen dari path info seperti php-cgi.
- **RewriteEngine guard** di `public/.htaccess` (Apache 2.4):
  ```apache
  RewriteEngine On
  RewriteCond %{QUERY_STRING} ^.+ad([de]) [NC,OR]
  RewriteCond %{QUERY_STRING} ^%[a-f0-9]{2} [NC]
  RewriteRule . - [F,L]
  ```
  Ini menutup pola argument-injection klasik, tetapi **tidak menjamin** menutup varian PHP2Shell lengkap — hanya mitigasi sementara.
- **Web Application Firewall / mod_security** dengan ruleset PHP2Shell bila tersedia.

Mitigasi sementara **tidak menggantikan upgrade**.

---

## 6. Daftar rotasi kredensial (perlu verifikasi manual + tindakan manual)

Karena `public/setup-links.php` dan `public/test-symlink.php` sempat ekspos di repo/sejarah (F-6), anggap seluruh rahasia yang mungkin pernah berada di `.env` **terpotensi bocor**. Rotasi semua:

1. `APP_KEY` — buat baru: `php artisan key:generate` (semua sesi user akan logout)
2. `DB_PASSWORD` (MySQL DirectAdmin)
3. `MAIL_PASSWORD` / kredensial SMTP
4. Kredensial admin aplikasi (username/password login EKAPTA, termasuk akun admin di tabel `users`)
5. Kredensial DirectAdmin/SSH/FTP jika sama dipakai
6. Token layanan pihak ketiga di `.env` bila ada (AWS/Pusher/dll) — cek `config/services.php` untuk daftar kuncinya

---

## 7. Rencana lanjutan (belum dikerjakan di branch ini)

| Prioritas | Item | Pemicu |
|---|---|---|
| P1 | Kunci `composer.json` ke `"php": "^8.2"`, `config.platform.php` ke `8.2.35`, `composer update` (menyelesaikan F-2, F-3, dan F-9) | Setelah W-1 sukses dan server 8.2+ aktif |
| P1 | Verifikasi `.env` produksi: `APP_DEBUG=false`, `APP_ENV=production` | Sebelum go-live patch |
| P2 | Upgrade `laravel/framework` 8.x → 10.x/11.x (menutup EOL framework) | Setelah P-1 stabil; butuh adaptasi kode ( Sanctum 2→4, dll.) |
| P2 | Hapus sejarah file berbahaya dari git history (`git filter-repo`) bila dianggap perlu | Opsional; file sudah tidak ada di HEAD |
| P3 | `serve.bat` lokal: sesuaikan path PHP ke 8.2+ | Menyertai P-1 |
| P3 | Rapikan remote ganda (`origin` + `kp`) | Kapan saja |

---

## 8. Referensi

- PHP release announcements: 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12 — advisories PHP2Shell (CVE-2026-109618, CVE-2026-109619)
- Prekursor pola serangan: CVE-2024-4577 (argument injection php-cgi, Windows)
- `laravel/framework` v8.83.23 — versi terakhir seri 8.x; EOL security 2023-01-31

## 9. Catatan verifikasi manual yang tidak bisa dipastikan dari repo

- Versi PHP persis dan SAPI di server produksi (F-1, F-10)
- Document root DirectAdmin mengarah ke `public/` (F-5 verifikasi §4 poin 4)
- Apakah file berbahaya lama pernah ter-deploy ke server produksi (F-6 → §6 rotasi)
- Nilai rahasia di `.env` produksi (F-8) — jangan pernah commit nilai ke repo
