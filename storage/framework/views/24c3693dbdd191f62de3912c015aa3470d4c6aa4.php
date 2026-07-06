<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengajuan Kerja Praktek</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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

            <?php
                // Cek apakah ada pengajuan aktif (review/diterima/revisi)
                $hasActivePengajuan = $pengajuans->whereIn('status', ['review', 'diterima', 'revisi'])->count() > 0;
                // Bisa ajukan lagi kalau tidak ada pengajuan aktif (ditolak bisa kirim lagi tanpa batas)
                $bisaAjukanLagi = !$hasActivePengajuan;
                $dosenPembimbing = Auth::guard('mahasiswa')->user()->dosenPembimbing();
            ?>

            <?php if(count($pengajuans_acc) == 0): ?>
                <?php if($bisaAjukanLagi): ?>
                    <a href="<?php echo e(route('kp.pengajuan.create')); ?>" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i> Buat
                        Pengajuan KP</a>
                <?php endif; ?>
            <?php else: ?>
                <?php if($dosenPembimbing): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Selamat! Pengajuan kerja Praktek anda sudah di Acc oleh Prodi dan dosen pembimbing sudah dipilih.
                        Silahkan lakukan <b><a href="<?php echo e(route('kp.pendaftaran.create')); ?>">Pendaftaran Kerja Praktek.</a></b>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Pengajuan kerja Praktek anda sudah di Acc oleh Prodi. Saat ini masih menunggu ploting dosen
                        pembimbing, jadi tombol cetak lembar persetujuan akan muncul setelah dosen pembimbing dipilih.
                    </div>
                <?php endif; ?>
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
                                        <th>Judul KP</th>
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
                                                    href="<?php echo e(route('kp.pengajuan.detail', $pengajuan->id)); ?>"><?php echo e($pengajuan->judul); ?></a>
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
                                                        <a href="<?php echo e(route('kp.pengajuan.detail', $pengajuan->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow mr-2">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <?php if(count($pengajuan->revisis) == 0): ?>
                                                            <div onclick="confirmDelete()">
                                                                <form action="<?php echo e(route('kp.pengajuan.delete')); ?>"
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
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <a href="<?php echo e(route('kp.pengajuan.detail', $pengajuan->id)); ?>"
                                                           class="btn btn-primary btn-sm shadow mr-2 mb-1">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <a href="<?php echo e(route('kp.pengajuan.edit', $pengajuan->id)); ?>"
                                                           class="btn btn-success btn-sm shadow mb-1"><i
                                                                class="fas fa-upload mr-1"></i>Submit Revisi</a>
                                                    </div>
                                                <?php elseif($pengajuan->status == 'diterima'): ?>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <a href="<?php echo e(route('kp.pengajuan.detail', $pengajuan->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow mr-2 mb-1">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <?php if($dosenPembimbing): ?>
                                                            <a href="<?php echo e(route('kp.cetak.lembar.persetujuan.mahasiswa')); ?>"
                                                                class="btn btn-success btn-sm shadow mb-1" target="_blank">
                                                                <i class="fas fa-download mr-1"></i> Lembar Persetujuan
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="#" class="btn btn-secondary btn-sm shadow mb-1 disabled" aria-disabled="true">
                                                                <i class="bi bi-hourglass-bottom mr-1"></i> Menunggu Ploting Dosen
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan'): ?>
                                                    <div class="d-flex">
                                                        <a href="<?php echo e(route('kp.pengajuan.detail', $pengajuan->id)); ?>"
                                                            class="btn btn-primary btn-sm shadow mr-2">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                        <?php if($pengajuan->status == 'ditolak'): ?>
                                                            <a href="<?php echo e(route('kp.cetak.surat.penolakan', $pengajuan->id)); ?>"
                                                                class="btn btn-secondary btn-sm shadow" target="_blank">
                                                                <i class="fas fa-file-alt mr-1"></i> Surat Penolakan
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
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





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/pengajuan/pengajuan.blade.php ENDPATH**/ ?>