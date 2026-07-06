<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
        <style>
            * {
                font-size: 12pt;
                margin: 46px 0px 0px 0px;
            }

            .f-14 {
                font-size: 14pt;
            }

            .center {
                margin-left: auto;
                margin-right: auto;
            }

            .image {
                margin-top: 0px;
            }
        </style>
    </head>

    <body>
        <center>
            <b class="f-14">{{ $title }}</b>
            <br><br><br>
            <b class="f-14">LAPORAN KERJA Praktek</b>
            <br><br><br>
            <div style="margin: 0px 50px 0px 50px;">
                <b style="text-transform: uppercase;">{{ $pengajuan->judul }}</b>
            </div>
            <br><br><br><br>
            <span>Disusun Oleh:</span>
            <br>
            <b style="text-transform: uppercase;"><u>{{ $mahasiswa->nama }}</u></b>
            <br>
            <b>{{ $mahasiswa->nim }}</b>
            <br><br><br><br><br>
            <span>Telah disetujui untuk dapat diujikan pada Jilid Kerja Praktek</span>
            <br><br><br><br>
            <span>Wonosobo, {{ $date }}</span>
            <br><br><br>
            <table class="center">
                @if ($type == 1)
                    <tr>
                        <td style="text-align:center;">
                            <span>Pembimbing {{ $dosen_pendamping ? 'Utama' : '' }}</span>
                            <br>
                            @if($ttd_dosen_utama)
                                <img src="{{ $ttd_dosen_utama }}" class="image" height="80">
                            @else
                                <br><br><br><br>
                            @endif
                            <br>
                            <span>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</span>
                            <br>
                            <b>NIDN. {{ $dosen_utama->nidn }}</b>
                        </td>
                        @if($dosen_pendamping)
                        <td width="50"></td>
                        <td style="text-align:center;">
                            <span>Pembimbing Pendamping</span>
                            <br>
                            @if(isset($ttd_dosen_pendamping) && $ttd_dosen_pendamping)
                                <img src="{{ $ttd_dosen_pendamping }}" class="image" height="80">
                            @else
                                <br><br><br><br>
                            @endif
                            <br>
                            <span>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</span>
                            <br>
                            <b>NIDN. {{ $dosen_pendamping->nidn }}</b>
                        </td>
                        @endif
                    </tr>
                @else
                    <tr>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($dosens as $dosen)
                            @php
                                $no++;
                            @endphp
                            <td style="text-align:center;">
                                <span>Penguji
                                    @if ($no == 1)
                                        I
                                    @elseif ($no == 2)
                                        II
                                    @elseif ($no == 3)
                                        III
                                    @endif
                                </span>
                                <br>
                                @if($dosen->ttd)
                                    <img src="{{ \App\Helpers\AppHelper::instance()->convertStorageImage($dosen->ttd) }}" class="image" height="80">
                                @else
                                    <br><br><br><br>
                                @endif
                                <br>
                                <span>{{ $dosen->nama . ', ' . $dosen->gelar }}</span>
                                <br>
                                <b>NIDN. {{ $dosen->nidn }}</b>
                            </td>
                        @endforeach
                    </tr>
                @endif
            </table>
        </center>
    </body>

    </html>




