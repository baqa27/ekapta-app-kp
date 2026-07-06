<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                    <a href="<?php echo e(route('ujian.rekap')); ?>" class="btn btn-success btn-sm shadow mt-3" target="_blank">
                        <i class="bi bi-people"></i> Rekap Pendaftaran Ujian Pendadaran Mahasiswa
                    </a>
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
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Tempat Ujian</th>
                                    <th>Nilai</th>
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
                                            <?php echo e($ujian->mahasiswa->nama); ?> - <?php echo e($ujian->mahasiswa->nim); ?>

                                        </td>
                                        <td>
                                            <?php echo e($ujian->mahasiswa->prodi); ?>

                                        </td>
                                        <td><?php echo e($ujian->pengajuan->judul); ?></td>
                                        <td>
                                            <?php echo e(date('d M Y H:i', strtotime($ujian->created_at))); ?>

                                        </td>
                                        <td>
                                            <?php echo e($ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian) : null); ?>

                                        </td>
                                        <td><?php echo e($ujian->tempat_ujian); ?></td>
                                        <td class="text-center">
                                            <b><?php echo e(count($ujian->reviews()->where('status','diterima')->get()) >= 5 ? \App\Helpers\AppHelper::hitung_nilai_mahasiswa($ujian)['nilai'] : null); ?></b>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="<?php echo e(route('ujian.review.admin', $ujian->id)); ?>"
                                                    class="btn btn-info btn-sm shadow mr-2">
                                                    <i class="fas fa-check-circle mr-1"></i> Review
                                                </a>
                                                <a href="<?php echo e(route('ujian.prodi.detail' , $ujian->id)); ?>"
                                                class="btn btn-primary btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Tempat Ujian</th>
                                    <th>Nilai</th>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/prodi/ujian/ujian.blade.php ENDPATH**/ ?>