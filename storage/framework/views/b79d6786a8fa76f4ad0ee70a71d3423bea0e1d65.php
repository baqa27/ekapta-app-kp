<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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
                            <h3 class="card-title"><strong>Judul Kerja Praktek : </strong><?php echo e($pengajuan->judul); ?></h3>
                        </div>
                        <div class="card-body">
                            <?php
                                $dosenPembimbing = Auth::guard('mahasiswa')->user()->dosenPembimbing();
                            ?>
                            <p><b>Gambaran Singkat</b></p>
                            <?php echo nl2br($pengajuan->deskripsi); ?>

                            <hr>
                            <strong>Lokasi KP</strong> <br>
                            <?php echo e($pengajuan->lokasi_kp); ?>

                            <br><br>
                            <strong>Alamat Instansi</strong> <br>
                            <?php echo e($pengajuan->alamat_instansi); ?>

                            <br><br>
                            <?php if($pengajuan->lampiran): ?>
                                <strong>Bukti Diterima Instansi</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->lampiran)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> <?php echo e(basename($pengajuan->lampiran)); ?>

                                </a>
                                <br><br>
                            <?php endif; ?>
                            <?php if($pengajuan->files_pendukung): ?>
                                <strong>File Pendukung</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->files_pendukung)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> <?php echo e(basename($pengajuan->files_pendukung)); ?>

                                </a>
                                <br><br>
                            <?php endif; ?>
                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                <?php echo e($pengajuan->created_at->format('d M Y H:s')); ?>

                            </div>
                            <?php if($pengajuan->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    <?php echo e(date('d M Y H:s', strtotime($pengajuan->tanggal_acc))); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php if($pengajuan->status == 'diterima' && !$dosenPembimbing): ?>
                                <hr>
                                <div class="alert alert-info mb-0">
                                    <h5><i class="fas fa-hourglass-half mr-2"></i>Menunggu Ploting Dosen Pembimbing</h5>
                                    <p class="mb-0">
                                        Pengajuan KP Anda sudah diterima. Tombol cetak lembar persetujuan dan langkah
                                        pendaftaran akan muncul setelah dosen pembimbing dipilih oleh Prodi.
                                    </p>
                                </div>
                            <?php elseif($pengajuan->status == 'diterima' && $dosenPembimbing): ?>
                                <hr>
                                <div class="alert alert-success">
                                    <h5><i class="fas fa-check-circle mr-2"></i>Pengajuan Diterima!</h5>

                                    <p class="mb-2">
                                        <strong>Dosen Pembimbing:</strong> <?php echo e($dosenPembimbing->nama . ', ' . $dosenPembimbing->gelar); ?>

                                    </p>

                                    <hr>
                                    <p><strong>Langkah Selanjutnya (Persetujuan Pembimbing):</strong></p>
                                    <ol class="mb-3">
                                        <li>Cetak <strong>Lembar Persetujuan Pembimbing</strong> (klik tombol di bawah)</li>
                                        <li>Minta tanda tangan calon dosen pembimbing (offline/online) tergantung permintaan dosen.</li>
                                        <li>Upload lembar persetujuan yang sudah ditandatangani saat Pendaftaran KP</li>
                                    </ol>

                                    <a href="<?php echo e(route('kp.cetak.lembar.persetujuan.mahasiswa')); ?>" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-file-pdf mr-2"></i>Cetak Lembar Persetujuan Pembimbing
                                    </a>

                                    <a href="<?php echo e(route('kp.pendaftaran.mahasiswa')); ?>" class="btn btn-success ml-2">
                                        <i class="fas fa-arrow-right mr-2"></i>Lanjut ke Pendaftaran KP
                                    </a>
                                </div>
                            <?php endif; ?>
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
                                                                <?php echo e(basename($revisi->lampiran)); ?>

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





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/pengajuan/detail.blade.php ENDPATH**/ ?>