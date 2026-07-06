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
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pengajuan
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pengajuan
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Pengajuan
                                        Revisi</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Pengajuan
                                        Ditolak</a></li>
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
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.pengajuan.review', $pengajuan->id)); ?>"
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
                                                <th>Kelas</th>
                                                <th>Judul</th>
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
                                            <?php $__currentLoopData = $pengajuans_acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('kp.pengajuan.review', $pengajuan->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            <?php if($pengajuan->pendaftaran == null): ?>
                                                                <?php if(count($pengajuan->mahasiswa->dosens) == 0): ?>
                                                                    <div onclick="confirmCancel()">
                                                                        <form action="<?php echo e(route('kp.pengajuan.cancel.acc')); ?>"
                                                                            method="post">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="id"
                                                                                value="<?php echo e($pengajuan->id); ?>">
                                                                            <button
                                                                                class="btn btn-danger btn-sm mr-2 shadow"
                                                                                type="submit">
                                                                                <i class="bi bi-x-circle mr-1"></i> Batalkan
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
                                                <th>Kelas</th>
                                                <th>Judul</th>
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
                                            <?php $__currentLoopData = $pengajuans_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.pengajuan.review', $pengajuan->id)); ?>"
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
                                                <th>Kelas</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_4">

                                    <table id="example4" class="table table-bordered">
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
                                            <?php $__currentLoopData = $pengajuans_ditolak; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('kp.pengajuan.review', $pengajuan->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            <?php if($pengajuan->status != 'diterima'): ?>
                                                                <div onclick="confirmCancel()">
                                                                    <form action="<?php echo e(route('kp.pengajuan.cancel.tolak')); ?>"
                                                                        method="post">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="id"
                                                                            value="<?php echo e($pengajuan->id); ?>">
                                                                        <input type="hidden" name="mahasiswa_id"
                                                                            value="<?php echo e($pengajuan->mahasiswa->id); ?>">
                                                                        <button class="btn btn-danger btn-sm mr-2 shadow"
                                                                            type="submit">
                                                                            <i class="bi bi-x-circle mr-1"></i> Batalkan
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
                                                <th>Kelas</th>
                                                <th>Judul</th>
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/prodi/pengajuan/pengajuan.blade.php ENDPATH**/ ?>