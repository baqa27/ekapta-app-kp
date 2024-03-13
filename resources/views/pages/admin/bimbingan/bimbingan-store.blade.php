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

                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="border p-3 col-md-6">
                                    Mahasiswa: <b> {{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b>
                                </div>
                                <div class="border p-3 col-md-6">
                                    Dosen: <b> {{ $dosen->nama . ', ' . $dosen->gelar }}</b>
                                </div>
                            </div>
                            <form action="{{ route('bimbingan.admin.input.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                                <div class="mt-3">
                                    <label>Lembar Bimbingan (Format: pdf, maksimal 1Mb)</label>
                                    <input type="file" name="lampiran"
                                        class="form-control @error('lampiran')
                                    is-invalid
                                    @enderror"
                                        accept=".pdf" required>
                                        @if ($dosen_mahasiswa->lampiran)
                                        <a href="{{ asset($dosen_mahasiswa->lampiran) }}"
                                            target="_blank"><i class="fas fa-paperclip ml-1"></i> Lampiran Lembar Bimbingan</a>
                                        @endif
                                    @error('lampiran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-3 d-flex">
                                    <b class="flex-grow-1">BAB BIMBINGAN</b>
                                    <div class="flex-shrink-0">
                                        <b>TANGGAL ACC</b>
                                    </div>
                                </div>
                                <div>
                                    @foreach ($bimbingans as $bimbingan)
                                        <input type="hidden" name="ids[]" value="{{ $bimbingan->id }}">
                                        <div class="border p-3 d-flex">
                                            <span class="flex-grow-1">
                                                <b>{{ $bimbingan->bagian->bagian }}</b>
                                                @if ($bimbingan->lampiran)
                                                    <br> <a href="{{ asset($bimbingan->lampiran) }}"
                                                        target="_blank"><i class="fas fa-paperclip ml-1"></i> Lampiran</a>
                                                @endif
                                            </span>
                                            <div class="flex-shrink-0">
                                                <input type="date" name="dates[]" class="form-control" required>
                                                @if ($bimbingan->tanggal_acc)
                                                    <span
                                                        class="text-success">{{ \App\Helpers\AppHelper::parse_date_short($bimbingan->tanggal_acc) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary shadow"><i class="fas fa-save"></i>
                                        Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
