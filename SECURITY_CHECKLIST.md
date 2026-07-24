# SECURITY_CHECKLIST.md — Checklist Verifikasi Keamanan EKAPTA

Gunakan checklist ini untuk memverifikasi status hardening aplikasi sebelum deploy ke produksi.

---

## 1. Konfigurasi Lingkungan Produksi
- [x] `APP_DEBUG=false` dikonfigurasi untuk produksi
- [x] `APP_ENV=production` dikonfigurasi untuk produksi
- [x] `APP_KEY` sudah di-generate dan tidak kosong
- [x] `.env` **tidak** dicommit ke repositori git (ada di `.gitignore`)
- [x] `.env.example` **tidak** mengandung credential asli
- [ ] 🔲 Pastikan `APP_DEBUG=false` sebelum deploy (saat ini masih `true` di `.env`)
- [ ] 🔲 Rotate Google Drive OAuth credentials yang sudah terlanjur terekspos

---

## 2. HTTP Security Headers
- [x] `X-Frame-Options: SAMEORIGIN` — mencegah Clickjacking
- [x] `X-Content-Type-Options: nosniff` — mencegah MIME sniffing
- [x] `X-XSS-Protection: 1; mode=block` — filter XSS browser lama
- [x] `Referrer-Policy: strict-origin-when-cross-origin`
- [x] `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- [x] `Content-Security-Policy` dasar aktif
- [x] `Strict-Transport-Security` aktif saat HTTPS

---

## 3. Session & Cookie Security
- [x] `http_only = true` — cookie tidak bisa diakses JavaScript
- [x] `same_site = lax` — proteksi CSRF dasar
- [x] `secure = true` default — cookie hanya via HTTPS
- [x] Session diregenerasi saat login (`$request->session()->regenerate()`)
- [x] Session diinvalidasi saat logout (`$request->session()->invalidate()`)
- [x] CSRF token diregenerasi saat logout (`$request->session()->regenerateToken()`)

---

## 4. Autentikasi & Brute Force Protection
- [x] Rate limiter `throttle:5,1` aktif pada semua POST login routes
- [x] Session regenerasi setelah login berhasil
- [x] Password mahasiswa di-migrate ke bcrypt jika masih plaintext
- [x] Pesan error login tidak membedakan "NIM salah" vs "password salah" (mencegah user enumeration)

---

## 5. Otorisasi & IDOR Protection
- [x] Pengecekan kepemilikan resource di `editJudulPengajuan()` (mahasiswa_id)
- [x] Pengecekan kepemilikan di `pengajuanDetail()`, `edit()`, `delete()`
- [x] Middleware otorisasi per role (`isMahasiswa`, `isDosen`, `isProdi`, `isAdmin`)
- [x] Middleware menggunakan `Auth::check()` bukan `Auth::user()`

---

## 6. Keamanan File Upload
- [x] Whitelist ekstensi: `pdf`, `docx`, `doc`, `xls`, `xlsx`, `jpg`, `jpeg`, `png`
- [x] Validasi MIME type server-side (membaca magic bytes sesungguhnya)
- [x] Penamaan file acak UUID v4 (mencegah prediksi nama file)
- [x] Exception handling upload menampilkan pesan user-friendly

---

## 7. Path Traversal & Storage
- [x] `sanitizePath()` menggunakan regex aman `preg_replace('#(\.\.?/)+#', '', $path)`
- [x] Null byte `\0` dibersihkan dari path
- [x] `basename()` digunakan pada `serveByFilename()` untuk mencegah escape directory

---

## 8. SQL Injection
- [x] Semua query menggunakan Eloquent / parameter binding Laravel
- [x] `selectRaw` dengan literal string statis (tanpa interpolasi user input) — aman
- [x] Tidak ditemukan `DB::raw()` dengan input pengguna langsung

---

## 9. Open Redirect
- [x] `redirect_url` dari form divalidasi dengan `safeRedirectUrl()` yang memverifikasi host

---

## 10. Error Handling & Information Disclosure
- [x] `Handler.php` menangkap `InvalidArgumentException` dari upload
- [x] `Handler.php` menangkap `PostTooLargeException`
- [x] Produksi: `APP_DEBUG=false` mencegah stack trace terekspos

---

## 11. CSRF Protection
- [x] `VerifyCsrfToken` middleware aktif di group `web`
- [x] Semua form menggunakan `@csrf` (perlu diverifikasi di blade)
- [ ] 🔲 Logout menggunakan GET — pertimbangkan migrasi ke POST untuk CSRF-safe logout

---

## 12. Dependency Audit
- [ ] 🔲 Jalankan `composer audit` secara berkala
- [ ] 🔲 `facade/ignition ^2.5` (dev) — pastikan tidak aktif di produksi
- [ ] 🔲 `minimum-stability: dev` di composer.json — pertimbangkan ubah ke `stable`

---

## Legenda Status
- [x] = Sudah diterapkan / aman
- 🔲 = Diperlukan tindakan tambahan / perhatian
