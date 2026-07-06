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
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Seminar
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Seminar
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Seminar
                                        Revisi</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_review; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $review->seminar->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->pengajuan->judul); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo e($review->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if($review->dosen_status == 'penguji'): ?>
                                                            <a href="<?php echo e(route('review.seminar.dosen', $review->id)); ?>"
                                                               class="btn btn-primary btn-sm shadow">
                                                                <i class="fas fa-star mr-1"></i> Review
                                                            </a>
                                                        <?php elseif($review->dosen_status == 'pembimbing'): ?>
                                                            <a href="<?php echo e(route('review.seminar.dosen', $review->id)); ?>"
                                                               class="btn btn-primary btn-sm shadow">
                                                                <i class="fas fa-star mr-1"></i>Input Nilai
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $review->seminar->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->pengajuan->judul); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo e($review->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('review.seminar.dosen', $review->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-star mr-1"></i> Input Nilai
                                                            </a>
                                                            <?php if($review->dosen_status == 'penguji' && $review->seminar->lampiran_laporan == null): ?>
                                                                <div onclick="return confirmCancel()">
                                                                    <form action="<?php echo e(route('review.seminar.cancel.acc')); ?>"
                                                                          method="post">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="id"
                                                                               value="<?php echo e($review->id); ?>">
                                                                        <button class="btn btn-danger btn-sm shadow" type="submit">
                                                                            <i class="bi bi-x-circle"></i> Batalkan
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            <?php endif; ?>
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
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_3">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $seminars_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $review->seminar->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->mahasiswa->prodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($review->seminar->pengajuan->judul); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning"><?php echo e($review->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('review.seminar.dosen', $review->id)); ?>"
                                                                class="btn btn-info btn-sm shadow mr-2">
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
                                                <th>Judul TA</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/dosen/seminar/seminar.blade.php ENDPATH**/ ?>