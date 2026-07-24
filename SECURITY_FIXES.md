# SECURITY_FIXES.md — Rincian Perbaikan Kode Keamanan

Dokumen ini mencatat seluruh perubahan source code yang diterapkan selama audit keamanan EKAPTA.

---

## FIX-001: HTTP Security Headers Middleware

**File:** `app/Http/Middleware/SecurityHeaders.php` (BARU), `app/Http/Kernel.php` (DIPERBARUI)

**Kode Sebelum (Kernel.php):**
```php
protected $middleware = [
    \App\Http\Middleware\TrustProxies::class,
    \Fruitcake\Cors\HandleCors::class,
    \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \App\Http\Middleware\TrimStrings::class,
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
];
```

**Kode Sesudah (Kernel.php):**
```php
protected $middleware = [
    ...
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    \App\Http\Middleware\SecurityHeaders::class,  // ← DITAMBAHKAN
];
```

**Isi SecurityHeaders.php:**
```php
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');         // Cegah Clickjacking
$response->headers->set('X-Content-Type-Options', 'nosniff');     // Cegah MIME sniffing
$response->headers->set('X-XSS-Protection', '1; mode=block');    // XSS filter browser lama
$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
$response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
// HSTS hanya jika HTTPS aktif
if ($request->isSecure()) {
    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
}
$response->headers->set('Content-Security-Policy', "default-src 'self'; ...");
```

---

## FIX-002: Hardening Session Cookie

**File:** `config/session.php` (baris 171)

**Kode Sebelum:**
```php
'secure' => env('SESSION_SECURE_COOKIE'),
```

**Kode Sesudah:**
```php
'secure' => env('SESSION_SECURE_COOKIE', true),
```

**Alasan:** Jika `SESSION_SECURE_COOKIE` tidak diset di `.env`, nilai default `null` menyebabkan cookie dikirimkan via HTTP plaintext.

---

## FIX-003: Path Traversal — sanitizePath() diperbaiki dengan Regex Aman

**File:** `app/Http/Controllers/StorageController.php` (baris 136-154)

**Kode Sebelum (Gemini — berpotensi loop infinit):**
```php
while (str_contains($path, '../') || str_contains($path, './')) {
    $path = str_replace(['../', './'], '', $path);
}
```

**Kode Sesudah (regex deterministik):**
```php
$path = str_replace("\0", '', $path);                      // Hapus null bytes
$path = preg_replace('#(\.\.?/)+#', '', $path);           // Hapus ../ dan ./ (regex aman)
$path = str_replace('..', '', $path);                      // Hapus sisa ..
return ltrim($path, '/');
```

**Alasan Improvement:** `while-loop` dengan `str_contains` berpotensi menghasilkan loop banyak iterasi pada string crafted. `preg_replace` dengan pattern `#(\.\.?/)+#` menangani semua varian dalam satu operasi O(n) tanpa risiko loop.

---

## FIX-004: File Upload — Tambah Validasi MIME Type Server-Side

**File:** `app/Helpers/AppHelper.php` (baris 125-174)

**Kode Sebelum (Gemini — hanya validasi client-side header):**
```php
$extension = strtolower($lampiran->getClientOriginalExtension());
if (!in_array($extension, $allowedExtensions, true)) {
    throw new \InvalidArgumentException('Tipe file tidak diperbolehkan');
}
```

**Kode Sesudah (tambah validasi MIME sesungguhnya):**
```php
// Validasi ekstensi client (layer pertama)
$extension = strtolower($lampiran->getClientOriginalExtension());
if (!in_array($extension, $allowedExtensions, true)) {
    throw new \InvalidArgumentException('Ekstensi file tidak diperbolehkan: ' . $extension);
}

// Validasi MIME type dari konten file sesungguhnya (layer kedua — tidak bisa dimanipulasi)
$realMimeType = $lampiran->getMimeType();
if (!in_array($realMimeType, $allowedMimeTypes, true)) {
    throw new \InvalidArgumentException('Tipe file tidak diperbolehkan: ' . $realMimeType);
}
```

**Alasan:** `getClientOriginalExtension()` membaca nilai dari nama file yang dikirimkan browser — bisa dimanipulasi. `getMimeType()` menggunakan `finfo` untuk membaca magic bytes dari konten file sesungguhnya — tidak bisa dimanipulasi.

---

## FIX-005: Open Redirect — safeRedirectUrl() di BimbinganManualController

**File:** `app/Http/Controllers/KP/BimbinganManualController.php`

**Kode Sebelum:**
```php
$redirectUrl = $request->input('redirect_url', url()->previous());
return redirect($redirectUrl)->with('success', $successMsg);
```

**Kode Sesudah:**
```php
$redirectUrl = $this->safeRedirectUrl($request->input('redirect_url'));
return redirect($redirectUrl)->with('success', $successMsg);

// Method safeRedirectUrl() yang ditambahkan:
private function safeRedirectUrl(?string $url): string
{
    $fallback = url()->previous();
    if (empty($url)) return $fallback;
    $parsed = parse_url($url);
    if (!isset($parsed['host'])) return $url;  // Relative URL aman
    $appHost = parse_url(config('app.url'), PHP_URL_HOST);
    if ($parsed['host'] === $appHost) return $url;  // Host sama = aman
    return $fallback;  // Host berbeda = tolak, fallback
}
```

---

## FIX-006: Brute Force — Rate Limiter pada Login Routes

**File:** `routes/web.php`

**Kode Sebelum:**
```php
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->middleware('isMahasiswaLogin');
Route::post('/login/prodi', [LoginController::class, 'cekProdi'])->middleware('isProdiLogin');
Route::post('/login/dosen', [LoginController::class, 'cekDosen'])->middleware('isDosenLogin');
Route::post('/login/admin', [LoginController::class, 'cekAdmin'])->middleware('isAdminLogin');
```

**Kode Sesudah:**
```php
// throttle:5,1 = maksimal 5 percobaan per 1 menit per IP
Route::post('/login/mahasiswa', [...])->middleware(['isMahasiswaLogin', 'throttle:5,1']);
Route::post('/login/prodi', [...])->middleware(['isProdiLogin', 'throttle:5,1']);
Route::post('/login/dosen', [...])->middleware(['isDosenLogin', 'throttle:5,1']);
Route::post('/login/admin', [...])->middleware(['isAdminLogin', 'throttle:5,1']);
```

---

## FIX-007: IDOR + Missing Validation — editJudulPengajuan()

**File:** `app/Http/Controllers/PengajuanController.php`

**Kode Sebelum:**
```php
public function editJudulPengajuan(Request $request, $id){
    $pengajuan = Pengajuan::findOrFail($id);  // ← Tidak ada ownership check!
    $pengajuan->update(['judul' => $request->judul]);  // ← Tidak ada validasi!
    return back()->with('success','Judul tugas akhir berhasil di update.');
}
```

**Kode Sesudah:**
```php
public function editJudulPengajuan(Request $request, $id)
{
    $pengajuan = Pengajuan::findOrFail($id);
    // Cek kepemilikan (IDOR protection)
    if ($pengajuan->mahasiswa_id !== Auth::guard('mahasiswa')->user()->id) {
        abort(403, 'Anda tidak memiliki akses untuk mengubah judul ini.');
    }
    // Validasi input
    $validated = $request->validate(['judul' => ['required', 'string', 'min:5', 'max:255']]);
    $pengajuan->update(['judul' => $validated['judul']]);
    return back()->with('success', 'Judul tugas akhir berhasil di update.');
}
```

---

## FIX-008: Middleware Check() vs User()

**File:** `app/Http/Middleware/IsDosen.php`, `IsProdi.php`

**Kode Sebelum:**
```php
if (Auth::guard('dosen')->user()) { ... }
```

**Kode Sesudah:**
```php
if (Auth::guard('dosen')->check()) { ... }  // Mengembalikan boolean — lebih aman & idiomatik
```

---

## FIX-009: Handler Exception Upload File

**File:** `app/Exceptions/Handler.php`

**Ditambahkan:**
```php
$this->renderable(function (\InvalidArgumentException $e, Request $request) {
    $message = $e->getMessage();
    if ($request->expectsJson()) {
        return response()->json(['message' => $message], 422);
    }
    return redirect()->back()->withInput(...)->with('error', $message);
});
```

---

## FIX-010: Bersihkan .env.example dari Credential Asli

**File:** `.env.example`

Seluruh credential asli (SMTP password, Google Drive OAuth, SALT) diganti dengan placeholder:
- `MAIL_PASSWORD=your-app-password-here`
- `GOOGLE_DRIVE_CLIENT_SECRET=your-google-drive-client-secret`
- `GOOGLE_DRIVE_REFRESH_TOKEN=your-google-drive-refresh-token`
- `SALT_ENCRYPTION="ganti-dengan-nilai-acak-anda"`
