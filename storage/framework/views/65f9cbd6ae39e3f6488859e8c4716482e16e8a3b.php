

<?php $__env->startSection('content'); ?>
    <!-- Content Header -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 fw-bold"><?php echo e($title); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            
            
            <?php if(Auth::guard('mahasiswa')->user()->email == '-'): ?>
                <div class="alert alert-warning border-0 shadow-sm mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Silahkan update Email utama Anda untuk notifikasi!
                </div>
            <?php endif; ?>
            <?php if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62'): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <i class="fas fa-phone-slash mr-2"></i> Nomor WhatsApp Wajib diawali <b>62</b> (bukan 08).
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Left Column: Profile Card -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($mahasiswa->nama)); ?>&background=random"
                                    alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center mt-3"><?php echo e($mahasiswa->nama); ?></h3>
                            <p class="text-muted text-center"><?php echo e($mahasiswa->nim); ?></p>
                            <p class="text-muted text-center mb-1"><?php echo e($mahasiswa->prodi); ?></p>
                            <hr>
                            <strong><i class="fas fa-book mr-1"></i> Data Akademik</strong>
                            <p class="text-muted small mt-2">
                                Semester: <?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->semester ?? '-'); ?><br>
                                Status: <?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->status ?? '-'); ?><br>
                                Dosen Wali: <?php echo e(\App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali)->nama ?? '-'); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings Form -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white p-3 border-bottom-0">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Edit Kontak</a></li>
                                <li class="nav-item"><a class="nav-link" href="#biodata" data-toggle="tab">Detail Biodata</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Settings Tab -->
                                <div class="active tab-pane" id="settings">
                                    <form class="form-horizontal" action="<?php echo e(route('kp.profile.update')); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($mahasiswa->id); ?>">
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email Utama</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    name="email" value="<?php echo e($mahasiswa->email); ?>" placeholder="Email">
                                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">WhatsApp (62..)</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control <?php if(substr($mahasiswa->hp,0,2) !== '62'): ?> is-invalid <?php endif; ?>" 
                                                    name="hp" value="<?php echo e($mahasiswa->hp); ?>" placeholder="628xxx">
                                                <small class="text-muted">Contoh: 6281234567890</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Alamat</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="alamat" rows="3"><?php echo e($mahasiswa->alamat); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-3 col-sm-9">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Biodata Tab (Read Only) -->
                                <div class="tab-pane" id="biodata">
                                    <dl class="row">
                                        <dt class="col-sm-4">Tempat, Tgl Lahir</dt>
                                        <dd class="col-sm-8"><?php echo e($mahasiswa->tptlahir); ?>, <?php echo e($mahasiswa->tgllahir); ?></dd>

                                        <dt class="col-sm-4">Jenis Kelamin</dt>
                                        <dd class="col-sm-8"><?php echo e($mahasiswa->jeniskelamin); ?></dd>

                                        <dt class="col-sm-4">NIK</dt>
                                        <dd class="col-sm-8"><?php echo e($mahasiswa->nik); ?></dd>

                                        <dt class="col-sm-4">Kelas</dt>
                                        <dd class="col-sm-8"><?php echo e($mahasiswa->kelas); ?></dd>

                                        <dt class="col-sm-4">Tahun Masuk</dt>
                                        <dd class="col-sm-8"><?php echo e($mahasiswa->thmasuk); ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('kp.layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/kp/pages/mahasiswa/profile.blade.php ENDPATH**/ ?>