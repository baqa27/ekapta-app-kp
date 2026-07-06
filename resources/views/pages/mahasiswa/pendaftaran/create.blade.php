@extends('layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
                        <li class="breadcrumb-item active">Form Pendaftaran TA</li>
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
                            <h3 class="card-title">Form Pendaftaran Tugas Akhir</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $selectedBiaya = old('biaya');
                            @endphp
                            <form action="{{ route('pendaftaran.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->nim }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->nama }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->prodi }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Utama (1) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ $dosen_utama ? $dosen_utama->nama . ', ' . $dosen_utama->gelar : 'Belum ditentukan' }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Pendamping (2) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ $dosen_pendamping ? $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar : 'Belum ditentukan' }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan->judul }}" disabled>
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
                                                name="lampiran_1" required>
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
                                                name="lampiran_2" required>
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
                                                name="lampiran_3" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pengumpulan KP <br>
                                        <small>bukti diminta di perpus FASTIKOM</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_4') is-invalid @enderror"
                                                name="lampiran_4" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Tugas Akhir <br>
                                        <small>Pembayaran melalui SIMA UNSIQ.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror"
                                                name="lampiran_5" required>
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
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                        name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran') }}"
                                        placeholder="Tanggal Pembayaran.." required>
                                    @error('tanggal_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">BIAYA TUGAS AKHIR (TA) / SKRIPSI
                                    </label>
                                    @foreach ($biayaOptions as $biayaOption)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('biaya') is-invalid @enderror"
                                                type="radio" name="biaya" value="{{ $biayaOption['value'] }}"
                                                @checked((string) $selectedBiaya === (string) $biayaOption['value'])>
                                            <label class="form-check-label" style="top: -1px; position:relative;">
                                                {{ $biayaOption['label'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('biaya')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success" id="btn-submit"
                                        onclick="this.disabled=true;this.innerHTML='Memproses...';this.form.submit();">Submit</button>
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
