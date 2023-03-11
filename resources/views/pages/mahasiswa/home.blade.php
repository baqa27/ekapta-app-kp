@extends('layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-3 mt-5">
                <div class="col-sm-12">
                    <h1 class="mb-3"> Hai! {{ Auth::guard('mahasiswa')->user()->nama }}</h1>
                    <p>Selamat Datang di {{ config('app.name') }}, {{ config('app.name') }} adalah Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam ducimus et explicabo iste placeat quidem quisquam, reprehenderit tenetur voluptate! Aliquam commodi delectus dolorem enim minus nesciunt nobis, quaerat repellendus voluptate.</p>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">

            <div class="card mb-5">
                <div class="card-header">
                    <b>Silahkan Pilih Menu dibawah ini:</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('dashboard.mahasiswa.kp') }}" class="btn btn-info col-md-12 p-3 btn-lg">📙 MENU KERJA PRAKTEK</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('dashboard.mahasiswa.ta') }}" class="btn btn-primary col-md-12 p-3 btn-lg">📕 MENU TUGAS AKHIR</a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('dashboard.mahasiswa.jilid') }}" class="btn btn-secondary col-md-12 p-3 btn-lg">🖨 MENU PENJILIDAN</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.content -->
    @endsection
