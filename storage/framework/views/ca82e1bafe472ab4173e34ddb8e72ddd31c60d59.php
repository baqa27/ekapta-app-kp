

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                    <a href="<?php echo e(route('kp.seminar.rekap')); ?>" class="btn btn-success btn-sm shadow mt-3" target="_blank">
                        <i class="bi bi-people"></i> Rekap Pendaftaran Seminar Mahasiswa
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

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Catatan:</strong> Mahasiswa <strong>Kelas Karyawan</strong> tidak perlu mengikuti seminar KP dan tidak akan muncul dalam daftar ini.
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Seminar KP
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Seminar KP
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Seminar KP
                                        Revisi</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">

                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_review; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->nama); ?> - <?php echo e($seminar->mahasiswa->nim); ?>

                                                        <?php if($is_karyawan): ?>
                                                            <br><small class="badge badge-info">Kelas Karyawan</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                                    <td>
                                                        <?php echo e(date('d M Y H:i', strtotime($seminar->created_at))); ?>

                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.review.admin', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-check-circle mr-1"></i> Review
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
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">

                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Tempat Seminar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->nama); ?> - <?php echo e($seminar->mahasiswa->nim); ?>

                                                        <?php if($is_karyawan): ?>
                                                            <br><small class="badge badge-info">Kelas Karyawan</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                                    <td>
                                                        <?php echo e(date('d M Y H:i', strtotime($seminar->created_at))); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-'); ?>

                                                    </td>
                                                    <td><?php echo e($seminar->tempat_ujian ?? '-'); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.review.admin', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow">
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
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Tempat Seminar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_3">

                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->nama); ?> - <?php echo e($seminar->mahasiswa->nim); ?>

                                                        <?php if($is_karyawan): ?>
                                                            <br><small class="badge badge-info">Kelas Karyawan</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                                    <td>
                                                        <?php echo e(date('d M Y H:i', strtotime($seminar->created_at))); ?>

                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.review.admin', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow">
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
                                                <th>Judul</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/seminar/seminar.blade.php ENDPATH**/ ?>