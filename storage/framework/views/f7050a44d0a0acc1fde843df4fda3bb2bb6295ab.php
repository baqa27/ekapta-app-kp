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
                            <h3 class="card-title flex-shrink-0"><?php echo e($dosen->nama.', '.$dosen->gelar); ?></h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('bimbingan.update')); ?>" method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="id" value="<?php echo e($bimbingan->id); ?>">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bagian Bimbingan</label>
                                    <input type="text" class="form-control" value="<?php echo e($bimbingan->bagian->bagian); ?>"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Keterangan</label>
                                    <textarea id="summernote" name="keterangan" required>
                                    <?php echo e($bimbingan->keterangan); ?>

                                </textarea>
                                    <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><small><?php echo e($message); ?></small></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lampiran (Format: .pdf, Max: 5Mb)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="lampiran" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['lampiran'];
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
                                    <?php if($bimbingan->lampiran): ?>
                                        <div class="bg-light p-2 rounded">
                                            <small>
                                                <b>Lampiran sebelumnya : </b>
                                    <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" class="ml-3 text-primary"
                                                    target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                    <?php echo e(Str::substr($bimbingan->lampiran, 19)); ?></a>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/bimbingan/edit.blade.php ENDPATH**/ ?>