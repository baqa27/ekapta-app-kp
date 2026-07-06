

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Seminar KP</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active">Pendaftaran</li>
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
                            <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Form Pendaftaran Seminar KP</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Informasi:</strong> Setelah submit, pendaftaran akan diverifikasi oleh <strong>Himpunan</strong>.
                                Pastikan semua dokumen lengkap dan pembayaran sudah sesuai (Rp 25.000).
                            </div>

                            <form action="<?php echo e(route('kp.seminar.store')); ?>" method="post" enctype="multipart/form-data">
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
                                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->nim); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->nama); ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Prodi</label>
                                                    <input type="text" class="form-control" value="<?php echo e($mahasiswa->prodi); ?>" disabled>
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
                                                        name="no_wa" placeholder="08xxxxxxxxxx" value="<?php echo e(old('no_wa', $mahasiswa->hp)); ?>" required>
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
                                            <input type="text" class="form-control" value="<?php echo e($pengajuan_acc->judul); ?>" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Laporan Final PDF <span class="text-danger">*</span></label>
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
                                                                name="file_laporan" accept=".pdf" required>
                                                            <label class="custom-file-label">Pilih file (maks 10 MB)</label>
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
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Lembar Pengesahan PDF <span class="text-danger">*</span></label>
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
                                                                name="file_pengesahan" accept=".pdf" required>
                                                            <label class="custom-file-label">Pilih file (maks 10 MB)</label>
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
                                                    <label>Sertifikat 1 <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_1" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <label class="custom-file-label">Pilih file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 2 <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_2" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <label class="custom-file-label">Pilih file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 3 <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_3" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <label class="custom-file-label">Pilih file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 4 <span class="text-danger">*</span></label>
                                                    <div class="input-group mb-3">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="lampiran_4" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <label class="custom-file-label">Pilih file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Dokumen</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Format: PDF/JPG/PNG, maks 10 MB per file</small>
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
                                                value="<?php echo e(old('link_akses_produk')); ?>" required>
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
                                                    <label class="custom-file-label">Pilih file (opsional)</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Dokumen</span>
                                                </div>
                                            </div>
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
                                            Nominal pembayaran: <strong>Rp <?php echo e(number_format($himpunan->biaya_seminar ?? 25000, 0, ',', '.')); ?></strong>
                                        </div>

                                        <?php if($himpunan && $himpunan->metodePembayarans()->active()->count() > 0): ?>
                                        <div class="card bg-light mb-3">
                                            <div class="card-body py-2">
                                                <h6 class="mb-2"><i class="fas fa-wallet mr-2"></i>Informasi Rekening Pembayaran:</h6>
                                                <ul class="mb-0 pl-3">
                                                    <li><strong>Cash</strong> - Di Sekre Himpunan</li>
                                                    <?php $__currentLoopData = $himpunan->metodePembayarans()->active()->orderBy('urutan')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <strong><?php echo e($metode->nama); ?></strong>: <?php echo e($metode->nomor); ?> 
                                                        <small class="text-muted">a.n. <?php echo e($metode->nama_pemilik); ?></small>
                                                    </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php endif; ?>

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
                                                        <option value="Cash" <?php echo e(old('metode_bayar') == 'Cash' ? 'selected' : ''); ?>>Cash (Di Sekre Himpunan)</option>
                                                        <?php if($himpunan): ?>
                                                            <?php $__currentLoopData = $himpunan->metodePembayarans()->active()->orderBy('urutan')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($metode->nama); ?>" <?php echo e(old('metode_bayar') == $metode->nama ? 'selected' : ''); ?>>
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
                                                    <label>Upload Bukti Pembayaran <span class="text-danger">*</span></label>
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
                                                                name="bukti_bayar" accept=".pdf,.jpg,.jpeg,.png" required>
                                                            <label class="custom-file-label">Pilih file (maks 10 MB)</label>
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
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Pendaftaran
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





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/seminar/create.blade.php ENDPATH**/ ?>