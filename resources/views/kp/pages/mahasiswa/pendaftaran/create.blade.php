@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Kerja Praktek</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
                        <li class="breadcrumb-item active">Form Pendaftaran KP</li>
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
                            <h3 class="card-title">Form Pendaftaran Kerja Praktek</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $selectedBiaya = old('biaya');
                            @endphp
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="alert alert-info">
                                Maksimal ukuran tiap file 5 MB. Jika setelah semua dokumen diisi lalu submit gagal,
                                biasanya total ukuran upload terlalu besar, jadi PDF/gambar perlu dikompres dulu.
                            </div>
                            <form action="{{ route('kp.pendaftaran.store') }}" method="post" enctype="multipart/form-data">
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
                                    <label for="exampleInputEmail1">Dosen Pembimbing Kerja Praktek</label>
                                    <input type="text" class="form-control"
                                        value="{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : 'Belum ditentukan' }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Kerja Praktek</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan->judul }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Acc. Kaprodi <br>
                                        <small>Download disini : <a
                                                href="{{ route('kp.cetak.lembar.persetujuan.mahasiswa') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_1') is-invalid @enderror"
                                                name="lampiran_1" accept=".pdf" required>
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
                                    <label for="exampleInputEmail1">Bukti Transkrip Nilai
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_2') is-invalid @enderror"
                                                name="lampiran_2" accept=".pdf" required>
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
                                    <label for="exampleInputFile">Sertifikat KKL</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_3') is-invalid @enderror"
                                                name="lampiran_3" accept=".pdf" required>
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
                                    <label for="exampleInputFile">Bukti Pembayaran Kerja Praktek <br>
                                        <small>Pembayaran Kerja Praktek dilakukan melalui sistem SIMA. Masuk ke menu <b>Prog. Kegiatan Fakultas</b>. Pilih menu <b>Kerja Praktek</b> sesuai jurusan Anda. Lakukan pembayaran sesuai nominal yang tertera. Simpan bukti pembayaran sebagai arsip.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror"
                                                name="lampiran_5" accept=".pdf,.png,.jpg,.jpeg" required>
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
                                    <label for="exampleInputFile">Bukti Diterima Instansi</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_7') is-invalid @enderror"
                                                name="lampiran_7" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_7')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Pendukung <br>
                                        <small>Upload 2 sertifikat peserta seminar KP (dijadikan 1 file PDF)</small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('dokumen_pendukung') is-invalid @enderror"
                                                name="dokumen_pendukung" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('dokumen_pendukung')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Nomor Pembayaran dihidden, database tetap null --}}
                                <input type="hidden" name="nomor_pembayaran" value="">

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
                                    <label for="exampleInputEmail1">BIAYA KERJA PRAKTEK (KP)
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





@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const fileName = this.value.split('\\').pop();
            const label = this.parentElement.querySelector('.custom-file-label');

            if (label) {
                label.classList.add('selected');
                label.textContent = fileName;
            }
        });
    });
});
</script>
@endsection
