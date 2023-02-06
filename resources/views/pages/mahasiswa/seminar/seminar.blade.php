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
                <div class="mb-3 bg-primary rounded p-2">
                    Anda sudah melakukan pendaftaran Seminar TA, silahkan tunggu validasi dari Admin.
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar Proposal Anda</h3>
                        </div>
                        <div class="card-body">

                            <table>
                                <tr>
                                    <td>Dosen Pembimbing (1)</td>
                                    <td>&nbsp:&nbsp</td>
                                    <td><strong>{{ $dosen_utama ? $dosen_utama->nama . ' ,' . $dosen_utama->gelar : '' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dosen Pembimbing (2)</td>
                                    <td>&nbsp:&nbsp</td>
                                    <td><strong>{{ $dosen_pendamping ? $dosen_pendamping->nama . ' ,' . $dosen_pendamping->gelar : '' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dosen Penguji</td>
                                    <td>&nbsp:&nbsp</td>
                                    <td>
                                        <strong></strong>
                                    </td>
                                </tr>
                            </table>

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
                                                    <a href="" class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle mr-1"></i> Detail
                                                    </a>
                                                @elseif ($seminar->is_valid == 1)
                                                    <a href="" class="btn btn-success btn-sm">
                                                        <i class="bi bi-star mr-1"></i> Lihat Review
                                                    </a>
                                                @elseif ($seminar->is_valid == 2)
                                                    <a href="" class="btn btn-primary btn-sm">
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
