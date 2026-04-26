<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\Seminar;

// Import Shared Models
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class CetakController extends \App\Http\Controllers\Controller
{

    public function cetakLembarPersetujuanMahasiswa()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan = Pengajuan::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan belum diterima');
        }

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // KP workflow: single dosen with status 'pembimbing'
        // Fallback to 'utama' for legacy data
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $dateLocale = $pengajuan->tanggal_acc
            ? Carbon::parse($pengajuan->tanggal_acc)->day.' '.Carbon::parse($pengajuan->tanggal_acc)->monthName.' '.Carbon::parse($pengajuan->tanggal_acc)->year
            : null;

        $data = [
            'title' => 'Lembar Persetujuan Pembimbing',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'dosen_pembimbing' => $dosenPembimbing,
            // Legacy support: pass same dosen as dosen_utama for old views
            'dosen_utama' => $dosenPembimbing,
            'dosen_pendamping' => null,
            'prodi' => $prodi,
            'dateLocale' => $dateLocale,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.lembarPersetujuanPembimbing', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Persetujuan-Pembimbing.pdf');
    }

    public function cetakLembarPernyataanKeaslian()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $data = [
            'title' => 'Lembar Persetujuan Pembimbing',
            'mahasiswa' => $mahasiswa,
        ];
        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.lembarPernyataanKeaslian', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Pernyataan-Keaslian.pdf');
    }

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

    public function cetakSuratTugasBimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::with('fakultas.dekans')
            ->where(function($q) use ($mahasiswa) {
                $q->where('kode', $mahasiswa->prodi)
                  ->orWhere('namaprodi', $mahasiswa->prodi);
            })->first();

        // KP: 1 dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $dosenUtama = $dosenPembimbing;
        $dosenPendamping = null;

        $qrcode = 'data:image/' . ';base64,' . base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('kp/cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));
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
        $no_urut = null;
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
            'date' => $tanggal_acc,
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

    public function cetakBeritaAcaraUjianProposal($seminar)
    {
        $seminar = Seminar::with(['reviews.dosen', 'pengajuan', 'mahasiswa'])->findOrFail($seminar);

        // Untuk KP, nilai diambil dari seminar_kps.nilai_akhir, bukan dari review
        $nilai_huruf = $seminar->nilai_huruf ?? null;
        $nilai_angka = $seminar->nilai_akhir ?? $seminar->nilai_seminar ?? null;

        // KP: 1 pembimbing + 1 penguji = 2 review
        $seminars_acc = $seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get();
        $is_complete = count($seminars_acc) >= 2 && $seminar->is_lulus == 1;

        $data = [
            'title' => 'BERITA ACARA SEMINAR KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $seminar,
            'nilai' => $nilai_huruf,
            'nilai_angka' => $nilai_angka,
            'is_complete' => $is_complete,
            'tanggal_ujian' => $seminar->tanggal_ujian ? Carbon::parse($seminar->tanggal_ujian)->day.' '.Carbon::parse($seminar->tanggal_ujian)->monthName.' '.Carbon::parse($seminar->tanggal_ujian)->year : date('d F Y'),
            'title_form_nilai' => 'LEMBAR PENILAIAN SEMINAR KP',
            'title_form_revisi' => 'LEMBAR REVISI SEMINAR KP',
            'is_blank' => false,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.berita-acara-seminar', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Seminar-KP.pdf');
    }

    public function cetakBeritaAcaraUjianProposalBlank($ujian_or_seminar, $type)
    {
        $ujian_or_seminar = Seminar::with(['reviews.dosen', 'pengajuan', 'mahasiswa'])->findOrFail($ujian_or_seminar);

        $data = [
            'title' => 'BERITA ACARA SEMINAR KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $ujian_or_seminar,
            'nilai' => null,
            'nilai_angka' => null,
            'is_complete' => null,
            'tanggal_ujian' => $ujian_or_seminar->tanggal_ujian ? Carbon::parse($ujian_or_seminar->tanggal_ujian)->day.' '.Carbon::parse($ujian_or_seminar->tanggal_ujian)->monthName.' '.Carbon::parse($ujian_or_seminar->tanggal_ujian)->year : date('d F Y'),
            'title_form_nilai' => 'LEMBAR PENILAIAN SEMINAR KP',
            'title_form_revisi' => 'LEMBAR REVISI SEMINAR KP',
            'is_blank' => true,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.berita-acara-seminar', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Seminar-KP-Blank.pdf');
    }



    public function cetakRiwayatBimbinganMahasiswa(){
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
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

        $qrcode = 'data:image/' . ';base64,' . base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . base64_encode($mahasiswa->id))));

        $startDate = AppHelper::getBimbinganStartDateFromPendaftaran($pendaftaran);
        $dateLocale = $startDate ? $startDate->day.' '.$startDate->monthName.' '.$startDate->year : null;
        $dateExpired = AppHelper::getBimbinganExpiredDateFromPendaftaran($pendaftaran, 6);

        // KP: Ambil bimbingan online yang ACC
        $bimbingan_dosen_utama = $dosenPembimbing ? $dosenPembimbing->bimbingansKP()->with(['revisis','bagian'])->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->get() : collect([]);

        // KP: Ambil bimbingan manual yang ACC
        $bimbingan_manual = \App\Models\KP\AjuanBimbinganManualKP::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'acc')
            ->with(['bimbingan', 'bimbingan.bagian'])
            ->get();

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
            'title' => 'Lembar Bimbingan KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => now(),
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'ttd_dosen_utama' => ($dosenUtama && $dosenUtama->ttd) ? AppHelper::instance()->convertStorageImage($dosenUtama->ttd) : null,
            'ttd_dosen_pendamping' => ($dosenPendamping && $dosenPendamping->ttd) ? AppHelper::instance()->convertStorageImage($dosenPendamping->ttd) : null,
            'bimbingan_dosen_utama' => $bimbingan_dosen_utama,
            'bimbingan_manual' => $bimbingan_manual,
            'bimbingan_dosen_pendamping' => collect([]),
            'no_urut' => $no_urut,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.lembarBimbinganKP', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Bimbingan-KP.pdf');
    }

    public function cetakLembarPersetujuan($type){
        $mahasiswa = Mahasiswa::with(['seminarKP','pengajuansKP','dosens'])->where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // KP: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Gunakan Seminar untuk KP (bukan Ujian)
        $seminar = $mahasiswa->seminarKP()->where('is_lulus', 1)->first();
        $dosens = [];
        if ($seminar) {
            $reviews = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->with(['dosen'])->get();
            foreach ($reviews as $review) {
                $dosens[] = $review->dosen;
            }
        }

        $data = [
            'title' => $type == 1 ? 'LEMBAR PERSETUJUAN PEMBIMBING' : 'LEMBAR PERSETUJUAN PENGUJI',
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenPembimbing,
            'dosen_pembimbing' => $dosenPembimbing,
            'prodi' => $prodi,
            'date' => $seminar ? AppHelper::parse_date_short_surat($seminar->tanggal_ujian) : null,
            'ttd_dosen_utama' => $dosenPembimbing && $dosenPembimbing->ttd != null ? AppHelper::instance()->convertStorageImage($dosenPembimbing->ttd) : null,
            'dosens' => $dosens,
            'type' => $type,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.lembar-persetujuan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($type == 1 ? 'Lembar-Persetujuan-Pembimbing.pdf' : 'Lembar-Persetujuan-Penguji.pdf');
    }

    public function cetakLembarPengesahan(){
        $mahasiswa = Mahasiswa::with(['seminarKP','pengajuansKP','dosens'])->where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::with(['fakultas', 'fakultas.dekans'])
            ->where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $dekan = ($prodi && $prodi->fakultas) ? $prodi->fakultas->dekans()->where(function($q) {
            $q->where('status', 'active')->orWhere('status', '1');
        })->first() : null;

        // KP: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Gunakan Seminar untuk KP (bukan Ujian)
        $seminar = $mahasiswa->seminarKP()->where('is_lulus', 1)->first();

        $data = [
            'title' => 'LEMBAR PENGESAHAN',
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenPembimbing,
            'dosen_pembimbing' => $dosenPembimbing,
            'prodi' => AppHelper::instance()->getDosen($prodi->kodekaprodi),
            'date' => $seminar ? AppHelper::parse_date_short_surat($seminar->tanggal_ujian) : null,
            'ttd_dosen_utama' => $dosenPembimbing && $dosenPembimbing->ttd != null ? AppHelper::instance()->convertStorageImage($dosenPembimbing->ttd) : null,
            'dekan' => $dekan,
            'ttd_dekan' => $dekan && $dekan->image ? AppHelper::instance()->convertStorageImage($dekan->image) : null,
            'stempel' => $prodi->fakultas->image ? AppHelper::instance()->convertStorageImage($prodi->fakultas->image) : null,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.lembar-pengesahan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Pengesahan.pdf');
    }

    /**
     * Cetak Surat Penolakan Pengajuan KP
     */
    public function cetakSuratPenolakan($pengajuan)
    {
        $pengajuan = Pengajuan::with(['mahasiswa', 'prodi', 'revisis'])->findOrFail($pengajuan);

        // Pastikan pengajuan milik mahasiswa yang login
        if ($pengajuan->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            return back()->with('error', 'Akses ditolak');
        }

        // Pastikan status ditolak
        if ($pengajuan->status != Pengajuan::DITOLAK) {
            return back()->with('error', 'Pengajuan belum ditolak');
        }

        $mahasiswa = $pengajuan->mahasiswa;
        $prodi = $pengajuan->prodi;

        // Ambil catatan penolakan terakhir
        $revisiTerakhir = $pengajuan->revisis()->orderBy('created_at', 'desc')->first();

        $tanggalTolak = $revisiTerakhir ? $revisiTerakhir->created_at : $pengajuan->updated_at;
        $dateLocale = Carbon::parse($tanggalTolak)->day.' '.Carbon::parse($tanggalTolak)->monthName.' '.Carbon::parse($tanggalTolak)->year;

        // Hitung sisa kesempatan
        $jumlahTolak = $pengajuan->jumlah_tolak ?? 1;
        $sisaKesempatan = Pengajuan::MAX_TOLAK - $jumlahTolak;

        $data = [
            'title' => 'Surat Penolakan Pengajuan KP',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'prodi' => $prodi,
            'catatan' => $revisiTerakhir ? $revisiTerakhir->catatan : '-',
            'tanggal_tolak' => $dateLocale,
            'jumlah_tolak' => $jumlahTolak,
            'sisa_kesempatan' => $sisaKesempatan,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.suratPenolakan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Surat-Penolakan-Pengajuan-KP.pdf');
    }

    public function cetakFormulirNilaiAkhir()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // Ambil pengajuan KP yang diterima
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        // Cek apakah kelas karyawan
        $isKaryawan = AppHelper::isKaryawanKP($mahasiswa);

        // Ambil seminar KP yang lulus (hanya untuk reguler)
        $seminar = $mahasiswa->seminarKP()->where('is_lulus', 1)->first();

        // Ambil dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Ambil Kaprodi (Koordinator KP) menggunakan helper getDosen
        $kaprodi = null;
        if ($prodi && $prodi->kodekaprodi) {
            $kaprodi = AppHelper::instance()->getDosen($prodi->kodekaprodi);
        }

        // Ambil bobot penilaian dari presentase_nilai_kps
        $presentaseNilai = \App\Models\KP\PresentaseNilai::where('prodi_id', $prodi->id ?? null)->first();

        if ($isKaryawan) {
            // Karyawan: Hanya 2 komponen (Pembimbing & Instansi)
            // Bobot: Pembimbing 40% + Instansi 60%
            $bobot_pembimbing = 40;
            $bobot_instansi = 60;

            // Ambil nilai dari jilid KP atau seminar KP
            $jilid = $mahasiswa->jilidKP;
            $seminarKaryawan = $mahasiswa->seminarKP;

            // Nilai pembimbing: prioritas dari jilid, fallback ke seminar
            $nilai_pembimbing = ($jilid && $jilid->nilai_pembimbing)
                ? $jilid->nilai_pembimbing
                : ($seminarKaryawan ? $seminarKaryawan->nilai_pembimbing : 0);

            // Nilai instansi: prioritas dari jilid, fallback ke seminar
            $nilai_instansi = ($jilid && $jilid->nilai_instansi)
                ? $jilid->nilai_instansi
                : ($seminarKaryawan ? $seminarKaryawan->nilai_instansi : 0);

            // Hitung bobot x nilai
            $bobot_x_nilai_pembimbing = ($nilai_pembimbing * $bobot_pembimbing) / 100;
            $bobot_x_nilai_instansi = ($nilai_instansi * $bobot_instansi) / 100;

            // Hitung nilai akhir
            $nilai_akhir = $bobot_x_nilai_pembimbing + $bobot_x_nilai_instansi;

            $data = [
                'title' => 'FORMULIR NILAI AKHIR KERJA PRAKTEK',
                'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
                'mahasiswa' => $mahasiswa,
                'prodi' => $prodi,
                'pengajuan' => $pengajuan,
                'dosen_pembimbing' => $dosenPembimbing,
                'kaprodi' => $kaprodi,
                'bobot_pembimbing' => $bobot_pembimbing,
                'bobot_instansi' => $bobot_instansi,
                'nilai_pembimbing' => $nilai_pembimbing,
                'nilai_instansi' => $nilai_instansi,
                'bobot_x_nilai_pembimbing' => $bobot_x_nilai_pembimbing,
                'bobot_x_nilai_instansi' => $bobot_x_nilai_instansi,
                'nilai_akhir' => $nilai_akhir,
            ];

            $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf->loadview('kp.pages.cetak.formulir-nilai-akhir-karyawan', $data);
            $pdf->setPaper('A4', 'portrait');

            return $pdf->stream('Formulir-Nilai-Akhir-KP-Karyawan.pdf');
        }

        // Reguler: 3 komponen (Pembimbing, Penguji, Instansi)
        $bobot_pembimbing = $presentaseNilai->bobot_pembimbing ?? 40;
        $bobot_penguji = $presentaseNilai->bobot_penguji ?? 30;
        $bobot_instansi = $presentaseNilai->bobot_instansi ?? 30;

        // Ambil nilai dari seminar_kps
        $nilai_pembimbing = $seminar->nilai_pembimbing ?? 0;
        $nilai_penguji = $seminar->nilai_penguji ?? 0;
        $nilai_instansi = $seminar->nilai_instansi ?? 0;

        // Hitung bobot x nilai
        $bobot_x_nilai_pembimbing = ($nilai_pembimbing * $bobot_pembimbing) / 100;
        $bobot_x_nilai_penguji = ($nilai_penguji * $bobot_penguji) / 100;
        $bobot_x_nilai_instansi = ($nilai_instansi * $bobot_instansi) / 100;

        // Hitung nilai akhir
        $nilai_akhir = $bobot_x_nilai_pembimbing + $bobot_x_nilai_penguji + $bobot_x_nilai_instansi;

        $data = [
            'title' => 'FORMULIR NILAI AKHIR KERJA PRAKTEK',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'pengajuan' => $pengajuan,
            'dosen_pembimbing' => $dosenPembimbing,
            'kaprodi' => $kaprodi,
            'bobot_pembimbing' => $bobot_pembimbing,
            'bobot_penguji' => $bobot_penguji,
            'bobot_instansi' => $bobot_instansi,
            'nilai_pembimbing' => $nilai_pembimbing,
            'nilai_penguji' => $nilai_penguji,
            'nilai_instansi' => $nilai_instansi,
            'bobot_x_nilai_pembimbing' => $bobot_x_nilai_pembimbing,
            'bobot_x_nilai_penguji' => $bobot_x_nilai_penguji,
            'bobot_x_nilai_instansi' => $bobot_x_nilai_instansi,
            'nilai_akhir' => $nilai_akhir,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.formulir-nilai-akhir', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir-Nilai-Akhir-KP.pdf');
    }

    public function cetakBeritaAcaraSerahTerima()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        $prodi = Prodi::where(function($q) use ($mahasiswa) {
            $q->where('kode', $mahasiswa->prodi)
              ->orWhere('namaprodi', $mahasiswa->prodi);
        })->first();

        // Tanggal hari ini
        $date = Carbon::now()->locale('id');
        $hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanList = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Terbilang tanggal
        $terbilangAngka = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh',
            'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas',
            'delapan belas', 'sembilan belas', 'dua puluh', 'dua puluh satu', 'dua puluh dua', 'dua puluh tiga',
            'dua puluh empat', 'dua puluh lima', 'dua puluh enam', 'dua puluh tujuh', 'dua puluh delapan',
            'dua puluh sembilan', 'tiga puluh', 'tiga puluh satu'
        ];

        $data = [
            'title' => 'Berita Acara Serah Terima',
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'prodi' => $prodi,
            'hari' => $hariList[$date->dayOfWeek],
            'tanggal_text' => $terbilangAngka[$date->day],
            'bulan_text' => $bulanList[$date->month],
            'tahun_text' => 'dua ribu ' . $terbilangAngka[$date->year - 2000],
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadview('kp.pages.cetak.beritaAcaraSerahTerima', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Serah-Terima-Produk-KP.pdf');
    }

}
