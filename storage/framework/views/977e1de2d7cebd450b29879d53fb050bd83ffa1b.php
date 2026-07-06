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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
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
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($pendaftaran->status == 'review'): ?> bg-secondary
                            <?php elseif($pendaftaran->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($pendaftaran->status == 'diterima'): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php echo e($pendaftaran->status); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    NIM
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e(Auth::guard('mahasiswa')->user()->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e(Auth::guard('mahasiswa')->user()->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Prodi
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e(Auth::guard('mahasiswa')->user()->prodi); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dosen Pembimbing Kerja Praktek
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e($dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : 'Belum ditentukan'); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Judul Kerja Praktek
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e($pendaftaran->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dokumen Acc. Kaprodi
                                </div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Transkrip Nilai
                                </div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Sertifikat KKL
                                </div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_3)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->lampiran_3, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Pembayaran Kerja Praktek
                                </div>
                                <div class="col-md-8">
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_5)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->lampiran_5, 40)); ?></a>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Diterima Instansi
                                </div>
                                <div class="col-md-8">
                                    <?php if($pendaftaran->lampiran_7): ?>
                                    <a href="<?php echo e(storage_url($pendaftaran->lampiran_7)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->lampiran_7, 40)); ?></a>
                                    <?php else: ?>
                                    -
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dokumen Pendukung
                                </div>
                                <div class="col-md-8">
                                    <?php if($pendaftaran->dokumen_pendukung): ?>
                                    <a href="<?php echo e(storage_url($pendaftaran->dokumen_pendukung)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i> <?php echo e(Str::substr($pendaftaran->dokumen_pendukung, 40)); ?></a>
                                    <?php else: ?>
                                    -
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Pembayaran
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e(\Carbon\Carbon::parse($pendaftaran->tanggal_pembayaran)->format('d-m-Y')); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Biaya
                                </div>
                                <div class="col-md-8">
                                    <span class="text-success fs-5">Rp, <?php echo e($pendaftaran->biaya); ?>,-</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-8">
                                    <b><?php echo e($pendaftaran->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-8">
                                    <?php if($pendaftaran->tanggal_acc): ?>
                                        <b
                                            class="text-success"><?php echo e(date('d M Y H:m', strtotime($pendaftaran->tanggal_acc))); ?></b>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>
                        <?php if($pendaftaran->status == 'diterima'): ?>
                            <div class="card-footer">
                                <a href="<?php echo e(route('kp.cetak.surat.tugas.bimbingan')); ?>" class="btn btn-success btn-sm"
                                    target="_blank"><i class="fas fa-download mr-1"></i>
                                    Surat Tugas Bimbingan KP</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    
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
                                            <span class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                <?php echo e($revisi->created_at->format('d M Y H:m a')); ?>

                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="<?php echo e(asset('ekapta/adminLTE/dist/img/default-profile.png')); ?>"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            <?php echo nl2br($revisi->keterangan); ?>

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

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/pendaftaran/detail.blade.php ENDPATH**/ ?>