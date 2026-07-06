<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="<?php echo e(route('kp.pengajuan.prodi')); ?>" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($pengajuan->status == 'review'): ?> bg-secondary
                            <?php elseif($pengajuan->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($pengajuan->status == 'diterima'): ?>
                            bg-success
                            <?php elseif($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan'): ?>
                            bg-danger <?php endif; ?>
                            ">
                                <?php echo e($pengajuan->status); ?>

                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td><?php echo e($pengajuan->mahasiswa->nim); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td><?php echo e($pengajuan->mahasiswa->nama); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td><?php echo e($pengajuan->prodi->namaprodi); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Kelas</b></td>
                                    <td><?php echo e(\App\Helpers\AppHelper::format_kelas_mahasiswa($pengajuan->mahasiswa->kelas ?? null)); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td>
                                        
                                        <span class="text-<?php echo e(count($pengajuanCekIsPlagiat) <= 1 ? 'success' : 'warning'); ?>"><?php echo e($pengajuan->judul); ?></span>
                                        <a type="button" class="ml-2" data-toggle="modal" data-target="#modal-cek">
                                            <i class="bi bi-check-circle mr-1"></i> Check Plagiarism
                                        </a>
                                    </td>
                                </tr>

                            </table>
                            <hr>
                            <strong>Lokasi KP</strong> <br>
                            <?php echo e($pengajuan->lokasi_kp); ?>

                            <br><br>
                            <strong>Alamat Instansi</strong> <br>
                            <?php echo e($pengajuan->alamat_instansi); ?>

                            <br><br>
                            <?php if($pengajuan->lampiran): ?>
                                <strong>Bukti Diterima Instansi</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->lampiran)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> <?php echo e(basename($pengajuan->lampiran)); ?>

                                </a>
                                <br><br>
                            <?php endif; ?>
                            <?php if($pengajuan->files_pendukung): ?>
                                <strong>File Pendukung</strong> <br>
                                <a href="<?php echo e(storage_url($pengajuan->files_pendukung)); ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> <?php echo e(basename($pengajuan->files_pendukung)); ?>

                                </a>
                                <br><br>
                            <?php endif; ?>
                            <hr>
                            <p><b>Gambaran Singkat</b></p>
                            <?php echo nl2br($pengajuan->deskripsi); ?>

                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                <?php echo e($pengajuan->created_at->format('d M Y H:m')); ?>

                            </div>
                            <?php if($pengajuan->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    <?php echo e(date('d M Y H:m', strtotime($pengajuan->tanggal_acc))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="<?php echo e(route('kp.pengajuan.prodi')); ?>" class="btn btn-secondary mr-2">
                                        <i class="bi bi-arrow-left mr-2"></i> Kembali
                                </a>
                                <?php if($pengajuan->status == 'review'): ?>
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi Pengajuan
                                    </button>

                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="bi bi-check-circle mr-2"></i> Acc Pengajuan
                                    </button>

                                    <button type="button" class="btn btn-danger mr-2" data-toggle="modal"
                                        data-target="#modal-tolak">
                                        <i class="fas fa-x mr-2"></i> Tolak Pengajuan
                                    </button>
                                <?php elseif($pengajuan->status == 'diterima'): ?>
                                    <?php
                                        $dosenPembimbing = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama', 'pendamping'])->first();
                                    ?>
                                    <?php if(!$dosenPembimbing): ?>
                                        <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                            data-target="#modal-edit">
                                            <i class="bi bi-pencil-square mr-2"></i> Ploting Dosen Pembimbing
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                                            data-target="#modal-show">
                                            <i class="bi bi-info-circle mr-2"></i> Dosen Pembimbing
                                        </button>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-secondary mr-2" data-toggle="modal"
                                        data-target="#modal-edit-judul">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit Judul Kerja Praktek
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($pengajuan->revisis)); ?>

                                </span>
                            </h3>
                            <?php if(in_array($pengajuan->status, ['review', 'revisi', 'diterima'])): ?>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-catatan">
                                        <i class="bi bi-plus-square mr-2"></i> Tambah Catatan
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $revisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card bg-light">
                                    <div class="card-header"><i class="fas fa-calendar mr-2"></i>
                                        <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="<?php echo e(route('kp.pengajuan.revisi.delete')); ?>" method="post">
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
                                        <small>
                                            Lampiran :
                                            <?php if($revisi->lampiran): ?>
                                                <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" class="ml-3"
                                                    target="_blank"><i class="fas fa-paperclip"></i>
                                                    <?php echo e(basename($revisi->lampiran)); ?></a>
                                            <?php endif; ?>
                                        </small>
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

    <!-- Modal Cek Is Plagiat -->
    <div class="modal fade" id="modal-cek">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Check Plagiarism</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b> Judul Pengajuan Kerja Praktek :</b> <br>
                    <span
                        class="text-<?php echo e(count($pengajuanCekIsPlagiat) <= 1 ? 'success' : 'warning'); ?>"><?php echo e($pengajuan->judul); ?>

                        <i
                            class="bi bi-<?php echo e(count($pengajuanCekIsPlagiat) <= 1 ? 'check' : 'info'); ?>-circle ml-1"></i></span>
                    <hr>
                    <b> Semua judul pengajuan kerja Praktek yang sudah digunakan :</b> <br>
                    <?php
                        $no = 1;
                    ?>
                    <?php $__currentLoopData = $pengajuanCekIsPlagiat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($result->nim == $pengajuan->nim): ?>
                            <del>
                                <span class="text-secondary">
                                    [<?php echo e($no++); ?>]
                                    [Judul : <?php echo e($result->judul); ?>]
                                    [Prodi : <?php echo e(optional($result->prodi)->namaprodi ?? optional($result->mahasiswa)->prodi ?? 'N/A'); ?> ]
                                    [Status : <?php echo e($result->status); ?> ]</span>
                            </del>
                            <br>
                        <?php else: ?>
                            <span class="text-primary">[<?php echo e($no++); ?>][Judul : <?php echo e($result->judul); ?>]</span>
                            <span class="text-info">[Prodi : <?php echo e(optional($result->prodi)->namaprodi ?? optional($result->mahasiswa)->prodi ?? 'N/A'); ?> ]</span>
                            <span class="text-<?php echo e($result->status == 'review' ? 'secondary' : 'success'); ?>">
                                [Status : <?php echo e($result->status); ?> ]
                            </span>
                            <br>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <?php if(in_array($pengajuan->status, ['review', 'revisi', 'diterima'])): ?>
        <div class="modal fade" id="modal-catatan">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pengajuan.catatan')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($pengajuan->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Catatan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="catatan-tambahan">Catatan</label>
                                <textarea class="form-control" name="catatan" id="catatan-tambahan" rows="4" placeholder="Tambahkan catatan untuk mahasiswa" required></textarea>
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
                            <button type="submit" class="btn btn-success">Simpan Catatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal Revisi -->
    <?php if($pengajuan->status == 'revisi' || $pengajuan->status == 'review'): ?>
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pengajuan.revisi')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($pengajuan->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="catatan">Catatan</label>
                                <textarea class="form-control" name="catatan" id="catatan" rows="4" placeholder="Catatan revisi" required></textarea>
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
    <?php endif; ?>

    <!-- Modal Tolak -->
    <?php if($pengajuan->status == 'review'): ?>
        <div class="modal fade" id="modal-tolak">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pengajuan.tolak')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($pengajuan->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Tolak Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan">
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
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
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

        <div class="modal fade" id="modal-acc">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pengajuan.acc')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($pengajuan->id); ?>">
                        <div class="modal-header">
                            <h4 class="modal-title">Acc Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Setelah ACC, lakukan <strong>Ploting Dosen Pembimbing</strong> untuk menentukan dosen yang akan membimbing mahasiswa.
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" name="catatan" placeholder="Catatan untuk mahasiswa..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success"><i class="fas fa-check mr-2"></i>Setujui Pengajuan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    <?php endif; ?>

    <?php if($pengajuan->status == 'diterima'): ?>
        <?php if(count($mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama', 'pendamping'])->get()) == 0): ?>
            <!-- Modal Ploting Dosen Pembimbing (1 Dosen untuk KP) -->
            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo e(route('kp.ploting.pembimbing')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($pengajuan->id); ?>">
                            <input type="hidden" name="nim" value="<?php echo e($pengajuan->mahasiswa->nim); ?>">
                            <div class="modal-header">
                                <h4 class="modal-title">Ploting Dosen Pembimbing</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pilih 1 dosen pembimbing untuk Kerja Praktek mahasiswa ini.
                                </div>
                                <div class="mb-3">
                                    <a href="<?php echo e(route('kp.bimbingan.rekap.dosen')); ?>" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-users"></i> Lihat Rekap Bimbingan Dosen
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Pembimbing <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select class="select-1" name="dosen_pembimbing" style="width: 100%;" required>
                                            <option value="">Pilih Dosen Pembimbing</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>">
                                                    <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-success"><i class="fas fa-check mr-2"></i>Simpan Ploting</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        <?php endif; ?>

        <?php if(count($mahasiswa->dosens) != 0): ?>
            <!-- Modal Show/Edit Pembimbing -->
            <div class="modal fade" id="modal-show">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo e(route('kp.ploting.pembimbing')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="nim" value="<?php echo e($pengajuan->mahasiswa->nim); ?>">
                            <div class="modal-header">
                                <h4 class="modal-title">Dosen Pembimbing</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <a href="<?php echo e(route('kp.bimbingan.rekap.dosen')); ?>" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-users"></i> Lihat Rekap Bimbingan Dosen
                                    </a>
                                </div>
                                <?php
                                    $currentDosen = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama'])->first();
                                ?>
                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Pembimbing <span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select class="select-1" name="dosen_pembimbing" style="width: 100%;" required>
                                            <option value="">Pilih Dosen Pembimbing</option>
                                            <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dosen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dosen->id); ?>"
                                                    <?php if($currentDosen && $currentDosen->id == $dosen->id): ?> selected <?php endif; ?>>
                                                    <?php echo e($dosen->nama . ', ' . $dosen->gelar); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i>Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        <?php endif; ?>

        <div class="modal fade" id="modal-edit-judul">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?php echo e(route('kp.pengajuan.edit.judul', $pengajuan->id)); ?>" method="post">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Judul Kerja Praktek</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Judul Kerja Praktek</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="judul" class="form-control"
                                        value="<?php echo e($pengajuan->judul); ?>" required>
                                </div>
                                <?php $__errorArgs = ['judul'];
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
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Reinitialize Select2 when modal is shown
    $('#modal-edit, #modal-show').on('shown.bs.modal', function () {
        $(this).find('.select-1, .select-2, .select-3').select2({
            placeholder: "Pilih...",
            allowClear: true,
            width: '100%',
            dropdownParent: $(this) // Important: attach dropdown to modal
        });
    });
    
    // Destroy Select2 when modal is hidden to prevent memory leaks
    $('#modal-edit, #modal-show').on('hidden.bs.modal', function () {
        $(this).find('.select-1, .select-2, .select-3').select2('destroy');
    });
});
</script>
<?php $__env->stopPush(); ?>





<?php echo $__env->make('kp.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/prodi/pengajuan/review.blade.php ENDPATH**/ ?>