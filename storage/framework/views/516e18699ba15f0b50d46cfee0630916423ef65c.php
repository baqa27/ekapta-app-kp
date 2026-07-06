<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo e($title); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Kelas</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                ?>
                                <?php $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($no++); ?></td>
                                    <td>
                                        <?php echo e($pengajuan->mahasiswa->nama); ?> - <?php echo e($pengajuan->mahasiswa->nim); ?>

                                    </td>
                                    <td>
                                        <?php echo e($pengajuan->prodi->namaprodi); ?>

                                    </td>
                                    <td>
                                        <?php echo e(\App\Helpers\AppHelper::format_kelas_mahasiswa($pengajuan->mahasiswa->kelas ?? null)); ?>

                                    </td>
                                    <td><?php echo e($pengajuan->judul); ?></td>
                                    <td>
                                        <?php if($pengajuan->status == 'review'): ?>
                                        <span class="badge bg-secondary">Review</span>
                                        <?php elseif($pengajuan->status == 'revisi'): ?>
                                        <span class="badge bg-warning">Revisi</span>
                                        <?php elseif($pengajuan->status == 'diterima'): ?>
                                        <span class="badge bg-success">Diterima</span>
                                        <?php elseif($pengajuan->status == 'ditolak'): ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(url('/pengajuan/review-admin/'.$pengajuan->id)); ?>"
                                            class="btn btn-info btn-sm shadow">
                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Kelas</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
<!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/pengajuan/pengajuan.blade.php ENDPATH**/ ?>