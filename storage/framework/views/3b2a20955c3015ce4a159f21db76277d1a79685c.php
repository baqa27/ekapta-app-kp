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
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">

            <?php if($ujian_not_lulus && !$ujian_has_ready): ?>
                <a href="<?php echo e(route('ujian.create')); ?>" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Pendaftaran Ujian TA</a>
            <?php endif; ?>

            <?php if($check_ujian_has_done): ?>
                <div class="mb-3 bg-success rounded p-2">
                    SELAMAT! PROSES PENGAJUAN TA, PENDAFTARAN TA, BIMBINGAN TA, SEMINAR TA, DAN UJIAN TA SUDAH SELESAI,
                    silahkan lakukan <a href="<?php echo e(route('jilid.create')); ?>"><b><u>PENJILIDAN TUGAS AKHIR !</u></b></a>
                </div>
            <?php endif; ?>

            <?php if($reviews_has_acc): ?>
                <div class="mb-3 bg-primary rounded p-2">
                    Selamat bimbingan Ujian Pendadaran anda sudah selesai.
                </div>
            <?php else: ?>
                <div class="mb-3 bg-secondary rounded p-2">
                    Silahkan tunggu review dan penilaian dari dosen pembimbing dan penguji!
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">ujian Proposal Anda</h3>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    Dosen Pembimbing
                                </div>
                                <div class="col-md-10">
                                    1.
                                    <strong><?php echo e($dosen_utama ? $dosen_utama->nama . ' ,' . $dosen_utama->gelar : ''); ?></strong>
                                    <br>
                                    2.
                                    <strong><?php echo e($dosen_pendamping ? $dosen_pendamping->nama . ' ,' . $dosen_pendamping->gelar : ''); ?></strong>
                                </div>
                            </div>

                            

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Tanggal Ujian</th>
                                        <th>Tempat Ujian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $ujians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ujian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td>
                                                <a
                                                    href="<?php echo e(route('ujian.detail', $ujian->id)); ?>"><?php echo e($ujian->pengajuan->judul); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e($ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian) : null); ?>

                                            </td>
                                            <td><?php echo e($ujian->tempat_ujian); ?> </td>
                                            <td>
                                                <?php if($ujian->is_lulus == \App\Models\Ujian::VALID_LULUS): ?>
                                                    <span class="badge bg-success">LULUS</span>
                                                <?php elseif($ujian->is_lulus == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                                    <span class="badge bg-danger">TIDAK LULUS</span>
                                                <?php else: ?>
                                                    <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?>
                                                        <span class="badge bg-secondary">REVIEW</span>
                                                    <?php elseif($ujian->is_valid == \App\Models\Ujian::VALID_LULUS): ?>
                                                        <span class="badge bg-success">VALID</span>
                                                    <?php elseif($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                                        <span class="badge bg-warning">TIDAK VALID</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?>
                                                    <a href="<?php echo e(route('ujian.detail', $ujian->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle"></i> Detail
                                                    </a>
                                                <?php elseif($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                                    <a href="<?php echo e(route('ujian.edit', $ujian->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-upload"></i> Submit
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($check_ujian_has_done && $ujian->is_lulus != \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                                    <a href="<?php echo e(route('ujian.reviews', $ujian->id)); ?>"
                                                        class="btn btn-info btn-sm mb-1">
                                                        <i class="bi bi-star"></i> Lihat Review
                                                    </a>
                                                    <?php if($reviews_has_acc): ?>
                                                        
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Tanggal Ujian</th>
                                        <th>Tempat Ujian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/ujian/ujian.blade.php ENDPATH**/ ?>