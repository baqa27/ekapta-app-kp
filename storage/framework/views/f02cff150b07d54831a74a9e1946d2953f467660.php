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

                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>NAMA DOSEN</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUMLAH</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td><?php echo e($dosen->nidn); ?></td>
                                            <td><?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></td>
                                            <td><?php echo e(\App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen)); ?></td>
                                            <td><?php echo e(\App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen, false)); ?>

                                            </td>
                                            <td><?php echo e(\App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen) + \App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen, false)); ?>

                                            </td>
                                            <td>
                                                <?php if(count($dosen->mahasiswas) != 0): ?>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modal-default-<?php echo e($dosen->id); ?>">
                                                        <i class="fas fa-users"></i> List Mahasiswa
                                                    </button>
                                                    <div class="modal fade" id="modal-default-<?php echo e($dosen->id); ?>">
                                                        <div class="modal-dialog" style="max-width: 100%; height: 100%; margin: 0; padding: 0;">
                                                            <div class="modal-content" style="height: 100%; border-radius: 0;">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">List Mahasiswa</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $dosen->mahasiswas()->whereDoesntHave('jilid')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mahasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <div class="p-2 border m-1 border-dark">
                                                                                <?php echo e($mahasiswa->nama . '/' . $mahasiswa->nim); ?>

                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>NAMA DOSEN</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUMLAH</th>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/prodi/bimbingan/rekap-dosen.blade.php ENDPATH**/ ?>