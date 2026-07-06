

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit mr-2"></i><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Revisi:</strong> Perbaiki data yang diminta, lalu submit ulang.
                            </div>

                            <form action="<?php echo e(route('kp.seminar.update', $seminar->id)); ?>" method="post" enctype="multipart/form-data">
                                <?php echo method_field('PUT'); ?>
                                <?php echo csrf_field(); ?>

                                
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">1. Informasi Diri</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>NIM</label>
                                                    <input type="text" class="form-control" value="<?php echo e($seminar->mahasiswa->nim); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" value="<?php echo e($seminar->mahasiswa->nama); ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Prodi</label>
                                                    <input type="text" class="form-control" value="<?php echo e($seminar->mahasiswa->prodi); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor WA Aktif <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control <?php $__errorArgs = ['no_wa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        name="no_wa" placeholder="08xxxxxxxxxx" value="<?php echo e(old('no_wa', $seminar->no_wa)); ?>" required>
                                                    <?php $__errorArgs = ['no_wa'];
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
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">2. Informasi Laporan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Judul Kerja Praktek</label>
                                            <input type="text" class="form-control" value="<?php echo e($seminar->pengajuan->judul); ?>" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Laporan Final PDF</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['file_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="file_laporan" accept=".pdf">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php $__errorArgs = ['file_laporan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <?php if($seminar->file_laporan): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->file_laporan)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_laporan)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Lembar Pengesahan PDF</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['file_pengesahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="file_pengesahan" accept=".pdf">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php $__errorArgs = ['file_pengesahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <?php if($seminar->file_pengesahan): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->file_pengesahan)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->file_pengesahan)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">3. Syarat Sertifikat Seminar/Pelatihan (4 Sertifikat)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 1</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_1" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php if($seminar->lampiran_1): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->lampiran_1)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_1)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 2</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_2" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php if($seminar->lampiran_2): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->lampiran_2)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_2)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 3</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_3" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php if($seminar->lampiran_3): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->lampiran_3)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_3)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 4</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_4" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php if($seminar->lampiran_4): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->lampiran_4)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->lampiran_4)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Format: PDF/JPG/PNG, maks 10 MB per file. Kosongkan jika tidak ingin mengubah.</small>
                                    </div>
                                </div>

                                
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">4. Link Akses Produk KP</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Link Akses Produk <span class="text-danger">*</span></label>
                                            <input type="url" class="form-control <?php $__errorArgs = ['link_akses_produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="link_akses_produk" placeholder="https://..."
                                                value="<?php echo e(old('link_akses_produk', $seminar->link_akses_produk)); ?>" required>
                                            <small class="text-muted">Masukkan link untuk mengakses produk KP (Google Drive, GitHub, dll)</small>
                                            <?php $__errorArgs = ['link_akses_produk'];
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
                                        <div class="form-group">
                                            <label>Dokumen Penilaian <small class="text-muted">(Opsional)</small></label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input <?php $__errorArgs = ['dokumen_penilaian'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        name="dokumen_penilaian" accept=".pdf,.jpg,.jpeg,.png">
                                                    <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Dokumen</span>
                                                </div>
                                            </div>
                                            <?php if($seminar->dokumen_penilaian): ?>
                                            <div class="mt-2 bg-light p-2 rounded">
                                                <small>File sebelumnya:
                                                    <a href="<?php echo e(storage_url($seminar->dokumen_penilaian)); ?>" target="_blank">
                                                        <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->dokumen_penilaian)); ?>

                                                    </a>
                                                </small>
                                            </div>
                                            <?php endif; ?>
                                            <small class="text-muted">Upload dokumen penilaian tambahan jika ada</small>
                                            <?php $__errorArgs = ['dokumen_penilaian'];
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
                                </div>

                                
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">5. Pembayaran Seminar</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-light border mb-3">
                                            <i class="fas fa-info-circle text-primary mr-2"></i>
                                            Nominal pembayaran: <strong>Rp <?php echo e(number_format($seminar->jumlah_bayar ?? 25000, 0, ',', '.')); ?></strong>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                    <select name="metode_bayar" class="form-control <?php $__errorArgs = ['metode_bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                                        <option value="">-- Pilih Metode --</option>
                                                        <option value="Cash" <?php echo e(old('metode_bayar', $seminar->metode_bayar) == 'Cash' ? 'selected' : ''); ?>>Cash (Di Sekre Himpunan)</option>
                                                        <?php if($himpunan): ?>
                                                            <?php $__currentLoopData = $himpunan->metodePembayarans()->active()->orderBy('urutan')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($metode->nama); ?>" <?php echo e(old('metode_bayar', $seminar->metode_bayar) == $metode->nama ? 'selected' : ''); ?>>
                                                                <?php echo e($metode->nama); ?> (<?php echo e($metode->nomor); ?>)
                                                            </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                    <?php $__errorArgs = ['metode_bayar'];
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Bukti Pembayaran</label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input <?php $__errorArgs = ['bukti_bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="bukti_bayar" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                    <?php $__errorArgs = ['bukti_bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <?php if($seminar->bukti_bayar): ?>
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="<?php echo e(storage_url($seminar->bukti_bayar)); ?>" target="_blank">
                                                                <i class="fas fa-paperclip"></i> <?php echo e(basename($seminar->bukti_bayar)); ?>

                                                            </a>
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <a href="<?php echo e(route('kp.seminar.mahasiswa')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/seminar/edit.blade.php ENDPATH**/ ?>