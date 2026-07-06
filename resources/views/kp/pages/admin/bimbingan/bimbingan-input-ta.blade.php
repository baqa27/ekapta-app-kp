@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
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
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>BAB</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @forelse ($bimbingans_review ?? [] as $bimbingan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $bimbingan->mahasiswa->nama ?? '-' }} - {{ $bimbingan->mahasiswa->nim ?? '-' }}</td>
                                            <td>{{ $bimbingan->bagian->bagian ?? '-' }}</td>
                                            <td>{{ $bimbingan->tanggal_bimbingan ? \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d M Y') : '-' }}</td>
                                            <td>
                                                @if($bimbingan->status == 'review')
                                                    <span class="badge bg-secondary">Review</span>
                                                @elseif($bimbingan->status == 'diterima')
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif($bimbingan->status == 'revisi')
                                                    <span class="badge bg-warning">Revisi</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $bimbingan->status ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $dosen = $bimbingan->mahasiswa->dosens->first(function($d) {
                                                        return $d->pivot->status == 'pembimbing' || $d->pivot->status == 'utama';
                                                    });
                                                @endphp
                                                @if($dosen)
                                                    <a href="{{ route($createRoute ?? 'kp.bimbingan.admin.input.create.ta', [$dosen->id, $bimbingan->mahasiswa->id]) }}"
                                                       class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data bimbingan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>BAB</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>

    </section>
    <!-- /.content -->
@endsection
