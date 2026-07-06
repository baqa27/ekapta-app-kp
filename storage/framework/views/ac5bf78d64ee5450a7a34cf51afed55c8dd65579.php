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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel <?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM / NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>DOSEN PEMBIMBING</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $dosen->mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($mahasiswa->bimbingans()->whereNotNull('lampiran_acc')->where('status', 'review')->whereHas('dosens', function ($query) use ($dosen) {
                                                        $query->where('dosen_id', $dosen->id);
                                                    })->first()): ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($mahasiswa->nim.'/'.$mahasiswa->nama); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($mahasiswa->prodi); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?>

                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route($createRoute ?? 'bimbingan.admin.input.create', [$dosen->id, $mahasiswa->id])); ?>" class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-upload"></i> Input Bimbingan
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM / NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>DOSEN PEMBIMBING</th>
                                        <th>AKSI</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/bimbingan/bimbingan-input.blade.php ENDPATH**/ ?>