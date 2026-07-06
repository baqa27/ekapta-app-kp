

<?php $__env->startSection('content'); ?>

<div class="container-login bg-light pt-5">
    <div class="container">
        <div class="d-flex justify-content-center">
            <h1 class="fw-bold"><a href="<?php echo e(route('login')); ?>"
                    class="text-decoration-none text-dark text-uppercase">Ekapta</a>
            </h1>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <div class="card p-3 col-md-5">
                <h5 class="fw-bold">Login Himpunan</h5>
                <hr>
                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('kp.cek.himpunan')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroup-sizing-default"><i
                                    class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="username"
                                placeholder="Masukkan username..." value="<?php echo e(old('username')); ?>" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroup-sizing-default"><i
                                    class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="password"
                                placeholder="Masukkan password..." required>
                        </div>
                    </div>
                    <div class="mt-4 mb-3">
                        <button class="btn btn-primary-me btn-login col-md-12" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/himpunan/login.blade.php ENDPATH**/ ?>