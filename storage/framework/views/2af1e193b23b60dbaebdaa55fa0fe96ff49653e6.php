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

            <?php if(count($pendaftarans_review_acc_revisi) == 0 ): ?>
                <a href="<?php echo e(route('pendaftaran.create')); ?>" class="btn btn-primary mb-4"><i
                        class="fas fa-plus mr-2"></i>
                    <?php echo e($title); ?></a>
            <?php endif; ?>

            <?php if(count($pendaftaranIsAcc) != 0): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Selamat! Pendaftaran tugas akhir anda sudah di Acc oleh Admin, anda bisa memulai <b><a
                            href="<?php echo e(route('bimbingan.mahasiswa')); ?>">Bimbingan Tugas
                            Akhir.</a></b>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pendaftaran Anda</h3>
                        </div>
                        <div class="card-body">

                            <table>
                                <tr>
                                    <td><span class="mr-2">Dosen Pembimbing Utama</span></td>
                                    <td><span class="mr-2">:</span></td>
                                    <td><strong>
                                            <?php if($dosen_utama): ?>
                                                <?php echo e($dosen_utama->nama.', '.$dosen_utama->gelar); ?>

                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td><span class="mr-2">Dosen Pembimbing Pendamping</span></td>
                                    <td><span class="mr-2">:</span></td>
                                    <td><strong>
                                            <?php if($dosen_pendamping): ?>
                                                <?php echo e($dosen_pendamping->nama.', '.$dosen_pendamping->gelar); ?>

                                            <?php endif; ?>
                                        </strong>
                                    </td>
                                </tr>
                            </table>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul TA</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Acc</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $no=1;
                                ?>
                                <?php $__currentLoopData = $pendaftarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendaftaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($no++); ?></td>
                                        <td>
                                            <a href="<?php echo e(url('pendaftaran/detail/'.$pendaftaran->id)); ?>"><?php echo e($pendaftaran->pengajuan->judul); ?></a>
                                        </td>
                                        <td><?php echo e($pendaftaran->created_at->format('d M Y H:m')); ?></td>
                                        <td>
                                            <?php if($pendaftaran->tanggal_acc): ?>
                                                <?php echo e(date('d M Y H:m', strtotime($pendaftaran->tanggal_acc))); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($pendaftaran->status =='diterima'): ?>
                                                <span class="badge bg-success">Diterima</span>

                                            <?php elseif($pendaftaran->status =='revisi'): ?>
                                                <span class="badge bg-warning">Revisi</span>

                                            <?php elseif($pendaftaran->status =='review'): ?>
                                                <span class="badge bg-secondary">Review</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($pendaftaran->status =='diterima'): ?>
                                                <a href="<?php echo e(route('cetak.surat.tugas.bimbingan')); ?>"
                                                   class="btn btn-success btn-sm" target="_blank"><i
                                                        class="fas fa-download mr-1"></i>
                                                    Surat Tugas Bimbingan TA</a>

                                            <?php elseif($pendaftaran->status =='revisi'): ?>
                                                <a href="<?php echo e(url('pendaftaran/edit/'.$pendaftaran->id)); ?>"
                                                   class="btn btn-primary btn-sm"><i class="fa fa-upload mr-1"></i>
                                                    Submit Revisi</a>

                                            <?php elseif($pendaftaran->status =='review'): ?>
                                                <a href="<?php echo e(url('pendaftaran/detail/'.$pendaftaran->id)); ?>"
                                                   class="btn btn-primary btn-sm"><i
                                                        class="fas fa-info-circle mr-1"></i>Detail</a>

                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Pendaftaran</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Tanggal Acc</th>
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
<!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/pendaftaran/pendaftaran.blade.php ENDPATH**/ ?>