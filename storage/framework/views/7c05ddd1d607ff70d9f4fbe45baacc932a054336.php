

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
                            <h3 class="card-title">Setting Dosen</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                NIDN
                                            </div>
                                            <div class="col-md-9">
                                                <b><?php echo e($dosen->nidn); ?></b>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                Nama Dosen
                                            </div>
                                            <div class="col-md-9">
                                                <b><?php echo e($dosen->nama); ?>, <?php echo e($dosen->gelar); ?></b>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                Prodi
                                            </div>
                                            <div class="col-md-9">
                                                <b><?php echo e(\App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi) != null ? \App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi)->namaprodi : ''); ?></b>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info btn-sm mr-2 mb-1" data-toggle="modal"
                                                data-target="#modal-edit">
                                            <i class="bi bi-pencil-square mr-2"></i> Edit TTD Dosen
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <img
                                            src="<?php echo e(asset($dosen->ttd != null ? $dosen->ttd : 'ekapta/assets/img/not-found.png')); ?>"
                                            alt="Stempel Fakultas" height="100">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">Penugasan Dosen</div>
                        <div class="card-body">
                            <form action="<?php echo e(route('dosen.prodi.update', $dosen->id)); ?>" method="post">
                                <?php echo method_field('PUT'); ?>
                                <?php echo csrf_field(); ?>
                                <table id="example1" class="table">
                                    <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Prodi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $dosen->prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen_prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="prodis[]"
                                                       value="<?php echo e($dosen_prodi->id); ?>"
                                                       class="form-check" checked>
                                            </td>
                                            <td><?php echo e($dosen_prodi->namaprodi); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="prodis[]"
                                                       value="<?php echo e($prodi->id); ?>"
                                                       class="form-check">
                                            </td>
                                            <td><?php echo e($prodi->namaprodi); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <button class="btn btn-primary" type="submit">Simpan</button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Modal Edit Dosen-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('dosen.update', $dosen->id)); ?>" method="post" enctype="multipart/form-data">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h4 class="modal-title">TTD Dosen </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Edit TTD Dosen</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['ttd'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="ttd" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                
                            </div>
                            <?php $__errorArgs = ['ttd'];
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
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/dosen/setting.blade.php ENDPATH**/ ?>