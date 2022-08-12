@extends('layouts.dashboardMahasiswa')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Bimbingan Tugas Akhir</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">

        {{-- <a href="{{ route('bimbingan.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
            Buat
            Bimbingan TA</a> --}}

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">Bimbingan Anda</h3>
                        <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pembimbing
                                    Utama</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pembimbing
                                    Pendamping</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">

                                Dosen Pembimbing : <strong>
                                    @if ($dosen_utama)
                                    {{ $dosen_utama->nama.', '.$dosen_utama->gelar }}
                                    @endif
                                </strong>

                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bagian Bimbingan</th>
                                            <th>Tanggal Bimbingan</th>
                                            <th>Tanggal ACC</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $no = 1;
                                        @endphp
                                        @foreach ($bimbingans_utama as $bimbingan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $bimbingan->bagian->bagian }}</td>
                                            <td>{{ $bimbingan->created_at->format('d M y H:m') }}</td>
                                            <td>
                                                @if ($bimbingan->tanggal_acc)
                                                {{ date('d M y H:m', strtotime($bimbingan->tanggal_acc)); }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($bimbingan->status == 'review')
                                                <span class="badge bg-secondary">Review</span>
                                                @elseif ($bimbingan->status == 'revisi')
                                                <span class="badge bg-warning">Revisi</span>
                                                @elseif ($bimbingan->status == 'diterima')
                                                <span class="badge bg-success">Diterima</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($bimbingan->status == 'review')
                                                <div class="d-flex">
                                                    <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                        class="btn btn-info btn-sm shadow mr-2">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    {{-- @if (count($bimbingan->revisis) == 0)
                                                    <form action="{{ route('bimbingan.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                                                        <button class="btn btn-danger btn-sm shadow" type="submit"
                                                            onclick="confirmDelete()">
                                                            <i class="fas fa-trash mr-1"
                                                                onclick="confirmDelete()"></i>Hapus</button>
                                                    </form>
                                                    @endif --}}
                                                </div>

                                                @elseif ($bimbingan->status == 'revisi')
                                                <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                    class="btn btn-info btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>

                                                <a href="{{ url('/bimbingan/edit/'.$bimbingan->id) }}"
                                                    class="btn btn-primary btn-sm shadow" type="submit"><i
                                                        class="fas fa-pen mr-1"></i>Edit</a>

                                                @elseif ($bimbingan->status == 'diterima')
                                                <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                    class="btn btn-info btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>
                                                @else
                                                <a href="{{ url('/bimbingan/edit/'.$bimbingan->id) }}"
                                                    class="btn btn-primary btn-sm shadow" type="submit"><i
                                                        class="fas fa-pen mr-1"></i>Edit</a>

                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Bagian Bimbingan</th>
                                            <th>Tanggal Bimbingan</th>
                                            <th>Tanggal ACC</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">

                                Dosen Pembimbing : <strong>
                                    @if ($dosen_pendamping)
                                    {{ $dosen_pendamping->nama.', '.$dosen_pendamping->gelar }}
                                    @endif
                                </strong>

                                <table id="example2" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bagian Bimbingan</th>
                                            <th>Tanggal Bimbingan</th>
                                            <th>Tanggal ACC</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $no = 1;
                                        @endphp
                                        @foreach ($bimbingans_pendamping as $bimbingan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $bimbingan->bagian->bagian }}</td>
                                            <td>{{ $bimbingan->created_at->format('d M y H:m') }}</td>
                                            <td>
                                                @if ($bimbingan->tanggal_acc)
                                                {{ date('d M y H:m', strtotime($bimbingan->tanggal_acc)); }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($bimbingan->status == 'review')
                                                <span class="badge bg-secondary">Review</span>
                                                @elseif ($bimbingan->status == 'revisi')
                                                <span class="badge bg-warning">Revisi</span>
                                                @elseif ($bimbingan->status == 'diterima')
                                                <span class="badge bg-success">Diterima</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($bimbingan->status == 'review')
                                                <div class="d-flex">
                                                    <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                        class="btn btn-info btn-sm shadow mr-2">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    {{-- @if (count($bimbingan->revisis) == 0)
                                                    <form action="{{ route('bimbingan.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                                                        <button class="btn btn-danger btn-sm shadow" type="submit"
                                                            onclick="confirmDelete()">
                                                            <i class="fas fa-trash mr-1"
                                                                onclick="confirmDelete()"></i>Hapus</button>
                                                    </form>
                                                    @endif --}}
                                                </div>

                                                @elseif ($bimbingan->status == 'revisi')
                                                <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                    class="btn btn-info btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>

                                                <a href="{{ url('/bimbingan/edit/'.$bimbingan->id) }}"
                                                    class="btn btn-primary btn-sm shadow" type="submit"><i
                                                        class="fas fa-pen mr-1"></i>Edit</a>

                                                @elseif ($bimbingan->status == 'diterima')
                                                <a href="{{ url('/bimbingan/detail/'.$bimbingan->id) }}"
                                                    class="btn btn-info btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>
                                                @else
                                                <a href="{{ url('/bimbingan/edit/'.$bimbingan->id) }}"
                                                    class="btn btn-primary btn-sm shadow" type="submit"><i
                                                        class="fas fa-pen mr-1"></i>Edit</a>

                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Bagian Bimbingan</th>
                                            <th>Tanggal Bimbingan</th>
                                            <th>Tanggal ACC</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>

                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /.content -->

@endsection