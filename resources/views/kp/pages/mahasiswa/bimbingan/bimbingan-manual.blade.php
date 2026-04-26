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
                            <table id="tableRiwayatManual" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status Bimbingan</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>File Laporan</th>
                                        <th>Tanggal Ajuan</th>
                                        <th>Status Ajuan</th>
                                        <th>Catatan Pembimbing</th>
                                        <th>Catatan Ajuan</th>
                                        <th>File Lembar Bimbingan</th>
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
                                        <td>
                                            @if($item->bimbingan && $item->bimbingan->lampiran)
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->bimbingan->lampiran) }}" target="_blank"
                                                   class="text-info" title="File Laporan">
                                                    <i class="fas fa-file-pdf mr-1"></i>{{ basename($item->bimbingan->lampiran) }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $item->getStatusBadgeClass() }}">
                                                {{ $item->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $item->keterangan ? Str::limit($item->keterangan, 30) : '-' }}</td>
                                        <td>
                                            @if($item->catatan_reviewer)
                                                {{ Str::limit($item->catatan_reviewer, 30) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->foto_lembar_bimbingan && $item->foto_lembar_bimbingan != '0')
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->foto_lembar_bimbingan) }}" target="_blank"
                                                   class="text-primary" title="Lembar Bimbingan">
                                                    <i class="fas fa-file-alt mr-1"></i>{{ basename($item->foto_lembar_bimbingan) }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
