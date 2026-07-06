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

        p {
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
            left: 495px;
            bottom: -20px;
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
                    <h4>LEMBAR PERSETUJUAN <br>
                        CALON DOSEN PEMBIMBING KERJA PRAKTEK (KP)
                    </h4>
                </center>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left">Yang bertanda tangan di bawah ini </p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Nama Dosen Pembimbing</p>
            </td>
            <td width="20">
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $dosen_utama ? $dosen_utama->nama.', '.$dosen_utama->gelar : '......................................' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">NIDN </p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $dosen_utama ? $dosen_utama->nidn : '......................................' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Menyatakan </p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p><b>Menerima / Tidak Menerima</b> (coret yang tidak sesuai)</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="10"></td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left">Membimbing Kerja Praktek (KP) mahasiswa berikut: </p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Nama</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->nama }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">NIM</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->nim }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Program Studi</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $mahasiswa->prodi }}</p>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top !important;">
                <p class="margin-left">Judul Kerja Praktek</p>
            </td>
            <td style="vertical-align: top !important;">
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $pengajuan->judul }}</p>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top !important;">
                <p class="margin-left">Lokasi Kerja Praktek</p>
            </td>
            <td style="vertical-align: top !important;">
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $pengajuan->lokasi_kp ?? '-' }}</p>
            </td>
        </tr>
        <tr>
            <td width="200" height="50">
                <p class="margin-left top">Catatan Pembimbing</p>
            </td>
            <td>
                <p class="titik-dua top">:</p>
            </td>
            <td width="300">@for ($i = 0; $i < 200; $i++) {{ '.' }} @endfor </td>
        </tr>
        <tr>
            <td colspan="3" height="40">
                <p class="text-keterangan">Wonosobo, {{ $dateLocale ?? '………………………' }}</p>
            </td>
        </tr>
    </table>

    <table align="center">
        <tr>
            <td height="60" width="290">
                <center>
                    <p>
                        Mengetahui,<br>
                        Kaprodi {{ $prodi->namaprodi }} <br><br><br><br>
                        @if (\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi))
                        <b><u>{{ \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nama.', '.\App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->gelar }}</u></b><br>
                        <b>NIDN. {{ \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nidn }}</b>
                        @else
                        <b><u>........................................</u></b><br>
                        <b>NIDN. ........................</b>
                        @endif
                    </p>
                </center>
            </td>
            <td width="290">
                <center>
                    <p>
                        Menyetujui,<br>
                        Calon Pembimbing <br><br><br><br>
                        @if($dosen_utama)
                        <b><u>{{ $dosen_utama->nama.', '.$dosen_utama->gelar }}</u></b><br>
                        <b>NIDN. {{ $dosen_utama->nidn }}</b>
                        @else
                        <b><u>........................................</u></b><br>
                        <b>NIDN. ........................</b>
                        @endif
                    </p>
                </center>
            </td>
        </tr>
    </table>

</body>
</html>