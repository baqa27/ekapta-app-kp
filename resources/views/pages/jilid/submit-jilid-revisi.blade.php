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
                        <li class="breadcrumb-item"><a href="#">Jilid TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                {{ $jilid->catatan }}
                            </div>
                            <form action="{{ route('jilid.update', $jilid->id) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @csrf
                                <div class="form-group">
                                    <label>Link Project <br>
                                    <small>Upload ke google drive, kemudian inputkan link project</small></label>
                                    <input type="url" class="form-control" name="link_project" value="{{ $jilid->link_project }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Laporan Skripsi Format PDF (Digabung Dengan Lembar Pengesahan TTD)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                   name="laporan_pdf" accept=".pdf">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('laporan_pdf')
                                    <small class="text-danger"
                                           style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span>Lampiran sebelumnya : </span>
                                            <a href="{{ asset($jilid->laporan_pdf) }}" class="text-primary"
                                               target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($jilid->laporan_pdf, 21) }}</a>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Laporan Skripsi Format WORD</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                   name="laporan_word" accept=".docx" >
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('laporan_word')
                                    <small class="text-danger"
                                           style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span>Lampiran sebelumnya : </span>
                                            <a href="{{ asset($jilid->laporan_word) }}" class="text-primary"
                                               target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($jilid->laporan_word, 21) }}</a>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Pengesahan (Dengan TTD)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('lembar_pengesahan')is-invalid @enderror"
                                                   name="lembar_pengesahan" accept=".pdf" >
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_pengesahan')
                                    <small class="text-danger"
                                           style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span>Lampiran sebelumnya : </span>
                                            <a href="{{ asset($jilid->lembar_pengesahan) }}" class="text-primary"
                                               target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($jilid->lembar_pengesahan, 21) }}</a>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">Submit</button>
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
