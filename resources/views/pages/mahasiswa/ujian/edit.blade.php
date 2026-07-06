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
                    <li class="breadcrumb-item"><a href="#">Pendaftaran Ujian Pendadaran TA</a></li>
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
                        <form action="{{ route('ujian.update', $ujian->id) }}" method="post"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label for="exampleInputEmail1">NIM</label>
                                <input type="text" class="form-control" value="{{ $ujian->mahasiswa->nim }}"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $ujian->mahasiswa->nama }}"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Prodi</label>
                                <input type="text" class="form-control" value="{{ $ujian->mahasiswa->prodi }}"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                <input type="text" class="form-control" value="{{ $ujian->pengajuan->judul }}"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Bukti Lunas Pembayaran SPP Sampai Semester
                                    Terakhir</label>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_1) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_1, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Bukti Lunas Pembayaran Tugas Akhir (TA)</label>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_2) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_2, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Ijazah Terakhir Yang Asli</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_3"
                                                @error('lampiran_3') is-invalid @enderror>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_3) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_3, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan KTP / Kartu Keluarga Terbaru</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_4"
                                                @error('lampiran_4') is-invalid @enderror>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_4) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_4, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat TOEFL</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_5"
                                                @error('lampiran_5') is-invalid @enderror>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_5) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_5, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat Tahfidz</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_6"
                                                @error('lampiran_6') is-invalid @enderror @if (!$ujian->lampiran_6) required @endif>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran_6')
                                <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                                @if ($ujian->lampiran_6)
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($ujian->lampiran_6) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($ujian->lampiran_6, 21) }}</a>
                                        </small>
                                    </div>
                                @else
                                    <small class="text-muted">Belum ada sertifikat tahfidz yang diunggah.</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Syahadah Tahfidz 30 Juz (Jika Ada) <br>
                                    <small class="text-muted">Opsional</small></label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_syahadah"
                                                @error('lampiran_syahadah') is-invalid @enderror>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran_syahadah')
                                <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                                @if ($ujian->lampiran_syahadah)
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($ujian->lampiran_syahadah) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($ujian->lampiran_syahadah, 21) }}</a>
                                        </small>
                                    </div>
                                @else
                                    <small class="text-muted">Belum ada syahadah yang diunggah.</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat Komputer</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_7"
                                                @error('lampiran_7') is-invalid @enderror>
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_7) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_7, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_8"
                                                @error('lampiran_8') is-invalid @enderror>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran_8')
                                <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_8) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_8, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Laporan Tugas Akhir (Format: .pdf, max 5Mb )</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_laporan"
                                                @error('lampiran_laporan') is-invalid @enderror accept=".pdf">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran_laporan')
                                <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="{{ storage_url($ujian->lampiran_laporan) }}" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            {{ Str::substr($ujian->lampiran_laporan, 21) }}</a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">File Artikel (Format: .pdf/.doc/.docx, max 5Mb)</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="artikel"
                                                @error('artikel') is-invalid @enderror accept=".pdf,.doc,.docx"
                                                @if (!$ujian->artikel) required @endif>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('artikel')
                                <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                                @if ($ujian->artikel)
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Artikel sebelumnya : </span>
                                            <a href="{{ storage_url($ujian->artikel) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($ujian->artikel, 21) }}</a>
                                        </small>
                                    </div>
                                @else
                                    <small class="text-danger">File artikel wajib diunggah.</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Link Artikel <br>
                                    <small class="text-muted">Opsional</small></label>
                                <input type="url" class="form-control @error('link_artikel') is-invalid @enderror"
                                        name="link_artikel" value="{{ old('link_artikel', $ujian->link_artikel) }}"
                                        placeholder="https://...">
                                @error('link_artikel')
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
