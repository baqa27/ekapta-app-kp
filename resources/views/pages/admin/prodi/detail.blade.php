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
                            <h3 class="card-title">Detail Prodi</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-3">
                                    Nama Prodi
                                </div>
                                <div class="col-md-9">
                                    <span class="mr-3">:</span>
                                    <b>{{ $prodi->namaprodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    Jenjang
                                </div>
                                <div class="col-md-9">
                                    <span class="mr-3">:</span>
                                    <b>{{ $prodi->jenjang }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    Kaprodi
                                </div>
                                <div class="col-md-9">
                                    <span class="mr-3">:</span>
                                    <b>{{ \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi) != null
                                        ? \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->nama .
                                            ',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ' .
                                            \App\Helpers\AppHelper::instance()->getDosen($prodi->kodekaprodi)->gelar
                                        : '' }}</b>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Bagian Bimbingan</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="fas fa-plus mr-2"></i> Buat Bagian Bimbingan
                            </button>

                            <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="fas fa-upload mr-2"></i> Import Bagian Bimbingan
                            </button>

                            <ul class="list-group mt-3">
                                @php
                                    $no = 1;
                                @endphp

                                @foreach ($prodi->bagians as $bagian)
                                    <li
                                        class="list-group-item text-secondary {{ count($bagian->bimbingans) != 0 ? 'border-success' : '' }}">
                                        <span
                                            class="badge {{ count($bagian->bimbingans) != 0 ? 'badge-success' : 'badge-secondary' }} mr-2">{{ $no++ }}</span>
                                        <span style="position: relative;top:2px;">{{ $bagian->bagian }}</span>

                                        <div class="float-right">
                                            <div class="d-flex">
                                                @if (count($bagian->bimbingans) != 0)
                                                    <div class="badge badge-info mr-2">
                                                        <span style="position: relative;top:5px;">
                                                            Mahasiswa : <b>{{ count($bagian->bimbingans) }}</b>
                                                        </span>
                                                    </div>
                                                @endif
                                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-{{ $bagian->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @if (count($bagian->bimbingans) == 0)
                                                    <form action="{{ route('bagian.delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bagian->id }}">
                                                        <button class="btn btn-danger btn-sm float-right" type="submit"
                                                            onclick="confirmDelete()">
                                                            <i class="fas fa-trash" onclick="confirmDelete()"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Create -->
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bagian.store') }}" method="post">
                    @csrf

                    <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Buat Bagian Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Nama Bagian Bimbingan</label>
                            <input type="text" class="form-control @error('bagian') is-invalid @enderror" name="bagian"
                                placeholder="Nama bagian bimbingan..." required>
                            @error('bagian')
                                <div class="invalid-feedback">{{ $message }}</div>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bagian.import') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="prodi" value="{{ $prodi->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Import Bagian Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File Import<br>
                                <small>Format file : <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file">
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Edit -->
    @foreach ($prodi->bagians as $bagian)
        <div class="modal fade" id="modal-edit-{{ $bagian->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('bagian.update') }}" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $bagian->id }}">

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Bagian Bimbingan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Bagian Bimbingan</label>
                                <input type="text" class="form-control @error('bagian') is-invalid @enderror"
                                    name="bagian" value="{{ $bagian->bagian }}" required>
                                @error('bagian')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
    @endforeach
@endsection
