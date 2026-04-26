@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Seminar Aktif</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Seminar Selesai</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- TAB SEMINAR AKTIF -->
                                <div class="tab-pane active" id="tab_1">
                                    <table id="table-seminar-aktif" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars_aktif as $seminar)
                                                @php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $seminar->mahasiswa->nama }}
                                                        @if($is_karyawan)
                                                            <span class="badge badge-info ml-1">Karyawan</span>
                                                        @endif
                                                        <br><small class="text-muted">{{ $seminar->mahasiswa->nim }}</small>
                                                    </td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-' }}</td>
                                                    <td>
                                                        @if($is_karyawan)
                                                            <span class="badge badge-warning">Menunggu Penilaian</span>
                                                        @else
                                                            <span class="badge badge-primary">Sedang Berjalan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('kp.seminar.prodi.detail', $seminar->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- TAB SEMINAR SELESAI -->
                                <div class="tab-pane" id="tab_2">
                                    <table id="table-seminar-selesai" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars_selesai as $seminar)
                                                @php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $seminar->mahasiswa->nama }}
                                                        @if($is_karyawan)
                                                            <span class="badge badge-info ml-1">Karyawan</span>
                                                        @endif
                                                        <br><small class="text-muted">{{ $seminar->mahasiswa->nim }}</small>
                                                    </td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-' }}</td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.seminar.prodi.detail', $seminar->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    $(function () {
        var dataTableConfig = {
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        };

        // DataTable untuk Tab Seminar Aktif - cek dulu apakah sudah diinisialisasi
        if (!$.fn.DataTable.isDataTable('#table-seminar-aktif')) {
            $('#table-seminar-aktif').DataTable(dataTableConfig);
        }
        
        // DataTable untuk Tab Seminar Selesai - cek dulu apakah sudah diinisialisasi
        if (!$.fn.DataTable.isDataTable('#table-seminar-selesai')) {
            $('#table-seminar-selesai').DataTable(dataTableConfig);
        }
    });
</script>
@endsection




