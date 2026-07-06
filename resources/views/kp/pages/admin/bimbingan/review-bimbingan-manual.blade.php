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
                        <li class="breadcrumb-item"><a href="#">KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info Alert -->
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Bimbingan Manual KP</h5>
                <p class="mb-0">
                    Review pengajuan lembar bimbingan manual dari mahasiswa. Cocokkan file laporan dengan lembar bimbingan yang diupload.
                    Status final ditentukan oleh hasil review Anda.
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $ajuan_pending->count() }}</h3>
                            <p>Menunggu Review</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $ajuan_acc->count() }}</h3>
                            <p>ACC</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $ajuan_revisi->count() }}</h3>
                            <p>Revisi/Ditolak</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Card -->
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="review-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pending-tab" data-toggle="pill" href="#pending" role="tab">
                                <i class="fas fa-clock mr-1"></i> Pending 
                                @if($ajuan_pending->count() > 0)
                                    <span class="badge badge-warning">{{ $ajuan_pending->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="acc-tab" data-toggle="pill" href="#acc" role="tab">
                                <i class="fas fa-check mr-1"></i> ACC
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="revisi-tab" data-toggle="pill" href="#revisi" role="tab">
                                <i class="fas fa-redo mr-1"></i> Revisi/Ditolak
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="review-tabs-content">
                        <!-- Tab Pending -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            @if($ajuan_pending->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="40">No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Bagian</th>
                                            <th>Tgl. Bimbingan</th>
                                            <th>Status Bimbingan</th>
                                            <th>Tgl. Submit</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ajuan_pending as $index => $ajuan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $ajuan->mahasiswa->nim }}</td>
                                            <td>{{ $ajuan->mahasiswa->nama }}</td>
                                            <td>{{ $ajuan->bimbingan->bagian->bagian ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($ajuan->tanggal_bimbingan)->format('d M Y') }}</td>
                                            <td>
                                                @if($ajuan->status_mahasiswa == 'acc')
                                                    <span class="badge badge-success">ACC</span>
                                                @else
                                                    <span class="badge badge-warning">Revisi</span>
                                                @endif
                                            </td>
                                            <td>{{ $ajuan->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('kp.bimbingan-manual.review.detail', $ajuan->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-check-circle"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-4x mb-3"></i>
                                <p>Tidak ada pengajuan yang menunggu review</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tab ACC -->
                        <div class="tab-pane fade" id="acc" role="tabpanel">
                            @if($ajuan_acc->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="40">No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Bagian</th>
                                            <th>Tgl. Bimbingan</th>
                                            <th>Tgl. ACC</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ajuan_acc as $index => $ajuan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $ajuan->mahasiswa->nim }}</td>
                                            <td>{{ $ajuan->mahasiswa->nama }}</td>
                                            <td>{{ $ajuan->bimbingan->bagian->bagian ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($ajuan->tanggal_bimbingan)->format('d M Y') }}</td>
                                            <td>{{ $ajuan->tanggal_review ? $ajuan->tanggal_review->format('d M Y H:i') : '-' }}</td>
                                            <td>
                                                <a href="{{ route('kp.bimbingan-manual.review.detail', $ajuan->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fa-4x mb-3"></i>
                                <p>Belum ada pengajuan yang di-ACC</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tab Revisi/Ditolak -->
                        <div class="tab-pane fade" id="revisi" role="tabpanel">
                            @if($ajuan_revisi->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="40">No</th>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Bagian</th>
                                            <th>Status</th>
                                            <th>Tgl. Review</th>
                                            <th>Catatan</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ajuan_revisi as $index => $ajuan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $ajuan->mahasiswa->nim }}</td>
                                            <td>{{ $ajuan->mahasiswa->nama }}</td>
                                            <td>{{ $ajuan->bimbingan->bagian->bagian ?? '-' }}</td>
                                            <td>
                                                <span class="badge {{ $ajuan->getStatusBadgeClass() }}">
                                                    {{ $ajuan->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td>{{ $ajuan->tanggal_review ? $ajuan->tanggal_review->format('d M Y H:i') : '-' }}</td>
                                            <td><small>{{ Str::limit($ajuan->catatan_reviewer, 40) }}</small></td>
                                            <td>
                                                <a href="{{ route('kp.bimbingan-manual.review.detail', $ajuan->id) }}" 
                                                   class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-redo fa-4x mb-3"></i>
                                <p>Tidak ada pengajuan yang direvisi/ditolak</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
@endpush
