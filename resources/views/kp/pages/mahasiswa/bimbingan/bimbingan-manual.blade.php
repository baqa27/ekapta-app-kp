@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.bimbingan.mahasiswa') }}">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">{{ $title }}</h3>
                            @if($dosen)
                            <h3 class="card-title flex-shrink-0">{{ $dosen->nama . ', ' . $dosen->gelar }}</h3>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Form Submit Bimbingan Manual --}}
                            @if($bimbingan->status != 'diterima')
                                <form action="{{ route('kp.bimbingan-manual.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="bimbingan_id" value="{{ $bimbingan->id }}">

                                    <div class="form-group">
                                        <label>Bagian Bimbingan</label>
                                        <input type="text" class="form-control" value="{{ $bimbingan->bagian->bagian ?? '-' }}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>File Laporan</label>
                                        <div>
                                            @if($bimbingan->lampiran)
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($bimbingan->lampiran) }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-paperclip mr-1"></i>{{ basename($bimbingan->lampiran) }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_lembar_bimbingan">Lembar Bimbingan Manual (Format: PDF, PNG, JPG, JPEG | Max: 5MB)</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('foto_lembar_bimbingan') is-invalid @enderror"
                                                    name="foto_lembar_bimbingan" accept=".pdf,.jpeg,.png,.jpg" required>
                                                <label class="custom-file-label" for="foto_lembar_bimbingan">Pilih file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Dokumen</span>
                                            </div>
                                        </div>
                                        @error('foto_lembar_bimbingan')
                                            <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Bimbingan Offline</label>
                                        <input type="date" name="tanggal_bimbingan" class="form-control @error('tanggal_bimbingan') is-invalid @enderror"
                                            value="{{ old('tanggal_bimbingan', date('Y-m-d')) }}" required>
                                        @error('tanggal_bimbingan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Status Hasil Bimbingan Offline</label>
                                        <select name="status_mahasiswa" class="form-control @error('status_mahasiswa') is-invalid @enderror" required>
                                            <option value="acc" {{ old('status_mahasiswa') == 'acc' ? 'selected' : '' }}>ACC - Dosen menyetujui</option>
                                            <option value="revisi" {{ old('status_mahasiswa') == 'revisi' ? 'selected' : '' }}>Revisi - Masih ada perbaikan</option>
                                        </select>
                                        @error('status_mahasiswa')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Catatan <span class="text-danger">*</span></label>
                                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" placeholder="Catatan hasil bimbingan offline dengan dosen..." required>{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-4">
                                        <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Bimbingan {{ $bimbingan->bagian->bagian ?? '' }} sudah di-ACC. Silahkan lanjutkan ke bab berikutnya.
                                </div>

                                <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- History Pengajuan Bimbingan Manual --}}
                    @if($history->count() > 0)
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Pengajuan Bimbingan Manual</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="tableRiwayatManual" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status Bimbingan</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Tanggal Ajuan</th>
                                        <th>Status Ajuan</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($item->status_mahasiswa == 'acc')
                                                <span class="badge badge-success">ACC</span>
                                            @else
                                                <span class="badge badge-warning">Revisi</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d M Y') }}</td>
                                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $item->getStatusBadgeClass() }}">
                                                {{ $item->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm shadow" data-toggle="modal" data-target="#modalDetail-{{ $item->id }}">
                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                            </button>

                                            <!-- Modal Detail Bimbingan Manual -->
                                            <div class="modal fade" id="modalDetail-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel-{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content text-left">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="modalDetailLabel-{{ $item->id }}">
                                                                <i class="fas fa-file-alt mr-2 text-primary"></i> Detail Pengajuan Bimbingan Manual
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td width="50%"><strong>Status Hasil Dosen</strong></td>
                                                                            <td>: 
                                                                                @if($item->status_mahasiswa == 'acc')
                                                                                    <span class="badge badge-success">ACC</span>
                                                                                @else
                                                                                    <span class="badge badge-warning">Revisi</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Status Verifikasi</strong></td>
                                                                            <td>: 
                                                                                <span class="badge {{ $item->getStatusBadgeClass() }}">
                                                                                    {{ $item->getStatusLabel() }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tr>
                                                                            <td width="50%"><strong>Tanggal Bimbingan</strong></td>
                                                                            <td>: {{ \Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d F Y') }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Tanggal Ajuan</strong></td>
                                                                            <td>: {{ $item->created_at->format('d F Y H:i') }}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            
                                                            <hr class="my-3">
                                                            
                                                            <div class="row mb-3">
                                                                <div class="col-md-6 mb-3 mb-md-0">
                                                                    <div class="card bg-light h-100 mb-0">
                                                                        <div class="card-header">
                                                                            <h5 class="card-title mb-0"><i class="fas fa-file-pdf mr-2"></i>File Laporan</h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            @if($item->bimbingan && $item->bimbingan->lampiran)
                                                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->bimbingan->lampiran) }}" target="_blank" class="text-primary">
                                                                                    <i class="fas fa-paperclip mr-1"></i>{{ basename($item->bimbingan->lampiran) }}
                                                                                </a>
                                                                            @else
                                                                                <span class="text-muted">-</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="card bg-light h-100 mb-0">
                                                                        <div class="card-header">
                                                                            <h5 class="card-title mb-0"><i class="fas fa-file-image mr-2 text-success"></i>Lembar Bimbingan Manual</h5>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            @if($item->foto_lembar_bimbingan && $item->foto_lembar_bimbingan != '0')
                                                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->foto_lembar_bimbingan) }}" target="_blank" class="text-success">
                                                                                    <i class="fas fa-paperclip mr-1"></i>{{ basename($item->foto_lembar_bimbingan) }}
                                                                                </a>
                                                                            @else
                                                                                <span class="text-muted">Tidak ada file</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <div class="card bg-light mb-0">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title mb-0"><i class="fas fa-comment mr-2 text-primary"></i>Catatan Hasil Bimbingan (Mahasiswa)</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-0" style="white-space: pre-line;">{{ $item->keterangan ?? '-' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div>
                                                                <div class="card bg-light mb-0">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title mb-0"><i class="fas fa-comment-dots mr-2 text-secondary"></i>Catatan Verifikasi (Admin/Prodi)</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p class="mb-0" style="white-space: pre-line;">{{ $item->catatan_reviewer ?? 'Belum ada catatan verifikasi' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // DataTable untuk Riwayat Pengajuan Bimbingan Manual
    $(document).ready(function() {
        if ($('#tableRiwayatManual').length && !$.fn.DataTable.isDataTable('#tableRiwayatManual')) {
            $('#tableRiwayatManual').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Next",
                        "previous": "Previous"
                    },
                    "emptyTable": "Tidak ada data tersedia"
                }
            });
        }
    });
</script>
@endpush
