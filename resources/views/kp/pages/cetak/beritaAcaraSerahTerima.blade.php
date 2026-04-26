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

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
        }

        .container {
            margin: 60px 70px 60px 80px;
        }

        h3 {
            text-decoration: underline;
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            font-size: 12pt;
            text-align: justify;
            line-height: 1.6;
        }

        .info-table td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 12pt;
        }

        .info-table .label {
            width: 100px;
            padding-left: 40px;
        }

        .info-table .separator {
            width: 15px;
            text-align: center;
        }

        .pihak-bold {
            font-weight: bold;
        }

        ol {
            margin-left: 20px;
            font-size: 12pt;
            line-height: 1.8;
        }

        ol li {
            text-align: justify;
            margin-bottom: 5px;
        }

        .ttd-table {
            width: 100%;
            margin-top: 40px;
        }

        .ttd-table td {
            text-align: center;
            vertical-align: top;
            font-size: 12pt;
        }

        .ttd-space {
            height: 100px;
        }

        .underline {
            text-decoration: underline;
            font-weight: bold;
        }

        .blank-line {
            display: inline-block;
            min-width: 200px;
            border-bottom: 1px dotted #000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>BERITA ACARA SERAH TERIMA</h3>

        <p>
            Pada hari ini, {{ $hari }} tanggal {{ $tanggal_text }} bulan {{ $bulan_text }} tahun {{ $tahun_text }}, kami yang bertanda tangan di bawah ini :
        </p>
        <br>

        {{-- PIHAK PERTAMA --}}
        <table class="info-table">
            <tr>
                <td colspan="3" style="padding-left: 0;"><strong>I.</strong></td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td class="label">Institusi</td>
                <td class="separator">:</td>
                <td>Universitas Sains Al-Qur'an</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>Jl. KH. Hasyim Asy'ari Km. 03, Mojotengah, Wonosobo</td>
            </tr>
        </table>
        <p>
            Dalam hal ini bertindak untuk dan atas nama Program Studi {{ $prodi->namaprodi }},
            Fakultas Teknik dan Ilmu Komputer, Universitas Sains Al-Qur'an yang selanjutnya
            disebut sebagai <span class="pihak-bold">PIHAK PERTAMA</span>
        </p>
        <br>

        {{-- PIHAK KEDUA --}}
        <table class="info-table">
            <tr>
                <td colspan="3" style="padding-left: 0;"><strong>II.</strong></td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>...............................................</td>
            </tr>
            <tr>
                <td class="label">Institusi</td>
                <td class="separator">:</td>
                <td>{{ $pengajuan->lokasi_kp ?? '.............................................' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>...............................................</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>{{ $pengajuan->alamat_instansi ?? '.............................................' }}</td>
            </tr>
        </table>
        <p>
            Selanjutnya disebut sebagai <span class="pihak-bold">PIHAK KEDUA</span>
        </p>
        <br>

        <p>
            Kedua belah pihak telah bersepakat mengadakan serah terima produk dengan ketentuan sebagai berikut:
        </p>

        <ol>
            <li>Pihak pertama menyerahkan kepada pihak kedua produk berupa "<strong>{{ $pengajuan->judul }}</strong>"</li>
            <li>Pihak kedua menerima penyerahan sebagaimana tersebut pada ayat 1 dari pihak pertama</li>
            <li>Pihak kedua berhak menggunakan dan mengimplementasikan produk yang telah diserahkan oleh pihak pertama untuk digunakan sebagaimana mestinya</li>
        </ol>
        <br>

        <p>
            Berita acara serah terima ini dibuat dengan sesungguhnya, untuk dipergunakan sebagaimana mestinya.
        </p>

        {{-- TTD --}}
        <table class="ttd-table">
            <tr>
                <td width="50%"><strong>PIHAK PERTAMA</strong></td>
                <td width="50%"><strong>PIHAK KEDUA</strong></td>
            </tr>
            <tr>
                <td>Yang menyerahkan,</td>
                <td>Yang menerima,</td>
            </tr>
            <tr>
                <td class="ttd-space"></td>
                <td class="ttd-space"></td>
            </tr>
            <tr>
                <td><span class="underline">{{ $mahasiswa->nama }}</span></td>
                <td><span class="underline">...............................................</span></td>
            </tr>
        </table>
    </div>
</body>

</html>
