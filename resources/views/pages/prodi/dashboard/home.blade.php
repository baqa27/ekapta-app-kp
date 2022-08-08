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
        <div class="row mb-4">
            <!-- Pengajuan TA -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">Pengajuan TA</div>
                    <div class="card-body">
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <a href="">Lihat semua</a>
                    </div>
                </div>
            </div>
            <!-- Pendaftaran TA -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">Pendaftaran TA</div>
                    <div class="card-body">
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <a href="">Lihat semua</a>
                    </div>
                </div>
            </div>
            <!-- Seminar Proposal -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">Seminar Proposal</div>
                    <div class="card-body">
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <a href="">Lihat semua</a>
                    </div>
                </div>
            </div>
            <!-- Ujian Pendadaran -->
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">Ujian Pendadaran</div>
                    <div class="card-body">
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                        <a href="" class="text-dark">
                            <div class="callout callout-info">
                                <h6>Febi Arifin</h6>
                                <small class="text-secondary">4 Agustus 2022</small>
                            </div>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <a href="">Lihat semua</a>
                    </div>
                </div>
            </div>
        </div>

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
</section>
<!-- /.content -->

@endsection