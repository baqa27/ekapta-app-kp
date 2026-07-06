

<?php $__env->startSection('content'); ?>

<div class="container-login bg-light pt-5">
    <div class="container">
        <div class="d-flex justify-content-center">
            <h1 class="fw-bold"><a href="<?php echo e(route('home')); ?>"
                    class="text-decoration-none text-dark text-uppercase">Ekapta</a>
            </h1>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <div class="card p-3 col-md-5">
                <h5 class="fw-bold">Login</h5>
                <hr>
                <a href="<?php echo e(route('login.mahasiswa')); ?>" class="btn btn-primary-me mb-3">Login Mahasiswa</a>
                <a href="<?php echo e(route('login.prodi')); ?>" class="btn btn-primary-me mb-3">Login Prodi</a>
                <a href="<?php echo e(route('login.dosen')); ?>" class="btn btn-primary-me mb-3">Login Dosen</a>
                <a href="<?php echo e(route('login.admin')); ?>" class="btn btn-primary-me mb-3">Login Admin</a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/home/login.blade.php ENDPATH**/ ?>