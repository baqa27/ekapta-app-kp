@php
$bulan = null;
if ($date->format('m') == '01'){
$bulan = 'I';
}elseif ($date->format('m') == '02'){
$bulan = 'II';
}elseif ($date->format('m') == '03'){
$bulan = 'III';
}elseif ($date->format('m') == '04'){
$bulan = 'IV';
}elseif ($date->format('m') == '05'){
$bulan = 'V';
}elseif ($date->format('m') == '06'){
$bulan = 'VI';
}elseif ($date->format('m') == '07'){
$bulan = 'VII';
}elseif ($date->format('m') == '08'){
$bulan = 'VIII';
}elseif ($date->format('m') == '09'){
$bulan = 'IX';
}elseif ($date->format('m') == '10'){
$bulan = 'X';
}elseif ($date->format('m') == '11'){
$bulan = 'XI';
}elseif ($date->format('m') == '12'){
$bulan = 'XII';
}
@endphp
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
            font-family: 'Times New Roman', Times, serif;
        }

        .margin-left {
            margin-left: 80px;
        }

        p {
            font-size: 12pt;
            text-align: justify
        }

        .text-expired {
            margin-left: 80px;
            color: rgb(255, 89, 191);
        }

        .table-border {
            margin-left: 80px;
            width: 580px;
            border-collapse: collapse;
        }

        .table-border th,
        .table-border td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
            font-size: 11pt;
            page-break-inside: avoid;
        }

        .table-border th {
            background-color: #d9d9d9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-weight: bold;
            text-align: center;
        }

        /* Repeat kop + kolom header tiap halaman */
        .table-border thead {
            display: table-header-group;
        }

        .table-border tbody {
            display: table-row-group;
        }

        .table-border td.plain-cell,
        .table-border .plain td {
            border: 0;
            padding: 1px 2px;
            page-break-inside: auto;
        }

        .table-border td.plain-cell {
            padding: 0;
        }

        .table-border td.plain-cell h3 {
            text-decoration: underline;
            margin: 4px 0 10px 0;
        }

        .text-center {
            text-align: center;
        }

        .notes-cell {
            font-size: 10pt;
            line-height: 1.3;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <table class="table-border">
        <thead>
            <tr>
                <td colspan="5" class="plain-cell">
                    <table class="plain" style="width:100%;">
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <h3>LEMBAR BIMBINGAN SKRIPSI / TUGAS AKHIR (TA)</h3>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;">
                                <table class="plain">
                                    <tr>
                                        <td width="120">NAMA</td>
                                        <td width="2">:</td>
                                        <td>{{ $mahasiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIM</td>
                                        <td>:</td>
                                        <td>{{ $mahasiswa->nim }}</td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>:</td>
                                        <td>{{ $prodi->namaprodi }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align:top;">JUDUL TA</td>
                                        <td style="vertical-align:top;">:</td>
                                        <td>{{ $pengajuan->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td>PEMBIMBING 1</td>
                                        <td>:</td>
                                        <td>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</td>
                                    </tr>
                                    <tr>
                                        <td>NO SURAT TUGAS</td>
                                        <td>:</td>
                                        <td>{{ $no_urut }}/ST.TA/FASTIKOM-UNSIQ/{{ $bulan }}/{{ $date->format('Y') }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="90" style="vertical-align:top; text-align:right;">
                                <img src="{{ $qr_code }}" alt="QR Code" height="80">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th width="30">No</th>
                <th width="100">TANGGAL</th>
                <th width="120">BAGIAN (BAB)</th>
                <th width="250">KETERANGAN / CATATAN</th>
                <th width="80">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php $no_utama = 1; @endphp
            @forelse ($riwayat_utama as $grup)
                @foreach ($grup['rows'] as $row)
                <tr>
                    <td class="text-center">{{ $no_utama++ }}</td>
                    <td class="text-center">{{ $row['tanggal']->translatedFormat('d M Y') }}</td>
                    <td><b>{{ $grup['bagian'] }}</b></td>
                    <td class="notes-cell">{{ $row['catatan'] }}</td>
                    <td class="text-center">
                        @if($ttd_dosen_utama)
                            <img src="{{ $ttd_dosen_utama }}" height="50" style="max-width:60px;">
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center"><i>Belum ada data bimbingan.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <table class="table-border">
        <thead>
            <tr>
                <td colspan="5" class="plain-cell">
                    <table class="plain" style="width:100%;">
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <img src="{{ $kop_surat }}" alt="Kop Surat" height="151">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <h3>LEMBAR BIMBINGAN SKRIPSI / TUGAS AKHIR (TA)</h3>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align:top;">
                                <table class="plain">
                                    <tr>
                                        <td width="120">NAMA</td>
                                        <td width="2">:</td>
                                        <td>{{ $mahasiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIM</td>
                                        <td>:</td>
                                        <td>{{ $mahasiswa->nim }}</td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>:</td>
                                        <td>{{ $prodi->namaprodi }}</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align:top;">JUDUL TA</td>
                                        <td style="vertical-align:top;">:</td>
                                        <td>{{ $pengajuan->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td>PEMBIMBING 2</td>
                                        <td>:</td>
                                        <td>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</td>
                                    </tr>
                                    <tr>
                                        <td>NO SURAT TUGAS</td>
                                        <td>:</td>
                                        <td>{{ $no_urut }}/ST.TA/FASTIKOM-UNSIQ/{{ $bulan }}/{{ $date->format('Y') }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="90" style="vertical-align:top; text-align:right;">
                                <img src="{{ $qr_code }}" alt="QR Code" height="80">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th width="30">No</th>
                <th width="100">TANGGAL</th>
                <th width="120">BAGIAN (BAB)</th>
                <th width="250">KETERANGAN / CATATAN</th>
                <th width="80">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php $no_pendamping = 1; @endphp
            @forelse ($riwayat_pendamping as $grup)
                @foreach ($grup['rows'] as $row)
                <tr>
                    <td class="text-center">{{ $no_pendamping++ }}</td>
                    <td class="text-center">{{ $row['tanggal']->translatedFormat('d M Y') }}</td>
                    <td><b>{{ $grup['bagian'] }}</b></td>
                    <td class="notes-cell">{{ $row['catatan'] }}</td>
                    <td class="text-center">
                        @if($ttd_dosen_pendamping)
                            <img src="{{ $ttd_dosen_pendamping }}" height="50" style="max-width:60px;">
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center"><i>Belum ada data bimbingan.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="text-expired">
        <b><i>NB. BATAS MAKSIMAL SAMPAI PADA : {{ $date_expired }}</i></b>
    </p>
</body>

</html>
