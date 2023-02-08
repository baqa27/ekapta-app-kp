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

        p, b,span {
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
                <p class="margin-left"><i>Bismillaahirrohmaanirrokhiim</i> </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br>
                <p class="margin-left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tugas Akhir Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas Sains Al-Qur’an (UNSIQ) Jawa Tengah di Wonosobo telah mengadakan Sidang pada:
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
                :
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Tanggal</p>
            </td>
            <td>
                :
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
                <b>: 2020150032</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Nama</b></p>
            </td>
            <td>
                <b>: Febi Arifin</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Program Studi</b></p>
            </td>
            <td>
                <b>: Teknik Informatika</b>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p><b>Judul Tugas Akhir</b></p>
            </td>
            <td>
                <b style="padding-right: 10px;">: Lorem ipsum dolor sit amet, consectetur adipisicing elit. </b>
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
                <p>: LULUS</p>
            </td>
        </tr>
        <tr>
            <td width="100"></td>
            <td>
                <p>Nilai </p>
            </td>
            <td>
                <p>: 100</p>
            </td>
        </tr> <tr>
            <td width="100"></td>
            <td>
                <p>Predikat </p>
            </td>
            <td>
                <p>: </p>
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
                    <img src="{{ $ttd_dekan }}" alt="TTD Dekan" height="70" id="ttd">
                    <br><br>
                    <span>Dosen Penguji I, M.Kom</span>
                </center>
            </td>
            <td>
                <center>
                    <span>Penguji II</span>
                    <br><br>
                    <img src="{{ $ttd_dekan }}" alt="TTD Dekan" height="70" id="ttd">
                    <br><br>
                    <span>Dosen Penguji II, M.Kom</span>
                </center>
            </td>
            <td>
                <center>
                    <span>Penguji I</span>
                    <br><br>
                    <img src="{{ $ttd_dekan }}" alt="TTD Dekan" height="70" id="ttd">
                    <br><br>
                    <span>Dosen Penguji I, M.Kom</span>
                </center>
            </td>
        </tr>
    </table>

</body>
</html>
