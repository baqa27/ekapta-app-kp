@extends('layouts.dashboard')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon 
                            @if ($pengajuan->status == 'review')
                            bg-secondary
                            @elseif ($pengajuan->status == 'revisi')
                            bg-warning
                            @elseif ($pengajuan->status == 'diterima')
                            bg-success
                            @elseif ($pengajuan->status == 'ditolak')
                            bg-danger
                            @endif
                            ">
                            {{ $pengajuan->status }}
                        </div>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td><b class="mr-3">Nim</b></td>
                                <td>:</td>
                                <td>{{ $pengajuan->nim }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Nama</b></td>
                                <td>:</td>
                                <td>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->nim)->nama }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Prodi</b></td>
                                <td>:</td>
                                <td>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->nim)->prodi }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Judul</b></td>
                                <td>:</td>
                                <td>{{ $pengajuan->judul }}</td>
                            </tr>

                        </table>
                        <hr>
                        <p><b>Deskripsi</b></p>
                        {!! nl2br($pengajuan->deskripsi) !!}
                        <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i> {{
                            $pengajuan->created_at->format('d M y H:m') }}
                        </div>
                        @if ($pengajuan->tanggal_acc)
                        <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                            {{ date('d M y H:m', strtotime($pengajuan->tanggal_acc)) }}
                        </div>
                        @endif
                        <hr>
                        <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($pengajuan->lampiran) }}" class="ml-3"
                                target="_blank"><i class="fas fa-paperclip"></i> Lampiran.pdf</a></p>
                    </div>

                </div>

                <div class="card card-primary card-outline mt-2">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Revisi</strong>
                            <span class="badge bg-danger rounded-pill">
                                {{ count($pengajuan->revisis) }}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach ($revisis as $revisi)
                        <div class="card bg-light">
                            <div class="card-header"><i class="fas fa-calendar mr-2"></i> {{
                                $revisi->created_at->format('d M y H:m') }}
                            </div>
                            <div class="card-body">
                                {!! nl2br($revisi->catatan) !!}
                            </div>
                            <div class="card-footer">
                                Lampiran :
                                @if ($revisi->lampiran)
                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                        class="fas fa-paperclip"></i> Lampiran.pdf</a>
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="d-flex justify-content-center mb-3">
                        {{ $revisis->links() }}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<!-- /.content -->


@endsection