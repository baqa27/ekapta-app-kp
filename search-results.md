# Search Results for Laravel KP Module

## 1. Route/Controller for URL `/kp/cetak/surat-tugas-bimbingan`

**Route File:** `D:\ekapta final\ekapta-app-new\routes\web.php` (lines 942-943)
```php
Route::get('/cetak/surat-tugas-bimbingan', [KPCetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [KPCetakController::class, 'cetakSuratTugasBimbingan'])->name('cetak.surat.tugas.bimbingan.pendaftaran');
```

**Controller:** `D:\ekapta final\ekapta-app-new\app\Http\Controllers\KP\CetakController.php`
**Method:** `cetakSuratTugasBimbingan($pendaftaran)` (lines 83-154)
```php
public function cetakSuratTugasBimbingan($pendaftaran)
{
    $pendaftaran = Pendaftaran::findOrFail($pendaftaran);
    $mahasiswa = $pendaftaran->pengajuan->mahasiswa;
    $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
    // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
    $prodi = Prodi::where('kode', $mahasiswa->prodi)
        ->orWhere('namaprodi', $mahasiswa->prodi)
        ->first();

    // KP: 1 dosen pembimbing
    $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
    // Fallback untuk data lama
    if (!$dosenPembimbing) {
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
    }
    $dosenUtama = $dosenPembimbing;
    $dosenPendamping = null;

    $qrcode = 'data:image/' . ';base64,' . base64_encode(QrCode::format('svg')->size(400)->errorCorrection('H')->generate(url('kp/cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));
    $qrcode_bimbingan = 'data:image/' . ';base64,' . base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . base64_encode($mahasiswa->id))));

    $tanggal_acc = Carbon::parse($pendaftaran->tanggal_acc);
    $dateLocale = $tanggal_acc->day.' '.$tanggal_acc->monthName.' '.$tanggal_acc->year;

    $dateExpired = AppHelper::getBimbinganExpiredDateFromPendaftaran($pendaftaran, 6);
    $tanggalPembayaran = AppHelper::parseFlexibleDate($pendaftaran->tanggal_pembayaran);
    $tanggalPembayaranLocale = $tanggalPembayaran
        ? $tanggalPembayaran->locale('id')->isoFormat('D MMMM Y')
        : $pendaftaran->tanggal_pembayaran;

    $dekan = ($prodi && $prodi->fakultas) ? $prodi->fakultas->dekans()->where(function($q) {
        $q->where('status', 'active')->orWhere('status', '1');
    })->first() : null;

    $pendaftarans = Pendaftaran::whereYear('created_at', Carbon::parse($pendaftaran->created_at)->year)->get();
    $no=0;
    $no_urut = 000;
    foreach($pendaftarans as $p){
        $no++;
        if($pendaftaran->id == $p->id){
            $no_urut = sprintf("%03d", $no);
            break;
        }
    }

    $data = [
        'title' => 'Surat Tugas Bimbingan',
        'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
        'mahasiswa' => $mahasiswa,
        'pendaftaran' => $pendaftaran,
        'dosen_utama' => $dosenUtama,
        'dosen_pendamping' => $dosenPendamping,
        'prodi' => $prodi,
        'date' =>  $tanggal_acc,
        'dateLocale' => $dateLocale,
        'tanggal_pembayaran_locale' => $tanggalPembayaranLocale,
        'qr_code' => $qrcode,
        'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
        'dekan' => $dekan,
        'stempel' => ($prodi && $prodi->fakultas && $prodi->fakultas->image) ? AppHelper::instance()->convertStorageImage($prodi->fakultas->image) : null,
        'ttd_dekan' => ($dekan && $dekan->image) ? AppHelper::instance()->convertStorageImage($dekan->image) : null,
        'no_urut' => $no_urut,
         'pengajuan' => $pengajuan,
         'qr_code_bimbingan' => $qrcode_bimbingan,
    ];

    $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
    $pdf->loadview('kp.pages.cetak.suratTugasBimbingan', $data);
    $pdf->setPaper('A4', 'portrait');
    return $pdf->stream('Surat-Tugas-Bimbingan.pdf');
}
```

## 2. View PDF Lempar Bimbingan KP - Label "Pembimbing:"

**File:** `D:\ekapta final\ekapta-app-new\resources\views\kp\pages\cetak\lembarBimbinganKP.blade.php` (line 258)
**Snippet:** (lines 255-271)
```blade
{{-- Catatan Pembimbing --}}
@if(!empty($bimbingan['catatan_mahasiswa']))
    <div style="margin-bottom: 6px;">
        <strong>Pembimbing:</strong><br>
        {{ strip_tags($bimbingan['catatan_mahasiswa']) }}
    </div>
@endif

{{-- Catatan Prodi/Admin --}}
@if (!empty($bimbingan['catatan_dosen']))
    @if(!empty($bimbingan['catatan_mahasiswa']))
        <hr style="margin: 4px 0; border: 0; border-top: 1px dashed #ccc;">
    @endif
    <strong>Prodi/Admin:</strong><br>
    {{ strip_tags($bimbingan['catatan_dosen']) }}
@endif
```

## 3. View/Controller Halaman Bimbingan untuk DOSEN

**Routes:** `D:\ekapta final\ekapta-app-new\routes\web.php` (lines 783-784)
```php
Route::get('/bimbingan-dosen', [KPBimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
Route::get('/bimbingan-dosen-progress', [KPBimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
```

**Controller:** `D:\ekapta final\ekapta-app-new\app\Http\Controllers\KP\BimbinganController.php`

### Method 1: `bimbinganDosen()` (lines 55-88)
**View:** `kp.pages.dosen.bimbingan.bimbingan`
**Variables Sent to View:**
```php
return view('kp.pages.dosen.bimbingan.bimbingan', [
    'title' => 'Bimbingan Kerja Praktek',
    'active' => 'bimbingan-kp',
    'sidebar' => 'kp.partials.sidebarDosen',
    'module' => 'kp',
    'bimbingans' => $dosen->bimbingansKP()
        ->where('status', 'review')
        ->whereNull('tanggal_manual_acc')
        ->whereHas('mahasiswa.pengajuansKP', function($query) {
            $query->where('status', 'diterima');
        })
        ->orderBy('tanggal_bimbingan', 'desc')
        ->get(),
    'bimbingans_diterima' => $dosen->bimbingansKP()
        ->where('status', 'diterima')
        ->whereHas('mahasiswa.pengajuansKP', function($query) {
            $query->where('status', 'diterima');
        })
        ->orderBy('tanggal_bimbingan', 'desc')
        ->get(),
    'bimbingans_revisi' => $dosen->bimbingansKP()
        ->where('status', 'revisi')
        ->whereHas('mahasiswa.pengajuansKP', function($query) {
            $query->where('status', 'diterima');
        })
        ->orderBy('tanggal_bimbingan', 'desc')
        ->get(),
]);
```
**Query Logic:** Loads bimbingan records for the authenticated dosen, filtered by:
- `bimbingans`: status='review' AND tanggal_manual_acc IS NULL AND mahasiswa has diterima pengajuan
- `bimbingans_diterima`: status='diterima' AND mahasiswa has diterima pengajuan  
- `bimbingans_revisi`: status='revisi' AND mahasiswa has diterima pengajuan
All ordered by tanggal_bimbingan descending

### Method 2: `bimbinganDosenProgress()` (lines 726-750)
**View:** `kp.pages.dosen.bimbingan.bimbingan-progress`
**Variables Sent to View:**
```php
return view('kp.pages.dosen.bimbingan.bimbingan-progress', [
    'title' => 'Bimbingan Kerja Praktek',
    'active' => 'bimbingan-progress-kp',
    'sidebar' => 'kp.partials.sidebarDosen',
    'module' => 'kp',
    'mahasiswas' => $mahasiswas,
]);
```
**Query Logic:** Loads mahasiswa data for the authenticated dosen where:
- Mahasiswa has at least one diterima pengajuan
- Either: dosen_mahasiswas.status = 'pembimbing' OR mahasiswa has bimbingan linked to this dosen
Includes eager loading of bimbingansKP.bagian, pengajuansKP, pendaftaransKP