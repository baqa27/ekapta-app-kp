@extends('kp.layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
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
                            @if ($pendaftaran->status == 'review') bg-secondary
                            @elseif ($pendaftaran->status == 'revisi')
                            bg-warning
                            @elseif ($pendaftaran->status == 'diterima')
                            bg-success @endif
                            ">
                                {{ $pendaftaran->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $mahasiswa->nama ?? $pendaftaran->mahasiswa->nama ?? '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                <b>{{ $mahasiswa->prodi ?? '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tahun Masuk
                                </div>
                                <div class="col-md-7">
                                <b>{{ $mahasiswa->thmasuk ?? '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dosen Pembimbing Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : 'Belum ditentukan' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Status Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->status_pendaftaran_label }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Email
                                </div>
                                <div class="col-md-7">
                                <b>{{ $mahasiswa->email ?? '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    No. HP
                                </div>
                                <div class="col-md-7">
                                <b>{{ $mahasiswa->hp ?? '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Semester
                                </div>
                                <div class="col-md-7">
                                    <b>{{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim)->semester : '' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Acc. Kaprodi
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Transkrip Nilai
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Sertifikat KKL
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_3, 40) }}</a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Pembayaran Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_5, 40) }}</a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Diterima Instansi
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_7) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_7, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            @if($pendaftaran->dokumen_pendukung)
                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Pendukung
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->dokumen_pendukung) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->dokumen_pendukung, 40) }}</a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($pendaftaran->lampiran_8)
                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Lainnya
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($pendaftaran->lampiran_8) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_8, 40) }}</a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Nomor Pembayaran dihidden dari tampilan --}}

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pembayaran)->format('d-m-Y') }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Biaya
                                </div>
                                <div class="col-md-7">
                                    <span class="text-success fs-5">Rp. {{ number_format((float) $pendaftaran->biaya, 0, ',', '.') }},-</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-7">
                                    @if ($pendaftaran->tanggal_acc)
                                        <b>{{ date('d M Y H:m', strtotime($pendaftaran->tanggal_acc)) }}</b>
                                    @endif
                                </div>
                            </div>

                            @if ($pendaftaran->status == 'diterima')
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Surat Tugas Bimbingan
                                    </div>
                                    <div class="col-md-7">

                                        <a href="{{ url('kp/cetak/surat-tugas-bimbingan/' . $pendaftaran->id) }}"
                                            target="_blank"><i class="fas fa-download"></i> Surat tugas Bimbingan KP</a>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="card-footer">
                            <div class="d-flex">
                                @if ($pendaftaran->status == 'review')
                                    <a href="{{ route('kp.pendaftaran.admin') }}" class="btn btn-secondary mr-2">
                                            <i class="bi bi-arrow-left mr-2"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi Pendaftaran
                                    </button>

                                    {{--<div onclick="confirmAcc()">
                                        <form action="{{ route('kp.pendaftaran.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc Pendaftaran
                                            </button>
                                        </form>
                                    </div>--}}

                                    <!-- Modal Confirm Acc -->
                                    <button type="button" class="btn btn-success"
                                        data-toggle="modal" data-target="#modal-confirm-acc">
                                        <i class="fas fa-check mr-2"></i> Acc Pendaftaran
                                    </button>

                                    <div class="modal fade" id="modal-confirm-acc">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('kp.pendaftaran.acc') }}" method="post">
                                                    @csrf

                                                    <input type="hidden" name="id" value="{{ $pendaftaran->id }}">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Acc Pendaftaran Kerja Praktek</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                NIM
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $mahasiswa->nim ?? '-' }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Nama Lengkap
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $mahasiswa->nama ?? '-' }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Prodi
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $mahasiswa->prodi ?? '-' }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="" class="form-label text-danger">Mahasiswa akan tergabung pada bimbingan dengan tahun masuk:</label>
                                                            <input type="text" name="tahun_masuk" value="{{ $mahasiswa->thmasuk ?? '' }}" class="form-control" required>
                                                        </div>
                                                        <br>
                                                        <span class="text-danger">* Jika ingin mengubah tahun masuk bimbingan, maka ubah data tahun masuk mahasiswa!</span>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($pendaftaran->revisis) }}
                                </span>
                            </h3>
                            @if ($pendaftaran->status == 'revisi')
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
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('kp.pendaftaran.revisi.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $revisi->id }}">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>

                                    @if ($revisi->lampiran)
                                    <div class="card-footer">
                                        Lampiran :
                                        @if ($revisi->lampiran)
                                            <a href="{{ storage_url($pendaftaran->lampiran) }}" class="ml-3" target="_blank"><i
                                                    class="fas fa-paperclip"></i>
                                                {{ Str::substr($revisi->lampiran, 40) }}</a>
                                        @endif
                                    </div>
                                    @endif
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
    @if ($pendaftaran->status != 'diterima')
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kp.pendaftaran.revisi') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi Pendaftaran</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="4" placeholder="Tuliskan catatan revisi..." required></textarea>
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




