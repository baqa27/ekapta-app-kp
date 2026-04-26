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
                        <li class="breadcrumb-item"><a href="#">Bimbingan TA</a></li>
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
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">{{ $title }}</h3>
                            <h3 class="card-title flex-shrink-0">{{ $dosen->nama . ', ' . $dosen->gelar }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($bimbingan->catatan)
                                <div class="alert alert-warning mb-2">
                                    <i class="fas fa-info-circle"></i> Catatan revisi: {{ $bimbingan->catatan }}
                                </div>
                            @endif
                            
                            @if (!$bimbingan->lampiran_acc)
                                <form action="{{ route('bimbingan.submit.acc.manual.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $bimbingan->id }}">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Bagian Bimbingan</label>
                                        <input type="text" class="form-control" value="{{ $bimbingan->bagian->bagian }}"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Lembar Acc Bimbingan(Format: PDF, PNG, JPG, JPEG| Max:
                                            5MB)</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('lampiran_acc')is-invalid @enderror"
                                                    name="lampiran_acc" accept=".pdf, .jpeg,.png,.jpg" required>
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Dokumen</span>
                                            </div>
                                        </div>
                                        @error('lampiran_acc')
                                            <small class="text-danger"
                                                style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div>
                                        <label>Tanggal Acc Bimbingan</label>
                                        <input type="date" name="tanggal_manual_acc" class="form-control" required>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Sudah input lembar acc bimbingan. Silahkan tunggu
                                    validasi dari prodi.
                                </div>

                                <a href="{{ route('bimbingan.mahasiswa') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                            @endif
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
