@extends('layouts.dashboardFotokopi')

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
            <div class="mb-3 d-flex">
                <div class="flex-shrink-1">
                    <a href="{{ route('jilid.index') }}" class="btn btn-secondary float-end"><i
                            class="bi bi-arrow-left ml-2"></i> Kembali</a>
                </div>
                <h4 class="flex-grow-0"></h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-3 rounded border">
                                <table>
                                    <tr>
                                        <td>NIM/NAMA MAHASISWA</td>
                                        <td>: <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>: <b>{{ $prodi ? $prodi->namaprodi : '' }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="mt-3">
                                @foreach ($mahasiswa->bimbingans()->where('pembimbing', 'utama')->get() as $bimbingan)
                                    <a href="{{ asset($bimbingan->lampiran) }}" class="btn btn-primary mb-3"
                                        target="_blank"><i class="fas fa-download"></i> {{ $bimbingan->bagian->bagian }}</a>
                                @endforeach
                            </div>
                            <form action="{{ route('jilid.acc', $jilid->id) }}" method="post">
                                @method('put')
                                @csrf
                                <div class="mt-4">
                                    <label>JUMLAH PEMBAYARAN</label>
                                    <input type="number" name="total_pembayaran" class="form-control" placeholder="Nominal pembayaran penjilidan" value="{{ $jilid->total_pembayaran }}" required>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> SIMPAN</button>
                                </div>
                            </form>
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
