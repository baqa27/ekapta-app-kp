<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(config('app.name')); ?></h1>
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
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1"><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="mt-3 row">
                                <div class="col-12 p-3 rounded border">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            NIM/NAMA MAHASISWA
                                        </div>
                                        <div class="col-md-9">
                                            <b><?php echo e($mahasiswa->nim . '/' . $mahasiswa->nama); ?></b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            PRODI
                                        </div>
                                        <div class="col-md-9">
                                            <b><?php echo e($prodi ? $prodi->namaprodi : '-'); ?></b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            JUDUL SKRIPSI
                                        </div>
                                        <div class="col-md-9">
                                            <b><?php echo e($pengajuan ? $pengajuan->judul : '-'); ?></b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            TANGGAL DAFTAR
                                        </div>
                                        <div class="col-md-9">
                                            <b><?php echo e($pengajuan ? \App\Helpers\AppHelper::parse_date($pengajuan->pendaftaran->created_at) : '-'); ?></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            TANGGAL BERAKHIR BIMBINGAN
                                        </div>
                                        <div class="col-md-9">
                                            <?php if($pendaftaran): ?>
                                                <?php if($is_expired): ?>
                                                    <span class="badge bg-danger">TIDAK AKTIF</span>
                                                <?php else: ?>
                                                    <span class="bg-primary rounded badge bg-primary countdown"
                                                        data-expire="<?php echo e(\Carbon\Carbon::parse($date_expired)->endOfDay()->format('Y/m/d H:i:s')); ?>">
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="col-12 mt-3 mb-3">
                                    <span class="badge badge-success"> <i class="fas fa-check-circle mr-1"></i>
                                        Diterima/Acc
                                    </span>
                                    <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                                        Belum Bimbingan/Review/Belum Di Acc
                                    </span>
                                </div>
                                <div class="col-md-6 p-3 rounded border mb-2">
                                    <?php if($dosen_utama): ?>
                                        Pembimbing 1: <b><?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?></b><hr>
                                        <?php $__currentLoopData = $mahasiswa->bimbingans()->where('pembimbing', 'utama')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="#"
                                                class="btn <?php echo e($bimbingan->status == 'diterima' ? 'btn-success' : 'btn-secondary'); ?> mb-3 btn-sm"><i
                                                    class="fas <?php echo e($bimbingan->status == 'diterima' ? 'fa-check-circle' : 'fa-circle'); ?>"></i>
                                                <?php echo e($bimbingan->bagian->bagian); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        TIDAK ADA BIMBINGAN
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 p-3 rounded border mb-2">
                                    <?php if($dosen_pendamping): ?>
                                        Pembimbing 2: <b><?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?></b><hr>
                                        <?php $__currentLoopData = $mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="#"
                                                class="btn <?php echo e($bimbingan->status == 'diterima' ? 'btn-success' : 'btn-secondary'); ?> mb-3 btn-sm"><i
                                                    class="fas <?php echo e($bimbingan->status == 'diterima' ? 'fa-check-circle' : 'fa-circle'); ?>"></i>
                                                <?php echo e($bimbingan->bagian->bagian); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        TIDAK ADA BIMBINGAN
                                    <?php endif; ?>
                                </div>
                            </div>
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

<?php echo $__env->make('layouts.dashboardFotokopi', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/public/detail.blade.php ENDPATH**/ ?>