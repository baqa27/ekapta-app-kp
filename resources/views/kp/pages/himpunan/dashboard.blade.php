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
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- ============================================== --}}
            {{-- SECTION KERJA PRAKTEK --}}
            {{-- ============================================== --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i> Kerja Praktek (KP)</h3>
                </div>
                <div class="card-body">

                    {{-- Info Box KP — bisa diklik langsung ke halaman verifikasi --}}
                    <div class="row">
                        {{-- Total Seminar --}}
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="{{ route('kp.seminar.himpunan') }}" class="text-decoration-none">
                                <div class="info-box" style="cursor:pointer;" title="Lihat semua seminar KP">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Seminar KP</span>
                                        <span class="info-box-number">{{ count($seminars) }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Seminar Review (Menunggu Validasi) --}}
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="{{ route('kp.seminar.himpunan') }}" class="text-decoration-none">
                                <div class="info-box mb-3" style="cursor:pointer;" title="Lihat seminar yang perlu divalidasi">
                                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Menunggu Validasi</span>
                                        <span class="info-box-number">{{ count($seminars_review) }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Seminar Diterima --}}
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="{{ route('kp.seminar.himpunan') }}" class="text-decoration-none">
                                <div class="info-box mb-3" style="cursor:pointer;" title="Lihat seminar yang sudah divalidasi">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sudah Divalidasi</span>
                                        <span class="info-box-number">{{ count($seminars_diterima) }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Seminar Revisi --}}
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="{{ route('kp.seminar.himpunan') }}" class="text-decoration-none">
                                <div class="info-box mb-3" style="cursor:pointer;" title="Lihat seminar yang perlu revisi">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Perlu Revisi</span>
                                        <span class="info-box-number">{{ count($seminars_revisi) }} Mahasiswa</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Progress Bars + Info Panel --}}
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-info card-outline" style="min-height: 16rem">
                                <div class="card-header">
                                    <h3 class="card-title">Seminar KP Berdasarkan Status</h3>
                                </div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Sudah Divalidasi
                                        <span class="float-right"><b>{{ count($seminars_diterima) }}</b>/{{ count($seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($seminars) > 0 ? (count($seminars_diterima) / count($seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Menunggu Validasi
                                        <span class="float-right"><b>{{ count($seminars_review) }}</b>/{{ count($seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($seminars) > 0 ? (count($seminars_review) / count($seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Perlu Revisi
                                        <span class="float-right"><b>{{ count($seminars_revisi) }}</b>/{{ count($seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($seminars) > 0 ? (count($seminars_revisi) / count($seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-secondary card-outline" style="min-height: 16rem">
                                <div class="card-header">
                                    <h3 class="card-title">Akses Cepat</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <a href="{{ route('kp.seminar.himpunan') }}" class="btn btn-block btn-outline-primary btn-sm">
                                                <i class="fas fa-check-double mr-1"></i> Validasi Seminar
                                            </a>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <a href="{{ route('kp.jadwal.himpunan') }}" class="btn btn-block btn-outline-info btn-sm">
                                                <i class="fas fa-calendar-alt mr-1"></i> Penjadwalan
                                            </a>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <a href="{{ route('kp.seminar.himpunan.rekap') }}" class="btn btn-block btn-outline-success btn-sm">
                                                <i class="fas fa-table mr-1"></i> Rekap Seminar
                                            </a>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <a href="{{ route('kp.payment.himpunan') }}" class="btn btn-block btn-outline-secondary btn-sm">
                                                <i class="fas fa-money-bill mr-1"></i> Pembayaran
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================== --}}
                    {{-- GRAFIK REKAPAN PER BULAN --}}
                    {{-- (Acuan: tanggal ACC/validasi oleh himpunan) --}}
                    {{-- ============================================== --}}
                    <div class="row mt-2">
                        <div class="col-md-8">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Rekapan Validasi Seminar per Bulan
                                    </h3>
                                    <div class="card-tools">
                                        <small class="text-muted">Berdasarkan tanggal ACC/validasi himpunan — 12 bulan terakhir</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="chartRekapBulan" height="120"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-success card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-table mr-2"></i>Tabel Rekap Bulanan</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped table-hover mb-0">
                                        <thead class="bg-success text-white">
                                            <tr>
                                                <th>Bulan</th>
                                                <th class="text-center">Divalidasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalAll = array_sum($dataBulan);
                                            @endphp
                                            @foreach($labelsBulan as $i => $bulan)
                                                <tr>
                                                    <td>{{ $bulan }}</td>
                                                    <td class="text-center">
                                                        @if($dataBulan[$i] > 0)
                                                            <span class="badge badge-success">{{ $dataBulan[$i] }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold bg-light">
                                                <td>Total</td>
                                                <td class="text-center"><span class="badge badge-primary">{{ $totalAll }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    const ctx = document.getElementById('chartRekapBulan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsBulan) !!},
            datasets: [{
                label: 'Seminar Divalidasi (ACC Himpunan)',
                data: {!! json_encode($dataBulan) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.75)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' mahasiswa divalidasi';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    title: { display: true, text: 'Jumlah Mahasiswa' }
                },
                x: {
                    title: { display: true, text: 'Bulan' }
                }
            }
        }
    });
</script>
@endpush
