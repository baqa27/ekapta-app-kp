<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewSeminar;
use App\Models\ReviewUjian;
use App\Models\Seminar;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Str;

class CetakController extends Controller
{

    public function cetakLembarPersetujuanMahasiswa()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan = Pengajuan::where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Lembar Persetujuan Pembimbing',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosenUtama ? $dosenUtama : null,
            'dosen_pendamping' => $dosenPendamping ? $dosenPendamping : null,
            'prodi' => $prodi,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembarPersetujuanPembimbing', $data);
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
        $pdf->loadView('pages.cetak.lembarPernyataanKeaslian', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Pernyataan-Keaslian.pdf');
    }

    public function cetakSuratTugasBimbingan($pendaftaran)
    {
        $pendaftaran = Pendaftaran::findOrFail($pendaftaran);
        $mahasiswa = $pendaftaran->pengajuan->mahasiswa;
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(400)->errorCorrection('H')->generate(url('cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));

        $dateLocale = Carbon::parse(now())->day.' '.Carbon::parse(now())->monthName.' '.Carbon::parse(now())->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(12);

        $dekan = $prodi->fakultas->dekans()->where('status', 'active')->first();

        $data = [
            'title' => 'Surat Tugas Bimbingan',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => now(),
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'dekan' => $dekan,
            'stempel' => $prodi->fakultas->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image,0)) : null,
            // 'stempel' => $prodi->fakultas->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image,31)) : null,
            'ttd_dekan' => $dekan->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image,0)): null,
            // 'ttd_dekan' => $dekan->image ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image,31)): null,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.suratTugasBimbingan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Surat-Tugas-Bimbingan.pdf');
    }

    public function cetakSuratTugasBimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('cetak/surat-tugas-bimbingan/' . $pendaftaran->id)));

        $dateLocale = Carbon::parse(now())->day.' '.Carbon::parse(now())->monthName.' '.Carbon::parse(now())->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(12);

        $dekan = $prodi->fakultas->dekans()->where('status', 'active')->first();

        $data = [
            'title' => 'Surat Tugas Bimbingan',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'mahasiswa' => $mahasiswa,
            'pendaftaran' => $pendaftaran,
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
            'prodi' => $prodi,
            'date' => now(),
            'dateLocale' => $dateLocale,
            'qr_code' => $qrcode,
            'date_expired' => $dateExpired->day.' '.$dateExpired->monthName.' '.$dateExpired->year,
            'dekan' => $dekan,
            'stempel' => $prodi->fakultas->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image, 0)) : null,
            // 'stempel' => $prodi->fakultas->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($prodi->fakultas->image, 31)) : null,
            'ttd_dekan' => $dekan->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image, 0)) : null,
            // 'ttd_dekan' => $dekan->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dekan->image, 31)) : null,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.suratTugasBimbingan', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Surat-Tugas-Bimbingan.pdf');
    }

    public function cetakBeritaAcaraUjianProposal($seminar)
    {
        $seminar = Seminar::findOrFail($seminar);

        $reviews_penguji = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get();
        $reviews_pembimbing = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PEMBIMBING)->get();

        $nilai_dosen_penguji_1 = AppHelper::instance()->hitung_nilai_seminar($reviews_penguji[0]->nilai_1, $reviews_penguji[0]->nilai_2, $reviews_penguji[0]->nilai_3, $reviews_penguji[0]->nilai_4);
        $nilai_dosen_penguji_2 = AppHelper::instance()->hitung_nilai_seminar($reviews_penguji[1]->nilai_1, $reviews_penguji[1]->nilai_2, $reviews_penguji[1]->nilai_3, $reviews_penguji[1]->nilai_4);
        $nilai_dosen_penguji_3 = AppHelper::instance()->hitung_nilai_seminar($reviews_penguji[2]->nilai_1, $reviews_penguji[2]->nilai_2, $reviews_penguji[2]->nilai_3, $reviews_penguji[2]->nilai_4);

        $nilai_dosen_pembimbing_1 = AppHelper::instance()->hitung_nilai_seminar($reviews_pembimbing[0]->nilai_1, $reviews_pembimbing[0]->nilai_2, $reviews_pembimbing[0]->nilai_3, $reviews_pembimbing[0]->nilai_4);
        $nilai_dosen_pembimbing_2 = AppHelper::instance()->hitung_nilai_seminar($reviews_pembimbing[1]->nilai_1, $reviews_pembimbing[1]->nilai_2, $reviews_pembimbing[1]->nilai_3, $reviews_pembimbing[1]->nilai_4);

        $nilai_dosen_pembimbing = ($nilai_dosen_pembimbing_1 + $nilai_dosen_pembimbing_2) / 2;
        $nilai_dosen_penguji = ($nilai_dosen_penguji_1 + $nilai_dosen_penguji_2 + $nilai_dosen_penguji_3) / 3;

        $prodi = Prodi::where('namaprodi', $seminar->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        $nilai = ($presentase_nilai->bobot_pembimbing / 100 * $nilai_dosen_pembimbing) + ($presentase_nilai->bobot_penguji / 100 * $nilai_dosen_penguji);

        $nilai_huruf = null;
        if ($nilai > 85) {
            $nilai_huruf = 'A';
        } else if ($nilai > 69) {
            $nilai_huruf = 'B';
        } else if ($nilai > 55) {
            $nilai_huruf = 'C';
        } else if ($nilai > 45) {
            $nilai_huruf = 'D';
        } else if ($nilai > 0) {
            $nilai_huruf = 'E';
        }

        $dosens = null;
        foreach ($seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get() as $review) {
            $dosens[] = $review->dosen;
        }

        $seminars_acc = $seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get();

        $data = [
            'title' => 'BERITA ACARA SEMINAR TUGAS AKHIR',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $seminar,
            'ttd_dosen_1' => $dosens[0]->ttd ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[0]->ttd, 31)): null,
            'ttd_dosen_2' => $dosens[0]->ttd ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[1]->ttd, 31)): null,
            'ttd_dosen_3' => $dosens[0]->ttd ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[2]->ttd, 31)): null,
            'dosen_1' => $dosens[0],
            'dosen_2' => $dosens[1],
            'dosen_3' => $dosens[2],
            'nilai' => $nilai_huruf,
            'is_complete' => count($seminars_acc) == 5 ? true : null,
            'tanggal_ujian' => Carbon::parse($seminar->tanggal_ujian)->day.' '.Carbon::parse($seminar->tanggal_ujian)->monthName.' '.Carbon::parse($seminar->tanggal_ujian)->year,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.berita-acara-ujian', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Seminar-Proposal.pdf');
    }

    public function cetakBeritaAcaraUjianPendadaran($ujian)
    {
        $ujian = Ujian::findOrFail($ujian);
        $prodi = Prodi::where('namaprodi', $ujian->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        $reviews_penguji = $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get();
        $reviews_pembimbing = $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PEMBIMBING)->get();

        $nilai_dosen_penguji_1 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[0]->nilai_1, $reviews_penguji[0]->nilai_2, $reviews_penguji[0]->nilai_3, $reviews_penguji[0]->nilai_4, $prodi->id);
        $nilai_dosen_penguji_2 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[1]->nilai_1, $reviews_penguji[1]->nilai_2, $reviews_penguji[1]->nilai_3, $reviews_penguji[1]->nilai_4, $prodi->id);
        $nilai_dosen_penguji_3 = AppHelper::instance()->hitung_nilai_ujian($reviews_penguji[2]->nilai_1, $reviews_penguji[2]->nilai_2, $reviews_penguji[2]->nilai_3, $reviews_penguji[2]->nilai_4, $prodi->id);

        $nilai_dosen_pembimbing_1 = AppHelper::instance()->hitung_nilai_ujian($reviews_pembimbing[0]->nilai_1, $reviews_pembimbing[0]->nilai_2, $reviews_pembimbing[0]->nilai_3, $reviews_pembimbing[0]->nilai_4, $prodi->id);
        $nilai_dosen_pembimbing_2 = AppHelper::instance()->hitung_nilai_ujian($reviews_pembimbing[1]->nilai_1, $reviews_pembimbing[1]->nilai_2, $reviews_pembimbing[1]->nilai_3, $reviews_pembimbing[1]->nilai_4, $prodi->id);

        $nilai_dosen_pembimbing = ($nilai_dosen_pembimbing_1 + $nilai_dosen_pembimbing_2) / 2;
        $nilai_dosen_penguji = ($nilai_dosen_penguji_1 + $nilai_dosen_penguji_2 + $nilai_dosen_penguji_3) / 3;

        $nilai = ($presentase_nilai->bobot_pembimbing / 100 * $nilai_dosen_pembimbing) + ($presentase_nilai->bobot_penguji / 100 * $nilai_dosen_penguji);

        $nilai_huruf = null;
        if ($nilai > 85) {
            $nilai_huruf = 'A';
        } else if ($nilai > 69) {
            $nilai_huruf = 'B';
        } else if ($nilai > 55) {
            $nilai_huruf = 'C';
        } else if ($nilai > 45) {
            $nilai_huruf = 'D';
        } else if ($nilai > 0) {
            $nilai_huruf = 'E';
        }

        $dosens = null;
        foreach ($ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get() as $review) {
            $dosens[] = $review->dosen;
        }

        $ujians_acc = $ujian->reviews()->where('status', ReviewUjian::DITERIMA)->get();

        $data = [
            'title' => 'BERITA ACARA UJIAN TUGAS AKHIR',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'ujian_or_seminar' => $ujian,
            'ttd_dosen_1' => AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[0]->ttd, 31)),
            'ttd_dosen_2' => AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[1]->ttd, 31)),
            'ttd_dosen_3' => AppHelper::instance()->convertImage('storage/app/public/' . substr($dosens[2]->ttd, 31)),
            'dosen_1' => $dosens[0],
            'dosen_2' => $dosens[1],
            'dosen_3' => $dosens[2],
            'nilai' => $nilai_huruf,
            'is_complete' => count($ujians_acc) == 5 ? true : null,
            'tanggal_ujian' => Carbon::parse($ujian->tanggal_ujian)->day.' '.Carbon::parse($ujian->tanggal_ujian)->monthName.' '.Carbon::parse($ujian->tanggal_ujian)->year,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.berita-acara-ujian', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Ujian-Pendadaran.pdf');
    }

    public function cetakRiwayatBimbinganMahasiswa(){
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran = Pendaftaran::where('pengajuan_id', $pengajuan->id)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosenUtama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosenPendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $qrcode = 'data:image/' . ';base64,' . base64_encode(\QrCode::format('svg')->size(200)->errorCorrection('H')->generate(url('public/riwayat-bimbingan/' . $mahasiswa->id)));

        $dateLocale = Carbon::parse(now())->day.' '.Carbon::parse(now())->monthName.' '.Carbon::parse(now())->year;

        $dateExpired = Carbon::parse($pendaftaran->tanggal_acc)->addMonthsNoOverflow(12);

        $bimbingan_dosen_utama = $dosenUtama->bimbingans()->with(['revisis','bagian'])->where('mahasiswa_id', $mahasiswa->id)->get();
        $bimbingan_dosen_pendamping = $dosenPendamping->bimbingans()->with(['revisis','bagian'])->where('mahasiswa_id', $mahasiswa->id)->get();

        $data = [
            'title' => 'Lembar Bimbingan Skripsi',
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
            'ttd_dosen_utama' => $dosenUtama->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenUtama->ttd, 0)) : null,
            'ttd_dosen_pendamping' => $dosenPendamping->ttd != null ? AppHelper::instance()->convertImage('storage/app/public/' . substr($dosenPendamping->ttd, 0)) : null,
            'bimbingan_dosen_utama' => $bimbingan_dosen_utama,
            'bimbingan_dosen_pendamping' => $bimbingan_dosen_pendamping,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.lembarBimbinganSkripsi', $data);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Lembar-Bimbingan-Skripsi.pdf');
    }
}
