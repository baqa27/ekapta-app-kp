<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewSeminar;
use App\Models\Seminar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;

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
            'dosen_utama' => $dosenUtama,
            'dosen_pendamping' => $dosenPendamping,
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

        $dateLocale = Carbon::parse(now())->formatLocalized('%d %B %Y');

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
            'date_expired' => Carbon::parse($dateExpired)->formatLocalized('%d %B %Y'),
            'dekan' => $dekan,
            'stempel' => AppHelper::instance()->convertImage('storage/app/public/' . $prodi->fakultas->image),
            'ttd_dekan' => AppHelper::instance()->convertImage('storage/app/public/' . $dekan->image),
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

        $dateLocale = Carbon::parse(now())->formatLocalized('%d %B %Y');

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
            'date_expired' => Carbon::parse($dateExpired)->formatLocalized('%d %B %Y'),
            'dekan' => $dekan,
            'stempel' => $prodi->fakultas->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . $prodi->fakultas->image) : '',
            'ttd_dekan' => $dekan->image != null ? AppHelper::instance()->convertImage('storage/app/public/' . $dekan->image) : '',
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

        $data = [
            'title' => 'Berita Acara Ujian Proposal',
            'kop_surat' => AppHelper::instance()->convertImage('public/ekapta/assets/img/kop-surat.jpg'),
            'seminar' => $seminar,
            'ttd_dosen_1' => AppHelper::instance()->convertImage('storage/app/public/' . $dosens[0]->ttd),
            'ttd_dosen_2' => AppHelper::instance()->convertImage('storage/app/public/' . $dosens[1]->ttd),
            'ttd_dosen_3' => AppHelper::instance()->convertImage('storage/app/public/' . $dosens[2]->ttd),
            'dosen_1' => $dosens[0],
            'dosen_2' => $dosens[1],
            'dosen_3' => $dosens[2],
            'nilai' => $nilai_huruf,
        ];

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->loadView('pages.cetak.berita-acara-ujian-proposal', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Berita-Acara-Ujian-Proposal.pdf');
    }
}
