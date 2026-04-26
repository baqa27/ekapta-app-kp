@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">Detail Bimbingan Manual</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Card Utama dengan Ribbon Status -->
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon {{ $ajuan->status == 'acc' ? 'bg-success' : ($ajuan->status == 'pending' ? 'bg-secondary' : 'bg-warning') }}">
                                {{ $ajuan->getStatusLabel() }}
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Info Mahasiswa & Bimbingan -->
                            <table>
                                <tr>
                                    <td width="150"><b>NIM</b></td>
                                    <td>{{ $mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b>Nama</b></td>
                                    <td>{{ $mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b>Prodi</b></td>
                                    <td>{{ $mahasiswa->prodi }}</td>
                                </tr>
                                @if($dosen)
                                <tr>
                                    <td><b>Pembimbing</b></td>
                                    <td>{{ $dosen->nama }}, {{ $dosen->gelar }}</td>
                                </tr>
                                @endif
                                @if($pengajuan)
                                <tr>
                                    <td><b>Judul KP</b></td>
                                    <td>{{ $pengajuan->judul }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><b>Bagian</b></td>
                                    <td>{{ $bimbingan->bagian->bagian ?? '-' }}</td>
                                </tr>
                            </table>

                            <hr>

                            <p><b>Status Bimbingan</b></p>
                            <p>
                                @if($ajuan->status_mahasiswa == 'acc')
                                    <span class="badge badge-success">ACC</span>
                                @else
                                    <span class="badge badge-warning">Revisi</span>
                                @endif
                            </p>

                            <hr>

                            <!-- Catatan Pembimbing -->
                            <p><b>Catatan Pembimbing</b></p>
                            <p>{!! nl2br(e($ajuan->keterangan ?? '-')) !!}</p>

                            @if($ajuan->catatan_reviewer)
                            <hr>
                            <p><b class="text-{{ $ajuan->status == 'acc' ? 'success' : 'warning' }}">Catatan Reviewer</b></p>
                            <p>{{ $ajuan->catatan_reviewer }}</p>
                            @endif

                            <hr>

                            <!-- Dokumen -->
                            <p><b>Dokumen</b></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><b>File Laporan:</b></p>
                                    @if($bimbingan->lampiran)
                                        <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($bimbingan->lampiran) }}" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> {{ basename($bimbingan->lampiran) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><b>Lembar Bimbingan:</b></p>
                                    @if($ajuan->foto_lembar_bimbingan && $ajuan->foto_lembar_bimbingan != '0')
                                        <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($ajuan->foto_lembar_bimbingan) }}" target="_blank">
                                            <i class="fas fa-file-alt mr-1"></i> {{ basename($ajuan->foto_lembar_bimbingan) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="mt-3 text-secondary">
                                <i class="fas fa-calendar mr-2"></i> Tanggal Bimbingan: {{ \Carbon\Carbon::parse($ajuan->tanggal_bimbingan)->format('d M Y') }}
                            </div>
                            <div class="text-secondary">
                                <i class="fas fa-clock mr-2"></i> Tanggal Submit: {{ $ajuan->created_at->format('d M Y H:i') }}
                            </div>
                            @if($ajuan->tanggal_review)
                            <div class="text-success">
                                <i class="fas fa-calendar-check mr-2"></i> Tanggal Review: {{ $ajuan->tanggal_review->format('d M Y H:i') }}
                            </div>
                            @endif
                        </div>

                        <!-- Footer dengan Tombol Aksi -->
                        @if($ajuan->status == 'pending')
                        <div class="card-footer">
                            <div class="d-flex">
                                <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-acc">
                                    <i class="fas fa-check mr-2"></i> ACC Pengajuan
                                </button>
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#modal-tolak">
                                    <i class="fas fa-times mr-2"></i> Tolak Pengajuan
                                </button>
                            </div>
                        </div>
                        @elseif($ajuan->status == 'acc')
                        <div class="card-footer">
                            <form action="{{ route('kp.bimbingan-manual.cancel-acc') }}" method="post" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin membatalkan ACC? Status akan kembali ke pending.')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $ajuan->id }}">
                                <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo mr-2"></i> Batalkan ACC
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Card Riwayat -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <strong>Riwayat Pengajuan</strong>
                                <span class="badge bg-danger rounded-pill">{{ count($history) }}</span>
                            </h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table id="tableRiwayatManualAdmin" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl. Bimbingan</th>
                                        <th>Status</th>
                                        <th>Catatan Pembimbing</th>
                                        <th>File</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $index => $item)
                                    <tr class="{{ $item->id == $ajuan->id ? 'bg-light' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_bimbingan)->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $item->getStatusBadgeClass() }}">
                                                {{ $item->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $item->keterangan ? Str::limit($item->keterangan, 40) : '-' }}</td>
                                        <td>
                                            @if($item->bimbingan && $item->bimbingan->lampiran)
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->bimbingan->lampiran) }}"
                                                   target="_blank" class="btn btn-xs btn-info" title="Laporan">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if($item->foto_lembar_bimbingan && $item->foto_lembar_bimbingan != '0')
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($item->foto_lembar_bimbingan) }}"
                                                   target="_blank" class="btn btn-xs btn-success ml-1" title="Lembar">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->id != $ajuan->id)
                                            <a href="{{ route('kp.bimbingan-manual.review.detail', $item->id) }}"
                                               class="btn btn-xs btn-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @else
                                            <span class="badge badge-primary">Saat ini</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal ACC -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bimbingan-manual.acc') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ajuan->id }}">
                    <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">ACC Pengajuan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal ACC <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_acc" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Status Bimbingan <span class="text-danger">*</span></label>
                            <select name="status_mahasiswa" class="form-control" required>
                                <option value="acc" {{ $ajuan->status_mahasiswa == 'acc' ? 'selected' : '' }}>ACC - Dosen menyetujui</option>
                                <option value="revisi" {{ $ajuan->status_mahasiswa == 'revisi' ? 'selected' : '' }}>Revisi - Masih ada perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan catatan pembimbing..." required>{{ $ajuan->keterangan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Catatan Reviewer <span class="text-danger">*</span></label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check mr-2"></i> Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="modal-tolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bimbingan-manual.revisi') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ajuan->id }}">
                    <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Tolak Pengajuan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status Bimbingan <span class="text-danger">*</span></label>
                            <select name="status_mahasiswa" class="form-control" required>
                                <option value="acc" {{ $ajuan->status_mahasiswa == 'acc' ? 'selected' : '' }}>ACC - Dosen menyetujui</option>
                                <option value="revisi" {{ $ajuan->status_mahasiswa == 'revisi' ? 'selected' : '' }}>Revisi - Masih ada perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan Pembimbing <span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Masukkan catatan pembimbing..." required>{{ $ajuan->keterangan }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Alasan penolakan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if ($('#tableRiwayatManualAdmin').length && !$.fn.DataTable.isDataTable('#tableRiwayatManualAdmin')) {
        $('#tableRiwayatManualAdmin').DataTable({
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
