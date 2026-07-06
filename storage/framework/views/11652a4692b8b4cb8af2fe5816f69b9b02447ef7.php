<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/summernote/summernote-bs4.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- My CSS-->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/assets/css/dashboard.css" />
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/toastr/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon"> 
    
    <style>
        body { background-color: #f4f6f9 !important; }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="wrapper">

        

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <!-- /.content-wrapper -->

        

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/dist/js/adminlte.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/jszip/jszip.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Summernote -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Toastr -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/toastr/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    <script src="<?php echo e(asset('ekapta/assets/js/jquery.countdown.min.js')); ?>"></script>
    
    <script src="<?php echo e(asset('ekapta/assets/js/dashboard-mahasiswa.js')); ?>"></script>

    
    <?php if(session('success')): ?>
        <script>
            $(document).Toasts('create', {
                class: 'bg-success mt-5 mr-3',
                title: 'Success',
                autohide: true,
                delay: 3000,
                body: '<?php echo e(session('success')); ?>'
            })
        </script>
    <?php endif; ?>

    
    <?php if(session('warning')): ?>
        <script>
            $(document).Toasts('create', {
                class: 'bg-warning mt-5 mr-3',
                title: 'Warning',
                autohide: true,
                delay: 3000,
                body: '<?php echo e(session('warning')); ?>'
            })
        </script>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '<?php echo e(session('error')); ?>'
            })
        </script>
    <?php endif; ?>

    <?php echo $__env->make('kp.partials.sidebar-menu-state', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/layouts/dashboardFotokopi.blade.php ENDPATH**/ ?>