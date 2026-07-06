<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.bimbingan.mahasiswa')); ?>">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                <?php if($bimbingan->status == 'review'): ?> bg-secondary
                                <?php elseif($bimbingan->status == 'revisi'): ?> bg-warning
                                <?php elseif($bimbingan->status == 'diterima'): ?> bg-success
                                <?php elseif($bimbingan->status == 'ditolak'): ?> bg-danger <?php endif; ?>">
                                <?php echo e(strtoupper($bimbingan->status)); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>NIM</strong></div>
                                <div class="col-md-9"><?php echo e($bimbingan->mahasiswa->nim); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Nama</strong></div>
                                <div class="col-md-9"><?php echo e($bimbingan->mahasiswa->nama); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Prodi</strong></div>
                                <div class="col-md-9"><?php echo e($bimbingan->mahasiswa->prodi); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Judul KP</strong></div>
                                <div class="col-md-9"><?php echo e($pengajuan ? $pengajuan->judul : '-'); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Dosen Pembimbing</strong></div>
                                <div class="col-md-9"><?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-'); ?></div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Bagian Bimbingan</strong></div>
                                <div class="col-md-9">
                                    <span class="badge bg-primary"><?php echo e($bimbingan->bagian->bagian); ?></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Tanggal Bimbingan</strong></div>
                                <div class="col-md-9"><?php echo e($bimbingan->tanggal_bimbingan ? date('d M Y', strtotime($bimbingan->tanggal_bimbingan)) : '-'); ?></div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Tanggal ACC</strong></div>
                                <div class="col-md-9">
                                    <?php if($bimbingan->tanggal_acc): ?>
                                        <span class="text-success"><?php echo e(date('d M Y', strtotime($bimbingan->tanggal_acc))); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>File Bimbingan</strong></div>
                                <div class="col-md-9">
                                    <?php if($bimbingan->lampiran): ?>
                                        <a href="<?php echo e(storage_url($bimbingan->lampiran)); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> <?php echo e(basename($bimbingan->lampiran)); ?>

                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if($bimbingan->keterangan): ?>
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Keterangan</strong></div>
                                <div class="col-md-9"><?php echo nl2br($bimbingan->keterangan); ?></div>
                            </div>
                            <?php endif; ?>

                            <div class="mt-3 text-secondary">
                                <i class="fas fa-calendar mr-2"></i> Disubmit: <?php echo e($bimbingan->created_at->format('d M Y H:i')); ?>

                            </div>
                        </div>
                    </div>

                    
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill"><?php echo e(count($revisis)); ?></span>
                            </h3>
                            <div class="card-tools">
                                <?php echo e($revisis->links()); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                <?php $__empty_1 = true; $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">
                                                <?php if($revisi->reviewer_type == 'prodi' && $revisi->prodi): ?>
                                                    <span class="badge bg-info">Prodi <?php echo e($revisi->prodi->namaprodi); ?></span>
                                                <?php elseif($revisi->dosen): ?>
                                                    <?php echo e($revisi->dosen->nama . ', ' . $revisi->dosen->gelar); ?>

                                                <?php else: ?>
                                                    Admin
                                                <?php endif; ?>
                                            </span>
                                            <span class="direct-chat-timestamp float-right">
                                                <?php echo e($revisi->created_at->format('d M Y H:i a')); ?>

                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="<?php echo e(asset('ekapta/adminLTE/dist/img/default-profile.png')); ?>"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            <?php echo nl2br($revisi->catatan); ?>

                                            <?php if($revisi->tanggal_bimbingan): ?>
                                                <div>
                                                    <small><i class="fas fa-calendar"></i> Tanggal bimbingan: <?php echo e(\Carbon\Carbon::parse($revisi->tanggal_bimbingan)->format('d M Y')); ?></small>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($revisi->lampiran || $revisi->lampiran_revisi): ?>
                                                <div class="p-1 mt-3 bg-light rounded">
                                                    <?php if($revisi->lampiran_revisi): ?>
                                                        <div>
                                                            <small>
                                                                <span class="text-secondary ml-2"><b>Lampiran revisi: </b></span>
                                                                <a href="<?php echo e(storage_url($revisi->lampiran_revisi)); ?>" target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    <?php echo e(basename($revisi->lampiran_revisi)); ?>

                                                                </a>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($revisi->lampiran): ?>
                                                        <small>
                                                            <span class="text-secondary ml-2"><b>Lampiran bimbingan sebelumnya: </b></span>
                                                            <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip ml-1"></i>
                                                                <?php echo e(basename($revisi->lampiran)); ?>

                                                            </a>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted">Belum ada revisi</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/bimbingan/detail.blade.php ENDPATH**/ ?>