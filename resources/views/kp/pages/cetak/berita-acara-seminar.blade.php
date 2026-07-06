<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
        }

        .margin-left {
            margin-left: 80px;
        }

        p,
        b,
        span {
            font-size: 11pt;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        .top {
            position: relative;
            top: -29px;
        }

        .text-keterangan {
            position: relative;
            left: 560px;
        }

        .table-bordered {
            border: 1px solid black;
            border-collapse: collapse;
            /* text-align: center; */
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
            </td>
        </tr>
        <tr>
            <td height="60">
                <center>
                    <h4>
                        <u>{{ $title }}</u>
                    </h4>
                </center>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left"><i>Bismillaahirrohmaanirrokhiim</i></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br>
                <p class="margin-left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kerja Praktek Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas
                    Sains Al-Qur’an (UNSIQ) Jawa Tengah di Wonosobo telah mengadakan Sidang pada:
                </p>
                <br>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="100"></td>
            <td width="100">
                <p>Hari</p>
            </td>
            <td width="1">:</td>
            <td>
                @if($ujian_or_seminar->tanggal_ujian)
                    {{ \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->dayName }}
                @else
                    ……………………………
                @endif
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Tanggal</p>
            </td>
            <td width="1">:</td>
            <td>
                @if($ujian_or_seminar->tanggal_ujian)
                    {{ \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->day . ' ' . \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->monthName . ' ' . \Carbon\Carbon::parse($ujian_or_seminar->tanggal_ujian)->year }}
                @else
                    ……………………………
                @endif
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="588" height="30">
                <center>
                    <b>MEMUTUSKAN</b>
                </center>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td height="20"></td>
            <td colspan="4">
                <p>Bahwa Saudara:</p>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td width="100">
                <p><b>NIM</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b>{{ $ujian_or_seminar->mahasiswa->nim }}</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Nama</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b>{{ $ujian_or_seminar->mahasiswa->nama }}</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Program Studi</b></p>
            </td>
            <td width="1">:</td>
            <td>
                <b>{{ $ujian_or_seminar->mahasiswa->prodi }}</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td style="vertical-align: top !important;">
                <p><b>Judul Kerja Praktek</b></p>
            </td>
            <td style="vertical-align: top !important;" width="1">:</td>
            <td width="350" style="text-align: justify;">
                <b style="padding-right: 10px;">{{ $ujian_or_seminar->pengajuan->judul }}</b>
            </td>
        </tr>
        <tr>
            <td height="20" colspan="4"></td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Dinyatakan </p>
            </td>
            <td width="1">:</td>
            <td>
                @if (!$is_blank)
                    <p>
                        @if ($ujian_or_seminar->is_lulus == 1)
                            LULUS
                        @elseif($ujian_or_seminar->is_lulus == 2)
                            TIDAK LULUS
                        @endif
                    </p>
                @endif
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Nilai </p>
            </td>
            <td width="1">:</td>
            <td>
                <p>
                    @if ($is_complete)
                        {{ $nilai }}
                    @elseif($nilai_angka)
                        {{ number_format($nilai_angka, 2) }}
                    @endif
                </p>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Predikat </p>
            </td>
            <td width="1">:</td>
            <td>
                <p>
                    @if ($is_complete || $nilai)
                        @if ($nilai == 'A')
                            Baik Sekali
                        @elseif($nilai == 'B')
                            Baik
                        @elseif($nilai == 'C')
                            Cukup
                        @elseif($nilai == 'D')
                            Kurang
                        @elseif($nilai == 'E')
                            Kurang Sekali
                        @endif
                    @endif
                </p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" width="700" height="50">
                <p class="text-keterangan">Wonosobo, {{ $tanggal_ujian }}</p>
            </td>
        </tr>
        <tr>
            @php
                $no = 0;
                $reviews_penguji = $ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get();
            @endphp
            @if(count($reviews_penguji) > 0)
                @foreach ($reviews_penguji as $review)
                    @php
                        $no++;
                        $ttd_dosen = null;
                        if (!$is_blank) {
                            $ttd_dosen = $review->dosen->ttd
                                ? \App\Helpers\AppHelper::instance()->convertStorageImage($review->dosen->ttd)
                                : null;
                        }
                    @endphp
                    <td height="120">
                        <center>
                            <span>Penguji
                                @if ($no == 1)
                                    I
                                @elseif ($no == 2)
                                    II
                                @elseif ($no == 3)
                                    III
                                @endif
                            </span>
                            <br><br>
                            @if($ttd_dosen)
                                <img src="{{ $ttd_dosen }}" alt="TTD Dosen" height="70">
                            @else
                                <br><br><br>
                            @endif
                            <br><br>
                            <span>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</span>
                        </center>
                    </td>
                @endforeach
            @else
                {{-- Placeholder jika belum ada penguji --}}
                <td height="120" colspan="3">
                    <center>
                        <span class="text-muted">Dosen penguji belum diploting</span>
                    </center>
                </td>
            @endif
        </tr>
    </table>
    <br><br><br><br>

    @php
        $no = 0;
        $reviews_penguji = $ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get();
    @endphp
    @foreach ($reviews_penguji as $review)
        @php
            $no++;
            if (!$is_blank) {
                $ttd_dosen_penguji = $review->dosen->ttd
                    ? \App\Helpers\AppHelper::instance()->convertStorageImage($review->dosen->ttd)
                    : null;
                // Untuk KP, nilai diambil dari seminar_kps, bukan dari review
                $nilai_penguji_display = $ujian_or_seminar->nilai_penguji ?? $ujian_or_seminar->nilai_seminar ?? 0;
            }
        @endphp
        <table>
            <tr>
                <td>
                    <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
                </td>
            </tr>
            <tr>
                <td height="60">
                    <center>
                        <h4>
                            <u>{{ $title_form_nilai }}</u>
                        </h4>
                    </center>
                </td>
            </tr>
        </table>
        <table class="margin-left">
            <tr>
                <td width="100">
                    <p><b>NIM</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b>{{ $ujian_or_seminar->mahasiswa->nim }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Nama</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b>{{ $ujian_or_seminar->mahasiswa->nama }}</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p><b>Program Studi</b></p>
                </td>
                <td width="1">:</td>
                <td>
                    <b>{{ $ujian_or_seminar->mahasiswa->prodi }}</b>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top !important;">
                    <p><b>Judul Kerja Praktek</b></p>
                </td>
                <td style="vertical-align: top !important;" width="1">:</td>
                <td width="400" style="text-align: justify;">
                    <b style="padding-right: 10px;">{{ $ujian_or_seminar->pengajuan->judul }}</b>
                </td>
            </tr>
        </table>
        <br>
        {{-- Tabel Penilaian KP - Sederhana, hanya NILAI TOTAL --}}
        <table class="margin-left table-bordered">
            <tr>
                <td height="20" width="275"
                    style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center; padding: 10px;">
                    <b>NILAI SEMINAR KP</b>
                </td>
                <td width="150"
                    style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                    <b>JUMLAH NILAI</b>
                </td>
            </tr>
            <tr>
                <td height="60" style="border: 1px solid black; text-align: center; padding: 10px;">
                    <h4><b>NILAI TOTAL</b></h4>
                </td>
                <td height="60" style="border: 1px solid black; text-align:center; font-size: 18pt;">
                    <b>{{ $is_blank ? '……………' : number_format($nilai_penguji_display, 2) }}</b>
                </td>
            </tr>
        </table>
        <br>
        <table class="margin-left">
            <tr>
                <td width="50" style="vertical-align: top !important;">
                    Catatan :
                </td>
                <td style="vertical-align: top !important;" width="1"></td>
                <td width="390">
                    @for ($i = 0; $i < 455; $i++)
                        {{ '.' }}
                    @endfor
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="3" width="700" height="50">
                    <p class="text-keterangan">Wonosobo, {{ $tanggal_ujian }}</p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <center>
                        <span>Penguji
                            @if ($no == 1)
                                I
                            @elseif ($no == 2)
                                II
                            @elseif ($no == 3)
                                III
                            @endif
                        </span>
                        @if ($is_blank)
                            <br><br><br><br>
                        @else
                            <br>
                            @if(isset($ttd_dosen_penguji) && $ttd_dosen_penguji)
                                <img src="{{ $ttd_dosen_penguji }}" alt="TTD Dosen" height="70">
                            @else
                                <br><br><br>
                            @endif
                            <br>
                        @endif
                        <span>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</span>
                    </center>
                </td>
            </tr>
        </table>
    @endforeach

    @if (!$is_blank)
        @php
            $reviews_pembimbing = $ujian_or_seminar->reviews()->where('dosen_status', 'pembimbing')->get();
        @endphp
        @foreach ($reviews_pembimbing as $review)
            @php
                $ttd_dosen_pembimbing = $review->dosen->ttd
                    ? \App\Helpers\AppHelper::instance()->convertStorageImage($review->dosen->ttd)
                    : null;
                // Untuk KP, nilai diambil dari seminar_kps, bukan dari review
                $nilai_pembimbing_display = $ujian_or_seminar->nilai_pembimbing ?? 0;
            @endphp
            <table>
                <tr>
                    <td>
                        <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
                    </td>
                </tr>
                <tr>
                    <td height="60">
                        <center>
                            <h4>
                                <u>{{ $title_form_nilai }}</u>
                            </h4>
                        </center>
                    </td>
                </tr>
            </table>
            <table class="margin-left">
                <tr>
                    <td width="100">
                        <p><b>NIM</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->nim }}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Nama</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->nama }}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Program Studi</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->prodi }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top !important;">
                        <p><b>Judul Kerja Praktek</b></p>
                    </td>
                    <td style="vertical-align: top !important;" width="1">:</td>
                    <td width="400" style="text-align: justify;">
                        <b style="padding-right: 10px;">{{ $ujian_or_seminar->pengajuan->judul }}</b>
                    </td>
                </tr>
            </table>
            <br>
            {{-- Tabel Penilaian KP - Sederhana, hanya NILAI TOTAL --}}
            <table class="margin-left table-bordered">
                <tr>
                    <td height="20" width="275"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center; padding: 10px;">
                        <b>NILAI SEMINAR KP</b>
                    </td>
                    <td width="150"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                        <b>JUMLAH NILAI</b>
                    </td>
                </tr>
                <tr>
                    <td height="60" style="border: 1px solid black; text-align: center; padding: 10px;">
                        <h4><b>NILAI TOTAL</b></h4>
                    </td>
                    <td height="60" style="border: 1px solid black; text-align:center; font-size: 18pt;">
                        <b>{{ number_format($nilai_pembimbing_display, 2) }}</b>
                    </td>
                </tr>
            </table>
            <br>
            <table class="margin-left">
                <tr>
                    <td width="50" style="vertical-align: top !important;">
                        Catatan :
                    </td>
                    <td style="vertical-align: top !important;" width="1"></td>
                    <td width="390">
                        @for ($i = 0; $i < 455; $i++)
                            {{ '.' }}
                        @endfor
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="3" width="700" height="50">
                        <p class="text-keterangan">Wonosobo, {{ $tanggal_ujian }}</p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <center>
                            <span>Pembimbing
                                @if ($no == 1)
                                    I
                                @elseif ($no == 2)
                                    II
                                @endif
                            </span>
                            <br>
                            @if($ttd_dosen_pembimbing)
                                <img src="{{ $ttd_dosen_pembimbing }}" alt="TTD Dosen" height="70">
                            @else
                                <br><br><br>
                            @endif
                            <br>
                            <span>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</span>
                        </center>
                    </td>
                </tr>
            </table>
        @endforeach
    @endif

    @if ($is_blank)
        @php
            $no = 0;
        @endphp
        @foreach ($ujian_or_seminar->reviews()->where('dosen_status', 'penguji')->get() as $review)
            @php
                $no++;
            @endphp
            <table>
                <tr>
                    <td>
                        <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
                    </td>
                </tr>
                <tr>
                    <td height="60">
                        <center>
                            <h4>
                                <u>{{ $title_form_revisi }}</u>
                            </h4>
                        </center>
                    </td>
                </tr>
            </table>
            <table class="margin-left">
                <tr>
                    <td width="100">
                        <p><b>NIM</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->nim }}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Nama</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->nama }}</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Program Studi</b></p>
                    </td>
                    <td width="1">:</td>
                    <td>
                        <b>{{ $ujian_or_seminar->mahasiswa->prodi }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top !important;">
                        <p><b>Judul Kerja Praktek</b></p>
                    </td>
                    <td style="vertical-align: top !important;" width="1">:</td>
                    <td width="400" style="text-align: justify;">
                        <b style="padding-right: 10px;">{{ $ujian_or_seminar->pengajuan->judul }}</b>
                    </td>
                </tr>
            </table>
            <br>
            <table class="margin-left table-bordered">
                <tr>
                    <td height="20" width="20"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">NO
                    </td>
                    <td width="370"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                        URAIAN
                        PENILAIAN</td>
                    <td width="100"
                        style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">TANDA
                        TANGAN
                    </td>
                </tr>
                <tr>
                    <td height="340" style="border: 1px solid black; text-align: center;"></td>
                    <td style="border: 1px solid black; padding-left:5px;"></td>
                    <td style="border: 1px solid black; text-align:center;"></td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <td width="320">
                        <center>
                            <span>Acc. Revisi pada tanggal</span><br><br>
                            <span>....................................................</span><br><br>
                            <span>Penguji
                                @if ($no == 1)
                                    I
                                @elseif ($no == 2)
                                    II
                                @elseif ($no == 3)
                                    III
                                @endif
                            </span>
                            <br><br><br><br>
                            <span>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span>Wonosobo, {{ $tanggal_ujian }}</span><br><br>
                            </span><br><br>
                            <span>Penguji
                                @if ($no == 1)
                                    I
                                @elseif ($no == 2)
                                    II
                                @elseif ($no == 3)
                                    III
                                @endif
                            </span>
                            <br><br><br><br>
                            <span>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</span>
                        </center>
                    </td>
                </tr>
            </table>
        @endforeach
    @endif

</body>

</html>




