@extends('layouts.dashboardMahasiswa')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"> Hai! {{ Auth::guard('mahasiswa')->user()->nama }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">

        <!-- Alur Ekapta -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Alur Ekapta</h3>
                    </div>
                    <div class="card-body">
                        <div id="accordion">
                            <!-- Pengajuan -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                            <span class="badge bg-white textprimary">1</span> Pengajuan TA
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            <li>
                                                Mahasiswa mengajukan judul TA dengan mengisi form ajuan
                                                diantaranya: Judul TA, deskripsi,
                                                dan upload file
                                            </li>
                                            <li>
                                                Jika status ajuan Diterima maka bisa melanjutkan ke proses
                                                pendaftaran. Jika status Revisi
                                                maka dapat dapat merevisi ajuan. Jika status Ditolak maka
                                                harus membuat ajuan baru.
                                            </li>
                                            <li>
                                                Setelah status ajuan diterima, mahasiswa dapat mencetak
                                                Lembar Persetujuan Pembimbing yang
                                                harus ditandatangani oleh calon dosen pembimbing, kemudian
                                                diupload di Form Pendaftaran
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Pendaftaran -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                            <span class="badge bg-white textprimary">2</span> Pendaftaran TA
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            <li>
                                                Pendaftaran hanya bisa dilakukan Jika status ajuan diterima
                                            </li>
                                            <li>
                                                Mahasiswa mengisi formular pendaftaran dan mengupload
                                                beberapa dokumen termasuk lembar
                                                persetujuan calon dosen pembimbing yang sudah ditandatangani
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Bimbingan -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                                            <span class="badge bg-white textprimary">3</span> Bimbingan
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            <li>
                                                Mahaiswa memulai bimbingan dengan memilih bagian (missal Bab
                                                I) kemudian mahasiswa upload
                                                file bimbingan Bab I
                                            </li>
                                        </ul>
                                        <p class="fw-semibold" style="margin-left: 18px;">Setelah direview
                                            Dosen :</p>
                                        <ul>
                                            <li>
                                                Jika status dari dosen revisi, maka mahasiswisa memperbaiki
                                                laporannya dan bisa upload
                                                Kembali sampai status dari dosen Diterima
                                            </li>
                                            <li>
                                                Setelah Bab I diterima, maka bisa melanjutkan bimbingan ke
                                                bab II dst
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Seminar -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                                            <span class="badge bg-white textprimary">4</span> Seminar
                                            Proposal
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            <li>
                                                Syarat untuk mendaftar seminar proposal, harus sudah acc bab
                                                I sampai bab III
                                            </li>
                                            <li>
                                                Mahasiswa mendaftara seminar dengan mengisi form pendaftaran
                                            </li>
                                        </ul>
                                        <p class="fw-semibold" style="margin-left: 18px;">Setelah Seminar
                                            Proposal :</p>
                                        <ul>
                                            <li>
                                                Mahasiswa upload bimbingan revisi seminar proposal ditujukan
                                                ke dosen penguji
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Ujian -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFive">
                                            <span class="badge bg-white textprimary">5</span> Ujian
                                            Pendadaran
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            <li>
                                                Syarat untuk mendaftar ujian pendadaran, harus sudah acc
                                                semua bab, produk, dan artikel
                                            </li>
                                            <li>
                                                Mahasiswa mendaftar ujian pendadaran dengan mengisi form
                                                pendaftaran
                                            </li>
                                        </ul>
                                        <p class="fw-semibold" style="margin-left: 18px;">Setelah Ujian
                                            Pendadaran :</p>
                                        <ul>
                                            <li>
                                                Mahasiswa upload bimbingan revisi seminar proposal ditujukan
                                                ke dosen penguji
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!-- Calendar -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-gradient-dark">
                    <div class="card-header border-0">

                        <h3 class="card-title">
                            <i class="far fa-calendar-alt"></i>
                            Calendar
                        </h3>
                        <!-- tools card -->
                        <div class="card-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown"
                                    data-offset="-52">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a href="#" class="dropdown-item">Add new event</a>
                                    <a href="#" class="dropdown-item">Clear events</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">View calendar</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-dark btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-dark btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body pt-0">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

@endsection