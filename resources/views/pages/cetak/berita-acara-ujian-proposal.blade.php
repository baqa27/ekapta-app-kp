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

        p, b, span {
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
                    <u>BERITA ACARA UJIAN TUGAS AKHIR</u>
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
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tugas Akhir Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas
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
        <td>
            : {{ \Carbon\Carbon::parse($seminar->tanggal_ujian)->dayName }}
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p>Tanggal</p>
        </td>
        <td>
            : {{ \Carbon\Carbon::parse($seminar->tanggal_ujian)->day.' '.\Carbon\Carbon::parse($seminar->tanggal_ujian)->monthName.' '.\Carbon\Carbon::parse($seminar->tanggal_ujian)->year  }}
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
        <td colspan="2">
            <p>Bahwa Saudara:</p>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td width="100">
            <p><b>NIM</b></p>
        </td>
        <td>
            <b>: {{ $seminar->mahasiswa->nim }}</b>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p><b>Nama</b></p>
        </td>
        <td>
            <b>: {{ $seminar->mahasiswa->nama }}</b>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p><b>Program Studi</b></p>
        </td>
        <td>
            <b>: {{ $seminar->mahasiswa->prodi }}</b>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p><b>Judul Tugas Akhir</b></p>
        </td>
        <td>
            <b style="padding-right: 10px;">: {{ $seminar->pengajuan->judul }}</b>
        </td>
    </tr>
    <tr>
        <td height="20" colspan="3"></td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p>Dinyatakan </p>
        </td>
        <td>
            <p>:
                @if($seminar->is_lulus == 1)
                    LULUS
                @elseif($seminar->is_lulus == 2)
                    TIDAK LULUS
                @endif
            </p>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p>Nilai </p>
        </td>
        <td>
            <p>:
                @if($is_complete != null)
                    {{ $nilai }}
                @endif
            </p>
        </td>
    </tr>
    <tr>
        <td width="100"></td>
        <td>
            <p>Predikat </p>
        </td>
        <td>
            <p>:
                @if($is_complete != null)
                    @if($nilai == 'A')
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
            <p class="text-keterangan">Wonosobo,
                8 Februari 2023</p>
        </td>
    </tr>
    <tr>
        <td height="120">
            <center>
                <span>Penguji I</span>
                <br><br>
                <img src="{{ $ttd_dosen_1 }}" alt="TTD Dekan" height="70" id="ttd">
                <br><br>
                <span>{{ $dosen_1->nama }}, {{ $dosen_1->gelar }}</span>
            </center>
        </td>
        <td>
            <center>
                <span>Penguji II</span>
                <br><br>
                <img src="{{ $ttd_dosen_2 }}" alt="TTD Dekan" height="70" id="ttd">
                <br><br>
                <span>{{ $dosen_2->nama }}, {{ $dosen_2->gelar }}</span>
            </center>
        </td>
        <td>
            <center>
                <span>Penguji III</span>
                <br><br>
                <img src="{{ $ttd_dosen_3 }}" alt="TTD Dekan" height="70" id="ttd">
                <br><br>
                <span>{{ $dosen_3->nama }}, {{ $dosen_3->gelar }}</span>
            </center>
        </td>
    </tr>
</table>

</body>
</html>
