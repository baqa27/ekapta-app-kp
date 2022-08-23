@extends('layouts.dashboardMahasiswa')

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
                    <li class="breadcrumb-item"><a href="#">Bimbingan TA</a></li>
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
                            @if ($bimbingan->status == 'review')
                            bg-secondary
                            @elseif ($bimbingan->status == 'revisi')
                            bg-warning
                            @elseif ($bimbingan->status == 'diterima')
                            bg-success
                            @elseif ($bimbingan->status == 'ditolak')
                            bg-danger
                            @endif
                            ">
                            {{ $bimbingan->status }}
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title"><strong>Bagian </strong>{{ $bimbingan->bagian->bagian }}</h3>
                    </div>
                    <div class="card-body">
                        <p><b>Keterangan</b></p>
                        {!! nl2br($bimbingan->keterangan) !!}
                        <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                            {{ date('d M y H:m', strtotime($bimbingan->tanggal_bimbingan)) }}
                        </div>
                        @if ($bimbingan->tanggal_acc)
                        <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                            {{ date('d M y H:m', strtotime($bimbingan->tanggal_acc)) }}
                        </div>
                        @endif
                        <hr>
                        <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($bimbingan->lampiran) }}" class="ml-3"
                                target="_blank"><i class="fas fa-paperclip"></i> Lampiran</a></p>
                    </div>
                    <!-- /.card-body -->
                </div>

                {{-- Revisi --}}
                <div class="card card-primary card-outline mt-2">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Revisi</strong>
                            <span class="badge bg-danger rounded-pill">
                                {{ count($bimbingan->revisis) }}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">

                        @foreach ($revisis as $revisi)
                        <div class="card bg-light">
                            <div class="card-header">
                                <span class="mr-5">Direview oleh
                                    <b>{{ $revisi->dosen->nama.', '.$revisi->dosen->gelar }}</b>
                                </span>
                                <div class="float-right">
                                    <i class="fas fa-calendar mr-2"></i> {{ $revisi->created_at->format('y M d H:m') }}
                                </div>
                            </div>
                            <div class="card-body">
                                {!! nl2br($revisi->catatan) !!}
                            </div>
                            <div class="card-footer">
                                Lampiran :
                                @if ($revisi->lampiran)
                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                        class="fas fa-paperclip"></i> Lampiran</a>
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