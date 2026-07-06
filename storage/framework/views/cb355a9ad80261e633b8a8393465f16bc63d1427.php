<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bimbingan Tugas Akhir</h1>
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
            <div class="row">
                <div class="col-md-10">
                    <?php if(\App\Helpers\AppHelper::check_bimbingan_is_complete($mahasiswa)): ?>
                        <a href="<?php echo e(route('cetak.riwayat.bimbingan.mahasiswa')); ?>" class="btn btn-warning mb-3"
                            target="_blank"><i class="fas fa-download"></i> DOWNLOAD LEMBAR BIMBINGAN SKRIPSI</a>
                        
                    <?php endif; ?>

                    <?php if($is_expired && !\App\Helpers\AppHelper::check_bimbingan_is_complete($mahasiswa)): ?>
                        <div class="mb-3 bg-danger rounded p-2">
                            Masa bimbingan anda sudah habis, silahkan lakukan <a
                                href="<?php echo e(route('pendaftaran.disable', $pendaftaran_acc->id)); ?>"><u><b>Perpanjangan
                                        TA!</b></u></a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Tanggal Berakhir Bimbingan : <b><?php echo e(\Carbon\Carbon::parse($date_expired)->locale('id')->isoFormat('D MMMM Y')); ?>

                            </b>
                            <?php if($is_seminar): ?>
                                , Selamat anda sudah bisa melakukan
                                <b><a href="<?php echo e(route('seminar.create')); ?>">Pendaftaran Seminar TA</a></b>
                            <?php endif; ?>
                            <?php if($is_ujian): ?>
                                , <b><a href="<?php echo e(route('ujian.create')); ?>">Pendaftaran Ujian Pendadaran</a></b>
                            <?php endif; ?>
                            <?php if($check_ujian_has_done): ?>
                                , <b><a href="<?php echo e(route('jilid.create')); ?>"> Ajukan Penjilidan Tugas Akhir</a></b>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-center mb-3 bg-primary rounded p-2 countdown"
                            data-expire="<?php echo e(\Carbon\Carbon::parse($date_expired)->endOfDay()->format('Y/m/d H:i:s')); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3">Bimbingan Anda</h3>
                                    <ul class="nav nav-pills ml-auto p-2">
                                        <li class="nav-item"><a class="nav-link active" href="#tab_1"
                                                data-toggle="tab">Pembimbing
                                                Utama</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pembimbing
                                                Pendamping</a>
                                        </li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">

                                            Dosen Pembimbing : <strong>
                                                <?php if($dosen_utama): ?>
                                                    <?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?>

                                                <?php endif; ?>
                                            </strong>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no = 1;
                                                    ?>
                                                    <?php $__currentLoopData = $bimbingans_utama; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($bimbingan->bagian->bagian); ?></td>
                                                            <td>
                                                                <?php if($bimbingan->tanggal_bimbingan): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_bimbingan))); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($bimbingan->tanggal_acc): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_acc))); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($bimbingan->status == 'review'): ?>
                                                                    <span class="badge bg-secondary">Review</span>
                                                                <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                    <span class="badge bg-warning">Revisi</span>
                                                                <?php elseif($bimbingan->status == 'diterima'): ?>
                                                                    <span class="badge bg-success">Diterima</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(!$is_expired): ?>
                                                                    <?php if($bimbingan->status == 'review'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                         <?php if($dosen_utama->is_manual): ?>
                                                                            <a href="<?php echo e(route('bimbingan.submit.acc.manual', $bimbingan->id)); ?>"
                                                                                class="btn btn-primary btn-sm shadow">
                                                                                <i class="fas fa-upload"></i> Input Acc
                                                                                Manual
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-primary btn-sm shadow"
                                                                            type="submit"><i
                                                                                class="fas fa-upload mr-1"></i>Submit</a>
                                                                    <?php elseif($bimbingan->status == 'diterima'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                        
                                                                    <?php elseif($no - 1 == 1): ?>
                                                                        <?php if($bimbingan->status == null): ?>
                                                                            <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                                class="btn btn-primary btn-sm shadow"
                                                                                type="submit"><i
                                                                                    class="fas fa-upload mr-1"></i>Submit</a>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if(count(\App\Helpers\AppHelper::instance()->getBimbinganIsAcc($bimbingan->mahasiswa->id)) > 1): ?>
                                                                        <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->mahasiswa->nim) == false): ?>
                                                                            <?php if($bimbingan->status == null): ?>
                                                                                <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                                    class="btn btn-primary btn-sm shadow"
                                                                                    type="submit"><i
                                                                                        class="fas fa-upload mr-1"></i>Submit</a>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_2">

                                            Dosen Pembimbing : <strong>
                                                <?php if($dosen_pendamping): ?>
                                                    <?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?>

                                                <?php endif; ?>
                                            </strong>

                                            <table id="example2" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $no = 1;
                                                    ?>
                                                    <?php $__currentLoopData = $bimbingans_pendamping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bimbingan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($no++); ?></td>
                                                            <td><?php echo e($bimbingan->bagian->bagian); ?></td>
                                                            <td>
                                                                <?php if($bimbingan->tanggal_bimbingan): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_bimbingan))); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($bimbingan->tanggal_acc): ?>
                                                                    <?php echo e(date('d M Y', strtotime($bimbingan->tanggal_acc))); ?>

                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($bimbingan->status == 'review'): ?>
                                                                    <span class="badge bg-secondary">Review</span>
                                                                <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                    <span class="badge bg-warning">Revisi</span>
                                                                <?php elseif($bimbingan->status == 'diterima'): ?>
                                                                    <span class="badge bg-success">Diterima</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if(!$is_expired): ?>
                                                                    <?php if($bimbingan->status == 'review'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow mr-2">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                         <?php if($dosen_pendamping->is_manual): ?>
                                                                            <a href="<?php echo e(route('bimbingan.submit.acc.manual', $bimbingan->id)); ?>"
                                                                                class="btn btn-primary btn-sm shadow">
                                                                                <i class="fas fa-upload"></i> Input Acc
                                                                                Manual
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    <?php elseif($bimbingan->status == 'revisi'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-primary btn-sm shadow"
                                                                            type="submit"><i
                                                                                class="fas fa-upload mr-1"></i>Submit</a>
                                                                    <?php elseif($bimbingan->status == 'diterima'): ?>
                                                                        <a href="<?php echo e(url('/bimbingan/detail/' . $bimbingan->id)); ?>"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                        
                                                                    <?php elseif($no - 1 == 1): ?>
                                                                        <?php if($bimbingan->status == null): ?>
                                                                            <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                                class="btn btn-primary btn-sm shadow"
                                                                                type="submit"><i
                                                                                    class="fas fa-upload mr-1"></i>Submit</a>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if(count(\App\Helpers\AppHelper::instance()->getBimbinganIsAcc($bimbingan->mahasiswa->id)) > 1): ?>
                                                                        <?php if(\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->mahasiswa->nim) == false): ?>
                                                                            <?php if($bimbingan->status == null): ?>
                                                                                <a href="<?php echo e(url('/bimbingan/edit/' . $bimbingan->id)); ?>"
                                                                                    class="btn btn-primary btn-sm shadow"
                                                                                    type="submit"><i
                                                                                        class="fas fa-upload mr-1"></i>Submit</a>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>

                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center mb-3 card" style="max-height: 300px;">
                    <div class="text-center mt-3">
                        <a href="<?php echo e(url('public/riwayat-bimbingan/' . base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid())); ?>"
                            class="btn btn-secondary btn-sm shadow mb-2" target="_blank">
                            Tracking Bimbingan <small><i class="bi bi-chevron-right"></i></small>
                        </a>
                        <p class="text-secondary">Atau scan QRCODE dibawah:</p>
                        <div class="mb-3">
                            <?php echo QrCode::size(150)->generate(
                                url('public/riwayat-bimbingan/' . base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid()),
                            ); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/bimbingan/bimbingan.blade.php ENDPATH**/ ?>