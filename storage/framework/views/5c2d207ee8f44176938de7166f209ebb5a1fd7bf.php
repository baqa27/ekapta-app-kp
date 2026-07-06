

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('kp.dashboard.himpunan')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card <?php echo e($is_pendaftaran_open ? 'bg-success' : 'bg-danger'); ?>">
                        <div class="card-body d-flex justify-content-between align-items-center py-2">
                            <span class="text-white">
                                <i class="fas <?php echo e($is_pendaftaran_open ? 'fa-door-open' : 'fa-door-closed'); ?> mr-2"></i>
                                Pendaftaran Seminar KP: <strong><?php echo e($is_pendaftaran_open ? 'DIBUKA' : 'DITUTUP'); ?></strong>
                            </span>
                            <form action="<?php echo e(route('kp.seminar.himpunan.toggle')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm <?php echo e($is_pendaftaran_open ? 'btn-light' : 'btn-warning'); ?>">
                                    <i class="fas <?php echo e($is_pendaftaran_open ? 'fa-lock' : 'fa-unlock'); ?> mr-1"></i>
                                    <?php echo e($is_pendaftaran_open ? 'Tutup Pendaftaran' : 'Buka Pendaftaran'); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel <?php echo e($title); ?></h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_review" data-toggle="tab">
                                        Review
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_revisi" data-toggle="tab">
                                        Revisi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_diterima" data-toggle="tab">
                                        Diterima
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Review -->
                                <div class="tab-pane active" id="tab_review">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $seminars_review; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nim : '-'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nama : 'Mahasiswa tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->prodi : '-'); ?></td>
                                                    <td><?php echo e($seminar->pengajuan ? $seminar->pengajuan->judul : 'Judul tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->created_at->format('d M Y H:i')); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.himpunan.review', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Revisi -->
                                <div class="tab-pane" id="tab_revisi">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $seminars_revisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nim : '-'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nama : 'Mahasiswa tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->prodi : '-'); ?></td>
                                                    <td><?php echo e($seminar->pengajuan ? $seminar->pengajuan->judul : 'Judul tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->created_at->format('d M Y H:i')); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.himpunan.review', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Diterima -->
                                <div class="tab-pane" id="tab_diterima">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Tempat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $seminars_acc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($no++); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nim : '-'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->nama : 'Mahasiswa tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->mahasiswa ? $seminar->mahasiswa->prodi : '-'); ?></td>
                                                    <td><?php echo e($seminar->pengajuan ? $seminar->pengajuan->judul : 'Judul tidak ditemukan'); ?></td>
                                                    <td><?php echo e($seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-'); ?></td>
                                                    <td><?php echo e($seminar->tempat_ujian ?? '-'); ?></td>
                                                    <td>
                                                        <a href="<?php echo e(route('kp.seminar.himpunan.review', $seminar->id)); ?>"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
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





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/himpunan/seminar/index.blade.php ENDPATH**/ ?>