

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
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Bimbingan
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Bimbingan
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Bimbingan
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
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($bimbingan->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $bimbingan->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->prodi->namaprodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->bagian); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo e($bimbingan->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.bimbingan.review', $bimbingan->id)); ?>"
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
                                                <th>Bagian Bimbingan</th>
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
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $bimbingans_diterima; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($bimbingan->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $bimbingan->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->prodi->namaprodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->bagian); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo e($bimbingan->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('kp.bimbingan.review', $bimbingan->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            <?php if(!$bimbingan->mahasiswa->seminar()->where('is_valid', 1)->first()): ?>
                                                            <form action="<?php echo e(route('kp.bimbingan.cancel.acc')); ?>"
                                                                method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($bimbingan->id); ?>">
                                                                <button class="btn btn-danger btn-sm shadow" type="submit">
                                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                                </button>
                                                            </form>
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
                                                <th>Bagian Bimbingan</th>
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
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                            ?>
                                            <?php $__currentLoopData = $bimbingans_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($bimbingan->mahasiswa->nama); ?>

                                                        <?php echo e('(' . $bimbingan->mahasiswa->nim . ')'); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->prodi->namaprodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($bimbingan->bagian->bagian); ?>

                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning"><?php echo e($bimbingan->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="<?php echo e(route('kp.bimbingan.review', $bimbingan->id)); ?>"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            <form action="<?php echo e(route('kp.bimbingan.cancel.revisi')); ?>"
                                                                method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo e($bimbingan->id); ?>">
                                                                <button class="btn btn-danger btn-sm shadow" type="submit">
                                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                                </button>
                                                            </form>
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
                                                <th>Bagian Bimbingan</th>
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/dosen/bimbingan/bimbingan.blade.php ENDPATH**/ ?>