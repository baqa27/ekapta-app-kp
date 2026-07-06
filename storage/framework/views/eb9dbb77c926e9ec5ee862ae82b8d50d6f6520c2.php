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
                <h5 class="fw-bold">Login to Ekapta</h5>
                <hr>
                <form action="<?php echo e(route('cek.mahasiswa')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-3">
                        <label for="nim" class="form-label fw-semibold">NIM</label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroup-sizing-default"><i
                                    class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="nim" value="<?php echo e(old('nim')); ?>" placeholder="Masukkan nim..."
                                required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nim" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="inputGroup-sizing-default"><i
                                    class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="password"
                                placeholder="Masukkan password..." required>
                        </div>
                        <small class="text-muted d-block mt-2">
                        </small>
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

<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/login.blade.php ENDPATH**/ ?>