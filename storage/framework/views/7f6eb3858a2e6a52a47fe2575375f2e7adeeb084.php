<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Ujian TA</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
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
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?> bg-secondary
                            <?php elseif($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                            bg-warning
                            <?php elseif($ujian->is_valid == \App\Models\Ujian::VALID_LULUS): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW): ?>
                                    review
                                <?php elseif($ujian->is_valid == \App\Models\Ujian::DITERIMA): ?>
                                    diterima
                                <?php elseif($ujian->is_valid == \App\Models\Ujian::REVISI): ?>
                                    revisi
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->mahasiswa->prodi); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Utama (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_utama->nama . ', ' . $dosen_utama->gelar); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Pendamping (2) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Ijazah Terakhir Yang Asli
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_3)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_3, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan KTP / Kartu Keluarga Terbaru
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_4)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_4, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat TOEFL
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_5)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_5, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Tahfidz
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->lampiran_6): ?>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_6)); ?>" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_6, 40)); ?></a>
                                    <?php else: ?>
                                        <span class="text-danger">Belum upload sertifikat tahfidz</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Syahadah Tahfidz 30 Juz (Jika Ada)
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->lampiran_syahadah): ?>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_syahadah)); ?>" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_syahadah, 40)); ?></a>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada (opsional)</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Komputer
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_7)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_7, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($ujian->lampiran_8)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($ujian->lampiran_8, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Laporan Skripsi
                                </div>
                                <div class="col-md-7">
                                    <?php if($ujian->lampiran_laporan): ?>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_laporan)); ?>" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_laporan, 40)); ?></a>
                                    <?php else: ?>
                                        <span class="text-danger">Belum Upload Laporan Skripsi</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($ujian->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>

                            <?php if($ujian->tanggal_acc): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Validasi Pendaftaran
                                    </div>
                                    <div class="col-md-7">
                                        <b class="text-success"><?php echo e(date('d M Y H:m', strtotime($ujian->tanggal_acc))); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($ujian->tanggal_ujian): ?>
                                <?php
                                    $tanggal_ujian = \App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian);
                                ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tanggal Ujian
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger"><?php echo e($tanggal_ujian); ?></b>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tempat Ujian
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger"><?php echo e($ujian->tempat_ujian); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW && Auth::guard('admin')->user()): ?>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi ujian
                                    </button>

                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc Pendaftaran ujian
                                    </button>
                                </div>
                            </div>
                        <?php elseif($ujian->is_valid == \App\Models\Ujian::VALID_LULUS): ?>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-ploting-penguji">
                                        <i class="bi bi-pencil-square mr-2"></i>
                                        <?php if(count($dosens_penguji) == 0): ?>
                                            Ploting
                                        <?php endif; ?> Dosen Penguji
                                    </button>
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal"
                                        data-target="#modal-set-date-exam">
                                        <i class="bi bi-calendar mr-2"></i> Tentukan Tanggal dan Tempat Ujian
                                    </button>
                                    <a href="<?php echo e(route('cetak.berita.acara.ujian.pendadaran', $ujian->id)); ?>"
                                        class="btn btn-success mr-2" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran
                                    </a>
                                    <a href="<?php echo e(route('cetak.berita.acara.ujian.proposal.blank', [$ujian->id, 2])); ?>"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran Kosong
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($ujian->revisis)); ?>

                                </span>
                            </h3>
                            <?php if($ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-plus-square mr-2"></i> Tambahkan Revisi
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">

                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="<?php echo e(route('ujian.revisi.delete')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($revisi->id); ?>">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php echo nl2br($revisi->catatan); ?>

                                    </div>

                                    <?php if($revisi->lampiran): ?>
                                        <div class="card-footer">
                                            Lampiran :
                                            <?php if($revisi->lampiran): ?>
                                                <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" class="ml-3"
                                                    target="_blank"><i class="fas fa-paperclip"></i>
                                                    <?php echo e(Str::substr($revisi->lampiran, 40)); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <?php echo e($revisis->links()); ?>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <?php if($ujian->is_valid == \App\Models\Ujian::REVIEW || $ujian->is_valid == \App\Models\Ujian::NOT_VALID_LULUS): ?>
        <!-- Modal Revisi -->
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('ujian.revisi')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($ujian->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi ujian</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea id="summernote" name="catatan" required></textarea>
                            </div>
                            
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

         <!-- Modal Acc -->
        <div class="modal fade" id="modal-acc">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('ujian.acc')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($ujian->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi ujian</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan"></textarea>
                            </div>
                            
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    <?php else: ?>
        <div class="modal fade" id="modal-ploting-penguji">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">
                            <?php if(count($dosens_penguji) == 0): ?>
                                Ploting
                            <?php endif; ?> Dosen Penguji
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if(count($dosens_penguji) != 0): ?>
                            <b>Dosen Penguji</b>
                            <div class="p-2 border rounded">
                                <?php $no = 1; ?>
                                <?php $__currentLoopData = $dosens_penguji; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span>Dosen Penguji<?php echo e($no++); ?>. <b><?php echo e($dosen->dosen->nama); ?>,
                                            <?php echo e($dosen->dosen->gelar); ?></b></span>
                                    <br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(count($reviews_check) == 0): ?>
                            <form action="<?php echo e(route('ploting.penguji.ujian')); ?>" method="post">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" value="<?php echo e($ujian->id); ?>" name="ujian_id" />
                                <div class="form-group mt-2">
                                    <label for="" class="form-label">Dosen Peguji 1</label>
                                    <div class="col-md-12">
                                        <select class="select-1" name="dosen_penguji[]" style="width: 100%;" required>
                                            <option value="">Pilih</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>">
                                                    <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Penguji 2</label>
                                    <div class="col-md-12">
                                        <select class="select-2" name="dosen_penguji[]" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>">
                                                    <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Penguji 3</label>
                                    <div class="col-md-12">
                                        <select class="select-3" name="dosen_penguji[]" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>">
                                                    <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-success">Simpan</button>
                        <?php endif; ?>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-set-date-exam">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Tentukan Tanggal dan Tempat Ujian Peserta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(route('ujian.set.date.exam')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" value="<?php echo e($ujian->id); ?>" name="ujian_id" />
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tanggal Ujian</label>
                                <input type="datetime-local" class="form-control" name="tanggal_ujian" value="<?php echo e($ujian->tanggal_ujian); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tempat Ujian</label>
                                <input type="text" class="form-control" name="tempat_ujian" value="<?php echo e($ujian->tempat_ujian); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/ujian/review.blade.php ENDPATH**/ ?>