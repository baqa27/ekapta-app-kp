

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
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
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
                <a href="<?php echo e(route('kp.seminar.admin')); ?>" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($seminar->is_valid == 0): ?> bg-secondary
                            <?php elseif($seminar->is_valid == 2): ?>
                            bg-warning
                            <?php elseif($seminar->is_valid == 1): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php if($seminar->is_valid == 0): ?>
                                    review
                                <?php elseif($seminar->is_valid == 1): ?>
                                    diterima
                                <?php elseif($seminar->is_valid == 2): ?>
                                    revisi
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->mahasiswa->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->mahasiswa->prodi); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_utama ? $dosen_utama->nama . ', ' . $dosen_utama->gelar : '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dosen Penguji
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->dosenPenguji ? $seminar->dosenPenguji->nama . ', ' . $seminar->dosenPenguji->gelar : '-'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($seminar->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($seminar->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Seminar KP
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($seminar->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($seminar->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    File Laporan Proposal
                                </div>
                                <div class="col-md-7">
                                    <?php if($seminar->lampiran_3): ?>
                                    <a href="<?php echo e(storage_url($seminar->lampiran_3)); ?>" target="_blank"><i
                                        class="fas fa-paperclip"></i>
                                    <?php echo e(Str::substr($seminar->lampiran_3, 40)); ?></a>
                                    <?php else: ?>
                                    <span class="text-danger">Belum Upload File Laporan Proposal</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nomor Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->nomor_pembayaran); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Jumlah Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b
                                        class="text-success"><?php echo e($seminar->jumlah_bayar ? 'Rp ' . $seminar->jumlah_bayar : ''); ?></b>
                                </div>
                            </div>
                            <hr>

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>

                            <?php if($seminar->tanggal_acc): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Validasi Pendaftaran
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-success"><?php echo e(date('d M Y H:m', strtotime($seminar->tanggal_acc))); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($seminar->tanggal_ujian): ?>
                                <?php
                                    $tanggal_ujian = \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian);
                                ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tanggal Seminar
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger"><?php echo e($tanggal_ujian); ?></b>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tempat Seminar
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger"><?php echo e($seminar->tempat_ujian); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        
                        <?php if($seminar->is_valid == 0): ?>
                            <div class="card-footer">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pendaftaran seminar KP dikelola oleh <strong>Himpunan</strong>. Admin/Prodi hanya dapat melihat data.
                                </div>
                            </div>
                        <?php elseif($seminar->is_valid == 1): ?>
                            
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="<?php echo e(route('kp.cetak.berita.acara.ujian.proposal', $seminar->id)); ?>"
                                        class="btn btn-success mr-2" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Seminar KP
                                    </a>
                                    <a href="<?php echo e(route('kp.cetak.berita.acara.ujian.proposal.blank', [$seminar->id, 1])); ?>"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Seminar KP Kosong
                                    </a>
                                </div>
                            </div>
                        <?php elseif($seminar->is_valid == 2): ?>
                            <div class="card-footer">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Status: <strong>Revisi</strong> - Menunggu mahasiswa memperbaiki dokumen.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($seminar->revisis)); ?>

                                </span>
                            </h3>
                            
                        </div>

                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="card bg-light mb-2">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M Y H:i')); ?>

                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                    </div>
                                    <?php if($revisi->lampiran): ?>
                                        <div class="card-footer">
                                            Lampiran :
                                            <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" class="ml-3" target="_blank">
                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($revisi->lampiran)); ?>

                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-muted text-center">Belum ada revisi</p>
                            <?php endif; ?>
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

    

<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/seminar/review.blade.php ENDPATH**/ ?>