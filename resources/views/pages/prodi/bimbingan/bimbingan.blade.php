@extends('layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                        </div>
                        <div class="card-body">

                            <span class="badge badge-success"> <i class="fas fa-check-circle mr-1"></i>
                                Diterima/Acc
                            </span>
                            <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                                Review/Belum Di Acc
                            </span>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Bagian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($mahasiswas as $mahasiswa)
                                        @if (count($mahasiswa->bimbingans) != 0)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $mahasiswa->nama }}
                                                    {{ '(' . $mahasiswa->nim . ')' }}
                                                </td>
                                                <td>
                                                    {{ $mahasiswa->prodi }}
                                                </td>
                                                <td>{{ $mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('bimbingan.review.prodi',$mahasiswa->pengajuans()->where('status', 'diterima')->first()->id ) }}" class="btn btn-primary btn-sm"><i class="bi bi-info-circle"></i> Detail Bimbingan</a>
                                                </td>

                                                {{-- <td>
                                                    @php
                                                        $dosen_utama = $mahasiswa
                                                            ->dosens()
                                                            ->where('status', 'utama')
                                                            ->first();
                                                        $dosen_pendamping = $mahasiswa
                                                            ->dosens()
                                                            ->where('status', 'pendamping')
                                                            ->first();
                                                    @endphp
                                                    <div class="mt-2 border p-2 rounded">
                                                        <small>Dosen Pembimbing utama
                                                            <b>{{ $dosen_utama->nama .
                                                                ',
                                                                                                                                                                                                                                        ' .
                                                                $dosen_utama->gelar }}</b>
                                                        </small>
                                                        <br>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'utama')
                                                                @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                    <span class="badge badge-success">
                                                                        <i class="fas fa-check-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 border p-2 rounded">
                                                        <small>Dosen Pembimbing Pendamping
                                                            <b>{{ $dosen_pendamping->nama .
                                                                ',
                                                                                                                                                                                                                                        ' .
                                                                $dosen_pendamping->gelar }}</b>
                                                        </small>
                                                        <br>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'pendamping')
                                                                @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                    <a
                                                                        href="{{ route('bimbingan.review.prodi', $bimbingan->id) }}">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                            {{ $bimbingan->bagian->bagian }}
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td> --}}
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Bagian</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
