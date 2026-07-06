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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran Ujian Pendadaran TA</a></li>
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
                            <?php if($ujian->is_valid == 0): ?> bg-secondary
                            <?php elseif($ujian->is_valid == 2): ?>
                            bg-warning
                            <?php elseif($ujian->is_valid == 1): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php if($ujian->is_valid == 0): ?>
                                REVIEW
                                <?php elseif($ujian->is_valid == 1): ?>
                                VALID
                                <?php elseif($ujian->is_valid == 2): ?>
                                TIDAK VALID
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->prodi); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Utama (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Pendamping (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Ijazah Terakhir Yang Asli
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_3)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_3, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan KTP / Kartu Keluarga Terbaru
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_4)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_4, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat TOEFL
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_5)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_5, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Tahfidz
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->lampiran_6): ?>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_6)); ?>" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_6, 40)); ?></a>
                                    <?php else: ?>
                                        <span class="text-danger">Belum upload sertifikat tahfidz</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Syahadah Tahfidz 30 Juz (Jika Ada)
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->lampiran_syahadah): ?>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_syahadah)); ?>" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_syahadah, 40)); ?></a>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada (opsional)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Komputer
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_7)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_7, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_8)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_8, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Laporan Skripsi
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_laporan)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_laporan, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>

                            <?php if($ujian->tanggal_acc): ?>
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->tanggal_acc): ?>
                                    <b class="text-success"><?php echo e(date('d M Y H:m', strtotime($ujian->tanggal_acc))); ?></b>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                        </div>
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
                                            <?php echo nl2br($revisi->catatan); ?>

                                            <?php if($revisi->lampiran): ?>
                                                <div class="p-1 mt-3 bg-light rounded">
                                                    <small>
                                                        <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                        <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" target="_blank">
                                                            <i class="fas fa-paperclip ml-1"></i>
                                                            <?php echo e(Str::substr($revisi->lampiran, 16)); ?>

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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/ujian/detail.blade.php ENDPATH**/ ?>