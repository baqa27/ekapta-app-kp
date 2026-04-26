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
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir</a></li>
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
                            <h3 class="card-title p-3">Pengumpulan Akhir</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_kp" data-toggle="tab">
                                        <i class="fas fa-briefcase mr-1"></i> Jilid KP
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_ta" data-toggle="tab">
                                        <i class="fas fa-graduation-cap mr-1"></i> Jilid TA
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                {{-- TAB JILID KP --}}
                                <div class="tab-pane active" id="tab_kp">
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#tab_kp_valid" data-toggle="tab">Valid (Menunggu Jilid)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tab_kp_selesai" data-toggle="tab">Selesai</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        {{-- Tab Valid KP --}}
                                        <div class="tab-pane active" id="tab_kp_valid">
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
                                                    @foreach ($jilids_kp as $jilid)
                                                        @if ($jilid->status == \App\Models\KP\Jilid::JILID_VALID)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $jilid->mahasiswa->nim }}</td>
                                                                <td>{{ $jilid->mahasiswa->nama }}</td>
                                                                <td>
                                                                    <span class="badge bg-primary">Menunggu Proses Jilid</span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('kp.pengumpulan-akhir.prodi.detail', $jilid->id) }}" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Tab Selesai KP --}}
                                        <div class="tab-pane" id="tab_kp_selesai">
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
                                                    @foreach ($jilids_kp as $jilid)
                                                        @if ($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI)
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
                                                                    <a href="{{ route('kp.pengumpulan-akhir.prodi.detail', $jilid->id) }}" class="btn btn-info btn-sm">
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

                                {{-- TAB JILID TA --}}
                                <div class="tab-pane" id="tab_ta">
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#tab_ta_valid" data-toggle="tab">Valid (Menunggu Jilid)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tab_ta_selesai" data-toggle="tab">Selesai</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        {{-- Tab Valid TA --}}
                                        <div class="tab-pane active" id="tab_ta_valid">
                                            <table id="table_ta_valid" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>NIM</th>
                                                        <th>Nama Mahasiswa</th>
                                                        <th>Tanggal Submit</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach ($jilids_ta as $jilid)
                                                        @if (in_array($jilid->status, ['terkumpul', 'review', 'revisi']))
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>{{ $jilid->created_at->format('d M Y H:i') }}</td>
                                                            <td>
                                                                @if($jilid->status == 'terkumpul')
                                                                    <span class="badge bg-primary">Menunggu Jilid</span>
                                                                @elseif($jilid->status == 'review')
                                                                    <span class="badge bg-secondary">Review</span>
                                                                @elseif($jilid->status == 'revisi')
                                                                    <span class="badge bg-warning">Revisi</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('kp.pengumpulan-akhir.prodi.detail.ta', $jilid->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Tab Selesai TA --}}
                                        <div class="tab-pane" id="tab_ta_selesai">
                                            <table id="table_ta_selesai" class="table table-bordered table-striped">
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
                                                    @foreach ($jilids_ta as $jilid)
                                                        @if ($jilid->status == 'selesai')
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    <span class="text-success font-weight-bold">
                                                                        Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                    </span>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">Selesai</span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('kp.pengumpulan-akhir.prodi.detail.ta', $jilid->id) }}" class="btn btn-info btn-sm">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
