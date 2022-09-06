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
            font-size: 12pt;
            text-align: justify
        }

        .margin-right {
            margin-right: 40px;
        }

        .margin-top {
            position: relative;
            top: 10px;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        .top {
            position: relative;
            top: -24px;
        }

        .text-keterangan {
            position: relative;
            left: 495px;
        }

        #qr-code {
            margin-top: -40px;
            margin-left: 670px;
        }

        .text-expired {
            margin-left: 80px;
            color: rgb(255, 89, 191);
        }

        .d-flex {
            display: flex;
        }

        #stempel {
            opacity: 40%;
            position: relative;
            top: 10px;
            right: -20px;
        }

        #ttd {
            position: relative;
            left: -80px;
        }

        #detail-dekan {
            position: relative;
            top: -30px
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
            <td>
                <center>
                    <h3>SURAT TUGAS PEMBIMBINGAN TUGAS AKHIR/SKRIPSI <br>
                        No. {{ $pendaftaran->id }}/FASTIKOM-UNSIQ/
                        @if ($date->format('m') == '01')
                            I
                        @elseif ($date->format('m') == '02')
                            II
                        @elseif ($date->format('m') == '03')
                            III
                        @elseif ($date->format('m') == '04')
                            IV
                        @elseif ($date->format('m') == '05')
                            V
                        @elseif ($date->format('m') == '06')
                            VI
                        @elseif ($date->format('m') == '07')
                            VII
                        @elseif ($date->format('m') == '08')
                            VIII
                        @elseif ($date->format('m') == '09')
                            IX
                        @elseif ($date->format('m') == '10')
                            X
                        @elseif ($date->format('m') == '11')
                            XI
                        @elseif ($date->format('m') == '12')
                            XII
                        @endif
                        /{{ $date->format('Y') }}
                    </h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>
    <img src="{{ $qr_code }}" alt="QR Code" height="80" id="qr-code">
    <table>
        <tr>
            <td colspan="3">
                <p class="margin-left"><b><i>Assalamu'alaikum Wr. Wb.</i></b> </p>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left margin-right">Dekan Fakultas Teknik dan Ilmu Komputer (FASTIKOM) Universitas Sains
                    Al-Qur'an
                    (UNSIQ) Jawa Tengah di Wonosobo, memberikan tugas kepada:</p>
                <br>
            </td>
        </tr>
        <tr>
            <td width="180">
                <p class="margin-left">1. Nama</p>
            </td>
            <td width="20">
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p class="margin-top">{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }} <br>
                    (Selaku Pembimbing 1)
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">2. Nama </p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p class="margin-top">{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }} <br>
                    (Selaku Pembimbing 2)
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br>
                <p class="margin-left">Untuk memberikan bimbingan Tugas Akhir (TA) / Skripsi kepada mahasiswa tersebut
                    dibawah ini:</p>
                <br>
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
            <td>
                <p class="margin-left">Tanggal Pembayaran</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td>
                <p>{{ $pendaftaran->tanggal_pembayaran }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="margin-left">Judul Tugas Akhir</p>
            </td>
            <td>
                <p class="titik-dua">:</p>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <p class="margin-right top"><b>{{ $pendaftaran->judul }}</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left margin-right">Selama melakukan pembimbingan, harus dilaksanakan dengan
                    sungguh-sungguh dan
                    tidak menyimpang dari kaidah keilmuannya. Pembimbingan TA / Skripsi makasimal dilakukan selama 12
                    bulan (2 Semester). Jika sampai batas waktu yang telah ditentukan mahasiswa tersebut belum
                    menyelesaikan TA / Skripsi, maka TA / Skripsi tersebut dianggap gugur dan mahasiswa harus mengambil
                    judul TA / Skripsi yang berbeda dari judul sebelumnya.</p>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="margin-left"><b><i>Wassalamu'alakum Wr. Wb.</i></b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="text-keterangan">Wonosobo,
                    {{ $dateLocale }}</p>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td height="60" width="290"></td>
            <td width="290">
                <center>
                    <span>
                        Dekan <br>
                        <div class="d-flex">
                            <img src="{{ $stempel }}" alt="Stempel Dekan" height="140" id="stempel">
                            <img src="{{ $ttd_dekan }}" alt="TTD Dekan" height="110" id="ttd">
                        </div>
                        <div id="detail-dekan">
                            <b><u>{{ $dekan->namadekan . ', ' . $dekan->gelar }}</u>
                            </b><br>
                            <b>NPU. {{ $dekan->nidn }}</b>
                        </div>
                    </span>
                </center>
            </td>
        </tr>
    </table>
    <p class="text-expired">
        <b><i>NB. BATAS MAKSIMAL SAMPAI PADA : {{ $date_expired }}</i></b>
    </p>

</body>

</html>
