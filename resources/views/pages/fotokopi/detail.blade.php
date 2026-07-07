@extends($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi') : (isset($is_prodi) && $is_prodi ? 'layouts.dashboard' : 'layouts.dashboardMahasiswa'))

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
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="mb-3 d-flex">
                <div class="flex-shrink-1">
                    <a href="{{ route($is_admin ? 'jilid.index' : (isset($is_prodi) && $is_prodi ? 'jilid.prodi.index' : 'jilid.mahasiswa')) }}"
                        class="btn btn-secondary float-end"><i class="bi bi-arrow-left ml-2"></i> Kembali</a>
                </div>
                <h4 class="flex-grow-0"></h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-3 rounded border mb-4">
                                <table>
                                    <tr>
                                        <td>NIM/NAMA MAHASISWA</td>
                                        <td>: <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>: <b>{{ $prodi ? $prodi->namaprodi : '' }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>JUDUL TUGAS AKHIR</td>
                                        <td>:
                                            <b>{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            @if ($is_admin)
                                @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                    {{-- SUPER ADMIN: Semua dokumen di semua status --}}
                                    <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir TA</strong></h5>
                                    <hr>
                                    <div class="mt-3">
                                        @if($jilid->lembar_pengesahan)
                                        <a href="{{ storage_url($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR PENGESAHAN</a>
                                        @endif
                                        @if($jilid->lembar_keaslian)
                                        <a href="{{ storage_url($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR KEASLIAN</a>
                                        @endif
                                        @if($jilid->lembar_persetujuan_pembimbing)
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_pembimbing) }}"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR PERSETUJUAN PEMBIMBING</a>
                                        @endif
                                        @if($jilid->lembar_persetujuan_penguji)
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}"
                                            class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i>
                                            LEMBAR PERSETUJUAN PENGUJI</a>
                                        @endif
                                        @if($jilid->lembar_bimbingan)
                                        <a href="{{ storage_url($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR BIMBINGAN</a>
                                        @endif
                                        @if($jilid->lembar_revisi)
                                        <a href="{{ storage_url($jilid->lembar_revisi) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LEMBAR REVISI</a>
                                        @endif
                                        @if($jilid->laporan_pdf)
                                        <a href="{{ storage_url($jilid->laporan_pdf) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT PDF</a>
                                        @endif
                                        @if($jilid->laporan_word)
                                        <a href="{{ storage_url($jilid->laporan_word) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT WORD</a>
                                        @endif
                                        @if($jilid->artikel)
                                        <a href="{{ storage_url($jilid->artikel) }}" class="btn btn-primary mb-3"
                                            target="_blank"><i class="fas fa-download"></i> ARTIKEL FORMAT WORD</a>
                                        @endif
                                        @if ($jilid->berita_acara)
                                            <a href="{{ storage_url($jilid->berita_acara) }}" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> BERITA ACARA</a>
                                        @endif
                                        @if ($jilid->panduan)
                                            <a href="{{ storage_url($jilid->panduan) }}" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> PANDUAN PENGGUNAAN PRODUK TA</a>
                                        @endif
                                        @if ($jilid->lampiran)
                                            <a href="{{ storage_url($jilid->lampiran) }}" class="btn btn-primary mb-3"
                                                target="_blank"><i class="fas fa-download"></i> DOKUMEN LAMPIRAN</a>
                                        @endif
                                        @if ($jilid->link_project)
                                            <a href="{{ $jilid->link_project }}" class="btn btn-secondary mb-3"
                                                target="_blank"><i class="fas fa-paper-plane"></i> LINK PROJECT</a>
                                        @endif
                                        @if ($jilid->file_artikel)
                                            <a href="{{ storage_url($jilid->file_artikel) }}" class="btn btn-info mb-3"
                                                target="_blank"><i class="fas fa-download"></i> FILE ARTIKEL</a>
                                        @endif
                                        @if ($jilid->file_loa)
                                            <a href="{{ storage_url($jilid->file_loa) }}" class="btn btn-info mb-3"
                                                target="_blank"><i class="fas fa-download"></i> FILE LOA</a>
                                        @endif
                                        @if ($jilid->status_artikel)
                                            <span class="badge badge-warning mb-3 p-2" style="font-size:0.9rem">
                                                <i class="fas fa-tag"></i> Status Artikel: {{ ucfirst($jilid->status_artikel) }}
                                            </span>
                                        @endif
                                        @if ($jilid->link_artikel)
                                            <a href="{{ $jilid->link_artikel }}" class="btn btn-secondary mb-3"
                                                target="_blank"><i class="fas fa-external-link-alt"></i> LINK ARTIKEL</a>
                                        @endif
                                    </div>
                                @else
                                    {{-- ADMIN FOTOKOPI: Hanya PDF --}}
                                    @if ($jilid->laporan_pdf)
                                        <a href="{{ storage_url($jilid->laporan_pdf) }}" class="btn btn-primary mb-3 mt-4"
                                            target="_blank"><i class="fas fa-download"></i> LAPORAN PDF</a>
                                    @endif
                                @endif

                                {{-- Form aksi admin (validasi / pembayaran) --}}
                                <form action="{{ route('jilid.acc', $jilid->id) }}" method="post">
                                    @method('put')
                                    @csrf
                                    @if ($jilid->status == 3)
                                        <input type="hidden" name="status" value="4">
                                        <div class="mt-4">
                                            <label>JUMLAH PEMBAYARAN</label>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran penjilidan"
                                                value="{{ $jilid->total_pembayaran }}" required>
                                        </div>
                                    @elseif ($jilid->status == 1)
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">--pilih--</option>
                                                <option value="3">VALID</option>
                                                <option value="2">TIDAK VALID</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Catatan</label>
                                            <textarea name="catatan" id="summernote" required></textarea>
                                        </div>
                                    @endif
                                    @if ($jilid->status == 1 || $jilid->status == 3)
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                            @if ($jilid->status == 1)
                                                SIMPAN
                                            @elseif ($jilid->status == 3)
                                                SELESAI
                                            @endif
                                        </button>
                                    </div>
                                    @endif
                                </form>

                            @else
                                {{-- VIEW PRODI / MAHASISWA DENGAN LIST YANG RAPI --}}
                                <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir TA</strong></h5>
                                <hr>

                                @if($jilid->lembar_pengesahan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Pengesahan (TTD)</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_pengesahan) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_keaslian)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Keaslian</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_keaslian) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_persetujuan_pembimbing)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Pembimbing</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_pembimbing) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_persetujuan_penguji)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Penguji</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_bimbingan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Bimbingan TA</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_bimbingan) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_revisi)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Revisi (ACC Penguji)</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_revisi) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->laporan_pdf)
                                <div class="row mb-2">
                                    <div class="col-md-4">Laporan TA (PDF)</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->laporan_pdf, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->laporan_pdf }}" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        @else
                                            <a href="{{ storage_url($jilid->laporan_pdf) }}" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->laporan_word)
                                <div class="row mb-2">
                                    <div class="col-md-4">Laporan TA (Word)</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->laporan_word, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->laporan_word }}" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        @else
                                            <a href="{{ storage_url($jilid->laporan_word) }}" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->artikel)
                                <div class="row mb-2">
                                    <div class="col-md-4">Artikel (Word)</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->artikel, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->artikel }}" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        @else
                                            <a href="{{ storage_url($jilid->artikel) }}" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->berita_acara)
                                <div class="row mb-2">
                                    <div class="col-md-4">Berita Acara</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->berita_acara) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->panduan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Panduan Penggunaan Produk TA</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->panduan, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->panduan }}" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka Link Google Drive
                                            </a>
                                        @else
                                            <a href="{{ storage_url($jilid->panduan) }}" target="_blank" class="text-primary">
                                                <i class="fas fa-paperclip"></i> Buka File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lampiran)
                                <div class="row mb-2">
                                    <div class="col-md-4">Dokumen Lampiran</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lampiran) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->link_project)
                                <div class="row mb-2">
                                    <div class="col-md-4">Link Project TA</div>
                                    <div class="col-md-8">
                                        <a href="{{ $jilid->link_project }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-external-link-alt"></i> Buka Link
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($jilid->file_artikel)
                                <div class="row mb-2">
                                    <div class="col-md-4">File Artikel</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->file_artikel) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->file_loa)
                                <div class="row mb-2">
                                    <div class="col-md-4">File LoA / Letter of Acceptance</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->file_loa) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> Buka File
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->status_artikel)
                                <div class="row mb-2">
                                    <div class="col-md-4">Status Artikel</div>
                                    <div class="col-md-8">
                                        <span class="badge badge-warning p-2">{{ ucfirst($jilid->status_artikel) }}</span>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->link_artikel)
                                <div class="row mb-2">
                                    <div class="col-md-4">Link Artikel</div>
                                    <div class="col-md-8">
                                        <a href="{{ $jilid->link_artikel }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Buka Link
                                        </a>
                                    </div>
                                </div>
                                @endif

                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    
                    {{-- Revisi --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($revisis) }}
                                </span>
                            </h3>

                            <div class="card-tools">
                                {{ $revisis->links() }}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="p-2">

                                @foreach ($revisis as $revisi)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span
                                                class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:m a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br($revisi->catatan) !!}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
