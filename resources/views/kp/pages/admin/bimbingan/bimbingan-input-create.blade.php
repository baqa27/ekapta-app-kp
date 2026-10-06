@extends('kp.layouts.dashboard')

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
                            <a href="{{ route($route) }}" class="btn btn-secondary btn-sm shadow">
                                <i class="bi bi-chevron-left"></i> Kembali
                            </a>
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
                            <form action="{{ route($storeRoute ?? 'kp.bimbingan.admin.input.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="dosen_id" value="{{ $dosen->id }}">
                                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                                {{--<div class="mt-3">
                                    <label>Lembar Bimbingan (Format: pdf, maksimal 5MB)</label>
                                    <input type="file" name="lampiran"
                                        class="form-control @error('lampiran')
                                    is-invalid
                                    @enderror"
                                        accept=".pdf">
                                    @if ($dosen_mahasiswa->lampiran)
                                        <a href="{{ asset($dosen_mahasiswa->lampiran) }}" target="_blank"><i
                                                class="fas fa-paperclip ml-1"></i> Lampiran Lembar Bimbingan</a>
                                    @endif
                                    @error('lampiran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>--}}
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
                                            <div class="flex-grow-1">
                                                <b
                                                    class="text-{{ $bimbingan->tanggal_acc ? 'success' : 'secondary' }}">{{ $bimbingan->bagian->bagian }}</b>
                                                @if ($bimbingan->lampiran)
                                                    <br> <a href="{{ asset($bimbingan->lampiran) }}" target="_blank"><i
                                                            class="fas fa-paperclip ml-1"></i> Lampiran</a>
                                                @else
                                                    <br><span class="text-secondary">Belum Upload File Bimbingan</span>
                                                @endif

                                                @if ($bimbingan->lampiran_acc && $bimbingan->status == 'review')
                                                    | <a href="{{ asset($bimbingan->lampiran_acc) }}" target="_blank"><i
                                                            class="fas fa-paperclip ml-1"></i> Lembar Acc Bimbingan</a>
                                                     | Tanggal acc : <span class="text-muted">{{ $bimbingan->tanggal_manual_acc }}</span>
                                                     <a href="{{ route('bimbingan.acc.submit.manual', $bimbingan->id) }}" class="btn btn-success btn-sm" onclick="return confirm('Yakin ingin acc?')"><i class="fas fa-check"></i> Acc Bimbingan</a>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#modal-tolak"
                                                        onclick="setTolakAction('{{ route('bimbingan.reject.submit.manual', $bimbingan->id) }}')">
                                                        <i class="bi bi-x-circle mr-2"></i> Tolak Bimbingan
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="flex-shrink-0">
                                                <input type="date" name="dates[]" class="form-control"
                                                    @if ($bimbingan->status == null) disabled @endif>
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

    <div class="modal fade" id="modal-tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                {{-- form akan diisi action-nya dengan JS --}}
                <form id="form-tolak" method="POST">
                    @method('put')
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tolak Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setTolakAction(action) {
            const form = document.getElementById('form-tolak');
            form.action = action;
        }
    </script>
@endsection
