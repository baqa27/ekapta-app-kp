<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seminar Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar TA</a></li>
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

            <?php if($is_ujian): ?>
                <div class="mb-3 bg-success rounded p-2">
                    Selamat anda sudah bisa melakukan pendaftaran Ujian Pendadaran TA. Silahkan lakukan pendaftaran
                    <a href="<?php echo e(route('ujian.create')); ?>"><b><u>Ujian Pendadaran TA!</u></b></a>
                </div>
            <?php endif; ?>

            <?php if(count($reviews_acc) < 2): ?>
                <div class="mb-3 bg-secondary rounded p-2">
                    Silahkan tunggu review dan penilaian dari dosen pembimbing dan penguji!
                </div>
            <?php else: ?>
                <div class="mb-3 bg-primary rounded p-2">
                    Selamat bimbingan Seminar TA anda sudah selesai.
                </div>
            <?php endif; ?>

            <?php if(!$seminar): ?>
                <a href="<?php echo e(route('seminar.create')); ?>" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Pendaftaran Seminar Proposal</a>
            <?php else: ?>
                <?php if($seminar->is_valid == 0): ?>
                    <div class="mb-3 bg-primary rounded p-2">
                        Anda sudah melakukan pendaftaran Seminar TA, silahkan tunggu validasi dari Admin.
                    </div>
                <?php elseif($seminar->is_valid == 2): ?>
                    <div class="mb-3 bg-warning rounded p-2">
                        Silahkan revisi pendaftaran Seminar TA anda sesuai instruksi dari admin, kemudian submit ulang!
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar Proposal Anda</h3>
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

                                    <?php if($seminar): ?>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <a
                                                    href="<?php echo e(route('seminar.detail', $seminar->id)); ?>"><?php echo e($seminar->pengajuan->judul); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e($seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : null); ?>

                                            </td>
                                            <td><?php echo e($seminar->tempat_ujian); ?></td>
                                            <td>
                                                <?php if($seminar->is_lulus == 1): ?>
                                                    <span class="badge bg-success">LULUS</span>
                                                <?php else: ?>
                                                    <?php if($seminar->is_valid == 0): ?>
                                                        <span class="badge bg-secondary">REVIEW</span>
                                                    <?php elseif($seminar->is_valid == 1): ?>
                                                        <span class="badge bg-success">VALID</span>
                                                    <?php elseif($seminar->is_valid == 2): ?>
                                                        <span class="badge bg-warning">TIDAK VALID</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($seminar->is_valid == 0): ?>
                                                    <a href="<?php echo e(route('seminar.detail', $seminar->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle"></i> Detail
                                                    </a>
                                                <?php elseif($seminar->is_valid == 1): ?>
                                                    <?php if($check_ujian_has_done): ?>
                                                        <a href="<?php echo e(route('seminar.reviews', $seminar->id)); ?>"
                                                            class="btn btn-info btn-sm mb-1">
                                                            <i class="bi bi-star"></i> Lihat Review
                                                        </a>
                                                        <?php if($reviews_has_acc): ?>
                                                            
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php elseif($seminar->is_valid == 2): ?>
                                                    <a href="<?php echo e(route('seminar.edit', $seminar->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-upload"></i> Submit
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/seminar/seminar.blade.php ENDPATH**/ ?>