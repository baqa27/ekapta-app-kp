@extends(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'kp.layouts.dashboard' : 'kp.layouts.dashboardFotokopi')

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
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir KP</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (Auth::guard('admin')->user()->type != \App\Models\Admin::TYPE_SUPER_ADMIN)
                {{-- Welcome Card --}}
                <div class="card bg-gradient-info mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><i class="fas fa-user-circle mr-2"></i>Selamat datang, <strong>{{ Auth::guard('admin')->user()->nama }}</strong></h4>
                                <p class="mb-0 mt-2"><i class="fas fa-info-circle mr-1"></i> Dashboard Fotokopi FASTIKOM</p>
                            </div>
                            <a href="{{ route('logout.admin') }}" class="btn btn-light">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card Statistik --}}
                <div class="row mb-4">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $jilids_kp->where('status', \App\Models\KP\Jilid::JILID_VALID)->count() }}</h3>
                                <p>Menunggu Pengumpulan Akhir KP</p>
                            </div>
                            <div class="icon"><i class="fas fa-clock"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $jilids_kp->where('status', \App\Models\KP\Jilid::JILID_SELESAI)->count() }}</h3>
                                <p>Selesai Pengumpulan Akhir KP</p>
                            </div>
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $jilids_ta->where('status', 'terkumpul')->count() }}</h3>
                                <p>Menunggu Jilid TA</p>
                            </div>
                            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $jilids_ta->where('status', 'selesai')->count() }}</h3>
                                <p>Selesai Jilid TA</p>
                            </div>
                            <div class="icon"><i class="fas fa-graduation-cap"></i></div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                {{-- Admin Super: Tab Review/Revisi/Valid/Selesai --}}
                                <ul class="nav nav-pills mb-3">
                                    <li class="nav-item"><a class="nav-link active" href="#tab_review" data-toggle="tab">Review</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_revisi" data-toggle="tab">Revisi</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_valid" data-toggle="tab">Valid</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_selesai" data-toggle="tab">Selesai</a></li>
                                </ul>

                                <div class="tab-content">
                                    {{-- Tab Review --}}
                                    <div class="tab-pane active" id="tab_review">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">NIM</th>
                                                    <th width="30%">Nama</th>
                                                    <th width="20%">Total Pembayaran</th>
                                                    <th width="15%">Status</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_REVIEW) as $jilid)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>
                                                            {{ $jilid->mahasiswa->nama }}
                                                            @if(\App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa))
                                                                <span class="badge badge-info badge-sm ml-1">Karyawan</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $jilid->total_pembayaran ? 'Rp '.number_format($jilid->total_pembayaran, 0, ',', '.') : '-' }}</td>
                                                        <td><span class="badge badge-secondary">Pemeriksaan</span></td>
                                                        <td>
                                                            <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye mr-1"></i> Periksa
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Revisi --}}
                                    <div class="tab-pane" id="tab_revisi">
                                        <table id="example3" class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">NIM</th>
                                                    <th width="30%">Nama</th>
                                                    <th width="20%">Total Pembayaran</th>
                                                    <th width="15%">Status</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_REVISI) as $jilid)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>
                                                            {{ $jilid->mahasiswa->nama }}
                                                            @if(\App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa))
                                                                <span class="badge badge-info badge-sm ml-1">Karyawan</span>
                                                            @endif
                                                        </td>
                                                        <td>-</td>
                                                        <td><span class="badge badge-warning">Revisi</span></td>
                                                        <td>
                                                            <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Valid --}}
                                    <div class="tab-pane" id="tab_valid">
                                        <table id="example4" class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">NIM</th>
                                                    <th width="30%">Nama</th>
                                                    <th width="20%">Total Pembayaran</th>
                                                    <th width="15%">Status</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_VALID) as $jilid)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>
                                                            {{ $jilid->mahasiswa->nama }}
                                                            @if(\App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa))
                                                                <span class="badge badge-info badge-sm ml-1">Karyawan</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $jilid->total_pembayaran ? 'Rp '.number_format($jilid->total_pembayaran, 0, ',', '.') : '-' }}</td>
                                                        <td><span class="badge badge-primary">Valid</span></td>
                                                        <td>
                                                            <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye mr-1"></i> Dokumen
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Selesai --}}
                                    <div class="tab-pane" id="tab_selesai">
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">NIM</th>
                                                    <th width="30%">Nama</th>
                                                    <th width="20%">Total Pembayaran</th>
                                                    <th width="15%">Status</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids_kp->where('status', \App\Models\KP\Jilid::JILID_SELESAI) as $jilid)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>
                                                            {{ $jilid->mahasiswa->nama }}
                                                            @if(\App\Helpers\AppHelper::isKaryawanKP($jilid->mahasiswa))
                                                                <span class="badge badge-info badge-sm ml-1">Karyawan</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $jilid->total_pembayaran ? 'Rp '.number_format($jilid->total_pembayaran, 0, ',', '.') : '-' }}</td>
                                                        <td>
                                                            <span class="badge badge-success">Selesai</span>
                                                            @if ($jilid->is_completed)
                                                                <br><small class="text-primary">Sudah setor perpus</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!$jilid->is_completed)
                                                                <a href="{{ route('kp.pengumpulan-akhir.confirm.completed', $jilid->id) }}"
                                                                    class="btn btn-sm btn-success"
                                                                    onclick="return confirm('Yakin konfirmasi sudah setor ke perpustakaan?')">
                                                                    <i class="fas fa-check mr-1"></i> Konfirmasi Setor
                                                                </a>
                                                            @else
                                                                <span class="text-muted">Sudah dikonfirmasi</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                {{-- Fotokopi: Tab KP dan TA --}}
                                <ul class="nav nav-tabs mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#jilid-kp" data-toggle="tab">
                                            <i class="fas fa-briefcase mr-1"></i> Pengumpulan Akhir KP
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#jilid-ta" data-toggle="tab">
                                            <i class="fas fa-graduation-cap mr-1"></i> Jilid TA
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    {{-- JILID KP --}}
                                    <div class="tab-pane active" id="jilid-kp">
                                        <ul class="nav nav-pills mb-3">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#kp-menunggu" data-toggle="pill">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu Jilid
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#kp-selesai" data-toggle="pill">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai Jilid
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            {{-- Menunggu Pengumpulan Akhir KP --}}
                                            <div class="tab-pane active" id="kp-menunggu">
                                                <table id="table_kp_valid" class="table table-bordered table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="15%">NIM</th>
                                                            <th width="30%">Nama Mahasiswa</th>
                                                            <th width="20%">Tanggal Submit</th>
                      