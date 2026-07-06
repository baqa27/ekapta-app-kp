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
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="<?php echo e(route('kp.bimbingan.dosen')); ?>" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($bimbingan->status == 'review'): ?> bg-secondary
                            <?php elseif($bimbingan->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($bimbingan->status == 'diterima'): ?>
                            bg-success
                            <?php elseif($bimbingan->status == 'ditolak'): ?>
                            bg-danger <?php endif; ?>
                            ">
                                <?php echo e($bimbingan->status); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td><?php echo e($bimbingan->mahasiswa->nim); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td><?php echo e($bimbingan->mahasiswa->nama); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td><?php echo e($bimbingan->mahasiswa->prodi); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td><?php echo e($pengajuan->judul); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Bagian</b></td>
                                    <td><?php echo e($bimbingan->bagian->bagian); ?></td>
                                </tr>
                            </table>
                            <hr>

                            <b>Keterangan</b> <br>
                            <div class="p-2 rounded" style="background-color: #dbdbdb">
                                <?php echo nl2br($bimbingan->keterangan); ?>

                            </div>

                            <div class="mt-4 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                Tanggal Submit <b><?php echo e(date('d M Y', strtotime($bimbingan->tanggal_bimbingan))); ?></b>
                            </div>

                            <?php if($bimbingan->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Acc <b><?php echo e(date('d M Y', strtotime($bimbingan->tanggal_acc))); ?></b>
                                </div>
                            <?php endif; ?>
                            <hr>

                            <p class="mt-3"><b>Lampiran : </b> <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i>
                                    <?php echo e(basename($bimbingan->lampiran)); ?></a></p>

                            <hr>
                            <div class="bordered mt-2">
                                <b>Bagian Bimbingan Kerja Praktek</b>

                                <div class="mt-2 border p-2 rounded">

                                    <?php
                                        $dosenPembimbing = $bimbingan->mahasiswa->dosens()->where('dosen_id', Auth::guard('dosen')->user()->id)->first();
                                    ?>

                                    <?php
                                        $pivotStatus = $dosenPembimbing->pivot->status;
                                    ?>
                                    
                                    <?php if($pivotStatus == 'utama'): ?>
                                        <?php
                                            $bimbingansGrouped = $mahasiswa->bimbingansKP()->where('pembimbing', 'utama')->orderBy('id', 'desc')->get()->unique('bagian_id')->sortBy('bagian_id');
                                        ?>
                                        <?php $__currentLoopData = $bimbingansGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbinganMahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAccKP($bimbinganMahasiswa->id)): ?>
                                                <a href="<?php echo e(storage_url($bimbinganMahasiswa->lampiran)); ?>"
                                                    class="badge badge-success mr-1" target="_blank">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary mr-1">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                    <?php elseif($pivotStatus == 'pendamping'): ?>
                                        <?php
                                            $bimbingansGrouped = $mahasiswa->bimbingansKP()->where('pembimbing', 'pendamping')->orderBy('id', 'desc')->get()->unique('bagian_id')->sortBy('bagian_id');
                                        ?>
                                        <?php $__currentLoopData = $bimbingansGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbinganMahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAccKP($bimbinganMahasiswa->id)): ?>
                                                <a href="<?php echo e(storage_url($bimbinganMahasiswa->lampiran)); ?>"
                                                    class="badge badge-success mr-1" target="_blank">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary mr-1">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                    <?php elseif($pivotStatus == 'pembimbing'): ?>
                                        <?php
                                            $bimbingansGrouped = $mahasiswa->bimbingansKP()->orderBy('id', 'desc')->get()->unique('bagian_id')->sortBy('bagian_id');
                                        ?>
                                        <?php $__currentLoopData = $bimbingansGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbinganMahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAccKP($bimbinganMahasiswa->id)): ?>
                                                <a href="<?php echo e(storage_url($bimbinganMahasiswa->lampiran)); ?>"
                                                    class="badge badge-success mr-1" target="_blank">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary mr-1">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    <?php echo e($bimbinganMahasiswa->bagian->bagian); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="<?php echo e(route('kp.bimbingan.dosen')); ?>" class="btn btn-secondary mr-2">
                                        <i class="bi bi-arrow-left mr-2"></i> Kembali
                                </a>
                                <?php if($bimbingan->status == 'review'): ?>
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi bimbingan
                                    </button>

                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc bimbingan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($bimbingan->revisis)); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <span class="mr-5">Direview oleh <b>Anda</b>
                                        </span>
                                        <div class="float-right">
                                            <div class="d-flex">
                                                <span class="mr-3">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                                </span>
                                                <?php if($revisi->dosen->id == Auth::guard('dosen')->user()->id): ?>
                                                    
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                        <?php if($revisi->tanggal_bimbingan): ?>
                                            <div class="mt-4 text-secondary">
                                                <small><i class="fas fa-calendar mr-2"></i> Tanggal Bimbingan <b><?php echo e(date('d M Y', strtotime($revisi->tanggal_bimbingan))); ?></b></small>
                                            </div>
                                        <?php endif; ?>
                                        <?php if($revisi->lampiran_revisi): ?>
                                            <small>
                                                Lampiran revisi:
                                                <?php if($revisi->lampiran_revisi): ?>
                                                    <a href="<?php echo e(storage_url($bimbingan->lampiran_revisi)); ?>" class="ml-3" target="_blank"><i
                                                            class="fas fa-paperclip"></i>
                                                        <?php echo e(basename($revisi->lampiran_revisi)); ?></a>
                                                <?php endif; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($revisi->lampiran): ?>
                                    <div class="card-footer">
                                        <small>
                                            Lampiran bimbingan sebelumnya:
                                                <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    <?php echo e(basename($revisi->lampiran)); ?></a>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <?php echo e($revisis->links()); ?>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bimbingan.revisi.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($bimbingan->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="catatan" id="catatan" rows="4" placeholder="Catatan revisi" required></textarea>
                            <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Lampiran (Opsional)</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="lampiran">
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
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

    <!-- Modal Acc -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.bimbingan.acc')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($bimbingan->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Acc bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" required></textarea>
                            <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/dosen/bimbingan/review.blade.php ENDPATH**/ ?>