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
            border: 1px solid black;
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
                    <h3>LEMBAR BIMBINGAN KERJA PRAKTEK (KP)</h3>
                    <br>
                </center>
            </td>
        </tr>
    </table>

    <img src="{{ $qr_code }}" alt="QR Code" height="80" id="qr-code">

    <table class="margin-left">
        <tr>
            <td width="150">NAMA</td>
            <td width="2">:</td>
            <td width="350">{{ $mahasiswa->nama }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td width="2">:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>PRODI</td>
            <td width="2">:</td>
            <td>{{ $prodi->namaprodi ?? $mahasiswa->prodi }}</td>
        </tr>
        <tr>
            <td style="text-align: left;vertical-align: top;">JUDUL KP</td>
            <td width="2" style="text-align: left;vertical-align: top;">:</td>
            <td>{{ $pengajuan->judul }}</td>
        </tr>
        <tr>
            <td>PEMBIMBING</td>
            <td width="2">:</td>
            <td>{{ $dosen_utama->nama }}{{ $dosen_utama->gelar ? ', ' . $dosen_utama->gelar : '' }}</td>
        </tr>
        <tr>
            <td>NO SURAT TUGAS</td>
            <td width="2">:</td>
            <td>
                @if($no_urut)
                    {{ $no_urut }}/ST.KP/FASTIKOM-UNSIQ/{{$bulan}}/{{ $date->format('Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>MASA BERLAKU</td>
            <td width="2">:</td>
            <td>{{ $dateLocale }} s/d {{ $date_expired }}</td>
        </tr>
    </table>

    <br>

    <table class="margin-left table-border">
        <thead>
            <tr style="background-color: #d9d9d9">
                <th width="30" class="text-center">No</th>
                <th width="120" class="text-center">Bagian (BAB)</th>
                <th width="80" class="text-center">Tanggal</th>
                <th class="text-center">Keterangan / Catatan</th>
                <th width="60" class="text-center">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $all_bimbingan = collect([]);
                
                // Online
                foreach ($bimbingan_dosen_utama as $bimb) {
                    $all_bimbingan->push([
                        'type' => 'online',
                        'bagian_id' => $bimb->bagian_id,
                        'bagian_name' => $bimb->bagian ? $bimb->bagian->bagian : 'Lainnya',
                        'tanggal' => $bimb->tanggal_acc,
                        'catatan_mahasiswa' => $bimb->keterangan,
                        'catatan_dosen' => $bimb->revisis,
                        'id_for_sort' => $bimb->id,
                    ]);
                }
                
                // Manual
                foreach ($bimbingan_manual as $manual) {
                    $all_bimbingan->push([
                        'type' => 'manual',
                        'bagian_id' => $manual->bimbingan->bagian_id ?? 'manual',
                        'bagian_name' => $manual->bimbingan && $manual->bimbingan->bagian ? $manual->bimbingan->bagian->bagian : 'Bimbingan Manual',
                        'tanggal' => $manual->tanggal_bimbingan,
                        'catatan_mahasiswa' => $manual->keterangan,
                        'catatan_dosen' => $manual->catatan_reviewer,
                        'id_for_sort' => $manual->id,
                    ]);
                }
                
                // Grouping: Use bagian_name as the key.
                $bimbingan_grouped = $all_bimbingan->groupBy('bagian_name');
                
                // Sort groups by the date of their first item
                $bimbingan_grouped = $bimbingan_grouped->sortBy(function ($group) {
                        return $group->min('tanggal');
                });
            @endphp
            
            @forelse ($bimbingan_grouped as $bagian_name => $items)
                @php
                    // Sort items within the group by date
                    $sorted_items = $items->sortBy('tanggal');
                    $rowspan = $sorted_items->count();
                @endphp
                
                @foreach ($sorted_items as $bimbingan)
                    <tr>
                        @if ($loop->first)
                            <td class="text-center" rowspan="{{ $rowspan }}">{{ $no++ }}</td>
                            <td rowspan="{{ $rowspan }}" style="padding-left: 8px;"><b>{{ $bagian_name }}</b></td>
                        @endif
                        
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($bimbingan['tanggal'])->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            {{-- Bimbingan Online: Catatan Mahasiswa dan Dosen --}}
                            @if ($bimbingan['type'] == 'online')
                                {{-- Catatan Mahasiswa --}}
                                @if(!empty($bimbingan['catatan_mahasiswa']))
                                    <div style="margin-bottom: 6px;">
                                        <strong>Mahasiswa:</strong><br>
                                        {{ strip_tags($bimbingan['catatan_mahasiswa']) }}
                                    </div>
                                @endif

                                {{-- Catatan Dosen --}}
                                @if (count($bimbingan['catatan_dosen']) > 0)
                                    @if(!empty($bimbingan['catatan_mahasiswa']))
                                        <hr style="margin: 4px 0; border: 0; border-top: 1px dashed #ccc;">
                                    @endif
                                    <strong>Dosen:</strong>
                                    <ul>
                                        @foreach ($bimbingan['catatan_dosen'] as $revisi)
                                            <li>{{ strip_tags($revisi->catatan) }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                {{-- Bimbingan Manual: Catatan Pembimbing dan Prodi/Admin --}}
                                {{-- Catatan Pembimbing --}}
                                @if(!empty($bimbingan['catatan_mahasiswa']))
                                    <div style="margin-bottom: 6px;">
                                        <strong>Pembimbing:</strong><br>
                                        {{ strip_tags($bimbingan['catatan_mahasiswa']) }}
                                    </div>
                                @endif

                                {{-- Catatan Prodi/Admin --}}
                                @if (!empty($bimbingan['catatan_dosen']))
                                    @if(!empty($bimbingan['catatan_mahasiswa']))
                                        <hr style="margin: 4px 0; border: 0; border-top: 1px dashed #ccc;">
                                    @endif
                                    <strong>Prodi/Admin:</strong><br>
                                    {{ strip_tags($bimbingan['catatan_dosen']) }}
                                @endif
                            @endif

                            {{-- Fallback jika kosong --}}
                            @if(empty($bimbingan['catatan_mahasiswa']) && 
                                (($bimbingan['type'] == 'online' && count($bimbingan['catatan_dosen']) == 0) || 
                                    ($bimbingan['type'] == 'manual' && empty($bimbingan['catatan_dosen']))))
                                <i>Tidak ada catatan</i>
                            @endif
                        </td>
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
