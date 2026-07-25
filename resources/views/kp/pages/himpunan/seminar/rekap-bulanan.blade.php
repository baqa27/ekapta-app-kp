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
                        <li class="breadcrumb-item"><a href="{{ route('kp.seminar.himpunan.rekap') }}">Rekap Seminar KP</a></li>
                        <li class="breadcrumb-item active">Rekap Bulanan</li>
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
                            <h3 class="card-title p-3">Rekap Seminar KP Bulanan</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('kp.seminar.himpunan.rekap') }}">
                                        <i class="fas fa-list mr-1"></i> Rekap Semua
                                    </a>
                                </li>
                                <li class="nav-item">
                                    {{-- Tab Aktif Rekap Bulanan --}}
                                    <a class="nav-link active" href="javascript:void(0);">
                                        <i class="fas fa-calendar-alt mr-1"></i> Rekap Bulanan
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Filter Bulan & Tahun (Auto-submit saat opsi diganti) --}}
                        <div class="card-body border-bottom pb-3">
                            <form method="GET" action="{{ route('kp.seminar.himpunan.rekap.bulanan') }}" class="form-inline">
                                <div class="form-group mr-3 mb-2">
                                    <label class="mr-2 font-weight-bold">Bulan:</label>
                                    <select name="bulan" class="form-control form-control-sm" onchange="this.form.submit()">
                                        @foreach($namaBulan as $num => $nama)
                                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-3 mb-2">
                                    <label class="mr-2 font-weight-bold">Tahun:</label>
                                    <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                                        @foreach($tahunTersedia as $thn)
                                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                                {{ $thn }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mb-2">
                                    <i class="fas fa-search mr-1"></i> Tampilkan
                                </button>
                            </form>
                        </div>

                        {{-- Info rekap terpilih --}}
                        <div class="card-body pt-2 pb-1">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Menampilkan seminar yang <strong>divalidasi/di-ACC oleh Himpunan</strong>
                                pada bulan <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>
                                — Ditemukan: <strong>{{ count($seminars) }}</strong> mahasiswa
                            </div>
                        </div>

                        <div class="card-body table-responsive">
                            <table id="tblRekapBulanan" class="table table-bordered table-striped">
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
                                    @forelse ($seminars as $index => $seminar)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
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
                                                    ? \Carbon\Carbon::parse($seminar->tanggal_acc)->translatedFormat('d M Y H:i')
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
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                                Tidak ada seminar yang divalidasi pada
                                                <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($seminars) > 0)
                                <tfoot>
                                    <tr class="font-weight-bold bg-light">
                                        <td colspan="6" class="text-right">Total Divalidasi:</td>
                                        <td colspan="4"><span class="badge badge-success badge-pill px-3">{{ count($seminars) }} mahasiswa</span></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
