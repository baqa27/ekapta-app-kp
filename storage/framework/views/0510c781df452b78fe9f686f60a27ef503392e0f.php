<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($title); ?></title>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/assets/css/style.css" />
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo e(asset('ekapta')); ?>/bootstrap/dist/css/bootstrap.rtl.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon"> 
</head>
<body>

    <!-- Content -->

    <?php echo $__env->yieldContent('content'); ?>

    <!-- End Content -->

    <!-- Bootstrap JS -->
    <script src="<?php echo e(asset('ekapta')); ?>/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/assets/js/jquery-1.10.2.js"></script>
    <script src="<?php echo e(asset('ekapta')); ?>/assets/js/main.js"></script>
    <!-- Swetalert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(session('error')): ?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: '<?php echo e(session('error')); ?>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    <?php endif; ?>

</body>
</html><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/layouts/home.blade.php ENDPATH**/ ?>