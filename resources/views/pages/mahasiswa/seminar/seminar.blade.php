@extends('layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seminar Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar TA</a></li>
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
            @if (!$seminar)
                <a href="{{ route('seminar.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Pendaftaran Seminar Proposal</a>
            @else
                @if($seminar->is_valid == 0)
                    <div class="mb-3 bg-primary rounded p-2">
                        Anda sudah melakukan pendaftaran Seminar TA, silahkan tunggu validasi dari Admin.
                    </div>
                @elseif($seminar->is_valid == 1)
                    <div class="mb-3 bg-success rounded p-2">
                        Selamat Seminar TA anda sudah di Acc, silahkan tunggu review dan penilain dari dosen pembimbing dan penguji.
                    </div>
                @elseif($seminar->is_valid == 2)
                    <div class="mb-3 bg-warning rounded p-2">
                        Silahkan revisi pendaftaran Seminar TA anda sesuai instruksi dari admin, kemudian submit ulang!
                    </div>
                @endif
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar Proposal Anda</h3>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    Dosen Pembimbing
                                </div>
                                <div class="col-md-10">
                                    1. <strong>{{ $dosen_utama ? $dosen_utama->nama . ' ,' . $dosen_utama->gelar : '' }}</strong>
                                    <br>
                                    2. <strong>{{ $dosen_pendamping ? $dosen_pendamping->nama . ' ,' . $dosen_pendamping->gelar : '' }}</strong>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-2">
                                    Dosen Penguji
                                </div>
                                <div class="col-md-10">
                                    @if(count($dosens_penguji) != 0)
                                        @php $no = 1; @endphp
                                        @foreach($dosens_penguji as $dosen)
                                            <span>{{ $no++  }}. <b>{{$dosen->dosen->nama}}, {{$dosen->dosen->gelar}}</b></span><br>
                                        @endforeach
                                    @endif
                                </div>
                            </div>


                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($seminar)
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <a href="">{{ $seminar->pengajuan->judul }}</a>
                                            </td>
                                            <td>
                                                @if ($seminar->is_valid == 1)
                                                    <a href="" class="btn btn-primary btn-sm"><i
                                                            class="bi bi-star"></i> Lihat Nilai</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($seminar->is_valid == 0)
                                                    <span class="badge bg-secondary">Review</span>
                                                @elseif ($seminar->is_valid == 1)
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif ($seminar->is_valid == 2)
                                                    <span class="badge bg-warning">Revisi</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($seminar->is_valid == 0)
                                                    <a href="{{ route('seminar.detail', $seminar->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle mr-1"></i> Detail
                                                    </a>
                                                @elseif ($seminar->is_valid == 1)
                                                    <a href="{{ route('seminar.reviews', $seminar->id)  }}" class="btn btn-success btn-sm">
                                                        <i class="bi bi-star mr-1"></i> Lihat Review
                                                    </a>
                                                @elseif ($seminar->is_valid == 2)
                                                    <a href="{{ route('seminar.edit', $seminar->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-upload mr-1"></i> Submit
                                                    </a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Nilai</th>
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
@endsection
