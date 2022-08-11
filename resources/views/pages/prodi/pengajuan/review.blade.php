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
                    <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
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
                        <div class="ribbon 
                            @if ($pengajuan->status == 'review')
                            bg-secondary
                            @elseif ($pengajuan->status == 'revisi')
                            bg-warning
                            @elseif ($pengajuan->status == 'diterima')
                            bg-success
                            @elseif ($pengajuan->status == 'ditolak')
                            bg-danger
                            @endif
                            ">
                            {{ $pengajuan->status }}
                        </div>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td><b class="mr-3">Nim</b></td>
                                <td>:</td>
                                <td>{{ $pengajuan->nim }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Nama</b></td>
                                <td>:</td>
                                <td>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->nim)->nama }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Prodi</b></td>
                                <td>:</td>
                                <td>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->nim)->prodi }}</td>
                            </tr>
                            <tr>
                                <td><b class="mr-3">Judul</b></td>
                                <td>:</td>
                                <td>{{ $pengajuan->judul }}</td>
                            </tr>

                        </table>
                        <hr>
                        <p><b>Deskripsi</b></p>
                        {!! nl2br($pengajuan->deskripsi) !!}
                        <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i> {{
                            $pengajuan->created_at->format('d M y H:m') }}
                        </div>
                        @if ($pengajuan->tanggal_acc)
                        <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                            {{ date('d M y H:m', strtotime($pengajuan->tanggal_acc)) }}
                        </div>
                        @endif
                        <hr>
                        <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($pengajuan->lampiran) }}" class="ml-3"
                                target="_blank"><i class="fas fa-paperclip"></i> Lampiran.pdf</a></p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex">
                            @if ($pengajuan->status =='review')
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-revisi">
                                <i class="bi bi-pencil-square mr-2"></i> Revisi Pengajuan
                            </button>

                            <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                data-target="#modal-acc">
                                <i class="fas fa-check mr-2"></i> Acc Pengajuan
                            </button>

                            <button type="button" class="btn btn-danger mr-2" data-toggle="modal"
                                data-target="#modal-tolak">
                                <i class="fas fa-x mr-2"></i> Tolak Pengajuan
                            </button>
                            @elseif($pengajuan->status == 'diterima')
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-edit">
                                <i class="bi bi-pencil-square mr-2"></i> Edit Dosen Pendamping
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Revisi --}}
                <div class="card card-primary card-outline mt-2">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Revisi</strong>
                            <span class="badge bg-danger rounded-pill">
                                {{ count($pengajuan->revisis) }}
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach ($revisis as $revisi)
                        <div class="card bg-light">
                            <div class="card-header"><i class="fas fa-calendar mr-2"></i> {{
                                $revisi->created_at->format('d M
                                y H:m') }}
                                <div class="float-right">
                                    <form action="{{ route('pengajuan.revisi.delete') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $revisi->id }}">
                                        <button class="btn btn-danger btn-sm float-right" type="submit"
                                            onclick="confirmDelete()">
                                            <i class="fas fa-trash" onclick="confirmDelete()"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                {!! nl2br($revisi->catatan) !!}
                            </div>
                            <div class="card-footer">
                                Lampiran :
                                @if ($revisi->lampiran)
                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                        class="fas fa-paperclip"></i> Lampiran.pdf</a>
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

<!-- Modal Acc -->
<div class="modal fade" id="modal-acc">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.acc') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                <input type="hidden" name="nim" value="{{ $pengajuan->nim }}">
                <div class="modal-header">
                    <h4 class="modal-title">Acc Pengajuan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="form-label">Dosen Pembimbing Utama</label>
                        <select class="form-control" name="dosen_utama" required>
                            <option value="">Pilih</option>
                            @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama.'.'.$dosen->gelar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Dosen Pembimbing Pendamping</label>
                        <select class="form-control" name="dosen_pendamping" required>
                            <option value="">Pilih</option>
                            @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama.'.'.$dosen->gelar }}</option>
                            @endforeach
                        </select>
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

<!-- Modal Revisi -->
<div class="modal fade" id="modal-revisi">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.revisi') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">Revisi Pengajuan</h4>
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
                                <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
                                    name="lampiran">
                                <label class="custom-file-label" for="exampleInputFile">Choose
                                    file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Dokumen</span>
                            </div>
                        </div>
                        @error('lampiran')
                        <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message
                            }}</small>
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

<!-- Modal Tolak -->
<div class="modal fade" id="modal-tolak">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.tolak') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                <div class="modal-header">
                    <h4 class="modal-title">Tolak Pengajuan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Lampiran</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('lampiran') is-invalid @enderror"
                                    name="lampiran">
                                <label class="custom-file-label" for="exampleInputFile">Choose
                                    file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Dokumen</span>
                            </div>
                        </div>
                        @error('lampiran')
                        <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message
                            }}</small>
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

<!-- Modal Edit -->
@if ($pengajuan->status =='diterima')
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengajuan.acc') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                <input type="hidden" name="nim" value="{{ $pengajuan->nim }}">
                <div class="modal-header">
                    <h4 class="modal-title">Acc Pengajuan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="form-label">Dosen Pembimbing Utama</label>
                        <select class="form-control" name="dosen_utama" required>
                            <option value="">Pilih</option>
                            @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @if ($dosen_utama) {{ $dosen_utama->id == $dosen->id ?
                                'selected' : '' }}
                                @endif >{{ $dosen->nama.'.'.$dosen->gelar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Dosen Pembimbing Pendamping</label>
                        <select class="form-control" name="dosen_pendamping" required>
                            <option value="">Pilih</option>
                            @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @if ($dosen_pendamping) {{ $dosen_pendamping->id ==
                                $dosen->id ? 'selected' : '' }}
                                @endif >{{ $dosen->nama.'.'.$dosen->gelar }}</option>
                            @endforeach
                        </select>
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