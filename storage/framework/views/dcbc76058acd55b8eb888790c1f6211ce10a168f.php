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
                        <li class="breadcrumb-item"><a href="#">Detail Bimbingan TA</a></li>
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
                        <div class="card-header">
                            Detail Bimbingan TA
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    Nama / NIM
                                </div>
                                <div class="col-md-8">
                                    : <?php echo e($pengajuan->mahasiswa->nama); ?> / <?php echo e($pengajuan->mahasiswa->nim); ?>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    Judul TA
                                </div>
                                <div class="col-md-8">
                                    : <?php echo e($pengajuan->judul); ?>

                                </div>
                            </div>
                            <hr>

                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i> Diterima/Acc
                            </span>
                            <span class="badge badge-secondary">
                                <i class="fas fa-circle mr-1"></i> Review/Belum Di Acc
                            </span>

                            <div class="row justify-content-center mt-4">
                                <div class="col-md-6 border rounded p-2">
                                    Pembimbing Utama : <b><?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?> </b>
                                    <hr>
                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($bimbingan->pembimbing == 'utama'): ?>
                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                    <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" target="_blank">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        <?php echo e($bimbingan->bagian->bagian . ' [ Di Acc pada ' . \Carbon\Carbon::parse($bimbingan->tanggal_acc)->formatLocalized('%d %B %Y')); ?>]
                                                    </span>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    <?php echo e($bimbingan->bagian->bagian); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="col-md-6 border rounded p-2">
                                    Pembimbing Pendamping :
                                    <b><?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?>

                                    </b>
                                    <hr>
                                    <?php $__currentLoopData = $mahasiswa->bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($bimbingan->pembimbing == 'pendamping'): ?>
                                            <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id)): ?>
                                            <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" target="_blank">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        <?php echo e($bimbingan->bagian->bagian . ' [ Di Acc pada ' . \Carbon\Carbon::parse($bimbingan->tanggal_acc)->formatLocalized('%d %B %Y')); ?>]
                                                    </span>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    <?php echo e($bimbingan->bagian->bagian); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/prodi/bimbingan/detail.blade.php ENDPATH**/ ?>