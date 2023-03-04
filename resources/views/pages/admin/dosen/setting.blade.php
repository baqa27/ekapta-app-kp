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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Setting Fakultas</h3>
                        </div>
                        <div class="card-body">

                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        NIDN
                                    </div>
                                    <div class="col-md-9">
                                        <span class="mr-3">:</span>
                                        <b>{{ $dosen->nidn }}</b>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        Nama Dosen
                                    </div>
                                    <div class="col-md-9">
                                        <span class="mr-3">:</span>
                                        <b>{{ $dosen->nama }}, {{ $dosen->gelar }}</b>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        Prodi
                                    </div>
                                    <div class="col-md-9">
                                        <span class="mr-3">:</span>
                                        <b>{{ \App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi) != null ? \App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi)->namaprodi : '' }}</b>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary btn-sm mr-2 mb-1" data-toggle="modal"
                                                data-target="#modal-edit">
                                            <i class="bi bi-pencil-square mr-2"></i> TTD Dosen
                                        </button>
                                    </div>
                                    <div class="col-md-9">
                                        <img src="{{ asset($dosen->ttd != null ? $dosen->ttd : 'ekapta/assets/img/not-found.png') }}"
                                             alt="Stempel Fakultas" height="100">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Modal Edit Dosen-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dosen.update', $dosen->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">TTD Dosen </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Edit TTD Dosen</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('ttd')is-invalid @enderror"
                                        name="ttd" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('ttd')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
