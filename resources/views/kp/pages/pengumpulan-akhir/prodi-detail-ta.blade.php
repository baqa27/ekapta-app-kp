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
                        <li class="breadcrumb-item"><a href="{{ route('kp.pengumpulan-akhir.prodi.index') }}">Pengumpulan Akhir</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="{{ route('kp.pengumpulan-akhir.prodi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left ml-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{-- Card Info --}}
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($jilid->status == 'review') bg-secondary
                                @elseif ($jilid->status == 'revisi') bg-warning
                                @elseif ($jilid->status == 'terkumpul') bg-primary
                                @elseif ($jilid->status == 'selesai') bg-success @endif">
                                @if ($jilid->status == 'review')
                                    REVIEW
                                @elseif ($jilid->status == 'revisi')
                                    REVISI
                                @elseif ($jilid->status == 'terkumpul')
                                    VALID
                                @elseif ($jilid->status == 'selesai')
                                    SELESAI
                                @else
                                    {{ strtoupper($jilid->status) }}
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
                                <div class="col-md-4">Judul Tugas Akhir</div>
                                <div class="col-md-8"><b>{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul ?? '-' }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Submit</div>
                                <div class="col-md-8"><b>{{ $jilid->created_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>

                            @if($jilid->updated_at && $jilid->updated_at != $jilid->created_at)
                            <div class="row">
                                <div class="col-md-4">Terakhir Update</div>
                                <div class="col-md-8"><b>{{ $jilid->updated_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>
                            @endif

                            <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir TA</strong></h5>
                            <hr>

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

                            @if($jilid->lembar_keaslian)
                            <div class="row">
                                <div class="col-md-4">Lembar Keaslian</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_keaslian) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_keaslian) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_persetujuan_pembimbing)
                            <div class="row">
                                <div class="col-md-4">Lembar Persetujuan Pembimbing</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_persetujuan_pembimbing) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_persetujuan_pembimbing) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_persetujuan_penguji)
                            <div class="row">
                                <div class="col-md-4">Lembar Persetujuan Penguji</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_persetujuan_penguji) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_bimbingan)
                            <div class="row">
                                <div class="col-md-4">Lembar Bimbingan TA</div>
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
                                <div class="col-md-4">Laporan TA (PDF)</div>
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
                                <div class="col-md-4">Laporan TA (Word)</div>
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

                            @if($jilid->artikel)
                            <div class="row">
                                <div class="col-md-4">Artikel (Word)</div>
                                <div class="col-md-8">
                                    @if(filter_var($jilid->artikel, FILTER_VALIDATE_URL))
                                        <a href="{{ $jilid->artikel }}" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                        </a>
                                    @else
                                        <a href="{{ storage_url($jilid->artikel) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($jilid->artikel) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->berita_acara)
                            <div class="row">
                                <div class="col-md-4">Berita Acara</div>
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
                                <div class="col-md-4">Panduan Penggunaan Produk TA</div>
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

                            @if($jilid->lampiran)
                            <div class="row">
                                <div class="col-md-4">Dokumen Lampiran</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lampiran) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lampiran) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->link_project)
                            <div class="row">
                                <div class="col-md-4">Link Project TA</div>
                                <div class="col-md-8">
                                    <a href="{{ $jilid->link_project }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-external-link-alt"></i> Buka Link
                                    </a>
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
                                            <td>{!! nl2br($revisi->catatan) !!}</td>
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
