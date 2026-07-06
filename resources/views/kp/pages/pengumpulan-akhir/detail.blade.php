@extends($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'kp.layouts.dashboard' : 'kp.layouts.dashboardFotokopi') : 'kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route($is_admin ? 'kp.pengumpulan-akhir.index' : 'kp.pengumpulan-akhir.mahasiswa') }}">Jilid KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
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
                                // Cek apakah user adalah admin fotokopi
                                $is_fotokopi = $is_admin && Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_ADMIN_FOTOCOPY;
                                
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
                                        } elseif($jilid->nilai_pembimbing) {
                                            $nilai_pembimbing = $jilid->nilai_pembimbing;
                                        } elseif($mahasiswa->jilidKP && $mahasiswa->jilidKP->nilai_pembimbing) {
                                            $nilai_pembimbing = $mahasiswa->jilidKP->nilai_pembimbing;
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

                            <h5 class="mt-3"><strong>Dokumen Pengumpulan Akhir</strong></h5>
                            <hr>

                            @if($is_fotokopi)
                                {{-- Tampilan Sederhana untuk Admin Fotokopi: Hanya Laporan PDF --}}
                                <div class="row mb-3">
                                    <div class="col-md-12">
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
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <strong>Laporan KP (PDF) belum diupload.</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- Tampilan Lengkap untuk Super Admin dan Mahasiswa --}}
                                @php
                                    $has_documents = $jilid->lembar_keaslian || $jilid->lembar_persetujuan_pembimbing || 
                                                    $jilid->lembar_persetujuan_penguji || $jilid->lembar_pengesahan || 
                                                    $jilid->lembar_bimbingan || $jilid->lembar_revisi || 
                                                    $jilid->laporan_pdf || $jilid->laporan_word || $jilid->artikel || 
                                                    $jilid->file_project || $jilid->link_project || $jilid->form_nilai_kp || 
                                                    $jilid->berita_acara || $jilid->panduan || $jilid->bukti_nilai_instansi || 
                                                    $jilid->lampiran;
                                @endphp

                                @if(!$has_documents)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Belum ada dokumen yang diupload.</strong>
                                    @if(!$is_admin && $jilid->status == 2)
                                    <br>Silahkan upload dokumen melalui halaman <a href="{{ route('kp.pengumpulan-akhir.edit', $jilid->id) }}">Revisi Pengumpulan Akhir</a>.
                                    @endif
                                </div>
                                @endif

                            {{-- Dokumen Utama --}}
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
                                <div class="col-md-4">Lembar Persetujuan Pembimbing (TTD)</div>
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
                                <div class="col-md-4">Lembar Persetujuan Penguji (TTD)</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lembar_persetujuan_penguji) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_persetujuan_penguji) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
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

                            {{-- Laporan KP --}}
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

                            @if($jilid->artikel)
                            <div class="row">
                                <div class="col-md-4">Artikel KP (Word)</div>
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

                            {{-- Dokumen Pendukung --}}
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

                            @if($jilid->bukti_nilai_instansi)
                            <div class="row">
                                <div class="col-md-4">Bukti Nilai Instansi</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->bukti_nilai_instansi) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->bukti_nilai_instansi) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lampiran)
                            <div class="row">
                                <div class="col-md-4">Dokumen Lampiran Lainnya</div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($jilid->lampiran) }}" target="_blank" class="text-primary">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lampiran) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif
                            @endif
                            {{-- End of document display --}}

                            @if ($is_admin && ($jilid->status == 1 || $jilid->status == 3))
                            <form action="{{ route('kp.pengumpulan-akhir.acc', $jilid->id) }}" method="post">
                                @method('put')
                                @csrf
                                @if ($jilid->status == 3)
                                    <input type="hidden" name="status" value="4">
                                    <div class="form-group">
                                        <label>Jumlah Pembayaran (Opsional)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran jilid KP"
                                                value="{{ $jilid->total_pembayaran }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> Selesaikan Proses Jilid
                                    </button>
                                @elseif ($jilid->status == 1)
                                    <div class="form-group">
                                        <label>Status Verifikasi <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="3">VALID - Dokumen lengkap dan benar</option>
                                            <option value="2">TIDAK VALID - Perlu revisi</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan <span class="text-danger">*</span></label>
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Berikan catatan untuk mahasiswa..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i> Simpan Verifikasi
                                    </button>
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
                                            <span class="direct-chat-name float-left">Admin/Fotokopi</span>
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



