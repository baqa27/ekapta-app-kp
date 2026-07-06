

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo e($title); ?></a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

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
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Seminar Aktif</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Seminar Selesai</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- TAB SEMINAR AKTIF -->
                                <div class="tab-pane active" id="tab_1">
                                    <table id="table-seminar-aktif" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $seminars_aktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->nama); ?>

                                                        <?php if($is_karyawan): ?>
                                                            <span class="badge badge-info ml-1">Karyawan</span>
                                                        <?php endif; ?>
                                                        <br><small class="text-muted"><?php echo e($seminar->mahasiswa->nim); ?></small>
                                                    </td>
                                                    <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                                    <td><?php echo e($seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-'); ?></td>
                                                    <td>
                                                        <?php if($is_karyawan): ?>
                                                            <span class="badge badge-warning">Menunggu Penilaian</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-primary">Sedang Berjalan</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.prodi.detail', $seminar->id)); ?>" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- TAB SEMINAR SELESAI -->
                                <div class="tab-pane" id="tab_2">
                                    <table id="table-seminar-selesai" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $seminars_selesai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP($seminar->mahasiswa);
                                                ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td>
                                                        <?php echo e($seminar->mahasiswa->nama); ?>

                                                        <?php if($is_karyawan): ?>
                                                            <span class="badge badge-info ml-1">Karyawan</span>
                                                        <?php endif; ?>
                                                        <br><small class="text-muted"><?php echo e($seminar->mahasiswa->nim); ?></small>
                                                    </td>
                                                    <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                                    <td><?php echo e($seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-'); ?></td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.prodi.detail', $seminar->id)); ?>" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(function () {
        var dataTableConfig = {
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
        };

        // DataTable untuk Tab Seminar Aktif - cek dulu apakah sudah diinisialisasi
        if (!$.fn.DataTable.isDataTable('#table-seminar-aktif')) {
            $('#table-seminar-aktif').DataTable(dataTableConfig);
        }
        
        // DataTable untuk Tab Seminar Selesai - cek dulu apakah sudah diinisialisasi
        if (!$.fn.DataTable.isDataTable('#table-seminar-selesai')) {
            $('#table-seminar-selesai').DataTable(dataTableConfig);
        }
    });
</script>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/prodi/seminar/seminar.blade.php ENDPATH**/ ?>