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
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>BAB</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $bimbingans_review ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td><?php echo e($bimbingan->mahasiswa->nama ?? '-'); ?> - <?php echo e($bimbingan->mahasiswa->nim ?? '-'); ?></td>
                                            <td><?php echo e($bimbingan->bagian->bagian ?? '-'); ?></td>
                                            <td><?php echo e($bimbingan->tanggal_bimbingan ? \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d M Y') : '-'); ?></td>
                                            <td>
                                                <?php if($bimbingan->status == 'review'): ?>
                                                    <span class="badge bg-secondary">Review</span>
                                                <?php elseif($bimbingan->status == 'diterima'): ?>
                                                    <span class="badge bg-success">Diterima</span>
                                                <?php elseif($bimbingan->status == 'revisi'): ?>
                                                    <span class="badge bg-warning">Revisi</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e($bimbingan->status ?? '-'); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $dosen = $bimbingan->mahasiswa->dosens->first(function($d) {
                                                        return $d->pivot->status == 'pembimbing' || $d->pivot->status == 'utama';
                                                    });
                                                ?>
                                                <?php if($dosen): ?>
                                                    <a href="<?php echo e(route($createRoute ?? 'kp.bimbingan.admin.input.create.ta', [$dosen->id, $bimbingan->mahasiswa->id])); ?>"
                                                       class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data bimbingan</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>BAB</th>
                                        <th>Tanggal Bimbingan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>

    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/bimbingan/bimbingan-input-ta.blade.php ENDPATH**/ ?>