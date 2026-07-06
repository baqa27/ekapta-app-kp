

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Jilid KP</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
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
                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP(Auth::guard('mahasiswa')->user());
            ?>

            <?php if(!$jilid): ?>
                <a href="<?php echo e(route('kp.pengumpulan-akhir.create')); ?>" class="btn btn-primary mb-4">
                    <i class="fas fa-plus mr-2"></i> Submit Jilid KP
                </a>
            <?php else: ?>
                <?php if($jilid->status == 4): ?>
                    <div class="alert alert-success">
                        <h5><i class="fas fa-check-circle mr-2"></i> JILID KP SELESAI</h5>
                        Proses jilid KP Anda sudah selesai. Silahkan ambil ke Fotokopi dan bayar sebesar
                        <strong>Rp <?php echo e(number_format($jilid->total_pembayaran ?? 0, 0, ',', '.')); ?></strong>
                    </div>
                <?php elseif($jilid->status == 3): ?>
                    <div class="alert alert-primary">
                        <h5><i class="fas fa-info-circle mr-2"></i> DOKUMEN VALID - MENUNGGU PROSES JILID</h5>
                        Dokumen KP sudah dikonfirmasi oleh admin dan siap untuk dijilid. Silahkan konfirmasi dan
                        melakukan pembayaran ke <strong>Fotokopi FASTIKOM</strong> dengan membawa dokumen-dokumen asli
                        yang akan disertakan dalam penjilidan KP seperti lembar keaslian KP, lembar pengesahan, lembar
                        bimbingan, lampiran-lampiran, dll.
                        <br><small class="text-muted">Tunggu sampai proses jilid selesai oleh petugas fotokopi.</small>
                    </div>
                <?php elseif($jilid->status == 2): ?>
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i> DOKUMEN PERLU REVISI</h5>
                        Dokumen Anda perlu diperbaiki. Silakan submit ulang dengan mengklik tombol "Submit Revisi".
                    </div>
                <?php elseif($jilid->status == 1): ?>
                    <div class="alert alert-secondary">
                        <h5><i class="fas fa-hourglass-half mr-2"></i> MENUNGGU REVIEW ADMIN</h5>
                        Dokumen Anda sedang direview oleh Admin. Tunggu konfirmasi melalui halaman ini.
                        <br><small class="text-muted">Pastikan file upload Google Drive akses dibuat PUBLIC!</small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Jilid KP Anda</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Tanggal Submit</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php $__currentLoopData = $jilids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id)); ?>">
                                                    <?php echo e($j->mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul ?? '-'); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($j->created_at->format('d M Y H:i')); ?></td>
                                            <td>
                                                <?php if($j->status == 1): ?>
                                                    <span class="badge bg-secondary">Review</span>
                                                <?php elseif($j->status == 2): ?>
                                                    <span class="badge bg-warning">Revisi</span>
                                                <?php elseif($j->status == 3): ?>
                                                    <span class="badge bg-primary">Valid</span>
                                                <?php elseif($j->status == 4): ?>
                                                    <span class="badge bg-success">Selesai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($j->status == 1): ?>
                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                <?php elseif($j->status == 2): ?>
                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id)); ?>"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.edit', $j->id)); ?>"
                                                        class="btn btn-success btn-sm shadow">
                                                        <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                    </a>
                                                <?php elseif($j->status == 3 || $j->status == 4): ?>
                                                    <a href="<?php echo e(route('kp.pengumpulan-akhir.detail.mahasiswa', $j->id)); ?>"
                                                        class="btn btn-primary btn-sm">
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
                                        <th>Judul Kerja Praktek</th>
                                        <th>Tanggal Submit</th>
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





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/pengumpulan-akhir/index.blade.php ENDPATH**/ ?>