<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($pengajuan->status == 'review'): ?> bg-secondary
                            <?php elseif($pengajuan->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($pengajuan->status == 'diterima'): ?>
                            bg-success
                            <?php elseif($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan'): ?>
                            bg-danger <?php endif; ?>
                            ">
                                <?php echo e($pengajuan->status); ?>

                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title"><strong>Judul Tugas Akhir : </strong><?php echo e($pengajuan->judul); ?></h3>
                        </div>
                        <div class="card-body">
                            <p><b>Deskripsi</b></p>
                            <?php echo nl2br($pengajuan->deskripsi); ?>

                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                <?php echo e($pengajuan->created_at->format('d M Y H:s')); ?>

                            </div>
                            <?php if($pengajuan->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    <?php echo e(date('d M Y H:s', strtotime($pengajuan->tanggal_acc))); ?>

                                </div>
                            <?php endif; ?>
                            <hr>
                            <p class="mt-3"><b>Lampiran : </b> <a href="<?php echo e(storage_url($pengajuan->lampiran)); ?>" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i>
                                    <?php echo e(Str::substr($pengajuan->lampiran, 40)); ?></a></p>
                        </div>

                    </div>

                    
                    <?php if($pengajuan->status != 'dibatalkan'): ?>
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Revisi</b>
                                    <span class="badge bg-danger rounded-pill">
                                        <?php echo e(count($revisis)); ?>

                                    </span>
                                </h3>

                                <div class="card-tools">
                                    <?php echo e($revisis->links()); ?>

                                </div>
                            </div>

                            <div class="card-body">
                                <div class="p-2">

                                    <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-infos clearfix">
                                                <span
                                                    class="direct-chat-name float-left"><?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->mahasiswa->nim)->prodi); ?></span>
                                                <span class="direct-chat-timestamp float-right">
                                                    <?php echo e($revisi->created_at->format('d M Y H:m a')); ?>

                                                </span>
                                            </div>
                                            <img class="direct-chat-img"
                                                src="<?php echo e(asset('ekapta/adminLTE/dist/img/default-profile.png')); ?>"
                                                alt="message user image">
                                            <div class="direct-chat-text p-2">
                                                <?php echo nl2br($revisi->catatan); ?>

                                                <?php if($revisi->lampiran): ?>
                                                    <div class="p-1 mt-3 bg-light rounded">
                                                        <small>
                                                            <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                            <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip ml-1"></i>
                                                                <?php echo e(Str::substr($revisi->lampiran, 40)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                            </div>

                        </div>
                    <?php endif; ?>

                    
                    <?php if($pengajuan->status == 'dibatalkan'): ?>
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Riwayat Bimbingan</b>
                                </h3>
                            </div>

                            <div class="card-body row">

                                <?php $__currentLoopData = $pengajuan->bimbingan_canceleds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 p-2 rounded border mb-2">
                                        <span class="badge bg-info"><?php echo e($bimbingan->bagian->bagian); ?></span>
                                        <?php if($bimbingan->status == 'review'): ?>
                                            <span class="badge bg-secondary">Review</span>
                                        <?php elseif($bimbingan->status == 'revisi'): ?>
                                            <span class="badge bg-warning">Revisi</span>
                                        <?php elseif($bimbingan->status == 'diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum melakukan bimbingan</span>
                                        <?php endif; ?>
                                        <?php if($bimbingan->lampiran): ?>
                                            <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" target="_blank"><i class="fas fa-download"></i>
                                                Lampiran</a>
                                        <?php endif; ?>
                                        <br>Dosen pembimbing <?php echo e($bimbingan->pembimbing); ?> :
                                        <?php echo e($bimbingan->dosen->nama . ',' . $bimbingan->dosen->gelar); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/pengajuan/detail.blade.php ENDPATH**/ ?>