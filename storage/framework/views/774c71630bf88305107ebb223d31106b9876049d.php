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
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pendaftaran
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pendaftaran
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Pendaftaran
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
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $pendaftarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendaftaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->nama); ?> - <?php echo e($pendaftaran->mahasiswa->nim); ?>

                                                        <?php else: ?>
                                                            Mahasiswa tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->prodi); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->pengajuan): ?>
                                                            <?php echo e($pendaftaran->pengajuan->judul); ?>

                                                        <?php else: ?>
                                                            Judul tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($pendaftaran->status_pendaftaran_label); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->status == 'review'): ?>
                                                            <span class="badge bg-secondary">Review</span>
                                                        <?php elseif($pendaftaran->status == 'revisi'): ?>
                                                            <span class="badge bg-warning">Revisi</span>
                                                        <?php elseif($pendaftaran->status == 'diterima'): ?>
                                                            <span class="badge bg-success">Diterima</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.pendaftaran.review', $pendaftaran->id)); ?>"
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
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
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
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $pendaftarans_acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendaftaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->nama); ?> - <?php echo e($pendaftaran->mahasiswa->nim); ?>

                                                        <?php else: ?>
                                                            Mahasiswa tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->prodi); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->pengajuan): ?>
                                                            <?php echo e($pendaftaran->pengajuan->judul); ?>

                                                        <?php else: ?>
                                                            Judul tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($pendaftaran->status_pendaftaran_label); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->status == 'review'): ?>
                                                            <span class="badge bg-secondary">Review</span>
                                                        <?php elseif($pendaftaran->status == 'revisi'): ?>
                                                            <span class="badge bg-warning">Revisi</span>
                                                        <?php elseif($pendaftaran->status == 'diterima'): ?>
                                                            <span class="badge bg-success">Diterima</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('kp.pendaftaran.review', $pendaftaran->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>

                                                            <?php if($pendaftaran->mahasiswa): ?>
                                                                <?php
                                                                    $cekBimbinganIsActive = \App\Helpers\AppHelper::instance()
                                                                        ->getMahasiswa($pendaftaran->mahasiswa->nim)
                                                                        ->bimbingans()
                                                                        ->whereIn('status', ['review', 'revisi', 'diterima'])
                                                                        ->get();
                                                                ?>
                                                                <?php if(count($cekBimbinganIsActive) == 0): ?>
                                                                    <div onclick="confirmCancel()">
                                                                        <form action="<?php echo e(route('kp.pendaftaran.cancel.acc')); ?>"
                                                                            method="post">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="id"
                                                                                value="<?php echo e($pendaftaran->id); ?>">
                                                                            <button class="btn btn-danger btn-sm shadow">
                                                                                <i class="bi bi-x-circle mr-1"></i>Batalkan
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                <?php endif; ?>
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
                                                <th>Judul</th>
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
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
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $pendaftarans_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendaftaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->nama); ?> - <?php echo e($pendaftaran->mahasiswa->nim); ?>

                                                        <?php else: ?>
                                                            Mahasiswa tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->mahasiswa): ?>
                                                            <?php echo e($pendaftaran->mahasiswa->prodi); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($pendaftaran->pengajuan): ?>
                                                            <?php echo e($pendaftaran->pengajuan->judul); ?>

                                                        <?php else: ?>
                                                            Judul tidak ditemukan
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($pendaftaran->status_pendaftaran_label); ?></td>
                                                    <td>
                                                        <?php if($pendaftaran->status == 'review'): ?>
                                                            <span class="badge bg-secondary">Review</span>
                                                        <?php elseif($pendaftaran->status == 'revisi'): ?>
                                                            <span class="badge bg-warning">Revisi</span>
                                                        <?php elseif($pendaftaran->status == 'diterima'): ?>
                                                            <span class="badge bg-success">Diterima</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.pendaftaran.review', $pendaftaran->id)); ?>"
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
                                                <th>Status Pendaftaran</th>
                                                <th>Status</th>
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/admin/pendaftaran/pendaftaran.blade.php ENDPATH**/ ?>