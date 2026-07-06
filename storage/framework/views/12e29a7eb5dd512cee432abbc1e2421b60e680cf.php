

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
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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
                            <h3 class="card-title p-3"><?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_1" data-toggle="tab">Valid (Menunggu Jilid)</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_2" data-toggle="tab">Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($jilid->status == \App\Models\KP\Jilid::JILID_VALID): ?>
                                                    <tr>
                                                        <td><?php echo e($no++); ?></td>
                                                        <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                        <td><?php echo e($jilid->mahasiswa->nama); ?></td>
                                                        <td>
                                                            <span class="badge bg-primary">Menunggu Proses Jilid</span>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo e(route('kp.pengumpulan-akhir.prodi.detail', $jilid->id)); ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                                
                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Total Pembayaran</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $jilids_kp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jilid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI): ?>
                                                    <tr>
                                                        <td><?php echo e($no++); ?></td>
                                                        <td><?php echo e($jilid->mahasiswa->nim); ?></td>
                                                        <td><?php echo e($jilid->mahasiswa->nama); ?></td>
                                                        <td>
                                                            <?php if($jilid->total_pembayaran): ?>
                                                                Rp <?php echo e(number_format($jilid->total_pembayaran, 0, ',', '.')); ?>

                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Selesai</span>
                                                            <?php if($jilid->is_completed): ?>
                                                                <br><small class="text-primary">Sudah setor perpus</small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo e(route('kp.pengumpulan-akhir.prodi.detail', $jilid->id)); ?>" class="btn btn-info btn-sm">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/pengumpulan-akhir/prodi-index.blade.php ENDPATH**/ ?>