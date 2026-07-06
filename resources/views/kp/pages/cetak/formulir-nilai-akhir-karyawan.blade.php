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

        .table-bordered {
            border: 1px solid black;
            border-collapse: collapse;
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
                    <p style="font-size: 10pt;">(PROGRAM KELAS KARYAWAN)</p>
                </center>
            </td>
        </tr>
    </table>

    <table class="margin-left">
        <tr>
            <td width="100">
                <p>Nama</p>
            </td>
            <td width="1">:</td>
            <td width="400">
                <b>{{ $mahasiswa->nama ?? '-' }}</b>
            </td>
        </tr>
        <tr>
            <td>
                <p>NIM</p>
            </td>
            <td width="1">:</td>
            <td>
                <b>{{ $mahasiswa->nim ?? '-' }}</b>
            </td>
        </tr>
        <tr>
            <td>
                <p>Prodi</p>
            </td>
            <td width="1">:</td>
            <td>
                <b>{{ $prodi->namaprodi ?? $mahasiswa->prodi ?? '-' }}</b>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top !important;">
                <p>Lokasi KP</p>
            </td>
            <td style="vertical-align: top !important;" width="1">:</td>
            <td>
                <b>{{ $pengajuan->lokasi_kp ?? '-' }}</b>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top !important;">
                <p>Judul KP</p>
            </td>
            <td style="vertical-align: top !important;" width="1">:</td>
            <td>
                <b>{{ $pengajuan->judul ?? '-' }}</b>
            </td>
        </tr>
        <tr>
            <td height="10"></td>
        </tr>
        <tr>
            <td style="vertical-align: top !important;">
                <p>Pembimbing</p>
            </td>
            <td style="vertical-align: top !important;" width="1">:</td>
            <td>
                <b>{{ $dosen_pembimbing->nama ?? '-' }}</b>
            </td>
        </tr>
    </table>

    <br>

    {{-- Tabel Nilai Karyawan: Hanya 2 komponen (Pembimbing & Instansi) --}}
    <table class="margin-left table-bordered">
        <tr>
            <td height="20" width="30"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>No.</b>
            </td>
            <td width="150"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>Komponen Penilaian</b>
            </td>
            <td width="60"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>Bobot</b>
            </td>
            <td width="60"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>Nilai</b>
            </td>
            <td width="80"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>Bobot x Nilai</b>
            </td>
            <td width="100"
                style="border: 1px solid black; background-color:rgb(189, 189, 189); text-align: center;">
                <b>Tanda Tangan<br>& Stempel</b>
            </td>
        </tr>
        <tr>
            <td height="60" style="border: 1px solid black; text-align: center;">1</td>
            <td style="border: 1px solid black; padding-left:5px;">Pembimbing</td>
            <td style="border: 1px solid black; text-align:center;"><b>{{ $bobot_pembimbing }}%</b></td>
            <td style="border: 1px solid black; text-align:center;"><b></b></td>
            <td style="border: 1px solid black; text-align:center;"><b></b></td>
            <td style="border: 1px solid black; text-align:center;"></td>
        </tr>
        <tr>
            <td height="60" style="border: 1px solid black; text-align: center;">2</td>
            <td style="border: 1px solid black; padding-left:5px;">Perusahaan</td>
            <td style="border: 1px solid black; text-align:center;"><b>{{ $bobot_instansi }}%</b></td>
            <td style="border: 1px solid black; text-align:center;"><b></b></td>
            <td style="border: 1px solid black; text-align:center;"><b></b></td>
            <td style="border: 1px solid black; text-align:center;"></td>
        </tr>
        <tr>
            <td height="40" style="border: 1px solid black; text-align: center;" colspan="4">
                <h4><b>NILAI AKHIR</b></h4>
            </td>
            <td style="border: 1px solid black; text-align:center;" colspan="2">
                <h4><b></b></h4>
            </td>
        </tr>
    </table>

    <br>

    <table class="margin-left">
        <tr>
            <td colspan="2">
                <p style="font-size: 10pt; font-style: italic;">
                    <b>Catatan:</b> Program Kelas Karyawan tidak mengikuti Seminar KP, sehingga penilaian hanya terdiri dari
                    komponen Pembimbing dan Perusahaan.
                </p>
            </td>
        </tr>
    </table>

    <br><br>

    <table>
        <tr>
            <td width="300"></td>
            <td width="300">
                <center>
                    <p>Wonosobo, {{ str_repeat('.', 30) }}</p>
                    <br>
                    <p>Koordinator KP FASTIKOM,</p>
                    <br><br><br><br>
                    <p><b><u>{{ $kaprodi->nama ?? str_repeat('.', 50) }}</u></b></p>
                    <br>
                    <p>NIDN {{ $kaprodi->nidn ?? str_repeat('.', 15) }}</p>
                </center>
            </td>
        </tr>
    </table>

</body>

</html>
