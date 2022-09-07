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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
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
                            <form action="{{ route('pendaftaran.update') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $pendaftaran->id }}">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->prodi }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Utama (1) Tugas Akhir <br>
                                        <small>Lembar Bimbingan Pembimbing Utama Bisa didownload disini : <a
                                                href="https://drive.google.com/file/d/1Velgma6Skdufta177kz6Z4DEFVAI50F9/view?usp=sharing"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <input type="text" class="form-control" value="{{ $dosen_utama->nama }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Pendamping (2) Tugas Akhir <br>
                                        <small>Lembar Bimbingan Pembimbing Pendamping Bisa didownload disini :
                                            <a href="https://drive.google.com/file/d/1UWl33hEeZMSDpLTmxlXXKNYAlbwLJG3T/view?usp=sharing"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <input type="text" class="form-control" value="{{ $dosen_pendamping->nama }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="{{ $pendaftaran->judul }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="email" class="form-control" value="{{ $mahasiswa->email }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">No. HP <br> <small>Yang Terdaftar di
                                            WhatsApp
                                            dan Telegram</small> </label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->hp }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Semester</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->semester }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Acc. Kaprodi <br>
                                        <small>Download disini : <a
                                                href="{{ route('cetak.lembar.persetujuan.mahasiswa') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_1') is-invalid @enderror"
                                                name="lampiran_1">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_1')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="pd-2 rounded bg-light mt-1">
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ asset($pendaftaran->lampiran_1) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($pendaftaran->lampiran_1, 21) }}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bukti Lembar Pernyataan Keaslian Hasil Tugas
                                        Akhir
                                        <br>
                                        <small>Download disini : <a href="{{ route('cetak.lembar.pernyataan.keaslian') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_2') is-invalid @enderror"
                                                name="lampiran_2">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_2')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="pd-2 rounded bg-light mt-1">
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ asset($pendaftaran->lampiran_2) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($pendaftaran->lampiran_2, 21) }}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Transkrip Nilai <br>
                                        <small>Jika SKS lulus lebih dari 120 maka pengajuan Surat Tugas
                                            Pembimbing akan diproses namun jika belum memenuhi 120 sks lulus
                                            maka pengajuan akan dibatalkan.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_3') is-invalid @enderror"
                                                name="lampiran_3">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_3')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="pd-2 rounded bg-light mt-1">
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ asset($pendaftaran->lampiran_3) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($pendaftaran->lampiran_3, 21) }}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pengumpulan KP <br>
                                        <small>bukti diminta di perpus FASTIKOM</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_4') is-invalid @enderror"
                                                name="lampiran_4">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_4')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="pd-2 rounded bg-light mt-1">
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ asset($pendaftaran->lampiran_4) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($pendaftaran->lampiran_4, 21) }}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Tugas Akhir <br>
                                        <small>Pembayaran Tugas Akhir (TA) ke Juru bayar FASTIKOM. ( Mas
                                            Harri)</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror"
                                                name="lampiran_5">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_5')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="pd-2 rounded bg-light mt-1">
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ asset($pendaftaran->lampiran_5) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($pendaftaran->lampiran_5, 21) }}</a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nomor Pembayaran <br> <small>(PBXXXX) yang
                                            tertera pada Bukti Bayar FASTIKOM (BUKAN NOMOR TRANSFER DARI
                                            BANK)</small></label>
                                    <input type="text"
                                        class="form-control @error('nomor_pembayaran') is-invalid @enderror"
                                        placeholder="Masukkan Nomor Pembayaran.."
                                        value="{{ $pendaftaran->nomor_pembayaran }}" name="nomor_pembayaran" required>
                                    @error('nomor_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran <br>
                                        <small>Tanggal Pembayaran Sebelumnya :
                                            <b>{{ $pendaftaran->tanggal_pembayaran }}</b></small>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                        name="tanggal_pembayaran" value="{{ $pendaftaran->tanggal_pembayaran }}">
                                    @error('tanggal_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">BIAYA TUGAS AKHIR (TA) / SKRIPSI
                                    </label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="750000"
                                            @if ($pendaftaran->biaya == 750000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Sarjana Kelas A
                                            (Reguler) : Rp. 750.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="375000"
                                            @if ($pendaftaran->biaya == 375000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Sarjana
                                            Kelas A (Reguler) : Rp. 375.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="850000"
                                            @if ($pendaftaran->biaya == 850000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Sarjana Kelas B
                                            (Ekstensi) : Rp. 850.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="425000"
                                            @if ($pendaftaran->biaya == 425000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Sarjana
                                            Kelas B (Ekstensi) : Rp. 425.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="650000"
                                            @if ($pendaftaran->biaya == 650000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Diploma Kelas A
                                            (Reguler) : Rp. 650.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="325000"
                                            @if ($pendaftaran->biaya == 325000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Diploma
                                            Kelas A (Reguler) : Rp. 325.000,-</label>
                                    </div>
                                    @error('biaya')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
