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

            <a href="form-seminar.html" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                Pendaftaran Seminar Proposal</a>

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
                                    <td><strong>{{ $dosen_utama ? $dosen_utama->nama.' ,'. $dosen_utama->gelar : '' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Dosen Pembimbing (2)</td>
                                    <td>&nbsp:&nbsp</td>
                                    <td><strong>{{ $dosen_pendamping ? $dosen_pendamping->nama.' ,'. $dosen_pendamping->gelar : '' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Dosen Penguji</td>
                                    <td>&nbsp:&nbsp</td>
                                    <td><strong>{{ $dosen_penguji ? $dosen_penguji->nama.' ,'. $dosen_penguji->gelar : '' }}</strong></td>
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
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <a href="">Lihat rekap pendaftaran</a>
                                        </td>
                                        <td>
                                            <a href="">Lihat Nilai</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Diterima</span>
                                            <span class="badge bg-warning">Revisi</span>
                                            <span class="badge bg-secondary">Review</span>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-success btn-sm"><i
                                                    class="fas fa-download mr-1"></i>
                                                Berita Acara Ujian Proposal</a>
                                            <a href="" class="btn btn-primary btn-sm"><i class="fas fa-pen mr-1"></i>
                                                Edit</a>
                                            <a href="" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash mr-1"></i>Hapus</a>
                                        </td>
                                    </tr>
                                    <!-- Data kosong -->
                                    <!-- <tr>
                                                <td colspan="6">
                                                    <div class="text-center">
                                                        Belum ada pengajuan TA
                                                    </div>
                                                </td>
                                            </tr> -->
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
