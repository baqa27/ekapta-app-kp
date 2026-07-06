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
                    <li class="breadcrumb-item"><a href="#">Pendaftaran Ujian Pendadaran TA</a></li>
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
                    <div class="card-header">
                        <h3 class="card-title"><?php echo e($title); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('ujian.update', $ujian->id)); ?>" method="post"
                            enctype="multipart/form-data">
                            <?php echo method_field('PUT'); ?>
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">NIM</label>
                                <input type="text" class="form-control" value="<?php echo e($ujian->mahasiswa->nim); ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama Lengkap</label>
                                <input type="text" class="form-control" value="<?php echo e($ujian->mahasiswa->nama); ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Prodi</label>
                                <input type="text" class="form-control" value="<?php echo e($ujian->mahasiswa->prodi); ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                <input type="text" class="form-control" value="<?php echo e($ujian->pengajuan->judul); ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Bukti Lunas Pembayaran SPP Sampai Semester
                                    Terakhir</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input <?php $__errorArgs = ['lampiran_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="lampiran_1">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_1'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_1)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_1, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Bukti Lunas Pembayaran Tugas Akhir (TA)</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input <?php $__errorArgs = ['lampiran_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="lampiran_2">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_2'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_2)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_2, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Ijazah Terakhir Yang Asli</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_3"
                                                <?php $__errorArgs = ['lampiran_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_3'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_3)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_3, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan KTP / Kartu Keluarga Terbaru</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_4"
                                                <?php $__errorArgs = ['lampiran_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_4'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_4)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_4, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat TOEFL</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_5"
                                                <?php $__errorArgs = ['lampiran_5'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_5'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_5)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_5, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat Tahfidz</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_6"
                                                <?php $__errorArgs = ['lampiran_6'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php if(!$ujian->lampiran_6): ?> required <?php endif; ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_6'];
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
                                <?php if($ujian->lampiran_6): ?>
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(storage_url($ujian->lampiran_6)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($ujian->lampiran_6, 21)); ?></a>
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <small class="text-muted">Belum ada sertifikat tahfidz yang diunggah.</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Syahadah Tahfidz 30 Juz (Jika Ada) <br>
                                    <small class="text-muted">Opsional</small></label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_syahadah"
                                                <?php $__errorArgs = ['lampiran_syahadah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_syahadah'];
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
                                <?php if($ujian->lampiran_syahadah): ?>
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="<?php echo e(storage_url($ujian->lampiran_syahadah)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($ujian->lampiran_syahadah, 21)); ?></a>
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <small class="text-muted">Belum ada syahadah yang diunggah.</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Scan Sertifikat Komputer</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_7"
                                                <?php $__errorArgs = ['lampiran_7'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_7'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_7)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_7, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_8"
                                                <?php $__errorArgs = ['lampiran_8'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_8'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_8)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_8, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Laporan Tugas Akhir (Format: .pdf, max 5Mb )</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="lampiran_laporan"
                                                <?php $__errorArgs = ['lampiran_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> accept=".pdf">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['lampiran_laporan'];
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
                                <div class="rounded bg-light">
                                    <small>
                                        <span class="ml-3">Lampiran sebelumnya : </span>
                                        <a href="<?php echo e(storage_url($ujian->lampiran_laporan)); ?>" class="text-primary"
                                            target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                            <?php echo e(Str::substr($ujian->lampiran_laporan, 21)); ?></a>
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">File Artikel (Format: .pdf/.doc/.docx, max 5Mb)</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="artikel"
                                                <?php $__errorArgs = ['artikel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> accept=".pdf,.doc,.docx"
                                                <?php if(!$ujian->artikel): ?> required <?php endif; ?>>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['artikel'];
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
                                <?php if($ujian->artikel): ?>
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Artikel sebelumnya : </span>
                                            <a href="<?php echo e(storage_url($ujian->artikel)); ?>" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                <?php echo e(Str::substr($ujian->artikel, 21)); ?></a>
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <small class="text-danger">File artikel wajib diunggah.</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Link Artikel <br>
                                    <small class="text-muted">Opsional</small></label>
                                <input type="url" class="form-control <?php $__errorArgs = ['link_artikel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="link_artikel" value="<?php echo e(old('link_artikel', $ujian->link_artikel)); ?>"
                                        placeholder="https://...">
                                <?php $__errorArgs = ['link_artikel'];
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

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/ujian/edit.blade.php ENDPATH**/ ?>