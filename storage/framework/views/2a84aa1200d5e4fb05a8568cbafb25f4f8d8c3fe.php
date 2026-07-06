<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Bimbingan TA</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1"><?php echo e($title); ?></h3>
                            <h3 class="card-title flex-shrink-0"><?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></h3>
                        </div>
                        <div class="card-body">
                            <?php if($bimbingan->catatan): ?>
                                <div class="alert alert-warning mb-2">
                                    <i class="fas fa-info-circle"></i> Catatan revisi: <?php echo e($bimbingan->catatan); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php if(!$bimbingan->lampiran_acc): ?>
                                <form action="<?php echo e(route('bimbingan.submit.acc.manual.store')); ?>" method="post"
                                    enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" name="id" value="<?php echo e($bimbingan->id); ?>">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Bagian Bimbingan</label>
                                        <input type="text" class="form-control" value="<?php echo e($bimbingan->bagian->bagian); ?>"
                                            disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Lembar Acc Bimbingan(Format: PDF, PNG, JPG, JPEG| Max:
                                            5MB)</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input <?php $__errorArgs = ['lampiran_acc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="lampiran_acc" accept=".pdf, .jpeg,.png,.jpg" required>
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Dokumen</span>
                                            </div>
                                        </div>
                                        <?php $__errorArgs = ['lampiran_acc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"
                                                style="position:relative;top:-15px;left:5px"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label>Tanggal Acc Bimbingan</label>
                                        <input type="date" name="tanggal_manual_acc" class="form-control" required>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Sudah input lembar acc bimbingan. Silahkan tunggu
                                    validasi dari prodi.
                                </div>

                                <a href="<?php echo e(route('bimbingan.mahasiswa')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <?php endif; ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/bimbingan/submit-acc-manual.blade.php ENDPATH**/ ?>