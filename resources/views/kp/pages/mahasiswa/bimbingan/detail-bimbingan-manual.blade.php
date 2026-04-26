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
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-2"></i>
                                Detail Pengajuan Bimbingan Manual
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Info Bimbingan -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Bagian</strong></td>
                                            <td>: {{ $ajuan->bimbingan->bagian->bagian ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Bimbingan</strong></td>
                                            <td>: {{ \Carbon\Carbon::parse($ajuan->tanggal_bimbingan)->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Submit</strong></td>
                                            <td>: {{ $ajuan->created_at->format('d F Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="40%"><strong>Status Mahasiswa</strong></td>
                                            <td>:
                                                @if($ajuan->status_mahasiswa == 'acc')
                                                    <span class="badge badge-success">ACC</span>
                                                @else
                                                    <span class="badge badge-warning">Revisi</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Review</strong></td>
                                            <td>: <span class="badge {{ $ajuan->getStatusBadgeClass() }}">{{ $ajuan->getStatusLabel() }}</span></td>
                                        </tr>
                                        @if($ajuan->tanggal_review)
                                        <tr>
                                            <td><strong>Tanggal Review</strong></td>
                                            <td>: {{ $ajuan->tanggal_review->format('d F Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <!-- File Laporan -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0"><i class="fas fa-file-pdf mr-2"></i>File Laporan</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($ajuan->bimbingan->lampiran)
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($ajuan->bimbingan->lampiran) }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-paperclip mr-1"></i>{{ basename($ajuan->bimbingan->lampiran) }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0"><i class="fas fa-file-alt mr-2"></i>Lembar Bimbingan Manual</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($ajuan->foto_lembar_bimbingan && $ajuan->foto_lembar_bimbingan != '0')
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($ajuan->foto_lembar_bimbingan) }}" target="_blank" class="text-success">
                                                    <i class="fas fa-paperclip mr-1"></i>{{ basename($ajuan->foto_lembar_bimbingan) }}
                                                </a>
                                                <br><br>
                                                <a href="{{ \App\Helpers\StorageHelper::kpFileUrl($ajuan->foto_lembar_bimbingan) }}" target="_blank" class="btn btn-info">
                                                    <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada file</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            @if($ajuan->keterangan)
                            <div class="mb-4">
                                <h6><strong>Keterangan Mahasiswa:</strong></h6>
                                <p class="text-muted">{{ $ajuan->keterangan }}</p>
                            </div>
                            @endif

                            <!-- Catatan Reviewer -->
                            @if($ajuan->catatan_reviewer)
                            <div class="alert {{ $ajuan->status == 'acc' ? 'alert-success' : 'alert-warning' }}">
                                <h6><i class="icon fas fa-comment"></i> Catatan dari Admin/Prodi:</h6>
                                <p class="mb-0">{{ $ajuan->catatan_reviewer }}</p>
                            </div>
                            @endif

                            <!-- Tombol -->
                            <div class="mt-4">
                                <a href="{{ route('kp.bimbingan-manual.create', $ajuan->bimbingan_id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
