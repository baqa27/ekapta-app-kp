@extends('layouts.dashboardMahasiswa')

@section('content')

@include('partials.setLocaleTime')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengajuan Tugas Akhir</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
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

        @if (count($pengajuanIsAcc) == 0)
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i> Buat
            Pengajuan TA</a>
        @else
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Selamat! Pengajuan tugas akhir anda sudah di Acc oleh Prodi, silahkan lakukan <b><a
                    href="{{ route('pendaftaran.create') }}">Pendaftaran Tugas
                    Akhir.</a></b>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Pengajuan Anda</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal ACC</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($pengajuans as $pengajuan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><a href="{{ url('/pengajuan/detail/'.$pengajuan->id) }}">{{ $pengajuan->judul
                                            }}</a></td>
                                    <td>
                                        {{-- {{ $pengajuan->created_at->isoFormat('dddd, D MMMM Y') }} --}}
                                        {{ $pengajuan->created_at->format('d M Y H:m') }}
                                    </td>
                                    <td>
                                        @if ($pengajuan->tanggal_acc != null)
                                        {{ date('d M y H:m', strtotime($pengajuan->tanggal_acc)); }}
                                        {{-- {{\Carbon\Carbon::parse($pengajuan->tanggal_acc)->formatLocalized('%A, %d
                                        %B
                                        %Y')}} --}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pengajuan->status == 'review')
                                        <span class="badge bg-secondary">Review</span>
                                        @elseif ($pengajuan->status == 'revisi')
                                        <span class="badge bg-warning">Revisi</span>
                                        @elseif ($pengajuan->status == 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                        @elseif ($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pengajuan->status == 'review')
                                        <div class="d-flex">
                                            <a href="{{ url('/pengajuan/detail/'.$pengajuan->id) }}"
                                                class="btn btn-primary btn-sm shadow mr-2">
                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                            </a>
                                            @if (count($pengajuan->revisis) == 0)
                                            <form action="{{ route('pengajuan.delete')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                                                <button class="btn btn-danger btn-sm shadow" type="submit"
                                                    onclick="confirmDelete()">
                                                    <i class="fas fa-trash mr-1"
                                                        onclick="confirmDelete()"></i>Hapus</button>
                                            </form>
                                            @endif
                                        </div>

                                        @elseif ($pengajuan->status == 'revisi')
                                        <a href="{{ url('/pengajuan/edit/'.$pengajuan->id) }}"
                                            class="btn btn-primary btn-sm shadow" type="submit"><i
                                                class="fas fa-upload mr-1"></i>Submit</a>

                                        @elseif ($pengajuan->status == 'diterima')
                                        <div class="d-flex">
                                            <a href="{{ url('/pengajuan/detail/'.$pengajuan->id) }}"
                                                class="btn btn-primary btn-sm shadow mr-2">
                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                            </a>
                                            <a href="{{ route('cetak.lembar.persetujuan.mahasiswa') }}"
                                                class="btn btn-success btn-sm shadow" target="_blank">
                                                <i class="bi bi-download"></i> Lembar Persetujuan Pembimbing
                                            </a>
                                        </div>

                                        @elseif ($pengajuan->status == 'ditolak')
                                        <form action="{{ route('pengajuan.delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                                            <button class="btn btn-danger btn-sm shadow" type="submit"
                                                onclick="confirmDelete()">
                                                <i class="fas fa-trash mr-1"
                                                    onclick="confirmDelete()"></i>Hapus</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal ACC</th>
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