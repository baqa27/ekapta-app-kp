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

                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-2"></i> Import Data Mahasiswa
                            </button>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Prodi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $mahasiswa->nim }}</td>
                                            <td>{{ $mahasiswa->nama }}</td>
                                            <td>
                                                {{ $mahasiswa->prodi }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Dosen</th>
                                        <th>Prodi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('mahasiswa.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Mahasiswa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File <br>
                                <small>Format file <b>.CSV</b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file">
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">CSV</span>
                                </div>
                            </div>
                            @error('file')
                                <small class="text-danger"
                                    style="position:relative;top:-15px;left:5px">{{ $message }}</small>
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
@endsection
