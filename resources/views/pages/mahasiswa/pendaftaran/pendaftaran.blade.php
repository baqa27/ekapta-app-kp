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
                    <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">

        <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
            {{ $title }}</a>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Pendaftaran Anda</h3>
                    </div>
                    <div class="card-body">

                        Dosen Pembimbing (1) : <strong>{{ $dosen_utama->nama.', '.$dosen_utama->gelar }}</strong> <br>
                        Dosen Pembimbing (2) : <strong>{{ $dosen_pendamping->nama.', '.$dosen_pendamping->gelar
                            }}</strong>

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pendaftaran</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Acc</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no=1;
                                @endphp
                                @foreach ($pendaftarans as $pendaftaran)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <a href="{{ url('pendaftaran/detail/'.$pendaftaran->id) }}">{{
                                            $pendaftaran->judul }}</a>
                                    </td>
                                    <td>{{ $pendaftaran->created_at->format('y M d H:m') }}</td>
                                    <td>{{ $pendaftaran->tanggal_acc }}</td>
                                    <td>
                                        @if ($pendaftaran->status =='diterima')
                                        <span class="badge bg-success">Diterima</span>

                                        @elseif ($pendaftaran->status =='revisi')
                                        <span class="badge bg-warning">Revisi</span>

                                        @elseif ($pendaftaran->status =='review')
                                        <span class="badge bg-secondary">Review</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pendaftaran->status =='diterima')
                                        <a href="" class="btn btn-success btn-sm"><i class="fas fa-download mr-1"></i>
                                            Surat Tugas Bimbingan TA</a>

                                        @elseif ($pendaftaran->status =='revisi')
                                        <a href="" class="btn btn-primary btn-sm"><i class="fas fa-pen mr-1"></i>
                                            Edit</a>

                                        @elseif ($pendaftaran->status =='review')
                                        <a href="{{ url('pendaftaran/detail/'.$pendaftaran->id) }}"
                                            class="btn btn-primary btn-sm"><i
                                                class="fas fa-info-circle mr-1"></i>Detail</a>

                                        {{-- <form action="{{ route('pendaftaran.delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                                            <button class="btn btn-danger btn-sm shadow" type="submit"
                                                onclick="confirmDelete()">
                                                <i class="fas fa-trash mr-1"
                                                    onclick="confirmDelete()"></i>Hapus</button>
                                        </form> --}}

                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Pendaftaran</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Acc</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

@endsection