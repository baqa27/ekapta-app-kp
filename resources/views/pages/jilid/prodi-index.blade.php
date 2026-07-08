@extends('layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Pengumpulan TA</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">{{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_valid" data-toggle="tab">Valid (Menunggu Konfirmasi)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_selesai" data-toggle="tab">Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                {{-- Tab Valid (Menunggu Jilid) --}}
                                <div class="tab-pane active" id="tab_valid">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($jilids as $jilid)
                                                @if ($jilid->status == \App\Models\Jilid::JILID_VALID)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>{{ $jilid->mahasiswa->nama }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">Menunggu Konfirmasi</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('jilid.prodi.detail', $jilid->id) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Tab Selesai --}}
                                <div class="tab-pane" id="tab_selesai">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Total Pembayaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($jilids as $jilid)
                                                @if ($jilid->status == \App\Models\Jilid::JILID_SELESAI)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>{{ $jilid->mahasiswa->nama }}</td>
                                                        <td>
                                                            @if ($jilid->total_pembayaran)
                                                                Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Selesai</span>
                                                            @if ($jilid->is_completed)
                                                                <br><small class="text-primary">Sudah setor perpus</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('jilid.prodi.detail', $jilid->id) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
