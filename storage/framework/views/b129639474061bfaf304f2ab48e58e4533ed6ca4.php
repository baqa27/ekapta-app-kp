<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengajuan Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
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

            <?php if(count($pengajuans_acc) == 0): ?>
                <a href="<?php echo e(route('pengajuan.create')); ?>" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i> Buat
                    Pengajuan TA</a>
            <?php else: ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Selamat! Pengajuan tugas akhir anda sudah di Acc oleh Prodi, silahkan lakukan <b><a
                            href="<?php echo e(route('pendaftaran.create')); ?>">Pendaftaran Tugas
                            Akhir.</a></b>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pengajuan Anda</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul TA</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal ACC</th>
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
                                            <td><a
                                                    href="<?php echo e(url('/pengajuan/detail/' . $pengajuan->id)); ?>"><?php echo e($pengajuan->judul); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e($pengajuan->created_at->format('d M Y H:m')); ?>

                                            </td>
                                            <td>
                                                <?php if($pengajuan->tanggal_acc != null): ?>
                                                    <?php echo e(date('d M Y H:m', strtotime($pengajuan->tanggal_acc))); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($pengajuan->status == 'review'): ?>
                                                    <span class="badge bg-secondary">Review</span>
                                                <?php elseif($pengajuan->status == 'revisi'): ?>
                                                    <span class="badge bg-warning">Revisi</span>
                                                <?php elseif($pengajuan->status == 'diterima'): ?>
                                                    <span class="badge bg-success">Diterima</span>
                                                <?php elseif($pengajuan->status == 'ditolak'): ?>
                                                    <span class="badge bg-danger">Ditolak</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($pengajuan->status == 'review'): ?>
                                                    <div class="d-flex">
                                                        <a href="<?php echo e(url('/pengajuan/detail/' . $pengajuan->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow mr-2">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <?php if(count($pengajuan->revisis) == 0): ?>
                                                            <div onclick="confirmDelete()">
                                                                <form action="<?php echo e(route('pengajuan.delete')); ?>"
                                                                    method="post">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="hidden" name="id"
                                                                        value="<?php echo e($pengajuan->id); ?>">
                                                                    <button class="btn btn-danger btn-sm shadow"
                                                                        type="submit">
                                                                        <i class="fas fa-trash mr-1"></i>Hapus</button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($pengajuan->status == 'revisi'): ?>
                                                    <a href="<?php echo e(url('/pengajuan/edit/' . $pengajuan->id)); ?>"
                                                        class="btn btn-primary btn-sm shadow" type="submit"><i
                                                            class="fas fa-upload mr-1"></i>Submit</a>
                                                <?php elseif($pengajuan->status == 'diterima'): ?>
                                                    <div class="d-flex">
                                                        <a href="<?php echo e(url('/pengajuan/detail/' . $pengajuan->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow mr-2">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <?php if(count(Auth::guard('mahasiswa')->user()->dosens) != 0): ?>
                                                            <a href="<?php echo e(route('cetak.lembar.persetujuan.mahasiswa')); ?>"
                                                                class="btn btn-success btn-sm shadow" target="_blank">
                                                                <i class="bi bi-download"></i> Lembar Persetujuan Pembimbing
                                                            </a>
                                                        <?php else: ?>
                                                        <a href="#" class="btn btn-secondary btn-sm shadow">
                                                            <i class="bi bi-hourglass-bottom mr-1"></i> Menunggu Ploting Dosen Pembimbing
                                                        </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan'): ?>
                                                    <a href="<?php echo e(url('/pengajuan/detail/' . $pengajuan->id)); ?>"
                                                        class="btn btn-primary btn-sm shadow mr-2">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal ACC</th>
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
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/pengajuan/pengajuan.blade.php ENDPATH**/ ?>