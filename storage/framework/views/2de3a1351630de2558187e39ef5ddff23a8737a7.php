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
                        <li class="breadcrumb-item"><a href="#">Semiar TA</a></li>
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
                            <?php if($seminar->is_valid == 0): ?> bg-secondary
                            <?php elseif($seminar->is_valid == 2): ?>
                            bg-warning
                            <?php elseif($seminar->is_valid == 1): ?>
                            bg-success <?php endif; ?>
                            ">
                                <?php if($seminar->is_valid == 0): ?>
                                    review
                                <?php elseif($seminar->is_valid == 1): ?>
                                    diterima
                                <?php elseif($seminar->is_valid == 2): ?>
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
                                    <b><?php echo e($seminar->mahasiswa->nim); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->mahasiswa->nama); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->mahasiswa->prodi); ?></b>
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
                                    <b><?php echo e($seminar->pengajuan->judul); ?></b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($seminar->lampiran_1)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($seminar->lampiran_1, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Seminar Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <a href="<?php echo e(storage_url($seminar->lampiran_2)); ?>" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        <?php echo e(Str::substr($seminar->lampiran_2, 40)); ?></a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    File Laporan Proposal
                                </div>
                                <div class="col-md-7">
                                    <?php if($seminar->lampiran_3): ?>
                                    <a href="<?php echo e(storage_url($seminar->lampiran_3)); ?>" target="_blank"><i
                                        class="fas fa-paperclip"></i>
                                    <?php echo e(Str::substr($seminar->lampiran_3, 40)); ?></a>
                                    <?php else: ?>
                                    <span class="text-danger">Belum Upload File Laporan Proposal</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b><?php echo e($seminar->created_at->format('d M Y H:m')); ?></b>
                                </div>
                            </div>

                            <?php if($seminar->tanggal_acc): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Validasi Pendaftaran
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-success"><?php echo e(date('d M Y H:m', strtotime($seminar->tanggal_acc))); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($seminar->tanggal_ujian): ?>
                                <?php
                                    $tanggal_ujian = \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian);
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
                                            class="text-danger"><?php echo e($seminar->tempat_ujian); ?></b>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php if($seminar->is_valid == 0 && Auth::guard('admin')->user()): ?>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi seminar
                                    </button>

                                    <div onclick="confirmAcc()">
                                        <form action="<?php echo e(route('seminar.acc')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($seminar->id); ?>">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc seminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php elseif($seminar->is_valid == 1): ?>
                            <div class="card-footer">
                                <?php if($seminar->lampiran_3 || count($seminar->reviews) > 2): ?>
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
                                        <a href="<?php echo e(route('cetak.berita.acara.ujian.proposal', $seminar->id)); ?>"
                                            class="btn btn-success" target="_blank">
                                            <i class="bi bi-download"></i> Berita Acara Ujian Proposal
                                        </a>
                                        &nbsp;
                                        <a href="<?php echo e(route('cetak.berita.acara.ujian.proposal.blank', [$seminar->id, 1])); ?>"
                                            class="btn btn-secondary" target="_blank">
                                            <i class="bi bi-download"></i> Berita Acara Ujian Proposal Kosong
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <?php if(count($seminar->reviews) == 2): ?>
                                        <span class="text-danger">MAHASISWA BELUM UPLOAD FILE LAPORAN. SILAHKAN BATALKAN ACC PENDAFTARAN SEMINAR TERLEBIH DAHULU.</span>
                                        <div onclick="return confirmCancel()">
                                            <form action="<?php echo e(route('seminar.cancel.acc')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($seminar->id); ?>"/>
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-x-circle mr-1"></i> Batalkan Acc
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($seminar->revisis)); ?>

                                </span>
                            </h3>
                            <?php if($seminar->is_valid == 2): ?>
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
                                            <form action="<?php echo e(route('seminar.revisi.delete')); ?>" method="post">
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

    <!-- Modal Revisi -->
    <?php if($seminar->is_valid == 0 || $seminar->is_valid == 2): ?>
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('seminar.revisi')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($seminar->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi seminar</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea id="summernote" name="catatan" required>
                        </textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <form action="<?php echo e(route('ploting.penguji')); ?>" method="post">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" value="<?php echo e($seminar->id); ?>" name="seminar_id" />
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
                                    <label for="" class="form-label">Dosen Penguji 3 (Opsional)</label>
                                    <div class="col-md-12">
                                        <select class="select-3" name="dosen_penguji[]" style="width: 100%">
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
                        <form action="<?php echo e(route('seminar.set.date.exam')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" value="<?php echo e($seminar->id); ?>" name="seminar_id" />
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tanggal Ujian</label>
                                <input type="datetime-local" class="form-control" name="tanggal_ujian" value="<?php echo e($seminar->tanggal_ujian); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tempat Ujian</label>
                                <input type="text" class="form-control" name="tempat_ujian" value="<?php echo e($seminar->tempat_ujian); ?>" required>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/admin/seminar/review.blade.php ENDPATH**/ ?>