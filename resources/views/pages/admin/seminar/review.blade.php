@extends('layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Semiar TA</a></li>
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
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($seminar->is_valid == 0) bg-secondary
                            @elseif ($seminar->is_valid == 2)
                            bg-warning
                            @elseif ($seminar->is_valid == 1)
                            bg-success @endif
                            ">
                                @if ($seminar->is_valid == 0)
                                review
                                @elseif ($seminar->is_valid == 1)
                                diterima
                                @elseif ($seminar->is_valid == 2)
                                revisi
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $seminar->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $seminar->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Utama (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Pendamping (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester
                                        Terakhir
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <a href="{{ asset($seminar->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_1, 21) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <a href="{{ asset($seminar->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_2, 21) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Berkas File Tugas Akhir Lengkap
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <a href="{{ asset($seminar->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_3, 21) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Lembar Bimbingan TA Yang Telah di Acc
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <a href="{{ asset($seminar->lampiran_4) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_4, 21) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Lembar Persetujuan
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <a href="{{ asset($seminar->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_5, 21) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    <b>{{ $seminar->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>

                            @if ($seminar->tanggal_acc)
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Acc
                                </div>
                                <div class="col-md-7">
                                    <span class="mr-3">:</span>
                                    @if ($seminar->tanggal_acc)
                                    <b>{{ date('d M Y H:m', strtotime($seminar->tanggal_acc)) }}</b>
                                    @endif
                                </div>
                            </div>
                            @endif

                        </div>

                        @if ($seminar->is_valid == 0)
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi seminar
                                    </button>

                                    <div onclick="confirmAcc()">
                                        <form action="{{ route('seminar.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $seminar->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc seminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($seminar->revisis) }}
                                </span>
                            </h3>
                            @if ($seminar->is_valid == 2)
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-plus-square mr-2"></i> Tambahkan Revisi
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">

                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:m') }}
                                        {{-- <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('seminar.revisi.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $revisi->id }}">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div> --}}
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    <div class="card-footer">
                                        Lampiran :
                                        @if ($revisi->lampiran)
                                            <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                                    class="fas fa-paperclip"></i>
                                                {{ Str::substr($revisi->lampiran, 16) }}</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <!-- Modal Revisi -->
    @if ($seminar->is_valid == 0 || $seminar->is_valid == 2)
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('seminar.revisi') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $seminar->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi seminar</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea id="summernote" name="catatan" required>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran')is-invalid @enderror"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran')
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
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
    @endif
@endsection
