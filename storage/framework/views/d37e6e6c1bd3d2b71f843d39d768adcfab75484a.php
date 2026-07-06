<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title); ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/summernote/summernote-bs4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/toastr/toastr.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet"
        href="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon">

     
     <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet"/>
     <style type="text/css">
         .bootstrap-tagsinput .tag {
             margin-right: 2px;
             color: white !important;
             background-color: #0d6efd;
             padding: 2px 4px 2px 4px;
             border-radius: 10px;
         }
     </style>
     
     <style>
         body { background-color: #343a40 !important; }
         .content-wrapper { background-color: #f4f6f9; }
     </style>
     
     <style>
         @media (max-width: 991.98px) {
             body:not(.sidebar-open) .main-sidebar {
                 margin-left: -250px;
             }
         }
     </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php echo $__env->make($sidebar, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <?php echo $__env->yieldContent('content'); ?>

        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/dist/js/adminlte.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
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
    <!-- bs-custom-file-input -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- Toastr -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/toastr/toastr.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?php echo e(asset('ekapta')); ?>/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    <script src="<?php echo e(asset('ekapta/assets/js/dashboard.js')); ?>"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    
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

    <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '<?php echo e($message); ?>'
            })
        </script>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php $__errorArgs = ['lampiran_acc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger mt-5 mr-3',
                title: 'Error',
                autohide: true,
                delay: 3000,
                body: '<?php echo e($message); ?>'
            })
        </script>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if($active == 'pengajuan' || $active == 'seminar' || $active == 'ujian' || $active == 'dosen'): ?>
        <script>
            $(document).ready(function() {
                $('.select-1').select2();
            })

            $(document).ready(function() {
                $('.select-2').select2();
            })

            $(document).ready(function() {
                $('.select-3').select2();
            })
        </script>
    <?php endif; ?>

    <?php echo $__env->make('kp.layouts.js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('kp.partials.sidebar-menu-state', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <script>
    $(function() {
        // Deteksi touch device (Android/iOS)
        var isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        if (isTouchDevice && $(window).width() < 992) {
            $('body').addClass('sidebar-collapse');
        }
    });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/layouts/dashboard.blade.php ENDPATH**/ ?>