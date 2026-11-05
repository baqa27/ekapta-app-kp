@php
    $bulan = null;
    $m = $date->format('m');
    if ($m == '01') $bulan = 'I';
    elseif ($m == '02') $bulan = 'II';
    elseif ($m == '03') $bulan = 'III';
    elseif ($m == '04') $bulan = 'IV';
    elseif ($m == '05') $bulan = 'V';
    elseif ($m == '06') $bulan = 'VI';
    elseif ($m == '07') $bulan = 'VII';
    elseif ($m == '08') $bulan = 'VIII';
    elseif ($m == '09') $bulan = 'IX';
    elseif ($m == '10') $bulan = 'X';
    elseif ($m == '11') $bulan = 'XI';
    elseif ($m == '12') $bulan = 'XII';
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
            margin-left: 50px;
        }

        p {
            font-size: 12pt;
            text-align: justify
        }

        .margin-right {
            margin-right: 40px;
        }

        .titik-dua {
            margin-left: 10px;
            margin-right: 10px;
        }

        #qr-code {
            margin-top: -40px;
            margin-left: 620px;
        }

        .table-border {
            border: 0;
            border-collapse: collapse;
            width: 87%; /* Adjusted for margin-left 50px */
        }

        .table-border th,
        .table-border td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
            font-size: 11pt;
        }

        .text-center {
            text-align: center;
        }

        ul {
            margin: 0;
            padding-left: 15px;
        }

        /* Prevent page break inside table rows */
        .table-border tr {
            page-break-inside: avoid;
        }

        /* Allow page break between rows */
        .table-border tbody tr {
            page-break-after: auto;
        }

        /* Repeat table header on each page */
        .table-border thead {
            display: table-header-group;
        }

        .table-border tbody {
            display: table-row-group;
        }

        /* Table fixes for DomPDF - consistent with surat tugas */
        .table-border th {
            background-color: #d9d9d9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-weight: bold;
            padding: 8px 6px;
        }
        .table-border td {
            padding: 6px;
        }
        .table-border td.plain-cell,
        .table-border .plain td {
            border: 0;
            padding: 1px 2px;
        }
        .table-border td.plain-cell {
            padding: 0;
        }
        .table-border td.plain-cell h3 {
            text-decoration: underline;
            margin: 4px 0 10px 0;
        }
        .notes-cell {
            font-size: 10pt;
            line-height: 1.3;
        }
        .notes-cell ul {
            margin: 2px 0 0 0;
            padding-left: 14px;
        }
        .notes-cell li {
            margin: 1px 0;
            list-style-type: disc;
        }
        .notes-cell .divider {
            border: 0;
            border-top: 1px dashed #999;
            margin: 3px 0;
        }
        .notes-cell .empty {
            font-style: italic;
            color: #666;
        }
        .notes-cell .note-block {
            margin-bottom: 4px;
        }

        .page-break {
            page-break-before: always;
        }

        .header-block {
            page-break-inside: avoid;
        }

        .bimbingan-table {
            page-break-inside: auto;
        }

        .bimbingan-table tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
@php $no = 1; @endphp

<table class="margin-left table-border">
    <thead>
        <tr>
            <td colspan="5" class="plain-cell">
                <table class="plain" style="width:100%;">
                    <tr>
                        <td style="text-align:center;"><img src="{{ $kop_surat }}" alt="Kop Surat" height="151"></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"><h3>LEMBAR BIMBINGAN KERJA PRAKTEK (KP)</h3></td>
                    </tr>
                </table>
                <table class="plain" style="width:100%;">
                    <tr>
                        <td style="vertical-align:top;">
                            <table class="plain">
                                <tr><td width="150">NAMA</td><td width="2">:</td><td>{{ $mahasiswa->nama }}</td></tr>
                                <tr><td>NIM</td><td>:</td><td>{{ $mahasiswa->nim }}</td></tr>
                                <tr><td>PRODI</td><td>:</td><td>{{ $prodi->namaprodi ?? $mahasiswa->prodi }}</td></tr>
                                <tr><td style="vertical-align:top;">JUDUL KP</td><td style="vertical-align:top;">:</td><td>{{ $pengajuan->judul }}</td></tr>
                                <tr><td>PEMBIMBING</td><td>:</td><td>{{ $dosen_utama->nama }}{{ $dosen_utama->gelar ? ', ' . $dosen_utama->gelar : '' }}</td></tr>
                                <tr>
                                    <td>NO SURAT TUGAS</td><td>:</td>
                                    <td>@if($no_urut){{ $no_urut }}/ST.KP/FASTIKOM-UNSIQ/{{ $bulan }}/{{ $date->format('Y') }}@else-@endif</td>
                                </tr>
                                <tr><td>MASA BERLAKU</td><td>:</td><td>{{ $dateLocale }} s/d {{ $date_expired }}</td></tr>
                            </table>
                        </td>
                        <td width="90" style="vertical-align:top; text-align:right;">
                            <img src="{{ $qr_code }}" alt="QR Code" height="80">
                        </td>
                    </tr>
                </table>
                <br>
            </td>
        </tr>
        <tr style="background-color: #d9d9d9">
            <th width="30" class="text-center">No</th>
            <th width="80" class="text-center">Tanggal</th>
            <th width="120" class="text-center">Bagian (BAB)</th>
            <th class="text-center">Keterangan / Catatan</th>
            <th width="60" class="text-center">Tanda Tangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($riwayat_bimbingan as $grup)
            @foreach ($grup['rows'] as $row)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $row['tanggal']->translatedFormat('d M Y') }}</td>
                <td style="padding-left: 8px;"><b>{{ $grup['bagian'] }}</b></td>
                <td class="notes-cell" style="padding: 6px;">{{ $row['catatan'] }}</td>
                <td class="text-center">
                    @if($ttd_dosen_utama)
                        <img src="{{ $ttd_dosen_utama }}" height="40" style="max-width: 100%; object-fit:contain;">
                    @endif
                </td>
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">
                    <i>Belum ada data bimbingan.</i>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

        <table class="margin-left" style="margin-top: 10px;">
            <tr>
                <td>
                    <p style="font-size: 10pt; color: rgb(255, 89, 191);">
                       <b> NB. BATAS MAKSIMAL SAMPAI PADA : {{ $date_expired }}</b>
                    </p>
                </td>
            </tr>
        </table>
</body>
</html>
