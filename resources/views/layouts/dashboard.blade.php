<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/toastr/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include($sidebar)

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @yield('content')

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            &copy 2022-All Right Reserverd. Presented by <a href="https://fastikom-unsiq.ac.id/"
                class="text-decoration-none fw-semibold">Fastikom</a>
            <div class="float-right d-none d-sm-inline">
                Template by <a href="https://adminlte.io">AdminLTE</a>
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <!-- Summernote -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('ekapta') }}/adminLTE/dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Toastr -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/toastr/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        const confirmDelete = () => {
        event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: 'Yakin ingin dihapus ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }

        const confirmAcc = () => {
        event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: 'Yakin ingin diacc ?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }

        const confirmCancel = () => {
        event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: 'Yakin ingin dibatalkan ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>

    {{-- Alert success --}}
    @if (session('success'))
    <script>
        $(document).Toasts('create', {
            class : 'bg-success mt-5 mr-3',
            title: 'Success',
            autohide: true,
            delay: 3000,
            body: '{{session('success')}}'
        })
    </script>
    @endif

    {{-- Alert warning --}}
    @if (session('warning'))
    <script>
        $(document).Toasts('create', {
            class : 'bg-warning mt-5 mr-3',
            title: 'Warning',
            autohide: true,
            delay: 3000,
            body: '{{session('warning')}}'
        })
    </script>
    @endif

    {{-- Alert Error --}}
    @if (session('error'))
    <script>
        $(document).Toasts('create', {
            class : 'bg-danger mt-5 mr-3',
            title: 'Error',
            autohide: true,
            delay: 3000,
            body: '{{session('error')}}'
        })
    </script>
    @endif

    @error('lampiran')
    <script>
        $(document).Toasts('create', {
            class : 'bg-danger mt-5 mr-3',
            title: 'Error',
            autohide: true,
            delay: 3000,
            body: '{{$message}}'
        })
    </script>
    @enderror

    @error('lampiran_acc')
    <script>
        $(document).Toasts('create', {
            class : 'bg-danger mt-5 mr-3',
            title: 'Error',
            autohide: true,
            delay: 3000,
            body: '{{$message}}'
        })
    </script>
    @enderror

    <script>
        // Summernote
        $(function () {
            $('#summernote').summernote()
        })
        // Custom file input
        $(function () {
            bsCustomFileInput.init();
        });
        // DataTable
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
        $(function () {
            $("#example2").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

        });
        // Calendar
        $('#calendar').datetimepicker({
            format: 'L',
            inline: true
        })
    </script>
</body>
</html>