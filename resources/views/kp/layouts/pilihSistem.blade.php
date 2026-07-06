<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Pilih Sistem - EKAPTA' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- My CSS-->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/assets/css/dashboard.css" />
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon"> 
    {{-- Prevent white flash on page load --}}
    <style>
        body { background-color: #f4f6f9 !important; }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="wrapper">

        <!-- Navbar MINIMAL (tanpa menu navigasi) -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white sticky-top">
            <div class="container">
                <a href="{{ route('kp.pilih.sistem') }}" class="navbar-brand">
                    <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="UNSIQ Logo"
                         class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light" style="text-transform: uppercase;">
                        <b>{{ config('app.name') }}</b>
                    </span>
                </a>

                <!-- Right navbar links -->
                <ul class="order-3 navbar-nav navbar-no-expand ml-auto">
                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="#" class="dropdown-item">
                                {{ Auth::guard('mahasiswa')->user()->nama }}
                                {{ '('.Auth::guard('mahasiswa')->user()->nim.')' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('kp.profile') }}" class="dropdown-item">
                                <i class="far fa-user mr-2"></i> Profile
                            </a>
                            <a href="{{ route('kp.mahasiswa.account') }}" class="dropdown-item">
                                <i class="bi bi-gear mr-2"></i> Pengaturan Akun
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout.mahasiswa') }}" class="dropdown-item dropdown-footer bg-danger">
                                Logout <i class="bi bi-box-arrow-right"></i>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('ekapta') }}/adminLTE/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>

</html>
