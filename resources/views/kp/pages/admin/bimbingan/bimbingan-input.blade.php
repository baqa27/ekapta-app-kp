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
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Bimbingan Aktif</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Bimbingan Selesai</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- TAB AKTIF -->
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($mahasiswas_aktif as $mahasiswa)
                                                @php
                                                    $dosen = $mahasiswa->dosens->first(function($d) { return $d->pivot->status == 'pembimbing'; })
                                                            ?? $mahasiswa->dosens->first(function($d) { return $d->pivot->status == 'utama'; });
                                                @endphp
                                                @if($dosen)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $mahasiswa->nama }} - {{ $mahasiswa->nim }}</td>
                                                    <td>{{ $mahasiswa->prodi }}</td>
                                                    <td>{{ $dosen->nama }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">Review</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route($createRoute ?? 'kp.bimbingan.admin.input.create', [$dosen->id, $mahasiswa->id]) }}"
                                                           class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->

                                <!-- TAB SELESAI -->
                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($mahasiswas_selesai as $mahasiswa)
                                                @php
                                                    $dosen = $mahasiswa->dosens->first(function($d) { return $d->pivot->status == 'pembimbing'; })
                                                            ?? $mahasiswa->dosens->first(function($d) { return $d->pivot->status == 'utama'; });
                                                @endphp
                                                @if($dosen)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $mahasiswa->nama }} - {{ $mahasiswa->nim }}</td>
                                                    <td>{{ $mahasiswa->prodi }}</td>
                                                    <td>{{ $dosen->nama }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Selesai</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route($createRoute ?? 'kp.bimbingan.admin.input.create', [$dosen->id, $mahasiswa->id]) }}"
                                                           class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>

    </section>
    <!-- /.content -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Konfigurasi DataTable
    var dataTableConfig = {
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
    };

    // DataTable untuk Tab Bimbingan Aktif - cek dulu apakah sudah diinisialisasi
    if (!$.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable(dataTableConfig);
    }

    // DataTable untuk Tab Bimbingan Selesai - cek dulu apakah sudah diinisialisasi
    if (!$.fn.DataTable.isDataTable('#example2')) {
        $('#example2').DataTable(dataTableConfig);
    }
});
</script>
@endpush
