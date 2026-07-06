<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Profile</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <?php if(Auth::guard('mahasiswa')->user()->email == '-'): ?>
                <div class="alert alert-warning mb-2" style="text-transform: uppercase;">
                    Silahkan update Email anda dengan email aktif untuk mendapatkan notifikasi!
                </div>
            <?php endif; ?>
            <?php if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62'): ?>
                <div class="alert alert-warning mb-2" style="text-transform: uppercase;">
                    Silahkan update Nomor WhatsApp anda dengan awalan kode negara <b>62</b>!
                </div>
            <?php endif; ?>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Kontak Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('profile.update')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($mahasiswa->id); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email (Hapus tanda - )</label>
                                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e($mahasiswa->email); ?>" name="email" required>
                                            <?php $__errorArgs = ['email'];
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
                                            <label for="exampleInputEmail1">No. WhatsApp (Inputan diawali 62, contoh: 6281234567890)</label>
                                            <input type="text" class="form-control <?php if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62'): ?> is-invalid <?php endif; ?>"
                                                value="<?php echo e($mahasiswa->hp); ?>" name="hp" required>
                                            <?php if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62'): ?>
                                                <div class="invalid-feedback">
                                                        Nomor WhatsApp belum diawali dengan <b>62</b>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Alamat</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e($mahasiswa->alamat); ?>" name="alamat" required>
                                            <?php $__errorArgs = ['alamat'];
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
                                <div class="mt-3">
                                    <button class="btn btn-success" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo e($title); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">NIM</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->nim); ?>"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->nama); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tahun Masuk</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->thmasuk); ?>"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Prodi</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->prodi); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tempat Lahir</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->tptlahir); ?>"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tanggal Lahir</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->tgllahir); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Jenis Kelamin</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo e($mahasiswa->jeniskelamin); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Dosen Wali</label>
                                            <?php
                                            $dosen_wali = \App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali) ? \App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali) : null
                                        ?>
                                        <input type="text" class="form-control"
                                            value="<?php echo e($dosen_wali ? $dosen_wali->nama.', '.$dosen_wali->gelar : ''); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">NIK</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->nik); ?>"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Kelas</label>
                                        <input type="text" class="form-control" value="<?php echo e($mahasiswa->kelas); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Semester</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->semester : ''); ?>"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Status</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo e(\App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->status : ''); ?>"
                                            disabled>
                                    </div>
                                </div>
                            </div>
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

<?php echo $__env->make('layouts.dashboardMahasiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/unsiq/domains/fastikom-unsiq.ac.id/public_html/ekapta/ekapta-app-new/resources/views/pages/mahasiswa/profile.blade.php ENDPATH**/ ?>