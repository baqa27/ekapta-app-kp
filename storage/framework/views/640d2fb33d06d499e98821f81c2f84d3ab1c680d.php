

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pengaturan Persentase Nilai Kerja Praktek - Prodi <?php echo e($prodi->namaprodi); ?></h3>
                        </div>
                        <div class="card-body">
                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <?php if(session('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo e(session('error')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('prodi.presentase.nilai.kp.store')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="prodi_id" value="<?php echo e($prodi->id); ?>">
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Keterangan:</strong> Total ketiga persentase nilai harus sama dengan <strong>100%</strong>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card card-outline card-success">
                                            <div class="card-header">
                                                <h5 class="card-title m-0">
                                                    <i class="fas fa-building mr-2"></i>Nilai Instansi
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="bobot_instansi">Persentase Nilai Instansi (%)</label>
                                                    <input type="number" 
                                                           name="bobot_instansi"
                                                           id="bobot_instansi"
                                                           class="form-control form-control-lg text-center <?php $__errorArgs = ['bobot_instansi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(old('bobot_instansi', $presentase_nilai ? $presentase_nilai->bobot_instansi : 30)); ?>"
                                                           min="0" max="100"
                                                           required>
                                                    <?php $__errorArgs = ['bobot_instansi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <small class="text-muted">Nilai dari pembimbing instansi/perusahaan</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <h5 class="card-title m-0">
                                                    <i class="fas fa-user-tie mr-2"></i>Nilai Dosen Pembimbing
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="bobot_pembimbing">Persentase Nilai Pembimbing (%)</label>
                                                    <input type="number" 
                                                           name="bobot_pembimbing"
                                                           id="bobot_pembimbing"
                                                           class="form-control form-control-lg text-center <?php $__errorArgs = ['bobot_pembimbing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(old('bobot_pembimbing', $presentase_nilai ? $presentase_nilai->bobot_pembimbing : 35)); ?>"
                                                           min="0" max="100"
                                                           required>
                                                    <?php $__errorArgs = ['bobot_pembimbing'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <small class="text-muted">Nilai dari dosen pembimbing KP</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-outline card-warning">
                                            <div class="card-header">
                                                <h5 class="card-title m-0">
                                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Nilai Dosen Penguji
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="bobot_penguji">Persentase Nilai Penguji (%)</label>
                                                    <input type="number" 
                                                           name="bobot_penguji"
                                                           id="bobot_penguji"
                                                           class="form-control form-control-lg text-center <?php $__errorArgs = ['bobot_penguji'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value="<?php echo e(old('bobot_penguji', $presentase_nilai ? $presentase_nilai->bobot_penguji : 35)); ?>"
                                                           min="0" max="100"
                                                           required>
                                                    <?php $__errorArgs = ['bobot_penguji'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <small class="text-muted">Nilai dari dosen penguji seminar KP</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5 class="mb-0">
                                                    Total: <span id="total-nilai" class="badge badge-lg badge-success">100</span> %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <a href="<?php echo e(route('prodis')); ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const instansi = document.getElementById('bobot_instansi');
        const pembimbing = document.getElementById('bobot_pembimbing');
        const penguji = document.getElementById('bobot_penguji');
        const totalBadge = document.getElementById('total-nilai');

        function updateTotal() {
            const total = (parseInt(instansi.value) || 0) + 
                         (parseInt(pembimbing.value) || 0) + 
                         (parseInt(penguji.value) || 0);
            
            totalBadge.textContent = total;
            
            if (total === 100) {
                totalBadge.className = 'badge badge-lg badge-success';
            } else {
                totalBadge.className = 'badge badge-lg badge-danger';
            }
        }

        instansi.addEventListener('input', updateTotal);
        pembimbing.addEventListener('input', updateTotal);
        penguji.addEventListener('input', updateTotal);

        // Initial calculation
        updateTotal();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/prodi/presentase-nilai.blade.php ENDPATH**/ ?>