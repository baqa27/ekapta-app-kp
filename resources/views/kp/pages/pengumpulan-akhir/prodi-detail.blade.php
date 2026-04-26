@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.pengumpulan-akhir.prodi.index') }}">Jilid KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    {{-- Card Info --}}
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($jilid->status == 1) bg-secondary
                                @elseif ($jilid->status == 2) bg-warning
                                @elseif ($jilid->status == 3) bg-primary
                                @elseif ($jilid->status == 4) bg-success @endif">
                                @if ($jilid->status == 1)
                                    REVIEW
                                @elseif ($jilid->status == 2)
                                    REVISI
                                @elseif ($jilid->status == 3)
                                    VALID
                                @elseif ($jilid->status == 4)
                                    SELESAI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">NIM</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nim }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Nama Lengkap</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nama }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Prodi</div>
                                <div class="col-md-8"><b>{{ $prodi ? $prodi->namaprodi : $mahasiswa->prodi }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Judul KP</div>
                                <div class="col-md-8"><b>{{ $jilid->mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul ?? '-' }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Submit</div>
                                <div class="col-md-8"><b>{{ $jilid->created_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Waktu Pelaksanaan KP</div>
                                <div class="col-md-8"><b>{{ $waktu_pelaksanaan }}</b></div>
                            </div>
                            <hr>

                            @if($jilid->updated_at && $jilid->updated_at != $jilid->created_at)
                            <div class="row">
                                <div class="col-md-4">Terakhir Update</div>
                                <div class="col-md-8"><b>{{ $jilid->updated_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>
                            @endif

                            @php
                                // Ambil presentase nilai dari database (relasi presentaseNilaiKP)
                                $prodi_obj = \App\Models\Prodi::where('kode', $mahasiswa->prodi)
                                    ->orWhere('namaprodi', $mahasiswa->prodi)
                                    ->first();
                                $presentase_nilai = $prodi_obj && $prodi_obj->presentaseNilaiKP ? $prodi_obj->presentaseNilaiKP : null;
                            @endphp

                            {{-- Nilai KP --}}
                            <h5 class="mt-3"><strong>Nilai Kerja Praktek</strong></h5>
                            <hr>

                            {{-- Nilai Pembimbing --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Pembimbing</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_pembimbing = 0;
                                        if($mahasiswa->seminarKP && $mahasiswa->seminarKP->nilai_pembimbing) {
                                            $nilai_pembimbing = $mahasiswa->seminarKP->nilai_pembimbing;
                                        } elseif($jilid->nilai_pembimbing) {
                                            $nilai_pembimbing = $jilid->nilai_pembimbing;
                                        }
                                    @endphp
                                    @if($nilai_pembimbing > 0)
                                        <b>{{ number_format($nilai_pembimbing, 2) }}</b>
                                        @php
                                            $dosen_pembimbing = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama'])->first();
                                        @endphp
                                        @if($dosen_pembimbing)
                                        <small class="text-muted">({{ $dosen_pembimbing->nama }})</small>
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

                            <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir</strong></h5>
                            <hr>

                            @php
                                $has_documents = $jilid->lembar_pengesahan || $jilid->lembar_bimbingan || 
                                                $jilid->lembar_revisi || $jilid->laporan_pdf || $jilid->laporan_word || 
                                                $jilid->file_project || $jilid->link_project || $jilid->form_nilai_kp || 
                                                $jilid->berita_acara || $jilid->panduan;
                            @endphp

                            @if(!$has_documents)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Belum ada dokumen yang diupload.</strong>
                            </div>
                            @endif

                            @if($jilid->lembar_pengesahan)
                            <div class="row">
                                <div class="col-md-4">Lembar Pengesahan (TTD)</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_pengesahan) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_pengesahan) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_bimbingan)
                            <div class="row">
                                <div class="col-md-4">Lembar Bimbingan KP</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_bimbingan) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_bimbingan) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_revisi)
                            <div class="row">
                                <div class="col-md-4">Lembar Revisi (ACC Penguji)</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_revisi) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_revisi) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->laporan_pdf)
                            <div class="row">
                                <div class="col-md-4">Laporan KP (PDF)</div>
                                <div class="col-md-8">
                                    @if(filter_var($jilid->laporan_pdf, FILTER_VALIDATE_URL))
                                        <a href="{{ $jilid->laporan_pdf }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                        </a>
                                    @else
                                        <a href="{{ storage_url($jilid->laporan_pdf) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_pdf) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->laporan_word)
                            <div class="row">
                                <div class="col-md-4">Laporan KP (Word)</div>
                                <div class="col-md-8">
                                    @if(filter_var($jilid->laporan_word, FILTER_VALIDATE_URL))
                                        <a href="{{ $jilid->laporan_word }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                        </a>
                                    @else
                                        <a href="{{ storage_url($jilid->laporan_word) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_word) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->file_project)
                            <div class="row">
                                <div class="col-md-4">File Project/Program KP</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->file_project) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->file_project) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->link_project)
                            <div class="row">
                                <div class="col-md-4">Link Project KP</div>
                                <div class="col-md-8">
                                    <a href="{{ $jilid->link_project }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-external-link-alt"></i> Buka Link
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->form_nilai_kp)
                            <div class="row">
                                <div class="col-md-4">Form Nilai KP</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->form_nilai_kp) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->form_nilai_kp) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->berita_acara)
                            <div class="row">
                                <div class="col-md-4">Berita Acara Serah Terima Produk</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->berita_acara) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->berita_acara) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->panduan)
                            <div class="row">
                                <div class="col-md-4">Panduan Penggunaan Produk KP</div>
                                <div class="col-md-8">
                                    @if(filter_var($jilid->panduan, FILTER_VALIDATE_URL))
                                        <a href="{{ $jilid->panduan }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                        </a>
                                    @else
                                        <a href="{{ storage_url($jilid->panduan) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($jilid->panduan) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Riwayat Revisi --}}
                            @if($revisis->count() > 0)
                            <h5 class="mt-4"><strong>Riwayat Revisi</strong></h5>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="50">No</th>
                                            <th width="150">Tanggal</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($revisis as $revisi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $revisi->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $revisi->catatan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $revisis->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
