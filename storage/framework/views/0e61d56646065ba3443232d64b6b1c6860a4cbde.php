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
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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
                <a href="<?php echo e(route('kp.pengajuan.admin')); ?>" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
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
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td><?php echo e($pengajuan->mahasiswa->nim); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td><?php echo e($pengajuan->mahasiswa->nama); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td><?php echo e($pengajuan->prodi->namaprodi); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Kelas</b></td>
                                    <td><?php echo e(\App\Helpers\AppHelper::format_kelas_mahasiswa($pengajuan->mahasiswa->kelas ?? null)); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td><?php echo e($pengajuan->judul); ?></td>
                                </tr>

                            </table>
                            <hr>
                            <strong>Lokasi KP</strong> <br>
                            <?php echo e($pengajuan->lokasi_kp); ?>

                            <br><br>
                            <strong>Alamat Instansi</strong> <br>
                            <?php echo e($pengajuan->alamat_instansi); ?> }
                            <br><br>
                            <?php if($pengajuan->lampiran): ?>
                                <strong>Bukti Diterima Instansi</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->lampiran)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> Lihat Bukti Diterima Instansi
                                </a>
                                <br><br>
                            <?php endif; ?>
                            <?php if($pengajuan->files_pendukung): ?>
                                <strong>File Pendukung</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->files_pendukung)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> Lihat File Pendukung
                                </a>
                                <br><br>
                            <?php endif; ?>
                            <hr>
                            <p><b>Gambaran Singkat</b></p>
                            <?php echo nl2br($pengajuan->deskripsi); ?>

                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                <?php echo e($pengajuan->created_at->format('d M y H:m')); ?>

                            </div>
                            <?php if($pengajuan->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    <?php echo e(date('d M y H:m', strtotime($pengajuan->tanggal_acc))); ?>

                                </div>
                            <?php endif; ?>
                            </div>

                    </div>

                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($pengajuan->revisis)); ?>

                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header"><i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M y H:m')); ?>

                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                    </div>
                                    <?php if($revisi->lampiran): ?>
                                    <div class="card-footer">
                                        <small>
                                            Lampiran :
                                            <?php if($revisi->lampiran): ?>
                                                <a href="<?php echo e(storage_url($pengajuan->lampiran)); ?>" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    <?php echo e(basename($revisi->lampiran)); ?></a>
                                            <?php endif; ?>
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
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/pengajuan/review.blade.php ENDPATH**/ ?>