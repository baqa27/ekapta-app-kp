@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir KP</a></li>
                        <li class="breadcrumb-item active">Revisi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (count($revisis) != 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Pengajuan Pengumpulan Akhir KP Anda berstatus <strong>REVISI</strong>. Silahkan submit ulang dokumen yang diperlukan.
                            <br>
                            <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $jilid->id) }}" class="btn btn-sm btn-outline-dark mt-2">
                                <i class="fas fa-eye mr-1"></i> Lihat Catatan Revisi
                            </a>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Revisi belum terkirim.</strong> Perbaiki kesalahan berikut lalu submit ulang:
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kp.pengumpulan-akhir.update', $jilid->id) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @csrf

                                <h5><strong>Data Mahasiswa</strong></h5>
                                <hr>

                                @php
                                    $mahasiswa = $jilid->mahasiswa;
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
                                            <input type="text" class="form-control" value="{{ $jilid->lokasi_kp ?? $pengajuan->lokasi_kp ?? '-' }}" disabled>
                                            <input type="hidden" name="lokasi_kp" value="{{ $jilid->lokasi_kp ?? $pengajuan->lokasi_kp ?? '' }}">
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

                                @php
                                    // Cek apakah admin fotokopi
                                    $is_fotokopi = false; // mahasiswa view, always false
                                    
                                    // Cek apakah mahasiswa karyawan
                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($mahasiswa);
                                    
                                    // Ambil presentase nilai dari database (relasi presentaseNilaiKP)
                                    $prodi_obj = \App\Models\Prodi::where('kode', $mahasiswa->prodi)
                                        ->orWhere('namaprodi', $mahasiswa->prodi)
                                        ->first();
                                    $presentase_nilai = $prodi_obj && $prodi_obj->presentaseNilaiKP ? $prodi_obj->presentaseNilaiKP : null;
                                @endphp

                                @if(!$is_fotokopi)
                                {{-- Nilai KP - Hanya untuk Super Admin dan Mahasiswa --}}
                                <h5 class="mt-3"><strong>Nilai Kerja Praktek</strong></h5>
                                <hr>

                                {{-- Nilai Pembimbing dari seminar_kps.nilai_pembimbing atau jilid_kps.nilai_pembimbing --}}
                                <div class="row">
                                    <div class="col-md-4">Nilai Dosen Pembimbing</div>
                                    <div class="col-md-8">
                                        @php
                                            // Logika sama dengan Prodi: cek seminar dulu, baru jilid
                                            $nilai_pembimbing = 0;
                                            if($mahasiswa->seminarKP && $mahasiswa->seminarKP->nilai_pembimbing) {
                                                $nilai_pembimbing = $mahasiswa->seminarKP->nilai_pembimbing;
                                            } elseif(isset($jilid) && $jilid && $jilid->nilai_pembimbing) {
                                                $nilai_pembimbing = $jilid->nilai_pembimbing;
                                            } elseif($mahasiswa->jilidKP && $mahasiswa->jilidKP->nilai_pembimbing) {
                                                $nilai_pembimbing = $mahasiswa->jilidKP->nilai_pembimbing;
                                            }
                                        @endphp
                                        @if($nilai_pembimbing > 0)
                                            <b>{{ number_format($nilai_pembimbing, 2) }}</b>
                                            @php
                                                $dosen_pembimbing_nilai = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama'])->first();
                                            @endphp
                                            @if($dosen_pembimbing_nilai)
                                            <small class="text-muted">({{ $dosen_pembimbing_nilai->nama }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum dinilai</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>

                                @if(!$is_karyawan)
                                {{-- Nilai Penguji - HANYA untuk mahasiswa reguler --}}
                                <div class="row">
                                    <div class="col-md-4">Nilai Dosen Penguji</div>
                                    <div class="col-md-8">
                                        @php
                                            $nilai_penguji = 0;
                                            if($mahasiswa->seminarKP) {
                                                // Prioritas: nilai_seminar (dari link penilaian), fallback ke nilai_penguji
                                                $nilai_penguji = $mahasiswa->seminarKP->nilai_seminar ?? $mahasiswa->seminarKP->nilai_penguji ?? 0;
                                            }
                                        @endphp
                                        @if($nilai_penguji > 0)
                                            <b>{{ number_format($nilai_penguji, 2) }}</b>
                                            @if($mahasiswa->seminarKP && $mahasiswa->seminarKP->sesiSeminar && $mahasiswa->seminarKP->sesiSeminar->dosenPenguji)
                                            <small class="text-muted">({{ $mahasiswa->seminarKP->sesiSeminar->dosenPenguji->nama ?? '-' }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted">Belum dinilai</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                {{-- Nilai Instansi --}}
                                <div class="row">
                                    <div class="col-md-4">Nilai Instansi</div>
                                    <div class="col-md-8">
                                        @php
                                            $nilai_instansi = 0;
                                            if($mahasiswa->seminarKP) {
                                                $nilai_instansi = $mahasiswa->seminarKP->nilai_instansi ?? 0;
                                            }
                                        @endphp
                                        @if($nilai_instansi > 0)
                                            <b>{{ number_format($nilai_instansi, 2) }}</b>
                                        @else
                                            <span class="text-muted">Belum dinilai</span>
                                        @endif
                                    </div>
                                </div>
                                <hr>

                                {{-- Nilai Akhir KP --}}
                                @php
                                    // Hitung nilai akhir berdasarkan jenis mahasiswa dan presentase dari database
                                    $nilai_pembimbing_display = $nilai_pembimbing;
                                    $nilai_penguji_display = $is_karyawan ? 0 : ($nilai_penguji ?? 0);
                                    $nilai_instansi_display = $nilai_instansi;
                                    
                                    // Ambil bobot dari database atau gunakan default
                                    if ($presentase_nilai) {
                                        $bobot_pembimbing = $presentase_nilai->bobot_pembimbing;
                                        $bobot_penguji = $is_karyawan ? 0 : $presentase_nilai->bobot_penguji;
                                        $bobot_instansi = 100 - $bobot_pembimbing - $bobot_penguji;
                                    } else {
                                        // Default jika tidak ada presentase_nilai
                                        $bobot_pembimbing = $is_karyawan ? 40 : 40;
                                        $bobot_penguji = $is_karyawan ? 0 : 30;
                                        $bobot_instansi = $is_karyawan ? 60 : 30;
                                    }
                                    
                                    // Hitung nilai akhir
                                    $nilai_akhir_calculated = ($nilai_pembimbing_display * $bobot_pembimbing / 100) + 
                                                             ($nilai_penguji_display * $bobot_penguji / 100) + 
                                                             ($nilai_instansi_display * $bobot_instansi / 100);
                                @endphp

                                <div class="row">
                                    <div class="col-md-4"><strong>Nilai Akhir KP</strong></div>
                                    <div class="col-md-8">
                                        @if($nilai_akhir_calculated > 0)
                                            <h4 class="text-success"><b>{{ number_format($nilai_akhir_calculated, 2) }}</b></h4>
                                        @else
                                            <h4 class="text-muted"><b>0.00</b></h4>
                                        @endif
                                        <small class="text-muted">
                                            @if($is_karyawan)
                                                Formula: (Pembimbing × {{ $bobot_pembimbing }}%) + (Instansi × {{ $bobot_instansi }}%)
                                            @else
                                                Formula: (Pembimbing × {{ $bobot_pembimbing }}%) + (Penguji × {{ $bobot_penguji }}%) + (Instansi × {{ $bobot_instansi }}%)
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <hr>
                                @endif
                                {{-- End Nilai KP --}}

                                <h5 class="mt-4"><strong>Dokumen Utama</strong></h5>
                                <hr>

                                {{-- Simple PDF fields dari config --}}
                                    @foreach(config('kp.dokumen_pengumpulan_akhir') as $document)
                                        @if(!in_array($document['field'], ['laporan_pdf', 'laporan_word', 'panduan', 'link_project']))
                                            <div class="form-group">
                                                <label>{{ $document['label'] }} @if($document['required'] && !$jilid->{$document['field']})<span class="text-danger">*</span>@endif</label>
                                                <div class="input-group mb-2">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error($document['field'])is-invalid @enderror"
                                                            name="{{ $document['field'] }}" accept=".pdf" id="{{ $document['field'] }}">
                                                        <label class="custom-file-label" for="{{ $document['field'] }}">Pilih file baru (opsional)...</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Dokumen</span>
                                                    </div>
                                                </div>
                                                @error($document['field'])
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                @if($jilid->{$document['field']})
                                                    <small>File saat ini: <a href="{{ storage_url($jilid->{$document['field']}) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->{$document['field']}) }}</a></small>
                                                @else
                                                    <small class="text-muted">Belum diunggah</small>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                <h5 class="mt-4"><strong>Laporan Kerja Praktek</strong></h5>
                                <hr>

                                {{-- Laporan PDF (toggle upload/link) --}}
                                <div class="form-group">
                                    <label>
                                        Laporan KP Format PDF
                                        <br><small class="text-muted">Digabung dengan Lembar Pengesahan TTD</small>
                                    </label>
                                    <select name="type_laporan_pdf" id="type_laporan_pdf" class="form-control mb-2" onchange="toggleInputFieldsPdf()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field_pdf" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                name="laporan_pdf" accept=".pdf" id="laporan_pdf">
                                            <label class="custom-file-label">Pilih file PDF...</label>
                                        </div>
                                    </div>
                                    <div id="link_field_pdf" style="display:none;">
                                        <input type="url" name="laporan_link_pdf" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link_pdf">
                                    </div>
                                    @error('laporan_pdf')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->laporan_pdf)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->laporan_pdf) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_pdf) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Laporan Word (toggle upload/link) --}}
                                <div class="form-group">
                                    <label>
                                        Laporan KP Format Word
                                        <br><small class="text-muted">Format .docx</small>
                                    </label>
                                    <select name="type_laporan" id="type_laporan" class="form-control mb-2" onchange="toggleInputFields()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                name="laporan_word" accept=".docx" id="laporan_word">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="link_field" style="display:none;">
                                        <input type="url" name="laporan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link">
                                    </div>
                                    @error('laporan_word')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->laporan_word)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->laporan_word) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_word) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- File Project --}}
                                <div class="form-group">
                                    <label>
                                        File Project / Program KP
                                        <br><small class="text-muted">File project/program dikompres dalam format .zip atau .rar (Maks 100 MB)</small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('file_project')is-invalid @enderror"
                                                name="file_project" accept=".zip,.rar" id="file_project">
                                            <label class="custom-file-label" for="file_project">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('file_project')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->file_project)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->file_project) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->file_project) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Link Project --}}
                                <div class="form-group">
                                    <label>
                                        Link Produk KP
                                        <br><small class="text-muted">Opsional - Upload ke Google Drive</small>
                                    </label>
                                    <input type="url" class="form-control" name="link_project" value="{{ $jilid->link_project }}" placeholder="https://drive.google.com/...">
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Pendukung</strong></h5>
                                <hr>

                                {{-- Form Nilai KP --}}
                                <div class="form-group">
                                    <label>
                                        Form Nilai KP
                                         <!-- <br><small><a href="{{ route('kp.cetak.formulir.nilai.akhir') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Formulir Nilai Akhir KP</a></small> -->
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('form_nilai_kp')is-invalid @enderror"
                                                name="form_nilai_kp" accept=".pdf,.jpg,.jpeg,.png" id="form_nilai_kp">
                                            <label class="custom-file-label" for="form_nilai_kp">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('form_nilai_kp')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->form_nilai_kp)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->form_nilai_kp) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->form_nilai_kp) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Berita Acara --}}
                                <div class="form-group">
                                    <label>
                                        Berita Acara Serah Terima Produk KP
                                        <br><small class="text-muted">Berita acara serah terima produk dengan instansi/tempat penelitian KP dengan template
                                            <a href="https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view" target="_blank" class="text-primary">https://drive.google.com/file/d/1X9eJxyj5GiPYP2MYHGOZiZEEbWWgGJ0J/view</a>
                                        </small>
                                        <br><small><a href="{{ route('kp.cetak.berita.acara.serah.terima') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Template Berita Acara</a></small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('berita_acara')is-invalid @enderror"
                                                name="berita_acara" accept=".pdf,.jpg,.jpeg,.png" id="berita_acara">
                                            <label class="custom-file-label" for="berita_acara">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <small class="text-info d-block mb-2"><i class="fas fa-info-circle"></i> Wajib untuk prodi Teknik Informatika</small>
                                    @error('berita_acara')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->berita_acara)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->berita_acara) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->berita_acara) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Bukti Nilai Instansi --}}
                                <div class="form-group">
                                    <label>
                                        Bukti Nilai Instansi
                                        <br><small class="text-muted">Surat atau dokumen yang menunjukkan nilai yang diberikan instansi tempat KP</small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('bukti_nilai_instansi')is-invalid @enderror"
                                                name="bukti_nilai_instansi" accept=".pdf,.jpg,.jpeg,.png" id="bukti_nilai_instansi">
                                            <label class="custom-file-label" for="bukti_nilai_instansi">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('bukti_nilai_instansi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->bukti_nilai_instansi)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->bukti_nilai_instansi) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->bukti_nilai_instansi) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Lampiran --}}
                                <div class="form-group">
                                    <label>
                                        Dokumen Lampiran Lainnya
                                        <br><small class="text-muted">Dokumen pendukung lain yang relevan dengan KP</small>
                                    </label>
                                    <div class="input-group mb-2">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
                                                name="lampiran" accept=".pdf" id="lampiran">
                                            <label class="custom-file-label" for="lampiran">Pilih file baru (opsional)...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->lampiran)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->lampiran) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->lampiran) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                {{-- Panduan (toggle upload/link) --}}
                                <div class="form-group">
                                    <label>
                                        Panduan Penggunaan Produk KP
                                        <br><small class="text-muted">Format .docx atau Link Google Drive</small>
                                    </label>
                                    <select name="type_panduan" id="type_panduan" class="form-control mb-2" onchange="toggleInputFieldsPanduan()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="panduan_upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('panduan')is-invalid @enderror"
                                                name="panduan" id="panduan" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="panduan_link_field" style="display:none;">
                                        <input type="url" name="panduan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="panduan_link">
                                    </div>
                                    <small class="text-info d-block mb-2"><i class="fas fa-info-circle"></i> Wajib untuk prodi Teknik Informatika</small>
                                    @error('panduan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($jilid->panduan)
                                        <small>File saat ini: <a href="{{ storage_url($jilid->panduan) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> {{ basename($jilid->panduan) }}</a></small>
                                    @else
                                        <small class="text-muted">Belum diunggah</small>
                                    @endif
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('kp.pengumpulan-akhir.mahasiswa') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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
