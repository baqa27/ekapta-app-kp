

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.seminar.himpunan')); ?>">Seminar KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                <?php if($seminar->is_valid == 0): ?> bg-secondary
                                <?php elseif($seminar->is_valid == 1): ?> bg-success
                                <?php elseif($seminar->is_valid == 2): ?> bg-warning
                                <?php endif; ?>">
                                <?php if($seminar->is_valid == 0): ?> Review
                                <?php elseif($seminar->is_valid == 1): ?> Diterima
                                <?php elseif($seminar->is_valid == 2): ?> Revisi
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="250">NIM</td>
                                    <td><b><?php echo e($seminar->mahasiswa->nim); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td><b><?php echo e($seminar->mahasiswa->nama); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Prodi</td>
                                    <td><b><?php echo e($seminar->mahasiswa->prodi); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Dosen Pembimbing</td>
                                    <td><b>
                                        <?php
                                            $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                            if (!$dosen_pembimbing) {
                                                $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'utama')->first();
                                            }
                                        ?>
                                        <?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-'); ?>

                                    </b></td>
                                </tr>
                                <tr>
                                    <td>Judul Kerja Praktek</td>
                                    <td><b><?php echo e($seminar->pengajuan->judul); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><b><?php echo e($seminar->mahasiswa->email); ?></b></td>
                                </tr>
                                <tr>
                                    <td>No. HP</td>
                                    <td><b><?php echo e($seminar->mahasiswa->hp); ?></b></td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td width="250">File Laporan KP</td>
                                    <td>
                                        <?php if($seminar->file_laporan): ?>
                                            <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->file_laporan)); ?>" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_laporan)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lembar Pengesahan</td>
                                    <td>
                                        <?php if($seminar->file_pengesahan): ?>
                                            <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->file_pengesahan)); ?>" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_pengesahan)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bukti Pembayaran</td>
                                    <td>
                                        <?php if($seminar->bukti_bayar): ?>
                                            <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->bukti_bayar)); ?>" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->bukti_bayar)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sertifikat Seminar KP 1</td>
                                    <td>
                                        <?php if($seminar->lampiran_1): ?>
                                            <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_1)); ?>" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_1)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sertifikat Seminar KP 2</td>
                                    <td>
                                        <?php if($seminar->lampiran_2): ?>
                                            <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_2)); ?>" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_2)); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($seminar->lampiran_3): ?>
                                <tr>
                                    <td>Sertifikat Seminar KP 3</td>
                                    <td>
                                        <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_3)); ?>" target="_blank">
                                            <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_3)); ?>

                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($seminar->lampiran_4): ?>
                                <tr>
                                    <td>Sertifikat Seminar KP 4</td>
                                    <td>
                                        <a href="<?php echo e(App\Helpers\AppHelper::instance()->storageUrl($seminar->lampiran_4)); ?>" target="_blank">
                                            <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_4)); ?>

                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if($seminar->link_akses_produk): ?>
                                <tr>
                                    <td>Link Produk KP</td>
                                    <td>
                                        <a href="<?php echo e($seminar->link_akses_produk); ?>" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> <?php echo e($seminar->link_akses_produk); ?>

                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Jumlah Bayar</td>
                                    <td><b>Rp <?php echo e(number_format($seminar->jumlah_bayar ?? 0, 0, ',', '.')); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pendaftaran</td>
                                    <td><b><?php echo e($seminar->created_at->format('d M Y H:i')); ?></b></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Validasi</td>
                                    <td><b><?php echo e($seminar->tanggal_acc ? date('d M Y H:i', strtotime($seminar->tanggal_acc)) : '-'); ?></b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="<?php echo e(route('kp.seminar.himpunan')); ?>" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                                </a>

                                <?php if($seminar->is_valid == 0 || $seminar->is_valid == 2): ?>
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modal-revisi">
                                        <i class="fas fa-edit mr-1"></i> Revisi Pendaftaran
                                    </button>
                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-acc">
                                        <i class="fas fa-check mr-1"></i> Acc Pendaftaran
                                    </button>
                                <?php endif; ?>

                                <?php if($seminar->is_valid == 1): ?>
                                    <a href="<?php echo e(route('kp.jadwal.himpunan')); ?>" class="btn btn-primary">
                                        <i class="fas fa-calendar-alt mr-1"></i> Lihat Penjadwalan
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Info Penilaian -->
                    <?php if($seminar->is_lulus == 1): ?>
                    <div class="card card-info card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><strong>Informasi Penilaian</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Input nilai KP dilakukan oleh <strong>Prodi</strong>. Silahkan hubungi prodi untuk input nilai.
                            </div>
                            
                            <?php if($seminar->nilai_instansi || $seminar->nilai_pembimbing || $seminar->nilai_penguji): ?>
                            <hr>
                            <table class="table table-borderless mb-0">
                                <?php if($seminar->nilai_instansi): ?>
                                <tr>
                                    <td width="200"><strong>Nilai Instansi</strong></td>
                                    <td>: <span class="text-success font-weight-bold"><?php echo e(number_format($seminar->nilai_instansi, 2)); ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($seminar->nilai_pembimbing): ?>
                                <tr>
                                    <td><strong>Nilai Pembimbing</strong></td>
                                    <td>: <span class="text-success font-weight-bold"><?php echo e(number_format($seminar->nilai_pembimbing, 2)); ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($seminar->nilai_penguji): ?>
                                <tr>
                                    <td><strong>Nilai Penguji</strong></td>
                                    <td>: <span class="text-success font-weight-bold"><?php echo e(number_format($seminar->nilai_penguji, 2)); ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($seminar->nilai_akhir): ?>
                                <tr>
                                    <td><strong>Nilai Akhir</strong></td>
                                    <td>: <span class="text-primary font-weight-bold" style="font-size: 1.2rem;"><?php echo e(number_format($seminar->nilai_akhir, 2)); ?></span></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Riwayat Revisi -->
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill ml-2">
                                    <?php echo e(count($seminar->revisis)); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="card bg-light mb-2">
                                <div class="card-header">
                                    <i class="fas fa-calendar mr-2"></i> <?php echo e($revisi->created_at->format('d M Y H:i')); ?>

                                </div>
                                <div class="card-body">
                                    <?php echo nl2br($revisi->catatan); ?>

                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted text-center">Belum ada revisi</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.seminar.himpunan.revisi')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($seminar->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi Pendaftaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="4" required placeholder="Catatan revisi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-warning">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal ACC -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('kp.seminar.himpunan.acc')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($seminar->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">ACC Pendaftaran</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan ACC (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/himpunan/seminar/review.blade.php ENDPATH**/ ?>