@extends($sidebar ? 'layouts.dashboard' : 'layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="{{ route($sidebar ? 'jilid.prodi.index' : 'jilid.mahasiswa') }}">Pengumpulan TA</a></li>
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
                                <div class="col-md-4">Judul TA</div>
                                <div class="col-md-8"><b>{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul ?? '-' }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Submit</div>
                                <div class="col-md-8"><b>{{ $jilid->created_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Waktu Pelaksanaan TA</div>
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

                            {{-- Nilai TA --}}
                            @php
                                // Ambil presentase nilai dari database (relasi presentaseNilai)
                                $presentase_nilai = $prodi && $prodi->presentaseNilai ? $prodi->presentaseNilai : null;
                            @endphp

                            <h5 class="mt-3"><strong>Nilai Tugas Akhir</strong></h5>
                            <hr>

                            {{-- Nilai Pembimbing --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Pembimbing</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_pembimbing = 0;
                                        if($mahasiswa->ujian && $mahasiswa->ujian->nilai_pembimbing) {
                                            $nilai_pembimbing = $mahasiswa->ujian->nilai_pembimbing;
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

                            {{-- Nilai Penguji --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Penguji</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_penguji = 0;
                                        if($mahasiswa->ujian) {
                                            $nilai_penguji = $mahasiswa->ujian->nilai_penguji ?? $mahasiswa->ujian->nilai_seminar ?? 0;
                                        }
                                    @endphp
                                    @if($nilai_penguji > 0)
                                        <b>{{ number_format($nilai_penguji, 2) }}</b>
                                        @if($mahasiswa->ujian && $mahasiswa->ujian->sesiUjian && $mahasiswa->ujian->sesiUjian->dosenPenguji)
                                        <small class="text-muted">({{ $mahasiswa->ujian->sesiUjian->dosenPenguji->nama ?? '-' }})</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            {{-- Nilai Instansi --}}
                            <div class="row">
                                <div class="col-md-4">Nilai Instansi</div>
                                <div class="col-md-8">
                                    @php
                                        $nilai_instansi = 0;
                                        if($mahasiswa->ujian) {
                                            $nilai_instansi = $mahasiswa->ujian->nilai_instansi ?? 0;
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

                            {{-- Nilai Akhir TA --}}
                            @php
                                $nilai_pembimbing_display = $nilai_pembimbing;
                                $nilai_penguji_display = $nilai_penguji ?? 0;
                                $nilai_instansi_display = $nilai_instansi;
                                
                                if ($presentase_nilai) {
                                    $bobot_pembimbing = $presentase_nilai->bobot_pembimbing;
                                    $bobot_penguji = $presentase_nilai->bobot_penguji;
                                    $bobot_instansi = 100 - $bobot_pembimbing - $bobot_penguji;
                                } else {
                                    $bobot_pembimbing = 40;
                                    $bobot_penguji = 30;
                                    $bobot_instansi = 30;
                                }
                                
                                $nilai_akhir_calculated = ($nilai_pembimbing_display * $bobot_pembimbing / 100) + 
                                                         ($nilai_penguji_display * $bobot_penguji / 100) + 
                                                         ($nilai_instansi_display * $bobot_instansi / 100);
                            @endphp
                            
                            <div class="row">
                                <div class="col-md-4"><strong>Nilai Akhir TA</strong></div>
                                <div class="col-md-8">
                                    @if($nilai_akhir_calculated > 0)
                                        <h4 class="text-success"><b>{{ number_format($nilai_akhir_calculated, 2) }}</b></h4>
                                    @else
                                        <h4 class="text-muted"><b>0.00</b></h4>
                                    @endif
                                    <small class="text-muted">
                                        Formula: (Pembimbing × {{ $bobot_pembimbing }}%) + (Penguji × {{ $bobot_penguji }}%) + (Instansi × {{ $bobot_instansi }}%)
                                    </small>
                                </div>
                            </div>
                            <hr>

                            <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir</strong></h5>
                            <hr>

                            @if($is_admin && Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                {{-- SUPER ADMIN: Semua dokumen --}}
                                <div class="mt-3">
                                    @if($jilid->lembar_pengesahan)
                                    <a href="{{ storage_url($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR PENGESAHAN</a>
                                    @endif
                                    @if($jilid->lembar_keaslian)
                                    <a href="{{ storage_url($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR KEASLIAN</a>
                                    @endif
                                    @if($jilid->lembar_persetujuan_pembimbing)
                                    <a href="{{ storage_url($jilid->lembar_persetujuan_pembimbing) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR PERSETUJUAN PEMBIMBING</a>
                                    @endif
                                    @if($jilid->lembar_persetujuan_penguji)
                                    <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR PERSETUJUAN PENGUJI</a>
                                    @endif
                                    @if($jilid->lembar_bimbingan)
                                    <a href="{{ storage_url($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR BIMBINGAN</a>
                                    @endif
                                    @if($jilid->lembar_revisi)
                                    <a href="{{ storage_url($jilid->lembar_revisi) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LEMBAR REVISI</a>
                                    @endif
                                    @if($jilid->laporan_pdf)
                                    <a href="{{ storage_url($jilid->laporan_pdf) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT PDF</a>
                                    @endif
                                    @if($jilid->laporan_word)
                                    <a href="{{ storage_url($jilid->laporan_word) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> LAPORAN FORMAT WORD</a>
                                    @endif
                                    @if($jilid->artikel)
                                    <a href="{{ storage_url($jilid->artikel) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> ARTIKEL FORMAT WORD</a>
                                    @endif
                                    @if($jilid->panduan)
                                    <a href="{{ storage_url($jilid->panduan) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> PANDUAN PENGGUNAAN PRODUK TA</a>
                                    @endif
                                    @if($jilid->lampiran)
                                    <a href="{{ storage_url($jilid->lampiran) }}" class="btn btn-primary mb-3" target="_blank"><i class="fas fa-download"></i> DOKUMEN LAMPIRAN</a>
                                    @endif
                                    @if($jilid->link_project)
                                    <a href="{{ $jilid->link_project }}" class="btn btn-secondary mb-3" target="_blank"><i class="fas fa-paper-plane"></i> LINK PROJECT</a>
                                    @endif
                                    @if($jilid->file_artikel)
                                    <a href="{{ storage_url($jilid->file_artikel) }}" class="btn btn-info mb-3" target="_blank"><i class="fas fa-download"></i> FILE ARTIKEL</a>
                                    @endif
                                    @if($jilid->file_loa)
                                    <a href="{{ storage_url($jilid->file_loa) }}" class="btn btn-info mb-3" target="_blank"><i class="fas fa-download"></i> FILE LOA</a>
                                    @endif
                                    @if($jilid->status_artikel)
                                    <span class="badge badge-warning mb-3 p-2" style="font-size:0.9rem"><i class="fas fa-tag"></i> Status Artikel: {{ ucfirst($jilid->status_artikel) }}</span>
                                    @endif
                                    @if($jilid->link_artikel)
                                    <a href="{{ $jilid->link_artikel }}" class="btn btn-secondary mb-3" target="_blank"><i class="fas fa-external-link-alt"></i> LINK ARTIKEL</a>
                                    @endif
                                    @if($jilid->nama_jurnal)
                                    <span class="badge badge-info mb-3 p-2" style="font-size:0.9rem"><i class="fas fa-book-open"></i> Jurnal: {{ $jilid->nama_jurnal }}</span>
                                    @endif
                                    @if($jilid->kategori_jurnal)
                                    <span class="badge badge-secondary mb-3 p-2" style="font-size:0.9rem"><i class="fas fa-bookmark"></i> Kategori: {{ $jilid->kategori_jurnal }}</span>
                                    @endif
                                </div>
                            @elseif ($is_admin && Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_ADMIN_FOTOCOPY)
                                {{-- ADMIN FOTOKOPI: Hanya PDF --}}
                                @if($jilid->laporan_pdf)
                                <a href="{{ storage_url($jilid->laporan_pdf) }}" class="btn btn-primary mb-3 mt-4" target="_blank"><i class="fas fa-download"></i> LAPORAN PDF</a>
                                @endif
                            @else
                                {{-- VIEW PRODI / MAHASISWA --}}
                                @if($jilid->lembar_pengesahan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Pengesahan (TTD)</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_pengesahan) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_keaslian)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Keaslian</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_keaslian) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_persetujuan_pembimbing)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Pembimbing</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_pembimbing) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_persetujuan_penguji)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Persetujuan Penguji</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_bimbingan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Bimbingan TA</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_bimbingan) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lembar_revisi)
                                <div class="row mb-2">
                                    <div class="col-md-4">Lembar Revisi (ACC Penguji)</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lembar_revisi) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->laporan_pdf)
                                <div class="row mb-2">
                                    <div class="col-md-4">Laporan TA (PDF)</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->laporan_pdf, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->laporan_pdf }}" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> Buka Link Google Drive</a>
                                        @else
                                            <a href="{{ storage_url($jilid->laporan_pdf) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
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
                                            <a href="{{ $jilid->laporan_word }}" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> Buka Link Google Drive</a>
                                        @else
                                            <a href="{{ storage_url($jilid->laporan_word) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
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
                                            <a href="{{ $jilid->artikel }}" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> Buka Link Google Drive</a>
                                        @else
                                            <a href="{{ storage_url($jilid->artikel) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->panduan)
                                <div class="row mb-2">
                                    <div class="col-md-4">Panduan Penggunaan Produk TA</div>
                                    <div class="col-md-8">
                                        @if(filter_var($jilid->panduan, FILTER_VALIDATE_URL))
                                            <a href="{{ $jilid->panduan }}" target="_blank" class="text-primary"><i class="fas fa-external-link-alt"></i> Buka Link Google Drive</a>
                                        @else
                                            <a href="{{ storage_url($jilid->panduan) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->lampiran)
                                <div class="row mb-2">
                                    <div class="col-md-4">Dokumen Lampiran</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->lampiran) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->link_project)
                                <div class="row mb-2">
                                    <div class="col-md-4">Link Project TA</div>
                                    <div class="col-md-8">
                                        <a href="{{ $jilid->link_project }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                                    </div>
                                </div>
                                @endif

                                @if($jilid->file_artikel)
                                <div class="row mb-2">
                                    <div class="col-md-4">File Artikel</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->file_artikel) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->file_loa)
                                <div class="row mb-2">
                                    <div class="col-md-4">File LoA / Letter of Acceptance</div>
                                    <div class="col-md-8">
                                        <a href="{{ storage_url($jilid->file_loa) }}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Buka File</a>
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
                                        <a href="{{ $jilid->link_artikel }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt"></i> Buka Link</a>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->nama_jurnal)
                                <div class="row mb-2">
                                    <div class="col-md-4">Nama Jurnal</div>
                                    <div class="col-md-8">
                                        <strong>{{ $jilid->nama_jurnal }}</strong>
                                    </div>
                                </div>
                                <hr>
                                @endif

                                @if($jilid->kategori_jurnal)
                                <div class="row mb-2">
                                    <div class="col-md-4">Kategori Jurnal</div>
                                    <div class="col-md-8">
                                        <span class="badge badge-secondary p-2">{{ $jilid->kategori_jurnal }}</span>
                                    </div>
                                </div>
                                @endif
                            @endif

                            {{-- Form aksi admin (validasi / pembayaran) --}}
                            @if($is_admin && ($jilid->status == 1 || $jilid->status == 3))
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
                            @endif
                        </div>
                    </div>

                    {{-- Card Revisi --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">{{ count($revisis) }}</span>
                            </h3>
                            <div class="card-tools">
                                {{ $revisis->links() }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                @forelse ($revisis as $revisi)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:i a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br(e($revisi->catatan)) !!}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Belum ada revisi</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection