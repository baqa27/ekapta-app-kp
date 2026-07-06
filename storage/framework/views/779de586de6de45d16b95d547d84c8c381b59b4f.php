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
                            
                            Tabel <?php echo e($title); ?>

                        </div>
                        <div class="card-body">

                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUDUL SKRIPSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $no = 1;
                                    ?>
                                    <?php $__currentLoopData = $seminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $dosen_utama = $seminar->mahasiswa->dosens()->where('status', 'utama')->first();
                                        $dosen_pendamping = $seminar->mahasiswa->dosens()->where('status', 'pendamping')->first();
                                    ?>
                                        <tr>
                                            <td><?php echo e($no++); ?></td>
                                            <td><?php echo e($seminar->mahasiswa->nim); ?></td>
                                            <td><?php echo e($seminar->mahasiswa->nama); ?></td>
                                            <td><?php echo e($seminar->mahasiswa->prodi); ?></td>
                                            <td><?php echo e($dosen_utama ? $dosen_utama->nama.', '.$dosen_utama->gelar : null); ?></td>
                                            <td><?php echo e($dosen_pendamping ? $dosen_pendamping->nama.', '.$dosen_pendamping->gelar : null); ?></td>
                                            <td><?php echo e($seminar->pengajuan->judul); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUDUL SKRIPSI</th>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/seminar/rekap.blade.php ENDPATH**/ ?>