

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seminar Kerja Praktek</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">

            <?php
                $is_karyawan = \App\Helpers\AppHelper::isKaryawanKP(Auth::guard('mahasiswa')->user());
            ?>

            <?php if($is_karyawan): ?>
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info-circle"></i> Informasi Kelas Karyawan</h5>
                    Mahasiswa Program Kelas Karyawan <strong>TIDAK PERLU</strong> mengikuti Seminar KP.
                    Silahkan langsung melanjutkan ke tahap <b><a href="<?php echo e(route('kp.pengumpulan-akhir.mahasiswa')); ?>" class="text-white" style="text-decoration: underline;">Jilid KP / Pengumpulan Akhir</a></b> setelah semua bimbingan selesai (ACC).
                </div>
            <?php endif; ?>

            
            <?php if(!$seminar && !$is_karyawan): ?>
                <?php if($is_pendaftaran_open): ?>
                    <a href="<?php echo e(route('kp.seminar.create')); ?>" class="btn btn-primary mb-4">
                        <i class="fas fa-plus mr-2"></i> Pendaftaran Seminar KP
                    </a>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-door-closed mr-2"></i>
                        <strong>Pendaftaran Seminar KP belum dibuka.</strong> Silahkan tunggu pengumuman dari Himpunan.
                    </div>
                <?php endif; ?>
            <?php elseif($seminar): ?>
                
                <?php if($seminar->status_seminar == 'selesai' || $seminar->status_seminar == 'selesai_seminar'): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Selamat! Seminar KP anda sudah selesai. Silahkan lanjut ke <b><a href="<?php echo e(route('kp.pengumpulan-akhir.create')); ?>">Jilid KP.</a></b>
                    </div>
                <?php elseif($seminar->status_seminar == 'revisi'): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Pendaftaran perlu <strong>direvisi</strong>. Silahkan perbaiki sesuai catatan dari Himpunan.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar KP Anda</h3>
                        </div>
                        <div class="card-body">

                            
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Laporan</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jadwal Seminar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($seminar): ?>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <a href="<?php echo e(route('kp.seminar.detail', $seminar->id)); ?>">
                                                    <?php echo e(Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 50)); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($seminar->created_at->format('d M Y H:i')); ?></td>
                                            <td>
                                                <?php if($seminar->tanggal_ujian): ?>
                                                    <strong><?php echo e($seminar->tanggal_ujian->format('d M Y')); ?></strong><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> <?php echo e($seminar->tanggal_ujian->format('H:i')); ?> WIB
                                                        <?php if($seminar->tempat_ujian): ?>
                                                            <br><i class="fas fa-map-marker-alt"></i> <?php echo e($seminar->tempat_ujian); ?>

                                                        <?php endif; ?>
                                                        <?php if($seminar->urutan_presentasi): ?>
                                                            <br><i class="fas fa-list-ol"></i> Urutan: <?php echo e($seminar->urutan_presentasi); ?>

                                                        <?php endif; ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted"><i class="fas fa-hourglass-half"></i> Menunggu jadwal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php switch($seminar->status_seminar):
                                                    case ('menunggu_verifikasi'): ?>
                                                        <span class="badge bg-secondary">Review</span>
                                                        <?php break; ?>
                                                    <?php case ('diterima'): ?>
                                                        <span class="badge bg-success">Diterima</span>
                                                        <?php break; ?>
                                                    <?php case ('revisi'): ?>
                                                        <span class="badge bg-warning">Revisi Berkas</span>
                                                        <?php break; ?>
                                                    <?php case ('dijadwalkan'): ?>
                                                        <span class="badge bg-info">Dijadwalkan</span>
                                                        <?php break; ?>
                                                    <?php case ('selesai_seminar'): ?>
                                                    <?php case ('selesai'): ?>
                                                        <span class="badge bg-success">Selesai</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-secondary"><?php echo e($seminar->status_label ?? 'Review'); ?></span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                
                                                <a href="<?php echo e(route('kp.seminar.detail', $seminar->id)); ?>" class="btn btn-primary btn-sm mb-1">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>

                                                <?php if($seminar->status_seminar == 'revisi' || $seminar->is_valid == 2): ?>
                                                    <a href="<?php echo e(route('kp.seminar.edit', $seminar->id)); ?>" class="btn btn-success btn-sm mb-1">
                                                        <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Laporan</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jadwal Seminar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/seminar/seminar.blade.php ENDPATH**/ ?>