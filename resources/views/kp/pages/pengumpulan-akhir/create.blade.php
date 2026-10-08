@extends('kp.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir KP</a></li>
                        <li class="breadcrumb-item active">Submit</li>
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
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                    @endif

                    @if(isset($jilid) && $jilid && $jilid->isDraft())
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Nilai Pembimbing Sudah Diberikan</h5>
                        <p class="mb-0">Dosen pembimbing sudah memberikan nilai: <strong>{{ $jilid->nilai_pembimbing }}</strong></p>
                        @if($jilid->catatan)
                        <p class="mb-0">Catatan: {{ $jilid->catatan }}</p>
                        @endif
                        <p class="mb-0 mt-2"><em>Silahkan upload dokumen Pengumpulan Akhir KP di bawah ini.</em></p>
                    </div>
                    @endif

                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-info-circle"></i> Informasi Penting</h5>
                        <ul class="mb-0">
                            <li>Pastikan semua dokumen yang diupload sudah lengkap dan sesuai dengan format yang ditentukan</li>
                            <li><strong>Batas ukuran file:</strong>
                                <ul>
                                    <li>Dokumen PDF (Lembar Pengesahan, Bimbingan, Revisi): Maksimal 5MB per file</li>
                                    <li>File Project (ZIP/RAR): Maksimal 30MB</li>
                                    <li>Laporan PDF/Word: Maksimal 10MB per file</li>
                                    <li>Form Nilai & Berita Acara: Maksimal 1MB per file</li>
                                </ul>
                            </li>
                            <li>Jika file terlalu besar, compress/kompres terlebih dahulu atau upload ke Google Drive dan gunakan link</li>
                        </ul>
                    </div>

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kp.pengumpulan-akhir.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <h5><strong>Data Mahasiswa</strong></h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="{{ $mahasiswa->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NIM</label>
                                            <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Semester Pelaksanaan KP</label>
                                            <input type="text" class="form-control" value="Semester {{ $mahasiswa->semesterKP ?? '-' }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();
                                    $pendaftaran = $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first();
                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                    if (!$dosen_pembimbing) {
                                        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
                                    }
                                @endphp

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul KP</label>
                                            <textarea class="form-control" rows="2" disabled>{{ $pengajuan->judul ?? '-' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pembimbing KP</label>
                                            <input type="text" class="form-control" value="{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lokasi KP</label>
                                            <input type="text" class="form-control" value="{{ $pengajuan->lokasi_kp ?? '-' }}" disabled>
                                            <input type="hidden" name="lokasi_kp" value="{{ $pengajuan->lokasi_kp ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Pelaksanaan KP</label>
                                            <input type="text" class="form-control" value="{{ $waktu_pelaksanaan }}" disabled>
                                            <input type="hidden" name="waktu_pelaksanaan_kp" value="{{ $waktu_pelaksanaan }}">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Utama</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>Lembar Pengesahan KP (TTD) <span class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('lembar_pengesahan')is-invalid @enderror"
                                                name="lembar_pengesahan" accept=".pdf" id="lembar_pengesahan" required>
                                            <label class="custom-file-label" for="lembar_pengesahan">Pilih file PDF...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Lembar pengesahan yang sudah ditandatangani pembimbing, penguji, dan pihak terkait</small>
                                    @error('lembar_pengesahan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Lembar Bimbingan KP <span class="text-danger">*</span>
                                        <br><small><a href="{{ route('kp.cetak.riwayat.bimbingan.mahasiswa') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Lembar Bimbingan</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('lembar_bimbingan')is-invalid @enderror"
                                                name="lembar_bimbingan" accept=".pdf" id="lembar_bimbingan" required>
                                            <label class="custom-file-label" for="lembar_bimbingan">Pilih file PDF...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_bimbingan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Lembar Revisi (ACC Penguji, 1 file) <span class="text-danger">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('lembar_revisi')is-invalid @enderror"
                                                name="lembar_revisi" accept=".pdf" id="lembar_revisi" required>
                                            <label class="custom-file-label" for="lembar_revisi">Pilih file PDF...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_revisi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <h5 class="mt-4"><strong>Laporan Kerja Praktek</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format PDF <span class="text-danger">*</span>
                                        <br><small class="text-muted">Digabung dengan Lembar Pengesahan TTD</small>
                                    </label>
                                    <select name="type_laporan_pdf" id="type_laporan_pdf" class="form-control mb-2" onchange="toggleInputFieldsPdf()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field_pdf" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                name="laporan_pdf" accept=".pdf" id="laporan_pdf">
                                            <label class="custom-file-label">Pilih file PDF...</label>
                                        </div>
                                    </div>
                                    <div id="link_field_pdf" style="display:none;">
                                        <input type="url" name="laporan_link_pdf" class="form-control" placeholder="https://drive.google.com/..." id="laporan_link_pdf">
                                    </div>
                                    @error('laporan_pdf')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format Word <span class="text-danger">*</span>
                                        <br><small class="text-muted">Format .docx</small>
                                    </label>
                                    <select name="type_laporan" id="type_laporan" class="form-control mb-2" onchange="toggleInputFields()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                name="laporan_word" accept=".docx" id="laporan_word">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="link_field" style="display:none;">
                                        <input type="url" name="laporan_link" class="form-control" placeholder="https://drive.google.com/..." id="laporan_link">
                                    </div>
                                    @error('laporan_word')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Link Produk KP
                                        <br><small class="text-muted">Opsional - Upload ke Google Drive</small>
                                    </label>
                                    <input type="url" class="form-control" name="link_project" value="{{ old('link_project') }}" placeholder="https://drive.google.com/...">
                                </div>

                                <div class="form-group">
                                    <label>
                                        File Project / Program KP <span class="text-muted">(Opsional)</span>
                                        <br><small class="text-muted">File project/program dikompres dalam format .zip atau .rar (Maks 100 MB). Kosongkan jika prodi tidak mewajibkan produk.</small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('file_project')is-invalid @enderror"
                                                name="file_project" accept=".zip,.rar" id="file_project">
                                            <label class="custom-file-label" for="file_project">Pilih file ZIP/RAR...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('file_project')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Pendukung</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>
                                        Form Nilai KP <span class="text-danger">*</span>
                                        <br><small><a href="{{ route('kp.cetak.formulir.nilai.akhir') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Formulir Nilai Akhir KP</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('form_nilai_kp')is-invalid @enderror"
                                                name="form_nilai_kp" accept=".pdf,.jpg,.jpeg,.png" id="form_nilai_kp" required>
                                            <label class="custom-file-label" for="form_nilai_kp">Pilih file PDF/Image...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('form_nilai_kp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Berita Acara Serah Terima Produk <span class="text-muted">(Opsional)</span>
                                        <br><small class="text-muted">Berita acara serah terima produk dengan instansi/tempat penelitian KP dengan template
                                            <a href="https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view" target="_blank" class="text-primary">https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view</a>
                                        </small>
                                        <br><small><a href="{{ route('kp.cetak.berita.acara.serah.terima') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Template Berita Acara</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('berita_acara')is-invalid @enderror"
                                                name="berita_acara" accept=".pdf,.jpg,.jpeg,.png" id="berita_acara">
                                            <label class="custom-file-label" for="berita_acara">Pilih file PDF/Image...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('berita_acara')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Panduan Penggunaan Produk KP <span class="text-muted">(Opsional)</span>
                                        <br><small class="text-muted">Format .docx atau Link Google Drive. Kosongkan jika tidak ada produk.</small>
                                    </label>
                                    <select name="type_panduan" id="type_panduan" class="form-control mb-2" onchange="toggleInputFieldsPanduan()">
                                        <option value="">-- Pilih Metode Upload (Jika Ada Produk) --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="panduan_upload_field" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('panduan')is-invalid @enderror"
                                                name="panduan" id="panduan" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="panduan_link_field" style="display:none;">
                                        <input type="url" name="panduan_link" class="form-control" placeholder="https://drive.google.com/..." id="panduan_link">
                                    </div>
                                    @error('panduan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('kp.pengumpulan-akhir.mahasiswa') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Pengumpulan Akhir KP
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
@section('script')
    <script>
        // Custom file input - show selected filename
        $(document).ready(function() {
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });

        function toggleInputFields() {
            var typeLaporan = document.getElementById('type_laporan').value;
            var uploadField = document.getElementById('upload_field');
            var linkField = document.getElementById('link_field');
            const laporan_word = document.getElementById('laporan_word');
            const laporan_link = document.getElementById('laporan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_word.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_word.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                laporan_word.required = false;
                laporan_link.required = false;
            }
        }

        function toggleInputFieldsPdf() {
            var typeLaporan = document.getElementById('type_laporan_pdf').value;
            var uploadField = document.getElementById('upload_field_pdf');
            var linkField = document.getElementById('link_field_pdf');
            const laporan_pdf = document.getElementById('laporan_pdf');
            const laporan_link = document.getElementById('laporan_link_pdf');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_pdf.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_pdf.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                laporan_pdf.required = false;
                laporan_link.required = false;
            }
        }

        function toggleInputFieldsPanduan() {
            var typeLaporan = document.getElementById('type_panduan').value;
            var uploadField = document.getElementById('panduan_upload_field');
            var linkField = document.getElementById('panduan_link_field');
            const panduan = document.getElementById('panduan');
            const panduan_link = document.getElementById('panduan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                panduan.required = true;
                panduan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                panduan.required = false;
                panduan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                panduan.required = false;
                panduan_link.required = false;
            }
        }
    </script>
@endsection




