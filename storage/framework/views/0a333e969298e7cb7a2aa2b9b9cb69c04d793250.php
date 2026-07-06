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
                        <?php if($review_ujian->dosen_status == 'penguji'): ?>
                            <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            <?php if($review_ujian->status == 'review'): ?> bg-secondary
                            <?php elseif($review_ujian->status == 'revisi'): ?>
                            bg-warning
                            <?php elseif($review_ujian->status == 'diterima'): ?>
                            bg-success
                            <?php elseif($review_ujian->status == 'ditolak'): ?>
                            bg-danger <?php endif; ?>
                            ">
                                <?php echo e($review_ujian->status); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td><?php echo e($review_ujian->ujian->mahasiswa->nim); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td><?php echo e($review_ujian->ujian->mahasiswa->nama); ?></td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td><?php echo e($review_ujian->ujian->mahasiswa->prodi); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul TA</b></td>
                                    <td><?php echo e($review_ujian->ujian->pengajuan->judul); ?>

                                    </td>
                                </tr>
                            </table>
                            <hr>

                            <div class="mt-4 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                Tanggal Submit <b><?php echo e(date('d M Y H:m', strtotime($review_ujian->created_at))); ?></b>
                            </div>

                            <?php if($review_ujian->tanggal_acc): ?>
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Acc <b><?php echo e(date('d M Y H:m', strtotime($review_ujian->tanggal_acc))); ?></b>
                                </div>
                            <?php endif; ?>

                            <?php if($review_ujian->ujian->tanggal_ujian): ?>
                                <div class="text-danger"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Ujian <b><?php echo e(date('d M Y H:m', strtotime($review_ujian->ujian->tanggal_ujian))); ?></b>
                                </div>
                                <hr>
                            <?php endif; ?>

                            <?php if($review_ujian->dosen_status == 'penguji'): ?>
                            <b>Keterangan</b> <br>
                            <div class="p-2 rounded" style="background-color: #dbdbdb">
                                <?php echo nl2br($review_ujian->keterangan); ?>

                            </div>
                            <hr>

                            <div class="shadow-lg p-2 rounded">
                                <b>Laporan Tugas Akhir : </b>
                                <a href="<?php echo e(storage_url($review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan)); ?>"
                                    class="ml-3 text-primary" target="_blank"><i
                                        class="fas fa-paperclip mr-2"></i>
                                    <?php echo e(Str::substr($review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan, 40)); ?></a>
                            </div>

                            <?php if($review_ujian->ujian->artikel): ?>
                            <div class="shadow-lg p-2 rounded mt-2">
                                <b>File Artikel : </b>
                                <a href="<?php echo e(storage_url($review_ujian->ujian->artikel)); ?>"
                                    class="ml-3 text-primary" target="_blank"><i
                                        class="fas fa-paperclip mr-2"></i>
                                    <?php echo e(Str::substr($review_ujian->ujian->artikel, 40)); ?></a>
                            </div>
                            <?php endif; ?>

                            <?php if($review_ujian->ujian->link_artikel): ?>
                            <div class="shadow-lg p-2 rounded mt-2">
                                <b>Link Artikel : </b>
                                <a href="<?php echo e($review_ujian->ujian->link_artikel); ?>"
                                    class="ml-3 text-primary" target="_blank"
                                    rel="noopener noreferrer"><i
                                        class="fas fa-link mr-2"></i>
                                    <?php echo e($review_ujian->ujian->link_artikel); ?></a>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>

                        </div>
                        <?php if($review_ujian->dosen_status == 'penguji' && $review_ujian->status == 'review'): ?>
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                            data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi bimbingan
                                    </button>

                                    
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                            data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc bimbingan
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($review_ujian->status == 'diterima' || $review_ujian->dosen_status == 'pembimbing'): ?>
                        <div class="card card-primary card-outline mt-2">
                            <div class="card-header">Input Nilai</div>
                            <div class="card-body">
                                <form action="<?php echo e(route('review.ujian.nilai')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($review_ujian->id); ?>">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Substansi / Isi Materi</label>
                                                <input type="number" name="nilai_1" class="form-control <?php $__errorArgs = ['nilai_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($review_ujian->nilai_1); ?>" required>
                                                <?php $__errorArgs = ['nilai_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Kompetensi Ilmu </label>
                                                <input type="number" name="nilai_2" class="form-control <?php $__errorArgs = ['nilai_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($review_ujian->nilai_2); ?>" required>
                                                <?php $__errorArgs = ['nilai_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Metodologi dan Redaksi TA</label>
                                                <input type="number" name="nilai_3" class="form-control <?php $__errorArgs = ['nilai_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($review_ujian->nilai_3); ?>" required>
                                                <?php $__errorArgs = ['nilai_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Presentasi</label>
                                                <input type="number" name="nilai_4" class="form-control <?php $__errorArgs = ['nilai_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($review_ujian->nilai_4); ?>" required>
                                                <?php $__errorArgs = ['nilai_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>

                                        <?php if($form_status == 1): ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Status</label>
                                                    <select class="form-control" name="is_lulus" required>
                                                        <option value="">-- pilih --</option>
                                                        <option value="1" <?php echo e($review_ujian->ujian->is_lulus == 1 ? 'selected' :''); ?>>Lulus</option>
                                                        <option value="2" <?php echo e($review_ujian->ujian->is_lulus == 2 ? 'selected' :''); ?>>Tidak Lulus</option>
                                                    </select>
                                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Submit Nilai</button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($review_ujian->dosen_status == 'penguji'): ?>
                    
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    <?php echo e(count($review_ujian->revisis)); ?>

                                </span>
                            </h3>
                            <?php if($review_ujian->status == 'revisi'): ?>
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
                                        <span class="mr-5">
                                            Direvisi oleh <b>Anda</b>
                                        </span>
                                        <div class="float-right">
                                            <div class="d-flex">
                                                <span class="mr-3">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    <?php echo e($revisi->created_at->format('d M Y H:m')); ?>

                                                </span>
                                                <div onclick="confirmDelete()">
                                                    <form action="<?php echo e(route('review.ujian.revisi.delete')); ?>"
                                                        method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="id"
                                                            value="<?php echo e($revisi->id); ?>">
                                                        <button class="btn btn-danger btn-sm float-right"
                                                            type="submit">
                                                            <i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
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
                                                <a href="<?php echo e(storage_url($revisi->lampiran)); ?>" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    <?php echo e(Str::substr($revisi->lampiran, 40)); ?></a>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <?php if($review_ujian->dosen_status == 'penguji'): ?>
    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('review.ujian.revisi.store')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($review_ujian->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi Ujian TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Catatan</label>
                            <textarea id="summernote" name="catatan" required></textarea>
                            <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
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

    <!-- Modal Acc -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('review.ujian.acc')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($review_ujian->id); ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Acc Ujian TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" required></textarea>
                            <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger"><?php echo e($message); ?></small>
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

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/dosen/ujian/review.blade.php ENDPATH**/ ?>