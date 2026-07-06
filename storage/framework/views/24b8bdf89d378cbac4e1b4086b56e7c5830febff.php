

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
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.seminar.mahasiswa')); ?>">Seminar KP</a></li>
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
                                <?php if($seminar->is_valid == 0): ?> bg-secondary
                                <?php elseif($seminar->is_valid == 2): ?> bg-warning
                                <?php elseif($seminar->is_valid == 1): ?> bg-success <?php endif; ?>">
                                <?php if($seminar->is_valid == 0): ?>
                                    REVIEW
                                <?php elseif($seminar->is_valid == 1): ?>
                                    DITERIMA
                                <?php elseif($seminar->is_valid == 2): ?>
                                    REVISI
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">NIM</div>
                                <div class="col-md-8"><b><?php echo e($seminar->mahasiswa->nim); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Nama Lengkap</div>
                                <div class="col-md-8"><b><?php echo e($seminar->mahasiswa->nama); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Prodi</div>
                                <div class="col-md-8"><b><?php echo e($seminar->mahasiswa->prodi); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">No. WhatsApp</div>
                                <div class="col-md-8"><b><?php echo e($seminar->no_wa ?? $seminar->mahasiswa->hp ?? '-'); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Dosen Pembimbing KP</div>
                                <div class="col-md-8"><b><?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-'); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Judul Laporan KP</div>
                                <div class="col-md-8"><b><?php echo e($seminar->judul_laporan ?? $seminar->pengajuan->judul); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Metode Pembayaran</div>
                                <div class="col-md-8"><b><?php echo e($seminar->metode_bayar ?? '-'); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Jumlah Pembayaran</div>
                                <div class="col-md-8"><b class="text-success">Rp <?php echo e(number_format($seminar->jumlah_bayar ?? 25000, 0, ',', '.')); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Pendaftaran</div>
                                <div class="col-md-8"><b><?php echo e($seminar->created_at->format('d M Y H:i')); ?></b></div>
                            </div>
                            <hr>

                            <?php if($seminar->tanggal_acc): ?>
                            <div class="row">
                                <div class="col-md-4">Tanggal Validasi</div>
                                <div class="col-md-8"><b class="text-success"><?php echo e(date('d M Y H:i', strtotime($seminar->tanggal_acc))); ?></b></div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            
                            <?php if($seminar->tanggal_ujian): ?>
                            <div class="row">
                                <div class="col-md-4">Tanggal Seminar</div>
                                <div class="col-md-8"><b><?php echo e($seminar->tanggal_ujian->format('d M Y')); ?></b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Jam Seminar</div>
                                <div class="col-md-8"><b><?php echo e($seminar->tanggal_ujian->format('H:i')); ?> WIB</b></div>
                            </div>
                            <hr>

                            <?php if($seminar->tempat_ujian): ?>
                            <div class="row">
                                <div class="col-md-4">Tempat/Link Seminar</div>
                                <div class="col-md-8"><b><?php echo e($seminar->tempat_ujian); ?></b></div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->urutan_presentasi): ?>
                            <div class="row">
                                <div class="col-md-4">Urutan Presentasi</div>
                                <div class="col-md-8"><b><?php echo e($seminar->urutan_presentasi); ?></b></div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->dosenPenguji): ?>
                            <div class="row">
                                <div class="col-md-4">Dosen Penguji</div>
                                <div class="col-md-8"><b><?php echo e($seminar->dosenPenguji->nama); ?>, <?php echo e($seminar->dosenPenguji->gelar); ?></b></div>
                            </div>
                            <hr>
                            <?php endif; ?>
                            <?php endif; ?>

                            
                            <?php if($seminar->nilai_seminar): ?>
                            <div class="row">
                                <div class="col-md-4">Nilai Seminar</div>
                                <div class="col-md-8">
                                    <b><?php echo e(number_format($seminar->nilai_seminar, 2)); ?></b>
                                    <?php if($seminar->sesiSeminar && $seminar->sesiSeminar->dosenPenguji): ?>
                                    <small class="text-muted">(Penguji: <?php echo e($seminar->sesiSeminar->dosenPenguji->nama ?? '-'); ?>)</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            
                            <?php if($seminar->nilai_instansi): ?>
                            <div class="row">
                                <div class="col-md-4">Nilai Instansi</div>
                                <div class="col-md-8">
                                    <b><?php echo e(number_format($seminar->nilai_instansi, 2)); ?></b>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            
                            <?php if($seminar->file_laporan): ?>
                            <div class="row">
                                <div class="col-md-4">Laporan Final PDF</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->file_laporan)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_laporan)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->file_pengesahan): ?>
                            <div class="row">
                                <div class="col-md-4">Lembar Pengesahan</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->file_pengesahan)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_pengesahan)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->bukti_bayar): ?>
                            <div class="row">
                                <div class="col-md-4">Bukti Pembayaran</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->bukti_bayar)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->bukti_bayar)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->lampiran_1): ?>
                            <div class="row">
                                <div class="col-md-4">Sertifikat 1</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_1)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(Str::substr($seminar->lampiran_1, 40)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->lampiran_2): ?>
                            <div class="row">
                                <div class="col-md-4">Sertifikat 2</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_2)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(Str::substr($seminar->lampiran_2, 40)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->lampiran_3): ?>
                            <div class="row">
                                <div class="col-md-4">Sertifikat 3</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_3)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(Str::substr($seminar->lampiran_3, 40)); ?>

                                    </a>
                                </div>
                            </div>
                            <hr>
                            <?php endif; ?>

                            <?php if($seminar->lampiran_4): ?>
                            <div class="row">
                                <div class="col-md-4">Sertifikat 4</div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_4)); ?>" target="_blank">
                                        <i class="fas fa-paperclip"></i> <?php echo e(Str::substr($seminar->lampiran_4, 40)); ?>

                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill"><?php echo e(count($revisis)); ?></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                <?php $__empty_1 = true; $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">Admin/Himpunan</span>
                                            <span class="direct-chat-timestamp float-right">
                                                <?php echo e($revisi->created_at->format('d M Y H:i a')); ?>

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
                                                        <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($revisi->lampiran)); ?>" target="_blank">
                                                            <i class="fas fa-paperclip ml-1"></i>
                                                            <?php echo e(basename($revisi->lampiran)); ?>

                                                        </a>
                                                    </small>
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





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/seminar/detail.blade.php ENDPATH**/ ?>