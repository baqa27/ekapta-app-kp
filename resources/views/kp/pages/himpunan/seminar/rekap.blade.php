@extends('kp.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.dashboard.himpunan') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">

                        {{-- Header card: judul kiri + 2 tombol navigasi rekap kanan --}}
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Rekap Seminar KP</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    {{-- Tombol aktif: Rekap Semua --}}
                                    <a class="nav-link active" href="{{ route('kp.seminar.himpunan.rekap') }}">
                                        <i class="fas fa-list mr-1"></i> Rekap Semua
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('kp.seminar.himpunan.rekap.bulanan') }}">
                                        <i class="fas fa-calendar-alt mr-1"></i> Rekap Bulanan
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Prodi</th>
                                        <th>Judul KP</th>
                                        <th>Status</th>
                                        <th>Tgl Validasi (ACC)</th>
                                        <th>Tanggal Seminar</th>
                                        <th>Tempat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($seminars as $seminar)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $seminar->mahasiswa->nim ?? '-' }}</td>
                                            <td>{{ $seminar->mahasiswa->nama ?? '-' }}</td>
                                            <td>{{ $seminar->mahasiswa->prodi ?? '-' }}</td>
                                            <td>{{ $seminar->pengajuan->judul ?? '-' }}</td>
                                            <td>
                                                @if ($seminar->is_valid == 0)
                                                    <span class="badge bg-secondary">Review</span>
                                                @elseif ($seminar->is_valid == 1)
                                                    <span class="badge bg-success">Diterima</span>
                                                @elseif ($seminar->is_valid == 2)
                                                    <span class="badge bg-warning">Revisi</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $seminar->tanggal_acc
                                                    ? \Carbon\Carbon::parse($seminar->tanggal_acc)->translatedFormat('d M Y')
                                                    : '-' }}
                                            </td>
                                            <td>{{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-' }}</td>
                                            <td>{{ $seminar->tempat_ujian ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('kp.seminar.himpunan.review', $seminar->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </a>
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
@endsection
