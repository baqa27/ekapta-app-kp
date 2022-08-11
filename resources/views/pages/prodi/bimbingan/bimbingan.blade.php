@extends('layouts.dashboard')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
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
                            Diterima
                        </span>
                        <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                            Review
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
                                        {{ '('.$mahasiswa->nim.')' }}
                                    </td>
                                    <td>
                                        {{ $mahasiswa->prodi}}
                                    </td>
                                    <td>{{ \App\Helpers\AppHelper::instance()->getPendaftaran($mahasiswa->nim)->judul }}
                                    </td>
                                    <td>
                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                        @if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->bagian->id))
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            {{$bimbingan->bagian->bagian }}
                                        </span>
                                        @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-circle mr-1"></i>
                                            {{$bimbingan->bagian->bagian }}
                                        </span>
                                        @endif
                                        @endforeach
                                    </td>
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