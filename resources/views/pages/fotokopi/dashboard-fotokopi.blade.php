@extends('layouts.dashboardFotokopi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
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
    <div class="content">
        <div class="container">
            <div class="mb-3 d-flex">
                <h4 class="flex-grow-1">Selamat datang {{ Auth::guard('admin')->user()->nama }}</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('logout.admin') }}" class="btn btn-danger float-end">Logout <i
                            class="bi bi-box-arrow-right ml-2"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM/NAMA MAHASISWA</th>
                                        <th>TOTAL PEMBAYARAN</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($jilids as $jilid)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}</td>
                                            <td>
                                                @if ($jilid->total_pembayaran)
                                                    <span class="text-success">
                                                        Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($jilid->status == 1)
                                                    <span class="badge bg-secondary">REVIEW</span>
                                                @elseif ($jilid->status == 3)
                                                    <span class="badge bg-secondary">VALID</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($jilid->status == 1)
                                                    <a href="{{ route('jilid.detail', $jilid->id) }}"
                                                        class="btn btn-secondary"><i class="fas fa-eye"></i> REVIEW JILID
                                                        SKRIPSI</a>
                                                @elseif ($jilid->status == 3)
                                                    <a href="{{ route('jilid.detail', $jilid->id) }}"
                                                        class="btn btn-primary"><i class="fas fa-book"></i> JILID
                                                        SKRIPSI</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM/NAMA MAHASISWA</th>
                                        <th>TOTAL PEMBAYARAN</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
